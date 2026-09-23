<?php

namespace backend\controllers;

use Yii;
use yii\db\Query;
use yii\helpers\Url;
use common\models\User;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\Leave;
use common\models\Tran;
use common\models\Trandetail;
use common\models\Tranevent;
use common\models\Traneventcrew;
use common\models\Document;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use backend\controllers\BaseController;
use JavaClass;
use Java;

/**
 * LeaveController implements the CRUD actions for Leave model.
 */
class LeaveController extends BaseController
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
                    'select' => ['POST', 'GET'],
                    'selectparttime' => ['POST', 'GET'],
                    'selectparttimeattendance' => ['POST', 'GET'],
                    'exportbiofinger' => ['GET'],
                    'crewtypelist' => ['GET'],
                    'crewlist' => ['GET'],
                    'crewhistory' => ['GET'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'create',
                            'createabs',
                            'update',
                            'updateabs',
                            'updatestatus',
                            'view',
                            'print',
                            'GetEnumText',
                            'delete',
                            'list',
                            'listabs',
                            'detail',
                            'getno',
                            'nextparttimeno',
                            'getworkdates',
                            'selectparttime',
                            'selectparttimeattendance',
                            'leavetypelist',
                            'filter',
                            'deletemassal',
                            'approve',
                            'reject',
                            'import',
                            'importclockhistory',
                            'getparttimeprofile',
                            'getparttimeattendance',
                            'getparttimepayment',
                            'downloadtemplate',
                            'downloadsamplereal',
                            'exportbiofinger',
                            'savetemplate',
                            'settemplate',
                            'resettemplate',
                            'previewcode',
                            'select',
                            'selectref',
                            'crewtypelist',
                            'crewlist',
                            'crewhistory',
                            'crewhistoryabs'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return true;
                        }
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isaksestype("leave", "lihat"));
                        }
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isaksestype("leave", "tambah"));
                        }
                    ],
                    [
                        'actions' => ['update', 'updateabs'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isaksestype("leave", "ubah"));
                        }
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isaksestype("leave", "hapus"));
                        }
                    ],
                    [
                        'actions' => [
                            'index',
                            'create',
                            'createabs',
                            'update',
                            'updateabs',
                            'print',
                            'list',
                            'listabs',
                            'detail',
                            'GetEnumText',
                            'leavetypelist',
                            'selectparttime',
                            'selectparttimeattendance',
                            'getno',
                            'filter',
                            'deletemassal',
                            'approve',
                            'import',
                            'importclockhistory',
                            'downloadtemplate',
                            'downloadsamplereal',
                            'reject',
                            'savetemplate',
                            'settemplate',
                            'resettemplate',
                            'previewcode',
                            'select',
                            'selectref',
                            'crewtypelist',
                            'crewlist',
                            'crewhistory',
                            'crewhistoryabs'
                        ],
                        'allow' => true,
                        'roles' => ['?', '@']
                    ],
                    [
                        'actions' => [
                            'index',
                            'create',
                            'createabs',
                            'update',
                            'updateabs',
                            'view',
                            'print',
                            'GetEnumText',
                            'delete',
                            'list',
                            'listabs',
                            'detail',
                            'getno',
                            'nextparttimeno',
                            'getworkdates',
                            'selectparttime',
                            'selectparttimeattendance',
                            'leavetypelist',
                            'filter',
                            'deletemassal',
                            'approve',
                            'reject',
                            'import',
                            'importclockhistory',
                            'getparttimeprofile',
                            'getparttimeattendance',
                            'getparttimepayment',
                            'downloadtemplate',
                            'downloadsamplereal',
                            'exportbiofinger',
                            'exportexcel',
                            'exportsingle',
                            'savetemplate',
                            'settemplate',
                            'resettemplate',
                            'previewcode',
                            'select',
                            'selectref',
                            'crewtypelist',
                            'crewlist',
                            'crewhistory',
                            'crewhistoryabs'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return true;
                        }
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($leavetype = null, $tab = 'profiledata')
    {
        if (empty($leavetype)) {
            $leavetype = Yii::$app->request->get('leavetype');
        }

        if (empty($leavetype)) {
            $path = Yii::$app->request->pathInfo;
            $types = ['absent', 'operational', 'leave', 'parttime'];

            foreach ($types as $type) {
                if (strpos($path, $type) === 0) {
                    $leavetype = $type;
                    break;
                }
            }
        }

        $tab = Yii::$app->request->get('tab', $tab);

        $searchModel = new Leave();
        $user = Yii::$app->user;
        $access = $user->identity->getAccess('leave');

        $viewFile = 'index';

        return $this->render($viewFile, [
            'access' => $access,
            'searchModel' => $searchModel,
            'leavetype' => $leavetype,
            'tab' => $tab,
        ]);
    }

    /**
     * Export single record to Excel
     */
    public function actionExportsingle($id = null, $leavetype = '', $tab = '')
    {
        if (empty($id) || $id === 'undefined' || $id === 'null') {
            Yii::error(" Invalid ID: '$id'", 'leave-export');
            Yii::$app->session->setFlash('error', 'ID tidak valid.');
            return $this->redirect(['index']);
        }

        if (empty($leavetype)) {
            $path = Yii::$app->request->pathInfo;
            if (strpos($path, 'absent') === 0) {
                $leavetype = 'absent';
            } elseif (strpos($path, 'leave') === 0) {
                $leavetype = 'leave';
            } elseif (strpos($path, 'parttime') === 0) {
                $leavetype = 'parttime';
            }
        }

        $tab = $tab ?: 'profiledata';

        Yii::info("📤 Exporting: ID=$id, Type=$leavetype, Tab=$tab", 'leave-export');

        try {
            // var_dump("dika");exit;
            // 3. CEK DULU APAKAH DATA ADA
            $exists = Yii::$app->db->createCommand(
                "SELECT leaveid FROM leave WHERE leaveid = :id AND status != '10'"
            )->bindValue(':id', $id)->queryScalar();

            if (!$exists) {
                Yii::error(" Data not found: $id", 'leave-export');
                Yii::$app->session->setFlash('error', 'Data tidak ditemukan.');
                return $this->redirect(['index', 'leavetype' => $leavetype, 'tab' => $tab]);
            }

            // 4. BUILD SQL DENGAN WHERE YANG BENAR
            // $whereConditions = ["l.status != '10'", "l.leaveid = :id"];
            // $queryParams = [':id' => $id];
            // $id 
            $filter = " WHERE 1=1 and l.status != '10' and l.leaveid = '$id' ";

            // Filter by leavetype/tab
            if ($leavetype === 'parttime') {
                if ($tab === 'profiledata') {
                    $filter .= " AND l.parttime_no IS NOT NULL";
                    $filter .= " AND l.contactid IS NULL";
                } elseif ($tab === 'workattendance') {
                    $filter .= " AND l.contactid IS NOT NULL";
                    $filter .= " AND (l.check_in IS NOT NULL OR l.check_out IS NOT NULL)";
                } elseif ($tab === 'parttimepayment') {
                    $filter .= " AND l.contactid IS NOT NULL";
                    $filter .= " AND l.payment IS NOT NULL OR l.total IS NOT NULL)";
                }
            } else {
                $filter .= " AND (l.parttime_no IS NULL OR l.parttime_no = '')";
                if ($leavetype === 'leave') {
                    $filter .= " AND lt.enumtype = 'leavetype'";
                    $enumtype = 'leavetype';
                } elseif ($leavetype === 'absent') {
                    $filter .= " AND lt.enumtype = 'absenttype'";
                    $enumtype = 'absenttype';
                }
            }


            // $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);

            // 5. BUILD SQL BERDASARKAN TAB
            if ($leavetype === 'parttime' && $tab === 'profiledata') {
                $sql = "
                SELECT 
                    l.parttime_no AS No_Parttime,
                    l.parttime_name AS Nama,
                    g.enumtext_id AS Grade,
                    ml.enumtext_id AS Member_Level,
                    jp.enumtext_id AS Jenis_Parttime,
                    l.working_at AS Tempat_Bekerja,
                    l.contact_phone1 AS Telepon,
                    l.contact_email1 AS Email,
                    TO_CHAR(l.tanggal_mulai, 'DD-MM-YYYY') AS Tanggal_Mulai,
                    CASE WHEN l.is_active = '1' THEN 'Aktif' ELSE 'Tidak Aktif' END AS Status
                FROM leave l
                LEFT JOIN enum g ON g.enumid = l.grade
                LEFT JOIN enum ml ON ml.enumid = l.member_level
                LEFT JOIN enum jp ON jp.enumid = l.jenis_parttime
                LEFT JOIN enum lt ON lt.enumid = l.leavetype
                $filter
            ";
            } elseif ($leavetype === 'parttime' && $tab === 'workattendance') {
                $sql = "
                SELECT 
                    COALESCE(l.parttime_no, lp.parttime_no) AS No. Parttime,
                    COALESCE(l.parttime_name, lp.parttime_name) AS Nama,
                    TO_CHAR(l.leavedate, 'DD-MM-YYYY') AS Tanggal,
                    TO_CHAR(l.check_in, 'HH24:MI') AS Jam Masuk,
                    TO_CHAR(l.check_out, 'HH24:MI') AS Jam Keluar,
                    l.late_duration AS Terlambat,
                    l.overtime_duration AS Lembur,
                    lt.enumtext_id AS Jenis Absensi
                FROM leave l
                LEFT JOIN leave lp ON lp.leaveid = l.contactid AND lp.parttime_no IS NOT NULL
                LEFT JOIN enum lt ON lt.enumid = l.leavetype
                $filter
            ";
            } elseif ($leavetype === 'parttime' && $tab === 'parttimepayment') {
                $sql = "
                SELECT 
                    COALESCE(l.parttime_no, lp.parttime_no) AS No. Parttime,
                    COALESCE(l.parttime_name, lp.parttime_name) AS Nama,
                    TO_CHAR(l.leavedate, 'DD-MM-YYYY') AS Tanggal,
                    pmt.enumtext_id AS Metode Pembayaran,
                    l.total AS Total,
                    TO_CHAR(l.payment_date, 'DD-MM-YYYY') AS Tanggal Bayar,
                    ps.enumtext_id AS Status
                FROM leave l
                LEFT JOIN leave lp ON lp.leaveid = l.contactid AND lp.parttime_no IS NOT NULL
                LEFT JOIN enum pmt ON pmt.enumid = l.payment
                LEFT JOIN enum ps ON ps.enumid = l.payment_status
                $filter
            ";
            } elseif ($leavetype === 'absent') {
                $sql = "
                SELECT 
                    c.contact_name AS Nama,
                    l.employee_code AS Kode,
                    lt.enumtext_id AS Jenis,
                    TO_CHAR(l.leavedate, 'DD-MM-YYYY') AS Tanggal,
                    TO_CHAR(l.check_in, 'HH24:MI') AS Masuk,
                    TO_CHAR(l.check_out, 'HH24:MI') AS Keluar
                FROM leave l
                LEFT JOIN contacts c ON c.contact_id = l.contactid
                LEFT JOIN enum lt ON lt.enumid = l.leavetype
                $filter
            ";
            } else {
                $sql = "
                SELECT 
                    c.contact_name AS Nama,
                    l.employee_code AS Kode,
                    lt.enumtext_id AS Jenis,
                    TO_CHAR(l.leavedate, 'DD-MM-YYYY') AS Mulai,
                    TO_CHAR(l.leaveduedate, 'DD-MM-YYYY') AS Selesai,
                    l.note AS Alasan
                FROM leave l
                LEFT JOIN contacts c ON c.contact_id = l.contactid
                LEFT JOIN enum lt ON lt.enumid = l.leavetype
                $filter
            ";
            }
            // echo $sql;exit;
            $data = Yii::$app->db->createCommand($sql)->queryAll();
            // var_dump($data);exit;

            if (empty($data)) {
                Yii::warning(" No data found for ID: $id", 'leave-export');
                Yii::$app->session->setFlash('warning', 'Data tidak ditemukan.');
                return $this->redirect(['index', 'leavetype' => $leavetype, 'tab' => $tab]);
            }

            // 7. GENERATE FILENAME
            $personName = '';
            if ($leavetype === 'parttime') {
                $personName = $data[0]['Nama'] ?? $data[0]['No. Parttime'] ?? 'Parttime';
            } else {
                $personName = $data[0]['Nama'] ?? 'Employee';
            }
            $personName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $personName);

            $tabName = $leavetype === 'parttime' ? '_' . ucfirst($tab) : '';
            $filename = 'Export_' . ucfirst($leavetype) . $tabName . '_' . $personName . '_' . date('Ymd_His') . '.csv';

            // 8. OUTPUT HEADERS (CRITICAL!)
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Pragma: no-cache');
            header('Expires: 0');

            $output = fopen('php://output', 'w');

            // UTF-8 BOM
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Write header
            if (!empty($data)) {
                fputcsv($output, array_keys($data[0]));
            }

            // Write data
            foreach ($data as $row) {
                if (isset($row['Total']) && $row['Total']) {
                    $row['Total'] = 'Rp ' . number_format($row['Total'], 0, ',', '.');
                }
                fputcsv($output, array_values($row));
            }

            fclose($output);
            Yii::$app->end(); // CRITICAL: Stop script execution

        } catch (\Exception $e) {
            Yii::error(' Export Error: ' . $e->getMessage(), 'leave-export');
            Yii::$app->session->setFlash('error', 'Gagal export: ' . $e->getMessage());
            return $this->redirect(['index', 'leavetype' => $leavetype, 'tab' => $tab]);
        }
    }
    /**
     * Creates a new Leave model.
     */

    public function actionCreate($leavetype = '')
    {
        $model = new Leave();

        if (empty($leavetype)) {
            $leavetype = Yii::$app->request->get('leavetype', 'leave');
        }

        $tab = Yii::$app->request->get('tab', 'profiledata');

        if (!empty($leavetype) && $leavetype === 'absent' && $model->isNewRecord) {
            $hadirEnumId = Yii::$app->db->createCommand(
                "SELECT enumid FROM enum 
             WHERE enumtype = 'absenttype' 
             AND LOWER(enumtext_id) = 'hadir' 
             LIMIT 1"
            )->queryScalar();

            if ($hadirEnumId) {
                $model->leavetype = $hadirEnumId;
                Yii::info('Auto-set leavetype to Hadir: ' . $hadirEnumId, 'leave');
            }
        }

        if ($model->isNewRecord && $leavetype === 'parttime' && $tab === 'profiledata') {
            $model->parttime_no = $this->generateParttimeNo();
            Yii::info("Pre-filled parttime_no: {$model->parttime_no}", 'leave');
        }

        if ($model->load(Yii::$app->request->post())) {
            Yii::info('=== START LEAVE CREATE ===', 'leave');

            if (empty($model->leaveno)) {
                $model->leaveno = Leave::nextNo($leavetype);
            }

            if (!empty($model->contactid) && empty($model->employee_code)) {
                $employeeCode = Yii::$app->db->createCommand(
                    "SELECT contact_no FROM contacts WHERE contact_id = :id"
                )->bindValue(':id', $model->contactid)->queryScalar();

                if ($employeeCode) {
                    $model->employee_code = $employeeCode;
                }
            }

            if ($leavetype === 'parttime' && $tab === 'parttimepayment') {
                $workDateIds = Yii::$app->request->post('Leave')['work_date_ids'] ?? [];

                if (empty($workDateIds)) {
                    throw new \Exception('Pilih minimal 1 tanggal kerja yang akan dibayar!');
                }

                $parttimeProfileId = $model->contactid;

                if (!$parttimeProfileId) {
                    throw new \Exception('Pilih data parttime terlebih dahulu!');
                }

                Yii::info("💰 Creating payment for " . count($workDateIds) . " work dates", 'leave');

                $profileData = Yii::$app->db->createCommand(
                    "SELECT parttime_no, parttime_name, payment, bankname, bankaccount_no, 
                        account_owner_name, hubungan_pemilik_rekening 
                    FROM leave 
                    WHERE leaveid = :id 
                    AND parttime_no IS NOT NULL
                    LIMIT 1"
                )->bindValue(':id', $parttimeProfileId)->queryOne();

                if (!$profileData) {
                    throw new \Exception('Data parttime profile tidak ditemukan!');
                }

                $pendingId = Yii::$app->db->createCommand(
                    "SELECT enumid FROM enum 
                        WHERE enumtype = 'payment_status' 
                        AND LOWER(enumtext_id) LIKE '%pending%' 
                        LIMIT 1"
                )->queryScalar();

                $alreadyPaidCheck = Yii::$app->db->createCommand(
                    "SELECT contactid FROM leave 
                    WHERE contactid IN (" . implode(',', array_map(function ($id) {
                        return "'" . $id . "'";
                    }, $workDateIds)) . ")
                    AND status != '10'
                    AND (total IS NOT NULL OR payment_date IS NOT NULL)"
                )->queryColumn();

                if (!empty($alreadyPaidCheck)) {
                    throw new \Exception('Beberapa tanggal yang dipilih sudah dibayar! Silakan refresh dan pilih ulang.');
                }

                $successCount = 0;
                $errors = [];

                foreach ($workDateIds as $attendanceId) {
                    $paymentModel = new Leave();

                    try {
                        $paymentModel->leaveid = Yii::$app->db->createCommand("SELECT uuid_generate_v4()")->queryScalar();

                        $paymentModel->contactid = $attendanceId;

                        $paymentModel->payment = $model->payment;
                        $paymentModel->total = $model->total;
                        $paymentModel->amount = $model->amount;
                        $paymentModel->payment_date = !empty($model->payment_date)
                            ? date("Y-m-d", strtotime(str_replace('/', '-', $model->payment_date)))
                            : null;
                        $paymentModel->payment_status = !empty($model->payment_status)
                            ? $model->payment_status
                            : $pendingId;

                        $paymentModel->parttime_no = $profileData['parttime_no'];
                        $paymentModel->parttime_name = $profileData['parttime_name'];
                        $paymentModel->bankname = $profileData['bankname'];
                        $paymentModel->bankaccount_no = $profileData['bankaccount_no'];
                        $paymentModel->account_owner_name = $profileData['account_owner_name'];
                        $paymentModel->hubungan_pemilik_rekening = $profileData['hubungan_pemilik_rekening'];

                        $attendanceData = Yii::$app->db->createCommand(
                            "SELECT leavedate, leavetype FROM leave WHERE leaveid = :id"
                        )->bindValue(':id', $attendanceId)->queryOne();

                        if (!$attendanceData) {
                            throw new \Exception("Attendance not found: $attendanceId");
                        }

                        $paymentModel->leavedate = $attendanceData['leavedate'];
                        $paymentModel->leaveduedate = $attendanceData['leavedate'];
                        $paymentModel->leavetype = $attendanceData['leavetype'];

                        $paymentModel->leaveno = Leave::nextNo('parttime');

                        $paymentModel->status = 1;
                        $paymentModel->employee_code = 'N/A';
                        $paymentModel->temperature = '0.00';
                        $paymentModel->evaluation = 'OK';
                        $paymentModel->note = 'Payment untuk tanggal ' . date('d/m/Y', strtotime($attendanceData['leavedate']));

                        $paymentModel->check_in = null;
                        $paymentModel->check_out = null;
                        $paymentModel->late_duration = null;
                        $paymentModel->overtime_duration = null;

                        $attachment = \yii\web\UploadedFile::getInstance($model, 'fileUpload');
                        if (!empty($attachment) && $attachment->size > 0) {
                            $attachmentFilename = $paymentModel->leaveid . '-attachment-' . time() . "." . $attachment->extension;
                            $attachmentPath = Yii::getAlias('@webroot/uploads/leave/');

                            if (!is_dir($attachmentPath)) {
                                mkdir($attachmentPath, 0755, true);
                            }

                            if ($attachment->saveAs($attachmentPath . $attachmentFilename)) {
                                $paymentModel->attachment = $attachmentFilename;
                                Yii::info("Attachment saved: $attachmentFilename", 'leave');
                            }
                        }

                        if (!$paymentModel->save(false)) {
                            throw new \Exception('Save failed: ' . json_encode($paymentModel->errors));
                        }

                        $successCount++;
                        Yii::info("Payment: {$paymentModel->leaveno} for $attendanceId", 'leave');
                    } catch (\Exception $e) {
                        $errors[] = "Attendance $attendanceId: " . $e->getMessage();
                        Yii::error(" Error: " . $e->getMessage(), 'leave');
                    }
                }

                if ($successCount === 0) {
                    throw new \Exception('Gagal membuat payment: ' . implode('; ', $errors));
                }

                if ($successCount < count($workDateIds)) {
                    Yii::$app->session->setFlash(
                        'warning',
                        "Berhasil: $successCount, Gagal: " . (count($workDateIds) - $successCount)
                    );
                }

                // $transaction->commit();

                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'success' => true,
                        'message' => "Payment berhasil dibuat untuk $successCount tanggal kerja"
                    ];
                }

                Yii::$app->session->setFlash('success', "Payment berhasil dibuat untuk $successCount tanggal kerja");
                return $this->redirect(['index', 'leavetype' => $leavetype, 'tab' => $tab]);
            }

            if (empty($model->employee_code)) {
                $model->employee_code = 'N/A';
            }

            if (empty($model->temperature)) {
                $model->temperature = '0.00';
            }

            if (empty($model->evaluation)) {
                $model->evaluation = 'OK';
            }

            $model->leavedate = !empty($model->leavedate)
                ? date("Y-m-d", strtotime(str_replace('/', '-', $model->leavedate)))
                : null;

            $model->leaveduedate = !empty($model->leaveduedate)
                ? date("Y-m-d", strtotime(str_replace('/', '-', $model->leaveduedate)))
                : null;

            if ($leavetype === 'parttime' && !empty($model->payment_date)) {
                $model->payment_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->payment_date)));
            }

            if ($leavetype === 'parttime') {
                if (!empty($model->tanggal_mulai)) {
                    $model->tanggal_mulai = date("Y-m-d", strtotime(str_replace('/', '-', $model->tanggal_mulai)));
                }

                if (!empty($model->birth_date)) {
                    $model->birth_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->birth_date)));
                    Yii::info("Formatted birth_date: " . $model->birth_date, 'leave');
                }

                if (!empty($model->contact_bod)) {
                    $model->contact_bod = date("Y-m-d", strtotime(str_replace('/', '-', $model->contact_bod)));
                }

                if ($leavetype === 'parttime' && $tab === 'parttimepayment') {

                    $workDateIds = Yii::$app->request->post('Leave')['work_date_ids'] ?? [];

                    if (!empty($workDateIds)) {
                        $firstWorkDate = Yii::$app->db->createCommand(
                            "SELECT leavedate FROM leave WHERE leaveid = :id LIMIT 1"
                        )->bindValue(':id', $workDateIds[0])->queryScalar();

                        if ($firstWorkDate) {
                            $model->leavedate = $firstWorkDate;
                            $model->leaveduedate = $firstWorkDate;
                            Yii::info("Auto-filled leavedate from work_dates: $firstWorkDate", 'leave');
                        }
                    }

                    if (empty($model->leavedate) && !empty($model->payment_date)) {
                        $model->leavedate = $model->payment_date;
                        $model->leaveduedate = $model->payment_date;
                        Yii::info("Using payment_date as leavedate: {$model->leavedate}", 'leave');
                    }

                    if (empty($model->leavedate)) {
                        $model->leavedate = date('Y-m-d');
                        $model->leaveduedate = date('Y-m-d');
                        Yii::warning(" leavedate empty, using today: {$model->leavedate}", 'leave');
                    }

                    if (empty($model->payment_status)) {
                        $pendingId = Yii::$app->db->createCommand(
                            "SELECT enumid FROM enum 
                        WHERE enumtype = 'payment_status' 
                        AND LOWER(enumtext_id) LIKE '%pending%' 
                        LIMIT 1"
                        )->queryScalar();

                        if ($pendingId) {
                            $model->payment_status = $pendingId;
                        }
                    }
                }

                $familyDateFields = ['mother_bod', 'father_bod', 'guardian_bod', 'spouse_bod'];
                foreach ($familyDateFields as $field) {
                    if (!empty($model->$field)) {
                        $model->$field = date("Y-m-d", strtotime(str_replace('/', '-', $model->$field)));
                    }
                }
            }

            if ($leavetype === 'absent' || $leavetype === 'parttime') {
                $dateForCheckTime = $model->leavedate;

                if (!empty($model->check_in)) {
                    if (preg_match('/^\d{1,2}:\d{2}$/', $model->check_in)) {
                        $model->check_in = $dateForCheckTime . ' ' . $model->check_in . ':00';
                    }
                } else {
                    $model->check_in = null;
                }

                if (!empty($model->check_out)) {
                    if (preg_match('/^\d{1,2}:\d{2}$/', $model->check_out)) {
                        $model->check_out = $dateForCheckTime . ' ' . $model->check_out . ':00';
                    }
                } else {
                    $model->check_out = null;
                }

                $model->autoCalculateDurations();
            } else {
                $model->check_in = null;
                $model->check_out = null;
                $model->late_duration = null;
                $model->overtime_duration = null;
            }

            $model->status = 1;

            if ($model->validate(false)) {
                Yii::info('Validation passed', 'leave');

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $identityCardFile = \yii\web\UploadedFile::getInstance($model, 'identitycardfile');
                    if (!empty($identityCardFile)) {
                        $identityCardFile->name = ($model->parttime_name ?? 'parttime') . '-ktp-' . time() . '.' . $identityCardFile->extension;
                        $model->identitycardfile = $identityCardFile->name;
                    }

                    $studentCardFile = \yii\web\UploadedFile::getInstance($model, 'studentcardfile');
                    if (!empty($studentCardFile)) {
                        $studentCardFile->name = ($model->parttime_name ?? 'parttime') . '-kartu-pelajar-' . time() . '.' . $studentCardFile->extension;
                        $model->studentcardfile = $studentCardFile->name;
                    }

                    $pictEmployee = \yii\web\UploadedFile::getInstance($model, 'pict_employee');
                    $webcamPhoto = Yii::$app->request->post('webcam_photo');

                    if (!empty($pictEmployee) && $pictEmployee->size > 0) {
                        $filename = 'leave-' . time() . '.' . $pictEmployee->extension;
                        $path = Yii::getAlias('@webroot/uploads/leave/');

                        if (!is_dir($path)) {
                            mkdir($path, 0777, true);
                        }

                        if ($pictEmployee->saveAs($path . $filename)) {
                            $model->pict_employee = $filename;
                            Yii::info("File upload saved: $filename", 'leave');
                        }
                    } elseif (!empty($webcamPhoto) && strpos($webcamPhoto, 'data:image') === 0) {
                        list($type, $data) = explode(';', $webcamPhoto);
                        list(, $data) = explode(',', $data);
                        $imageData = base64_decode($data);

                        $filename = 'leave-webcam-' . time() . '.jpg';
                        $path = Yii::getAlias('@webroot/uploads/leave/');

                        if (!is_dir($path)) {
                            mkdir($path, 0777, true);
                        }

                        if (file_put_contents($path . $filename, $imageData)) {
                            $model->pict_employee = $filename;
                            Yii::info("Webcam photo saved: $filename", 'leave');
                        }
                    } else {
                        $oldPictEmployeeFromPost = Yii::$app->request->post('old_pict_employee');
                        if (!empty($oldPictEmployeeFromPost)) {
                            $model->pict_employee = $oldPictEmployeeFromPost;
                            Yii::info("Keeping old photo: $oldPictEmployeeFromPost", 'leave');
                        }
                    }


                    if ($model->save(false)) {
                        Yii::info('Model saved with ID: ' . $model->leaveid, 'leave');

                        if (!empty($identityCardFile)) {
                            $path = Yii::getAlias('@webroot/uploads/leave/');
                            if (!is_dir($path)) {
                                mkdir($path, 0777, true);
                            }
                            $identityCardFile->saveAs($path . $identityCardFile->name);
                            Yii::info("KTP saved: " . $identityCardFile->name, 'leave');
                        }

                        if (!empty($studentCardFile)) {
                            $path = Yii::getAlias('@webroot/uploads/leave/');
                            if (!is_dir($path)) {
                                mkdir($path, 0777, true);
                            }
                            $studentCardFile->saveAs($path . $studentCardFile->name);
                            Yii::info("Kartu Pelajar saved: " . $studentCardFile->name, 'leave');
                        }

                        if (!empty($pictEmployee) && empty($webcamPhoto)) {
                            $path = Yii::getAlias('@webroot/uploads/leave/') . $pictEmployee->name;
                            $pictEmployee->saveAs($path);
                        }

                        $attachment = \yii\web\UploadedFile::getInstance($model, 'fileUpload');
                        if (!empty($attachment) && $attachment->size > 0) {
                            $attachmentFilename = $model->leaveid . '-attachment-' . time() . "." . $attachment->extension;

                            $attachmentPath = Yii::getAlias('@webroot/uploads/leave/');
                            if (!is_dir($attachmentPath)) {
                                mkdir($attachmentPath, 0755, true);
                            }

                            $fullPath = $attachmentPath . $attachmentFilename;
                            if ($attachment->saveAs($fullPath)) {
                                Yii::info('Attachment saved: ' . $fullPath, 'leave');

                                Leave::updateAll(
                                    ['attachment' => $attachmentFilename],
                                    ['leaveid' => $model->leaveid]
                                );

                                $model->refresh();
                            }
                        }

                        $transaction->commit();
                        Yii::info('Transaction committed', 'leave');

                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return [
                                'success' => true,
                                'message' => 'Data Berhasil Disimpan',
                                'id' => $model->leaveid,
                                'leaveno' => $model->leaveno,
                            ];
                        }

                        Yii::$app->session->setFlash('success', 'Leave request created successfully. Leave No: ' . $model->leaveno);
                        return $this->redirect(['index', 'leavetype' => $leavetype, 'tab' => $tab]);
                    } else {
                        throw new \Exception('Gagal menyimpan data: ' . json_encode($model->errors));
                    }
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    Yii::error(' Error: ' . $e->getMessage(), 'leave');

                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return [
                            'success' => false,
                            'message' => 'Error: ' . $e->getMessage()
                        ];
                    }

                    Yii::$app->session->setFlash('error', $e->getMessage());
                    return $this->redirect(['index', 'leavetype' => $leavetype, 'tab' => $tab]);
                }
            } else {
                Yii::error(' Validation failed: ' . json_encode($model->errors), 'leave');

                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'success' => false,
                        'message' => 'Validasi gagal',
                        'errors' => $model->errors
                    ];
                }
            }
        }

        if ($model->isNewRecord && empty($model->leaveno)) {
            $model->leaveno = Leave::nextNo($leavetype);
        }

        if (!empty($leavetype)) {
            $model->leavetype = $leavetype;
        }

        $model->status = 1;

        return $this->renderAjax('_form', [
            'model' => $model,
            'isajax' => true,
            'leavetype' => $leavetype,
            'tab' => $tab,
        ]);
    }
    public function actionCreateabs($leavetype = '')
    {
        $model = new Leave();

        if (empty($leavetype)) {
            $leavetype = Yii::$app->request->get('leavetype', 'leave');
        }

        $tab = Yii::$app->request->get('tab', 'profiledata');

        if (!empty($leavetype) && $leavetype === 'absent' && $model->isNewRecord) {
            $hadirEnumId = Yii::$app->db->createCommand(
                "SELECT enumid FROM enum 
             WHERE enumtype = 'absenttype' 
             AND LOWER(enumtext_id) = 'hadir' 
             LIMIT 1"
            )->queryScalar();

            if ($hadirEnumId) {
                $model->leavetype = $hadirEnumId;
                Yii::info('Auto-set leavetype to Hadir: ' . $hadirEnumId, 'leave');
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            Yii::info('=== START LEAVE CREATE ===', 'leave');

            if (empty($model->leaveno)) {
                $model->leaveno = Leave::nextNo($leavetype);
            }

            if (!empty($model->contactid) && empty($model->employee_code)) {
                $employeeCode = Yii::$app->db->createCommand(
                    "SELECT contact_no FROM contacts WHERE contact_id = :id"
                )->bindValue(':id', $model->contactid)->queryScalar();

                if ($employeeCode) {
                    $model->employee_code = $employeeCode;
                }
            }

            if ($leavetype === 'parttime' && $tab === 'parttimepayment') {
                $workDateIds = Yii::$app->request->post('Leave')['work_date_ids'] ?? [];

                if (empty($workDateIds)) {
                    throw new \Exception('Pilih minimal 1 tanggal kerja yang akan dibayar!');
                }

                $parttimeProfileId = $model->contactid;

                if (!$parttimeProfileId) {
                    throw new \Exception('Pilih data parttime terlebih dahulu!');
                }

                $profileData = Yii::$app->db->createCommand(
                    "SELECT parttime_no, parttime_name, payment, bankname, bankaccount_no, 
                        account_owner_name, hubungan_pemilik_rekening 
                    FROM leave 
                    WHERE leaveid = :id 
                    AND parttime_no IS NOT NULL
                    LIMIT 1"
                )->bindValue(':id', $parttimeProfileId)->queryOne();

                if (!$profileData) {
                    throw new \Exception('Data parttime profile tidak ditemukan!');
                }

                $successCount = 0;
                $errors = [];

                foreach ($workDateIds as $attendanceId) {
                    $paymentModel = new Leave();

                    try {
                        $paymentModel->leaveid = Yii::$app->db->createCommand("SELECT uuid_generate_v4()")->queryScalar();
                        $paymentModel->contactid = $attendanceId;
                        $paymentModel->payment = $model->payment;
                        $paymentModel->total = $model->total;
                        $paymentModel->amount = $model->amount;
                        $paymentModel->payment_date = !empty($model->payment_date)
                            ? date("Y-m-d", strtotime(str_replace('/', '-', $model->payment_date)))
                            : null;

                        $paymentModel->parttime_no = $profileData['parttime_no'];
                        $paymentModel->parttime_name = $profileData['parttime_name'];
                        $paymentModel->bankname = $profileData['bankname'];
                        $paymentModel->bankaccount_no = $profileData['bankaccount_no'];
                        $paymentModel->account_owner_name = $profileData['account_owner_name'];
                        $paymentModel->hubungan_pemilik_rekening = $profileData['hubungan_pemilik_rekening'];

                        $attendanceData = Yii::$app->db->createCommand(
                            "SELECT leavedate, leavetype FROM leave WHERE leaveid = :id"
                        )->bindValue(':id', $attendanceId)->queryOne();

                        if (!$attendanceData) {
                            throw new \Exception("Attendance not found: $attendanceId");
                        }

                        $paymentModel->leavedate = $attendanceData['leavedate'];
                        $paymentModel->leaveduedate = $attendanceData['leavedate'];
                        $paymentModel->leavetype = $attendanceData['leavetype'];
                        $paymentModel->leaveno = Leave::nextNo('parttime');
                        $paymentModel->status = 1;
                        $paymentModel->employee_code = 'N/A';
                        $paymentModel->temperature = '0.00';
                        $paymentModel->evaluation = 'OK';
                        $paymentModel->note = 'Payment untuk tanggal ' . date('d/m/Y', strtotime($attendanceData['leavedate']));
                        $paymentModel->check_in = null;
                        $paymentModel->check_out = null;
                        $paymentModel->late_duration = null;
                        $paymentModel->overtime_duration = null;

                        $attachment = \yii\web\UploadedFile::getInstance($model, 'fileUpload');
                        if (!empty($attachment) && $attachment->size > 0) {
                            $attachmentFilename = $paymentModel->leaveid . '-attachment-' . time() . "." . $attachment->extension;
                            $attachmentPath = Yii::getAlias('@webroot/uploads/leave/');

                            if (!is_dir($attachmentPath)) {
                                mkdir($attachmentPath, 0755, true);
                            }

                            if ($attachment->saveAs($attachmentPath . $attachmentFilename)) {
                                $paymentModel->attachment = $attachmentFilename;
                                Yii::info("Attachment saved: $attachmentFilename", 'leave');
                            }
                        }

                        if (!$paymentModel->save(false)) {
                            throw new \Exception('Save failed: ' . json_encode($paymentModel->errors));
                        }

                        $successCount++;
                        Yii::info("Payment: {$paymentModel->leaveno} for $attendanceId", 'leave');
                    } catch (\Exception $e) {
                        $errors[] = "Attendance $attendanceId: " . $e->getMessage();
                        Yii::error(" Error: " . $e->getMessage(), 'leave');
                    }
                }

                if ($successCount === 0) {
                    throw new \Exception('Gagal membuat payment: ' . implode('; ', $errors));
                }

                if ($successCount < count($workDateIds)) {
                    Yii::$app->session->setFlash(
                        'warning',
                        "Berhasil: $successCount, Gagal: " . (count($workDateIds) - $successCount)
                    );
                }

                // $transaction->commit();

                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'success' => true,
                        'message' => "Payment berhasil dibuat untuk $successCount tanggal kerja"
                    ];
                }

                Yii::$app->session->setFlash('success', "Payment berhasil dibuat untuk $successCount tanggal kerja");
                return $this->redirect(['index', 'leavetype' => $leavetype, 'tab' => $tab]);
            }

            if (empty($model->employee_code)) {
                $model->employee_code = 'N/A';
            }

            if (empty($model->temperature)) {
                $model->temperature = '0.00';
            }

            if (empty($model->evaluation)) {
                $model->evaluation = 'OK';
            }

            $model->leavedate = !empty($model->leavedate)
                ? date("Y-m-d", strtotime(str_replace('/', '-', $model->leavedate)))
                : null;

            $model->leaveduedate = !empty($model->leaveduedate)
                ? date("Y-m-d", strtotime(str_replace('/', '-', $model->leaveduedate)))
                : null;

            if ($leavetype === 'absent' || $leavetype === 'parttime') {
                $dateForCheckTime = $model->leavedate;

                if (!empty($model->check_in)) {
                    if (preg_match('/^\d{1,2}:\d{2}$/', $model->check_in)) {
                        $model->check_in = $dateForCheckTime . ' ' . $model->check_in . ':00';
                    }
                } else {
                    $model->check_in = null;
                }

                if (!empty($model->check_out)) {
                    if (preg_match('/^\d{1,2}:\d{2}$/', $model->check_out)) {
                        $model->check_out = $dateForCheckTime . ' ' . $model->check_out . ':00';
                    }
                } else {
                    $model->check_out = null;
                }

                $model->autoCalculateDurations();
            } else {
                $model->check_in = null;
                $model->check_out = null;
                $model->late_duration = null;
                $model->overtime_duration = null;
            }

            $model->status = 1;

            if ($model->validate(false)) {
                Yii::info('Validation passed', 'leave');

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $identityCardFile = \yii\web\UploadedFile::getInstance($model, 'identitycardfile');
                    if (!empty($identityCardFile)) {
                        $identityCardFile->name = ($model->parttime_name ?? 'parttime') . '-ktp-' . time() . '.' . $identityCardFile->extension;
                        $model->identitycardfile = $identityCardFile->name;
                    }

                    $studentCardFile = \yii\web\UploadedFile::getInstance($model, 'studentcardfile');
                    if (!empty($studentCardFile)) {
                        $studentCardFile->name = ($model->parttime_name ?? 'parttime') . '-kartu-pelajar-' . time() . '.' . $studentCardFile->extension;
                        $model->studentcardfile = $studentCardFile->name;
                    }

                    $pictEmployee = \yii\web\UploadedFile::getInstance($model, 'pict_employee');
                    $webcamPhoto = Yii::$app->request->post('webcam_photo');

                    if (!empty($pictEmployee) && $pictEmployee->size > 0) {
                        $filename = 'leave-' . time() . '.' . $pictEmployee->extension;
                        $path = Yii::getAlias('@webroot/uploads/leave/');

                        if (!is_dir($path)) {
                            mkdir($path, 0777, true);
                        }

                        if ($pictEmployee->saveAs($path . $filename)) {
                            $model->pict_employee = $filename;
                            Yii::info("File upload saved: $filename", 'leave');
                        }
                    } elseif (!empty($webcamPhoto) && strpos($webcamPhoto, 'data:image') === 0) {
                        list($type, $data) = explode(';', $webcamPhoto);
                        list(, $data) = explode(',', $data);
                        $imageData = base64_decode($data);

                        $filename = 'leave-webcam-' . time() . '.jpg';
                        $path = Yii::getAlias('@webroot/uploads/leave/');

                        if (!is_dir($path)) {
                            mkdir($path, 0777, true);
                        }

                        if (file_put_contents($path . $filename, $imageData)) {
                            $model->pict_employee = $filename;
                            Yii::info("Webcam photo saved: $filename", 'leave');
                        }
                    } else {
                        $oldPictEmployeeFromPost = Yii::$app->request->post('old_pict_employee');
                        if (!empty($oldPictEmployeeFromPost)) {
                            $model->pict_employee = $oldPictEmployeeFromPost;
                            Yii::info("Keeping old photo: $oldPictEmployeeFromPost", 'leave');
                        }
                    }


                    if ($model->save(false)) {
                        Yii::info('Model saved with ID: ' . $model->leaveid, 'leave');

                        if (!empty($identityCardFile)) {
                            $path = Yii::getAlias('@webroot/uploads/leave/');
                            if (!is_dir($path)) {
                                mkdir($path, 0777, true);
                            }
                            $identityCardFile->saveAs($path . $identityCardFile->name);
                            Yii::info("KTP saved: " . $identityCardFile->name, 'leave');
                        }

                        if (!empty($studentCardFile)) {
                            $path = Yii::getAlias('@webroot/uploads/leave/');
                            if (!is_dir($path)) {
                                mkdir($path, 0777, true);
                            }
                            $studentCardFile->saveAs($path . $studentCardFile->name);
                            Yii::info("Kartu Pelajar saved: " . $studentCardFile->name, 'leave');
                        }

                        if (!empty($pictEmployee) && empty($webcamPhoto)) {
                            $path = Yii::getAlias('@webroot/uploads/leave/') . $pictEmployee->name;
                            $pictEmployee->saveAs($path);
                        }

                        $attachment = \yii\web\UploadedFile::getInstance($model, 'fileUpload');
                        if (!empty($attachment) && $attachment->size > 0) {
                            $attachmentFilename = $model->leaveid . '-attachment-' . time() . "." . $attachment->extension;

                            $attachmentPath = Yii::getAlias('@webroot/uploads/leave/');
                            if (!is_dir($attachmentPath)) {
                                mkdir($attachmentPath, 0755, true);
                            }

                            $fullPath = $attachmentPath . $attachmentFilename;
                            if ($attachment->saveAs($fullPath)) {
                                Yii::info('Attachment saved: ' . $fullPath, 'leave');

                                Leave::updateAll(
                                    ['attachment' => $attachmentFilename],
                                    ['leaveid' => $model->leaveid]
                                );

                                $model->refresh();
                            }
                        }

                        $transaction->commit();
                        Yii::info('Transaction committed', 'leave');

                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return [
                                'success' => true,
                                'message' => 'Data Berhasil Disimpan',
                                'id' => $model->leaveid,
                                'leaveno' => $model->leaveno,
                            ];
                        }

                        Yii::$app->session->setFlash('success', 'Leave request created successfully. Leave No: ' . $model->leaveno);
                        return $this->redirect(['index', 'leavetype' => $leavetype, 'tab' => $tab]);
                    } else {
                        throw new \Exception('Gagal menyimpan data: ' . json_encode($model->errors));
                    }
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    Yii::error(' Error: ' . $e->getMessage(), 'leave');

                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return [
                            'success' => false,
                            'message' => 'Error: ' . $e->getMessage()
                        ];
                    }

                    Yii::$app->session->setFlash('error', $e->getMessage());
                    return $this->redirect(['index', 'leavetype' => $leavetype, 'tab' => $tab]);
                }
            } else {
                Yii::error(' Validation failed: ' . json_encode($model->errors), 'leave');

                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'success' => false,
                        'message' => 'Validasi gagal',
                        'errors' => $model->errors
                    ];
                }
            }
        }

        if ($model->isNewRecord && empty($model->leaveno)) {
            $model->leaveno = Leave::nextNo($leavetype);
        }

        if (!empty($leavetype)) {
            $model->leavetype = $leavetype;
        }

        $model->status = 1;

        return $this->renderAjax('_formabs', [
            'model' => $model,
            'isajax' => true,
            'leavetype' => $leavetype,
            'tab' => $tab,
        ]);
    }

    public function actionNextParttimeNo()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            $number = $this->generateParttimeNo();

            return [
                'success' => true,
                'number' => $number
            ];
        } catch (\Exception $e) {
            Yii::error(" Error in nextParttimeNo: " . $e->getMessage(), 'leave');

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'number' => '10001' // Fallback
            ];
        }
    }


    public function actionSelectparttime()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $search = Yii::$app->request->post('q', '');
        $id = Yii::$app->request->post('id', '');
        $page = (int) Yii::$app->request->post('page', 1);
        $mode = Yii::$app->request->post('mode', 'basic'); // NEW: basic, payment, attendance

        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        // echo $query; die;

        // BASE QUERY - hanya ambil data PROFILE (bukan attendance/payment)
        $query = Leave::find()
            ->select([
                'l.leaveid',
                'l.parttime_no',
                'l.parttime_name',
                'l.contact_email1 AS parttime_email',
                'l.contact_phone1 AS parttime_phone',
                'l.grade',
                'g.enumtext_id AS grade_name',
                'l.member_level',
                'ml.enumtext_id AS member_level_name',
                'l.jenis_parttime',
                'jp.enumtext_id AS jenis_parttime_name',
                'l.is_active',
                'l.working_at',
                'l.current_position',
                'l.payment',
                'pmt.enumtext_id AS payment_name',
                'l.bankname',
                'l.bankaccount_no',
                'l.account_owner_name',
                'l.hubungan_pemilik_rekening',
                'hr.enumtext_id AS hubungan_rekening_name',

                // // Tambahan untuk mode PAYMENT
                // ($mode === 'payment' ? 'l.bankname' : 'NULL AS bankname'),
                // ($mode === 'payment' ? 'l.bankaccount_no' : 'NULL AS bankaccount_no'),
                // ($mode === 'payment' ? 'l.account_owner_name' : 'NULL AS account_owner_name'),
                // ($mode === 'payment' ? 'l.hubungan_pemilik_rekening' : 'NULL AS hubungan_pemilik_rekening'),
            ])
            ->from('leave l')
            ->leftJoin('enum g', 'g.enumid = l.grade AND g.enumtype = \'grade\'')
            ->leftJoin('enum ml', 'ml.enumid = l.member_level AND ml.enumtype = \'member_level\'')
            ->leftJoin('enum jp', 'jp.enumid = l.jenis_parttime AND jp.enumtype = \'jenis_parttime\'')
            ->leftJoin('enum pmt', 'pmt.enumid = l.payment AND pmt.enumtype = \'payment\'')
            ->leftJoin('enum hr', 'hr.enumid = l.hubungan_pemilik_rekening AND hr.enumtype = \'hubungan_rekening\'')
            ->where(['!=', 'l.status', '10'])
            ->andWhere(['IS NOT', 'l.parttime_no', null])
            ->andWhere(['!=', 'l.parttime_no', '']);

        // if ($mode === 'payment') {
        //     $query->leftJoin('enum hr', 'hr.enumid = l.hubungan_pemilik_rekening AND hr.enumtype = \'hubungan_rekening\'');
        // }

        if (!empty($id)) {
            $query->andWhere(['l.leaveid' => $id]);
        }

        if (!empty($search) && empty($id)) {
            $query->andWhere([
                'or',
                ['ilike', 'l.parttime_name', $search],
                ['ilike', 'l.parttime_no', $search],
                ['ilike', 'l.contact_phone1', $search],
            ]);
        }

        $totalCount = $query->count();

        $results = $query->orderBy(['l.parttime_no' => SORT_ASC])
            ->limit($perPage)
            ->offset($offset)
            ->asArray()
            ->all();

        $items = array_map(function ($row) {
            return [
                'id' => $row['leaveid'],
                'text' => $row['parttime_name'] . ' (' . $row['parttime_no'] . ')',
                'parttime_no' => $row['parttime_no'] ?? '',
                'parttime_name' => $row['parttime_name'] ?? '',
                'parttime_email' => $row['parttime_email'] ?? '',
                'parttime_phone' => $row['parttime_phone'] ?? '',
                'grade' => $row['grade'] ?? '',
                'grade_name' => $row['grade_name'] ?? '',
                'member_level' => $row['member_level'] ?? '',
                'member_level_name' => $row['member_level_name'] ?? '',
                'jenis_parttime' => $row['jenis_parttime'] ?? '',
                'jenis_parttime_name' => $row['jenis_parttime_name'] ?? '',
                'is_active' => $row['is_active'] ?? false,
                'working_at' => $row['working_at'] ?? '',
                'current_position' => $row['current_position'] ?? '',
                'payment' => $row['payment'] ?? '',
                'payment_name' => $row['payment_name'] ?? '',
                'bankname' => $row['bankname'] ?? '',
                'bankaccount_no' => $row['bankaccount_no'] ?? '',
                'account_owner_name' => $row['account_owner_name'] ?? '',
                'hubungan_pemilik_rekening' => $row['hubungan_pemilik_rekening'] ?? '',
                'hubungan_rekening_name' => $row['hubungan_rekening_name'] ?? '',
            ];
        }, $results);

        return [
            'items' => $items,
            'total_count' => $totalCount,
            'pagination' => ['more' => ($offset + $perPage) < $totalCount],
        ];
    }

    public function actionSelectparttimeattendance()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // ========== GET PARAMETERS ==========
        $search = Yii::$app->request->post('q', '');
        $id = Yii::$app->request->post('id', '');
        $page = (int) Yii::$app->request->post('page', 1);

        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        Yii::info("📋 actionSelectParttimeAttendance - search: '$search', id: '$id', page: $page", 'leave');

        try {
            // QUERY LENGKAP - AMBIL SEMUA DATA DARI PROFILE
            $sql = "
            SELECT 
                l.leaveid,
                l.parttime_no,
                l.parttime_name,
                l.contact_phone1,
                l.contact_email1,
                l.grade,
                l.member_level,
                l.jenis_parttime,
                l.tanggal_mulai,
                l.working_at,
                l.current_position,
                l.company_phone,
                l.current_work_status,
                l.is_active,
                l.alamat_tinggal,
                l.pendidikan_sekolah,
                l.alamat_sekolah,
                l.jurusan,
                l.npwpno,
                l.pict_employee,
                l.identitycardfile,
                l.studentcardfile,
                
                -- ENUM NAMES
                g.enumtext_id AS grade_name,
                ml.enumtext_id AS member_level_name,
                jp.enumtext_id AS jenis_parttime_name,
                ws.enumtext_id AS work_status_name
                
            FROM leave l
            LEFT JOIN enum g ON g.enumid = l.grade AND g.enumtype = 'grade'
            LEFT JOIN enum ml ON ml.enumid = l.member_level AND ml.enumtype = 'member_level'
            LEFT JOIN enum jp ON jp.enumid = l.jenis_parttime AND jp.enumtype = 'jenis_parttime'
            LEFT JOIN enum ws ON ws.enumid = l.current_work_status AND ws.enumtype = 'current_work_status'
            WHERE l.status != '10'
                AND l.parttime_no IS NOT NULL 
                AND l.parttime_no != ''
                AND (l.is_active = '1' OR l.is_active::boolean = true)
        ";

            $params = [];

            // ========== FILTER BY ID (untuk edit/restore) ==========
            if (!empty($id)) {
                $sql .= " AND l.leaveid = :id";
                $params[':id'] = $id;
                Yii::info("🔍 Filter by ID: $id", 'leave');
            }

            // ========== SEARCH FILTER ==========
            if (!empty($search) && empty($id)) {
                $sql .= " AND (
                l.parttime_name ILIKE :search 
                OR l.parttime_no ILIKE :search
                OR l.contact_phone1 ILIKE :search
            )";
                $params[':search'] = '%' . $search . '%';
            }

            // ========== COUNT TOTAL ==========
            $countSql = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";
            $totalCount = Yii::$app->db->createCommand($countSql, $params)->queryScalar();

            Yii::info("📊 Total records found: $totalCount", 'leave');

            // ========== ADD ORDER, LIMIT, OFFSET ==========
            $sql .= " ORDER BY l.parttime_no ASC LIMIT :limit OFFSET :offset";
            $params[':limit'] = $perPage;
            $params[':offset'] = $offset;

            // ========== EXECUTE QUERY ==========
            $results = Yii::$app->db->createCommand($sql, $params)->queryAll();

            Yii::info("📦 Retrieved " . count($results) . " records for page $page", 'leave');

            // ========== FORMAT ITEMS - LENGKAP ==========
            $items = array_map(function ($row) {
                return [
                    'id' => $row['leaveid'],
                    'text' => $row['parttime_name'] . ' (' . $row['parttime_no'] . ')',

                    // BASIC INFO
                    'parttime_no' => $row['parttime_no'] ?? '',
                    'parttime_name' => $row['parttime_name'] ?? '',
                    'parttime_phone' => $row['contact_phone1'] ?? '',
                    'parttime_email' => $row['contact_email1'] ?? '',

                    // WORK INFO
                    'grade' => $row['grade'] ?? '',
                    'grade_name' => $row['grade_name'] ?? '',
                    'member_level' => $row['member_level'] ?? '',
                    'member_level_name' => $row['member_level_name'] ?? '',
                    'jenis_parttime' => $row['jenis_parttime'] ?? '',
                    'jenis_parttime_name' => $row['jenis_parttime_name'] ?? '',
                    'current_work_status' => $row['current_work_status'] ?? '',
                    'work_status_name' => $row['work_status_name'] ?? '',

                    // COMPANY INFO
                    'working_at' => $row['working_at'] ?? '',
                    'current_position' => $row['current_position'] ?? '',
                    'company_phone' => $row['company_phone'] ?? '',

                    // PERSONAL DATA
                    'alamat_tinggal' => $row['alamat_tinggal'] ?? '',
                    'pendidikan_sekolah' => $row['pendidikan_sekolah'] ?? '',
                    'alamat_sekolah' => $row['alamat_sekolah'] ?? '',
                    'jurusan' => $row['jurusan'] ?? '',
                    'npwpno' => $row['npwpno'] ?? '',

                    // FILES
                    'pict_employee' => $row['pict_employee'] ?? '',
                    'identitycardfile' => $row['identitycardfile'] ?? '',
                    'studentcardfile' => $row['studentcardfile'] ?? '',

                    // STATUS
                    // 'is_active' => $row['is_active'] ?? false,
                    'is_active' => $row['is_active'] ?? '0',
                    'tanggal_mulai' => !empty($row['tanggal_mulai'])
                        ? date('d/m/Y', strtotime($row['tanggal_mulai']))
                        : '',
                ];
            }, $results);

            // ========== LOG SUCCESS ==========
            Yii::info("actionSelectParttimeAttendance success - returned: " . count($items) . " items", 'leave');

            // ========== RETURN RESPONSE ==========
            return [
                'items' => $items,
                'total_count' => $totalCount,
                'pagination' => [
                    'more' => ($offset + $perPage) < $totalCount
                ],
                'debug' => YII_DEBUG ? [
                    'sql' => $sql,
                    'search' => $search,
                    'id' => $id,
                    'page' => $page,
                    'offset' => $offset,
                    'limit' => $perPage,
                    'total' => $totalCount,
                    'returned' => count($items),
                ] : null,
            ];
        } catch (\Exception $e) {
            // ========== ERROR HANDLING ==========
            Yii::error(" Error in actionSelectParttimeAttendance: " . $e->getMessage(), 'leave');
            Yii::error("Stack trace: " . $e->getTraceAsString(), 'leave');

            return [
                'items' => [],
                'total_count' => 0,
                'pagination' => ['more' => false],
                'error' => YII_DEBUG ? $e->getMessage() : 'An error occurred while fetching data',
                'debug' => YII_DEBUG ? [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ] : null,
            ];
        }
    }

    public function actionGetworkdates()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $parttimeId = Yii::$app->request->get('parttime_id', '');
        $editMode = Yii::$app->request->get('edit_mode', false);
        $currentPaymentId = Yii::$app->request->get('payment_id', '');

        if (empty($parttimeId)) {
            return [
                'success' => false,
                'error' => 'Parttime ID is required',
                'dates' => []
            ];
        }

        Yii::info("📅 Get work dates for parttime: $parttimeId", 'leave');

        try {
            $parttime = Leave::find()
                ->where(['leaveid' => $parttimeId])
                ->andWhere(['IS NOT', 'parttime_no', null])
                ->andWhere(['!=', 'status', '10'])
                ->one();

            if (!$parttime) {
                return [
                    'success' => false,
                    'error' => 'Parttime not found',
                    'dates' => []
                ];
            }

            // ⭐ QUERY FIX: Cek apakah leaveid ini SUDAH JADI contactid di payment record
            $sql = "
            SELECT 
                l.leaveid,
                l.leavedate,
                l.check_in,
                l.check_out,
                l.late_duration,
                l.overtime_duration,
                lt.enumtext_id AS jenis_absensi
            FROM leave l
            LEFT JOIN enum lt ON lt.enumid = l.leavetype
            WHERE l.contactid = :parttimeId
              AND lt.enumtype = 'parttimetype'
              AND l.status != '10'
              AND l.leavedate IS NOT NULL
              AND l.check_in IS NOT NULL
              AND l.check_out IS NOT NULL
        ";

            $params = [':parttimeId' => $parttimeId];

            // JIKA BUKAN EDIT MODE, EXCLUDE YANG SUDAH DIBAYAR
            if (!$editMode) {
                $sql .= "
              AND l.leaveid NOT IN (
                  SELECT DISTINCT contactid
                  FROM leave
                  WHERE contactid IS NOT NULL
                    AND status != '10'
                    AND check_in IS NULL
                    AND check_out IS NULL
                    AND (total IS NOT NULL OR payment_date IS NOT NULL)
              )
            ";
            } else {
                // JIKA EDIT MODE, EXCLUDE KECUALI YANG SEDANG DI-EDIT
                if (!empty($currentPaymentId)) {
                    $sql .= "
              AND l.leaveid NOT IN (
                  SELECT DISTINCT contactid
                  FROM leave
                  WHERE contactid IS NOT NULL
                    AND status != '10'
                    AND check_in IS NULL
                    AND check_out IS NULL
                    AND (total IS NOT NULL OR payment_date IS NOT NULL)
                    AND leaveid != :currentPaymentId
              )
            ";
                    $params[':currentPaymentId'] = $currentPaymentId;
                }
            }

            $sql .= " ORDER BY l.leavedate DESC";

            $command = Yii::$app->db->createCommand($sql)
                ->bindValue(':parttimeId', $parttimeId);

            if ($editMode && !empty($currentPaymentId)) {
                $command->bindValue(':currentPaymentId', $currentPaymentId);
            }

            $workDates = $command->queryAll();
            // $workDates = Yii::$app->db->createCommand($sql)
            //     ->bindValue(':parttimeId', $parttimeId)
            //     ->queryAll();

            // Yii::info("Found " . count($workDates) . " available dates", 'leave');

            $dates = array_map(function ($row) {
                $dateFormatted = date('d/m/Y', strtotime($row['leavedate']));
                $checkIn = $row['check_in'] ? date('H:i', strtotime($row['check_in'])) : '-';
                $checkOut = $row['check_out'] ? date('H:i', strtotime($row['check_out'])) : '-';

                $description = "{$dateFormatted} | {$row['jenis_absensi']} | {$checkIn} - {$checkOut}";

                $extra = [];
                if ($row['late_duration'])
                    $extra[] = "Terlambat: {$row['late_duration']}";
                if ($row['overtime_duration'])
                    $extra[] = "Lembur: {$row['overtime_duration']}";
                if (!empty($extra))
                    $description .= ' (' . implode(', ', $extra) . ')';

                return [
                    'id' => $row['leaveid'],
                    'text' => $description,
                    'date' => $row['leavedate'],
                    'date_formatted' => $dateFormatted,
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'late_duration' => $row['late_duration'] ?? null,
                    'overtime_duration' => $row['overtime_duration'] ?? null,
                    'jenis_absensi' => $row['jenis_absensi'],
                ];
            }, $workDates);

            return [
                'success' => true,
                'dates' => $dates,
                'total' => count($dates),
                'parttime_info' => [
                    'id' => $parttime->leaveid,
                    'name' => $parttime->parttime_name,
                    'no' => $parttime->parttime_no,
                ]
            ];
        } catch (\Exception $e) {
            Yii::error(" Error: " . $e->getMessage(), 'leave');
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'dates' => []
            ];
        }
    }

    // /**
    //  * Generate Parttime Number (10001, 10002, ...)
    //  */
    private function generateParttimeNo()
    {
        $sql = "
        SELECT COALESCE(
            MAX(CAST(parttime_no AS INTEGER)), 
            10000
        ) + 1 AS next_number
        FROM \"leave\" 
        WHERE parttime_no IS NOT NULL
        AND parttime_no ~ '^[0-9]+$'
        AND status != '10'
    ";

        try {
            $result = Yii::$app->db->createCommand($sql)->queryOne();
            $nextNumber = $result['next_number'] ?? 10001;

            Yii::info("Generated Parttime No: $nextNumber", 'leave');

            return (string) $nextNumber;
        } catch (\Exception $e) {
            Yii::error(" Error generating parttime number: " . $e->getMessage(), 'leave');
            return '10001';
        }
    }

    public function actionUpdate($id, $leavetype = '')
    {
        $model = $this->findModel($id);

        $oldPictEmployee = $model->pict_employee;
        $oldIdentityCardFile = $model->identitycardfile;
        $oldStudentCardFile = $model->studentcardfile;
        $oldAttachment = $model->attachment;

        $tab = Yii::$app->request->get('tab', '');

        if ($leavetype === 'parttime' && $tab === 'parttimepayment' && !$model->isNewRecord) {
            if (!empty($model->contactid)) {
                $profileId = Yii::$app->db->createCommand(
                    "SELECT contactid FROM leave WHERE leaveid = :id"
                )->bindValue(':id', $model->contactid)->queryScalar();

                if ($profileId) {
                    Yii::$app->session->set('payment_profile_id_' . $model->leaveid, $profileId);
                    Yii::info("Stored profile_id: $profileId for payment: {$model->leaveid}", 'leave');
                }
            }
        }

        if (empty($leavetype)) {
            $leavetype = Yii::$app->request->get('leavetype', '');
        }

        if (empty($leavetype) && !empty($model->leavetype)) {
            $enumType = Yii::$app->db->createCommand(
                "SELECT enumtype FROM enum WHERE enumid = :enumid"
            )->bindValue(':enumid', $model->leavetype)->queryScalar();

            if ($enumType === 'absenttype') {
                $leavetype = 'absent';
            } elseif ($enumType === 'parttimetype') {
                $leavetype = 'parttime';
            } elseif ($enumType === 'leavetype') {
                $leavetype = 'leave';
            }
        }

        if ($leavetype === 'parttime' && empty($tab)) {
            if (!empty($model->check_in) || !empty($model->check_out)) {
                $tab = 'workattendance';
            } elseif (!empty($model->payment) || !empty($model->total)) {
                $tab = 'parttimepayment';
            } elseif (!empty($model->parttime_no) && !empty($model->parttime_name)) {
                $tab = 'profiledata';
            } else {
                $tab = 'profiledata'; // default
            }

            Yii::info("Auto-detected tab: $tab based on data fields", 'leave');
        }

        // FALLBACK DEFAULT
        if (empty($tab)) {
            $tab = 'profiledata';
        }

        // $tab = Yii::$app->request->get('tab', 'profiledata');

        // // Auto-detect tab for parttime
        // if ($leavetype === 'parttime') {
        //     if (!empty($model->parttime_no) && !empty($model->parttime_name)) {
        //         $tab = 'profiledata';
        //     } elseif (!empty($model->check_in) || !empty($model->check_out)) {
        //         $tab = 'workattendance';
        //     } elseif (!empty($model->payment) || !empty($model->total)) {
        //         $tab = 'parttimepayment';
        //     }
        // }

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {

                // Auto-fill employee_code
                if (!empty($model->contactid) && empty($model->employee_code)) {
                    $employeeCode = Yii::$app->db->createCommand(
                        "SELECT contact_no FROM contacts WHERE contact_id = :id"
                    )->bindValue(':id', $model->contactid)->queryScalar();

                    if ($employeeCode) {
                        $model->employee_code = $employeeCode;
                    }
                }

                if (empty($model->temperature)) {
                    $model->temperature = '0.00';
                }

                if (empty($model->evaluation)) {
                    $model->evaluation = 'OK';
                }

                // Convert dates
                $model->leavedate = $model->leavedate ? date("Y-m-d", strtotime(str_replace('/', '-', $model->leavedate))) : null;
                $model->leaveduedate = $model->leaveduedate ? date("Y-m-d", strtotime(str_replace('/', '-', $model->leaveduedate))) : null;

                if ($leavetype === 'parttime' && !empty($model->payment_date)) {
                    $model->payment_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->payment_date)));
                }

                if ($leavetype === 'parttime') {
                    if (!empty($model->tanggal_mulai)) {
                        $model->tanggal_mulai = date("Y-m-d", strtotime(str_replace('/', '-', $model->tanggal_mulai)));
                    }

                    if (!empty($model->birth_date)) {
                        $model->birth_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->birth_date)));
                    }

                    if (!empty($model->contact_bod)) {
                        $model->contact_bod = date("Y-m-d", strtotime(str_replace('/', '-', $model->contact_bod)));
                    }

                    $familyDateFields = ['mother_bod', 'father_bod', 'guardian_bod', 'spouse_bod'];
                    foreach ($familyDateFields as $field) {
                        if (!empty($model->$field)) {
                            $model->$field = date("Y-m-d", strtotime(str_replace('/', '-', $model->$field)));
                        }
                    }
                }

                if ($leavetype === 'parttime' && $tab === 'parttimepayment') {
                    $workDateIds = Yii::$app->request->post('Leave')['work_date_ids'] ?? [];

                    Yii::info("📦 Work Date IDs: " . json_encode($workDateIds), 'leave');

                    if (!Yii::$app->request->isPost && !empty($model->contactid)) {
                        $attendanceProfileId = Yii::$app->db->createCommand(
                            "SELECT contactid FROM leave WHERE leaveid = :id"
                        )->bindValue(':id', $model->contactid)->queryScalar();

                        if ($attendanceProfileId) {
                            // Simpan di session atau variable
                            Yii::$app->session->set('payment_profile_id_' . $model->leaveid, $attendanceProfileId);
                        }
                    }

                    if (!empty($workDateIds)) {
                        $model->contactid = $workDateIds[0];


                        // Ambil tanggal dari record pertama
                        $firstWorkDate = Yii::$app->db->createCommand(
                            "SELECT leavedate FROM leave WHERE leaveid = :id LIMIT 1"
                        )->bindValue(':id', $workDateIds[0])->queryScalar();

                        if ($firstWorkDate) {
                            $model->leavedate = $firstWorkDate;
                            $model->leaveduedate = $firstWorkDate;
                            Yii::info("Updated leavedate from work_dates: $firstWorkDate", 'leave');
                        }
                    }

                    // Set default payment status jika kosong
                    if (empty($model->payment_status)) {
                        $pendingId = Yii::$app->db->createCommand(
                            "SELECT enumid FROM enum 
                            WHERE enumtype = 'payment_status' 
                            AND LOWER(enumtext_id) LIKE '%pending%' 
                            LIMIT 1"
                        )->queryScalar();

                        if ($pendingId) {
                            $model->payment_status = $pendingId;
                        }
                    }

                    // Fallback: Gunakan payment_date jika work_dates kosong
                    if (empty($model->leavedate) && !empty($model->payment_date)) {
                        $model->leavedate = $model->payment_date;
                        $model->leaveduedate = $model->payment_date;
                        Yii::info("Using payment_date as leavedate: {$model->leavedate}", 'leave');
                    }

                    // Last resort: Gunakan tanggal hari ini
                    if (empty($model->leavedate)) {
                        $model->leavedate = date('Y-m-d');
                        $model->leaveduedate = date('Y-m-d');
                        Yii::warning(" leavedate empty, using today: {$model->leavedate}", 'leave');
                    }
                }


                if ($leavetype === 'absent' || $leavetype === 'parttime') {
                    $dateForCheckTime = $model->leavedate;

                    if (!empty($model->check_in)) {
                        if (strlen($model->check_in) > 8 && strpos($model->check_in, ' ') !== false) {
                            $timePart = explode(' ', $model->check_in)[1];
                            $model->check_in = $dateForCheckTime . ' ' . substr($timePart, 0, 5) . ':00';
                        } elseif (preg_match('/^\d{1,2}:\d{2}$/', $model->check_in)) {
                            $model->check_in = $dateForCheckTime . ' ' . $model->check_in . ':00';
                        }
                    } else {
                        $model->check_in = null;
                    }

                    if (!empty($model->check_out)) {
                        if (strlen($model->check_out) > 8 && strpos($model->check_out, ' ') !== false) {
                            $timePart = explode(' ', $model->check_out)[1];
                            $model->check_out = $dateForCheckTime . ' ' . substr($timePart, 0, 5) . ':00';
                        } elseif (preg_match('/^\d{1,2}:\d{2}$/', $model->check_out)) {
                            $model->check_out = $dateForCheckTime . ' ' . $model->check_out . ':00';
                        }
                    } else {
                        $model->check_out = null;
                    }

                    $model->autoCalculateDurations();
                } else {
                    $model->check_in = null;
                    $model->check_out = null;
                    $model->late_duration = null;
                    $model->overtime_duration = null;
                }

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $identityCardFile = \yii\web\UploadedFile::getInstance($model, 'identitycardfile');
                    if (!empty($identityCardFile)) {
                        // Hapus file lama
                        if ($oldIdentityCardFile && file_exists(Yii::getAlias('@webroot/uploads/leave/') . $oldIdentityCardFile)) {
                            unlink(Yii::getAlias('@webroot/uploads/leave/') . $oldIdentityCardFile);
                            Yii::info("🗑️ Deleted old KTP: $oldIdentityCardFile", 'leave');
                        }
                        $identityCardFile->name = ($model->parttime_name ?? 'parttime') . '-ktp-' . time() . '.' . $identityCardFile->extension;
                        $model->identitycardfile = $identityCardFile->name;
                    } else {
                        $oldIdentityCardFileFromPost = Yii::$app->request->post('old_identitycardfile');
                        $model->identitycardfile = $oldIdentityCardFileFromPost ?? $oldIdentityCardFile;
                    }

                    $studentCardFile = \yii\web\UploadedFile::getInstance($model, 'studentcardfile');
                    if (!empty($studentCardFile)) {
                        // Hapus file lama
                        if ($oldStudentCardFile && file_exists(Yii::getAlias('@webroot/uploads/leave/') . $oldStudentCardFile)) {
                            unlink(Yii::getAlias('@webroot/uploads/leave/') . $oldStudentCardFile);
                            Yii::info("🗑️ Deleted old Student Card: $oldStudentCardFile", 'leave');
                        }
                        $studentCardFile->name = ($model->parttime_name ?? 'parttime') . '-kartu-pelajar-' . time() . '.' . $studentCardFile->extension;
                        $model->studentcardfile = $studentCardFile->name;
                    } else {
                        $oldStudentCardFileFromPost = Yii::$app->request->post('old_studentcardfile');
                        $model->studentcardfile = $oldStudentCardFileFromPost ?? $oldStudentCardFile;
                    }

                    $pictEmployee = \yii\web\UploadedFile::getInstance($model, 'pict_employee');
                    $webcamPhoto = Yii::$app->request->post('webcam_photo');

                    if (!empty($pictEmployee) && $pictEmployee->size > 0) {
                        // Hapus file lama
                        if ($oldPictEmployee && file_exists(Yii::getAlias('@webroot/uploads/leave/') . $oldPictEmployee)) {
                            unlink(Yii::getAlias('@webroot/uploads/leave/') . $oldPictEmployee);
                            Yii::info("🗑️ Deleted old photo: $oldPictEmployee", 'leave');
                        }

                        $filename = $model->leaveid . '-employee-' . time() . '.' . $pictEmployee->extension;
                        $path = Yii::getAlias('@webroot/uploads/leave/');

                        if (!is_dir($path)) {
                            mkdir($path, 0777, true);
                        }

                        if ($pictEmployee->saveAs($path . $filename)) {
                            $model->pict_employee = $filename;
                            Yii::info("File upload saved: $filename", 'leave');
                        }
                    }
                    // PRIORITAS 2: WEBCAM (hanya jika tidak ada file upload)
                    elseif (!empty($webcamPhoto) && strpos($webcamPhoto, 'data:image') === 0) {
                        // Hapus file lama
                        if ($oldPictEmployee && file_exists(Yii::getAlias('@webroot/uploads/leave/') . $oldPictEmployee)) {
                            unlink(Yii::getAlias('@webroot/uploads/leave/') . $oldPictEmployee);
                            Yii::info("🗑️ Deleted old photo: $oldPictEmployee", 'leave');
                        }

                        list($type, $data) = explode(';', $webcamPhoto);
                        list(, $data) = explode(',', $data);
                        $imageData = base64_decode($data);

                        $filename = $model->leaveid . '-employee-webcam-' . time() . '.jpg';
                        $path = Yii::getAlias('@webroot/uploads/leave/');

                        if (!is_dir($path)) {
                            mkdir($path, 0777, true);
                        }

                        if (file_put_contents($path . $filename, $imageData)) {
                            $model->pict_employee = $filename;
                            Yii::info("Webcam photo saved: $filename", 'leave');
                        }
                    }
                    // PRIORITAS 3: Keep old file
                    else {
                        $oldPictEmployeeFromPost = Yii::$app->request->post('old_pict_employee');
                        $model->pict_employee = $oldPictEmployeeFromPost ?? $oldPictEmployee;
                        Yii::info("Keeping old photo: " . $model->pict_employee, 'leave');
                    }

                    $attachment = \yii\web\UploadedFile::getInstance($model, 'fileUpload');
                    if (!empty($attachment)) {
                        if ($oldAttachment && file_exists(Yii::getAlias('@webroot/uploads/leave/') . $oldAttachment)) {
                            unlink(Yii::getAlias('@webroot/uploads/leave/') . $oldAttachment);
                        }
                        $attachmentFilename = $model->leaveid . '-attachment-' . time() . '.' . $attachment->extension;
                        $model->attachment = $attachmentFilename;
                    } else {
                        $oldAttachmentFromPost = Yii::$app->request->post('old_attachment');
                        $model->attachment = $oldAttachmentFromPost ?? $oldAttachment;
                    }

                    // SAVE MODEL
                    if ($model->save(false)) {
                        // Save foto KTP
                        if (!empty($identityCardFile)) {
                            $path = Yii::getAlias('@webroot/uploads/leave/');
                            if (!is_dir($path)) {
                                mkdir($path, 0777, true);
                            }
                            $identityCardFile->saveAs($path . $identityCardFile->name);
                            Yii::info("KTP saved: " . $identityCardFile->name, 'leave');
                        }

                        // Save foto Kartu Pelajar
                        if (!empty($studentCardFile)) {
                            $path = Yii::getAlias('@webroot/uploads/leave/');
                            if (!is_dir($path)) {
                                mkdir($path, 0777, true);
                            }
                            $studentCardFile->saveAs($path . $studentCardFile->name);
                            Yii::info("Kartu Pelajar saved: " . $studentCardFile->name, 'leave');
                        }

                        // Save pict_employee (jika upload biasa)
                        if (!empty($pictEmployee) && empty($webcamPhoto)) {
                            $path = Yii::getAlias('@webroot/uploads/leave/') . $model->pict_employee;
                            $pictEmployee->saveAs($path);
                        }

                        // Save attachment
                        if (!empty($attachment)) {
                            $attachmentPath = Yii::getAlias('@webroot/uploads/leave/');
                            if (!is_dir($attachmentPath)) {
                                mkdir($attachmentPath, 0755, true);
                            }
                            $attachment->saveAs($attachmentPath . $model->attachment);
                        }

                        $transaction->commit();
                        Yii::info('Update successful', 'leave');

                        if (Yii::$app->request->isAjax) {
                            return $this->asJson([
                                'success' => true,
                                'message' => 'Data berhasil diupdate'
                            ]);
                        }

                        return $this->redirect(['index', 'leavetype' => $leavetype, 'tab' => $tab]);
                    } else {
                        throw new \Exception('Gagal menyimpan data: ' . json_encode($model->errors));
                    }
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    Yii::error(' Error in actionUpdate: ' . $e->getMessage(), 'leave');

                    if (Yii::$app->request->isAjax) {
                        return $this->asJson([
                            'success' => false,
                            'message' => 'Error: ' . $e->getMessage(),
                        ]);
                    }
                }
            }
        }

        if (!empty($model->birth_date)) {
            $model->birth_date = Yii::$app->formatter->asDate($model->birth_date, 'php:d/m/Y');
        }

        // Format untuk form display
        if (!empty($model->check_in) && ($leavetype === 'absent' || $leavetype === 'parttime')) {
            if (strpos($model->check_in, ' ') !== false) {
                $timePart = explode(' ', $model->check_in)[1];
                $model->check_in = substr($timePart, 0, 5);
            }
        }

        if (!empty($model->check_out) && ($leavetype === 'absent' || $leavetype === 'parttime')) {
            if (strpos($model->check_out, ' ') !== false) {
                $timePart = explode(' ', $model->check_out)[1];
                $model->check_out = substr($timePart, 0, 5);
            }
        }

        $model->leavedate = $model->leavedate ? Yii::$app->formatter->asDate($model->leavedate, 'php:d/m/Y') : null;
        $model->leaveduedate = $model->leaveduedate ? Yii::$app->formatter->asDate($model->leaveduedate, 'php:d/m/Y') : null;

        return $this->renderAjax('_form', [
            'model' => $model,
            'isajax' => true,
            'leavetype' => $leavetype,
            'tab' => $tab,
        ]);
    }
    public function actionUpdateabs($id, $leavetype = '')
    {
        $model = $this->findModel($id);

        $oldPictEmployee = $model->pict_employee;
        $oldPictEmployee2 = $model->pict_employee2;
        $oldAttachment = $model->attachment;

        $tab = Yii::$app->request->get('tab', '');

        if (empty($leavetype)) {
            $leavetype = Yii::$app->request->get('leavetype', '');
        }

        if (empty($leavetype) && !empty($model->leavetype)) {
            $enumType = Yii::$app->db->createCommand(
                "SELECT enumtype FROM enum WHERE enumid = :enumid"
            )->bindValue(':enumid', $model->leavetype)->queryScalar();

            if ($enumType === 'absenttype') {
                $leavetype = 'absent';
            }
        }

        if (empty($tab)) {
            $tab = 'profiledata';
        }

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {

                if (!empty($model->contactid) && empty($model->employee_code)) {
                    $employeeCode = Yii::$app->db->createCommand(
                        "SELECT contact_no FROM contacts WHERE contact_id = :id"
                    )->bindValue(':id', $model->contactid)->queryScalar();

                    if ($employeeCode) {
                        $model->employee_code = $employeeCode;
                    }
                }

                $model->leavedate = $model->leavedate ? date("Y-m-d", strtotime(str_replace('/', '-', $model->leavedate))) : null;
                $model->leaveduedate = $model->leaveduedate ? date("Y-m-d", strtotime(str_replace('/', '-', $model->leaveduedate))) : null;

                if ($leavetype === 'absent' || $leavetype === 'parttime') {
                    $dateForCheckTime = $model->leavedate;

                    if (!empty($model->check_in)) {
                        if (strlen($model->check_in) > 8 && strpos($model->check_in, ' ') !== false) {
                            $timePart = explode(' ', $model->check_in)[1];
                            $model->check_in = $dateForCheckTime . ' ' . substr($timePart, 0, 5) . ':00';
                        } elseif (preg_match('/^\d{1,2}:\d{2}$/', $model->check_in)) {
                            $model->check_in = $dateForCheckTime . ' ' . $model->check_in . ':00';
                        }
                    } else {
                        $model->check_in = null;
                    }

                    if (!empty($model->check_out)) {
                        if (strlen($model->check_out) > 8 && strpos($model->check_out, ' ') !== false) {
                            $timePart = explode(' ', $model->check_out)[1];
                            $model->check_out = $dateForCheckTime . ' ' . substr($timePart, 0, 5) . ':00';
                        } elseif (preg_match('/^\d{1,2}:\d{2}$/', $model->check_out)) {
                            $model->check_out = $dateForCheckTime . ' ' . $model->check_out . ':00';
                        }
                    } else {
                        $model->check_out = null;
                    }

                    if (!empty($model->check_in) && !empty($model->check_out)) {
                        if (strtotime($model->check_out) < strtotime($model->check_in)) {

                            if (Yii::$app->request->isAjax) {
                                return $this->asJson([
                                    'success' => false,
                                    'message' => 'Error: Jam Check-Out tidak boleh kurang dari jam Check-In.'
                                ]);
                            }

                            Yii::$app->session->setFlash('error', 'Jam Check-Out tidak boleh kurang dari jam Check-In.');
                            return $this->renderAjax('_formabs', [
                                'model' => $model,
                                'isajax' => true,
                                'leavetype' => $leavetype,
                                'tab' => $tab,
                            ]);
                        }
                    }

                    $model->autoCalculateDurations();
                } else {
                    $model->check_in = null;
                    $model->check_out = null;
                    $model->late_duration = null;
                    $model->overtime_duration = null;
                }

                $model->pict_employee = $oldPictEmployee;
                $model->pict_employee2 = $oldPictEmployee2;

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $pathClockIn = Yii::getAlias('@webroot/uploads/leave/clockin/');
                    $pathClockOut = Yii::getAlias('@webroot/uploads/leave/clockout/');

                    if (!is_dir($pathClockIn)) {
                        mkdir($pathClockIn, 0777, true);
                    }
                    if (!is_dir($pathClockOut)) {
                        mkdir($pathClockOut, 0777, true);
                    }

                    $hasNewFile1 = false;
                    $hasNewFile2 = false;
                    $hasNewAttachment = false;

                    $pictEmployee = \yii\web\UploadedFile::getInstance($model, 'pict_employee');
                    $webcamPhoto = Yii::$app->request->post('webcam_photo');

                    if (!empty($pictEmployee) && $pictEmployee->size > 0) {
                        if ($oldPictEmployee && file_exists($pathClockIn . $oldPictEmployee)) {
                            unlink($pathClockIn . $oldPictEmployee);
                        }
                        $filename = $model->leaveid . '-employee-' . time() . '.' . $pictEmployee->extension;
                        $model->pict_employee = $filename;
                        $hasNewFile1 = true;
                    } elseif (!empty($webcamPhoto) && strpos($webcamPhoto, 'data:image') === 0) {
                        if ($oldPictEmployee && file_exists($pathClockIn . $oldPictEmployee)) {
                            unlink($pathClockIn . $oldPictEmployee);
                        }
                        list(, $data) = explode(';', $webcamPhoto);
                        list(, $data) = explode(',', $data);
                        $imageData = base64_decode($data);
                        $filename = $model->leaveid . '-employee-webcam-' . time() . '.jpg';

                        if (file_put_contents($pathClockIn . $filename, $imageData)) {
                            $model->pict_employee = $filename;
                        }
                    } else {
                        $model->pict_employee = Yii::$app->request->post('old_pict_employee') ?? $oldPictEmployee;
                    }

                    $pictEmployee2 = \yii\web\UploadedFile::getInstance($model, 'pict_employee2');
                    $webcamPhoto2 = Yii::$app->request->post('webcam_photo2');

                    if (!empty($pictEmployee2) && $pictEmployee2->size > 0) {
                        if ($oldPictEmployee2 && file_exists($pathClockOut . $oldPictEmployee2)) {
                            unlink($pathClockOut . $oldPictEmployee2);
                        }
                        $filename2 = $model->leaveid . '-employee2-' . time() . '.' . $pictEmployee2->extension;
                        $model->pict_employee2 = $filename2;
                        $hasNewFile2 = true;
                    } elseif (!empty($webcamPhoto2) && strpos($webcamPhoto2, 'data:image') === 0) {
                        if ($oldPictEmployee2 && file_exists($pathClockOut . $oldPictEmployee2)) {
                            unlink($pathClockOut . $oldPictEmployee2);
                        }
                        list(, $data2) = explode(';', $webcamPhoto2);
                        list(, $data2) = explode(',', $data2);
                        $imageData2 = base64_decode($data2);
                        $filename2 = $model->leaveid . '-employee2-webcam-' . time() . '.jpg';

                        if (file_put_contents($pathClockOut . $filename2, $imageData2)) {
                            $model->pict_employee2 = $filename2;
                        }
                    } else {
                        $model->pict_employee2 = Yii::$app->request->post('old_pict_employee2') ?? $oldPictEmployee2;
                    }

                    $attachment = \yii\web\UploadedFile::getInstance($model, 'fileUpload');
                    if (!empty($attachment)) {
                        if ($oldAttachment && file_exists($pathClockIn . $oldAttachment)) {
                            unlink($pathClockIn . $oldAttachment);
                        }
                        $attachmentFilename = $model->leaveid . '-attachment-' . time() . '.' . $attachment->extension;
                        $model->attachment = $attachmentFilename;
                        $hasNewAttachment = true;
                    } else {
                        $model->attachment = Yii::$app->request->post('old_attachment') ?? $oldAttachment;
                    }

                    if ($model->save(false)) {
                        if ($hasNewFile1 && !empty($pictEmployee)) {
                            $pictEmployee->saveAs($pathClockIn . $model->pict_employee);
                        }
                        if ($hasNewFile2 && !empty($pictEmployee2)) {
                            $pictEmployee2->saveAs($pathClockOut . $model->pict_employee2);
                        }
                        if ($hasNewAttachment && !empty($attachment)) {
                            $attachment->saveAs($pathClockIn . $model->attachment);
                        }

                        $transaction->commit();

                        if (Yii::$app->request->isAjax) {
                            return $this->asJson([
                                'success' => true,
                                'message' => 'Data berhasil diupdate'
                            ]);
                        }
                        return $this->redirect(['index', 'leavetype' => $leavetype, 'tab' => $tab]);
                    } else {
                        throw new \Exception('Gagal menyimpan data ke database.');
                    }
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    Yii::error(' Error in actionUpdate: ' . $e->getMessage(), 'leave');
                    if (Yii::$app->request->isAjax) {
                        return $this->asJson([
                            'success' => false,
                            'message' => 'Error: ' . $e->getMessage(),
                        ]);
                    }
                }
            }
        }

        if (!empty($model->check_in) && ($leavetype === 'absent' || $leavetype === 'parttime')) {
            if (strpos($model->check_in, ' ') !== false) {
                $model->check_in = substr(explode(' ', $model->check_in)[1], 0, 5);
            }
        }
        if (!empty($model->check_out) && ($leavetype === 'absent' || $leavetype === 'parttime')) {
            if (strpos($model->check_out, ' ') !== false) {
                $model->check_out = substr(explode(' ', $model->check_out)[1], 0, 5);
            }
        }

        $model->leavedate = $model->leavedate ? Yii::$app->formatter->asDate($model->leavedate, 'php:d/m/Y') : null;
        $model->leaveduedate = $model->leaveduedate ? Yii::$app->formatter->asDate($model->leaveduedate, 'php:d/m/Y') : null;

        return $this->renderAjax('_formabs', [
            'model' => $model,
            'isajax' => true,
            'leavetype' => $leavetype,
            'tab' => $tab,
        ]);
    }

    public function actionApprove($id)
    {
        $model = $this->findModel($id);
        $model->status = '2'; // Approved (string)

        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', 'Leave request approved successfully.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Reject leave request
     */
    public function actionReject($id)
    {
        $model = $this->findModel($id);
        $model->status = '3'; // Rejected (string)

        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', 'Leave request rejected.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Delete leave (soft delete)
     */
    public function actionDelete($id = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = $id ?? Yii::$app->request->post('id');

        if (!$id) {
            return ['success' => false, 'message' => 'ID tidak ditemukan!'];
        }

        $model = $this->findModel($id);

        $model->status = 10; // nomor, bukan string

        if ($model->save(false)) {
            return ['success' => true, 'message' => 'Data berhasil dihapus!'];
        }

        return ['success' => false, 'message' => 'Gagal menghapus!'];
    }

    /**
     * Mass delete
     */
    public function actionDeletemassal()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $ids = Yii::$app->request->post('ids');

        if (empty($ids)) {
            return ['success' => false, 'message' => 'Tidak ada data yang dipilih!'];
        }

        Leave::updateAll(
            ['status' => 10],      // soft delete
            ['leaveid' => $ids]    // where in (...)
        );

        return ['success' => true, 'message' => 'Data berhasil dihapus massal!'];
    }


    /**
     * View leave detail
     */
    public function actionDetail($id)
    {
        $model = Leave::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException("Leave tidak ditemukan.");
        }

        return $this->render('detail', ['model' => $model]);
    }

    /**
     * Get leave types list for dropdown
     */

    public function actionLeavetypelist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $q = Yii::$app->request->get('q', null);
        $enumtype = Yii::$app->request->get('enumtype', 'leavetype');
        $id = Yii::$app->request->get('id', null); // Support untuk restore edit mode
        $filterActiveOnly = Yii::$app->request->get('filter_active_only', false);
        $leavetypeFilter = Yii::$app->request->get('leavetype', '');

        // AUTO-DETECT ENUMTYPE
        if ($leavetypeFilter === 'leave') {
            $enumtype = 'leavetype';
        } elseif ($leavetypeFilter === 'absent') {
            $enumtype = 'absenttype';
        } elseif ($leavetypeFilter === 'parttime') {
            $enumtype = 'parttimetype';
        }

        $userId = Yii::$app->user->id;
        $listBahasa = "SELECT lang FROM users WHERE userid = :userid";
        $bahasa = Yii::$app->db->createCommand($listBahasa, [':userid' => $userId])->queryScalar();

        $params = [':bahasa' => $bahasa, ':enumtype' => $enumtype];

        // BASE QUERY
        $sql = "SELECT DISTINCT 
        e.enumid as id, 
        e.enumno as no,
        CASE WHEN :bahasa = 'id' 
            THEN COALESCE(e.enumtext_id, e.enumtext_en) 
            ELSE COALESCE(e.enumtext_en, e.enumtext_id) 
        END as text
    FROM enum e
    WHERE e.enumtype = :enumtype";

        // Filter by ID (untuk restore edit mode)
        if ($id !== null) {
            $sql .= " AND e.enumid = :id";
            $params[':id'] = $id;
        }

        // Filter by search query
        if ($q !== null && $id === null) {
            $sql .= " AND (LOWER(e.enumtext_en) LIKE :q OR LOWER(e.enumtext_id) LIKE :q)";
            $params[':q'] = "%" . strtolower($q) . "%";
        }

        // ⭐ TAMBAH FILTER STATUS
        if ($filterActiveOnly) {
            $sql .= " AND e.status != '10'";
        }

        $sql .= " ORDER BY e.enumno ASC LIMIT 10";

        try {
            $data = Yii::$app->db->createCommand($sql, $params)->queryAll();

            // Log untuk debugging
            Yii::info("💰 Leavetypelist - enumtype: $enumtype, count: " . count($data), 'leave');
            Yii::info("💰 Raw data: " . json_encode($data), 'leave');

            // CRITICAL: Pastikan setiap item punya id dan text
            $results = array_map(function ($item) {
                return [
                    'id' => $item['id'] ?? '',
                    'text' => $item['text'] ?? 'Unknown',
                    'no' => $item['no'] ?? ''
                ];
            }, $data);

            Yii::info("💰 Formatted results: " . json_encode($results), 'leave');

            return [
                'results' => $results,
                'pagination' => ['more' => false]
            ];
        } catch (\Exception $e) {
            Yii::error(" Error in leavetypelist: " . $e->getMessage(), 'leave');
            return [
                'results' => [],
                'pagination' => ['more' => false],
                'error' => $e->getMessage()
            ];
        }
    }

    public function actionList($leavetype = '')
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $params = Yii::$app->request->queryParams;
        $leavetype = $leavetype ?: ($params['leavetype'] ?? '');
        $tab = $params['tab'] ?? 'profiledata';
        $search = $params['search'] ?? '';
        $start = (int) ($params['start'] ?? 0);
        $length = (int) ($params['length'] ?? 10);
        $draw = (int) ($params['draw'] ?? 1);
        $date = $params['datefilter'] ?? '';
        $duedate = $params['duedatefilter'] ?? '';

        $sortcolumn = $params['order'][0]['column'] ?? 0;
        $ordercolumn = $params['columns'][$sortcolumn]['data'] ?? 'createdat';
        $columnorder = strtoupper($params['order'][0]['dir'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';

        $allowedColumns = [
            'createdat' => 'l.createdat',
            'leaveno' => 'l.leaveno',
            'leavedate' => 'l.leavedate',
            'contact_name' => 'c.contact_name',
            'leavetype_name' => 'lt.enumtext_en',
            'tranno' => 't.tranno'
        ];

        $orderSql = $allowedColumns[$ordercolumn] ?? 'l.createdat';

        $whereConditions = ["l.status <> '10'"];
        $queryParams = [];

        if ($leavetype === 'parttime') {
            if ($tab === 'profiledata') {
                $whereConditions[] = "l.parttime_no IS NOT NULL AND l.parttime_no != ''";
            } elseif ($tab === 'workattendance') {
                $whereConditions[] = "l.contactid IS NOT NULL AND l.contactid != ''";
                $whereConditions[] = "(l.check_in IS NOT NULL OR l.check_out IS NOT NULL)";
                $whereConditions[] = "lt.enumtype = 'parttimetype'";
            } elseif ($tab === 'parttimepayment') {
                $whereConditions[] = "l.contactid IS NOT NULL AND l.contactid != ''";
                $whereConditions[] = "(l.payment IS NOT NULL OR l.total IS NOT NULL OR l.payment_date IS NOT NULL OR l.amount IS NOT NULL)";
            }
            $enumTypeFilter = ($tab === 'workattendance') ? 'parttimetype' : null;
        } else {
            $whereConditions[] = "(l.parttime_no IS NULL OR l.parttime_no = '')";
            if ($leavetype === 'leave') {
                $enumTypeFilter = 'leavetype';
            } elseif ($leavetype === 'absent') {
                $enumTypeFilter = 'absenttype';
            } else {
                $enumTypeFilter = null;
            }
        }

        if (!empty($enumTypeFilter)) {
            $whereConditions[] = "(lt.enumtype = '$enumTypeFilter' OR lt.enumid IS NULL)";
        }

        if ($search !== '') {
            $whereConditions[] = "(
            LOWER(c.contact_name) LIKE LOWER(:search)
            OR LOWER(l.parttime_name) LIKE LOWER(:search)
            OR LOWER(l.parttime_no) LIKE LOWER(:search)
            OR LOWER(lt.enumtext_en) LIKE LOWER(:search)
            OR LOWER(lt.enumtext_id) LIKE LOWER(:search)
            OR LOWER(l.note) LIKE LOWER(:search)
            OR LOWER(t.tranno) LIKE LOWER(:search)
            OR LOWER(l.employee_code) LIKE LOWER(:search)
            OR TO_CHAR(l.leavedate, 'DD-MM-YYYY') LIKE :search
            OR TO_CHAR(l.payment_date, 'DD-MM-YYYY') LIKE :search
        )";
            $queryParams[':search'] = '%' . $search . '%';
        }

        if ($date !== '') {
            $dates = explode(" - ", $date);
            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));
            $whereConditions[] = "DATE(l.leavedate) BETWEEN '$startDate' AND '$endDate'";
        }

        if ($duedate !== '') {
            $duedates = explode(" - ", $duedate);
            $startDueDate = date('Y-m-d', strtotime($duedates[0]));
            $endDueDate = date('Y-m-d', strtotime($duedates[1]));
            $whereConditions[] = "DATE(l.leaveduedate) BETWEEN '$startDueDate' AND '$endDueDate'";
        }

        $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);

        $baseQuery = "FROM leave l
        LEFT JOIN contacts c ON c.contact_id = l.contactid
        LEFT JOIN enum lt ON lt.enumid = l.leavetype
        LEFT JOIN enum g ON g.enumid = l.grade
        LEFT JOIN enum ml ON ml.enumid = l.member_level AND ml.enumtype = 'member_level'
        LEFT JOIN enum jp ON jp.enumid = l.jenis_parttime AND jp.enumtype = 'jenis_parttime'
        LEFT JOIN enum pmt ON pmt.enumid = l.payment
        LEFT JOIN enum ps ON ps.enumid = l.payment_status AND ps.enumtype = 'payment_status'
        LEFT JOIN enum hr ON hr.enumid = l.hubungan_pemilik_rekening AND hr.enumtype = 'hubungan_rekening'
        LEFT JOIN enum ws ON ws.enumid = l.current_work_status
        LEFT JOIN tranevents te ON te.traneventid = l.traneventid
        LEFT JOIN trans t ON t.tranid = te.tranid
        LEFT JOIN contacts cr ON cr.contact_id = t.contact_id
        $whereClause";

        $countSql = "SELECT COUNT(*) AS total " . $baseQuery;
        $totalCount = (int) Yii::$app->db->createCommand($countSql, $queryParams)->queryScalar();

        $sql = "SELECT
            l.leaveid,
            l.leaveno,
            l.leave_status,
            l.employee_code,
            c.contact_name,
            l.pict_employee,
            CASE WHEN l.is_active = '1' OR l.is_active::boolean = true THEN 1 ELSE 0 END AS is_active,
            TO_CHAR(l.leavedate, 'DD-MM-YYYY') AS leavedate,
            TO_CHAR(l.leaveduedate, 'DD-MM-YYYY') AS leaveduedate,
            l.check_in,
            l.check_out,
            l.late_duration,
            l.overtime_duration,
            l.grade,
            g.enumtext_id AS grade_name,
            l.current_work_status,
            ws.enumtext_id AS work_status_name,
            l.working_at,
            l.current_position,
            l.company_phone,
            lt.enumtext_en AS leavetype_name,
            lt.enumtext_id AS leavetype_name_id,
            lt.enumtype,
            l.note AS leave_reason,
            t.tranid,
            t.tranno,
            t.eventname,
            t.locations,
            t.trandate,
            t.tranduedate,
            cr.contact_name AS event_contact_name,
            cr.jobcompany AS event_jobcompany
        $baseQuery
        ORDER BY $orderSql $columnorder
        LIMIT $length OFFSET $start";
        // echo $sql;die;

        try {
            $data = Yii::$app->db->createCommand($sql, $queryParams)->queryAll();

            foreach ($data as &$row) {
                if (!empty($row['leavedate'])) {
                    $row['leavedate_display'] = Yii::$app->formatter->asDate($row['leavedate'], 'php:d-m-Y');
                }
                if (!empty($row['leaveduedate'])) {
                    $row['leaveduedate_display'] = $row['leaveduedate'] ? Yii::$app->formatter->asDate($row['leaveduedate'], 'php:d-m-Y') : null;
                }
                if (!empty($row['check_in'])) {
                    $row['check_in_display'] = substr($row['check_in'], 11, 5);
                }
                if (!empty($row['check_out'])) {
                    $row['check_out_display'] = substr($row['check_out'], 11, 5);
                }

                $row['is_active_text'] = ($row['is_active'] == 1) ? 'Aktif' : 'Tidak Aktif';
                $row['is_active_badge'] = ($row['is_active'] == 1) ? 'success' : 'secondary';
            }

            return [
                'data' => $data ?: [],
                'draw' => $draw,
                'recordsTotal' => $totalCount,
                'recordsFiltered' => $totalCount,
                'leavetype' => $leavetype,
                'tab' => $tab,
            ];
        } catch (\Exception $e) {
            Yii::error('Error in actionList Leave: ' . $e->getMessage(), 'leave');

            return [
                'data' => [],
                'draw' => $draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'error' => YII_DEBUG ? $e->getMessage() : 'Database error occurred',
            ];
        }
    }
    public function actionListabs($leavetype = '')
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $params = Yii::$app->request->queryParams;
        $leavetype = $leavetype ?: ($params['leavetype'] ?? '');
        $search = $params['search'] ?? '';
        $start = (int) ($params['start'] ?? 0);
        $length = (int) ($params['length'] ?? 10);
        $draw = (int) ($params['draw'] ?? 1);
        $date = (!empty($params['datefilter'])) ? $params['datefilter'] : date('d-m-Y') . ' - ' . date('d-m-Y');

        $whereConditions = [
            "t.status <> '10'",
            "t.statuspro IN ('5', '10')",
            "t.trantype = 'sales/order'",
            "(l.parttime_no IS NULL OR l.parttime_no = '')",
            "(lt.enumtype = 'absenttype' OR lt.enumid IS NULL)"
        ];

        if (!empty($crew_type)) {
            $crew_type_escaped = Yii::$app->db->quoteValue($crew_type);
            $whereConditions[] = "tec.crewtypeid = $crew_type_escaped";
        }

        if (!empty($crew_id)) {
            $crew_id_escaped = Yii::$app->db->quoteValue($crew_id);
            $whereConditions[] = "tec.crewid = $crew_id_escaped";
        }

        if ($search !== '') {
            $search_escaped = Yii::$app->db->quoteValue("%$search%");
            $whereConditions[] = "(
        LOWER(COALESCE(cr.contact_name, '')) LIKE LOWER($search_escaped)
        OR LOWER(COALESCE(t.tranno, '')) LIKE LOWER($search_escaped)
        OR LOWER(COALESCE(t.eventname, '')) LIKE LOWER($search_escaped)
        OR COALESCE(TO_CHAR(l.leavedate, 'DD-MM-YYYY'), '') LIKE $search_escaped
    )";
        }

        $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);

        $dateClause = '';
        if ($date !== '') {
            $dates = explode(" - ", $date);
            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));
            $dateClause = " AND DATE(t.trandate) BETWEEN '$startDate' AND '$endDate'";
        }

        $baseFrom = "
        FROM trans t
        LEFT JOIN tranevents te ON te.tranid      = t.tranid
        LEFT JOIN traneventcrews tec ON tec.traneventid = te.traneventid
        LEFT JOIN leave l       ON l.traneventid  = te.traneventid
        LEFT JOIN contacts c    ON c.contact_id   = l.contactid
        LEFT JOIN enum lt       ON lt.enumid      = l.leavetype
        LEFT JOIN contacts cr   ON cr.contact_id  = t.contact_id
        $whereClause
        $dateClause
    ";

        $countSql = "SELECT COUNT(DISTINCT t.tranid) AS total $baseFrom";

        try {
            $countResult = Yii::$app->db->createCommand($countSql)->queryOne();
            $totalCount = (int) ($countResult['total'] ?? 0);
        } catch (\Exception $e) {
            $totalCount = 0;
        }

        $sql =
            "SELECT
            t.tranid,
            t.tranno,
            t.eventname,
            t.locations,
            t.trandate,
            t.tranduedate,
            cr.contact_name,
            cr.jobcompany,

            COUNT(DISTINCT l.leaveid)   AS total_leave,
            COUNT(DISTINCT l.contactid) AS total_crew,

            TO_CHAR(MIN(l.leavedate),    'DD-MM-YYYY') AS leavedate_first,
            TO_CHAR(MAX(l.leaveduedate), 'DD-MM-YYYY') AS leavedate_last,

            MIN(l.check_in)  AS check_in_first,
            MAX(l.check_out) AS check_out_last,

            MIN(l.leaveid)   AS leaveid,
            MIN(l.leaveno)   AS leaveno
        $baseFrom
        GROUP BY
            t.tranid, t.tranno, t.eventname, t.locations,
            t.trandate, t.tranduedate, cr.contact_name, cr.jobcompany
        ORDER BY t.trandate DESC, t.tranno DESC
        LIMIT '$length' OFFSET '$start'
    ";
        // echo $sql;exit;

        try {
            $rows = Yii::$app->db->createCommand($sql)->queryAll();

            foreach ($rows as &$row) {
                if (!empty($row['check_in_first'])) {
                    $row['check_in_display'] = substr($row['check_in_first'], 11, 5);
                }
                if (!empty($row['check_out_last'])) {
                    $row['check_out_display'] = substr($row['check_out_last'], 11, 5);
                }
            }
            unset($row);

            return [
                'data' => $rows,
                'draw' => $draw,
                'recordsTotal' => $totalCount,
                'recordsFiltered' => $totalCount,
            ];
        } catch (\Exception $e) {
            Yii::error(' Error in actionListabs: ' . $e->getMessage(), 'leave');

            return [
                'data' => [],
                'draw' => $draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'error' => YII_DEBUG ? $e->getMessage() : 'Database error occurred',
            ];
        }
    }
    public function actionCrewhistory($leaveid = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (empty($leaveid)) {
            return ['data' => [], 'total' => 0];
        }

        try {
            $contactData = Yii::$app->db->createCommand("
            SELECT tec.crewid AS contactid 
            FROM traneventcrews tec
            LEFT JOIN tranevents te ON te.traneventid = tec.traneventid
            LEFT JOIN trans t ON t.tranid = te.tranid
            WHERE t.tranid = '$leaveid' 
            AND tec.status != '10'
        ")->queryOne();

            if (!$contactData || empty($contactData['contactid'])) {
                return ['data' => [], 'total' => 0, 'message' => 'Contact not found'];
            }

            $contactid = $contactData['contactid'];

            $sql =
                "SELECT 
                te.startdate,
                te.enddate,
                te.eventtypeid,
                t.tranno,
                t.eventname,
                t.locations,
                p.enumtext_id AS crew_type,
                c.contact_name AS crew_name,
                tec.fee,
                tec.dinasfee,
                tec.jobid,
                j.enumtext_id AS job_name
               
            FROM traneventcrews tec
            LEFT JOIN tranevents te ON te.traneventid = tec.traneventid
            LEFT JOIN trans t ON t.tranid = te.tranid
            LEFT JOIN contacts c ON c.contact_id = tec.crewid
            LEFT JOIN enum j ON j.enumid = tec.jobid AND j.enumtype = 'job'
            LEFT JOIN enum p ON p.enumid = 'position.'||tec.crewtypeid AND p.enumtype = 'position'
            LEFT JOIN leave l ON l.contactid = tec.crewid AND l.status <> '10'
            WHERE tec.crewid = '$contactid' AND t.status <> 10 AND te.status <> 10
                AND tec.status <> 10
            ORDER BY te.startdate DESC
        ";

            $data = Yii::$app->db->createCommand($sql)->queryAll();
            // echo $sql;exit;

            $formattedData = array_map(function ($row) {
                return [
                    'startdate' => !empty($row['startdate']) ?
                        Yii::$app->formatter->asDatetime($row['startdate'], 'php:d/m/Y H:i') : '-',
                    'enddate' => !empty($row['enddate']) ?
                        Yii::$app->formatter->asDatetime($row['enddate'], 'php:d/m/Y H:i') : '-',
                    'tranno' => $row['tranno'] ?? '-',
                    'eventname' => $row['eventname'] ?? '-',
                    'locations' => $row['locations'] ?? '-',
                    'crew_type' => $row['crew_type'] ?? '-',
                    'crew_name' => $row['crew_name'] ?? '-',
                    'job_name' => $row['job_name'] ?? '-',
                    'fee' => !empty($row['fee']) ?
                        'Rp ' . number_format($row['fee'], 0, ',', '.') : '-',
                    'dinasfee' => !empty($row['dinasfee']) ?
                        'Rp ' . number_format($row['dinasfee'], 0, ',', '.') : '-',


                ];
            }, $data);

            return [
                'data' => $formattedData,
                'total' => count($data),
                'contactid' => $contactid, // untuk debugging
                'success' => true
            ];
        } catch (\Exception $e) {
            Yii::error("Error fetching crew history: " . $e->getMessage(), 'leave');
            return [
                'data' => [],
                'total' => 0,
                'error' => $e->getMessage(),
                'success' => false
            ];
        }
    }
    public function actionCrewhistoryabs($tranid = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (empty($tranid)) {
            return ['data' => [], 'total' => 0];
        }

        $crew_type = Yii::$app->request->get('crew_type', '');
        $crew_id = Yii::$app->request->get('crew_id', '');

        try {
            $whereConditions = [
                "t.tranid = '$tranid'",
                "tec.status <> '10'",
                "t.status <> '10'",
                "t.statuspro IN ('5', '10')",
                "te.status <> '10'"
            ];

            if (!empty($crew_type)) {
                $crew_type_escaped = Yii::$app->db->quoteValue($crew_type);
                $whereConditions[] = "tec.crewtypeid = $crew_type_escaped";
            }

            if (!empty($crew_id)) {
                $crew_id_escaped = Yii::$app->db->quoteValue($crew_id);
                $whereConditions[] = "tec.crewid = $crew_id_escaped";
            }

            $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);

            $sql =
                "SELECT 
                te.startdate,
                te.enddate,
                te.eventtypeid,
                t.tranno,
                t.eventname,
                t.locations,
                p.enumtext_id AS crew_type,
                c.contact_name AS crew_name,
                c.contact_no,
                tec.fee,
                tec.dinasid,
                tec.dinasfee,
                tec.jobid,
                j.enumtext_id AS job_name,
                g.enumtext_id AS dinas,
                l.pict_employee,
                l.pict_employee2,
                l.check_in,
                l.check_out,
                l.late_duration,
                l.overtime_duration,
                l.leaveid
            FROM traneventcrews tec
            LEFT JOIN tranevents te ON te.traneventid = tec.traneventid
            LEFT JOIN trans t ON t.tranid = te.tranid
            LEFT JOIN contacts c ON c.contact_id = tec.crewid
            LEFT JOIN enum j ON j.enumid = tec.jobid AND j.enumtype = 'job'
            LEFT JOIN enum g ON g.enumid = tec.dinasid AND g.enumtype = 'dinas'
            LEFT JOIN enum p ON p.enumid = 'position.' || tec.crewtypeid AND p.enumtype = 'position'
            LEFT JOIN leave l ON l.contactid = tec.crewid 
                AND l.traneventid = tec.traneventid
            $whereClause
            ORDER BY te.startdate ASC
        ";

            $data = Yii::$app->db->createCommand($sql)->queryAll();

            $formattedData = array_map(function ($row) {
                return [
                    'startdate' => !empty($row['startdate']) ?
                        Yii::$app->formatter->asDatetime($row['startdate'], 'php:d/m/Y H:i') : '-',
                    'enddate' => !empty($row['enddate']) ?
                        Yii::$app->formatter->asDatetime($row['enddate'], 'php:d/m/Y H:i') : '-',
                    'tranno' => $row['tranno'] ?? '-',
                    'eventtypeid' => $row['eventtypeid'] == '0' ? 'Setup' : ($row['eventtypeid'] == '1' ? 'Event' : ($row['eventtypeid'] == '2' ? 'Bongkar' : ($row['eventtypeid'] == '3' ? 'Antar' : 'Tarik'))),
                    'eventname' => $row['eventname'] ?? '-',
                    'locations' => $row['locations'] ?? '-',
                    'crew_type' => $row['crew_type'] ?? '-',
                    'crew_name' => $row['crew_name'] ?? '-',
                    'contact_no' => $row['contact_no'] ?? '-',
                    'job_name' => $row['job_name'] ?? '-',
                    'fee' => !empty($row['fee']) ?
                        'Rp ' . number_format($row['fee'], 0, ',', '.') : '-',
                    'dinasfee' => !empty($row['dinasfee']) ?
                        'Rp ' . number_format($row['dinasfee'], 0, ',', '.') : '-',
                    'pict_employee' => (!empty($row['pict_employee']) && trim($row['pict_employee']) !== '' && $row['pict_employee'] !== 'blank.png' && $row['pict_employee'] !== 'null') ?
                        Yii::$app->request->baseUrl . '/uploads/leave/clockin/' . $row['pict_employee'] : null,

                    'pict_employee2' => (!empty($row['pict_employee2']) && trim($row['pict_employee2']) !== '' && $row['pict_employee2'] !== 'blank.png' && $row['pict_employee2'] !== 'null') ?
                        Yii::$app->request->baseUrl . '/uploads/leave/clockout/' . $row['pict_employee2'] : null,
                    'check_in' => !empty($row['check_in']) ?
                        Yii::$app->formatter->asDatetime($row['check_in'], 'php:H:i') : '-',
                    'check_out' => !empty($row['check_out']) ?
                        Yii::$app->formatter->asDatetime($row['check_out'], 'php:H:i') : '-',
                    'late_duration' => $row['late_duration'] ?? null,
                    'overtime_duration' => $row['overtime_duration'] ?? null,
                    'dinas' => $row['dinas'] ?? '-',
                    'leaveid' => $row['leaveid'] ?? null,
                ];
            }, $data);

            return [
                'data' => $formattedData,
                'total' => count($data),
                'success' => true
            ];
        } catch (\Exception $e) {
            Yii::error("Error fetching crew history: " . $e->getMessage(), 'leave');
            return [
                'data' => [],
                'total' => 0,
                'error' => $e->getMessage(),
                'success' => false
            ];
        }
    }

    public function actionUpdatestatus()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $status = Yii::$app->request->post('status');

        if (empty($id) || $status === null) {
            return ['success' => false, 'message' => 'Parameter tidak lengkap'];
        }

        $model = Leave::findOne($id);
        if (!$model) {
            return ['success' => false, 'message' => 'Data tidak ditemukan'];
        }

        $oldStatus = $model->leave_status;
        $model->leave_status = $status;

        $statusLabels = [
            0 => 'Menunggu Persetujuan',
            1 => 'Approved',
            2 => 'Cancelled',
            3 => 'Rejected',
        ];
        $statusText = $statusLabels[$status] ?? 'Unknown';

        if ($model->save(false)) {

            try {
                $this->sendLeaveNotification($model, (int) $oldStatus, (int) $status);
            } catch (\Exception $e) {
                // Notifikasi gagal tidak boleh membatalkan update status
                Yii::error('Notification failed: ' . $e->getMessage(), 'leave-notify');
            }

            return [
                'success' => true,
                'message' => 'Status berhasil diperbarui',
                'statusText' => $statusText,
            ];
        }

        return ['success' => false, 'message' => 'Gagal simpan'];
    }

    /**
     * Get crew types for dropdown
     */
    public function actionCrewtypelist()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $q = Yii::$app->request->get('q', '');
        $page = (int) Yii::$app->request->get('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $query = (new \yii\db\Query())
            ->select([
                'enumid AS id',
                'enumtext_id AS text',
                'enumno'
            ])
            ->from('enum')
            ->where(['enumtype' => 'position'])
            ->andWhere(['!=', 'status', '10'])
            ->andWhere(['IN', 'enumid', ['position.pi', 'position.cr', 'position.op', 'position.sb', 'position.fe']]);

        if (!empty($q)) {
            $query->andWhere([
                'or',
                ['ilike', 'enumtext_id', $q],
                ['ilike', 'enumtext_en', $q]
            ]);
        }

        $totalCount = $query->count();

        $results = $query
            ->orderBy(['enumno' => SORT_ASC])
            ->limit($perPage)
            ->offset($offset)
            ->all();

        return [
            'items' => $results,
            'pagination' => [
                'more' => ($offset + $perPage) < $totalCount
            ]
        ];
    }

    /**
     * Get crews for dropdown (filtered by crew type if provided)
     */
    public function actionCrewlist()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $q = Yii::$app->request->get('q', '');
        $crewType = Yii::$app->request->get('crew_type', '');
        $page = (int) Yii::$app->request->get('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $query = (new \yii\db\Query())
            ->select([
                'c.contact_id AS id',
                "CONCAT(c.contact_name, ' (', c.contact_no, ')') AS text",
                'c.contact_no',
                'c.contact_name',
                'c.positionid'
            ])
            ->from(['c' => 'contacts'])
            ->where(['c.contacttype' => 'employee'])
            ->andWhere(['!=', 'c.contact_status', '10'])
            ->andWhere(['IN', 'c.positionid', ['position.pi', 'position.cr', 'position.op']]);

        if (!empty($crewType)) {
            $query->andWhere(['c.positionid' => $crewType]);
        }

        if (!empty($q)) {
            $query->andWhere([
                'or',
                ['ilike', 'c.contact_name', $q],
                ['ilike', 'c.contact_no', $q]
            ]);
        }

        $totalCount = $query->count();

        $results = $query
            ->orderBy(['c.contact_name' => SORT_ASC])
            ->limit($perPage)
            ->offset($offset)
            ->all();

        return [
            'items' => $results,
            'pagination' => [
                'more' => ($offset + $perPage) < $totalCount
            ]
        ];
    }
    public function actionGetno()
    {
        try {
            $leavetype = Yii::$app->request->get('leavetype', '');
            Yii::info("🔢 actionGetno called with leavetype: " . ($leavetype ?: 'EMPTY'), 'leave');

            $nextNo = Leave::nextNo($leavetype);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                "success" => true,
                "no" => $nextNo,
                "regno" => preg_replace('/\D/', '', $nextNo)
            ];
        } catch (\Exception $e) {
            Yii::error("Error in actionGetno: " . $e->getMessage(), 'leave');

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                "success" => false,
                "no" => "LV-0001",
                // "no" => "LV-" . date('Ym') . "-0001",
                "regno" => "0001",
                "error" => $e->getMessage()
            ];
        }
    }

    /**
     * Select2 data for employee dropdown
     */
    public function actionGetEnumText()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $enumId = Yii::$app->request->post('enumid');

        if (!$enumId) {
            return ['text' => ''];
        }

        try {
            $text = (new \yii\db\Query())
                ->select(['enumtext_id'])
                ->from('enum')
                ->where(['enumid' => $enumId])
                ->scalar();

            return ['text' => $text ?? ''];
        } catch (\Exception $e) {
            Yii::error('Error in actionGetEnumText: ' . $e->getMessage());
            return ['text' => ''];
        }
    }


    public function actionSelect()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $q = Yii::$app->request->post('q', '');
        $id = Yii::$app->request->post('id', '');
        $page = (int) Yii::$app->request->post('page', 1);

        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT 
                c.contact_id AS id,
                CONCAT(c.contact_name, ' - ', COALESCE(c.contact_phone1, 'No Phone')) AS text,
                c.contact_no,
                c.contact_name,
                c.contact_email1,
                c.contact_phone1,
                c.address,
                c.pict_employee,
                c.positionid,
                c.levelid,
                c.divisionid,
                c.contact_status,
                c.contractid,
                c.jobcompany,
                c.contact_phone2,
                e1.enumtext_id AS position_name,
                e2.enumtext_id AS division_name,
                e3.enumtext_id AS level_name,
                e4.enumtext_id AS activity_status_text,
                c.contact_status AS activity_status_id,
                e5.enumtext_id AS contract_status_text,
                c.contractid AS contract_status_id
            FROM contacts c
            LEFT JOIN enum e1 ON e1.enumid = c.positionid
            LEFT JOIN enum e2 ON e2.enumid = c.divisionid
            LEFT JOIN enum e3 ON e3.enumid = c.levelid AND e3.enumtype = 'level'
            LEFT JOIN enum e4 ON e4.enumid = c.contact_status
            LEFT JOIN enum e5 ON e5.enumid = c.contractid AND e5.enumtype = 'status'
            WHERE c.contacttype = 'employee' AND c.contact_status <> '10'";

        $countSql = "SELECT COUNT(*) FROM contacts c WHERE c.contacttype = 'employee'";

        $params = [];
        $filter = "";

        if (!empty($id)) {
            $filter .= " AND c.contact_id = :id";
            $params[':id'] = $id;
        } elseif (!empty($q)) {
            $filter .= " AND (c.contact_name ILIKE :q OR c.contact_no ILIKE :q OR c.contact_phone1 ILIKE :q)";
            $params[':q'] = "%{$q}%";
        }

        $totalCount = (int) Yii::$app->db->createCommand($countSql . $filter, $params)->queryScalar();

        $finalSql = $sql . $filter . " ORDER BY c.contact_name ASC LIMIT {$perPage} OFFSET {$offset}";
        $rows = Yii::$app->db->createCommand($finalSql, $params)->queryAll();

        return [
            'items' => $rows,
            'pagination' => [
                'more' => ($offset + $perPage) < $totalCount
            ]
        ];
    }

    public function actionSelectref()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $q = Yii::$app->request->post('q', '');
        $module = Yii::$app->request->post('module', 'leave');
        $type = Yii::$app->request->post('type', '');
        $refid = Yii::$app->request->post('refid', '');
        $traneventid = Yii::$app->request->post('traneventid', '');

        $page = (int) Yii::$app->request->post('page', 1);
        $limit = 5;
        $offset = ($page - 1) * $limit;
        $trantype = "$module/$type";

        $out = ['totalcount' => 0, 'items' => []];

        if ($trantype == 'leave/tranid') {
            $where =
                "WHERE A.status = '1' AND A.trantype = 'sales/order' AND A.statuspro IN ('5', '10')
              ";

            if (!empty($q)) {
                $q = addslashes($q);
                $where .= "
                AND (
                    A.tranno ILIKE '%$q%'
                    OR A.eventname ILIKE '%$q%'
                )
            ";
            }

            $sql =
                "SELECT
                A.tranid AS id,
                CONCAT(A.tranno, ' - ', A.eventname) AS text,
                A.trandate,
                A.eventname

            FROM trans A
            $where
            ORDER BY A.trandate DESC
            LIMIT $limit OFFSET $offset
            ";

            $sqlCount = "
            SELECT COUNT(*)
            FROM trans A
            $where
             ";

            $out['items'] = Yii::$app->db->createCommand($sql)->queryAll();
            $out['totalcount'] = Yii::$app->db->createCommand($sqlCount)->queryScalar();

            return $out;
        }

        if ($trantype == 'leave/tahap') {
            $where = "
            WHERE te.status <> '10'
        ";

            if (!empty($refid)) {
                $refid = addslashes($refid);
                $where .= "
                AND te.tranid = '$refid'
            ";
            }

            if (!empty($q)) {
                $q = addslashes($q);

                $where .=
                    "AND (
                    CASE
                        WHEN te.eventtypeid = '0' THEN 'Setup'
                        WHEN te.eventtypeid = '1' THEN 'Event'
                        WHEN te.eventtypeid = '2' THEN 'Bongkar'
                        WHEN te.eventtypeid = '3' THEN 'Antar'
                        WHEN te.eventtypeid = '4' THEN 'Tarik'
                        ELSE 'Unknown'
                    END ILIKE '%$q%'
                )
            ";
            }

            $sql =
                "SELECT
                te.traneventid AS id,
                TO_CHAR(te.startdate, 'DD/MM/YYYY') AS start_date,
                TO_CHAR(te.startdate, 'HH24:MI') AS start_time,

                TO_CHAR(te.enddate, 'DD/MM/YYYY') AS end_date,
                TO_CHAR(te.enddate, 'HH24:MI') AS end_time,

                CONCAT(
                CASE
                    WHEN te.eventtypeid = '0' THEN 'Setup'
                    WHEN te.eventtypeid = '1' THEN 'Event'
                    WHEN te.eventtypeid = '2' THEN 'Bongkar'
                    WHEN te.eventtypeid = '3' THEN 'Antar'
                    WHEN te.eventtypeid = '4' THEN 'Tarik'
                    ELSE 'Unknown'
                END,
                ' | ',
                te.startdate,
                ' - ',
                te.enddate
            ) AS text

            FROM tranevents te
            $where
            ORDER BY te.startdate ASC
            LIMIT $limit OFFSET $offset
        ";

            $sqlCount = "
            SELECT COUNT(*)
            FROM tranevents te
            $where
        ";

            $out['items'] = Yii::$app->db->createCommand($sql)->queryAll();
            $out['totalcount'] = Yii::$app->db->createCommand($sqlCount)->queryScalar();

            return $out;
        }

        if ($trantype == 'leave/contact') {
            $where = "
            WHERE contacttype = 'employee'
            AND contact_status <> '10'
        ";

            if (!empty($traneventid)) {
                $traneventid = addslashes($traneventid);
                $where .= "
                AND tec.traneventid = '$traneventid'

                AND c.contact_id NOT IN (
                    SELECT l.contactid
                    FROM leave l
                    WHERE l.traneventid = '$traneventid'
                    AND l.status <> '10' AND l.check_out IS NOT NULL
                )
            ";
            }

            if (!empty($q)) {
                $q = addslashes($q);
                $where .= "
                AND (
                    c.contact_name ILIKE '%$q%'
                    OR c.contact_no ILIKE '%$q%'
                )
            ";
            }

            $sql = "
            SELECT
                c.contact_id AS id, 
                c.contact_no,
                c.contact_name,
                CONCAT(c.contact_name, ' - ', c.contact_no) AS text
            FROM traneventcrews tec
            LEFT JOIN contacts c ON c.contact_id = tec.crewid
            $where
            ORDER BY tec.ord ASC
            LIMIT $limit OFFSET $offset
            ";

            $sqlCount = "
            SELECT COUNT(DISTINCT c.contact_id)
            FROM traneventcrews tec
            LEFT JOIN contacts c
                ON c.contact_id = tec.crewid

            $where
            ";
            // echo $sql;exit;

            $out['items'] = Yii::$app->db->createCommand($sql)->queryAll();
            $out['totalcount'] = Yii::$app->db->createCommand($sqlCount)->queryScalar();

            return $out;
        }
        return $out;
    }
    /**
     * Template actions
     */
    public function actionSavetemplate()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $template = Yii::$app->request->get('template');
        $companyid = Yii::$app->session->get('companyid');

        if (!$template || !$companyid) {
            return ['success' => false, 'message' => 'Data tidak lengkap.'];
        }

        Yii::$app->db->createCommand()->insert('numbertemplate', [
            'template' => $template,
            'companyid' => $companyid,
            'type' => 'leave'
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
                'leave_code' => $template
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

        Yii::$app->db->createCommand()->update('company', [
            'leave_code' => 'LV',
        ], ['companyid' => $companyid])->execute();

        return ['success' => true];
    }


    public function actionPreviewcode($template = null)
    {
        $session = Yii::$app->session;
        $companyId = Yii::$app->session->get('companyid');

        if ($template) {
            $session->set('leave_code', $template);

            Yii::$app->db->createCommand()
                ->update('company', ['leave_code' => $template], ['companyid' => $companyId])
                ->execute();
        }

        return $this->asJson([
            'success' => true,
        ]);
    }

    /**
     * Find model
     */
    protected function findModel($id)
    {
        if (($model = Leave::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionImport($leavetype = '')
    {

        if (empty($leavetype)) {
            $path = Yii::$app->request->pathInfo;
            if (strpos($path, 'absent') === 0) {
                $leavetype = 'absent';
            } elseif (strpos($path, 'operational') === 0) {
                $leavetype = 'operational'; // TAMBAHKAN INI
            } elseif (strpos($path, 'leave') === 0) {
                $leavetype = 'leave';
            }
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {
            Yii::$app->request->enableCsrfValidation = false;

            $file = \yii\web\UploadedFile::getInstanceByName('csvFile');

            if (!$file) {
                return ['success' => false, 'message' => 'File tidak ditemukan'];
            }

            if (!in_array(strtolower($file->extension), ['csv'])) {
                return ['success' => false, 'message' => 'File harus berformat CSV'];
            }

            $csvData = [];
            $handle = fopen($file->tempName, 'r');

            // Skip BOM
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($handle);
            }

            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $csvData[] = $row;
            }
            fclose($handle);

            $header = array_shift($csvData);

            if (count($header) < 4) {
                return ['success' => false, 'message' => 'Format CSV tidak valid. Minimal 4 kolom diperlukan (Nama, Jenis, Tanggal Mulai, Tanggal Selesai)'];
            }

            Yii::info('📊 Total rows: ' . count($csvData), 'leave-import');

            $imported = 0;
            $failed = 0;
            $errors = [];

            $userId = Yii::$app->user->id;
            $userIp = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

            // Counter untuk tracking nomor per prefix
            $leaveCounters = [];

            foreach ($csvData as $rowIndex => $row) {
                if (empty(array_filter($row))) {
                    continue;
                }

                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if (count($row) < 4) {
                        throw new \Exception("Format CSV tidak valid (minimal 4 kolom: Nama, Jenis, Tanggal Mulai, Tanggal Selesai)");
                    }

                    // 1. Generate UUID
                    $leaveid = Yii::$app->db->createCommand("SELECT uuid_generate_v4()")->queryScalar();

                    // 2. Cari Contact ID
                    $contactName = trim($row[0]);
                    if (empty($contactName)) {
                        throw new \Exception("Nama employee kosong");
                    }

                    $contactId = Yii::$app->db->createCommand(
                        "SELECT contact_id FROM contacts 
                     WHERE LOWER(TRIM(contact_name)) = LOWER(TRIM(:name)) 
                     AND contacttype = 'employee' 
                     AND contact_status != '10'
                     LIMIT 1"
                    )->bindValue(':name', $contactName)->queryScalar();

                    if (!$contactId) {
                        throw new \Exception("Employee '$contactName' tidak ditemukan");
                    }

                    // 3. AUTO-DETECT Leave Type berdasarkan nama (Fleksibel)
                    $leavetypeName = trim($row[1]);
                    if (empty($leavetypeName)) {
                        throw new \Exception("Jenis leave/absent kosong");
                    }

                    // Cari di database (support nama Indonesia & English)
                    $leavetypeResult = Yii::$app->db->createCommand(
                        "SELECT enumid, enumtype, enumtext_id, enumtext_en FROM enum 
                     WHERE (LOWER(TRIM(enumtext_id)) = LOWER(TRIM(:name)) 
                            OR LOWER(TRIM(enumtext_en)) = LOWER(TRIM(:name))) 
                     AND enumtype IN ('leavetype', 'absenttype') 
                     LIMIT 1"
                    )->bindValue(':name', $leavetypeName)->queryOne();

                    if (!$leavetypeResult) {
                        throw new \Exception("Leave type '$leavetypeName' tidak ditemukan. Pastikan nama sesuai dengan master data.");
                    }

                    $leavetypeId = $leavetypeResult['enumid'];
                    $enumType = $leavetypeResult['enumtype'];

                    Yii::info("Detected type: {$leavetypeResult['enumtext_id']} (enumtype: $enumType)", 'leave-import');

                    // 4. Format Tanggal
                    $leavedateRaw = trim($row[2]);
                    $leaveduedateRaw = trim($row[3]);

                    $leavedate = $this->parseDate($leavedateRaw);
                    $leaveduedate = $this->parseDate($leaveduedateRaw);

                    if (!$leavedate || !$leaveduedate) {
                        throw new \Exception("Format tanggal salah. Gunakan DD/MM/YYYY, DD-MM-YYYY, atau YYYY-MM-DD");
                    }

                    // 5. Note/Alasan
                    $note = isset($row[4]) ? trim($row[4]) : 'Import dari CSV';

                    // 6. Handle Jam HANYA untuk tipe ABSENT
                    $checkIn = null;
                    $checkOut = null;

                    if ($enumType === 'absenttype') {
                        // Kolom 5 = Jam Masuk (opsional)
                        if (isset($row[5]) && !empty(trim($row[5]))) {
                            $time = trim($row[5]);
                            if (preg_match('/^\d{1,2}:\d{2}$/', $time)) {
                                $checkIn = $leavedate . ' ' . $time . ':00';
                            }
                        }

                        // Kolom 6 = Jam Keluar (opsional)
                        if (isset($row[6]) && !empty(trim($row[6]))) {
                            $time = trim($row[6]);
                            if (preg_match('/^\d{1,2}:\d{2}$/', $time)) {
                                $checkOut = $leavedate . ' ' . $time . ':00';
                            }
                        }

                        Yii::info("⏰ Absent type - Check In: $checkIn, Check Out: $checkOut", 'leave-import');
                    } else {
                        Yii::info("📅 Leave type - No clock time needed", 'leave-import');
                    }

                    // 7. Generate Leave Number berdasarkan tipe
                    $prefix = ($enumType === 'absenttype') ? 'AT' : 'LV';

                    if (!isset($leaveCounters[$prefix])) {
                        $maxNumberSql = "
                        SELECT COALESCE(
                            MAX(
                                CAST(
                                    REGEXP_REPLACE(leaveno, '[^0-9]', '', 'g') AS INTEGER
                                )
                            ), 
                            0
                        ) AS max_number
                        FROM \"leave\" 
                        WHERE leaveno LIKE :prefix
                        AND leaveno IS NOT NULL
                        AND leaveno != ''
                        AND status != '10'
                    ";

                        $maxCmd = Yii::$app->db->createCommand($maxNumberSql);
                        $maxCmd->bindValue(':prefix', $prefix . '-%');
                        $maxResult = $maxCmd->queryOne();
                        $leaveCounters[$prefix] = ($maxResult['max_number'] ?? 0);
                    }

                    $leaveCounters[$prefix]++;
                    $leaveno = $prefix . '-' . str_pad($leaveCounters[$prefix], 4, "0", STR_PAD_LEFT);

                    Yii::info("🔢 Generated: $leaveno (prefix: $prefix, counter: {$leaveCounters[$prefix]})", 'leave-import');

                    // 8. Auto Calculate Late & Overtime (HANYA untuk absent)
                    $lateDuration = null;
                    $overtimeDuration = null;

                    if ($enumType === 'absenttype' && $checkIn) {
                        $checkInTime = new \DateTime($checkIn);
                        $workStart = new \DateTime($leavedate . ' 08:00:00');
                        $diffMinutes = ($checkInTime->getTimestamp() - $workStart->getTimestamp()) / 60;

                        if ($diffMinutes > 5) {
                            if ($diffMinutes >= 60) {
                                $hours = floor($diffMinutes / 60);
                                $mins = $diffMinutes % 60;
                                $lateDuration = $mins > 0 ? "{$hours} jam {$mins} menit" : "{$hours} jam";
                            } else {
                                $lateDuration = round($diffMinutes) . " menit";
                            }
                        }
                    }

                    if ($enumType === 'absenttype' && $checkOut) {
                        $checkOutTime = new \DateTime($checkOut);
                        $workEnd = new \DateTime($leavedate . ' 17:00:00');
                        $diffMinutes = ($checkOutTime->getTimestamp() - $workEnd->getTimestamp()) / 60;

                        if ($diffMinutes > 0) {
                            if ($diffMinutes >= 60) {
                                $hours = floor($diffMinutes / 60);
                                $mins = $diffMinutes % 60;
                                $overtimeDuration = $mins > 0 ? "{$hours} jam {$mins} menit" : "{$hours} jam";
                            } else {
                                $overtimeDuration = round($diffMinutes) . " menit";
                            }
                        }
                    }

                    // 9. Insert Data
                    $sql = "INSERT INTO \"leave\" (
                    leaveid, contactid, leavetype, leavedate, leaveduedate, 
                    note, leaveno, status, check_in, check_out, 
                    late_duration, overtime_duration, 
                    createdby, createdat, creatdip
                ) VALUES (
                    :leaveid, :contactid, :leavetype, :leavedate, :leaveduedate,
                    :note, :leaveno, :status, :check_in, :check_out,
                    :late_duration, :overtime_duration,
                    :createdby, NOW(), :creatdip
                )";

                    $command = Yii::$app->db->createCommand($sql);
                    $command->bindValue(':leaveid', $leaveid);
                    $command->bindValue(':contactid', $contactId);
                    $command->bindValue(':leavetype', $leavetypeId);
                    $command->bindValue(':leavedate', $leavedate);
                    $command->bindValue(':leaveduedate', $leaveduedate);
                    $command->bindValue(':note', $note);
                    $command->bindValue(':leaveno', $leaveno);
                    $command->bindValue(':status', '1');
                    $command->bindValue(':check_in', $checkIn);
                    $command->bindValue(':check_out', $checkOut);
                    $command->bindValue(':late_duration', $lateDuration);
                    $command->bindValue(':overtime_duration', $overtimeDuration);
                    $command->bindValue(':createdby', $userId);
                    $command->bindValue(':creatdip', $userIp);

                    $command->execute();

                    $transaction->commit();
                    $imported++;

                    Yii::info("Row " . ($rowIndex + 2) . ": $contactName → $leaveno ({$leavetypeResult['enumtext_id']})", 'leave-import');
                } catch (\Exception $e) {
                    if ($transaction && $transaction->isActive) {
                        $transaction->rollBack();
                    }

                    $failed++;
                    $errorMsg = "Baris " . ($rowIndex + 2) . ": " . $e->getMessage();
                    $errors[] = $errorMsg;
                    Yii::error($errorMsg, 'leave-import');

                    // Rollback counter
                    if (isset($prefix) && isset($leaveCounters[$prefix])) {
                        $leaveCounters[$prefix]--;
                    }

                    if (count($errors) >= 10) {
                        $errors[] = "... (lebih dari 10 error, sisanya tidak ditampilkan)";
                        break;
                    }
                }
            }

            return [
                'success' => ($imported > 0),
                'message' => "Import selesai! Berhasil: $imported, Gagal: $failed",
                'imported' => $imported,
                'failed' => $failed,
                'errors' => $errors,
            ];
        } catch (\Exception $e) {
            Yii::error(' Fatal Error: ' . $e->getMessage(), 'leave-import');
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    private function parseDate($dateStr)
    {
        $dateStr = trim($dateStr);

        // Try DD/MM/YYYY
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $dateStr, $matches)) {
            return sprintf('%04d-%02d-%02d', $matches[3], $matches[2], $matches[1]);
        }

        // Try DD-MM-YYYY
        if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $dateStr, $matches)) {
            return sprintf('%04d-%02d-%02d', $matches[3], $matches[2], $matches[1]);
        }

        // Try YYYY-MM-DD (already correct format)
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr)) {
            return $dateStr;
        }

        $timestamp = strtotime(str_replace('/', '-', $dateStr));
        if ($timestamp !== false && $timestamp > 0) {
            return date('Y-m-d', $timestamp);
        }

        Yii::warning("Failed to parse date: $dateStr", 'leave');
        return null;

        // return false;
    }

    /**
     * Import absensi dari format clock history
     */


    public function actionImportclockhistory($leavetype = '')
    {
        if (empty($leavetype)) {
            $path = Yii::$app->request->pathInfo;
            if (strpos($path, 'absent') === 0) {
                $leavetype = 'absent';
            } elseif (strpos($path, 'leave') === 0) {
                $leavetype = 'leave';
            }
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {
            Yii::$app->request->enableCsrfValidation = false;

            $file = \yii\web\UploadedFile::getInstanceByName('csvFile');

            if (!$file) {
                return ['success' => false, 'message' => 'File tidak ditemukan'];
            }

            if (!in_array(strtolower($file->extension), ['csv'])) {
                return ['success' => false, 'message' => 'File harus berformat CSV'];
            }

            // BACA FILE DAN PARSE BIOFINGER FORMAT
            $rawData = file_get_contents($file->tempName);

            // Remove BOM if exists
            $rawData = str_replace("\xEF\xBB\xBF", '', $rawData);

            $lines = explode("\n", $rawData);

            Yii::info('📊 Total lines in file: ' . count($lines), 'leave-import');

            // PARSE SETIAP BARIS BIOFINGER
            $clockRecords = [];

            foreach ($lines as $lineNum => $line) {
                $line = trim($line);

                if (empty($line)) {
                    continue;
                }

                // Parse baris BIOFINGER
                $parsed = $this->parseBiofingerLine($line);

                if ($parsed) {
                    $clockRecords[] = $parsed;
                    Yii::info("Line " . ($lineNum + 1) . ": {$parsed['name']} - {$parsed['datetime']} ({$parsed['status']})", 'leave-import');
                }
            }

            if (empty($clockRecords)) {
                return [
                    'success' => false,
                    'message' => 'Tidak ada data clock yang valid ditemukan dalam file'
                ];
            }

            Yii::info('Total valid records: ' . count($clockRecords), 'leave-import');

            // KELOMPOKKAN PER EMPLOYEE & DATE
            $groupedData = $this->groupClockRecords($clockRecords);

            // IMPORT KE DATABASE
            $imported = 0;
            $failed = 0;
            $errors = [];

            $userId = Yii::$app->user->id;
            $userIp = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

            // Counter untuk nomor
            $leaveCounters = [];
            $prefix = 'AT';

            // Get max number
            $maxNumberSql = "
            SELECT COALESCE(
                MAX(
                    CAST(
                        REGEXP_REPLACE(leaveno, '[^0-9]', '', 'g') AS INTEGER
                    )
                ), 
                0
            ) AS max_number
            FROM \"leave\" 
            WHERE leaveno LIKE :prefix
            AND leaveno IS NOT NULL
            AND leaveno != ''
            AND status != '10'
        ";

            $maxCmd = Yii::$app->db->createCommand($maxNumberSql);
            $maxCmd->bindValue(':prefix', $prefix . '-%');
            $maxResult = $maxCmd->queryOne();
            $leaveCounters[$prefix] = ($maxResult['max_number'] ?? 0);

            // Get default "Hadir" leave type
            $hadirEnumId = Yii::$app->db->createCommand(
                "SELECT enumid FROM enum 
             WHERE enumtype = 'absenttype' 
             AND LOWER(enumtext_id) = 'hadir' 
             LIMIT 1"
            )->queryScalar();

            if (!$hadirEnumId) {
                return [
                    'success' => false,
                    'message' => 'Leave type "Hadir" tidak ditemukan di database'
                ];
            }

            foreach ($groupedData as $key => $record) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    // Cari employee
                    $employeeName = $record['name'];

                    $contactData = Yii::$app->db->createCommand(
                        "SELECT contact_id, contact_no FROM contacts 
                    WHERE LOWER(TRIM(contact_name)) = LOWER(TRIM(:name)) 
                    AND contacttype = 'employee' 
                    AND contact_status != '10'
                    LIMIT 1"
                    )->bindValue(':name', $employeeName)->queryOne(); // ← queryOne() untuk ambil array

                    if (!$contactData) {
                        throw new \Exception("Employee '$employeeName' tidak ditemukan");
                    }

                    $contactId = $contactData['contact_id'];
                    $employeeCode = $contactData['contact_no'] ?? 'N/A';

                    // Generate leave number
                    $leaveCounters[$prefix]++;
                    $leaveno = $prefix . '-' . str_pad($leaveCounters[$prefix], 4, "0", STR_PAD_LEFT);

                    $date = $record['date'];
                    $checkIn = $record['check_in'];
                    $checkOut = $record['check_out'];

                    // Calculate late & overtime
                    $lateDuration = null;
                    $overtimeDuration = null;

                    if ($checkIn) {
                        $checkInTime = new \DateTime($checkIn);
                        $workStart = new \DateTime($date . ' 08:00:00');
                        $diffMinutes = ($checkInTime->getTimestamp() - $workStart->getTimestamp()) / 60;

                        if ($diffMinutes > 5) {
                            if ($diffMinutes >= 60) {
                                $hours = floor($diffMinutes / 60);
                                $mins = $diffMinutes % 60;
                                $lateDuration = $mins > 0 ? "{$hours} jam {$mins} menit" : "{$hours} jam";
                            } else {
                                $lateDuration = round($diffMinutes) . " menit";
                            }
                        }
                    }

                    if ($checkOut) {
                        $checkOutTime = new \DateTime($checkOut);
                        $workEnd = new \DateTime($date . ' 17:00:00');
                        $diffMinutes = ($checkOutTime->getTimestamp() - $workEnd->getTimestamp()) / 60;

                        if ($diffMinutes > 0) {
                            if ($diffMinutes >= 60) {
                                $hours = floor($diffMinutes / 60);
                                $mins = $diffMinutes % 60;
                                $overtimeDuration = $mins > 0 ? "{$hours} jam {$mins} menit" : "{$hours} jam";
                            } else {
                                $overtimeDuration = round($diffMinutes) . " menit";
                            }
                        }
                    }

                    // Insert
                    $leaveid = Yii::$app->db->createCommand("SELECT uuid_generate_v4()")->queryScalar();

                    $sql = "INSERT INTO \"leave\" (
                    leaveid, contactid, leavetype, leavedate, leaveduedate, 
                    note, leaveno, status, check_in, check_out, 
                    late_duration, overtime_duration, 
                    employee_code, temperature, evaluation,
                    createdby, createdat, creatdip
                ) VALUES (
                    :leaveid, :contactid, :leavetype, :leavedate, :leaveduedate,
                    :note, :leaveno, :status, :check_in, :check_out,
                    :late_duration, :overtime_duration,
                    :employee_code, :temperature, :evaluation,
                    :createdby, NOW(), :creatdip
                )";

                    $command = Yii::$app->db->createCommand($sql);
                    $command->bindValue(':leaveid', $leaveid);
                    $command->bindValue(':contactid', $contactId);
                    $command->bindValue(':leavetype', $hadirEnumId);
                    $command->bindValue(':leavedate', $date);
                    $command->bindValue(':leaveduedate', $date);
                    $command->bindValue(':note', 'Import dari BIOFINGER Clock History');
                    $command->bindValue(':leaveno', $leaveno);
                    $command->bindValue(':status', '1');
                    $command->bindValue(':check_in', $checkIn);
                    $command->bindValue(':check_out', $checkOut);
                    $command->bindValue(':late_duration', $lateDuration);
                    $command->bindValue(':overtime_duration', $overtimeDuration);
                    $command->bindValue(':employee_code', $employeeCode);
                    $command->bindValue(':temperature', '0.00');
                    $command->bindValue(':evaluation', 'OK');
                    $command->bindValue(':createdby', $userId);
                    $command->bindValue(':creatdip', $userIp);

                    $command->execute();
                    $transaction->commit();
                    $imported++;

                    Yii::info("Imported: $employeeName - $date → $leaveno", 'leave-import');
                } catch (\Exception $e) {
                    if ($transaction && $transaction->isActive) {
                        $transaction->rollBack();
                    }

                    $failed++;
                    $errorMsg = "{$record['name']} ({$record['date']}): " . $e->getMessage();
                    $errors[] = $errorMsg;
                    Yii::error($errorMsg, 'leave-import');

                    $leaveCounters[$prefix]--;

                    if (count($errors) >= 10) {
                        $errors[] = "... (lebih dari 10 error)";
                        break;
                    }
                }
            }

            return [
                'success' => ($imported > 0),
                'message' => "Import selesai! Berhasil: $imported, Gagal: $failed",
                'imported' => $imported,
                'failed' => $failed,
                'errors' => $errors,
            ];
        } catch (\Exception $e) {
            Yii::error(' Fatal: ' . $e->getMessage(), 'leave-import');
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get parttime profile data
     */
    public function actionGetparttimeprofile($id = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // MODE 1: Get profile_id from attendance_id
        $attendanceId = Yii::$app->request->get('attendance_id');

        if ($attendanceId) {
            try {
                Yii::info("🔍 Getting profile_id from attendance: $attendanceId", 'leave');

                $profileId = Yii::$app->db->createCommand(
                    "SELECT contactid FROM leave WHERE leaveid = :id LIMIT 1"
                )->bindValue(':id', $attendanceId)->queryScalar();

                if ($profileId) {
                    Yii::info("Found profile_id: $profileId", 'leave');
                    return [
                        'success' => true,
                        'profile_id' => $profileId
                    ];
                }

                Yii::warning(" No profile found for attendance: $attendanceId", 'leave');
                return [
                    'success' => false,
                    'message' => 'Profile not found for attendance'
                ];
            } catch (\Exception $e) {
                Yii::error(" Error: " . $e->getMessage(), 'leave');
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }

        // MODE 2: Get full profile data by profile_id (original functionality)
        if (empty($id)) {
            return [
                'success' => false,
                'message' => 'Parameter id or attendance_id required'
            ];
        }

        try {
            $profile = Yii::$app->db->createCommand("
            SELECT 
                l.*,
                c.contact_name,
                c.contact_email1,
                c.contact_phone1,
                c.pict_employee,
                c.positionid,
                c.levelid,
                c.divisionid,
                c.contact_status,
                c.contractid,
                g.enumtext_id AS grade_name,
                ws.enumtext_id AS work_status_name,
                pos.enumtext_id AS position_name,
                lv.enumtext_id AS level_name,
                div.enumtext_id AS division_name,
                st.enumtext_id AS status_name
            FROM leave l
            LEFT JOIN contacts c ON c.contact_id = l.contactid
            LEFT JOIN enum g ON g.enumid = l.grade
            LEFT JOIN enum ws ON ws.enumid = l.current_work_status
            LEFT JOIN enum pos ON pos.enumid = c.positionid
            LEFT JOIN enum lv ON lv.enumid = c.levelid
            LEFT JOIN enum div ON div.enumid = c.divisionid
            LEFT JOIN enum st ON st.enumid = c.contact_status
            WHERE l.leaveid = :id
        ", [':id' => $id])->queryOne();

            if (!$profile) {
                return [
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ];
            }

            return [
                'success' => true,
                'data' => $profile
            ];
        } catch (\Exception $e) {
            Yii::error("Error in getparttimeprofile: " . $e->getMessage(), 'leave');
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function actionGetparttimeattendance($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $attendance = Yii::$app->db->createCommand("
        SELECT 
            l.leaveid,
            l.leaveno,
            l.contactid,
            c.contact_name,
            TO_CHAR(l.leavedate, 'DD-MM-YYYY') AS work_date,
            l.check_in,
            l.check_out,
            l.late_duration,
            l.overtime_duration,
            l.note,
            l.attachment
        FROM leave l
        LEFT JOIN contacts c ON c.contact_id = l.contactid
        WHERE l.leaveid = :id
    ", [':id' => $id])->queryOne();

        if (!$attendance) {
            return ['success' => false, 'message' => 'Data tidak ditemukan'];
        }

        return ['success' => true, 'data' => $attendance];
    }

    /**
     * Get parttime payment data
     */
    public function actionGetparttimepayment($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $payment = Yii::$app->db->createCommand("
        SELECT 
            l.leaveid,
            l.contactid,
            c.contact_name,
            l.payment,
            pmt.enumtext_id AS payment_method,
            l.total,
            TO_CHAR(l.payment_date, 'DD-MM-YYYY') AS payment_date,
            l.amount
        FROM leave l
        LEFT JOIN contacts c ON c.contact_id = l.contactid
        LEFT JOIN enum pmt ON pmt.enumid = l.payment
        WHERE l.leaveid = :id
    ", [':id' => $id])->queryOne();

        if (!$payment) {
            return ['success' => false, 'message' => 'Data tidak ditemukan'];
        }

        return ['success' => true, 'data' => $payment];
    }


    /**
     * Parse satu baris BIOFINGER format
     */
    private function parseBiofingerLine($line)
    {
        $parts = str_getcsv($line, ',', '"');

        if (count($parts) < 10) {
            return null;
        }

        $nama = null;
        $datetime = null;
        $status = null;

        // Cari field "Nama:"
        foreach ($parts as $i => $part) {
            $part = trim($part);

            if (strpos($part, 'Nama') !== false && $i + 1 < count($parts)) {
                $namaField = trim($parts[$i + 1]);
                // Ambil nama pertama (sebelum koma)
                $namaArr = explode(',', $namaField);
                $nama = trim($namaArr[0]);
            }
        }

        // AMBIL DATA CLOCK DARI 3 KOLOM TERAKHIR
        // Format: ["01-Nov-2025  07:16", "Clock-In", "0.00", "OK"]
        $lastParts = array_slice($parts, -4);

        if (count($lastParts) >= 2) {
            $datetimeStr = trim($lastParts[0]);
            $statusStr = trim($lastParts[1]);

            if (!empty($datetimeStr) && !empty($statusStr)) {
                $datetime = $datetimeStr;
                $status = $statusStr;
            }
        }

        if (!$nama || !$datetime || !$status) {
            return null;
        }

        return [
            'name' => $nama,
            'datetime' => $datetime,
            'status' => $status
        ];
    }

    /**
     * Kelompokkan clock records per employee & date
     */
    private function groupClockRecords($records)
    {
        $grouped = [];

        foreach ($records as $record) {
            // Parse datetime "01-Nov-2025  07:16"
            $datetimeParts = explode('  ', $record['datetime']);
            $dateStr = trim($datetimeParts[0]);
            $timeStr = isset($datetimeParts[1]) ? trim($datetimeParts[1]) : '';

            // Convert "01-Nov-2025" to "2025-11-01"
            $dateObj = \DateTime::createFromFormat('d-M-Y', $dateStr);

            if (!$dateObj) {
                continue;
            }

            $date = $dateObj->format('Y-m-d');
            $name = $record['name'];

            $key = $name . '|' . $date;

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'name' => $name,
                    'date' => $date,
                    'check_in' => null,
                    'check_out' => null
                ];
            }

            // Format time menjadi "Y-m-d H:i:s"
            if (!empty($timeStr) && preg_match('/^\d{1,2}:\d{2}$/', $timeStr)) {
                $fullDatetime = $date . ' ' . $timeStr . ':00';

                if (stripos($record['status'], 'Clock-In') !== false) {
                    $grouped[$key]['check_in'] = $fullDatetime;
                } elseif (stripos($record['status'], 'Clock-Out') !== false) {
                    $grouped[$key]['check_out'] = $fullDatetime;
                }
            }
        }

        return $grouped;
    }

    public function actionExportbiofinger($leavetype = '')
    {
        if (empty($leavetype)) {
            $path = Yii::$app->request->pathInfo;
            if (strpos($path, 'absent') === 0) {
                $leavetype = 'absent';
            } elseif (strpos($path, 'operational') === 0) {
                $leavetype = 'operational'; // TAMBAHKAN INI
            } elseif (strpos($path, 'leave') === 0) {
                $leavetype = 'leave';
            }
        }

        // Get date range from request
        $startDate = Yii::$app->request->get('start_date', date('Y-m-01')); // Default: awal bulan ini
        $endDate = Yii::$app->request->get('end_date', date('Y-m-t'));     // Default: akhir bulan ini

        Yii::info("📤 Exporting BIOFINGER data: $leavetype from $startDate to $endDate", 'leave-export');

        // Get data
        $records = Leave::exportToBiofingerFormat($startDate, $endDate, $leavetype);

        if (empty($records)) {
            Yii::$app->session->setFlash('warning', 'Tidak ada data untuk di-export pada periode tersebut.');
            return $this->redirect(['index', 'leavetype' => $leavetype]);
        }

        // Generate filename
        $startFormatted = date('d-M-Y', strtotime($startDate));
        $endFormatted = date('d-M-Y', strtotime($endDate));
        $filename = 'Kartu_History_Clock_' . $startFormatted . '_to_' . $endFormatted . '.csv';

        // Set headers untuk download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        // UTF-8 BOM
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Company info
        $companyData = Yii::$app->db->createCommand("
        SELECT c.* 
        FROM company c 
        LEFT JOIN users u ON u.companyid = c.companyid 
        WHERE u.userid = :userid
    ", [':userid' => Yii::$app->user->id])->queryOne();

        $companyName = $companyData['nama_perusahaan'] ?? 'BIOFINGER Indonesia';
        $reportTitle = 'Kartu History Clock Per Department';
        $periode = $startFormatted . ' s/d ' . $endFormatted;
        $createdDate = date('d-M-Y');
        $createdTime = date('H:i');

        // GROUP DATA PER EMPLOYEE
        $groupedData = [];
        foreach ($records as $record) {
            $employeeName = $record->contact_name;
            $employeeCode = $record->employee_code ?? 'N/A';
            $position = $record->position_name ?? '*';
            $division = $record->division_name ?? '*';

            $key = $employeeCode;

            if (!isset($groupedData[$key])) {
                $groupedData[$key] = [
                    'name' => $employeeName,
                    'code' => $employeeCode,
                    'position' => $position,
                    'division' => $division,
                    'records' => []
                ];
            }

            $groupedData[$key]['records'][] = $record;
        }

        // WRITE DATA DALAM FORMAT BIOFINGER
        foreach ($groupedData as $employeeData) {
            foreach ($employeeData['records'] as $record) {
                // Parse dates
                $date = date('d-M-Y', strtotime($record->leavedate));

                // Check In
                $checkInDateTime = '';
                $checkInStatus = '';
                if ($record->check_in) {
                    $checkInTime = date('H:i', strtotime($record->check_in));
                    $checkInDateTime = $date . '  ' . $checkInTime;
                    $checkInStatus = 'Clock-In';
                }

                // Check Out
                $checkOutDateTime = '';
                $checkOutStatus = '';
                if ($record->check_out) {
                    $checkOutTime = date('H:i', strtotime($record->check_out));
                    $checkOutDateTime = $date . '  ' . $checkOutTime;
                    $checkOutStatus = 'Clock-Out';
                }

                // Temperature sebagai TEXT (sudah dalam format string)
                $temperature = !empty($record->temperature) ? $record->temperature : '0.00';

                // Evaluation sebagai TEXT
                $evaluation = !empty($record->evaluation) ? $record->evaluation : 'OK';

                // Employee Code
                $employeeCode = !empty($employeeData['code']) ? $employeeData['code'] : 'N/A';

                // FORMAT BIOFINGER: Satu baris untuk Clock-In
                if ($checkInDateTime) {
                    fputcsv($output, [
                        $companyName,
                        'Laporan		: ',
                        $reportTitle,
                        'Kode	:',
                        $employeeData['code'] . ', ',
                        'Periode		: ',
                        $periode,
                        'Nama	:',
                        $employeeData['name'] . ', ' . $employeeData['name'],
                        'Dibuat Tgl	:',
                        $createdDate,
                        $createdTime,
                        'Jabatan	:',
                        $employeeData['position'] . ', ' . $employeeData['division'],
                        'Kriteria 	      :',
                        '',
                        'Tanggal-Jam',
                        'Status',
                        'Suhu',
                        'Evaluasi',
                        'Tanggal-Jam',
                        'Status',
                        'Suhu',
                        'Evaluasi',
                        'Tanggal-Jam',
                        'Status',
                        'Suhu',
                        'Evaluasi',
                        $checkInDateTime,
                        $checkInStatus,
                        $temperature,
                        $evaluation
                    ]);
                }

                // FORMAT BIOFINGER: Satu baris untuk Clock-Out
                if ($checkOutDateTime) {
                    fputcsv($output, [
                        $companyName,
                        'Laporan		: ',
                        $reportTitle,
                        'Kode	:',
                        $employeeData['code'] . ', ',
                        'Periode		: ',
                        $periode,
                        'Nama	:',
                        $employeeData['name'] . ', ' . $employeeData['name'],
                        'Dibuat Tgl	:',
                        $createdDate,
                        $createdTime,
                        'Jabatan	:',
                        $employeeData['position'] . ', ' . $employeeData['division'],
                        'Kriteria 	      :',
                        '',
                        'Tanggal-Jam',
                        'Status',
                        'Suhu',
                        'Evaluasi',
                        'Tanggal-Jam',
                        'Status',
                        'Suhu',
                        'Evaluasi',
                        'Tanggal-Jam',
                        'Status',
                        'Suhu',
                        'Evaluasi',
                        $checkOutDateTime,
                        $checkOutStatus,
                        $temperature,
                        $evaluation
                    ]);
                }
            }
        }

        fclose($output);
        Yii::$app->end();
    }

    /**
     * Export Leave/Absent data to Excel
     */
    public function actionExportexcel($leavetype = '')
    {
        $this->layout = false;

        if (empty($leavetype)) {
            $path = Yii::$app->request->pathInfo;
            if (strpos($path, 'absent') === 0) {
                $leavetype = 'absent';
            } elseif (strpos($path, 'operational') === 0) {
                $leavetype = 'operational';
            } elseif (strpos($path, 'leave') === 0) {
                $leavetype = 'leave';
            } elseif (strpos($path, 'parttime') === 0) {
                $leavetype = 'parttime';
            }
        }

        $tab = Yii::$app->request->get('tab', 'profiledata');
        $startDate = Yii::$app->request->get('start_date', '');
        $endDate = Yii::$app->request->get('end_date', '');

        try {
            $enumTypeFilter = null;
            if ($leavetype === 'leave') {
                $enumTypeFilter = 'leavetype';
            } elseif ($leavetype === 'absent') {
                $enumTypeFilter = 'absenttype';
            } elseif ($leavetype === 'parttime') {
                $enumTypeFilter = 'parttimetype';
            }

            $whereConditions = ["l.status != '10'"];
            $queryParams = [];

            $isProfileData = ($leavetype === 'parttime' && $tab === 'profiledata');

            if (!$isProfileData && !empty($startDate) && !empty($endDate)) {
                $whereConditions[] = "l.leavedate BETWEEN :start_date AND :end_date";
                $queryParams[':start_date'] = $startDate;
                $queryParams[':end_date'] = $endDate;
            } elseif (!$isProfileData && (empty($startDate) || empty($endDate))) {
                $startDate = date('Y-m-01');
                $endDate = date('Y-m-t');
                $whereConditions[] = "l.leavedate BETWEEN :start_date AND :end_date";
                $queryParams[':start_date'] = $startDate;
                $queryParams[':end_date'] = $endDate;
            }

            if ($leavetype === 'parttime') {
                if ($tab === 'profiledata') {
                    $whereConditions[] = "l.parttime_no IS NOT NULL";
                    $whereConditions[] = "l.parttime_name IS NOT NULL";
                    $whereConditions[] = "(l.contactid IS NULL OR l.contactid = '')";
                } elseif ($tab === 'workattendance') {
                    $whereConditions[] = "l.contactid IS NOT NULL";
                    $whereConditions[] = "(l.check_in IS NOT NULL OR l.check_out IS NOT NULL)";
                    $whereConditions[] = "lt.enumtype = 'parttimetype'";
                } elseif ($tab === 'parttimepayment') {
                    $whereConditions[] = "l.contactid IS NOT NULL";
                    $whereConditions[] = "(l.payment IS NOT NULL OR l.total IS NOT NULL)";
                    $whereConditions[] = "(l.check_in IS NULL AND l.check_out IS NULL)";
                }
            } else {
                $whereConditions[] = "(l.parttime_no IS NULL OR l.parttime_no = '')";
                if ($enumTypeFilter) {
                    $whereConditions[] = "lt.enumtype = :enumtype";
                    $queryParams[':enumtype'] = $enumTypeFilter;
                }
            }

            $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);

            if ($leavetype === 'parttime' && $tab === 'profiledata') {
                $sql = "
            SELECT DISTINCT ON (l.parttime_no)
                l.parttime_no,
                l.parttime_name,
                g.enumtext_id AS grade_name,
                ml.enumtext_id AS member_level_name,
                jp.enumtext_id AS jenis_parttime_name,
                l.working_at,
                l.contact_phone1,
                l.contact_email1,
                CASE WHEN l.is_active = '1' THEN 'Aktif' ELSE 'Tidak Aktif' END AS status_aktif
            FROM leave l
            LEFT JOIN enum g ON g.enumid = l.grade
            LEFT JOIN enum ml ON ml.enumid = l.member_level
            LEFT JOIN enum jp ON jp.enumid = l.jenis_parttime
            $whereClause
            ORDER BY l.parttime_no ASC, l.leaveid DESC
            ";

                $headers = ['No. Parttime', 'Nama', 'Grade', 'Member Level', 'Jenis Parttime', 'Tempat Bekerja', 'Telepon', 'Email', 'Status'];
            } elseif ($leavetype === 'parttime' && $tab === 'workattendance') {
                $sql = "
            SELECT 
                COALESCE(l.parttime_no, lp.parttime_no) AS parttime_no,
                COALESCE(l.parttime_name, lp.parttime_name) AS parttime_name,
                TO_CHAR(l.leavedate, 'DD-MM-YYYY') AS tanggal,
                TO_CHAR(l.check_in, 'HH24:MI') AS jam_masuk,
                TO_CHAR(l.check_out, 'HH24:MI') AS jam_keluar,
                l.late_duration AS terlambat,
                l.overtime_duration AS lembur,
                lt.enumtext_id AS jenis_absensi
            FROM leave l
            LEFT JOIN leave lp ON lp.leaveid = l.contactid AND lp.parttime_no IS NOT NULL
            LEFT JOIN enum lt ON lt.enumid = l.leavetype
            $whereClause
            ORDER BY l.leavedate DESC
            ";

                $headers = ['No. Parttime', 'Nama', 'Tanggal', 'Jam Masuk', 'Jam Keluar', 'Terlambat', 'Lembur', 'Jenis Absensi'];
            } elseif ($leavetype === 'parttime' && $tab === 'parttimepayment') {
                $sql = "
            SELECT 
                COALESCE(l.parttime_no, lp.parttime_no) AS parttime_no,
                COALESCE(l.parttime_name, lp.parttime_name) AS parttime_name,
                TO_CHAR(l.leavedate, 'DD-MM-YYYY') AS tanggal_kerja,
                COALESCE(pmt.enumtext_id, 
                    CASE WHEN l.bankname IS NOT NULL 
                    THEN 'TRANSFER - ' || l.bankname 
                    ELSE '-' 
                    END
                ) AS metode_pembayaran,
                l.total,
                TO_CHAR(l.payment_date, 'DD-MM-YYYY') AS tanggal_bayar,
                ps.enumtext_id AS status_pembayaran
            FROM leave l
            LEFT JOIN leave lp ON lp.leaveid = l.contactid AND lp.parttime_no IS NOT NULL
            LEFT JOIN enum pmt ON pmt.enumid = l.payment
            LEFT JOIN enum ps ON ps.enumid = l.payment_status
            LEFT JOIN enum lt ON lt.enumid = l.leavetype
            $whereClause
            ORDER BY l.payment_date DESC
            ";

                $headers = ['No. Parttime', 'Nama', 'Tanggal Kerja', 'Metode Pembayaran', 'Total', 'Tanggal Bayar', 'Status Pembayaran'];
            } elseif ($leavetype === 'absent') {
                $sql = "
            SELECT 
                c.contact_name,
                l.employee_code,
                lt.enumtext_id AS jenis_absensi,
                TO_CHAR(l.leavedate, 'DD-MM-YYYY') AS tanggal,
                TO_CHAR(l.check_in, 'HH24:MI') AS jam_masuk,
                TO_CHAR(l.check_out, 'HH24:MI') AS jam_keluar,
                l.late_duration AS terlambat,
                l.overtime_duration AS lembur,
                l.note AS keterangan
            FROM leave l
            LEFT JOIN contacts c ON c.contact_id = l.contactid
            LEFT JOIN enum lt ON lt.enumid = l.leavetype
            $whereClause
            ORDER BY l.leavedate DESC
            ";

                $headers = ['Nama Karyawan', 'Kode Karyawan', 'Jenis Absensi', 'Tanggal', 'Jam Masuk', 'Jam Keluar', 'Terlambat', 'Lembur', 'Keterangan'];
            } else {
                $sql = "
            SELECT 
                c.contact_name,
                l.employee_code,
                lt.enumtext_id AS jenis_cuti,
                TO_CHAR(l.leavedate, 'DD-MM-YYYY') AS tanggal_mulai,
                TO_CHAR(l.leaveduedate, 'DD-MM-YYYY') AS tanggal_selesai,
                l.note AS alasan,
                CASE 
                    WHEN l.status = '1' THEN 'Pending'
                    WHEN l.status = '2' THEN 'Approved'
                    WHEN l.status = '3' THEN 'Rejected'
                    ELSE 'Unknown'
                END AS status
            FROM leave l
            LEFT JOIN contacts c ON c.contact_id = l.contactid
            LEFT JOIN enum lt ON lt.enumid = l.leavetype
            $whereClause
            ORDER BY l.leavedate DESC
            ";

                $headers = ['Nama Karyawan', 'Kode Karyawan', 'Jenis Cuti', 'Tanggal Mulai', 'Tanggal Selesai', 'Alasan', 'Status'];
            }

            $data = Yii::$app->db->createCommand($sql, $queryParams)->queryAll();

            if (empty($data)) {
                Yii::$app->session->setFlash('warning', 'Tidak ada data untuk di-export pada periode tersebut.');
                return $this->redirect(['index', 'leavetype' => $leavetype, 'tab' => $tab]);
            }

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Data Export');

            // Write Header
            $colIndex = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($colIndex . '1', $header);
                $sheet->getStyle($colIndex . '1')->getFont()->setBold(true);
                $sheet->getStyle($colIndex . '1')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFD9EAD3'); // Light green fill
                $colIndex++;
            }

            $rowIndex = 2;
            foreach ($data as $row) {
                if ($leavetype === 'parttime' && $tab === 'parttimepayment' && isset($row['total'])) {
                    $row['total'] = $row['total'] ? 'Rp ' . number_format($row['total'], 0, ',', '.') : '-';
                }

                $colIndex = 'A';
                foreach ($row as $val) {
                    $sheet->setCellValue($colIndex . $rowIndex, $val);
                    $colIndex++;
                }
                $rowIndex++;
            }

            $lastCol = $sheet->getHighestColumn();
            foreach (range('A', $lastCol) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $tabName = $leavetype === 'parttime' ? '_' . ucfirst($tab) : '';
            $filename = 'Export_' . ucfirst($leavetype) . $tabName . '_' . date('Ymd_His') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        } catch (\Exception $e) {
            Yii::error('Export Excel Error: ' . $e->getMessage(), 'leave-export');
            Yii::$app->session->setFlash('error', 'Gagal export: ' . $e->getMessage());
            return $this->redirect(['index', 'leavetype' => $leavetype, 'tab' => $tab]);
        }
    }
    /**
     * Deteksi kolom dari header CSV
     */
    private function detectColumns($headerRow)
    {
        $map = [];

        foreach ($headerRow as $index => $header) {
            $header = strtolower(trim($header));

            // Nama employee
            if (
                strpos($header, 'nama') !== false ||
                strpos($header, 'name') !== false ||
                strpos($header, 'employee') !== false ||
                strpos($header, 'karyawan') !== false
            ) {
                $map['name'] = $index;
            }

            // Tanggal
            if (
                strpos($header, 'tanggal') !== false ||
                strpos($header, 'date') !== false ||
                strpos($header, 'tgl') !== false
            ) {
                $map['date'] = $index;
            }

            // Check In
            if (
                strpos($header, 'masuk') !== false ||
                strpos($header, 'check in') !== false ||
                strpos($header, 'checkin') !== false ||
                strpos($header, 'clock in') !== false ||
                strpos($header, 'in') === 0
            ) {
                $map['checkin'] = $index;
            }

            if (
                strpos($header, 'keluar') !== false ||
                strpos($header, 'check out') !== false ||
                strpos($header, 'checkout') !== false ||
                strpos($header, 'clock out') !== false ||
                strpos($header, 'out') === 0
            ) {
                $map['checkout'] = $index;
            }
        }

        return $map;
    }

    /**
     * Parse time dari berbagai format
     */
    private function parseTime($timeStr, $date)
    {
        $timeStr = trim($timeStr);

        if (empty($timeStr) || $timeStr === '-' || $timeStr === 'N/A') {
            return null;
        }

        if (preg_match('/^(\d{1,2}):(\d{2})(?::(\d{2}))?$/', $timeStr, $matches)) {
            $hour = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $minute = $matches[2];
            $second = $matches[3] ?? '00';

            return "$date $hour:$minute:$second";
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $timeStr)) {
            return $timeStr;
        }

        return null;
    }

    public function actionDownloadsamplereal($leavetype = '')
    {

        if (empty($leavetype)) {
            $path = Yii::$app->request->pathInfo;
            if (strpos($path, 'absent') === 0) {
                $leavetype = 'absent';
            } elseif (strpos($path, 'leave') === 0) {
                $leavetype = 'leave';
            }
        }

        $employees = Yii::$app->db->createCommand(
            "SELECT contact_name FROM contacts 
         WHERE contacttype = 'employee' 
         AND contact_status != '10' 
         ORDER BY contact_name
         LIMIT 5"
        )->queryColumn();

        $leaveTypes = Yii::$app->db->createCommand(
            "SELECT enumtext_id, enumtext_en, enumtype 
         FROM enum 
         WHERE enumtype IN ('leavetype', 'absenttype') 
         ORDER BY enumtype, enumtext_id"
        )->queryAll();

        $filename = 'template_import_leave_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($output, [
            'Nama Employee',
            'Jenis Leave/Absent',
            'Tanggal Mulai (DD/MM/YYYY)',
            'Tanggal Selesai (DD/MM/YYYY)',
            'Alasan (Opsional)',
            'Jam Masuk - HH:MM (Khusus Absent)',
            'Jam Pulang - HH:MM (Khusus Absent)'
        ]);

        if (!empty($employees) && !empty($leaveTypes)) {
            $absentTypes = array_filter($leaveTypes, function ($t) {
                return $t['enumtype'] === 'absenttype';
            });

            $leaveTypesOnly = array_filter($leaveTypes, function ($t) {
                return $t['enumtype'] === 'leavetype';
            });

            if (isset($employees[0]) && !empty($absentTypes)) {
                $hadirType = current(array_filter($absentTypes, function ($t) {
                    return stripos($t['enumtext_id'], 'hadir') !== false;
                }));

                if ($hadirType) {
                    fputcsv($output, [
                        $employees[0],
                        $hadirType['enumtext_id'],
                        date('d/m/Y'),
                        date('d/m/Y'),
                        'Hadir normal',
                        '08:00',
                        '17:00'
                    ]);
                }
            }

            if (isset($employees[1]) && !empty($absentTypes)) {
                $hadirType = current(array_filter($absentTypes, function ($t) {
                    return stripos($t['enumtext_id'], 'hadir') !== false;
                }));

                if ($hadirType) {
                    fputcsv($output, [
                        $employees[1],
                        $hadirType['enumtext_id'],
                        date('d/m/Y'),
                        date('d/m/Y'),
                        'Terlambat',
                        '09:30',
                        '17:00'
                    ]);
                }
            }

            if (isset($employees[2]) && !empty($absentTypes)) {
                $hadirType = current(array_filter($absentTypes, function ($t) {
                    return stripos($t['enumtext_id'], 'hadir') !== false;
                }));

                if ($hadirType) {
                    fputcsv($output, [
                        $employees[2],
                        $hadirType['enumtext_id'],
                        date('d/m/Y'),
                        date('d/m/Y'),
                        'Lembur',
                        '08:00',
                        '20:00'
                    ]);
                }
            }

            // Contoh 4: Cuti Tahunan (tanpa jam)
            if (isset($employees[3]) && !empty($leaveTypesOnly)) {
                fputcsv($output, [
                    $employees[3],
                    reset($leaveTypesOnly)['enumtext_id'],
                    date('d/m/Y', strtotime('+1 day')),
                    date('d/m/Y', strtotime('+3 days')),
                    'Liburan keluarga',
                    '',
                    ''
                ]);
            }

            // Contoh 5: Sakit (tanpa jam)
            if (isset($employees[4]) && !empty($leaveTypesOnly)) {
                $sakitType = current(array_filter($leaveTypesOnly, function ($t) {
                    return stripos($t['enumtext_id'], 'sakit') !== false;
                }));

                if ($sakitType) {
                    fputcsv($output, [
                        $employees[4],
                        $sakitType['enumtext_id'],
                        date('d/m/Y'),
                        date('d/m/Y'),
                        'Flu dan demam',
                        '',
                        ''
                    ]);
                }
            }
        }

        // Instruksi
        fputcsv($output, []);
        fputcsv($output, ['=== PETUNJUK PENGGUNAAN ===']);
        fputcsv($output, ['1. Kolom Nama Employee: Harus sama persis dengan data di database']);
        fputcsv($output, ['2. Kolom Jenis: Pilih dari daftar di bawah (bisa pakai nama Indonesia atau English)']);
        fputcsv($output, ['3. Kolom Tanggal: Format DD/MM/YYYY atau DD-MM-YYYY']);
        fputcsv($output, ['4. Kolom Jam Masuk & Keluar: HANYA untuk tipe ABSENT, format HH:MM']);
        fputcsv($output, ['5. Untuk CUTI/LEAVE: Kosongkan kolom Jam Masuk & Keluar']);
        fputcsv($output, []);

        fputcsv($output, []);
        fputcsv($output, ['=== DAFTAR EMPLOYEE ===']);
        foreach ($employees as $emp) {
            fputcsv($output, [$emp]);
        }

        fputcsv($output, []);
        fputcsv($output, ['=== DAFTAR JENIS LEAVE/ABSENT ===']);
        fputcsv($output, ['Nama Indonesia', 'Nama English', 'Kategori']);
        foreach ($leaveTypes as $type) {
            fputcsv($output, [
                $type['enumtext_id'],
                $type['enumtext_en'],
                $type['enumtype'] === 'absenttype' ? 'ABSENT (perlu jam)' : 'LEAVE (tidak perlu jam)'
            ]);
        }

        fclose($output);
        exit;
    }



    public function actionDownloadTemplate()
    {
        $filename = 'template_import_leave.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Header CSV
        fputcsv($output, [
            'Nama Employee',
            'Jenis Leave/Absent',
            'Tanggal Mulai (DD/MM/YYYY)',
            'Tanggal Selesai (DD/MM/YYYY)',
            'Alasan/Note',
            'Jam Masuk (HH:MM)',
            'Jam Pulang (HH:MM)'
        ]);

        // Contoh data
        fputcsv($output, [
            'John Doe',
            'Hadir',
            '01/01/2025',
            '01/01/2025',
            'Hadir normal',
            '08:00',
            '17:00'
        ]);

        fputcsv($output, [
            'Jane Smith',
            'Cuti Tahunan',
            '05/01/2025',
            '07/01/2025',
            'Liburan keluarga',
            '',
            ''
        ]);

        fclose($output);
        exit;
    }

    public function actionPrint($jenis = '', $id = '', $detailid = '', $jenisreport = 'pdf')
    {
        require_once(Yii::getAlias('@anyname') . "/Report/java/Java.inc");
        $compileManager = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");

        $leavetype = Yii::$app->request->get('leavetype', 'leave');

        $judul = "";
        $report = null;
        $sql = "";
        $sqlsub = "";
        $sqlcount = "";
        $sqlcountsub = "";

        if ($jenis == "0") {
            $judul = "ID Card Karyawan";
            $this->getQueryIDCardParttime($id, $detailid, $sql, $sqlsub, $sqlcount, $sqlcountsub);
            $jrxmlPath = Yii::getAlias('@anyname') . "/Report/rptIDCardPT.jrxml";
            $report = $compileManager->compileReport(realpath($jrxmlPath));
        } elseif ($jenis == "2") {
            $judul = "Laporan";
            $this->getQueryLeave($id, $detailid, $sql, $sqlsub, $sqlcount, $sqlcountsub);
            $jrxmlPath = Yii::getAlias('@anyname') . "/Report/rptIDCard.jrxml";
            $report = $compileManager->compileReport(realpath($jrxmlPath));
        } elseif ($jenis == "3") {
            $judul = ($leavetype === 'absent') ? "Laporan Absensi" : "Laporan Cuti";
            $this->getQueryLeave($id, $detailid, $sql, $sqlsub, $sqlcount, $sqlcountsub);
            $jrxmlPath = Yii::getAlias('@anyname') . "/Report/rptLeave.jrxml";
            $report = $compileManager->compileReport(realpath($jrxmlPath));
        } else {
            throw new \Exception("Jenis laporan tidak valid. Gunakan jenis 0, 2, atau 3.");
        }

        // echo $sql;exit;

        $userId = Yii::$app->user->id;
        $companyData = Yii::$app->db->createCommand("
        SELECT c.* 
        FROM company c 
        LEFT JOIN users u ON u.companyid = c.companyid 
        WHERE u.userid = :userid
    ", [':userid' => $userId])->queryOne();

        $params = new Java("java.util.HashMap");
        $params->put("pquery", $sql);
        $params->put("pquerysub", $sqlsub);
        $params->put("pdirfotoinstansi", Yii::getAlias('@anyname') . "/Report/img/");
        $params->put("pdirfotoemployee", Yii::getAlias('@webroot') . "/uploads/leave/");
        $params->put("pjudul", $judul);
        $params->put("puser", Yii::$app->user->identity->username);
        $params->put("pwaktu", date("d-m-Y H:i:s"));
        $params->put("pinstansi", $companyData['nama_perusahaan'] ?? 'Company Name');
        $params->put("palamat", $companyData['alamat'] ?? '-');
        $params->put("ptelp", $companyData['nomor_telepon'] ?? '-');
        $params->put("pjabatan", Yii::$app->user->identity->jabatan ?? 'Staff');
        $params->put("pusername", strtoupper(Yii::$app->user->identity->username));
        $params->put("SUBREPORT_DIR", Yii::getAlias('@anyname') . "/Report/");

        $namafile = str_replace(" ", "_", $judul) . "_" . ($id ?: 'all') . "_" . date('YmdHis');

        if ($jenisreport == "excel") {
            return Yii::$app->function->PrintExcel($report, $params, $namafile);
        } else if ($jenisreport == "word") {
            return Yii::$app->function->PrintDoc($report, $params, $namafile);
        } else {
            return Yii::$app->function->PrintPDF($report, $params, $namafile);
        }
    }

    public function getQueryIDCardParttime($id, $detailid, &$sql, &$sqlsub, &$sqlcount, &$sqlcountsub)
    {
        $filter = "";
        if (!empty($id) && $id !== 'undefined' && $id !== 'null') {
            $filter = " AND l.leaveid = '" . $id . "' ";
        }

        $temp =
            "SELECT 
            l.leaveid,
            l.parttime_no,
            l.parttime_name,
            
             CASE 
            WHEN l.pict_employee IS NULL OR l.pict_employee = '' 
            THEN 'default-avatar1.jpg'  
            ELSE l.pict_employee 
            END AS pict_employee,
            l.identitycardfile,
            l.studentcardfile,
            
            l.contactid AS contact_id,
            l.contact_phone1 AS parttime_phone,
            l.contact_email1 AS parttime_email,
            l.alamat_tinggal,
            
           
            l.nik,
            l.birth_place,
            TO_CHAR(l.birth_date, 'DD-MM-YYYY') AS birth_date,
            l.npwpno,
            
            
            l.pendidikan_sekolah,
            l.alamat_sekolah,
            l.jurusan,
            l.pengalaman_kerja,
            
           
            l.grade,
            g.enumtext_id AS grade_name,
            l.member_level,
            ml.enumtext_id AS member_level_name,
            l.jenis_parttime,
            jp.enumtext_id AS jenis_parttime_name,
            TO_CHAR(l.tanggal_mulai, 'DD-MM-YYYY') AS tanggal_mulai,
            l.working_at,
            l.current_position,
            l.company_phone,
            l.current_work_status,
            ws.enumtext_id AS work_status_name,
            
           
            CASE WHEN l.is_active = '1' OR l.is_active::boolean = true 
                 THEN 'Aktif' 
                 ELSE 'Tidak Aktif' 
            END AS status_aktif,
            
            
            l.bankname,
            l.bankaccount_no,
            l.account_owner_name,
            l.hubungan_pemilik_rekening,
            hr.enumtext_id AS hubungan_rekening_name,
            
            
            l.father_name,
            TO_CHAR(l.father_bod, 'DD-MM-YYYY') AS father_bod,
            l.father_bop,
            l.father_phone,
            l.father_work,
            l.father_address,
            
            l.mother_name,
            TO_CHAR(l.mother_bod, 'DD-MM-YYYY') AS mother_bod,
            l.mother_bop,
            l.mother_phone,
            l.mother_work,
            l.mother_address,
            
            l.guardian_name,
            TO_CHAR(l.guardian_bod, 'DD-MM-YYYY') AS guardian_bod,
            l.guardian_bop,
            l.guardian_phone,
            l.guardian_work,
            l.guardian_address,
            
            l.spouse_name,
            TO_CHAR(l.spouse_bod, 'DD-MM-YYYY') AS spouse_bod,
            l.spouse_bop,
            l.spouse_phone,
            l.spouse_work,
            l.spouse_address,
            
           
            l.keterangan_khusus
            
        FROM leave l
        LEFT JOIN enum g ON g.enumid = l.grade AND g.enumtype = 'grade'
        LEFT JOIN enum ml ON ml.enumid = l.member_level AND ml.enumtype = 'member_level'
        LEFT JOIN enum jp ON jp.enumid = l.jenis_parttime AND jp.enumtype = 'jenis_parttime'
        LEFT JOIN enum ws ON ws.enumid = l.current_work_status AND ws.enumtype = 'current_work_status'
        LEFT JOIN enum hr ON hr.enumid = l.hubungan_pemilik_rekening AND hr.enumtype = 'hubungan_rekening'
        LEFT JOIN contacts c ON c.contact_id = l.contactid
        
        WHERE l.status != '10'
            AND l.parttime_no IS NOT NULL 
            AND l.parttime_no != ''
            AND l.contactid IS NULL
            $filter
        ORDER BY l.parttime_no ASC
    ";

        // FINAL QUERY UNTUK JASPER
        $sql = "SELECT * FROM ( $temp ) AS result";

        // SUB QUERY (untuk detail)
        if ($id != '') {
            $sqlsub = "SELECT * FROM leave WHERE leaveid = '" . $id . "'";
        } else {
            $sqlsub = "SELECT * FROM leave WHERE 1=0";
        }

        $sqlcount = "SELECT COUNT(*) AS count FROM ( $temp ) AS result";
        $sqlcountsub = "SELECT COUNT(*) AS count FROM ( $sqlsub ) AS result";
    }
    public function getQueryLeave($id, $detailid, &$sql, &$sqlsub, &$sqlcount, &$sqlcountsub)
    {
        $startDate = Yii::$app->request->get('start_date');
        $endDate = Yii::$app->request->get('end_date');
        $leavetype = Yii::$app->request->get('leavetype', 'leave');

        $whereConditions = ["l.status <> '10'"];

        if ($leavetype === 'parttime') {
            $enumTypeFilter = 'parttimetype';
        } else {
            $whereConditions[] = "(l.parttime_no IS NULL OR l.parttime_no = '')";
            if ($leavetype === 'leave') {
                $enumTypeFilter = 'leavetype';
            } elseif ($leavetype === 'absent') {
                $enumTypeFilter = 'absenttype';
            } else {
                $enumTypeFilter = null;
            }
        }

        if (!empty($enumTypeFilter)) {
            $whereConditions[] = "(lt.enumtype = '$enumTypeFilter' OR lt.enumid IS NULL)";
        }

        if (!empty($detailid)) {
            $whereConditions[] = "l.leaveid = '" . $detailid . "'";
        } elseif (!empty($id) && $id !== 'all') {
            $whereConditions[] = "l.leaveid = '" . $id . "'";
        } else {
            if (!empty($startDate) && !empty($endDate)) {
                $whereConditions[] = "DATE(l.leavedate) BETWEEN '{$startDate}' AND '{$endDate}'";
            }
        }

        $whereClause = "WHERE " . implode(" AND ", $whereConditions);

        $temp = "SELECT 
            l.leaveno,
            l.leaveid,
            l.contactid,
            c.contact_name,
            c.contact_no,
            c.contact_phone1,
            c.address,
            c.idnumber,
            c.positionid,
            c.divisionid,
            p.enumtext_id AS position_name,
            d.enumtext_id AS division_name,
            l.note,
            l.leavetype,
            lt.enumtext_id AS leavetype_name,
            lt.enumtext_en AS leavetype_name_en,
            l.leavedate,
            l.leaveduedate,
            l.check_in,
            l.check_out,
            l.late_duration,
            l.overtime_duration,
            CASE 
                WHEN l.status = '1' THEN 'Pending'
                WHEN l.status = '2' THEN 'Approved'
                WHEN l.status = '3' THEN 'Rejected'
                ELSE 'Unknown'
            END AS status_text
        FROM leave l
        LEFT JOIN contacts c ON c.contact_id = l.contactid
        LEFT JOIN enum p ON p.enumid = c.positionid
        LEFT JOIN enum d ON d.enumid = c.divisionid
        LEFT JOIN enum lt ON lt.enumid = l.leavetype
        {$whereClause}
        ORDER BY l.createdat DESC";

        $sql = "SELECT * FROM ( $temp ) AS result";

        $useId = !empty($detailid) ? $detailid : (!empty($id) && $id !== 'all' ? $id : '');

        if ($useId !== '') {
            $sqlsub = "SELECT * FROM leave WHERE leaveid = '" . $useId . "'";
        } else {
            $sqlsub = "SELECT * FROM leave WHERE 1=0";
        }

        $sqlcount = "SELECT COUNT(*) AS count FROM ( $temp ) AS result";
        $sqlcountsub = "SELECT COUNT(*) AS count FROM ( $sqlsub ) AS result";
    }
}
