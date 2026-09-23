<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use common\models\Company;

class BaseController extends Controller
{
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        // Daftar route yang diizinkan walau tglendsubs lewat
        $whitelistRoutes = [
            'site/logout',
            'users/billing',  // Halaman billing tentunya
            'users/changebilling',  // Halaman billing tentunya
            'users/softdelete',  // Halaman billing tentunya
        ];
        $billing = Company::findOne(['companyid' => Yii::$app->session->get('companyid')]);
        // Cek apakah user sudah login dan punya tanggal akhir langganan
        if (!Yii::$app->user->isGuest && $billing) {
            $now = new \DateTime();
            $end = new \DateTime($billing->tglendsubs);

            // Cek apakah sekarang sudah melewati tanggal akhir
            if ($now > $end) {
                $currentRoute = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
                if (!in_array($currentRoute, $whitelistRoutes)) {
                    Yii::$app->session->setFlash('billing_expired', Yii::$app->lang->t('message', 'mes1'));
                    return $this->redirect(Url::to(['users/billing']));
                }
            }
        }

        return true;
    }
}
