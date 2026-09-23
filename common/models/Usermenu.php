<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "usermenu".
 *
 * @property integer $usermenuid
 * @property string $userid
 * @property integer $menuid
 * @property integer $lihat
 * @property integer $tambah
 * @property integer $ubah
 * @property integer $hapus
 * @property integer $cetak
 * @property integer $persetujuan
 */
class Usermenu extends \yii\db\ActiveRecord
{
    /** 
     * Tambahan properti untuk menampung nama menu hasil join
     */
    public $menuname;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usermenu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid'], 'string'],
            [['menuid', 'lihat', 'tambah', 'ubah', 'hapus', 'cetak', 'persetujuan'], 'integer'],
            // supaya menuname bisa dipakai di form
            [['menuname'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'usermenuid' => 'Usermenuid',
            'userid' => 'Userid',
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
}
