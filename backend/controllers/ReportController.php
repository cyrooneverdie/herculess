<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\User;
use common\models\Contact;
use common\models\Product;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\db\Query;
use JavaClass;
use Java;

use common\models\Document;

/**
 * ReportController implements the CRUD actions for Report model.
 */
class ReportController extends Controller
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
                        'actions' => ['index', 'download', 'laporan', 'list', 'campuran', 'contact', 'print'],
                        'allow' => true,
                        'matchCallback' => function () {
                            // return (Yii::$app->enum->isadmin());
                            return true;
                        }
                    ],

                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("contact", "lihat"));
                        }
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("contact", "add contact"));
                        }
                    ],
                    [
                        'actions' => ['update', 'update2', 'updatebersyarat', 'settings'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("contact", "ubah"));
                        }
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (Yii::$app->enum->isakses("contact", "hapus"));
                        }
                    ],


                    [
                        'actions' => [
                            'index',
                            'download',
                            'laporan',
                            'list',
                            'campuran',
                            'contact',
                            'print'
                        ],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }
    public function actionSetlang($lang)
    {
        Yii::$app->lang->setLang($lang);
        return $this->redirect(Yii::$app->request->referrer);
    }


    public function actionGenlang()
    {
        Yii::$app->lang->genLang();
    }

    public function actionIndex()
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('index'); // Render tanpa layout
        }
        return $this->render('index'); // Render biasa kalau bukan AJAX
    }

    public function actionLaporan($type)
    {
        $type = Yii::$app->request->get('type');

        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('laporan', ['type' => $type]); // Hanya render isinya, tanpa layout
        }
        return $this->render('laporan', ['type' => $type]); // Normal jika tanpa AJAX
    }

    public function actionList()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = "SELECT productcode, description_product, price FROM products limit 5";

        $data = Yii::$app->db->createCommand($query)->queryAll();
        return ['data' => $data ?: []];
    }

    public function actionContact()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = "SELECT 
                    c.contact_no AS no, 
                    c.contact_name AS name, 
                    c.contact_email1 AS email,
                    c.contact_phone1 AS phone,
                    g.enumtext_en AS gender,  
                    s.enumtext_en AS status,  
                    r.enumtext_en AS reli,    
                    e.enumtext_en AS edu,     
                    c.contact_bod AS bod,
                    CASE WHEN c.contact_isvendor = 1 THEN 'YES' ELSE '' END AS vendor,
                    CASE WHEN c.contact_iscustomer = 1 THEN 'YES' ELSE '' END AS customer,
                    k.enumtext_en AS identity_document,
                    c.idnumber,
                    j.enumtext_en AS job,
                    c.jobcompany,
                    c.jobstart,
                    c.jobend,
                    o.enumtext_id AS country,
                    p.enumtext_id AS state,
                    q.enumtext_id AS city,
                    d.enumtext_id AS district,
                    s.enumtext_id AS subdistrict,
                    c.address,
                    c.zip
            FROM contacts c
            LEFT JOIN enum g ON g.enumid = c.contact_gender AND g.enumtype = 'gender'
            LEFT JOIN enum s ON s.enumid = c.contact_status AND s.enumtype = 'status'
            LEFT JOIN enum r ON r.enumid = c.contact_religion AND r.enumtype = 'religion'
            LEFT JOIN enum e ON e.enumid = c.contact_education AND e.enumtype = 'education'
            LEFT JOIN enum j ON j.enumid = c.jobposition AND j.enumtype = 'jobposition'
            LEFT JOIN enum o ON o.enumid = c.countryid
            LEFT JOIN enum p ON p.enumid = c.stateid
            LEFT JOIN enum q ON q.enumid = c.cityid
            LEFT JOIN enum d ON d.enumid = c.districtid
            LEFT JOIN enum k ON k.enumid = c.idtype AND k.enumtype = 'idtype'
            WHERE c.contact_status = '1'";

        $data = Yii::$app->db->createCommand($query)->queryAll();
        return ['data' => $data ?: []];
    }

    public function actionCampuran()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $companyid = Yii::$app->session->get('companyid');
        $query = "SELECT t.tranid,
            c.contact_name,
            p.dateadd,
            p.productname,
            v.variantid,
            vh.price_buy,
            vh.price_wholesaler,
            vh.price_sell
        FROM trans t
        LEFT JOIN contacts c ON t.contact_id::text = c.contact_id::text
        LEFT JOIN products p ON t.productid::text = p.productid::text
        LEFT JOIN variants v ON v.productid::text = p.productid::text
        LEFT JOIN variantprices vh ON vh.variantid::text = v.variantid::text
        WHERE c.companyid = '$companyid';

                        ";
        // $query = "
        //     SELECT t.tranid, 
        //            c.contact_name, 
        //            p.dateadd, 
        //            p.productname, 
        //            v.variantid,
        //            vh.price_buy, 
        //            vh.price_wholesaler, 
        //            vh.price_sell
        //     FROM trans t
        //     LEFT JOIN contacts c ON cast(t.contact_id as text) = cast(c.contact_id as text)
        //     LEFT JOIN products p ON cast(p.productid as text) = cast(t.productid as text)
        //     LEFT JOIN variants v ON v.productid = p.productid
        //     LEFT JOIN variantprices vh ON vh.variantid = v.variantid
        //     WHERE c.companyid = :companyid
        // ";
        $data = Yii::$app->db->createCommand($query)->queryAll();
        return ['data' => $data ?: []];
    }

    public function actionDownload($type)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $type = Yii::$app->request->get('type');
        if ($type == "pdf") {
        }
    }

    public function actionPrint($id = "", $detailid = "", $module = "", $type = "")
    {
        require_once(Yii::getAlias('@anyname') . "/Report/java/Java.inc");

        $compileManager = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");
        $jenisreport = "pdf";

        $judul = "";
        $subjudul = "";
        $report = null;
        $sql = "";
        $sqlsub = "";
        $sqlcount = "";
        $sqlcountsub = "";
        $transaction = null;

        if ($id) {
            $transaction = Yii::$app->db->createCommand("
            SELECT trantype FROM trans WHERE tranid = '" . $id . "'")->queryOne();
        }

        $trantype = $transaction ? $transaction['trantype'] : '';

        if ($trantype == 'sales/quote') {
            $judul = "Quote";
            $this->getQueryTran($id, $detailid, $sql, $sqlsub, $sqlcount, $sqlcountsub);
            $jrxmlPath = Yii::getAlias('@anyname') . "/Report/rptQuoteTotal.jrxml";
            $report = $compileManager->compileReport(realpath($jrxmlPath));

        } else if ($trantype == 'sales/order') {

            if (($module == 'purchase' && $type == 'delivery') || ($module == 'sales' && $type == 'return')) {
                if ($module == 'purchase' && $type == 'delivery') {
                    $judul = "Penarikan";
                } else {
                    $judul = "Barang Kembali";
                }

                $this->getQueryPenarikan($id, $detailid, $sql, $sqlsub, $sqlcount, $sqlcountsub, $crew_pengembalian, $crew_penarikan);

                if ($module == 'purchase' && $type == 'delivery') {
                    $jrxmlPath = Yii::getAlias('@anyname') . "/Report/rptPenarikan.jrxml";

                } else if ($module == 'sales' && $type == 'return') {
                    $jrxmlPath = Yii::getAlias('@anyname') . "/Report/rptKembali.jrxml";
                }
            } else if ($type == 'delivery') {
                $judul = "Surat Jalan";
                $this->getQuerySerah($id, $detailid, $sql, $sqlsub, $sqlcount, $sqlcountsub);
                $jrxmlPath = Yii::getAlias('@anyname') . "/Report/rptSuratSerah.jrxml";
            } else if ($type == 'delivery2') {
                $judul = "Surat Jalan Copy";
                $this->getQuerySerah($id, $detailid, $sql, $sqlsub, $sqlcount, $sqlcountsub);
                $jrxmlPath = Yii::getAlias('@anyname') . "/Report/rptSuratSerahCopy.jrxml";
            } else if ($type == 'pengambilan') {
                $judul = "Surat Pengambilan";
                $this->getQueryPengambilan($id, $detailid, $sql, $sqlsub, $sqlcount, $sqlcountsub);
                $jrxmlPath = Yii::getAlias('@anyname') . "/Report/rptSuratPengambilan.jrxml";
            } else if ($type == 'pengambilan2') {
                $judul = "Surat Pengambilan Copy";
                $this->getQueryPengambilan($id, $detailid, $sql, $sqlsub, $sqlcount, $sqlcountsub);
                $jrxmlPath = Yii::getAlias('@anyname') . "/Report/rptSuratPengambilanCopy.jrxml";
            } else {
                $judul = "Project";
                $this->getQueryTran($id, $detailid, $sql, $sqlsub, $sqlcount, $sqlcountsub);
                $jrxmlPath = Yii::getAlias('@anyname') . "/Report/rptProjectTotal.jrxml";
            }
            $report = $compileManager->compileReport(realpath($jrxmlPath));

        } else if ($trantype == 'purchase/order') {
            $judul = "Purchase Order";
            $this->getQueryPurchase($id, $detailid, $sql, $sqlsub, $sqlcount, $sqlcountsub);
            $jrxmlPath = Yii::getAlias('@anyname') . "/Report/rptPurchaseOrder.jrxml";
            $report = $compileManager->compileReport(realpath($jrxmlPath));
        } else if ($trantype == 'sales/delivery') {
            $judul = "Pengantaran";
            $this->getQueryOut($id, $detailid, $sql, $sqlsub, $sqlcount, $sqlcountsub);
            $jrxmlPath = Yii::getAlias('@anyname') . "/Report/rptPengantaran.jrxml";
            $report = $compileManager->compileReport(realpath($jrxmlPath));

        }
        // echo " " . $sql . "\n";exit;

        $params = new Java("java.util.HashMap");
        $params->put("pquery", $sql);
        $params->put("pquerysub", $sqlsub);
        $params->put("pcrew_pengembalian", $crew_pengembalian);
        $params->put("pcrew_penarikan", $crew_penarikan);
        $params->put("pdirfotoinstansi", Yii::getAlias('@anyname') . "/Report/img/");
        $params->put("pjudul", $judul);
        $params->put("puser", Yii::$app->user->identity->username);
        $params->put("pwaktu", date("d-m-Y H:i:s"));
        $params->put("pinstansi", "Laundry Ikonten");
        $params->put("palamat", "Jl. Contoh No. 1, Batam");
        $params->put("ptelp", "0812-3456-7890");
        $params->put("pjabatan", "Kasir");
        $params->put("pusername", strtoupper(Yii::$app->user->identity->username));
        $params->put("SUBREPORT_DIR", Yii::getAlias('@anyname') . "/Report/");

        $namafile = str_replace(" ", "_", $judul) . "_" . $id;

        if ($jenisreport == "pdf") {
            \Yii::$app->function->PrintPDF($report, $params, $namafile);
        } else if ($jenisreport == "excel") {
            \Yii::$app->function->PrintExcel($report, $params, $namafile);
        } else if ($jenisreport == "word") {
            \Yii::$app->function->PrintDoc($report, $params, $namafile);
        }
    }

    public function getQueryTran($id, $detailid, &$sql, &$sqlsub, &$sqlcount, &$sqlcountsub)
    {
        $filter = "";
        if ($id <> '') {
            $filter = " AND t.tranid ='" . $id . "' ";
        }
        if ($detailid <> '') {
            $filter = " AND t.trandetailid ='" . $detailid . "' ";
        }

        $temp =
            "SELECT
            t.trandetailid,
            tr.tranid,
            tr.tranno,
            c.contact_no,
            c.contact_name,
            c.jobcompany,
            c.contact_phone1,
            c.contact_email1,
            tr.trandate,
            tr.term,
            tr.tranduedate,
            tr.setupdate,
            tr.withdrawaldate,
            tr.locations,
            tr.eventname,
            COALESCE(ae.contact_name, '') AS ae_name,
            COALESCE(ae.contact_phone1, '') AS ae_phone,

            CASE
                WHEN tr.eventtype = '0' THEN 'Full Rent'
                WHEN tr.eventtype = '1' THEN 'Dry Rent'
                WHEN tr.eventtype = '2' THEN 'Sub Rent'
                WHEN tr.eventtype = '3' THEN 'Take Away'
                ELSE '-'
            END AS eventype,

            p.productcode,
            p.productname,
            t.description,
            t.amount,
            t.freqvalue,
            t.price,
            t.itemsubtotaltax AS totalitem,
            t.itemdiscpersen,
            tx.enumtext_id AS tax,
            t.itemtotaltax,
            tr.subtotal,
            tr.disc,
            tr.totalafterdisc,

            CASE
                WHEN COALESCE(tr.ppnamount, 0) > 0 THEN 'PPN'
                WHEN COALESCE(tr.pphamount, 0) > 0 THEN 'PPH'
                ELSE NULL
            END AS pajak_jenis,

            CASE
                WHEN COALESCE(tr.ppnamount, 0) > 0
                OR COALESCE(tr.pphamount, 0) > 0
                THEN 'ACTIVE'
                ELSE 'NON ACTIVE'
            END AS pajak_status,

            CASE
                WHEN COALESCE(tr.ppnamount, 0) > 0 THEN tr.ppnamount
                WHEN COALESCE(tr.pphamount, 0) > 0 THEN tr.pphamount
                ELSE NULL
            END AS pajak_amount,

            COALESCE(tr.otherdiscount, 0) AS otherdiscount,
            COALESCE(tr.deliverycharge, 0) AS deliverycharge,
            
            CASE
                WHEN COALESCE(tr.ppnamount, 0) > 0
                OR COALESCE(tr.pphamount, 0) > 0
                THEN 'Harga di atas <b>SUDAH</b> termasuk PPN & PPH'
                ELSE 'Harga di atas BELUM termasuk PPN & PPH'
            END AS keterangan_pajak,

            tr.grandtotal

        FROM trandetails t
        LEFT JOIN trans tr ON t.tranid = tr.tranid
        LEFT JOIN contacts c ON tr.contact_id = c.contact_id
        LEFT JOIN contacts ae ON tr.aeid = ae.contact_id AND ae.contacttype = 'employee'
        LEFT JOIN products p ON t.productid = p.productid
        LEFT JOIN enum tx ON t.itemtaxid = tx.enumid
        WHERE tr.status <> 10
        AND tr.trantype IN ('sales/quote', 'sales/order')

        $filter
        ORDER BY tr.tranno ASC
    ";

        // echo $temp;
        // exit();

        $sql = "SELECT * FROM ( $temp ) AS result";
        $sqlsub = "SELECT * FROM trandetail WHERE tranid = '" . $id . "'";
        $sqlcount = "SELECT COUNT(*) AS count FROM ( $temp ) AS result";
        $sqlcountsub = "SELECT COUNT(*) AS count FROM ( $sqlsub ) AS result";
    }
    public function getQueryPengambilan($id, $detailid, &$sql, &$sqlsub, &$sqlcount, &$sqlcountsub)
    {
        $filter = "";
        if ($id <> '') {
            $filter = " AND t.refid ='" . $id . "' ";
        }
        if ($detailid <> '') {
            $filter = " AND td.trandetailid ='" . $detailid . "' ";
        }

        $temp =
            "SELECT
            td.trandetailid,
            t.tranid,              
            t.refid AS order_id,   
            c.contact_name,
            c.jobcompany,
            c.contact_phone1,
            ord.term,
            ord.locations,
            ord.tranno,
            p.productname,
            td.description as description,
            
            STRING_AGG(DISTINCT COALESCE(ret.list_barcodes, ''), ', ') AS barcodes,      
            (COALESCE(u.enumtext_en, '')) AS unit,
            COALESCE(ret.total_returned, 0) as amount,

            CASE WHEN MAX(DATE(ord.withdrawaldate)) IS NULL THEN '-' ELSE
                (EXTRACT(DAY FROM MAX(ord.withdrawaldate))::text || ' ' || 
                CASE EXTRACT(MONTH FROM MAX(ord.withdrawaldate))
                    WHEN 1 THEN 'Januari' WHEN 2 THEN 'Februari' WHEN 3 THEN 'Maret'
                    WHEN 4 THEN 'April' WHEN 5 THEN 'Mei' WHEN 6 THEN 'Juni' 
                    WHEN 7 THEN 'Juli' WHEN 8 THEN 'Agustus' WHEN 9 THEN 'September' 
                    WHEN 10 THEN 'Oktober' WHEN 11 THEN 'November' WHEN 12 THEN 'Desember'
                END || ' ' || 
                EXTRACT(YEAR FROM MAX(DATE(ord.withdrawaldate)))::text)
            END as return_date,
            CASE WHEN MAX(DATE(ord.withdrawaldate)) IS NULL THEN '-' ELSE 
                TO_CHAR(MAX(DATE(ord.withdrawaldate)), 'HH24:MI') 
            END as return_time,
            COALESCE(MAX(inv.tranno), '-') AS invoice,
            CASE 
                WHEN EXTRACT(MONTH FROM ord.trandate) = EXTRACT(MONTH FROM ord.tranduedate) 
                    AND EXTRACT(YEAR FROM ord.trandate) = EXTRACT(YEAR FROM ord.tranduedate) THEN
                    '(' || EXTRACT(DAY FROM ord.trandate)::text || 
                    CASE WHEN (ord.tranduedate::date - ord.trandate::date) = 1 THEN ' & ' ELSE ' - ' END || 
                    EXTRACT(DAY FROM ord.tranduedate)::text || ' ' ||
                    CASE EXTRACT(MONTH FROM ord.trandate)
                        WHEN 1 THEN 'Januari' WHEN 2 THEN 'Februari' WHEN 3 THEN 'Maret' WHEN 4 THEN 'April'
                        WHEN 5 THEN 'Mei' WHEN 6 THEN 'Juni' WHEN 7 THEN 'Juli' WHEN 8 THEN 'Agustus'
                        WHEN 9 THEN 'September' WHEN 10 THEN 'Oktober' WHEN 11 THEN 'November' WHEN 12 THEN 'Desember'
                    END || ' ' || EXTRACT(YEAR FROM ord.trandate)::text || ')'
                WHEN EXTRACT(YEAR FROM ord.trandate) = EXTRACT(YEAR FROM ord.tranduedate) THEN
                    '(' || EXTRACT(DAY FROM ord.trandate)::text || ' ' ||
                    CASE EXTRACT(MONTH FROM ord.trandate)
                        WHEN 1 THEN 'Januari' WHEN 2 THEN 'Februari' WHEN 3 THEN 'Maret' WHEN 4 THEN 'April'
                        WHEN 5 THEN 'Mei' WHEN 6 THEN 'Juni' WHEN 7 THEN 'Juli' WHEN 8 THEN 'Agustus'
                        WHEN 9 THEN 'September' WHEN 10 THEN 'Oktober' WHEN 11 THEN 'November' WHEN 12 THEN 'Desember'
                    END || ' - ' || EXTRACT(DAY FROM ord.tranduedate)::text || ' ' ||
                    CASE EXTRACT(MONTH FROM ord.tranduedate)
                        WHEN 1 THEN 'Januari' WHEN 2 THEN 'Februari' WHEN 3 THEN 'Maret' WHEN 4 THEN 'April'
                        WHEN 5 THEN 'Mei' WHEN 6 THEN 'Juni' WHEN 7 THEN 'Juli' WHEN 8 THEN 'Agustus'
                        WHEN 9 THEN 'September' WHEN 10 THEN 'Oktober' WHEN 11 THEN 'November' WHEN 12 THEN 'Desember'
                    END || ' ' || EXTRACT(YEAR FROM ord.trandate)::text || ')'
                ELSE
                    '(' || EXTRACT(DAY FROM ord.trandate)::text || ' ' ||
                    CASE EXTRACT(MONTH FROM ord.trandate)
                        WHEN 1 THEN 'Januari' WHEN 2 THEN 'Februari' WHEN 3 THEN 'Maret' WHEN 4 THEN 'April'
                        WHEN 5 THEN 'Mei' WHEN 6 THEN 'Juni' WHEN 7 THEN 'Juli' WHEN 8 THEN 'Agustus'
                        WHEN 9 THEN 'September' WHEN 10 THEN 'Oktober' WHEN 11 THEN 'November' WHEN 12 THEN 'Desember'
                    END || ' ' || EXTRACT(YEAR FROM ord.trandate)::text || ' - ' ||
                    EXTRACT(DAY FROM ord.tranduedate)::text || ' ' ||
                    CASE EXTRACT(MONTH FROM ord.tranduedate)
                        WHEN 1 THEN 'Januari' WHEN 2 THEN 'Februari' WHEN 3 THEN 'Maret' WHEN 4 THEN 'April'
                        WHEN 5 THEN 'Mei' WHEN 6 THEN 'Juni' WHEN 7 THEN 'Juli' WHEN 8 THEN 'Agustus'
                        WHEN 9 THEN 'September' WHEN 10 THEN 'Oktober' WHEN 11 THEN 'November' WHEN 12 THEN 'Desember'
                    END || ' ' || EXTRACT(YEAR FROM ord.tranduedate)::text || ')'
            END as trandate
            
        FROM trandetails td
        LEFT JOIN trans t ON td.tranid = t.tranid
        LEFT JOIN trans ord ON t.refid = ord.tranid AND ord.trantype = 'sales/order'
        LEFT JOIN contacts c ON ord.contact_id = c.contact_id
        LEFT JOIN products p ON td.productid = p.productid
        LEFT JOIN enum u ON u.enumid = p.unitid AND u.enumtype = 'unit'
        LEFT JOIN trans inv ON inv.refid = ord.tranid AND inv.trantype = 'sales/invoice' AND inv.status <> 10 
        
        LEFT JOIN tranvariants del_items ON del_items.refid = t.tranid 
            AND del_items.productid = td.productid 
            AND del_items.type = '2' 
            AND del_items.status <> 10

        LEFT JOIN variants v ON v.productid = td.productid 
            AND (v.barcode = del_items.barcode OR v.asetno = del_items.barcode)

        LEFT JOIN (
            SELECT 
                tr.refid AS order_id_ref, 
                tv.productid,
                STRING_AGG(DISTINCT tv.barcode, ', ') AS list_barcodes,
                COUNT(DISTINCT tv.tranvariantid) AS total_returned
            FROM tranvariants tv
            LEFT JOIN trans tr ON tv.refid = tr.tranid
            WHERE tr.trantype IN ('sales/return', 'purchase/delivery')
            AND tr.status <> 10 AND tv.status <> 10
            AND tv.type = '3' 
            GROUP BY tr.refid, tv.productid
        ) AS ret ON ret.order_id_ref = t.refid AND ret.productid = td.productid
            
        WHERE t.status <> 10
        AND t.trantype = 'sales/delivery' 
        $filter
        GROUP BY t.tranid, t.refid, td.trandetailid, c.contact_name, c.jobcompany, c.contact_phone1, ord.term, ord.locations, ord.tranno,
            p.productname, p.description_product, ret.total_returned, u.enumtext_en, ord.trandate, ord.tranduedate, td.productid
        ORDER BY td.ord ASC
        ";

        $sql = "SELECT * FROM ( $temp ) AS result";
        $sqlsub = "SELECT * FROM trandetails WHERE tranid = '" . $id . "'";
        $sqlcount = "SELECT COUNT(*) AS count FROM ( $temp ) AS result";
        $sqlcountsub = "SELECT COUNT(*) AS count FROM ( $sqlsub ) AS result";
    }
    public function getQueryPurchase($id, $detailid, &$sql, &$sqlsub, &$sqlcount, &$sqlcountsub)
    {
        $filter = "";
        if ($id <> '') {
            $filter = " AND t.tranid ='" . $id . "' ";
        }
        if ($detailid <> '') {
            $filter = " AND t.trandetailid ='" . $detailid . "' ";
        }

        $temp =
            "SELECT
        t.trandetailid,
        tr.tranid,
        tr.tranno,
        COALESCE(ref.tranno, '') AS refernce_po,
        c.contact_no,
        c.contact_name,
        c.jobcompany,
        tr.trandate,
        
        CASE
            WHEN tr.eventtype = '0' THEN 'Milik'
            WHEN tr.eventtype = '1' THEN 'Pinjam'
            ELSE '-'
        END AS eventtype,
        
        p.productcode,
        p.productname,
        t.description,
        t.amount,
        t.price,
        t.itemsubtotaltax AS totalitem,
        t.itemdiscpersen,
        tx.enumtext_id AS tax,
        t.itemtotaltax,
        tr.subtotal,
        tr.disc,
        tr.otherdiscount,
        tr.totalafterdisc,
        
        CASE
            WHEN COALESCE(tr.ppnamount, 0) > 0
            THEN tr.ppnamount
        ELSE NULL
        END AS ppnamount,
        
        tr.grandtotal,
        
        CASE
            WHEN COALESCE(tr.ppnamount, 0) > 0 THEN 'PPN'
            ELSE NULL
        END AS ppn_status
        
        FROM trandetails t
        LEFT JOIN trans tr ON t.tranid = tr.tranid
        LEFT JOIN trans ref ON ref.tranid = tr.refid AND ref.trantype = 'purchase/request'
        LEFT JOIN contacts c ON tr.contact_id = c.contact_id
        LEFT JOIN products p ON t.productid = p.productid
        LEFT JOIN enum tx ON t.itemtaxid = tx.enumid
        WHERE tr.status <> 10
        AND tr.trantype = 'purchase/order'

            $filter
            ORDER BY tr.tranno ASC
            ";


        $sql = "SELECT * FROM ( $temp ) AS result";
        $sqlsub = "SELECT * FROM trandetail WHERE tranid = '" . $id . "'";
        $sqlcount = "SELECT COUNT(*) AS count FROM ( $temp ) AS result";
        $sqlcountsub = "SELECT COUNT(*) AS count FROM ( $sqlsub ) AS result";
    }
    public function getQueryOut($id, $detailid, &$sql, &$sqlsub, &$sqlcount, &$sqlcountsub)
    {
        $filter = "";
        if ($id <> '') {
            $filter .= " AND tr.tranid ='" . addslashes($id) . "' ";
        }
        if ($detailid <> '') {
            $filter .= " AND t.trandetailid ='" . addslashes($detailid) . "' ";
        }

        $temp =
            "SELECT
            tr.tranid,
            c.jobcompany,
            c.contact_phone1,
            tr.trandate,
            ref.locations AS locations,
            COALESCE(cdel.contact_name, '') AS crew_pengantaran,
            CASE
                WHEN ref.withdrawaldate::text = '' THEN NULL
                ELSE ref.withdrawaldate
            END AS withdrawaldate,
            p.productname,
            COALESCE(t.description, '') AS description,
            COALESCE(scan_del.list_asetnodeli, '-') AS asetnodeli,
            COALESCE(scan_del.total_sent, 0) AS total_sent

        FROM trandetails t
        INNER JOIN trans tr ON t.tranid = tr.tranid AND tr.trantype = 'sales/delivery' AND tr.status <> 10
        LEFT JOIN trans ref ON ref.tranid = tr.refid AND ref.trantype = 'sales/order'
        LEFT JOIN contacts c ON c.contact_id = ref.contact_id
        LEFT JOIN products p ON t.productid = p.productid
        LEFT JOIN contacts cdel ON cdel.contact_id = tr.contact_id

        LEFT JOIN (
            SELECT
                COUNT(DISTINCT tv.tranvariantid) AS total_sent,
                STRING_AGG(DISTINCT v.asetno, ', ' ORDER BY v.asetno ASC) AS list_asetnodeli,
                tv.refid,
                tv.productid
            FROM tranvariants tv
            LEFT JOIN variants v ON v.variantid = tv.variantid
            WHERE tv.type = '2' AND tv.status <> 10  
            GROUP BY tv.refid, tv.productid
        ) scan_del ON scan_del.refid = tr.tranid AND scan_del.productid = t.productid

        WHERE 1=1
        $filter
       
        GROUP BY
            tr.tranid, ref.locations, tr.trandate, ref.withdrawaldate,
            c.jobcompany, c.contact_phone1, tr.tranduedate, tr.tranno,
            tr.locations, p.productname, t.description, cdel.contact_name,
            scan_del.list_asetnodeli, scan_del.total_sent
    ";

        $sql = "SELECT * FROM ( $temp ) AS result";
        $sqlsub = "SELECT * FROM trandetails WHERE tranid = '" . addslashes($id) . "' AND status <> 10";
        $sqlcount = "SELECT COUNT(*) AS count FROM ( $temp ) AS result";
        $sqlcountsub = "SELECT COUNT(*) AS count FROM trandetails WHERE tranid = '" . addslashes($id) . "' AND status <> 10";
    }
    public function getQueryPenarikan($id, $detailid, &$sql, &$sqlsub, &$sqlcount, &$sqlcountsub, &$crew_pengembalian = null, &$crew_penarikan = null)
    {
        $filter = "";
        if ($id <> '') {
            $filter .= " AND t.tranid = '" . addslashes($id) . "' ";
        }

        if ($detailid <> '') {
            $filter .= " AND tdd.trandetailid = '" . addslashes($detailid) . "' ";
        }

        $temp =
            "SELECT
            t.withdrawaldate AS withdrawaldate,
            sd.trandate AS trandate,
            COALESCE(c.jobcompany, '') AS jobcompany,
            COALESCE(c.contact_phone1, '') AS contact_phone1,
            COALESCE(t.locations, '') AS locations,
            COALESCE(p.productname, '') AS productname,
            COALESCE(cdel.contact_name, '') AS crew_pengantaran,
            COALESCE(sr.description, '') AS description,
            COALESCE(sr_pd.condition, '-') AS condition,
            COALESCE(scan_del.total_sent, 0) AS total_sent,
            COALESCE(sr.total_return, 0) AS total_return,
            COALESCE(sr_pd.total_returnpd, 0) AS total_returnpd,
            COALESCE(scan_del.list_asetnodeli, '-') AS asetnodeli,
            COALESCE(sr.list_asetnokembali, '-') AS asetnokembali,
            COALESCE(sr_pd.list_asetnopenarikan, '-') AS asetnopenarikan

            FROM trans t
            LEFT JOIN contacts c ON t.contact_id = c.contact_id
            
            LEFT JOIN trans sd ON sd.refid = t.tranid AND sd.trantype = 'sales/delivery' AND sd.status <> 10
            LEFT JOIN trandetails tdd ON tdd.tranid = sd.tranid AND tdd.status <> 10
            LEFT JOIN products p ON tdd.productid = p.productid
            LEFT JOIN contacts cdel ON cdel.contact_id = sd.contact_id

            LEFT JOIN (
                SELECT
                    COUNT(DISTINCT tv.tranvariantid) AS total_sent,
                    STRING_AGG(DISTINCT v.asetno, ', ' ORDER BY v.asetno ASC) AS list_asetnodeli,
                    tv.refid,
                    tv.productid
                FROM tranvariants tv
                LEFT JOIN variants v ON v.variantid = tv.variantid
                WHERE tv.type = '2' AND tv.status <> 10   
                GROUP BY tv.refid, tv.productid 
            ) scan_del ON scan_del.refid = sd.tranid AND scan_del.productid = tdd.productid

            LEFT JOIN (
                SELECT 
                    r.refid,
                    tv.productid,
                    STRING_AGG(DISTINCT COALESCE(td_ret.description, ''), ', ') AS description,
                    COUNT(DISTINCT tv.tranvariantid) AS total_return,
                    STRING_AGG(DISTINCT v.asetno, ', ' ORDER BY v.asetno ASC) AS list_asetnokembali
                FROM trans r
                INNER JOIN tranvariants tv ON tv.refid = r.tranid AND tv.status <> 10 AND tv.type = '3'
                LEFT JOIN trandetails td_ret ON td_ret.tranid = r.tranid AND td_ret.productid = tv.productid AND td_ret.status <> 10
                LEFT JOIN variants v ON v.variantid = tv.variantid AND v.status <> 10    
                WHERE r.trantype = 'sales/return' AND r.status <> 10  
                GROUP BY r.refid, tv.productid
            ) sr ON sr.refid = t.tranid AND sr.productid = tdd.productid

            LEFT JOIN (
                SELECT 
                    r.refid,
                    tv.productid,
                    COUNT(DISTINCT tv.tranvariantid) AS total_returnpd,
                    STRING_AGG(DISTINCT v.asetno, ', ' ORDER BY v.asetno ASC) AS list_asetnopenarikan,
                    STRING_AGG(DISTINCT COALESCE(vcond.enumtext_en, '-'), ', ') AS condition
                FROM trans r
                INNER JOIN tranvariants tv ON tv.refid = r.tranid AND tv.status <> 10 AND tv.type = '3'
                LEFT JOIN variants v ON v.variantid = tv.variantid AND v.status <> 10    
                LEFT JOIN enum vcond ON vcond.enumid = v.condition AND vcond.enumtype = 'condition'
                WHERE r.trantype = 'purchase/delivery' AND r.reftype = 'sales/order' AND r.status <> 10  
                GROUP BY r.refid, tv.productid
            ) sr_pd ON sr_pd.refid = t.tranid AND sr_pd.productid = tdd.productid
            
            WHERE t.status <> 10
            $filter
            
            GROUP BY t.tranid, t.withdrawaldate, c.jobcompany, c.contact_phone1, sd.trandate, sd.tranid, sr_pd.condition,
                t.locations, p.productname, cdel.contact_name, sr_pd.total_returnpd, sr_pd.list_asetnopenarikan,
                sr.description, sr.total_return, sr.list_asetnokembali, scan_del.total_sent, scan_del.list_asetnodeli
            ";

        $q_pengembalian = "SELECT contact_name FROM contacts WHERE contact_id =
            (SELECT contact_id FROM trans WHERE refid = '" . addslashes($id) . "' AND trantype = 'sales/return' AND status <> 10 LIMIT 1)";

        $q_penarikan = "SELECT contact_name FROM contacts WHERE contact_id =
            (SELECT contact_id FROM trans WHERE refid = '" . addslashes($id) . "' AND trantype = 'purchase/delivery' AND status <> 10 LIMIT 1)";

        $sql = "SELECT * FROM ( $temp ) AS result";
        $sqlsub = "SELECT * FROM trandetails WHERE tranid = '" . addslashes($id) . "' AND status <> 10";
        $sqlcount = "SELECT COUNT(*) AS count FROM ( $temp ) AS result";
        $sqlcountsub = "SELECT COUNT(*) AS count FROM trandetails WHERE tranid = '" . addslashes($id) . "' AND status <> 10";
        $crew_pengembalian = Yii::$app->db->createCommand($q_pengembalian)->queryScalar() ?: '';
        $crew_penarikan = Yii::$app->db->createCommand($q_penarikan)->queryScalar() ?: '';

    }
    public function getQuerySurat($id, $detailid, &$sql, &$sqlsub, &$sqlcount, &$sqlcountsub)
    {
        $filter = "";
        if ($id <> '') {
            $filter .= " AND t.tranid ='" . $id . "' ";
        }
        if ($detailid <> '') {
            $filter .= " AND td.trandetailid ='" . $detailid . "' ";
        }

        $temp =
            "SELECT 
            td.trandetailid,
            t.tranid,
            t.tranno,
            COALESCE(delivery.total_sent, 0) AS amount_sent,
            COALESCE(return1.total_return1, 0) AS amount_return1,
            COALESCE(return1.description, '') AS description,
            COALESCE(return2.total_return2, 0) AS amount_return2,
            c.jobcompany,
            c.contact_phone1,
            t.trandate,
            t.tranduedate,
            t.locations,
            CASE 
                WHEN t.withdrawaldate::text = '' THEN NULL 
                ELSE t.withdrawaldate 
            END AS withdrawaldate,
            p.productcode,
            p.productname,
            COALESCE(aset_delivery.list_asetnodeli, '-') AS asetno_delivery,
            COALESCE(aset_return1.list_asetnoreturn1, '-') AS asetno_return1,
            COALESCE(aset_return2.list_asetnoreturn2, '-') AS asetno_return2,
            COALESCE(aset_return2.list_condition, '-') AS condition,
            MAX(td.amount) AS amount

        FROM trandetails td
        INNER JOIN trans t ON td.tranid = t.tranid
        LEFT JOIN contacts c ON c.contact_id = t.contact_id
        LEFT JOIN products p ON td.productid = p.productid 

        LEFT JOIN (
            SELECT tr.refid, trd.productid, SUM(COALESCE(trd.amount, 0) + COALESCE(trd.amount2, 0)) as total_sent
            FROM trans tr
            JOIN trandetails trd ON tr.tranid = trd.tranid
            WHERE tr.trantype = 'sales/delivery' AND tr.status <> 10
            GROUP BY tr.refid, trd.productid
        ) delivery ON delivery.refid = t.tranid AND delivery.productid = td.productid

        LEFT JOIN (
            SELECT tr.refid, trd.productid, trd.description, SUM(COALESCE(trd.amount, 0) + COALESCE(trd.amount2, 0)) as total_return1
            FROM trans tr
            JOIN trandetails trd ON tr.tranid = trd.tranid
            WHERE tr.trantype = 'sales/return' 
            AND tr.eventtype ='0'
            AND tr.status <> 10
            GROUP BY tr.refid, trd.productid, trd.description
        ) return1 ON return1.refid = t.tranid AND return1.productid = td.productid

        LEFT JOIN (
            SELECT tr.refid, trd.productid, SUM(COALESCE(trd.amount, 0) + COALESCE(trd.amount2, 0)) as total_return2
            FROM trans tr
            JOIN trandetails trd ON tr.tranid = trd.tranid
            WHERE tr.trantype = 'sales/return' 
            AND tr.eventtype ='1'
            AND tr.status <> 10
            GROUP BY tr.refid, trd.productid
        ) return2 ON return2.refid = t.tranid AND return2.productid = td.productid

        LEFT JOIN (
            SELECT 
                tr.refid, trd.productid,
                STRING_AGG(DISTINCT v.asetno, ', ' ORDER BY v.asetno) AS list_asetnodeli
            FROM trans tr
            JOIN trandetails trd ON tr.tranid = trd.tranid
            JOIN tranvariants tv ON tv.trandetailid = trd.trandetailid
            JOIN variants v ON v.variantid = tv.variantid
            WHERE tr.status <> 10 AND tv.type ='2'
            AND tr.trantype = 'sales/delivery'
            GROUP BY tr.refid, trd.productid
        ) aset_delivery ON aset_delivery.refid = t.tranid AND aset_delivery.productid = td.productid

        LEFT JOIN (
            SELECT 
                tr.refid, trd.productid,
                STRING_AGG(DISTINCT v.asetno, ', ' ORDER BY v.asetno) AS list_asetnoreturn1
            FROM trans tr
            JOIN trandetails trd ON tr.tranid = trd.tranid
            JOIN tranvariants tv ON tv.trandetailid = trd.trandetailid
            JOIN variants v ON v.variantid = tv.variantid
            WHERE tr.status <> 10 AND tv.type ='3' AND tr.eventtype ='0'
            GROUP BY tr.refid, trd.productid
        ) aset_return1 ON aset_return1.refid = t.tranid AND aset_return1.productid = td.productid

        LEFT JOIN (
            SELECT 
                tr.refid, trd.productid,
                STRING_AGG(DISTINCT v.asetno, ', ' ORDER BY v.asetno) AS list_asetnoreturn2,
                STRING_AGG(DISTINCT e.enumtext_en, ', ' ORDER BY e.enumtext_en) AS list_condition
            FROM trans tr
            JOIN trandetails trd ON tr.tranid = trd.tranid
            JOIN tranvariants tv ON tv.trandetailid = trd.trandetailid
            JOIN variants v ON v.variantid = tv.variantid
            LEFT JOIN enum e ON e.enumid = tv.condition AND e.enumtype = 'condition'
            WHERE tr.status <> 10 AND tv.type ='3' AND tr.eventtype ='1'
            GROUP BY tr.refid, trd.productid
        ) aset_return2 ON aset_return2.refid = t.tranid AND aset_return2.productid = td.productid

        WHERE t.status <> 10
        AND t.trantype = 'sales/order'

        $filter
        GROUP BY 
        td.trandetailid, t.tranid, t.tranno, c.jobcompany, c.contact_phone1, t.trandate, t.tranduedate,
        t.locations, t.withdrawaldate, p.productcode, p.productname, aset_delivery.list_asetnodeli, 
        aset_return1.list_asetnoreturn1, aset_return2.list_asetnoreturn2, aset_return2.list_condition, 
        delivery.total_sent, return1.total_return1, return1.description, return2.total_return2 
        ORDER BY t.tranno ASC
        ";
        // echo $temp;exit();

        $sql = "SELECT * FROM ( $temp ) AS result";
        // echo $sql;exit();
        $sqlsub = "SELECT * FROM trandetails WHERE tranid = '" . $id . "'";
        $sqlcount = "SELECT COUNT(*) AS count FROM ( $temp ) AS result";
        $sqlcountsub = "SELECT COUNT(*) AS count FROM ( $sqlsub ) AS result";
    }
    public function getQuerySerah($id, $detailid, &$sql, &$sqlsub, &$sqlcount, &$sqlcountsub)
    {
        $filter = "";
        if ($id <> '') {
            $filter = " AND ord.tranid ='" . $id . "' ";
        }
        if ($detailid <> '') {
            $filter = " AND td.trandetailid ='" . $detailid . "' ";
        }

        $temp =
            "SELECT
            td.trandetailid,
            ord.tranid,   
            c.contact_name,
            c.jobcompany,
            c.contact_phone2,
            ord.term,
            ord.tranno,
            ord.locations,
            p.productname,
            td.description as description,
            STRING_AGG(DISTINCT COALESCE(ret.barcode, v.asetno), ', ') AS barcodes,      
            COALESCE(u.enumtext_en, '') AS unit,
            COALESCE(td.amount, 0) as amount,
            CASE WHEN MAX(DATE(t.createdat)) IS NULL THEN '-' ELSE
                (EXTRACT(DAY FROM MAX(t.createdat))::text || ' ' || 
                CASE EXTRACT(MONTH FROM MAX(t.createdat))
                    WHEN 1 THEN 'Januari' WHEN 2 THEN 'Februari' WHEN 3 THEN 'Maret'
                    WHEN 4 THEN 'April' WHEN 5 THEN 'Mei' WHEN 6 THEN 'Juni' 
                    WHEN 7 THEN 'Juli' WHEN 8 THEN 'Agustus' WHEN 9 THEN 'September' 
                    WHEN 10 THEN 'Oktober' WHEN 11 THEN 'November' WHEN 12 THEN 'Desember'
                END || ' ' || 
                EXTRACT(YEAR FROM MAX(DATE(t.createdat)))::text)
            END as return_date,
            CASE WHEN MAX(t.createdat) IS NULL THEN '-' ELSE 
                TO_CHAR(MAX(t.createdat), 'HH24:MI') 
            END as return_time,
            CASE WHEN MAX(DATE(ord.withdrawaldate)) IS NULL THEN '-' ELSE
                (EXTRACT(DAY FROM MAX(ord.withdrawaldate))::text || ' ' || 
                CASE EXTRACT(MONTH FROM MAX(ord.withdrawaldate))
                    WHEN 1 THEN 'Januari' WHEN 2 THEN 'Februari' WHEN 3 THEN 'Maret'
                    WHEN 4 THEN 'April' WHEN 5 THEN 'Mei' WHEN 6 THEN 'Juni' 
                    WHEN 7 THEN 'Juli' WHEN 8 THEN 'Agustus' WHEN 9 THEN 'September' 
                    WHEN 10 THEN 'Oktober' WHEN 11 THEN 'November' WHEN 12 THEN 'Desember'
                END || ' ' || 
                EXTRACT(YEAR FROM MAX(DATE(ord.withdrawaldate)))::text)
            END as withdrawal_date,
            CASE WHEN MAX(ord.withdrawaldate) IS NULL THEN '-' ELSE 
                TO_CHAR(MAX(ord.withdrawaldate), 'HH24:MI') 
            END as withdrawal_time,
            CASE 
                WHEN EXTRACT(MONTH FROM ord.trandate) = EXTRACT(MONTH FROM ord.tranduedate) 
                     AND EXTRACT(YEAR FROM ord.trandate) = EXTRACT(YEAR FROM ord.tranduedate) THEN
                    '(' || EXTRACT(DAY FROM ord.trandate)::text || 
                    CASE WHEN (ord.tranduedate::date - ord.trandate::date) = 1 THEN ' & ' ELSE ' - ' END || 
                    EXTRACT(DAY FROM ord.tranduedate)::text || ' ' ||
                    CASE EXTRACT(MONTH FROM ord.trandate)
                        WHEN 1 THEN 'Januari' WHEN 2 THEN 'Februari' WHEN 3 THEN 'Maret' WHEN 4 THEN 'April'
                        WHEN 5 THEN 'Mei' WHEN 6 THEN 'Juni' WHEN 7 THEN 'Juli' WHEN 8 THEN 'Agustus'
                        WHEN 9 THEN 'September' WHEN 10 THEN 'Oktober' WHEN 11 THEN 'November' WHEN 12 THEN 'Desember'
                    END || ' ' || EXTRACT(YEAR FROM ord.trandate)::text || ')'

                WHEN EXTRACT(YEAR FROM ord.trandate) = EXTRACT(YEAR FROM ord.tranduedate) THEN
                    '(' || EXTRACT(DAY FROM ord.trandate)::text || ' ' ||
                    CASE EXTRACT(MONTH FROM ord.trandate)
                        WHEN 1 THEN 'Januari' WHEN 2 THEN 'Februari' WHEN 3 THEN 'Maret' WHEN 4 THEN 'April'
                        WHEN 5 THEN 'Mei' WHEN 6 THEN 'Juni' WHEN 7 THEN 'Juli' WHEN 8 THEN 'Agustus'
                        WHEN 9 THEN 'September' WHEN 10 THEN 'Oktober' WHEN 11 THEN 'November' WHEN 12 THEN 'Desember'
                    END || ' - ' || EXTRACT(DAY FROM ord.tranduedate)::text || ' ' ||
                    CASE EXTRACT(MONTH FROM ord.tranduedate)
                        WHEN 1 THEN 'Januari' WHEN 2 THEN 'Februari' WHEN 3 THEN 'Maret' WHEN 4 THEN 'April'
                        WHEN 5 THEN 'Mei' WHEN 6 THEN 'Juni' WHEN 7 THEN 'Juli' WHEN 8 THEN 'Agustus'
                        WHEN 9 THEN 'September' WHEN 10 THEN 'Oktober' WHEN 11 THEN 'November' WHEN 12 THEN 'Desember'
                    END || ' ' || EXTRACT(YEAR FROM ord.trandate)::text || ')'

                ELSE
                    '(' || EXTRACT(DAY FROM ord.trandate)::text || ' ' ||
                    CASE EXTRACT(MONTH FROM ord.trandate)
                        WHEN 1 THEN 'Januari' WHEN 2 THEN 'Februari' WHEN 3 THEN 'Maret' WHEN 4 THEN 'April'
                        WHEN 5 THEN 'Mei' WHEN 6 THEN 'Juni' WHEN 7 THEN 'Juli' WHEN 8 THEN 'Agustus'
                        WHEN 9 THEN 'September' WHEN 10 THEN 'Oktober' WHEN 11 THEN 'November' WHEN 12 THEN 'Desember'
                    END || ' ' || EXTRACT(YEAR FROM ord.trandate)::text || ' - ' ||
                    EXTRACT(DAY FROM ord.tranduedate)::text || ' ' ||
                    CASE EXTRACT(MONTH FROM ord.tranduedate)
                        WHEN 1 THEN 'Januari' WHEN 2 THEN 'Februari' WHEN 3 THEN 'Maret' WHEN 4 THEN 'April'
                        WHEN 5 THEN 'Mei' WHEN 6 THEN 'Juni' WHEN 7 THEN 'Juli' WHEN 8 THEN 'Agustus'
                        WHEN 9 THEN 'September' WHEN 10 THEN 'Oktober' WHEN 11 THEN 'November' WHEN 12 THEN 'Desember'
                    END || ' ' || EXTRACT(YEAR FROM ord.tranduedate)::text || ')'
            END as trandate
            
        FROM trandetails td
        LEFT JOIN trans ord ON td.tranid = ord.tranid AND ord.trantype = 'sales/order'
        LEFT JOIN trans t ON t.refid = ord.tranid AND t.trantype = 'sales/delivery' AND t.status <> 10
        LEFT JOIN contacts c ON ord.contact_id = c.contact_id
        LEFT JOIN products p ON td.productid = p.productid
        LEFT JOIN enum u ON u.enumid = p.unitid AND u.enumtype = 'unit'
        LEFT JOIN trans inv ON inv.refid = ord.tranid AND inv.trantype = 'sales/invoice' AND inv.status <> 10 
        
        LEFT JOIN tranvariants del_items ON del_items.refid = t.tranid 
            AND del_items.productid = td.productid 
            AND del_items.type = '2' 
            AND del_items.status <> 10

        LEFT JOIN variants v ON v.productid = td.productid 
            AND (v.barcode = del_items.barcode OR v.asetno = del_items.barcode)

        LEFT JOIN (
            SELECT 
                tr.refid AS order_id_ref, 
                tv.productid,
                tv.barcode
            FROM tranvariants tv
            LEFT JOIN trans tr ON tv.refid = tr.tranid
            WHERE tr.trantype = 'sales/delivery' 
              AND tr.status <> 10 AND tv.status <> 10
              AND tv.type = '2'
        ) AS ret ON ret.order_id_ref = ord.tranid 
            AND ret.productid = td.productid 

        LEFT JOIN (
            SELECT
                tr_del.refid AS order_id_ref,
                tv.productid,
                COUNT(DISTINCT tv.barcode) AS total_sent
            FROM tranvariants tv
            LEFT JOIN trans tr_del ON tv.refid = tr_del.tranid
            WHERE tv.type = '2' AND tv.status <> 10 AND tr_del.trantype = 'sales/delivery' AND tr_del.status <> 10
            GROUP BY tr_del.refid, tv.productid
        ) as delivery ON delivery.order_id_ref = ord.tranid AND delivery.productid = td.productid
        
        WHERE ord.status <> 10
        AND ord.trantype = 'sales/order' 
        $filter
        GROUP BY ord.tranid, td.trandetailid, c.contact_name, c.jobcompany, c.contact_phone2, ord.term, ord.locations,
            p.productname, u.enumtext_en, ord.trandate, ord.tranduedate, td.productid, delivery.total_sent, td.description
        ORDER BY td.ord ASC
    ";

        $sql = "SELECT * FROM ( $temp ) AS result";
        $sqlsub = "SELECT * FROM trandetails WHERE tranid = '" . $id . "'";
        $sqlcount = "SELECT COUNT(*) AS count FROM ( $temp ) AS result";
        $sqlcountsub = "SELECT COUNT(*) AS count FROM ( $sqlsub ) AS result";
    }

}
