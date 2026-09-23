<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'Reset Password';
?>

<!--begin::Aside-->
<div class="d-flex flex-column flex-column-fluid flex-center w-lg-50 p-10">
    <!--begin::Wrapper-->
    <div class="d-flex justify-content-between flex-column-fluid flex-column w-100 mw-450px">
        <!--begin::Header-->
        <div class="d-flex flex-stack py-2">
            <!--begin::Back link-->
            <!-- <div class="me-2">
            </div> -->
            <!--end::Back link-->
            <!--begin::Sign Up link-->
            
            <!--end::Sign Up link=-->   
        </div>
        <!--end::Header-->
        <!--begin::Body-->

        <div class="py-20">
            <!--begin::Form-->
            <?php $form = ActiveForm::begin([
                'id' => 'signin-form',
                'method' => 'post',
                'validateOnType' => false,
                'validateOnChange' => false,
                'validatingCssClass' => '',
                'class' => 'form w-100',
                'requiredCssClass' => '',
                'options' => [
                    'enctype' => 'multipart/form-data',
                    'data-pjax' => true,
                ],
                'fieldConfig' => [
                    'errorOptions' => [
                        'tag'   => 'div',
                        'class' => 'fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback',
                    ],
                    'options' => [
                        'class' => 'fv-row mb-8',
                    ],
                    'horizontalCssClasses' => [
                        'label' => '',
                        'offset' => '',
                        'wrapper' => '',
                        'error' => 'text-center',
                        'hint' => '',
                    ],
                ]

            ]); ?>
            <!-- <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" data-kt-redirect-url="index.html" action="#"> -->
            <!--begin::Body-->
            <div class="card-body">
                <!--begin::Heading-->
               
                <?php


//var_dump(Yii::$app->session->getFlash('error'));exit;
    if (Yii::$app->session->hasFlash('success')){
    ?>
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mt-10 mb-10" role="alert">
        <div class="me-3">
            <i class="fas fa-exclamation-triangle fa-lg text-success"></i>
        </div>
        <div><?=  Yii::$app->session->getFlash('success') ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  

    <?php
    }else if (Yii::$app->session->hasFlash('error')){
    ?>
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mt-10 mb-10" role="alert">
        <div class="me-3">
            <i class="fas fa-exclamation-triangle fa-lg text-danger"></i>
        </div>
        <div><?=  Yii::$app->session->getFlash('error') ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php } ?>
                <!--begin::Heading-->
                <!--begin::Input group=-->
                <!-- <div class="fv-row mb-8"> -->
                <!--begin::Email-->
              

                <!-- ?= $form->field($model, 'email', [])->textInput([
                    'class' => 'form-control form-control-solid',
                    'autocomplete' => 'off',
                    'autofocus' => true,
                    'placeholder' => $model->getAttributeLabel('email'),

                ]) ?> -->
                <!-- <input type="text" placeholder="Email" name="email" autocomplete="off" data-kt-translate="sign-in-input-email" class="form-control form-control-solid" /> -->
                <!--end::Email-->
                <!-- <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        <div data-field="email" data-validator="notEmpty">Email address is required</div>
                    </div> -->
                <!-- </div> -->
                <!--end::Input group=-->
                <!-- <div class="fv-row mb-7"> -->
                <!--begin::Password-->
                <?= $form->field($model, 'password', [])->passwordInput(['class' => 'form-control form-control-solid', 'placeholder' => $model->getAttributeLabel('password')]) ?>
  <?= $form->field($model, 'password_repeat', [])->passwordInput(['class' => 'form-control form-control-solid', 'placeholder' => $model->getAttributeLabel('password_repeat')]) ?>

                <!-- <input type="text" placeholder="Password" name="password" autocomplete="off" data-kt-translate="sign-in-input-password" class="form-control form-control-solid" /> -->
                <!--end::Password-->
                <!-- </div> -->
                <!--end::Input group=-->
                <!--begin::Wrapper-->
                <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-10">
                    
                    <!--begin::Link-->
                  

                    <!--end::Link-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Actions-->
                <div class="d-flex flex-stack">
                    <!--begin::Submit-->
                    <!-- <button id="kt_sign_in_submit" class="btn btn-primary me-2 flex-shrink-0"> -->
                    <!--begin::Indicator label-->
                    <!-- <span class="indicator-label" data-kt-translate="sign-in-submit">Sign In</span> -->
                    <!--end::Indicator label-->
                    <!--begin::Indicator progress-->
                    <!-- <span class="indicator-progress">
                            <span data-kt-translate="general-progress">Please wait...</span>
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span> -->
                    <!--end::Indicator progress-->
                    <!-- </button> -->
                    <?= Html::submitButton(Yii::$app->lang->t('signin', 'submit'), ['class' => 'btn btn-primary me-2 flex-shrink-0', 'name' => 'login-button']) ?>
                    <!--end::Submit-->
                    <!--begin::Social-->
                    <?php //echo $this->render("_social") ?>
                    <!--end::Social-->
                </div>
                <!--end::Actions-->
            </div>
            <!--begin::Body-->
            <!-- </form> -->
            <?php ActiveForm::end(); ?>
            <!--end::Form-->
        </div>
        <!--end::Body-->

        <!--begin::Footer-->
        <?php //echo $this->render("_lang") ?>
        <!--end::Footer-->

    </div>
    <!--end::Wrapper-->
</div>
<!--end::Aside-->