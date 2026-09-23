<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "document".
 *
 * @property string $documentid
 * @property string $documenttype
 * @property string $refid
 * @property string $documentname
 * @property string $documentpath
 * @property double $documensize
 * @property string $createdby
 * @property string $createdip
 * @property string $createdat
 * @property string $updatedby
 * @property string $updatedip
 * @property string $updatedat
 * @property string $status
 */
class Document extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['documentid'], 'required'],
            [['documentid', 'documenttype', 'refid', 'documentname', 'documentext', 'documentpath', 'createdby', 'createdip', 'updatedby', 'updatedip', 'status'], 'string'],
            [['documensize', 'ord'], 'number'],
            [['createdat', 'updatedat'], 'safe'],
            [['documentid'], 'unique'],
            [
                ['documentpath'],
                'file',
                'skipOnEmpty' => true,
                'extensions' => 'jpg, jpeg, png, webp',
                'maxSize' => 1024 * 1024 * 1, // 2MB
                'tooBig' => 'Gambar di repeater terlalu besar, maksimal 1MB.'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'documentid' => 'Documentid',
            'documenttype' => 'Documenttype',
            'refid' => 'Refid',
            'documentname' => 'Documentname',
            'documentext' => 'Ext',
            'documentpath' => 'Documentpath',
            'documensize' => 'Documensize',
            'createdby' => 'Createdby',
            'createdip' => 'Createdip',
            'createdat' => 'Createdat',
            'updatedby' => 'Updatedby',
            'updatedip' => 'Updatedip',
            'updatedat' => 'Updatedat',
            'status' => 'Status',
        ];
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                // $this->documentid = \Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();
                // $this->documentno = $this->nextNo();
                $this->createdat = new \yii\db\Expression('NOW()');
                $this->createdip = $_SERVER['REMOTE_ADDR'];
                $this->createdby = \Yii::$app->user->id;

                $this->status = '1';
                return true;
            } else {
                $this->updatedat = new \yii\db\Expression('NOW()');
                $this->updatedip = $_SERVER['REMOTE_ADDR'];
                $this->updatedby = \Yii::$app->user->id;
                // $this->status = '0';
                return true;
            }
        }
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['productid' => 'refid']);
    }
    public function getVariant()
    {
        return $this->hasOne(Variant::className(), ['variantid' => 'refid']);
    }
    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['contactid' => 'refid']);
    }


}
