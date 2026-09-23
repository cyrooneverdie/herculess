<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "varianharga".
 *
 * @property string $variantid
 * @property double $price_buy
 * @property double $price_sell
 * @property double $price_wholesaler
 *
 * @property Variant $variant
 */
class VariantPrice extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'variantprices';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['variantid', 'price_buy', 'price_sell'], 'required'],
            [['variantid'], 'string'],
            [['price_buy', 'price_sell', 'price_wholesaler'], 'safe'],
            [['price_buy', 'price_sell', 'price_wholesaler'], 'default', 'value' => 0],
            [['variantid'], 'unique', 'message' => 'ID Variant sudah memiliki harga'],
            [['variantid'], 'exist', 'skipOnError' => true, 'targetClass' => Variant::class, 'targetAttribute' => ['variantid' => 'variantid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'variantid' => 'ID Variant',
            'price_buy' => Yii::$app->lang->t('variantharga', 'variantharga_price_buy') ,
            'price_sell' => Yii::$app->lang->t('variantharga', 'variantharga_price_sell') ,
            'price_wholesaler' => Yii::$app->lang->t('variantharga', 'variantharga_price_wholesaler') ,
        ];
    }

    /**
     * Relasi ke Variant
     */
    public function getVariant()
    {
        return $this->hasOne(Variant::class, ['variantid' => 'variantid']);
    }

    /**
     * Format harga ke format Rupiah
     */
    public function getHargaBeliFormatted()
    {
        return 'Rp ' . number_format($this->price_buy, 0, ',', '.');
    }

    /**
     * Format harga ke format Rupiah
     */
    public function getHargaJualFormatted()
    {
        return 'Rp ' . number_format($this->price_sell, 0, ',', '.');
    }

    /**
     * Format harga ke format Rupiah
     */
    public function getHargaGrosirFormatted()
    {
        return 'Rp ' . number_format($this->price_wholesaler, 0, ',', '.');
    }

    /**
     * Calculate profit margin (in percentage)
     */
    public function getMarginPercentage()
    {
        if ($this->price_buy > 0 && $this->price_sell > 0) {
            $profit = $this->price_sell - $this->price_buy;
            return round(($profit / $this->price_buy) * 100, 2);
        }
        return 0;
    }

    /**
     * Calculate profit amount
     */
    public function getProfitAmount()
    {
        return $this->price_sell - $this->price_buy;
    }
}
