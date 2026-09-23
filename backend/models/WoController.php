<?php

namespace backend\controllers;

use Yii;
use yii\db\Query;
use common\models\Wo;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\Variant;
use yii\filters\VerbFilter;
use common\models\WoDetail;
use common\models\VariantPrice;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * PurchaseController implements the CRUD actions for Wo model.
 */

class WoController extends Controller
{
    // $details = WoDetail::find()
    // ->with(['varian.product', 'wo.contact']) // Load related data
    // ->where(['woid' => $model->woid])
    // ->asArray()
    // ->all();

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
                        'actions' => ['request', 'create', 'update', 'detail', 'delete', 'list', 'getno', 'contactlist', 'varianlist', 'reflist', 'getprice', 'updatestatus', 'massaction', 'print', 'duplicate'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return true;
                        }
                    ],
                    [
                        'actions' => ['request'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("transaksi", "lihat"));
                        }
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("transaksi", "tambah"));
                        }
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("transaksi", "ubah"));
                        }
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("transaksi", "hapus"));
                        }
                    ],
                    [
                        'actions' => [
                            'request',
                            'create',
                            'update',
                            'detail',
                            'list',
                            'contactlist',
                            'varianlist',
                            'reflist',
                            'getprice',
                            'getno',
                            'updatestatus',
                            'massaction',
                            'print',
                            'duplicate',
                            'savetemplate',
                            'settemplate',
                            'resettemplate',
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
     * Lists all Wo models.
     * @return mixed
     */
    public function actionRequest($companyid = null)
    {
        // ===== LOGIKA DARI actionIndex =====
        $userId = Yii::$app->user->id;
        $session = Yii::$app->session;

        // Hapus session list company agar selalu update, tapi JANGAN hapus companyid
        $session->remove('company_list');

        // Ambil daftar company dan simpan di session
        $query = "SELECT * FROM company WHERE userid = :userid AND status = 1";
        $data = Yii::$app->db->createCommand($query)
            ->bindValue(':userid', $userId)
            ->queryAll();
        $session->set('company_list', $data);

        // Ambil companyid dari session atau database
        $companyid = $session->get('companyid');
        if (!$companyid) {
            $companyid = Yii::$app->db->createCommand("SELECT companyid FROM users WHERE userid = :userid")
                ->bindValue(':userid', $userId)
                ->queryScalar();

            // Jika companyid ditemukan, simpan ke session
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

        $url = Yii::$app->request->url;
        // Default values
        $module = 'purchase';
        $type = 'request';

        // Extract module and type from URL
        if (preg_match('#/([^/]+)/([^/\?]+)#', $url, $matches)) {
            $module = $matches[1]; // purchase or sales
            $type = $matches[2];   // request, order, delivery, etc.
        }

        // Also check GET parameters if they exist (fallback)
        if (Yii::$app->request->get('module')) {
            $module = Yii::$app->request->get('module');
        }

        if (Yii::$app->request->get('type')) {
            $type = Yii::$app->request->get('type');
        }

        $searchModel = new Wo();

        // Simply get the last Wosaction by date instead of filtering by opadd
        $lastWo = Yii::$app->db->createCommand("
        SELECT wono FROM wo WHERE status = 1 ORDER BY wodate DESC LIMIT 1")->queryOne();

        // Query to get all worker order with contact information where status = 1
        // Filter by wotype field to match the current module/type combination

        $session = Yii::$app->session;
        $companyid = $session->get('companyid');

        if (!$companyid) {
            // Jika companyid tidak ditemukan di session, ambil dari users
            $userId = Yii::$app->user->id;
            $companyid = Yii::$app->db->createCommand("SELECT companyid FROM users WHERE userid = :userid")
                ->bindValue(':userid', $userId)
                ->queryScalar();
        }

        // var_dump($companyid);
        // die;

        // Jika companyid ditemukan, set nilai companyid pada transaksi
        // if ($companyid) {
        //     $companyid = $companyid;
        // }

        $transactions = Yii::$app->db->createCommand("
            SELECT
                w.woid,
                w.contact_id,
                w.wodate,
                w.woduedate,
                w.wono,
                w.status,
                w.wotype,
                c.contact_name AS contact_name
            FROM wo w
            LEFT JOIN contacts c ON c.contact_id = w.contact_id
            WHERE w.wotype = :wotype AND w.companyid = :companyid
            ORDER BY w.wodate DESC
        ")->bindValue(':wotype', "$module/$type")->bindValue(':companyid', "$companyid")->queryAll();

        // Create a title based on the parameters - capitalize first letter of both module and type
        $title = ucfirst($module) . " " . ucfirst($type);

        $numbercode = Yii::$app->db->createCommand("SELECT * FROM numbertemplates where companyid = '$companyid' AND type = '$type'")->queryAll();
        // return var_dump($transactions , $companyid);
        // die;

        $previewcode = Wo::nextNoWo();
        return $this->render('index', [
            'model' => $lastWo,
            'transactions' => $transactions,
            'searchModel' => $searchModel,
            'module' => $module,
            'type' => $type,
            'title' => $title,
            'numbercode' => $numbercode,
            'previewcode' => $previewcode
        ]);
    }

    public function actionPreviewcode()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $type = Yii::$app->request->get('type');
        // var_dump($type);die;
        $companyid = Yii::$app->session->get('companyid');
        if (!$companyid) {
            $userId = Yii::$app->user->id;
            $companyid = Yii::$app->db->createCommand("SELECT companyid FROM users WHERE userid = :userid")
                ->bindValue(':userid', $userId)
                ->queryScalar();
        }

        try {
            $template = Yii::$app->db->createCommand("
            SELECT template FROM numbertemplates 
            WHERE companyid = :companyid AND type = :type
            LIMIT 1
        ")
                ->bindValue(':companyid', $companyid)
                ->bindValue(':type', $type)
                ->queryScalar();

            if (!$template) {
                $template = $type;
            }

            // var_dump($template);die;
            // Tambahkan setelah ambil $template
            $bulanRomawi = Wo::convertToRoman(date('n'));
            $tahun = date('Y');

            // Build regex pattern di PHP
            $regexPattern = '^[0-9]+/' . $template . '/' . $bulanRomawi . '/' . $tahun . '$';

            // Cari nomor urut berdasarkan bulan dan tahun
            $sql = "
    SELECT COALESCE(
        MAX(CAST(
            regexp_replace(wono, '^([0-9]+)/' || :template || '/" . $bulanRomawi . "/" . $tahun . "$', '\\1') AS INTEGER
        )), 0) + 1 AS next_no
    FROM wo
    WHERE wono ~ :regexPattern
    AND companyid = :companyid
";

            $results = Yii::$app->db->createCommand($sql)
                ->bindValue(':template', $template)
                ->bindValue(':regexPattern', $regexPattern)
                ->bindValue(':companyid', $companyid)
                ->queryOne();


            $nextKode = $results['next_no'] ?? 1;

            // Format: 00001-CT-IV-2025
            $fullKode = sprintf(
                "%s/%s/%s/%s",
                str_pad($nextKode, 5, "0", STR_PAD_LEFT),
                $template,
                $bulanRomawi,
                $tahun
            );

            return ['success' => true, 'preview' => $fullKode,'previewcode' => $fullKode];
        } catch (Exception $e) {
            Yii::error("Error generating worker order number: " . $e->getMessage());
            return 'Error generating worker order number';
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

        // Map document types to database columns
        $typeMap = [
            'ORD' => 'ord_code',
            'D' => 'delivery_code',
            'INV' => 'invoices_code',
            'RTN' => 'return_code',
            'RQ' => 'request_code',
            'QU' => 'quotation_code'
        ];

        // Check if type is valid
        if (!isset($typeMap[$type])) {
            return ['success' => false, 'message' => 'Invalid document type'];
        }

        // Update the template
        try {
            $affectedRows = Yii::$app->db->createCommand()->update('company', [
                $typeMap[$type] => $template
            ], ['companyid' => $companyid])->execute();

            return ['success' => (bool)$affectedRows];
        } catch (\Exception $e) {
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
            'D'   => 'delivery_code',
            'INV' => 'invoices_code',
            'RTN' => 'return_code',
            'RQ'  => 'request_code',
            'QU'  => 'quotation_code',
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
     * Displays a single Wo model.
     * @param string $id
     * @return mixed
     */
    public function actionDetail($id)
    {
        // Mengambil data worker order utama
        $model = Wo::findOne($id);
        

        if (!$model) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'pesan' => 'Worker Order tidak ditemukan'
                ];
            }
            return $this->redirect(['index']);
        }

        // Parse wotype to get module and type
        $parts = explode('/', $model-> wotype);
        $module = $parts[0] ?? 'purchase';
        $type = $parts[1] ?? 'request';

        // Menggunakan query builder untuk mendapatkan detail worker order
        $details = Yii::$app->db->createCommand("
        SELECT w.wodetailid, t.woid, w.variantid, w.amount, w.itemsubtotal, w.price, w.itemtype,
               v.productid, v.sku, v.stock, v.status, v.description, v.productimage,
               c.contact_name, c.address, c.contact_phone1, c.contact_email1
        FROM wodetails wd
        LEFT JOIN variants v ON w.variantid::text = v.variantid::text
        LEFT JOIN wo w ON w.woid = w.woid
        LEFT JOIN contacts c ON tr.contact_id = c.contact_id
        WHERE w.woid = :woid
    ")
            ->bindValue(':woid', $id)
            ->queryAll();

        // Menghitung total worker order
        $total = 0;
        foreach ($details as $detail) {
            $total += $detail['itemsubtotal'];
        }

        // var_dump($details);
        // die;

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_detail', [
                'model' => $model,
                'details' => $details,
                'total' => $total,
                'module' => $module,
                'type' => $type
            ]);
        } else {
            return $this->render('_detail', [
                'model' => $model,
                'details' => $details,
                'total' => $total,
                'module' => $module,
                'type' => $type
            ]);
        }
    }

    public function actionPrint($id)
    {
        // Mengambil data worker order utama
        $model = Wo::findOne($id);

        if (!$model) {
            Yii::$app->session->setFlash('error', 'Worker Order tidak ditemukan');
            return $this->redirect(['index']);
        }

        // $session = Yii::$app->session;
        // $companyid = $session->get('companyid');

        // if (!$companyid) {
        // Jika companyid tidak ditemukan di session, ambil dari users
        $userId = Yii::$app->user->id;
        $companyname = Yii::$app->db->createCommand("SELECT u.companyid, c.nama_perusahaan  FROM users u LEFT JOIN company c ON u.companyid = c.companyid WHERE u.userid = :userid")
            ->bindValue(':userid', $userId)
            ->queryScalar();
        // }

        // var_dump($companyid);

        // Parse wotype to get module and type
        $parts = explode('/', $model->wotype);
        $module = $parts[0] ?? 'purchase';
        $type = $parts[1] ?? 'request';

        // Menggunakan query builder untuk mendapatkan detail wosaksi
        $details = Yii::$app->db->createCommand("
        SELECT wd.wodetailid, wd.woid, wd.variantid, wd.amount, wd.itemsubtotal, wd.price,
               v.productid, p.productname, v.sku, v.stock, v.status, v.description, v.productimage,
               c.contact_name,c.jobcompany, w.wotype,
               w.ppnamount, w.pphamount, (w.ppnamount + w.pphamount) AS pajak_total
        FROM wodetails w
        LEFT JOIN variants v ON t.variantid::text = v.variantid::text
        LEFT JOIN products p ON p.productid::text = v.productid::text
        LEFT JOIN wo w ON wd.woid = w.woid
        LEFT JOIN contacts c ON tr.contact_id = c.contact_id
        WHERE t.woid = :woid
    ")
            ->bindValue(':woid', $id)
            ->queryAll();

        // Menghitung total wosaksi
        $total = 0;
        foreach ($details as $detail) {
            $total += $detail['itemsubtotal'];
        }

        // Konversi angka ke terbilang
        $terbilang = $this->convertNumberToWords($total);

        // Set layout to false to remove standard layout
        $this->layout = false;

        // Return the print view
        return $this->render('_print', [
            'model' => $model,
            'details' => $details,
            'total' => $total,
            'module' => $module,
            'type' => $type,
            'terbilang' => $terbilang
        ]);
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

    /**
     * Creates a new Wo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Wo();

        // Extract module and type from URL or GET parameters
        $url = Yii::$app->request->url;
        $module = 'purchase'; // Default module
        $type = 'request';    // Default type

        if (preg_match('#/([^/]+)/([^/\?]+)#', $url, $matches)) {
            $module = $matches[1];
            $type = $matches[2];
        }

        // Check GET parameters as fallback
        if (Yii::$app->request->get('module')) {
            $module = Yii::$app->request->get('module');
        }

        if (Yii::$app->request->get('type')) {
            $type = Yii::$app->request->get('type');
        }

        // Generate wo number
        $woNo = $model->nextNowo();
        $model->wono = $woNo;

        // Set the worker order type as combined module/type
        $model->wotype = "$module/$type";
        // var_dump($model->wotype);die;

        if ($model->load(Yii::$app->request->post())) {
            // Remove debugging code
            // var_dump($_POST);
            // die;

            if ($model->subtotal != null) {
                $model->subtotal = str_replace(".", "", $model->subtotal);
            }

            // Set default values if needed
            // if (empty($model->wodate)) {
            //     $model->wodate = date('Y-m-d');
            // }

            // var_dump($_POST['Wo']['subtotal'] , $model->subtotal);
            // die;
            // Flag harga termasuk pajak
            $model->priceincludetax = ($_POST['Wo']['price-include-tax'] ?? '') === 'on' ? 1 : 0;
            if (empty($model->wotype)) {
                $model->wotype = "$module/$type";
            }

            $valid = $model->validate(false);

            if ($valid) {

                $transaction = Yii::$app->db->beginTransaction();
                try {

                    $post = Yii::$app->request->post();

                    // Convert all numeric fields with proper formatting
                    $model->subtotal = isset($post['Wo']['subtotal']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['subtotal']) : 0;

                    $model->disc = isset($post['Wo']['disc']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['disc']) : 0;

                    $model->totalafterdisc = isset($post['Wo']['totalafterdisc']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['totalafterdisc']) : 0;

                    $model->ppnamount = isset($post['Wo']['ppnamount']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['ppnamount']) : 0;

                    $model->pphamount = isset($post['Wo']['pphamount']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['pphamount']) : 0;

                    $model->otherdiscount = isset($post['Wo']['otherdiscount']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['otherdiscount']) : 0;

                    $model->deliverycharge = isset($post['Wo']['deliverycharge']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['deliverycharge']) : 0;

                    $model->grandtotal = isset($post['Wo']['grandtotal']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['grandtotal']) : 0;

                    // Flag harga termasuk pajak
                    $model->priceincludetax = ($post['Wo']['price-include-tax'] ?? '') === 'on' ? 1 : 0;
                    // Save main transaction data
                    if ($flag = $model->save(false)) {
                        $postData = Yii::$app->request->post();
                        // Get the post data
                        // $post = Yii::$app->request->post();

                        // // Process numeric fields from Tran array with proper decimal handling
                        // $model->subtotal = isset($post['Tran']['subtotal']) ?
                        //     (float) str_replace(['.', ','], ['', '.'], $post['Tran']['subtotal']) : 0;

                        // $model->disc = isset($post['Tran']['disc']) ?
                        //     (float) str_replace(['.', ','], ['', '.'], $post['Tran']['disc']) : 0;

                        // $model->totalafterdisc = isset($post['Tran']['totalafterdisc']) ?
                        //     (float) str_replace(['.', ','], ['', '.'], $post['Tran']['totalafterdisc']) : 0;

                        // $model->ppnamount = isset($post['Tran']['ppnamount']) ?
                        //     (float) str_replace(['.', ','], ['', '.'], $post['Tran']['ppnamount']) : 0;

                        // $model->pphamount = isset($post['Tran']['pphamount']) ?
                        //     (float) str_replace(['.', ','], ['', '.'], $post['Tran']['pphamount']) : 0;

                        // $model->otherdiscount = isset($post['Tran']['otherdiscount']) ?
                        //     (float) str_replace(['.', ','], ['', '.'], $post['Tran']['otherdiscount']) : 0;

                        // $model->deliverycharge = isset($post['Tran']['deliverycharge']) ?
                        //     (float) str_replace(['.', ','], ['', '.'], $post['Tran']['deliverycharge']) : 0;

                        // $model->grandtotal = (float) str_replace(['.', ','], ['', '.'], $post['Tran']['grandtotal']);

                        // // Flag harga termasuk pajak
                        // $model->priceincludetax = ($post['Tran']['price-include-tax'] ?? '') === 'on' ? 1 : 0;



                        $detailsJson = $postData['details'] ?? '[]';
                        $groupedDetails = json_decode($detailsJson, true); // ubah JSON string jadi array

                        if (!empty($groupedDetails) && is_array($groupedDetails)) {
                            // var_dump($groupedDetails);
                            foreach ($groupedDetails as $detail) {
                                $detailModel = new WoDetail();
                                $detailModel->woid = $model->woid;
                                $detailModel->variantid = $detail['variantid'] ?? null;
                                $detailModel->itemtype = $detail['description'] ?? null;
                                $detailModel->amount = (int) ($detail['amount'] ?? 1);
                                // var_dump($detail['harga'] );
                                // Ubah format harga dan diskon
                                $price = str_replace(['.', ','], ['', '.'], $detail['price'] ?? '0');
                                // var_dump($detail['harga'] ,$harga);
                                $itemdisc = str_replace(['.', ','], ['', '.'], $detail['itemdisctotal'] ?? '0');
                                // var_dump($detail['itemdisctotal']);
                                // die;
                                $totalAfterDisc = str_replace(['.', ','], ['', '.'], $detail['totalafterdisc'] ?? '0');
                                // var_dump($detail['itemsubtotal']);
                                // die;
                                $itemsubtotal = str_replace(['.', ','], ['', '.'], $detail['itemsubtotal'] ?? '0');

                                $detailModel->price = (float) $price;
                                // var_dump($detailModel->harga);
                                // die;
                                $detailModel->itemdisc = (float) $itemdisc;
                                $detailModel->itemsubtotal = (float) $itemsubtotal;
                                // var_dump($detailModel->itemsubtotal);
                                // die;
                                $detailModel->totalafterdisc = (float) $totalAfterDisc;
                                $detailModel->itemtotal = (float) $totalAfterDisc;

                                // Set discount percentage
                                $discValue = str_replace(['.', ','], ['', '.'], $detail['disc'] ?? '0');
                                $detailModel->itemdiscpersen = (float) $discValue;
                                // $detailModel->ord

                                // Set tax type (explicitly cast to int)
                                $detailModel->taxtype = (int)($detail['tax_type'] ?? 0);

                                // Simpan model
                                if (!$detailModel->save(false)) {
                                    throw new \Exception('Failed to save transaction detail: ' . json_encode($detailModel->errors));
                                }
                            }
                            // var_dump($model->subtotal , $model->disc , $model->totalafterdisc , $model->grandtotal);
                            // var_dump($groupedDetails);
                            // die;
                        }

                        $transaction->commit();

                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return [
                                'success' => true,
                                'refid' => $model->refid,
                                'pesan' => 'Data Berhasil Disimpan',
                                'id' => $model->woid,
                                'wono' => $model->wono,

                                // 'model' => $model,
                            ];
                        }

                        return $this->redirect(['request', 'module' => $module, 'type' => $type]);
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
                'module' => $module,
                'type' => $type,
            ]);
        } else {
            return $this->render('_form', [
                'dialog' => 1,
                'model' => $model,
                'isajax' => "false",
                'module' => $module,
                'type' => $type,
            ]);
        }
    }

    /**
     * Updates an existing Wo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // Parse the trantype to get module and type

        $parts = explode('/', $model->Wotype);
        $module = $parts[0] ?? 'purchase';
        $type = $parts[1] ?? 'request';

        // Yii::$app->formatter->asDate($model->trandate, 'php:d-m-Y');
        // Yii::$app->formatter->asDate($model->tranduedate, 'php:d-m-Y');
        // Retrieve existing transaction details
        $existingDetails = WoDetail::find()->where(['woid' => $id])->all();
        $detailsData = [];

        // Build the details data array
        foreach ($existingDetails as $detail) {
            $variant = Yii::$app->db->createCommand('
                SELECT v.*, p.productname
                FROM variants v
                LEFT JOIN products p ON v.productid = CAST(p.productid AS TEXT)
                WHERE v.variantid = :variantid
            ')
                ->bindValue(':variantid', $detail->variantid)
                ->queryOne();

            $detailItem = [
                'wodetailid' => $detail->wodetailid,
                'variantid' => $detail->variantid,
                'amount' => $detail->amount, // qty
                'price' => $detail->price, // price
                'subtotal' => $detail->itemsubtotal, // subtotal
                'totalafterdisc' => $detail->totalafterdisc, // subtotal
                'itemdiscpersen' => $detail->itemdiscpersen ?? 0, // disc(%)
                'itemdisctotal' => $detail->itemdisc ?? 0, // disc
                'taxtype' => $detail->taxtype ?? 0, // tax
                'itemtype' => $detail->itemtype, // tax
            ];

            if ($variant) {
                $detailItem['sku'] = $variant['sku'];
                $detailItem['description'] = $variant['description'] ?? $detail->itemtype;
                $detailItem['productname'] = $variant['productname'];
            }

            $detailsData[] = $detailItem;
        }

        if ($model->load(Yii::$app->request->post())) {
            // Set default values if needed
            // if (empty($model->wodate)) {
            //     $model->wodate = date('Y-m-d');
            // }

            // Format dates correctly
            if (!empty($model->wodate)) {
                if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $model->wodate)) {
                    $parts = explode('-', $model->wodate);
                    $model->wodate = $parts[2] . '-' . $parts[1] . '-' . $parts[0]; // Convert to YYYY-MM-DD
                }
            }

            if (!empty($model->woduedate)) {
                if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $model->woduedate)) {
                    $parts = explode('-', $model->woduedate);
                    $model->woduedate = $parts[2] . '-' . $parts[1] . '-' . $parts[0]; // Convert to YYYY-MM-DD
                }
            }

            // Ensure the wotype remains intact
            if (empty($model->wotype)) {
                $model->wotype = "$module/$type";
            }

            // Flag harga termasuk pajak (matching your actionCreate)
            $post = Yii::$app->request->post();
            $model->priceincludetax = ($post['Wo']['price-include-tax'] ?? '') === 'on' ? 1 : 0;

            $valid = $model->validate(false);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    // Convert all numeric fields with proper formatting before saving
                    $model->subtotal = isset($post['Wo']['subtotal']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['subtotal']) : 0;

                    $model->disc = isset($post['Wo']['disc']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['disc']) : 0;

                    $model->totalafterdisc = isset($post['Wo']['totalafterdisc']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['totalafterdisc']) : 0;

                    $model->ppnamount = isset($post['Wo']['ppnamount']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['ppnamount']) : 0;

                    $model->pphamount = isset($post['Wo']['pphamount']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['pphamount']) : 0;

                    $model->otherdiscount = isset($post['Wo']['otherdiscount']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['otherdiscount']) : 0;

                    $model->deliverycharge = isset($post['Wo']['deliverycharge']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['deliverycharge']) : 0;

                    $model->grandtotal = isset($post['Wo']['grandtotal']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['grandtotal']) : 0;

                    // Save main transaction data
                    if ($flag = $model->save(false)) {
                        // Get transaction details from POST
                        $detailsJson = Yii::$app->request->post('details', '[]');
                        $details = json_decode($detailsJson, true);

                        // Check if JSON was invalid
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            throw new \Exception('Invalid JSON format for transaction details: ' . json_last_error_msg());
                        }

                        if (is_array($details) && !empty($details)) {
                            // Track which details to keep
                            $currentDetailIds = [];

                            foreach ($details as $detail) {
                                // Validate detail data
                                if (empty($detail['variantid'])) {
                                    throw new \Exception('Product variant must be selected');
                                }

                                if (!isset($detail['amount']) || intval($detail['amount']) <= 0) {
                                    throw new \Exception('Product quantity must be greater than 0');
                                }

                                if (isset($detail['trandetailid']) && !empty($detail['wodetailid'])) {
                                    // Update existing detail
                                    $detailModel = WoDetail::findOne($detail['wodetailid']);
                                    if (!$detailModel) {
                                        // If not found, create new
                                        $detailModel = new WoDetail();
                                        $detailModel->woid = $model->woid;
                                    }
                                } else {
                                    // Create new detail
                                    $detailModel = new WoDetail();
                                    $detailModel->woid = $model->woid;
                                }

                                // Update detail attributes
                                $detailModel->variantid = $detail['variantid'];
                                $detailModel->amount = intval($detail['amount']);

                                // Process numeric values with proper formatting - matching your create action
                                $price = str_replace(['.', ','], ['', '.'], $detail['price'] ?? '0');
                                $itemdisc = str_replace(['.', ','], ['', '.'], $detail['itemdisctotal'] ?? '0');
                                $totalAfterDisc = str_replace(['.', ','], ['', '.'], $detail['totalafterdisc'] ?? '0');
                                $itemsubtotal = str_replace(['.', ','], ['', '.'], $detail['itemsubtotal'] ?? '0');

                                $detailModel->price = (float) $price;
                                $detailModel->itemdisc = (float) $itemdisc;
                                $detailModel->itemsubtotal = (float) $itemsubtotal;
                                $detailModel->totalafterdisc = (float) $totalAfterDisc;
                                $detailModel->itemtotal = (float) $totalAfterDisc;

                                // Set discount percentage
                                $discValue = str_replace(['.', ','], ['', '.'], $detail['disc'] ?? '0');
                                $detailModel->itemdiscpersen = (float) $discValue;

                                // Set tax type (explicitly cast to int)
                                $detailModel->taxtype = (int)($detail['tax_type'] ?? 0);
                                $detailModel->itemtype = ($detail['description']);

                                // Save the detail
                                if (!$detailModel->save(false)) {
                                    throw new \Exception('Failed to save transaction detail: ' . json_encode($detailModel->errors));
                                }

                                // Track this detail ID
                                $currentDetailIds[] = $detailModel->wodetailid;
                            }

                            // var_dump($detailModel->itemtype);die;

                            // Handle deleted details
                            if (!empty($currentDetailIds)) {
                                $removedDetails = WoDetail::find()
                                    ->where(['woid' => $model->woid])
                                    ->andWhere(['NOT IN', 'wodetailid', $currentDetailIds])
                                    ->all();

                                foreach ($removedDetails as $removedDetail) {
                                    // Delete the detail without stock adjustments
                                    $removedDetail->delete();
                                }
                            }
                        } else {
                            throw new \Exception('Transaction details are invalid or empty');
                        }

                        $transaction->commit();

                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return [
                                'success' => true,
                                'pesan' => 'Data successfully updated',
                                'id' => $model->woid,
                                'wono' => $model->wono
                            ];
                        }

                        return $this->redirect(['request', 'module' => $module, 'type' => $type]);
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::error('Worker order error: ' . $e->getMessage(), 'application');

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
                        'pesan' => implode("<br>", $model->getFirstErrors()),
                        'id' => $model->woid,
                        'wono' => $model->wono
                    ];
                }
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
                'isajax' => "true",
                'detailsData' => $detailsData,
                'module' => $module,
                'type' => $type,
            ]);
        } else {
            return $this->render('_form', [
                'model' => $model,
                'isajax' => "false",
                'detailsData' => $detailsData,
                'module' => $module,
                'type' => $type,
            ]);
        }
    }

    public function actionDuplicate($id)
    {
        $originalModel = $this->findModel($id);

        // Create a new model instance
        $model = new Wo();

        // Copy all attributes from the original model
        $model->attributes = $originalModel->attributes;

        // Reset the primary key and any unique fields
        $model->woid = null;
        $model->wono = $originalModel->wono . " Copy";

        // Parse the wotype to get module and type
        $parts = explode('/', $originalModel->wotype);
        $module = $parts[0] ?? 'purchase';
        $type = $parts[1] ?? 'request';

        // Retrieve existing transaction details from the original
        $existingDetails = WoDetail::find()->where(['woid' => $id])->all();
        $detailsData = [];

        // Build the details data array for the new transaction
        foreach ($existingDetails as $detail) {
            $variant = Yii::$app->db->createCommand('
                SELECT v.*, p.productname
                FROM variants v
                LEFT JOIN products p ON v.productid = CAST(p.productid AS TEXT)
                WHERE v.variantid = :variantid
            ')
                ->bindValue(':variantid', $detail->variantid)
                ->queryOne();

            $detailItem = [
                'variantid' => $detail->variantid,
                'amount' => $detail->amount, // qty
                'price' => $detail->price, // price
                'subtotal' => $detail->itemsubtotal, // subtotal
                'totalafterdisc' => $detail->totalafterdisc, // subtotal
                'itemdiscpersen' => $detail->itemdiscpersen ?? 0, // disc(%)
                'itemdisctotal' => $detail->itemdisc ?? 0, // disc
                'taxtype' => $detail->taxtype ?? 0, // tax
                'itemtype' => $detail->itemtype ?? 0, // tax
            ];

            if ($variant) {
                $detailItem['sku'] = $variant['sku'];
                $detailItem['description'] = $variant['description'] ?? $detail->itemtype;
                $detailItem['productname'] = $variant['productname'];
            }

            $detailsData[] = $detailItem;
        }

        if ($model->load(Yii::$app->request->post())) {
            // Set default values if needed
            // if (empty($model->wodate)) {
            //     $model->wodate = date('Y-m-d');
            // }

            // Format dates correctly
            if (!empty($model->wodate)) {
                if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $model->wodate)) {
                    $parts = explode('-', $model->wodate);
                    $model->wodate = $parts[2] . '-' . $parts[1] . '-' . $parts[0]; // Convert to YYYY-MM-DD
                }
            }

            if (!empty($model->woduedate)) {
                if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $model->woduedate)) {
                    $parts = explode('-', $model->woduedate);
                    $model->woduedate = $parts[2] . '-' . $parts[1] . '-' . $parts[0]; // Convert to YYYY-MM-DD
                }
            }

            // Ensure the wotype remains intact
            if (empty($model->wotype)) {
                $model->wotype = "$module/$type";
            }

            // Flag harga termasuk pajak
            $post = Yii::$app->request->post();
            $model->priceincludetax = ($post['Wo']['price-include-tax'] ?? '') === 'on' ? 1 : 0;

            $valid = $model->validate(false);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    // Convert all numeric fields with proper formatting before saving
                    $model->subtotal = isset($post['Wo']['subtotal']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['subtotal']) : 0;

                    $model->disc = isset($post['Wo']['disc']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['disc']) : 0;

                    $model->totalafterdisc = isset($post['Wo']['totalafterdisc']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['totalafterdisc']) : 0;

                    $model->ppnamount = isset($post['Wo']['ppnamount']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['ppnamount']) : 0;

                    $model->pphamount = isset($post['Wo']['pphamount']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['pphamount']) : 0;

                    $model->otherdiscount = isset($post['Wo']['otherdiscount']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['otherdiscount']) : 0;

                    $model->deliverycharge = isset($post['Wo']['deliverycharge']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['deliverycharge']) : 0;

                    $model->grandtotal = isset($post['Wo']['grandtotal']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Wo']['grandtotal']) : 0;

                    // Save the new transaction
                    if ($model->save(false)) {
                        // Get transaction details from POST
                        $detailsJson = Yii::$app->request->post('details', '[]');
                        $details = json_decode($detailsJson, true);

                        // Check if JSON was invalid
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            throw new \Exception('Invalid JSON format for transaction details: ' . json_last_error_msg());
                        }

                        if (is_array($details) && !empty($details)) {
                            foreach ($details as $detail) {
                                // Validate detail data
                                if (empty($detail['variantid'])) {
                                    throw new \Exception('Product variant must be selected');
                                }

                                if (!isset($detail['amount']) || intval($detail['amount']) <= 0) {
                                    throw new \Exception('Product quantity must be greater than 0');
                                }

                                // Create new detail for the new transaction
                                $detailModel = new WoDetail();
                                $detailModel->woid = $model->woid;

                                // Set detail attributes
                                $detailModel->variantid = $detail['variantid'];
                                $detailModel->amount = intval($detail['amount']);

                                // Process numeric values with proper formatting
                                $price = str_replace(['.', ','], ['', '.'], $detail['price'] ?? '0');
                                $itemdisc = str_replace(['.', ','], ['', '.'], $detail['itemdisctotal'] ?? '0');
                                $totalAfterDisc = str_replace(['.', ','], ['', '.'], $detail['totalafterdisc'] ?? '0');
                                $itemsubtotal = str_replace(['.', ','], ['', '.'], $detail['itemsubtotal'] ?? '0');

                                $detailModel->price = (float) $price;
                                $detailModel->itemdisc = (float) $itemdisc;
                                $detailModel->itemsubtotal = (float) $itemsubtotal;
                                $detailModel->totalafterdisc = (float) $totalAfterDisc;
                                $detailModel->itemtotal = (float) $totalAfterDisc;

                                // Set discount percentage
                                $discValue = str_replace(['.', ','], ['', '.'], $detail['disc'] ?? '0');
                                $detailModel->itemdiscpersen = (float) $discValue;

                                // Set tax type (explicitly cast to int)
                                $detailModel->taxtype = (int)($detail['tax_type'] ?? 0);
                                $detailModel->itemtype = (int)($detail['description'] ?? 0);

                                // Save the detail
                                if (!$detailModel->save(false)) {
                                    throw new \Exception('Failed to save transaction detail: ' . json_encode($detailModel->errors));
                                }
                            }
                        } else {
                            throw new \Exception('Transaction details are invalid or empty');
                        }

                        $transaction->commit();

                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return [
                                'success' => true,
                                'pesan' => 'Data successfully duplicated',
                                'id' => $model->woid,
                                'wono' => $model->wono
                            ];
                        }

                        return $this->redirect(['request', 'module' => $module, 'type' => $type]);
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::error('Transaction duplication error: ' . $e->getMessage(), 'application');

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
                        'pesan' => implode("<br>", $model->getFirstErrors()),
                    ];
                }
            }
        }

        // For GET request, show the form with duplicated data
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
                'isajax' => "true",
                'detailsData' => $detailsData,
                'module' => $module,
                'type' => $type,
            ]);
        } else {
            return $this->render('_form', [
                'model' => $model,
                'isajax' => "false",
                'detailsData' => $detailsData,
                'module' => $module,
                'type' => $type,
            ]);
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

        // Validasi parameter
        if (empty($id) || $status === null) {
            return ['success' => false, 'message' => 'Parameter tidak lengkap'];
        }

        $model = Wo::findOne($id);
        if (!$model) {
            return ['success' => false, 'message' => 'Data transaksi tidak ditemukan'];
        }

        // Update status
        $model->status = $status;

        // Siapkan label status untuk response
        $statusLabels = [
            0 => 'Draft',
            1 => 'Approved',
            5 => 'Cancelled',
            10 => 'Rejected'
        ];

        $statusText = isset($statusLabels[$status]) ? $statusLabels[$status] : 'Unknown';

        // Simpan perubahan
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

    /**
     * Handles mass actions (delete, approve, cancel) for multiple transactions
     *
     * @return \yii\web\Response
     */
    public function actionMassaction()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Get the post data
        $ids = Yii::$app->request->post('ids');
        $action = Yii::$app->request->post('action');
        $status = Yii::$app->request->post('status');

        // Validate input
        if (empty($ids) || !is_array($ids)) {
            return [
                'success' => false,
                'message' => 'Tidak ada data yang dipilih!'
            ];
        }

        // Validate action and status
        $validActions = ['delete', 'approve', 'cancel'];
        $validStatuses = [1, 5, 10]; // 1=Approved, 5=Cancelled, 10=Rejected/Deleted

        if (!in_array($action, $validActions) || !in_array($status, $validStatuses)) {
            return [
                'success' => false,
                'message' => 'Aksi atau status tidak valid!'
            ];
        }

        try {
            // Convert array to format string for SQL (example: '1','2','3')
            $idString = implode(',', array_map(fn($id) => "'" . addslashes($id) . "'", $ids));

            // Create the SQL query
            $sql = "UPDATE wo SET status = :status,
                updatedat = NOW(),
                updatedby = :user_id,
                updatedip = :user_ip
                WHERE woid IN ($idString)";

            // Execute the SQL query
            $rowsAffected = Yii::$app->db->createCommand($sql, [
                ':status' => $status,
                ':user_id' => Yii::$app->user->id,
                ':user_ip' => Yii::$app->request->userIP
            ])->execute();

            // Get action text for response message
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
            }

            // Return success response
            return [
                'success' => true,
                'message' => "Data berhasil $actionText!",
                'rows_affected' => $rowsAffected,
                'action' => $action,
                'status' => $status
            ];
        } catch (\Exception $e) {
            // Log the error
            Yii::error("Error in mass action: " . $e->getMessage());

            // Return error response
            return [
                'success' => false,
                'message' => "Terjadi kesalahan: " . $e->getMessage(),
                'error' => true
            ];
        }
    }

    /**
     * Deletes an existing Wo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->post('id');
        if (!$id) {
            return $this->asJson(['success' => false, 'message' => 'ID tidak ditemukan!']);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = $this->findModel($id);

            // Parse the trantype to get module and type for redirect
            $parts = explode('/', $model->wotype);
            $module = $parts[0] ?? 'purchase';
            $type = $parts[1] ?? 'request';

            // Get all details before deleting to adjust stock
            $details = WoDetail::find()->where(['woid' => $id])->all();
            // Update stock for each item
            foreach ($details as $detail) {
                //    var_dump($detail);
                $detail->status = 10;
                if (!$detail->save(false)) {
                    throw new \Exception('Failed to update purchase detail status: ' . json_encode($detail->errors));
                }
            }

            // Soft delete - set status to 10 instead of deleting
            $model->status = 10;

            if ($model->save(false)) {
                $transaction->commit();
                return $this->asJson(['success' => true, 'module' => $module, 'type' => $type]);
            } else {
                $transaction->rollBack();
                return $this->asJson(['success' => false, 'message' => 'Gagal menghapus']);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->asJson(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Finds the Wo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Wo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Wo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionList()
    {
        // var_dump($_POST);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = Yii::$app->user->id;
        $params = Yii::$app->request->queryParams; // Ambil parameter filter

        // Parameter filter untuk transaksi
        $contact = $params['contact'] ?? '';
        $search = $params['search'] ?? '';
        $date = $params['datefilter'] ?? '';
        $statuspaid = $params['status'] ?? '';
        $module = $params['module'] ?? 'purchase'; // Module parameter (purchase/sales)
        $type = $params['type'] ?? '';             // Type parameter (request/order/etc)

        // Combine module and type for filtering
        $trantype = "$module/$type";
        // var_dump($trantype);

        // Ambil data sorting dari DataTables
        $sortcolumn = $params['order'][0]['column'] ?? 0;
        $ordercolumn = $params['columns'][$sortcolumn]['data'] ?? 'wodate';
        $columnorder = $params['order'][0]['dir'] ?? 'DESC';

        // Validasi sorting biar aman dari SQL Injection
        $allowedColumns = ['wono', 'wodate', 'woduedate', 'contact_name', 'total'];
        if (!in_array($ordercolumn, $allowedColumns)) {
            $ordercolumn = 'wodate'; // Default sorting
        }

        $session = Yii::$app->session;
        $companyid = $session->get('companyid');

        if (!$companyid) {
            // Jika companyid tidak ditemukan di session, ambil dari users
            $userId = Yii::$app->user->id;
            $companyid = Yii::$app->db->createCommand("SELECT companyid FROM users WHERE userid = :userid")
                ->bindValue(':userid', $userId)
                ->queryScalar();
        }


        // Query untuk ambil data transaksi
        $query = "SELECT
                    w.woid,
                    w.status,
                    w.wono,
                    w.wodate,
                    w.woduedate,
                    w.wotype,
                    w.totalpaid,
                    w.statuspaid,
                    w.grandtotal,
                    c.contact_name,
                    c.jobcompany,
                    (SELECT COALESCE(SUM(td.itemsubtotal), 0) FROM wodetails wd WHERE wd.woid = w.woid) as total
                  FROM wo w
                  LEFT JOIN contacts c ON c.contact_id = t.contact_id
                  WHERE t.companyid = '$companyid' AND t.status <> 10
                  ";

        // Filter berdasarkan tipe transaksi
        if ($type !== '') {
            $query .= " AND w.wotype = :wotype";
        }

        // Buat kondisi filter
        $filter = "";

        if (isset($contact) && $contact !== '') {
            $filter .= " AND t.contact_id = :contact";
        }

        // $statuspaid = $params['status'] ?? '';

        if (isset($statuspaid) && $statuspaid !== '') {
            // Jika status = 'paid', hanya pilih transaksi dengan statuspaid = 'paid'
            if ($statuspaid == 'paid') {
                $filter .= " AND t.statuspaid = 'paid'";
            }
            // Jika status = 'unpaid', pilih transaksi dengan statuspaid NULL atau tidak 'paid'
            elseif ($statuspaid == 'unpaid') {
                $filter .= " AND (t.statuspaid IS NULL OR t.statuspaid != 'paid')";
            }
        }

        if (isset($search) && $search !== "") {
            $filter .= " AND (
                w.wono ILIKE :search
             OR c.contact_name ILIKE :search
            )";
        }

        if ($date != "") {
            $dates = explode(" - ", $date);

            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));
            // var_dump($startDate, $endDate);die;
            $filter .= " AND DATE(w.wodate) BETWEEN :startDate AND :endDate";
        }

        // Query akhir dengan ORDER BY
        $query .= $filter . " ORDER BY " . $ordercolumn . " " . $columnorder;

        // Prepare the command with parameters
        $command = Yii::$app->db->createCommand($query);

        // Bind parameters (more secure than string concatenation)
        if ($type !== '') {
            $command->bindValue(':wotype', $wotype);
        }

        if (isset($contact) && $contact !== '') {
            $command->bindValue(':contact', $contact);
        }


        if (isset($search) && $search !== "") {
            $command->bindValue(':search', '%' . $search . '%');
        }

        if ($date != "") {
            $command->bindValue(':startDate', $startDate);
            $command->bindValue(':endDate', $endDate);
        }

        // Execute the query
        $data = $command->queryAll();

        // Format tanggal untuk output JSON and add module/type for UI handling
        foreach ($data as &$row) {
            if (isset($row['wodate'])) {
                $row['wodate_display'] = Yii::$app->formatter->asDate($row['wodate'], 'php:d-m-Y');
            }
            if (isset($row['woduedate'])) {
                $row['woduedate_display'] = $row['woduedate'] ? Yii::$app->formatter->asDate($row['woduedate'], 'php:d-m-Y') : null;
            }

            // Parse wotype to extract module and type
            if (isset($row['wotype'])) {
                $parts = explode('/', $row['wotype']);
                $row['module'] = $parts[0] ?? 'purchase';
                $row['type'] = $parts[1] ?? 'request';
            }
        }

        // var_dump($data , $module , $type);
        return [
            'data' => $data ?: [],
            'module' => $module,
            'type' => $type
        ];
    }


    public function actionContactlist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $q = Yii::$app->request->get('q', null); // Aman dari Undefined Index
        $id = Yii::$app->request->get('id', null);
        $module = Yii::$app->request->get('module');
        // $parentid = Yii::$app->request->get('parentid', 0); //ini kalo buat hierarkis
        $userid = Yii::$app->user->id;
        $session = Yii::$app->session;
        $companyid = $session->get('companyid');
        // $listBahasa = "SELECT lang FROM users WHERE userid = '$userid'";

        // $bahasa = Yii::$app->db->createCommand($listBahasa)->queryScalar();
        $params = [];
        //query ngambil dari tabel enums aja
        if ($module == 'sales') {
            $sql = "SELECT * FROM contacts WHERE companyid = :companyid AND contact_iscustomer = 1 AND contact_status = '1'";
        } else {
            $sql = "SELECT * FROM contacts WHERE companyid = :companyid AND contact_isvendor = 1 AND contact_status = '1'";
        }
        $params[':companyid'] = $companyid;

        if ($q !== null) {
            $sql .= " AND (LOWER(contact_name) LIKE :q OR LOWER(contact_email1) LIKE :q OR LOWER(contact_phone1) LIKE :q OR LOWER(jobcompany) LIKE :q)";
            $params[':q'] = "%" . strtolower($q) . "%";
        }

        $sql .= " ORDER BY contact_no ASC LIMIT 5";
        $data = Yii::$app->db->createCommand($sql, $params)->queryAll();

        return ['data' => array_values($data)];
    }

    public function actionVarianlist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $q = \Yii::$app->request->get('q', '');  // Default to empty string if not provided
        $module = \Yii::$app->request->get('module', 'purchase');  // Default module is purchase

        $query = Variant::find()
            ->alias('v')
            ->innerJoin('products p', 'v.productid::text = p.productid::text')  // Join the produk table
            // ->select(['v.varianid', 'v.produkid', 'p.namaproduk'])  // Select varianid, produkid, and namaproduk
            ->where(['p.companyid' => Yii::$app->session->get('companyid')])
            ->andWhere(['p.status' => 1]);;

        // Apply the LIKE condition only if $q is provided
        if (!empty($q)) {
            $query->andWhere([
                'or',
                ['ilike', 'v.sku', $q],
                ['ilike', 'v.description', $q]
            ]);
        }

        // Limit the results to prevent overload
        // $query->limit(10);

        // Prepare the results for Select2
        $results = [];
        foreach ($query->all() as $variant) {
            // var_dump($varian->produk , $varian);
            // die;
            $results[] = [
                'id' => $variant->variantid,  // The unique identifier of the variant
                'sku' => $variant->sku,
                'product' => $variant->product->productname,
                'description' => $variant->description,  // Customize display text
                'module' => $module,  // Pass the module to the frontend
            ];
        }

        return [
            'results' => $results
        ];
    }

    public function actionReflist()
    {
        // Ambil parameter module, type, dan q
        $module = Yii::$app->request->get('module', 'defaultModule');  // Default jika tidak ada
        $type = Yii::$app->request->get('type', 'defaultType');        // Default jika tidak ada
        $q = Yii::$app->request->get('q', '');  // Kata pencarian, jika ada

        // Menentukan trantype berdasarkan type
        $wotype = '';
        if ($type === 'return') {
            $wotype = $module . '/invoice'; // Jika type adalah return, cari wotype 'invoice'
        } elseif ($type === 'invoice') {
            $wotype = $module . '/delivery'; // Jika type adalah invoice, cari wotype 'delivery'
        } elseif ($type === 'delivery') {
            $wotype =  $module . '/order'; // Jika type adalah delivery, cari wotype 'order'
        } elseif ($type === 'order' && $module === 'purchase') {
            $wotype = $module . '/request'; // Jika type adalah order, cari wotype 'quotation'
        } elseif ($type === 'order' && $module === 'sales') {
            $wotype = $module . '/quote'; // Jika type adalah order, cari wotype 'quotation'
        }

        $session = Yii::$app->session;
        $companyid = $session->get('companyid');

        if (!$companyid) {
            // Jika companyid tidak ditemukan di session, ambil dari users
            $userId = Yii::$app->user->id;
            $companyid = Yii::$app->db->createCommand("SELECT companyid FROM users WHERE userid = :userid")
                ->bindValue(':userid', $userId)
                ->queryScalar();
        }

        $sql = "SELECT woid, wono, wotype FROM wo WHERE wotype = :wotype AND status = 1 AND companyid = :companyid";

        // Jika ada pencarian (q), tambahkan kondisi pencarian
        if (!empty($q)) {
            $sql .= " AND wono LIKE :q";
        }



        // Persiapkan query dan bind parameter
        $command = Yii::$app->db->createCommand($sql);
        $command->bindParam(':wotype', $wotype, \PDO::PARAM_STR);
        $command->bindParam(':companyid', $companyid, \PDO::PARAM_STR);

        // Jika ada pencarian, bind parameter untuk q
        if (!empty($q)) {
            $qParam = "%" . $q . "%"; // Untuk LIKE query
            $command->bindParam(':q', $qParam, \PDO::PARAM_STR);
        }

        // Eksekusi query
        $results = $command->queryAll();

        // Limit hasil pencarian
        // $query->limit(10);  // Sesuaikan dengan kebutuhan Anda

        // Ambil hasil pencarian
        $formattedResults = [];
        foreach ($results as $wo) {
            $formattedResults[] = [
                'id' => $wo['woid'],    // ID wo
                'wono' => $wo['wono'], // Nomor wo
                'module' => $module,         // Kirimkan module ke frontend
                'wotype' => $wo['wotype'], // Tampilkan wotype untuk informasi lebih lanjut
            ];
        }

        // Kembalikan hasil dalam format JSON
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // return var_dump($results);
        return [
            'results' => $formattedResults
        ];
    }


    public function actionGetprice($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $module = Yii::$app->request->get('module', 'purchase'); // Default to purchase

        // Find the variant from the 'Varian' table by varianid
        $variant = Variant::findOne($id);

        // Check if the variant exists
        if ($variant !== null) {
            // Fetch the price data from the 'VarianHarga' table using the varianid
            $price = VariantPrice::findOne(['variantid' => $id]);

            // Check if the price data exists
            if ($price !== null) {
                // Return different price based on module (purchase vs sales)
                $price = ($module === 'purchase') ? $price->price_buy : $price->price_sell;

                return [
                    'success' => true,
                    'price' => $price,
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Price data not found for this variant.',
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Variant not found.',
            ];
        }
    }

    public function actionGetno()
    {
        try {
            $nextWoNo = (new Wo())->nextNoWo();
        } catch (Exception $e) {
            $nextWoNo = "00001";
        }

        return $this->asJson([
            "no" => $nextWoNo
        ]);
    }
}
