<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "units".
 *
 * @property string $unitid
 * @property string|null $unitno
 * @property string|null $unitname
 * @property string|null $opadd
 * @property string|null $pcadd
 * @property string|null $tgladd
 * @property string|null $opedit
 * @property string|null $pcedit
 * @property string|null $tgledit
 * @property int|null $status
 */
class Units extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'units';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['unitid'], 'required'],
            [['unitid', 'unitno', 'unitname', 'opadd', 'pcadd', 'opedit', 'pcedit'], 'string'],
            [['tgladd', 'tgledit'], 'safe'],
            [['status'], 'default', 'value' => null],
            [['status'], 'integer'],
            // [['unitid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        // var_dump(yii::$app->language);exit;
        return [
            'unitid' => strtoupper(Yii::t('app', 'id')),
            'unitno' => ucfirst(Yii::t('app', 'number')),
            'unitname' => ucfirst(Yii::t('app', 'name')),
            'status' => ucfirst(Yii::t('app', 'status')),
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->unitid = \Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();
                //  $this->tokoid = Yii::$app->session->get('tokoid');
                return true;
            } else {
                return true;
            }
            return parent::beforeSave();
        }
    }
}
