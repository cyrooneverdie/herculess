<?php

namespace common\models;

use yii\data\SqlDataProvider;
use Yii;

/**
 * This is the model class for table "enum".
 *
 * @property string $enumid
 * @property string $enumtype
 * @property string $enumno
 * @property string $enumtext_en
 * @property string $enumtext_id
 * @property string $refid
 */
class Enum extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'enum';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = [
            [['enumtext_id'], 'required'],
            [['enumid', 'enumtype', 'enumno', 'enumtext_en', 'enumtext_id', 'enum_code_id', 'enum_code_en', 'enum_desc'], 'string'],
            [['status', 'iscut'], 'integer'],
            [['amount', 'amount2'], 'safe'],
            [['refid', 'refid2', 'refid3'], 'string'],

            [['createdby', 'createdip', 'createdat', 'updatedby', 'refid', 'iscut', 'updatedip', 'updatedat'], 'safe'],
        ];
        
        if (in_array($this->enumtype, ['category', 'brand', 'type', 'spec', 'warehouse', 'location', 'switcher', 'job','subcategory', 'meal'])) {
        $rules[] = [['enum_code_id'], 'required'];
        }

        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'enumid' => 'Enumid',
            'enumtype' => 'Tipe',
            'enumno' => 'Kode',
            'enumtext_en' => 'Data',
            'enumtext_id' => 'Data',
            'refid' => 'Refid',
            'iscut' => 'Iscut',
            'refid2' => 'Refid2',
            'refid3' => 'Refid3',
            'enum_code_id' => 'Kode',
            'enum_code_en' => 'Code',
            'enum_desc' => 'Description',
            'amount' => 'Amount',
            'amount2' => 'Amount',
        ];
    }

    
    public function beforeSave($insert)
{
    if (parent::beforeSave($insert)) {

        // Jika enumtext_en kosong, isi sama dengan enumtext_id
        if (!empty($this->enumtext_id)) {
            $this->enumtext_en = $this->enumtext_id;
        }

        if (!empty($this->enum_code_id)) {
            $this->enum_code_en = $this->enum_code_id;
        }

        // Buat UUID jika record baru
        if ($this->isNewRecord) {
            $this->enumid = \Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();
        }

        return true;
    }
    return false;
}


    public function getEnum()
    {
        return $this->hasOne(Enum::class, ['enumid' => 'refid']);
    }
    public function getSellcoa()
    {
        return $this->hasOne(Coas::class, ['coa_id' => 'refid2']);
    }
    public function getBuycoa()
    {
        return $this->hasOne(Coas::class, ['coa_id' => 'refid3']);
    }
}
