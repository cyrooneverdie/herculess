<?php

namespace common\models;
use yii\data\SqlDataProvider;
use Yii;

/**
 * This is the model class for table "enum".
 *

 * @property string $enum_type
 * @property string $enum_no
 * @property string $enum_name

 */
class Enum extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'enums';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'enum_no'], 'required'],
            [['enum_type', 'enum_no', 'enum_name'], 'string'],
          //  [['amount'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
          //  'enumid' => 'Enumid',
            'enum_type' => 'Tipe',
            'enum_no' => 'Kode',
           // 'enumtext_en' => 'Enumtext En',
            'enum_name' => 'Name',
            //'refid' => 'Refid',
        ];
    }

   // public function beforeSave($insert)
   // {


   //     if (parent::beforeSave($insert)) {
           // $this->tgltempo = Yii::$app->function->datePostgres($this->tgltempo);
  //          if ($this->isNewRecord) {
   //             $this->enum_no = \Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();
                
   //             return true;
  //          } else {
              
  //              return true;
   //         }
   //         return parent::beforeSave();
  //      }
 //   }



    public function search($params, $enum_type) {
        $filter = " WHERE 1=1 AND A.enum_type='" . $enum_type . "'";
        if (isset($params['filter']) && $params['filter'] != "") {
            $filter .= " AND "
                . "("
                . "A.enum_no ilike '%" . str_replace("'", "''", $params['filter']) . "%' "
                . "or A.enum_name ilike '%" . str_replace("'", "''", $params['filter']) . "%' "
                // . "or B.produktext_id ilike '%" . str_replace("'", "''", $params['filter']) . "%' "
                . ")";
            
        }
      //  if (isset($params['enumid']) && $params['enumid'] != "") {
      //      $filter .= " AND A.enumid='" . $params['enumid'] . "'";
        //}

      //  if (isset($params['categoryid']) && $params['categoryid'] != "") {
       //     $filter .= " AND B.categoryid =' " . $params['categoryid'] ."'";
          //  $paramsArray[':categoryid'] = $params['categoryid'];
     //   }
    

        // var_dump(isset($params['jenis']) && $params['jenis'] != ""); die;
        if (isset($params['jenis']) && $params['jenis'] != "" && $params['jenis'] != 100) {

            //$filter .= " AND coalesce(A.jeniscustomer,0)='" . $params['jenis'] . "'";
        }

      //  $lang = strtolower(Yii::$app->language);
    //    $id = "categoryid";
        $sql = "
 SELECT A.enum_type, A.enum_name, A.enum_no FROM enum A 
 
$filter
$orderby";
        $sqlcount = "SELECT COUNT(enum_no) FROM ($sql) as temp";


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
            'key' => 'enum_no',
            'pagination' => [
                'pageSize' => $pagedata,
            ],
            'sort' => [
                'defaultOrder' => ['enum_no' => SORT_DESC],
                'attributes' => [
                  //  'enumid',
                    'enum_type',
                  
                    'enum_name',
                  //  'enumtext_id',
                    'enum_no',
                   //'refid'
                ],
            ]
        ]);
        return $dataProvider;
    }
}
