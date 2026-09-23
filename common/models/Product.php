<?php

namespace common\models;

use Yii;
use yii\db\Query;
use yii\data\ActiveDataProvider;


/**
 * This is the model class for table "product".
 *
 * @property string $productid
 * @property string $description_product
 * @property int $type
 * @property string $categoryid
 * @property string $specid
 * @property string $typeid
 * @property string $brandid
 * @property string $opadd
 * @property string $tgladd
 * @property string $pcadd
 * @property string $opedit
 * @property string $dateedit
 * @property string $pcedit
 * @property string $companyid
 * @property int $status
 * @property string $productcode
 * @property string $productpict
 * @property $fileUpload
 * @property $price
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $fileUpload;

    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['productname'], 'required', 'message' => Yii::$app->lang->t('extra', 'extra40')],
            [
                [
                    'productid',
                    'productname',
                    'coaching_mode',
                    'coaching_limit',
                    'coaching_period',
                    'duration_type',
                    'duration_days',
                    'booking_limit',
                    'booking_period',
                    'exp_date',
                    'visit_limit',
                    'visit_period',
                    'opadd',
                    'pcadd',
                    'opedit',
                    'pcedit',
                    'description_product',
                    'productpict',
                    'custom_duration_unit',
                    'custom_duration_value',
                    // 'visit_expired_status',
                    'visit_mode',
                    'date_mode',
                    'booking_mode'
                ],
                'string'
            ],
            [['type', 'status'], 'integer'],
            [['purchaseprice', 'sellprice', 'visit_expired_status'], 'safe'],
            [
                ['fileUpload'],
                'file',
                'skipOnEmpty' => true,
                'extensions' => 'jpg, jpeg, png, webp',
                'maxSize' => 1024 * 1024 * 1,
                'tooBig' => 'Ukuran gambar terlalu besar. Maksimal adalah 1MB.',
            ],
            [['dateadd', 'dateedit',], 'safe'],
            [['productid'], 'unique', 'message' => 'ID product sudah terdaftar'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'productid' => 'ID',
            'productname' => 'Nama Paket',
            'productcode' => Yii::$app->lang->t('enum', 'code'),
            'productpict' => Yii::$app->lang->t('produk_table', 'produk_gambar'),
            'typeid' => Yii::$app->lang->t('produk_table', 'produk_jenis'),
            'categoryid' => Yii::$app->lang->t('produk_table', 'produk_kategori'),
            'subcategoryid' => Yii::$app->lang->t('subcategory', 'label1'),
            'brandid' => Yii::$app->lang->t('produk_table', 'produk_brand'),
            'specid' => Yii::$app->lang->t('produk', 'produk_display'),
            'description_product' => Yii::$app->lang->t('produk', 'produk_deskripsi'),
            'status' => 'Status',
            'action' => Yii::$app->lang->t('produk_table', 'produk_action'),
            'total' => Yii::$app->lang->t('produk_table', 'produk_total'),
            'amount' => Yii::$app->lang->t('produk_table', 'produk_jumlah'),
            'purchaseprice' => Yii::$app->lang->t('varianharga', 'purchaseprice'),
            'sellprice' => Yii::$app->lang->t('varianharga', 'sellprice'),
            'price' => Yii::$app->lang->t('front_home', 'price'),
            'fileUpload' => Yii::$app->lang->t('produk', 'produk_gambar'),
            'opadd' => 'Dibuat Oleh',
            'dateadd' => 'Tanggal Dibuat',
            'pcadd' => 'PC Dibuat',
            'opedit' => 'Diubah Oleh',
            'dateedit' => 'Tanggal Diubah',
            'pcedit' => 'PC Diubah',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $user = \Yii::$app->user->identity;
            if ($this->isNewRecord) {
                // $this->productid = \Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();
                $this->opadd = $user->id;
                $this->dateadd = new \yii\db\Expression('NOW()');
                $this->pcadd = $_SERVER['REMOTE_ADDR'];
                $this->status = 1;
                $this->companyid = $user->companyid;
                return true;
            } else {
                $this->opedit = $user->id;
                $this->dateedit = new \yii\db\Expression('NOW()');
                $this->pcedit = $_SERVER['REMOTE_ADDR'];
                return true;
            }
        }
        return false;
    }

    public function nextKode($origin = null)
    {

        if ($origin === 'select2') {
            $template = 'PRV';
        } else {
            $template = 'PRD';
        }

        try {
            $sql =
                "SELECT COALESCE(
                MAX(NULLIF(REGEXP_REPLACE(productcode, '[^0-9]', '', 'g'), '')::INTEGER), 0
                    ) + 1 AS no
                    FROM products
                    WHERE status <> 10
                    AND productcode ILIKE '" . $template . "-%'
                ";

            $results = Yii::$app->db->createCommand($sql)->queryOne();
            // echo $sql;exit;

            $nextKode = $results['no'] ?? 1;
            $fullKode = $template . '-' . str_pad($nextKode, 4, '0', STR_PAD_LEFT);
        } catch (Exception $e) {
            Yii::error("Gagal generate product code: " . $e->getMessage(), __METHOD__);
            $fullKode = $template . '-0001';
        }

        return $fullKode;
    }

    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['refid' => 'productid'])->orderBy(['ord' => SORT_ASC]);
    }


}
