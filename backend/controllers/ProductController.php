<?php

namespace backend\controllers;

use Yii;
use Exception;
use yii\web\Controller;
use common\models\Product;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use common\models\Variant;
use common\models\Trandetail;
use common\models\Document;
use common\models\Model;
use yii\db\Query;


class ProductController extends Controller
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
                        'actions' => ['index', 'indexv', 'create', 'update', 'view', 'delete', 'list', 'deletemassal', 'restore', 'search', 'searchstock', 'price', 'cekstockproduct', 'cekstock', 'indexstock', 'categorylist', 'unitlist', 'info', 'missing', 'cekmissing', 'fixsequence'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return true;
                        }
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return Yii::$app->enum->isakses("produk", "lihat");
                        }
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return Yii::$app->enum->isakses("produk", "tambah");
                        }
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return Yii::$app->enum->isakses("produk", "ubah");
                        }
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return Yii::$app->enum->isakses("produk", "hapus");
                        }
                    ],
                    [
                        'actions' => [
                            'index',
                            'indexv',
                            'create',
                            'update',
                            'list',
                            'listapi',
                            'getno',
                            'deletemassal',
                            'savetemplate',
                            'settemplate',
                            'resettemplate',
                            'previewcode',
                            'detail',
                            'select',
                            'set',
                            'categorylist',
                            'unitlist'
                        ],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Produk models.
     * @return mixed
     */
    public function actionIndex()
    {
        $user = Yii::$app->user;

        $access = $user->identity->getAccess('product');
        $model = new Product();

        return $this->render('index', [
            'model' => $model,
            'access' => $access
        ]);
    }
    public function actionIndexv()
    {
        $user = Yii::$app->user;

        $access = $user->identity->getAccess('product');
        $model = new Product();

        return $this->render('indexv', [
            'model' => $model,
            'access' => $access
        ]);
    }

    /**
     * Get category list for filter dropdown
     */
    public function actionCategorylist()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $sql =
            "SELECT DISTINCT
                e.enumid as id,
                e.enumtext_en as text
            FROM enum e
            INNER JOIN products p ON p.categoryid = e.enumid
            WHERE e.enumtype = 'category'
            AND e.status <> '10'
            AND p.status <> '10'
            ORDER BY e.enumtext_en ASC
        ";

        $data = Yii::$app->db->createCommand($sql)->queryAll();

        return [
            'results' => $data,
            'pagination' => ['more' => false]
        ];
    }

    /**
     * Get unit list for filter dropdown
     */
    public function actionUnitlist()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $sql =
            "SELECT DISTINCT
                e.enumid as id,
                e.enumtext_en as text
            FROM enum e
            INNER JOIN products p ON p.unitid = e.enumid
            WHERE e.enumtype = 'unit'
            AND e.status <> '10'
            AND p.status <> '10'
            ORDER BY e.enumtext_en ASC
        ";

        $data = Yii::$app->db->createCommand($sql)->queryAll();

        return [
            'results' => $data,
            'pagination' => ['more' => false]
        ];
    }

    public function actionSelect()
    {
        $q = $_POST['q'] ?? ($_POST['search'] ?? '');
        $id = $_POST['id'];

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['totalcount' => 0, 'items' => ['id' => '', 'text' => '']];
        $filter = " WHERE 1=1 AND A.status <> '10' AND A.contact_id IS NULL";

        $limit = isset($_POST['limit']) ? $_POST['limit'] : 5;
        $start = $limit * (isset($_POST['page']) ? ($_POST['page'] - 1) : 0);
        $order = " order by A.productcode asc limit $limit offset $start";

        if (!is_null($q)) {
            $filter .= " AND ("
                . "A.productcode ilike '%" . $q . "%' "
                . " or A.productname ilike '%" . $q . "%' "
                . ") ";
        }

        if (!is_null($id)) {
            $filter .= " AND A.productid ='" . $id . "'";
        }

        $sql = "SELECT
				A.productid as id, A.productpict, A.productname ||' ['||A.productcode ||']'as text
                FROM products A
                $filter";

        $sqlcount = "SELECT COUNT(*) FROM ($sql) as temp";
        $query = $sql . $order;

        $data = Yii::$app->db->createCommand($query)->queryAll();
        $count = Yii::$app->db->createCommand($sqlcount)->queryScalar();

        $out['items'] = array_values($data);
        $out['totalcount'] = $count;
        return $out;
    }
    public function actionSearch()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->request->queryParams;
        $q = $params['q'] ?? '';
        $limit = (int) ($params['limit'] ?? 10);
        $offset = (int) ($params['offset'] ?? 0);
        $notin = $params['notin'] ?? [];
        $categoryid = $params['categoryid'] ?? '';

        $filter = " WHERE 1=1 AND status <> '10'";

        if (!empty($notin)) {
            $notinList = "'" . implode("','", $notin) . "'";
            $filter .= " AND productid NOT IN ($notinList)";
        }

        if ($q !== '') {
            $filter .= " AND (
            productcode ILIKE '%$q%' 
            OR productname ILIKE '%$q%'
        )";
        }

        if ($categoryid !== '') {
            $filter .= " AND categoryid = '$categoryid'";
        }

        $sql =
            "SELECT 
            productid AS value,
            productname,
            productcode,
            productpict
        FROM products
        $filter
        ORDER BY productcode ASC
        LIMIT $limit OFFSET $offset
    ";

        $data = Yii::$app->db->createCommand($sql)->queryAll();

        return [
            "data" => $data,
            "hasMore" => count($data) === $limit
        ];
    }
    public function actionSearchstock()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->request->queryParams;
        $q = $params['q'] ?? '';
        $limit = (int) ($params['limit'] ?? 10);
        $offset = (int) ($params['offset'] ?? 0);
        $notin = $params['notin'] ?? [];
        $categoryid = $params['categoryid'] ?? '';

        $filter = " WHERE 1=1 AND status <> '10' AND contact_id IS NULL";

        if (!empty($notin)) {
            $notinList = "'" . implode("','", $notin) . "'";
            $filter .= " AND productid NOT IN ($notinList)";
        }

        if ($q !== '') {
            $filter .= " AND (
                productcode ILIKE '%$q%' 
                OR productname ILIKE '%$q%'
            )";
        }

        if ($categoryid !== '') {
            $filter .= " AND categoryid = '$categoryid'";
        }

        $sql =
            "SELECT 
                productid AS value,
                productname,
                productcode,
                productpict
            FROM products
            $filter
            ORDER BY productcode ASC
            LIMIT $limit OFFSET $offset
        ";

        $data = Yii::$app->db->createCommand($sql)->queryAll();

        return [
            "data" => $data,
            "hasMore" => count($data) === $limit
        ];
    }
    public function actionIndexstock()
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('indexstock');
        }
        return $this->render('indexstock');
    }
    public function actionMissing()
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_missing');
        }
        return $this->render('_missing');
    }

    public function actionPrice()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->request;
        $productid = $params->get('productid');

        $term = $params->get('term', 0);
        $freq = $term > 0 ? $term : $params->get('freq', 0);
        $qty = $params->get('qty', 0);
        $tranid = $params->get('tranid', '');
        $withdrawaldate = $params->get('withdrawaldate', date('d/m/Y H:i'));
        $setupdate = $params->get('setupdate', date('d/m/Y H:i'));

        if (strpos($withdrawaldate, '/') !== false) {
            $withdrawaldate = \DateTime::createFromFormat('d/m/Y H:i', $withdrawaldate)->format('Y-m-d H:i:s');
        }

        if (strpos($setupdate, '/') !== false) {
            $setupdate = \DateTime::createFromFormat('d/m/Y H:i', $setupdate)->format('Y-m-d H:i:s');
        }

        $product = Product::find()->andWhere(['CAST(productid AS text)' => $productid])->one();

        if (!$product) {
            return $this->jsonResponse(false, "Product not found");
        }

        $stockData = $product->getStockProduct($setupdate, $withdrawaldate, $tranid);
        $stock = $stockData['barangTersedia'];
        // $price = $product->purchaseprice;
        // var_dump($stockData);die();

        return [
            'success' => true,
            'pesan' => 'Success',
            'data' => [
                'price' => $product->purchaseprice ?? 0,
                'qty' => $qty,
                'productname' => $product->productname,
                'productid' => $product->productid,
                'stock' => $stock,
                'freq' => $freq,
                'date' => date('Y-m-d H:i:s'),
                'detail_stock' => $stockData
            ]
        ];
    }
    public function actionCekstock()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;

        $products = $request->get('products');
        $date = $request->get('date');
        $tranid = $request->get('tranid', '');
        // var_dump($tranid);

        if (empty($date)) {
            $date = date('d/m/Y') . ' - ' . date('d/m/Y');
        }
        [$start, $end] = array_map('trim', explode('-', $date));

        $start = explode(' ', $start)[0];
        $end = explode(' ', $end)[0];

        $startDate = \DateTime::createFromFormat('d/m/Y', $start);
        $endDate = \DateTime::createFromFormat('d/m/Y', $end);

        $dates = [];
        while ($startDate <= $endDate) {
            $dates[] = $startDate->format('Y-m-d');
            $startDate->modify('+1 day');
        }

        $query = Product::find()->where(['<>', 'status', 10]);

        if (!empty($products)) {
            $query->andWhere(['productid' => $products]);
        }

        $productsData = $query->all();
        $result = [];

        foreach ($productsData as $product) {
            $stock = [];

            foreach ($dates as $d) {
                $stock[$d] = $product->getStock($d, $d, $tranid);
            }

            $readyList = [];

            foreach ($stock as $s) {
                if (isset($s['ready'])) {
                    $readyList[] = $s['ready'];
                }
            }

            $available = !empty($readyList) ? min($readyList) : 0;

            $result[] = [
                'productid' => $product->productid,
                'productname' => $product->productname,
                'tranid' => $tranid,
                'available' => $available,
                'stock' => $stock,
            ];
        }

        return [
            'data' => $result
        ];
    }
    public function actionCekstockproduct()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $products = $request->get('products'); // array ID
        $date = $request->get('date');
        // $tranid = $request->get('tranid', '');
        // var_dump($tranid);

        if (empty($date)) {
            $date = date('d/m/Y') . ' - ' . date('d/m/Y');
        }

        [$start, $end] = array_map('trim', explode('-', $date));
        $startDate = \DateTime::createFromFormat('d/m/Y', $start);
        $endDate = \DateTime::createFromFormat('d/m/Y', $end);
        // $paramsstart = $startDate->format('Y-m-d');

        $dates = [];
        while ($startDate <= $endDate) {
            $dates[] = $startDate->format('Y-m-d');
            $startDate->modify('+1 day');
        }

        $query = Product::find()
            ->where(['<>', 'status', 10])
            ->andWhere(['contact_id' => null])->orderBy(['productcode' => SORT_ASC]);

        if (!empty($products)) {
            $query->andWhere(['productid' => $products]);
        }

        $productsData = $query->all();
        $result = [];

        foreach ($productsData as $product) {
            $stock = [];
            $returnDate = null;

            foreach ($dates as $d) {
                $stockData = $product->getStockProduct($d, $d);

                if ($returnDate === null && !empty($stockData['returnDate'])) {
                    $returnDate = $stockData['returnDate'] ?? null;
                }

                $stock[$d] = [
                    'ready' => $stockData['ready'],
                    'deal' => $stockData['deal'],
                    'lead' => $stockData['lead'],
                    'outin' => $stockData['outin'],
                    'returnDate' => $stockData['returnDate'],
                    'out' => $stockData['out'],
                    'barangTersedia' => $stockData['barangTersedia'],
                ];
            }
            // var_dump($stock);die();

            $result[] = [
                'productid' => $product->productid,
                'productname' => $product->productname,
                'stock' => $stock,
                'returnDate' => $returnDate,
            ];
        }

        return [
            'data' => $result
        ];
    }
    public function actionCekmissing()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->request->queryParams;

        $search = $params['search']['value'] ?? ($params['search'] ?? '');
        $length = intval($params['length'] ?? 10);
        $start = intval($params['start'] ?? 0);
        $draw = $params['draw'] ?? 1;

        $contact = $params['contact'] ?? '';
        $product = $params['product'] ?? '';
        $date = $params['datefilter'] ?? '';

        $sortcolumn = $params['order'][0]['column'] ?? 0;
        $ordercolumn = $params['columns'][$sortcolumn]['data'] ?? 'trandate';
        $columnorder = $params['order'][0]['dir'] ?? 'DESC';

        $allowedColumns = ['p.productname', 'tranno', 'trandate', 'v.barcode', 'c.contact_name', 'c.jobcompany'];
        if (!in_array($ordercolumn, $allowedColumns)) {
            $ordercolumn = 'trandate';
        }
        if (!in_array(strtoupper($columnorder), ['ASC', 'DESC'])) {
            $columnorder = 'DESC';
        }

        $conditions = " WHERE tv.type = '2' AND tv.status <> 10 
                AND v.status = 10 AND v.description = 'lose' AND p.status <> 10 
                AND NOT EXISTS (
                    SELECT 1 
                    FROM tranvariants tv_in
                    INNER JOIN trans tr_ret ON tr_ret.tranid = tv_in.refid
                    WHERE tr_ret.trantype IN ('sales/return', 'purchase/delivery')
                        AND tv_in.type = '3' 
                        AND tv_in.barcode = tv.barcode 
                        AND tr_ret.refid = o.tranid
                        AND COALESCE(tv_in.status, 0) <> 10
                )";

        if ($contact !== '') {
            $contact = addslashes($contact);
            $conditions .= " AND o.contact_id = '" . $contact . "'";
        }

        if ($product !== '') {
            $product = addslashes($product);
            $conditions .= " AND tv.productid = '" . $product . "'";
        }

        if (!empty($search)) {
            $keywords = preg_split('/\s+/', trim($search));
            foreach ($keywords as $word) {
                $word = addslashes($word);
                $conditions .= " AND (
                p.productname ILIKE '%{$word}%'
                OR o.tranno ILIKE '%{$word}%'
                OR v.barcode ILIKE '%{$word}%'
                OR c.contact_name ILIKE '%{$word}%'
                OR c.jobcompany ILIKE '%{$word}%'
            ) ";
            }
        }

        if ($date !== '') {
            $dates = explode(" - ", $date);
            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));
            $conditions .= " AND DATE(o.trandate) BETWEEN '$startDate' AND '$endDate'";
        }

        $countSql =
            "SELECT COUNT(*) FROM (
                SELECT 1
                FROM tranvariants tv
                INNER JOIN trans t ON t.tranid = tv.refid AND t.trantype = 'sales/delivery'
                INNER JOIN trans o ON o.tranid = t.refid AND o.trantype = 'sales/order' 
                LEFT JOIN products p ON p.productid = tv.productid 
                LEFT JOIN variants v ON v.variantid = tv.variantid 
                LEFT JOIN contacts c ON c.contact_id = o.contact_id
                $conditions
                GROUP BY v.variantid, p.productname, v.barcode, c.contact_name, c.jobcompany
            ) AS total_data";

        $totalRecords = Yii::$app->db->createCommand($countSql)->queryScalar();

        $sql =
            "SELECT 
                p.productname, 
                MAX(o.tranno) AS tranno, 
                MAX(o.trandate) AS trandate, 
                v.barcode, c.contact_name, c.jobcompany,
                v.variantid,
                COUNT(tv.tranvariantid) AS qty
            FROM tranvariants tv
            LEFT JOIN trans t ON t.tranid = tv.refid AND t.trantype = 'sales/delivery'
            LEFT JOIN trans o ON o.tranid = t.refid AND o.trantype = 'sales/order' 
            LEFT JOIN products p ON p.productid = tv.productid 
            LEFT JOIN variants v ON v.variantid = tv.variantid 
            LEFT JOIN contacts c ON c.contact_id = o.contact_id
            $conditions
            GROUP BY v.variantid, p.productname, v.barcode, c.contact_name, c.jobcompany
            ORDER BY $ordercolumn $columnorder
            LIMIT $length OFFSET $start";

        $data = Yii::$app->db->createCommand($sql)->queryAll();

        return [
            'data' => $data ?: [],
            'draw' => intval($draw),
            'recordsTotal' => intval($totalRecords),
            'recordsFiltered' => intval($totalRecords),
            'pagination' => [
                'more' => ($length + $start) < $totalRecords,
            ],
        ];
    }

    /**
     * Displays a single Produk model.
     * @param string $id
     * @return mixed
     */

    public function actionList()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->request->queryParams;
        $search = $params['search']['value'] ?? ($params['search'] ?? '');
        $length = $params['length'] ?? 10;
        $start = ($params['start'] ?? 0);
        $draw = $params['draw'] ?? 1;

        // $filterCategory = $params['filter_category'] ?? '';
        // $filterUnit = $params['filter_unit'] ?? '';

        $query =
            "SELECT
                p.productid, p.productname, p.purchaseprice, 
                p.description_product, p.features, p.duration_days
                    
            FROM products p
            WHERE p.status <> '10'
        ";

        // if (!empty($filterUnit)) {
        //     $query .= " AND p.unitid = '" . addslashes($filterUnit) . "'";
        // }

        if (!empty($search)) {
            $keywords = preg_split('/\s+/', trim($search));

            foreach ($keywords as $word) {
                $word = addslashes($word);
                $query .= " AND (
                p.productname ILIKE '%{$word}%'
                -- OR p.productcode ILIKE '%{$word}%' OR p.custom_duration_unit ILIKE '%{$word}%'
                -- OR p.custom_duration_unit ILIKE '%{$word}%'
                -- OR s.enumtext_en ILIKE '%{$word}%' OR u.enumtext_en ILIKE '%{$word}%'
            ) ";
            }
        }

        $query .=
            " GROUP BY p.productid, p.productname
            ";

        $sql = $query . " ORDER BY p.dateadd ASC LIMIT " . $length . " OFFSET " . $start;
        // echo $sql;exit;

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $totalQuery = "SELECT COUNT(*) FROM products WHERE status <> '10'";

        $recordsTotal = (int) Yii::$app->db->createCommand($totalQuery)->queryScalar();

        $countQuery =
            "SELECT COUNT(DISTINCT p.productid)
            FROM products p
            WHERE p.status <> '10'
        ";


        // if (!empty($filterUnit)) {
        //     $countQuery .= " AND p.unitid = '" . addslashes($filterUnit) . "'";
        // }

        if (!empty($search)) {
            $keywords = preg_split('/\s+/', trim($search));
            foreach ($keywords as $word) {
                $word = addslashes($word);
                $countQuery .= " AND (
                    p.productname ILIKE '%{$word}%'
                    -- OR p.productcode ILIKE '%{$word}%'
                    -- OR p.series ILIKE '%{$word}%'
                    -- OR sc.enumtext_en ILIKE '%{$word}%'
                    -- OR s.enumtext_en ILIKE '%{$word}%'
                    -- OR u.enumtext_en ILIKE '%{$word}%'
                ) ";
            }
        }

        $recordsFiltered = (int) Yii::$app->db->createCommand($countQuery)->queryScalar();

        return [
            "draw" => intval($draw),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
            'pagination' => [
                'more' => ($length + $start) < $recordsFiltered,
            ],
        ];
    }

    /**
     * Creates a new Produk model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post())) {
            // var_dump($model->attributes); // Debugging line to check the attributes
            $post = Yii::$app->request->post();

            $rawFeatures = $post['Fitur'] ?? [];
            $fiturList = [];

            foreach ($rawFeatures as $item) {
                if (!empty($item['name'])) {
                    $fiturList[] = trim($item['name']);
                }
            }

            $model->features = json_encode($fiturList);

            $model->duration_type = $post['duration_option'] ?? $model->duration_type;
            $model->visit_mode = $post['Visit']['mode'] ?? 'unlimited';
            $model->visit_period = $post['Visit']['period'] ?? 'periode';
            $model->visit_expired_status = (int) ($post['Visit']['expired_status'] ?? 0);
            $model->coaching_mode = $post['Coaching']['mode'] ?? 'none';
            $model->coaching_period = $post['Coaching']['period'] ?? 'periode';
            $model->booking_mode = $post['Booking']['mode'] ?? 'none';
            $model->booking_period = $post['Booking']['period'] ?? 'mingguan';
            $model->date_mode = $post['Tanggal']['mode'] ?? 'normal';

            $valid = $model->validate();

            if ($valid) {
                $productId = Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
                $model->productid = $productId;

                $transaction = Yii::$app->db->beginTransaction();
                try {

                    if (!$model->save(false)) {
                        throw new Exception("Failed to save product: " . implode(', ', $model->getFirstErrors()));
                    }

                    $transaction->commit();

                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return [
                            'success' => true,
                            'pesan' => 'Data Berhasil Disimpan',
                            'id' => $model->productid,
                            'productname' => $model->productname
                        ];
                    }
                    return $this->redirect(['index', 'id' => $model->productid]);

                } catch (Exception $e) {
                    $transaction->rollBack();

                    if (isset($mainPhotoName) && isset($uploadPath) && file_exists($uploadPath)) {
                        @unlink($uploadPath);
                    }

                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['success' => false, 'pesan' => $e->getMessage()];
                    }
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    $pesan = implode("<br/>", array_merge($model->getFirstErrors()));
                    return ['success' => false, 'pesan' => $pesan];
                }
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
                'isajax' => true,
            ]);
        }

        return $this->render('_form', [
            'model' => $model,
            'isajax' => false,
        ]);
    }

    /**
     * Updates an existing Produk model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = Product::findOne($id);

        if ($model == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (!empty($model->features) && is_string($model->features)) {
            $model->features = json_decode($model->features, true);
        }

        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();

            $rawFeatures = $post['Fitur'] ?? [];
            $fiturList = [];

            foreach ($rawFeatures as $item) {
                if (!empty($item['name']) && trim($item['name']) !== '') {
                    $fiturList[] = trim($item['name']);
                }
            }

            $model->features = json_encode($fiturList);

            $model->duration_type = $post['duration_option'] ?? $model->duration_type;
            $model->visit_mode = $post['Visit']['mode'] ?? 'unlimited';
            $model->visit_period = $post['Visit']['period'] ?? 'periode';
            $model->visit_expired_status = (int) ($post['Visit']['expired_status'] ?? 0);
            $model->coaching_mode = $post['Coaching']['mode'] ?? 'none';
            $model->coaching_period = $post['Coaching']['period'] ?? 'periode';
            $model->booking_mode = $post['Booking']['mode'] ?? 'none';
            $model->booking_period = $post['Booking']['period'] ?? 'mingguan';
            $model->date_mode = $post['Tanggal']['mode'] ?? 'normal';

            if ($model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if (!$model->save(false)) {
                        throw new Exception("Failed to save product: " . implode(', ', $model->getFirstErrors()));
                    }

                    $transaction->commit();

                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return [
                            'success' => true,
                            'pesan' => 'Data Berhasil Disimpan',
                            'id' => $model->productid
                        ];
                    }
                    return $this->redirect(['index', 'id' => $model->productid]);

                } catch (Exception $e) {
                    $transaction->rollBack();
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['success' => false, 'pesan' => $e->getMessage()];
                    }
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
                'isajax' => true,
            ]);
        }

        return $this->render('_form', [
            'model' => $model,
            'isajax' => false,
        ]);
    }

    /**
     * Deletes an existing Produk model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findProduct($id);

        if (!$model) {
            return Yii::$app->request->isAjax
                ? $this->jsonResponse(false, 'Product not found')
                : $this->redirect(['index']);
        }

        try {
            $transaction = Yii::$app->db->beginTransaction();
            $model->status = 10;
            // $model->stock = null;
            // $model->description = null;

            if ($model->productpict) {
                $path = Yii::getAlias('@webroot/uploads/produk/') . $model->productpict;
                if (file_exists($path) && is_file($path)) {
                    unlink($path);
                }
            }

            $valid = $model->validate();

            if ($valid && $model->save(true)) {
                $transaction->commit();
                return Yii::$app->request->isAjax
                    ? $this->jsonResponse(true, 'Success Delete Data')
                    : $this->redirect(['index']);
            } else {
                $transaction->rollBack();
                return Yii::$app->request->isAjax
                    ? $this->jsonResponse(false, 'Failed Delete Data')
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

        if (!empty($ids)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $idString = implode(',', array_map(function ($id) {
                    return "'" . addslashes($id) . "'";
                }, $ids));

                $sql = "UPDATE products SET status = 10 
                WHERE productid IN ($idString)";
                Yii::$app->db->createCommand($sql)->execute();

                $sqlSelect = "SELECT productid, productcode 
                FROM products WHERE status <> 10 ORDER BY dateadd ASC";
                $activeProducts = Yii::$app->db->createCommand($sqlSelect)->queryAll();

                $counters = [];

                foreach ($activeProducts as $product) {
                    $parts = explode('-', $product['productcode']);
                    $prefix = $parts[0] ?? 'PRD';

                    if (!isset($counters[$prefix])) {
                        $counters[$prefix] = 1;
                    } else {
                        $counters[$prefix]++;
                    }

                    $newCode = $prefix . '-' . str_pad($counters[$prefix], 4, '0', STR_PAD_LEFT);

                    if ($product['productcode'] !== $newCode) {
                        $sqlUpdate = "UPDATE products 
                                  SET productcode = '$newCode' 
                                  WHERE productid = '" . $product['productid'] . "'";

                        Yii::$app->db->createCommand($sqlUpdate)->execute();
                    }

                }

                $transaction->commit();
                return ['success' => true, 'message' => 'Data berhasil dihapus!'];

            } catch (Exception $e) {
                $transaction->rollBack();
                return ['success' => false, 'message' => 'Gagal menghapus data: ' . $e->getMessage()];
            }
        }
        return ['success' => false, 'message' => 'Tidak ada data yang dipilih!'];
    }
    public function actionRestore()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $ids = Yii::$app->request->post('ids');

        if (!empty($ids)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $idString = implode(',', array_map(function ($id) {
                    return "'" . addslashes($id) . "'";
                }, $ids));

                $sql = "UPDATE variants SET status = 1, stock = NULL, 
                description = 'restore', locationid = 'location.1'
                WHERE variantid IN ($idString)";

                Yii::$app->db->createCommand($sql)->execute();

                $transaction->commit();
                return ['success' => true, 'message' => 'Data berhasil dipulihkan!'];

            } catch (Exception $e) {
                $transaction->rollBack();
                return ['success' => false, 'message' => 'Gagal memulihkan data: ' . $e->getMessage()];
            }
        }
        return ['success' => false, 'message' => 'Tidak ada data yang dipilih!'];
    }

    public function actionFixsequence()
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $sqlSelect = "SELECT productid, productcode 
            FROM products WHERE status <> 10 ORDER BY dateadd ASC";
            $activeProducts = Yii::$app->db->createCommand($sqlSelect)->queryAll();

            $index = 1;
            $template = 'PRD';
            $updatedCount = 0;

            foreach ($activeProducts as $product) {
                $newKode = $template . '-' . str_pad($index, 4, '0', STR_PAD_LEFT);

                if ($product['productcode'] !== $newKode) {
                    $sqlUpdate = "UPDATE products SET productcode = '$newKode' WHERE productid = '$product[productid]'
                   ";
                    Yii::$app->db->createCommand($sqlUpdate)->execute();

                    $updatedCount++;
                }

                $index++;
            }

            $transaction->commit();
            echo "Berhasil merapikan urutan! Ada " . $updatedCount . " kode produk yang disesuaikan.";

        } catch (\Exception $e) {
            $transaction->rollBack();
            echo "Gagal merapikan urutan: " . $e->getMessage();
        }
    }

    /**
     * Finds the Produk model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    // public function actionSet()
    // {
    //     Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    //     $data = (new Query())
    //         ->select(['productid', 'COUNT(variantid) AS total_variant'])
    //         ->from('variants')
    //         ->where(['status' => 1])
    //         ->groupBy('productid')
    //         ->all();
    //     $transaction = Yii::$app->db->beginTransaction();

    //     try {
    //         foreach ($data as $var) {
    //             $trandetail = new Trandetail();
    //             $trandetail->tranid = "617eff36-d0a2-4401-8cbe-51a28299402e";
    //             $trandetail->productid = $var['productid'];
    //             $trandetail->amount = $var['total_variant'];
    //             $trandetail->remain = $var['total_variant'];
    //             $trandetail->remain2 = $var['total_variant'];
    //             $trandetail->status = 1;

    //             if (!$trandetail->save(false)) {
    //                 throw new Exception();
    //             }
    //         }
    //         $transaction->commit();
    //         return "success";

    //     } catch (Exception $e) {
    //         $transaction->rollBack();
    //         return "gagal";

    //     }

    // }

    public function actionDetail($id)
    {
        $id = Yii::$app->request->get('id');
        $viewType = Yii::$app->request->get('viewType', 'index');

        $sql =
            "SELECT 
                p.*,
                c.enumtext_id as categoryname,
                sc.enumtext_id as subcategoryname,
                t.enumtext_id as typename,
                b.enumtext_id as brandname,
                s.enumtext_id as specname,
                u.enumtext_id as unitname
            FROM products p
            LEFT JOIN enum c ON c.enumid = p.categoryid AND c.enumtype = 'category'
            LEFT JOIN enum sc ON sc.enumid = p.subcategoryid AND sc.enumtype = 'subcategory'
            LEFT JOIN enum t ON t.enumid = p.typeid AND t.enumtype = 'type'
            LEFT JOIN enum b ON b.enumid = p.brandid AND b.enumtype = 'brand'
            LEFT JOIN enum s ON s.enumid = p.specid AND s.enumtype = 'spec'
            LEFT JOIN enum u ON u.enumid = p.unitid AND u.enumtype = 'unit'
            WHERE p.productid = '$id' AND p.status <> '10'
            ";

        $model = Yii::$app->db->createCommand($sql)->queryOne();

        if (!$model) {
            throw new NotFoundHttpException("Produk tidak ditemukan.");
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
            AND d.documenttype = 'product'
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
            'images' => $images,
            'viewType' => $viewType
        ]);
    }
    protected function findProduct($id)
    {
        return Product::find()
            ->where(new \yii\db\Expression("productid::text = :id"))
            ->addParams([':id' => $id])
            ->one();
    }
    protected function jsonResponse($success, $pesan, $model = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'success' => $success,
            'pesan' => $pesan,
            'id' => $model->productid ?? null,
            'name' => $model->productname ?? null,
        ];
    }

    public function actionInfo($id, $date = null, $type = null)
    {
        $model = $this->findProduct($id);

        if (!$model) {
            throw new \yii\web\NotFoundHttpException('Product not found.');
        }

        $dateFilter = "";
        if ($date) {
            $parts = explode(' - ', $date);
            if (count($parts) == 2) {
                $start = date('Y-m-d', strtotime(str_replace('/', '-', trim($parts[0]))));
                $end = date('Y-m-d', strtotime(str_replace('/', '-', trim($parts[1]))));

                $dateFilter = " AND DATE(t.setupdate) <= '$end' AND (DATE(t.withdrawaldate)) >= '$start' ";
            }
        }

        $typeFilter = "";

        if ($type == 'deal') {
            $typeFilter = " AND t.statuspro IN ('1', '5', '10')";
        } else if ($type == 'lead') {
            $typeFilter = " AND t.statuspro = '0' ";
        }

        $sql =
            "SELECT * FROM (
            SELECT 
                p.productname,
                t.tranid,
                SPLIT_PART(t.locations, ',', 1) as locations,
                t.setupdate,
                t.withdrawaldate,
                t.tranno,
                c.contact_name,
                c.jobcompany,
                COALESCE(
                    (SELECT SUM(td2.amount)
                    FROM trandetails td2
                    WHERE td2.tranid = t.tranid 
                    AND td2.productid = '$id' 
                    AND td2.status <> 10),

                    (SELECT SUM(COALESCE(tdo.amount, 0) + COALESCE(tdo.amount2, 0))
                    FROM trans r
                    JOIN trandetails tdo ON tdo.tranid = r.tranid
                    WHERE r.refid = t.tranid AND tdo.productid = '$id' 
                    AND tdo.status <> 10 AND r.trantype = 'sales/delivery' 
                    AND r.status = '1'),
                    0
                ) as amount

            FROM trans t
            JOIN products p ON p.productid = '$id' AND p.status <> 10
            JOIN contacts c ON c.contact_id = t.contact_id
            WHERE t.status = 1 AND t.trantype = 'sales/order'
            $dateFilter
            $typeFilter
        ) sub
        WHERE sub.amount > 0  
        ORDER BY sub.setupdate DESC";

        $history = Yii::$app->db->createCommand($sql)->queryAll();
        // echo($sql);die();

        return $this->renderAjax('_info', [
            'model' => $model,
            'history' => $history,
            'date' => $date,
            'type' => $type
        ]);
    }
}