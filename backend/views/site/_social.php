<?php

use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\authclient\widgets\AuthChoice;
?>
<div class="d-flex align-items-center">
    
    <div class="text-gray-500 fw-semibold fs-6 me-3 me-md-6" data-kt-translate="general-or">Or</div>
    <!--begin::Symbol-->
    <a href="<?= Url::to(['site/auth', 'authclient' => 'google']) ?>" class="symbol symbol-circle symbol-45px w-45px bg-light me-3">
        <img alt="Logo" src="<?php echo Yii::$app->getUrlManager()->getBaseUrl() ?>/assets/media/svg/brand-logos/google-icon.svg" class="p-4" />
    </a>
    <!--end::Symbol-->
    <!--begin::Symbol-->
    <a href="<?= Url::to(['site/auth', 'authclient' => 'facebook']) ?>" class="symbol symbol-circle symbol-45px w-45px bg-light me-3">
        <img alt="Logo" src="<?php echo Yii::$app->getUrlManager()->getBaseUrl() ?>/assets/media/svg/brand-logos/facebook-3.svg" class="p-4" />
    </a>
    <!--end::Symbol-->
    <!--begin::Symbol-->
    <!-- <a href="#" class="symbol symbol-circle symbol-45px w-45px bg-light">
                            <img alt="Logo" src="<?php echo Yii::$app->getUrlManager()->getBaseUrl() ?>/assets/media/svg/brand-logos/apple-black.svg" class="theme-light-show p-4" />
                            <img alt="Logo" src="<?php echo Yii::$app->getUrlManager()->getBaseUrl() ?>/assets/media/svg/brand-logos/apple-black-dark.svg" class="theme-dark-show p-4" />
                        </a> -->
    <!--end::Symbol-->
  
</div>  