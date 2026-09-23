<?php

namespace common\models;

use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use common\models\GlobalFunction;
use Yii;
use common\models\Enums;
class Kas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [[
            //     // 'kas_no',
            //     'kas_name',
            //     // 'kas_bop',
            //     // 'kas_bod',
            //     'kas_typeid',
            //     'kas_gender',
            //     //  'kas_phone1',
            //     //  'kas_phone2',
            //     //  'kas_married',
            //     // 'kas_education',
            //     //  'kas_religion',
            //     //  'kas_email1',
            //     //  'kas_email2',
            //     // 'kas_status',
            //     // 'kas_iscustomer',
            //     // 'kas_isemployee',
            //     // 'kas_isother',
            //     // 'kas_iscompany',
            //     'userid'
            // ], 'required'],

            [['kasid'], 'required'],
            [[
                'kasid',
                'kasnomor',
                'nomorrekening',
                'catatan',
                'kastext',
                'opadd',
                'opedit',
                'pcadd',
                'pcedit',
                'akunid',
            ], 'string'],

            [['tgladd', 'tgledit', 'transferdate'], 'safe'],
            [['jenis', 'sumber', 'status'], 'integer'],
            [['kasid'], 'unique', 'message' => 'ID Kontak sudah terdaftar'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            // 'kasid' => 'ID',
            'kasnomor' => Yii::$app->lang->t('kasbackend', 'kasno'),
            'catatan' => Yii::$app->lang->t('kasbackend','catatan'),
            'tgladd' => Yii::$app->lang->t('kasbackend','tgladd'),
            'transferdate' => Yii::$app->lang->t('kasbackend','transferdate'),
            'nomorrekening' => Yii::$app->lang->t('kasbackend','nomorrekening'),
        ];
    }

    public function search($params)
    {
        // var_dump($params);
        // die;
        $query = Kas::find()
            ->where(['userid' => Yii::$app->user->id])
            ->with(['gender', 'religion', 'married']);

        // Tambahkan filter jika diperlukan, misalnya:
        if (!empty($params['search'])) {
            $query->andFilterWhere([
                'or',
                ['ilike', 'kas_name', $params['search']],
                ['ilike', 'kas_phone1', $params['search']],
            ]);
        }

        // Terapkan filter spesifik (dropdown)
        if (!empty($params['Kas'])) {
            $query->andFilterWhere([
                'or',
                'kas_gender'   => $this->kas_gender,
                $params['Kas'],
                'kas_married'  => $this->kas_married,
                $params['Kas'],
                'kas_religion' => $this->kas_religion,
                $params['Kas'],
            ]);
        }

        // var_dump($params);
        // die;
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => isset($params['per-page']) && $params['per-page'] != "-" ? $params['per-page'] : 10],
            'sort' => ['defaultOrder' => ['kasid' => SORT_ASC]],

        ]);
    }


    public function beforeSave($insert)
    {

        // $this->motorid = !($this->motorid == "") ? $this->motorid : null;
        //  $this->bod = Yii::$app->function->datePostgresTZ($this->bod);
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->kasid = \Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();
                //    $this->kas_no = $this->nextNo();
                //    $this->createdat = GlobalFunction::standard_date('DATE_RFC822', time());
                //    $this->createdip = $_SERVER['REMOTE_ADDR'];
                //    $this->createdby = \Yii::$app->user->id;

                //    $this->status = '0';
                return true;
            } else {
                // if (!class_exists('common\models\GlobalFunction')) {
                //     die('Class GlobalFunction tidak ditemukan!');
                // }
                // $this->updatedat = GlobalFunction::standard_date('DATE_RFC822', time());
                // $this->updatedip = $_SERVER['REMOTE_ADDR'];
                // $this->updatedby = \Yii::$app->user->id;
                // $this->status = '0';
                return true;
            }
            return parent::beforeSave();
        }
    }


    public function nextNo()
    {
        try {
            $filter = "";
            $sql = "select cast(max(NULLIF(regexp_replace(kasnomor, '\D','','g'), '')::numeric) as bigint)+1 as kode from kas where status <> '10'";
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
            $nextKode = "STR001";
        }
        $nextKode = "KM-" . $nextKode;
        return $nextKode;
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['userid' => 'userid']);
    }
}
