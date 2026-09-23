<?php

namespace backend\controllers;

use Yii;
use common\models\Model;
use common\models\Enum;
use common\models\Receipt;
use common\models\Varian;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use kartik\mpdf\Pdf;
use JavaClass;
use Java;
use yii\web\UploadedFile;

use common\models\Document;

/**
 * EnumController implements the CRUD actions for Enum model.z
 */
class EnumController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'cancel' => ['POST'],
                    'upload' => ['POST', 'GET', 'PUT'],
                    'create' => ['POST', 'GET', 'PUT']
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['cancel', 'upload', 'changestatus', 'changestatusall', 'hapusfoto'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->issuperadmin());
                        }
                    ],
                    [
                        'actions' => ['index', 'enumdetail', 'adjust', 'create', 'getno', 'update', 'view', 'delete', 'print', 'hapusfoto', 'printall'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isadmin());
                        }
                    ],
                    [
                        'actions' => ['print'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isadminvw());
                        }
                    ],
                    [
                        'actions' => ['index', 'enumdetail', 'view', 'tempo', 'tebus', 'jual'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("Enum", "lihat"));
                        }
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("Enum", "tambah"));
                        }
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("Enum", "ubah"));
                        }
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("Enum", "hapus"));
                        }
                    ],
                    [
                        'actions' => ['print', 'printall'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("Enum", "cetak"));
                        }
                    ],
                    [
                        'actions' => ['changestatus', 'changestatusall'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("Enum", "persetujuan"));
                        }
                    ],
                    [
                        'actions' => ['list', 'index', 'genderlist', 'unitlist', 'locationlist', 'kategorilist', 'statuslist', 'getvoucher'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($enumtype)
    {

        if (!Yii::$app->function->findByField("enumid", "enum", " and enumid='" . $enumtype . "' ")) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $searchModel = new Enum();
        //    $dataProvider = $searchModel->search(Yii::$app->request->queryParams); //enumtype
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $enumtype); ////produk

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'enumtype' => $enumtype,
        ]);
    }


    public function actionEnumdetail()
    {
        $parentid = $_POST['expandRowKey'];
        return Yii::$app->controller->renderAjax('_detail.php', [
            'id' => $parentid
        ]);
    }


    public function actionCreate($enumtype)
    {

        if (!Yii::$app->function->findByField("enumid", "enum", " and enumid='" . $enumtype . "' ")) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $model = new Enum;


        if ($model->load(Yii::$app->request->post())) {


            $valid = $model->validate();
            $id = \Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();

            if ($valid) {
                $model->enumid = $id;
                $Transaction = Yii::$app->db->beginTransaction();
                try {

                    if ($flag = $model->save(false)) {


                        if ($flag) {
                            $Transaction->commit();
                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return ['success' => true, 'pesan' => 'Data Berhasil Disimpan', 'id' => $model->enumid, 'name' => $model->enumtext_id];
                            }
                            return $this->redirect(['index', 'id' => $model->enumid]);
                        } else {

                            $Transaction->rollBack();
                            //                        var_dump($Transaction);
                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return ['success' => false, 'pesan' => implode($model->getFirstErrors(), "<br/>(X) ") . "(X) " . implode($modeldetail->getFirstErrors(), "<br/>(X) "), 'id' => $model->pindaid, 'name' => $model->pindano];
                            }
                        }
                    }
                } catch (Exception $e) {

                    $Transaction->rollBack();
                    // var_dump($e);
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['success' => false, 'pesan' => $e->getMessage(), 'id' => $model->enumid, 'name' => $model->enumno];
                    }
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    try {
                        $pesan = implode($model->getFirstErrors(), "<br/>(X) ") . "(X) ";
                        // $pesan .= implode($modeldetail->getError(), "<br/>(X) ");
                    } catch (Exception $exc) {
                    }
                    return ['success' => false, 'pesan' => $pesan];
                }
            }
        }



        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
                'isajax' => "true",
                'enumtype' => $enumtype,
            ]);
        } else {
            return $this->render('_form', [
                'model' => $model,
                'isajax' => "false",
                'enumtype' => $enumtype,
            ]);
        }
    }


    public function actionCreate2()
    {
        $model = new Enum;


        // $modelvarian = new Usermenu();

        if ($model->load(Yii::$app->request->post()) && $modelvarian->load(Yii::$app->request->post())) {
            // var_dump($_POST); die;
            //	$modelmenu = Model::createMultipleID(Usermenu::classname(), $modelmenu, 'usermenuid');
            Model::loadMultiple($modelmenu, Yii::$app->request->post());
            //	$modellaporan = Model::createMultipleID(Userlaporan::classname(), $modellaporan, 'userlaporanid');
            Model::loadMultiple($modellaporan, Yii::$app->request->post());

            if ($modelvarian->username != "") {

                if ($modelvarian->password == "" || $modelvarian->password_repeat == "") {
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['success' => false, 'pesan' => "Password Tidak Boleh Kosong", 'id' => $model->enumid, 'name' => $model->enumtext_id];
                    }
                }

                if (!($modelvarian->validate())) {
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['success' => false, 'pesan' => "" . implode($model->getFirstErrors(), "<br/>(X) ") . "(X) " . implode($modelvarian->getFirstErrors(), "<br/>(X) "), 'id' => $model->enumid, 'name' => $model->enumtext_id];
                    }
                }
            }

            $valid = $model->validate();
            $valid = Model::validateMultiple($modelmenu) && $valid;
            $valid = Model::validateMultiple($modellaporan) && $valid;
            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    // var_dump($modelvarian->username != ""); die;
                    if ($modelvarian->username != "") {
                        if (($flag = $modelvarian->save(false))) {
                            $model->userid = $modelvarian->userid;
                        }
                    }
                    if ($flag = $model->save(false)) {
                    }

                    foreach ($modelmenu as $indexdetail => $modelmenu) {

                        if ($flag === false) {
                            break;
                        }

                        $modelmenu->userid = $model->userid;

                        if (!($flag = $modelmenu->save(false))) {
                            break;
                        }
                    }
                    foreach ($modellaporan as $indexdetails => $modellaporan) {

                        if ($flag === false) {
                            break;
                        }

                        $modellaporan->userid = $model->userid;

                        if (!($flag = $modellaporan->save(false))) {
                            break;
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return ['success' => true, 'pesan' => 'Data Berhasil Disimpan', 'id' => $model->enumid, 'name' => $model->enumtext_id];
                        }
                        return $this->redirect(['view', 'id' => $model->enumid]);
                    } else {
                        $transaction->rollBack();
                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return ['success' => false, 'pesan' => implode($model->getFirstErrors(), "") . implode($modelvarian->getFirstErrors(), ""), 'id' => $model->enumid, 'name' => $model->enumtext_id];
                        }
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['success' => false, 'pesan' => implode($model->getFirstErrors(), "") . implode($modelvarian->getFirstErrors(), ""), 'id' => $model->enumid, 'name' => $model->enumtext_id];
                    }
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => false, 'pesan' => implode($model->getFirstErrors(), "") . implode($modelvarian->getFirstErrors(), ""), 'id' => $model->enumid, 'name' => $model->enumtext_id];
                }
            }
        }





        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
                //  'modelvarian' => $modelvarian,
                //	'modelmenu' => (empty($modelmenu)) ? [new Usermenu] : $modelmenu,
                //	'modellaporan' => (empty($modellaporan)) ? [new Userlaporan] : $modellaporan,
                'isajax' => "true"
            ]);
        } else {
            return $this->render('_form', [
                'model' => $model,
                //  'modelvarian' => $modelvarian,
                //	'modelmenu' => (empty($modelmenu)) ? [new Usermenu] : $modelmenu,
                //  'modellaporan' => (empty($modellaporan)) ? [new Userlaporan] : $modellaporan,
                'isajax' => "false"
            ]);
        }
    }

    public function actionUpdate($enumtype, $id)
    {
        if (!Yii::$app->function->findByField("enumid", "enum", " and enumid='" . $enumtype . "' ")) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }


        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {



            $valid = $model->validate();

            if ($valid) {
                $Transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {



                        if ($flag) {
                            $Transaction->commit();

                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return ['success' => true, 'pesan' => 'Data Berhasil Disimpan', 'id' => $model->enumid, 'name' => $model->enumno];
                            }
                            return $this->redirect(['index', 'id' => $model->enumid]);
                        }
                    }
                } catch (Exception $e) {

                    $Transaction->rollBack();
                    //var_dump($e);
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['success' => false, 'pesan' => $e, 'id' => $model->enumid, 'name' => $model->enumno];
                    }
                }
            } else {
                //var_dump($model->getFirstErrors());
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => false, 'pesan' => implode($model->getFirstErrors(), "<br/>(X) ") . "(X) ", 'id' => $model->enumid, 'name' => $model->enumno];
                }
            }
        }


        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
                'isajax' => "true",
                'enumtype' => $enumtype,
            ]);
        } else {
            return $this->render('_form', [
                'model' => $model,
                'isajax' => "false",
                'enumtype' => $enumtype,
            ]);
        }
    }

    public function actionGetno($type = "")
    {
        $enumtype = $_POST['enumtype'];
        $array = array(
            "no" => Enum::nextNo($enumtype)
        );
        return \yii\helpers\Json::encode($array);
    }

    public function actionChangestatus()
    {
        // echo "ayam"; die;
        $status = $_POST['status'];
        $enumid = $_POST['enumid'];
        $jenis = $_POST['jenis'];
        $varianid = $_POST['varianid'];

        // var_dump($_POST); die;

        $Enumno = Yii::$app->function->findByField("Enumno", "Enum", " and enumid='" . $enumid . "' and status <>'10'");
        // var_dump($Enumno); die;
        if ($status == 1) {
            $text = "Telah Disetujui!";
            $box = "info";
        } else if ($status == 10) {
            $text = "Telah Ditolak!";
            $box = "danger";
        } else if ($status == 3) {
            $text = "Telah Bermasalah!";
            $box = "danger";
        }

        if ($varianid != '' || $varianid != null) {
            $statusvarian = Yii::$app->function->findByField("status", "varian", " and varianid='" . $varianid . "' and status <> 10");
            $varianid = Yii::$app->function->findByField("varianid", "varian", " and varianid='" . $varianid . "' and status <> 10");
        }

        try {
            // var_dump($varianid); die;
            $sql = "UPDATE Enum SET status='" . $status . "' WHERE enumid='" . $enumid . "'";
            //  echo $sql; exit; a
            $results = \Yii::$app->db->createCommand($sql)->execute();
            $results = true;

            //echo $enumid.">>".$status;exit;
            if ($status == 1) {
                //$result = Yii::$app->function->createJurnalEnum($enumid, $status);
            }
            if ($status == 2) {
                // $sqltgljual = "UPDATE Enum SET tgljual=now()::timestamp WHERE enumid='" . $enumid . "'";
                // $resjual = \Yii::$app->db->createCommand($sqltgljual)->execute();
            }
        } catch (Exception $exc) {
            $results = false;
            $text = $exc->getTraceAsString();
        }

        $growl = [
            'type' => $box,
            //            'delay' => 0,
            'title' => "Enum Nomor : " . $Enumno . " " . $text . "<i class='fa fa-check'></i><hr>",
            'icon' => 'fa fa-check',
            //            'body' => $text,
            'showSeparator' => true,
        ];


        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['result' => $results, 'pesan' => $text, 'growl' => $growl];
        }
    }

    public function actionChangestatusall()
    {
        die;
        $status = $_POST['status'];
        //        $enumid = $_POST['enumid'];
        if ($status == 1) {
            $text = "Telah Disetujui!";
            $box = "info";
        } else if ($status == 10) {
            $text = "Telah Ditolak!";
            $box = "danger";
        }

        $rec = explode('#', $_POST['postdata']);

        for ($i = 0; $i < count($rec) - 1; $i++) {
            $data = explode('|', $rec[$i]);
            $enumid = $data[0];
            if ($enumid == "")
                continue;
            $Enumno = Yii::$app->function->findByField("Enumno", "Enum", " and enumid='" . $enumid . "'");
            try {
                $sql = "UPDATE Enum SET status='" . $status . "' WHERE enumid='" . $enumid . "'";
                $results = \Yii::$app->db->createCommand($sql)->execute();
                $results = true;
            } catch (Exception $exc) {
                $results = false;
                $text = $exc->getTraceAsString();
            }
        }

        $growl = [
            'type' => $box,
            //            'delay' => 0,
            'title' => (count($rec) - 1) . " Data Terseleksi " . $text . "<i class='fa fa-check'></i><hr>",
            'icon' => 'fa fa-check',
            //            'body' => $text,
            'showSeparator' => true,
        ];


        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['result' => $results, 'pesan' => $text, 'growl' => $growl];
        }
    }

    /**
     * Deletes an existing Enum model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $enumtype)
    {
        $this->findModel($id)->delete();

        //  return $this->redirect(['index']);
        return $this->redirect(['index', 'enumtype' => $enumtype]);
    }

    public function actionCancel($id, $enumtype)
    {
        $receipt = Receipt::find()->where(['<>', 'status', 10])->andWhere(['enumid' => $id]);
        if ($receipt != null) {
            $model = $this->findModel($id)->updateAll(['status' => 10], "enumid = '" . $id . "'");
        }
        return $this->redirect(['index', 'enumtype' => $enumtype]);
    }


    // public function actionList()
    // {
    //     $q = $_POST['q'];
    //     $id = $_POST['id'];
    //     $tipe = $_POST['tipe'];
    //     $tglbayar = $_POST['tglbayar'];
    //     // $discdenda = $_POST['discdenda'];
    //     // $isdenda = $_POST['isdenda'];
    //     // var_dump ($discdenda); exit;
    //     if ($tglbayar != "") {
    //         $tglbayar = \Yii::$app->function->datePostgres($tglbayar);
    //     } else {
    //         $tglbayar = date("Y-m-d");
    //     }
    //     // var_dump ($tglbayar); exit;
    //     \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    //     $out = ['totalcount' => 0, 'items' => ['id' => '', 'text' => '']];
    //     // $filter = " WHERE 1=1 AND A.status<>10 AND (coalesce(B.bulan,0)<>0 or coalesce(C.dendalama,0) > 1 or coalesce(X.bosisa,0) > 0  or coalesce(D.dendabaru,0) > 1)";
    //     $filter = " WHERE 1=1 AND A.status <> 10";

    //     $limit = isset($_POST['limit']) ? $_GET['_POST'] : 10;
    //     $start = $limit * (isset($_POST['page']) ? $_POST['page'] : 0);
    //     $order = " order by A.Enumno asc limit $limit offset $start";

    //     if (!is_null($q)) {
    //         $filter .= " AND ("
    //             . "A.Enumno ilike '%" . $q . "%' "
    //             . " or A1.nama ilike '%" . $q . "%' "
    //             . " or A1.telp ilike '%" . $q . "%'"
    //             . ") ";
    //     }
    //     if (!is_null($id)) {
    //         $filter .= " AND A.enumid='" . $id . "'";
    //     }

    //     $sql = "
    // 	$filter";

    //     // echo $sql; die;

    //     $sqlcount = "SELECT COUNT(enumid) FROM ($sql) as temp";
    //     $query = $sql . $order;
    //     //echo $query;exit;
    //     $data = Yii::$app->db->createCommand($query)->queryAll();
    //     $count = Yii::$app->db->createCommand($sqlcount)->queryScalar();

    //     $out['items'] = array_values($data);
    //     $out['totalcount'] = $count;
    //     //var_dump($out); exit;
    //     return $out;
    // }

    public function actionList()
    {
        $enumtype = Yii::$app->request->get('enumtype');
        $q = $_POST['q'];
        $id = $_POST['id'];
        $tipe = $_POST['tipe'];

        // Ambil parameter filter
        $refid_category = Yii::$app->request->get('refid_category');
        $refid_subcategory = Yii::$app->request->get('refid_subcategory');
        $refid_country = Yii::$app->request->get('refid_country');
        $refid_state = Yii::$app->request->get('refid_state');
        $refid_city = Yii::$app->request->get('refid_city');

        $limit = isset($_POST['limit']) ? $_GET['_POST'] : 10;
        $start = $limit * (isset($_POST['page']) ? $_POST['page'] : 0);
        $order = " order by A.Enumno asc limit $limit offset $start";

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Build query
        $query = Enum::find()
            ->select([
                'enumid',
                'enumtype',
                'enumno',
                'enum_code_id',
                'enumtext_id',
                'amount',
                'amount2',
                'refid',
                'refid2',
                'refid3',
                'iscut',
                'E1.enumtext_id as reftext',
                'E2.enumtext_id as reftext2',
                'E3.enumtext_id as reftext3',
            ])
            ->from('enum')
            ->leftJoin('enum E1', 'enum.refid = E1.enumid')
            ->leftJoin('enum E2', 'enum.refid2 = E2.enumid')
            ->leftJoin('enum E3', 'enum.refid3 = E3.enumid')
            ->where(['enum.enumtype' => $enumtype])
            ->andWhere(['<>', 'enum.status', 10]);

        // Filter berdasarkan search
        if (!empty($search)) {
            $query->andWhere([
                'or',
                ['ilike', 'enum.enumtext_id', $search],
                ['ilike', 'enum.enum_code_id', $search]
            ]);
        }

        // Filter berdasarkan enumtype
        if ($enumtype == 'spec' || $enumtype == 'subcategory') {
            // Untuk spec & subcategory → filter by category (refid)
            if (!empty($refid_category)) {
                $query->andWhere(['enum.refid' => $refid_category]);
            }
        }

        if ($enumtype == 'type') {
            // Untuk type → filter by subcategory (refid)
            if (!empty($refid_subcategory)) {
                $query->andWhere(['enum.refid' => $refid_subcategory]);
            }
        }

        if ($enumtype == 'state') {
            // Untuk state → filter by country (refid)
            if (!empty($refid_country)) {
                $query->andWhere(['enum.refid' => $refid_country]);
            }
        }

        if ($enumtype == 'city') {
            // Untuk city → filter by state (refid2)
            if (!empty($refid_state)) {
                $query->andWhere(['enum.refid2' => $refid_state]);
            }
        }

        if ($enumtype == 'district') {
            // Untuk district → filter by city (refid3)
            if (!empty($refid_city)) {
                $query->andWhere(['enum.refid3' => $refid_city]);
            }
        }

        $sqlcount = "SELECT COUNT(enumid) FROM ($query) as temp";
        $query = $query . $order;
        //echo $query;exit;
        $data = Yii::$app->db->createCommand($query)->queryAll();
        $count = Yii::$app->db->createCommand($sqlcount)->queryScalar();

        $out['items'] = array_values($data);
        $out['totalcount'] = $count;
        //var_dump($out); exit;
        return $out;
    }
    public function actionLoadEnum()
    {
        if (Yii::$app->request->isAjax) {
            $harga = "A.harga";
            $tipeharga = $_POST['tipeharga'];
            if ($tipeharga != "0") {
                $harga = "A.harga" . $tipeharga;
            }
            $sql = "SELECT A.Enumno,$harga as harga,A.hargabeli FROM Enum A WHERE A.enumid='" . $_POST['id'] . "'";
            $rows = Yii::$app->db->createCommand($sql)->queryAll();
            return \yii\helpers\Json::encode($rows);
        }
    }

    public function actionEducationlist()
    {
        $q = $_POST['q'] ?? null;
        $id = $_POST['id'] ?? null;
        $enum_type = $_POST['enum_type'] ?? null;

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $out = ['totalcount' => 0, 'items' => []];
        $filter = "WHERE 1=1 ";

        $sql = Enum::find()
            ->select(['c.contact_education as code', 'e.enum_name AS education'])
            ->from('contact c')
            ->innerJoin('enum e', 'c.contact_education = e.enumno')
            ->where(['e.enumtype' => 'individual_education'])
            ->andWhere(['like', 'enumtype', $q]);
        // ->limit($limit)
        // ->offset(($page - 1) * $limit); 



        $query = $sql;
        //   $sqlcount = "SELECT COUNT(*) FROM ($sql) AS temp";

        $data = Yii::$app->db->createCommand($query)->queryAll();
        $count = Yii::$app->db->createCommand($sqlcount)->queryScalar();
        $totalCount = $query->count();

        $out['items'] = array_values($data);
        $out['totalcount'] = $count;

        return $out;
    }

    public function actionGenderlist()
    {
        $q = $_POST['q'] ?? null;
        $id = $_POST['id'] ?? null;
        $enum_type = $_POST['enum_type'] ?? null;

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $out = ['totalcount' => 0, 'items' => []];
        $filter = "WHERE 1=1 ";

        $sql = Enum::find()
            ->select(['c.contact_gender as code', 'e.enum_name AS gender'])
            ->from('contact c')
            ->innerJoin('enum e', 'c.contact_gender = e.enumno')
            ->where(['e.enumtype' => 'gender'])
            ->andWhere(['like', 'enumtype', $q]);
        // ->limit($limit)
        // ->offset(($page - 1) * $limit); 



        $query = $sql;
        //   $sqlcount = "SELECT COUNT(*) FROM ($sql) AS temp";

        $data = Yii::$app->db->createCommand($query)->queryAll();
        $count = Yii::$app->db->createCommand($sqlcount)->queryScalar();
        $totalCount = $query->count();

        $out['items'] = array_values($data);
        $out['totalcount'] = $count;

        return $out;
    }

    public function actionMarriedlist()
    {
        $q = $_POST['q'] ?? null;
        $id = $_POST['id'] ?? null;
        $enum_type = $_POST['enum_type'] ?? null;

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $out = ['totalcount' => 0, 'items' => []];
        $filter = "WHERE 1=1 ";

        $sql = Enum::find()
            ->select(['c.contact_married as code', 'e.enum_name AS married'])
            ->from('contact c')
            ->innerJoin('enum e', 'c.contact_married = e.enumno')
            ->where(['e.enumtype' => 'married'])
            ->andWhere(['like', 'enumytpe', $q]);
        // ->limit($limit)
        // ->offset(($page - 1) * $limit); 



        $query = $sql;
        //   $sqlcount = "SELECT COUNT(*) FROM ($sql) AS temp";

        $data = Yii::$app->db->createCommand($query)->queryAll();
        $count = Yii::$app->db->createCommand($sqlcount)->queryScalar();
        $totalCount = $query->count();

        $out['items'] = array_values($data);
        $out['totalcount'] = $count;

        return $out;
    }

    public function actionKategorilist()
    {
        $q = $_POST['q'];
        $id = $_POST['id'];
        $produktype = $_POST['enumtype'];
        // var_dump($produktype);exit;

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['totalcount' => 0, 'items' => []];
        $filter = "WHERE 1=1 ";

        if (!is_null($produktype)) {
            $filter .= " AND enumtext_id ILIKE '%" . addslashes($q) . "%'";
        }
        if ($produktype == 'produktype.fd') {
            $enumtype = 'master.fd';
        } elseif ($produktype == 'produktype.fb') {
            $enumtype = 'master.fb';
        } elseif ($produktype == 'produktype.ac') {
            $enumtype = 'master.ac';
        }

        if (!is_null($enumtype)) {
            $filter .= " AND enumtype ='$enumtype'";
        }
        $sql = "SELECT enumid AS id, enumtext_id AS text 
                    FROM enum 
                     $filter";
        $query = $sql;
        $sqlcount = "SELECT COUNT(*) FROM ($sql) AS temp";

        $data = Yii::$app->db->createCommand($query)->queryAll();
        $count = Yii::$app->db->createCommand($sqlcount)->queryScalar();

        $out['items'] = array_values($data);
        $out['totalcount'] = $count;

        return $out;
    }

    public function actionUnitlist()
    {
        $q = $_POST['q'] ?? null;
        $id = $_POST['id'] ?? null;
        $enumtype = $_POST['enumtype'] ?? null;

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['totalcount' => 0, 'items' => []];
        $filter = "WHERE 1=1 ";

        if (!is_null($q)) {
            $filter .= " AND enumtext_id ILIKE '%" . addslashes($q) . "%'";
        }
        if ($enumtype == 'produktype.fd') {
            $enumtype = 'master.un';
        } elseif ($enumtype == 'produktype.fb') {
            $enumtype = 'master.un';
        } elseif ($enumtype == 'produktype.ac') {
            $enumtype = 'master.un';
        }

        if (!is_null($enumtype)) {
            $filter .= " AND enumtype ='$enumtype'";
        }

        // if (!is_null($id)) {
        //     $filter .= " AND enumno = '" . addslashes($id) . "'";
        //     $sql = "SELECT enumno AS id, enumtext_id AS text
        //             FROM enum 
        //             LEFT JOIN enum S ON S.enumtype = S.kategoriproduk.fd
        //             LEFT JOIN enum J ON J.enumtype = J.kategoriproduk.fb
        //             LEFT JOIN enum K ON K.enumtype = K.kategoriproduk.ac
        //             WHERE 1=1 $filter";
        // } else {
        //     $sql = "SELECT enumid AS id, enumtext_id AS text 
        //             FROM enum 
        //             WHERE enumtype = 'kategoriproduk.fd' $filter";
        // }
        $sql = "SELECT enumid AS id, enumtext_id AS text 
                    FROM enum 
                     $filter";
        $query = $sql;
        $sqlcount = "SELECT COUNT(*) FROM ($sql) AS temp";

        $data = Yii::$app->db->createCommand($query)->queryAll();
        $count = Yii::$app->db->createCommand($sqlcount)->queryScalar();

        $out['items'] = array_values($data);
        $out['totalcount'] = $count;

        return $out;
    }

    public function actionLocationlist()
    {
        $q = $_POST['q'] ?? null;
        $id = $_POST['id'] ?? null;
        $enumtype = $_POST['enumtype'] ?? null;

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['totalcount' => 0, 'items' => []];
        $filter = "WHERE 1=1 ";

        if (!is_null($q)) {
            $filter .= " AND enumtext_id ILIKE '%" . addslashes($q) . "%'";
        }
        if ($enumtype == 'produktype.fd') {
            $enumtype = 'master.lk';
        } elseif ($enumtype == 'produktype.fb') {
            $enumtype = 'master.lk';
        } elseif ($enumtype == 'produktype.ac') {
            $enumtype = 'master.lk';
        }

        if (!is_null($enumtype)) {
            $filter .= " AND enumtype ='$enumtype'";
        }

        // if (!is_null($id)) {
        //     $filter .= " AND enumno = '" . addslashes($id) . "'";
        //     $sql = "SELECT enumno AS id, enumtext_id AS text
        //             FROM enum 
        //             LEFT JOIN enum S ON S.enumtype = S.kategoriproduk.fd
        //             LEFT JOIN enum J ON J.enumtype = J.kategoriproduk.fb
        //             LEFT JOIN enum K ON K.enumtype = K.kategoriproduk.ac
        //             WHERE 1=1 $filter";
        // } else {
        //     $sql = "SELECT enumid AS id, enumtext_id AS text 
        //             FROM enum 
        //             WHERE enumtype = 'kategoriproduk.fd' $filter";
        // }
        $sql = "SELECT enumid AS id, enumtext_id AS text 
                    FROM enum 
                     $filter";
        $query = $sql;
        $sqlcount = "SELECT COUNT(*) FROM ($sql) AS temp";

        $data = Yii::$app->db->createCommand($query)->queryAll();
        $count = Yii::$app->db->createCommand($sqlcount)->queryScalar();

        $out['items'] = array_values($data);
        $out['totalcount'] = $count;

        return $out;
    }

    public function actionStatuslist()
    {
        $q = $_POST['q'] ?? null;
        $id = $_POST['id'] ?? null;
        //  $enumtype = $_POST['enumtype'] ?? null;
        // var_dump($enumtype);exit;

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['totalcount' => 0, 'items' => []];
        $filter = "WHERE 1=1 ";

        if (!is_null($q)) {
            $filter .= " AND enumtext_id ILIKE '%" . addslashes($q) . "%'";
        }
        if ($enumtype == 'statuspaid') {
            $enumtype = 'trantype.re';
        } elseif ($enumtype == 'statuspaid') {
            $enumtype = 'trantype.sa';
        } elseif ($enumtype == 'statuspaid') {
            $enumtype = 'trantype.rt';
        }

        if (!is_null($enumtype)) {
            $filter .= " AND E.enumtype ='$enumtype'";
        }
        // }
        //else {
        $sql = "SELECT E.enumid AS id, E.enumtext_id AS text
        FROM enum E
        LEFT JOIN tran T ON E.enumid = T.statuspaid

                     $filter";
        // }
        $query = $sql;
        $sqlcount = "SELECT COUNT(*) FROM ($sql) AS temp";

        $data = Yii::$app->db->createCommand($query)->queryAll();
        $count = Yii::$app->db->createCommand($sqlcount)->queryScalar();

        $out['items'] = array_values($data);
        $out['totalcount'] = $count;

        return $out;
    }
    protected function findModel($id)
    {
        if (($model = Enum::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPrint($jenis, $id, $jenis2 = "")
    {

        // $model = $this->findModel($id);
        //        if (isset($_GET['tipe']) && ($_GET['tipe']) != "") {
        //            $model->updateAll(['isprint' => 1, 'status' => 1], "enumid = '" . $id . "'");
        //        }
        $jenisreport = "pdf";

        // set_time_limit(40000);
        require_once(Yii::getAlias('@anyname') . "/Report/java/Java.inc");


        $compileManager = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");



        //===================================================================================
        //DEFAULT
        //===================================================================================
        $report = null;
        $judul = "";
        $namafile = "";

        //        $tglprint = substr($tglprint, 0, 2) . " " . ($this->getNmBulan(substr($tglprint, 3, 2))) . " " . substr($tglprint, 6, 4);
        //===================================================================================
        //COMPILE REPORT
        //===================================================================================

        // if ($jenis == "0") {
        //     $judul = "Tanda Terima";
        //     $subjudul = "";
        //     $this->getQueryEnum($id, $jenis2, $sql, $sqlsub, $sqlcount, $sqlcountsub);
        //     $report = $compileManager->compileReport(realpath(Yii::getAlias('@anyname') . "/Report/rptKwitansiNew.jrxml"));
        // } else if ($jenis == "1") {
        //     $judul = "Nota Perpanjangan Gadai";
        //     $subjudul = "";
        //     $this->getQueryEnum2($id, $jenis2, $sql, $sqlsub, $sqlcount, $sqlcountsub);
        //     // echo $sql; die;
        //     $report = $compileManager->compileReport(realpath(Yii::getAlias('@anyname') . "/Report/rptKwitansiNew.jrxml"));
        // } else if ($jenis == "2") {
        //     $judul = "SURAT BUKTI GADAI";
        //     $subjudul = "";
        //     $this->getQueryEnum3($id, $sql, $sqlsub, $sqlcount, $sqlcountsub);
        //     $report = $compileManager->compileReport(realpath(Yii::getAlias('@anyname') . "/Report/jpgadai/SuratPerjanjian2.jrxml"));
        // } else if ($jenis == "3") {
        //     // $sql = "UPDATE Enum SET tglkeluar= now()::date WHERE enumid='" . $id . "'";
        //     // $data = Yii::$app->db->createCommand($sql)->queryAll();
        //     //var_dump ($sql); exit;
        //     $judul = "Bukti Serah Terima BPKB";
        //     $subjudul = "Periode " . $filterdate;
        //     $this->getQueryBuktibpkb($id, $sql, $sqlsub, $sqlcount, $sqlcountsub);
        //     $report = $compileManager->compileReport(realpath(Yii::getAlias('@anyname') . "/Report/rptBuktiSerahTerimaBpkb.jrxml"));
        // }
        $namafile = str_replace(" ", "_", $judul) . "_" . $model->Enumno;




        \Yii::$app->function->isDataExistReport($sqlcount, $isexist, $max);
        if (!$isexist) {
            echo '<script>alert("Maaf, Tidak ada data untuk diprint!!");</script>';
            exit();
        }
        //===================================================================================
        //GET LOKASI & PENANDATANGAN
        //===================================================================================
        //  $this->getPenandaTangan($kaquotationid, $pbid, $kaquotationid, $kaquotationnama, $kaquotationnip, $kaquotationjabatan, $pbid, $pbnama, $pbnip, $pbjabatan);
        //  $this->getLokasi($propinsiid, $kabupatenid, $kepemilikanid, $ruangid, $propinsikode, $propinsinama, $propinsivw, $kabupatenkode, $kabupatennama, $kabupatenvw, $kepemilikankode, $kepemilikannama, $kepemilikanvw, $ruangkode, $ruangnama, $ruangvw);
        //===================================================================================
        //PARAMETER
        //===================================================================================
        /* $querybo = "

                  select Enumno,
                  SUM(CASE WHEN tunggakan = 1 THEN 150000 WHEN tunggakan = 2 THEN 300000 else 0 END) as tunggakan, sum(totalbayar) as totalbayar from (
                  select B.Enumno,
                  CASE WHEN coalesce(A.jmltelat) > 30 THEN 1 ELSE 0 END as tunggakan,
                  SUM(coalesce(CASE isdenda WHEN 1 THEN 0 ELSE (bo) END,0)) as totalbayar
                  from receipt A
                  LEFT JOIN Enum B on A.enumid = B.enumid
                  where A.enumid ='$id' and A.status <>'10' and B.Enumno not like 'PD%'
                  group by Enumno,jmltelat ) as A
                      group by Enumno
                  ";
                   */
        $tglbayar = date("Y-m-d");
        $querytunggakan = "
		select case when SUM(totalbo) > '300000' THEN '300000' ELSE SUM(totalbo) end as totalbo from (
			select 
			CASE 
			WHEN sum(totalbo) = 1 THEN 150000
			WHEN sum(totalbo) > 1 THEN 300000 ELSE 0 end as totalbo from (		 
				select 				
				CASE WHEN jmltelat > 31 and jmltelat < 61 THEN 1 
					 WHEN jmltelat > 61 THEN 2 end as totalbo FROM receipt
				where enumid ='$id'
			) as A	
			UNION ALL
		SELECT CASE WHEN cektelathari(B.tgltempo,'" . $tglbayar . "'::date) > 31 AND cektelathari(B.tgltempo,'" . $tglbayar . "'::date) < 61 THEN 150000 
		WHEN cektelathari(B.tgltempo,'" . $tglbayar . "'::date) > 61 THEN 300000 END as totalbo	from vwlastinfo B where B.enumid ='$id'
	) as A
				";
        $sqltunggakan = "SELECT CASE WHEN cektelathari(B.tgltempo,'" . $tglbayar . "'::date) > 31 and cektelathari(B.tgltempo,'" . $tglbayar . "'::date) < 61  THEN 150000 
		WHEN cektelathari(B.tgltempo,'" . $tglbayar . "'::date) > 61 THEN 300000 ELSE 0 END as totalbo	from vwlastinfo B where B.enumid ='$id' 
				";
        $querybayar = "
				SELECT coalesce(A.bo,0) as bo, coalesce(A.bobayar,0) as bobayar, coalesce(A.bosisa) as bosisa FROM vwbo A		
		where A.enumid ='$id'
			";
        $querypotdenda = "SELECT coalesce(sum(disc),0)::int as potdenda FROM receipt where enumid ='$id' and tipe ='3' and status <> '10'";
        $potdenda = Yii::$app->db->createCommand($querypotdenda)->queryAll();
        $tunggakan = Yii::$app->db->createCommand($querytunggakan)->queryAll();
        //var_dump ($querytunggakan); exit;
        $result = Yii::$app->db->createCommand($querybayar)->queryAll();
        $tunggakan = $tunggakan[0]['totalbo'];
        $totalbayar = $result[0]['bobayar'];
        $pbo = $result[0]['bo'];
        //var_dump ($pbo);

        if ($totalbayar == '' || $totalbayar == null) {
            $totalbayar = 0;
        }

        $tunggakan = $tunggakan - $totalbayar;
        //var_dump($tunggakan . "-" . $totalbayar); exit; 
        if ($tunggakan == '' || $tunggakan == null || $tunggakan < 0) {
            $tunggakan = 0;
        }
        //echo $totalbayar . " , " . $tunggakan; exit;
        $lastdenda = "
				SELECT sum(B.denda)::integer as denda from Enum A
