<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tranevents".
 *
 * @property string $traneventid
 * @property string $tranid
 * @property string|null $eventtypeid
 * @property string|null $startdate
 * @property string|null $enddate
 * @property int|null $ord
 */
class Tranevent extends \yii\db\ActiveRecord
{

    // public $Traneventcrew = [];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tranevents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['eventtypeid', 'startdate', 'enddate'], 'default', 'value' => null],
            [['ord'], 'default', 'value' => 1],
            [['startdate', 'enddate'], 'required'],
            [['traneventid', 'tranid', 'eventtypeid'], 'string'],
            [['startdate', 'enddate', 'status', 'createdat', 'updatedat'], 'safe'],
            [['ord'], 'default', 'value' => null],
            [['ord'], 'safe'],
            // [['traneventid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'traneventid' => 'Traneventid',
            'tranid' => 'Tranid',
            'eventtypeid' => 'Eventtypeid',
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
            'ord' => 'Ord',
        ];
    }

    public function beforeSave($insert)
    {
         $this->startdate = Yii::$app->function->datePostgresTZTime($this->startdate);
         $this->enddate = Yii::$app->function->datePostgresTZTime($this->enddate);
        //  var_dump($this);exit;
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                // $this->traneventid = \Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();
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

   public function getTraneventcrews()
    {
        return $this->hasMany(Traneventcrew::class, ['traneventid' => 'traneventid'])->orderBy(['ord' => SORT_ASC]);
    }

    public function getTran()
    {
        return $this->hasOne(Tran::class, ['tranid' => 'tranid']);
    }
}
