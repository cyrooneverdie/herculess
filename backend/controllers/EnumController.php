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
    public function init()
    {
        parent::init();
        Yii::$app->language = Yii::$app->lang->getLang();
    }

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
                    'update' => ['POST', 'GET',],
                    'cancel' => ['POST'],
                    'upload' => ['POST', 'GET', 'PUT'],
                    'create' => ['POST', 'GET', 'PUT']
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // [
                    //     'actions' => ['cancel', 'upload', 'changestatus', 'changestatusall', 'hapusfoto'],
                    //     'allow' => true,
                    //     'matchCallback' => function () {
                    //         return (Yii::$app->enum->issuperadmin());
                    //     }
                    // ],
                    // [
                    //     'actions' => ['index','enumdetail', 'adjust', 'create', 'getno', 'update', 'view', 'delete', 'print', 'hapusfoto', 'printall'],
                    //     'allow' => true,
                    //     'matchCallback' => function () {
                    //         return (Yii::$app->enum->isadmin());
                    //     }
                    // ],
                    // [
                    //     'actions' => ['print'],
                    //     'allow' => true,
                    //     'matchCallback' => function () {
                    //         return (Yii::$app->enum->isadminvw());
                    //     }
                    // ],
                    [
                        'actions' => ['index', 'enumdetail', 'view', 'tempo', 'tebus', 'massaction', 'jual', 'detail', 'update', 'delete', 'filter', 'load'],
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
                        'actions' => ['list', 'select', 'create', 'index', 'enumlist', 'unitlist', 'locationlist', 'kategorilist', 'statuslist', 'getvoucher', 'massaction', 'detail', 'update', 'delete', 'filter', 'load', 'save'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($enumtype)
    {
        $params = Yii::$app->request->queryParams;
        $refid = $params['refid'] ?? '';

        $model = new Enum();
        $model->refid = $refid;

        return $this->render('index', [
            'searchModel' => $model,
            'enumtype' => $enumtype,
        ]);
    }

    public function actionMassaction()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $ids = Yii::$app->request->post('ids');
        $action = Yii::$app->request->post('action');
        $status = Yii::$app->request->post('status');

        if (empty($ids) || !is_array($ids)) {
            return [
                'success' => false,
                'message' => 'Tidak ada data yang dipilih!'
            ];
        }

        try {
            $idString = implode(',', array_map(function ($id) {
                return "'" . addslashes($id) . "'";
            }, $ids));

            if ($action === 'delete') {
                // Soft delete → status jadi 10
                $sql = "UPDATE enum 
                    SET status = 10,
                        updatedby = :user_id,
                        updatedat = NOW()
                    WHERE enumid IN ($idString)";
                $rowsAffected = Yii::$app->db->createCommand($sql, [
                    ':user_id' => Yii::$app->user->id,
                ])->execute();

                $message = "Data berhasil dihapus (soft delete)!";
            } else {
                // Update status biasa
                $sql = "UPDATE enum 
                    SET status = :status,
                        updatedby = :user_id,
                        updatedat = NOW()
                    WHERE enumid IN ($idString)";
                $rowsAffected = Yii::$app->db->createCommand($sql, [
                    ':status' => $status,
                    ':user_id' => Yii::$app->user->id,
                ])->execute();

                $message = "Status berhasil diubah!";
            }

            return [
                'success' => true,
                'message' => $message,
                'rows_affected' => $rowsAffected,
                'action' => $action,
                'status' => $status
            ];
        } catch (\Exception $e) {
            Yii::error("Error in mass action: " . $e->getMessage());

            return [
                'success' => false,
                'message' => "Terjadi kesalahan: " . $e->getMessage(),
                'error' => true
            ];
        }
    }

    public function actionSelect()
    {
        $q = $_POST['q'];
        $id = $_POST['id'];
        $enumtype = $_POST['enumtype'];

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['totalcount' => 0, 'items' => ['id' => '', 'text' => '']];
        $filter = " WHERE 1=1 AND A.status <> '10'"; //hapus enum_code_id karna error
        $filter .= " AND A.enumtype = 'position'"; //baru

        $limit = isset($_POST['limit']) ? $_POST['limit'] : 5;
        $start = $limit * (isset($_POST['page']) ? ($_POST['page'] - 1) : 0);
        $order = " order by A.enum_code_id asc limit $limit offset $start";

        if (!empty($q)) {
            $filter .= " AND ("
                . "A.enum_code_id ilike '%" . $q . "%' "
                . " or A.enumtext_id ilike '%" . $q . "%' "
                . ") ";
        }

        if (!empty($enumtype)) {
            $filter .= " AND A.enumtype = '" . addslashes($enumtype) . "'";
        }

        if (!empty($positionid)) {
            $filter .= " AND A.positionid = 'position." . addslashes($positionid) . "'";
        }


        if (!is_null($id)) {
            $filter = " AND (A.enumid = '" . addslashes($id) . "'";
        } else {
        }

        $sql = "SELECT
				A.enumid as id,  A.enumtext_id ||' ['||A.enum_code_id ||']'as text
                FROM enum A
                $filter";

        $sqlcount = "SELECT COUNT(*) FROM ($sql) as temp";
        $query = $sql . $order;
        // var_dump($query);die();
        // echo $query;exit;
        $data = Yii::$app->db->createCommand($query)->queryAll();
        $count = Yii::$app->db->createCommand($sqlcount)->queryScalar();

        $out['items'] = array_values($data);
        $out['totalcount'] = $count;
        return $out;
    }

    public function actionDetail($enumid)
    {
        // $parentid = $_POST['expandRowKey'];
        return Yii::$app->controller->renderAjax('_detail.php', [
            'id' => $enumid
        ]);
    }

    public function actionLoad($enumtype)
    {
        $exist = Enum::find()
            ->andWhere(['enumtype' => $enumtype])
            ->exists();
        $model = new Enum();

        if (!$exist) {
            return $this->renderPartial('_masterform', [
                'enumtype' => $enumtype,
                'exsist' => true,
                'model' => $model
            ]);
        }

        return $this->renderPartial('_masterform', [
            'enumtype' => $enumtype,
            'exsist' => true,
            'model' => $model
        ]);
    }

    public function actionCreate($enumtype, $refid = null)
    {
        $params = Yii::$app->request->queryParams;
        $refid = $params['refid'] ?? '';
        $model = new Enum;

        $model->enumtype = $enumtype;
        // var_dump($enumtype); exit;
        if ($model->load(Yii::$app->request->post())) {

            // Cek manual duplikat
            $exists = Enum::find()
                ->where(['enumtype' => $enumtype])
                ->andWhere([
                    'enumtext_id' => $model->enumtext_id,
                    'enum_code_id' => $model->enum_code_id,
                    'refid' => $model->refid,
                ])
                ->andWhere(['<>', 'status', 10]) // abaikan data soft delete
                ->exists();

            if ($exists) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => false, 'pesan' => 'Data cannot be the same'];
                }
                Yii::$app->session->setFlash('error', 'Data cannot be the same');
                Yii::$app->session->close();
                return $this->redirect(['create', 'enumtype' => $model->enumtype]);
            }

            // $id = \Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();
            // $model->enumid = $id;
            $valid = $model->validate();
            // var_dump($valid);exit;

            if ($valid) {

                try {
                    if ($model->enumtype == 'job' || $model->enumtype == 'dinas') {
                        $model->amount = (float) str_replace(',', '', $model->amount);
                    }
                    if ($flag = $model->save(false)) {
                        if ($flag) {
                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return ['success' => true, 'pesan' => 'Data Berhasil Disimpan', 'id' => $model->enumid, 'name' => $model->enumtext_id];
                            }
                            return $this->redirect(['index', 'enumtype' => $model->enumtype]);
                        } else {
                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return ['success' => false, 'pesan' => implode($model->getFirstErrors()), 'id' => $model->enumid, 'name' => $model->enumtext_id];
                            }
                            Yii::$app->session->setFlash('error', $model->getFirstErrors());
                            Yii::$app->session->close();
                            return $this->redirect(['create', 'enumtype' => $model->enumtype]);
                        }
                    }
                } catch (Exception $e) {
                    // var_dump($e);
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['success' => false, 'pesan' => $e->getMessage()];
                    }
                    Yii::$app->session->setFlash('error', $e->getMessage());
                    Yii::$app->session->close();
                    return $this->redirect(['create', 'enumtype' => $model->enumtype]);
                }
            } else {
                //var_dump($model->getFirstErrors());
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => false, 'pesan' => implode($model->getFirstErrors()), 'id' => $model->enumid, 'name' => $model->enumtext_id];
                }
            }
        }
        $model->refid = $refid;
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
                'isajax' => true,
                'enumtype' => $enumtype,
                'refid' => $refid,
            ]);
        } else {
            return $this->render('_form', [
                'model' => $model,
                'isajax' => false,
                'enumtype' => $enumtype,
                'refid' => $refid,
            ]);
        }
    }

    public function actionUpdate($enumid, $enumtype)
    {
        // var_dump($enumtype);exit;
        $model = $this->findModel($enumid);
        $model->enumtype = $enumtype;

        if ($model->load(Yii::$app->request->post())) {

            //  Validasi manual duplikat
            $exists = Enum::find()
                ->where(['enumtype' => $enumtype])
                ->andWhere([
                    'enumtext_id' => $model->enumtext_id,
                    'enum_code_id' => $model->enum_code_id,
                    'refid' => $model->refid,
                ])
                ->andWhere(['<>', 'enumid', $enumid]) // exclude diri sendiri
                ->andWhere(['<>', 'status', 10]) // abaikan data soft delete
                ->exists();

            if ($exists) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => false, 'pesan' => 'Data cannot be the same'];
                }
                Yii::$app->session->setFlash('error', 'Data cannot be the same');
                Yii::$app->session->close();
                return $this->redirect(['update', 'enumid' => $model->enumid, 'enumtype' => $model->enumtype]);
            }

            $valid = $model->validate();
            // var_dump($valid);exit;
            if ($valid) {
                try {
                    if ($model->enumtype == 'job' || $model->enumtype == 'dinas') {
                        $model->amount = (float) str_replace(',', '', $model->amount);
                    }
                    if ($flag = $model->save()) {
                        if ($flag) {
                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return ['success' => true, 'pesan' => 'Data Berhasil Disimpan', 'id' => $model->enumid, 'name' => $model->enumtext_id];
                            }
                            return $this->redirect(['index', 'enumtype' => $model->enumtype]);
                        } else {
                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return ['success' => false, 'pesan' => implode($model->getFirstErrors()), 'id' => $model->enumid, 'name' => $model->enumtext_id];
                            }
                            Yii::$app->session->setFlash('error', $model->getFirstErrors());
                            Yii::$app->session->close();
                            return $this->redirect(['update', 'enumid' => $model->enumid, 'enumtype' => $model->enumtype]);
                        }
                    }
                } catch (Exception $e) {
                    //var_dump($e);
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['success' => false, 'pesan' => $e->getMessage(), 'id' => $model->enumid, 'name' => $model->enumtext_id];
                    }
                    Yii::$app->session->setFlash('error', $model->getFirstErrors());
                    Yii::$app->session->close();
                    return $this->redirect(['update', 'enumid' => $model->enumid, 'enumtype' => $model->enumtype]);
                }
            } else {
                //var_dump($model->getFirstErrors());
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => false, 'pesan' => implode($model->getFirstErrors()), 'id' => $model->enumid, 'name' => $model->enumtext_id];
                }
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
                'isajax' => true,
                'enumtype' => $enumtype,
            ]);
        } else {
            return $this->render('_form', [
                'model' => $model,
                'isajax' => false,
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
    public function actionDelete()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!Yii::$app->request->isPost) {
            return [
                'success' => false,
                'message' => 'Hanya bisa diakses dengan metode POST.'
            ];
        }

        $enumid = Yii::$app->request->post('enumid');

        try {
            $sql = "UPDATE enum 
                SET status = 10,
                    updatedby = :user_id,
                    updatedat = NOW()
                WHERE enumid = :enumid";
            $rowsAffected = Yii::$app->db->createCommand($sql, [
                ':user_id' => Yii::$app->user->id,
                ':enumid' => $enumid
            ])->execute();

            if ($rowsAffected) {
                return [
                    'success' => true,
                    'message' => 'Data berhasil dihapus (soft delete).'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal menghapus data.'
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }

    public function actionCancel($id, $enumtype)
    {
        $receipt = Receipt::find()->where(['<>', 'status', 10])->andWhere(['enumid' => $id]);
        if ($receipt != null) {
            $model = $this->findModel($id)->updateAll(['status' => 10], "enumid = '" . $id . "'");
        }
        return $this->redirect(['index', 'enumtype' => $enumtype]);
    }

    public function actionList()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = Yii::$app->user->id;
        $params = Yii::$app->request->queryParams;

        $enumtype = $params['enumtype'] ?? '';
        $search = $params['search'] ?? '';
        $start = (int) ($params['start'] ?? 0);
        $length = (int) ($params['length'] ?? 10);
        $refid = $params['refid'] ?? '';
        $refid_country = $params['refid_country'] ?? '';
        $refid_state = $params['refid_state'] ?? '';
        $refid_city = $params['refid_city'] ?? '';
        $refid_category = $params['refid_category'] ?? '';
        $refid_subcategory = $params['refid_subcategory'] ?? '';
        $unitFilter = $params['unit'] ?? '';
        $crewtypeid = $params['crewtypeid'] ?? '';
        $for = $params['for'] ?? '';
        $draw = $params['draw'] ?? 1;

        $sortcolumn = $params['order'][0]['column'] ?? 0;
        $ordercolumn = $params['columns'][$sortcolumn]['data'] ?? 'a.enumno';
        $columnorder = $params['order'][0]['dir'] ?? 'ASC';

        if ($sortcolumn == "0") {
            $orderdefault = " a.enum_code_id desc";
        } else {
            $orderdefault = $ordercolumn . " " . $columnorder;
        }

        $sql =
            "SELECT 
            a.enumid, a.enumtype, 
            CASE WHEN coalesce(a.enum_code_id,'') <> '' THEN a.enum_code_id || ' - ' || a.enumtext_id  
            ELSE a.enumtext_id
            END as enumtext_id,
            a.amount, a.iscut, a.refid, a.refid2, a.refid3, a.enum_code_id, a.enum_desc, a.amount2,
            CASE WHEN a.iscut = 1 THEN 'Ya' ELSE 'Tidak' END AS iscut_text,
            r1.enumtext_id AS reftext,
            r2.enumtext_id AS reftext2,
            r3.enumtext_id AS reftext3,
            COALESCE(c1.coa_name_id, '') AS coaselltext,
            COALESCE(c2.coa_name_id, '') AS coabuytext
        FROM enum a
        LEFT JOIN enum r1 ON a.refid = r1.enumid
        LEFT JOIN enum r2 ON a.refid2 = r2.enumid 
        LEFT JOIN enum r3 ON a.refid3 = r3.enumid 
        LEFT JOIN coas c1 ON a.refid2 = c1.coa_id::text
        LEFT JOIN coas c2 ON a.refid3 = c2.coa_id::text
        WHERE a.enumtype = '$enumtype' AND a.status <> 10";

        $filter = "";

        if (!empty($refid)) {
            $filter .= " AND a.refid = '$refid' ";
        }

        if (isset($unitFilter) && $unitFilter !== '') {
            $filter .= " AND a.enumid = '$unitFilter'";
        }

        if ($enumtype == 'state' && !empty($refid_country)) {
            $filter .= " AND a.refid = '$refid_country'";
        }
        if ($enumtype == 'city' && !empty($refid_state)) {
            $filter .= " AND a.refid = '$refid_state'";
        }
        if ($enumtype == 'district' && !empty($refid_city)) {
            $filter .= " AND a.refid = '$refid_city'";
        }

        if (in_array($enumtype, ['spec', 'subcategory']) && !empty($refid_category)) {
            $filter .= " AND a.refid = '$refid_category'";
        }

        if ($enumtype == 'type' && !empty($refid_subcategory)) {
            $filter .= " AND a.refid = '$refid_subcategory'";
        }

        if (!empty($search)) {
            $filter .= " AND (
            a.enumtext_id ILIKE '%$search%'
            OR a.enum_code_id ILIKE '%$search%'
            )";
        }

        $sqlcount = "SELECT count(*) as allcount FROM (" . $sql . $filter . ") as temp ";

        if ($for == 'select2') {
            $page = (int) ($params['page'] ?? 1);
            $limit = (int) ($params['limit'] ?? 5);
            $offset = ($page - 1) * $limit;

            $sqlall = $sql . $filter . " ORDER BY a.enumtext_id ASC LIMIT $limit OFFSET $offset";
        } else {
            $limit = $length;
            $offset = $start;

            $sqlall = $sql . $filter . " ORDER BY " . $orderdefault . " LIMIT " . $limit . " OFFSET " . $offset;
        }

        $rows = \Yii::$app->db->createCommand($sqlall)->queryAll();
        $totalCount = (int) Yii::$app->db->createCommand($sqlcount)->queryScalar();

        return [
            'data' => $rows ?: [],
            "draw" => intval($draw),
            "recordsTotal" => $totalCount,
            "recordsFiltered" => $totalCount,
            'pagination' => [
                'more' => ($offset + $limit) < $totalCount,
            ],
        ];
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

    public function actionEnumlist()
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
            $enumtype = 'master.fd';
        } elseif ($enumtype == 'produktype.fb') {
            $enumtype = 'master.fb';
        } elseif ($enumtype == 'produktype.ac') {
            $enumtype = 'master.ac';
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
    public function actionSave()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Ambil parameter dengan nama yang benar sesuai JS
        $enumId = Yii::$app->request->post('enumid');
        $enumName = Yii::$app->request->post('enumtext_id');
        $enumType = Yii::$app->request->post('enumtype');
        $enumCode = Yii::$app->request->post('enum_code_id');

        // Validasi input
        if (empty($enumName)) {
            return [
                'success' => false,
                'message' => 'Name is required',
                'errors' => ['enumtext_id' => ['Name cannot be blank']]
            ];
        }

        if (empty($enumType)) {
            return [
                'success' => false,
                'message' => 'Type is required',
                'errors' => ['enumtype' => ['Type cannot be blank']]
            ];
        }

        // Update atau Create
        if ($enumId) {
            $model = Enum::findOne($enumId);
            if (!$model) {
                return ['success' => false, 'message' => 'Data not found'];
            }
        } else {
            $model = new Enum();
            // Generate UUID untuk data baru
            $id = \Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
            $model->enumid = $id;
            $model->enumtype = $enumType;
        }

        // Set attributes
        $model->enumtext_id = $enumName;
        if (!empty($enumCode)) {
            $model->enum_code_id = $enumCode;
        }

        // Validasi manual untuk duplikat
        $exists = Enum::find()
            ->where(['enumtype' => $enumType])
            ->andWhere(['enumtext_id' => $enumName])
            ->andWhere(['<>', 'enumid', $model->enumid])
            ->andWhere(['<>', 'status', 10])
            ->exists();

        if ($exists) {
            return [
                'success' => false,
                'message' => 'Data with the same name already exists'
            ];
        }

        // Simpan dengan validasi
        if ($model->validate()) {
            if ($model->save(false)) {
                return [
                    'success' => true,
                    'message' => $enumId ? 'Data updated successfully' : 'Data saved successfully',
                    'id' => $model->enumid,
                    'data' => [
                        'enumid' => $model->enumid,
                        'enumtext_id' => $model->enumtext_id,
                        'enum_code_id' => $model->enum_code_id
                    ]
                ];
            }
        }

        // Jika gagal, kembalikan error detail
        return [
            'success' => false,
            'message' => 'Failed to save: ' . implode(', ', $model->getFirstErrors()),
            'errors' => $model->getErrors()
        ];
    }

}
