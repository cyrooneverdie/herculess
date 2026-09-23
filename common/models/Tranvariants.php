<?php

namespace common\models;

use Yii;

class Tranvariants extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tranvariants';
    }

    public function rules()
    {
        return [
            [['tranvariantid', 'variantid', 'trandetailid'], 'string'],
            [['barcode', 'from_locationid', 'condition'], 'string', 'max' => 255],
            [['type', 'trandate', 'refid', 'productid'], 'safe'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'tranvariantid' => 'Tranvariant ID',
            'trandetailid' => 'Trandetail ID',
            'variantid' => 'Variant ID',
            'barcode' => 'Barcode',
            'from_locationid' => 'From Location ID',
            'type' => 'Type',
            'condition' => 'Condition',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->trandate && !preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $this->trandate)) {
                $this->trandate = Yii::$app->function->datePostgresTZTime($this->trandate);
            }
            if ($this->isNewRecord) {
                // $this->tranvariantid = Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();
            }
            return true;
        }
        return false;
    }

    public function getTrandetail()
    {
        return $this->hasOne(Trandetail::class, ['trandetailid' => 'trandetailid']);
    }

    public function getVariant()
    {
        return $this->hasOne(Variant::class, ['variantid' => 'variantid']);
    }
}