<?php

namespace common\models;

use yii\db\ActiveRecord;
use Yii;
use yii\db\Query;

class Trandetail extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trandetails'; // Nama tabel
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tranid', 'itemid', 'itemtype', 'itemtaxid', 'unit', 'productid', 'description'], 'string'],
            [['amount', 'amount2', 'itemqty', 'iscut', 'status', 'ord', 'taxtype', 'unit', 'vol', 'refid', 'trandetailid', 'remain', 'remain2', 'stockmilik', 'stockpinjam'], 'safe'], // amount harus berupa integer
            [
                [
                    'price',
                    'itemprice',
                    'itemsubtotal',
                    'itemtax',
                    'itemdisc',
                    'itemtotal',
                    'itemdiscpersen',
                    'itemdisctax',
                    'itemsubtotaltax',
                    'itemtotaltax',
                    'totalafterdisc',
                    'freqvalue',
                    'variantid'
                ],
                'number'
            ],
            [['startdate', 'enddate', 'variantid', 'barcode'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'productid' => Yii::$app->lang->t('produk', 'produk_label'),
            'itemqty' => Yii::$app->lang->t('produk', 'produk_qty'),
            'unit' => Yii::$app->lang->t('produk', 'unit')
        ];
    }


    public function getContact()
    {
        return $this->hasOne(Contact::class, ['contact_id' => 'contact_id'])
            ->via('tran');
    }

    public function getTran()
    {
        return $this->hasOne(Tran::class, ['tranid' => 'tranid']);
    }

    public function getVariant()
    {
        return $this->hasOne(Variant::class, ['variantid' => 'variantid']);
    }

    public function getVariants()
    {
        return $this->hasMany(Variant::class, ['refid' => 'trandetailid'])->orderBy('variantid');
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['productid' => 'productid']);
    }

    public function getRef()
    {
        return $this->hasOne(Trandetail::class, ['trandetailid' => 'refid']);
    }

    public function getTranvariant()
    {
        return $this->hasMany(
            Tranvariants::class,
            ['trandetailid' => 'trandetailid']
        )
            ->andWhere(['status' => 1])
            ->orderBy('tranvariantid');
    }

    public function getVariantsId()
    {

        $query = Yii::$app->db->createCommand(
            "SELECT v.variantid 
            FROM variants v
            WHERE v.locationid <> 'location.1'
            AND v.status <> 10
            AND v.productid = '" . $this->productid . "'
            "
        )->queryColumn();

        return $query;
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->trandetailid = \Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();

                if ($this->status === null) {
                    $this->status = 1;
                }

                if ($this->ord === null) {
                    $this->ord = 1;
                }

                if ($this->iscut === null) {
                    $this->iscut = 0;
                }

                if ($this->itemqty === null) {
                    $this->itemqty = $this->amount;
                }

                if ($this->itemprice === null) {
                    $this->itemprice = $this->price;
                }

                if ($this->itemtotal === null) {
                    $this->itemtotal = $this->totalafterdisc;
                }

                if ($this->taxtype === null) {
                    $this->taxtype = 0;
                }

                if ($this->itemtax === null) {
                    $this->itemtax = 0;
                }

                if ($this->itemdisctax === null) {
                    $this->itemdisctax = 0;
                }

                if ($this->itemsubtotaltax === null) {
                    $this->itemsubtotaltax = 0;
                }

                if ($this->itemtotaltax === null) {
                    $this->itemtotaltax = 0;
                }
            }

            return true;
        }
        return false;
    }
}
