<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "faqs".
 *
 * @property int $faqid
 * @property string|null $question
 * @property string|null $answer
 * @property string|null $faqtype
 * @property int|null $view_count
 * @property int|null $helpful
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Faq extends ActiveRecord
{
    public static function primaryKey()
    {
        return ['faqid'];
    }

    public static function tableName()
    {
        return 'faqs';
    }

    public static function getTypes()
    {
        return [
            'pengguna' => 'Pengguna',
            'akun' => 'Akun',
            'pembayaran' => 'Pembayaran',
            'sistem' => 'Sistem',
            'transaksi' => 'Transaksi',
            'vendor' => 'Vendor',
            'mitra' => 'Mitra',
            'lainnya' => 'Lainnya',
        ];
    }

    public function rules()
    {
        return [
            [['question', 'answer', 'faqtype'], 'required'],
            [['question', 'answer'], 'string'],
            [['faqtype'], 'in', 'range' => array_keys(self::getTypes())],
            [['view_count', 'helpful'], 'integer', 'min' => 0],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'faqid' => Yii::t('app', 'FAQ ID'),
            'question' => Yii::t('app', 'Question'),
            'answer' => Yii::t('app', 'Answer'),
            'faqtype' => Yii::t('app', 'Category'),
            'view_count' => Yii::t('app', 'View Count'),
            'helpful' => Yii::t('app', 'Helpful Count'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

     public function search($params, $faqtype) {
        $filter = " WHERE 1=1 AND A.faqtype='" . $faqtype . "'";
        if (isset($params['filter']) && $params['filter'] != "") {
            $filter .= " AND "
                . "("
                . "A.enumno ilike '%" . str_replace("'", "''", $params['filter']) . "%' "
                . "or A.enumtext_id ilike '%" . str_replace("'", "''", $params['filter']) . "%' "
                // . "or B.produktext_id ilike '%" . str_replace("'", "''", $params['filter']) . "%' "
                . ")";
        }
        if (isset($params['faqid']) && $params['faqid'] != "") {
            $filter .= " AND A.faqid='" . $params['faqid'] . "'";
        }

      //  if (isset($params['categoryid']) && $params['categoryid'] != "") {
       //     $filter .= " AND B.categoryid =' " . $params['categoryid'] ."'";
          //  $paramsArray[':categoryid'] = $params['categoryid'];
     //   }
    

        // var_dump(isset($params['jenis']) && $params['jenis'] != ""); die;
        if (isset($params['jenis']) && $params['jenis'] != "" && $params['jenis'] != 100) {

            //$filter .= " AND coalesce(A.jeniscustomer,0)='" . $params['jenis'] . "'";
        }

        $lang = strtolower(Yii::$app->language);
    //    $id = "categoryid";
        $sql = "
 SELECT A.faqid, A.faqtype, A.enumtext_$lang, A.enumno, A.amount FROM enum A 
 
$filter
";
        $sqlcount = "SELECT COUNT(faqid) FROM ($sql) as temp";


        $count = Yii::$app->db->createCommand($sqlcount)->queryScalar();

        $pagedata = 10;
        if (isset($params['per-page']) && $params['per-page'] != "") {
            if ($params['per-page'] == "-") {
                $pagedata = 1000;
            } else {
                $pagedata = $params['per-page'];
            }
        }
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            'key' => 'enumid',
            'pagination' => [
                'pageSize' => $pagedata,
            ],
            'sort' => [
                'defaultOrder' => ['enumid' => SORT_DESC],
                'attributes' => [
                    'faqid',
                    'enumtype',
                  
                    'langtext_en',
                    'langtext_id',
                    'enumno',
                   'refid',
                   'iscut',
                   'refid2', 
                   'refid3'
                ],
            ]
        ]);
        return $dataProvider;
    }
    
}