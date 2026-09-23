<?php

namespace backend\controllers;

use Yii;
use common\models\Pembayaran;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use common\models\Model;
use common\models\User;
use yii\helpers\FileHelper;
use common\models\Facedata;
use common\models\Workerorder;
use yii\filters\AccessControl;
use common\models\Leave;
use PhpOffice\PhpSpreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use JavaClass;
use yii\helpers\Url;
use Java;

class ApiController extends Controller
{

  public $enableCsrfValidation = false;
  /**
   * @inheritdoc
   */
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::className(),
        'rules' => [
          [
            'actions' => [
              'list',
              'login',
              'changepassword',
              'test',
              'getworkorder',
              'getworkorderlast',
              'submitworkorder',
              'testjarak',
              'createfcm',
              'submitkelengkapan',
              'upfoto',
              'clockin',
              'clockout',
              'detail',
              'schedulelast',
              'schedulecount',
              'schedule',
              'scheduledetail',
              'checkdistance',
            ],
            'allow' => true,
            'roles' => ['?', '@'],
          ],
        ],
      ],
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'cancel' => ['post'],
        ],
      ],
    ];
  }

  public function actionLogin()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $headers = Yii::$app->request->headers;
    $auth = $headers->get('Custom-Security');

    if ($auth != "dwansoft123") {
      $out['totalCount'] = 0;
      $out['success'] = false;
      $out['pesan'] = "Access Denied!";
      $out['data'] = array_values([]);

      return $out;
    }

    try {
      $filter = "where 1=1 ";
      $params = Yii::$app->request->post();
      // var_dump(Yii::$app->request->post());exit;

      $username = $params['username'];
      $pass = $params['pass'];

      $query =
        "SELECT 
            a.userid, 
            COALESCE(NULLIF(a.username, ''), a.name) AS display_name, 
            a.password_hash 
        FROM users a 
        LEFT JOIN contacts b ON a.contact_id = b.contact_id 
        WHERE a.status = '1' AND a.positionid IN ('position.pi', 'position.cr', 'position.op', 'position.fl')
        AND (a.username = '$username' OR (a.username IS NULL OR a.username = '') AND a.name = '$username')";
      // echo $query;exit;

      $userarr = Yii::$app->db->createCommand($query)->queryAll();
      $userid = $userarr[0]['userid'];
      $password_hash = $userarr[0]['password_hash'];
      //return $customerid;    

      if ($userid == "") {
        $out['totalCount'] = 0;
        $out['success'] = false;
        $out['pesan'] = "User Tidak Terdaftar!";
        $out['data'] = array_values([]);
        return $out;
      }

      $pass = str_replace("'", "''", $pass);

      // var_dump(Yii::$app->security->validatePassword($pass, $password_hash));exit;
      $hasil = false;
      if (Yii::$app->security->validatePassword($pass, $password_hash)) {
        $hasil = true;
      }

      if (!$hasil) {
        $out['totalCount'] = 0;
        $out['success'] = false;
        $out['pesan'] = "Password Tidak Valid!";
        $out['data'] = array_values([]);
        return $out;
      }

      $filter .= " AND a.status = '1' AND a.userid='" . $userid . "'";

      $sql =
        "SELECT 
            a.userid, COALESCE(a.username, a.name) as employeenama, 
            a.status, b.contact_no as employeekode, 
            b.contact_email1 as email, b.contact_phone1 as phone, 
            b.status_register as statusregister, 
            SPLIT_PART(COALESCE(event_user.locations, ''), ',', 1) AS event_locations,
            COALESCE(TO_CHAR(l.check_in, 'YYYY-MM-DD HH24:MI:SS'), '') AS check_in,
            COALESCE(TO_CHAR(l.check_out, 'YYYY-MM-DD HH24:MI:SS'), '') AS check_out,
            COALESCE(event_user.userid::text, '') AS event_userid,
            COALESCE(event_user.traneventid::text, '') AS traneventid, 
            COALESCE(event_user.event_name, '') AS current_event_name,
            COALESCE(f.face_id, '') AS faceid,
            COALESCE(f.total_face, 0) AS total_face,
            COALESCE(
                TO_CHAR(event_user.startdate, 'YYYY-MM-DD HH24:MI:SS'),
                ''
            ) AS startdate,

            COALESCE(
                TO_CHAR(event_user.enddate, 'YYYY-MM-DD HH24:MI:SS'),
                ''
            ) AS enddate
        FROM users a
        LEFT JOIN contacts b ON a.contact_id = b.contact_id 
      
        LEFT JOIN (
            SELECT 
                u.userid, 
                t.locations, 
                te.traneventid,
                t.eventname as event_name,
                te.startdate,
                l.check_out,
                te.enddate
            FROM traneventcrews tec
            INNER JOIN tranevents te ON tec.traneventid = te.traneventid
            INNER JOIN trans t ON te.tranid = t.tranid
            INNER JOIN users u ON tec.crewid = u.contact_id
            LEFT JOIN leave l ON te.traneventid = l.traneventid 
              AND l.leavedate = CURRENT_DATE AND l.contactid = u.contact_id 
            WHERE t.coordinate IS NOT NULL AND t.coordinate <> '' 
              AND t.status <> '10' AND t.statuspro IN ('5', '10')
              AND te.status <> '10' AND tec.status <> '10'
              AND CURRENT_DATE BETWEEN DATE(te.startdate) AND DATE(te.enddate)
        ) as event_user ON a.userid = event_user.userid

        LEFT JOIN leave l ON l.contactid = b.contact_id 
              AND l.traneventid = event_user.traneventid 
              AND l.leavedate = CURRENT_DATE AND l.status <> '10'

        LEFT JOIN (
            SELECT user_id, 
                   MAX(face_id) as face_id, 
                   COUNT(face_id) as total_face 
            FROM facedata 
            GROUP BY user_id
        ) f ON f.user_id = a.userid::text

        $filter
        ";
      // echo($sql);exit;
      $data = Yii::$app->db->createCommand($sql)->queryAll();

      $out['success'] = true;
      $out['pesan'] = "Login Sukses";
      $out['data'] = array_values($data);
      // $out['query'] = $hasil;//$request->post();				
      return $out;
    } catch (\Exception $exception) {
      $out['totalCount'] = 0;
      $out['success'] = false;
      $out['pesan'] = $exception->getMessage();
      $out['data'] = array_values([]);
      return $out;
    }
  }
  public function actionChangepassword()
  {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $headers = Yii::$app->request->headers;
    $auth = $headers->get('Custom-Security');

    if ($auth != "dwansoft123") {
      $out['totalCount'] = 0;
      $out['success'] = false;
      $out['pesan'] = "Access Denied!";
      $out['data'] = array_values([]);
      return $out;
    }

    try {
      $params = Yii::$app->request->post();
      $userid = isset($params['LoginID']) ? $params['LoginID'] : '';
      $passNew = isset($params['pass']) ? $params['pass'] : '';
      $passOld = isset($params['passold']) ? $params['passold'] : '';

      if (empty($userid)) {
        $out['totalCount'] = 0;
        $out['success'] = false;
        $out['pesan'] = "User ID tidak valid!";
        $out['data'] = array_values([]);
        return $out;
      }

      $queryUser = "SELECT userid, password_hash FROM users WHERE userid = '$userid' AND status = '1'";
      $user = Yii::$app->db->createCommand($queryUser)->queryOne();

      if (!$user) {
        $out['totalCount'] = 0;
        $out['success'] = false;
        $out['pesan'] = "User Tidak Terdaftar atau Nonaktif!";
        $out['data'] = array_values([]);
        return $out;
      }

      if (!Yii::$app->security->validatePassword($passOld, $user['password_hash'])) {
        $out['totalCount'] = 0;
        $out['success'] = false;
        $out['pesan'] = "Password Sebelumnya Salah!";
        $out['data'] = array_values([]);
        return $out;
      }

      $newPasswordHash = Yii::$app->security->generatePasswordHash($passNew);

      $sqlUpdate = "UPDATE users SET password_hash = '$newPasswordHash' WHERE userid = '$userid'";
      Yii::$app->db->createCommand($sqlUpdate)->execute();

      $sqlProfile =
        "SELECT 
                a.userid, COALESCE(a.username, a.name) as employeenama, 
                a.status, b.contact_no as employeekode, 
                b.contact_email1 as email, b.contact_phone1 as phone, 
                b.status_register as statusregister
            FROM users a
            LEFT JOIN contacts b ON a.contact_id = b.contact_id 
            WHERE a.userid = '$userid' AND a.status = '1'
        ";
      $data = Yii::$app->db->createCommand($sqlProfile)->queryAll();

      $out['success'] = true;
      $out['pesan'] = "Password Berhasil Diganti";
      $out['data'] = array_values($data);
      return $out;

    } catch (\Exception $exception) {
      $out['totalCount'] = 0;
      $out['success'] = false;
      $out['pesan'] = "Terjadi kesalahan backend: " . $exception->getMessage();
      $out['data'] = array_values([]);
      return $out;
    }
  }

  public function actionClockin()
  {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $headers = Yii::$app->request->headers;
    $auth = $headers->get('Custom-Security');

    if ($auth != "dwansoft123") {
      $out['totalCount'] = 0;
      $out['success'] = false;
      $out['pesan'] = "Access Denied!";
      $out['data'] = array_values([]);
      return $out;
    }

    try {
      $params = Yii::$app->request->post();
      $userId = $params['userid'] ?? '';
      $latitude = Yii::$app->request->post('latitude');
      $longitude = Yii::$app->request->post('longitude');
      $traneventidParam = Yii::$app->request->post('traneventid') ?? '';

      if (empty($userId)) {
        $out['totalCount'] = 0;
        $out['success'] = false;
        $out['pesan'] = "UserId is required!";
        $out['data'] = array_values([]);
        return $out;
      }

      if ($latitude == null || $latitude == "0") {
        $out['totalCount'] = 0;
        $out['success'] = false;
        $out['pesan'] = "Lokasi Anda Tidak Terbaca! Pastikan GPS Aktif.";
        $out['data'] = array_values([]);
        return $out;
      }

      $sqlCoordinate =
        "SELECT t.coordinate
      FROM trans t
      INNER JOIN tranevents te ON t.tranid = te.tranid
      INNER JOIN traneventcrews tec ON te.traneventid = tec.traneventid
      INNER JOIN contacts c ON tec.crewid = c.contact_id
      WHERE c.contact_id = (SELECT contact_id FROM users WHERE userid = '$userId' AND status <> '10' LIMIT 1)
      AND t.coordinate IS NOT NULL AND TRIM(t.coordinate) != ''
      AND t.status <> '10' AND t.statuspro IN ('5', '10')";

      if (!empty($traneventidParam)) {
        $sqlCoordinate .= " AND te.traneventid = '$traneventidParam' ";
      }

      $sqlCoordinate .= " ORDER BY te.startdate ASC LIMIT 1";

      $coordinate = Yii::$app->db->createCommand($sqlCoordinate)->queryScalar();

      if (empty($coordinate)) {
        $out['totalCount'] = 0;
        $out['success'] = false;
        $out['pesan'] = "Koordinat lokasi penugasan tidak ditemukan untuk user ini!";
        $out['data'] = array_values([]);
        return $out;
      }

      $coordinate = trim($coordinate);
      $coordinateArray = preg_split('/[\s,]+/', $coordinate);
      $latitudeto = isset($coordinateArray[0]) ? trim($coordinateArray[0]) : null;
      $longitudeto = isset($coordinateArray[1]) ? trim($coordinateArray[1]) : null;

      $distance = Yii::$app->function->getJarak($latitude, $longitude, $latitudeto, $longitudeto);
      // echo $latitude . " - " .$longitude. " - " . $latitudeto . " - " . $longitudeto . " - " . $distance; exit;

      if ($distance > 500) {
        $out['totalCount'] = 0;
        $out['success'] = false;
        $out['pesan'] = "Anda Diluar Jangkauan! (Jarak Anda: " . round($distance) . " meter)";
        $out['data'] = array_values([]);
        return $out;
      }

      $uploadedFile = \yii\web\UploadedFile::getInstanceByName('image');
      if (!$uploadedFile) {
        $out['totalCount'] = 0;
        $out['success'] = false;
        $out['pesan'] = "Image file is required for clock-in!";
        $out['data'] = array_values([]);
        return $out;
      }

      $userData =
        "SELECT 
          u.userid, u.contact_id,
          c.contact_no
        FROM users u
        LEFT JOIN contacts c ON u.contact_id = c.contact_id
        WHERE u.userid = '$userId' AND u.status = '1'
        LIMIT 1";
      $user = Yii::$app->db->createCommand($userData)->queryOne();

      if (!$user || empty($user['contact_id'])) {
        $out['totalCount'] = 0;
        $out['success'] = false;
        $out['pesan'] = "User not found!";
        $out['data'] = array_values([]);
        return $out;
      }

      $contactId = $user['contact_id'];
      $employeeCode = $user['contact_no'];

      $tz = new \DateTimeZone('Asia/Jakarta');
      $now = new \DateTime('now', $tz);
      $currentDate = $now->format('Y-m-d');
      $currentTime = $now->format('Y-m-d H:i:s');
      $userIp = Yii::$app->request->userIP ?? '0.0.0.0';

      $hadirEnumId =
        "SELECT enumid
        FROM enum
        WHERE enumtype ='absenttype'
        AND LOWER(enumtext_id) = 'hadir'
        LIMIT 1";
      $hadirEnumIdResult = Yii::$app->db->createCommand($hadirEnumId)->queryScalar();

      if (!$hadirEnumIdResult) {
        $out['totalCount'] = 0;
        $out['success'] = false;
        $out['pesan'] = "Absent type 'Hadir' not found!";
        $out['data'] = array_values([]);
        return $out;
      }

      $lastNo =
        "SELECT COALESCE(
            MAX(CAST(REGEXP_REPLACE(leaveno, '[^0-9]', '', 'g') AS INTEGER)),
            0
        ) AS max_no
        FROM leave
        WHERE leaveno LIKE 'AT-%'
        AND status != '10'";
      $leaveNoResult = Yii::$app->db->createCommand($lastNo)->queryScalar();
      $leaveno = 'AT-' . str_pad((int) $leaveNoResult + 1, 4, '0', STR_PAD_LEFT);

      $uploadDir = Yii::getAlias('@webroot/uploads/leave/clockin/');
      if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
      }

      $fileName = 'AT_' . $userId . '_' . time() . '.' . $uploadedFile->extension;
      $filePath = $uploadDir . $fileName;

      $traneventid =
        "SELECT te.traneventid, te.tranid
      FROM traneventcrews tec
      INNER JOIN tranevents te ON tec.traneventid = te.traneventid
      INNER JOIN trans t ON te.tranid = t.tranid
      WHERE tec.crewid = '$contactId'
        AND t.coordinate IS NOT NULL AND t.coordinate <> ''
        AND t.status <> '10' AND t.statuspro IN ('5', '10')
        AND te.status <> '10' AND tec.status <> '10'
        ORDER BY ABS(EXTRACT(EPOCH FROM (te.startdate - NOW()))) ASC
      LIMIT 1
      ";

      $traneventidResult = Yii::$app->db->createCommand($traneventid)->queryOne();

      $transaction = Yii::$app->db->beginTransaction();

      $leave = new Leave();
      $leave->leaveid = Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();
      $leave->contactid = $contactId;
      $leave->leaveno = $leaveno;
      $leave->leavetype = $hadirEnumIdResult;
      $leave->leavedate = $currentDate;
      $leave->leaveduedate = $currentDate;
      $leave->check_in = $currentTime;
      $leave->check_out = null;
      $leave->status = '1';
      $leave->employee_code = $employeeCode;
      $leave->createdat = $currentTime;
      $leave->createdby = $userId;
      $leave->creatdip = $userIp;
      $leave->pict_employee = $fileName;
      $leave->latitude = $latitude;
      $leave->longitude = $longitude;
      $leave->traneventid = $traneventidResult ? $traneventidResult['traneventid'] : null;
      $leave->refid = $traneventidResult ? $traneventidResult['tranid'] : null;
      $leave->autoCalculateDurations();

      if ($leave->save()) {
        if ($uploadedFile->saveAs($filePath)) {
          $transaction->commit();

          $out['totalCount'] = 1;
          $out['success'] = true;
          $out['pesan'] = "Absensi Clock-In berhasil dicatat!";
          $out['data'] = [$leave->attributes];
          return $out;
        } else {
          $transaction->rollBack();
          $out['totalCount'] = 0;
          $out['success'] = false;
          $out['pesan'] = "Gagal mengunggah gambar berkas ke server penyimpanan.";
          $out['data'] = array_values([]);
          return $out;
        }
      } else {
        $transaction->rollBack();
        $errors = $leave->getErrors();
        $out['totalCount'] = 0;
        $out['success'] = false;
        $out['pesan'] = "Gagal validasi data absensi.";
        $out['data'] = $errors;
        return $out;
      }

    } catch (\Exception $e) {
      if (isset($transaction) && $transaction->isActive) {
        $transaction->rollBack();
      }
      $out['totalCount'] = 0;
      $out['success'] = false;
      $out['pesan'] = "Internal Server Error: " . $e->getMessage();
      $out['data'] = array_values([]);
      return $out;
    }
  }

  public function actionClockout()
  {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $headers = Yii::$app->request->headers;
    $auth = $headers->get('Custom-Security');

    if ($auth != "dwansoft123") {
      $out['totalCount'] = 0;
      $out['success'] = false;
      $out['pesan'] = "Access Denied!";
      $out['data'] = array_values([]);

      return $out;
    }

    try {
      $params = Yii::$app->request->post();
      $userId = $params['userid'] ?? '';
      $latitude = Yii::$app->request->post('latitude');
      $longitude = Yii::$app->request->post('longitude');
      $traneventidParam = Yii::$app->request->post('traneventid') ?? '';

      if (empty($userId)) {
        $out['totalCount'] = 0;
        $out['success'] = false;
        $out['pesan'] = "UserId is required!";
        $out['data'] = array_values([]);
        return $out;
      }

      if ($latitude == null || $latitude == "0") {
        $out['totalCount'] = 0;
        $out['success'] = false;
        $out['pesan'] = "Lokasi Tidak Terbaca! Pastikan GPS Aktif.";
        $out['data'] = array_values([]);
        return $out;
      }

      $sqlCoordinate =
        "SELECT t.coordinate
      FROM trans t
      INNER JOIN tranevents te ON t.tranid = te.tranid
      INNER JOIN traneventcrews tec ON te.traneventid = tec.traneventid
      INNER JOIN contacts c ON tec.crewid = c.contact_id
      WHERE c.contact_id = (SELECT contact_id FROM users WHERE userid = '$userId' AND status = '1' LIMIT 1)
      AND t.coordinate IS NOT NULL AND TRIM(t.coordinate) != ''
      AND t.status <> '10' AND t.statuspro IN ('5', '10')";

      if (!empty($traneventidParam)) {
        $sqlCoordinate .= " AND te.traneventid = '$traneventidParam' ";
      }

      $sqlCoordinate .= " ORDER BY te.startdate ASC LIMIT 1";

      $coordinate = Yii::$app->db->createCommand($sqlCoordinate)->queryScalar();

      if (empty($coordinate)) {
        $out['totalCount'] = 0;
        $out['success'] = false;
        $out['pesan'] = "Koordinat lokasi penugasan tidak ditemukan untuk user ini!";
        $out['data'] = array_values([]);
        return $out;
      }

      $coordinate = trim($coordinate);
      $coordinateArray = preg_split('/[\s,]+/', $coordinate);
      $latitudeto = isset($coordinateArray[0]) ? trim($coordinateArray[0]) : null;
      $longitudeto = isset($coordinateArray[1]) ? trim($coordinateArray[1]) : null;

      $distance = Yii::$app->function->getJarak($latitude, $longitude, $latitudeto, $longitudeto);
      // echo $latitude . " - " .$longitude. " - " . $latitudeto . " - " . $longitudeto . " - " . $distance; exit;

      if ($distance > 500) {
        $out['totalCount'] = 0;
        $out['success'] = false;
        $out['pesan'] = "Anda Diluar Jangkauan! (Jarak Anda: " . round($distance) . " meter)";
        $out['data'] = array_values([]);
        return $out;
      }

      $uploadedFile = \yii\web\UploadedFile::getInstanceByName('image');
      if (!$uploadedFile) {
        $out['totalCount'] = 0;
        $out['success'] = false;
        $out['pesan'] = "Image file is required for clock-in!";
        $out['data'] = array_values([]);
        return $out;
      }

      $userData =
        "SELECT 
          u.userid, u.contact_id,
          c.contact_no
        FROM users u
        LEFT JOIN contacts c ON u.contact_id = c.contact_id
        WHERE u.userid = '$userId' AND u.status = '1'
        LIMIT 1";
      $user = Yii::$app->db->createCommand($userData)->queryOne();

      if (!$user || empty($user['contact_id'])) {
        $out['totalCount'] = 0;
        $out['success'] = false;
        $out['pesan'] = "User not found!";
        $out['data'] = array_values([]);
        return $out;
      }

      $contactId = $user['contact_id'];

      $tz = new \DateTimeZone('Asia/Jakarta');
      $now = new \DateTime('now', $tz);
      $currentDate = $now->format('Y-m-d');
      $currentTime = $now->format('Y-m-d H:i:s');
      $userIp = Yii::$app->request->userIP ?? '0.0.0.0';

      $leaveid =
        "SELECT leaveid
      FROM leave
      WHERE contactid = '$contactId' AND leavedate = '$currentDate' AND status = '1'
      AND check_in IS NOT NULL AND check_out IS NULL
      AND status <> '10'
      LIMIT 1";

      $leaveIdResult = Yii::$app->db->createCommand($leaveid)->queryScalar();

      if (!$leaveIdResult) {
        $out['totalCount'] = 0;
        $out['success'] = false;
        $out['pesan'] = "Active clock-in record not found for today!";
        $out['data'] = array_values([]);
        return $out;
      }

      $leave = Leave::findOne($leaveIdResult);

      if (!$leave) {
        $out['totalCount'] = 0;
        $out['success'] = false;
        $out['pesan'] = "Leave record not found!";
        $out['data'] = array_values([]);
        return $out;
      }

      $uploadDir = Yii::getAlias('@webroot/uploads/leave/clockout/');
      if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
      }

      $fileName = 'AT_' . $userId . '_' . time() . '.' . $uploadedFile->extension;
      $filePath = $uploadDir . $fileName;
      $transaction = Yii::$app->db->beginTransaction();

      $leave->check_out = $currentTime;
      $leave->updatedat = $currentTime;
      $leave->updatedby = $userId;
      $leave->updatedip = $userIp;
      $leave->pict_employee2 = $fileName;
      $leave->latitude2 = $latitude;
      $leave->longitude2 = $longitude;

      if ($leave->save()) {
        if ($uploadedFile->saveAs($filePath)) {
          $transaction->commit();

          $out['totalCount'] = 1;
          $out['success'] = true;
          $out['pesan'] = "Absensi Clock-Out berhasil dicatat!";
          $out['data'] = [$leave->attributes];
          return $out;
        } else {
          $transaction->rollBack();
          $out['totalCount'] = 0;
          $out['success'] = false;
          $out['pesan'] = "Gagal mengunggah gambar berkas ke server penyimpanan.";
          $out['data'] = array_values([]);
          return $out;
        }
      } else {
        $transaction->rollBack();
        $errors = $leave->getErrors();
        $out['totalCount'] = 0;
        $out['success'] = false;
        $out['pesan'] = "Gagal validasi data absensi.";
        $out['data'] = $errors;
        return $out;
      }

    } catch (\Exception $e) {
      if (isset($transaction) && $transaction->isActive) {
        $transaction->rollBack();
      }
      $out['success'] = false;
      $out['pesan'] = "Error: " . $e->getMessage();
      $out['data'] = array_values([]);
      return $out;
    }
  }

  public function actionCheckdistance()
  {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $headers = Yii::$app->request->headers;
    $auth = $headers->get('Custom-Security');

    if ($auth != "dwansoft123") {
      return ['success' => false, 'pesan' => 'Access Denied!'];
    }

    $userId = Yii::$app->request->post('userid') ?? '';
    $latitude = Yii::$app->request->post('latitude');
    $longitude = Yii::$app->request->post('longitude');
    $traneventidParam = Yii::$app->request->post('traneventid') ?? '';

    if (empty($userId) || !$latitude || !$longitude) {
      return ['success' => false, 'pesan' => 'Parameter tidak lengkap.'];
    }

    $sqlCoordinate = 
    "SELECT t.coordinate FROM trans t
      INNER JOIN tranevents te ON t.tranid = te.tranid
      INNER JOIN traneventcrews tec ON te.traneventid = tec.traneventid
      INNER JOIN contacts c ON tec.crewid = c.contact_id
      WHERE c.contact_id = (SELECT contact_id FROM users WHERE userid = '$userId' AND status <> '10' LIMIT 1)
      AND t.coordinate IS NOT NULL AND TRIM(t.coordinate) != ''
      AND t.status <> '10' AND t.statuspro IN ('5', '10')";

    if (!empty($traneventidParam)) {
      $sqlCoordinate .= " AND te.traneventid = '$traneventidParam' ";
    }
    $sqlCoordinate .= " ORDER BY te.startdate ASC LIMIT 1";

    $coordinate = Yii::$app->db->createCommand($sqlCoordinate)->queryScalar();

    if (empty($coordinate)) {
      return ['success' => false, 'pesan' => 'Koordinat lokasi penugasan tidak ditemukan!'];
    }

    $coordinateArray = preg_split('/[\s,]+/', trim($coordinate));
    $latitudeto = $coordinateArray[0] ?? null;
    $longitudeto = $coordinateArray[1] ?? null;

    $distance = Yii::$app->function->getJarak($latitude, $longitude, $latitudeto, $longitudeto);

    if ($distance > 500) {
      return [
        'success' => false,
        'isInRange' => false,
        'pesan' => "Anda Diluar Jangkauan! (Jarak Anda: " . round($distance) . " meter)"
      ];
    }

    return ['success' => true, 'isInRange' => true, 'pesan' => 'Jarak sesuai.'];
  }
  public function actionList()
  {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    try {
      $headers = Yii::$app->request->headers;
      $auth = $headers->get('Custom-Security');

      if ($auth != "dwansoft123") {
        $out['totalCount'] = 0;
        $out['success'] = false;
        $out['pesan'] = "Access Denied!";
        $out['data'] = array_values([]);
        return $out;
      }

      $params = Yii::$app->request->post();
      $userId = $params['userid'] ?? null;

      if (empty($userId)) {
        return [
          'totalCount' => 0,
          'success' => false,
          'pesan' => 'User ID tidak boleh kosong.',
          'data' => [],
        ];
      }

      $sql =
        "SELECT
            l.leaveid,
            l.leaveno,
            l.check_in, 
            l.check_out, 
            l.leavedate,
            l.late_duration,
            l.overtime_duration
          FROM leave l
          LEFT JOIN users u ON l.contactid = u.contact_id
          LEFT JOIN enum e ON l.leavetype = e.enumid
          
          WHERE u.userid = '$userId' AND l.status <> '10'
          AND e.enumtype = 'absenttype' AND LOWER(enumtext_id) = 'hadir'
          ORDER BY l.leavedate ASC
      ";
      // echo $sql;die; 

      $sqlcount = "SELECT COUNT(*) 
             FROM leave l
             LEFT JOIN users u ON l.contactid = u.contact_id
             LEFT JOIN enum e ON l.leavetype = e.enumid
             WHERE u.userid = '$userId' AND l.status <> '10'    
             AND e.enumtype = 'absenttype' AND LOWER(enumtext_id) = 'hadir'";

      $count = Yii::$app->db->createCommand($sqlcount)->queryScalar();
      $data = Yii::$app->db->createCommand($sql)->queryAll();

      $out['totalCount'] = (int) $count;
      $out['success'] = true;
      $out['pesan'] = "Berhasil mengambil data leave";
      $out['data'] = array_values($data);

      return $out;

    } catch (\Exception $exception) {
      $out['totalCount'] = 0;
      $out['success'] = false;
      $out['pesan'] = "Error: " . $exception->getMessage();
      $out['data'] = array_values([]);
      return $out;
    }
  }

  public function actionDetail()
  {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $headers = Yii::$app->request->headers;
    $auth = $headers->get('Custom-Security');

    if ($auth != "dwansoft123") {
      return [
        'totalCount' => 0,
        'success' => false,
        'pesan' => 'Access Denied!',
        'data' => [],
      ];
    }

    try {
      $params = Yii::$app->request->post();
      $leaveid = $params['leaveid'] ?? '';
      $type = strtolower($params['type'] ?? 'masuk');

      if (empty($leaveid)) {
        return [
          'totalCount' => 0,
          'success' => false,
          'pesan' => 'Leave ID tidak boleh kosong.',
          'data' => [],
        ];
      }

      if (empty($type)) {
        return ['success' => false, 'pesan' => 'type kosong, value: ' . var_export($params, true)];
      }

      $sql =
        "SELECT
            l.leaveid,
            l.leaveno,
            l.check_in,
            l.check_out,
            l.leavedate,
            l.late_duration,
            l.overtime_duration,
            l.pict_employee,
            l.pict_employee2,
            te.eventtypeid,
            t.eventname,
            t.locations,
            c.contact_name AS customer,
            l.latitude2,
            l.longitude2,
            l.latitude,
            l.longitude
        FROM leave l
        LEFT JOIN traneventcrews tec ON l.traneventid = tec.traneventid AND l.contactid = tec.crewid
        LEFT JOIN tranevents te ON tec.traneventid = te.traneventid
        LEFT JOIN trans t ON t.tranid = te.tranid
        LEFT JOIN contacts c ON t.contact_id = c.contact_id
        WHERE l.leaveid = '$leaveid'
        AND l.status <> '10'
        LIMIT 1";

      $data = Yii::$app->db->createCommand($sql)->queryOne();

      if (!$data) {
        return [
          'totalCount' => 0,
          'success' => false,
          'pesan' => 'Data tidak ditemukan.',
          'data' => [],
        ];
      }

      if ($type === 'keluar') {
        $data['pict_url'] = !empty($data['pict_employee2'])
          ? Yii::$app->request->hostInfo . '/uploads/leave/clockout/' . $data['pict_employee2']
          : null;
        $data['active_latitude'] = $data['latitude2'];
        $data['active_longitude'] = $data['longitude2'];
      } else {
        $data['pict_url'] = !empty($data['pict_employee'])
          ? Yii::$app->request->hostInfo . '/uploads/leave/clockin/' . $data['pict_employee']
          : null;
        $data['active_latitude'] = $data['latitude'];
        $data['active_longitude'] = $data['longitude'];
      }
      return [
        'totalCount' => 1,
        'success' => true,
        'pesan' => 'Berhasil mengambil detail absensi.',
        'data' => [$data],
      ];

    } catch (\Exception $e) {
      return [
        'totalCount' => 0,
        'success' => false,
        'pesan' => 'Error: ' . $e->getMessage(),
        'data' => [],
      ];
    }
  }
  public function actionSchedulelast()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $headers = Yii::$app->request->headers;
    $auth = $headers->get('Custom-Security');

    if ($auth != "dwansoft123") {
      $out['totalCount'] = 0;
      $out['success'] = false;
      $out['pesan'] = "Access Denied!";
      $out['data'] = array_values([]);

      return $out;
    }

    try {
      $params = Yii::$app->request->post();
      $userId = $params['userid'] ?? null;

      if (empty($userId)) {
        return [
          'totalCount' => 0,
          'success' => false,
          'pesan' => 'User ID tidak boleh kosong.',
          'data' => [],
        ];
      }

      $sql =
        "SELECT
            te.traneventid,
            t.tranid,
            te.startdate,
            te.enddate,
            t.coordinate,
            t.locations,
            t.eventname,
            te.eventtypeid as eventtype
        FROM traneventcrews tec
        INNER JOIN tranevents te ON tec.traneventid = te.traneventid
        INNER JOIN trans t ON te.tranid = t.tranid
        INNER JOIN users u ON tec.crewid = u.contact_id
        INNER JOIN contacts c ON t.contact_id = c.contact_id
        WHERE u.userid = '$userId' AND u.status = '1'
          AND t.coordinate IS NOT NULL AND t.coordinate <> ''
          AND t.status <> '10' AND t.statuspro IN ('5', '10')
          AND te.status <> '10' AND te.startdate >= CURRENT_DATE
        ORDER BY te.startdate DESC
        LIMIT 1
      ";

      $data = Yii::$app->db->createCommand($sql)->queryAll();
      // echo $sql;die;

      $out['totalCount'] = count($data);
      $out['success'] = true;
      $out['pesan'] = "Berhasil mengambil data jadwal";
      $out['data'] = array_values($data);
      return $out;

    } catch (\Exception $exception) {
      $out['totalCount'] = 0;
      $out['success'] = false;
      $out['pesan'] = "Error: " . $exception->getMessage();
      $out['data'] = array_values([]);
      return $out;
    }

  }
  public function actionSchedulecount()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $headers = Yii::$app->request->headers;
    $auth = $headers->get('Custom-Security');

    if ($auth != "dwansoft123") {
      $out['totalCount'] = 0;
      $out['success'] = false;
      $out['pesan'] = "Access Denied!";
      $out['data'] = array_values([]);

      return $out;
    }

    try {
      $params = Yii::$app->request->post();
      $userId = $params['userid'] ?? null;

      if (empty($userId)) {
        return [
          'totalCount' => 0,
          'success' => false,
          'pesan' => 'User ID tidak boleh kosong.',
          'data' => [],
        ];
      }

      $sql =
        "SELECT
            COUNT(t.tranid) FILTER (WHERE t.statuspro >= '1' AND t.status <> '10') as total,
            COUNT(t.tranid) FILTER (WHERE t.statuspro IN ('5', '10') AND t.status <> '10') as total_onprogress,
            COUNT(t.tranid) FILTER (WHERE t.statuspro = '15' AND t.status <> '10') as total_completed

        FROM traneventcrews tec
        INNER JOIN tranevents te ON tec.traneventid = te.traneventid
        INNER JOIN trans t ON te.tranid = t.tranid
        INNER JOIN users u ON tec.crewid = u.contact_id
        INNER JOIN contacts c ON t.contact_id = c.contact_id
        WHERE u.userid = '$userId' AND u.status = '1'
          AND t.coordinate IS NOT NULL AND t.coordinate <> ''
          AND te.status <> '10' AND te.startdate >= CURRENT_DATE
        GROUP BY u.userid
      ";

      $data = Yii::$app->db->createCommand($sql)->queryAll();
      // echo $sql;die;

      $out['totalCount'] = count($data);
      $out['success'] = true;
      $out['pesan'] = "Berhasil mengambil data jadwal";
      $out['data'] = array_values($data);
      return $out;

    } catch (\Exception $exception) {
      $out['totalCount'] = 0;
      $out['success'] = false;
      $out['pesan'] = "Error: " . $exception->getMessage();
      $out['data'] = array_values([]);
      return $out;
    }

  }
  public function actionSchedule()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $headers = Yii::$app->request->headers;
    $auth = $headers->get('Custom-Security');

    if ($auth != "dwansoft123") {
      $out['totalCount'] = 0;
      $out['success'] = false;
      $out['pesan'] = "Access Denied!";
      $out['data'] = array_values([]);

      return $out;
    }

    try {
      $params = Yii::$app->request->post();
      $userId = $params['userid'] ?? null;

      if (empty($userId)) {
        return [
          'totalCount' => 0,
          'success' => false,
          'pesan' => 'User ID tidak boleh kosong.',
          'data' => [],
        ];
      }

      $sql =
        "SELECT
            te.traneventid,
            t.tranid,
            te.startdate,
            te.enddate,
            t.coordinate,
            t.locations,
            t.eventname,
            c.contact_name as customer,
            te.eventtypeid as eventtype
        FROM traneventcrews tec
        INNER JOIN tranevents te ON tec.traneventid = te.traneventid
        INNER JOIN trans t ON te.tranid = t.tranid
        INNER JOIN users u ON tec.crewid = u.contact_id
        INNER JOIN contacts c ON t.contact_id = c.contact_id
        WHERE u.userid = '$userId' AND u.status = '1'
          AND t.coordinate IS NOT NULL AND t.coordinate <> ''
          AND t.status <> '10' AND t.statuspro IN ('5', '10')
          AND te.status <> '10' AND te.startdate >= CURRENT_DATE
        ORDER BY te.startdate ASC
      ";

      $data = Yii::$app->db->createCommand($sql)->queryAll();
      // echo $sql;die;

      $out['totalCount'] = count($data);
      $out['success'] = true;
      $out['pesan'] = "Berhasil mengambil data jadwal";
      $out['data'] = array_values($data);
      return $out;

    } catch (\Exception $exception) {
      $out['totalCount'] = 0;
      $out['success'] = false;
      $out['pesan'] = "Error: " . $exception->getMessage();
      $out['data'] = array_values([]);
      return $out;
    }

  }
  public function actionScheduledetail()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $headers = Yii::$app->request->headers;
    $auth = $headers->get('Custom-Security');

    if ($auth != "dwansoft123") {
      $out['totalCount'] = 0;
      $out['success'] = false;
      $out['pesan'] = "Access Denied!";
      $out['data'] = array_values([]);

      return $out;
    }

    try {
      $params = Yii::$app->request->post();
      $userId = $params['userid'] ?? null;
      $traneventid = $params['traneventid'] ?? null;

      if (empty($userId)) {
        return [
          'totalCount' => 0,
          'success' => false,
          'pesan' => 'User ID tidak boleh kosong.',
          'data' => [],
        ];
      }

      $extraFilter = '';
      if (!empty($traneventid)) {
        $traneventid = addslashes($traneventid);
        $extraFilter = " AND te.traneventid = '$traneventid'";
      }

      $sql =
        "SELECT
            te.traneventid,
            t.tranid,
            t.tranno,
            te.startdate,
            te.enddate,
            t.coordinate,
            t.locations,
            t.eventname,
            t.trandate,
            t.tranduedate,
            c.contact_name as customer,
            te.eventtypeid as eventtype,
            e.enumtext_id as jobname,
            tec.crewtypeid as positionid,
            t.note
        FROM traneventcrews tec
        INNER JOIN tranevents te ON tec.traneventid = te.traneventid
        INNER JOIN trans t ON te.tranid = t.tranid
        INNER JOIN users u ON tec.crewid = u.contact_id
        INNER JOIN contacts c ON t.contact_id = c.contact_id
        LEFT JOIN enum e ON tec.jobid = e.enumid AND e.enumtype = 'job'
        WHERE u.userid = '$userId' AND u.status = '1'
          AND t.coordinate IS NOT NULL AND t.coordinate <> ''
          AND t.status <> '10' AND t.statuspro IN ('5', '10')
          AND te.status <> '10' AND te.startdate >= CURRENT_DATE
          $extraFilter
        ORDER BY te.startdate ASC
      ";

      $data = Yii::$app->db->createCommand($sql)->queryAll();
      // echo $sql;die;

      $out['totalCount'] = count($data);
      $out['success'] = true;
      $out['pesan'] = "Berhasil mengambil data jadwal";
      $out['data'] = array_values($data);
      return $out;

    } catch (\Exception $exception) {
      $out['totalCount'] = 0;
      $out['success'] = false;
      $out['pesan'] = "Error: " . $exception->getMessage();
      $out['data'] = array_values([]);
      return $out;
    }

  }
  public function actionTestjarak()
  {
    $latitudeFrom = 1.1320939;
    $longitudeFrom = 104.093412;
    $latitudeTo = 1.06183;
    $longitudeTo = 103.943397;

    $res = Yii::$app->function->getJarak($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo);
    // var_dump($res);
    // exit;
    return $res;
  }

}
