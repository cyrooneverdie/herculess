<?php

namespace common\models;

use Yii;


class Trantracking extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'trantracking';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['trantrackingid', 'tranid', 'time'], 'required'],
            [['trantrackingid', 'tranid', 'status', 'location', 'remarks', 'createdby', 'createdip', 'updatedby', 'updatedip'], 'string'],
            [['time', 'ord', 'createdat', 'updatedat', 'trackstatus'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'time' => 'Time',
            'status' => 'Status',
            'location' => 'Location',
            'remarks' => 'Remarks',
        ];
    }

    public function beforeSave($insert)
    {
        $this->time = Yii::$app->function->datePostgresTZTime($this->time);
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->createdat = new \yii\db\Expression('NOW()');
                $this->createdby = Yii::$app->user->id;
                $this->createdip = Yii::$app->request->userIP;
                $this->trackstatus = 1;

            } else {
                $this->updatedat = new \yii\db\Expression('NOW()');
                $this->updatedby = Yii::$app->user->id;
                $this->updatedip = Yii::$app->request->userIP;
            }
            return true;
        }
        return false;
    }

    public function getTran()
    {
        return $this->hasOne(Tran::class, ['tranid' => 'tranid']);
    }
}
