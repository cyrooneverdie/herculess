<?php

namespace backend\controllers;

use Yii;
use DateTime;
use common\models\Tran;
use common\models\Leave;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\Trandetail;
use common\models\Tranevent;
use common\models\Variant;
use common\models\Traneventcrew;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use common\models\Model;
use Exception;
use yii\helpers\ArrayHelper;
use common\models\Product;
use common\models\Tranvariants;
use common\models\Trantracking;
use common\models\Contact;


class TranController extends Controller
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
        return false;
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
                    'deletereturn' => ['POST']
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'request',
                            'requestpd',
                            'create',
                            'update',
                            'updatepo',
                            'detail',
                            'delete',
                            'list',
                            'getno',
                            'contactlist',
                            'varianlist',
                            'reflist',
                            'updatestatus',
                            'updatestatuspaid',
                            'updatestatuspro',
                            'updatestatusclient',
                            'massaction',
                            'print',
                            'duplicate',
                            'updatepro',
                            'crew',
                            'loadref',
                            'createdeli',
                            'updatedeli',
                            'select',
                            'createtrack',
                            'listtrack',
                            'deletetrack',
                            'tranvariant',
                            'listtranvariant',
                            'listdetail',
                            'listbarcode',
                            'searchproductbybarcode',
                            'searchbarcodein',
                            'getwhatsappnumber',
                            'getpo',
                            'loadstockin',
                            'loadreturn',
                            'loadscan',
                            'createitem',
                            'createreturnstock',
                            'createvariants',
                            'cekscan',
                            'cekbarcode',
                            'cekreturn'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return true;
                        }
                    ],
                    [
                        'actions' => ['request'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return Yii::$app->enum->isakses("transaksi", "lihat");
                        }
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return Yii::$app->enum->isakses("transaksi", "tambah");
                        }
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return Yii::$app->enum->isakses("transaksi", "ubah");
                        }
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return Yii::$app->enum->isakses("transaksi", "hapus");
                        }
                    ],
                    [
                        'actions' => [
                            'request',
                            'requestpd',
                            'create',
                            'createpro',
                            'createpo',
                            'update',
                            'updatepo',
                            'updatepro',
                            'detail',
                            'list',
                            'contactlist',
                            'varianlist',
                            'reflist',
                            'loadref',
                            'getno',
                            'getpo',
                            'updatestatus',
                            'updatestatuspaid',
                            'updatestatuspro',
                            'massaction',
                            'print',
                            'duplicate',
                            'savetemplate',
                            'settemplate',
                            'resettemplate',
                            'trackdelete',
                            'track',
                            'trackcreate',
                            'deletereturn',
                            'createitem',
                            'createreturnstock',
                            'previewcode'
                        ],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Tran models.
     * @return mixed
     */
    public function actionRequest()
    {
        $pathInfo = Yii::$app->request->getPathInfo();
        $parts = explode('/', trim($pathInfo, '/'));

        $module = $parts[0] ?? 'purchase';
        $type = $parts[1] ?? 'request';

        if (Yii::$app->request->get('module')) {
            $module = Yii::$app->request->get('module');
        }

        $type_id = null;
        if (Yii::$app->request->get('type')) {
            $type = Yii::$app->request->get('type');
            $type_id = Yii::$app->request->get('type');
        }

        $searchModel = new Tran();
        $lastTran = Tran::find()->where(['status' => 1])->orderBy(['trandate' => SORT_DESC])->one();

        if ($lastTran === null) {
            $lastTran = new Tran();
        }

        $trantype = addslashes("$module/$type");
        $transactions = Yii::$app->db->createCommand(
            "SELECT
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
        WHERE t.trantype = '$trantype' 
        ORDER BY t.trandate DESC"
        )->queryAll();

        $title = ucfirst($module) . " " . ucfirst($type);
        $numbercode = Yii::$app->db->createCommand("SELECT * FROM numbertemplates where type = '$type_id'")->queryAll();

        $render = "index";

        $key = "$module/$type";
        if (in_array($key, ["sales/quote", "sales/order"])) {
            $render = "indexpro";
        } else if ($key == "sales/delivery") {
            $render = "indexdeli";
        } else if (in_array($key, ["purchase/order", "purchase/request", "purchase/return"])) {
            $render = "indexord";
        } else if ($key == "sales/return") {
            $render = "indexreturn";
        } else if ($key == "purchase/delivery") {
            $render = "indexin";
        } else if ($key == "invoice/calendar") {
            $render = "invoicecal";
        }

        $previewcode = Tran::nextNoTransaksi();

        return $this->render($render, [
            'model' => $lastTran,
            'transactions' => $transactions,
            'searchModel' => $searchModel,
            'module' => $module,
            'type' => $type,
            'title' => $title,
            'type_id' => $type_id,
            'numbercode' => $numbercode,
            'previewcode' => $previewcode
        ]);
    }
    public function actionRequestpd()
    {
        $url = Yii::$app->request->url;
        $module = 'purchase';
        $type = 'request';

        if (preg_match('#/([^/]+)/([^/\?]+)#', $url, $matches)) {
            $module = $matches[1];
            $type = $matches[2];
        }

        if (Yii::$app->request->get('module')) {
            $module = Yii::$app->request->get('module');
        }

        $type_id = null;
        if (Yii::$app->request->get('type')) {
            $type_id = Yii::$app->request->get('type');
        }

        $searchModel = new Tran();
        $lastTran = Tran::find()->where(['status' => 1])->orderBy(['trandate' => SORT_DESC])->one();

        if ($lastTran === null) {
            $lastTran = new Tran();
        }

        $trantype = addslashes("$module/$type");
        $transactions = Yii::$app->db->createCommand(
            "SELECT
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
            WHERE t.trantype = '$trantype' 
            ORDER BY t.trandate DESC
        "
        )->queryAll();

        $title = ucfirst($module) . " " . ucfirst($type);
        $numbercode = Yii::$app->db->createCommand("SELECT * FROM numbertemplates where type = '$type_id'")->queryAll();

        $render = "index";

        if ("$module/$type" == "sales/quote" || "$module/$type" == "sales/order") {
            $render = "indexpro";
        } else if ("$module/$type" == "sales/delivery") {
            $render = "indexdeli";
        } else if ("$module/$type" == "purchase/order" || "$module/$type" == "purchase/request" || "$module/$type" == "purchase/return") {
            $render = "indexord";
        } else if ("$module/$type" == "sales/return") {
            $render = "indexreturn";
        } else if ("$module/$type" == "purchase/delivery") {
            $render = "indexin";
        }

        $previewcode = Tran::nextNoTransaksi();
        return $this->render($render, [
            'model' => $lastTran,
            'transactions' => $transactions,
            'searchModel' => $searchModel,
            'module' => $module,
            'type' => $type,
            'title' => $title,
            'type_id' => $type_id,
            'numbercode' => $numbercode,
            'previewcode' => $previewcode
        ]);
    }
    public function actionCrew($startDate, $endDate, $exclude = '', $tranId = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $startFormatted = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $startDate)));
        $endFormatted = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $endDate)));

        $usedIds = !empty($exclude) ? explode(',', $exclude) : [];

        $pic = $this->findCrew(['position.cr', 'position.op'], $startFormatted, $endFormatted, 1, $usedIds, $tranId);
        if (!empty($pic[0]['contact_id'])) {
            $usedIds[] = $pic[0]['contact_id'];
        }

        $operator = $this->findCrew('position.op', $startFormatted, $endFormatted, 1, $usedIds, $tranId);
        if (!empty($operator[0]['contact_id'])) {
            $usedIds[] = $operator[0]['contact_id'];
        }

        $driver = $this->findCrew('position.dr', $startFormatted, $endFormatted, 1, $usedIds, $tranId);
        $freelance = $this->findCrew('position.fe', $startFormatted, $endFormatted, 1, $usedIds, $tranId);

        $crew = $this->findCrew('position.cr', $startFormatted, $endFormatted, 2, $usedIds, $tranId);
        foreach ($crew as $c) {
            if (!empty($c['contact_id'])) {
                $usedIds[] = $c['contact_id'];
            }
        }

        $standby = $this->findCrew('position.cr', $startFormatted, $endFormatted, 1, $usedIds, $tranId);

        return [
            "success" => true,
            "data" => [
                "pic" => $pic[0] ?? null,
                "operator" => $operator[0] ?? null,
                "driver" => $driver[0] ?? null,
                "freelance" => $freelance[0] ?? null,
                "crew1" => $crew[0] ?? null,
                "crew2" => $crew[1] ?? null,
                "standby" => $standby[0] ?? null,
            ]
        ];
    }
    protected function findCrew($position, $startDate, $endDate, $limit = 1, $excludeIds = [], $tranId = null)
    {
        if (is_array($position)) {
            $positionsFormatted = "'" . implode("','", $position) . "'";
            $positionWhere = "c.positionid IN ($positionsFormatted)";
        } else {
            $positionWhere = "c.positionid = '$position'";
        }

        $excludeWhere = "";
        if (!empty($excludeIds)) {
            $cleanIds = "'" . implode("','", array_filter($excludeIds)) . "'";
            $excludeWhere = " AND c.contact_id NOT IN ($cleanIds)";
        }

        $tranWhere = "";
        if (!empty($tranId)) {
            $tranWhere = " AND t.tranid <> '$tranId'";
        }

        $pureStartDate = date('Y-m-d', strtotime($startDate));
        $pureEndDate = date('Y-m-d', strtotime($endDate));

        $sql = "SELECT c.contact_id,
        (c.contact_no || ' [' || c.contact_name || ']') AS contact_name,
        COALESCE(work_count.total_work, 0) AS total_work
        FROM contacts c
        LEFT JOIN (
            SELECT tec.crewid, COUNT(*) AS total_work
            FROM traneventcrews tec
            WHERE tec.status <> '10'
            GROUP BY tec.crewid
        ) work_count ON work_count.crewid = c.contact_id
        WHERE c.contacttype = 'employee'
        AND c.contact_status = '1'
        AND $positionWhere 
        $excludeWhere
        AND c.contact_id NOT IN (
            SELECT tec.crewid
            FROM traneventcrews tec
            INNER JOIN tranevents te ON te.traneventid = tec.traneventid
            INNER JOIN trans t ON t.tranid = te.tranid
            WHERE DATE(te.startdate) <= '$pureEndDate' 
            AND DATE(te.enddate) >= '$pureStartDate'
            AND t.status NOT IN ('5', '10') 
            AND te.status <> '10'
            AND tec.status <> '10'
            $tranWhere
        )
        ORDER BY total_work ASC
        LIMIT $limit";

        $crew = Yii::$app->db->createCommand($sql)->queryAll();

        for ($i = 0; $i < $limit; $i++) {
            if (!isset($crew[$i])) {
                $crew[$i] = ["contact_id" => null, "contact_name" => null];
            }
        }

        return $crew;
    }
    public function actionPreviewcode()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $type = Yii::$app->request->get('type');
        // var_dump($type);die;
        $companyid = Yii::$app->session->get('companyid');
        if (!$companyid) {
            $userId = intval(Yii::$app->user->id);
            $companyid = Yii::$app->db->createCommand("
                SELECT companyid FROM users WHERE userid = '$userId'
            ")->queryScalar();
        }

        try {
            $type = addslashes($type);
            $companyid = intval($companyid);

            $template = Yii::$app->db->createCommand("
                SELECT template FROM numbertemplates 
                WHERE companyid = $companyid AND type = '$type'
                LIMIT 1
            ")->queryScalar();

            if (!$template) {
                $template = $type;
            }

            // var_dump($template);die;
            $bulanRomawi = Tran::convertToRoman(date('n'));
            $tahun = date('Y');

            $regexPattern = '^[0-9]+/' . $template . '/' . $bulanRomawi . '/' . $tahun . '$';

            $sql = "
                SELECT COALESCE(
                    MAX(CAST(
                        regexp_replace(tranno, '^([0-9]+)/$template/$bulanRomawi/$tahun$', '\\1') AS INTEGER
                    )), 0
                ) + 1 AS next_no
                FROM trans
                WHERE tranno ~ '$regexPattern'
                AND companyid = $companyid
            ";

            $results = Yii::$app->db->createCommand($sql)->queryOne();


            $nextKode = $results['next_no'] ?? 1;

            $fullKode = sprintf(
                "%s/%s/%s/%s",
                str_pad($nextKode, 5, "0", STR_PAD_LEFT),
                $template,
                $bulanRomawi,
                $tahun
            );

            return ['success' => true, 'preview' => $fullKode, 'previewcode' => $fullKode];
        } catch (Exception $e) {
            Yii::error("Error generating transaction number: " . $e->getMessage());
            return 'Error generating transaction number';
        }
    }
    public function actionSavetemplate()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $controllerId = Yii::$app->controller->id;
        $template = Yii::$app->request->get('template');
        $companyid = Yii::$app->session->get('companyid');

        if (!$template || !$companyid) {
            return ['success' => false, 'message' => 'Data tidak lengkap.'];
        }

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
        $type = Yii::$app->request->get('numberformat');
        // var_dump($type);die;
        // Validate required parameters
        if (!$template || !$companyid || !$type) {
            return ['success' => false, 'message' => 'Incomplete data'];
        }

        $typeMap = [
            'ORD' => 'ord_code',
            'D' => 'delivery_code',
            'INV' => 'invoices_code',
            'RTN' => 'return_code',
            'RQ' => 'request_code',
            'QU' => 'quotation_code'
        ];

        if (!isset($typeMap[$type])) {
            return ['success' => false, 'message' => 'Invalid document type'];
        }

        try {
            $affectedRows = Yii::$app->db->createCommand()->update('company', [
                $typeMap[$type] => $template
            ], ['companyid' => $companyid])->execute();

            return ['success' => (bool) $affectedRows];
        } catch (Exception $e) {
            Yii::error("Failed to set template: " . $e->getMessage());
            return ['success' => false, 'message' => 'Database error'];
        }
    }
    public function actionResettemplate()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $companyid = Yii::$app->session->get('companyid');

        if (!$companyid) {
            return ['success' => false, 'message' => 'Company ID tidak ditemukan.'];
        }

        $type = Yii::$app->request->get('type');
        $template = Yii::$app->request->get('numberFormat');

        $typeMap = [
            'ORD' => 'ord_code',
            'D' => 'delivery_code',
            'INV' => 'invoices_code',
            'RTN' => 'return_code',
            'RQ' => 'request_code',
            'QU' => 'quotation_code',
        ];

        if (isset($typeMap[$type])) {
            Yii::$app->db->createCommand()->update('company', [
                $typeMap[$type] => $template
            ], ['companyid' => $companyid])->execute();

            return ['success' => true];
        }

        return ['success' => false, 'message' => 'Tipe tidak valid.'];
    }

    /**
     * Converts a number to words in Indonesian
     * @param float $number
     * @return string
     */
    private function convertNumberToWords($number)
    {
        $units = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];

        if ($number < 12) {
            return $units[$number];
        } elseif ($number < 20) {
            return $units[$number - 10] . ' Belas';
        } elseif ($number < 100) {
            return $units[floor($number / 10)] . ' Puluh ' . $units[$number % 10];
        } elseif ($number < 200) {
            return 'Seratus ' . $this->convertNumberToWords($number - 100);
        } elseif ($number < 1000) {
            return $units[floor($number / 100)] . ' Ratus ' . $this->convertNumberToWords($number % 100);
        } elseif ($number < 2000) {
            return 'Seribu ' . $this->convertNumberToWords($number - 1000);
        } elseif ($number < 1000000) {
            return $this->convertNumberToWords(floor($number / 1000)) . ' Ribu ' . $this->convertNumberToWords($number % 1000);
        } elseif ($number < 1000000000) {
            return $this->convertNumberToWords(floor($number / 1000000)) . ' Juta ' . $this->convertNumberToWords($number % 1000000);
        } elseif ($number < 1000000000000) {
            return $this->convertNumberToWords(floor($number / 1000000000)) . ' Milyar ' . $this->convertNumberToWords($number % 1000000000);
        } elseif ($number < 1000000000000000) {
            return $this->convertNumberToWords(floor($number / 1000000000000)) . ' Trilyun ' . $this->convertNumberToWords($number % 1000000000000);
        }

        return '';
    }
    public function actionCreatedeli()
    {
        $model = new Tran();
        $modeldetails = [new Trandetail];

        $params = Yii::$app->request->queryParams;
        $module = $params['module'] ?? '';
        $type = $params['type'] ?? '';
        $type_id = $params['type_id'] ?? '';
        $currentUserId = Yii::$app->user->identity->userid;

        if ($model->load(Yii::$app->request->post())) {
            // var_dump(Yii::$app->request->post());die;
            $postTran = Yii::$app->request->post('Tran', []);

            if (!empty($postTran['trantype'])) {
                $model->trantype = $postTran['trantype'];
            } else {
                $model->trantype = "$module/$type";
            }

            if ($model->isNewRecord && $currentUserId) {
                $sql = "SELECT c.contact_id, c.divisionid 
                FROM users u
                INNER JOIN contacts c ON c.contact_id = u.contact_id 
                WHERE u.userid = '{$currentUserId}'";

                $userContact = Yii::$app->db->createCommand($sql)->queryOne();

                if ($userContact && $userContact['divisionid'] === 'division.warehouse') {
                    $model->contact_id = $userContact['contact_id'];
                }
            }

            $modeldetails = Model::createMultipleID(Trandetail::classname(), $modeldetails, 'trandetailid');
            Model::loadMultiple($modeldetails, Yii::$app->request->post());

            $valid = $model->validate();
            $valid = Model::validateMultiple($modeldetails) && $valid;

            $id = Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();

            if ($valid) {
                $model->tranid = $id;
                if ($model->trantype == 'purchase/delivery') {
                    $model->statuspro = 10;
                } else {
                    $model->statuspro = 0;
                }

                $transaction = Yii::$app->db->beginTransaction();

                try {
                    $model->tranno = $model->nextNoTransaksi();

                    if ($flag = $model->save(false)) {
                        foreach ($modeldetails as $indexdetail => $modeldetail) {
                            if ($flag === false) {
                                break;
                            }

                            $modeldetail->tranid = $model->tranid;
                            $modeldetail->ord = $indexdetail + 1;

                            if (!($flag = $modeldetail->save(false))) {
                                break;
                            }

                        }

                        if ($flag) {
                            $transaction->commit();

                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return ['success' => true, 'pesan' => 'Data Berhasil Disimpan', 'id' => $model->tranid, 'name' => $model->tranno];
                            }
                            return $this->redirect(['index', 'id' => $model->tranid]);
                        } else {
                            $transaction->rollBack();
                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return $this->notValid($model, $modeldetails);
                            }
                        }
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();

                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['success' => false, 'pesan' => $e->getMessage()];
                    }
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return $this->notValid($model, $modeldetails);
                }
            }
        }

        $tranNo = $model->nextNoTransaksi();
        $model->tranno = $tranNo;
        $model->trandate = date("d/m/Y");
        $model->trantype = "$module/$type";

        if ($model->isNewRecord && $currentUserId) {
            $sql = "SELECT c.contact_id, c.divisionid 
                FROM users u
                INNER JOIN contacts c ON c.contact_id = u.contact_id 
                WHERE u.userid = '{$currentUserId}'";

            $userContact = Yii::$app->db->createCommand($sql)->queryOne();

            if ($userContact && $userContact['divisionid'] === 'division.warehouse') {
                $model->contact_id = $userContact['contact_id'];
            }
        }

        $form = '_formdeli';
        if ($model->trantype == 'purchase/delivery' && $type_id == '1') {
            $form = '_formin';
        } else if ($model->trantype == 'purchase/delivery' && $type_id == '2') {
            $form = '_forminpo';
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax($form, [
                'model' => $model,
                'modeldetails' => (empty($modeldetails)) ? [new Trandetail] : $modeldetails,
                'isajax' => true,
            ]);
        } else {
            return $this->render($form, [
                'model' => $model,
                'modeldetails' => (empty($modeldetails)) ? [new Trandetail] : $modeldetails,
                'isajax' => false,
            ]);
        }
    }
    public function actionUpdatedeli($id)
    {
        $model = Tran::findOne($id);

        if ($model == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $modeldetails = $model->trandetails;

        if ($model->load(Yii::$app->request->post())) {
            // var_dump(Yii::$app->request->post());die;

            $eventType = $model->eventtype;

            $oldIDs = ArrayHelper::map($modeldetails, 'trandetailid', 'trandetailid');
            $modeldetails = Model::createMultipleID(Trandetail::classname(), $modeldetails, 'trandetailid');

            Model::loadMultiple($modeldetails, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modeldetails, 'trandetailid', 'trandetailid')));

            $valid = $model->validate();
            $valid = Model::validateMultiple($modeldetails) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {

                        if (!empty($deletedIDs)) {
                            $tvs = Tranvariants::find()->where(['trandetailid' => $deletedIDs])->all();
                            foreach ($tvs as $tv) {
                                $variant = Variant::findOne($tv->variantid);
                                if ($variant) {
                                    $variant->locationid = 'location.1';
                                    if (!$variant->save()) {
                                        throw new \Exception('Gagal update lokasi variant: ' . json_encode($variant->errors));
                                    }
                                }
                            }
                            Trandetail::deleteAll(['trandetailid' => $deletedIDs]);
                            Tranvariants::deleteAll(['trandetailid' => $deletedIDs]);
                            Variant::deleteAll(['refid' => $deletedIDs]);
                        }

                        foreach ($modeldetails as $indexdetail => $modeldetail) {
                            $modeldetail->tranid = $model->tranid;
                            $modeldetail->ord = $indexdetail + 1;

                            $oldDetail = Trandetail::findOne($modeldetail->trandetailid);
                            $oldAmountMilik = $oldDetail ? (int) $oldDetail->amount : 0;
                            $oldAmountPinjam = $oldDetail ? (int) $oldDetail->amount2 : 0;
                            $oldRemain2 = $oldDetail ? (int) $oldDetail->remain2 : 0;

                            if ($modeldetail->save(false)) {

                                if ($model->trantype == 'purchase/delivery' && $eventType == '1') {
                                    $newAmount = (int) $modeldetail->remain2;

                                    if ($newAmount > $oldRemain2) {
                                        $diff = $newAmount - $oldRemain2;
                                        for ($i = 0; $i < $diff; $i++) {
                                            $variant = new Variant();
                                            $variant->variantid = Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();
                                            $variant->productid = $modeldetail->productid;
                                            $variant->refid = $modeldetail->trandetailid;
                                            $variant->tranid = $modeldetail->tranid;
                                            $variant->status = 1;
                                            $variant->sku = '1'; // pinjam
                                            $variant->locationid = 'location.1';
                                            $variant->condition = 'condition.1';

                                            if (!$variant->save()) {
                                                $errorMsg = empty($variant->errors) ? "beforeSave returned false" : json_encode($variant->errors);
                                                throw new Exception("Gagal simpan variant ke-$i: " . $errorMsg);
                                            }
                                        }
                                    } elseif ($newAmount < $oldRemain2) {
                                        $diff = $oldRemain2 - $newAmount;
                                        $ids = Variant::find()
                                            ->select('variantid')
                                            ->where(['refid' => $modeldetail->trandetailid])
                                            ->orderBy(['variantid' => SORT_DESC])
                                            ->limit($diff)
                                            ->column();

                                        if (!empty($ids)) {
                                            Variant::deleteAll(['variantid' => $ids]);
                                        }
                                    }
                                } elseif ($model->trantype == 'purchase/delivery' && in_array((string) $eventType, ['0', ''], true) && $model->reftype != 'sales/order') {
                                    $newRemain2 = (int) $modeldetail->remain2;

                                    if ($newRemain2 < $oldRemain2) {
                                        $variants = Variant::find()
                                            ->where(['refid' => $modeldetail->trandetailid])
                                            ->andWhere(['<>', 'status', 10])
                                            ->all();

                                        foreach ($variants as $variant) {
                                            $variant->status = 10;
                                            if (!$variant->save(false)) {
                                                throw new Exception('Gagal membatalkan variant untuk trandetail ' . $modeldetail->trandetailid);
                                            }
                                        }
                                    }

                                } elseif ($model->trantype == 'sales/delivery') {
                                    $newAmountMilik = (int) $modeldetail->amount;
                                    $newAmountPinjam = (int) $modeldetail->amount2;

                                    if ($newAmountMilik < $oldAmountMilik) {
                                        $tvsMilik = Tranvariants::find()
                                            ->where(['trandetailid' => $modeldetail->trandetailid])
                                            ->andWhere(['type' => 2, 'status' => 1])
                                            ->all();

                                        foreach ($tvsMilik as $tv) {
                                            $v = Variant::findOne($tv->variantid);
                                            if ($v) {
                                                $v->locationid = !empty($tv->from_locationid) ? $tv->from_locationid : 'location.1';
                                                if (!$v->save(false)) {
                                                    throw new Exception('Gagal mengembalikan lokasi barang milik ke gudang utama');
                                                }
                                            }
                                            if (!$tv->delete()) {
                                                throw new Exception('Gagal menghapus data scan tranvariant (milik).');
                                            }
                                        }
                                    }

                                    if ($newAmountPinjam > $oldAmountPinjam) {
                                        $diff = $newAmountPinjam - $oldAmountPinjam;
                                        for ($i = 0; $i < $diff; $i++) {
                                            $variant = Variant::find()->where([
                                                'productid' => $modeldetail->productid,
                                                'status' => 1,
                                                'locationid' => 'location.1',
                                                'sku' => '1'
                                            ])->one();

                                            if ($variant) {
                                                $variant->locationid = 'location.3';
                                                $variant->save(false);

                                                $tranvariant = new Tranvariants();
                                                $tranvariant->tranvariantid = Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();
                                                $tranvariant->trandetailid = $modeldetail->trandetailid;
                                                $tranvariant->variantid = $variant->variantid;
                                                $tranvariant->productid = $modeldetail->productid;
                                                $tranvariant->type = 2; // stcokout
                                                $tranvariant->status = 0;
                                                $tranvariant->refid = $modeldetail->tranid;
                                                $tranvariant->trandate = date('Y-m-d H:i:s');

                                                if (!$tranvariant->save(false)) {
                                                    throw new Exception('Gagal simpan penambahan tranvariant pinjam');
                                                }
                                            } else {
                                                throw new Exception('Stok fisik di gudang tidak cukup untuk produk ' . $modeldetail->product->productname);
                                            }
                                        }
                                    } elseif ($newAmountPinjam < $oldAmountPinjam) {
                                        $diff = $oldAmountPinjam - $newAmountPinjam;
                                        $tvs = Tranvariants::find()
                                            ->where(['trandetailid' => $modeldetail->trandetailid])
                                            ->andWhere(['type' => 2, 'status' => 0])
                                            ->orderBy(['tranvariantid' => SORT_DESC])
                                            ->limit($diff)
                                            ->all();

                                        foreach ($tvs as $tv) {
                                            $v = Variant::findOne($tv->variantid);
                                            if ($v) {
                                                $v->locationid = 'location.1';
                                                if (!$v->save(false)) {
                                                    throw new Exception('Gagal mengembalikan lokasi barang pinjam');
                                                }
                                            }
                                            if (!$tv->delete()) {
                                                throw new Exception('Gagal menghapus tranvariant pinjam.');
                                            }
                                        }
                                    }

                                } else if ($model->trantype == 'sales/return' || ($model->trantype == 'purchase/delivery' && $model->reftype == 'sales/order')) {
                                    $newAmountMilik = (int) $modeldetail->amount;
                                    $newAmountPinjam = (int) $modeldetail->amount2;

                                    if ($newAmountMilik < $oldAmountMilik) {

                                        $tvsMilik = Tranvariants::find()
                                            ->where(['trandetailid' => $modeldetail->trandetailid])
                                            ->andWhere(['type' => 3, 'status' => 1])
                                            ->all();

                                        foreach ($tvsMilik as $tv) {
                                            $v = Variant::findOne($tv->variantid);
                                            if ($v) {
                                                $v->locationid = !empty($tv->from_locationid) ? $tv->from_locationid : 'location.3';
                                                if (!$v->save(false)) {
                                                    throw new Exception('Gagal mengembalikan lokasi barang milik ke lapangan');
                                                }
                                            }
                                            if (!$tv->delete()) {
                                                throw new Exception('Gagal menghapus data scan return (milik).');
                                            }
                                        }
                                    }

                                    if ($newAmountPinjam > $oldAmountPinjam) {
                                        $diff = $newAmountPinjam - $oldAmountPinjam;

                                        for ($i = 0; $i < $diff; $i++) {
                                            $variant = Variant::find()->where([
                                                'productid' => $modeldetail->productid,
                                                'status' => 1,
                                                'locationid' => 'location.3',
                                                'sku' => '1' // barang pinjam
                                            ])->one();

                                            if ($variant) {
                                                $from_location = $variant->locationid;

                                                $lastDelivery = Tranvariants::find()
                                                    ->where(['variantid' => $variant->variantid, 'type' => 2])
                                                    ->andWhere(['<>', 'status', 10])
                                                    ->orderBy(['trandate' => SORT_DESC])
                                                    ->one();

                                                $lokasiAwal = ($lastDelivery && !empty($lastDelivery->from_locationid))
                                                    ? $lastDelivery->from_locationid
                                                    : 'location.1';

                                                $variant->locationid = $lokasiAwal;
                                                $variant->save(false);

                                                $tranvariant = new Tranvariants();
                                                $tranvariant->tranvariantid = Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();
                                                $tranvariant->trandetailid = $modeldetail->trandetailid;
                                                $tranvariant->variantid = $variant->variantid;
                                                $tranvariant->productid = $modeldetail->productid;
                                                $tranvariant->type = 3;
                                                $tranvariant->status = 0;
                                                $tranvariant->refid = $modeldetail->tranid;
                                                $tranvariant->from_locationid = $from_location;

                                                if (!$tranvariant->save(false)) {
                                                    throw new Exception('Gagal simpan penambahan tranvariant pinjam (return)');
                                                }
                                            } else {
                                                throw new Exception('Stok barang pinjam di lapangan tidak cukup/tidak ditemukan untuk direturn: ' . $modeldetail->product->productname);
                                            }
                                        }
                                    } elseif ($newAmountPinjam < $oldAmountPinjam) {
                                        $diff = $oldAmountPinjam - $newAmountPinjam;

                                        $tvs = Tranvariants::find()
                                            ->where(['trandetailid' => $modeldetail->trandetailid])
                                            ->andWhere(['type' => 3, 'status' => 0])
                                            ->orderBy(['tranvariantid' => SORT_DESC])
                                            ->limit($diff)
                                            ->all();

                                        foreach ($tvs as $tv) {
                                            $v = Variant::findOne($tv->variantid);
                                            if ($v) {
                                                $v->locationid = !empty($tv->from_locationid) ? $tv->from_locationid : 'location.3';
                                                if (!$v->save(false)) {
                                                    throw new Exception('Gagal membatalkan return barang pinjam');
                                                }
                                            }
                                            if (!$tv->delete()) {
                                                throw new Exception('Gagal menghapus tranvariant return pinjam.');
                                            }
                                        }
                                    }
                                }
                            } else {
                                $flag = false;
                                throw new Exception('Error: ' . json_encode($modeldetail->getFirstErrors()));
                            }
                        }

                        if ($flag) {
                            $transaction->commit();

                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return ['success' => true, 'pesan' => 'Data Berhasil Disimpan', 'id' => $model->tranid, 'name' => $model->tranno];
                            }
                            return $this->redirect(['index', 'id' => $model->tranid]);
                        } else {
                            $transaction->rollBack();
                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return $this->notValid($model, $modeldetails);
                            }
                        }
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    Yii::error('Update delivery error: ' . $e->getMessage());

                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['success' => false, 'pesan' => $e->getMessage()];
                    }
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return $this->notValid($model, $modeldetails);
                }
            }
        }

        $model->trandate = $model->trandate ? Yii::$app->formatter->asDate($model->trandate, 'dd/MM/yyyy') : null;
        $params = Yii::$app->request->queryParams;
        $type_id = $params['type_id'] ?? '';

        $form = '_formdeli';

        if ($model->trantype == 'sales/return') {
            $form = '_formreturn';
        } else if ($model->trantype == 'purchase/delivery') {
            if ($type_id == '2') {
                $form = '_forminpo';
            } else {
                $form = '_formin';
            }
        } else if ($model->trantype == 'sales/delivery') {
            $form = '_formdeli';
        }

        if (empty($form)) {
            throw new NotFoundHttpException('Form view tidak ditemukan untuk trantype: ' . $model->trantype);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax($form, [
                'model' => $model,
                'modeldetails' => (empty($modeldetails)) ? [new Trandetail] : $modeldetails,
                'isajax' => true,
            ]);
        } else {
            return $this->render($form, [
                'model' => $model,
                'modeldetails' => (empty($modeldetails)) ? [new Trandetail] : $modeldetails,
                'isajax' => false,
            ]);
        }
    }
    public function actionCreatereturnstock()
    {
        $model = new Tran();
        $modeldetails = [new Trandetail];
        $params = Yii::$app->request->queryParams;
        $module = $params['module'] ?? '';
        $type = $params['type'] ?? '';
        $moduleType = $module . '/' . $type;
        $currentUserId = Yii::$app->user->identity->userid;

        $kodePenomoran = null;
        if ($moduleType == 'sales/return') {
            $kodePenomoran = 'RO';
        } elseif ($moduleType == 'purchase/return') {
            $kodePenomoran = 'RI';
        }

        if ($model->load(Yii::$app->request->post())) {
            // var_dump(Yii::$app->request->post());die;
            if ($model->isNewRecord && $currentUserId) {
                $sql = "SELECT c.contact_id, c.divisionid 
                FROM users u
                INNER JOIN contacts c ON c.contact_id = u.contact_id 
                WHERE u.userid = '{$currentUserId}'";

                $userContact = Yii::$app->db->createCommand($sql)->queryOne();

                if ($userContact && $userContact['divisionid'] === 'division.warehouse') {
                    $model->contact_id = $userContact['contact_id'];
                }
            }

            $modeldetails = Model::createMultipleID(Trandetail::classname(), $modeldetails, 'trandetailid');
            Model::loadMultiple($modeldetails, Yii::$app->request->post());

            $valid = $model->validate();
            $valid = Model::validateMultiple($modeldetails) && $valid;

            $newid = Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();

            if ($valid) {
                $model->tranid = $newid;
                $model->trantype = $moduleType;
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    $model->tranno = $model->nextNoTransaksi($kodePenomoran);

                    if ($flag = $model->save(false)) {
                        // var_dump(Yii::$app->request->post()['Trandetail']);die;

                        foreach ($modeldetails as $indexdetail => $modeldetail) {
                            if ($flag === false) {
                                break;
                            }

                            $modeldetail->tranid = $model->tranid;
                            $modeldetail->ord = $indexdetail + 1;

                            if (!($flag = $modeldetail->save(false))) {
                                break;
                            }

                            if ($model->trantype == 'purchase/return') {
                                for ($i = 0; $i < $modeldetail->amount; $i++) {
                                    $tranvariant = new Tranvariants();
                                    $tranvariant->trandetailid = $modeldetail->trandetailid;
                                    if (!($flag = $tranvariant->save(false))) {
                                        throw new Exception('Error: ' . $tranvariant->getFirstErrors());
                                    }
                                }
                            }
                        }

                        if ($flag) {
                            $transaction->commit();

                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return ['success' => true, 'pesan' => 'Data Berhasil Disimpan', 'id' => $model->tranid, 'name' => $model->tranno];
                            }
                            return $this->redirect(['index', 'id' => $model->tranid]);
                        } else {
                            $transaction->rollBack();
                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return $this->notValid($model, $modeldetails);
                            }
                        }
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();

                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['success' => false, 'pesan' => $e->getMessage()];
                    }
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return $this->notValid($model, $modeldetails);
                }
            }
        }

        // // var_dump($moduleType);die();
        $params = Yii::$app->request->getQueryParams();
        if ($moduleType == 'sales/return') {
            $params['penomoran'] = 'RO';
        } elseif ($moduleType == 'purchase/return') {
            $params['penomoran'] = 'RI';
        }

        $model->trantype = $moduleType;
        Yii::$app->request->setQueryParams($params);
        $model->tranno = $model->nextNoTransaksi();
        $model->trandate = date("d/m/Y");

        if ($model->isNewRecord && $currentUserId) {
            $sql = "SELECT c.contact_id, c.divisionid 
                FROM users u
                INNER JOIN contacts c ON c.contact_id = u.contact_id 
                WHERE u.userid = '{$currentUserId}'";

            $userContact = Yii::$app->db->createCommand($sql)->queryOne();

            if ($userContact && $userContact['divisionid'] === 'division.warehouse') {
                $model->contact_id = $userContact['contact_id'];
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_formreturn', [
                'model' => $model,
                'modeldetails' => (empty($modeldetails)) ? [new Trandetail] : $modeldetails,
                'isajax' => true,
            ]);
        } else {
            return $this->render('_formreturn', [
                'model' => $model,
                'modeldetails' => (empty($modeldetails)) ? [new Trandetail] : $modeldetails,
                'isajax' => false,
            ]);
        }
    }
    public function actionList()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->request->queryParams;
        $contact = $params['contact'] ?? '';
        $status = $params['status'] ?? '';
        $statusInvoice = $params['statusInvoice'] ?? '';
        $product = $params['product'] ?? '';
        $search = $params['search'] ?? '';
        $date = $params['datefilter'] ?? '';
        $duedate = $params['duedatefilter'] ?? '';
        $module = $params['module'] ?? 'purchase';
        $type_id = $params['type_id'] ?? (is_numeric($params['type'] ?? null) ? $params['type'] : '');
        $type = $params['type'] ?? '';
        if (empty($type) || is_numeric($type)) {
            $type = 'delivery';
        }

        $trantype = "$module/$type";
        // var_dump($type_id);exit;

        $sortcolumn = $params['order'][0]['column'] ?? 0;
        $ordercolumn = $params['columns'][$sortcolumn]['data'] ?? 'createdat';
        $columnorder = $params['order'][0]['dir'] ?? 'DESC';

        $allowedColumns = ['createdat', 'tranno', 'trandate', 'tranduedate', 'contact_name', 'total', 'sisa_pembayaran'];
        if (!in_array($ordercolumn, $allowedColumns)) {
            $ordercolumn = 't.createdat';
        } else {
            if ($ordercolumn === 'createdat') {
                $ordercolumn = 't.createdat';
            }
        }

        $reftype = '';
        $is_in_condition = false;

        if ("$module/$type" == "purchase/delivery") {
            if ($type_id == 1) {
                $reftype = 'sales/order';
            } elseif ($type_id == 2) {
                $reftype = "'purchase/request', 'purchase/order'";
                $is_in_condition = true;
            }
        }

        $query =
            "SELECT
                t.*,
                u.name,
                r.tranno as ref,
                COALESCE(con.contact_name, '-') as po_contact,
                COALESCE(con.jobcompany, '-') as po_jobcompany,
                COALESCE(ref_contacts.contact_name, '-') as ref_contact_name,
                COALESCE(ref_contacts.jobcompany, '-') as ref_jobcompany,
                COALESCE(c.contact_name, '-') as contact_name,
                COALESCE(c.jobcompany, '-') as jobcompany,
                COALESCE(c.contact_phone1, '-') as contact_phone1,
                COALESCE(a.contact_name, '-') as ae,
                COALESCE(item_count.count, 0) as item_count, 
                COALESCE(del.amount_del, 0) as amount_del,
                COALESCE(scan_out.total_scan, 0) AS total_scan_del,
                COALESCE(ret_tot.amount_del, 0) as amount_ret,
                COALESCE(del_refid.amount_del, 0) as amount_del_refid,
                COALESCE(count_out.total_scan, 0) as total_scan_out,
                COALESCE(del_so.total_del, 0) AS total_del,
                COALESCE(count_ret.total_ret, 0) AS total_ret,
                COALESCE(scan_ret.total_ret, 0) + COALESCE(scan_retpd.total_ret_pd, 0) AS total_ret_docs,

                COALESCE(po_amount.amount_po, 0) AS amount_po,
                COALESCE(td_sum.total_td_amount, 0) AS td_amount,
                COALESCE(scan_pd.count, 0) AS count_in,
                COALESCE(count_po_pinjam.count, 0) AS count_in_pinjam,
                COALESCE(scan_in.count, 0) AS count_pd,
                COALESCE(count_missing.count, 0) AS count_missing,
                COALESCE(ref_contacts.statuspro, '') AS statuspro_ord,
                COALESCE((
                    SELECT SUM(kd.value) 
                    FROM cashdetails kd 
                    JOIN cashs k ON k.cashid = kd.cashid 
                    LEFT JOIN trans inv ON inv.tranid = k.refid AND inv.trantype ='sales/invoice' AND inv.status <> 10
                    WHERE inv.refid = t.tranid AND k.status <> 10
                ), 0) AS total_invoice,

                (t.grandtotal - COALESCE((SELECT SUM(kd.value) 
                    FROM cashdetails kd 
                    JOIN cashs k ON k.cashid = kd.cashid 
                    LEFT JOIN trans inv ON inv.tranid = k.refid AND inv.trantype ='sales/invoice' AND inv.status <> 10
                    WHERE inv.refid = t.tranid AND k.status <> 10),0)) AS sisa_invoice,

                (SELECT 
                    COALESCE(SUM(td.itemsubtotaltax), 0) 
                    FROM trandetails td 
                    WHERE td.tranid = t.tranid) as total,

                COALESCE((
                    SELECT SUM(kd.value) FROM cashdetails kd 
                    JOIN cashs k ON k.cashid = kd.cashid 
                    WHERE k.refid = t.tranid 
                    AND k.status <> 10),0) AS total_pembayaran,

                (t.grandtotal - COALESCE((SELECT SUM(kd.value) 
                FROM cashdetails kd JOIN cashs k ON k.cashid = kd.cashid 
                WHERE k.refid = t.tranid AND k.status <> 10),0)) AS sisa_pembayaran

            FROM trans t
            LEFT JOIN trans r ON r.tranid = t.refid
            LEFT JOIN contacts c ON c.contact_id = t.contact_id
            LEFT JOIN contacts a ON a.contact_id = t.aeid
            LEFT JOIN users u ON u.userid::text = t.createdby
            LEFT JOIN trans po ON po.tranid = t.refid AND po.status <> 10
			LEFT JOIN contacts con ON con.contact_id = po.contact_id

            -- utk stock in
            LEFT JOIN (
                    SELECT B.tranid, SUM(A.remain2) as amount_po                 
                    FROM trandetails A
                    LEFT JOIN trans B ON A.tranid=B.tranid AND COALESCE(B.status,0) <> 10
                    AND COALESCE(A.status,0) <> 10 
                    GROUP BY B.tranid
            ) po_amount ON po_amount.tranid = t.tranid 

            LEFT JOIN (
                SELECT COUNT(v.variantid) as count, v.tranid
                FROM variants v
                WHERE v.status <> 10 AND v.sku IS NULL -- milik
                GROUP BY v.tranid
            ) AS scan_in ON scan_in.tranid = t.tranid

            LEFT JOIN (
                SELECT COUNT(v.variantid) as count, v.tranid
                FROM variants v
                WHERE v.status <> 10 AND v.sku = '1' -- pinjam 
                GROUP BY v.tranid
            ) AS count_po_pinjam ON count_po_pinjam.tranid = t.tranid

            LEFT JOIN (
                SELECT tranid, SUM(amount + amount2) as total_td_amount
                FROM trandetails
                GROUP BY tranid
            ) td_sum ON td_sum.tranid = t.tranid

            LEFT JOIN (
                    SELECT COUNT(tv.tranvariantid) as count, t2.refid 
                    FROM tranvariants tv
                    LEFT JOIN trans t2 ON tv.refid = t2.tranid AND t2.status <> 10 AND t2.trantype = 'purchase/delivery'
                    WHERE tv.type='3' AND tv.status <> 10 
                    GROUP BY t2.refid
            ) AS scan_pd ON scan_pd.refid = t.refid

            LEFT JOIN (
                SELECT COUNT(tv.tranvariantid) AS COUNT, o.tranid AS refid
                FROM tranvariants tv
                INNER JOIN trans t ON t.tranid = tv.refid AND t.trantype = 'sales/delivery' AND t.status = '1'
                INNER JOIN trans o ON o.tranid = t.refid AND o.trantype = 'sales/order' AND o.status = '1'
                LEFT JOIN products p ON p.productid = tv.productid 
                LEFT JOIN variants v ON v.variantid = tv.variantid 
                WHERE tv.type = '2' AND tv.status <> 10 AND v.status = 10 
                AND p.status <> 10
                    AND NOT EXISTS (
                    SELECT 1 
                    FROM tranvariants tv_in
                    INNER JOIN trans tr_ret ON tr_ret.tranid = tv_in.refid 
                    WHERE tr_ret.trantype IN ('sales/return', 'purchase/delivery')
                        AND tr_ret.status <> 10 AND tv_in.type = '3' 
                        AND tv_in.barcode = tv.barcode 
                        AND tr_ret.refid = o.tranid 
                        AND COALESCE(tv_in.status, 0) <> 10
                    )
                GROUP BY o.tranid    
            ) AS count_missing ON count_missing.refid = t.refid
            
            -- utk return stock
            LEFT JOIN (
                    SELECT B.refid, (SUM(A.amount) + SUM(A.amount2)) as amount_del                 
                    FROM trandetails A
                    INNER JOIN trans B ON A.tranid=B.tranid AND COALESCE(B.status,0) = 1
                    AND COALESCE(A.status,0) <> 10 AND B.trantype='sales/delivery'
                    GROUP BY B.refid
            ) del_refid ON del_refid.refid = t.refid

            LEFT JOIN (
                    SELECT B.tranid, (SUM(A.amount) + SUM(A.amount2)) as amount_del                 
                    FROM trandetails A
                    INNER JOIN trans B ON A.tranid=B.tranid AND COALESCE(B.status,0) <> 10
                    AND COALESCE(A.status,0) <> 10 AND B.trantype='sales/return'
                    GROUP BY B.tranid
            ) ret_tot ON ret_tot.tranid = t.tranid

            LEFT JOIN (
                    SELECT COUNT(tv.tranvariantid) as count, t2.tranid 
                    FROM tranvariants tv
                    LEFT JOIN trans t2 ON tv.refid = t2.tranid AND t2.status <> 10 AND t2.trantype = 'sales/return'
                    WHERE tv.type='3' AND tv.status <> 10 
                    GROUP BY t2.tranid
            ) AS item_count ON item_count.tranid = t.tranid

            LEFT JOIN (
                    SELECT c.contact_name, c.jobcompany, o.tranid, o.statuspro
                    FROM trans o
                    LEFT JOIN contacts c ON c.contact_id = o.contact_id
                    WHERE o.trantype = 'sales/order' AND o.status <> 10
            ) AS ref_contacts ON ref_contacts.tranid = t.refid

            -- utk sales/order ga bisa finis kalau belum scan semua stockout
            LEFT JOIN (
                SELECT t.refid AS del_refid, COUNT(tv.tranvariantid) AS total_scan
                FROM tranvariants tv
                LEFT JOIN trans t ON t.tranid = tv.refid AND t.status <> 10 AND t.trantype = 'sales/delivery'
                WHERE tv.type = '2' AND tv.status <> 10 
                GROUP BY t.refid
            ) AS count_out ON count_out.del_refid = t.tranid

            LEFT JOIN (
                SELECT d.refid, SUM(COALESCE(sd.amount, 0) + COALESCE(sd.amount2, 0)) AS total_del
                FROM trandetails sd
                LEFT JOIN trans d ON d.tranid = sd.tranid AND d.status <> 10 AND d.trantype = 'sales/delivery'
                WHERE COALESCE(sd.status, 0) <> 10
                GROUP BY d.refid
            ) AS del_so ON del_so.refid = t.tranid

            LEFT JOIN (
                SELECT r.refid, COUNT(tv.tranvariantid) AS total_ret
                FROM tranvariants tv
                LEFT JOIN trans r ON r.tranid = tv.refid AND r.status <> 10 
                WHERE tv.type = '3' AND tv.status <> 10 AND r.trantype = 'sales/return'
                GROUP BY r.refid
            ) AS count_ret ON count_ret.refid = t.tranid

            --- utk status sales/delivery
            LEFT JOIN (
                SELECT t.tranid AS del_tranid, COUNT(tv.tranvariantid) AS total_scan
                FROM tranvariants tv
                LEFT JOIN trans t ON t.tranid = tv.refid AND t.status <> 10 AND t.trantype = 'sales/delivery'
                WHERE tv.type = '2' AND tv.status <> 10 
                GROUP BY t.tranid
            ) AS scan_out ON scan_out.del_tranid = t.tranid

             LEFT JOIN (
                    SELECT B.tranid, (SUM(A.amount) + SUM(A.amount2)) as amount_del                 
                    FROM trandetails A
                    INNER JOIN trans B ON A.tranid=B.tranid AND COALESCE(B.status,0) = 1
                    AND COALESCE(A.status,0) <> 10 AND B.trantype='sales/delivery'
                    GROUP BY B.tranid
            ) del ON del.tranid = t.tranid

            LEFT JOIN (
                SELECT r.refid AS ret_refid, COUNT(r.tranid) AS total_ret_pd
                FROM trans r
                WHERE r.trantype = 'purchase/delivery' AND r.status <> 10
                AND r.reftype = 'sales/order'
                GROUP BY r.refid
            ) AS scan_retpd ON scan_retpd.ret_refid = t.refid

            LEFT JOIN (
                SELECT r.refid AS ret_refid, COUNT(r.tranid) AS total_ret
                FROM trans r
                WHERE r.trantype = 'sales/return' AND r.status <> 10
                GROUP BY r.refid
            ) AS scan_ret ON scan_ret.ret_refid = t.refid

            WHERE t.status <> 10";

        if ($type !== '') {
            $query .= " AND t.trantype = '" . addslashes($trantype) . "'";
        }

        if ($reftype !== '') {
            if ($is_in_condition) {
                $query .= " AND t.reftype IN ($reftype)";
            } else {
                $query .= " AND t.reftype = '" . addslashes($reftype) . "'";
            }
        }
        if ($contact !== '') {
            $query .= " AND t.contact_id = '" . $contact . "'";
        }

        if ($product !== '') {
            $query .= " AND tv.productid = '" . $product . "'";
        }

        if ($search !== '') {
            $safeSearch = addslashes($search);
            $query .= " AND (
            t.tranno ILIKE '%$safeSearch%'
            OR c.contact_name ILIKE '%$safeSearch%'
            OR c.jobcompany ILIKE '%$safeSearch%'
            OR ref_contacts.contact_name ILIKE '%$safeSearch%'
            OR ref_contacts.jobcompany ILIKE '%$safeSearch%'
            OR r.tranno ILIKE '%$safeSearch%'
            )";
        }

        if ($date !== '') {
            $dates = explode(" - ", $date);
            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));
            $query .= " AND DATE(t.trandate) BETWEEN '$startDate' AND '$endDate'";
        }

        if ($duedate !== '') {
            $duedates = explode(" - ", $duedate);
            $startDueDate = date('Y-m-d', strtotime($duedates[0]));
            $endDueDate = date('Y-m-d', strtotime($duedates[1]));
            $query .= " AND DATE(t.tranduedate) BETWEEN '$startDueDate' AND '$endDueDate'";
        }

        if ($status === 'paid') {
            $query .= " AND (t.grandtotal - COALESCE((
                SELECT SUM(kd.value) 
                FROM cashdetails kd 
                JOIN cashs k ON k.cashid = kd.cashid 
                LEFT JOIN trans inv ON inv.tranid = k.refid AND inv.trantype ='sales/invoice' AND inv.status <> 10
                WHERE inv.refid = t.tranid AND k.status <> 10
            ), 0)) <= 0 AND t.grandtotal > 0";
        } elseif ($status === 'unpaid') {
            $query .= " AND (t.grandtotal - COALESCE((
                SELECT SUM(kd.value) 
                FROM cashdetails kd 
                JOIN cashs k ON k.cashid = kd.cashid 
                LEFT JOIN trans inv ON inv.tranid = k.refid AND inv.trantype ='sales/invoice' AND inv.status <> 10
                WHERE inv.refid = t.tranid AND k.status <> 10
            ), 0)) > 0";
        }

        if ($statusInvoice === 'paid') {
            $query .= " AND (t.grandtotal - COALESCE((SELECT SUM(kd.value) FROM cashdetails kd JOIN cashs k ON k.cashid = kd.cashid WHERE k.refid = t.tranid AND k.status <> 10), 0)) <= 0 AND t.grandtotal > 0";
        } elseif ($statusInvoice === 'unpaid') {
            $query .= " AND (t.grandtotal - COALESCE((SELECT SUM(kd.value) FROM cashdetails kd JOIN cashs k ON k.cashid = kd.cashid WHERE k.refid = t.tranid AND k.status <> 10), 0)) > 0";
        }

        $query .= " ORDER BY $ordercolumn $columnorder";
        // echo ($query); die;

        $data = Yii::$app->db->createCommand($query)->queryAll();

        foreach ($data as &$row) {
            if (isset($row['trandate'])) {
                $row['trandate_display'] = Yii::$app->formatter->asDate($row['trandate'], 'php:d-m-Y');
            }
            if (isset($row['tranduedate'])) {
                $row['tranduedate_display'] = $row['tranduedate'] ? Yii::$app->formatter->asDate($row['tranduedate'], 'php:d-m-Y') : null;
            }
            if (isset($row['trantype'])) {
                $parts = explode('/', $row['trantype']);
                $row['module'] = $parts[0] ?? 'purchase';
                $row['type'] = $parts[1] ?? 'request';
            }
        }

        return [
            'data' => $data ?: [],
            'module' => $module,
            'type' => $type
        ];
    }
    public function actionListtranvariant()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->request->queryParams;

        $id = $params['id'] ?? '';
        $search = $params['search'] ?? '';
        $length = $params['length'] ?? 100;
        $start = $params['start'] ?? 0;
        $draw = $params['draw'] ?? 1;

        $sql =
            "SELECT 
                t.eventtype,
                td.trandetailid,
                td.productid,
                p.productname,
                td.amount as qty,
                td.description,
                COALESCE(del.sent, 0) as sent,
                td.amount - COALESCE(del.sent, 0) as remain
            
            FROM trans t
            LEFT JOIN trandetails td ON td.tranid = t.tranid 
            LEFT JOIN products p ON p.productid = td.productid
            LEFT JOIN (
                SELECT 
                    d.refid,
                    tdi.productid,
                    COALESCE(SUM(tdi.amount + tdi.amount2), 0) as sent
                FROM trandetails tdi 
                LEFT JOIN trans d ON d.tranid = tdi.tranid
                WHERE d.trantype = 'sales/delivery'
                AND d.status <> '10'  
                AND tdi.status <> '10'
                GROUP BY d.refid, tdi.productid
            ) as del ON del.refid = t.tranid AND del.productid = td.productid

            WHERE t.tranid = '$id' AND td.status <> 10
            AND t.status <> 10
            AND t.trantype ='sales/order'
        ";

        if (!empty($search)) {
            $escapedSearch = str_replace("'", "''", $search);
            $sql .= " AND p.productname ILIKE '%$escapedSearch%'";
        }

        $sql .= "
        GROUP BY td.trandetailid, td.productid, t.eventtype, p.productname, td.amount, del.sent
        ORDER BY td.ord ASC
        LIMIT " . (int) $length . " OFFSET " . (int) $start . "
         ";

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        // echo $sql;exit;

        $countSql = "
        SELECT COUNT(td.*)
        FROM trans t
        LEFT JOIN trandetails td ON td.tranid = t.tranid
        WHERE t.tranid = '$id' AND td.status <> 10
         ";

        $recordsTotal = (int) Yii::$app->db->createCommand($countSql)->queryScalar();

        $recordsFiltered = $recordsTotal;
        if (!empty($search)) {
            $recordsFiltered = count($data);
        }

        return [
            'data' => $data ?: [],
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'pagination' => [
                'more' => ($length + $start) < $recordsTotal,
            ],
        ];
    }
    public function actionGetpo()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->request->queryParams;

        $id = $params['refid'] ?? '';
        $length = $params['length'] ?? 100;
        $start = $params['start'] ?? 0;
        $draw = $params['draw'] ?? 1;

        $sql =
            "SELECT po.*,
            c.contact_name,
            c.jobcompany,
            u.name
            FROM trans po
            LEFT JOIN contacts c ON c.contact_id = po.contact_id
            LEFT JOIN users u ON u.userid::text = po.createdby
            WHERE po.refid = '$id' AND po.trantype = 'purchase/order' 
            AND po.status <> 10
        ";

        $sql .=
            "ORDER BY po.trandate DESC
             LIMIT " . (int) $length . " OFFSET " . (int) $start . "
         ";

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        // echo $sql;exit;

        $countSql = "
        SELECT COUNT(*)
        FROM trans po
        WHERE po.refid = '$id' AND po.trantype = 'purchase/order' AND po.status <> 10
         ";

        $recordsTotal = (int) Yii::$app->db->createCommand($countSql)->queryScalar();

        $recordsFiltered = $recordsTotal;
        if (!empty($search)) {
            $recordsFiltered = count($data);
        }

        return [
            'data' => $data ?: [],
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'pagination' => [
                'more' => ($length + $start) < $recordsTotal,
            ],
        ];
    }
    public function actionLoadreturn()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->request->queryParams;

        $id = $params['id'] ?? '';
        $search = $params['search'] ?? '';
        $length = $params['length'] ?? 100;
        $start = $params['start'] ?? 0;
        $draw = $params['draw'] ?? 1;

        $sql =
            "SELECT 
            p.productname,
            main_so.productid,
            COALESCE(del.total_milik, 0) AS sent_milik,
            COALESCE(del.total_pinjam, 0) AS sent_pinjam,
            (COALESCE(ret.return_milik, 0) + COALESCE(retpd.return_milik, 0)) AS return_milik,
            (COALESCE(ret.return_pinjam, 0) + COALESCE(retpd.return_pinjam, 0)) AS return_pinjam,
            (COALESCE(del.total_milik, 0) - (COALESCE(ret.return_milik, 0) + COALESCE(retpd.return_milik, 0))) AS remain_milik,
            (COALESCE(del.total_pinjam, 0) - (COALESCE(ret.return_pinjam, 0) + COALESCE(retpd.return_pinjam, 0))) AS remain_pinjam

            FROM (
                    SELECT td.productid, MIN(td.ord) AS min_ord
                    FROM trandetails td
                    INNER JOIN trans t ON t.tranid = td.tranid
                    WHERE t.refid = '$id' 
                    AND t.trantype = 'sales/delivery'
                    AND t.status <> 10
                    AND td.status <> 10
                    GROUP BY td.productid
                ) main_so

            INNER JOIN products p ON p.productid = main_so.productid
            
            LEFT JOIN (
                SELECT 
                    tv.productid,
                    COUNT(tv.tranvariantid) FILTER (WHERE tv.status = 1) AS total_milik,
                    COUNT(tv.tranvariantid) FILTER (WHERE tv.status = 0) AS total_pinjam
                FROM tranvariants tv
                INNER JOIN trans t ON t.tranid = tv.refid AND t.trantype = 'sales/delivery'
                WHERE t.refid = '$id' 
                AND t.status <> 10 AND tv.type = '2'
                AND tv.status <> 10 
                GROUP BY tv.productid
            ) del ON del.productid = main_so.productid

            LEFT JOIN (
                SELECT
                    tdr.productid,
                    SUM(tdr.amount) AS return_milik,
                    SUM(tdr.amount2) AS return_pinjam
                FROM trandetails tdr
                INNER JOIN trans r ON r.tranid = tdr.tranid
                WHERE r.refid = '$id'
                AND r.trantype = 'sales/return'
                AND r.status <> 10
                AND tdr.status <> 10
                GROUP BY tdr.productid
            ) ret ON ret.productid = main_so.productid

            LEFT JOIN (
                SELECT
                    tdr.productid,
                    SUM(tdr.amount) AS return_milik,
                    SUM(tdr.amount2) AS return_pinjam
                FROM trandetails tdr
                INNER JOIN trans r ON r.tranid = tdr.tranid
                WHERE r.refid = '$id'
                AND r.trantype = 'purchase/delivery'
                AND r.status <> 10 AND r.reftype = 'sales/order'
                AND tdr.status <> 10
                GROUP BY tdr.productid
            ) retpd ON retpd.productid = main_so.productid

        ";

        $sql .= "
        ORDER BY main_so.min_ord ASC
        LIMIT " . (int) $length . " OFFSET " . (int) $start . "
         ";
        //  echo $sql;exit;

        $rawData = Yii::$app->db->createCommand($sql)->queryAll();

        $filteredData = array_filter($rawData, function ($item) {
            return (int) $item['remain_milik'] > 0 || (int) $item['remain_pinjam'] > 0;
        });

        $data = array_values($filteredData);

        $countSql = "
        SELECT COUNT(DISTINCT td.productid)
        FROM trandetails td
        INNER JOIN trans t ON t.tranid = td.tranid
        WHERE t.refid = '$id'
          AND t.trantype = 'sales/delivery'
          AND t.status = 1 AND td.status <> 10
        ";

        $recordsTotal = (int) Yii::$app->db->createCommand($countSql)->queryScalar();
        $recordsFiltered = count($data);

        return [
            'data' => $data ?: [],
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'pagination' => [
                'more' => ($length + $start) < $recordsTotal,
            ],
        ];
    }
    public function actionLoadscan()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->request->queryParams;
        $id = $params['id'] ?? '';
        $search = $params['search'] ?? '';
        $length = $params['length'] ?? 100;
        $start = $params['start'] ?? 0;
        $draw = $params['draw'] ?? 1;
        $type = $_GET['type'] ?? '';
        $reftype2 = $_GET['reftype2'] ?? '';

        $join = "";
        if ($type == '1' && $reftype2 == 'purchase/request') {
            $join =
                "LEFT JOIN (
            SELECT B.tranid as tranid, A.productid, B.tranid as reftranid, 
            COALESCE(SUM(A.remain2), 0) as amount_outstock,
            COALESCE(SUM(A.amount2), 0) as amount2_outstock
            FROM trandetails A
            INNER JOIN trans B ON A.tranid=B.tranid AND coalesce(B.status,0) = 1
            AND coalesce(A.status,0) <> 10 AND B.trantype='purchase/delivery'
            GROUP BY B.tranid, A.productid, B.tranid
            ) OT ON OT.reftranid = r.tranid AND OT.productid = td.productid

            LEFT JOIN (
                SELECT 
                    td.trandetailid,
                    td.productid,
                    COUNT(v.variantid) AS total_variant
                FROM variants v
                INNER JOIN trandetails td ON td.productid = v.productid
                INNER JOIN trans t ON t.tranid = td.tranid AND t.tranid = v.tranid
                WHERE coalesce(v.status,0) <> 10
                AND coalesce(t.status,0) <> 10
                AND coalesce(td.status,0) <> 10
                AND v.tranid IS NOT NULL
                GROUP BY td.trandetailid, td.productid
            ) AS TV 
            ON TV.trandetailid = td.trandetailid
            AND TV.productid = td.productid
            ";
        } else if ($type == '1' && $reftype2 == 'sales/order') {
            $extra_cols = "
                pending_scan.so_tranno,
                pending_scan.pending_barcodes as pending_barcodes,
                pending_scan.product_unreturn as product_unreturn,
            ";

            $join =
                " LEFT JOIN (
                SELECT 
                    B.tranid, 
                    B.tranno, 
                    B.refid, 
                    A.productid, 
                    COALESCE(SUM(A.amount), 0) as amount_outstock,
                    COALESCE(SUM(A.amount2), 0) as amount2_outstock
                FROM trandetails A
                INNER JOIN trans B ON A.tranid = B.tranid 
                WHERE coalesce(B.status,0) = 1 
                AND coalesce(A.status,0) <> 10 
                AND B.trantype = 'purchase/delivery'
                GROUP BY B.tranid, B.tranno, B.refid, A.productid
            ) OT ON OT.tranid = r.tranid AND OT.productid = td.productid

            LEFT JOIN (
                SELECT 
                    td.trandetailid,
                    td.productid,
                    COUNT(tv.tranvariantid) AS total_variant
                FROM tranvariants tv
                INNER JOIN trandetails td ON td.trandetailid = tv.trandetailid
                INNER JOIN trans t ON t.tranid = td.tranid 
                WHERE coalesce(tv.status,0) <> 10
                AND coalesce(t.status,0) <> 10
                AND tv.refid IS NOT NULL
                AND tv.type = '3'
                GROUP BY td.trandetailid, td.productid
            ) AS TV ON TV.trandetailid = td.trandetailid
            AND TV.productid = td.productid

             LEFT JOIN (
                SELECT 
                    tv_out.productid,
                    p.productname AS product_unreturn,
                    so.tranno AS so_tranno,
                    so.tranid AS so_tranid,
                    STRING_AGG(DISTINCT tv_out.barcode, ' / ') AS pending_barcodes
                FROM tranvariants tv_out
                INNER JOIN trans tr_del ON tr_del.tranid = tv_out.refid 
                LEFT JOIN products p ON p.productid = tv_out.productid
                INNER JOIN trans so ON so.tranid = tr_del.refid

                WHERE tv_out.type = '2' AND COALESCE(tv_out.status, 0) <> 10
                AND tr_del.status <> 10 AND tr_del.trantype ='sales/delivery'
                AND so.trantype = 'sales/order' AND so.status <> 10
            
                AND NOT EXISTS (
                    SELECT 1 
                    FROM tranvariants tv_in
                    INNER JOIN trans tr_ret ON tr_ret.tranid = tv_in.refid 
                        AND tr_ret.status <> 10 AND tr_ret.trantype IN ('sales/return', 'purchase/delivery')
                    WHERE tv_in.type = '3'
                        AND COALESCE(tv_in.status, 0) <> 10
                        AND COALESCE(tr_ret.status, 0) <> 10
                        AND tv_in.barcode = tv_out.barcode
                        AND tr_ret.refid = tr_del.refid
                )

                GROUP BY tv_out.productid, so.tranno, p.productname, so.tranid
                 HAVING STRING_AGG(DISTINCT tv_out.barcode, ' / ') IS NOT NULL
            ) AS pending_scan 
                ON pending_scan.productid = td.productid 
                AND td.status <> 10 AND pending_scan.so_tranid = r.refid
          ";
        } else if ($type == '2') {
            $join =
                "LEFT JOIN (
                SELECT B.tranid as tranid, A.productid,
                COALESCE(SUM(A.amount), 0)  as amount_outstock,
                COALESCE(SUM(A.amount2), 0)  as amount2_outstock
                FROM trandetails A
                INNER JOIN trans B ON A.tranid = B.tranid AND coalesce(B.status, 0) = 1
                AND coalesce(A.status, 0) <> 10 AND B.trantype = 'sales/delivery'
                GROUP BY B.tranid, A.productid
            ) OT ON OT.tranid = r.tranid AND OT.productid = td.productid

            LEFT JOIN (
                SELECT 
                    td.trandetailid,
                    td.productid,
                    COUNT(tv.tranvariantid) AS total_variant
                FROM tranvariants tv
                INNER JOIN trandetails td ON td.trandetailid = tv.trandetailid
                INNER JOIN trans t ON t.tranid = td.tranid 
                WHERE coalesce(tv.status, 0) <> 10
                AND coalesce(t.status, 0) <> 10
                AND tv.refid IS NOT NULL
                AND tv.type = '2'
                GROUP BY td.trandetailid, td.productid
            ) AS TV 
            ON TV.trandetailid = td.trandetailid
            AND TV.productid = td.productid
          ";

        } else if ($type == '3') {
            $extra_cols = "
                pending_scan.so_tranno,
                pending_scan.pending_barcodes as pending_barcodes,
                pending_scan.product_unreturn as product_unreturn,
            ";

            $join =
                " LEFT JOIN (
                SELECT 
                    B.tranid, 
                    B.tranno, 
                    B.refid, 
                    A.productid, 
                    COALESCE(SUM(A.amount), 0) as amount_outstock,
                    COALESCE(SUM(A.amount2), 0) as amount2_outstock
                FROM trandetails A
                INNER JOIN trans B ON A.tranid = B.tranid 
                WHERE coalesce(B.status,0) = 1 
                AND coalesce(A.status,0) <> 10 
                AND B.trantype = 'sales/return'
                GROUP BY B.tranid, B.tranno, B.refid, A.productid
            ) OT ON OT.tranid = r.tranid AND OT.productid = td.productid

           LEFT JOIN (
                SELECT 
                    td.trandetailid,
                    td.productid,
                    COUNT(tv.tranvariantid) AS total_variant
                FROM tranvariants tv
                INNER JOIN trandetails td ON td.trandetailid = tv.trandetailid
                INNER JOIN trans t ON t.tranid = td.tranid 
                WHERE coalesce(tv.status,0) <> 10
                AND coalesce(t.status,0) <> 10
                AND tv.refid IS NOT NULL
                AND tv.type = '3'
                GROUP BY td.trandetailid, td.productid
            ) AS TV ON TV.trandetailid = td.trandetailid
            AND TV.productid = td.productid

             LEFT JOIN (
                SELECT 
                    tv_out.productid,
                    p.productname AS product_unreturn,
                    so.tranno AS so_tranno,
                    so.tranid AS so_tranid,
                    STRING_AGG(DISTINCT tv_out.barcode, ' / ') AS pending_barcodes
                FROM tranvariants tv_out
                INNER JOIN trans tr_del ON tr_del.tranid = tv_out.refid 
                LEFT JOIN products p ON p.productid = tv_out.productid
                INNER JOIN trans so ON so.tranid = tr_del.refid

                WHERE tv_out.type = '2' AND COALESCE(tv_out.status, 0) <> 10
                AND tr_del.status <> 10 AND tr_del.trantype ='sales/delivery'
                AND so.trantype = 'sales/order' AND so.status <> 10
            
                AND NOT EXISTS (
                    SELECT 1 
                    FROM tranvariants tv_in
                    INNER JOIN trans tr_ret ON tr_ret.tranid = tv_in.refid 
                        AND tr_ret.status <> 10 
                        AND tr_ret.trantype IN ('sales/return', 'purchase/delivery')
                    WHERE tv_in.type = '3'
                        AND COALESCE(tv_in.status, 0) <> 10
                        AND COALESCE(tr_ret.status, 0) <> 10
                        AND tv_in.barcode = tv_out.barcode
                        AND tr_ret.refid = tr_del.refid
                )

                GROUP BY tv_out.productid, so.tranno, p.productname, so.tranid
                 HAVING STRING_AGG(DISTINCT tv_out.barcode, ' / ') IS NOT NULL
            ) AS pending_scan 
                ON pending_scan.productid = td.productid 
                AND td.status <> 10 AND pending_scan.so_tranid = r.refid
          ";
        }
        $sql =
            "SELECT 
                MAX(r.tranid) AS tranid,
                MAX(r.refid) AS refid,
                MAX(td.trandetailid) AS trandetailid,
                td.productid,
                MAX(p.productname) AS productname,
                $extra_cols
                MAX(coalesce(OT.amount_outstock, 0)) AS sent_milik,
                MAX(coalesce(OT.amount2_outstock, 0)) AS sent_pinjam,
                MAX(coalesce(OT.amount_outstock, 0) + coalesce(OT.amount2_outstock, 0)) AS senttotal,
                MAX(coalesce(TV.total_variant, 0)) AS total_variant,
                MAX(coalesce(OT.amount_outstock, 0) + coalesce(OT.amount2_outstock, 0) - coalesce(TV.total_variant, 0)) AS remain

            FROM trandetails td
            INNER JOIN trans r ON r.tranid = td.tranid AND coalesce(r.status,0) <> 10
            LEFT JOIN products p ON p.productid = td.productid
            $join
            WHERE td.tranid = '$id'
            AND coalesce(td.status, 0) <> 10
            AND coalesce(r.status, 0) <> 10

           GROUP BY td.productid " . (!empty($extra_cols) ? ", pending_scan.so_tranno, pending_scan.pending_barcodes, 
           pending_scan.product_unreturn" : "") . "
          ";

        if (!empty($search)) {
            $escapedSearch = str_replace("'", "''", $search);
            $sql .= " AND p.productname ILIKE '%$escapedSearch%'";
        }

        $sql .=
            "ORDER BY MAX(td.ord) ASC
        LIMIT " . (int) $length . " OFFSET " . (int) $start . "
        ";
        // echo $sql;exit;

        $data = Yii::$app->db->createCommand($sql)->queryAll();

        $countSql = "
            SELECT COUNT(DISTINCT td.productid)
            FROM trans t
            LEFT JOIN trandetails td ON td.tranid = t.tranid
            WHERE t.tranid = '$id' AND td.status <> 10
        ";

        $recordsTotal = (int) Yii::$app->db->createCommand($countSql)->queryScalar();

        $recordsFiltered = $recordsTotal;
        if (!empty($search)) {
            $recordsFiltered = count($data);
        }

        return [
            'data' => $data ?: [],
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'pagination' => [
                'more' => ($length + $start) < $recordsTotal,
            ],
        ];
    }
    public function actionLoadstockin()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $params = Yii::$app->request->queryParams;
        $id = $params['id'] ?? '';
        $search = $params['search'] ?? '';
        $length = $params['length'] ?? 100;
        $start = $params['start'] ?? 0;
        $draw = $params['draw'] ?? 1;

        $sql =
            "SELECT 
                td.trandetailid,
                PD.refid,
                td.productid,
                r.eventtype,
                p.productname,
                coalesce(PO.amount_po,0) AS amount_po,
                coalesce(PD.amount_in,0) AS amount_in,
                coalesce(PO.amount_po,0) - coalesce(PD.amount_in,0) AS remain

            FROM trandetails td
            INNER JOIN trans r ON r.tranid = td.tranid AND coalesce(r.status,0) <> 10

			LEFT JOIN (
			SELECT B.tranid as tranid, A.productid, SUM(A.amount)  as amount_po
			FROM trandetails A
			INNER JOIN trans B ON A.tranid=B.tranid AND coalesce(B.status,0) = 1
			AND coalesce(A.status,0) <> 10 AND B.trantype='purchase/order'
			GROUP BY B.tranid, A.productid
			) PO ON PO.tranid = td.tranid AND PO.productid = td.productid

			LEFT JOIN (
			SELECT B.refid as tranid, B.tranid as refid, A.productid, SUM(A.remain2)  as amount_in
			FROM trandetails A
			INNER JOIN trans B ON A.tranid=B.tranid AND coalesce(B.status,0) =1
			AND coalesce(A.status,0) <> 10 AND B.trantype='purchase/delivery'
			GROUP BY B.refid, B.tranid, A.productid
			) PD ON PD.tranid = td.tranid AND PD.productid = td.productid

            LEFT JOIN products p ON p.productid = td.productid
            WHERE td.tranid = '$id'
            AND coalesce(td.status,0) <> 10
            AND coalesce(r.status,0) <> 10
            AND td.productid IS NOT NULL AND td.productid <> ''         
             ";

        if (!empty($search)) {
            $sql .= " AND p.productname ILIKE '%$search%'";
        }

        $sql .=
            "-- GROUP BY td.trandetailid, td.productid, p.productname, td.amount, td.amount2
        ORDER BY td.trandetailid ASC
        LIMIT " . (int) $length . " OFFSET " . (int) $start . "
         ";
        // echo $sql;

        $data = Yii::$app->db->createCommand($sql)->queryAll();

        $countSql = "
        SELECT COUNT(td.*)
        FROM trans t
        LEFT JOIN trandetails td ON td.tranid = t.tranid
        WHERE t.tranid = '$id' AND td.status <> 10
         ";

        $recordsTotal = (int) Yii::$app->db->createCommand($countSql)->queryScalar();

        $recordsFiltered = $recordsTotal;
        if (!empty($search)) {
            $recordsFiltered = count($data);
        }

        return [
            'data' => $data ?: [],
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'pagination' => [
                'more' => ($length + $start) < $recordsTotal,
            ],
        ];
    }
    public function actionDeletereturn($id)
    {
        $model = Trandetail::findOne($id);

        if ($model == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $model->status = 10;
        if ($model->save(false)) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['success' => true, 'pesan' => 'Data Berhasil Dihapus'];
            }
            return $this->redirect(['index']);
        } else {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['success' => false, 'pesan' => 'Gagal menghapus data'];
            }
            return $this->redirect(['index']);
        }
    }
    public function actionCreateitem()
    {
        $modelvariants = new Tranvariants;

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            // var_dump($post);die;

            $modelvariants->load($post);
            $type = $post['Tranvariants']['type'] ?? null;
            // $details = $post['Tranvariants'] ?? [];
            // var_dump($type);die;
            $trandetail = $post['Trandetail'] ?? [];

            $trandetailrow = null;
            foreach ($trandetail as $row) {
                if (!empty($row['barcode'])) {
                    $trandetailrow = $row;
                    break;
                }
            }

            if (!$trandetailrow) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'pesan' => 'Barcode tidak terbaca atau data scan kosong.'
                ];
            }

            $transaction = Yii::$app->db->beginTransaction();

            try {
                // var_dump($trandetailrow['variantid']); exit; 
                $isexist = '';
                if ($type == '2') {
                    $isexist =
                        "SELECT COUNT(*) as count 
                            FROM tranvariants A 
                            JOIN trans B ON A.refid = B.tranid -- delivery
                            LEFT JOIN trans C ON B.refid = C.tranid -- order
                            WHERE A.type = '2' AND A.status <> 10 AND B.status <> 10
                            AND C.statuspro IN ('1', '5', '10') AND C.status <> 10
                            AND NOT EXISTS (
                                SELECT 1 
                                FROM tranvariants A2 
                                JOIN trans B2 ON A2.refid = B2.tranid -- return
                                WHERE A2.variantid = A.variantid 
                                AND A2.type = '3' AND A2.status <> 10
                                AND B2.status <> 10
                                AND B2.refid = B.refid -- order
                            )
                        AND A.variantid = '" . $trandetailrow['variantid'] . "' ";

                    $sumDel =
                        "SELECT SUM((A.amount + A.amount2))
                    FROM trandetails A
                    LEFT JOIN trans B ON A.tranid = B.tranid -- delivery
                    WHERE A.productid = '" . $trandetailrow['productid'] . "'
                    AND B.status <> 10 AND B.trantype = 'sales/delivery'
                    AND B.tranid = '" . $modelvariants->refid . "'";

                    $countScan =
                        "SELECT COUNT(*) FROM tranvariants A
                    LEFT JOIN trans B ON A.refid = B.tranid -- delivery
                    WHERE A.type = '2' AND A.status <> 10 AND B.status <> 10
                    AND A.productid = '" . $trandetailrow['productid'] . "'
                    AND B.tranid = '" . $modelvariants->refid . "'";

                    $totalDel =
                        "SELECT SUM((A.amount + A.amount2))
                    FROM trandetails A
                    LEFT JOIN trans B ON A.tranid = B.tranid -- delivery
                    WHERE B.status <> 10 AND B.trantype = 'sales/delivery'
                    AND B.tranid = '" . $modelvariants->refid . "'";

                    $totalScan =
                        "SELECT COUNT(*) FROM tranvariants A
                    LEFT JOIN trans B ON A.refid = B.tranid -- delivery
                    WHERE A.type = '2' AND A.status <> 10 AND B.status <> 10
                    AND B.tranid = '" . $modelvariants->refid . "'";
                    // var_dump($sumDel); exit;

                } else if ($type == '3') {
                    $getSOId =
                        "SELECT refid FROM trans WHERE tranid = '" . $modelvariants->refid . "' AND status <> 10";

                    $soId = Yii::$app->db->createCommand($getSOId)->queryScalar();

                    $checkBarcode =
                        "SELECT COUNT(*) FROM tranvariants A
                        LEFT JOIN trans B ON A.refid = B.tranid -- delivery
                        LEFT JOIN trans C ON B.refid = C.tranid -- Order
                    WHERE A.refid = B.tranid AND B.refid = '" . $soId . "'
                    AND A.type = '2' AND B.status <> 10
                    AND A.status <> 10 AND C.statuspro IN ('1', '5', '10')
                    AND A.barcode = '" . $trandetailrow['barcode'] . "'";
                    // echo ($soId);exit;

                    $existsInType2 = Yii::$app->db->createCommand($checkBarcode)->queryScalar();

                    if ($existsInType2 == 0) {
                        throw new Exception('Barcode tidak ditemukan dalam pengiriman untuk Order ini.');
                    }

                    $isexist = "SELECT COUNT(*) as count FROM tranvariants A 
                            LEFT JOIN trans B on A.refid = B.tranid -- Return
                            LEFT JOIN trans C on B.refid = C.tranid -- Order
                            WHERE A.type ='3' AND B.status <> 10 
                            AND A.status <> 10 AND C.statuspro IN ('1', '5', '10')
                            AND A.refid = B.tranid AND B.refid = '" . $soId . "'
                            AND A.variantid = '" . $trandetailrow['variantid'] . "' ";

                    $sumRet =
                        "SELECT SUM((A.amount + A.amount2))
                    FROM trandetails A
                    LEFT JOIN trans B ON A.tranid = B.tranid -- return
                    WHERE A.productid = '" . $trandetailrow['productid'] . "'
                    AND B.status <> 10 AND B.trantype = 'sales/return'
                    AND B.tranid = '" . $modelvariants->refid . "'";

                    $countScanRet =
                        "SELECT COUNT(*) FROM tranvariants A
                    LEFT JOIN trans B ON A.refid = B.tranid -- return
                    WHERE A.type = '3' AND A.status <> 10 AND B.status <> 10
                    AND A.productid = '" . $trandetailrow['productid'] . "'
                    AND B.tranid = '" . $modelvariants->refid . "'";

                    $totalRet =
                        "SELECT SUM((A.amount + A.amount2))
                    FROM trandetails A
                    LEFT JOIN trans B ON A.tranid = B.tranid -- return
                    WHERE B.status <> 10 AND B.trantype = 'sales/return'
                    AND B.tranid = '" . $modelvariants->refid . "'";

                    $totalScanRet =
                        "SELECT COUNT(*) FROM tranvariants A
                    LEFT JOIN trans B ON A.refid = B.tranid -- return
                    WHERE A.type = '3' AND A.status <> 10 AND B.status <> 10
                    AND B.tranid = '" . $modelvariants->refid . "'";
                } else if ($type == '1') {
                    $getSOId =
                        "SELECT refid FROM trans WHERE tranid = '" . $modelvariants->refid . "' AND status <> 10";

                    $soId = Yii::$app->db->createCommand($getSOId)->queryScalar();

                    $checkBarcode =
                        "SELECT COUNT(*) FROM tranvariants A
                        LEFT JOIN trans B ON A.refid = B.tranid -- delivery
                        LEFT JOIN trans C ON B.refid = C.tranid -- Order
                    WHERE A.refid = B.tranid AND B.refid = '" . $soId . "'
                    AND A.type = '2' AND B.status <> 10
                    AND A.status <> 10 AND C.statuspro IN ('1', '5', '10')
                    AND A.barcode = '" . $trandetailrow['barcode'] . "'";
                    // echo ($soId);exit;

                    $existsInType2 = Yii::$app->db->createCommand($checkBarcode)->queryScalar();

                    if ($existsInType2 == 0) {
                        throw new Exception('Barcode tidak ditemukan dalam pengiriman untuk Order ini.');
                    }

                    $isexist = "SELECT COUNT(*) as count FROM tranvariants A 
                            LEFT JOIN trans B on A.refid = B.tranid -- Return
                            LEFT JOIN trans C on B.refid = C.tranid -- Order
                            WHERE A.type ='3' AND B.status <> 10 
                            AND A.status <> 10 AND C.statuspro IN ('1', '5', '10')
                            AND A.refid = B.tranid AND B.refid = '" . $soId . "'
                            AND A.variantid = '" . $trandetailrow['variantid'] . "' ";

                    $sumIn =
                        "SELECT SUM((A.amount + A.amount2))
                    FROM trandetails A
                    LEFT JOIN trans B ON A.tranid = B.tranid -- return
                    WHERE A.productid = '" . $trandetailrow['productid'] . "'
                    AND B.status <> 10 AND B.trantype = 'purchase/delivery'
                    AND B.tranid = '" . $modelvariants->refid . "'";

                    $countScanIn =
                        "SELECT COUNT(*) FROM tranvariants A
                    LEFT JOIN trans B ON A.refid = B.tranid -- return
                    WHERE A.type = '3' AND A.status <> 10 AND B.status <> 10
                    AND A.productid = '" . $trandetailrow['productid'] . "'
                    AND B.tranid = '" . $modelvariants->refid . "'";

                    $totalIn =
                        "SELECT SUM((A.amount + A.amount2))
                    FROM trandetails A
                    LEFT JOIN trans B ON A.tranid = B.tranid -- return
                    WHERE B.status <> 10 AND B.trantype = 'purchase/delivery'
                    AND B.tranid = '" . $modelvariants->refid . "'";

                    $totalScanIn =
                        "SELECT COUNT(*) FROM tranvariants A
                    LEFT JOIN trans B ON A.refid = B.tranid -- return
                    WHERE A.type = '3' AND A.status <> 10 AND B.status <> 10
                    AND B.tranid = '" . $modelvariants->refid . "'";

                } else {
                    $isexist = "SELECT COUNT(*) as count FROM tranvariants A WHERE 1 = 0";
                }
                // echo ($isexist);exit;
                $data = Yii::$app->db->createCommand($isexist)->queryAll();
                // var_dump($data); exit;

                if ($type == '3' && $data[0]['count'] > 0) {
                    throw new Exception('Item telah direturn.');
                } else if ($type == '1' && $data[0]['count'] > 0) {
                    throw new Exception('Item telah diinputkan sebelumnya.');
                } else {
                    if ($data[0]['count'] > 0) {
                        throw new Exception('Item masih terikat transaksi sebelumnya (belum return).');
                    }
                }

                $variant = Variant::findOne($trandetailrow['variantid']);

                if (!$variant) {
                    throw new Exception('Variant tidak ditemukan.');
                }

                $tranvariant = new Tranvariants();
                $tranvariant->tranvariantid = Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();
                $tranvariant->trandetailid = $trandetailrow['trandetailid'];
                $tranvariant->variantid = $trandetailrow['variantid'] ?? null;
                $tranvariant->productid = $trandetailrow['productid'] ?? null;
                $tranvariant->barcode = $trandetailrow['barcode'] ?? null;
                $tranvariant->refid = $modelvariants->refid;
                $tranvariant->trandate = date('Y-m-d H:i:s');
                $tranvariant->status = 1;
                $tranvariant->type = ($type == '1') ? '3' : $type;
                $tranvariant->from_locationid = $variant->locationid ?? null;

                if ($variant) {
                    if ($type == '2') {
                        $variant->locationid = 'location.3';
                        $variant->stock = 1;

                    } else if ($type == '3' || $type == '1') {
                        $lastDelivery = Tranvariants::find()
                            ->where(['variantid' => $variant->variantid, 'type' => 2])
                            ->andWhere(['<>', 'status', 10])
                            ->orderBy(['trandate' => SORT_DESC])
                            ->one();

                        $lokasiAwal = ($lastDelivery && !empty($lastDelivery->from_locationid))
                            ? $lastDelivery->from_locationid
                            : 'location.1';

                        $variant->locationid = $lokasiAwal;
                    }

                    if (!$variant->save()) {
                        $errors = $variant->getFirstErrors();
                        $fieldName = array_key_first($errors);
                        $errorMessage = reset($errors);
                        throw new Exception("Field [$fieldName] error: $errorMessage");
                    }
                } else {
                    throw new Exception('Variant tidak ditemukan.');
                }

                if (!$tranvariant->save()) {
                    throw new Exception(implode(', ', $tranvariant->getFirstErrors()));
                }

                if ($type == '2') {
                    $sumDelValue = Yii::$app->db->createCommand($sumDel)->queryScalar();
                    $countScanValue = Yii::$app->db->createCommand($countScan)->queryScalar();
                    $totalDelVal = Yii::$app->db->createCommand($totalDel)->queryScalar();
                    $totalScanVal = Yii::$app->db->createCommand($totalScan)->queryScalar();

                    $finished = ($totalDelVal == $totalScanVal && $totalDelVal > 0);

                } else if ($type == '3') {
                    $sumRetValue = Yii::$app->db->createCommand($sumRet)->queryScalar();
                    $countScanRetValue = Yii::$app->db->createCommand($countScanRet)->queryScalar();
                    $totalRetVal = Yii::$app->db->createCommand($totalRet)->queryScalar();
                    $totalScanRetVal = Yii::$app->db->createCommand($totalScanRet)->queryScalar();

                    $finished = ($totalRetVal == $totalScanRetVal && $totalRetVal > 0);
                } else if ($type == '1') {
                    $sumInValue = Yii::$app->db->createCommand($sumIn)->queryScalar();
                    $countScanInValue = Yii::$app->db->createCommand($countScanIn)->queryScalar();
                    $totalInVal = Yii::$app->db->createCommand($totalIn)->queryScalar();
                    $totalScanInVal = Yii::$app->db->createCommand($totalScanIn)->queryScalar();

                    $finished = ($totalInVal == $totalScanInVal && $totalInVal > 0);
                } else {
                    $finished = false;
                }

                $transaction->commit();

                $pesan = "Scan Berhasil! " . $trandetailrow['productname'];
                if ($type == '2') {
                    $pesan = $finished
                        ? "<b>Scan Berhasil! Semua Item Telah di Scan.</b><br><br>" .
                        "Silahkan Print Surat Jalan pada Menu Stock Out<br><br>" .
                        "Jika Ada Barang yang Tidak Terpakai, Silahkan Input Barang Kembali Pada Menu Return Stock<br><br>"
                        : "<b>Scan Berhasil! " . $trandetailrow['productname'] . "</b><br><br>" .
                        "Total Item: $sumDelValue<br><br>" .
                        "Sudah Scan: $countScanValue<br>";
                } else if ($type == '3') {
                    $pesan = $finished
                        ? "<b>Scan Berhasil! Semua Item Telah Direturn.</b><br><br>" .
                        "Jika Sudah Penarikan Barang, Silahkan Input Penarikan Barang Pada Menu Stock In<br><br>"
                        : "<b>Scan Berhasil! " . $trandetailrow['productname'] . "</b><br><br>" .
                        "Total Item: $sumRetValue<br><br>" .
                        "Sudah Scan: $countScanRetValue<br>";
                } else if ($type == '1') {
                    $pesan = $finished
                        ? "<b>Scan Berhasil! Semua Item Telah di Scan.</b><br><br>" .
                        "Jika Proyek Selesai, Silahkan Ubah Status Proyek Menjadi Finish pada Menu Stock In"
                        : "<b>Scan Berhasil! " . $trandetailrow['productname'] . "</b><br><br>" .
                        "Total Item: $sumInValue<br><br>" .
                        "Sudah Scan: $countScanInValue<br>";
                }

                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => true,
                    'finished' => $finished,
                    'pesan' => $pesan,
                ];

            } catch (Exception $e) {
                $transaction->rollBack();

                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'pesan' => $e->getMessage(),
                    'debug' => $e instanceof \yii\db\Exception ? $e->errorInfo : null,
                ];
            }
        }

        $modelvariants->trandate = date("Y-m-d H:i:s");
        $modeldetails = [];

        $form = 'delivery';

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax($form, [
                'modeldetails' => (empty($modeldetails)) ? [new Trandetail] : $modeldetails,
                'modelvariants' => $modelvariants,
                'isajax' => true,
            ]);
        }

        return $this->render($form, [
            'modeldetails' => (empty($modeldetails)) ? [new Trandetail] : $modeldetails,
            'modelvariants' => $modelvariants,
            'isajax' => false,
        ]);
    }
    public function actionCreatevariants()
    {
        $modelvariants = new Variant;

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            // var_dump($post);
            $modelvariants->load($post, 'Tranvariants');
            // var_dump($modelvariants->attributes);die;
            $targetId = $post['trandetailid'] ?? null;
            $trandetail = $post['Trandetail'] ?? [];
            $trandetailrow = null;

            foreach ($trandetail as $row) {
                if (isset($row['trandetailid']) && $row['trandetailid'] == $targetId && !empty($row['barcode'])) {
                    $trandetailrow = $row;
                    break;
                }
            }

            $transaction = Yii::$app->db->beginTransaction();

            try {

                $variant = new Variant();
                $variant->variantid = Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();
                $variant->productid = $trandetailrow['productid'] ?? null;
                $variant->barcode = $trandetailrow['barcode'] ?? null;
                $variant->refid = $trandetailrow['trandetailid'] ?? null;
                $variant->trandate = date('Y-m-d H:i:s');
                $variant->tranid = $modelvariants->refid;
                $variant->status = 1;
                $variant->locationid = 'location.1';
                $variant->condition = 'condition.1';

                if (!$variant->save()) {
                    $errors = $variant->getFirstErrors();
                    $fieldName = array_key_first($errors);
                    $errorMessage = reset($errors);

                    throw new Exception("Field [$fieldName] error: $errorMessage");
                }

                $sumIn =
                    "SELECT SUM(A.remain2)
                FROM trandetails A
                LEFT JOIN trans B ON A.tranid = B.tranid
                WHERE A.productid = '" . $trandetailrow['productid'] . "'
                AND B.status <> 10 AND B.trantype = 'purchase/delivery'
                AND B.tranid = '" . $modelvariants->refid . "'";
                // echo ($sumIn);exit;

                $countScanIn =
                    "SELECT COUNT(*) FROM variants A
                LEFT JOIN trandetails B ON A.refid = B.trandetailid -- in
                WHERE A.status <> 10 AND B.status <> 10 AND A.sku IS NULL
                AND A.productid = '" . $trandetailrow['productid'] . "'
                AND B.trandetailid = '" . $trandetailrow['trandetailid'] . "'";
                // echo ($countScanIn);exit;

                $totalIn =
                    "SELECT SUM(A.remain2)
                FROM trandetails A
                LEFT JOIN trans B ON A.tranid = B.tranid -- in
                WHERE B.status <> 10 AND B.trantype = 'purchase/delivery'
                AND B.tranid = '" . $modelvariants->refid . "'";
                // echo ($totalIn);exit;

                $totalScanIn =
                    "SELECT COUNT(*) FROM variants A
                LEFT JOIN trans B ON A.tranid = B.tranid -- in
                WHERE A.status <> 10 AND B.status <> 10 AND A.sku IS NULL
                AND B.tranid = '" . $modelvariants->refid . "'";
                // echo ($totalScanIn);exit;

                $sumInValue = Yii::$app->db->createCommand($sumIn)->queryScalar();
                $countScanInValue = Yii::$app->db->createCommand($countScanIn)->queryScalar();
                $totalInValue = Yii::$app->db->createCommand($totalIn)->queryScalar();
                $totalScanInValue = Yii::$app->db->createCommand($totalScanIn)->queryScalar();
                $finished = ($totalInValue == $totalScanInValue && $totalInValue > 0);

                $transaction->commit();

                $pesan = $finished
                    ? "<b>Scan Berhasil! Semua Item Telah di Scan.</b><br><br>" .
                    "Silahkan Ke Menu Stock Out untuk Input Barang Keluar<br><br>"
                    : "<b>Scan Berhasil! " . $trandetailrow['productname'] . "</b><br><br>" .
                    "Total Item: $sumInValue<br><br>" .
                    "Sudah Scan: $countScanInValue<br>";

                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => true,
                    'finished' => $finished,
                    'pesan' => $pesan
                ];
            } catch (Exception $e) {

                $transaction->rollBack();

                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'pesan' => $e->getMessage()
                ];
            }
        }

        $modelvariants->trandate = date("d/m/Y H:i");
        $modeldetails = [];

        $form = 'delivery';

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax($form, [
                'modeldetails' => (empty($modeldetails)) ? [new Trandetail] : $modeldetails,
                'modelvariants' => $modelvariants,
                'isajax' => true,
            ]);
        }

        return $this->render($form, [
            'modeldetails' => (empty($modeldetails)) ? [new Trandetail] : $modeldetails,
            'modelvariants' => $modelvariants,
            'isajax' => false,
        ]);
    }
    public function actionCekscan()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $refid = trim(Yii::$app->request->get('refid', ''));
        // var_dump($refid);die;

        if (!$refid) {
            return ['success' => false, 'pesan' => 'Refid tidak ditemukan.'];
        }

        $sql =
            "SELECT
                t.tranid,
                td.productid,
                p.productname, 
                (td.amount + td.amount2) AS total_qty,
                sub_tv.total_scan,
                ((td.amount + td.amount2) - sub_tv.total_scan) AS sisa
            FROM trandetails td
            LEFT JOIN trans t ON td.tranid = t.tranid
            LEFT JOIN products p ON p.productid = td.productid
            LEFT JOIN (
                SELECT trandetailid, COUNT(tranvariantid) AS total_scan
                FROM tranvariants
                WHERE type = '2' AND status <> 10 AND refid = '$refid'
                GROUP BY trandetailid
            ) sub_tv ON sub_tv.trandetailid = td.trandetailid

            WHERE t.tranid = '$refid' AND t.status <> 10 
            AND td.status <> 10 AND t.trantype = 'sales/delivery'
            GROUP BY td.productid, t.tranid, p.productname, td.amount, td.amount2, sub_tv.total_scan
            HAVING (td.amount + td.amount2) > COALESCE(sub_tv.total_scan, 0)
            ";

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        // echo $sql;exit;

        if (empty($data)) {
            return ['success' => true, 'finished' => true, 'pesan' => 'Semua item telah discan.'];
        }

        return [
            'success' => true,
            'finished' => false,
            'items' => array_map(function ($row) {
                return [
                    'productname' => $row['productname'],
                    'total_qty' => (int) $row['total_qty'],
                    'total_scan' => (int) $row['total_scan'],
                    'sisa' => (int) $row['total_qty'] - (int) $row['total_scan'],
                ];
            }, $data)
        ];

    }
    public function actionCekbarcode()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $productid = trim(Yii::$app->request->get('productid', ''));
        // var_dump($refid);die;

        if (!$productid) {
            return ['success' => false, 'pesan' => 'Product tidak ditemukan.'];
        }

        $sql =
            "SELECT
                p.productid,
                p.productname, 
                v.barcode,
                COALESCE(v.asetno, '') AS asetno,
                e.enumtext_id as locationid,
                c.enumtext_id as condition
            FROM variants v
            LEFT JOIN products p ON p.productid = v.productid
            LEFT JOIN enum e ON e.enumid = v.locationid AND e.enumtype = 'location'
            LEFT JOIN enum c ON c.enumid = v.condition AND c.enumtype = 'condition'
            WHERE v.productid = '$productid' AND v.status <> 10 AND v.sku IS NULL
            ORDER BY v.barcode ASC
            ";

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        // echo $sql;exit;

        if (empty($data)) {
            return ['success' => true, 'finished' => true, 'pesan' => 'Tidak ditemukan barcode untuk produk ini.'];
        }

        return [
            'success' => true,
            'finished' => false,
            'items' => array_map(function ($row) {
                return [
                    'productname' => $row['productname'] ?? '-',
                    'barcode' => $row['barcode'] ?? '-',
                    'asetno' => ($row['asetno'] !== '') ? $row['asetno'] : '-',
                    'locationid' => $row['locationid'] ?? '-',
                    'condition' => $row['condition'] ?? '-',
                ];
            }, $data)
        ];

    }
    protected function notValid($model, $modeldetails = [], $modelevents = [])
    {
        $errorMessages = [];

        if ($model->hasErrors()) {
            foreach ($model->getFirstErrors() as $field => $msg) {
                $errorMessages[] = "<b>Header:</b> " . $msg;
            }
        }

        foreach ($modeldetails as $i => $detail) {
            if ($detail->hasErrors()) {
                foreach ($detail->getFirstErrors() as $field => $msg) {
                    $errorMessages[] = "<b>Detail Baris " . ($i + 1) . ":</b> " . $msg;
                }
            }
        }

        foreach ($modelevents as $i => $event) {
            if ($event->hasErrors()) {
                foreach ($event->getFirstErrors() as $field => $msg) {
                    $errorMessages[] = "<b>Event Baris " . ($i + 1) . ":</b> " . $msg;
                }
            }
        }

        $pesanHtml = !empty($errorMessages)
            ? implode("<br/>", $errorMessages)
            : "Validasi gagal, silakan periksa kembali inputan Anda.";

        return [
            'success' => false,
            'pesan' => $pesanHtml
        ];
    }
    public function actionCreatepro()
    {
        $params = Yii::$app->request->queryParams;
        $module = $params['module'] ?? '';
        $type = $params['type'] ?? '';
        $moduleType = "$module/$type";

        $model = new Tran();
        $modeldetail = [new Trandetail];
        $modelevent = [new Tranevent];
        $modeleventcrews = [new Traneventcrew];

        if ($model->load(Yii::$app->request->post())) {
            // var_dump(json_encode(Yii::$app->request->post(), JSON_PRETTY_PRINT));die();

            $modeldetail = Model::createMultipleID(Trandetail::classname(), $modeldetail, 'trandetailid');
            Model::loadMultiple($modeldetail, Yii::$app->request->post());

            $modelevent = Model::createMultipleID(Tranevent::classname(), $modelevent, 'traneventid');
            Model::loadMultiple($modelevent, Yii::$app->request->post());
            // var_dump($modelevent);exit;

            $valid = $model->validate();
            $valid = Model::validateMultiple($modeldetail) && $valid;
            $valid = Model::validateMultiple($modelevent) && $valid;

            $id = \Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();

            if ($valid) {
                $model->tranid = $id;
                $model->trantype = $moduleType;

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $model->tranno = $model->nextNoTransaksi();

                    if ($flag = $model->save(false)) {

                        foreach ($modeldetail as $indexdetail => $modeldetailItem) {
                            if ($flag === false) {
                                break;
                            }
                            $modeldetailItem->tranid = $model->tranid;
                            $modeldetailItem->ord = $indexdetail + 1;

                            if (!($flag = $modeldetailItem->save(false))) {
                                break;
                            }
                        }

                        foreach ($modelevent as $indexdetail => $modeleventItem) { // Mengubah variabel penampung loop agar tidak bentrok
                            if ($flag === false) {
                                break;
                            }
                            $traneventid = \Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();
                            $modeleventItem->traneventid = $traneventid;
                            $modeleventItem->tranid = $model->tranid;
                            $modeleventItem->ord = $indexdetail + 1;

                            $listeventcrew = Yii::$app->request->post()['Tranevent'][$indexdetail]['Traneventcrew'] ?? null;

                            if (!($flag = $modeleventItem->save(false))) {
                                break;
                            }

                            if ($listeventcrew) {
                                foreach ($listeventcrew as $detaileventcrew) {
                                    $modeleventcrew = new Traneventcrew();
                                    $modeleventcrew->traneventid = $traneventid;
                                    $modeleventcrew->crewtypeid = $detaileventcrew['crewtypeid'];
                                    $modeleventcrew->crewid = $detaileventcrew['crewid'];
                                    $modeleventcrew->jobid = $detaileventcrew['jobid'];
                                    $modeleventcrew->fee = $detaileventcrew['fee'];
                                    $modeleventcrew->dinasid = $detaileventcrew['dinasid'];
                                    $modeleventcrew->dinasfee = $detaileventcrew['dinasfee'];

                                    if (!($flag = $modeleventcrew->save(false))) {
                                        break 2;
                                    }
                                }
                            }
                        }

                        if ($flag) {
                            $transaction->commit();

                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return ['success' => true, 'pesan' => 'Data Berhasil Disimpan', 'id' => $model->tranid, 'name' => $model->tranno];
                            }
                            return $this->redirect(['index', 'id' => $model->tranid]);
                        } else {
                            $transaction->rollBack();
                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return $this->notValid($model, $modeldetail, $modelevent);
                            }
                        }
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();

                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['success' => false, 'pesan' => $e];
                    }
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return $this->notValid($model, $modeldetail, $modelevent);
                }
            }
        }

        $tranNo = $model->nextNoTransaksi();
        $model->tranno = $tranNo;
        $model->trandate = date("d/m/Y");
        $model->tranduedate = date("d/m/Y");
        $model->setupdate = date("d/m/Y H:i");
        $model->withdrawaldate = date("d/m/Y H:i");
        $model->term = 1;
        $model->trantype = "$module/$type";

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_formpro', [
                'model' => $model,
                'modeldetails' => (empty($modeldetail)) ? [new Trandetail] : $modeldetail,
                'modelevents' => (empty($modelevent)) ? [new Tranevent] : $modelevent,
                'modeleventcrews' => (empty($modeleventcrews)) ? [new Traneventcrew] : $modeleventcrews,
                'isajax' => true,
            ]);
        } else {
            return $this->render('_formpro', [
                'model' => $model,
                'modeldetails' => (empty($modeldetail)) ? [new Trandetail] : $modeldetail,
                'modelevents' => (empty($modelevent)) ? [new Tranevent] : $modelevent,
                'modeleventcrews' => (empty($modeleventcrews)) ? [new Traneventcrew] : $modeleventcrews,
                'isajax' => false,
            ]);
        }
    }
    public function actionUpdatepro($id)
    {
        $model = Tran::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('Data tidak ditemukan.');
        }

        $modeldetails = $model->trandetails;
        $modelevents = $model->tranevents;

        if ($model->load(Yii::$app->request->post())) {

            $oldDetailIDs = ArrayHelper::map($modeldetails, 'trandetailid', 'trandetailid');
            $modeldetails = Model::createMultipleID(Trandetail::class, $modeldetails, 'trandetailid');
            Model::loadMultiple($modeldetails, Yii::$app->request->post());
            $deletedDetailIDs = array_diff(
                $oldDetailIDs,
                array_filter(ArrayHelper::map($modeldetails, 'trandetailid', 'trandetailid'))
            );

            $oldEventIDs = ArrayHelper::map($modelevents, 'traneventid', 'traneventid');
            $modelevents = Model::createMultipleID(Tranevent::class, $modelevents, 'traneventid');
            Model::loadMultiple($modelevents, Yii::$app->request->post());
            $deletedEventIDs = array_diff(
                $oldEventIDs,
                array_filter(ArrayHelper::map($modelevents, 'traneventid', 'traneventid'))
            );

            $valid = $model->validate();
            $valid = Model::validateMultiple($modeldetails) && $valid;
            $valid = Model::validateMultiple($modelevents) && $valid;

            if (!$valid) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $this->notValid($model, $modeldetails, $modelevents);
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (!$model->save(false)) {
                    throw new \Exception('Gagal menyimpan header transaksi.');
                }

                if (!empty($deletedDetailIDs)) {
                    Trandetail::deleteAll(['trandetailid' => $deletedDetailIDs]);
                }

                if (!empty($deletedEventIDs)) {
                    Traneventcrew::deleteAll(['traneventid' => $deletedEventIDs]);
                    Tranevent::deleteAll(['traneventid' => $deletedEventIDs]);
                }

                foreach ($modeldetails as $i => $detail) {
                    $detail->tranid = $model->tranid;
                    $detail->ord = $i + 1;

                    if (!$detail->save(false)) {
                        throw new \Exception('Gagal menyimpan detail transaksi.');
                    }
                }

                $allNewCrewIds = [];

                foreach ($modelevents as $i => $event) {

                    $isNewEvent = empty($event->traneventid);
                    if ($isNewEvent) {
                        $event->traneventid = Yii::$app->db
                            ->createCommand('select uuid_generate_v4()')
                            ->queryScalar();
                    }

                    $event->tranid = $model->tranid;
                    $event->ord = $i + 1;

                    if (!$event->save(false)) {
                        throw new Exception('Gagal menyimpan event.');
                    }

                    if (!$isNewEvent) {
                        Traneventcrew::deleteAll(['traneventid' => $event->traneventid]);
                    }

                    $crewList = Yii::$app->request->post()['Tranevent'][$i]['Traneventcrew'] ?? [];

                    foreach ($crewList as $crew) {

                        if (empty($crew['crewid'])) {
                            continue;
                        }

                        if (!in_array($crew['crewid'], $allNewCrewIds)) {
                            $allNewCrewIds[] = $crew['crewid'];
                        }

                        $crewModel = new Traneventcrew();
                        $crewModel->traneventid = $event->traneventid;
                        $crewModel->crewtypeid = $crew['crewtypeid'] ?? null;
                        $crewModel->crewid = $crew['crewid'];
                        $crewModel->fee = $crew['fee'] ?? 0;
                        $crewModel->jobid = $crew['jobid'] ?? null;
                        $crewModel->dinasid = $crew['dinasid'] ?? null;
                        $crewModel->dinasfee = $crew['dinasfee'] ?? null;

                        if (!$crewModel->save(false)) {
                            throw new \Exception('Gagal menyimpan crew event.');
                        }
                    }
                }

                $transaction->commit();

                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => true,
                    'pesan' => 'Data berhasil diperbarui',
                    'id' => $model->tranid,
                    'name' => $model->tranno,
                ];
            } catch (Exception $e) {
                $transaction->rollBack();

                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'pesan' => $e->getMessage(),
                    'file' => YII_DEBUG ? $e->getFile() : null,
                    'line' => YII_DEBUG ? $e->getLine() : null,
                ];
            }
        }

        $model->trandate = $model->trandate ? Yii::$app->formatter->asDate($model->trandate, 'dd/MM/yyyy') : null;
        $model->tranduedate = $model->tranduedate ? Yii::$app->formatter->asDate($model->tranduedate, 'dd/MM/yyyy') : null;
        $model->setupdate = $model->setupdate ? Yii::$app->formatter->asDateTime($model->setupdate, 'dd/MM/yyyy HH:mm') : null;
        $model->withdrawaldate = $model->withdrawaldate ? Yii::$app->formatter->asDateTime($model->withdrawaldate, 'dd/MM/yyyy HH:mm') : null;

        return $this->renderAjax('_formpro', [
            'model' => $model,
            'modeldetails' => empty($modeldetails) ? [new Trandetail()] : $modeldetails,
            'modelevents' => empty($modelevents) ? [new Tranevent()] : $modelevents,
            'isajax' => true,
        ]);
    }
    public function actionCreatetrack($id)
    {
        $db = Yii::$app->db;

        $checkType = $db->createCommand(
            "SELECT trantype FROM trans WHERE tranid = '$id'"
        )->queryOne();

        if (!$checkType) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['success' => false, 'pesan' => 'Transaksi tidak ditemukan'];
            }
            return $this->redirect(['sales/order']);
        }

        $parts = explode('/', $checkType['trantype']);
        $module = $parts[0] ?? 'purchase';
        $type = $parts[1] ?? 'request';
        $fullType = $checkType['trantype'];

        $extraFields = "";
        $joinSql = "";

        if ($fullType == 'purchase/request') {
            $extraFields = ", o.locations, o.coordinate, o.eventname, o.eventtype";
            $joinSql = " LEFT JOIN trans o ON t.refid = o.tranid AND o.trantype = 'sales/order' ";
        } else if ($fullType == 'purchase/order') {
            $extraFields = ", o.locations, o.coordinate, o.eventname, o.eventtype";
            $joinSql = " LEFT JOIN trans pr ON t.refid = pr.tranid AND pr.trantype = 'purchase/request'
            LEFT JOIN trans o ON pr.refid = o.tranid AND o.trantype = 'sales/order' ";
        }

        $sqlTran = "SELECT t.* $extraFields FROM trans t $joinSql WHERE t.tranid = '$id' LIMIT 1";
        $rawModel = $db->createCommand($sqlTran)->queryOne();
        $model = (object) $rawModel;

        $sqlDetails = "SELECT *, p.productname FROM trandetails t 
        LEFT JOIN products p ON t.productid = p.productid WHERE t.tranid = '$id' ORDER BY t.ord ASC";

        $rawDetails = $db->createCommand($sqlDetails)->queryAll();
        $modeldetails = array_map(function ($item) {
            return (object) $item;
        }, $rawDetails);

        $sqlEvents = "SELECT * FROM tranevents WHERE tranid = '$id' ORDER BY ord ASC";
        $rawEvents = $db->createCommand($sqlEvents)->queryAll();

        $eventsWithCrew = [];
        foreach ($rawEvents as $event) {
            $eventObj = (object) $event;
            $eventId = $eventObj->traneventid;

            $sqlCrews = "SELECT * FROM traneventcrews WHERE traneventid = '$eventId' ORDER BY ord ASC";
            $rawCrews = $db->createCommand($sqlCrews)->queryAll();

            $eventsWithCrew[] = [
                'event' => $eventObj,
                'crews' => array_map(function ($item) {
                    return (object) $item;
                }, $rawCrews)
            ];
        }

        $modelpos = [];
        if (in_array($fullType, ['sales/order', 'purchase/order'])) {
            $sqlPos = "
            SELECT 
                po.tranid,
                po.tranno,
                po.status,
                po.trandate,
                po.grandtotal,
                c.contact_name,
                c.jobcompany,
                u.name as createdby
            FROM trans po
            LEFT JOIN contacts c ON c.contact_id = po.contact_id
            LEFT JOIN users u ON u.userid::text = po.createdby
            WHERE po.refid = " . Yii::$app->db->quoteValue($id) . "
                AND po.trantype = 'purchase/order'
                AND po.status <> 10
            ORDER BY po.trandate DESC
        ";

            $rawPos = $db->createCommand($sqlPos)->queryAll();
            $modelpos = array_map(function ($item) {
                return (object) $item;
            }, $rawPos);
        }


        $renderParams = [
            'model' => $model,
            'modeldetails' => $modeldetails,
            'modelevents' => $eventsWithCrew,
            'modelpos' => $modelpos,
            'module' => $module,
            'type' => $type
        ];

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_detail', $renderParams);
        } else {
            return $this->render('_detail', $renderParams);
        }
    }
    public function actionTrack($id)
    {
        $tran = Tran::findOne(['tranid' => $id]);
        if (!$tran) {
            throw new \Exception('Project tidak di temukan');
        }

        $model = $tran->trantracking;

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_tracklist', [
                'model' => $model,
                'tranno' => $tran->tranno,
                'tranid' => $tran->tranid,
            ]);
        } else {
            return $this->render('_tracklist', [
                'model' => $model,
                'tranno' => $tran->tranno,
                'tranid' => $tran->tranid,
            ]);
        }
    }
    public function actionTrackcreate($id)
    {
        try {
            $tran = Tran::findOne(['tranid' => $id]);
            if (!$tran) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => false, 'pesan' => 'Project tidak ditemukan'];
                }
                throw new \yii\web\NotFoundHttpException('Project tidak ditemukan');
            }

            $model = new Trantracking();
            $model->tranid = $id;

            if ($model->load(Yii::$app->request->post())) {
                $model->trantrackingid = \Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();

                $imageFile = \yii\web\UploadedFile::getInstanceByName('tracking-image');

                if ($imageFile) {
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    $extension = strtolower($imageFile->extension);

                    if (!in_array($extension, $allowedExtensions)) {
                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return ['success' => false, 'pesan' => 'Tipe file tidak valid. Hanya JPG, PNG, dan GIF yang diperbolehkan.'];
                        }
                    }

                    if ($imageFile->size > 2 * 1024 * 1024) {
                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return ['success' => false, 'pesan' => 'Ukuran file melebihi 2MB.'];
                        }
                    }

                    $uploadPath = null;

                    $possibleAliases = ['@webroot', '@frontend/web', '@backend/web', '@app/web'];
                    foreach ($possibleAliases as $alias) {
                        try {
                            $resolved = Yii::getAlias($alias, false);
                            if ($resolved && is_dir($resolved)) {
                                $uploadPath = rtrim($resolved, '/') . '/uploads/tracking/';
                                break;
                            }
                        } catch (\Exception $e) {
                            continue;
                        }
                    }

                    if (!$uploadPath) {
                        $uploadPath = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/uploads/tracking/';
                    }

                    \Yii::info('Upload path resolved: ' . $uploadPath, 'tracking-upload');

                    if (!is_dir($uploadPath)) {
                        $created = @mkdir($uploadPath, 0775, true);

                        if (!$created && !is_dir($uploadPath)) {
                            \Yii::error('Gagal membuat direktori: ' . $uploadPath . ' | Error: ' . error_get_last()['message'] ?? 'unknown', 'tracking-upload');
                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return ['success' => false, 'pesan' => 'Gagal membuat folder upload. Path: ' . $uploadPath];
                            }
                        }
                    }

                    if (!is_writable($uploadPath)) {
                        @chmod($uploadPath, 0775);
                        if (!is_writable($uploadPath)) {
                            \Yii::error('Folder tidak writable: ' . $uploadPath, 'tracking-upload');
                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return ['success' => false, 'pesan' => 'Folder upload tidak dapat ditulis.'];
                            }
                        }
                    }

                    $fileName = uniqid() . '_' . time() . '.' . $extension;
                    $filePath = $uploadPath . $fileName;

                    if ($imageFile->saveAs($filePath)) {
                        $model->image = 'uploads/tracking/' . $fileName;
                        \Yii::info('Image uploaded successfully: ' . $fileName, 'tracking-upload');
                    } else {
                        \Yii::error('Gagal menyimpan file ke: ' . $filePath, 'tracking-upload');
                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return ['success' => false, 'pesan' => 'Gagal menyimpan file.'];
                        }
                    }
                }

                if ($model->validate()) {
                    if ($model->save(false)) {
                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return ['success' => true, 'pesan' => 'Data Berhasil Disimpan'];
                        }
                        return $this->redirect(['track', 'id' => $id]);
                    } else {
                        \Yii::error('Model save failed: ' . json_encode($model->errors), 'tracking-upload');
                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return ['success' => false, 'pesan' => 'Gagal menyimpan data: ' . implode('<br>', $model->getFirstErrors())];
                        }
                    }
                } else {
                    \Yii::error('Model validation failed: ' . json_encode($model->errors), 'tracking-upload');
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['success' => false, 'pesan' => 'Validasi gagal: ' . implode('<br>', $model->getFirstErrors())];
                    }
                }
            }

            $dt = new \DateTime();
            $model->time = $dt->format('d/m/Y H:i');

            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_formtrack', [
                    'model' => $model,
                    'isajax' => true
                ]);
            } else {
                return $this->render('_formtrack', [
                    'model' => $model,
                    'isajax' => false
                ]);
            }

        } catch (\Exception $e) {
            \Yii::error('Error in actionTrackcreate: ' . $e->getMessage() . "\n" . $e->getTraceAsString(), 'tracking');

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'pesan' => 'Terjadi kesalahan: ' . $e->getMessage(),
                    'trace' => YII_DEBUG ? $e->getTraceAsString() : null,
                    'file' => YII_DEBUG ? $e->getFile() : null,
                    'line' => YII_DEBUG ? $e->getLine() : null
                ];
            }

            throw $e;
        }
    }
    public function actionTrackdelete($id)
    {
        $trantracking = Trantracking::findOne(['trantrackingid' => $id]);
        if (!$trantracking) {
            throw new \Exception('Tracking tidak di temukan');
        }
        $trantracking->trackstatus = 10;
        if ($trantracking->save(false)) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['success' => true, 'pesan' => 'Data Berhasil Dihapus'];
            }
            return $this->redirect(['index']);
        } else {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['success' => false, 'pesan' => 'Gagal menghapus data'];
            }
            return $this->redirect(['index']);
        }
    }

    /**
     * Creates a new Tran model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tran();
        $modeldetails = [new Trandetail];

        $params = Yii::$app->request->queryParams;
        $module = $params['module'] ?? '';
        $type = $params['type'] ?? '';
        $moduleType = $module . '/' . $type;

        if ($model->load(Yii::$app->request->post())) {
            // var_dump(json_encode(Yii::$app->request->post(), JSON_PRETTY_PRINT));die();
            $modeldetail = Model::createMultipleID(Trandetail::classname(), $modeldetails, 'trandetailid');
            Model::loadMultiple($modeldetail, Yii::$app->request->post());
            // var_dump($modelevent);exit;

            $valid = $model->validate();
            $valid = Model::validateMultiple($modeldetail) && $valid;

            $id = Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();

            $prefixPenomoran = null;
            if ($moduleType == 'purchase/order') {
                $prefixPenomoran = 'PO';
            } elseif ($moduleType == 'purchase/request') {
                $prefixPenomoran = 'PR';
            } elseif ($moduleType == 'purchase/return') {
                $prefixPenomoran = 'PRT';
            } elseif ($moduleType == 'sales/invoice') {
                $prefixPenomoran = 'INV';
            }

            if ($valid) {
                $model->tranid = $id;
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $model->tranno = $model->nextNoTransaksi($prefixPenomoran);

                    if ($flag = $model->save(false)) {
                        foreach ($modeldetail as $indexdetail => $detail) {
                            if ($flag === false) {
                                break;
                            }
                            $detail->tranid = $model->tranid;
                            $detail->ord = $indexdetail + 1;

                            if (!($flag = $detail->save(false))) {
                                break;
                            }
                        }

                        if ($flag) {
                            $transaction->commit();

                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return ['success' => true, 'pesan' => 'Data Berhasil Disimpan', 'id' => $model->tranid, 'name' => $model->tranno];
                            }
                            return $this->redirect(['index', 'id' => $model->tranid]);
                        } else {
                            $transaction->rollBack();
                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                $errodetail = "";
                                $error = implode($model->getFirstErrors(), "");
                                foreach ($modeldetail as $i => $detail) {
                                    if ($detail->hasErrors()) {
                                        foreach ($detail->getErrors() as $attribute => $messages) {
                                            foreach ($messages as $message) {
                                                $errodetail .= "<br/>Baris " . ($i + 1) . " " . $message;
                                            }
                                        }
                                    }
                                }
                                return ['success' => false, 'pesan' => $error . "<br/>" . $errodetail];
                            }
                        }
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();

                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['success' => false, 'pesan' => $e];
                    }
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    $errodetail = "";
                    $error = implode($model->getFirstErrors(), "");
                    foreach ($modeldetail as $i => $detail) {
                        if ($detail->hasErrors()) {
                            foreach ($detail->getErrors() as $attribute => $messages) {
                                foreach ($messages as $message) {
                                    $errodetail .= "<br/>Baris " . ($i + 1) . " " . $message;
                                }
                            }
                        }
                    }
                    return ['success' => false, 'pesan' => $error . "<br/>" . $errodetail];
                }
            }
        }

        $params = Yii::$app->request->getQueryParams();

        if ($moduleType == 'purchase/order') {
            $params['penomoran'] = 'PO';
        } elseif ($moduleType == 'purchase/request') {
            $params['penomoran'] = 'PR';
        } elseif ($moduleType == 'purchase/return') {
            $params['penomoran'] = 'PRT';
        }

        Yii::$app->request->setQueryParams($params);
        $model->tranno = $model->nextNoTransaksi();
        $model->trandate = date("d/m/Y");
        $model->tranduedate = date("d/m/Y");
        $model->trantype = $moduleType;

        $form = $moduleType == 'sales/invoice' ? '_form' : '_formord';

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax($form, [
                'model' => $model,
                'modeldetails' => $modeldetails,
                'isajax' => true,
                'module' => $module,
                'type' => $type,
            ]);
        } else {
            return $this->render($form, [
                'model' => $model,
                'modeldetails' => $modeldetails,
                'isajax' => false,
                'module' => $module,
                'type' => $type,
            ]);
        }
    }
    public function actionCreatepo()
    {
        $model = new Tran();
        $modeldetails = [new Trandetail];

        $params = Yii::$app->request->queryParams;
        $module = $params['module'] ?? '';
        $type = $params['type'] ?? '';
        $moduleType = $module . '/' . $type;
        $refid = $params['refid'] ?? null;

        if (empty($refid)) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['success' => false, 'pesan' => 'Refid tidak boleh kosong. Simpan Sales Order terlebih dahulu.'];
            }
            throw new \yii\web\BadRequestHttpException('Refid required');
        }

        if ($model->load(Yii::$app->request->post())) {
            // var_dump(json_encode(Yii::$app->request->post(), JSON_PRETTY_PRINT));die();
            $modeldetail = Model::createMultipleID(Trandetail::classname(), $modeldetails, 'trandetailid');
            Model::loadMultiple($modeldetail, Yii::$app->request->post());
            // var_dump($modelevent);exit;

            $valid = $model->validate();
            $valid = Model::validateMultiple($modeldetail) && $valid;

            $id = Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();

            if ($valid) {
                $model->tranid = $id;
                $model->trantype = 'purchase/order';
                if (empty($model->refid)) {
                    $model->refid = $refid;
                }

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $model->tranno = $model->nextNoTransaksi();

                    if ($flag = $model->save(false)) {
                        foreach ($modeldetail as $indexdetail => $modeldetail) {
                            if ($flag === false) {
                                break;
                            }
                            $modeldetail->tranid = $model->tranid;
                            $modeldetail->ord = $indexdetail + 1;

                            if (!($flag = $modeldetail->save(false))) {
                                break;
                            }
                        }

                        if ($flag) {
                            $transaction->commit();

                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return ['success' => true, 'pesan' => 'Data Berhasil Disimpan', 'id' => $model->tranid, 'name' => $model->tranno];
                            }
                            return $this->redirect(['index', 'id' => $model->tranid]);
                        } else {
                            $transaction->rollBack();
                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                $errodetail = "";
                                $error = implode($model->getFirstErrors(), "");
                                foreach ($modeldetail as $i => $detail) {
                                    if ($detail->hasErrors()) {
                                        foreach ($detail->getErrors() as $attribute => $messages) {
                                            foreach ($messages as $message) {
                                                $errodetail .= "<br/>Baris " . ($i + 1) . " " . $message;
                                            }
                                        }
                                    }
                                }
                                return ['success' => false, 'pesan' => $error . "<br/>" . $errodetail];
                            }
                        }
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();

                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['success' => false, 'pesan' => $e];
                    }
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    $errodetail = "";
                    $error = implode($model->getFirstErrors(), "");
                    foreach ($modeldetail as $i => $detail) {
                        if ($detail->hasErrors()) {
                            foreach ($detail->getErrors() as $attribute => $messages) {
                                foreach ($messages as $message) {
                                    $errodetail .= "<br/>Baris " . ($i + 1) . " " . $message;
                                }
                            }
                        }
                    }
                    return ['success' => false, 'pesan' => $error . "<br/>" . $errodetail];
                }
            }
        }

        $params = Yii::$app->request->getQueryParams();

        Yii::$app->request->setQueryParams($params);
        $model->tranno = $model->nextNoTransaksi();
        $model->trandate = date("d/m/Y");
        $model->tranduedate = date("d/m/Y");
        $model->trantype = 'purchase/order';
        $model->refid = $refid;

        $form = $moduleType == 'sales/invoice' ? '_form' : '_formpo';

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax($form, [
                'model' => $model,
                'modeldetails' => $modeldetails,
                'isajax' => true,
                'module' => $module,
                'type' => $type,
            ]);
        } else {
            return $this->render($form, [
                'model' => $model,
                'modeldetails' => $modeldetails,
                'isajax' => false,
                'module' => $module,
                'type' => $type,
            ]);
        }
    }

    /**
     * Updates an existing Tran model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = Tran::findOne($id);

        if ($model == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $modeldetails = $model->trandetails;
        $modelevents = $model->tranevents;

        if ($model->load(Yii::$app->request->post())) {
            // var_dump(Yii::$app->request->post());die();
            $oldDetailIDs = ArrayHelper::map($modeldetails, 'trandetailid', 'trandetailid');
            $modeldetails = Model::createMultipleID(Trandetail::classname(), $modeldetails, 'trandetailid');
            Model::loadMultiple($modeldetails, Yii::$app->request->post());
            $deletedDetailIDs = array_diff($oldDetailIDs, array_filter(ArrayHelper::map($modeldetails, 'trandetailid', 'trandetailid')));

            $oldEventIDs = ArrayHelper::map($modelevents, 'traneventid', 'traneventid');
            $modelevents = Model::createMultipleID(Tranevent::classname(), $modelevents, 'traneventid');
            Model::loadMultiple($modelevents, Yii::$app->request->post());
            $deletedEventIDs = array_diff($oldEventIDs, array_filter(ArrayHelper::map($modelevents, 'traneventid', 'traneventid')));

            $valid = $model->validate();
            $valid = Model::validateMultiple($modeldetails) && $valid;
            $valid = Model::validateMultiple($modelevents) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {

                        if (!empty($deletedDetailIDs)) {
                            Trandetail::deleteAll(['trandetailid' => $deletedDetailIDs]);
                        }

                        foreach ($modeldetails as $indexdetail => $modeldetail) {
                            if ($flag === false) {
                                break;
                            }
                            $modeldetail->tranid = $model->tranid;
                            $modeldetail->ord = $indexdetail + 1;

                            if (!($flag = $modeldetail->save(false))) {
                                break;
                            }
                        }

                        if (!empty($deletedEventIDs)) {
                            Tranevent::deleteAll(['traneventid' => $deletedEventIDs]);
                        }

                        foreach ($modelevents as $indexevent => $modelevent) {
                            if ($flag === false) {
                                break;
                            }
                            $modelevent->tranid = $model->tranid;
                            $modelevent->ord = $indexevent + 1;

                            if (!($flag = $modelevent->save(false))) {
                                break;
                            }

                            $existingCrews = $modelevent->traneventcrews;
                            $oldCrewIDs = ArrayHelper::map($existingCrews, 'traneventcrewid', 'traneventcrewid');

                            $crewData = Yii::$app->request->post('Tranevent', [])[$indexevent]['Traneventcrew'] ?? [];
                            $modeleventcrews = Model::createMultipleID(Traneventcrew::classname(), $existingCrews, 'traneventcrewid');
                            Model::loadMultiple($modeleventcrews, $crewData);

                            $deletedCrewIDs = array_diff($oldCrewIDs, array_filter(ArrayHelper::map($modeleventcrews, 'traneventcrewid', 'traneventcrewid')));

                            if (!empty($deletedCrewIDs)) {
                                Traneventcrew::deleteAll(['traneventcrewid' => $deletedCrewIDs]);
                            }

                            foreach ($modeleventcrews as $indexcrew => $modeleventcrew) {
                                if ($flag === false) {
                                    break;
                                }
                                $modeleventcrew->traneventid = $modelevent->traneventid;
                                if (!($flag = $modeleventcrew->save(false))) {
                                    break;
                                }
                            }
                        }

                        if ($flag) {
                            $transaction->commit();
                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return ['success' => true, 'pesan' => 'Data Berhasil Disimpan', 'id' => $model->tranid, 'name' => $model->tranno];
                            }
                            return $this->redirect(['index', 'id' => $model->tranid]);
                        } else {
                            $transaction->rollBack();
                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return ['success' => false, 'pesan' => 'Gagal menyimpan data detail', 'id' => $model->tranid, 'name' => $model->tranno];
                            }
                        }
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['success' => false, 'pesan' => $e->getMessage(), 'id' => $model->tranid, 'name' => $model->tranno];
                    }
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => false, 'pesan' => 'Validasi gagal: ' . implode(', ', $model->getFirstErrors()), 'id' => $model->tranid, 'name' => $model->tranno];
                }
            }
        }

        $model->trandate = $model->trandate == null ? null : Yii::$app->formatter->asDate($model->trandate, "dd/MM/yyyy");
        $model->tranduedate = $model->tranduedate == null ? null : Yii::$app->formatter->asDate($model->tranduedate, "dd/MM/yyyy");

        foreach ($modeldetails as $index => $detail) {
            Yii::info("Detail $index: " . $detail->productid . ' - ' . $detail->amount, 'application');
        }

        $form = $model->trantype == 'sales/invoice' ? '_form' : '_formord';

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax($form, [
                'model' => $model,
                'modeldetails' => (empty($modeldetails)) ? [new Trandetail] : $modeldetails,
                'modelevents' => (empty($modelevents)) ? [new Tranevent] : $modelevents,
                'isajax' => true,
            ]);
        } else {
            return $this->render($form, [
                'model' => $model,
                'modeldetails' => (empty($modeldetails)) ? [new Trandetail] : $modeldetails,
                'modelevents' => (empty($modelevents)) ? [new Tranevent] : $modelevents,
                'isajax' => true,
            ]);
        }
    }
    public function actionUpdatepo($id)
    {
        $model = Tran::findOne($id);

        if ($model == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        // var_dump($id);exit;

        $modeldetails = $model->trandetails;
        $modelevents = $model->tranevents;

        if ($model->load(Yii::$app->request->post())) {
            // var_dump(Yii::$app->request->post());die();
            $oldDetailIDs = ArrayHelper::map($modeldetails, 'trandetailid', 'trandetailid');
            $modeldetails = Model::createMultipleID(Trandetail::classname(), $modeldetails, 'trandetailid');
            Model::loadMultiple($modeldetails, Yii::$app->request->post());
            $deletedDetailIDs = array_diff($oldDetailIDs, array_filter(ArrayHelper::map($modeldetails, 'trandetailid', 'trandetailid')));

            $oldEventIDs = ArrayHelper::map($modelevents, 'traneventid', 'traneventid');
            $modelevents = Model::createMultipleID(Tranevent::classname(), $modelevents, 'traneventid');
            Model::loadMultiple($modelevents, Yii::$app->request->post());
            $deletedEventIDs = array_diff($oldEventIDs, array_filter(ArrayHelper::map($modelevents, 'traneventid', 'traneventid')));

            $valid = $model->validate();
            $valid = Model::validateMultiple($modeldetails) && $valid;
            $valid = Model::validateMultiple($modelevents) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {

                        if (!empty($deletedDetailIDs)) {
                            Trandetail::deleteAll(['trandetailid' => $deletedDetailIDs]);
                        }

                        foreach ($modeldetails as $indexdetail => $modeldetail) {
                            if ($flag === false) {
                                break;
                            }
                            $modeldetail->tranid = $model->tranid;
                            $modeldetail->ord = $indexdetail + 1;

                            if (!($flag = $modeldetail->save(false))) {
                                break;
                            }
                        }

                        if (!empty($deletedEventIDs)) {
                            Tranevent::deleteAll(['traneventid' => $deletedEventIDs]);
                        }

                        foreach ($modelevents as $indexevent => $modelevent) {
                            if ($flag === false) {
                                break;
                            }
                            $modelevent->tranid = $model->tranid;
                            $modelevent->ord = $indexevent + 1;

                            if (!($flag = $modelevent->save(false))) {
                                break;
                            }

                            $existingCrews = $modelevent->traneventcrews;
                            $oldCrewIDs = ArrayHelper::map($existingCrews, 'traneventcrewid', 'traneventcrewid');

                            $crewData = Yii::$app->request->post('Tranevent', [])[$indexevent]['Traneventcrew'] ?? [];
                            $modeleventcrews = Model::createMultipleID(Traneventcrew::classname(), $existingCrews, 'traneventcrewid');
                            Model::loadMultiple($modeleventcrews, $crewData);

                            $deletedCrewIDs = array_diff($oldCrewIDs, array_filter(ArrayHelper::map($modeleventcrews, 'traneventcrewid', 'traneventcrewid')));

                            if (!empty($deletedCrewIDs)) {
                                Traneventcrew::deleteAll(['traneventcrewid' => $deletedCrewIDs]);
                            }

                            foreach ($modeleventcrews as $indexcrew => $modeleventcrew) {
                                if ($flag === false) {
                                    break;
                                }
                                $modeleventcrew->traneventid = $modelevent->traneventid;
                                if (!($flag = $modeleventcrew->save(false))) {
                                    break;
                                }
                            }
                        }

                        if ($flag) {
                            $transaction->commit();
                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return ['success' => true, 'pesan' => 'Data Berhasil Disimpan', 'id' => $model->tranid, 'name' => $model->tranno];
                            }
                            return $this->redirect(['index', 'id' => $model->tranid]);
                        } else {
                            $transaction->rollBack();
                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                return ['success' => false, 'pesan' => 'Gagal menyimpan data detail', 'id' => $model->tranid, 'name' => $model->tranno];
                            }
                        }
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['success' => false, 'pesan' => $e->getMessage(), 'id' => $model->tranid, 'name' => $model->tranno];
                    }
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => false, 'pesan' => 'Validasi gagal: ' . implode(', ', $model->getFirstErrors()), 'id' => $model->tranid, 'name' => $model->tranno];
                }
            }
        }

        $model->trandate = $model->trandate == null ? null : Yii::$app->formatter->asDate($model->trandate, "dd/MM/yyyy");
        $model->tranduedate = $model->tranduedate == null ? null : Yii::$app->formatter->asDate($model->tranduedate, "dd/MM/yyyy");

        foreach ($modeldetails as $index => $detail) {
            Yii::info("Detail $index: " . $detail->productid . ' - ' . $detail->amount, 'application');
        }

        $form = '_formpo';

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax($form, [
                'model' => $model,
                'modeldetails' => (empty($modeldetails)) ? [new Trandetail] : $modeldetails,
                'modelevents' => (empty($modelevents)) ? [new Tranevent] : $modelevents,
                'isajax' => true,
            ]);
        } else {
            return $this->render($form, [
                'model' => $model,
                'modeldetails' => (empty($modeldetails)) ? [new Trandetail] : $modeldetails,
                'modelevents' => (empty($modelevents)) ? [new Tranevent] : $modelevents,
                'isajax' => true,
            ]);
        }
    }
    public function actionDuplicate($id)
    {
        $originalModel = $this->findModel($id);

        $model = new Tran();
        $model->attributes = $originalModel->attributes;
        $model->tranid = null;
        $model->tranno = $originalModel->tranno . " Copy";

        if (!empty($model->trandate)) {
            $model->trandate = date("d/m/Y", strtotime($model->trandate));
        }
        if (!empty($model->tranduedate)) {
            $model->tranduedate = date("d/m/Y", strtotime($model->tranduedate));
        }
        if (!empty($model->setupdate)) {
            $model->setupdate = date("d/m/Y H:i", strtotime($model->setupdate));
        }
        if (!empty($model->withdrawaldate)) {
            $model->withdrawaldate = date("d/m/Y H:i", strtotime($model->withdrawaldate));
        }

        $parts = explode('/', $originalModel->trantype);
        $module = $parts[0] ?? 'purchase';
        $type = $parts[1] ?? 'request';

        $existingDetails = Trandetail::find()
            ->where(['tranid' => $id])
            ->orderBy(['ord' => SORT_ASC])
            ->all();

        $existingEvents = Tranevent::find()
            ->where(['tranid' => $id])
            ->with(['traneventcrews'])
            ->orderBy(['ord' => SORT_ASC])
            ->all();

        $detailsData = [];
        foreach ($existingDetails as $detail) {
            $variant = Yii::$app->db->createCommand("
                SELECT p.productname
                FROM products p
                WHERE p.productid = '{$detail->productid}'
            ")->queryOne();

            $detailItem = [
                'productid' => $detail->productid,
                'amount' => $detail->amount,
                'price' => $detail->price,
                'itemsubtotal' => $detail->itemsubtotal,
                'totalafterdisc' => $detail->totalafterdisc,
                'itemdiscpersen' => $detail->itemdiscpersen ?? 0,
                'itemdisctotal' => $detail->itemdisc ?? 0,
                'taxtype' => $detail->taxtype ?? 0,
                'itemtype' => $detail->itemtype ?? 0,
            ];

            if ($variant) {
                $detailItem['productname'] = $variant['productname'] ?? '';
            }

            $detailsData[] = $detailItem;
        }

        $eventsData = [];
        $clonedEvents = [];

        foreach ($existingEvents as $event) {
            $crewsData = [];
            foreach ($event->traneventcrews as $crew) {
                $crewsData[] = [
                    'crewtypeid' => $crew->crewtypeid,
                    'crewid' => $crew->crewid,
                    'jobid' => $crew->jobid,
                    'fee' => $crew->fee,
                    'dinasid' => $crew->dinasid,
                    'dinasfee' => $crew->dinasfee,
                ];
            }

            $eventsData[] = [
                'traneventid' => null,              // reset — akan dapat UUID baru saat save
                'eventtypeid' => $event->eventtypeid,
                'startdate' => $event->startdate,
                'enddate' => $event->enddate,
                'crews' => $crewsData,
            ];

            $newEventModel = new Tranevent();
            $newEventModel->attributes = $event->attributes;
            $newEventModel->traneventid = null; // Reset ID agar dianggap data baru saat di-save
            $newEventModel->tranid = null;

            $crewsModels = [];
            foreach ($event->traneventcrews as $crew) {
                $newCrewModel = new Traneventcrew();
                $newCrewModel->attributes = $crew->attributes;
                $newCrewModel->traneventcrewid = null; // Reset ID crew
                $newCrewModel->traneventid = null;

                $crewsModels[] = $newCrewModel;
            }

            // Inject model crew ke dalam relasi model event khayalan ini
            // Sesuaikan 'traneventcrews' dengan nama relasi asli di model Tranevent Anda
            $newEventModel->populateRelation('traneventcrews', $crewsModels);

            $clonedEvents[] = $newEventModel;
        }

        if ($model->load(Yii::$app->request->post())) {

            $model->trandate = $model->trandate ? Yii::$app->formatter->asDate($model->trandate, 'dd/MM/yyyy') : null;
            $model->tranduedate = $model->tranduedate ? Yii::$app->formatter->asDate($model->tranduedate, 'dd/MM/yyyy') : null;
            $model->setupdate = $model->setupdate ? Yii::$app->formatter->asDateTime($model->setupdate, 'dd/MM/yyyy HH:mm') : null;
            $model->withdrawaldate = $model->withdrawaldate ? Yii::$app->formatter->asDateTime($model->withdrawaldate, 'dd/MM/yyyy HH:mm') : null;

            if (empty($model->trantype)) {
                $model->trantype = "$module/$type";
            }

            $post = Yii::$app->request->post();
            $model->priceincludetax = ($post['Trans']['price-include-tax'] ?? '') === 'on' ? 1 : 0;

            $fields = ['subtotal', 'disc', 'totalafterdisc', 'ppnamount', 'pphamount', 'otherdiscount', 'deliverycharge', 'grandtotal'];
            foreach ($fields as $field) {
                $model->$field = isset($post['Trans'][$field])
                    ? (float) str_replace(['.', ','], ['', '.'], $post['Trans'][$field])
                    : 0;
            }

            $valid = $model->validate();

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $newTranId = Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
                    $model->tranid = $newTranId;

                    if ($model->save(false)) {

                        $detailsJson = Yii::$app->request->post('details', '[]');
                        $details = json_decode($detailsJson, true);

                        if (is_array($details) && !empty($details)) {
                            foreach ($details as $indexDetail => $detail) {
                                $detailModel = new Trandetail();
                                $detailModel->tranid = $model->tranid;
                                $detailModel->ord = $indexDetail + 1;
                                $detailModel->productid = $detail['productid'] ?? null;
                                $detailModel->amount = intval($detail['amount'] ?? 0);
                                $detailModel->price = (float) str_replace(['.', ','], ['', '.'], $detail['price'] ?? '0');
                                $detailModel->itemdisc = (float) str_replace(['.', ','], ['', '.'], $detail['itemdisctotal'] ?? '0');
                                $detailModel->itemsubtotal = (float) str_replace(['.', ','], ['', '.'], $detail['itemsubtotal'] ?? '0');
                                $detailModel->totalafterdisc = (float) str_replace(['.', ','], ['', '.'], $detail['totalafterdisc'] ?? '0');
                                $detailModel->itemtotal = $detailModel->totalafterdisc;
                                $detailModel->itemdiscpersen = (float) str_replace(['.', ','], ['', '.'], $detail['disc'] ?? '0');
                                $detailModel->taxtype = (int) ($detail['tax_type'] ?? 0);
                                $detailModel->itemtype = (int) ($detail['description'] ?? 0);

                                if (!$detailModel->save(false)) {
                                    throw new \Exception('Gagal menyimpan detail transaksi.');
                                }
                            }
                        }

                        $postEvents = $post['Tranevent'] ?? [];
                        foreach ($postEvents as $indexEvent => $eventData) {
                            $eventModel = new Tranevent();
                            $eventModel->attributes = $eventData;
                            $newEventId = Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();

                            $eventModel->traneventid = $newEventId;
                            $eventModel->tranid = $model->tranid;
                            $eventModel->ord = $indexEvent + 1;

                            $eventModel->startdate = !empty($eventData['startdate'])
                                ? Yii::$app->formatter->asDate($eventData['startdate'], 'dd/MM/yyyy')
                                : null;
                            $eventModel->enddate = !empty($eventData['enddate'])
                                ? Yii::$app->formatter->asDate($eventData['enddate'], 'dd/MM/yyyy')
                                : null;

                            if (!$eventModel->save(false)) {
                                throw new \Exception('Gagal menyimpan event transaksi.');
                            }

                            $eventCrews = $eventData['Traneventcrew'] ?? [];
                            foreach ($eventCrews as $crewData) {
                                $crewModel = new Traneventcrew();
                                $crewModel->traneventid = $newEventId;
                                $crewModel->crewtypeid = $crewData['crewtypeid'] ?? null;
                                $crewModel->crewid = $crewData['crewid'] ?? null;
                                $crewModel->jobid = $crewData['jobid'] ?? null;
                                $crewModel->fee = (float) str_replace(['.', ','], ['', '.'], $crewData['fee'] ?? '0');
                                $crewModel->dinasid = $crewData['dinasid'] ?? null;
                                $crewModel->dinasfee = (float) str_replace(['.', ','], ['', '.'], $crewData['dinasfee'] ?? '0');

                                if (!$crewModel->save(false)) {
                                    throw new \Exception('Gagal menyimpan crew event.');
                                }
                            }
                        }

                        $transaction->commit();

                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return [
                                'success' => true,
                                'pesan' => 'Data Berhasil Diduplikasi',
                                'id' => $model->tranid,
                                'tranno' => $model->tranno,
                            ];
                        }

                        return $this->redirect(['request', 'module' => $module, 'type' => $type]);
                    }

                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::error('Duplication error: ' . $e->getMessage());

                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['success' => false, 'pesan' => $e->getMessage()];
                    }
                }

            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => false, 'pesan' => implode("<br>", $model->getFirstErrors())];
                }
            }
        }

        if (!empty($clonedEvents)) {
            foreach ($clonedEvents as $evt) {
                $crewCount = count($evt->traneventcrews ?? []);
                Yii::info("Event {$evt->traneventid}: {$crewCount} crews", 'duplicate');
            }
        }

        $renderMethod = Yii::$app->request->isAjax ? 'renderAjax' : 'render';
        return $this->$renderMethod('_formpro', [
            'model' => $model,
            'modeldetails' => empty($existingDetails) ? [new Trandetail] : $existingDetails,
            'modelevents' => empty($clonedEvents) ? [new Tranevent] : $clonedEvents,
            'detailsData' => $detailsData,
            'eventsData' => $eventsData,
            'isajax' => Yii::$app->request->isAjax ? "true" : "false",
            'module' => $module,
            'type' => $type,
        ]);
    }
    public function actionUpdatestatus()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;

        if (!$request->isPost) {
            return ['success' => false, 'message' => 'Hanya menerima request POST'];
        }

        $id = $request->post('id');
        $status = $request->post('status');

        if (empty($id) || $status === null) {
            return ['success' => false, 'message' => 'Parameter tidak lengkap'];
        }

        $model = Tran::findOne($id);
        if (!$model) {
            return ['success' => false, 'message' => 'Data transaksi tidak ditemukan'];
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($model->trantype === 'sales/quote' && $status == 1) {
                $model->trantype = 'sales/order';
                $model->status = 0;
                $status = 0;

                if (empty($model->setupdate)) {
                    $model->setupdate = date('d/m/Y H:i');
                }

                if (empty($model->withdrawaldate)) {
                    $model->withdrawaldate = date('d/m/Y H:i');
                }
            } else {
                $model->status = $status;
            }

            if (!$model->save(false)) {
                throw new \Exception('Gagal menyimpan perubahan status');
            }

            if ($status == 1) {
                $modeldetails = $model->trandetails;

                $locationGudangTR = Yii::$app->db->createCommand(
                    "SELECT enumid FROM enum WHERE enumtype = 'location' AND enumtext_id ILIKE '%Gudang TR%' LIMIT 1"
                )->queryScalar() ?: 'location.1';

                $conditionBaik = Yii::$app->db->createCommand(
                    "SELECT enumid FROM enum WHERE enumtype = 'condition' AND enumtext_id ILIKE '%Baik%' LIMIT 1"
                )->queryScalar() ?: 'condition.1';

                $now = date('Y-m-d H:i:s');

                foreach ($modeldetails as $modeldetail) {
                    if ($model->trantype == 'purchase/delivery' && ($model->eventtype == '1' || ($model->eventtype === '' && $model->reftype == 'purchase/order'))) {

                        for ($i = 0; $i < $modeldetail->remain2; $i++) {
                            $variant = new Variant();
                            $variant->variantid = Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
                            $variant->productid = $modeldetail->productid;
                            $variant->status = 1;
                            $variant->sku = '1';
                            $variant->tranid = $modeldetail->tranid;
                            $variant->refid = $modeldetail->trandetailid;
                            $variant->locationid = $locationGudangTR;
                            $variant->condition = $conditionBaik;
                            $variant->trandate = $now;

                            if (!$variant->save()) {
                                throw new \Exception('Gagal simpan variant: ' . json_encode($variant->errors));
                            }
                        }

                    } else if ($model->trantype == 'sales/delivery' && $modeldetail->amount2 > 0) {

                        for ($i = 0; $i < $modeldetail->amount2; $i++) {
                            $variantSql = "SELECT v.* FROM variants v
                            LEFT JOIN enum l ON v.locationid = l.enumid AND l.enumtype = 'location'
                            WHERE v.productid = :productid
                            AND l.enumtext_id !~* 'Bengkel|Dijual|Disewakan'
                            AND v.status = 1 AND v.sku = '1' 
                            LIMIT 1";

                            $variant = Variant::findBySql($variantSql, [':productid' => $modeldetail->productid])->one();

                            if (!$variant) {
                                throw new \Exception('Stok fisik tidak ditemukan untuk produk ' . $modeldetail->product->productname);
                            }

                            $originalLocation = $variant->locationid;
                            $variant->locationid = 'location.3';

                            if (!$variant->save(false)) {
                                throw new \Exception('Gagal update lokasi variant: ' . json_encode($variant->errors));
                            }

                            $tranvariant = new Tranvariants();
                            $tranvariant->tranvariantid = Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
                            $tranvariant->trandetailid = $modeldetail->trandetailid;
                            $tranvariant->variantid = $variant->variantid;
                            $tranvariant->productid = $modeldetail->productid;
                            $tranvariant->refid = $modeldetail->tranid;
                            $tranvariant->type = 2;
                            $tranvariant->status = 0; // pinjam
                            $tranvariant->from_locationid = $originalLocation;
                            $tranvariant->trandate = $now;

                            if (!$tranvariant->save(false)) {
                                throw new \Exception('Gagal simpan tranvariant: ' . json_encode($tranvariant->errors));
                            }
                        }

                    } else if ($model->trantype == 'sales/return' || ($model->trantype == 'purchase/delivery' && $model->reftype == 'sales/order')) {

                        for ($i = 0; $i < $modeldetail->amount2; $i++) {
                            $deliVariant = Tranvariants::find()
                                ->alias('tv')
                                ->innerJoin('trans t', 't.tranid = tv.refid')
                                ->where([
                                    'tv.productid' => $modeldetail->productid,
                                    'tv.type' => 2, // Type 2 = Delivery / Out
                                    't.refid' => $model->refid // refid pada return merujuk ke Sales Order 
                                ])
                                ->andWhere([
                                    'NOT IN',
                                    'tv.variantid',
                                    Tranvariants::find()
                                        ->select('variantid')
                                        ->where(['type' => 3, 'productid' => $modeldetail->productid])
                                ])
                                ->one();

                            if ($deliVariant) {
                                $variant = Variant::findOne($deliVariant->variantid);
                                $targetLocation = $deliVariant->from_locationid ?: 'location.1';
                            } else {
                                $variant = Variant::find()->where([
                                    'productid' => $modeldetail->productid,
                                    'locationid' => 'location.3',
                                    'status' => 1,
                                    'sku' => '1',
                                ])->one();

                                $targetLocation = 'location.1';
                            }

                            if (!$variant) {
                                throw new \Exception('Variant tidak ditemukan untuk dikembalikan: ' . $modeldetail->product->productname);
                            }

                            $variant->locationid = $targetLocation;

                            if (!$variant->save(false)) {
                                throw new \Exception('Gagal update lokasi variant: ' . json_encode($variant->errors));
                            }

                            $tranvariant = new Tranvariants();
                            $tranvariant->tranvariantid = Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
                            $tranvariant->trandetailid = $modeldetail->trandetailid;
                            $tranvariant->variantid = $variant->variantid;
                            $tranvariant->productid = $modeldetail->productid;
                            $tranvariant->refid = $modeldetail->tranid;
                            $tranvariant->type = 3; // return
                            $tranvariant->status = 0; //pinjam
                            $tranvariant->trandate = $now;

                            if (!$tranvariant->save(false)) {
                                throw new \Exception('Gagal simpan tranvariant return: ' . json_encode($tranvariant->errors));
                            }
                        }
                    }
                }

                if ($model->trantype == 'purchase/order' && $model->eventtype == '') {
                    $tran = new Tran();
                    $newDeliveryId = Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
                    $tran->tranid = $newDeliveryId;
                    $tran->trantype = 'purchase/delivery';
                    $tran->reftype = 'purchase/order';
                    $tran->refid = $model->tranid;
                    $tran->tranno = Tran::nextNoTransaksi("I");
                    $tran->trandate = date('Y-m-d');
                    $tran->status = 0;

                    if (!$tran->save(false)) {
                        throw new \Exception('Gagal membuat transaksi penerimaan barang: ' . json_encode($tran->errors));
                    }

                    foreach ($modeldetails as $modeldetail) {
                        $trandetail = new Trandetail();
                        $newDetailId = Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
                        $trandetail->trandetailid = $newDetailId;
                        $trandetail->tranid = $newDeliveryId;
                        $trandetail->productid = $modeldetail->productid;
                        $trandetail->amount = $modeldetail->amount;
                        $trandetail->remain = $modeldetail->amount;
                        $trandetail->remain2 = $modeldetail->amount;

                        if (!$trandetail->save(false)) {
                            throw new \Exception('Gagal membuat detail purchase delivery');
                        }

                        $totalAmount = (int) $modeldetail->amount;

                        for ($i = 0; $i < $totalAmount; $i++) {
                            $variant = new Variant();
                            $variant->variantid = Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
                            $variant->productid = $modeldetail->productid;
                            $variant->status = 1;
                            $variant->stock = 1; // pinjam po di so
                            $variant->sku = '1';
                            $variant->tranid = $newDeliveryId;
                            $variant->refid = $newDetailId;
                            $variant->locationid = 'location.1';
                            $variant->condition = 'condition.1';
                            $variant->trandate = $now;

                            if (!$variant->save()) {
                                throw new \Exception('Gagal simpan variant: ' . json_encode($variant->errors));
                            }
                        }
                    }
                }
            }

            $transaction->commit();

            $statusLabels = [0 => 'Draft', 1 => 'Approved', 5 => 'Cancelled', 10 => 'Rejected'];
            $statusText = $statusLabels[$status] ?? 'Unknown';

            return [
                'success' => true,
                'message' => 'Status berhasil diperbarui',
                'status' => $status,
                'statusText' => $statusText,
            ];

        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    public function actionUpdatestatuspro()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;

        $id = $request->post('id');
        $statuspro = $request->post('statuspro');
        // var_dump($id, $statuspro, $pendingBarcodes);die();

        if (!$request->isPost) {
            return ['success' => false, 'message' => 'Hanya menerima request POST'];
        }

        if (empty($id) || $statuspro === null) {
            return ['success' => false, 'message' => 'Parameter tidak lengkap'];
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model = Tran::findOne($id);
            if (!$model) {
                throw new Exception('Data transaksi tidak ditemukan');
            }

            $model->statuspro = $statuspro;

            if (!$model->save(false)) {
                throw new Exception('Gagal mengupdate status utama');
            }

            if ($model->trantype === 'purchase/delivery' && !empty($model->refid)) {
                $sql = " SELECT COUNT(*) FROM trans
                WHERE refid = '{$model->refid}' AND statuspro <> '15' 
                AND trantype = 'purchase/delivery' AND reftype = 'sales/order' AND status <> '10' ";

                $countret = Yii::$app->db->createCommand($sql)->queryScalar();

                if ($countret == 0) {
                    $updateSql = " UPDATE trans SET statuspro = '15'
                    WHERE tranid = '{$model->refid}' AND status <> '10'";

                    Yii::$app->db->createCommand($updateSql)->execute();
                }

                $pendingBarcodes =
                    "UPDATE variants 
                        SET status = '10', description = 'lose'
                        WHERE barcode IN (
                            SELECT tv_out.barcode
                            FROM tranvariants tv_out

                            INNER JOIN trans tr_del ON tr_del.tranid = tv_out.refid AND tr_del.status <> 10 AND tr_del.trantype = 'sales/delivery'
                            INNER JOIN trans so ON so.tranid = tr_del.refid AND so.status <> 10 AND so.trantype = 'sales/order'
                            INNER JOIN trans r ON r.refid = so.tranid AND r.status <> 10 AND r.trantype = 'purchase/delivery' AND r.reftype = 'sales/order'
                            WHERE r.tranid = '$id' AND tv_out.type = '2' AND COALESCE(tv_out.status, 0) <> 10
                            AND NOT EXISTS (
                                SELECT 1 
                                FROM tranvariants tv_in
                                INNER JOIN trans tr_ret ON tr_ret.tranid = tv_in.refid AND tr_ret.status <> 10 
                                    AND (
                                        (tr_ret.trantype = 'purchase/delivery' AND tr_ret.reftype = 'sales/order')
                                        OR (tr_ret.trantype = 'sales/return')
                                    )
                                WHERE tv_in.type = '3' 
                                AND COALESCE(tv_in.status, 0) <> 10 
                                AND tv_in.barcode = tv_out.barcode
                                AND tr_ret.refid = so.tranid 
                            )
                        )";

                // echo $pendingBarcodes;die();
                Yii::$app->db->createCommand($pendingBarcodes)->execute();
            }

            $transaction->commit();

            $statusLabels = [
                0 => 'Lead',
                1 => 'Deal',
                5 => 'On Progress',
                10 => 'Follow Up',
                15 => 'Finish'
            ];

            return [
                'success' => true,
                'message' => 'Status berhasil diperbarui',
                'statuspro' => $statuspro,
                'statusText' => $statusLabels[$statuspro] ?? 'Unknown'
            ];

        } catch (Exception $e) {
            $transaction->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    public function actionUpdatestatusclient()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;

        $id = $request->post('id');
        $statuspro = $request->post('statuspro');
        // var_dump($id, $statuspro, $pendingBarcodes);die();

        if (!$request->isPost) {
            return ['success' => false, 'message' => 'Hanya menerima request POST'];
        }

        if (empty($id) || $statuspro === null) {
            return ['success' => false, 'message' => 'Parameter tidak lengkap'];
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model = Tran::findOne($id);
            if (!$model) {
                throw new \Exception('Data transaksi tidak ditemukan');
            }

            $model->statuspro = $statuspro;

            if (!$model->save(false)) {
                throw new \Exception('Gagal mengupdate status utama');
            }


            $transaction->commit();

            $statusLabels = [
                0 => 'Belum',
                1 => 'Sudah',
            ];

            return [
                'success' => true,
                'message' => 'Status berhasil diperbarui',
                'statuspro' => $statuspro,
                'statusText' => $statusLabels[$statuspro] ?? 'Unknown'
            ];

        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Handles mass actions (delete, approve, cancel) for multiple transactions
     *
     * @return \yii\web\Response
     */
    public function actionMassaction()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $ids = Yii::$app->request->post('ids');
        $action = Yii::$app->request->post('action');
        $status = Yii::$app->request->post('status');
        $type = Yii::$app->request->post('type');
        $reftype2 = Yii::$app->request->post('reftype2');
        // var_dump($ids, $action, $status, $type, $reftype2);die();

        if (empty($ids) || !is_array($ids)) {
            return $this->asJson([
                'success' => false,
                'message' => 'Tidak ada data yang dipilih!'
            ]);
        }

        $validActions = ['delete', 'approve', 'cancel', 'close'];
        $validStatuses = [1, 5, 10, 15]; // 1=Approved, 5=Cancelled, 10=Rejected/Deleted, 15=Closed

        if (!in_array($action, $validActions) || !in_array($status, $validStatuses)) {
            return $this->asJson([
                'success' => false,
                'message' => 'Aksi atau status tidak valid!'
            ]);
        }

        try {

            $idString = implode(',', array_map(function ($id) {
                return "'" . addslashes($id) . "'";
            }, $ids));

            // var_dump($idString);die();
            $user_id = Yii::$app->user->id;
            $user_ip = Yii::$app->request->userIP;

            $rowsAffected = 0;
            // var_dump($type);die();

            if ($type == 'stock/in' && $reftype2 == 'purchase/request') {
                $sql = "UPDATE variants SET status = '$status'
                 WHERE variantid IN ($idString)";

                $rowsAffected = Yii::$app->db->createCommand($sql)->execute();

            } else if ($type == 'sales/item') {
                $sqlVariants = "UPDATE variants 
                    SET locationid = tranvariants.from_locationid
                    FROM tranvariants 
                    WHERE tranvariants.variantid = variants.variantid
                    AND tranvariants.tranvariantid IN ($idString)
                    AND tranvariants.type = '2'";
                // echo $sqlVariants;die();
                Yii::$app->db->createCommand($sqlVariants)->execute();

                $sql = "UPDATE tranvariants SET status = '$status'
                    WHERE tranvariantid IN ($idString)
                    AND type = '2'";

                $rowsAffected = Yii::$app->db->createCommand($sql)->execute();

            } else if ($type == 'stock/return' || ($type == 'stock/in' && $reftype2 == 'sales/order')) {
                $sqlVariants = "UPDATE variants 
                    SET locationid = 'location.3'
                    FROM tranvariants 
                    WHERE tranvariants.variantid = variants.variantid
                    AND tranvariants.tranvariantid IN ($idString)
                    AND tranvariants.type = '3'";

                Yii::$app->db->createCommand($sqlVariants)->execute();

                $sql = "UPDATE tranvariants SET status = '$status'
                    WHERE tranvariantid IN ($idString)
                    AND type = '3'";
                $rowsAffected = Yii::$app->db->createCommand($sql)->execute();

            } else {
                $sql = "UPDATE trans SET status = '$status',
                updatedat = NOW(),
                updatedby = '$user_id',
                updatedip = '$user_ip'
                WHERE tranid IN ($idString)";

                $rowsAffected = Yii::$app->db->createCommand($sql)->execute();
            }
            // echo $sqlVariants;die();
            // echo $sql;die();

            $actionText = '';
            switch ($action) {
                case 'delete':
                    $actionText = 'dihapus';
                    break;
                case 'approve':
                    $actionText = 'diapprove';
                    break;
                case 'cancel':
                    $actionText = 'dibatalkan';
                    break;
                case 'complete':
                    $actionText = 'selesai';
                    break;
            }

            return $this->asJson([
                'success' => true,
                'message' => "Data berhasil $actionText!",
                'rows_affected' => $rowsAffected,
                'action' => $action,
                'status' => $status
            ]);
        } catch (\Exception $e) {
            Yii::error("Error in mass action: " . $e->getMessage());

            return $this->asJson([
                'success' => false,
                'message' => "Terjadi kesalahan: " . $e->getMessage(),
                'error' => true
            ]);
        }
    }

    /**
     * Deletes an existing Tran model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionDelete($id = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = $id ?? Yii::$app->request->post('id');

        if (!$id) {
            return ['success' => false, 'message' => 'ID tidak ditemukan!'];
        }

        $model = $this->findModel($id);
        $parts = explode('/', $model->trantype);
        $module = $parts[0] ?? 'purchase';
        $type = $parts[1] ?? 'request';
        $transaction = Yii::$app->db->beginTransaction();

        try {
            Trandetail::updateAll(['status' => 10], ['tranid' => $id]);

            if ($model->trantype === 'purchase/order') {
                $childTranIds = Tran::find()
                    ->select('tranid')
                    ->where(['refid' => $id, 'reftype' => 'purchase/order'])
                    ->column();

                if (!empty($childTranIds)) {
                    Variant::updateAll(['status' => 10], ['in', 'tranid', $childTranIds]);

                    Trandetail::updateAll(['status' => 10], ['in', 'tranid', $childTranIds]);

                    Tran::updateAll(['status' => 10], ['in', 'tranid', $childTranIds]);
                }
            }

            if ($model->trantype === 'purchase/delivery' && $model->eventtype !== '0') {

                Variant::updateAll(
                    ['status' => 10],
                    ['and', ['tranid' => $id]]
                );
            }

            $eventIds = Tranevent::find()
                ->select('traneventid')
                ->where(['tranid' => $id])
                ->column();

            if (!empty($eventIds)) {
                Traneventcrew::updateAll(['status' => 10], ['in', 'traneventid', $eventIds]);

                Tranevent::updateAll(['status' => 10], ['tranid' => $id]);
            }

            $model->status = 10;
            if (!$model->save(false)) {
                throw new \yii\db\Exception('Gagal mengubah status transaksi utama');
            }

            $transaction->commit();

            return ['success' => true, 'module' => $module, 'type' => $type];

        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error("Delete error: " . $e->getMessage(), 'tran');
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }


    /**
     * Finds the Tran model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Tran the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tran::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionSelect()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $q = $_POST['q'] ?? '';
        $module = $_POST['module'] ?? 'purchase';
        $type = $_POST['type'] ?? '';
        $target = $_POST['target'] ?? '';
        $subtype = $_POST['subtype'] ?? '';
        $trantype = "$module/$type";
        $out = ['totalcount' => 0, 'items' => []];

        $where = " WHERE A.status = '1' ";
        if ($q !== '') {
            $qEscaped = addslashes($q);
            $where .= " AND (
                A.tranno ILIKE '%{$qEscaped}%'
                OR EXISTS (
                    SELECT 1 FROM contacts cx
                    WHERE cx.contact_id = A.contact_id
                    AND (
                        cx.contact_name ILIKE '%{$qEscaped}%'
                        OR cx.jobcompany ILIKE '%{$qEscaped}%'
                    )
                )
                OR EXISTS (
                    SELECT 1 FROM trans rx
                    JOIN contacts crx ON rx.contact_id = crx.contact_id
                    WHERE rx.refid = A.tranid AND rx.status = '1'
                    AND (
                        crx.contact_name ILIKE '%{$qEscaped}%'
                        OR crx.jobcompany ILIKE '%{$qEscaped}%'
                    )
                )
                OR EXISTS (
                    SELECT 1 FROM trans sox
                    JOIN contacts csox ON sox.contact_id = csox.contact_id
                    WHERE sox.tranid = (
                        CASE 
                            WHEN A.trantype IN ('purchase/order', 'purchase/delivery', 'purchase/return') THEN 
                                (SELECT x.refid FROM trans x WHERE x.tranid = A.refid)
                            ELSE A.refid 
                        END
                    )
                    AND sox.trantype = 'sales/order'
                    AND (
                        csox.contact_name ILIKE '%{$qEscaped}%'
                        OR csox.jobcompany ILIKE '%{$qEscaped}%'
                    )
                )
            ) ";
        }

        if ($trantype !== '') {
            if ($trantype == 'sales/return' || $trantype == 'purchase/request' || $trantype == 'sales/invoice') {
                $where .= " AND A.trantype = 'sales/order' ";

            } else if ($trantype == 'purchase/order') {
                $where .= " AND A.trantype = 'purchase/request' ";

            } else if ($trantype == 'purchase/return') {
                $where .= " AND A.trantype = 'purchase/order' ";

            } else if ($trantype == 'purchase/delivery') {
                if ($subtype === '2') {
                    $where .= " AND A.trantype = 'purchase/order' ";
                } else if ($subtype === '1') {
                    $where .= " AND A.trantype = 'sales/order' ";
                }

            } else if ($trantype == 'sales/item') {
                $where .= " AND A.trantype = 'sales/delivery' ";

            } else if ($trantype == 'stock/in') {
                $where .= " AND A.trantype = 'purchase/delivery' ";

            } else if ($trantype == 'stock/return') {
                $where .= " AND A.trantype = 'sales/return' ";

            } else {
                $where .= " AND A.trantype = '" . addslashes($trantype) . "' ";
            }
        }

        $join = "";
        if ($trantype == 'sales/order') {
            $join .=
                "LEFT JOIN (
                SELECT td.tranid, SUM(td.amount) AS total_amount
                FROM trandetails td
                LEFT JOIN trans t ON t.tranid = td.tranid
                WHERE t.trantype = 'sales/order'
                AND t.status = '1'
                AND td.status <> '10'
                AND t.statuspro = '10'
                GROUP BY td.tranid 
            ) so_qty ON so_qty.tranid = A.tranid

            LEFT JOIN (
                SELECT r.refid AS tranid, SUM(td.amount) + SUM(td.amount2) AS delivered_amount
                FROM trans r
                JOIN trandetails td ON td.tranid = r.tranid
                WHERE r.trantype = 'sales/delivery'
                AND r.status <> '10' AND td.status <> '10'
                AND EXISTS (
                    SELECT 1 FROM trandetails so_det 
                    LEFT JOIN trans o ON o.tranid = so_det.tranid
                    WHERE o.tranid = r.refid 
                    AND o.trantype ='sales/order'
                    AND so_det.productid = td.productid
                    AND so_det.status <> '10'
                )
                GROUP BY r.refid
            ) del_qty ON del_qty.tranid = A.tranid
            ";

            $where .= " AND COALESCE(so_qty.total_amount, 0) > COALESCE(del_qty.delivered_amount, 0)
             AND so_qty.tranid IS NOT NULL";
        } else if ($trantype == 'sales/return') {
            $join .= "
            LEFT JOIN (
                SELECT B.refid as so_tranid, B.tranid as sd_tranid, 
                     COALESCE(SUM(A.amount),0) + COALESCE(SUM(A.amount2),0) as total_outstock
                FROM trandetails A
                INNER JOIN trans B ON A.tranid=B.tranid 
                AND coalesce(B.status,0) = 1
                AND coalesce(A.status,0) <> 10 
                AND B.trantype='sales/delivery'
                GROUP BY B.refid, B.tranid
            ) del_qty ON del_qty.so_tranid = A.tranid

            LEFT JOIN (
                SELECT B.refid as so_tranid, 
                COALESCE(SUM(A.amount),0) + COALESCE(SUM(A.amount2),0) as total_return
                FROM trandetails A
                INNER JOIN trans B ON A.tranid=B.tranid AND coalesce(B.status,0) = 1
                WHERE B.trantype = '$target'
                AND coalesce(A.status, 0) <> 10 
                AND coalesce(B.status, 0) <> 10
                GROUP BY B.refid
            ) ret_qty ON ret_qty.so_tranid = A.tranid

            LEFT JOIN (
                SELECT sr.refid AS so_tranid, SUM(td.amount) + SUM(td.amount2) AS total_return
                FROM trans sr
                JOIN trandetails td ON td.tranid = sr.tranid
                    AND COALESCE(td.status, 0) <> 10
                WHERE sr.trantype = 'purchase/delivery'
                AND sr.reftype = 'sales/order'
                AND COALESCE(sr.status, 0) <> 10
                GROUP BY sr.refid
            ) so_retpd ON so_retpd.so_tranid = A.tranid

            LEFT JOIN (
                SELECT COUNT(tranvariantid) as count, refid as tranid 
                FROM tranvariants 
                WHERE type='2' AND status <> 10 
                GROUP BY tranid
            ) AS item_count ON item_count.tranid = del_qty.sd_tranid
            ";

            $where .= " AND A.statuspro IN ('5', '10') AND (
                COALESCE(item_count.count, 0) >= COALESCE(del_qty.total_outstock, 0)
            ) AND del_qty.total_outstock > 0 
            AND (COALESCE(ret_qty.total_return, 0) + COALESCE(so_retpd.total_return, 0)) < COALESCE(del_qty.total_outstock, 0)            
            ";
        } else if ($trantype == 'purchase/request') {
            $join .= "
                LEFT JOIN (
                SELECT A.tranid,
                    SUM(A.amount2) as total_amount2
                    
                FROM trandetails A
                INNER JOIN trans B ON A.tranid=B.tranid
                    AND coalesce(B.status,0) = 1
                    AND coalesce(A.status,0) <> 10
                    AND B.trantype='sales/order'
                GROUP BY A.tranid
            ) so_qty ON so_qty.tranid = A.tranid
            ";
            $where .= " AND EXISTS (
            SELECT 1 
            FROM trandetails td_so
            LEFT JOIN (
                SELECT pr_trans.refid, pr_det.productid, SUM(pr_det.amount) as total_pr2
                FROM trans pr_trans
                JOIN trandetails pr_det ON pr_det.tranid = pr_trans.tranid
                WHERE pr_trans.trantype IN ('purchase/request', 'purchase/order')
                AND coalesce(pr_trans.status, 0) <> 10
                GROUP BY pr_trans.refid, pr_det.productid
            ) pr_qty ON pr_qty.refid = A.tranid AND pr_qty.productid = td_so.productid
            
            WHERE td_so.tranid = A.tranid 
            AND coalesce(td_so.status, 0) <> 10
            AND td_so.amount2 < 0
            AND (ABS(td_so.amount2) - COALESCE(pr_qty.total_pr2,0)) > 0
        )";

        } else if ($trantype == 'purchase/order') {
            $join .=
                " LEFT JOIN (
                SELECT A.tranid,
                    SUM(A.amount) AS total_amount
                FROM trandetails A
                INNER JOIN trans B ON A.tranid=B.tranid
                    AND coalesce(B.status,0) = 1 AND coalesce(A.status,0) <> 10
                    AND B.trantype='purchase/request'
                GROUP BY A.tranid
            ) pr_qty ON pr_qty.tranid = A.tranid
            ";

            $where .= "AND EXISTS
            (SELECT 1 
            FROM trandetails td  

            LEFT JOIN (
            SELECT po_trans.refid, po_det.productid, SUM(po_det.amount) as total_po
            FROM trans po_trans
            JOIN trandetails po_det ON po_det.tranid = po_trans.tranid
            WHERE po_trans.trantype = 'purchase/order'
            AND coalesce(po_trans.status, 0) <> 10
            GROUP BY po_trans.refid, po_det.productid
            ) po_qty ON po_qty.refid = A.tranid AND po_qty.productid = td.productid

            WHERE td.tranid = A.tranid
            AND coalesce(td.status, 0) <> 10
            AND td.amount > COALESCE(po_qty.total_po, 0)
           
            )";

        } else if ($trantype == 'purchase/return') {
            $join .=
                " LEFT JOIN (
                SELECT A.tranid,
                    SUM(A.amount2) as total_amount2,
                    SUM(A.amount) AS total_amount
                FROM trandetails A
                INNER JOIN trans B ON A.tranid=B.tranid
                    AND coalesce(B.status,0) = 1
                    AND coalesce(A.status,0) <> 10
                    AND B.trantype='purchase/order'
                GROUP BY A.tranid
            ) pr_qty ON pr_qty.tranid = A.tranid
            ";

            $where .= "AND EXISTS (SELECT 1 FROM trandetails td 
            LEFT JOIN (
                SELECT r.refid AS tranid, td.productid, SUM(td.amount) AS returned_amount
                FROM trans r
                JOIN trandetails td ON td.tranid = r.tranid
                WHERE r.trantype = 'purchase/return'
                AND coalesce(r.status, 0) <> 10
                GROUP BY r.refid, td.productid
            ) ret_qty ON ret_qty.tranid = td.tranid AND ret_qty.productid = td.productid
            WHERE td.tranid = A.tranid
            AND coalesce(td.status, 0) <> 10
            AND td.amount > COALESCE(ret_qty.returned_amount, 0)
            )";

        } else if ($trantype == 'purchase/delivery') {
            $join .= "
                LEFT JOIN (
                SELECT r.refid AS tranid, tdx.productid, SUM(tdx.amount) AS total_delivered
                FROM trans r
                JOIN trandetails tdx ON tdx.tranid = r.tranid
                WHERE r.trantype = '$target'
                AND r.status <> '10'
                GROUP BY r.refid, tdx.productid
            ) del_qty_po ON del_qty_po.tranid = A.tranid

            LEFT JOIN (
                SELECT COUNT(tv.tranvariantid) AS count, sd3.refid AS so_tranid
                FROM tranvariants tv
                JOIN trans sd3 ON sd3.tranid = tv.refid
                    AND sd3.trantype = 'sales/delivery'
                    AND COALESCE(sd3.status, 0) = 1
                WHERE tv.type = '2' AND COALESCE(tv.status, 0) <> 10
                GROUP BY sd3.refid
            ) item_count ON item_count.so_tranid = A.tranid
            ";
            if ($subtype === '2') {
                $where .= " AND (
                    A.trantype = 'purchase/order'
                    AND (
                            A.refid IS NULL 
                            OR A.refid = '' 
                            OR EXISTS (
                                SELECT 1 FROM trans so_origin 
                                WHERE so_origin.tranid = A.refid 
                                AND so_origin.trantype IN ('sales/order', 'purchase/request')
                                AND so_origin.status = '1'
                            )
                        )
                    AND EXISTS (
                        SELECT 1
                        FROM trandetails td1
                        LEFT JOIN (
                            SELECT r.refid, tdx.productid, SUM(tdx.amount) AS total_delivered
                            FROM trans r
                            JOIN trandetails tdx ON tdx.tranid = r.tranid
                            WHERE r.trantype = '$target'
                            AND r.status <> '10'
                            AND r.refid = A.tranid
                            GROUP BY r.refid, tdx.productid
                        ) dq ON dq.productid = td1.productid
                        WHERE td1.tranid = A.tranid
                        AND td1.status <> 10
                        AND COALESCE(td1.amount, 0) > COALESCE(dq.total_delivered, 0)
                    )
                    AND NOT EXISTS (
                        SELECT 1
                        FROM trans ptr 
                        WHERE ptr.refid = A.tranid
                        AND ptr.trantype = 'purchase/return'
                        AND COALESCE(ptr.status, 0) <> 10
                    )
                ) ";
            } else if ($subtype === '1') {
                $join .= "
                LEFT JOIN (
                    SELECT sd.refid AS so_tranid,
                        SUM(td.amount) + SUM(td.amount2) AS total_outstock
                    FROM trans sd
                    JOIN trandetails td ON td.tranid = sd.tranid AND COALESCE(td.status, 0) <> 10
                    WHERE sd.trantype = 'sales/delivery' AND COALESCE(sd.status, 0) = 1
                    GROUP BY sd.refid
                ) del_qty ON del_qty.so_tranid = A.tranid

                LEFT JOIN (
                    SELECT sr.refid AS so_tranid, SUM(td.amount) + SUM(td.amount2) AS total_return
                    FROM trans sr
                    JOIN trandetails td ON td.tranid = sr.tranid AND COALESCE(td.status, 0) <> 10
                    WHERE sr.trantype = 'sales/return' AND COALESCE(sr.status, 0) <> 10
                    GROUP BY sr.refid
                ) ret_qty ON ret_qty.so_tranid = A.tranid

                LEFT JOIN (
                    SELECT sr.refid AS so_tranid, SUM(td.amount) + SUM(td.amount2) AS total_return
                    FROM trans sr
                    JOIN trandetails td ON td.tranid = sr.tranid AND COALESCE(td.status, 0) <> 10
                    WHERE sr.trantype = 'purchase/delivery' AND sr.reftype = 'sales/order'
                    AND COALESCE(sr.status, 0) <> 10
                    GROUP BY sr.refid
                ) so_retpd ON so_retpd.so_tranid = A.tranid
            ";

                $where .= " AND (
                A.trantype = 'sales/order'
                AND A.statuspro IN ('5', '10')
                AND (COALESCE(ret_qty.total_return, 0) + COALESCE(so_retpd.total_return, 0)) < COALESCE(del_qty.total_outstock, 0)
            ) ";

            }
        } else if ($trantype == 'sales/item') {

            $join .=
                "LEFT JOIN (
            SELECT B.refid as tranid,
            (SUM(A.amount) + SUM(A.amount2)) as amount_outstock
            FROM trandetails A
            INNER JOIN trans B ON A.tranid=B.tranid
                AND coalesce(B.status, 0)=1
                AND B.trantype ='sales/delivery'
            GROUP BY B.refid
          ) del_qty ON del_qty.tranid = A.refid
       
            LEFT JOIN (
               SELECT count(tv.tranvariantid) as amount_scan, tr.refid as tranid
               FROM tranvariants tv
               LEFT JOIN trans t ON t.tranid = tv.refid 
               LEFT JOIN trans tr ON t.refid = tr.tranid

               WHERE coalesce(t.status,0) = 1 AND coalesce(tv.status,0) <> 10
               GROUP BY tr.refid
            ) sc_qty ON sc_qty.tranid = A.tranid

            LEFT JOIN (
            SELECT o.tranid, o.statuspro
            FROM trans o
            WHERE o.trantype = 'sales/order' AND o.status = '1'
            AND o.statuspro = '10'
            ) ord ON ord.tranid = del_qty.tranid

           ";

            $where .= " AND COALESCE(del_qty.amount_outstock,0) > COALESCE(sc_qty.amount_scan,0)  AND ord.tranid IS NOT NULL ";
        } else if ($trantype == 'stock/return') {

            $join .=
                "LEFT JOIN (
            SELECT B.refid as tranid,
            SUM(A.amount)+SUM(A.amount2) as amount_outstock
            FROM trandetails A
            INNER JOIN trans B ON A.tranid=B.tranid
                AND coalesce(B.status,0)=1
                AND B.trantype='sales/return'
            GROUP BY B.refid
          ) del_qty ON del_qty.tranid = A.refid
       
            LEFT JOIN (
               SELECT count(tv.tranvariantid) as amount_scan, tr.refid as tranid
               FROM tranvariants tv
               LEFT JOIN trans t ON t.tranid = tv.refid 
               LEFT JOIN trans tr ON t.refid = tr.tranid

               WHERE coalesce(t.status,0) = 1 AND coalesce(tv.status,0) <> 10
               AND t.trantype = 'sales/return'
               GROUP BY tr.refid
            ) sc_qty ON sc_qty.tranid = A.tranid

             LEFT JOIN (
            SELECT o.tranid, o.statuspro
            FROM trans o
            WHERE o.trantype = 'sales/order' AND o.status = '1'
            AND o.statuspro = '10'
            ) ord ON ord.tranid = del_qty.tranid
           ";
            $where .= " AND COALESCE(del_qty.amount_outstock, 0) > COALESCE(sc_qty.amount_scan, 0) AND ord.tranid IS NOT NULL ";

        } else if ($trantype == 'stock/in') {
            $join .=
                "LEFT JOIN (
                SELECT B.refid as tranid,
                SUM(A.amount) as amount_in
                FROM trandetails A
                INNER JOIN trans B ON A.tranid=B.tranid
                    AND coalesce(B.status,0)=1 AND
                    B.reftype IN ('sales/order', 'purchase/request')
                    AND B.trantype='purchase/delivery'
                GROUP BY B.refid
            ) in_qty ON in_qty.tranid = A.refid

            LEFT JOIN (
               SELECT count(tv.tranvariantid) as amount_scan, tr.refid as tranid
               FROM tranvariants tv
               LEFT JOIN trans t ON t.tranid = tv.refid 
               LEFT JOIN trans tr ON t.refid = tr.tranid

               WHERE coalesce(t.status,0) = 1 AND coalesce(tv.status,0) <> 10
               AND tv.type ='3' AND t.reftype = 'sales/order'
               AND t.trantype = 'purchase/delivery'
               GROUP BY tr.refid
            ) sc_qty ON sc_qty.tranid = A.tranid

           ";
            $where .= " AND (COALESCE(in_qty.amount_in, 0) > COALESCE(sc_qty.amount_scan, 0)
             OR (A.refid IS NULL AND COALESCE(sc_qty.amount_scan, 0) = 0)) ";

            $where .= " AND (
                (A.reftype = 'purchase/request' AND A.eventtype = '0')
                OR 
                (A.reftype = 'sales/order' AND EXISTS (
                    SELECT 1 FROM trans so_origin 
                    WHERE so_origin.tranid = A.refid 
                    AND so_origin.trantype = 'sales/order' 
                    AND so_origin.statuspro IN ('5', '10')
                ))
            ) ";

        } else if ($trantype == 'sales/invoice') {
            $join .= "
            LEFT JOIN (
                SELECT td.tranid, SUM(COALESCE(td.amount, 0)) AS so_total_amount
                FROM trandetails td
                JOIN trans t ON t.tranid = td.tranid
                WHERE t.trantype = 'sales/order'
                AND t.status = '1' AND t.statuspro IN ('1', '5', '10')
                AND td.status <> '10'
                GROUP BY td.tranid 
            ) so_sum ON so_sum.tranid = A.tranid

            LEFT JOIN (
                SELECT inv_trans.refid, SUM(COALESCE(inv_det.amount, 0)) AS inv_total_amount
                FROM trans inv_trans
                JOIN trandetails inv_det ON inv_det.tranid = inv_trans.tranid
                WHERE inv_trans.trantype = 'sales/invoice' 
                AND inv_trans.status = '1' 
                AND inv_det.status <> '10'
                GROUP BY inv_trans.refid
            ) inv_sum ON inv_sum.refid = A.tranid
        ";
            $where .= " AND COALESCE(so_sum.so_total_amount, 0) > COALESCE(inv_sum.inv_total_amount, 0) ";

        }

        $limit = (int) ($_POST['limit'] ?? 5);
        $page = (int) ($_POST['page'] ?? 1);
        $offset = ($page - 1) * $limit;

        $sql =
            "SELECT 
            A.tranid AS id, A.tranno AS text,
            A.trantype AS reftype, 
            A.reftype AS reftype2,
            A.eventtype AS eventtype,
            A.trandate, A.locations, A.eventname, 
            COALESCE(c.contact_name, '') AS contact_name, 
            COALESCE(c.jobcompany, '') AS jobcompany,
            COALESCE(c.contact_name, cr.contact_name) AS name, 
            COALESCE(c.jobcompany, cr.jobcompany) AS company,
            COALESCE(co.contact_name, cr.contact_name) AS ref_name,
            COALESCE(co.jobcompany, cr.jobcompany) AS ref_company,
            COALESCE(con.contact_name, '') AS contactpd, 
            COALESCE(con.jobcompany, '') AS companypd,
            COALESCE(po_pinjam.tranid, '') AS po_tranid,
            COALESCE(pd.tranid, '') AS pd_tranid
            
            FROM trans A
            LEFT JOIN contacts c ON A.contact_id = c.contact_id AND c.contact_status <> '10'
            LEFT JOIN trans r ON r.refid = A.tranid AND r.status = '1' AND r.trantype = 'sales/return'
            LEFT JOIN contacts cr ON r.contact_id = cr.contact_id AND cr.contact_status <> '10'
            LEFT JOIN trans d ON d.tranid = A.refid AND d.status = '1' AND d.trantype = 'sales/delivery'
            LEFT JOIN trans o ON o.tranid = A.refid AND o.status = '1' AND o.trantype = 'sales/order'
            LEFT JOIN contacts co ON o.contact_id = co.contact_id AND co.contact_status <> '10'
            LEFT JOIN trans po ON po.tranid = A.refid AND po.status = '1' AND po.trantype = 'purchase/order'
            LEFT JOIN trans so ON so.tranid = po.refid AND so.status = '1' AND so.trantype = 'purchase/request'
            LEFT JOIN contacts con ON so.contact_id = con.contact_id AND con.contact_status <> '10'
            LEFT JOIN trans po_pinjam ON po_pinjam.refid = A.tranid AND po_pinjam.status = '1' AND po_pinjam.trantype = 'purchase/order'
            LEFT JOIN trans pd ON pd.refid = po_pinjam.tranid AND pd.status = '1' AND pd.trantype = 'purchase/delivery'
            $join
            $where
            GROUP BY A.tranid, A.tranno, A.trandate, A.locations, A.eventname, c.contact_name, c.jobcompany, 
                cr.contact_name, cr.jobcompany, co.contact_name, co.jobcompany, con.contact_name, con.jobcompany,
                 po_pinjam.tranid, pd.tranid
            ORDER BY A.createdat DESC
            LIMIT $limit OFFSET $offset
         ";
        // echo ($sql);die();

        $sqlcount =
            "SELECT COUNT(*) FROM (
            SELECT A.tranid
            FROM trans A
            $join
            $where
            GROUP BY A.tranid) x
        ";

        $out['items'] = Yii::$app->db->createCommand($sql)->queryAll();
        $out['totalcount'] = Yii::$app->db->createCommand($sqlcount)->queryScalar();

        return $out;
    }
    public function actionInvoice($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $invoice = Tran::findOne($id);
        if (!$invoice) {
            return ['modeldetails' => []];
        }

        $modeldetails = Yii::$app->db->createCommand(
            "SELECT
            td.trandetailid,
            td.amount,
            p.productname,
            td.productid
        FROM trandetails td
        LEFT JOIN products p ON p.productid = td.productid::uuid
        WHERE td.tranid = :tranid
    "
        )->bindValue(':tranid', $id)->queryAll();

        return [
            'modeldetails' => $modeldetails
        ];
    }
    public function actionListdetail()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->request->queryParams;

        $id = $params['id'] ?? '';
        $select = $params['select'] ?? '';
        $search = $params['search'] ?? '';
        $module = $params['module'] ?? 'purchase';
        $type = $params['type'] ?? '';
        $length = $params['length'] ?? 10;
        $start = $params['start'] ?? 0;
        $draw = $params['draw'] ?? 1;
        $trantype = "$module/$type";

        $query = "";
        $join = "";
        $filter = " WHERE t.status <> 10 AND t.tranid = '$id'";
        $group = "";
        $countField = "td.trandetailid";
        $orderBy = " ORDER BY td.ord ASC";

        if ($select == 'send') {
            $query = "SELECT 
                td.trandetailid, 
                p.productname, 
                td.amount AS send,
                td.amount2 AS send2,
                tdi.amount AS qty,
                tdi.refid,
                (COALESCE(
                    SUM(
                        CASE 
                            WHEN d.trantype = 'sales/delivery' THEN tda.amount
                            ELSE 0
                        END
                    ), 
                0) + 
                COALESCE(
                    SUM(
                        CASE 
                            WHEN d.trantype = 'sales/delivery' THEN tda.amount2
                            ELSE 0
                        END
                    ), 
                0)) AS sent,
                (tdi.amount - (COALESCE(
                    SUM(
                        CASE 
                            WHEN d.trantype = 'sales/delivery' THEN tda.amount
                            ELSE 0
                        END
                    ), 
                0) + 
                COALESCE(
                    SUM(
                        CASE 
                            WHEN d.trantype = 'sales/delivery' THEN tda.amount2
                            ELSE 0
                        END
                    ), 
                0))) AS remaining
                FROM trans t";

            $join = " LEFT JOIN trandetails td ON td.tranid = t.tranid
                LEFT JOIN trandetails tdi ON tdi.trandetailid = td.refid 
                LEFT JOIN trandetails tda ON tda.refid = tdi.trandetailid
                LEFT JOIN trans d ON d.tranid = tda.tranid AND d.status <> 10
                LEFT JOIN products p ON p.productid = td.productid ";

            $group = " GROUP BY 
                td.trandetailid,
                p.productname,
                td.amount,
                tdi.amount,
                tdi.refid";
        } elseif ($select == "return") {
            $query = "SELECT 
                t.tranid,
                t.tranno,
                t.trandate,
                td.trandetailid, 
                p.productname, 
                td.amount AS return,
                td.amount2 AS return2,
                pending_scan.pending_barcodes as pending_barcodes,
                pending_scan.statuspro as statuspro,
                COALESCE(scan_qty.total_scan, 0) AS total_scan,
                COALESCE(td.amount, 0) + COALESCE(td.amount2, 0) AS total_return

                FROM trans t ";
            $join =
                " INNER JOIN trandetails td ON td.tranid = t.tranid
                INNER JOIN products p ON p.productid = td.productid

                LEFT JOIN (
                    SELECT 
                    tv.trandetailid,
                    COUNT(tv.tranvariantid) AS total_scan
                FROM tranvariants tv
                INNER JOIN trans t ON t.tranid = tv.refid AND t.status <> 10
                WHERE COALESCE(tv.status,0) <> 10
                AND tv.refid IS NOT NULL
                AND tv.type = '3'
                GROUP BY tv.trandetailid
             ) AS scan_qty ON scan_qty.trandetailid = td.trandetailid

             LEFT JOIN (
                SELECT 
                    tv_out.productid,
                    so.tranid AS so_tranid,  
                    so.statuspro,
                    STRING_AGG(DISTINCT tv_out.barcode, '/ ') AS pending_barcodes
                FROM tranvariants tv_out
                INNER JOIN trans tr_del ON tr_del.tranid = tv_out.refid 
                    AND tr_del.status <> 10 AND tr_del.trantype = 'sales/delivery'

                    INNER JOIN trans so 
                    ON so.tranid = tr_del.refid 
                    AND so.status <> 10
                    AND so.trantype = 'sales/order'

                    WHERE tv_out.type = '2'
                    AND COALESCE(tv_out.status, 0) <> 10

                    AND NOT EXISTS (
                        SELECT 1 
                        FROM tranvariants tv_in
                        INNER JOIN trans tr_ret ON tr_ret.tranid = tv_in.refid 
                            AND tr_ret.status <> 10 AND tr_ret.trantype = 'sales/return'
                        WHERE tv_in.type = '3'
                            AND COALESCE(tv_in.status, 0) <> 10
                            AND tv_in.barcode = tv_out.barcode
                            AND tr_ret.refid = so.tranid
                    )
                GROUP BY tv_out.productid, so.tranid
            ) AS pending_scan 
                ON pending_scan.productid = td.productid 
                AND pending_scan.so_tranid = t.refid  
                AND td.status <> 10
        ";
            $filter = " WHERE t.tranid = '$id' AND t.status <> 10 AND td.status <> 10";
        } elseif ($select == 'received') {
            $query = "SELECT 
                t.tranid,
                td.trandetailid, 
                p.productname, 
                td.remain2 AS received,
                td.amount AS qty
                FROM trans t";

            $join = " LEFT JOIN trandetails td ON td.tranid = t.tranid 
                LEFT JOIN products p ON p.productid = td.productid ";
        } elseif ($select == 'pr') {
            $query = "SELECT 
                td.trandetailid,
                td.productid,
                p.productname,
                td.description,
                td.price,
                td.itemsubtotaltax,
                td.itemdiscpersen,
                td.itemtaxid,
                t.totalafterdisc,
                td.itemtotaltax,
                t.subtotal,
                t.grandtotal,
                COALESCE(t.priceincludetax, 0) as priceincludetax,
                td.amount2,
                (COALESCE(td.amount2, 0) * -1) - COALESCE(pr_qty.total_pr2, 0) AS remaining
                FROM trans t ";

            $join =
                " INNER JOIN trandetails td ON td.tranid = t.tranid 
            INNER JOIN products p ON p.productid = td.productid
            LEFT JOIN (
               SELECT pr_trans.refid, pr_det.productid, SUM(pr_det.amount) as total_pr2
               FROM trans pr_trans
                JOIN trandetails pr_det ON pr_det.tranid = pr_trans.tranid
                WHERE pr_trans.trantype = 'purchase/request' 
                AND coalesce(pr_trans.status, 0) <> 10
                GROUP BY pr_trans.refid, pr_det.productid
            ) pr_qty ON pr_qty.refid = t.tranid AND pr_qty.productid = td.productid
            ";

            $filter .= " AND td.amount2 < 0 
             AND td.productid IS NOT NULL 
             AND (ABS(td.amount2) > COALESCE(pr_qty.total_pr2,0))
             ";
        } elseif ($select == 'po') {
            $query = "SELECT 
                td.trandetailid,
                td.productid,
                p.productname,
                td.description,
                td.price,
                t.eventtype,
                t.trandate,
                c.contact_id,
                c.jobcompany,
                td.itemsubtotaltax,
                td.itemdiscpersen,
                td.itemtaxid,
                td.itemtotaltax,
                t.totalafterdisc,
                t.subtotal,
                t.grandtotal,
                COALESCE(t.priceincludetax, 0) as priceincludetax,
                ((COALESCE(td.amount, 0) - COALESCE(po_qty.total_po, 0))) AS remaining,
                td.amount
                FROM trans t ";

            $join = " INNER JOIN trandetails td ON td.tranid = t.tranid 
                INNER JOIN products p ON p.productid = td.productid 
                LEFT JOIN contacts c ON c.contact_id = t.contact_id

                LEFT JOIN(
                    SELECT po_trans.refid, po_det.productid, SUM(po_det.amount) as total_po
                    FROM trans po_trans
                    JOIN trandetails po_det ON po_det.tranid = po_trans.tranid
                    WHERE po_trans.trantype = 'purchase/order'
                    AND coalesce(po_trans.status, 0) <> 10
                    GROUP BY po_trans.refid, po_det.productid
                ) po_qty ON po_qty.refid = t.tranid AND po_qty.productid = td.productid
                ";
        } elseif ($select == 'ptr') {
            $query = "SELECT 
                td.trandetailid,
                td.productid,
                p.productname,
                td.description,
                td.price,
                t.eventtype,
                t.trandate,
                c.contact_id,
                c.contact_name,
                c.jobcompany,
                td.itemsubtotaltax,
                td.itemdiscpersen,
                td.itemtaxid,
                t.totalafterdisc,
                td.itemtotaltax,
                t.subtotal,
                t.grandtotal,
                COALESCE(t.priceincludetax, 0) as priceincludetax,
                ((COALESCE(td.amount, 0) - COALESCE(ret_qty.returned_amount, 0))) AS remaining,
                td.amount
                FROM trans t ";

            $join = " INNER JOIN trandetails td ON td.tranid = t.tranid 
                INNER JOIN products p ON p.productid = td.productid 
                LEFT JOIN contacts c ON c.contact_id = t.contact_id 
                LEFT JOIN (
                    SELECT r.refid AS tranid, td.productid, SUM(td.amount) AS returned_amount
                    FROM trans r
                    JOIN trandetails td ON td.tranid = r.tranid
                    WHERE r.trantype = 'purchase/return'
                    AND coalesce(r.status, 0) <> 10
                    GROUP BY r.refid, td.productid
                ) ret_qty ON ret_qty.tranid = t.tranid AND ret_qty.productid = td.productid
                ";
        } elseif ($select == 'inv') {
            $query = "SELECT 
                td.trandetailid,
                td.productid,
                p.productname,
                c.contact_id,
                c.contact_name,
                c.jobcompany,
                td.description,
                td.price,
                td.freqvalue,
                td.itemsubtotaltax,
                td.itemdiscpersen,
                td.itemtaxid,
                t.totalafterdisc,
                td.itemtotaltax,
                t.subtotal,
                t.grandtotal,
                COALESCE(t.priceincludetax, 0) as priceincludetax,
                td.amount,
                (COALESCE(td.amount, 0) - COALESCE(pr_qty.total_pr2, 0)) AS remaining
                FROM trans t ";

            $join =
                " INNER JOIN trandetails td ON td.tranid = t.tranid 
                INNER JOIN products p ON p.productid = td.productid
                LEFT JOIN contacts c ON c.contact_id = t.contact_id 
                LEFT JOIN (
                SELECT pr_trans.refid, pr_det.productid, SUM(pr_det.amount) as total_pr2
                FROM trans pr_trans
                    JOIN trandetails pr_det ON pr_det.tranid = pr_trans.tranid
                    WHERE pr_trans.trantype = 'sales/invoice' 
                    AND coalesce(pr_trans.status, 0) <> 10
                    GROUP BY pr_trans.refid, pr_det.productid
                ) pr_qty ON pr_qty.refid = t.tranid AND pr_qty.productid = td.productid
                ";

        } else if ($select == 'tab') {
            $query =
                "SELECT
                td.trandetailid,
                td.productid,
                p.productname,
                td.description,
                td.price,
                SUM(td.amount) AS amount,
                td.itemsubtotaltax,
                td.itemdiscpersen,
                td.itemtaxid,
                t.totalafterdisc,
                td.itemtotaltax,
                t.subtotal,
                t.grandtotal,
                COALESCE(t.priceincludetax, 0) as priceincludetax
                FROM trans t ";
            $join
                = " INNER JOIN trandetails td ON td.tranid = t.tranid
                    INNER JOIN products p ON p.productid = td.productid ";

            $filter =
                " WHERE td.status <> 10
                    AND ((t.tranid = '$id' AND t.trantype = 'sales/order' AND t.status <> 10) 
                        OR 
                        (t.refid = '$id' AND t.trantype = 'purchase/order' 
                         AND t.status = '1'
                        AND td.productid NOT IN (
                            SELECT so_td.productid 
                            FROM trans so_t
                            INNER JOIN trandetails so_td ON so_td.tranid = so_t.tranid
                            WHERE so_t.tranid = '$id' 
                            AND so_t.trantype = 'sales/order' 
                            AND so_t.status <> 10 AND so_td.status <> 10
                        )))";

            $group = " GROUP BY td.trandetailid, td.productid, p.productname, td.description, 
                td.price, td.itemsubtotaltax, td.itemdiscpersen, td.itemtaxid, 
                t.totalafterdisc, td.itemtotaltax, t.subtotal, t.grandtotal, 
                t.priceincludetax, t.trantype ";

            $orderBy = " ORDER BY (CASE WHEN t.trantype = 'sales/order' THEN 0 ELSE 1 END) ASC, td.ord ASC";
        } else {
            $query = "SELECT 
                td.trandetailid,
                td.description,
                p.productname,
                td.amount
                FROM trans t ";

            $join = " INNER JOIN trandetails td ON td.tranid = t.tranid
                INNER JOIN products p ON p.productid = td.productid ";

            $group = " GROUP BY td.trandetailid, p.productname, td.amount, td.ord, td.description, t.trantype ";

            $orderBy = " ORDER BY (CASE WHEN t.trantype = 'sales/order' THEN 0 ELSE 1 END) ASC, td.ord ASC ";
        }

        if ($type !== '' && $module !== '') {
            $filter .= " AND t.trantype = '" . addslashes($trantype) . "'";
        }

        if (isset($search["value"]) && $search["value"] !== '') {
            $safeSearch = addslashes($search["value"]);
            $filter .= " AND p.productname ILIKE '%$safeSearch%'";
        }

        $query = $query . $join . $filter . $group . $orderBy . " LIMIT " . $length . " OFFSET " . $start;
        // echo ($query);exit;

        $data = Yii::$app->db->createCommand($query)->queryAll();

        $recordsTotal = (int) Yii::$app->db->createCommand(
            "SELECT COUNT(*) FROM (SELECT $countField FROM trans t $join $filter $group) AS total"
        )->queryScalar();

        return [
            "draw" => intval($draw),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsTotal,
            "data" => $data ?: [],
            'pagination' => [
                'more' => ($length + $start) < $recordsTotal,
            ],
        ];
    }
    public function actionListbarcode()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->request->queryParams;

        $id = $params['id'] ?? '';
        $search = $params['search'] ?? '';
        $module = $params['module'] ?? 'purchase';
        $type = $params['type'] ?? '';
        $reftype2 = $params['reftype2'] ?? '';

        // var_dump($reftype2, $module, $type);exit;

        $trantype = "$module/$type";
        $sortcolumn = $params['order'][0]['column'] ?? 0;
        $ordercolumn = $params['columns'][$sortcolumn]['data'] ?? 'trandate';
        $columnorder = $params['order'][0]['dir'] ?? 'DESC';

        $date = $params['datefilter'] ?? '';

        if ($trantype == 'stock/return') {
            $trantype = 'sales/return';
        } else if ($trantype == 'sales/item') {
            $trantype = 'sales/delivery';
        } else if ($trantype == 'stock/in' && $reftype2 == 'sales/order') {
            $trantype = 'purchase/delivery';
        }

        $query = "";
        $alias = "";

        if ($trantype == 'stock/in' && $reftype2 == 'purchase/request') {
            $alias = "v";
            $query = "SELECT DISTINCT 
                p.productname,
                v.barcode,
                v.trandate,
                v.variantid as primaryid,
                t.tranid
            FROM variants v
            JOIN trandetails td ON td.productid = v.productid AND td.tranid = v.tranid 
            JOIN trans t ON t.tranid = td.tranid
            JOIN products p ON p.productid = td.productid
            WHERE t.trantype = 'purchase/delivery'
                AND t.status <> 10 AND v.status <> 10
            ";

        } else {
            $alias = "tv";
            $query = "SELECT
                p.productname,
                tv.barcode,
                tv.trandate,
                tv.tranvariantid as primaryid,
                t.tranid
            FROM tranvariants tv
            JOIN trandetails td ON td.trandetailid = tv.trandetailid
            JOIN trans t ON t.tranid = td.tranid
            JOIN products p ON p.productid = td.productid
            WHERE t.trantype='$trantype'
            AND t.status <> 10 AND tv.status <> 10
            ";
        }

        if (!empty($id)) {
            $safeId = addslashes($id);
            if ($trantype == 'stock/in' && $reftype2 == 'purchase/request') {
                $query .= " AND
             $alias.tranid = '$safeId' ";
            } else {
                $query .= " AND
             $alias.refid = '$safeId' ";
            }
        }

        if ($search !== '') {
            $safeSearch = addslashes($search);
            $query .= " AND (
            p.productname ILIKE '%$safeSearch%'
            OR t.tranno ILIKE '%$safeSearch%'
            OR $alias.barcode ILIKE '%$safeSearch%'
            )";
        }

        if ($date !== '') {
            $dates = explode(" - ", $date);
            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));
            $query .= " AND DATE(t.trandate) BETWEEN '$startDate' AND '$endDate'";
        }

        $query .= " ORDER BY $ordercolumn $columnorder";
        // echo $query;exit;

        $data = Yii::$app->db->createCommand($query)->queryAll();

        foreach ($data as &$row) {
            if (isset($row['trandate'])) {
                $row['trandate_display'] = Yii::$app->formatter->asDate($row['trandate'], 'php:d-m-Y');
            }
            if (isset($row['tranduedate'])) {
                $row['tranduedate_display'] = $row['tranduedate'] ? Yii::$app->formatter->asDate($row['tranduedate'], 'php:d-m-Y') : null;
            }
            if (isset($row['trantype'])) {
                $parts = explode('/', $row['trantype']);
                $row['module'] = $parts[0] ?? 'purchase';
                $row['type'] = $parts[1] ?? 'request';
            }
        }

        return [
            'data' => $data ?: [],
            'module' => $module,
            'type' => $type

        ];
    }
    public function actionSearchproductbybarcode()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $barcode = trim(Yii::$app->request->get('barcode', ''));
        // $type = trim(Yii::$app->request->get('type', ''));

        if (empty($barcode)) {
            return ['success' => false, 'message' => 'Barcode tidak boleh kosong'];
        }

        // $where = "";
        // if ($type === '2') {
        //     $where .= "AND v.locationid NOT IN ('location.4', '620e729b-483f-4527-b2f7-9dbf2719e884')";
        // } else if ($type === '3') {
        //     $where .= "AND v.locationid NOT IN ('location.4', '620e729b-483f-4527-b2f7-9dbf2719e884')";
        // }

        try {
            $sql =
                "SELECT
                v.productid,
                v.variantid,
                v.barcode,
                p.productname
            FROM variants v
            JOIN products p ON p.productid = v.productid AND p.status <> 10
            LEFT JOIN enum c ON c.enumid = v.condition AND c.enumtype ='condition'
            LEFT JOIN enum l ON l.enumid = v.locationid AND l.enumtype = 'location' 
            WHERE v.barcode = '$barcode' AND v.sku IS NULL
            AND v.status <> 10 AND c.enumtext_id = 'Baik' AND l.enumtext_id !~* 'Bengkel|Dijual'
         
            ";

            $product = Yii::$app->db->createCommand($sql)->queryOne();
            // echo($sql);exit;

            if (!$product) {
                return [
                    'success' => false,
                    'message' => 'Produk dengan barcode ' . $barcode . ' tidak ditemukan di Gudang'
                ];
            }

            return [
                'success' => true,
                'product' => [
                    'productid' => $product['productid'],
                    'productname' => $product['productname']
                ],
                'variant' => [
                    'variantid' => $product['variantid'],
                    'barcode' => $product['barcode'],
                    'trandetailid' => $product['trandetailid'],
                ]
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'debug' => [
                    'barcode' => $barcode,
                    'trace' => $e->getTraceAsString()
                ]
            ];
        }
    }
    public function actionSearchbarcodein()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $barcode = trim(Yii::$app->request->get('barcode', ''));

        if (empty($barcode)) {
            return ['success' => false, 'message' => 'Barcode tidak boleh kosong'];
        }

        try {
            $sql =
                "SELECT
                v.productid,
                v.variantid,
                v.barcode,
                p.productname
            FROM variants v
            JOIN products p ON p.productid = v.productid AND p.status <> 10
            WHERE v.barcode = '$barcode' AND v.sku IS NULL AND v.status <> 10
            ";

            $product = Yii::$app->db->createCommand($sql)->queryOne();
            // echo($sql);exit;

            if ($product) {
                return [
                    'success' => false,
                    'message' => 'Produk dengan barcode ' . $barcode . ' sudah terdaftar untuk produk ' . $product['productname']
                ];
            }

            return [
                'success' => true,
                'product' => [
                    'productid' => $product['productid'],
                    'productname' => $product['productname']
                ],
                'variant' => [
                    'variantid' => $product['variantid'],
                    'barcode' => $barcode
                ]
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'debug' => [
                    'barcode' => $barcode,
                    'trace' => $e->getTraceAsString()
                ]
            ];
        }
    }
    public function actionLoadref()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $refid = Yii::$app->request->get('refid');

        // Query utama
        $sql = "SELECT A.*, C.contact_name,
            A.aeid, A.operatorid, A.pic1id, A.pic2id, A.storemanid,
            AE.contact_name AS ae_name,
            OP.contact_name AS op_name,
            P1.contact_name AS pic1_name,
            P2.contact_name AS pic2_name,
            SM.contact_name AS storeman_name
            FROM trans A 
            LEFT JOIN contacts C ON A.contact_id = C.contact_id
            LEFT JOIN contacts AE ON A.aeid = AE.contact_id
            LEFT JOIN contacts OP ON A.operatorid = OP.contact_id
            LEFT JOIN contacts P1 ON A.pic1id = P1.contact_id
            LEFT JOIN contacts P2 ON A.pic2id = P2.contact_id
            LEFT JOIN contacts SM ON A.storemanid = SM.contact_id
            WHERE A.tranid = '" . $refid . "' 
            ORDER BY A.refid ASC LIMIT 1";
        $data = Yii::$app->db->createCommand($sql)->queryAll();

        // Query produk
        $sqldetail = "SELECT A.*, B.productid, P.productname
                  FROM trandetails A 
                  LEFT JOIN variants B ON A.variantid = B.variantid::text
                  LEFT JOIN products P ON B.productid = P.productid::text
                  WHERE A.tranid = '" . $refid . "' 
                  ORDER BY A.ord ASC";
        $datadetail = Yii::$app->db->createCommand($sqldetail)->queryAll();

        // Query event
        $sqlevent = "SELECT * FROM tranevents 
                 WHERE tranid = '" . $refid . "' 
                 ORDER BY ord ASC";
        $dataevent = Yii::$app->db->createCommand($sqlevent)->queryAll();

        // Query crew untuk setiap event
        foreach ($dataevent as &$event) {
            $sqlcrew = "SELECT B.*, C.contact_name as crewname 
                    FROM traneventcrews B
                    LEFT JOIN contacts C ON B.crewid = C.contact_id
                    WHERE B.traneventid = '" . $event['traneventid'] . "' 
                    ORDER BY B.crewtypeid ASC";
            $event['crews'] = Yii::$app->db->createCommand($sqlcrew)->queryAll();
        }

        return [
            'success' => true,
            'data' => array_values($data),
            'datadetail' => array_values($datadetail),
            'dataevent' => array_values($dataevent)
        ];
    }
    public function actionGetwhatsappnumber()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $tranid = Yii::$app->request->post('tranid');

        if (!$tranid) {
            return ['success' => false, 'message' => 'Transaction ID is required'];
        }

        $model = Tran::findOne($tranid);
        if (!$model) {
            return ['success' => false, 'message' => 'Data tidak ditemukan'];
        }

        $message = "*DETAIL TRANSAKSI*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";

        $customerCompany = $model->contact_id
            ? Yii::$app->function->findByField("jobcompany", "contacts", " AND contact_id ='{$model->contact_id}' ")
            : '-';

        $message .= "Nama perusahaan: {$customerCompany}\n";
        $message .= "Nama pelanggan: " . ($model->piccustomer ?? '-') . "\n";
        $message .= "No telp: " . ($model->piccustomer_telp ?? '-') . "\n";
        $message .= "Pic Lapangan 1: " . ($model->pic1 ?? '-') . "\n";
        $message .= "No. telp PIC lapangan: " . ($model->telppic1 ?? '-') . "\n";
        $message .= "Pic lapangan 2: " . ($model->pic2 ?? '-') . "\n";
        $message .= "No. telp PIC lapangan: " . ($model->telppic2 ?? '-') . "\n\n";

        $message .= "Tgl Mulai: " . ($model->trandate ? Yii::$app->formatter->asDate($model->trandate) : '-') . "\n";
        $message .= "Term: " . ($model->term ?? '-') . " Hari\n";
        $message .= "Tanggal selesai: " . ($model->tranduedate ? Yii::$app->formatter->asDate($model->tranduedate) : '-') . "\n\n";

        $eventTypes = ['Full Rent', 'Dry Rent', 'Sub Rent', 'Take Away'];
        $eventType = $eventTypes[$model->eventtype] ?? '-';

        $message .= "Lokasi Event: " . ($model->locations ?? '-') . "\n\n";
        $message .= "Nama Event: " . ($model->eventname ?? '-') . "\n";
        $message .= "Tipe Event: {$eventType}\n";
        $message .= "Tanggal setup: " . ($model->setupdate ? Yii::$app->formatter->asDatetime($model->setupdate) : '-') . "\n";
        $message .= "Tanggal Penarikan: " . ($model->withdrawaldate ? Yii::$app->formatter->asDatetime($model->withdrawaldate) : '-') . "\n\n";

        $details = Trandetail::find()->where(['tranid' => $tranid])->all();

        if ($details) {
            $message .= "Daftar Produk:\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━\n";

            foreach ($details as $i => $detail) {
                $productName = $detail->product->productname ?? '-';
                $message .= ($i + 1) . ". {$productName}\n";
                $message .= "   Qty: {$detail->amount} | Periode: {$detail->freqvalue} hari\n";

                if (!empty($detail->description)) {
                    $cleanDesc = str_replace(["\r", "\n"], ' ', $detail->description);
                    $message .= "   Deskripsi: {$cleanDesc}\n";
                }

                $message .= "\n";
            }
        }

        $pos = Tran::find()->where(['refid' => $tranid, 'trantype' => 'purchase/order', 'status' => 1])->all();

        if ($pos) {
            $message .= "Produk PO:\n";

            foreach ($pos as $poIndex => $po) {
                $vendorCompany = '-';

                if ($po->contact_id) {
                    $vendorCompany = Yii::$app->function->findByField("jobcompany", "contacts", " AND contact_id ='{$po->contact_id}' ") ?? '-';
                }

                $message .= "-Vendor: {$vendorCompany}\n";

                $poDetails = Trandetail::find()->where(['tranid' => $po->tranid])->all();

                if ($poDetails) {
                    foreach ($poDetails as $idx => $poDetail) {
                        $poProductName = $poDetail->product->productname ?? '-';
                        $message .= "  " . ($idx + 1) . ". {$poProductName}\n";
                        $message .= "     Qty: {$poDetail->amount}\n";

                        if (!empty($poDetail->description)) {
                            $cleanPoDesc = str_replace(["\r", "\n"], ' ', $poDetail->description);
                            $message .= "     Deskripsi: {$cleanPoDesc}\n";
                        }
                    }
                } else {
                    $message .= "  (Tidak ada detail produk PO)\n";
                }

                $message .= "\n";
            }
        }

        $events = Tranevent::find()->where(['tranid' => $tranid])->orderBy(['eventtypeid' => SORT_ASC])->all();

        if ($events) {
            $eventTypeNames = [
                0 => 'Setup',
                1 => 'Event',
                2 => 'Bongkar',
                3 => 'Antar',
                4 => 'Tarik'
            ];

            $crewTypeNames = [
                'pi' => 'PIC',
                'op' => 'Operator',
                'sb' => 'Standby',
                'cr' => 'Crew',
                'dr' => 'Driver',
                'fe' => 'Freelance',
            ];

            $message .= "Jadwal Kru:\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━\n";

            foreach ($events as $event) {
                $eventName = $eventTypeNames[$event->eventtypeid] ?? 'Unknown';

                $message .= "*{$eventName}*\n";

                $crews = Traneventcrew::find()
                    ->where(['traneventid' => $event->traneventid])
                    ->all();

                if ($crews) {
                    foreach ($crews as $crew) {
                        $crewType = $crewTypeNames[$crew->crewtypeid] ?? '-';

                        $crewName = '-';
                        $phone = '';

                        if ($crew->crewid) {
                            $crewName = Yii::$app->function->findByField(
                                "contact_name",
                                "contacts",
                                " AND contact_id ='{$crew->crewid}' "
                            );

                            $phoneRaw = Yii::$app->function->findByField(
                                "contact_phone1",
                                "contacts",
                                " AND contact_id ='{$crew->crewid}' "
                            );

                            if ($phoneRaw) {
                                $phone = preg_replace('/[^0-9]/', '', $phoneRaw);
                                if (str_starts_with($phone, '0')) {
                                    $phone = '62' . substr($phone, 1);
                                }
                            }
                        }

                        $message .= "- {$crewType}: {$crewName}";
                        if ($phone) {
                            $message .= " ({$phone})";
                        }
                        $message .= "\n";
                    }
                } else {
                    $message .= "- (Belum ada crew)\n";
                }

                $message .= "\n";
            }
        }

        if (!empty($model->note)) {
            $note = str_replace(["\r\n", "\n", "\r"], "\n", $model->note);
            $message .= "Catatan:\n";
            $message .= $note . "\n";
        }

        return [
            'success' => true,
            'message' => $message
        ];
    }
}
