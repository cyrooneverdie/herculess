<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "kategori".
 *
 * @property string $kategoriid
 * @property string $kategorikode
 * @property string $kategorinama
 * @property string $opadd
 * @property string $tgladd
 * @property string $pcadd
 * @property string $opedit
 * @property string $tgledit
 * @property string $pcedit
 * @property int $status
 *
 * @property Produk[] $produks
 */
class Kategori extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kategori';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kategorinama'], 'required'],
            [['kategoriid', 'kategorikode', 'kategorinama', 'opadd', 'pcadd', 'opedit', 'pcedit'], 'string'],
            [['tgladd', 'tgledit'], 'safe'],
            // [['status'], 'integer'],
            [['kategoriid'], 'unique', 'message' => 'ID Kategori sudah terdaftar'],
            [['kategorikode'], 'unique', 'message' => 'Kode Kategori sudah terdaftar'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'kategoriid' => 'ID',
            'kategorikode' => 'Kode Kategori',
            'kategorinama' => 'Nama Kategori',
            // 'status' => 'Status',
            'opadd' => 'Dibuat Oleh',
            'tgladd' => 'Tanggal Dibuat',
            'pcadd' => 'PC Dibuat',
            'opedit' => 'Diubah Oleh',
            'tgledit' => 'Tanggal Diubah',
            'pcedit' => 'PC Diubah'
        ];
    }

    /**
     * Gets query for [[Produks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduks()
    {
        return $this->hasMany(Produk::class, ['kategoriid' => 'kategoriid']);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->kategoriid = Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
                $this->opadd = Yii::$app->user->id;
                $this->tgladd = new \yii\db\Expression('NOW()');
                $this->pcadd = $_SERVER['REMOTE_ADDR'];
                $this->userid =  Yii::$app->user->id;
                // $this->status = 1;

                // Generate kode kategori if not provided
                if (empty($this->kategorikode)) {
                    $this->kategorikode = $this->nextKode();
                }

                return true;
            } else {
                $this->opedit = Yii::$app->user->id;
                $this->tgledit = new \yii\db\Expression('NOW()');
                $this->pcedit = $_SERVER['REMOTE_ADDR'];
                return true;
            }
        }
        return false;
    }

    /**
     * Generate next kode
     * @return string
     */
    public function nextKode()
    {
        try {
            $sql = "SELECT CAST(MAX(NULLIF(regexp_replace(kategorikode, '\D','','g'), '')::numeric) AS bigint)+1 AS kode FROM kategori";
            $nextKode = "1";
            $results = Yii::$app->db->createCommand($sql)->queryAll();
            foreach ($results as $result) {
                $nextKode = $result['kode'];
            }

            if ($nextKode == "") {
                $nextKode = 1;
            }

            // Format with leading zeros to get K-### format
            return "K-" . str_pad($nextKode, 5, "0", STR_PAD_LEFT);
        } catch (\Exception $e) {
            // If there's an error, return a default code
            return "K-00001";
        }
    }

    /**
     * Search method
     */
    public function search($params)
    {
        $query = Kategori::find()->where(['status' => 1]);

        // Add filters if needed
        if (!empty($params['search'])) {
            $query->andFilterWhere([
                'or',
                ['ilike', 'kategorinama', $params['search']],
                ['ilike', 'kategorikode', $params['search']],
            ]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => isset($params['per-page']) && $params['per-page'] != "-" ? $params['per-page'] : 10,
            ],
            'sort' => ['defaultOrder' => ['kategorinama' => SORT_ASC]],
        ]);
    }

    /**
     * Get list of categories (for dropdowns)
     */
    public static function getList()
    {
        $data = self::find()
            ->where(['status' => 1])
            ->orderBy(['kategorinama' => SORT_ASC])
            ->all();

        $list = [];
        foreach ($data as $item) {
            $list[$item->kategoriid] = $item->kategorinama;
        }

        return $list;
    }

    /**
     * Get status list
     */
    public static function getStatusList()
    {
        return [
            1 => 'Aktif',
            0 => 'Tidak Aktif'
        ];
    }
}
