<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
// Tambahkan juga model yang dibutuhkan:
use common\models\Company;
use common\models\Subscription;
use common\models\Tran;
use common\models\TranDetail;


class BillingController extends Controller
{
   /**
 * Dashboard / Billing Index
 * Menyatukan seluruh proses pemilihan company dan pembuatan subscription
 * di actionIndex agar jadi halaman utama modul Users.
 */public function actionIndex($companyid = null)
{
    $userId = Yii::$app->user->id;
    $session = Yii::$app->session;

    // 1. Refresh daftar perusahaan milik user
    $session->remove('company_list');
    $data = Yii::$app->db->createCommand("
        SELECT * FROM company 
        WHERE userid = :userid AND status = 1
    ")->bindValue(':userid', $userId)->queryAll();
    $session->set('company_list', $data);

    // 2. Tentukan companyid default bila kosong
    if (!$companyid) {
        $companyid = Yii::$app->db->createCommand("
            SELECT companyid FROM users WHERE userid = :userid
        ")->bindValue(':userid', $userId)->queryScalar();
    }

    // 3. Jika companyid dipilih, simpan ke session dan database
    if ($companyid) {
        $company = Yii::$app->db->createCommand("
            SELECT * FROM company 
            WHERE userid = :userid AND companyid = :companyid
        ")->bindValues([':userid' => $userId, ':companyid' => $companyid])->queryOne();

        if ($company) {
            $session->set('companyid',  $company['companyid']);
            $session->set('company_data', $company);

            Yii::$app->db->createCommand("
                UPDATE users SET companyid = :companyid WHERE userid = :userid
            ")->bindValues([':companyid' => $company['companyid'], ':userid' => $userId])->execute();
        }

        if (Yii::$app->request->get('companyid')) {
            return $this->redirect(['users/index']);
        }
    }

    // 4. Pastikan session companyid ada
    if (!$session->has('companyid') && $companyid) {
        $company = Yii::$app->db->createCommand("
            SELECT * FROM company WHERE companyid = :companyid
        ")->bindValue(':companyid', $companyid)->queryOne();

        if ($company) {
            $session->set('companyid',  $company['companyid']);
            $session->set('company_data', $company);
        }
    }

    // 5. Proses form subscription
    $paket      = Yii::$app->db->createCommand("SELECT * FROM subscriptions")->queryAll();
    $modelsubs  = Company::findOne(['companyid' => $companyid]);

    if (Yii::$app->request->isPost) {
        $paketdata   = Subscription::findOne(['subs_id' => Yii::$app->request->post('package')]);

        $contract     = Yii::$app->request->post('contract')      ?? '0';
        $total        = Yii::$app->request->post('total_price')   ?? $paketdata->total;
        $ppn          = Yii::$app->request->post('ppn');
        $discpercent  = Yii::$app->request->post('persendisc')    ?? '0';
        $base         = Yii::$app->request->post('harga');
        $disc         = Yii::$app->request->post('itemdisc')      ?? '0';

        $monyear = ($paketdata->tipe === 'mon')
            ? ($contract !== '1' ? $contract . ' months' : '1 month')
            : ($contract !== '1' ? $contract . ' years'  : '1 year');

        $tx = Yii::$app->db->beginTransaction();
        try {
            $modelsubs->setAttributes([
                'subs_id'      => $paketdata->subs_id,
                'package'      => $paketdata->package_name,
                'tipe'         => $paketdata->tipe,
                'subsprice'    => $total,
                'tglstartsubs' => date('Y-m-d H:i:s'),
                'tglendsubs'   => date('Y-m-d H:i:s', strtotime('+' . $monyear)),
                'subs_status'  => 1,
                'statuspaid'   => 'unpaid',
            ], false);

            if (!$modelsubs->save(false)) {
                throw new \Exception("Gagal simpan subscription: " . json_encode($modelsubs->getErrors()));
            }

            $modeltran = new Tran([
                'trandate'    => $modelsubs->tglstartsubs,
                'tranduedate' => $modelsubs->tglendsubs,
                'trantype'    => 'subscription',
                'tranno'      => 'SUBS',
                'subtotal'    => $total,
                'status'      => 1,
                'createdby'   => $userId,
                'companyid'   => $modelsubs->companyid,
            ]);
            if (!$modeltran->save(false)) {
                throw new \Exception("Gagal simpan transaksi: " . json_encode($modeltran->getErrors()));
            }

            $modeltrandetail = new TranDetail([
                'startdate'       => $modelsubs->tglstartsubs,
                'enddate'         => $modelsubs->tglendsubs,
                'itemtype'        => $modelsubs->package,
                'tranid'          => $modeltran->tranid,
                'itemprice'       => $base,
                'harga'           => $base,
                'itemsubtotal'    => $base,
                'itemtotal'       => $total,
                'jumlah'          => $contract,
                'itemtax'         => $ppn,
                'itemdisc'        => $disc,
                'itemdiscpersen'  => $discpercent,
                'status'          => 1,
            ]);
            if (!$modeltrandetail->save(false)) {
                throw new \Exception("Gagal simpan detil transaksi: " . json_encode($modeltrandetail->getErrors()));
            }

            $tx->commit();

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['success' => true, 'pesan' => Yii::$app->lang->t('message', 'mes2')];
            }

            Yii::$app->session->setFlash('subssuccess', Yii::$app->lang->t('message', 'mes2'));
            return $this->redirect(['users/profile', 'userid' => $userId]);

        } catch (\Throwable $e) {
            $tx->rollBack();
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['success' => false, 'pesan' => $e->getMessage()];
            }
        }
    }

    // 6. Ambil data histories
    $histories = Yii::$app->db->createCommand("
        SELECT t.*, c.package, c.type, c.tglstartsubs, c.subs_status, t.statuspaid, td.itemtype 
        FROM trans t
        LEFT JOIN company c ON c.companyid = t.companyid
        LEFT JOIN trandetails td ON td.tranid = t.tranid
        WHERE t.createdby = :userid AND t.status = 1
        ORDER BY t.trandate DESC
    ")->bindValue(':userid', $userId)->queryAll();

    // 7. Render view
    $view = Yii::$app->request->isAjax ? 'renderPartial' : 'render';
    return $this->$view('index', [
        'modelsubs' => $modelsubs,
        'session'   => $session,
        'paket'     => $paket,
        'histories' => $histories, // dikirim ke view
    ]);
}


}
