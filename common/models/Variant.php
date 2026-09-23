<?php

namespace common\models;

use Exception;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "varian".
 *
 * @property string $varianid
 * @property string $productid
 * @property string $sku
 * @property string|null $ukuran
 * @property int $stok
 * @property int $status
 * @property string|null $series
 *
 * @property Product $produk
 */
class Variant extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public $fileUpload;

    public static function tableName()
    {
        return 'variants';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['productid'], 'required'],
            [['variantid', 'productid', 'sku', 'description', 'description_variant', 'productimage', 'unitno', 'asetno', 'serialno', 'barcode', 'shelf', 'condition', 'contact_id', 'purchasedate', 'locationid', 'series'], 'string'],
            [['stock', 'status', 'price', 'trandate', 'tranid', 'refid'], 'safe'],
            [['variantid'], 'unique', 'message' => 'Variant ID is already registered'],
            [['productid'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['productid' => 'productid']],
            [
                ['fileUpload'],
                'file',
                'skipOnEmpty' => true,
                'extensions' => 'jpg, jpeg, png, webp',
                'maxSize' => 1024 * 1024 * 1,
                'tooBig' => 'Ukuran gambar terlalu besar. Maksimal adalah 1MB.',
            ],
            // description => utk deskripsi barang hilang
            // description_variant => utk deskripsi varian 
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'variantid' => 'ID Varian',
            'productid' => Yii::$app->lang->t('produk', 'label2'),
            'series' => Yii::$app->lang->t('series', 'label1'),
            'unitno' => Yii::$app->lang->t('produk', 'produk_nounit'),
            'asetno' => Yii::$app->lang->t('produk', 'produk_asset'),
            'serialno' => Yii::$app->lang->t('produk', 'produk_serial'),
            'barcode' => Yii::$app->lang->t('variant_table', 'barcode'),
            'condition' => Yii::$app->lang->t('variant_table', 'kondisi'),
            'locationid' => Yii::$app->lang->t('variant_table', 'lokasi'),
            'shelf' => Yii::$app->lang->t('shelf', 'label1'),
            'description_variant' => Yii::$app->lang->t('produk', 'produk_deskripsi'),
            'sku' => Yii::$app->lang->t('varian', 'varian_sku'),
            'productimage' => Yii::$app->lang->t('varian', 'varian_produkfoto'),
            'contact_id' => Yii::$app->lang->t('tran', 'supplier'),
            'purchasedate' => Yii::$app->lang->t('front_home', 'purchasedate'),
            'colour' => 'Warna',
            'size' => 'Ukuran',
            'stock' => 'Stok',
            'status' => 'Status',
            'fileUpload' => Yii::$app->lang->t('produk', 'produk_gambar'),

        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['productid' => 'productid']);
    }

    public function getContact()
    {
        return $this->hasOne(Contact::class, ['contact_id' => 'contact_id']);
    }

    public function getTrandetail()
    {
        return $this->hasOne(Trandetail::class, ['trandetailid' => 'refid']);
    }

    public function getDocuments()
    {
        return $this->hasMany(Document::class, ['refid' => 'variantid'])
            ->andWhere(['documenttype' => 'variant'])
            ->andWhere(['<>', 'status', 10]) 
            ->orderBy(['ord' => SORT_ASC]);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!empty($this->purchasedate)) {
                $this->purchasedate = Yii::$app->function->datePostgresTZ($this->purchasedate);
            }
            if ($this->trandate && !preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $this->trandate)) {
                $this->trandate = Yii::$app->function->datePostgresTZTime($this->trandate);
            }

            if ($this->isNewRecord) {
                // $this->variantid = Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
                if (empty($this->status)) {
                    $this->status = 1; // Status default jika tidak ada input status
                }
            }
            return true;
        }
        return false;
    }

    public static function nextBarcode($categoryid, $typeid, $brandid, $specid, $productid)
    {
        $categorycode = Yii::$app->function->findByField("enum_code_id", "enum", " and enumid='" . $categoryid . "' ");
        $typecode = Yii::$app->function->findByField("enum_code_id", "enum", " and enumid='" . $typeid . "' ");
        $brandcode = Yii::$app->function->findByField("enum_code_id", "enum", " and enumid='" . $brandid . "' ");
        $speccode = Yii::$app->function->findByField("enum_code_id", "enum", " and enumid='" . $specid . "' ");

        $sql =
            "SELECT COALESCE(
                    MAX(NULLIF(SUBSTRING(barcode FROM '([0-9]+)$'), '')::INTEGER), 0
                ) + 1 AS no
            FROM variants
            WHERE productid = :productid
            AND status <> 10;
            ";

        $next = Yii::$app->db->createCommand($sql, [':productid' => $productid])->queryOne();
        $nextno = $next['no'] ?? 1;

        $barcode = $categorycode . $typecode . $brandcode . $speccode . $nextno;

        return $barcode;
    }

    public function nextNoUnit()
    {

        try {
            $sql =
                "SELECT COALESCE(MAX(NULLIF(REGEXP_REPLACE(unitno, '[^0-9]', '', 'g'), '')::INTEGER), 0) + 1 
            AS unitno FROM variants WHERE status <> 10 AND unitno IS NOT NULL AND unitno <> ''";

            $result = Yii::$app->db->createCommand($sql)->queryOne();
            $nextNoUnit = $result['unitno'] ?? 1;
            $fullKode = '' . str_pad($nextNoUnit, 5, '0', STR_PAD_LEFT);
        } catch (Exception $e) {
            // Optional log
            Yii::error("Gagal generate unit no: " . $e->getMessage(), __METHOD__);
            $fullKode = '00001';
        }

        return $fullKode;
    }
    public function nextNoAset()
    {

        try {
            $sql =
                "SELECT COALESCE(MAX(NULLIF(REGEXP_REPLACE(asetno, '[^0-9]', '', 'g'), '')::INTEGER), 0) + 1 
            AS asetno FROM variants WHERE status <> 10 AND asetno IS NOT NULL AND asetno <> ''";

            $result = Yii::$app->db->createCommand($sql)->queryOne();
            $nextNoAset = $result['asetno'] ?? 1;
            $fullKode = '' . str_pad($nextNoAset, 5, '0', STR_PAD_LEFT);
        } catch (Exception $e) {
            // Optional log
            Yii::error("Gagal generate aset no: " . $e->getMessage(), __METHOD__);
            $fullKode = '00001';
        }

        return $fullKode;
    }
    public function nextNoSerial()
    {
        try {
            $sql =
                "SELECT COALESCE(MAX(NULLIF(REGEXP_REPLACE(serialno, '[^0-9]', '', 'g'), '')::INTEGER), 0) + 1 
            AS serialno FROM variants WHERE status <> 10 AND serialno IS NOT NULL AND serialno <> ''";

            $result = Yii::$app->db->createCommand($sql)->queryOne();
            $nextNoSerial = $result['serialno'] ?? 1;
            $fullKode = '' . str_pad($nextNoSerial, 5, '0', STR_PAD_LEFT);
        } catch (Exception $e) {
            // Optional log
            Yii::error("Gagal generate serial no: " . $e->getMessage(), __METHOD__);
            $fullKode = '00001';
        }

        return $fullKode;
    }


    public function getEnumText()
    {
        $sql =
            "SELECT 
            c.enumtext_id as condition, 
            w.enumtext_id as warehouse,
            w.enumid as warehouseid,
            s.enumtext_id as shelf,
            l.enumtext_id as location,
            p.productname

        FROM variants v
        LEFT JOIN products p ON p.productid::text = v.productid
        LEFT JOIN enum c ON c.enumid = v.condition AND c.enumtype = 'condition'
        LEFT JOIN enum s ON s.enumid = v.shelf AND s.enumtype = 'shelf'
        LEFT JOIN enum w ON w.enumid = s.refid AND w.enumtype = 'warehouse'
        LEFT JOIN enum l ON l.enumid = v.locationid AND l.enumtype = 'location'
        WHERE v.variantid = '" . $this->variantid . "' AND v.status <> 10
        ";
        $data = Yii::$app->db->createCommand($sql)->queryOne() ?: null;

        return $data;
    }

}
