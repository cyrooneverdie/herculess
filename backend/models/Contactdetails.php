<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contactdetails".
 *
 * @property string $contactdetailid
 * @property string $contactid
 * @property string|null $contact_name
 * @property string|null $contact_email1
 * @property string|null $jobposition
 * @property string|null $npwpfile
 * @property int|null $ord
 * @property int|null $status
 *
 * @property Contacts $contact
 */
class Contactdetails extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contactdetails';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['contact_name', 'contact_email1', 'jobposition', 'npwpfile', 'status'], 'default', 'value' => null],
            [['ord'], 'default', 'value' => 1],
            [['contactdetailid', 'contactid'], 'required'],
            [['contactdetailid', 'contactid', 'contact_name', 'contact_email1', 'jobposition', 'npwpfile'], 'string'],
            [['ord', 'status'], 'default', 'value' => null],
            [['ord', 'status'], 'integer'],
            [['contactdetailid'], 'unique'],
            [['contactid'], 'exist', 'skipOnError' => true, 'targetClass' => Contacts::class, 'targetAttribute' => ['contactid' => 'contact_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'contactdetailid' => 'Contactdetailid',
            'contactid' => 'Contactid',
            'contact_name' => 'Contact Name',
            'contact_email1' => 'Contact Email1',
            'jobposition' => 'Jobposition',
            'npwpfile' => 'Npwpfile',
            'ord' => 'Ord',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Contact]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(Contacts::class, ['contact_id' => 'contactid']);
    }

}
