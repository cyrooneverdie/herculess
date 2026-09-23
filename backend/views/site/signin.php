<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'Login';
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
            <!-- <div class="m-0 mt-5">
                <span class="text-gray-500 fw-bold fs-5 me-2"><?= Yii::$app->lang->t('signin', 'notmember') ?> ?</span>
                <a href="<?= Url::to(['site/signup']) ?>" class="link-primary fw-bold fs-5"><?= Yii::$app->lang->t('signin', 'signup') ?></a>
            </div> -->
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
                        'tag' => 'div',
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
                <div class="text-start mb-10">
                    <!--begin::Title-->
                    <h1 class="text-gray-900 mb-3 fs-3x"><?= Yii::$app->lang->t('signin', 'title1') ?></h1>
                </div>
                <?php
                //var_dump(Yii::$app->session->getFlash('error'));exit;
                if (Yii::$app->session->hasFlash('success')) {
                    ?>
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mt-10 mb-10"
                        role="alert">
                        <div class="me-3">
                            <i class="fas fa-exclamation-triangle fa-lg text-success"></i>
                        </div>
                        <div><?= Yii::$app->session->getFlash('success') ?></div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>


                    <?php
                } else if (Yii::$app->session->hasFlash('error')) {
                    ?>
                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mt-10 mb-10"
                            role="alert">
                            <div class="me-3">
                                <i class="fas fa-exclamation-triangle fa-lg text-danger"></i>
                            </div>
                            <div><?= Yii::$app->session->getFlash('error') ?></div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                <?php } ?>
                <!--begin::Heading-->
                <!--begin::Input group=-->
                <!-- <div class="fv-row mb-8"> -->
                <!--begin::Email-->
                <?= $form->field($model, 'username', [])->textInput([
                    'class' => 'form-control form-control-solid',
                    'autocomplete' => 'off',
                    'autofocus' => true,
                    'placeholder' => $model->getAttributeLabel('username'),

                ]) ?>


                <?= $form->field($model, 'password', [])->passwordInput(['class' => 'form-control form-control-solid', 'placeholder' => $model->getAttributeLabel('password')]) ?>

                <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-10">
                    <div>
                        <?= $form->field($model, 'rememberMe')->checkbox()->label(Yii::$app->lang->t('signin', 'rememberme')) ?>
                    </div>
                    <!--begin::Link-->
                    <!-- <a href="<?= \yii\helpers\Url::to(['site/forgot_password']) ?>" class="link-primary">
                        <?= Yii::$app->lang->t('signin', 'forgotpassword') ?> ?
                    </a> -->

                    <!--end::Link-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Actions-->
                <div class="d-flex flex-stack">

                    <?= Html::submitButton(Yii::$app->lang->t('signin', 'login'), ['class' => 'btn btn-primary me-2 flex-shrink-0', 'name' => 'login-button']) ?>
                    <!--end::Submit-->
                    <!--begin::Social-->

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
        <?php echo $this->render("_lang") ?>
        <!--end::Footer-->

    </div>
    <!--end::Wrapper-->
</div>
<!--end::Aside-->