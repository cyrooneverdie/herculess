<?php

namespace backend\controllers;

use common\models\Coas;
use yii\web\Response;
use Yii;
use yii\web\Controller;
use common\models\Kas;
use common\models\Tran;
use common\models\TranDetail;
use common\models\KasDetail;
use common\models\Contact;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\db\Query;

/**
 * KasController implements the CRUD actions for Kas model.
 */
class KasController extends Controller
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
                        'actions' => ['index', 'create', 'update', 'delete', 'list', 'getno', 'contactlist', 'invoicelist', 'purchaselist', 'invoicedetail', 'massaction', 'akunlist'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return true;
                        }
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("kas", "lihat"));
                        }
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("kas", "tambah"));
                        }
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("kas", "ubah"));
                        }
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("kas", "hapus"));
                        }
                    ],
                    [
                        'actions' => [
                            'index',
                            'create',
                            'update',
                            'list',
                            'contactlist',
                            'invoicelist',
                            'invoicedetail',
                            'purchaselist',
                            'getno',
                            'updatestatus',
                            'massaction',
                            'akunlist'
                        ],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Kas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Kas();

        // Simply get the last transaction by date
        $lastKas = Yii::$app->db->createCommand("
        SELECT kasnomor FROM kas WHERE status = 1 ORDER BY kasdate DESC LIMIT 1")->queryOne();

        // Query to get all kas transactions with contact information
        $transactions = Yii::$app->db->createCommand("
            SELECT
                k.kasid,
                k.refid,
                k.kasdate,
                k.kasnomor,
                k.status,
                c.contact_name AS contact_name
            FROM kas k
            LEFT JOIN contact c ON c.contact_id = k.refid
            ORDER BY k.kasdate DESC
        ")->queryAll();

        $title = "Daftar Transaksi Kas";

        return $this->render('index', [
            'model' => $lastKas,
            'transactions' => $transactions,
            'searchModel' => $searchModel,
            'title' => $title,
        ]);
    }
    /**
     * Creates a new Kas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionCreate()
    {
        $model = new Kas();

        // Generate nomor kas
        $cashNo = $model->nextNoKas();
        $model->kasnomor = $cashNo;

        if ($model->load(Yii::$app->request->post())) {

            // Set default values if needed
            if (empty($model->kasdate)) {
                $model->kasdate = date('Y-m-d');
            } else {
                // Make sure date is properly formatted
                $parts = explode('-', $model->kasdate);
                if (count($parts) === 3 && strlen($parts[2]) === 4) {
                    // Format from DD-MM-YYYY to YYYY-MM-DD
                    $model->kasdate = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
                }
            }

            // Set default values for required fields
            $model->jenis = Yii::$app->request->get('jenis', 0); // Default jenis (0=pengeluaran, 1=pemasukan)
            $model->sumber = 0; // Default sumber
            $model->refid = Yii::$app->request->post('refid');
            $model->status = 0; // Default status
            // var_dump($model->refid);die;
            // Set totalpaid from Cash array
            $cashData = Yii::$app->request->post('Cash');
            if (isset($cashData['totalpaid'])) {
                $model->totalpaid = (float) str_replace('.', '', $cashData['totalpaid']);
            }

            $valid = $model->validate(false);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    // Save main kas data
                    if ($flag = $model->save(false)) {
                        // Process details from POST data
                        $details = Yii::$app->request->post('details');
                        $cashDetails = Yii::$app->request->post('cash_details');

                        // Parse JSON cash_details if exists
                        $parsedCashDetails = null;
                        if (!empty($cashDetails)) {
                            $parsedCashDetails = json_decode($cashDetails, true);
                        }

                        // Check if details exist and process the data
                        if (!empty($details)) {
                            foreach ($details as $detail) {
                                $detailModel = new KasDetail();
                                $detailModel->kasid = $model->kasid; // Relation to the newly saved kas
                                $detailModel->akunid = $detail['akunid'];

                                // Map cashdetailtype to detail column
                                $detailModel->detail = $detail['cashdetailtype'] ?? '';

                                // Handle jumlah and price/harga
                                $detailModel->jumlah = (int)($detail['jumlah'] ?? $detail['qty'] ?? 1);
                                $detailModel->price = (float)str_replace('.', '', $detail['harga'] ?? 0);

                                // Convert value from formatted string (200.000) to float (200000)
                                $detailModel->value = (float)str_replace('.', '', $detail['value'] ?? 0);

                                // Set statuspaid if available
                                $detailModel->statuspaid = $detail['is_manual'] ?? '0';

                                // Set default status if not provided
                                $detailModel->status = 0;

                                // Save KasDetail
                                if (!$detailModel->save(false)) {
                                    throw new \Exception('Failed to save kas detail: ' . json_encode($detailModel->errors));
                                }
                            }
                        }
                        // If no details array but we have parsed JSON details
                        elseif (!empty($parsedCashDetails)) {
                            foreach ($parsedCashDetails as $detail) {
                                $detailModel = new KasDetail();
                                $detailModel->kasid = $model->kasid;
                                $detailModel->akunid = $detail['akunid'] ?? null;

                                // Map fields from JSON structure
                                $detailModel->detail = $detail['cashdetailtype'] ?? '';
                                $detailModel->jumlah = (int)($detail['qty'] ?? 1);
                                $detailModel->price = (float)($detail['harga'] ?? 0);
                                $detailModel->value = (float)($detail['amount'] ?? 0);
                                $detailModel->statuspaid = $detail['is_manual'] ?? '0';
                                $detailModel->status = $detail['paid'] ?? 0;

                                // Save KasDetail
                                if (!$detailModel->save(false)) {
                                    throw new \Exception('Failed to save kas detail from JSON: ' . json_encode($detailModel->errors));
                                }
                            }
                        }

                        // Commit transaction
                        $transaction->commit();

                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return [
                                'success' => true,
                                'pesan' => 'Data Berhasil Disimpan',
                                'id' => $model->kasid,
                                'cashno' => $model->kasnomor,
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
            ]);
        } else {
            return $this->render('_form', [
                'dialog' => 1,
                'model' => $model,
                'isajax' => "false",
            ]);
        }
    }


    /**
     * Updates an existing Kas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        // Retrieve existing kas details
        $existingDetails = KasDetail::find()->where(['kasid' => $id])->all();
        $detailsData = [];

        // Format totalpaid jika ada
        if ($model->totalpaid !== null) {
            $model->totalpaid = number_format($model->totalpaid, 0, '', '.');
        }

        // Dapatkan data invoice terkait jika ada
        $invoiceData = null;
        if (!empty($model->refid)) {
            $invoice = Tran::findOne($model->refid);
            if ($invoice) {
                $invoiceData = [
                    'tranid' => $invoice->tranid,
                    'tranno' => $invoice->tranno,
                    'trandate' => $invoice->trandate,
                    'contact_name' => $invoice->contact ? $invoice->contact->contact_name : '',
                    'contactid' => $invoice->contactid,
                ];

                // Dapatkan detail invoice untuk ditampilkan kembali
                // Gunakan join langsung di query
                $sql = "SELECT td.*, v.deskripsi
                    FROM trandetail td
                    LEFT JOIN varian v ON v.varianid = td.varianid
                    WHERE td.tranid = :tranid";

                $invoiceDetails = Yii::$app->db->createCommand($sql)
                    ->bindValue(':tranid', $invoice->tranid)
                    ->queryAll();

                $formattedInvoiceDetails = [];
                foreach ($invoiceDetails as $detail) {
                    $formattedInvoiceDetails[] = [
                        'deskripsi' => $detail['deskripsi'] ?? 'Produk',
                        'itemsubtotal' => $detail['itemsubtotal'],
                        'jumlah' => $detail['jumlah'],
                        'varianid' => $detail['varianid'],
                        'harga' => $detail['harga'] ?? 0
                    ];
                }
            }
        }

        // Build the details data array dengan format yang sesuai dengan yang diharapkan di form
        foreach ($existingDetails as $detail) {
            // Ekstrak invoiceid dan invoiceno dari detail->detail jika ada
            $invoiceNo = '';
            if ($detail->detail && preg_match('/Pembayaran untuk (.*?)$/', $detail->detail, $matches)) {
                $invoiceNo = $matches[1];
            }

            // Ambil data akun untuk ditampilkan di select2
            $akunId = $detail->akunid;
            // var_dump($akunId);

            if (!empty($akunId)) {
                $akun = Coas::findOne($akunId);
            }

            $detailItem = [
                'kasdetailid' => $detail->kasdetailid,
                'cashdetailtype' => $detail->detail,
                'qty' => $detail->jumlah,
                'jumlah' => $detail->jumlah,
                'harga' => $detail->price,
                'amount' => $detail->value,
                'value' => $detail->value,
                'paid' => $detail->status,
                'ord' => 1,
                'iscut' => 0,
                // 'akunid' => $detail->akunid,
                'akun' => $akun, // Tambahkan nama akun
                'invoiceid' => $model->refid,
                'invoiceno' => $invoiceNo,
                'is_manual' => $detail->statuspaid == '1' ? 1 : 0
            ];

            $detailsData[] = $detailItem;
        }

        if ($model->load(Yii::$app->request->post())) {
            // Format tanggal
            if (!empty($model->kasdate)) {
                $parts = explode('-', $model->kasdate);
                if (count($parts) === 3 && strlen($parts[2]) === 4) {
                    // Format from DD-MM-YYYY to YYYY-MM-DD
                    $model->kasdate = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
                }
            }

            // Proses totalpaid dari request
            $cashData = Yii::$app->request->post('Cash');
            if (isset($cashData['totalpaid'])) {
                $model->totalpaid = (float) str_replace('.', '', $cashData['totalpaid']);
            }

            $valid = $model->validate(false);
            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model->save(false)) {
                        // Hapus detail kas yang ada
                        KasDetail::deleteAll(['kasid' => $model->kasid]);

                        // Proses detail dari request
                        $details = Yii::$app->request->post('details');
                        $cashDetails = Yii::$app->request->post('cash_details');

                        // Parse JSON cash_details jika ada
                        $parsedCashDetails = null;
                        if (!empty($cashDetails)) {
                            $parsedCashDetails = json_decode($cashDetails, true);
                        }

                        // Proses details array jika ada
                        if (!empty($details)) {
                            foreach ($details as $detail) {
                                $detailModel = new KasDetail();
                                $detailModel->kasid = $model->kasid;
                                $detailModel->akunid = $detail['akunid'];

                                // Map cashdetailtype ke kolom detail
                                $detailModel->detail = $detail['cashdetailtype'] ?? '';

                                // Handle jumlah dan price/harga
                                $detailModel->jumlah = (int)($detail['jumlah'] ?? $detail['qty'] ?? 1);
                                $detailModel->price = (float)str_replace('.', '', $detail['harga'] ?? 0);

                                // Convert value dari format string (200.000) ke float (200000)
                                $detailModel->value = (float)str_replace('.', '', $detail['value'] ?? 0);

                                // Set statuspaid jika tersedia
                                $detailModel->statuspaid = $detail['is_manual'] ?? '0';

                                // Set default status
                                $detailModel->status = 0;

                                if (!$detailModel->save(false)) {
                                    throw new \Exception('Failed to save kas detail: ' . json_encode($detailModel->errors));
                                }
                            }
                        }
                        // Jika tidak ada array details tetapi ada parsed JSON details
                        elseif (!empty($parsedCashDetails)) {
                            foreach ($parsedCashDetails as $detail) {
                                $detailModel = new KasDetail();
                                $detailModel->kasid = $model->kasid;
                                $detailModel->akunid = $detail['akunid'] ?? null;

                                // Map fields dari struktur JSON
                                $detailModel->detail = $detail['cashdetailtype'] ?? '';
                                $detailModel->jumlah = (int)($detail['qty'] ?? 1);
                                $detailModel->price = (float)($detail['harga'] ?? 0);
                                $detailModel->value = (float)($detail['amount'] ?? 0);
                                $detailModel->statuspaid = $detail['is_manual'] ?? '0';
                                $detailModel->status = $detail['paid'] ?? 0;

                                if (!$detailModel->save(false)) {
                                    throw new \Exception('Failed to save kas detail from JSON: ' . json_encode($detailModel->errors));
                                }
                            }
                        }

                        $transaction->commit();

                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return [
                                'success' => true,
                                'pesan' => 'Data Berhasil Diperbarui',
                                'id' => $model->kasid,
                                'cashno' => $model->kasnomor,
                            ];
                        }

                        return $this->redirect(['index']);
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::error('Kas transaction error: ' . $e->getMessage(), 'application');

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
                        'id' => $model->kasid,
                        'cashno' => $model->kasnomor
                    ];
                }
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('_form', [
                'dialog' => 1,
                'model' => $model,
                'isajax' => "true",
                'detailsData' => $detailsData,
                'invoiceData' => $invoiceData,
                'invoiceDetails' => $formattedInvoiceDetails ?? []
            ]);
        } else {
            return $this->render('_form', [
                'dialog' => 1,
                'model' => $model,
                'isajax' => "false",
                'detailsData' => $detailsData,
                'invoiceData' => $invoiceData,
                'invoiceDetails' => $formattedInvoiceDetails ?? []
            ]);
        }
    }

    // public function actionMassaction()
    // {
    //     Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    //     // Get the post data
    //     $ids = Yii::$app->request->post('ids');
    //     $action = Yii::$app->request->post('action');
    //     $status = Yii::$app->request->post('status');

    //     // Validate input
    //     if (empty($ids) || !is_array($ids)) {
    //         return [
    //             'success' => false,
    //             'message' => 'Tidak ada data yang dipilih!'
    //         ];
    //     }

    //     // Validate action and status
    //     $validActions = ['delete', 'approve', 'cancel'];
    //     $validStatuses = [1, 5, 10]; // 1=Approved, 5=Cancelled/Problem, 10=Rejected/Unpaid

    //     if (!in_array($action, $validActions) || !in_array($status, $validStatuses)) {
    //         return [
    //             'success' => false,
    //             'message' => 'Aksi atau status tidak valid!'
    //         ];
    //     }

    //     try {
    //         // Special handling for delete action
    //         // if ($action === 'delete') {
    //         //     // Convert array to format string for SQL
    //         //     $idString = implode(',', array_map(fn($id) => "'" . addslashes($id) . "'", $ids));

    //         //     // Delete records
    //         //     $sql = "DELETE FROM kas WHERE kasid IN ($idString)";
    //         //     $rowsAffected = Yii::$app->db->createCommand($sql)->execute();
    //         // } else {
    //             // For approve and cancel actions, update status
    //             // Convert array to format string for SQL
    //             $idString = implode(',', array_map(fn($id) => "'" . addslashes($id) . "'", $ids));

    //             // Create the SQL query
    //             $sql = "UPDATE kas SET status = :status,
    //                 tgledit = NOW(),
    //                 opedit = :user_id,
    //                 pcedit = :user_ip
    //                 WHERE kasid IN ($idString)";

    //             // Execute the SQL query
    //             $rowsAffected = Yii::$app->db->createCommand($sql, [
    //                 ':status' => $status,
    //                 ':user_id' => Yii::$app->user->id,
    //                 ':user_ip' => Yii::$app->request->userIP
    //             ])->execute();
    //         // }

    //         // Get action text for response message
    //         $actionText = '';
    //         switch ($action) {
    //             case 'delete':
    //                 $actionText = 'dihapus';
    //                 break;
    //             case 'approve':
    //                 $actionText = 'diapprove';
    //                 break;
    //             case 'cancel':
    //                 $actionText = 'dibatalkan';
    //                 break;
    //         }

    //         // Return success response
    //         return [
    //             'success' => true,
    //             'message' => "Data berhasil $actionText!",
    //             'rows_affected' => $rowsAffected,
    //             'action' => $action,
    //             'status' => $status
    //         ];
    //     } catch (\Exception $e) {
    //         // Log the error
    //         Yii::error("Error in mass action: " . $e->getMessage());

    //         // Return error response
    //         return [
    //             'success' => false,
    //             'message' => "Terjadi kesalahan: " . $e->getMessage(),
    //             'error' => true
    //         ];
    //     }
    // }

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
        $validStatuses = [1, 5, 10]; // 1=Approved, 5=Cancelled/Problem, 10=Rejected/Unpaid

        if (!in_array($action, $validActions) || !in_array($status, $validStatuses)) {
            return [
                'success' => false,
                'message' => 'Aksi atau status tidak valid!'
            ];
        }

        try {
            // Convert array of IDs to string for SQL
            $idString = implode(',', array_map(fn($id) => "'" . addslashes($id) . "'", $ids));

            // Fetch current statuses of the selected records
            $currentStatuses = Yii::$app->db->createCommand("SELECT kasid, status FROM kas WHERE kasid IN ($idString)")
                ->queryAll();

            // Prepare condition to only update records that can actually be changed
            $updateCondition = [];
            $updateData = [
                ':status' => $status,
                ':user_id' => Yii::$app->user->id,
                ':user_ip' => Yii::$app->request->userIP
            ];

            foreach ($currentStatuses as $row) {
                // If status is 10 (Rejected), we ignore the update for this record
                if ($row['status'] == 10) {
                    // Don't update if it's rejected
                    continue;
                }
                // Otherwise, add it to the update condition
                $updateCondition[] = "kasid = '{$row['kasid']}'";
            }

            if (empty($updateCondition)) {
                return [
                    'success' => false,
                    'message' => 'Tidak ada data yang dapat diubah karena sudah berstatus Rejected!'
                ];
            }

            // Join the conditions for updating
            $updateConditionString = implode(' OR ', $updateCondition);

            // Create and execute the update SQL query
            $sql = "UPDATE kas SET status = :status,
                tgledit = NOW(),
                opedit = :user_id,
                pcedit = :user_ip
                WHERE ($updateConditionString)";

            $rowsAffected = Yii::$app->db->createCommand($sql, $updateData)->execute();

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
     * Deletes an existing Kas model.
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

            // Soft delete - set status instead of deleting
            $model->status = 10;

            if ($model->save(false)) {
                $transaction->commit();
                return $this->asJson(['success' => true, 'message' => 'Data berhasil dihapus id: ' . $id]);
            } else {
                $transaction->rollBack();
                return $this->asJson(['success' => false, 'message' => 'Gagal menghapus']);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->asJson(['success' => false, 'message' => $e->getMessage()]);
        }
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

        // Validate parameters
        if (empty($id) || $status === null) {
            return ['success' => false, 'message' => 'Parameter tidak lengkap'];
        }

        // Update status with direct SQL to avoid validation issues
        try {
            $rows = Yii::$app->db->createCommand("
                UPDATE kas
                SET status = :status,
                    tgledit = NOW(),
                    opedit = :opedit,
                    pcedit = :pcedit
                WHERE kasid = :id
            ")->bindValues([
                ':status' => $status,
                ':opedit' => Yii::$app->user->id,
                ':pcedit' => Yii::$app->request->userIP,
                ':id' => $id
            ])->execute();

            if ($rows === 0) {
                return ['success' => false, 'message' => 'Data tidak ditemukan atau tidak ada perubahan'];
            }

            if ($status == 1) {
                // Ambil tranid dari kas
                $tranid = Yii::$app->db->createCommand("
                    SELECT refid FROM kas WHERE kasid = :id
                ")->bindValue(':id', $id)->queryScalar();

                if ($tranid) {
                    Yii::$app->db->createCommand("
                        UPDATE tran
                        SET statuspaid = 'paid'
                        WHERE tranid = :tranid
                    ")->bindValue(':tranid', $tranid)->execute();
                }
            }

            // Prepare status labels for response
            $statusLabels = [
                0 => 'Waiting',
                1 => 'Approved',
                5 => 'Cancelled',
                10 => 'Rejected'
            ];

            $statusText = isset($statusLabels[$status]) ? $statusLabels[$status] : 'Unknown';

            return [
                'success' => true,
                'message' => 'Status berhasil diperbarui',
                'status' => $status,
                'statusText' => $statusText
            ];
        } catch (\Exception $e) {
            Yii::error("Error updating status: " . $e->getMessage());
            return ['success' => false, 'message' => 'Gagal menyimpan perubahan status: ' . $e->getMessage()];
        }
    }

    /**
     * Finds the Kas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Kas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Kas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionList()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = Yii::$app->user->id;
        $params = Yii::$app->request->queryParams; // Get filter parameters

        // Filter parameters for kas transactions
        $contact = $params['contact'] ?? '';
        $search = $params['search'] ?? '';
        $date = $params['datefilter'] ?? '';

        // Get sorting data from DataTables
        $sortcolumn = $params['order'][0]['column'] ?? 0;
        $ordercolumn = $params['columns'][$sortcolumn]['data'] ?? 'kasdate';
        $columnorder = $params['order'][0]['dir'] ?? 'DESC';

        // Validate sorting to prevent SQL Injection
        $allowedColumns = ['kasnomor', 'kasdate', 'contact_name', 'total'];
        if (!in_array($ordercolumn, $allowedColumns)) {
            $ordercolumn = 'kasdate'; // Default sorting
        }

        // Map column names to actual database columns if necessary
        if ($ordercolumn == 'total') {
            $ordercolumn = 'total_value'; // Use the calculated column name
        }

        // Query to get kas data
        $query = "SELECT
                    k.kasid,
                    k.kasnomor,
                    k.status,
                    k.kasdate,
                    k.jenis,
                    c.contact_name,
					t.contactid,
                    t.tranno,
                    COALESCE((SELECT SUM(kd.value) FROM kasdetail kd WHERE kd.kasid = k.kasid), 0) AS total_value,
                    COALESCE((SELECT SUM(kd.value) FROM kasdetail kd WHERE kd.kasid = k.kasid), 0) AS sisa_pembayaran
                FROM kas k
				LEFT JOIN tran t ON t.tranid = k.refid
				LEFT JOIN contact c ON c.contact_id = t.contactid
                WHERE k.opadd = :user_id
                  ";

        // Build filter conditions
        $filter = "";

        if (isset($contact) && $contact !== '') {
            $filter .= " AND k.refid = :contact";
        }

        if (isset($search) && $search !== "") {
            $filter .= " AND (
                k.kasnomor ILIKE :search
                OR c.contact_name ILIKE :search
            )";
        }

        if ($date != "") {
            $dates = explode(" - ", $date);
            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));
            $filter .= " AND DATE(k.kasdate) BETWEEN :startDate AND :endDate";
        }

        // Final query with ORDER BY
        $query .= $filter . " ORDER BY " . $ordercolumn . " " . $columnorder;

        // Prepare the command with parameters
        $command = Yii::$app->db->createCommand($query);
        $command->bindValue(':user_id', $id);

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

        // Format date and add display information for output JSON
        foreach ($data as &$row) {
            if (isset($row['kasdate'])) {
                $row['kasdate_display'] = Yii::$app->formatter->asDate($row['kasdate'], 'php:d-m-Y');
            }

            // Determine if this is income (KM) or expense (KK) based on jenis
            $row['cashno'] = $row['kasnomor']; // Map to the expected column name in the frontend
            $row['cashdate'] = $row['kasdate']; // Map to the expected column name in the frontend
            $row['total'] = $row['total_value']; // Map to the expected column name in the frontend
            $row['subtotal'] = $row['total_value']; // Map to the expected column name
            $row['cashid'] = $row['kasid']; // Map to the expected column name in the frontend
        }

        return [
            'data' => $data ?: [],
        ];
    }

    public function actionInvoicelist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $search = Yii::$app->request->get('q', '');

        $kastype = Yii::$app->request->get('kastype') == 'KM' ? 'KM' : 'KK'; // KM for income, KK for expense
        if ($kastype == 'KM') {
            $trantype = 'sales/invoice';
        } else {
            $trantype = 'purchase/invoice';
        }
        // var_dump($kastype);die;
        $session = Yii::$app->session;
        $companyid = $session->get('companyid');

        if (!$companyid) {
            // If companyid not found in session, get from users
            $userId = Yii::$app->user->id;
            $companyid = Yii::$app->db->createCommand("SELECT companyid FROM users WHERE userid = :userid")
                ->bindValue(':userid', $userId)
                ->queryScalar();
        }

        // Simplified query without details
        $query = "SELECT
                t.tranid,
                t.tranno,
                t.trandate,
                t.contactid,
                c.contact_name,
                t.trantype,
                t.status,
                t.statuspaid,
                (SELECT COALESCE(SUM(td.itemsubtotal), 0) FROM trandetail td WHERE td.tranid = t.tranid) as total
            FROM tran t
            LEFT JOIN contact c ON c.contact_id = t.contactid
            WHERE (t.trantype ILIKE '%$trantype%' OR t.trantype LIKE '%/faktur')
            AND t.companyid = :companyid
            AND t.status = '0'
            AND t.statuspaid is null
            ";

        // Add search filter if provided
        if (!empty($search)) {
            $query .= " AND (
                t.tranno ILIKE :search
                OR c.contact_name ILIKE :search
            )";
        }

        // Add ordering
        $query .= " ORDER BY t.trandate DESC, t.tranno DESC";

        // Create command
        $command = Yii::$app->db->createCommand($query);

        // Bind parameters
        $command->bindValue(':companyid', $companyid);
        if (!empty($search)) {
            $command->bindValue(':search', '%' . $search . '%');
        }

        // Execute query
        $data = $command->queryAll();

        // Format date and totals for output
        foreach ($data as &$row) {
            if (isset($row['trandate'])) {
                $row['trandate_display'] = Yii::$app->formatter->asDate($row['trandate'], 'php:d-m-Y');
            }

            // Format total
            if (isset($row['total'])) {
                $row['total_display'] = Yii::$app->formatter->asDecimal($row['total'], 0);
            }
        }

        return [
            'data' => $data
        ];
    }

    public function actionInvoicedetail($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Get transaction details directly from the database
        $details = Yii::$app->db->createCommand("
            SELECT
                td.trandetailid,
                td.varianid,
                td.jumlah,
                td.harga,
                td.itemsubtotal,
                v.deskripsi,
                v.sku,
                p.namaproduk
            FROM trandetail td
            LEFT JOIN varian v ON v.varianid = td.varianid
            LEFT JOIN produk p ON p.produkid = v.produkid::uuid
            WHERE td.tranid = :tranid
        ")
            ->bindValue(':tranid', $id)
            ->queryAll();

        // Return only the details array
        return [
            'details' => $details
        ];
    }
    public function actionAkunlist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $search = Yii::$app->request->get('q', '');

        $session = Yii::$app->session;
        $companyid = $session->get('companyid');

        if (!$companyid) {
            // If companyid not found in session, get from users
            $userId = Yii::$app->user->id;
            $companyid = Yii::$app->db->createCommand("SELECT companyid FROM users WHERE userid = :userid")
                ->bindValue(':userid', $userId)
                ->queryScalar();
        }

        // Base query for coas
        $query = "SELECT
                    coa_id,
                    coa_no,
                    coa_name_id,
                    coa_type
                FROM coas

                WHERE coa_status = 1 AND coa_level <> 1 AND coa_companyid = :companyid";

        // Add search filter if provided
        if (!empty($search)) {
            $query .= " AND (
                    coa_name_id ILIKE :search
                    OR coa_no ILIKE :search
                )";
        }

        // Add order by
        $query .= " ORDER BY coa_no ASC";

        // Create command
        $command = Yii::$app->db->createCommand($query);

        // Bind parameters
        $command->bindValue(':companyid', $companyid);
        if (!empty($search)) {
            $command->bindValue(':search', '%' . $search . '%');
        }

        // Execute query
        $data = $command->queryAll();

        // Format data for Select2
        // $formattedData = [];
        // foreach ($data as $row) {
        //     $formattedData[] = [
        //         'id' => $row['coa_id'],
        //         'text' => $row['coa_no'] . ' - ' . $row['coa_name_id'],
        //         'coa_type' => $row['coa_type']
        //     ];
        // }
        // var_dump($data);die;
        return [
            'data' => $data
        ];
    }

    public function actionContactlist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = Yii::$app->user->id;
        $params = Yii::$app->request->queryParams; // Ambil parameter filter
        $Gender = $params['gender'] ?? '';
        $Married = $params['married'] ?? '';
        $Religion = $params['reli'] ?? '';
        $search = $params['search'] ?? '';
        $date = $params['datefilter'] ?? '';

        // Ambil data sorting dari DataTables
        $sortcolumn = $params['order'][0]['column'] ?? 0;
        $ordercolumn = $params['columns'][$sortcolumn]['data'] ?? 'contact_no';
        $columnorder = $params['order'][0]['dir'] ?? 'DESC';

        // Validasi sorting biar aman dari SQL Injection
        $allowedColumns = ['contact_no', 'contact_name', 'contact_phone1', 'married'];
        if (!in_array($ordercolumn, $allowedColumns)) {
            $ordercolumn = 'contact_no'; // Default sorting
        }

        $query = "SELECT
        c.*,
        g.enumtext_en AS gender,
        r.enumtext_en AS religion,
        m.enumtext_en AS married
        FROM contact c
        LEFT JOIN company co ON c.companyid = co.companyid
        LEFT JOIN enum g ON g.enumid = c.contact_gender AND g.enumtype = 'gender'
        LEFT JOIN enum r ON r.enumid = c.contact_religion AND r.enumtype = 'religion'
        LEFT JOIN enum m ON m.enumid = c.contact_married AND m.enumtype = 'married'
      WHERE co.userid = '$id' AND c.contact_status = '1'";

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

        $data = Yii::$app->db->createCommand($query)->queryAll();
        return ['data' => $data ?: []]; // Format untuk datatable
    }

    public function actionGetno()
    {
        try {
            $nextKasNo = (new Kas())->nextNoKas();
        } catch (Exception $e) {
            $nextKasNo = "00001";
        }

        return $this->asJson([
            "no" => $nextKasNo
        ]);
    }
}
