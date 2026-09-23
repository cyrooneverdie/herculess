<?php

/** @var \yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use common\models\Company;
use common\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;

$controllerId = Yii::$app->controller->id;
$actionId = Yii::$app->controller->action->id;

$userId = Yii::$app->user->id;
$sql = "SELECT COUNT(*) FROM company WHERE userid = :userId";
$userHasCompany = Yii::$app->db->createCommand($sql)
    ->bindValue(':userId', $userId)
    ->queryScalar() > 0; // Hasilnya TRUE kalau ada company

$hideModalPages = ($controllerId === 'company' && ($actionId === 'index' || $actionId === 'create'));

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <!-- <title><?php //echo Html::encode($this->title) 
    ?></title> -->
    <title><?= $this->title ?></title>
    <link rel="canonical" href="http://app.clloo.com" />
    <link rel="shortcut icon" href="<?= Yii::getAlias('@web') . "/assets/media/logos/rbg.png" ?>" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!-- <link href="metronic/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" /> -->
    <link href="<?= Yii::getAlias('@web') ?>/metronic/assets/plugins/custom/datatables/datatables.bundle.css"
        rel="stylesheet" type="text/css" />
    <link href="<?= Yii::getAlias('@web') ?>/metronic/assets/plugins/global/plugins.bundle.css" rel="stylesheet"
        type="text/css" />
    <!-- <link href="metronic/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />  -->

    <!--end::Fonts-->
    <?php $this->head() ?>
</head>



<?php $this->beginBody() ?>

<body id="kt_app_body" data-kt-app-layout="light-sidebar" data-kt-app-header-fixed="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default"
    data-kt-app-sidebar-minimize="on">

    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            <?= $this->render('_header.php') ?>
            <!--begin::Wrapper-->

            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">

                <!--begin::Sidebar-->
                <?php echo $this->render("_sidebar") ?>
                <!--end::Sidebar-->
                <!--begin::Main-->

                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <!--begin::Content wrapper-->
                    <div class="d-flex flex-column flex-column-fluid">

                        <!--end::Toolbar-->
                        <!--begin::Content-->
                        <div class="app-content flex-column-fluid">
                            <div class="app-container utama">

                                <?= $content ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!--end:::Main-->
                <!--end::Content wrapper-->
                <!--begin::Footer-->
                <?php echo $this->render("_footer") ?>
                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::App-->
    <!--begin::Drawers-->
    <!--begin::Activities drawer-->
    <?php //echo $this->render("_drawer") 
    ?>
    <!--end::Chat drawer-->
    <!--end::Drawers-->
    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true"></div>
    <!--end::Scrolltop-->
</body>
<?php $this->endBody() ?>

</html>
<?php $this->endPage();

