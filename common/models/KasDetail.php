<?php

namespace common\models;

use yii\db\ActiveRecord;
use Yii;

class KasDetail extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kasdetail'; // Table name as per database
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kasid'], 'required', 'message' => 'Input Ini Harus Di Isi!'],
            [['kasdetailid', 'kasid', 'akunid', 'statuspaid', 'detail'], 'string'],
            [['value', 'price'], 'number'],
            [['status', 'jumlah'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'kasdetailid' => 'ID Detail Kas',
            'kasid' => 'ID Kas',
            'value' => 'Nilai',
            'status' => 'Status',
            'akunid' => 'ID Akun',
            'statuspaid' => 'Status Pembayaran',
            'detail' => 'Detail',
            'jumlah' => 'Jumlah',
            'price' => 'Harga',
        ];
    }

    /**
     * Relation to Kas model (Many-to-One)
     */
    public function getKas()
    {
        return $this->hasOne(Kas::class, ['kasid' => 'kasid']);
    }

    /**
     * Get Akun relation
     */
    public function getAkun()
    {
        return $this->hasOne(Coas::class, ['coa_id' => 'akunid']);
    }

    /**
     * Calculate total value (price * jumlah)
     * @return float Total value
     */
    public function calculateTotalValue()
    {
        if ($this->price && $this->jumlah) {
            return (float)$this->price * (int)$this->jumlah;
        }
        return (float)$this->value;
    }

    /**
     * Set auto-generate UUID for kasdetailid
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                // Generate UUID for kasdetailid if not already set
                if (empty($this->kasdetailid)) {
                    $this->kasdetailid = \Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
                }

                // Set status if not already set
                if ($this->status === null) {
                    $this->status = 0;
                }

                // Calculate value if price and jumlah are provided
                if ($this->price && $this->jumlah && empty($this->value)) {
                    $this->value = $this->price * $this->jumlah;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * After save, update total in parent Kas record
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Update the totalpaid in parent Kas record
        if ($this->kas) {
            $total = Yii::$app->db->createCommand("
                SELECT SUM(value) FROM kasdetail WHERE kasid = :kasid
            ")->bindValue(':kasid', $this->kasid)->queryScalar();

            $this->kas->updateTotalPaid($total);
        }
    }

    /**
     * After delete, update total in parent Kas record
     */
    public function afterDelete()
    {
        parent::afterDelete();

        // Update the totalpaid in parent Kas record
        $kas = Kas::findOne($this->kasid);
        if ($kas) {
            $total = Yii::$app->db->createCommand("
                SELECT SUM(value) FROM kasdetail WHERE kasid = :kasid
            ")->bindValue(':kasid', $this->kasid)->queryScalar();

            $kas->updateTotalPaid($total ?: 0);
        }
    }
}
