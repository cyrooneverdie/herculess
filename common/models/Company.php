<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;

class Company extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%company}}';
    }

    /**
     * {@inheritdoc}
     */

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid', 'companyid', 'email', 'nomor_telepon'], 'safe'],
            [['kode_company', 'statuspaid'], 'string'],
            [['status', 'subs_status'], 'integer'],
            [['nama_perusahaan'], 'required', 'message' => 'This input is required'],
            ['email', 'email'],
            ['nomor_telepon', 'match', 'pattern' => '/^[0-9]+$/']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'nama_lengkap' => 'Nama Cabang',
            'nama_perusahaan' => Yii::$app->lang->t('front_home', 'form2'),
            'posisi_perusahaan' => Yii::$app->lang->t('kasform', 'kasform1'),
            'email' => Yii::$app->lang->t('front_home', 'form1'),
            'nomor_telepon' => Yii::$app->lang->t('front_home', 'for26'),
            'jumlah_karyawan' => Yii::$app->lang->t('kasform', 'kasform2'),
        ];
    }
    public function nextNo()
    {
        try {
            $filter = "";
            $sql = "select cast(max(NULLIF(regexp_replace(kode_company, '\D','','g'), '')::numeric) as bigint)+1 as kode from company where";
            $nextKode = "1";
            $results = \Yii::$app->db->createCommand($sql)->queryAll();
            foreach ($results as $result) {
                $nextKode = $result['kode'];
            }

            if ($nextKode == "") {
                $nextKode = 1;
            }

            while (strlen($nextKode) < 5) {
                $nextKode = "0" . $nextKode;
            }
        } catch (Exception $e) {
            $nextKode = "CMP-001";
        }
        $nextKode = "CMP-" . $nextKode;
        return $nextKode;
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['userid' => 'userid']);
    }

    public function getKaryawan()
    {
        $id = $this->companyid;
        $userid = Yii::$app->user->id;
        $listBahasa = "SELECT lang FROM users WHERE userid = '$userid'";

        $bahasa = Yii::$app->db->createCommand($listBahasa)->queryScalar();
        $sql = "SELECT
                b.enumid,
                CASE
                    WHEN '$bahasa' = 'id'THEN
                    COALESCE(enumtext_id)
                    ELSE enumtext_en
                END as text,
                b.enumtext_id
                FROM enum b
                LEFT JOIN company a ON a.jumlah_karyawan = b.enumno
                WHERE b.enumtype = 'jumlahkaryawan'
                AND a.companyid = '$id'"; // Tidak pakai bind

        return Yii::$app->db->createCommand($sql)->queryOne();
        // return $this->hasOne(Enum::class, ['enum_no' => 'contact_education'])
        //             ->where(['enum_type' => 'contact_education']);
    }
}
