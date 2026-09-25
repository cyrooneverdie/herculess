<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Coas;
use Exception;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\db\Query;

class CoasController extends Controller
{
    public $successUrl = '';

    public function init()
    {
        parent::init();
        Yii::$app->language = Yii::$app->lang->getLang();
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if ($action->id == 'error') {
                $this->layout = 'error';
            }
            return true;
        } else {
            return false;
        }
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'list', 'create', 'update', 'delete', 'categorylist', 'accountlist', 'subtable', 'massaction', 'addcategory', 'editcategory', 'deletecategory'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return true;
                        }
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return true;
                        }
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return true;
                        }
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return true;
                        }
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return true;
                        }
                    ],
                    [
                        'actions' => [
                            'index',
                            'create',
                            'update',
                            'delete',
                            'list',
                            'categorylist',
                            'accountlist',
                            'subtable',
                            'massaction',
                            'addcategory',
                            'editcategory',
                            'getno'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($companyid = null)
    {
        $model = new Coas();

        $query = Yii::$app->db->createCommand("SELECT * FROM coas ORDER BY coa_no ASC")->queryAll();

        $id = Yii::$app->user->id;

        $session = Yii::$app->session;

        // Hapus session list company agar selalu update, tapi JANGAN hapus companyid
        $session->remove('company_list');

        $query = "SELECT * FROM company WHERE userid = :userid AND status = 1";
        $data = Yii::$app->db->createCommand($query)
            ->bindValue(':userid', $id)
            ->queryAll();
        $session->set('company_list', $data);

        // Ambil companyid terakhir yang disimpan di tabel users
        if (!$companyid) {
            $companyid = Yii::$app->db->createCommand("SELECT companyid FROM users WHERE userid = :userid")
                ->bindValue(':userid', $id)
                ->queryScalar();
        }

        // Jika ada companyid dari dropdown, update session dan database
        if ($companyid) {
            $query = "SELECT * FROM company WHERE userid = :userid AND companyid = :companyid";
            $company = Yii::$app->db->createCommand($query)
                ->bindValue(':userid', $id)
                ->bindValue(':companyid', $companyid)
                ->queryOne();

            if ($company) {
                $session->set('companyid', $company['companyid']);
                $session->set('company_data', $company);

                // Simpan ke tabel users
                Yii::$app->db->createCommand("UPDATE users SET companyid = :companyid WHERE userid = :userid")
                    ->bindValue(':companyid', $company['companyid'])
                    ->bindValue(':userid', $id)
                    ->execute();
            }

            if (Yii::$app->request->get('companyid')) {
                return $this->redirect(['index']);
            }
        }

        if (!$session->has('companyid')) {
            if ($companyid) {
                $company = Yii::$app->db->createCommand("SELECT * FROM company WHERE companyid = :companyid")
                    ->bindValue(':companyid', $companyid)
                    ->queryOne();

                if ($company) {
                    $session->set('companyid', $company['companyid']);
                    $session->set('company_data', $company);
                }
            }
        }

        return $this->render('index', [
            'model' => $model,
            'query' => $query,
        ]);
    }

    public function actionList()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = Yii::$app->user->id;
        $params = Yii::$app->request->queryParams;

        $search = $params['search'] ?? '';
        $category = $params['category'] ?? '';
        $sortcolumn = $params['order'][0]['column'] ?? 0;
        $ordercolumn = $params['columns'][$sortcolumn]['data'] ?? 'c1.coa_no';
        $columnorder = $params['order'][0]['dir'] ?? 'ASC';

        $allowedColumns = ['c1.coa_no', 'c1.coa_name_en', 'c1.coa_name_id'];

        $listBahasa = "SELECT lang FROM users WHERE userid = '$id'";
        $bahasa = Yii::$app->db->createCommand($listBahasa)->queryScalar();

        if (!in_array($ordercolumn, $allowedColumns)) {
            $ordercolumn = 'c1.coa_no';
        }

        $query =
            "SELECT 
                c1.coa_id,
                c1.coa_no,

            CASE
                WHEN c1.coa_category::text = '1' THEN COALESCE(cash.saldo_cash, 0)

                WHEN c1.coa_no = '10100' THEN COALESCE(ar.saldo_ar, 0)

                WHEN c1.coa_no = '40000' THEN COALESCE(rev.saldo_rev, 0)

                WHEN c1.coa_no = '40100' THEN COALESCE(disc.saldo_discount, 0)

                ELSE 0
            END AS saldo,

            CASE
                WHEN '$bahasa' = 'id' THEN COALESCE(c1.coa_name_id, c1.coa_name_en)
                ELSE COALESCE(c1.coa_name_en, c1.coa_name_id)
            END AS coa_name,

            CASE
                WHEN '$bahasa' = 'id' THEN COALESCE(c2.coa_name_id, c2.coa_name_en)
                ELSE COALESCE(c2.coa_name_en, c2.coa_name_id)
            END AS coa_category
        FROM coas c1

        LEFT JOIN (
            SELECT 
                k.accountid,
                SUM(
                    CASE 
                        WHEN k.type = 1 THEN COALESCE(kd.value, 0) 
                        WHEN k.type IN (0, 2) OR (k.type = 3 AND k.actiontype = '1') THEN -COALESCE(kd.value, 0)
                        ELSE 0 
                    END
                ) AS saldo_cash
            FROM cashs k    
            JOIN cashdetails kd ON kd.cashid = k.cashid
            WHERE k.status <> 10 AND kd.status <> 10 
            GROUP BY k.accountid
        ) cash ON CAST(cash.accountid AS text) = CAST(c1.coa_id AS text)

        LEFT JOIN (
            SELECT 
                COALESCE((
                    SELECT SUM(t.grandtotal) 
                    FROM trans t 
                    LEFT JOIN trans o ON o.tranid = t.refid
                    WHERE t.trantype = 'sales/invoice' 
                    AND t.status NOT IN ('5', '10') 
                    AND (o.status IS NULL OR o.status NOT IN ('5', '10'))
                ), 0)
                -
                COALESCE((
                    SELECT SUM(
                        CASE 
                            WHEN k.type IN (1, 4) THEN COALESCE(kd.value, 0)
                            ELSE 0
                        END
                    )
                    FROM cashs k
                    JOIN cashdetails kd ON kd.cashid = k.cashid
                    WHERE k.status <> 10 AND kd.status <> 10 
                    AND CAST(kd.accountid AS text) = (
                        SELECT CAST(coa_id AS text) 
                        FROM coas 
                        WHERE coa_no = '10100' 
                        LIMIT 1
                    )
                ), 0)
            AS saldo_ar
        ) ar ON c1.coa_no = '10100'

        LEFT JOIN (
            SELECT SUM(t.grandtotal) + COALESCE((
                SELECT SUM(kd.value)
                FROM cashs k
                JOIN cashdetails kd ON kd.cashid = k.cashid
                WHERE k.status <> 10 AND kd.status <> 10
                AND CAST(kd.accountid AS text) = (
                    SELECT CAST(coa_id AS text)
                    FROM coas
                    WHERE coa_no = '40000'
                    LIMIT 1
                )
            ), 0) AS saldo_rev
            FROM trans t
            LEFT JOIN trans o ON o.tranid = t.refid AND o.trantype = 'sales/order'
            WHERE t.trantype = 'sales/invoice' AND t.status NOT IN ('5', '10') AND o.status NOT IN ('5', '10')
        ) rev ON c1.coa_no = '40000'

        LEFT JOIN (
            SELECT SUM(t.disc) + COALESCE((
                SELECT SUM(kd.value)
                FROM cashs k
                JOIN cashdetails kd ON kd.cashid = k.cashid
                WHERE k.status <> 10 AND kd.status <> 10
                AND CAST(kd.accountid AS text) = (
                    SELECT CAST(coa_id AS text)
                    FROM coas
                    WHERE coa_no = '40100'
                    LIMIT 1
                )
            ), 0) AS saldo_discount
            FROM trans t 
            LEFT JOIN trans o ON o.tranid = t.refid AND o.trantype = 'sales/order'
            WHERE t.trantype = 'sales/invoice' AND t.status NOT IN ('5', '10') AND o.status NOT IN ('5', '10')
        ) disc ON c1.coa_no = '40100'

        LEFT JOIN (
            SELECT DISTINCT ON (coa_no) coa_no, coa_name_id, coa_name_en 
            FROM coas 
            WHERE coa_level = 1 AND coa_status <> 10
        ) c2 ON CAST(c1.coa_category AS text) = CAST(c2.coa_no AS text)

        WHERE c1.coa_status <> 10 AND c1.coa_type <> 'Category'
    ";
        $filter = "";

        if (isset($category) && $category !== '') {
            $filter .= " AND (CAST(c1.coa_no AS text) = '$category' OR CAST(c1.coa_category AS text) = '$category')";
        }

        if (isset($search) && $search !== '') {
            $isNumeric = is_numeric($search);
            if ($isNumeric) {
                $filter .= " AND (CAST(c1.coa_no AS text) = '$search' OR CAST(c1.coa_category AS text) = '$search')";
            } else {
                $filter .= " AND (c1.coa_name_en ILIKE '%$search%' OR c1.coa_name_id ILIKE '%$search%')";
            }
        }

        $orderBy = " ORDER BY " . $ordercolumn . " " . $columnorder;

        $sqlFinal = $query . $filter . $orderBy;

        try {
            $data = Yii::$app->db->createCommand($sqlFinal)->queryAll();
        } catch (\Exception $e) {
            Yii::error("Error ActionList: " . $e->getMessage(), 'application');
            return [
                'data' => [],
                'error' => $e->getMessage()
            ];
        }

        return [
            'data' => $data ?: []
        ];
    }
    public function actionCategorylist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $q = Yii::$app->request->get('q', null);
        $id = Yii::$app->user->id;
        $companyid = Yii::$app->session->get('companyid');

        $listBahasa = "SELECT lang FROM users WHERE userid = '$id'";
        $bahasa = Yii::$app->db->createCommand($listBahasa)->queryScalar();

        $whereSearch = "";
        if (!empty($q)) {
            $whereSearch = " AND (coa_name_id ILIKE '%$q%' 
            OR coa_name_en ILIKE '%$q%' OR coa_no ILIKE '%$q%') ";
        }

        $sql =
            "SELECT coa_id AS id, coa_no AS no,
                CASE
                    WHEN '$bahasa' = 'id' THEN
                        COALESCE(coa_name_id, coa_name_en)
                    ELSE COALESCE(coa_name_en, coa_name_id)
                END AS text
            FROM coas
            WHERE coa_level = 1 AND coa_companyid = '$companyid' $whereSearch
            ORDER BY coa_no ASC";

        $data = Yii::$app->db->createCommand($sql)->queryAll();

        return [
            'results' => array_map(function ($item) {
                return [
                    'id' => $item['no'],
                    'text' => $item['text'],
                ];
            }, $data),
        ];
    }
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $categoryName = '';
        if (!empty($model->coa_category)) {
            $userId = Yii::$app->user->id;
            $companyId = Yii::$app->session->get('companyid');

            $listBahasa = "SELECT lang FROM users WHERE userid = '$userId'";
            $bahasa = Yii::$app->db->createCommand($listBahasa)->queryScalar();

            $catSql = "SELECT 
                        CASE
                            WHEN '$bahasa' = 'id' THEN COALESCE(coa_name_id, coa_name_en)
                            ELSE COALESCE(coa_name_en, coa_name_id)
                        END AS category_name
                       FROM coas 
                       WHERE coa_no = '{$model->coa_category}' AND coa_level = 1 AND coa_companyid = '$companyId'";

            $categoryName = Yii::$app->db->createCommand($catSql)->queryScalar();
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->coa_id === '') {
                $directCoaID = Yii::$app->request->post('coa_id');
                $model->coa_id = !empty($directCoaID) ? $directCoaID : $model->coa_id;
            }

            $valid = $model->validate(false);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        $transaction->commit();

                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return [
                                'success' => true,
                                'pesan' => 'Data Berhasil Diperbarui',
                                'id' => $model->coa_id,
                                'name' => $model->coa_name_id
                            ];
                        }

                        return $this->redirect(['index']);
                    } else {
                        $transaction->rollBack();

                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return [
                                'success' => false,
                                'pesan' => implode("<br/>(X) ", $model->getFirstErrors()),
                                'id' => $model->coa_id,
                                'name' => $model->coa_name_id
                            ];
                        }
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();

                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return [
                            'success' => false,
                            'pesan' => $e->getMessage(),
                            'id' => $model->coa_id,
                            'name' => $model->coa_name_id
                        ];
                    }
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'success' => false,
                        'pesan' => implode("", $model->getFirstErrors()),
                        'id' => $model->coa_id,
                        'name' => $model->coa_name_id
                    ];
                }
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
                'categoryName' => $categoryName,
                'isajax' => "true"
            ]);
        } else {
            return $this->render('_form', [
                'model' => $model,
                'categoryName' => $categoryName,
                'isajax' => "false"
            ]);
        }
    }
    public function actionCreate()
    {
        $model = new Coas();
        $model->coa_status = 1;

        if ($model->load(Yii::$app->request->post())) {
            $valid = $model->validate(false);

            if (!empty(Yii::$app->request->post('coa_refid'))) {
                $directRefId = Yii::$app->request->post('coa_refid');

                $sql = "SELECT coa_level FROM coas WHERE coa_id = '$directRefId'";
                $results = Yii::$app->db->createCommand($sql)->queryAll();

                // var_dump($results);exit;
                if ($results) {
                    foreach ($results as $result) {
                        $model->coa_level = $result['coa_level'] + 1;
                    }
                }
            } else {
                $model->coa_level = 2;
            }

            // var_dump($model); exit;
            if (!empty($model->coa_category)) {
                $directCategoryId = Yii::$app->request->post('coa_category');
                $model->coa_category = !empty($directCategoryId) ? $directCategoryId : $model->coa_category;
            }
            // var_dump($_POST); die;
            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if (isset($_POST['coa_refid'])) {
                        $model->coa_refid = $_POST['coa_refid'];
                    }
                    $model->coa_category = $_POST['coa_category'];
                    if ($flag = $model->save(false)) {
                        $transaction->commit();
                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return [
                                'success' => true,
                                'pesan' => 'Data Berhasil Disimpan',
                                'id' => $model->coa_id,
                                'name' => $model->coa_name_id,
                                'debug_data' => $model->attributes
                            ];
                        }

                        return $this->redirect(['index']);
                    } else {
                        $transaction->rollBack();

                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return [
                                'success' => false,
                                'pesan' => implode("<br/>(X) ", $model->getFirstErrors())
                            ];
                        }
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return [
                            'success' => false,
                            'pesan' => $e->getMessage()
                        ];
                    }
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'success' => false,
                        'pesan' => implode("", $model->getFirstErrors())
                    ];
                }
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'dialog' => 1,
                'model' => $model,
                'isajax' => "true",
            ]);
        } else {
            return $this->render('_form', [
                'dialog' => 1,
                'model' => $model,
                'isajax' => "false",
            ]);
        }
    }
    public function actionAccountlist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $q = Yii::$app->request->get('q', null);
        $id = Yii::$app->user->id;
        $companyid = Yii::$app->session->get('companyid');
        $coa_no = Yii::$app->request->get('no', null);

        $listBahasa = "SELECT lang FROM users WHERE userid = '$id'";
        $bahasa = Yii::$app->db->createCommand($listBahasa)->queryScalar();

        $sql = "SELECT coa_id AS id, coa_no AS no,
                CASE
                    WHEN '$bahasa' = 'id' THEN
                        COALESCE(coa_name_id, coa_name_en)
                    ELSE COALESCE(coa_name_en, coa_name_id)
                END AS text,
                coa_level AS level
                FROM coas
                WHERE coa_companyid = :companyid AND coa_status = 1 
                AND coa_category = :coa_no AND coa_level NOT IN (1, 10)
                ORDER BY coa_no ASC";

        $data = Yii::$app->db->createCommand($sql)
            ->bindValue(':companyid', $companyid)
            ->bindValue(':coa_no', $coa_no)
            ->queryAll();

        return [
            'results' => array_map(function ($item) {
                return [
                    'id' => $item['id'],
                    'text' => $item['no'] . ' - ' . $item['text'],
                    'level' => $item['level'],
                ];
            }, $data),
        ];
    }

    protected function findModel($id)
    {
        if (($model = Coas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*
    // Versi lama yang sebelumnya dipakai (disimpan sebagai komentar untuk referensi):
    // public function actionMassaction()
    // {
    //     Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    //     $ids = Yii::$app->request->post('ids');
    //     $action = Yii::$app->request->post('action');
    //     $status = Yii::$app->request->post('status');
    //     $sql = "UPDATE coas SET coa_status = :status,
    //         updated_by = :user_id,
    //         updated_at = NOW()
    //         WHERE coa_id IN ($ids)";
    //     $rowsAffected = Yii::$app->db->createCommand($sql, [
    //         ':status' => $status,
    //         ':user_id' => Yii::$app->user->id,
    //     ])->execute();
    //     return [
    //         'success' => true,
    //         'message' => 'Data berhasil dihapus!',
    //         'rows_affected' => $rowsAffected,
    //     ];
    // }
    */

    public function actionMassaction()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $ids = Yii::$app->request->post('ids', []);
        $action = Yii::$app->request->post('action', 'delete');
        $status = (int) Yii::$app->request->post('status', 10);

        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $ids = array_values(array_filter(array_map(function ($id) {
            $id = trim((string) $id);
            return $id !== '' ? $id : null;
        }, $ids)));

        if (empty($ids)) {
            return [
                'success' => false,
                'message' => 'Tidak ada data yang dipilih.',
                'error' => true,
            ];
        }

        try {
            $rowsAffected = Yii::$app->db->createCommand()
                ->update(
                    'coas',
                    [
                        'coa_status' => $status,
                        'updated_by' => Yii::$app->user->id,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ],
                    ['in', 'coa_id', $ids]
                )
                ->execute();

            if ($rowsAffected <= 0) {
                return [
                    'success' => false,
                    'message' => 'Tidak ada data yang terhapus. Cek ID data atau statusnya.',
                    'rows_affected' => 0,
                    'error' => true,
                ];
            }

            return [
                'success' => true,
                'message' => $action === 'delete' ? 'Data berhasil dihapus!' : 'Data berhasil diproses!',
                'rows_affected' => $rowsAffected,
                'action' => $action,
                'status' => $status,
            ];
        } catch (\Exception $e) {
            Yii::error('Error in mass action: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'error' => true,
            ];
        }
    }
    public function actionSubtable()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data_id = Yii::$app->request->get('id');
        $id = Yii::$app->user->id;

        $listBahasa = "SELECT lang FROM users WHERE userid = '$id'";
        $bahasa = Yii::$app->db->createCommand($listBahasa)->queryScalar();

        $data_level = "SELECT coa_level + 1 FROM coas WHERE coa_id = CAST(:data_id AS UUID)";
        $level = Yii::$app->db->createCommand($data_level, [':data_id' => $data_id])->queryScalar();

        $subdata = Yii::$app->db->createCommand("
            SELECT DISTINCT c1.coa_id AS id, 
                c1.coa_no AS no, 
                c1.coa_level AS level,
                CASE
                    WHEN '$bahasa' = 'id' THEN
                        COALESCE(c1.coa_name_id, c1.coa_name_en)
                    ELSE COALESCE(c1.coa_name_en, c1.coa_name_id)
                END AS coa_name,
                CASE
                    WHEN '$bahasa' = 'id' THEN
                        COALESCE(c2.coa_name_id, c2.coa_name_en)
                    ELSE COALESCE(c2.coa_name_en, c2.coa_name_id)
                END AS coa_category_name,
                c1.coa_refid
            FROM coas c1
            LEFT JOIN coas c2 ON CAST(c1.coa_category AS text) = c2.coa_no
            WHERE c1.coa_companyid = :companyid 
            AND c1.coa_refid = :data_id
            AND c1.coa_level = :level
            AND c1.coa_status = 1
        ", [
            ':data_id' => $data_id,
            ':level' => $level
        ])->queryAll();

        // Return grouped data
        return [
            'data' => $subdata ?: []
        ];
    }



    public function actionAddcategory()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $companyid = Yii::$app->session->get('companyid');

        $name = Yii::$app->request->post('name');

        // var_dump($name);die;
        if (empty($name)) {
            return [
                'success' => false,
                'message' => 'Nama kategori harus diisi.' // Tambahkan Lang
            ];
        }

        if (!empty($name)) {
            $sql = "SELECT coa_no 
                    FROM coas 
                    WHERE coa_level = 1
                    ORDER BY coa_no DESC LIMIT 1";
            $result = Yii::$app->db->createCommand($sql)->queryOne();

            if (is_array($result)) {
                $coa_no = strval($result['coa_no'] + 1);
            } else {
                $coa_no = 1;
            }
        }

        try {
            $model = new Coas();

            $model->coa_name_id = $name;
            $model->coa_no = $coa_no;
            $model->coa_companyid = $companyid;
            $model->coa_level = 1;

            if ($model->validate() && $model->save()) {
                return [
                    'success' => true,
                    'message' => 'Kategori berhasil disimpan.',
                    'data' => [
                        'coa_id' => $model->coa_id,
                        'coa_name_id' => $model->coa_name_id
                    ]
                ];
            } else {
                Yii::error('Error saving category: ' . json_encode($model->errors), 'coas');

                return [
                    'success' => false,
                    'message' => 'Gagal menyimpan kategori: ' . implode(', ', $model->getFirstErrors())
                ];
            }
        } catch (\Exception $e) {
            Yii::error('Exception when saving category: ' . $e->getMessage(), 'kategori');

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }
    public function actionEditcategory()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // var_dump($_POST);die;

        $id = Yii::$app->request->post('id');
        $name = Yii::$app->request->post('name');

        if (empty($id)) {
            return [
                'success' => false,
                'message' => 'ID kategori harus diisi.'
            ];
        }

        if (empty($name)) {
            return [
                'success' => false,
                'message' => 'Nama kategori harus diisi.'
            ];
        }

        try {
            $model = $this->findModel($id);
            $model->coa_name_id = $name;

            if ($model->validate() && $model->save()) {
                return [
                    'success' => true,
                    'message' => 'Kategori berhasil diperbarui',
                    'data' => [
                        'coa_id' => $model->coa_id,
                        'coa_name_id' => $model->coa_name_id
                    ]
                ];
            } else {
                Yii::error('Error updating category: ' . json_encode($model->errors), 'coas');

                return [
                    'success' => false,
                    'message' => 'Gagal memperbarui kategori: ' . implode(', ', $model->getFirstErrors())
                ];
            }
        } catch (\Exception $e) {
            Yii::error('Exception when updating category: ' . $e->getMessage(), 'kategori');

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }

    public function actionDelete()
    {
        $id = Yii::$app->request->post('id');
        if (!$id) {
            return $this->asJson(['success' => false, 'message' => 'ID tidak ditemukan!']);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = $this->findModel($id);

            $model->coa_status = 10;

            if ($model->save(false)) {
                $transaction->commit();
                return $this->asJson(['success' => true, 'message' => 'Data berhasil dihapus id: ' . $id]);
            } else {
                $transaction->rollBack();
                return $this->asJson(['success' => false, 'message' => 'Gagal menghapus']);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->asJson(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function actionDeletecategory()
    {
        $id = Yii::$app->request->post('id');
        if (!$id) {
            return $this->asJson(['success' => false, 'message' => 'ID tidak ditemukan!']);
        }

        $model = $this->findModel($id);



        if ($model->delete()) {
            return $this->asJson(['success' => true]);
        } else {
            return $this->asJson(['success' => false, 'message' => 'Gagal menghapus']);
        }
    }
}
