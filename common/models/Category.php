<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "kategori".
 *
 * @property string $categoryid
 * @property string $categorycode
 * @property string $categoryname
 * @property string $opadd
 * @property string $dateadd
 * @property string $pcadd
 * @property string $opedit
 * @property string $dateedit
 * @property string $pcedit
 * @property int $status
 *
 * @property Product[] $produks
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categorys';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['categoryname'], 'required'],
            [['categoryid', 'categorycode', 'categoryname', 'opadd', 'pcadd', 'opedit', 'pcedit'], 'string'],
            [['dateadd', 'dateedit'], 'safe'],
            // [['status'], 'integer'],
            [['categoryid'], 'unique', 'message' => 'ID Kategori sudah terdaftar'],
            [['categorycode'], 'unique', 'message' => 'Kode Kategori sudah terdaftar'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'categoryid' => 'ID',
            'categorycode' => 'Kode Kategori',
            'categoryname' => 'Nama Kategori',
            // 'status' => 'Status',
            'opadd' => 'Dibuat Oleh',
            'dateadd' => 'Tanggal Dibuat',
            'pcadd' => 'PC Dibuat',
            'opedit' => 'Diubah Oleh',
            'dateedit' => 'Tanggal Diubah',
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
        return $this->hasMany(Product::class, ['categoryid' => 'categoryid']);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->categoryid = Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
                $this->opadd = Yii::$app->user->id;
                $this->dateadd = new \yii\db\Expression('NOW()');
                $this->pcadd = $_SERVER['REMOTE_ADDR'];
                $this->userid =  Yii::$app->user->id;
                // $this->status = 1;

                // Generate kode kategori if not provided
                if (empty($this->categorycode)) {
                    $this->categorycode = $this->nextKode();
                }

                return true;
            } else {
                $this->opedit = Yii::$app->user->id;
                $this->dateedit = new \yii\db\Expression('NOW()');
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
            $sql = "SELECT CAST(MAX(NULLIF(regexp_replace(categorycode, '\D','','g'), '')::numeric) AS bigint)+1 AS kode FROM categorys";
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
        $query = Category::find()->where(['status' => 1]);

        // Add filters if needed
        if (!empty($params['search'])) {
            $query->andFilterWhere([
                'or',
                ['ilike', 'categoryname', $params['search']],
                ['ilike', 'categorycode', $params['search']],
            ]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => isset($params['per-page']) && $params['per-page'] != "-" ? $params['per-page'] : 10,
            ],
            'sort' => ['defaultOrder' => ['categoryname' => SORT_ASC]],
        ]);
    }

    /**
     * Get list of categories (for dropdowns)
     */
    public static function getList()
    {
        $data = self::find()
            ->where(['status' => 1])
            ->orderBy(['categoryname' => SORT_ASC])
            ->all();

        $list = [];
        foreach ($data as $item) {
            $list[$item->categoryid] = $item->categoryname;
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
