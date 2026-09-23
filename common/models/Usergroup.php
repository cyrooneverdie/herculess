<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "usermenu".
 *
 * @property integer $usergroupid
 * @property string $positionid
 * @property integer $menuid
 * @property integer $lihat
 * @property integer $tambah
 * @property integer $ubah
 * @property integer $hapus
 * @property integer $cetak
 * @property integer $persetujuan
 */
class Usergroup extends \yii\db\ActiveRecord
{
    /** 
     * Tambahan properti untuk menampung nama menu hasil join
     */
    public $menuname;
    public $position;
    public $userid;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usergroup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['positionid'], 'string'],
            [['menuid', 'lihat', 'tambah', 'ubah', 'hapus', 'cetak', 'persetujuan'], 'safe'],
            [['menuname','position','userid'], 'safe'],
        ];
    }

    public $menu_refid;
       public $menu_icon;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'usergroupid' => 'Usergroupid',
            'positionid' => 'Positionid',
            'menuid' => 'Menuid',
            'lihat' => 'Lihat',
            'tambah' => 'Tambah',
            'ubah' => 'Ubah',
            'hapus' => 'Hapus',
            'cetak' => 'Cetak',
            'persetujuan' => 'Persetujuan',
            // label untuk field baru
            'menuname' => 'Nama Menu',
        ];
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
        
            if ($this->isNewRecord) {
                $this->usergroupid = Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
                // $this->contact_status = 1;
                // $this->createdat = $now;
                // $this->createdip = $userIp;
                // $this->createdby = $userId;
            }

            // Selalu update ini
            // $this->updatedat = $now;
            // $this->updatedip = $userIp;
            // $this->updatedby = $userId;

            return true;
        }
        return false;
    }

}

