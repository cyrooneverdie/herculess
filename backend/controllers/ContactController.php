<?php

namespace backend\controllers;

use Yii;
use yii\db\Query;
use yii\helpers\Url;
use common\models\User;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\Contact;
use common\models\Tran;
use common\models\Trandetail;
use common\models\Tranevent;
use common\models\Traneventcrew;
use common\models\Document;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use common\models\Model;
use yii\helpers\ArrayHelper;


use backend\controllers\BaseController;

class ContactController extends BaseController
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
                    'get-dashboard-data' => ['GET'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['view', 'detail'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return (bool) Yii::$app->enum->isaksestype("contacttype", "lihat");
                        }
                    ],
                    // [
                    //     'actions' => ['create', 'createem'],
                    //     'allow' => true,
                    //     'roles' => ['@'],
                    //     'matchCallback' => function () {
                    //         return (bool) Yii::$app->enum->isaksestype("contacttype", "tambah");
                    //     }
                    // ],
                    // [
                    //     'actions' => ['update', 'updateem', 'update2', 'updatestatus'],
                    //     'allow' => true,
                    //     'roles' => ['@'],
                    //     'matchCallback' => function () {
                    //         return (bool) Yii::$app->enum->isaksestype("contacttype", "ubah");
                    //     }
                    // ],
                    // [
                    //     'actions' => ['delete', 'deletemassal'],
                    //     'allow' => true,
                    //     'roles' => ['@'],
                    //     'matchCallback' => function () {
                    //         return (bool) Yii::$app->enum->isaksestype("contacttype", "hapus");
                    //     }
                    // ],
                    [
                        'actions' => [
                            'create',
                            'createem',
                            'index',
                            'list',
                            'personlist',
                            'select',
                            'delete',
                            'deletemassal',
                            'genderlist',
                            'marriedlist',
                            'relilist',
                            'edulist',
                            'typelist',
                            'countrylist',
                            'statelist',
                            'citylist',
                            'distriklist',
                            'subdistriklist',
                            'idtypelist',
                            'joblist',
                            'positionlist',
                            'divisionlist',
                            'getno',
                            'filter',
                            'photo',
                            'photobyfile',
                            'loadref',
                            'crewhistory',
                            'membercard',
                            'update',
                            'updateem',
                            'update2',
                            'updatestatus',
                            'get-dashboard-data'
                        ],
                        'allow' => true,
                        'roles' => ['@'], // Hanya user login yang bisa akses
                    ],
                ],
            ],
        ];
    }
    /**
     * Lists all Contact models.
     * @return mixed
     */
    public function actionProfile($jeniscontact = 0)
    {

        // var_dump("masuk"); die;
        $searchModel = new Contact();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('profile', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'jeniscontact' => $jeniscontact,
        ]);
    }

    /**
     * Displays a single Contact model.
     * @param string $id
     * @return mixed
     */
    public function actionSettings2($jeniscontact = 0)
    {
        return $this->render('settings', [
            //'model' => $this->findModel($id),
        ]);
    }

    public function actionSettings()
    {
        $user = Yii::$app->user->identity;
        $contact = new Contact();


        if ($user->load(Yii::$app->request->post()) && $user->validate()) {
            if (Yii::$app->request->isPost && isset($_FILES['avatar']) && $_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
                $file = $_FILES['avatar'];

                $uploadPath = Yii::getAlias('@webroot') . '/uploads/avatars/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $fileName = Yii::$app->security->generateRandomString() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);

                if (move_uploaded_file($file['tmp_name'], $uploadPath . $fileName)) {
                    $user->avatar = '/uploads/avatars/' . $fileName;
                }
            }
            $user->name = $user->name;

            if ($user->save()) {

                $contact_gender = Yii::$app->request->post('contact_gender');
                $contact = Contact::findOne(['userid' => $user->userid]);

                if ($contact) {
                    $contact->contact_gender = $contact_gender;
                    $contact->save();
                }

                Yii::$app->session->setFlash('success', 'Data pengguna berhasil diperbarui.');
                return $this->redirect(['settings']);
            }
        }

        return $this->render('settings', [
            'user' => $user,
            'contact' => $contact,
        ]);
    }

    public function actionSetlang($lang)
    {
        Yii::$app->lang->setLang($lang);
        return $this->redirect(Yii::$app->request->referrer);
    }


    public function actionGenlang()
    {
        Yii::$app->lang->genLang();
    }

    // public function actionIndex()
    // {
    //     $companyid = Yii::$app->session->get('companyid');
    //     // var_dump($companyid);
    //     return $this->render('index', [
    //         'companyid' => $companyid,
    //     ]);
    // }
    public function actionIndex($companyid = null, $contacttype = '')
    {
        // $this->isAkses($contacttype, "lihat");
        $searchModel = new Contact();
        // var_dump("masuk"); die;

        $user = Yii::$app->user;
        $access = $user->identity->getAccess($contacttype);

        $userId = $user->id;
        $session = Yii::$app->session;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $contacttype);
        // var_dump($contacttype); exit;
        return $this->render('index', [
            'contacttype' => $contacttype,
            'dataProvider' => $dataProvider,
            'access' => $access
        ]);
    }

    public function actionPreviewcode($template = null)
    {
        $session = Yii::$app->session;
        $companyId = Yii::$app->session->get('companyid');

        if ($template) {
            // Simpan template ke session
            $session->set('contact_code', $template);

            // Update juga di database
            Yii::$app->db->createCommand()
                ->update('company', ['contact_code' => $template], ['companyid' => $companyId])
                ->execute();
        }

        // Generate preview code
        return $this->asJson([
            'success' => true,
            // 'previewcode' => Contact::nextNo()
        ]);
    }

    public function actionSecurity($jeniscontact = 0)
    {
        return $this->render('security', [
            //'model' => $this->findModel($id),
        ]);
    }

    public function actionActivity($jeniscontact = 0)
    {
        return $this->render('activity', [
            //'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Contact model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionCreate($contacttype = null)
    {
        $model = new Contact();

        if ($model->load(Yii::$app->request->post())) {
            $model->contacttype = $contacttype;
            $convertDate = function ($dateStr) {
                if (empty($dateStr))
                    return null;
                $d = \DateTime::createFromFormat('d/m/Y', $dateStr);
                return $d ? $d->format('Y-m-d') : null;
            };

            $model->contact_bod = $convertDate($model->contact_bod);
            $model->jobstart = $convertDate($model->jobstart);
            $model->jobend = $convertDate($model->jobend);

            if ($model->validate(false)) {
                try {
                    $foto = \yii\web\UploadedFile::getInstance($model, 'npwpfiletemp');
                    if (!empty($foto)) {
                        $foto->name = $model->contact_name . '-npwpfile' . ".jpg";
                        $model->npwpfile = $foto->name;
                    }

                    if ($model->save(false)) {
                        if (!empty($foto)) {
                            $path = Yii::getAlias('@webroot/uploads/contact/') . $foto->name;
                            $foto->saveAs($path);
                        }

                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return [
                                'success' => true,
                                'pesan' => 'Data Berhasil Disimpan',
                                'id' => $model->contact_id,
                                'name' => $model->contact_name,
                                'redirect' => \yii\helpers\Url::to(['index']),
                            ];
                        }
                        return $this->redirect(['index', 'contacttype' => $contacttype]);
                    }
                } catch (\Throwable $e) {
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return [
                            'success' => false,
                            'pesan' => $e->getMessage(),
                            'redirect' => \yii\helpers\Url::to(['index']),
                        ];
                    }
                    throw $e;
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'success' => false,
                        'pesan' => implode('<br>', $model->getFirstErrors()),
                    ];
                }
            }
        }

        $model->contacttype = $contacttype;
        $model->contact_no = Contact::nextNo($contacttype);
        $model->contact_status = "1";
        $form = '';
        if ($contacttype == 'customer') {
            $form = '_form';
        } else {
            $form = '_forme';
        }

        if (Yii::$app->request->get('type') == 'master' || Yii::$app->request->isAjax) {
            return $this->renderAjax($form, [
                'model' => $model,
                'isajax' => true
            ]);
        } else {
            return $this->render($form, [
                'model' => $model,
                'isajax' => false
            ]);
        }
    }
    public function actionUpdate($contact_id, $contacttype = '')
    {
        $model = $this->findModel($contact_id);
        if (!empty($contacttype)) {
            $model->contacttype = $contacttype;
        } else {
            $contacttype = $model->contacttype;
        }

        $db = Yii::$app->db;

        if ($model->load(Yii::$app->request->post())) {

            $convertDate = function ($dateStr) {
                if (empty($dateStr))
                    return null;

                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr)) {
                    return $dateStr;
                }

                $d = \DateTime::createFromFormat('d/m/Y', $dateStr);
                if ($d && $d->format('d/m/Y') === $dateStr) {
                    return $d->format('Y-m-d');
                }

                return null;
            };

            $model->jobstart = $convertDate($model->jobstart);
            $model->jobend = $convertDate($model->jobend);

            $contactTypes = Yii::$app->request->post('Contact')['contact_typeid'] ?? [];
            $model->contact_typeid = json_encode($contactTypes, JSON_FORCE_OBJECT);

            $selectedTypes = $contactTypes;
            $typeFlags = ['VN' => 'contact_isvendor', 'CS' => 'contact_iscustomer'];
            foreach ($typeFlags as $type => $field) {
                $model->$field = in_array($type, $selectedTypes) ? 1 : 0;
            }

            $valid = $model->validate(false);

            if ($valid && !$model->hasErrors()) {
                $transaction = $db->beginTransaction();
                try {

                    $foto = \yii\web\UploadedFile::getInstance($model, 'npwpfiletemp');
                    if (!empty($foto)) {
                        if ($model->npwpfile) {
                            $path = Yii::getAlias('@webroot/uploads/contact/') . $model->npwpfile;
                            if (file_exists($path) && is_file($path)) {
                                unlink($path);
                            }
                        }
                        $foto->name = $model->contact_name . '-npwpfile-' . time() . ".jpg";
                        $model->npwpfile = $foto->name;
                    }

                    if ($flag = $model->save(false)) {
                        if (!empty($foto)) {
                            $path = Yii::getAlias('@webroot/uploads/contact/') . $foto->name;
                            $foto->saveAs($path);
                        }

                        $transaction->commit();
                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return ['success' => true, 'pesan' => 'Data Berhasil Disimpan', 'id' => $model->contact_id, 'name' => $model->contact_name];
                        }
                        return $this->redirect(['index', 'contacttype' => $contacttype]);
                    } else {
                        $transaction->rollBack();
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['success' => false, 'pesan' => $e->getMessage(), 'id' => $model->contact_id, 'name' => $model->contact_name];
                    }
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => false, 'pesan' => implode('<br>', $model->getFirstErrors()), 'id' => $model->contact_id, 'name' => $model->contact_name];
                }
            }
        }

        $model->jobstart = $model->jobstart == null ? null : Yii::$app->formatter->asDate($model->jobstart, "php:d/m/Y");
        $model->jobend = $model->jobend == null ? null : Yii::$app->formatter->asDate($model->jobend, "php:d/m/Y");

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
                'isajax' => true
            ]);
        } else {
            return $this->render('_form', [
                'model' => $model,
                'isajax' => false
            ]);
        }
    }

    public function actionCreateem($contacttype = null)
    {
        $model = new Contact();
        $modeldocuments = [new Document()];
        $db = Yii::$app->db;

        if ($model->load(Yii::$app->request->post())) {
            $model->contacttype = $contacttype;
            $model->contact_bod = !empty($model->contact_bod) ? date("Y-m-d", strtotime($model->contact_bod)) : null;
            $model->jobstart = !empty($model->jobstart) ? date("Y-m-d", strtotime($model->jobstart)) : null;
            $model->jobend = !empty($model->jobend) ? date("Y-m-d", strtotime($model->jobend)) : null;
            $model->contact_no = trim((string) $model->contact_no);
            $model->contact_name = trim((string) $model->contact_name);
            $model->contact_name2 = trim((string) $model->contact_name2);
            $model->contact_name3 = trim((string) $model->contact_name3);
            $model->jobcompany = trim((string) $model->jobcompany);

            if (!empty($model->contact_no)) {
                $contactNo = $db->quoteValue($model->contact_no);
                $exists = "SELECT COUNT(*) FROM contacts WHERE contact_no = {$contactNo} AND contact_status <> '10'";
                $existsData = $db->createCommand($exists)->queryScalar();

                if ($existsData > 0) {
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return [
                            'success' => false,
                            'pesan' => 'Kontak Id sudah ada. Silakan gunakan nomor lain.',
                        ];
                    } else {
                        $model->addError('contact_no', 'Kontak Id sudah ada. Silakan gunakan nomor lain.');
                    }
                }
            }

            $names = array_filter([
                $model->contact_name,
                $model->contact_name2,
                $model->contact_name3,
            ], 'strlen');

            if (!empty($names) && !empty($model->jobcompany)) {
                $company = $db->quoteValue($model->jobcompany);
                $nameConditions = [];

                foreach ($names as $name) {
                    $quotedName = $db->quoteValue($name);
                    $nameConditions[] = "(contact_name = {$quotedName} OR contact_name2 = {$quotedName} OR contact_name3 = {$quotedName})";
                }

                $whereName = implode(" OR ", $nameConditions);
                $existsName = "SELECT COUNT(*) FROM contacts WHERE jobcompany = {$company} AND contact_status <> '10' AND ({$whereName})";
                $existsNameData = $db->createCommand($existsName)->queryScalar();

                if ($existsNameData > 0) {
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return [
                            'success' => false,
                            'pesan' => 'Nama kontak sudah ada di perusahaan ini. Silakan gunakan nama lain.',
                        ];
                    } else {
                        $model->addError('contact_name', 'Nama kontak sudah ada di perusahaan ini. Silakan gunakan nama lain.');
                    }
                }
            }

            $modeldocuments = Model::createMultipleID(Document::class, $modeldocuments, 'documentid');
            Model::loadMultiple($modeldocuments, Yii::$app->request->post());

            if ($model->validate(false)) {
                try {
                    $foto = \yii\web\UploadedFile::getInstance($model, 'npwpfiletemp');
                    if (!empty($foto)) {
                        $foto->name = $model->contact_name . '-npwpfile' . ".jpg";
                        $model->npwpfile = $foto->name;
                    }

                    $foto2 = \yii\web\UploadedFile::getInstance($model, 'npwpfiletemp2');
                    if (!empty($foto2)) {
                        $foto2->name = $model->contact_name . '-npwpfile2' . ".jpg";
                        $model->npwpfile2 = $foto2->name;
                    }

                    $foto3 = \yii\web\UploadedFile::getInstance($model, 'npwpfiletemp3');
                    if (!empty($foto3)) {
                        $foto3->name = $model->contact_name . '-npwpfile3' . ".jpg";
                        $model->npwpfile3 = $foto3->name;
                    }

                    $idfile = \yii\web\UploadedFile::getInstance($model, 'idfiletemp');
                    if (!empty($idfile)) {
                        $idfile->name = $model->contact_name . '-idfile' . ".jpg";
                        $model->idfile = $idfile->name;
                    }

                    $contactphoto = \yii\web\UploadedFile::getInstance($model, 'contactphototemp');
                    if (!empty($contactphoto)) {
                        $contactphoto->name = $model->contact_name . '-contactphoto' . ".jpg";
                        $model->contactphoto = $contactphoto->name;
                    }

                    $nametagfile = \yii\web\UploadedFile::getInstance($model, 'nametagfiletemp');
                    if (!empty($nametagfile)) {
                        $nametagfile->name = $model->contact_name . '-nametagfile' . ".jpg";
                        $model->nametagfile = $nametagfile->name;
                    }

                    if ($model->save(false)) {
                        if (!empty($foto)) {
                            $path = Yii::getAlias('@webroot/uploads/contact/') . $foto->name;
                            $foto->saveAs($path);
                        }
                        if (!empty($foto2)) {
                            $path = Yii::getAlias('@webroot/uploads/contact/') . $foto2->name;
                            $foto2->saveAs($path);
                        }
                        if (!empty($foto3)) {
                            $path = Yii::getAlias('@webroot/uploads/contact/') . $foto3->name;
                            $foto3->saveAs($path);
                        }
                        if (!empty($idfile)) {
                            $path = Yii::getAlias('@webroot/uploads/contact/') . $idfile->name;
                            $idfile->saveAs($path);
                        }
                        if (!empty($contactphoto)) {
                            $path = Yii::getAlias('@webroot/uploads/contact/') . $contactphoto->name;
                            $contactphoto->saveAs($path);
                        }
                        if (!empty($nametagfile)) {
                            $path = Yii::getAlias('@webroot/uploads/contact/') . $nametagfile->name;
                            $nametagfile->saveAs($path);
                        }

                        foreach ($modeldocuments as $indexdetail => $docModel) {
                            $docModel->refid = $model->contact_id;
                            $docModel->ord = $indexdetail + 1;
                            $docModel->documenttype = "contact";

                            $foto = \yii\web\UploadedFile::getInstanceByName("Document[{$indexdetail}][documentpath]");

                            if (empty($docModel->documentid)) {
                                $docId = $db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
                                $docModel->documentid = $docId;
                            }

                            $existingPath = null;
                            if (
                                isset($_POST['Document'][$indexdetail]['documentpath_existing'])
                                && !empty($_POST['Document'][$indexdetail]['documentpath_existing'])
                            ) {
                                $existingPath = $_POST['Document'][$indexdetail]['documentpath_existing'];
                            }

                            if (!empty($foto)) {
                                $ext = pathinfo($foto->name, PATHINFO_EXTENSION);
                                $filename = $docModel->documentid . "." . $ext;

                                if (!empty($existingPath) && $existingPath !== $filename) {
                                    $oldPath = Yii::getAlias('@webroot') . '/uploads/contact/' . $existingPath;
                                    if (file_exists($oldPath)) {
                                        @unlink($oldPath);
                                    }
                                }

                                $docModel->documentname = $foto->name;
                                $docModel->documentext = $ext;
                                $docModel->documensize = $foto->size;
                                $docModel->documentpath = $filename;

                                $uploadPath = Yii::getAlias('@webroot') . '/uploads/contact/' . $filename;
                                if (!$foto->saveAs($uploadPath)) {
                                    throw new \Exception("Failed to save file: $filename");
                                }
                            } else {
                                if (!empty($existingPath)) {
                                    $docModel->documentpath = $existingPath;
                                } else {
                                    if ($docModel->isNewRecord) {
                                        continue;
                                    }
                                }
                            }

                            if (!($docModel->save(false))) {
                                throw new \Exception("Failed to save document: " . implode(', ', $docModel->getFirstErrors()));
                            }
                        }

                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return [
                                'success' => true,
                                'pesan' => 'Data Berhasil Disimpan',
                                'id' => $model->contact_id,
                                'name' => $model->contact_name,
                                'redirect' => \yii\helpers\Url::to(['index']),
                            ];
                        }
                        return $this->redirect(['index', 'contacttype' => $contacttype]);
                    }
                } catch (\Throwable $e) {
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return [
                            'success' => false,
                            'pesan' => $e->getMessage(),
                            'redirect' => \yii\helpers\Url::to(['index']),
                        ];
                    }
                    throw $e;
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'success' => false,
                        'pesan' => implode('<br>', $model->getFirstErrors()),
                    ];
                }
            }
        }

        $position = Yii::$app->request->queryParams['position'] ?? null;
        if ($position) {
            $model->positionid = 'position.' . $position;
        }
        $model->contacttype = $contacttype;
        $model->contact_no = Contact::nextNo($contacttype);
        $model->contact_status = "1";

        if (Yii::$app->request->get('type') == 'master' || Yii::$app->request->isAjax) {
            return $this->renderAjax('_forme', [
                'model' => $model,
                'modeldocument' => (empty($modeldocuments)) ? [new Document()] : $modeldocuments,
                'isajax' => true
            ]);
        } else {
            return $this->render('_forme', [
                'model' => $model,
                'modeldocument' => (empty($modeldocuments)) ? [new Document()] : $modeldocuments,
                'isajax' => false
            ]);
        }
    }
    public function actionUpdateem($contacttype = '', $contact_id)
    {
        $model = $this->findModel($contact_id);
        if ($contacttype !== '') {
            $model->contacttype = $contacttype;
        }

        $modeldocuments = $model->documents;
        $db = Yii::$app->db;

        if ($model->load(Yii::$app->request->post())) {
            $contactTypes = Yii::$app->request->post('Contact')['contact_typeid'] ?? [];
            $model->contact_typeid = json_encode($contactTypes, JSON_FORCE_OBJECT);

            $selectedTypes = $contactTypes;
            $typeFlags = ['VN' => 'contact_isvendor', 'CS' => 'contact_iscustomer'];
            foreach ($typeFlags as $type => $field) {
                $model->$field = in_array($type, $selectedTypes) ? 1 : 0;
            }

            $model->contact_no = trim((string) $model->contact_no);
            $model->contact_name = trim((string) $model->contact_name);
            $model->contact_name2 = trim((string) $model->contact_name2);
            $model->contact_name3 = trim((string) $model->contact_name3);
            $model->jobcompany = trim((string) $model->jobcompany);

            $names = array_filter([
                $model->contact_name,
                $model->contact_name2,
                $model->contact_name3,
            ], 'strlen');

            if (!empty($names) && !empty($model->jobcompany)) {
                $company = $db->quoteValue($model->jobcompany);
                $id = $db->quoteValue($model->contact_id);
                $nameConditions = [];

                foreach ($names as $name) {
                    $quotedName = $db->quoteValue($name);
                    $nameConditions[] = "(contact_name = {$quotedName} OR contact_name2 = {$quotedName} OR contact_name3 = {$quotedName})";
                }

                $whereName = implode(" OR ", $nameConditions);
                $existsName = "SELECT COUNT(*) FROM contacts WHERE jobcompany = {$company} AND contact_status <> '10' AND contact_id <> {$id} AND ({$whereName})";
                $existsNameData = $db->createCommand($existsName)->queryScalar();

                if ($existsNameData > 0) {
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return [
                            'success' => false,
                            'pesan' => 'Nama kontak sudah ada di perusahaan ini. Silakan gunakan nama lain.',
                        ];
                    } else {
                        $model->addError('contact_name', 'Nama kontak sudah ada di perusahaan ini. Silakan gunakan nama lain.');
                    }
                }
            }

            $oldIDs = ArrayHelper::map($modeldocuments, 'documentid', 'documentid');
            $modeldocuments = Model::createMultipleID(Document::classname(), $modeldocuments, 'documentid');
            Model::loadMultiple($modeldocuments, Yii::$app->request->post());
            $deletedIDsDoc = array_diff($oldIDs, array_filter(ArrayHelper::map($modeldocuments, 'documentid', 'documentid')));

            $valid = $model->validate(false);

            if ($valid && !$model->hasErrors()) {
                $transaction = $db->beginTransaction();
                try {

                    $foto = \yii\web\UploadedFile::getInstance($model, 'npwpfiletemp');
                    if (!empty($foto)) {
                        if ($model->npwpfile) {
                            $path = Yii::getAlias('@webroot/uploads/contact/') . $model->npwpfile;
                            if (file_exists($path) && is_file($path)) {
                                unlink($path);
                            }
                        }
                        $foto->name = $model->contact_name . '-npwpfile-' . time() . ".jpg";
                        $model->npwpfile = $foto->name;
                    }

                    $foto2 = \yii\web\UploadedFile::getInstance($model, 'npwpfiletemp2');
                    if (!empty($foto2)) {
                        if ($model->npwpfile2) {
                            $path = Yii::getAlias('@webroot/uploads/contact/') . $model->npwpfile2;
                            if (file_exists($path) && is_file($path)) {
                                unlink($path);
                            }
                        }
                        $foto2->name = $model->contact_name . '-npwpfile2-' . time() . ".jpg";
                        $model->npwpfile2 = $foto2->name;
                    }

                    $foto3 = \yii\web\UploadedFile::getInstance($model, 'npwpfiletemp3');
                    if (!empty($foto3)) {
                        if ($model->npwpfile3) {
                            $path = Yii::getAlias('@webroot/uploads/contact/') . $model->npwpfile3;
                            if (file_exists($path) && is_file($path)) {
                                unlink($path);
                            }
                        }
                        $foto3->name = $model->contact_name . '-npwpfile3-' . time() . ".jpg";
                        $model->npwpfile3 = $foto3->name;
                    }

                    $idfile = \yii\web\UploadedFile::getInstance($model, 'idfiletemp');
                    if (!empty($idfile)) {
                        if ($model->idfile) {
                            $path = Yii::getAlias('@webroot/uploads/contact/') . $model->idfile;
                            if (file_exists($path) && is_file($path)) {
                                unlink($path);
                            }
                        }
                        $idfile->name = $model->contact_name . '-idfile-' . time() . ".jpg";
                        $model->idfile = $idfile->name;
                    }

                    $contactphoto = \yii\web\UploadedFile::getInstance($model, 'contactphototemp');
                    if (!empty($contactphoto)) {
                        if ($model->contactphoto) {
                            $path = Yii::getAlias('@webroot/uploads/contact/') . $model->contactphoto;
                            if (file_exists($path) && is_file($path)) {
                                unlink($path);
                            }
                        }
                        $contactphoto->name = $model->contact_name . '-contactphoto-' . time() . ".jpg";
                        $model->contactphoto = $contactphoto->name;
                    }

                    $nametagfile = \yii\web\UploadedFile::getInstance($model, 'nametagfiletemp');
                    if (!empty($nametagfile)) {
                        if ($model->nametagfile) {
                            $path = Yii::getAlias('@webroot/uploads/contact/') . $model->nametagfile;
                            if (file_exists($path) && is_file($path)) {
                                unlink($path);
                            }
                        }
                        $nametagfile->name = $model->contact_name . '-nametagfile-' . time() . ".jpg";
                        $model->nametagfile = $nametagfile->name;
                    }

                    if (!empty($deletedIDsDoc)) {
                        Document::updateAll(['status' => 10], ['documentid' => $deletedIDsDoc]);
                    }

                    if ($flag = $model->save(false)) {
                        if (!empty($foto)) {
                            $path = Yii::getAlias('@webroot/uploads/contact/') . $foto->name;
                            $foto->saveAs($path);
                        }
                        if (!empty($foto2)) {
                            $path = Yii::getAlias('@webroot/uploads/contact/') . $foto2->name;
                            $foto2->saveAs($path);
                        }
                        if (!empty($foto3)) {
                            $path = Yii::getAlias('@webroot/uploads/contact/') . $foto3->name;
                            $foto3->saveAs($path);
                        }
                        if (!empty($idfile)) {
                            $path = Yii::getAlias('@webroot/uploads/contact/') . $idfile->name;
                            $idfile->saveAs($path);
                        }
                        if (!empty($contactphoto)) {
                            $path = Yii::getAlias('@webroot/uploads/contact/') . $contactphoto->name;
                            $contactphoto->saveAs($path);
                        }
                        if (!empty($nametagfile)) {
                            $path = Yii::getAlias('@webroot/uploads/contact/') . $nametagfile->name;
                            $nametagfile->saveAs($path);
                        }

                        foreach ($modeldocuments as $indexdetail => $docModel) {
                            $docModel->refid = $model->contact_id;
                            $docModel->ord = $indexdetail + 1;
                            $docModel->documenttype = "contact";

                            if (empty($docModel->documentid)) {
                                $docId = $db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
                                $docModel->documentid = $docId;
                            }

                            $foto = \yii\web\UploadedFile::getInstanceByName("Document[{$indexdetail}][documentpath]");
                            $existingPath = $_POST['Document'][$indexdetail]['documentpath_existing'] ?? null;

                            if (!empty($foto)) {
                                if (!empty($existingPath)) {
                                    $oldPath = Yii::getAlias('@webroot') . '/uploads/contact/' . $existingPath;
                                    if (file_exists($oldPath)) {
                                        @unlink($oldPath);
                                    }
                                }

                                $ext = pathinfo($foto->name, PATHINFO_EXTENSION);
                                $filename = $docModel->documentid . "." . $ext;

                                $docModel->documentname = $foto->name;
                                $docModel->documentext = $ext;
                                $docModel->documensize = $foto->size;
                                $docModel->documentpath = $filename;

                                $uploadPath = Yii::getAlias('@webroot') . '/uploads/contact/' . $filename;
                                if (!$foto->saveAs($uploadPath)) {
                                    throw new Exception("Failed to save file: $filename");
                                }
                            } else {
                                if (!empty($existingPath)) {
                                    $docModel->documentpath = $existingPath;
                                    if (!$docModel->isNewRecord) {
                                        $oldDoc = Document::findOne($docModel->documentid);
                                        if ($oldDoc) {
                                            $docModel->documentname = $oldDoc->documentname;
                                            $docModel->documentext = $oldDoc->documentext;
                                            $docModel->documensize = $oldDoc->documensize;
                                        }
                                    }
                                } else {
                                    if ($docModel->isNewRecord) {
                                        continue;
                                    }
                                }
                            }

                            if (!($flag = $docModel->save(false))) {
                                throw new Exception("Failed to save document: " . implode(', ', $docModel->getFirstErrors()));
                            }
                        }

                        if ($flag) {
                            $transaction->commit();
                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return ['success' => true, 'pesan' => 'Data Berhasil Disimpan', 'id' => $model->contact_id, 'name' => $model->contact_name];
                            }
                            return $this->redirect(['index', 'contacttype' => $contacttype]);
                        } else {
                            $transaction->rollBack();
                        }
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['success' => false, 'pesan' => $e->getMessage(), 'id' => $model->contact_id, 'name' => $model->contact_name];
                    }
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => false, 'pesan' => implode('<br>', $model->getFirstErrors()), 'id' => $model->contact_id, 'name' => $model->contact_name];
                }
            }
        }

        $model->contact_bod = $model->contact_bod == null ? null : Yii::$app->formatter->asDate($model->contact_bod, "dd/MM/yyyy");
        $model->jobstart = $model->jobstart == null ? null : Yii::$app->formatter->asDate($model->jobstart, "dd/MM/yyyy");
        $model->jobend = $model->jobend == null ? null : Yii::$app->formatter->asDate($model->jobend, "dd/MM/yyyy");

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_forme', [
                'model' => $model,
                'modeldocument' => (empty($modeldocuments)) ? [new Document()] : $modeldocuments,
                'isajax' => true
            ]);
        } else {
            return $this->render('_forme', [
                'model' => $model,
                'modeldocument' => (empty($modeldocuments)) ? [new Document()] : $modeldocuments,
                'isajax' => false
            ]);
        }
    }

    public function actionMembercard($contact_id)
    {
        $model = $this->findModel($contact_id);

        $packageName = $model->product->productname ?? 'Basic Monthly';

        $qrData = $model->contact_no ?? $model->contact_id;
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($qrData);

        return $this->render('member_card', [
            'model' => $model,
            'packageName' => $packageName,
            'qrUrl' => $qrUrl,
        ]);
    }
    public function actionAddvendor()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $name = Yii::$app->request->post('name');
        $code = Yii::$app->request->post('code');
        $module = Yii::$app->request->post('moduletype');
        $model = new Contact();

        if (empty($name)) {
            return [
                'success' => false,
                'message' => 'Nama kategori harus diisi.'
            ];
        }

        try {
            $model->contact_name = $name;
            $model->contact_status = 1;
            $model->companyid = Yii::$app->session->get('companyid');
            // $model->userid = Yii::$app->user->id;
            $model->contact_typeid = ['0'];
            if ($module == 'sales') {
                $model->contact_iscustomer = 1;
            } else {
                $model->contact_isvendor = 1;
            }

            // If code provided, use it
            if (!empty($code)) {
                $model->contact_no = $code;
            } else {
                // Generate code
                $model->contact_no = $model->nextNo();
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
                        'contact_id' => $model->contact_id,
                        'contact_no' => $model->contact_no,
                        'contact_name' => $model->contact_name,
                        'contact_typeid' => $model->contact_typeid
                        // 'userid' => $model->userid
                    ]
                ];
            } else {
                Yii::error('Error saving category: ' . json_encode($model->errors), 'kategori');

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

    public function actionUpdatestatus()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = Yii::$app->request;
        if (!$request->isPost) {
            return ['success' => false, 'message' => 'Hanya menerima request POST'];
        }

        $id = Yii::$app->request->post('id');
        $status = Yii::$app->request->post('status');

        if (empty($id) || $status === null) {
            return ['success' => false, 'message' => 'Parameter tidak lengkap'];
        }

        $model = Contact::findOne($id);
        if (!$model) {
            return ['success' => false, 'message' => 'Data transaksi tidak ditemukan'];
        }

        $model->status_register = $status;

        $statusLabels = [
            0 => 'Close',
            1 => 'Open',

        ];

        $statusText = isset($statusLabels[$status]) ? $statusLabels[$status] : 'Unknown';

        if ($model->save(false)) {
            return [
                'success' => true,
                'message' => 'Status berhasil diperbarui',
                'status' => $status,
                'statusText' => $statusText
            ];
        } else {
            return ['success' => false, 'message' => 'Gagal menyimpan perubahan status'];
        }
    }

    public function actionSavetemplate()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $template = Yii::$app->request->get('template');
        $companyid = Yii::$app->session->get('companyid');

        if (!$template || !$companyid) {
            return ['success' => false, 'message' => 'Data tidak lengkap.'];
        }

        $controllerId = Yii::$app->controller->id;
        Yii::$app->db->createCommand()->insert('numbertemplate', [
            'template' => $template,
            'companyid' => $companyid,
            'months' => null, // isi default
            'years' => null,
            'type' => $controllerId
        ])->execute();

        return ['success' => true];
    }

    public function actionSettemplate()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $template = Yii::$app->request->get('template');
        $companyid = Yii::$app->session->get('companyid');

        if ($template && $companyid) {
            Yii::$app->db->createCommand()->update('company', [
                'contact_code' => $template
            ], ['companyid' => $companyid])->execute();

            return ['success' => true];
        }

        return ['success' => false, 'message' => 'Data tidak lengkap'];
    }

    public function actionResettemplate()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $companyid = Yii::$app->session->get('companyid');

        if (!$companyid) {
            return ['success' => false, 'message' => 'Company ID tidak ditemukan.'];
        }

        // Hapus template yang aktif (atau tandai null/default)
        Yii::$app->db->createCommand()->update('company', [
            'contact_code' => 'CT', // reset ke CT
        ], ['companyid' => $companyid])->execute();

        return ['success' => true];
    }

    /**
     * Deletes an existing Contact model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */


    public function actionDetail($contact_id)
    {

        $model = Contact::findOne($contact_id);
        if (!$model) {
            throw new NotFoundHttpException("Kontak tidak ditemukan.");
        }

        $modeldocuments = $model->documents;

        return $this->render('detail', [
            'model' => $model,
            'modeldocument' => (empty($modeldocuments)) ? [new Document()] : $modeldocuments
        ]);
    }


    public function actionDelete()
    {
        $id = Yii::$app->request->post('id');
        if (!$id) {
            return $this->asJson(['success' => false, 'message' => 'ID tidak ditemukan!']);
        }

        $model = $this->findModel($id);
        $model->contact_status = "10";
        //var_dump($model->contact_status);
        // $model = $this->findModel($id);
        if ($model->save(false)) {
            return $this->asJson(['success' => true]);
        } else {
            return $this->asJson(['success' => false, 'message' => 'Gagal menghapus']);
        }
    }
    public function actionDeletemassal()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $db = Yii::$app->db;
        $ids = Yii::$app->request->post('ids');

        if (!empty($ids)) {
            $idString = implode(',', array_map(function ($id) use ($db) {
                return $db->quoteValue($id);
            }, $ids));

            $sql = "UPDATE contacts SET contact_status = '10' WHERE contact_id IN ($idString)";
            $db->createCommand($sql)->execute();
            return ['success' => true, 'message' => 'Data berhasil dihapus!'];
        }
        return ['success' => false, 'message' => 'Tidak ada data yang dipilih!'];
    }



    /**
     * Finds the Contact model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Contact the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Contact::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionJoblist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $db = Yii::$app->db;

        $q = Yii::$app->request->get('q', null);
        $userid = Yii::$app->user->id;
        $listBahasa = "SELECT lang FROM users WHERE userid = " . $db->quoteValue($userid);
        $bahasa = $db->createCommand($listBahasa)->queryScalar();
        $bahasaQuoted = $db->quoteValue($bahasa);

        $sql = "SELECT DISTINCT
                e.enumid as id, e.enumno as no,
            CASE
                WHEN {$bahasaQuoted} = 'id' THEN COALESCE(enumtext_id)
                ELSE enumtext_en
            END as text
            FROM enum e
            WHERE e.enumtype = 'jobposition'";

        if ($q !== null) {
            $qLike = $db->quoteValue("%" . strtolower($q) . "%");
            $sql .= " AND LOWER(e.enumtext_en) LIKE {$qLike}";
        }

        $sql .= " ORDER BY enumno ASC LIMIT 5";
        $data = $db->createCommand($sql)->queryAll();

        return ['results' => array_values($data)];
    }

    public function actionEdulist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $db = Yii::$app->db;

        $q = Yii::$app->request->get('q', null);
        $userid = Yii::$app->user->id;
        $listBahasa = "SELECT lang FROM users WHERE userid = " . $db->quoteValue($userid);
        $bahasa = $db->createCommand($listBahasa)->queryScalar();
        $bahasaQuoted = $db->quoteValue($bahasa);

        $sql = "SELECT DISTINCT e.enumid as id, e.enumno as no,
                    CASE
                    WHEN {$bahasaQuoted} = 'id' THEN COALESCE(enumtext_id)
                ELSE enumtext_en
            END as text
            FROM enum e
            WHERE e.enumtype = 'individual_education'";

        if ($q !== null) {
            $qLike = $db->quoteValue("%" . strtolower($q) . "%");
            $sql .= " AND LOWER(e.enumtext_en) LIKE {$qLike}";
        }

        $sql .= " ORDER BY enumno ASC LIMIT 5";
        $data = $db->createCommand($sql)->queryAll();

        return ['results' => array_values($data)];
    }




    public function actionpersonlist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $db = Yii::$app->db;

        $q = Yii::$app->request->get('q', null);
        $userid = Yii::$app->user->id;
        $listBahasa = "SELECT lang FROM users WHERE userid = " . $db->quoteValue($userid);
        $bahasa = $db->createCommand($listBahasa)->queryScalar();
        $bahasaQuoted = $db->quoteValue($bahasa);

        $sql = "SELECT DISTINCT e.enumid as id, e.enumno as no,
                CASE
                    WHEN {$bahasaQuoted} = 'id' THEN COALESCE(enumtext_id)
                    ELSE enumtext_en
                END as text
            FROM enum e
            WHERE e.enumtype = 'contactperson'";

        if ($q !== null) {
            $qLike = $db->quoteValue("%" . strtolower($q) . "%");
            $sql .= " AND LOWER(e.enumtext_en) LIKE {$qLike}";
        }

        $sql .= " ORDER BY enumno ASC LIMIT 5";
        $data = $db->createCommand($sql)->queryAll();

        return ['results' => array_values($data)];
    }

    public function actionGenderlist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $db = Yii::$app->db;

        $q = Yii::$app->request->get('q', null);
        $userid = Yii::$app->user->id;
        $listBahasa = "SELECT lang FROM users WHERE userid = " . $db->quoteValue($userid);
        $bahasa = $db->createCommand($listBahasa)->queryScalar();
        $bahasaQuoted = $db->quoteValue($bahasa);

        $sql = "SELECT DISTINCT e.enumid as id, e.enumno as no,
                CASE
                    WHEN {$bahasaQuoted} = 'id' THEN COALESCE(enumtext_id)
                    ELSE enumtext_en
                END as text
            FROM enum e
            WHERE e.enumtype = 'gender'";

        if ($q !== null) {
            $qLike = $db->quoteValue("%" . strtolower($q) . "%");
            $sql .= " AND LOWER(e.enumtext_en) LIKE {$qLike}";
        }

        $sql .= " ORDER BY enumno ASC LIMIT 5";
        $data = $db->createCommand($sql)->queryAll();

        return ['results' => array_values($data)];
    }

    public function actionMarriedlist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $db = Yii::$app->db;

        $q = Yii::$app->request->get('q', null);
        $userid = Yii::$app->user->id;
        $listBahasa = "SELECT lang FROM users WHERE userid = " . $db->quoteValue($userid);
        $bahasa = $db->createCommand($listBahasa)->queryScalar();
        $bahasaQuoted = $db->quoteValue($bahasa);

        $sql = "SELECT DISTINCT e.enumid as id, e.enumno as no,
                CASE
                    WHEN {$bahasaQuoted} = 'id' THEN COALESCE(enumtext_id)
                ELSE enumtext_en
            END as text
            FROM enum e
            WHERE e.enumtype = 'married'";

        if ($q !== null) {
            $qLike = $db->quoteValue("%" . strtolower($q) . "%");
            $sql .= " AND LOWER(e.enumtext_en) LIKE {$qLike}";
        }

        $sql .= " ORDER BY enumno ASC LIMIT 5";
        $data = $db->createCommand($sql)->queryAll();

        return ['results' => array_values($data)];
    }

    public function actionRelilist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $db = Yii::$app->db;

        $q = Yii::$app->request->get('q', null);
        $userid = Yii::$app->user->id;
        $listBahasa = "SELECT lang FROM users WHERE userid = " . $db->quoteValue($userid);
        $bahasa = $db->createCommand($listBahasa)->queryScalar();
        $bahasaQuoted = $db->quoteValue($bahasa);

        $sql = "SELECT DISTINCT e.enumid as id, e.enumno as no,
                    CASE
                WHEN {$bahasaQuoted} = 'id' THEN COALESCE(enumtext_id)
                ELSE enumtext_en
            END as text
            FROM enum e
            WHERE e.enumtype = 'religion'";

        if ($q !== null) {
            $qLike = $db->quoteValue("%" . strtolower($q) . "%");
            $sql .= " AND LOWER(e.enumtext_en) LIKE {$qLike}";
        }

        $sql .= " ORDER BY enumno ASC LIMIT 5";
        $data = $db->createCommand($sql)->queryAll();

        return ['results' => array_values($data)];
    }

    public function actionTypelist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $db = Yii::$app->db;

        $q = Yii::$app->request->get('q', null);
        $userid = Yii::$app->user->id;
        $listBahasa = "SELECT lang FROM users WHERE userid = " . $db->quoteValue($userid);
        $bahasa = $db->createCommand($listBahasa)->queryScalar();
        $bahasaQuoted = $db->quoteValue($bahasa);

        $sql = "SELECT DISTINCT
            e.enumtext_en as id,
            e.enumno as no,
            CASE
                WHEN {$bahasaQuoted} = 'id' THEN COALESCE(e.enumtext_id, e.enumtext_en)
                ELSE COALESCE(e.enumtext_en, e.enumtext_id)
            END as text
        FROM enum e
        WHERE e.enumtype = 'contacttype'
        AND e.enumtext_en IS NOT NULL
        AND e.enumtext_id IS NOT NULL";

        if ($q !== null) {
            $qLike = $db->quoteValue("%" . strtolower($q) . "%");
            $sql .= " AND (LOWER(e.enumtext_en) LIKE {$qLike} OR LOWER(e.enumtext_id) LIKE {$qLike})";
        }

        $sql .= " ORDER BY e.enumno ASC LIMIT 5";
        try {
            $data = $db->createCommand($sql)->queryAll();
            \Yii::info("actionTypelist data: " . json_encode($data));
            return ['results' => array_values($data)];
        } catch (\Exception $e) {
            \Yii::error("Error in actionTypelist: " . $e->getMessage());
            return ['results' => []];
        }
    }

    public function actionIdtypelist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $db = Yii::$app->db;

        $q = Yii::$app->request->get('q', null);
        $userid = Yii::$app->user->id;
        $listBahasa = "SELECT lang FROM users WHERE userid = " . $db->quoteValue($userid);
        $bahasa = $db->createCommand($listBahasa)->queryScalar();
        $bahasaQuoted = $db->quoteValue($bahasa);

        $sql = "SELECT DISTINCT
                e.enumid as id,
                e.enumno as no,
                CASE
                WHEN {$bahasaQuoted} = 'id' THEN COALESCE(enumtext_id)
                ELSE enumtext_en
            END as text
            FROM enum e
            WHERE e.enumtype = 'idtype'";

        if ($q !== null) {
            $qLike = $db->quoteValue("%" . strtolower($q) . "%");
            $sql .= " AND LOWER(e.enumtext_en) LIKE {$qLike}";
        }

        $sql .= " ORDER BY enumno ASC LIMIT 5";
        $data = $db->createCommand($sql)->queryAll();

        return ['results' => array_values($data)];
    }

    public function actionPositionlist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $db = Yii::$app->db;

        $q = Yii::$app->request->get('q', null);
        $userid = Yii::$app->user->id;
        $listBahasa = "SELECT lang FROM users WHERE userid = " . $db->quoteValue($userid);
        $bahasa = $db->createCommand($listBahasa)->queryScalar();
        $bahasaQuoted = $db->quoteValue($bahasa);

        $sql = "SELECT DISTINCT 
            e.enumid as id,
            e.enumno as no,
            CASE
                WHEN {$bahasaQuoted} = 'id' THEN COALESCE(enumtext_id)
                ELSE enumtext_en
            END as text
        FROM enum e
        WHERE e.enumtype = 'position'";

        if ($q !== null) {
            $qLike = $db->quoteValue("%" . strtolower($q) . "%");
            $sql .= " AND LOWER(e.enumtext_en) LIKE {$qLike}";
        }

        $sql .= " ORDER BY enumno ASC";
        $data = $db->createCommand($sql)->queryAll();

        return ['results' => array_values($data)];
    }

    public function actionDivisionlist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $db = Yii::$app->db;

        $q = Yii::$app->request->get('q', null);
        $userid = Yii::$app->user->id;
        $listBahasa = "SELECT lang FROM users WHERE userid = " . $db->quoteValue($userid);
        $bahasa = $db->createCommand($listBahasa)->queryScalar();
        $bahasaQuoted = $db->quoteValue($bahasa);

        $sql = "SELECT DISTINCT 
            e.enumid as id,
            e.enumno as no,
            CASE
                WHEN {$bahasaQuoted} = 'id' THEN COALESCE(enumtext_id)
                ELSE enumtext_en
            END as text
        FROM enum e
        WHERE e.enumtype = 'division'";

        if ($q !== null) {
            $qLike = $db->quoteValue("%" . strtolower($q) . "%");
            $sql .= " AND LOWER(e.enumtext_en) LIKE {$qLike}";
        }

        $sql .= " ORDER BY enumno ASC";
        $data = $db->createCommand($sql)->queryAll();

        return ['results' => array_values($data)];
    }

    public function actionCountrylist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $db = Yii::$app->db;

        $q = Yii::$app->request->get('q', null);

        $sql = "SELECT DISTINCT
                e.enumid as id,
                e.enumtext_id as text
            FROM enum e
            WHERE e.enumtype = 'country'";

        if ($q !== null) {
            $qLike = $db->quoteValue("%" . strtolower($q) . "%");
            $sql .= " AND LOWER(e.enumtext_id) LIKE {$qLike}";
        }

        $sql .= " ORDER BY enumtext_id ASC LIMIT 5";
        $data = $db->createCommand($sql)->queryAll();

        return ['results' => array_values($data)];
    }

    public function actionStatelist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $db = Yii::$app->db;

        $country = Yii::$app->request->get('refid', null);
        $q = Yii::$app->request->get('q', null);
        if ($country === null) {
            return ['results' => []];
        }

        $countryQuoted = $db->quoteValue($country);
        $sql = "SELECT DISTINCT
                e.enumid as id,
                e.enumtext_id as text
            FROM enum e
            WHERE e.enumtype = 'state' AND e.refid = {$countryQuoted}";

        if ($q !== null) {
            $qLike = $db->quoteValue("%" . strtolower($q) . "%");
            $sql .= " AND LOWER(e.enumtext_id) LIKE {$qLike}";
        }

        $sql .= " ORDER BY enumtext_id ASC LIMIT 5";
        $data = $db->createCommand($sql)->queryAll();

        return ['results' => array_values($data)];
    }

    public function actionCitylist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $db = Yii::$app->db;

        $state = Yii::$app->request->get('refid', null);
        $q = Yii::$app->request->get('q', null);

        if ($state === null) {
            return ['results' => ['Data']];
        }

        $stateQuoted = $db->quoteValue($state);
        $sql = "SELECT DISTINCT
                e.enumid as id,
                e.enumtext_id as text
            FROM enum e
            WHERE e.enumtype = 'city' AND e.refid = {$stateQuoted}";

        if ($q !== null) {
            $qLike = $db->quoteValue("%" . strtolower($q) . "%");
            $sql .= " AND LOWER(e.enumtext_id) LIKE {$qLike}";
        }

        $sql .= " ORDER BY enumtext_id ASC LIMIT 5";
        $data = $db->createCommand($sql)->queryAll();

        return ['results' => array_values($data)];
    }

    public function actionDistriklist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $db = Yii::$app->db;

        $city = Yii::$app->request->get('refid', null);
        $q = Yii::$app->request->get('q', null);

        if ($city === null) {
            return ['results' => ['Data']];
        }

        $cityQuoted = $db->quoteValue($city);
        $sql = "SELECT DISTINCT
                e.enumid as id,
                e.enumtext_id as text
            FROM enum e
            WHERE e.enumtype = 'district' AND e.refid = {$cityQuoted}";

        if ($q !== null) {
            $qLike = $db->quoteValue("%" . strtolower($q) . "%");
            $sql .= " AND LOWER(e.enumtext_id) LIKE {$qLike}";
        }

        $sql .= " ORDER BY enumtext_id ASC LIMIT 5";
        $data = $db->createCommand($sql)->queryAll();

        return ['results' => array_values($data)];
    }

    public function actionList()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $db = Yii::$app->db;
        $params = Yii::$app->request->queryParams;

        $contacttype = $params['contacttype'] ?? '';
        $filterEmployee = $params['filter_employee'] ?? '';
        $filterOrderStatus = $params['filter_order_status'] ?? '';
        $filterPosition = $params['filter_position'] ?? '';
        $filterDivision = $params['filter_division'] ?? '';

        $type = $params['type'] ?? null;
        $positionid = $params['positionid'] ?? null;
        $search = $params['search'] ?? '';
        $start = (int) ($params['start'] ?? 0);
        $length = (int) ($params['length'] ?? 5);

        $sortcolumn = $params['order'][0]['column'] ?? '';
        $ordercolumn = $params['columns'][$sortcolumn]['data'] ?? 'c.createdat';
        $columnorder = $params['order'][0]['dir'] ?? 'ASC';
        $draw = $params['draw'] ?? 1;

        if ($sortcolumn == "0") {
            $orderdefault = " c.createdat desc";
        } else {
            $orderdefault = $ordercolumn . " " . $columnorder;
        }

        $orderby = " order by " . $orderdefault . " limit " . $length . " offset " . $start . "";

        $userid = Yii::$app->user->id;
        $listBahasa = "SELECT lang FROM users WHERE userid = " . $db->quoteValue($userid);
        $bahasa = $db->createCommand($listBahasa)->queryScalar();
        $bahasaQuoted = $db->quoteValue($bahasa);

        $contacttypeLower = strtolower(trim((string) $contacttype));
        $typeWhere = "1=1";
        if (in_array($contacttypeLower, ['customer', '1', 'cs'])) {
            $typeWhere = "(c.contacttype IN ('customer', '1', 'cs', 'CS') OR c.contact_iscustomer = 1)";
        } else if (in_array($contacttypeLower, ['vendor', '0', 'vn'])) {
            $typeWhere = "(c.contacttype IN ('vendor', '0', 'vn', 'VN') OR c.contact_isvendor = 1)";
        } else if (!empty($contacttype)) {
            $typeWhere = "c.contacttype = " . $db->quoteValue($contacttype);
        }

        $companyId = Yii::$app->session->get('companyid');
        $companyWhere = !empty($companyId) ? " AND (c.companyid = " . $db->quoteValue($companyId) . " OR c.companyid IS NULL)" : "";

        $sql =
            " SELECT c.contact_id,c.contact_no, c.contact_name, 
                c.contact_email1, c.contact_phone1, c.contacttype, c.businesscategory,
                c.contact_status, c.positionid, c.levelid, c.divisionid, c.contractid, c.jobcompany, c.contact_status, c.status_register,
                CASE WHEN {$bahasaQuoted} = 'id' THEN g.enumtext_id ELSE coalesce(g.enumtext_en,'') END AS gender, 
                CASE WHEN {$bahasaQuoted} = 'id' THEN r.enumtext_id ELSE coalesce(r.enumtext_en,'') END AS religion, 
                CASE WHEN {$bahasaQuoted} = 'id' THEN m.enumtext_id ELSE coalesce (m.enumtext_en,'') END AS married,
                CASE WHEN {$bahasaQuoted} = 'id' THEN p.enumtext_id ELSE coalesce(p.enumtext_en,'') END AS position,
                CASE WHEN {$bahasaQuoted} = 'id' THEN l.enumtext_id ELSE coalesce(l.enumtext_en,'') END AS level,
                CASE WHEN {$bahasaQuoted} = 'id' THEN d.enumtext_id ELSE coalesce(d.enumtext_en,'') END AS division,
                CASE WHEN {$bahasaQuoted} = 'id' THEN s.enumtext_id ELSE coalesce(s.enumtext_en,'') END AS contract,
                pc.productname as packagename
                FROM contacts c 
                LEFT JOIN enum g ON g.enumid = c.contact_gender AND g.enumtype = 'gender' 
                LEFT JOIN enum r ON r.enumid = c.contact_religion AND r.enumtype = 'religion' 
                LEFT JOIN enum m ON m.enumid = c.contact_married AND m.enumtype = 'married' 
                LEFT JOIN enum p ON p.enumid = c.positionid AND p.enumtype = 'position'
                LEFT JOIN enum d ON d.enumid = c.divisionid AND d.enumtype = 'division'
                LEFT JOIN enum l ON l.enumid = c.levelid AND l.enumtype = 'level'
                LEFT JOIN enum s ON s.enumid = c.contractid AND s.enumtype = 'status'
                LEFT JOIN products pc ON pc.productid = c.personid AND pc.status <> '10'
                WHERE {$typeWhere} AND c.contact_status <> '10' {$companyWhere}
            ";

        $filter = "";

        if (!empty($type)) {
            $filter .= " AND c.contact_typeid = " . $db->quoteValue($type);
        }

        if (!empty($positionid)) {
            $filter .= " AND c.positionid = " . $db->quoteValue('position.' . $positionid);
        }

        if (!empty($filterEmployee)) {
            $filter .= " AND c.contact_id = " . $db->quoteValue($filterEmployee);
        }

        if (!empty($filterOrderStatus)) {
            if ($filterOrderStatus == 'has_order') {
                $filter .= " AND EXISTS (
                SELECT 1
                FROM traneventcrews tec
                INNER JOIN tranevents te ON te.traneventid = tec.traneventid
                INNER JOIN trans t ON t.tranid = te.tranid
                WHERE tec.crewid = c.contact_id
                    AND t.status != 10
                    AND t.trantype = 'sales/order'
            )";
            } elseif ($filterOrderStatus == 'no_order') {
                $filter .= " AND NOT EXISTS (
                SELECT 1
                FROM traneventcrews tec
                INNER JOIN tranevents te ON te.traneventid = tec.traneventid
                INNER JOIN trans t ON t.tranid = te.tranid
                WHERE tec.crewid = c.contact_id
                    AND t.status != 10
                    AND t.trantype = 'sales/order'
            )";
            }
        }

        if (!empty($filterPosition)) {
            $filter .= " AND c.positionid = " . $db->quoteValue($filterPosition);
        }

        if (!empty($filterDivision)) {
            $filter .= " AND c.divisionid = " . $db->quoteValue($filterDivision);
        }

        $countall = "SELECT count(*) FROM contacts c WHERE {$typeWhere} AND c.contact_status <> '10' {$companyWhere} " . $filter;

        $searchFilter = "";
        if (!empty($search)) {
            $searchLike = $db->quoteValue('%' . $search . '%');
            $filter .= " AND ( 
            c.contact_no ILIKE {$searchLike} OR 
            c.contact_name ILIKE {$searchLike} OR 
            c.contact_name2 ILIKE {$searchLike} OR 
            c.contact_name3 ILIKE {$searchLike} OR 
            c.jobcompany ILIKE {$searchLike} OR 
            c.contact_email1 ILIKE {$searchLike} OR 
            c.contact_email2 ILIKE {$searchLike} OR 
            c.contact_email3 ILIKE {$searchLike} OR 
            c.contact_phone1 ILIKE {$searchLike} OR 
            c.contact_phone2 ILIKE {$searchLike} OR 
            c.contact_phone3 ILIKE {$searchLike} OR 
            c.contact_phone4 ILIKE {$searchLike} OR 
            c.address ILIKE {$searchLike} OR 
            c.billaddress ILIKE {$searchLike} OR 
            c.zip ILIKE {$searchLike} OR 
            c.npwpno ILIKE {$searchLike} OR 
            c.idnumber ILIKE {$searchLike} OR 
            c.businesscategory ILIKE {$searchLike} OR 
            CAST(c.contact_education AS TEXT) ILIKE {$searchLike} OR 
            (CASE WHEN {$bahasaQuoted} = 'id' THEN p.enumtext_id ELSE coalesce(p.enumtext_en,'') END) ILIKE {$searchLike}
        )";
        }

        $countfilter = "SELECT count(*) FROM (" . $sql . $filter . $searchFilter . ") as temp ";
        $sqlall = $sql . $filter . $searchFilter . $orderby;

        $rows = $db->createCommand($sqlall)->queryAll();
        $totalCount = $db->createCommand($countall)->queryScalar();
        $totalFilter = $db->createCommand($countfilter)->queryScalar();

        return [
            'data' => $rows ?: [],
            "draw" => intval($draw),
            "recordsTotal" => $totalCount,
            "recordsFiltered" => $totalFilter,
            'pagination' => [
                'more' => ($length + $start) < $totalCount,
            ],
        ];
    }

    /**
     * Get crew history for a specific contact
     * @return array
     */


    public function actionCrewhistory()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $db = Yii::$app->db;
        $contactid = Yii::$app->request->get('contact_id');

        if (empty($contactid)) {
            return [
                'success' => false,
                'message' => 'Contact ID is required',
                'data' => []
            ];
        }

        try {
            $userid = Yii::$app->user->id;
            $listBahasa = "SELECT lang FROM users WHERE userid = " . $db->quoteValue($userid);
            $bahasa = $db->createCommand($listBahasa)->queryScalar();
            $bahasaQuoted = $db->quoteValue($bahasa);
            $userIdQuoted = $db->quoteValue($userid);
            $contactIdQuoted = $db->quoteValue($contactid);

            $sql =
                "SELECT 
            TO_CHAR(te.startdate, 'DD/MM/YYYY') as startdate,
            TO_CHAR(te.enddate, 'DD/MM/YYYY') as enddate,
            te.eventtypeid,
            t.tranno,
            t.eventname,
            t.locations,
            CASE 
                WHEN {$bahasaQuoted} = 'id' THEN p.enumtext_id 
                ELSE COALESCE(p.enumtext_en, p.enumtext_id) 
            END AS crew_type,
            c.contact_name AS crew_name,
            tec.fee,
            tec.dinasfee,
            tec.jobid,
            f.filename,
            CASE 
                WHEN {$bahasaQuoted} = 'id' THEN j.enumtext_id 
                ELSE COALESCE(j.enumtext_en, j.enumtext_id) 
            END AS job_name
        FROM traneventcrews tec
        LEFT JOIN tranevents te ON te.traneventid = tec.traneventid
        LEFT JOIN trans t ON t.tranid = te.tranid
        LEFT JOIN contacts c ON c.contact_id = tec.crewid
        LEFT JOIN enum j ON j.enumid = tec.jobid AND j.enumtype = 'job'
        LEFT JOIN enum p ON p.enumid = 'position.'||tec.crewtypeid AND p.enumtype = 'position'
        LEFT JOIN facedata f ON f.user_id = {$userIdQuoted}
        WHERE tec.crewid = {$contactIdQuoted}
            AND t.status != 10
            AND te.status != 10
            AND tec.status != 10
        ORDER BY te.startdate DESC
    ";

            $data = $db->createCommand($sql)->queryAll();

            foreach ($data as &$row) {
                $row['fee'] = $row['fee'] ? 'Rp ' . number_format($row['fee'], 0, ',', '.') : '-';
                $row['dinasfee'] = $row['dinasfee'] ? 'Rp ' . number_format($row['dinasfee'], 0, ',', '.') : '-';
            }

            return [
                'success' => true,
                'data' => $data,
                'total' => count($data),
                'message' => count($data) > 0 ? 'Data found' : 'No data found'
            ];
        } catch (\Exception $e) {
            Yii::error('Error in actionCrewhistory: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error loading data: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    public function actionPhotos($contact_id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $db = Yii::$app->db;

        $contactIdQuoted = $db->quoteValue($contact_id);
        $sql = "SELECT f.filename, u.userid
        FROM facedata f
        INNER JOIN users u ON u.userid::text = f.user_id
        WHERE u.contact_id = {$contactIdQuoted}
        ORDER BY f.registration_date DESC";

        $rows = $db->createCommand($sql)->queryAll();

        if (empty($rows)) {
            return ['photos' => []];
        }

        $baseUrl = Yii::$app->request->baseUrl;

        $photos = array_map(function ($row) use ($baseUrl) {
            return [
                'url' => $baseUrl . '/contact/photobyfile'
                    . '?userid=' . urlencode($row['userid'])
                    . '&filename=' . urlencode($row['filename']),
            ];
        }, $rows);

        return ['photos' => $photos];
    }

    public function actionPhotobyfile($userid, $filename)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $emptyPath = Yii::getAlias('@webroot/assets/media/logos/empty.png');

        if (!preg_match('/^[\w\-]+$/', $userid) || !preg_match('/^[\w\-\.]+$/', $filename)) {
            Yii::$app->response->headers->set('Content-Type', 'image/png');
            return file_get_contents($emptyPath);
        }

        $pyUrl = 'https://dreamland-anew-pang.ngrok-free.dev';
        $imageUrl = $pyUrl . '/showimage/' . $userid . '/' . $filename;

        $ctx = stream_context_create([
            'http' => [
                'timeout' => 10,
                'ignore_errors' => true,
            ]
        ]);

        $imageData = @file_get_contents($imageUrl, false, $ctx);

        if ($imageData === false || empty($imageData)) {
            Yii::$app->response->headers->set('Content-Type', 'image/png');
            return file_get_contents($emptyPath);
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->buffer($imageData) ?: 'image/jpeg';

        Yii::$app->response->headers->set('Content-Type', $mime);
        Yii::$app->response->headers->set('Cache-Control', 'public, max-age=86400');
        return $imageData;
    }

    public function actionSelect()
    {
        $db = Yii::$app->db;
        $q = $_POST['q'] ?? '';
        $contacttype = $_POST['contacttype'] ?? '';
        $positionid = $_POST['positionid'] ?? '';
        $divisionid = $_POST['divisionid'] ?? '';
        $selectedJson = $_POST['selected'] ?? '';
        $date = $_POST['date'] ?? null;
        $type = $_POST['type'] ?? null;
        // var_dump($type);die;

        $selectedMap = json_decode($selectedJson, true);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['totalcount' => 0, 'items' => []];
        $filter = " WHERE 1=1 AND A.contact_status <> '10'";

        $limit = isset($_POST['limit']) ? (int) $_POST['limit'] : 5;
        $page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
        $start = $limit * ($page - 1);
        $order = " ORDER BY A.contact_no ASC LIMIT $limit OFFSET $start";

        if (!empty($q)) {
            $qLike = $db->quoteValue('%' . $q . '%');
            $filter .= " AND ("
                . "A.contact_name ILIKE {$qLike} "
                . " OR A.address ILIKE {$qLike} "
                . " OR A.jobcompany ILIKE {$qLike} "
                . " OR A.contact_no ILIKE {$qLike}"
                . ") ";
        }

        if (!empty($contacttype)) {
            $filter .= " AND A.contacttype = " . $db->quoteValue($contacttype);
        }

        if (!empty($divisionid)) {
            $filter .= " AND A.divisionid = " . $db->quoteValue('division.' . $divisionid);
        }

        if ($positionid == 'sb') {
            $positionid = 'cr';
        }

        if ($positionid == 'pi') {
            $filter .= " AND A.positionid IN ('position.pi', 'position.cr', 'position.op', 'position.fl')";
        } elseif ($positionid != '') {
            $filter .= " AND A.positionid = " . $db->quoteValue('position.' . $positionid);
        }

        if (!empty($date) && !empty($selectedMap[$date])) {
            $ids = $selectedMap[$date];

            $quoted = array_map(function ($v) use ($db) {
                return $db->quoteValue($v);
            }, $ids);

            $filter .= " AND A.contact_id NOT IN (" . implode(",", $quoted) . ")";
        }

        if (empty($type)) {
            $sql = "SELECT
            A.contact_id AS id,
            A.contact_no || ' [' || A.contact_name || ']' AS text,
            A.contact_name AS contact_name,
            A.contact_email1 AS contact_email,
            A.contact_phone1 AS contact_phone,
            A.jobcompany AS jobcompany,
            A.address AS contact_address
        FROM contacts A 
        $filter";
        } else {
            $sql = "SELECT
            A.contact_id AS id,
            A.contact_no || ' [' || A.jobcompany || ']' AS text,
            A.contact_name AS contact_name,
            A.contact_email1 AS contact_email,
            A.contact_phone1 AS contact_phone,
            A.jobcompany AS jobcompany,
            A.address AS contact_address
        FROM contacts A
        $filter";
        }

        $sqlcount = "SELECT COUNT(*) FROM ($sql) as temp";
        $query = $sql . $order;
        // echo $query;die;

        $data = $db->createCommand($query)->queryAll();
        $count = $db->createCommand($sqlcount)->queryScalar();

        $out['items'] = array_values($data);
        $out['totalcount'] = (int) $count;
        return $out;
    }

    public function actionLoadcontact()
    {
        if (Yii::$app->request->isAjax) {
            $db = Yii::$app->db;
            $id = "-";
            if (isset($_POST['id']) && $_POST['id'] != "") {
                $id = $_POST['id'];
            }
            $idQuoted = $db->quoteValue($id);
            $sql = "SELECT A.* FROM contacts A WHERE A.contact_id::text = {$idQuoted}";
            $rows = $db->createCommand($sql)->queryAll();
            return \yii\helpers\Json::encode($rows);
        }
    }

    public function actionLoadref()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $db = Yii::$app->db;
        $contactid = Yii::$app->request->get('contactid');
        $contactIdQuoted = $db->quoteValue($contactid);

        $sql =
            "SELECT 
                A.contact_id,
                A.contact_name,
                A.contact_name2,
                A.contact_name3,
                A.contact_phone1,
                A.contact_phone3,
                A.contact_phone4
            FROM contacts A
            WHERE A.contact_id = {$contactIdQuoted} 
            AND A.contact_status <> '10'
            LIMIT 1";
        $data = $db->createCommand($sql)->queryAll();

        return [
            'success' => true,
            'data' => array_values($data),
        ];
    }

    public function actionGetno()
    {
        $db = Yii::$app->db;
        $companyid = Yii::$app->session->get('companyid');

        try {
            $companyIdQuoted = $db->quoteValue($companyid);
            $sql = "SELECT COALESCE(MAX(NULLIF(REGEXP_REPLACE(contact_no, '[^0-9]', '', 'g'), '')::INTEGER), 0) + 1 AS no
                    FROM contact
                    WHERE companyid = {$companyIdQuoted} and contact_status = '1'
                    ";
            $results = $db->createCommand($sql)->queryOne();

            $nextKode = $results['no'] ?? 1;
            $fullKode = "CT-" . str_pad($nextKode, 3, "0", STR_PAD_LEFT);
            $angkaKode = preg_replace('/\D/', '', $fullKode);

        } catch (Exception $e) {
            $fullKode = $angkaKode;
        }

        return $this->asJson([
            "no" => $fullKode,
            "regno" => $angkaKode
        ]);
    }

    public function actionGetDashboardData($id)
    {
        $model = Contact::findOne($id);
        if (!$model || !$this->isAuthorized($model)) {
            throw new \yii\web\ForbiddenHttpException('Unauthorized');
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $db = Yii::$app->db;
        $idQuoted = $db->quoteValue($id);

        $youPayable = (new \yii\db\Query())
            ->from('tran')
            ->where(['trantype' => 'purchase', 'contact_id' => $id])
            ->andWhere(['!=', 'statuspaid', 'paid'])
            ->sum('grandtotal') ?? 0;

        $theyPayable = (new \yii\db\Query())
            ->from('tran')
            ->where(['trantype' => 'sales', 'contact_id' => $id])
            ->andWhere(['!=', 'statuspaid', 'paid'])
            ->sum('grandtotal') ?? 0;

        $paymentReceived = (new \yii\db\Query())
            ->from('tran')
            ->where(['trantype' => 'sales', 'contact_id' => $id])
            ->andWhere(['statuspaid' => 'paid'])
            ->sum('totalpaid') ?? 0;

        $moneyChart = $db->createCommand("
            SELECT TO_CHAR(trandate, 'Mon') as month,
                SUM(CASE WHEN trantype = 'sales' THEN grandtotal ELSE 0 END) as income,
                SUM(CASE WHEN trantype IN ('purchase', 'subscription') THEN grandtotal ELSE 0 END) as expense
            FROM tran
            WHERE contact_id = {$idQuoted}
            GROUP BY TO_CHAR(trandate, 'Mon'), EXTRACT(MONTH FROM trandate)
            ORDER BY EXTRACT(MONTH FROM trandate)
        ")->queryAll();
        $salesChart = $db->createCommand("
            SELECT TO_CHAR(trandate, 'Mon') as month,
                SUM(grandtotal) as total
            FROM tran
            WHERE trantype = 'sales' AND contact_id = {$idQuoted}
            GROUP BY TO_CHAR(trandate, 'Mon'), EXTRACT(MONTH FROM trandate)
            ORDER BY EXTRACT(MONTH FROM trandate)
        ")->queryAll();

        $months = array_column($moneyChart, 'month');
        return [
            'summary' => [
                'you_payable' => $youPayable,
                'they_payable' => $theyPayable,
                'payment_received' => $paymentReceived,
                'your_net_debt' => ($theyPayable - $youPayable),
            ],
            'graph' => [
                'months' => $months,
                'sales' => array_column($salesChart, 'total'),
                'purchase' => array_column($moneyChart, 'expense'),
            ],
        ];
    }

}
