<?php 
    namespace common\models;

    use yii\data\SqlDataProvider;
    use Yii;

    /**
     * This is the model class for table "akun".
     *
     * @property string $akunkode
     * @property string $jenid
     * @property string $akunnama
     * @property double $debit
     * @property double $kredit
     *
     * @property Jen $jenid0
     */

    class Coas extends \yii\db\ActiveRecord {
        /**
         * @inheritdoc
         */

        public static function tableName() {
            return 'coas';
        }

        /**
         * @inheritdoc
         */

        public function rules() {
            return [
                [['coa_companyid', 'coa_no', 'coa_name_id', 'coa_level'], 'required'],
                [['coa_type', 'coa_no', 'coa_name_en', 'coa_name_id', 'coa_type'], 'string'],
                [['coa_level', 'coa_status'], 'integer'],
                [['coa_type', 'coa_refid', 'coa_category', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'safe'],
                [['coa_id'], 'unique', 'message' => 'ID Akun sudah terdaftar'],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels() {
            return [
                'coa_id' => 'ID',
                'coa_companyid' => 'Company ID',
                'coa_type' => 'Tipe',
                'coa_no' => 'No.',
                'coa_name_en' => 'Nama (EN)',
                'coa_name_id' => 'Nama (ID)',
                'coa_level' => 'Level',
                'coa_refid' => 'Ref ID',
                'coa_category' => 'Kategori',
                'coa_status' => 'Status',
                'created_by' => 'Dibuat Oleh',
                'created_at' => 'Tanggal Dibuat',
                'updated_by' => 'Diubah Oleh',
                'updated_at' => 'Tanggal Diubah',
            ];
        }        

        /**
         * @return \yii\db\ActiveQuery
         */
        public function search($params) {
            $query = Coas::find();

            if (!empty($params['search'])) {
                $query->andFilterWhere([
                    'or',
                    ['like', 'coa_no', $params['search']],
                    ['like', 'coa_name_en', $params['search']],
                    ['like', 'coa_name_id', $params['search']],
                ]);
            }

            return new ActiveDataProvider([
                'query' => $query,
                'pagination' => false,
                'sort' => ['defaultOrder' => ['coa_no' => SORT_ASC]],
            ]);
        }

        public function nextNo($jenid) {
            try {
                $filter = "";
                $sql = "select cast(max(akunkode::int) as bigint)+1 as kode from akun where jenid='" . $jenid . "'";
                // echo $sql; die;
                $nextKode = "1";
                $results = \Yii::$app->db->createCommand($sql)->queryAll();
                foreach ($results AS $result) {
                    $nextKode = $result['kode'];
                }
    
                if ($nextKode == "") {
                    $nextKode = 1;
                }
    
                while (strlen($nextKode) < 1) {
                    $nextKode = "0" . $nextKode;
                }
            } catch (Exception $e) {
                $nextKode = "00001";
            }
            return $nextKode;
        }

        public function beforeSave($insert) {
            if (parent::beforeSave($insert)) {
                if ($this->isNewRecord) {

                    if (!empty($this->coa_category)) {
                        $this->coa_type = "account";
                    } else {
                        $this->coa_type = "category";
                    }

                    if (empty($this->coa_level)) {
                        $this->coa_level = 1;
                    }

                    $id = Yii::$app->user->id;
                    $companyid = Yii::$app->session->get('companyid');
                    $this->coa_companyid = $companyid;
                    $this->coa_id = \Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();
                    $this->created_by = $id;
                    $this->created_at = new \yii\db\Expression('NOW()');
                    $this->coa_status = 1;
                    return true;
                } else {
                    return true;
                }
                return parent::beforeSave();
            }
        }
    }
?>