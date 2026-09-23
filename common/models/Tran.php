<?php

namespace common\models;

use yii\db\ActiveRecord;
use Yii;
use Exception;


class Tran extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trans';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['trandate', 'tranno'], 'required'],
            [['tranid', 'contact_id', 'tranno', 'trantype', 'reftype', 'note', 'memo', 'eventname', 'statuspaid', 'voucher', 'companyid', 'locations', 'coordinate', 'linkmap', 'eventtype', 'pic1id', 'pic2id', 'aeid', 'operatorid', 'storemanid', 'piccustomer', 'piccustomer_telp', 'pic1', 'pic2', 'pic3', 'picradio', 'telppic1', 'telppic2', 'telppic3'], 'string'],
            [['status', 'term', 'isdisctax', 'istax', 'priceincludetax'], 'integer'],
            [['trandate', 'tranduedate', 'startdate', 'enddate', 'setupdate', 'withdrawaldate'], 'safe'],
            [['subtotal', 'disc', 'discitem', 'tax', 'totalafterdisc', 'totalpaid', 'persendisc', 'rowitem', 'otherdiscount', 'ppnamount', 'pphamount', 'deliverycharge', 'othercharge', 'grandtotal'], 'number'],
            [['billto', 'billcompany', 'billcountry', 'billstate', 'billcity', 'billdistrict', 'billsubdistrict', 'billzip', 'billaddress', 'billemail', 'billphone', 'billfax'], 'string'],
            [['shipto', 'shipcompany', 'shipcountry', 'shipstate', 'shipcity', 'shipdistrict', 'shipsubdistrict', 'shipzip', 'shipaddress', 'shipemail', 'shipphone', 'shipfax'], 'string'],
            [['currencyid', 'termid', 'emp1id', 'emp2id', 'emp3id', 'warehouseid', 'productid', 'varianid'], 'string'],
            [['createdby', 'createdip', 'updatedby', 'updatedip'], 'string'],
            [['createdat', 'updatedat', 'refid', 'softbookingid'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tranid' => Yii::$app->lang->t('tran', 'tran_id'),
            'contact_id' => Yii::$app->lang->t('extrasidebar', 'extrasidebar5'),
            'trandate' => Yii::$app->lang->t('tran', 'tran_date'),
            'tranduedate' => Yii::$app->lang->t('tran', 'tran_duedate'),
            'setupdate' => Yii::$app->lang->t('tran', 'tran_setup'),
            'withdrawaldate' => Yii::$app->lang->t('tran', 'tran_withdrawal'),
            'eventname' => Yii::$app->lang->t('tran', 'tran_event'),
            'term' => Yii::$app->lang->t('tran', 'tran_term'),
            'coordinate' => Yii::$app->lang->t('tran', 'tran_coordinate'),
            'tranno' => Yii::$app->lang->t('tran', 'tran_no'),
            'note' => Yii::$app->lang->t('tran', 'tran_note'),
            'eventtype' => Yii::$app->lang->t('produk_table', 'produk_jenis'),
            'storemanid' => Yii::$app->lang->t('tran', 'tran_warehouse'),
            'subtotal' => 'Subtotal',
            'disc' => 'Diskon',
            'tax' => 'Pajak',
            'totalpaid' => 'Total',
            'status' => 'Status',
            'refid' => Yii::$app->lang->t('tran', 'refid'),
            'aeid' => 'AE',
            'operatorid' => 'Manager OP',
            'pic1id' => 'PIC 1',
            'pic2id' => 'PIC 2',
            'softbookingid' => 'Soft Booking',
            'linkmap' => 'Link Map',
            'piccustomer' => 'Nama Customer',
            'piccustomer_telp' => 'Telepon Customer',
            'pic1' => 'Nama PIC 1',
            'pic2' => 'Nama PIC 2',
            'pic3' => 'Nama PIC 3',
            'telppic1' => 'Telepon PIC 1',
            'telppic2' => 'Telepon PIC 2',
            'telppic3' => 'Telepon PIC 3',
            'delivery' => Yii::$app->lang->t('extra', 'delivery11')

        ];
    }

    public function getDetails()
    {
        return $this->hasMany(Trandetail::class, ['tranid' => 'tranid']);
    }

    public function getRef()
    {
        return $this->hasOne(Tran::class, ['tranid' => 'refid']);
    }
    public function getContact()
    {
        return $this->hasOne(Contact::class, ['contact_id' => 'contact_id']);
    }

    /**
     * Memformat tanggal dari berbagai format ke format YYYY-MM-DD
     * @param string $date Tanggal yang akan diformat
     * @return string Tanggal dalam format YYYY-MM-DD
     */
    // private function formatDate($date)
    // {
    //     if (empty($date)) {
    //         return null;
    //     }

    //     if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    //         return $date;
    //     }

    //     if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $date)) {
    //         $parts = explode('-', $date);
    //         return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
    //     }

    //     $timestamp = strtotime($date);
    //     if ($timestamp !== false) {
    //         return date('Y-m-d', $timestamp);
    //     }

    //     return $date;
    // }

    /**
     * Mengatur auto-generate UUID untuk tranid dan informasi terkait saat transaksi baru dibuat
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->contact_id = !($this->contact_id == "") ? $this->contact_id : null;
            $this->trandate = Yii::$app->function->datePostgresTZ($this->trandate);
            $this->tranduedate = Yii::$app->function->datePostgresTZ($this->tranduedate);
            
            if ($this->setupdate && !preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $this->setupdate)) {
                $this->setupdate = Yii::$app->function->datePostgresTZTime($this->setupdate);
            }

            if ($this->withdrawaldate && !preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $this->withdrawaldate)) {
                $this->withdrawaldate = Yii::$app->function->datePostgresTZTime($this->withdrawaldate);
            }
            // var_dump($this);

            if ($this->isNewRecord) {
                if (empty($this->tranid)) {
                    $this->tranid = \Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
                }

                if (empty($this->status)) {
                    $this->status = 0;
                }
                if (empty($this->statuspaid)) {
                    $this->statuspaid = 0;
                }
                if (empty($this->statuspro) && $this->trantype !== 'sales/return') {
                    $this->statuspro = 0;

                } else {
                    $this->statuspro = 10; // Set statuspro ke 10 untuk sales/return
                }

                if (empty($this->tranno)) {
                    $typeMap = [
                        'purchase/order' => 'PO',
                        'purchase/request' => 'PR',
                        'purchase/return' => 'PRT',
                        'sales/order' => 'ORD',
                        'sales/invoice' => 'INV',
                        'sales/return' => 'RO',
                        'purchase/delivery' => 'I',
                        'sales/delivery' => 'D',
                    ];

                    $prefix = $typeMap[$this->trantype] ?? null;
                    $this->tranno = $this->nextNoTransaksi($prefix);
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

                $this->createdat = new \yii\db\Expression('NOW()');
                $this->createdby = Yii::$app->user->id;
                $this->createdip = Yii::$app->request->userIP;
            } else {
                $this->updatedat = new \yii\db\Expression('NOW()');
                $this->updatedby = Yii::$app->user->id;
                $this->updatedip = Yii::$app->request->userIP;
            }

            return true;
        }
        return false;
    }
    public static function nextNoTransaksi($customType = null)
    {
        $type = $customType ?? Yii::$app->request->get('penomoran');
        // var_dump($type);die;

        try {
            $template = Yii::$app->db->createCommand(
                "SELECT template FROM numbertemplates
                WHERE type = '$type'
                LIMIT 1"
            )->queryScalar();

            if (!$template) {
                $template = $type;
            }

            // var_dump($template);die;
            $bulanRomawi = Tran::convertToRoman(date('n'));
            $tahun = date('Y');

            $regexPattern = '^[0-9]+/' . $template . '/' . $bulanRomawi . '/' . $tahun . '$';

            $sql = "
                SELECT COALESCE(
                    MAX(CAST(
                        regexp_replace(tranno, '^([0-9]+)/' || '$template' || '/" . $bulanRomawi . "/" . $tahun . "$', '\\1') AS INTEGER
                    )), 0) + 1 AS next_no
                FROM trans
                WHERE tranno ~ '$regexPattern'
            ";

            $results = Yii::$app->db->createCommand($sql)->queryOne();
            $nextKode = $results['next_no'] ?? 1;

            $fullKode = sprintf(
                "%s/%s/%s/%s",
                str_pad($nextKode, 5, "0", STR_PAD_LEFT),
                $template,
                $bulanRomawi,
                $tahun
            );

            return $fullKode;
        } catch (Exception $e) {
            Yii::error("Error generating transaction number: " . $e->getMessage());
            return 'Error generating transaction number';
        }
    }

    public static function convertToRoman($number)
    {
        $map = [
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        ];
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if ($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    public function getTrandetails()
    {
        return $this->hasMany(Trandetail::class, ['tranid' => 'tranid'])->orderBy(['ord' => SORT_ASC]);
    }

    public function getDelivery()
    {
        return $this->hasMany(Trandetail::className(), ['refid' => 'tranid'])->orderBy(['ord' => SORT_ASC]);
    }

    public function getTranevents()
    {
        return $this->hasMany(Tranevent::class, ['tranid' => 'tranid'])->orderBy(['ord' => SORT_ASC]);
    }

    public function getTrantracking()
    {
        return $this->hasMany(Trantracking::class, ['tranid' => 'tranid'])
            ->andWhere(['<>', 'trackstatus', 10])
            ->orderBy(['ord' => SORT_ASC]);
    }

}
