<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "traneventcrews".
 *
 * @property string $traneventcrewid
 * @property string $traneventid
 * @property string|null $crewtypeid
 * @property string|null $startdate
 * @property string|null $enddate
 * @property int|null $ord
 */
class Traneventcrew extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'traneventcrews';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['crewtypeid', 'startdate', 'enddate'], 'default', 'value' => null],
            [['ord'], 'default', 'value' => 1],
            // [['traneventcrewid', 'traneventid'], 'required'],
            [['traneventcrewid', 'traneventid', 'crewtypeid', 'crewid', 'jobid', 'fee', 'dinasid', 'dinasfee'], 'string'],
            [['startdate', 'enddate', 'status', 'createdat', 'updatedat',], 'safe'],
            [['ord'], 'default', 'value' => null],
            [['ord'], 'safe'],
            // [['traneventcrewid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'traneventcrewid' => 'Traneventcrewid',
            'traneventid' => 'Traneventid',
            'crewtypeid' => 'Crewtypeid',
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
            'job' => 'job',
            'ord' => 'Ord',
        ];
    }

    public function beforeSave($insert)
    {

        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->traneventcrewid = \Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();
                // Set status jika belum ada
                if (empty($this->status)) {
                    $this->status = 1;
                }

                return true;
            } else {

                return true;
            }
            return parent::beforeSave();
        }
    }

    public function getTranevent()
    {
        return $this->hasOne(Tranevent::class, ['traneventid' => 'traneventid']);
    }


}