left join receipt b ON a.enumid=b.enumid
where A.enumid ='$id' and (B.bulan - A.tenor) > 1 and B.status <>'10'
				";
        $hasildenda = Yii::$app->db->createCommand($lastdenda)->queryAll();

        $denda = $hasildenda[0]['denda'];
        if ($denda != "") {
            $dendatambahan = $denda;
        } else {
            $dendatambahan = 0;
        }
        //var_dump ($potdenda[0]['potdenda']); exit;
        $potongandenda = $potdenda[0]['potdenda'];
        //var_dump ($dendatambahan); exit;
        $total = $result[0]['bobayar'];
        $params = new Java("java.util.HashMap");
        $params->put("pquery", $sql);
        $params->put("pquerysub", $sqlsub);
        $params->put("pdirfotoinstansi", Yii::getAlias('@anyname') . "/Report/img/");
        $params->put("lastdenda", $dendatambahan);

        $params->put("potdenda", $potongandenda);
        $params->put("pemutus", "Fernando");
        $params->put("pbo", $tunggakan);
        $params->put("ptotalbo", $totalbayar);
        //        $params->put("pcountsub", 0);
        //        $params->put("pdirfotoinstansi", $this->getDirBase() . "/fotoInstansi/" . Yii::app()->user->getInstansi("logo"));
        //        $params->put("ppemda", (Yii::app()->user->getInstansi("jenisinstansi")));
        //        $params->put("pkota", Yii::app()->user->getInstansi("kota"));
        $params->put("pinstansi", Yii::$app->enum->getcabangvw("nama"));
        $params->put("pinstansi", "JP GADAI");
        $params->put("palamat", Yii::$app->enum->getcabangvw("alamat"));
        $params->put("ptelp", Yii::$app->enum->getcabangvw("telepon"));
        //  $params->put("pfax", "454227");
        //        $params->put("pkodepos", (Yii::app()->user->getInstansi("kodepos")));
        //
        $params->put("pusername", strtoupper(Yii::$app->user->identity->username));
        $params->put("pjabatan", "Kasir");
        //
        //        $params->put("pkaquotationnip", "");
        //        $params->put("pkaquotationnama", "");
        //
        //        $params->put("pkaquotationket", "");
        //        $params->put("pkaquotationjabatan", "");
        //
        $params->put("pjudul", $judul);
        $params->put("puser", Yii::$app->user->identity->username);
        //        $params->put("psubjudul", $subjudul);
        $params->put("pwaktu", date("d-m-Y H:i:s"));
        // $params->put("ptahun", $tahun);
        //        $params->put("pmax", intval($max));
        $params->put("SUBREPORT_DIR", Yii::getAlias('@anyname') . "/Report/");
        //===================================================================================
        //PRINT
        //===================================================================================

        if ($jenisreport == "pdf") {
            \Yii::$app->function->PrintPDF($report, $params, $namafile);
        } else if ($jenisreport == "excel") {
            \Yii::$app->function->PrintExcel($report, $params, $namafile);
        } else if ($jenisreport == "word") {
            \Yii::$app->function->PrintDoc($report, $params, $namafile);
        }
    }

    public function actionPrintall($jenisreport, $jenis, $filterdate = "", $filtersearch = "", $filterEnumman = "", $filterstatus = "", $filterkurir = "")
    {
        //set_time_limit(40000);
        require_once(Yii::getAlias('@anyname') . "/Report/java/Java.inc");

        $compileManager = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");


        //===================================================================================
        //DEFAULT
        //===================================================================================
        $report = null;
        $judul = "";
        $namafile = "";

        //===================================================================================
        //COMPILE REPORT
        //===================================================================================
        if ($jenis == 0) {
            $judul = "Laporan Enum";
            $subjudul = "Periode " . $filterdate;
            $this->getQueryEnumPeriod($filterdate, $filtersearch, $filterEnumman, $filterstatus, $filterkurir, $sql, $sqlsub, $sqlcount, $sqlcountsub);
            $report = $compileManager->compileReport(realpath(Yii::getAlias('@anyname') . "/Report/rptEnumperiod.jrxml"));
        } else {
            $judul = "Laporan Enum per Enumman";
            $subjudul = "Periode " . $filterdate;
            $this->getQueryEnumPrice($filterdate, $filtersearch, $filterEnumman, $filterstatus, $filterkurir, $sql, $sqlsub, $sqlcount, $sqlcountsub);
            $report = $compileManager->compileReport(realpath(Yii::getAlias('@anyname') . "/Report/rptEnumprice.jrxml"));
        }
        $namafile = str_replace(" ", "_", $judul) . "_" . date("dmY_hhmmss");

        \Yii::$app->function->isDataExistReport($sqlcount, $isexist, $max);
        if (!$isexist) {
            //            echo "Maaf, Tidak ada data untuk diprint!!";
            //            exit();
        }
        //===================================================================================
        //PARAMETER
        //===================================================================================

        $params = new Java("java.util.HashMap");
        $params->put("pquery", $sql);
        $params->put("pquerysub", $sqlsub);
        $params->put("pdirfotoinstansi", Yii::getAlias('@anyname') . "/Report/img/");
        $params->put("pjudul", $judul);
        $params->put("psubjudul", $subjudul);
        $params->put("pmax", intval($max));
        $params->put("SUBREPORT_DIR", Yii::getAlias('@anyname') . "/Report/");
        //===================================================================================
        //PRINT
        //===================================================================================
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

        if ($jenisreport == "pdf") {

            \Yii::$app->function->PrintPDF($report, $params, $namafile);
        } else if ($jenisreport == "excel") {
            \Yii::$app->function->PrintExcel($report, $params, $namafile);
        } else if ($jenisreport == "word") {
            \Yii::$app->function->PrintDoc($report, $params, $namafile);
        }
    }
    public function actionGetvoucher()
    {
        // var_dump("en"); exit;
        $id = $_POST['id'];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $sql = "
        select enumid as id, enumtext_id as text,amount/100 as disc from enum 
        where enumid ='$id'";
        $results = \Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($results as $index => $result) {
            $id = $result['id'];
            $text = $result['text'];
            $total = $result['disc'];
        }
        return \yii\helpers\Json::encode($results);
    }
}
