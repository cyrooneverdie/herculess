<?php

namespace backend\controllers;

use Yii;
use Exception;
use common\models\Product;
use common\models\Model;
use common\models\Trandetail;
use common\models\Document;
use common\models\Variant;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\helpers\ArrayHelper;


class VariantController extends Controller
{

    public function init()
    {
        parent::init();
        Yii::$app->language = Yii::$app->lang->getLang();
    }

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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'list', 'barcode', 'deletemassal', 'price', 'detail', 'select', 'variantdeli'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionDetail($id)
    {
        $model = $this->findVariant($id);

        if (!$model) {
            Yii::$app->session->setFlash('error', 'Variant not found');
            return $this->redirect(['index']);
        }

        $enum = [];

        if ($model->productid) {
            $product = Product::find()
                ->andWhere(['CAST(productid AS TEXT)' => $model->productid])
                ->one();
            $enum['productname'] = $product ? $product->productname : null;
        }

        $sql =
            "SELECT 
                c.enumtext_id as condition,
                w.enumtext_id as warehouse,
                s.enumtext_id as shelf,
                s.refid as warehouseid
            FROM variants v
            LEFT JOIN enum c ON c.enumid = v.condition AND c.enumtype = 'condition'
            LEFT JOIN enum s ON s.enumid = v.shelf AND s.enumtype = 'shelf'
            LEFT JOIN enum w ON w.enumid = s.refid AND w.enumtype = 'warehouse'
            WHERE v.variantid = '$id'
        ";

        $enumData = Yii::$app->db->createCommand($sql)->queryOne();

        if ($enumData) {
            $enum['condition'] = $enumData['condition'];
            $enum['warehouse'] = $enumData['warehouse'];
            $enum['shelf'] = $enumData['shelf'];
            $enum['warehouseid'] = $enumData['warehouseid'];
        }

        if ($model->contact_id) {
            $enum['contact'] = Yii::$app->function->findByField(
                "contact_name",
                "contacts",
                " and contact_id = '" . $model->contact_id . "' "
            );
        }

        if (isset($model->created_by) && $model->created_by) {
            $enum['created_by'] = Yii::$app->function->findByField(
                "username",
                "user",
                " and id = '" . $model->created_by . "' "
            );
        }

        if (isset($model->updated_by) && $model->updated_by) {
            $enum['updated_by'] = Yii::$app->function->findByField(
                "username",
                "user",
                " and id = '" . $model->updated_by . "' "
            );
        }

        $imagesSql =
            "SELECT 
                d.documentid,
                d.documentpath,
                d.documentname,
                d.ord
            FROM document as d
            WHERE d.refid = '$id'
            AND d.status <> '10'
            AND d.documenttype = 'variant'
            ORDER BY d.ord ASC
            ";

        $images = Yii::$app->db->createCommand($imagesSql)->queryAll();

        if (!empty($model['productpict'])) {
            array_unshift($images, [
                'documentid' => null,
                'documentpath' => $model['productpict'],
                'documentname' => 'Product Main Image',
                'ord' => 0
            ]);
        }

        return $this->render('detail', [
            'model' => $model,
            'enum' => $enum,
            'images' => $images,
        ]);
    }
    public function actionSelect()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $params = Yii::$app->request->queryParams;
        $search = $params['search'] ?? '';
        $draw = $params['draw'] ?? '';
        $page = $params['page'] ?? 1;
        $pageSize = 5;
        $offset = ($page - 1) * $pageSize;

        $id = $params['id'] ?? '';

        $sql =
            "SELECT v.barcode, v.variantid,
        p.productname, p.productcode
        
        from variants v
        left join products p on v.productid = p.productid::text
        where v.status <> 10";

        $filter = '';

        if (!empty($search)) {
            $filter .= " AND (v.barcode ILIKE '%" . $search . "%' OR p.productname ILIKE '%" . $search . "%')";
        }

        if (!empty($id)) {
            $filter .= " and v.productid = '" . $id . "'";
        }
        $sql .= $filter;

        $sql .= " ORDER BY v.barcode LIMIT " . $pageSize . " OFFSET " . $offset;

