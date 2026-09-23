<?php

namespace backend\controllers;

use Yii;
use common\models\Usergroup;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use common\models\User;
use common\models\Contact;
use common\models\Usermenu;
use yii\helpers\ArrayHelper;
use common\models\Model;


class UsersController extends Controller
{
    public function init()
    {
        parent::init();
        Yii::$app->language = Yii::$app->lang->getLang();
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                    ],
                    [
                        'actions' => [
                            'index',
                            'create',
                            'update',
                            'view',
                            'userslist',
                            'list',
                            'resetpassword',
                            'changepassword',
                            'updatepassword',
                            'profile',
                            'settings',
                            'delete',
                            'loadusergroup'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return Yii::$app->enum->isadmin();
                        },
                    ],
                    // hak lihat
                    [
                        'actions' => ['index', 'userslist', 'view', 'profile', 'settings', 'usergroup', 'load-usergroup'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return Yii::$app->enum->isakses("User", "lihat");
                        },
                    ],
                    // hak tambah
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return Yii::$app->enum->isakses("User", "tambah");
                        },
                    ],
                    // hak ubah
                    [
                        'actions' => ['update', 'changepassword', 'updatepassword', 'resetpassword'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return Yii::$app->enum->isakses("User", "ubah");
                        },
                    ],
                    // hak hapus
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return Yii::$app->enum->isakses("User", "hapus");
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if ($action->id === 'error') {
                $this->layout = 'error';
            }
            return true;
        }
        return false;
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUserslist()
    {
        return $this->render('userslist');
    }

    public function actionList()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $params = Yii::$app->request->queryParams;
        $contact = $params['contact'] ?? '';
        $search = $params['search'] ?? '';
        $positionFilter = $params['position'] ?? '';
        $sortcolumn = $params['order'][0]['column'] ?? 0;
        $ordercolumn = $params['columns'][$sortcolumn]['data'] ?? 'userid';
        $columnorder = $params['order'][0]['dir'] ?? 'DESC';

        $allowedColumns = ['userid', 'name', 'email', 'positionid', 'created_at', 'status', 'companyid', 'contact_name', 'position'];
        if (!in_array($ordercolumn, $allowedColumns)) {
            $ordercolumn = 'userid';
        }
        // var_dump($positionFilter);

        $currentUserId = Yii::$app->user->id;
        $currentCompanyId = Yii::$app->db->createCommand("
        SELECT companyid FROM users WHERE userid=:userid
    ")->bindValue(':userid', $currentUserId)->queryScalar();

        $query =
            "SELECT 
            u.userid, 
            u.name, 
            u.email, 
            u.avatar, 
            u.positionid, 
            e.enumtext_id AS position_name,
            e.enum_code_id AS position_code,   
            u.created_at, 
            u.status, 
            u.companyid, 
            c.contact_name, 
            c.jobcompany, 
            u.contact_id
        FROM users u
        LEFT JOIN contacts c ON c.contact_id = u.contact_id
        LEFT JOIN enum e ON e.enumid = u.positionid AND e.enumtype = 'position'
        WHERE (u.userid = '$currentUserId' OR u.companyid = '$currentCompanyId')
    ";

        if ($search !== "") {
            $query .= " AND (u.email ILIKE :search OR u.name ILIKE :search OR c.contact_name ILIKE :search)";
        }

        if ($contact !== '') {
            $query .= " AND c.contact_id = '" . intval($contact) . "'";
        }

        if ($positionFilter !== "") {
            $query .= " AND u.positionid = '$positionFilter'";
        }

        $query .= " ORDER BY $ordercolumn $columnorder";
        // echo $query;exit;

        $command = Yii::$app->db->createCommand($query);

        if ($search !== "") {
            $command->bindValue(':search', "%$search%");
        }

        $rows = $command->queryAll();
        $data = [];

        foreach ($rows as $row) {
            $status = ((int) $row['status'] === 1) ? 'Aktif' : 'Tidak Aktif';

            $positionName = $row['position_name'] ?? '';
            $positionCode = $row['position_code'] ?? '';

            if ($positionName && $positionCode) {
                $positionLabel = $positionName . " [" . $positionCode . "]";
            } else {
                $positionLabel = $positionName;
            }

            $data[] = [
                'userid' => $row['userid'],
                'name' => $row['name'],
                'email' => $row['email'],
                'avatar' => $row['avatar'],
                'position' => $positionLabel,
                'role' => $positionLabel,
                'created_at' => $row['created_at'],
                'status' => $status,
                'companyid' => $row['companyid'],
                'contact_name' => $row['contact_name'],
                'jobcompany' => $row['jobcompany'],
            ];
        }

        return ['data' => $data];
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionLoadusergroup()
    {
        $position = Yii::$app->request->post('position')
            ?? Yii::$app->request->get('position');

        $model = new User();
        $model->userid = null;

        return $this->renderAjax('_menucheckbox', [
            'model' => $model,
            'position' => $position,
        ]);
    }

    public function actionCreate()
    {
        $model = new User();
        $modelmenu = [];

        if ($model->load(Yii::$app->request->post())) {

            $model->positionid = Yii::$app->request->post('User')['positionid'] ?? null;  //baru 
            $postedPermissions = Yii::$app->request->post('permissions', []);

            if (!empty($postedPermissions)) {
                foreach ($postedPermissions as $menuId => $permissions) {
                    $usermenu = new Usermenu();
                    $usermenu->menuid = $menuId;
                    $usermenu->lihat = isset($permissions['lihat']) ? '1' : '0';
                    $usermenu->tambah = isset($permissions['tambah']) ? '1' : '0';
                    $usermenu->ubah = isset($permissions['ubah']) ? '1' : '0';
                    $usermenu->hapus = isset($permissions['hapus']) ? '1' : '0';
                    $usermenu->cetak = isset($permissions['cetak']) ? '1' : '0';
                    $usermenu->persetujuan = isset($permissions['persetujuan']) ? '1' : '0';
                    $modelmenu[] = $usermenu;
                }
            }

            $valid = $model->validate();
            $valid = Model::validateMultiple($modelmenu) && $valid;

            if ($valid) {
                try {
                    $model->role_id = 1;
                    $currentUserId = Yii::$app->user->id;
                    $currentCompanyId = Yii::$app->db->createCommand(
                        "SELECT companyid FROM users WHERE userid=:userid"
                    )->bindValue(':userid', $currentUserId)->queryScalar();
                    $model->companyid = $currentCompanyId;

                    if ($flag = $model->save()) {
                        foreach ($modelmenu as $mnu) {
                            $mnu->userid = $model->userid;
                            if (!($flag = $mnu->save(false)))
                                break;
                        }
                    }

                    if ($flag) {
                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = Response::FORMAT_JSON;
                            return [
                                'success' => true,
                                'pesan' => 'Data Berhasil Disimpan',
                                'id' => $model->userid,
                                'name' => $model->username
                            ];
                        }
                        return $this->redirect(['userslist']);
                    }

                } catch (\Exception $e) {
                    throw $e;
                }
            }

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_HTML;
                return $this->renderAjax('_form', [
                    'model' => $model,
                    'modelmenu' => empty($modelmenu) ? [new Usermenu] : $modelmenu,
                    'isajax' => 'true',
                    'position' => null
                ]);
            }
        }


        $sql =
            "SELECT 
            A.menu_id, 
            COALESCE(L.langtext_id, A.menu_name) AS menu_name,  
            A.menu_url, 
            A.menu_no, 
            A.menu_level,
            CASE A.menu_level 
                WHEN 0 THEN 'Area' 
                WHEN 1 THEN 'Pinjaman Dana' 
                ELSE '' 
            END as groupvw,
            COALESCE(B.lihat, '0') as lihat,
            COALESCE(B.tambah, '0') as tambah,
            COALESCE(B.ubah, '0') as ubah,
            COALESCE(B.hapus, '0') as hapus,
            COALESCE(B.cetak, '0') as cetak,
            COALESCE(B.persetujuan, '0') as persetujuan
        FROM menus A
        LEFT JOIN usermenu B 
            ON A.menu_id = B.menuid 
            AND B.userid::text = '-'
        LEFT JOIN lang L 
            ON L.langno = A.menu_name 
            AND L.langtype = 'extrasidebar'
        WHERE A.menu_id <> 15
        ORDER BY A.menu_no ASC;
    ";

        $rows = Yii::$app->db->createCommand($sql)->queryAll();

        $modelmenu = [];
        foreach ($rows as $i => $row) {
            $modelmenu[$i] = new Usermenu;
            $modelmenu[$i]->menuid = $row['menu_id'];
            $modelmenu[$i]->menuname = $row['menu_name'];
            $modelmenu[$i]->lihat = $row['lihat'];
            $modelmenu[$i]->tambah = $row['tambah'];
            $modelmenu[$i]->ubah = $row['ubah'];
            $modelmenu[$i]->hapus = $row['hapus'];
            $modelmenu[$i]->cetak = $row['cetak'];
            $modelmenu[$i]->persetujuan = $row['persetujuan'];
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
                'modelmenu' => empty($modelmenu) ? [new Usermenu] : $modelmenu,
                'isajax' => 'true',
                'position' => null
            ]);
        } else {
            return $this->render('_form', [
                'model' => $model,
                'modelmenu' => empty($modelmenu) ? [new Usermenu] : $modelmenu,
                'isajax' => 'false',
                'position' => null
            ]);
        }
    }
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->position_name = null;

        if (!empty($model->positionid)) {
            $position = Yii::$app->db->createCommand(
                "SELECT enumtext_id, enum_code_id
            FROM enum
            WHERE enumid = :pid AND enumtype = 'position'
        "
            )
                ->bindValue(':pid', $model->positionid)
                ->queryOne();

            if ($position) {
                $model->position_name = $position['enumtext_id'] . ' [' . $position['enum_code_id'] . ']';
            }
        }

        $modelmenu = Usermenu::find()->where(['userid' => $model->userid])->all();

        if (empty($modelmenu)) {
            $rows = Yii::$app->db->createCommand(
                "SELECT 
                A.menu_id, 
                COALESCE(L.langtext_id, A.menu_name) AS menu_name,  
                A.menu_url, 
                A.menu_no, 
                A.menu_level,
                CASE A.menu_level 
                    WHEN 0 THEN 'Area' 
                    WHEN 1 THEN 'Pinjaman Dana' 
                    ELSE '' 
                END as groupvw,
                COALESCE(B.lihat, '0') as lihat,
                COALESCE(B.tambah, '0') as tambah,
                COALESCE(B.ubah, '0') as ubah,
                COALESCE(B.hapus, '0') as hapus,
                COALESCE(B.cetak, '0') as cetak,
                COALESCE(B.persetujuan, '0') as persetujuans
            FROM menus A
            LEFT JOIN usermenu B ON A.menu_id = B.menuid AND B.userid = :userid
            LEFT JOIN lang L 
                ON L.langno = A.menu_name 
                AND L.langtype = 'extrasidebar'
            WHERE A.menu_id <> 15
            ORDER BY A.menu_no ASC
        "
            )->bindValue(':userid', (string) $model->userid)->queryAll();

            foreach ($rows as $i => $row) {
                $modelmenu[$i] = new Usermenu;
                $modelmenu[$i]->menuid = $row['menu_id'];
                $modelmenu[$i]->menuname = $row['menu_name'];
                $modelmenu[$i]->lihat = $row['lihat'];
                $modelmenu[$i]->tambah = $row['tambah'];
                $modelmenu[$i]->ubah = $row['ubah'];
                $modelmenu[$i]->hapus = $row['hapus'];
                $modelmenu[$i]->cetak = $row['cetak'];
                $modelmenu[$i]->persetujuan = $row['persetujuan'];
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            // var_dump(Yii::$app->request->post());exit;

            $model->positionid = Yii::$app->request->post('User')['positionid'] ?? null;

            $modelmenu = Model::createMultipleID(Usermenu::className(), $modelmenu, 'usermenuid');
            Model::loadMultiple($modelmenu, Yii::$app->request->post());

            $valid = $model->validate();
            $valid = Model::validateMultiple($modelmenu) && $valid;

            if ($valid) {
                try {

                    if ($flag = $model->save()) {

                        Usermenu::deleteAll(['userid' => $model->userid]);

                        $permissions = Yii::$app->request->post('permissions', []);
                        $fields = ['lihat', 'tambah', 'ubah', 'hapus', 'cetak', 'persetujuan'];

                        foreach ($permissions as $menuId => $perm) {
                            $usermenu = new Usermenu();
                            $usermenu->userid = $model->userid;
                            $usermenu->menuid = $menuId;

                            foreach ($fields as $f) {
                                $usermenu->$f = isset($perm[$f]) ? 1 : 0;
                            }

                            $usermenu->save(false);
                        }
                    }

                    if ($flag) {
                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = Response::FORMAT_JSON;
                            return [
                                'success' => true,
                                'pesan' => 'Data Berhasil Disimpan',
                                'id' => $model->userid,
                                'name' => $model->username
                            ];
                        }
                        return $this->redirect(['userslist']);
                    }

                } catch (\Exception $e) {
                    throw $e;
                }
            }

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_HTML;
                return $this->renderAjax('_form', [
                    'model' => $model,
                    'modelmenu' => empty($modelmenu) ? [new Usermenu] : $modelmenu,
                    'isajax' => 'true'
                ]);
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
                'modelmenu' => empty($modelmenu) ? [new Usermenu] : $modelmenu,
                'isajax' => 'true'
            ]);
        } else {
            return $this->render('_form', [
                'model' => $model,
                'modelmenu' => empty($modelmenu) ? [new Usermenu] : $modelmenu,
                'isajax' => 'false'
            ]);
        }
    }

    protected function findPosition($id)
    {
        $model = \common\models\Enum::find()
            ->where(['enum_id' => $id, 'enumtype' => 'position'])
            ->one();

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested position does not exist.');
    }

    public function actionUsergroup()
    {
        // var_dump("dsdfs");exit;
        // // Ambil position dari POST (select2)
        $positionId = Yii::$app->request->get('position');
        // var_dump( $positionId );exit;
        // // Jika belum memilih posisi → tampilkan form kosong
        // if (!$positionId) {
        //     return $this->render('form_group', [
        //         'model' => (object)['id' => null],
        //         'modelmenu' => [],
        //     ]);
        // }

        // Model dummy hanya untuk passing ke view
        // $model = (object)['id' => $positionId];

        // Ambil semua menu
        $sql = "
      SELECT 
        A.menu_id,A.menu_refid,
        COALESCE(L.langtext_id, A.menu_name) AS menu_name,
        A.menu_icon,
        U.usergroupid,
        COALESCE(U.lihat,'0') AS lihat,
        COALESCE(U.tambah,'0') AS tambah,
        COALESCE(U.ubah,'0') AS ubah,
        COALESCE(U.hapus,'0') AS hapus,
        COALESCE(U.cetak,'0') AS cetak,
        COALESCE(U.persetujuan,'0') AS persetujuan
    FROM menus A
    LEFT JOIN lang L 
        ON L.langno = A.menu_name 
    AND L.langtype = 'extrasidebar'
    LEFT JOIN usergroup U 
        ON U.menuid = A.menu_id 
    AND U.positionid::text = '" . $positionId . "'
    WHERE A.menu_status='1' 
    AND A.menu_level='2'
    -- AND A.menu_refid=:refid
    ORDER BY A.menu_id
    ";
        // var_dump($sql);exit;
        $rows = Yii::$app->db->createCommand($sql)->queryAll();

        $existing = Usergroup::find()
            ->where(['positionid' => $positionId])
            ->indexBy('menuid')
            ->all();

        $model = [];

        foreach ($rows as $row) {
            $item = new Usergroup();
            $item->positionid = $positionId;
            $item->menuid = $row['menu_id'];
            $item->menuname = $row['menu_name'];
            $item->menu_icon = $row['menu_icon'];
            $item->menu_refid = $row['menu_refid'];

            if (isset($existing[$row['menu_id']])) {
                $ex = $existing[$row['menu_id']];
                $item->lihat = $ex->lihat;
                $item->tambah = $ex->tambah;
                $item->ubah = $ex->ubah;
                $item->hapus = $ex->hapus;
                $item->cetak = $ex->cetak;
                $item->persetujuan = $ex->persetujuan;
            } else {
                $item->lihat = 0;
                $item->tambah = 0;
                $item->ubah = 0;
                $item->hapus = 0;
                $item->cetak = 0;
                $item->persetujuan = 0;
            }

            $model[] = $item;
        }

        // var_dump(Yii::$app->request->post('permissions'));exit;
        if (Yii::$app->request->post('permissions')) {

            $permissions = Yii::$app->request->post('permissions');
            $positionId = Yii::$app->request->post('position');

            Usergroup::deleteAll(['positionid' => $positionId]);

            foreach ($permissions as $menuId => $perm) {

                $row = new Usergroup();
                $row->positionid = $positionId;
                $row->menuid = $menuId;
                $row->lihat = isset($perm['lihat']) ? 1 : 0;
                $row->tambah = isset($perm['tambah']) ? 1 : 0;
                $row->ubah = isset($perm['ubah']) ? 1 : 0;
                $row->hapus = isset($perm['hapus']) ? 1 : 0;
                $row->cetak = isset($perm['cetak']) ? 1 : 0;
                $row->persetujuan = isset($perm['persetujuan']) ? 1 : 0;
                // var_dump($row);exit;
                $row->save(false);
            }


            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'success' => true,
                    'pesan' => 'Data Berhasil Disimpan',
                    'position' => $positionId,
                ];
            }


            Yii::$app->session->setFlash('success', 'Permission berhasil disimpan.');
            return $this->redirect(['users/usergroup']);
        }
        // var_dump($model);exit;
        return $this->render('form_group', [
            'model' => $model,
            // 'modelmenu' => $modelmenu,
        ]);
    }

    // public function actionProfile()
    // {
    //     $userId = Yii::$app->user->id;
    //     $model = $this->findModel($userId);

    //     // Jika form dikirim
    //     if ($model->load(Yii::$app->request->post())) {

    //         // Upload avatar (jika ada)
    //         $uploadedFile = \yii\web\UploadedFile::getInstance($model, 'avatarFile');
    //         if ($uploadedFile) {
    //             $fileName = 'avatar_' . $userId . '.' . $uploadedFile->extension;
    //             $uploadPath = Yii::getAlias('@uploads/avatars/') . $fileName;

    //             // Pastikan folder upload ada
    //             if (!is_dir(Yii::getAlias('@uploads/avatars/'))) {
    //                 mkdir(Yii::getAlias('@uploads/avatars/'), 0775, true);
    //             }

    //             if ($uploadedFile->saveAs($uploadPath)) {
    //                 $model->avatar = '/uploads/avatars/' . $fileName;
    //             }
    //         }

    //         // Simpan perubahan profil
    //         if ($model->save()) {
    //             if (Yii::$app->request->isAjax) {
    //                 Yii::$app->response->format = Response::FORMAT_JSON;
    //                 return [
    //                     'success' => true,
    //                     'message' => 'Profil berhasil diperbarui',
    //                     'avatar' => $model->avatar,
    //                 ];
    //             }

    //             Yii::$app->session->setFlash('success', 'Profil berhasil diperbarui.');
    //             return $this->redirect(['profile']);
    //         } else {
    //             if (Yii::$app->request->isAjax) {
    //                 Yii::$app->response->format = Response::FORMAT_JSON;
    //                 return [
    //                     'success' => false,
    //                     'message' => 'Gagal memperbarui profil',
    //                     'errors' => $model->errors,
    //                 ];
    //             }
    //         }
    //     }

    //     // Render tampilan profil
    //     if (Yii::$app->request->isAjax) {
    //         return $this->renderAjax('profile', [
    //             'model' => $model,
    //             'isajax' => 'true'
    //         ]);
    //     } else {
    //         return $this->render('profile', [
    //             'model' => $model,
    //             'isajax' => 'false'
    //         ]);
    //     }
    // }

    public function actionRequest($companyid = null)
    {
        $userId = Yii::$app->user->id;
        $session = Yii::$app->session;

        // Hapus session list company agar selalu update, tapi JANGAN hapus companyid
        $session->remove('company_list');

        // Ambil daftar company dan simpan di session
        $query = "SELECT * FROM company WHERE userid = '$userId' AND status = 1";
        $data = Yii::$app->db->createCommand($query)->queryAll();
        $session->set('company_list', $data);

        // Ambil companyid dari session atau database
        $companyid = $session->get('companyid');
        if (!$companyid) {
            $companyid = Yii::$app->db->createCommand("
            SELECT companyid FROM users WHERE userid = '$userId'
        ")->queryScalar();

            // Jika companyid ditemukan, simpan ke session
            if ($companyid) {
                $company = Yii::$app->db->createCommand("
                SELECT * FROM company WHERE companyid = '$companyid'
            ")->queryOne();

                if ($company) {
                    $session->set('companyid', $company['companyid']);
                    $session->set('company_data', $company);
                }
            }
        }

        $url = Yii::$app->request->url;
        // Default values
        $module = 'purchase';
        $type = 'request';

        if (preg_match('#/([^/]+)/([^/\?]+)#', $url, $matches)) {
            $module = $matches[1]; // purchase or sales
            $type = $matches[2];   // request, order, delivery, etc.
        }

        if (Yii::$app->request->get('module')) {
            $module = Yii::$app->request->get('module');
        }

        if (Yii::$app->request->get('type')) {
            $type = Yii::$app->request->get('type');
        }

        $searchModel = new User();

        $lastTran = Yii::$app->db->createCommand("
        SELECT tranno FROM trans WHERE status = 1 ORDER BY trandate DESC LIMIT 1")->queryOne();

        // Query to get all transactions with contact information where status = 1
        // Filter by trantype field to match the current module/type combination

        $session = Yii::$app->session;
        $companyid = $session->get('companyid');

        if (!$companyid) {
            // Jika companyid tidak ditemukan di session, ambil dari users
            $userId = Yii::$app->user->id;
            $companyid = Yii::$app->db->createCommand("
            SELECT companyid FROM users WHERE userid = '$userId'
        ")->queryScalar();
        }

        // var_dump($companyid);
        // die;

        // Jika companyid ditemukan, set nilai companyid pada transaksi
        // if ($companyid) {
        //     $companyid = $companyid;
        // }

        // Query transaksi
        $trantype = addslashes("$module/$type");
        $transactions = Yii::$app->db->createCommand("
        SELECT
            t.tranid,
            t.contact_id,
            t.trandate,
            t.tranduedate,
            t.tranno,
            t.status,
            t.trantype,
            c.contact_name AS contact_name
        FROM trans t
        LEFT JOIN contacts c ON c.contact_id = t.contact_id
        WHERE t.trantype = '$trantype' AND t.companyid = '$companyid'
        ORDER BY t.trandate DESC
    ")->queryAll();

        // Create a title based on the parameters - capitalize first letter of both module and type
        $title = ucfirst($module) . " " . ucfirst($type);

        $numbercode = Yii::$app->db->createCommand("SELECT * FROM numbertemplates where companyid = '$companyid' AND type = '$type'")->queryAll();
        // return var_dump($transactions , $companyid);
        // die;

        $previewcode = Tran::nextNoTransaksi();
        return $this->render('userslist', [
            'model' => $lastTran,
            'transactions' => $transactions,
            'searchModel' => $searchModel,
            'module' => $module,
            'type' => $type,
            'title' => $title,
            'numbercode' => $numbercode,
            'previewcode' => $previewcode
        ]);
    }
    public function actionResetpassword()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        if (!$id) {
            return ['success' => false, 'message' => 'ID tidak ditemukan'];
        }

        $model = $this->findModel($id);

        if (!$model) {
            return ['success' => false, 'message' => 'User tidak ditemukan'];
        }

        if ($model->resetPasswordToName()) {
            return [
                'success' => true,
                'message' => "Password user berhasil di-reset menjadi '{$model->name}'"
            ];
        }

        return ['success' => false, 'message' => 'Gagal reset password'];
    }

    public function actionDelete()
    {
        $id = Yii::$app->request->post('id'); // ambil ID dari request POST
        if (!$id) {
            return $this->asJson(['success' => false, 'message' => 'ID tidak ditemukan']);
        }

        $user = User::findOne($id);
        if (!$user) {
            return $this->asJson(['success' => false, 'message' => 'User tidak ditemukan']);
        }

        if ($user->delete()) {
            return $this->asJson(['success' => true, 'message' => 'User berhasil dihapus']);
        } else {
            return $this->asJson(['success' => false, 'message' => 'Gagal menghapus user']);
        }
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested user does not exist.');
    }

}
