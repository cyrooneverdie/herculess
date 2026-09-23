<?php
namespace common\models;

use yii\db\ActiveRecord;
use Yii;

class Subscription extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subscriptions'; // Nama tabel
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subs_id'], 'required'],
            [['package_name', 'tipe'],'string'],
            [['total'],'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'subs_id' => 'ID',
            'contract' => 'Kontrak',
            'total' => 'Total',
        ];
    }

    // /**
    //  * Relasi ke model Cash (Many-to-One)
    //  */
    // public function getCash()
    // {
    //     return $this->hasOne(Cash::class, ['cashid' => 'cashid']);
    // }

    /**
     * Sebelum menyimpan, generate subscriptionid dan set nilai default
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                // Generate UUID untuk subscriptionid
                if (empty($this->subscriptionid)) {
                    $this->subs_id = \Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
                }

                // Set default ord jika belum ada
                // if (empty($this->status)) {
                //     $this->status = 1;
                // }
            }

            return true;
        }
        return false;
    }
}
