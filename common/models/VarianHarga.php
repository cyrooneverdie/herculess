<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "varianharga".
 *
 * @property string $varianid
 * @property double $harga_beli
 * @property double $harga_jual
 * @property double $harga_grosir
 *
 * @property Varian $varian
 */
class VarianHarga extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'varianharga';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['varianid', 'harga_beli', 'harga_jual'], 'required'],
            [['varianid'], 'string'],
            [['harga_beli', 'harga_jual', 'harga_grosir'], 'safe'],
            [['harga_beli', 'harga_jual', 'harga_grosir'], 'default', 'value' => 0],
            [['varianid'], 'unique', 'message' => 'ID Varian sudah memiliki harga'],
            [['varianid'], 'exist', 'skipOnError' => true, 'targetClass' => Varian::class, 'targetAttribute' => ['varianid' => 'varianid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'varianid' => 'ID Varian',
            'harga_beli' => Yii::$app->lang->t('varianharga', 'varianharga_harga_beli') ,
            'harga_jual' => Yii::$app->lang->t('varianharga', 'varianharga_harga_jual') ,
            'harga_grosir' => Yii::$app->lang->t('varianharga', 'varianharga_harga_grosir') ,
        ];
    }

    /**
     * Relasi ke Varian
     */
    public function getVarian()
    {
        return $this->hasOne(Varian::class, ['varianid' => 'varianid']);
    }

    /**
     * Format harga ke format Rupiah
     */
    public function getHargaBeliFormatted()
    {
        return 'Rp ' . number_format($this->harga_beli, 0, ',', '.');
    }

    /**
     * Format harga ke format Rupiah
     */
    public function getHargaJualFormatted()
    {
        return 'Rp ' . number_format($this->harga_jual, 0, ',', '.');
    }

    /**
     * Format harga ke format Rupiah
     */
    public function getHargaGrosirFormatted()
    {
        return 'Rp ' . number_format($this->harga_grosir, 0, ',', '.');
    }

    /**
     * Calculate profit margin (in percentage)
     */
    public function getMarginPercentage()
    {
        if ($this->harga_beli > 0 && $this->harga_jual > 0) {
            $profit = $this->harga_jual - $this->harga_beli;
            return round(($profit / $this->harga_beli) * 100, 2);
        }
        return 0;
    }

    /**
     * Calculate profit amount
     */
    public function getProfitAmount()
    {
        return $this->harga_jual - $this->harga_beli;
    }
}
