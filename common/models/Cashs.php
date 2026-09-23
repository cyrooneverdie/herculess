<?php

namespace common\models;

use yii\db\ActiveRecord;
use Yii;

class Cashs extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cashs'; // Table name as per database
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cashdate'], 'required', 'message' => 'Input Ini Harus Di Isi!'],
            [['cashid', 'accountid', 'cashnumber', 'cashtext', 'note', 'refid', 'companyid', 'accountnumber', 'opadd', 'pcadd', 'opedit', 'pcedit'], 'string'],
            [['type', 'source', 'status'], 'integer'],
            [['cashdate', 'transferdate', 'dateadd', 'dateedit'], 'safe'],
            [['source', 'totalpaid'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cashid' => 'ID Kas',
            'cashdate' => 'Tanggal Kas',
            'accountid' => Yii::$app->lang->t('extra', 'extra21'),
            'type' => 'type',
            'source' => 'Sumber',
            'note' => 'Catatan',
            'cashnumber' => 'Nomor Kas',
            'cashtext' => 'Teks Kas',
            'opadd' => 'Operator Tambah',
            'dateadd' => 'Tanggal Tambah',
            'pcadd' => 'PC Tambah',
            'opedit' => 'Operator Edit',
            'dateedit' => 'Tanggal Edit',
            'pcedit' => 'PC Edit',
            'status' => 'Status',
            'transferdate' => 'Tanggal Transfer',
            'accountnumber' => 'Nomor Rekening',
            'companyid' => 'ID Perusahaan',
            'refid' => 'ID Referensi',
            'totalpaid' => 'Total Pembayaran'
        ];
    }


    public function getDetails()
    {
        return $this->hasMany(CashDetails::class, ['cashid' => 'cashid']);
    }


    public function getInvoice()
    {
        return $this->hasOne(Tran::class, ['tranid' => 'refid']);
    }

    public function getAkun()
    {
        return $this->hasOne(Coas::class, ['coa_id' => 'accountid']);
    }

    /**
     * Function to generate the next kas number
     */
    public function nextNoKas()
    {
        $userid = Yii::$app->user->id;
        $kastype = Yii::$app->request->get('kastype') == 'KM' ? 'KM' : 'KK'; // KM for income, KK for expense

        try {
            $sql = "SELECT COALESCE(
                MAX(CAST(REGEXP_REPLACE(cashnumber, '[^0-9]', '', 'g') AS INTEGER)),
                0
            ) + 1 AS no
            FROM cashs
            WHERE opadd = :userid
            AND cashnumber LIKE :kastype_prefix";

            $results = Yii::$app->db->createCommand($sql)
                ->bindValue(':userid', $userid)
                ->bindValue(':kastype_prefix', $kastype . '-%') // "KM-%" or "KK-%"
                ->queryOne();

            $nextKode = $results['no'] ?? 1;
            $fullKode = $kastype . "-" . str_pad($nextKode, 5, "0", STR_PAD_LEFT); // Padding 3 digits

        } catch (Exception $e) {
            $fullKode = $kastype . "-00001"; // Default if error
        }

        return $fullKode;
    }

    /**
     * Calculate total from details
     * @return float Total amount from details
     */
    public function calculateTotal()
    {
        $total = 0;
        foreach ($this->details as $detail) {
            $total += (float) $detail->nominal;
        }
        return $total;
    }

    /**
     * Update total paid amount
     * @param float $amount Amount to set
     * @return bool Whether the update was successful
     */
    public function updateTotalPaid($amount)
    {
        $this->totalpaid = $amount;
        return $this->save(false);
    }


    /**
     * Format date from various formats to YYYY-MM-DD
     * @param string $date Date to be formatted
     * @return string Date in YYYY-MM-DD format
     */
    private function formatDate($date)
    {
        if (empty($date)) {
            return null;
        }

        $date = trim($date);

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return $date;
        }

        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $date)) {
            $parts = explode('-', $date);
            return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }

        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
            $parts = explode('/', $date);
            return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }
        $timestamp = strtotime($date);
        if ($timestamp !== false) {
            return date('Y-m-d', $timestamp);
        }

        return $date;
    }

    /**
     * Set auto-generate UUID for cashid and related information
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->cashdate = $this->formatDate($this->cashdate);
            if (!empty($this->transferdate)) {
                $this->transferdate = $this->formatDate($this->transferdate);
            }

            if ($this->isNewRecord) {
                if (empty($this->cashid)) {
                    $this->cashid = \Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
                }

                if ($this->status === null) {
                    $this->status = 0;
                }

                if (empty($this->cashnumber)) {
                    $this->cashnumber = $this->nextNoKas();
                }

                $session = Yii::$app->session;
                $companyid = $session->get('companyid');

                if (!$companyid) {
                    $userId = Yii::$app->user->id;
                    $companyid = Yii::$app->db->createCommand("SELECT companyid FROM users WHERE userid = :userid")
                        ->bindValue(':userid', $userId)
                        ->queryScalar();
                }

                if ($companyid) {
                    $this->companyid = $companyid;
                }

                $this->dateadd = new \yii\db\Expression('NOW()');
                $this->opadd = Yii::$app->user->id;
                $this->pcadd = Yii::$app->request->userIP;
            } else {
                $this->dateedit = new \yii\db\Expression('NOW()');
                $this->opedit = Yii::$app->user->id;
                $this->pcedit = Yii::$app->request->userIP;
            }

            return true;
        }
        return false;
    }


}
