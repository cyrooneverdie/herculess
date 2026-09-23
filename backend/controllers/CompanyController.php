<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Company;
use common\models\User;
use common\models\Coas;
use common\models\Category;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\db\Query;

/**
 * CompanyController implements the CRUD actions for Company model.
 */
class CompanyController extends Controller
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
                        'actions' => ['index', 'create', 'update', 'view', 'list', 'getcompany', 'jumlahkaryawanlist'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return true;
                        }
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("company", "lihat"));
                        }
                    ],
                    [
                        'actions' => ['status'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            $roleId = Yii::$app->user->identity->role_id;
                            $roleName = Yii::$app->function->findByField("enum_name", "enums", " and enum_type='users_roles' and enum_no = '$roleId'");
                            return in_array($roleName, ['Superadmin', 'Admin']);
                        }
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("company", "tambah"));
                        }
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("company", "ubah"));
                        }
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("company", "hapus"));
                        }
                    ],
                    [
                        'actions' => [
                            'index',
                            'create',
                            'update',
                            'list',
                            'kategorilist',
                            'delete',
                            'getno',
                            'getcompany',
                            'jumlahkaryawanlist',
                            'joincompany'
                        ],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }

    public function actionGetcompany()
    {
        $companyData = Yii::$app->session->get('company_data');
        $companyList = Yii::$app->session->get('company_list');

        $roleName = Yii::$app->session->get('role_name') ?? 'User'; // Ganti default sesuai kebutuhan

        $model = $companyData ?? ['nama_perusahaan' => 'No Company'];
        $data = $companyList ?? [];

        return $this->asJson([
            'success' => true,
            'html' => $this->renderPartial('@backend/views/company/company', [
                'model' => $model,
                'data' => $data,
                'roleName' => $roleName
            ]),
        ]);
    }

    /**
     * Lists all Company models.
     * @return mixed
     */
    // public function actionIndex()
    // {
    //     $id = Yii::$app->user->id;

    //     // Dapatkan role user
    //     $roleId = Yii::$app->user->identity->role_id;
    //     $roleName = Yii::$app->function->findByField("enum_name", "enums", " and enum_type='users_roles' and enum_no = '$roleId'");

    //     // Hapus session list company agar selalu update, tapi JANGAN hapus companyid
    //     Yii::$app->session->remove('company_list');

    //     // Cek apakah dia Superadmin
    //     if ($roleName === 'Superadmin') {
    //         $query = "SELECT * FROM company";
    //     } else {
    //         $query = "SELECT * FROM company WHERE userid='$id' AND status=1";
    //     }

    //     $data = Yii::$app->db->createCommand($query)->queryAll();

    //     Yii::$app->session->set('company_list', $data);

    //     $sessionCompanyId = Yii::$app->session->get('companyid');

    //     if ($sessionCompanyId && in_array($sessionCompanyId, array_column($data, 'companyid'))) {
    //         $model = array_filter($data, function ($company) use ($sessionCompanyId) {
    //             return $company['companyid'] == $sessionCompanyId;
    //         });
    //         $model = array_values($model)[0];
    //     } else {
    //         $model = !empty($data) ? $data[0] : NULL;
    //         Yii::$app->session->set('companyid', $model ? $model['companyid'] : NULL);
    //     }

    //     Yii::$app->session->set('company_data', $model);

    //     if (Yii::$app->request->isAjax) {
    //         return $this->renderPartial('index', [
    //             'model' => $model,
    //             'data' => $data,
    //             'roleName' => $roleName
    //         ]);
    //     }

    //     return $this->render('index', [
    //         'model' => $model,
    //         'data' => $data,
    //         'roleName' => $roleName
    //     ]);
    // }

    public function actionIndex($companyid = null)
    {
        $userId = Yii::$app->user->id;
        $session = Yii::$app->session;

        $roleId = Yii::$app->user->identity->role_id;
        $roleName = Yii::$app->function->findByField("enum_name", "enums", " and enum_type='users_roles' and enum_no = '$roleId'");

        $session->remove('company_list');

        if ($roleName === 'Superadmin') {
            $query = "SELECT * FROM company";
        } else {
            $query = "SELECT * FROM company WHERE userid = '$userId' AND status = 1";
        }

        $data = Yii::$app->db->createCommand($query)->queryAll();
        $session->set('company_list', $data);

        if (!$companyid) {
            $companyid = Yii::$app->db->createCommand("SELECT companyid FROM users WHERE userid = '$userId'")->queryScalar();
        }

        if ($companyid) {
            $query = "SELECT * FROM company WHERE userid = '$userId' AND companyid = '$companyid'";
            $company = Yii::$app->db->createCommand($query)->queryOne();

            if ($company) {
                $session->set('companyid', $company['companyid']);
                $session->set('company_data', $company);

                Yii::$app->db->createCommand("UPDATE users SET companyid = '$companyid' WHERE userid = '$userId'")->execute();
            }

            if (Yii::$app->request->get('companyid')) {
                return $this->redirect(['index']);
            }
        }

        $sessionCompanyId = $session->get('companyid');

        if ($sessionCompanyId && in_array($sessionCompanyId, array_column($data, 'companyid'))) {
            $model = array_filter($data, function ($company) use ($sessionCompanyId) {
                return $company['companyid'] == $sessionCompanyId;
            });
            $model = !empty($model) ? array_values($model)[0] : null;
        } else {
            $model = !empty($data) ? $data[0] : null;
            if ($model) {
                $session->set('companyid', $model['companyid']);
                $session->set('company_data', $model);
            }
        }

        $numbercode = Yii::$app->db->createCommand("SELECT * FROM numbertemplates where companyid = '$companyid' AND type = 'contact'")->queryAll();

        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('index', [
                'model' => $model,
                'data' => $data,
                'roleName' => $roleName,
                'numbercode' => $numbercode
            ]);
        }

        return $this->render('index', [
            'model' => $model,
            'data' => $data,
            'roleName' => $roleName,
            'numbercode' => $numbercode
        ]);
    }

    /**
     * Displays a single Company model.
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
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Company();
        $model->status = 1;
        $model->userid = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isPost && isset($_FILES['logo']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
                $file = $_FILES['logo'];

                $uploadPath = Yii::getAlias('@webroot') . '/uploads/company/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $fileName = Yii::$app->security->generateRandomString() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);

                if (move_uploaded_file($file['tmp_name'], $uploadPath . $fileName)) {
                    $model->company_photo = '/uploads/company/' . $fileName;
                }
            } else {
                $model->company_photo = Yii::getAlias('@web') . "/assets/media/logos/rbg.png";
            }

            $valid = $model->validate(false);
            $model->package = 'Free';
            if ($valid) {
                try {
                    if ($flag = $model->save(false)) {
                        if ($flag) {
                            $companyId = $model->companyid;

                            $templateData = Yii::$app->db->createCommand('SELECT * FROM coa_templates')->queryAll();

                            $dataToInsert = [];
                            foreach ($templateData as $row) {
                                $dataToInsert[] = [
                                    'coa_companyid' => $companyId,
                                    'coa_type' => $row['coa_type'],
                                    'coa_no' => $row['coa_no'],
                                    'coa_name_en' => $row['coa_name_en'],
                                    'coa_name_id' => $row['coa_name_id'],
                                    'coa_level' => $row['coa_level'],
                                    'coa_status' => $row['coa_status'],
                                    'coa_category' => $row['coa_category'],
                                ];
                            }

                            Yii::$app->db->createCommand()->batchInsert('coas', ['coa_companyid', 'coa_type', 'coa_no', 'coa_name_en', 'coa_name_id', 'coa_level', 'coa_status', 'coa_category'], $dataToInsert)->execute();

                            Yii::$app->session->set('companyid', $companyId);
                            Yii::$app->session->set('company_data', $model->attributes);

                            $userId = Yii::$app->user->id;
                            $companyId = $model->companyid;

                            Yii::$app->db->createCommand("
                            UPDATE users 
                            SET companyid = '$companyId' 
                            WHERE userid = '$userId'
                        ")->execute();

                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return [
                                    'success' => true,
                                    'pesan' => 'Data Berhasil Disimpan',
                                    'id' => $companyId,
                                    'name' => $model->nama_lengkap
                                ];
                            }

                            return $this->redirect(['index']);
                        }
                    }
                } catch (Exception $e) {
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return [
                            'success' => false,
                            'pesan' => $e->getMessage(),
                            'id' => $model->companyid,
                            'name' => $model->nama_lengkap
                        ];
                    }
                }
            }
        }

        // === PERBAIKAN: gunakan boolean asli, bukan string "true"/"false" ===
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('_form', [
                'dialog' => 1,
                'model' => $model,
                'isajax' => true,
            ]);
        } else {
            return $this->render('_form', [
                'dialog' => 1,
                'model' => $model,
                'isajax' => false,
            ]);
        }
    }

    /**
     * Updates an existing Company model.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $logoRemove = Yii::$app->request->post('logo_remove');
            if ($logoRemove == '1' && !empty($model->company_photo)) {
                $oldPath = Yii::getAlias('@webroot') . $model->company_photo;
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
                $model->company_photo = null;
            }

            if (isset($_FILES['logo']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
                $file = $_FILES['logo'];
                $uploadPath = Yii::getAlias('@webroot') . '/uploads/company/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                if (!empty($model->company_photo)) {
                    $oldPath = Yii::getAlias('@webroot') . $model->company_photo;
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }

                $fileName = Yii::$app->security->generateRandomString() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
                if (move_uploaded_file($file['tmp_name'], $uploadPath . $fileName)) {
                    $model->company_photo = '/uploads/company/' . $fileName;
                }
            }

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
                                    'id' => $model->companyid,
                                    'name' => $model->nama_lengkap
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
                                    'id' => $model->companyid,
                                    'name' => $model->nama_lengkap
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
                            'id' => $model->companyid,
                            'name' => $model->nama_lengkap
                        ];
                    }
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'success' => false,
                        'pesan' => implode("", $model->getFirstErrors()),
                        'id' => $model->companyid,
                        'name' => $model->nama_lengkap
                    ];
                }
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('_form', [
                'dialog' => 1,
                'model' => $model,
                'isajax' => true,
            ]);
        } else {
            return $this->render('_form', [
                'dialog' => 1,
                'model' => $model,
                'isajax' => false,
            ]);
        }
    }

    public function actionJoincompany()
    {
        $companycodejoin = Yii::$app->request->post('companycode');
        $thisuser = User::findOne(Yii::$app->user->id);

        if (Yii::$app->request->isPost && $companycodejoin) {
            $searchcompany = Company::findOne($companycodejoin);

            if ($searchcompany) {
                // Set relasi ke company ID utama
                $thisuser->companyid = $searchcompany->companyid;
                $thisuser->save(false);

                // Duplicate data
                $duplicatecompany = new Company();
                $duplicatecompany->setAttributes($searchcompany->getAttributes([
                    // 'companyid',
                    'nama_lengkap',
                    'nama_perusahaan',
                    'jabatan_perusahaan',
                    'jumlah_karyawan',
                    'email',
                    'nomor_telepon',
                    'subs_id',
                    'status',
                    'package',
                    'type',
                    'tglstartsubs',
                    'tglendsubs',
                    'subsprice',
                    'subs_status',
                    'statuspaid',
                ]));
                $duplicatecompany->userid = Yii::$app->user->id;
                $duplicatecompany->save(false);
                if ($duplicatecompany->save(false)) {
                    if (Yii::$app->request->isAjax) {
                        return $this->asJson(['status' => 'success', 'message' => 'Company joined and duplicated.']);
                    } else {
                        Yii::$app->session->setFlash('success', 'Berhasil join dan salin company.');
                        return $this->redirect(['company/index']);
                    }
                } else {
                    if (Yii::$app->request->isAjax) {
                        return $this->asJson(['status' => 'error', 'errors' => $duplicatecompany->errors]);
                    }
                }
            } else {
                return $this->asJson(['status' => 'error', 'message' => 'Kode perusahaan tidak ditemukan.']);
            }
        }

        return $this->render('index', ['isajax' => Yii::$app->request->isAjax ? "true" : "false"]);
    }


    public function actionStatus($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $company = Company::findOne($id);
        if (!$company) {
            return ['success' => false, 'message' => 'Company not found'];
        }
        // var_dump($company->status);die;
        try {
            // Toggle between active (1) and inactive (10) status
            $company->status = ($company->status == 1) ? 10 : 1;

            if ($company->save(false)) {
                return ['success' => true, 'message' => 'Status updated successfully'];
            } else {
                return ['success' => false, 'message' => 'Failed to update status'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Deletes an existing Company model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionDelete()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        if (!$id) {
            return ['success' => false, 'message' => 'ID tidak ditemukan!'];
        }

        $model = $this->findModel($id);
        if (!$model) {
            return ['success' => false, 'message' => 'Data tidak ditemukan!'];
        }

        $userId = Yii::$app->user->id;
        $session = Yii::$app->session;

        $model->status = 10;

        if ($model->save(false)) {
            if ($session->get('companyid') == $id) {
                $newCompany = Company::find()
                    ->where(['userid' => $userId, 'status' => 1])
                    ->orderBy(['companyid' => SORT_ASC])
                    ->one();

                if ($newCompany) {
                    // Update session dengan perusahaan baru
                    $session->set('companyid', $newCompany->companyid);
                    $session->set('company_data', $newCompany->attributes);
                } else {
                    // Jika tidak ada perusahaan lain, reset session
                    $session->remove('companyid');
                    $session->remove('company_data');
                }
            }

            return ['success' => true];
        }

        return ['success' => false, 'message' => 'Gagal menghapus!'];
    }

    /**
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Company::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionJumlahkaryawanlist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $q = Yii::$app->request->get('q', null); // Aman dari Undefined Index
        // $id = Yii::$app->request->get('id', null);
        $userid = Yii::$app->user->id;
        $listBahasa = "SELECT lang FROM users WHERE userid = '$userid'";
        $bahasa = Yii::$app->db->createCommand($listBahasa)->queryScalar();

        $params = [];

        //query ngambil dari tabel enums aja
        $sql = "SELECT DISTINCT e.enumid as id, e.enumno as no,
                    CASE
                        WHEN '$bahasa' = 'id'THEN
                        COALESCE(enumtext_id)
                        ELSE enumtext_en
                    END as text
                FROM enum e
                WHERE e.enumtype = 'jumlahkaryawan'";

        if ($q !== null) {
            $sql .= " AND LOWER(e.enumtext_en) LIKE :q"; // Pencarian berdasarkan enum_name
            $params[':q'] = "%" . strtolower($q) . "%";
        }

        $sql .= " ORDER BY enumno ASC LIMIT 10"; // Tambahkan limit untuk Select2
        $data = Yii::$app->db->createCommand($sql, $params)->queryAll();

        return ['results' => array_values($data)];
    }

    public function actionList()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = Yii::$app->user->id;

        Yii::$app->session->remove('company_data');
        Yii::$app->session->remove('company_list');
        Yii::$app->session->remove('companyid');

        $query = "SELECT * FROM company WHERE userid = '$id' AND status <> 10";
        $data = Yii::$app->db->createCommand($query)->queryAll();

        Yii::$app->session->set('company_list', $data);

        $model = !empty($data) ? $data[0] : null;
        Yii::$app->session->set('company_data', $model);

        if ($model) {
            Yii::$app->session->set('companyid', $model['companyid']);
        }

        return [
            'model' => $model,
            'data' => $data ?: []
        ];
    }


    public function actionGetno()
    {
        try {
            $nextKode = (new Company())->nextKode();
            $angkaKode = preg_replace('/\D/', '', $nextKode); // Ambil angka saja
        } catch (Exception $e) {
            $nextKode = "P-00001";
            $angkaKode = "00001";
        }

        return $this->asJson([
            "no" => $nextKode,
            "regno" => $angkaKode
        ]);
    }
}