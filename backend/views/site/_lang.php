<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;
?>
<div class="m-0">
    <!--begin::Toggle-->
    
    <button class="btn btn-flex btn-link rotate" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
        <?php
        $icon = "united-states";
        $languange = "English";

        if (Yii::$app->language  == "id") {
            $icon = "indonesia";
            $languange = "Indonesia";
        } ?>
        <img data-kt-element="current-lang-flag" class="w-25px h-25px rounded-circle me-3"
            src="<?php echo Yii::$app->getUrlManager()->getBaseUrl() ?>/assets/media/flags/<?= $icon ?>.svg" alt="" />
        <span class="me-2"><?= $languange ?></span>

        <i class="ki-duotone ki-down fs-2 text-muted rotate-180 m-0"></i>
    </button>
    <!--end::Toggle-->
    <!--begin::Menu-->
    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-4" data-kt-menu="true">
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            <a href="<?= Url::to(['site/setlang', 'lang' => 'en']) ?>" class="menu-link d-flex px-5" data-kt-lang="English">
                <span class="symbol symbol-20px me-4">
                    <img data-kt-element="lang-flag" class="rounded-1" src="<?php echo Yii::$app->getUrlManager()->getBaseUrl() ?>/assets/media/flags/united-states.svg" alt="" />
                </span>
                <span data-kt-element="lang-name">English</span>
            </a>
        </div>
        <!--end::Menu item-->
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            <a href="<?= Url::to(['site/setlang', 'lang' => 'id']) ?>" class="menu-link d-flex px-5" data-kt-lang="Indonesia">
                <span class="symbol symbol-20px me-4">
                    <img data-kt-element="lang-flag" class="rounded-1" src="<?php echo Yii::$app->getUrlManager()->getBaseUrl() ?>/assets/media/flags/indonesia.svg" alt="" />
                </span>
                <span data-kt-element="lang-name">Indonesia</span>
            </a>
        </div>

        <!--end::Menu item-->
    </div>
    <!--end::Menu-->
</div>