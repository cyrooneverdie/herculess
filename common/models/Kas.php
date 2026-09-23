<?php

namespace common\models;

use yii\db\ActiveRecord;
use Yii;

class Kas extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kas'; // Table name as per database
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kasdate'], 'required', 'message' => 'Input Ini Harus Di Isi!'],
            [['kasid', 'akunid', 'kasnomor', 'kastext', 'catatan', 'refid', 'companyid', 'nomorrekening', 'opadd', 'pcadd', 'opedit', 'pcedit'], 'string'],
            [['jenis', 'sumber', 'status'], 'integer'],
            [['kasdate', 'transferdate', 'tgladd', 'tgledit'], 'safe'],
            [['sumber', 'totalpaid'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'kasid' => 'ID Kas',
            'kasdate' => 'Tanggal Kas',
            'akunid' => Yii::$app->lang->t('extra','extra21'),
            'jenis' => 'Jenis',
            'sumber' => 'Sumber',
            'catatan' => 'Catatan',
            'kasnomor' => 'Nomor Kas',
            'kastext' => 'Teks Kas',
            'opadd' => 'Operator Tambah',
            'tgladd' => 'Tanggal Tambah',
            'pcadd' => 'PC Tambah',
            'opedit' => 'Operator Edit',
            'tgledit' => 'Tanggal Edit',
            'pcedit' => 'PC Edit',
            'status' => 'Status',
            'transferdate' => 'Tanggal Transfer',
            'nomorrekening' => 'Nomor Rekening',
            'companyid' => 'ID Perusahaan',
            'refid' => 'ID Referensi',
            'totalpaid' => 'Total Pembayaran'
        ];
    }

    /**
     * Relation to KasDetail model (One-to-Many)
     */
    public function getDetails()
    {
        return $this->hasMany(KasDetail::class, ['kasid' => 'kasid']);
    }

    /**
     * Relation to Transaksi model for reference
     */
    public function getInvoice()
    {
        return $this->hasOne(Tran::class, ['tranid' => 'refid']);
    }

    public function getAkun()
    {
        return $this->hasOne(Coas::class, ['coa_id' => 'akunid']);
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

        // If already in YYYY-MM-DD format
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return $date;
        }

        // If in DD-MM-YYYY format
        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $date)) {
            $parts = explode('-', $date);
            return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }

        // Other formats (using strtotime if valid date format)
        $timestamp = strtotime($date);
        if ($timestamp !== false) {
            return date('Y-m-d', $timestamp);
        }

        // If format not recognized, return as is
        return $date;
    }

    /**
     * Set auto-generate UUID for kasid and related information
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Format dates regardless of whether it's a new record or not
            $this->kasdate = $this->formatDate($this->kasdate);
            if (!empty($this->transferdate)) {
                $this->transferdate = $this->formatDate($this->transferdate);
            }

            if ($this->isNewRecord) {
                // Generate UUID for kasid if not already set
                if (empty($this->kasid)) {
                    $this->kasid = \Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
                }

                // Set status if not already set
                if ($this->status === null) {
                    $this->status = 0;
                }

                // Set kas number if not already set
                if (empty($this->kasnomor)) {
                    $this->kasnomor = $this->nextNoKas();
                }

                // Set company ID from session
                $session = Yii::$app->session;
                $companyid = $session->get('companyid');

                if (!$companyid) {
                    // If companyid not found in session, get from users
                    $userId = Yii::$app->user->id;
                    $companyid = Yii::$app->db->createCommand("SELECT companyid FROM users WHERE userid = :userid")
                        ->bindValue(':userid', $userId)
                        ->queryScalar();
                }

                if ($companyid) {
                    $this->companyid = $companyid;
                }

                // Set creation timestamp and user info
                $this->tgladd = new \yii\db\Expression('NOW()');
                $this->opadd = Yii::$app->user->id;
                $this->pcadd = Yii::$app->request->userIP;
            } else {
                // Update timestamp and user info
                $this->tgledit = new \yii\db\Expression('NOW()');
                $this->opedit = Yii::$app->user->id;
                $this->pcedit = Yii::$app->request->userIP;
            }

            return true;
        }
        return false;
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
                MAX(CAST(REGEXP_REPLACE(kasnomor, '[^0-9]', '', 'g') AS INTEGER)),
                0
            ) + 1 AS no
            FROM kas
            WHERE opadd = :userid
            AND kasnomor LIKE :kastype_prefix";

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
            $total += (float)$detail->nominal;
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
}
