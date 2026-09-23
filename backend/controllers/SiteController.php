<?php

namespace backend\controllers;

use Yii;
use yii\web\Response;
use common\models\User;
use common\models\Contact;
use common\models\Tran;
// use common\models\Cashs;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\LoginForm;
use common\models\SignupForm;
use common\models\ContactForm;
use yii\filters\AccessControl;
use common\models\ForgotPassword;
use common\models\ResetPasswordForm;
use common\models\PasswordResetRequestForm;

/**
 * Site controller
 */
class SiteController extends BaseController
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
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [

                    [
                        'actions' => ['logout', 'index', 'getcompanies', 'verifyemail', 'admin', 'saleschartdata', 'changepassword'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['test', 'log'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => [
                            'fbdelete',
                            'login',
                            'forgot',
                            'changepassword',
                            'resetpassword',
                            'auth',
                            'signup',
                            'error',
                            'setlang',
                            'genlang',
                            'chartdata',
                            'chartpie',
                            'countlist',
                            'countlist2',
                            'verifyemail',
                            'calendar',
                            'employee',
                            'product',
                            'getcrew',
                            'getcrewlist',
                            'getproduct',
                            'getproductlist',
                            'getproject',
                            'getprojectlist',
                            'topcustomer',
                            'saleschartdata',
                            'salesdata',
                            'trackview',
                            'trackviewno',
                            'getinvoicecalendar',
                            'getinvoicedetail',
                        ],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['clearcache'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            $roleId = Yii::$app->user->identity->role_id;
                            $roleName = Yii::$app->function->findByField("enum_name", "enums", " and enum_type='users_roles' and enum_no = '$roleId'");
                            return in_array($roleName, ['Superadmin', 'Admin']);
                        }
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                    'auth' => ['get'],
                ],
            ],

        ];
    }
    /**
     * {@inheritdoc}
     */
    // backend/controllers/SiteController.php
    // Di SiteController.php
    public function actionForgot()
    {
        $model = new ForgotPassword();
        if ($model->load(Yii::$app->request->post()) && $model->sendEmail()) {
            Yii::$app->session->setFlash('success', 'Link reset password sudah dikirim ke email Anda.');
            return $this->goHome();
        }

        $this->layout = 'main-login';
        return $this->render('forgot_password', ['model' => $model]);
    }

    public function actionChangepassword()
    {
        $model = User::findOne(Yii::$app->user->id);
        $model->scenario = 'resetPassword';

        if ($model->load(Yii::$app->request->post())) {
            // var_dump($model->load(Yii::$app->request->post())); exit;
            if ($model->validate()) {
                if ($model->resetPassword()) {
                    Yii::$app->session->setFlash('success', 'Password berhasil diubah.');
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal mengubah password.');
                }
            }
        }

        return $this->render('change_password', [
            'model' => $model,
        ]);
    }

    public function actionResetpassword($token)
    {
        $user = User::findByPasswordResetToken($token);
        if (!$user) {
            //   throw new BadRequestHttpException('Token tidak valid atau sudah kadaluarsa.');
        }
        // var_dump($user);
        // exit;
        $model = new ResetPasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //var_dump($user->setPassword);exit;
            $user->setPassword($model->password);
            $user->removePasswordResetToken();
            if ($user->save()) {
                Yii::$app->session->setFlash('success', 'Password berhasil direset. Silakan login.');
                return $this->redirect(['site/login']);
            }
        }

        $this->layout = 'main-login';
        return $this->render('reset-password', [
            'model' => $model,
        ]);
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
                'successUrl' => $this->successUrl
            ],
        ];
    }

    public function actionClearcache()
    {
        $sql = "SELECT A.enumid as id,enumtext_en, enumtext_id FROM enum A";
        $rows = Yii::$app->db->createCommand($sql)->queryAll();
        Yii::$app->function->generateJson('enum.json', $rows);

        $sql = "SELECT A.langtype||'.'||langno as id,langtext_en,langtext_id FROM lang A";
        $rows = Yii::$app->db->createCommand($sql)->queryAll();
        Yii::$app->function->generateJson('lang.json', $rows);



        Yii::$app->cache->flush();
    }

    public function actionGetproject()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->request->queryParams;
        $eventtypeid = $params['eventtypeid'] ?? '';
        $mode = $params['mode'] ?? 'calendar';
        $tranid = $params['tranid'] ?? null;

        $query =
            "SELECT 
            t.tranid,
            t.tranno,
            t.eventname,
            t.locations,
            c.contact_name,
            c.jobcompany,
            t.trandate,
            t.tranduedate,
            t.setupdate
        FROM trans t
        LEFT JOIN contacts c ON t.contact_id = c.contact_id
        WHERE t.status = 1 AND t.withdrawaldate >= CURRENT_DATE
        AND t.trantype = 'sales/order' AND t.statuspro NOT IN ('0', '15')
        ";

        if ($mode === 'project' && $tranid) {
            $safeTranid = Yii::$app->db->quoteValue($tranid);
            $query .= " AND t.tranid = $safeTranid";
        }

        if ($eventtypeid !== '' && $eventtypeid !== null) {
            $safeEventTypeId = Yii::$app->db->quoteValue($eventtypeid);
            $query .= " AND EXISTS (
            SELECT 1 FROM tranevents te 
            WHERE te.tranid = t.tranid AND te.eventtypeid = $safeEventTypeId
        )";
        }

        $query .= " ORDER BY t.tranid ASC";

        $data = Yii::$app->db->createCommand($query)->queryAll();

        $colors = ['#3788d8', '#f1416c', '#50cd89', '#ffc700', '#7239ea', '#009ef7', '#f6c000', '#ff5733', '#33ff57', '#3357ff', '#ff33a8'];

        $projectColors = [];
        $productMap = [];
        $colorIndex = 0;
        $events = [];

        foreach ($data as $row) {
            if (!isset($projectColors[$row['tranid']])) {
                $projectColors[$row['tranid']] = $colors[$colorIndex % count($colors)];
                $colorIndex++;
            }

            if (!isset($productMap[$row['tranid']])) {
                $safeRowTranid = Yii::$app->db->quoteValue($row['tranid']);

                $productQuery =
                    "SELECT 
                    p.productname,
                    p.productpict,
                    td.amount,
                    td.unit
                FROM trandetails td
                LEFT JOIN products p ON td.productid = p.productid
                WHERE td.tranid = $safeRowTranid
                AND td.productid IS NOT NULL
                ORDER BY td.ord ASC
            ";

                $products = Yii::$app->db->createCommand($productQuery)->queryAll();

                foreach ($products as &$p) {
                    if (!empty($p['productpict'])) {
                        $p['productpict'] = Yii::getAlias('@web/uploads/produk/') . $p['productpict'];
                    }
                }
                unset($p);

                $productMap[$row['tranid']] = $products;
            }

            $eventColor = $projectColors[$row['tranid']];

            $startRaw = $row['setupdate'] ?: $row['trandate'];
            $endRaw = $row['tranduedate'] ?: $row['trandate'];

            $start = strtotime($startRaw) ?: strtotime($row['trandate']);
            $end = strtotime($endRaw) ?: $start;

            if ($end < $start) {
                $end = $start;
            }

            $events[] = [
                'tranid' => $row['tranid'],
                'id' => $row['tranid'],
                'title' => $row['jobcompany'] . ' - ' . ($row['eventname'] ?: $row['locations']),
                'start' => date('Y-m-d', $start),
                'end' => date('Y-m-d', strtotime('+1 day', $end)),
                'allDay' => true,
                'display' => 'block',
                'color' => $eventColor,
                'backgroundColor' => $eventColor,
                'borderColor' => $eventColor,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'tranno' => $row['tranno'],
                    'eventname' => $row['eventname'],
                    'location' => $row['locations'] ?? 'Tidak ada lokasi',
                    'customer' => $row['contact_name'] ?? 'Tidak ada customer',
                    'products' => $productMap[$row['tranid']],
                    'trandate' => $row['trandate'] ? date('d M Y', strtotime($row['trandate'])) : '-',
                    'tranduedate' => $row['tranduedate'] ? date('d M Y', strtotime($row['tranduedate'])) : '-',
                    'setupdate' => $row['setupdate'] ? date('d M Y', strtotime($row['setupdate'])) : '-'
                ]
            ];
        }

        return $events;
    }

    public function actionGetprojectlist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $eventTypes = [
            ['id' => '0', 'name' => 'Setup'],
            ['id' => '1', 'name' => 'Event'],
            ['id' => '2', 'name' => 'Bongkar'],
            ['id' => '3', 'name' => 'Antar'],
            ['id' => '4', 'name' => 'Tarik']
        ];

        return $eventTypes;
    }
    public function actionGetcrew()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->request->queryParams;

        $start = isset($params['start']) ? $params['start'] : '';
        $end = isset($params['end']) ? $params['end'] : '';
        $crewid = isset($params['crewid']) ? $params['crewid'] : '';

        $query =
            "SELECT 
                tec.traneventcrewid,
                tec.crewid,
                tec.crewtypeid,
                te.startdate,
                te.enddate,
                te.traneventid,
                te.eventtypeid,
                t.tranid,
                t.tranno,
                t.eventname,
                t.locations,
                t.note,
                c.contact_name as client_name,
                crew.contact_name as crew_name,
                crew.contact_phone1 as crew_phone
            FROM traneventcrews tec
            LEFT JOIN tranevents te ON tec.traneventid = te.traneventid
            LEFT JOIN trans t ON te.tranid = t.tranid
            LEFT JOIN contacts c ON t.contact_id = c.contact_id
            LEFT JOIN contacts crew ON tec.crewid = crew.contact_id
            WHERE t.status = 1 AND t.statuspro NOT IN ('0', '15')
            AND t.trantype = 'sales/order'
            AND (
                te.startdate BETWEEN '$start' AND '$end'
                OR te.enddate BETWEEN '$start' AND '$end'
                OR (te.startdate <= '$start' AND te.enddate >= '$end')
            )
        ";

        if (!empty($crewid)) {
            $query .= " AND tec.crewid = '$crewid'";
        }

        $query .= " ORDER BY te.startdate ASC";
        // echo($query);exit;
        $crew_events = Yii::$app->db->createCommand($query)->queryAll();
        $colors = ['#3788d8', '#f1416c', '#50cd89', '#ffc700', '#7239ea', '#009ef7', '#f6c000'];

        $eventTypeNames = [
            0 => 'Setup',
            1 => 'Event',
            2 => 'Bongkar',
            3 => 'Antar',
            4 => 'Tarik'
        ];

        $crewTypeMap = [
            'pi' => 'PIC',
            'op' => 'Operator',
            'sb' => 'Standby',
            'cr' => 'Crew',
            'dr' => 'Driver',
            'fe' => 'Freelance'
        ];

        $projectColors = [];
        $colorIndex = 0;

        $events = [];
        foreach ($crew_events as $row) {
            if (!isset($projectColors[$row['tranid']])) {
                $projectColors[$row['tranid']] = $colors[$colorIndex % count($colors)];
                $colorIndex++;
            }

            $eventColor = $projectColors[$row['tranid']];
            $crewTypeName = isset($crewTypeMap[$row['crewtypeid']])
                ? $crewTypeMap[$row['crewtypeid']]
                : 'Staff';

            $eventtypeid = (int) $row['eventtypeid'];
            $eventTypeName = isset($eventTypeNames[$eventtypeid]) ? $eventTypeNames[$eventtypeid] : '-';

            $events[] = [
                'id' => $row['traneventcrewid'],
                'title' => ($row['crew_name'] ?? 'Unknown') . ' - ' . ($row['eventname'] ?? '-'),
                'start' => $row['startdate'],
                'end' => $row['enddate'],
                'leavetitle' => ($row['fullname'] ?? 'Unknown') . ' - ' . ($row['leavetype'] ?? '-'),
                'leavestart' => $row['leavedate'] ?? null,
                'leaveend' => $row['leaveduedate'] ?? null,
                'allDay' => false,
                'display' => 'block',
                'backgroundColor' => $eventColor,
                'borderColor' => $eventColor,
                'extendedProps' => [
                    'tranid' => $row['tranid'],
                    'crew_name' => $row['crew_name'],
                    'crew_phone' => $row['crew_phone'],
                    'crewtype_name' => $crewTypeName,
                    'eventtypeid' => $row['eventtypeid'],
                    'eventtype_name' => $eventTypeName,
                    'eventname' => $row['eventname'],
                    'tranno' => $row['tranno'],
                    'client_name' => $row['client_name'],
                    'locations' => $row['locations'],
                    'leavetype' => $row['leavetype'] ?? null,
                    'fullname' => $row['fullname'] ?? null,
                    'note' => $row['note']
                ]
            ];
        }

        $leaveQuery =
            "SELECT 
            l.leaveid,
            l.leavedate,
            l.leaveduedate,
            l.leaveno,
            l.status,
            e.enumtext_en as leavetype,
            c.contact_name as fullname
        FROM leave l
        LEFT JOIN contacts c ON l.contactid = c.contact_id
        LEFT JOIN enum e ON e.enumid = l.leavetype AND e.enumtype = 'leavetype'
        WHERE l.status = '1' AND l.leave_status = '1'
        AND e.enumtype = 'leavetype'
         AND (
            l.leavedate BETWEEN '$start' AND '$end'
            OR l.leaveduedate BETWEEN '$start' AND '$end'
            OR (l.leavedate <= '$start' AND l.leaveduedate >= '$end')
        )
        ";

        if (!empty($crewid)) {
            $leaveQuery .= " AND l.contactid = '$crewid'";
        }

        $leave_data = Yii::$app->db->createCommand($leaveQuery)->queryAll();

        foreach ($leave_data as $leave) {
            if (!isset($projectColors[$leave['leaveid']])) {
                $projectColors[$leave['leaveid']] = $colors[$colorIndex % count($colors)];
                $colorIndex++;
            }

            $eventColor = $projectColors[$leave['leaveid']];

            if (
                $leave['leavedate'] > $end ||
                $leave['leaveduedate'] < $start
            ) {
                continue;
            }

            $events[] = [
                'id' => $leave['leaveid'],
                'title' => ($leave['fullname'] ?? 'Unknown') . ' - ' . ($leave['leavetype'] ?? '-'),
                'start' => $leave['leavedate'],
                'end' => $leave['leaveduedate'],
                'allDay' => false,
                'display' => 'block',
                'backgroundColor' => $eventColor,
                'borderColor' => $eventColor,
                'extendedProps' => [
                    'type' => 'leave',
                    'leavetype' => $leave['leavetype'],
                    'leaveno' => $leave['leaveno'],
                    'fullname' => $leave['fullname'] ?? null,
                    'note' => $leave['note']
                ]
            ];
        }

        return $events;
    }

    /**
     * Action untuk mendapatkan daftar crew untuk filter dropdown
     */
    public function actionGetcrewlist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $query =
            "SELECT DISTINCT 
            c.contact_id as crewid,
            c.contact_name as crew_name
        FROM traneventcrews tec
        LEFT JOIN contacts c ON tec.crewid = c.contact_id
        WHERE c.contact_name IS NOT NULL AND c.contact_status <> '10'
        ORDER BY c.contact_name ASC
     ";

        $crews = Yii::$app->db->createCommand($query)->queryAll();

        return $crews;
    }

    public function actionGetinvoicecalendar()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->request->queryParams;

        $month = (int) ($params['month'] ?? date('n'));
        $year = (int) ($params['year'] ?? date('Y'));
        $contact = $params['contact_id'] ?? '';
        $statusF = $params['status'] ?? '';

        $query = "SELECT
                t.tranid,
                t.tranno,
                t.trandate,
                t.tranduedate,
                t.grandtotal,
                t.statuspro,
                COALESCE(SUM(k.totalpaid), 0) AS totalpaid,
                c.contact_name AS client_name
            FROM trans t
            LEFT JOIN cashs k ON t.tranid = k.refid AND k.status <> 10
            LEFT JOIN contacts c ON t.contact_id = c.contact_id
            WHERE t.status = 1
              AND t.trantype = 'sales/invoice'
              AND EXTRACT(MONTH FROM t.tranduedate) = '$month'
              AND EXTRACT(YEAR FROM t.tranduedate) = '$year'";

        if (!empty($contact)) {
            $query .= " AND t.contact_id = '$contact'";
        }

        $query .= " GROUP BY t.tranid, t.tranno, t.trandate, t.tranduedate, t.grandtotal, t.statuspro, c.contact_name";
        $query .= " ORDER BY t.tranduedate ASC";

        $rows = Yii::$app->db->createCommand($query)->queryAll();
        $today = strtotime(date('Y-m-d'));

        $stats = [
            'terkirim' => ['count' => 0, 'amount' => 0],
            'dp' => ['count' => 0, 'amount' => 0],
            'jatuhtempo_terlambat1' => ['count' => 0, 'amount' => 0],
            'baddebt' => ['count' => 0, 'amount' => 0],
            'lunas' => ['count' => 0, 'amount' => 0],
            'total' => ['count' => 0, 'amount' => 0],
        ];

        $invoicesByDate = [];

        foreach ($rows as $r) {
            $amount = (float) $r['grandtotal'];
            $paid = (float) $r['totalpaid'];
            $due = $r['tranduedate'] ? strtotime($r['tranduedate']) : null;
            $diffDays = $due ? (int) floor(($today - $due) / 86400) : 0;
            $statuspro = (string) $r['statuspro'];

            if ($amount > 0 && $paid >= $amount) {
                $status = 'lunas';
                $badge = 'Lunas';
            } elseif ($diffDays > 60) {
                $status = 'baddebt';
                $badge = 'Terlambat ' . $diffDays . ' hari';
            } elseif ($diffDays > 30) {
                $status = 'terlambat1';
                $badge = 'Terlambat ' . $diffDays . ' hari';
            } elseif ($diffDays > 0) {
                $status = 'jatuhtempo';
                $badge = 'Jatuh Tempo (' . $diffDays . ' hari)';
            } elseif ($paid > 0) {
                $status = 'dp';
                $badge = 'Sudah DP';
            } elseif ($statuspro === '1') {
                $status = 'terkirim';
                $badge = 'Terkirim';
            } else {
                $status = 'draft';
                $badge = 'Draft';
            }

            $statKey = in_array($status, ['jatuhtempo', 'terlambat1']) ? 'jatuhtempo_terlambat1' : $status;

            if (isset($stats[$statKey])) {
                $stats[$statKey]['count']++;

                if ($statKey === 'dp') {
                    $stats[$statKey]['amount'] += $paid;
                } else {
                    $stats[$statKey]['amount'] += $amount;
                }
            }
            $stats['total']['count']++;
            $stats['total']['amount'] += $amount;

            if ($statusF && $statusF !== $status) {
                continue;
            }

            if (!$due) {
                continue;
            }

            $day = (int) date('j', $due);
            if (!isset($invoicesByDate[$day])) {
                $invoicesByDate[$day] = [];
            }

            $invoicesByDate[$day][] = [
                'tranid' => $r['tranid'],
                'code' => $r['tranno'],
                'status' => $status,
                'badge' => $badge,
                'client' => $r['client_name'] ?: 'Tidak ada customer',
                'amount' => 'Rp ' . number_format($amount, 0, ',', '.'),
                'paid' => 'Rp ' . number_format($paid, 0, ',', '.'),
                'sisa' => 'Rp ' . number_format($amount - $paid, 0, ',', '.'),
                'meta' => 'Jatuh tempo: ' . date('d M Y', $due),
                'extra' => in_array($status, ['terlambat1', 'baddebt']) ? $diffDays . ' hari terlambat' : '',
            ];
        }

        $firstDayTs = strtotime("$year-$month-01");
        $daysInMonth = (int) date('t', $firstDayTs);
        $firstDowIso = (int) date('N', $firstDayTs);

        $isCurrentMonth = ($year === (int) date('Y') && $month === (int) date('n'));

        return [
            'invoicesByDate' => $invoicesByDate,
            'summary' => $stats,
            'daysInMonth' => $daysInMonth,
            'leadingCount' => $firstDowIso - 1,
            'daysInPrevMonth' => (int) date('t', strtotime('-1 month', $firstDayTs)),
            'todayDay' => $isCurrentMonth ? (int) date('j') : null,
        ];
    }

    public function actionGetinvoicedetail()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = Yii::$app->request->get('id');

        $header = Yii::$app->db->createCommand("
        SELECT t.tranid, t.tranno, t.trandate, t.tranduedate, t.grandtotal, t.note, t.statuspro,
               COALESCE(SUM(k.totalpaid), 0) AS totalpaid,
               c.contact_name, c.jobcompany, c.contact_phone1
        FROM trans t
        LEFT JOIN cashs k ON t.tranid = k.refid AND k.status <> 10
        LEFT JOIN contacts c ON t.contact_id = c.contact_id
        WHERE t.tranid = '$id' AND t.status <> 10
        GROUP BY t.tranid, t.tranno, t.trandate, t.tranduedate, t.grandtotal, t.note, t.statuspro,
                 c.contact_name, c.jobcompany, c.contact_phone1
     ")->queryOne();

        if (!$header) {
            return ['success' => false, 'message' => 'Invoice tidak ditemukan'];
        }

        $items = Yii::$app->db->createCommand("
        SELECT p.productname, td.amount as qty, td.unit, td.price, td.freqvalue, td.itemsubtotaltax
        FROM trandetails td
        LEFT JOIN products p ON td.productid = p.productid
        WHERE td.tranid = '$id'
        ORDER BY td.ord ASC
    ")->queryAll();

        return [
            'success' => true,
            'header' => $header,
            'items' => $items,
        ];
    }

    public function successCallback($client)
    {
        $attributes = $client->getUserAttributes();

        $fbid = "";
        $googleid = "";
        $instagramid = "";
        $sumber = "";
        $username = "";
        $avatar = null;
        $jk = null;
        if (strpos($client->authUrl, 'facebook') > 0) {
            $fbid = $attributes['id'];
            $sumber = "facebook";
        } elseif (strpos($client->authUrl, 'google') > 0) {
            $googleid = $attributes['id'];
            $sumber = "google";
        } elseif (strpos($client->authUrl, 'instagram') > 0) {
            $instagramid = $attributes['id'];
            $sumber = "instagram";
            Yii::$app->session->set('tokenig', $_GET['code']);
        }

        if (!$this->action instanceof \yii\authclient\AuthAction) {
            throw new \yii\base\InvalidCallException("successCallback is only meant to be executed by AuthAction!");
        }
        $model = new User;

        $email = "";
        $nama = "";
        if (isset($attributes['email']) && $attributes['email'] != null && $attributes['email'] != "") {
            $email = $attributes['email'];
        }

        if (isset($attributes['emails'][0]['value']) && $attributes['emails'][0]['value'] != null && $attributes['emails'][0]['value'] != "") {
            $email = $attributes['emails'][0]['value'];
        }
        if (isset($attributes['name']) && $attributes['name'] != null && $attributes['name'] != "") {
            $nama = $attributes['name'];
        }

        if (isset($attributes['displayName']) && $attributes['displayName'] != null && $attributes['displayName'] != "") {
            $nama = $attributes['displayName'];
        }

        if (isset($attributes['full_name']) && $attributes['full_name'] != null && $attributes['full_name'] != "") {
            $nama = $attributes['full_name'];
        }

        if (isset($attributes['picture']) && $attributes['picture'] != null && $attributes['picture'] != "") {
            $avatar = $attributes['picture'];
        }


        if (isset($attributes['gender']) && $attributes['gender'] != null && $attributes['gender'] != "") {
            if ($attributes['gender'] == "male") {
                $jk = 0;
            } else {
                $jk = 1;
            }
        }

        // 
        if (isset($attributes['image']['url']) && $attributes['image']['url'] != null && $attributes['image']['url'] != "") {
            $avatar = $attributes['image']['url'];
        }


        if (isset($attributes['username']) && $attributes['full_name'] != null && $attributes['username'] != "") {
            $username = $attributes['username'];
        }

        if ($username != "") {
            $username = $username;
        } else if ($email != "") {
            $username = $email;
        } else if ($fbid != "") {
            $username = $fbid;
        } else if ($googleid != "") {
            $username = $googleid;
        } else if ($instagramid != "") {
            $username = $instagramid;

            //Yii::$app->session['tokenig'] = $_GET['code'];
        }
        // var_dump($avatar);
        // exit;
        $array = [
            'username' => $username,
            'sumber' => $sumber,
            'fbid' => $fbid,
            'googleid' => $googleid,
            'instagramid' => $instagramid,
            'email' => $email,
            'nama' => $nama,
            'jk' => $jk,
            'avatar' => $avatar
        ];



        $session = Yii::$app->session;
        $session['attributes'] = $array;

        if ($model->isRegistered($username)) {
            // $modeluser = User::find()->where(['username' => $username])->one();

            $modeluser = User::findByUsername($username);
            // var_dump($modeluser);die;
            if ((Yii::$app->user->login($modeluser, true ? 3600 * 24 * 30 : 0))) {
                //$tokoid = Yii::$app->function->findByField("tokoid", "toko", "and userid ='" . $modeluser['userid'] . "' ");
                // if ($tokoid == "" && $modeluser['grup'] != '2') {
                //return $this->redirect(['site/wizard']);
                //return $this->goHome();
                //}
                //return $this->goHome();
                return $this->redirect(['site/index']);
            }
        } else {
            $this->save();
            /*
            $template = Template::findOne("87fc7736-691e-4049-bdc9-7e5962e2ee89")->isi;

            $template = str_replace("{nama}",$nama,$template);
            $template = str_replace("{email}",$email, $template);
            $template = str_replace("{hp}",$hp, $template);
            $template = str_replace("{username}",$username, $template);
            $template = str_replace("{sumber}", strtoupper($sumber), $template);


            $modelpesannotif = new Pesan;
            $modelpesannotif->jenis = 0;
            $modelpesannotif->tujuan = "cs.doankz@gmail.com";
            $modelpesannotif->subjek = "Pendaftaran via $sumber, ".$nama."";
            $modelpesannotif->isi = $template;;
            $modelpesannotif->status = 0;	
            $modelpesannotif->pengirim = "info@jualanonline.id";
            $modelpesannotif->tokoid = "8a4adf4b-b65c-43c8-a8c1-e29d50026333";

                              //  $email = $model->sendMailRegistrasi("registrasi", 'Konfirmasi Email', $params = []);
            if ($modelpesannotif->save(false)) {
                $modelpesannotif->sendall($modelpesannotif->getPrimaryKey());	
            }

            */
        }
    }
    public function save()
    {
        $model = new User();
        $model->generateAuthKey();
        $model->status = Yii::$app->function->findByField("enum_no", "enums", "and enum_type ='users_status' and lower(enum_name)='active' ");
        $model->role_id = 2;
        if (isset($_SESSION['attributes'])) {
            $model->fb_id = $_SESSION['attributes']['fbid'];
            $model->google_id = $_SESSION['attributes']['googleid'];
            $model->username = $_SESSION['attributes']['username'];
            $model->password = $model->username;
            $model->name = $_SESSION['attributes']['nama'];
            $model->email = $_SESSION['attributes']['email'];
            $model->avatar = $_SESSION['attributes']['avatar'];
        }

        if ($model->validate() && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Anda telah mendaftarkan diri anda sebagai pemilik toko, Sekarang klik buat "toko baru" untuk memulai mendaftarkan toko pertama anda.!');

            if ((Yii::$app->user->login($model, true ? 3600 * 24 * 30 : 3600 * 24 * 30))) {
                /* $tokoid = Yii::$app->function->findByField("tokoid", "toko","and userid ='" . $modeluser['userid'] . "' " );
                if($tokoid == "" && $modeluser['grup'] != '2'){
                    return $this->redirect(['site/wizard']);
                } */
                // var_dump("TES");die;
                return $this->redirect(['site/index']);
            } else {
                // var_dump($model);die;
                $this->action->redirect(Url::to(['site/login'], true));
            }
        } else {
            $this->action->redirect(Url::to(['site/daftar'], true));
        }
    }
    public function actionGetcompanies()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = Yii::$app->user->id;
        $companies = "SELECT nama_perusahaan FROM company WHERE userid='$id'";
        $data = Yii::$app->db->createCommand($companies)->queryAll();
        return ['data' => $data];
    }

    public function actionChartpie()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $db = Yii::$app->db;
        $company = Yii::$app->session->get('companyid');
        $filter = Yii::$app->request->get('filter', 'monthly'); // Default Monthly kalau ga ada filter
        // Tentukan format grouping berdasarkan filter
        switch ($filter) {
            case 'daily':
                $dateFormat = "TO_CHAR(t.trandate, 'YYYY-MM-DD')"; // Format per hari
                break;
            case 'yearly':
                $dateFormat = "TO_CHAR(t.trandate, 'YYYY')"; // Format per tahun
                break;
            case 'monthly':
            default:
                $dateFormat = "TO_CHAR(t.trandate, 'YYYY-MM')"; // Format per bulan
                break;
        }

        $data = $db->createCommand("
            SELECT $dateFormat AS periode, COALESCE(SUM(t.subtotal), 0) AS total
            FROM tran t 
            WHERE t.companyid = :company AND t.trantype = 'purchase/invoice' AND status <> 10
            GROUP BY periode
            ORDER BY periode DESC
        ")->bindValue(':company', $company)
            ->queryAll();

        // Pisahkan label (periode transaksi) dan series (total transaksi)
        $labels = array_column($data, 'periode');
        $series = array_map('intval', array_column($data, 'total'));

        if (empty($labels)) {
            $labels = ['No Data'];
            $series = [1]; // Harus ada nilai agar Pie Chart bisa muncul
        }

        return [
            'labels' => $labels,
            'series' => $series,
        ];
    }

    // public function actionChartdata()
    // {
    //     \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    //     $company = Yii::$app->session->get('companyid');
    //     $db = Yii::$app->db;
    //     $filter = Yii::$app->request->get('filter', 'monthly'); // Default Monthly


    //     return [
    //         'labels' => $labels,
    //         'series' => $series,
    //     ];
    // }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex($companyid = null)
    {
        $userId = Yii::$app->user->id;
        $session = Yii::$app->session;
        $session->remove('company_list');

        $query = "SELECT * FROM company WHERE userid = :userid AND status = 1";
        $data = Yii::$app->db->createCommand($query)
            ->bindValue(':userid', $userId)
            ->queryAll();
        $session->set('company_list', $data);

        if (!$companyid) {
            $companyid = Yii::$app->db->createCommand("SELECT companyid FROM users WHERE userid = :userid")
                ->bindValue(':userid', $userId)
                ->queryScalar();
        }

        if ($companyid) {
            $query = "SELECT * FROM company WHERE userid = :userid AND companyid = :companyid";
            $company = Yii::$app->db->createCommand($query)
                ->bindValue(':userid', $userId)
                ->bindValue(':companyid', $companyid)
                ->queryOne();

            if ($company) {
                $session->set('companyid', $company['companyid']);
                $session->set('company_data', $company);

                Yii::$app->db->createCommand("UPDATE users SET companyid = :companyid WHERE userid = :userid")
                    ->bindValue(':companyid', $company['companyid'])
                    ->bindValue(':userid', $userId)
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

        $totalContacts = Contact::find()
            ->where(['contacttype' => 'customer', 'contact_status' => 1])
            ->count();

        $totalUsers = User::find()
            ->where(['status' => 1])
            ->count();

        $totalTrans = Tran::find()
            ->where(['status' => 1])
            ->count();

        $companyid = $session->get('companyid');

        $totalPenjualan = Yii::$app->db->createCommand("
            SELECT COALESCE(SUM(grandtotal), 0) as total
            FROM trans 
            WHERE status <> 10 
            AND companyid = '$companyid'
        ")->queryScalar();

        return $this->render('index', [
            'totalContacts' => $totalContacts,
            'totalUsers' => $totalUsers,
            'totalTrans' => $totalTrans,
            // 'totalCashs' => $totalCashs,
            'totalPenjualan' => $totalPenjualan,
            // 'totalKas' => $totalKas,
        ]);
    }

    public function actionSaleschartdata()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {
            $startDate = Yii::$app->request->get('start');
            $endDate = Yii::$app->request->get('end');

            $session = Yii::$app->session;
            $companyid = $session->get('companyid');

            if (!$companyid) {
                $userId = Yii::$app->user->id;
                $companyid = Yii::$app->db->createCommand("
                SELECT companyid FROM users WHERE userid = '$userId'
            ")->queryScalar();
            }

            if (!$startDate || !$endDate) {
                $sql = "
                SELECT 
                    TO_CHAR(t.trandate, 'Mon') AS month_name,
                    EXTRACT(MONTH FROM t.trandate) AS month_num,
                    COALESCE(SUM(t.grandtotal), 0) AS total
                FROM trans t
                WHERE t.companyid = '$companyid'
                  AND t.trantype LIKE 'sales/%'
                  AND t.status <> 10
                  AND EXTRACT(YEAR FROM t.trandate) = EXTRACT(YEAR FROM CURRENT_DATE)
                GROUP BY month_name, month_num
                ORDER BY month_num
            ";

                $results = Yii::$app->db->createCommand($sql)->queryAll();

                $monthlyData = array_fill(0, 12, 0);
                $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                foreach ($results as $row) {
                    $monthIndex = (int) $row['month_num'] - 1;
                    $monthlyData[$monthIndex] = (float) $row['total'];
                }

                return [
                    'labels' => $labels,
                    'data' => array_values($monthlyData)
                ];
            }

            $sql = "
            SELECT 
                TO_CHAR(t.trandate, 'DD Mon') AS day_label,
                t.trandate,
                COALESCE(SUM(t.grandtotal), 0) AS total
            FROM trans t
            WHERE t.companyid = '$companyid'
              AND t.trantype LIKE 'sales/%'
              AND t.status <> 10
              AND t.trandate BETWEEN '$startDate' AND '$endDate'
            GROUP BY t.trandate, day_label
            ORDER BY t.trandate
        ";

            $results = Yii::$app->db->createCommand($sql)->queryAll();

            // TAMBAHKAN: Debug log
            Yii::error([
                'startDate' => $startDate,
                'endDate' => $endDate,
                'companyid' => $companyid,
                'results_count' => count($results),
                'sql' => $sql
            ], 'saleschartdata-debug');

            $labels = [];
            $data = [];

            // PERBAIKAN: Jika tidak ada data, kembalikan array kosong tapi tetap valid
            if (empty($results)) {
                return [
                    'labels' => ['No Data'],
                    'data' => [0]
                ];
            }

            foreach ($results as $row) {
                $labels[] = $row['day_label'];
                $data[] = (float) $row['total'];
            }

            return [
                'labels' => $labels,
                'data' => $data
            ];
        } catch (\Exception $e) {
            Yii::error("Saleschartdata Error: " . $e->getMessage());
            return [
                'labels' => ['Error'],
                'data' => [0]
            ];
        }
    }

    public function actionAdmin()
    {
        return $this->render('admin');
    }

    public function actionEmployee()
    {
        return $this->render('calendar', ['module' => 'sales', 'type' => 'order']);
    }

    public function actionGetSaldoBulanan()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $start = Yii::$app->request->get('start', date('Y-m-d', strtotime('-30 days')));
        $end = Yii::$app->request->get('end', date('Y-m-d'));

        $companyId = Yii::$app->session->get('companyid'); // Bisa null

        $query = (new \yii\db\Query())
            ->select(['trandate', 'SUM(subtotal) as total'])
            ->from('tran')
            ->where(['between', 'trandate', $start, $end]);

        if ($companyId) {
            $query->andWhere(['companyid' => $companyId]);
        }

        $result = $query->groupBy('trandate')
            ->orderBy('trandate')
            ->all();

        $labels = [];
        $data = [];

        foreach ($result as $row) {
            $labels[] = Yii::$app->formatter->asDate($row['trandate'], 'php:d M');
            $data[] = (float) $row['total'];
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'debug' => [
                'start' => $start,
                'end' => $end,
                'companyId' => $companyId,
                'raw' => $result
            ]
        ];
    }

    // public function actionIndex($companyid = null)
    // {
    //     $userId = Yii::$app->user->id;

    //     // Hapus session list company agar selalu update, tapi JANGAN hapus companyid
    //     Yii::$app->session->remove('company_list');

    //     // Ambil daftar perusahaan user
    //     $query = "SELECT * FROM company WHERE userid = :userid AND status = 1";
    //     $data = Yii::$app->db->createCommand($query)
    //         ->bindValue(':userid', $userId)
    //         ->queryAll();

    //     Yii::$app->session->set('company_list', $data);

    //     // Ambil companyid dari session atau database jika tidak ada di request
    //     if (!$companyid) {
    //         $companyid = Yii::$app->session->get('companyid') ?? Yii::$app->db->createCommand("
    //         SELECT companyid FROM users WHERE userid = :userid
    //     ")->bindValue(':userid', $userId)->queryScalar();
    //     }

    //     // Pastikan companyid valid
    //     $company = array_filter($data, fn($c) => $c['companyid'] == $companyid);

    //     if ($company) {
    //         $model = array_values($company)[0];

    //         // **Cek apakah companyid berubah**
    //         $oldCompanyId = Yii::$app->session->get('companyid');
    //         if ($oldCompanyId != $model['companyid']) {
    //             // Simpan di session & database hanya jika berubah
    //             Yii::$app->session->set('companyid', $model['companyid']);
    //             Yii::$app->session->set('company_data', $model);

    //             Yii::$app->db->createCommand("UPDATE users SET companyid = :companyid WHERE userid = :userid")
    //                 ->bindValue(':companyid', $model['companyid'])
    //                 ->bindValue(':userid', $userId)
    //                 ->execute();

    //             // Redirect hanya jika datang dengan parameter ?companyid
    //             if ($companyid != null) {
    //                 return var_dump($companyid);
    //                 return $this->redirect(['index']);
    //             }
    //         }
    //     } else {
    //         // Jika companyid tidak valid, pakai yang pertama
    //         $model = !empty($data) ? $data[0] : null;
    //         $companyid = $model ? $model['companyid'] : null;

    //         Yii::$app->session->set('companyid', $companyid);
    //         Yii::$app->session->set('company_data', $model);

    //         // Simpan pilihan default ke database
    //         Yii::$app->db->createCommand("UPDATE users SET companyid = :companyid WHERE userid = :userid")
    //             ->bindValue(':companyid', $companyid)
    //             ->bindValue(':userid', $userId)
    //             ->execute();
    //     }

    //     return $this->render('index', [
    //         'model' => $model,
    //         'data' => $data
    //     ]);
    // }


    // public function actionIndex()
    // {
    //     return $this->render('index');
    // }

    public function actionSetlang($lang)
    {
        Yii::$app->lang->setLang($lang);
        return $this->redirect(Yii::$app->request->referrer);
    }


    public function actionGenlang()
    {
        Yii::$app->lang->genLang();
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            // var_dump("test1");die;
            return $this->goHome();
        }

        // $this->layout = 'blank';
        $this->layout = 'main-login';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // var_dump("test2");die;
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('signin', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
        $this->layout = 'main-login';
        $model = new SignupForm();
        // var_dump($model);exit;
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionVerifyemail($token)
    {

        $user = User::findByVerificationToken($token);

        if (!$user) {
            //  var_dump($user);exit;
            Yii::$app->session->setFlash('error', 'Invalid or expired verification token.');
            return $this->goHome();
        }

        $user->status = 1; // aktifkan akun
        $user->verification_token = null; // kosongkan token
        if ($user->save(false)) {
            Yii::$app->session->setFlash('success', Yii::$app->lang->t('extra', 'extra102'));
        } else {
            Yii::$app->session->setFlash('error', Yii::$app->lang->t('extra', 'extra103'));
        }

        return $this->goHome();
    }

    public function actionFbdelete()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $signed_request = $_POST['signed_request'];
        $data = $this->parse_signed_request($signed_request);
        $user_id = $data['user_id'];
        $status_url = 'https://www.<your_website>.com/deletion?id=abc123'; // URL to track the deletion
        $confirmation_code = 'abc123'; // unique code for the deletion request

        $data = array(
            'url' => $status_url,
            'confirmation_code' => $confirmation_code
        );
        echo json_encode($data);
    }

    public function parse_signed_request($signed_request)
    {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        $secret = "appsecret"; // Use your app secret here

        // decode the data
        $sig = $this->base64_url_decode($encoded_sig);
        $data = json_decode(base64_url_decode($payload), true);

        // confirm the signature
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            error_log('Bad Signed JSON signature!');
            return null;
        }

        return $data;
    }

    public function base64_url_decode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    public function actionSalesdata()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {
            $startDate = Yii::$app->request->get('start', date('Y-m-d'));
            $endDate = Yii::$app->request->get('end', date('Y-m-d'));

            $session = Yii::$app->session;
            $companyid = $session->get('companyid');

            if (!$companyid) {
                $userId = Yii::$app->user->id;
                $companyid = Yii::$app->db->createCommand("
                    SELECT companyid FROM users WHERE userid = '$userId'
                ")->queryScalar();
            }

            $sql = "
                SELECT 
                    COALESCE(SUM(t.grandtotal), 0) as total_penjualan,
                    COUNT(t.tranid) as total_transaksi
                FROM trans t
                WHERE t.companyid = '$companyid'
                  AND t.status <> 10
                  AND t.trantype LIKE 'sales/%'
                  AND t.trandate BETWEEN '$startDate' AND '$endDate'
            ";

            $result = Yii::$app->db->createCommand($sql)->queryOne();

            return [
                'success' => true,
                'data' => $result
            ];
        } catch (\Exception $e) {
            Yii::error("Salesdata Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function actionTopcustomer()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {
            $startDate = Yii::$app->request->get('start', date('Y-m-d'));
            $endDate = Yii::$app->request->get('end', date('Y-m-d'));

            $session = Yii::$app->session;
            $companyid = $session->get('companyid');

            if (!$companyid) {
                $userId = Yii::$app->user->id;
                $companyid = Yii::$app->db->createCommand("
                    SELECT companyid FROM users WHERE userid = '$userId'
                ")->queryScalar();
            }

            $sql = 
            "SELECT 
                    c.contact_id,
                    c.contact_name,
                    COUNT(t.tranid) as total_transaksi,
                    COALESCE(SUM(t.grandtotal), 0) as total_belanja
                FROM contacts c
                INNER JOIN trans t ON t.contact_id = c.contact_id
                WHERE t.companyid = '$companyid' AND c.contacttype = 'customer'
                AND t.status <> '10' AND t.trantype LIKE 'sales/invoice'
                AND t.trandate BETWEEN '$startDate' AND '$endDate'
                GROUP BY c.contact_id, c.contact_name
                ORDER BY total_transaksi DESC
                LIMIT 5
            ";

            $rows = Yii::$app->db->createCommand($sql)->queryAll();

            return [
                'success' => true,
                'data' => $rows
            ];
        } catch (\Exception $e) {
            Yii::error("Topcustomer Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }

    public function actionTrackview($id = null)
    {
        if (!$id) {
            return $this->renderPartial('tracking/not-found', ['message' => 'ID tidak valid.']);
        }

        $tran = \common\models\Tran::findOne(['tranid' => $id]);

        if (!$tran) {
            return $this->renderPartial('tracking/not-found', ['message' => 'Data tidak ditemukan.']);
        }

        $trackings = $tran->trantracking;

        return $this->renderPartial('tracking/view', [
            'tran' => $tran,
            'trackings' => $trackings,
        ]);
    }

    public function actionTrackviewno($no = null)
    {
        if (!$no) {
            return $this->renderPartial('tracking/not-found', ['message' => 'Nomor transaksi tidak valid.']);
        }

        $tran = \common\models\Tran::findOne(['tranno' => urldecode($no)]);

        if (!$tran) {
            return $this->renderPartial('tracking/not-found', ['message' => 'Data tidak ditemukan.']);
        }

        $trackings = $tran->trantracking;

        return $this->renderPartial('tracking/view', [
            'tran' => $tran,
            'trackings' => $trackings,
        ]);
    }
}
