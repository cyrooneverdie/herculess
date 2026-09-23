<?php

namespace common\models;

use yii\db\ActiveRecord;
use Yii;

class CashDetails extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cashdetails'; // Table name as per database
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cashdetailid'], 'required', 'message' => 'This Input Must Be Filled In!'],
            [['cashdetailid', 'cashid', 'accountid', 'statuspaid', 'detail'], 'string'],
            [['value', 'price'], 'number'],
            [['status', 'total'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cashdetailid' => 'ID Detail Kas',
            'cashid' => 'ID Kas',
            'value' => 'Nilai',
            'status' => 'Status',
            'accountid' => 'ID Akun',
            'statuspaid' => 'Status Pembayaran',
            'detail' => 'Detail',
            'amount' => 'Amount',
            'price' => 'Price',
        ];
    }

    /**
     * Relation to Kas model (Many-to-One)
     */
    public function getCashs()
    {
        return $this->hasOne(Cashs::class, ['cashid' => 'cashid']);
    }

    /**
     * Get Akun relation
     */
    public function getAkun()
    {
        return $this->hasOne(Coas::class, ['coa_id' => 'accountid']);
    }

    /**
     * Calculate total value (price * jumlah)
     * @return float Total value
     */
    public function calculateTotalValue()
    {
        if ($this->price && $this->amount) {
            return (float)$this->price * (int)$this->amount;
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
                if (empty($this->cashdetailid)) {
                    $this->cashdetailid = \Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
                }

                // Set status if not already set
                if ($this->status === null) {   
                    $this->status = 0;
                }

                // Calculate value if price and jumlah are provided
                if ($this->price && $this->amount && empty($this->value)) {
                    $this->value = $this->price * $this->amount;
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
        if ($this->cashs) {
            $total = Yii::$app->db->createCommand("
                SELECT SUM(value) FROM cashdetails WHERE cashid = :cashid
            ")->bindValue(':cashid', $this->cashid)->queryScalar();

            $this->cashs->updateTotalPaid($total);
        }
    }

    /**
     * After delete, update total in parent Kas record
     */
    public function afterDelete()
    {
        parent::afterDelete();

        // Update the totalpaid in parent Kas record
        $kas = Cash::findOne($this->cashid);
        if ($kas) {
            $total = Yii::$app->db->createCommand("
                SELECT SUM(value) FROM cashdetails WHERE cashid = :cashid
            ")->bindValue(':cashid', $this->cashid)->queryScalar();

            $kas->updateTotalPaid($total ?: 0);
        }
    }
}
