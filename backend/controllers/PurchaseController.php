<?php

namespace backend\controllers;

use Yii;
use yii\db\Query;
use common\models\Tran;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\Varian;
use yii\filters\VerbFilter;
use common\models\TranDetail;
use common\models\VarianHarga;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * PurchaseController implements the CRUD actions for Tran model.
 */

class PurchaseController extends Controller
{
    // $details = TranDetail::find()
    // ->with(['varian.product', 'tran.contact']) // Load related data
    // ->where(['tranid' => $model->tranid])
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
                        'actions' => ['request', 'create', 'update', 'detail', 'delete', 'list', 'getno', 'contactlist', 'varianlist', 'reflist', 'getprice', 'updatestatus', 'massaction', 'print', 'duplicate','detail'],
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
     * Lists all Tran models.
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

        $searchModel = new Tran();

        // Simply get the last transaction by date instead of filtering by opadd
        $lastTran = Yii::$app->db->createCommand("
        SELECT tranno FROM trans WHERE status = 1 ORDER BY trandate DESC LIMIT 1")->queryOne();

        // Query to get all transactions with contact information where status = 1
        // Filter by trantype field to match the current module/type combination

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
            WHERE t.trantype = :trantype AND t.companyid = :companyid
            ORDER BY t.trandate DESC
        ")->bindValue(':trantype', "$module/$type")->bindValue(':companyid', "$companyid")->queryAll();

        // Create a title based on the parameters - capitalize first letter of both module and type
        $title = ucfirst($module) . " " . ucfirst($type);

        $numbercode = Yii::$app->db->createCommand("SELECT * FROM numbertemplates where companyid = '$companyid' AND type = '$type'")->queryAll();
        // return var_dump($transactions , $companyid);
        // die;

        $previewcode = Tran::nextNoTransaksi();
        return $this->render('index', [
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
            $bulanRomawi = Tran::convertToRoman(date('n'));
            $tahun = date('Y');

            // Build regex pattern di PHP
            $regexPattern = '^[0-9]+/' . $template . '/' . $bulanRomawi . '/' . $tahun . '$';

            // Cari nomor urut berdasarkan bulan dan tahun
            $sql = "
    SELECT COALESCE(
        MAX(CAST(
            regexp_replace(tranno, '^([0-9]+)/' || :template || '/" . $bulanRomawi . "/" . $tahun . "$', '\\1') AS INTEGER
        )), 0) + 1 AS next_no
    FROM trans
    WHERE tranno ~ :regexPattern
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
     * Displays a single Tran model.
     * @param string $id
     * @return mixed
     */
    public function actionDetail($id)
    {
        // Mengambil data transaksi utama
        $model = Tran::findOne($id);

        if ($model) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'pesan' => 'Transaksi tidak ditemukan'
                ];
            }
            return $this->redirect(['index']);
        }

        // Parse trantype to get module and type
        $parts = explode('/', $model->trantype);
        $module = $parts[0] ?? 'purchase';
        $type = $parts[1] ?? 'request';

        // Menggunakan query builder untuk mendapatkan detail transaksi
        $details = Yii::$app->db->createCommand("
        SELECT t.trandetailid, t.tranid, t.varianid, t.jumlah, t.itemsubtotal, t.harga, t.itemtype,
               v.productid, v.sku, v.stok, v.status, v.deskripsi, v.produkfoto,
               c.contact_name, c.address, c.contact_phone1, c.contact_email1
        FROM trandetails t
        LEFT JOIN varians v ON t.varianid::text = v.variantid::text
        LEFT JOIN trans tr ON t.tranid = tr.tranid
        LEFT JOIN contacts c ON tr.contact_id = c.contact_id
        WHERE t.tranid = :tranid
    ")
            ->bindValue(':tranid', $id)
            ->queryAll();

        // Menghitung total transaksi
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
        // Mengambil data transaksi utama
        $model = Tran::findOne($id);

        if (!$model) {
            Yii::$app->session->setFlash('error', 'Transaksi tidak ditemukan');
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

        // Parse trantype to get module and type
        $parts = explode('/', $model->trantype);
        $module = $parts[0] ?? 'purchase';
        $type = $parts[1] ?? 'request';

        // Menggunakan query builder untuk mendapatkan detail transaksi
        $details = Yii::$app->db->createCommand("
        SELECT t.trandetailid, t.tranid, t.varianid, t.totalafterdisc as jumlah, t.itemsubtotal, t.itemprice as harga,
               v.productid, p.productname as namaproduk,v.sku,0 as stok, v.status, v.description as deskripsi, v.productimage,
               c.contact_name,c.jobcompany, t.itemtype,
               tr.ppnamount, tr.pphamount, (tr.ppnamount + tr.pphamount) AS pajak_total
        FROM trandetails t
        LEFT JOIN variants v ON t.varianid::text = v.variantid::text
        LEFT JOIN products p ON p.productid::text = v.productid::text
        LEFT JOIN trans tr ON t.tranid = tr.tranid
        LEFT JOIN contacts c ON tr.contact_id = c.contact_id
        WHERE t.tranid = :tranid
    ")
            ->bindValue(':tranid', $id)
            ->queryAll();

        // Menghitung total transaksi
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
     * Creates a new Tran model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tran();

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

        // Generate transaksi number
        $tranNo = $model->nextNoTransaksi();
        $model->tranno = $tranNo;

        // Set the transaction type as combined module/type
        $model->trantype = "$module/$type";
        // var_dump($model->trantype);die;

        if ($model->load(Yii::$app->request->post())) {
            // Remove debugging code
            // var_dump($_POST);
            // die;

            if ($model->subtotal != null) {
                $model->subtotal = str_replace(".", "", $model->subtotal);
            }

            // Set default values if needed
            // if (empty($model->trandate)) {
            //     $model->trandate = date('Y-m-d');
            // }

            // var_dump($_POST['Tran']['subtotal'] , $model->subtotal);
            // die;
            // Flag harga termasuk pajak
            $model->priceincludetax = ($_POST['Tran']['price-include-tax'] ?? '') === 'on' ? 1 : 0;
            if (empty($model->trantype)) {
                $model->trantype = "$module/$type";
            }

            $valid = $model->validate(false);

            if ($valid) {

                $transaction = Yii::$app->db->beginTransaction();
                try {

                    $post = Yii::$app->request->post();

                    // Convert all numeric fields with proper formatting
                    $model->subtotal = isset($post['Tran']['subtotal']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['subtotal']) : 0;

                    $model->disc = isset($post['Tran']['disc']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['disc']) : 0;

                    $model->totalafterdisc = isset($post['Tran']['totalafterdisc']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['totalafterdisc']) : 0;

                    $model->ppnamount = isset($post['Tran']['ppnamount']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['ppnamount']) : 0;

                    $model->pphamount = isset($post['Tran']['pphamount']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['pphamount']) : 0;

                    $model->otherdiscount = isset($post['Tran']['otherdiscount']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['otherdiscount']) : 0;

                    $model->deliverycharge = isset($post['Tran']['deliverycharge']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['deliverycharge']) : 0;

                    $model->grandtotal = isset($post['Tran']['grandtotal']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['grandtotal']) : 0;

                    // Flag harga termasuk pajak
                    $model->priceincludetax = ($post['Tran']['price-include-tax'] ?? '') === 'on' ? 1 : 0;
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
                                $detailModel = new TranDetail();
                                $detailModel->tranid = $model->tranid;
                                $detailModel->variantid = $detail['variantid'] ?? null;
                                $detailModel->itemtype = $detail['deskripsi'] ?? null;
                                $detailModel->jumlah = (int) ($detail['jumlah'] ?? 1);
                                // var_dump($detail['harga'] );
                                // Ubah format harga dan diskon
                                $harga = str_replace(['.', ','], ['', '.'], $detail['harga'] ?? '0');
                                // var_dump($detail['harga'] ,$harga);
                                $itemdisc = str_replace(['.', ','], ['', '.'], $detail['itemdisctotal'] ?? '0');
                                // var_dump($detail['itemdisctotal']);
                                // die;
                                $totalAfterDisc = str_replace(['.', ','], ['', '.'], $detail['totalafterdisc'] ?? '0');
                                // var_dump($detail['itemsubtotal']);
                                // die;
                                $itemsubtotal = str_replace(['.', ','], ['', '.'], $detail['itemsubtotal'] ?? '0');

                                $detailModel->harga = (float) $harga;
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
                                'id' => $model->tranid,
                                'tranno' => $model->tranno,

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
     * Updates an existing Tran model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // Parse the trantype to get module and type

        $parts = explode('/', $model->trantype);
        $module = $parts[0] ?? 'purchase';
        $type = $parts[1] ?? 'request';

        // Yii::$app->formatter->asDate($model->trandate, 'php:d-m-Y');
        // Yii::$app->formatter->asDate($model->tranduedate, 'php:d-m-Y');
        // Retrieve existing transaction details
        $existingDetails = TranDetail::find()->where(['tranid' => $id])->all();
        $detailsData = [];

        // Build the details data array
        foreach ($existingDetails as $detail) {
            $varian = Yii::$app->db->createCommand('
                SELECT v.*, p.namaproduk
                FROM varians v
                LEFT JOIN products p ON v.productid = CAST(p.productid AS TEXT)
                WHERE v.variantid = :variantid
            ')
                ->bindValue(':variantid', $detail->variantid)
                ->queryOne();

            $detailItem = [
                'trandetailid' => $detail->trandetailid,
                'variantid' => $detail->variantid,
                'jumlah' => $detail->jumlah, // qty
                'harga' => $detail->harga, // price
                'subtotal' => $detail->itemsubtotal, // subtotal
                'totalafterdisc' => $detail->totalafterdisc, // subtotal
                'itemdiscpersen' => $detail->itemdiscpersen ?? 0, // disc(%)
                'itemdisctotal' => $detail->itemdisc ?? 0, // disc
                'taxtype' => $detail->taxtype ?? 0, // tax
                'itemtype' => $detail->itemtype, // tax
            ];

            if ($varian) {
                $detailItem['sku'] = $varian['sku'];
                $detailItem['deskripsi'] = $varian['deskripsi'] ?? $detail->itemtype;
                $detailItem['namaproduk'] = $varian['namaproduk'];
            }

            $detailsData[] = $detailItem;
        }

        if ($model->load(Yii::$app->request->post())) {
            // Set default values if needed
            // if (empty($model->trandate)) {
            //     $model->trandate = date('Y-m-d');
            // }

            // Format dates correctly
            if (!empty($model->trandate)) {
                if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $model->trandate)) {
                    $parts = explode('-', $model->trandate);
                    $model->trandate = $parts[2] . '-' . $parts[1] . '-' . $parts[0]; // Convert to YYYY-MM-DD
                }
            }

            if (!empty($model->tranduedate)) {
                if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $model->tranduedate)) {
                    $parts = explode('-', $model->tranduedate);
                    $model->tranduedate = $parts[2] . '-' . $parts[1] . '-' . $parts[0]; // Convert to YYYY-MM-DD
                }
            }

            // Ensure the trantype remains intact
            if (empty($model->trantype)) {
                $model->trantype = "$module/$type";
            }

            // Flag harga termasuk pajak (matching your actionCreate)
            $post = Yii::$app->request->post();
            $model->priceincludetax = ($post['Tran']['price-include-tax'] ?? '') === 'on' ? 1 : 0;

            $valid = $model->validate(false);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    // Convert all numeric fields with proper formatting before saving
                    $model->subtotal = isset($post['Tran']['subtotal']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['subtotal']) : 0;

                    $model->disc = isset($post['Tran']['disc']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['disc']) : 0;

                    $model->totalafterdisc = isset($post['Tran']['totalafterdisc']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['totalafterdisc']) : 0;

                    $model->ppnamount = isset($post['Tran']['ppnamount']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['ppnamount']) : 0;

                    $model->pphamount = isset($post['Tran']['pphamount']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['pphamount']) : 0;

                    $model->otherdiscount = isset($post['Tran']['otherdiscount']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['otherdiscount']) : 0;

                    $model->deliverycharge = isset($post['Tran']['deliverycharge']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['deliverycharge']) : 0;

                    $model->grandtotal = isset($post['Tran']['grandtotal']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['grandtotal']) : 0;

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

                                if (!isset($detail['jumlah']) || intval($detail['jumlah']) <= 0) {
                                    throw new \Exception('Product quantity must be greater than 0');
                                }

                                if (isset($detail['trandetailid']) && !empty($detail['trandetailid'])) {
                                    // Update existing detail
                                    $detailModel = TranDetail::findOne($detail['trandetailid']);
                                    if (!$detailModel) {
                                        // If not found, create new
                                        $detailModel = new TranDetail();
                                        $detailModel->tranid = $model->tranid;
                                    }
                                } else {
                                    // Create new detail
                                    $detailModel = new TranDetail();
                                    $detailModel->tranid = $model->tranid;
                                }

                                // Update detail attributes
                                $detailModel->variantid = $detail['variantid'];
                                $detailModel->jumlah = intval($detail['jumlah']);

                                // Process numeric values with proper formatting - matching your create action
                                $harga = str_replace(['.', ','], ['', '.'], $detail['harga'] ?? '0');
                                $itemdisc = str_replace(['.', ','], ['', '.'], $detail['itemdisctotal'] ?? '0');
                                $totalAfterDisc = str_replace(['.', ','], ['', '.'], $detail['totalafterdisc'] ?? '0');
                                $itemsubtotal = str_replace(['.', ','], ['', '.'], $detail['itemsubtotal'] ?? '0');

                                $detailModel->harga = (float) $harga;
                                $detailModel->itemdisc = (float) $itemdisc;
                                $detailModel->itemsubtotal = (float) $itemsubtotal;
                                $detailModel->totalafterdisc = (float) $totalAfterDisc;
                                $detailModel->itemtotal = (float) $totalAfterDisc;

                                // Set discount percentage
                                $discValue = str_replace(['.', ','], ['', '.'], $detail['disc'] ?? '0');
                                $detailModel->itemdiscpersen = (float) $discValue;

                                // Set tax type (explicitly cast to int)
                                $detailModel->taxtype = (int)($detail['tax_type'] ?? 0);
                                $detailModel->itemtype = ($detail['deskripsi']);

                                // Save the detail
                                if (!$detailModel->save(false)) {
                                    throw new \Exception('Failed to save transaction detail: ' . json_encode($detailModel->errors));
                                }

                                // Track this detail ID
                                $currentDetailIds[] = $detailModel->trandetailid;
                            }

                            // var_dump($detailModel->itemtype);die;

                            // Handle deleted details
                            if (!empty($currentDetailIds)) {
                                $removedDetails = TranDetail::find()
                                    ->where(['tranid' => $model->tranid])
                                    ->andWhere(['NOT IN', 'trandetailid', $currentDetailIds])
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
                                'id' => $model->tranid,
                                'tranno' => $model->tranno
                            ];
                        }

                        return $this->redirect(['request', 'module' => $module, 'type' => $type]);
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::error('Transaction error: ' . $e->getMessage(), 'application');

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
                        'id' => $model->tranid,
                        'tranno' => $model->tranno
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
        $model = new Tran();

        // Copy all attributes from the original model
        $model->attributes = $originalModel->attributes;

        // Reset the primary key and any unique fields
        $model->tranid = null;
        $model->tranno = $originalModel->tranno . " Copy";

        // Parse the trantype to get module and type
        $parts = explode('/', $originalModel->trantype);
        $module = $parts[0] ?? 'purchase';
        $type = $parts[1] ?? 'request';

        // Retrieve existing transaction details from the original
        $existingDetails = TranDetail::find()->where(['tranid' => $id])->all();
        $detailsData = [];

        // Build the details data array for the new transaction
        foreach ($existingDetails as $detail) {
            $varian = Yii::$app->db->createCommand('
                SELECT v.*, p.namaproduk
                FROM varians v
                LEFT JOIN products p ON v.productid = CAST(p.productid AS TEXT)
                WHERE v.variantid = :variantid
            ')
                ->bindValue(':variantid', $detail->variantid)
                ->queryOne();

            $detailItem = [
                'variantid' => $detail->variantid,
                'jumlah' => $detail->jumlah, // qty
                'harga' => $detail->harga, // price
                'subtotal' => $detail->itemsubtotal, // subtotal
                'totalafterdisc' => $detail->totalafterdisc, // subtotal
                'itemdiscpersen' => $detail->itemdiscpersen ?? 0, // disc(%)
                'itemdisctotal' => $detail->itemdisc ?? 0, // disc
                'taxtype' => $detail->taxtype ?? 0, // tax
                'itemtype' => $detail->itemtype ?? 0, // tax
            ];

            if ($varian) {
                $detailItem['sku'] = $varian['sku'];
                $detailItem['deskripsi'] = $varian['deskripsi'] ?? $detail->itemtype;
                $detailItem['namaproduk'] = $varian['namaproduk'];
            }

            $detailsData[] = $detailItem;
        }

        if ($model->load(Yii::$app->request->post())) {
            // Set default values if needed
            // if (empty($model->trandate)) {
            //     $model->trandate = date('Y-m-d');
            // }

            // Format dates correctly
            if (!empty($model->trandate)) {
                if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $model->trandate)) {
                    $parts = explode('-', $model->trandate);
                    $model->trandate = $parts[2] . '-' . $parts[1] . '-' . $parts[0]; // Convert to YYYY-MM-DD
                }
            }

            if (!empty($model->tranduedate)) {
                if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $model->tranduedate)) {
                    $parts = explode('-', $model->tranduedate);
                    $model->tranduedate = $parts[2] . '-' . $parts[1] . '-' . $parts[0]; // Convert to YYYY-MM-DD
                }
            }

            // Ensure the trantype remains intact
            if (empty($model->trantype)) {
                $model->trantype = "$module/$type";
            }

            // Flag harga termasuk pajak
            $post = Yii::$app->request->post();
            $model->priceincludetax = ($post['Tran']['price-include-tax'] ?? '') === 'on' ? 1 : 0;

            $valid = $model->validate(false);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    // Convert all numeric fields with proper formatting before saving
                    $model->subtotal = isset($post['Tran']['subtotal']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['subtotal']) : 0;

                    $model->disc = isset($post['Tran']['disc']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['disc']) : 0;

                    $model->totalafterdisc = isset($post['Tran']['totalafterdisc']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['totalafterdisc']) : 0;

                    $model->ppnamount = isset($post['Tran']['ppnamount']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['ppnamount']) : 0;

                    $model->pphamount = isset($post['Tran']['pphamount']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['pphamount']) : 0;

                    $model->otherdiscount = isset($post['Tran']['otherdiscount']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['otherdiscount']) : 0;

                    $model->deliverycharge = isset($post['Tran']['deliverycharge']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['deliverycharge']) : 0;

                    $model->grandtotal = isset($post['Tran']['grandtotal']) ?
                        (float) str_replace(['.', ','], ['', '.'], $post['Tran']['grandtotal']) : 0;

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

                                if (!isset($detail['jumlah']) || intval($detail['jumlah']) <= 0) {
                                    throw new \Exception('Product quantity must be greater than 0');
                                }

                                // Create new detail for the new transaction
                                $detailModel = new TranDetail();
                                $detailModel->tranid = $model->tranid;

                                // Set detail attributes
                                $detailModel->variantid = $detail['variantid'];
                                $detailModel->jumlah = intval($detail['jumlah']);

                                // Process numeric values with proper formatting
                                $harga = str_replace(['.', ','], ['', '.'], $detail['harga'] ?? '0');
                                $itemdisc = str_replace(['.', ','], ['', '.'], $detail['itemdisctotal'] ?? '0');
                                $totalAfterDisc = str_replace(['.', ','], ['', '.'], $detail['totalafterdisc'] ?? '0');
                                $itemsubtotal = str_replace(['.', ','], ['', '.'], $detail['itemsubtotal'] ?? '0');

                                $detailModel->harga = (float) $harga;
                                $detailModel->itemdisc = (float) $itemdisc;
                                $detailModel->itemsubtotal = (float) $itemsubtotal;
                                $detailModel->totalafterdisc = (float) $totalAfterDisc;
                                $detailModel->itemtotal = (float) $totalAfterDisc;

                                // Set discount percentage
                                $discValue = str_replace(['.', ','], ['', '.'], $detail['disc'] ?? '0');
                                $detailModel->itemdiscpersen = (float) $discValue;

                                // Set tax type (explicitly cast to int)
                                $detailModel->taxtype = (int)($detail['tax_type'] ?? 0);
                                $detailModel->itemtype = (int)($detail['deskripsi'] ?? 0);

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
                                'id' => $model->tranid,
                                'tranno' => $model->tranno
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

        $model = Tran::findOne($id);
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
            // $idString = implode(',', array_map(fn($id) => "'" . addslashes($id) . "'", $ids));
            $idString = implode(',', array_map(function ($id) {
                return "'" . addslashes($id) . "'";
            }, $ids));
            // Create the SQL query
            $sql = "UPDATE trans SET status = :status,
                updatedat = NOW(),
                updatedby = :user_id,
                updatedip = :user_ip
                WHERE tranid IN ($idString)";

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
     * Deletes an existing Tran model.
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
            $parts = explode('/', $model->trantype);
            $module = $parts[0] ?? 'purchase';
            $type = $parts[1] ?? 'request';

            // Get all details before deleting to adjust stock
            $details = TranDetail::find()->where(['tranid' => $id])->all();
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
        $ordercolumn = $params['columns'][$sortcolumn]['data'] ?? 'trandate';
        $columnorder = $params['order'][0]['dir'] ?? 'DESC';

        // Validasi sorting biar aman dari SQL Injection
        $allowedColumns = ['tranno', 'trandate', 'tranduedate', 'contact_name', 'total'];
        if (!in_array($ordercolumn, $allowedColumns)) {
            $ordercolumn = 'trandate'; // Default sorting
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
                    t.tranid,
                    t.status,
                    t.tranno,
                    t.trandate,
                    t.tranduedate,
                    t.trantype,
                    t.totalpaid,
                    t.statuspaid,
                    t.grandtotal,
                    c.contact_name,
                    c.jobcompany,
                    (SELECT COALESCE(SUM(td.itemsubtotal), 0) FROM trandetails td WHERE td.tranid = t.tranid) as total
                  FROM trans t
                  LEFT JOIN contacts c ON c.contact_id = t.contact_id
                  WHERE t.companyid = '$companyid' AND t.status <> 10
                  ";

        // Filter berdasarkan tipe transaksi
        if ($type !== '') {
            $query .= " AND t.trantype = :trantype";
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
                t.tranno ILIKE :search
             OR c.contact_name ILIKE :search
            )";
        }

        if ($date != "") {
            $dates = explode(" - ", $date);

            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));
            // var_dump($startDate, $endDate);die;
            $filter .= " AND DATE(t.trandate) BETWEEN :startDate AND :endDate";
        }

        // Query akhir dengan ORDER BY
        $query .= $filter . " ORDER BY " . $ordercolumn . " " . $columnorder;

        // Prepare the command with parameters
        $command = Yii::$app->db->createCommand($query);

        // Bind parameters (more secure than string concatenation)
        if ($type !== '') {
            $command->bindValue(':trantype', $trantype);
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
            if (isset($row['trandate'])) {
                $row['trandate_display'] = Yii::$app->formatter->asDate($row['trandate'], 'php:d-m-Y');
            }
            if (isset($row['tranduedate'])) {
                $row['tranduedate_display'] = $row['tranduedate'] ? Yii::$app->formatter->asDate($row['tranduedate'], 'php:d-m-Y') : null;
            }

            // Parse trantype to extract module and type
            if (isset($row['trantype'])) {
                $parts = explode('/', $row['trantype']);
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

        $query = Varian::find()
            ->alias('v')
            ->innerJoin('produk p', 'v.productid::text = p.productid::text')  // Join the produk table
            // ->select(['v.variantid', 'v.productid', 'p.namaproduk'])  // Select variantid, productid, and namaproduk
            ->where(['p.companyid' => Yii::$app->session->get('companyid')])
            ->andWhere(['p.status' => 1]);;

        // Apply the LIKE condition only if $q is provided
        if (!empty($q)) {
            $query->andWhere([
                'or',
                ['ilike', 'v.sku', $q],
                ['ilike', 'v.deskripsi', $q]
            ]);
        }

        // Limit the results to prevent overload
        // $query->limit(10);

        // Prepare the results for Select2
        $results = [];
        foreach ($query->all() as $varian) {
            // var_dump($varian->produk , $varian);
            // die;
            $results[] = [
                'id' => $varian->variantid,  // The unique identifier of the variant
                'sku' => $varian->sku,
                'produk' => $varian->produk->namaproduk,
                'deskripsi' => $varian->deskripsi,  // Customize display text
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
        $trantype = '';
        if ($type === 'return') {
            $trantype = $module . '/invoice'; // Jika type adalah return, cari trantype 'invoice'
        } elseif ($type === 'invoice') {
            $trantype = $module . '/delivery'; // Jika type adalah invoice, cari trantype 'delivery'
        } elseif ($type === 'delivery') {
            $trantype =  $module . '/order'; // Jika type adalah delivery, cari trantype 'order'
        } elseif ($type === 'order' && $module === 'purchase') {
            $trantype = $module . '/request'; // Jika type adalah order, cari trantype 'quotation'
        } elseif ($type === 'order' && $module === 'sales') {
            $trantype = $module . '/quote'; // Jika type adalah order, cari trantype 'quotation'
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

        $sql = "SELECT tranid, tranno, trantype FROM trans WHERE trantype = :trantype AND status = 1 AND companyid = :companyid";

        // Jika ada pencarian (q), tambahkan kondisi pencarian
        if (!empty($q)) {
            $sql .= " AND tranno LIKE :q";
        }



        // Persiapkan query dan bind parameter
        $command = Yii::$app->db->createCommand($sql);
        $command->bindParam(':trantype', $trantype, \PDO::PARAM_STR);
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
        foreach ($results as $tran) {
            $formattedResults[] = [
                'id' => $tran['tranid'],    // ID transaksi
                'tranno' => $tran['tranno'], // Nomor transaksi
                'module' => $module,         // Kirimkan module ke frontend
                'trantype' => $tran['trantype'], // Tampilkan trantype untuk informasi lebih lanjut
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

        // Find the variant from the 'Varian' table by variantid
        $varian = Varian::findOne($id);

        // Check if the variant exists
        if ($varian !== null) {
            // Fetch the price data from the 'VarianHarga' table using the variantid
            $harga = VarianHarga::findOne(['variantid' => $id]);

            // Check if the price data exists
            if ($harga !== null) {
                // Return different price based on module (purchase vs sales)
                $price = ($module === 'purchase') ? $harga->harga_beli : $harga->harga_jual;

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
            $nextTranNo = (new Tran())->nextNoTransaksi();
        } catch (Exception $e) {
            $nextTranNo = "00001";
        }

        return $this->asJson([
            "no" => $nextTranNo
        ]);
    }
}