        $data = Yii::$app->db->createCommand($sql)
            ->queryAll();

        $recordsTotal = (int) Yii::$app->db->createCommand("SELECT COUNT(*) FROM variants v left join products p on p.productid::text = v.productid WHERE v.status <> '10'" . $filter)->queryScalar();

        return [
            'data' => $data,
            "draw" => intval($draw),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsTotal,
        ];
    }
    public function actionList()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $params = Yii::$app->request->queryParams;
        $search = $params['search']['value'] ?? '';
        $length = $params['length'] ?? 10;
        $start = $params['start'] ?? 0;
        $draw = $params['draw'] ?? 1;
        $id = $params['id'] ?? '';
        $condition = $params['condition'] ?? '';
        $locationid = $params['locationid'] ?? '';

        $sql =
            "SELECT 
            v.variantid, v.series,
            p.productid, p.productname, 
            v.serialno, v.asetno, v.unitno, v.price,
            cs.contact_name, v.description_variant as description,
            cs.jobcompany,
            v.purchasedate,
            v.barcode AS barcode,
            COALESCE(c.enumtext_id, '-') as condition, 
            COALESCE(s.enumtext_id, '-') as shelf,
            COALESCE(l.enumtext_id, '-') as location,
            COALESCE(w.enumtext_id, '-') as warehouse,
            COALESCE(d.documentpath, 'default.png') as documentpath

        FROM variants v
        LEFT JOIN products p on v.productid = p.productid::text
        LEFT JOIN enum c on c.enumid = v.condition and c.enumtype = 'condition'
        LEFT JOIN enum s on s.enumid = v.shelf and s.enumtype = 'shelf'
        LEFT JOIN enum w ON w.enumid = s.refid AND w.enumtype = 'warehouse'
        LEFT JOIN enum l ON l.enumid = v.locationid and l.enumtype = 'location'
        LEFT JOIN contacts cs ON cs.contact_id = v.contact_id AND cs.contact_status <> '10'
        LEFT JOIN document d ON d.refid = v.variantid AND d.documenttype = 'variant' AND d.status <> '10' AND d.ord = 1 
        WHERE v.status <> '10' AND v.sku IS NULL";

        $filter = '';

        if (!empty($search)) {
            $filter .= " AND (v.barcode ILIKE '%" . $search . "%' OR p.productname ILIKE '%" . $search . "%' OR v.series ILIKE '%" .
                $search . "%' OR v.asetno ILIKE '%" . $search . "%' OR v.serialno ILIKE '%" . $search . "%' OR v.unitno ILIKE '%"
                . $search . "%' OR v.locationid ILIKE '%" . $search . "%' OR v.series ILIKE '%" . $search . "%')";
        }

        if (!empty($id)) {
            $filter .= " and v.productid = '" . $id . "'";
        }

        if (!empty($condition)) {
            $filter .= " and v.condition = '" . $condition . "'";
        }

        if (!empty($locationid)) {
            $filter .= " and v.locationid = '" . $locationid . "'";
        }

        $sql .= $filter;

        $query = " GROUP BY v.variantid, p.productid, p.productname, v.serialno, v.asetno, v.unitno, v.price, v.barcode, v.series, d.documentpath,
        v.description_variant, c.enumtext_id, s.enumtext_id, w.enumtext_id, cs.contact_id, cs.jobcompany, v.purchasedate, l.enumtext_id";

        $sql .= $query . " ORDER BY v.unitno LIMIT " . $length . " OFFSET " . $start;

        // var_dump($sql);exit;

        $data = Yii::$app->db->createCommand($sql)
            ->queryAll();

        foreach ($data as &$row) {
            if (isset($row['purchasedate'])) {
                $row['purchasedate_display'] = $row['purchasedate'] ? Yii::$app->formatter->asDate($row['purchasedate'], 'php:d-m-Y') : null;
            }
        }

        $recordsTotal = (int) Yii::$app->db->createCommand("SELECT COUNT(*) FROM variants v LEFT JOIN products p on p.productid::text = v.productid WHERE v.status <> '10' AND v.sku IS NULL" . $filter)->queryScalar();

        return [
            'data' => $data,
            "draw" => intval($draw),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsTotal,
        ];
    }
    public function actionCreate()
    {
        $model = new Variant();
        $modeldocuments = [new Document()];

        $model->unitno = $model->nextNoUnit();
        $model->asetno = $model->nextNoAset();
        $model->serialno = $model->nextNoSerial();

        if ($model->load(Yii::$app->request->post())) {
            $model->productid = trim($model->productid);
            $model->serialno = trim($model->serialno);

            $exists = "SELECT COUNT(*) FROM variants WHERE productid = '" . $model->productid . "' AND serialno = '" . $model->serialno . "' AND status <> 10";
            $existsData = Yii::$app->db->createCommand($exists)->queryScalar();

            if ($existsData > 0) {
                $errorMsg = 'Data with the same product and serial number already exists';
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => false, 'pesan' => $errorMsg];
                }
                Yii::$app->session->setFlash('error', $errorMsg);
                return $this->redirect(['create']);
            }

            $modeldocuments = Model::createMultipleID(Document::class, $modeldocuments, 'documentid');
            Model::loadMultiple($modeldocuments, Yii::$app->request->post());

            $valid = $model->validate();
            $valid = Model::validateMultiple($modeldocuments) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $product = Product::find()->andWhere(['CAST(productid AS TEXT)' => $model->productid])->one();
                    if (!$product) {
                        throw new Exception("Product not found");
                    }

                    if (!$model->save(false)) {
                        throw new Exception("Failed to save variant");
                    }

                    foreach ($modeldocuments as $indexdetail => $docModel) {
                        $docModel->refid = $model->variantid;
                        $docModel->ord = $indexdetail + 1;
                        $docModel->documenttype = "variant";

                        $foto = \yii\web\UploadedFile::getInstanceByName("Document[{$indexdetail}][documentpath]");

                        if (empty($docModel->documentid)) {
                            $docModel->documentid = Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
                        }

                        $existingPath = $_POST['Document'][$indexdetail]['documentpath_existing'] ?? null;

                        if (!empty($foto)) {
                            $maxDocSize = 1 * 1024 * 1024;
                            if ($foto->size > $maxDocSize) {
                                throw new Exception("Gambar ke-" . ($indexdetail + 1) . " terlalu besar. Maksimal 2MB.");
                            }

                            $ext = pathinfo($foto->name, PATHINFO_EXTENSION);
                            $filename = $docModel->documentid . "." . $ext;

                            $docModel->documentname = $foto->name;
                            $docModel->documentext = $ext;
                            $docModel->documensize = $foto->size;
                            $docModel->documentpath = $filename;

                            $uploadDir = Yii::getAlias('@webroot') . '/uploads/variant/';
                            if (!is_dir($uploadDir)) {
                                mkdir($uploadDir, 0755, true);
                            }

                            $uploadPath = $uploadDir . $filename;
                            if (!$foto->saveAs($uploadPath)) {
                                throw new Exception("Failed to save file: $filename");
                            }
                        } else {
                            if (!empty($existingPath)) {
                                $docModel->documentpath = $existingPath;
                            } else {
                                if ($docModel->isNewRecord) {
                                    continue;
                                }
                            }
                        }

                        if (!$docModel->save(false)) {
                            throw new Exception("Failed to save document: " . implode(', ', $docModel->getFirstErrors()));
                        }
                    }

                    $transaction->commit();

                    if (Yii::$app->request->isAjax) {
                        return $this->jsonResponse(true, "Success Add Data");
                    }
                    Yii::$app->session->setFlash('success', 'Success Add Data');
                    return $this->redirect(['index']);

                } catch (Exception $e) {
                    $transaction->rollBack();
                    if (Yii::$app->request->isAjax) {
                        return $this->jsonResponse(false, $e->getMessage());
                    }
                    Yii::$app->session->setFlash('error', $e->getMessage());
                    return $this->redirect(['index']);
                }
            }
        }

        $renderData = [
            'model' => $model,
            'modeldocument' => empty($modeldocuments) ? [new Document()] : $modeldocuments,
            'isajax' => Yii::$app->request->isAjax,
        ];

        return Yii::$app->request->isAjax
            ? $this->renderAjax('_form', $renderData)
            : $this->render('_form', $renderData);
    }

    public function actionUpdate($id)
    {
        $model = $this->findVariant($id);

        if (!$model) {
            if (Yii::$app->request->isAjax) {
                return $this->jsonResponse(false, 'Variant is not found');
            }
            Yii::$app->session->setFlash('error', 'Variant is not found');
            return $this->redirect(['index']);
        }

        $modeldocuments = $model->documents;

        if ($model->load(Yii::$app->request->post())) {
            try {
                $oldIDs = ArrayHelper::map($modeldocuments, 'documentid', 'documentid');
                $modeldocuments = Model::createMultipleID(Document::class, $modeldocuments, 'documentid');
                Model::loadMultiple($modeldocuments, Yii::$app->request->post());
                $deletedIDsDoc = array_diff($oldIDs, array_filter(ArrayHelper::map($modeldocuments, 'documentid', 'documentid')));

                $valid = $model->validate();
                $valid = Model::validateMultiple($modeldocuments) && $valid;

                if ($valid) {
                    $transaction = Yii::$app->db->beginTransaction();

                    if (!$model->save(false)) {
                        throw new Exception("Failed to save variant");
                    }

                    if (!empty($deletedIDsDoc)) {
                        Document::updateAll(['status' => 10], ['documentid' => $deletedIDsDoc]);
                    }

                    foreach ($modeldocuments as $indexdetail => $docModel) {
                        $docModel->refid = $model->variantid;
                        $docModel->ord = $indexdetail + 1;
                        $docModel->documenttype = "variant";

                        if (empty($docModel->documentid)) {
                            $docModel->documentid = Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
                        }

                        $foto = \yii\web\UploadedFile::getInstanceByName("Document[{$indexdetail}][documentpath]");
                        $existingPath = $_POST['Document'][$indexdetail]['documentpath_existing'] ?? null;

                        if (!empty($foto)) {
                            if (!empty($existingPath)) {
                                $oldPath = Yii::getAlias('@webroot') . '/uploads/variant/' . $existingPath;
                                if (file_exists($oldPath)) {
                                    @unlink($oldPath);
                                }
                            }

                            $ext = pathinfo($foto->name, PATHINFO_EXTENSION);
                            $filename = $docModel->documentid . "." . $ext;

                            $docModel->documentname = $foto->name;
                            $docModel->documentext = $ext;
                            $docModel->documensize = $foto->size;
                            $docModel->documentpath = $filename;

                            $uploadPath = Yii::getAlias('@webroot') . '/uploads/variant/' . $filename;
                            if (!$foto->saveAs($uploadPath)) {
                                throw new Exception("Failed to save file: $filename");
                            }
                        } else {
                            if (!empty($existingPath)) {
                                $docModel->documentpath = $existingPath;
                                if (!$docModel->isNewRecord) {
                                    $oldDoc = Document::findOne($docModel->documentid);
                                    if ($oldDoc) {
                                        $docModel->documentname = $oldDoc->documentname;
                                        $docModel->documentext = $oldDoc->documentext;
                                        $docModel->documensize = $oldDoc->documensize;
                                    }
                                }
                            } else {
                                if ($docModel->isNewRecord) {
                                    continue;
                                }
                            }
                        }

                        if (!$docModel->save(false)) {
                            throw new Exception("Failed to save document: " . implode(', ', $docModel->getFirstErrors()));
                        }
                    }

                    $transaction->commit();

                    if (Yii::$app->request->isAjax) {
                        return $this->jsonResponse(true, "Success update data");
                    }
                    Yii::$app->session->setFlash('success', 'Success update data');
                    return $this->redirect(['update', 'id' => $model->variantid]);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                if (Yii::$app->request->isAjax) {
                    return $this->jsonResponse(false, $e->getMessage());
                }
                Yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(['update', 'id' => $model->variantid]);
            }
        }

        $enum = $model->getEnumText();
        $renderData = [
            'model' => $model,
            'modeldocument' => empty($modeldocuments) ? [new Document()] : $modeldocuments,
            'enum' => $enum,
            'isajax' => Yii::$app->request->isAjax,
        ];

        return Yii::$app->request->isAjax
            ? $this->renderAjax('_form', $renderData)
            : $this->render('_form', $renderData);
    }
    public function actionDelete()
    {
        $params = Yii::$app->request->post();
        // $productid = $params['productid'];
        $variantid = $params['variantid'];
        $model = $this->findVariant($variantid);
        if (!$model) {
            if (Yii::$app->request->isAjax) {
                return $this->jsonResponse(false, 'Variant not found');
            }
            Yii::$app->session->setFlash('error', 'Variant not found');
            return $this->redirect(['index']);
        }

        try {
            $transaction = Yii::$app->db->beginTransaction();
            $model->status = 10;
            // $model->stock = null;
            // $model->description = null;
            $valid = $model->validate();

            if ($valid && $model->save()) {
                $transaction->commit();
                return Yii::$app->request->isAjax
                    ? $this->jsonResponse(true, 'Data Berhasil Dihapus')
                    : $this->redirect(['index']);
            } else {
                $transaction->rollBack();
                return Yii::$app->request->isAjax
                    ? $this->jsonResponse(false, $model->getFirstErrors())
                    : $this->redirect(['index']);
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            return Yii::$app->request->isAjax
                ? $this->jsonResponse(false, $e->getMessage())
                : $this->redirect(['index']);
        }
    }
    public function actionDeletemassal()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $ids = Yii::$app->request->post('ids');

        if (empty($ids) || !is_array($ids)) {
            return ['success' => false, 'message' => 'Tidak ada data yang dipilih!'];
        }

        try {
            $affected = Variant::updateAll(
                ['status' => 10, 'stock' => null, 'description' => null],
                ['variantid' => $ids]
            );

            if ($affected > 0) {
                return ['success' => true, 'message' => "Berhasil menghapus $affected data!"];
            } else {
                return ['success' => false, 'message' => 'Tidak ada data yang berhasil dihapus!'];
            }
        } catch (Exception $e) {
            Yii::error("Error deleting variants: " . $e->getMessage(), __METHOD__);
            return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
        }
    }
    public function actionBarcode($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $product = Product::find()->andWhere(['productid' => $id])->one();
        if (!$product) {
            return $this->jsonResponse(false, "Product not found");
        }

        $barcode = Variant::nextBarcode($product->categoryid, $product->typeid, $product->brandid, $product->specid, $product->productid);

        return [
            'success' => true,
            'barcode' => $barcode,
            'productname' => $product->productname,
            'purchaseprice' => $product->purchaseprice,
        ];
    }
    public function actionGetUnitNo($id)
    {
        try {
            $nextNoUnit = (new Product())->nextNoUnit();
            $angkaKode = preg_replace('/\D/', '', $nextNoUnit); // Ambil angka saja
        } catch (Exception $e) {
            $nextNoUnit = "-00001";
            $angkaKode = "00001";
        }

        return $this->asJson([
            "no" => $nextNoUnit,
            "regno" => $angkaKode
        ]);
    }

    public function actionVariantdeli($refid)
    {
        $trandetail = Trandetail::findOne(['trandetailid' => $refid]);
        if (!$trandetail) {
            throw new Exception('Not found');

        }
        if (Yii::$app->request->isPost) {
            // var_dump(Yii::$app->request->post());exit;
            $tran = $trandetail->tran;

            try {
                $transaction = Yii::$app->db->beginTransaction();

                if ($tran->trantype == 'purchase/delivery' && $tran->reftype == 'purchase/order') {
                    $variants = Model::createMultipleID(Variant::classname(), [], 'trandetailid');
                    Model::loadMultiple($variants, Yii::$app->request->post());

                    foreach ($variants as $variant) {
                        $model = Variant::findOne($variant->variantid);
                        $model->condition = $variant->condition;

                        if (!$model->save()) {
                            throw new Exception('Error: ' . $model->getFirstErrors());
                        }

                    }
                } elseif (
                    $tran->trantype == 'sales/delivery' || $tran->trantype == 'sales/return'
                    || $tran->trantype == 'purchase/delivery' && $tran->reftype == 'sales/order'
                ) {
                    $postData = Yii::$app->request->post('Variant');
                    // var_dump($postData);exit;

                    if (!empty($postData)) {
                        foreach ($postData as $data) {
                            if (isset($data['variantid']) && !empty($data['variantid'])) {
                                $model = Variant::findOne($data['variantid']);
                                if ($model) {
                                    $model->condition = isset($data['condition']) ? $data['condition'] : $model->condition;
                                    $model->locationid = isset($data['locationid']) ? $data['locationid'] : $model->locationid;

                                    if (!$model->save()) {
                                        $error = implode(', ', $model->getFirstErrors());
                                        throw new Exception('Error saving variant ID ' . $model->variantid . ': ' . $error);
                                    }

                                    $updateVariant =
                                        "UPDATE tranvariants
                                    SET variantid = '" . $model->variantid . "'
                                    WHERE tranvariantid = '" . $data['tranvariantid'] . "'
                                    AND type = '3'
                                    ";

                                    Yii::$app->db->createCommand($updateVariant)->execute();
                                }
                            }
                        }
                    }
                }

                $transaction->commit();
                if (Yii::$app->request->isAjax) {
                    return $this->jsonResponse(true, "Success update data");
                }
                Yii::$app->session->setFlash('success', 'Success update data');
                return $this->redirect(['variantdeli', 'refid' => $refid]);

            } catch (Exception $e) {
                $transaction->rollBack();
                if (Yii::$app->request->isAjax) {
                    return $this->jsonResponse(false, $e->getMessage());
                }
                Yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(['variantdeli', 'refid' => $refid]);
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_variantlist', [
                'model' => $trandetail,
                "isajax" => true,
            ]);
        }

        return $this->render('_variantlist', [
            'model' => $trandetail,
            'isajax' => false
        ]);
    }
    protected function findVariant($variantid)
    {
        return Variant::find()
            ->andWhere(['variantid' => $variantid])
            ->andWhere(['<>', 'status', 10])
            ->one();
    }

    public function actionPrice()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $params = Yii::$app->request;
        $productid = $params->get('productid');
        $qty = $params->get('qty', 0);
        $trandate = $params->get('trandate', date('d/m/Y'));
        $tranduedate = $params->get('tranduedate', date('d/m/Y'));

        $trandate = Yii::$app->function->datePostgresTZ($trandate);
        $tranduedate = Yii::$app->function->datePostgresTZ($tranduedate);

        $product = Product::find()->andWhere(['CAST(productid AS text)' => $productid])->one();

        if (!$product) {
            return $this->jsonResponse(false, "Product not found");
        }

        $stockBegin = Variant::find()
            ->where(['productid' => $productid])
            ->andWhere(['status' => '1'])
            ->andWhere(['condition' => 'condition.1'])
            ->count();

        $amounts = (new Query())
            ->select(['td.amount'])
            ->from(['td' => 'trandetails'])
            ->leftJoin(['t' => 'trans'], 't.tranid = td.tranid')
            ->where([
                't.trantype' => 'sales/order',
                't.status' => 1,
                'td.productid' => $productid,
            ])
            ->andWhere(['>=', 't.tranduedate', $trandate])
            ->andWhere(['<=', 't.trandate', $tranduedate])
            ->all();

        $stockUse = 0;
        foreach ($amounts as $amount) {
            $stockUse += $amount['amount'];

        }

        $stock = $stockBegin - $stockUse;

        // var_dump($stock - $stockUse);exit;
        $price = $product->price;

        return [
            'success' => true,
            'pesan' => 'Success',
            'data' => [
                'price' => $price,
                'qty' => $qty,
                'productname' => $product->productname,
                'stock' => $stock
            ]
        ];
    }
    protected function jsonResponse($success, $pesan, $model = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            // 'success' => $success,
            'success' => $success,
            'pesan' => $pesan,
            'id' => $model->variantid ?? null,
            'product' => $model->productid ?? null,
        ];
    }
}
