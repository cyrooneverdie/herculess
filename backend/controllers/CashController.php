<?php

namespace backend\controllers;

use common\models\Coas;
use yii\web\Response;
use Yii;
use yii\web\Controller;
use common\models\Cashs;
use common\models\Tran;
use common\models\Contact;
use common\models\TranDetail;
use common\models\CashDetails;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\db\Query;


class CashController extends Controller
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
                    'delete' => ['POST']
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
        $searchModel = new Cashs();

        $lastKas = Yii::$app->db->createCommand("
        SELECT cashnumber FROM cashs WHERE status = 1 ORDER BY cashdate DESC LIMIT 1")->queryOne();

        $transactions = Yii::$app->db->createCommand("
            SELECT
                k.cashid,
                k.refid,
                k.cashdate,
                k.cashnumber,
                k.status,
                c.contact_name AS contact_name
            FROM cashs k
            LEFT JOIN contacts c ON c.contact_id = k.refid
            ORDER BY k.cashdate DESC
        ")->queryAll();

        $title = "Daftar Transaksi Kas";

        return $this->render('index', [
            'model' => $lastKas,
            'transactions' => $transactions,
            'searchModel' => $searchModel,
            'title' => $title,
        ]);
    }

    public function actionCreate()
    {
        $model = new Cashs();
        $modeldetails = [];

        $model->cashnumber = $model->nextNoKas();
        $model->cashdate = date("d/m/Y");

        $language = Yii::$app->language;
        $defaultAkun = Yii::$app->db->createCommand(
            "SELECT coa_id, coa_no, coa_name_id, coa_name_en
         FROM coas
         WHERE coa_status = 1 AND coa_level <> 1
           AND coa_category IN (2, 12)
         ORDER BY coa_no ASC LIMIT 1"
        )->queryOne();

        if ($defaultAkun) {
            $defaultAkun['text'] = $defaultAkun['coa_no'] . ' - ' .
                ($language === 'en' ? $defaultAkun['coa_name_en'] : $defaultAkun['coa_name_id']);
        }

        $defaultAkun2 = Yii::$app->db->createCommand(
            "SELECT coa_id, coa_no, coa_name_id, coa_name_en
         FROM coas
         WHERE coa_status = 1
           AND coa_level = 2 AND coa_category = 1
         ORDER BY coa_no DESC LIMIT 1"
        )->queryOne();

        $selectedAkun = [];
        if ($defaultAkun2) {
            $model->accountid = $defaultAkun2['coa_id'];
            $selectedAkun = [
                'id' => $defaultAkun2['coa_id'],
                'text' => $defaultAkun2['coa_no'] . ' - ' . ($language === 'en' ? $defaultAkun2['coa_name_en'] : $defaultAkun2['coa_name_id'])
            ];
        }

        $tranid = Yii::$app->request->get('id');
        if (!empty($tranid)) {
            $model->refid = $tranid;

            $invoiceData = $this->actionInvoicedetail($tranid);
            if (!isset($invoiceData['error'])) {
                $modeldetails = $invoiceData['details'];

            }
        }

        if ($model->load(Yii::$app->request->post())) {
            // var_dump($model->attributes);die;

            if (!empty($model->cashdate)) {
                $parts = explode('/', $model->cashdate);
                if (count($parts) === 3 && strlen($parts[2]) === 4) {
                    $model->cashdate = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
                }
            }

            $model->type = Yii::$app->request->get('type', 1); // 0=pengeluaran, 1=pemasukan
            $model->source = 0;
            $model->status = 1;

            $cashData = Yii::$app->request->post('Cash');
            if (isset($cashData['totalpaid'])) {
                $model->totalpaid = (float) str_replace('.', '', $cashData['totalpaid']);
            }

            if ($model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model->save(false)) {

                        $detailsArray = Yii::$app->request->post('Trandetail');

                        if (!empty($detailsArray)) {
                            foreach ($detailsArray as $index => $detail) {
                                // var_dump($detail); // Debug: Tampilkan isi $detail
                                if (empty($detail['accountid']) && empty($detail['price']))
                                    continue;

                                $detailModel = new CashDetails();
                                $detailModel->cashid = $model->cashid;

                                $detailModel->accountid = $detail['accountid'] ?? $detail['akunid'] ?? 'd32b6683-de72-4188-81e4-7eaf183b1d1a';
                                $detailModel->detail = $detail['cashdetailtype'] ?? '';

                                $price = (float) preg_replace('/[^0-9.]/', '', $detail['price']);
                                $qty = (float) ($detail['amount'] ?? 0);

                                $detailModel->amount = $qty;
                                $detailModel->price = $price;
                                $detailModel->value = $price;

                                $detailModel->statuspaid = $detail['is_manual'] ?? 0;
                                $detailModel->status = $detail['paid'] ?? 0;

                                if (!$detailModel->save(false)) {
                                    throw new \Exception('Gagal menyimpan detail ke-' . ($index + 1));
                                }
                            }
                        }

                        if (!empty($model->refid)) {
                            $purchaseTransaction = Tran::findOne($model->refid);
                            if ($purchaseTransaction) {
                                $purchaseTransaction->status = 1;     // Approved
                                $purchaseTransaction->statuspaid = 1; // Paid
                                $purchaseTransaction->save(false);
                            }
                        }

                        $transaction->commit();

                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return [
                                'success' => true,
                                'pesan' => 'Data Berhasil Disimpan',
                                'id' => $model->cashid,
                                'number' => $model->cashnumber,
                            ];
                        }
                        return $this->redirect(['index']);
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['success' => false, 'pesan' => $e->getMessage()];
                    }
                    throw $e;
                }
            }
        }

        $renderMethod = Yii::$app->request->isAjax ? 'renderAjax' : 'render';
        return $this->$renderMethod('_form', [
            'model' => $model,
            'modeldetails' => $modeldetails,
            'defaultAkun' => $defaultAkun ?? null,
            'selected' => $selectedAkun ?? null,
            'dialog' => 1,
            'isajax' => Yii::$app->request->isAjax ? "true" : "false",
        ]);
    }

    public function actionInvoicedetail($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $invoice = Tran::findOne($id);
        // var_dump($id);die;

        if (!$invoice) {
            return ['error' => 'Invoice not found.'];
        }

        $details =
            "SELECT t.tranno, t.tranid, t.grandtotal,
            paid.value,
            (COALESCE(t.grandtotal, 0) - COALESCE(paid.value, 0)) AS remaining_price, 
            STRING_AGG(p.productname, '- ') AS productnames
        FROM trans t
        LEFT JOIN trandetails td ON t.tranid = td.tranid
        LEFT JOIN products p ON p.productid = td.productid
        LEFT JOIN (
        SELECT SUM(COALESCE(cad.amount, 0)) AS total_amount, c.refid,
            SUM(COALESCE(cad.value, 0)) AS value
        FROM cashdetails cad
        LEFT JOIN cashs c  ON c.cashid = cad.cashid
        WHERE c.status = 1
            AND c.refid = '$id'
            GROUP BY c.refid
        ) AS paid ON paid.refid = td.tranid

        WHERE td.tranid = '$id'
            AND td.status <> '10' AND t.status <> '10'

        GROUP BY t.note, t.tranid, paid.total_amount, paid.value
        ";
        $detailsData = Yii::$app->db->createCommand($details)->queryAll();

        // echo($details);die;

        return [
            'invoiceno' => $invoice->tranno,
            'contact_id' => $invoice->contact_id,
            'details' => $detailsData,
        ];
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
        $existingDetails = CashDetails::find()->where(['cashid' => $id])->all();
        $detailsData = [];

        if ($model->totalpaid !== null) {
            $model->totalpaid = number_format($model->totalpaid, 0, '', '.');
        }

        $invoiceData = null;
        if (!empty($model->refid)) {
            $invoice = Tran::findOne($model->refid);
            if ($invoice) {
                $invoiceData = [
                    'tranid' => $invoice->tranid,
                    'tranno' => $invoice->tranno,
                    'trandate' => $invoice->trandate,
                    'contact_name' => $invoice->contact ? $invoice->contact->contact_name : '',
                    'contact_id' => $invoice->contact_id,
                ];

                $sql = "SELECT td.*, v.description
                    FROM trandetails td
                    LEFT JOIN variants v ON v.variantid = td.variantid
                    WHERE td.tranid = :tranid";

                $invoiceDetails = Yii::$app->db->createCommand($sql)
                    ->bindValue(':tranid', $invoice->tranid)
                    ->queryAll();

                $formattedInvoiceDetails = [];
                foreach ($invoiceDetails as $detail) {
                    $formattedInvoiceDetails[] = [
                        'description' => $detail['description'] ?? 'Products',
                        'itemsubtotal' => $detail['itemsubtotal'],
                        'amount' => $detail['amount'],
                        'variantid' => $detail['variantid'],
                        'harga' => $detail['harga'] ?? 0
                    ];
                }
            }
        }

        foreach ($existingDetails as $detail) {
            $invoiceNo = '';
            if ($detail->detail && preg_match('/Pembayaran untuk (.*?)$/', $detail->detail, $matches)) {
                $invoiceNo = $matches[1];
            }

            $accountId = $detail->accountid;
            // var_dump($akunId);

            if (!empty($accountId)) {
                $akun = Coas::findOne($accountId);
            }
            // var_dump($akun);exit;

            $detailItem = [
                'cashdetailid' => $detail->cashdetailid,
                'cashdetailtype' => $detail->detail,
                'qty' => $detail->amount,
                // 'amount' => $detail->amount,
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
            if (!empty($model->cashdate)) {
                $parts = explode('-', $model->cashdate);
                if (count($parts) === 3 && strlen($parts[2]) === 4) {
                    // Format from DD-MM-YYYY to YYYY-MM-DD
                    $model->cashdate = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
                }
            }

            $cashData = Yii::$app->request->post('Cash');
            if (isset($cashData['totalpaid'])) {
                $model->totalpaid = (float) str_replace('.', '', $cashData['totalpaid']);
            }

            $valid = $model->validate(false);
            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model->save(false)) {
                        CashDetails::deleteAll(['cashid' => $model->cashid]);

                        $details = Yii::$app->request->post('details');
                        $cashDetails = Yii::$app->request->post('cash_details');

                        $parsedCashDetails = null;
                        if (!empty($cashDetails)) {
                            $parsedCashDetails = json_decode($cashDetails, true);
                        }

                        if (!empty($details)) {
                            foreach ($details as $detail) {
                                $detailModel = new CashDetails();
                                $detailModel->cashid = $model->cashid;
                                $detailModel->accountid = $detail['accountid'];

                                $detailModel->detail = $detail['cashdetailtype'] ?? '';

                                $detailModel->amount = (int) ($detail['amount'] ?? $detail['qty'] ?? 1);
                                $detailModel->price = (float) str_replace('.', '', $detail['harga'] ?? 0);

                                $detailModel->value = (float) str_replace('.', '', $detail['value'] ?? 0);

                                $detailModel->statuspaid = $detail['is_manual'] ?? '0';

                                $detailModel->status = 0;

                                if (!$detailModel->save(false)) {
                                    throw new \Exception('Failed to save kas detail: ' . json_encode($detailModel->errors));
                                }
                            }
                        } elseif (!empty($parsedCashDetails)) {
                            foreach ($parsedCashDetails as $detail) {
                                $detailModel = new CashDetails();
                                $detailModel->cashid = $model->cashid;
                                $detailModel->accountid = $detail['accountid'] ?? null;

                                $detailModel->detail = $detail['cashdetailtype'] ?? '';
                                $detailModel->amount = (int) ($detail['qty'] ?? 1);
                                $detailModel->price = (float) ($detail['price'] ?? 0);
                                $detailModel->value = (float) ($detail['amount'] ?? 0);
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
                                'id' => $model->cashid,
                                'cashnumber' => $model->cashnumber,
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
                        'id' => $model->cashid,
                        'cashno' => $model->cashnumber
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
            $idString = implode(',', array_map(function ($id) {
                return "'" . addslashes($id) . "'";
            }, $ids));


            // Fetch current statuses of the selected records
            $currentStatuses = Yii::$app->db->createCommand("SELECT cashid, status FROM cashs WHERE cashid IN ($idString)")
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
                $updateCondition[] = "cashid = '{$row['cashid']}'";
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
            $sql = "UPDATE cashs SET status = :status,
                dateedit = NOW(),
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

    public function actionDetail($id)
    {
        // var_dump($model, $details, $total);
        // exit;
        $model = Tran::findOne($id);
        if (!$model) {
            return $this->renderAjax('index', ['message' => 'Transaksi tidak ditemukan']);
        }
        $details = trandetails::find()
            ->select([
                'trandetails.*',
                'products.productcode',
                'products.type',
                'products.productname',
                'products.product_description',
                'contacts.contact_name'
            ])
            ->joinWith(['produk', 'tran.tranno'])
            ->where(['trandetails.tranid' => $id])
            ->asArray()
            ->all();
        $total = array_sum(array_column($details, 'itemsubtotal'));

        return $this->renderAjax('_detail', [
            'model' => $model,
            'details' => $details,
            'total' => $total,
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

        // Validate parameters
        if (empty($id) || $status === null) {
            return ['success' => false, 'message' => 'Parameter tidak lengkap'];
        }

        // Update status with direct SQL to avoid validation issues
        try {
            $rows = Yii::$app->db->createCommand("
                UPDATE cashs
                SET status = :status,
                    dateedit = NOW(),
                    opedit = :opedit,
                    pcedit = :pcedit
                WHERE cashid = :id
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
                    SELECT refid FROM cashs WHERE cashid = :id
                ")->bindValue(':id', $id)->queryScalar();

                if ($tranid) {
                    Yii::$app->db->createCommand("
                        UPDATE trans
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
     * @return Cashs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cashs::findOne($id)) !== null) {
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
        $ordercolumn = $params['columns'][$sortcolumn]['data'] ?? 'cashdate';
        $columnorder = $params['order'][0]['dir'] ?? 'DESC';

        // Validate sorting to prevent SQL Injection
        $allowedColumns = ['cashnumber', 'cashdate', 'contact_name', 'total'];
        if (!in_array($ordercolumn, $allowedColumns)) {
            $ordercolumn = 'cashdate'; // Default sorting
        }

        // Map column names to actual database columns if necessary
        if ($ordercolumn == 'total') {
            $ordercolumn = 'total_value'; // Use the calculated column name
        }

        // Query to get cashs data
        $query = "SELECT
                    k.cashid,
                    k.cashnumber,
                    k.status,
                    k.cashdate,
                    k.type,
                    c.contact_name,
					t.contact_id,
                    t.tranno,
                    COALESCE((SELECT SUM(kd.value) FROM cashdetails kd WHERE kd.cashid = k.cashid), 0) AS total_value,
                    COALESCE((SELECT SUM(kd.value) FROM cashdetails kd WHERE kd.cashid = k.cashid), 0) AS sisa_pembayaran
                FROM cashs k
				LEFT JOIN trans t ON t.tranid = k.refid
				LEFT JOIN contacts c ON c.contact_id = t.contact_id
                WHERE k.opadd = :user_id
                  ";

        // Build filter conditions
        $filter = "";

        if (isset($contact) && $contact !== '') {
            $filter .= " AND t.contact_id = :contact";
        }

        if (isset($search) && $search !== "") {
            $filter .= " AND (
                k.cashnumber ILIKE :search
                OR c.contact_name ILIKE :search
            )";
        }

        if ($date != "") {
            $dates = explode(" - ", $date);
            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));
            $filter .= " AND DATE(k.cashdate) BETWEEN :startDate AND :endDate";
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
            if (isset($row['cashdate'])) {
                $row['cashdate_display'] = Yii::$app->formatter->asDate($row['cashdate'], 'php:d-m-Y');
            }

            // Determine if this is income (KM) or expense (KK) based on jenis
            $row['cashnumber'] = $row['cashnumber']; // Map to the expected column name in the frontend
            $row['cashdate'] = $row['cashdate']; // Map to the expected column name in the frontend
            $row['total'] = $row['total_value']; // Map to the expected column name in the frontend
            $row['subtotal'] = $row['total_value']; // Map to the expected column name
            $row['cashid'] = $row['cashid']; // Map to the expected column name in the frontend
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

        $query = "SELECT
                t.tranid,
                t.tranno,
                t.trandate,
                t.contact_id,
                c.contact_name,
                t.trantype,
                t.status,
                t.statuspaid,
                (SELECT COALESCE(SUM(td.itemsubtotal), 0) FROM trandetails td WHERE td.tranid = t.tranid) as total
            FROM trans t
            LEFT JOIN contacts c ON c.contact_id = t.contact_id
            WHERE (t.trantype ILIKE '%$trantype%' OR t.trantype LIKE '%/faktur')
            AND t.companyid = :companyid
            AND t.status = '0'
            AND t.statuspaid is null
            ";

        if (!empty($search)) {
            $query .= " AND (
                t.tranno ILIKE :search
                OR c.contact_name ILIKE :search
            )";
        }

        $query .= " ORDER BY t.trandate DESC, t.tranno DESC";
        $command = Yii::$app->db->createCommand($query);
        $command->bindValue(':companyid', $companyid);
        if (!empty($search)) {
            $command->bindValue(':search', '%' . $search . '%');
        }

        $data = $command->queryAll();

        foreach ($data as &$row) {
            if (isset($row['trandate'])) {
                $row['trandate_display'] = Yii::$app->formatter->asDate($row['trandate'], 'php:d-m-Y');
            }

            if (isset($row['total'])) {
                $row['total_display'] = Yii::$app->formatter->asDecimal($row['total'], 0);
            }
        }

        return [
            'data' => $data
        ];
    }


    public function actionAkunlist()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $search = Yii::$app->request->get('q', '');
        $type = Yii::$app->request->get('type', '');
        // $id = Yii::$app->request->get('id', null);

        $query = "SELECT DISTINCT ON (coa_no)
            coa_id,
            coa_no,
            coa_name_id,
            coa_name_en,
            coa_type
        FROM coas
        WHERE coa_status = 1 ";

        if ($type == '2') {
            $query .= " AND coa_category IN (2, 12) ";
        } else if ($type == '1') {
            $query .= " AND coa_category = 1 AND coa_level = 2 ";
        }

        if (!empty($search)) {
            $query .= " AND (
                coa_name_id ILIKE '%$search%'
                OR coa_name_en ILIKE '%$search%'
                OR coa_no ILIKE '%$search%'
            )";
        }

        $query .= " GROUP BY coa_no, coa_id, coa_name_id, coa_name_en, coa_type";
        $query .= " ORDER BY coa_no, coa_id ASC";
        // echo $query; die;

        $command = Yii::$app->db->createCommand($query);

        if (!empty($search)) {
            $command->bindValue(':search', '%' . $search . '%');
        }

        $data = $command->queryAll();

        $language = Yii::$app->language;
        foreach ($data as &$row) {
            $row['text'] = $row['coa_no'] . ' - ' . ($language === 'en' ? $row['coa_name_en'] : $row['coa_name_id']);
        }
        unset($row);

        // var_dump($data);die;
        return [
            'data' => array_values($data)
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
        FROM contacts c
        LEFT JOIN companys co ON c.companyid = co.companyid
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
            $nextKasNo = (new Cashs())->nextNoKas();
        } catch (Exception $e) {
            $nextKasNo = "00001";
        }

        return $this->asJson([
            "no" => $nextKasNo
        ]);
    }
}
