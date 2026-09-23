<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Kategori;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\db\Query;

/**
 * KategoriController implements the CRUD actions for Kategori model.
 */
class KategoriController extends Controller
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
        }
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
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'list', 'getno', 'addkategori', 'getkategori', 'editkategori'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return true;
                        }
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("kategori", "lihat"));
                        }
                    ],
                    [
                        'actions' => ['create', 'addkategori'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("kategori", "tambah"));
                        }
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("kategori", "ubah"));
                        }
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("kategori", "hapus"));
                        }
                    ],
                    [
                        'actions' => [
                            'index',
                            'create',
                            'update',
                            'list',
                            'getno',
                            'addkategori',
                            'getkategori',
                            'editkategori'
                        ],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Kategori models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Kategori();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Kategori model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Kategori model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Kategori();
        // $model->status = 1;

        // Get the next code
        $nextCode = $model->nextKode();
        $model->setAttribute('kategorikode', $nextCode);

        if ($model->load(Yii::$app->request->post())) {
            $valid = $model->validate(false);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if ($flag) {
                            $transaction->commit();

                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return [
                                    'success' => true,
                                    'pesan' => 'Data Berhasil Disimpan',
                                    'id' => $model->kategoriid,
                                    'name' => $model->kategorinama
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
                                    'id' => $model->kategoriid,
                                    'name' => $model->kategorinama
                                ];
                            }
                        }
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();

                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return [
                            'success' => false,
                            'pesan' => $e->getMessage(),
                            'id' => $model->kategoriid,
                            'name' => $model->kategorinama
                        ];
                    }
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'success' => false,
                        'pesan' => implode("", $model->getFirstErrors()),
                        'id' => $model->kategoriid,
                        'name' => $model->kategorinama
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

    /**
     * Handles add kategori from modal
     * Used in Produk form modal
     */
    public function actionAddkategori()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Get POST data
        $name = Yii::$app->request->post('name');
        $code = Yii::$app->request->post('code');

        // Validate inputs
        if (empty($name)) {
            return [
                'success' => false,
                'message' => 'Nama kategori harus diisi.'
            ];
        }

        try {
            $model = new Kategori();
            $model->kategorinama = $name;

            // If code provided, use it
            if (!empty($code)) {
                $model->kategorikode = $code;
            } else {
                // Generate code
                $model->kategorikode = $model->nextKode();
                // var_dump($model->kategorikode);
                // die;
            }

            // $model->status = 1;

            // Validate and save
            if ($model->validate() && $model->save()) {
                return [
                    'success' => true,
                    'message' => 'Kategori berhasil disimpan',
                    'data' => [
                        'kategoriid' => $model->kategoriid,
                        'kategorikode' => $model->kategorikode,
                        'kategorinama' => $model->kategorinama
                    ]
                ];
            } else {
                // Log the errors for debugging
                Yii::error('Error saving category: ' . json_encode($model->errors), 'kategori');

                return [
                    'success' => false,
                    'message' => 'Gagal menyimpan kategori: ' . implode(', ', $model->getFirstErrors())
                ];
            }
        } catch (\Exception $e) {
            // Log the exception for debugging
            Yii::error('Exception when saving category: ' . $e->getMessage(), 'kategori');

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }

    public function actionGetkategori()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // if (!\Yii::$app->request->isAjax) {
        //     return [
        //         'success' => false,
        //         'message' => 'Invalid request method'
        //     ];
        // }

        $id = \Yii::$app->request->get('id');

        if (!$id) {
            return [
                'success' => false,
                'message' => 'ID kategori tidak ditemukan'
            ];
        }

        $model = Kategori::findOne($id);

        if (!$model) {
            return [
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ];
        }

        return [
            'success' => true,
            'data' => [
                'kategoriid' => $model->kategoriid,
                'kategorinama' => $model->kategorinama,
                'kategoricode' => $model->kategorikode,
            ]
        ];
    }

    /**
     * Updates an existing Kategori model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $valid = $model->validate(false);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if ($flag) {
                            $transaction->commit();

                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return [
                                    'success' => true,
                                    'pesan' => 'Data Berhasil Diperbarui',
                                    'id' => $model->kategoriid,
                                    'name' => $model->kategorinama
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
                                    'id' => $model->kategoriid,
                                    'name' => $model->kategorinama
                                ];
                            }
                        }
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();

                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return [
                            'success' => false,
                            'pesan' => $e->getMessage(),
                            'id' => $model->kategoriid,
                            'name' => $model->kategorinama
                        ];
                    }
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'success' => false,
                        'pesan' => implode("", $model->getFirstErrors()),
                        'id' => $model->kategoriid,
                        'name' => $model->kategorinama
                    ];
                }
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
                'isajax' => "true"
            ]);
        } else {
            return $this->render('_form', [
                'model' => $model,
                'isajax' => "false"
            ]);
        }
    }

    public function actionEditkategori()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Get POST data
        $id = Yii::$app->request->post('id');
        $name = Yii::$app->request->post('name');
        $code = Yii::$app->request->post('code');

        // Validate inputs
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
            // Find existing model
            $model = $this->findModel($id);
            $model->kategorinama = $name;

            // If code provided, use it
            if (!empty($code)) {
                $model->kategorikode = $code;
            }

            // Validate and save
            if ($model->validate() && $model->save()) {
                return [
                    'success' => true,
                    'message' => 'Kategori berhasil diperbarui',
                    'data' => [
                        'kategoriid' => $model->kategoriid,
                        'kategorikode' => $model->kategorikode,
                        'kategorinama' => $model->kategorinama
                    ]
                ];
            } else {
                // Log the errors for debugging
                Yii::error('Error updating category: ' . json_encode($model->errors), 'kategori');

                return [
                    'success' => false,
                    'message' => 'Gagal memperbarui kategori: ' . implode(', ', $model->getFirstErrors())
                ];
            }
        } catch (\Exception $e) {
            // Log the exception for debugging
            Yii::error('Exception when updating category: ' . $e->getMessage(), 'kategori');

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Deletes an existing Kategori model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->post('id');
        if (!$id) {
            return $this->asJson(['success' => false, 'message' => 'ID tidak ditemukan!']);
        }

        $model = $this->findModel($id);
        // Soft delete - set status to 0 instead of deleting
        // $model->status = 0;

        if ($model->delete()) {
            return $this->asJson(['success' => true]);
        } else {
            return $this->asJson(['success' => false, 'message' => 'Gagal menghapus']);
        }
    }

    /**
     * Finds the Kategori model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Kategori the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Kategori::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionList()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->request->queryParams;

        $search = $params['search'] ?? '';

        // Ambil data sorting dari DataTables
        $sortcolumn = $params['order'][0]['column'] ?? 0;
        $ordercolumn = $params['columns'][$sortcolumn]['data'] ?? 'kategorikode';
        $columnorder = $params['order'][0]['dir'] ?? 'ASC';

        // Validasi sorting biar aman dari SQL Injection
        $allowedColumns = ['kategorikode', 'kategorinama'];
        if (!in_array($ordercolumn, $allowedColumns)) {
            $ordercolumn = 'kategorikode'; // Default sorting
        }

        $query = "SELECT k.*
                 FROM kategori k";

        // Buat kondisi filter
        if (isset($search) && $search !== "") {
            $query .= " AND (
                k.kategorinama ILIKE '%$search%' OR
                k.kategorikode ILIKE '%$search%'
            )";
        }

        // Query akhir dengan ORDER BY
        $query .= " ORDER BY " . $ordercolumn . " " . $columnorder;

        $data = Yii::$app->db->createCommand($query)->queryAll();
        return ['data' => $data ?: []]; // Format untuk datatable
    }

    public function actionGetno()
    {
        try {
            $nextKode = (new Kategori())->nextKode();
            $angkaKode = preg_replace('/\D/', '', $nextKode); // Ambil angka saja
        } catch (Exception $e) {
            $nextKode = "K-001";
            $angkaKode = "001";
        }

        return $this->asJson([
            "no" => $nextKode,
            "regno" => $angkaKode
        ]);
    }
}
