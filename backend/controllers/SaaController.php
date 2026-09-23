<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Tran;
use common\models\TranDetail;
use common\models\Varian;
use common\models\VarianHarga;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\db\Query;

/**
 * SalesController implements the CRUD actions for Tran model.
 */
class SalesController extends Controller
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
                        'actions' => ['request', 'create', 'update', 'detail', 'delete', 'list', 'getno', 'contactlist', 'varianlist', 'getprice'],
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
                            'getprice',
                            'getno'
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
        $url = Yii::$app->request->url;

        // Default type
        $type = 'request';

        // Extract type from URL
        if (preg_match('#/sales/([^/\?]+)#', $url, $matches)) {
            $type = $matches[1];
        }

        // Also check GET parameter if it exists (fallback)
        if (Yii::$app->request->get('type')) {
            $type = Yii::$app->request->get('type');
        }

        $searchModel = new Tran();

        // Simply get the last transaction by date instead of filtering by opadd
        $lastTran = Yii::$app->db->createCommand("
        SELECT tranno FROM tran WHERE status = 1 ORDER BY trandate DESC LIMIT 1")->queryOne();

        // Query to get all transactions with contact information where status = 1
        // Filter by trantype field to match the current type
        $transactions = Yii::$app->db->createCommand("
            SELECT
                t.tranid,
                t.contactid,
                t.trandate,
                t.tranduedate,
                t.tranno,
                t.status,
                t.trantype,
                c.contact_name AS contact_name
            FROM tran t
            LEFT JOIN contact c ON c.contact_id = t.contactid
            WHERE t.trantype = :type
            ORDER BY t.trandate DESC
        ")->bindValue(':type', $type)->queryAll();

        // Create a title based on the parameter - capitalize first letter and add "Sales" prefix
        $title = "Sales " . ucfirst($type);

        return $this->render('index', [
            'model' => $lastTran,
            'transactions' => $transactions,
            'searchModel' => $searchModel,
            'type' => $type,
            'title' => $title,
        ]);
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

        if (!$model) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'pesan' => 'Transaksi tidak ditemukan'
                ];
            }
            return $this->redirect(['index']);
        }

        // Menggunakan query builder untuk mendapatkan detail transaksi
        $details = Yii::$app->db->createCommand("
        SELECT t.trandetailid, t.tranid, t.varianid, t.jumlah, t.itemsubtotal, t.harga,
               v.produkid, v.sku, v.stok, v.status, v.deskripsi, v.produkfoto,
               c.contact_name
        FROM trandetail t
        LEFT JOIN varian v ON t.varianid::text = v.varianid::text
        LEFT JOIN tran tr ON t.tranid = tr.tranid
        LEFT JOIN contact c ON tr.contactid = c.contact_id
        WHERE t.tranid = :tranid
    ")
    ->bindValue(':tranid', $id)
    ->queryAll();

        // Menghitung total transaksi
        $total = 0;
        foreach ($details as $detail) {
            $total += $detail['itemsubtotal'];
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_detail', [
                'model' => $model,
                'details' => $details,
                'total' => $total,
            ]);
        } else {
            return $this->render('_detail', [
                'model' => $model,
                'details' => $details,
                'total' => $total,
            ]);
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
        $type = Yii::$app->request->get('type', 'request');

        // Generate transaksi number
        $tranNo = $model->nextNoTransaksi();
        $model->tranno = $tranNo;

        $model->trantype = $type;

        if ($model->load(Yii::$app->request->post())) {
            // Set default values if needed
            if (empty($model->trandate)) {
                $model->trandate = date('Y-m-d');
            }

            if (empty($model->tranduedate)) {
                // Default jatuh tempo 30 hari
                $model->tranduedate = date('Y-m-d', strtotime('+30 days'));
            }

            if (empty($model->trantype)) {
                $model->trantype = $type;
            }

            $valid = $model->validate(false);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    // Save main transaction data
                    if ($flag = $model->save(false)) {
                        // Get transaction details from POST
                        $detailsJson = Yii::$app->request->post('details');

                        // Check if details exist and decode the JSON
                        if (!empty($detailsJson)) {
                            $details = json_decode($detailsJson, true);

                            if (is_array($details)) {
                                foreach ($details as $detail) {
                                    $detailModel = new TranDetail();
                                    $detailModel->tranid = $model->tranid;
                                    $detailModel->varianid = $detail['varianid'];
                                    $detailModel->jumlah = $detail['jumlah'];

                                    // Get price from VarianHarga
                                    $varianHarga = VarianHarga::findOne(['varianid' => $detail['varianid']]);
                                    if ($varianHarga) {
                                        // Use harga_jual for sales
                                        $detailModel->harga = $varianHarga->harga_jual;
                                        $detailModel->itemsubtotal = $detailModel->harga * $detailModel->jumlah;
                                    } else {
                                        $detailModel->harga = 0;
                                        $detailModel->itemsubtotal = 0;
                                    }

                                    if (!$detailModel->save(false)) {
                                        throw new \Exception('Failed to save transaction detail: ' . json_encode($detailModel->errors));
                                    }

                                    // Update stock for this variant - DECREASE for sales
                                    $varian = Varian::findOne($detail['varianid']);
                                    if ($varian) {
                                        $varian->stok -= $detail['jumlah']; // Decrease stock for sales
                                        if (!$varian->save(false)) {
                                            throw new \Exception('Failed to update stock: ' . json_encode($varian->errors));
                                        }
                                    }
                                }
                            }
                        }

                        $transaction->commit();

                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return [
                                'success' => true,
                                'pesan' => 'Data Berhasil Disimpan',
                                'id' => $model->tranid,
                                'tranno' => $model->tranno
                            ];
                        }

                        return $this->redirect(['index']);
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
                'type' => $type,  // Pass type to the form
            ]);
        } else {
            return $this->render('_form', [
                'dialog' => 1,
                'model' => $model,
                'isajax' => "false",
                'type' => $type,  // Pass type to the form
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

        // Retrieve existing transaction details
        $existingDetails = TranDetail::find()->where(['tranid' => $id])->all();
        $detailsData = [];

        // Build the details data array
        foreach ($existingDetails as $detail) {
            $varian = Varian::findOne($detail->varianid);

            $detailItem = [
                'trandetailid' => $detail->trandetailid,
                'varianid' => $detail->varianid,
                'jumlah' => $detail->jumlah,
                'harga' => $detail->harga,
                'itemsubtotal' => $detail->itemsubtotal
            ];

            if ($varian) {
                $detailItem['sku'] = $varian->sku;
                $detailItem['deskripsi'] = $varian->deskripsi;
            }

            $detailsData[] = $detailItem;
        }

        if ($model->load(Yii::$app->request->post())) {
            // Log POST data for debugging
            $valid = $model->validate(false);
            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    // Save the model and store result in $flag
                    $flag = $valid;

                    if (!$flag) {
                        Yii::error('Model failed to save: ' . json_encode($model->errors), 'application');
                        throw new \Exception('Failed to save transaction. Please check the logs.');
                    }

                    // Get transaction details from POST
                    $detailsJson = Yii::$app->request->post('details');

                    // Process details if data exists
                    if (!empty($detailsJson)) {
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
                                if (empty($detail['varianid'])) {
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
                                $detailModel->varianid = $detail['varianid'];
                                $detailModel->jumlah = intval($detail['jumlah']);

                                // Handle price
                                if (isset($detail['harga'])) {
                                    // If price is provided, use it (ensure it's clean from formatting)
                                    $harga = is_numeric($detail['harga']) ?
                                        $detail['harga'] :
                                        intval(preg_replace('/[^\d]/', '', $detail['harga']));

                                    $detailModel->harga = $harga;
                                } else {
                                    // If not, get from varianharga
                                    $varianHarga = VarianHarga::findOne(['varianid' => $detail['varianid']]);
                                    $detailModel->harga = $varianHarga ? $varianHarga->harga_jual : 0;
                                }

                                // Calculate subtotal
                                $detailModel->itemsubtotal = $detailModel->harga * $detailModel->jumlah;

                                // Save the detail
                                if (!$detailModel->save(false)) {
                                    throw new \Exception('Failed to save transaction detail: ' . json_encode($detailModel->errors));
                                }

                                // Track this detail ID
                                $currentDetailIds[] = $detailModel->trandetailid;
                            }

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
                    } else {
                        throw new \Exception('Transaction details cannot be empty');
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

                    return $this->redirect(['index']);
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
            ]);
        } else {
            return $this->render('_form', [
                'model' => $model,
                'isajax' => "false",
                'detailsData' => $detailsData,
            ]);
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

            // Get all details before deleting to adjust stock
            $details = TranDetail::find()->where(['tranid' => $id])->all();
            // Update stock for each item
            foreach ($details as $detail) {
                $varian = Varian::findOne($detail->varianid);
                if ($varian) {
                    $varian->stok += $detail->jumlah; // Add back stock for sales deletion
                    if (!$varian->save(false)) {
                        throw new \Exception('Failed to update stock: ' . json_encode($varian->errors));
                    }
                }
            }

            // Soft delete - set status to 10 instead of deleting
            $model->status = 10;

            if ($model->save(false)) {
                $transaction->commit();
                return $this->asJson(['success' => true]);
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
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = Yii::$app->user->id;
        $params = Yii::$app->request->queryParams; // Ambil parameter filter

        // Parameter filter untuk transaksi
        $contact = $params['contact'] ?? '';
        $search = $params['search'] ?? '';
        $date = $params['datefilter'] ?? '';
        $type = $params['type'] ?? ''; // Get transaction type from query parameters

        // Ambil data sorting dari DataTables
        $sortcolumn = $params['order'][0]['column'] ?? 0;
        $ordercolumn = $params['columns'][$sortcolumn]['data'] ?? 'trandate';
        $columnorder = $params['order'][0]['dir'] ?? 'DESC';

        // Validasi sorting biar aman dari SQL Injection
        $allowedColumns = ['tranno', 'trandate', 'tranduedate', 'contact_name', 'total'];
        if (!in_array($ordercolumn, $allowedColumns)) {
            $ordercolumn = 'trandate'; // Default sorting
        }

        // Query untuk ambil data transaksi
        $query = "SELECT
                    t.tranid,
                    t.tranno,
                    t.trandate,
                    t.tranduedate,
                    t.trantype,
                    c.contact_name,
                    (SELECT COALESCE(SUM(td.itemsubtotal), 0) FROM trandetail td WHERE td.tranid = t.tranid) as total
                  FROM tran t
                  LEFT JOIN contact c ON c.contact_id = t.contactid
                  WHERE t.status = 1
                  ";

        // Filter berdasarkan tipe transaksi
        if ($type !== '') {
            $query .= " AND t.trantype = :type";
        }

        // Buat kondisi filter
        $filter = "";

        if (isset($contact) && $contact !== '') {
            $filter .= " AND t.contactid = :contact";
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
            $filter .= " AND DATE(t.trandate) BETWEEN :startDate AND :endDate";
        }

        // Query akhir dengan ORDER BY
        $query .= $filter . " ORDER BY " . $ordercolumn . " " . $columnorder;

        // Prepare the command with parameters
        $command = Yii::$app->db->createCommand($query);

        // Bind parameters (more secure than string concatenation)
        if ($type !== '') {
            $command->bindValue(':type', $type);
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

        // Format tanggal untuk output JSON
        foreach ($data as &$row) {
            if (isset($row['trandate'])) {
                $row['trandate_display'] = Yii::$app->formatter->asDate($row['trandate'], 'php:d-m-Y');
            }
            if (isset($row['tranduedate'])) {
                $row['tranduedate_display'] = $row['tranduedate'] ? Yii::$app->formatter->asDate($row['tranduedate'], 'php:d-m-Y') : null;
            }
        }

        return ['data' => $data ?: []]; // Format untuk datatable
    }

    public function actionContactlist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = Yii::$app->user->id;
        $params = Yii::$app->request->queryParams; // Ambil parameter filter
        $Gender = $params['gender'] ?? '';
        $Married = $params['married'] ?? '';
        $Religion = $params['reli'] ?? '';
        $vendor = $params['isvendor'] ?? '';
        $customer = $params['iscustomer'] ?? '';
        $search = $params['search'] ?? '';
        $date = $params['datefilter'] ?? '';

        // var_dump($params);
        // die;
        // Ambil data sorting dari DataTables
        $sortcolumn = $params['order'][0]['column'] ?? 0;
        $ordercolumn = $params['columns'][$sortcolumn]['data'] ?? 'contact_no';
        $columnorder = $params['order'][0]['dir'] ?? 'DESC';

        // Validasi sorting biar aman dari SQL Injection
        $allowedColumns = ['contact_no', 'contact_name', 'contact_phone1', 'married'];
        if (!in_array($ordercolumn, $allowedColumns)) {
            $ordercolumn = 'contact_no'; // Default sorting
        }
        // var_dump($params);
        // die;
        $query = "SELECT
        c.*,
        g.enumtext_en AS gender,
        r.enumtext_en AS religion,
        m.enumtext_en AS married
        -- t.enumtext_en AS tipe
        FROM contact c
        LEFT JOIN company co ON c.companyid = co.companyid
        LEFT JOIN enum g ON g.enumid = c.contact_gender AND g.enumtype = 'gender'
        LEFT JOIN enum r ON r.enumid = c.contact_religion AND r.enumtype = 'religion'
        LEFT JOIN enum m ON m.enumid = c.contact_married AND m.enumtype = 'married'
        -- LEFT JOIN enum t ON t.enumno = c.contact_typeid AND t.enumtype = 'contacttype'
      WHERE co.userid = '$id' AND c.contact_status = '1'
      ";

        // Buat kondisi filter
        $filter = "";

        // Jangan pakai empty(), karena 0 akan dianggap false
        if (isset($Gender) && $Gender !== '') {
            $filter .= " AND c.contact_gender = '$Gender'";
        }

        if (isset($Married) && $Married !== '') {
            $filter .= " AND c.contact_married = '$Married'";
        }

        if (isset($Religion) && $Religion !== '') {
            $filter .= " AND c.contact_religion = '$Religion'";
        }

        if (isset($vendor) && $vendor !== '') {
            $filter .= " AND c.contact_isvendor = 1";
        }

        if (isset($customer) && $customer !== '') {
            $filter .= " AND c.contact_iscustomer = 1";
        }

        // if (!empty($Type)) {
        //     var_dump($Type);
        //     $filter .= " AND (";
        //     $filters = [];

        //     foreach ($Type as $val) {
        //         $filters[] = match ($val) {
        //             'contacttype.cs' => "c.contact_iscustomer = '1'",
        //             'contacttype.vn' => "c.contact_isvendor = '1'",
        //             default => null
        //         };
        //     }
        //     $filter .= implode(" OR ", array_filter($filters)) . ")";
        // }

        if (isset($search) && $search !== "") {
            $filter .= " AND  (
            c.contact_name ILIKE '%$search%'
         OR c.contact_email1 ILIKE '%$search%'
         OR c.contact_phone1 ILIKE '%$search%'
         OR CAST (c.contact_education as text) ILIKE '%$search%'
         OR CAST (c.contact_no as text) ILIKE '%$search%'
         )";
        }

        if ($date != "") {
            $dates = explode(" - ", $date);
            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));
            $filter .= " AND DATE(c.contact_bod) BETWEEN '" . $startDate . "' AND '" . $endDate . "'";
        }
        // Query akhir dengan ORDER BY & LIMIT
        $query .= $filter . " ORDER BY " . $ordercolumn . " " . $columnorder;
        // var_dump($query);
        // die;
        $data = Yii::$app->db->createCommand($query)->queryAll();
        return ['data' => $data ?: []]; // Format untuk datatable
    }

    public function actionVarianlist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $q = \Yii::$app->request->get('q', '');  // Default to empty string if not provided

        // Initialize query to fetch variants from 'varian' table
        $query = Varian::find()
            ->alias('v')
            ->innerJoin('produk p', 'v.produkid::text = p.produkid::text')  // Cast both fields to text
            ->where(['p.userid' => Yii::$app->user->id]);

        // Apply the LIKE condition only if $q is provided
        if (!empty($q)) {
            $query->andWhere(['or',
                ['ilike', 'v.sku', $q],
                ['ilike', 'v.deskripsi', $q]
            ]);
        }

        // Limit the results to prevent overload
        $query->limit(10);

        // Prepare the results for Select2
        $results = [];
        foreach ($query->all() as $varian) {
            $results[] = [
                'id' => $varian->varianid,  // The unique identifier of the variant
                'sku' => $varian->sku,
                'text' => $varian->deskripsi,  // Customize display text
            ];
        }

        return [
            'results' => $results
        ];
    }

    public function actionGetprice($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Find the variant from the 'Varian' table by varianid
        $varian = Varian::findOne($id);

        // Check if the variant exists
        if ($varian !== null) {
            // Fetch the price data from the 'VarianHarga' table using the varianid
            $harga = VarianHarga::findOne(['varianid' => $id]);

            // Check if the price data exists
            if ($harga !== null) {
                return [
                    'success' => true,
                    'price' => $harga->harga_jual,  // Return the price (could be beli, grosir, or jual depending on requirement)
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
