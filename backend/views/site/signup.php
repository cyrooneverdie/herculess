<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;



$this->title = 'Sign Up';
$this->params['breadcrumbs'][] = $this->title;
?>


<!--begin::Aside-->
<div class="d-flex flex-column flex-column-fluid flex-center w-lg-50 p-10">
    <!--begin::Wrapper-->
    <div class="d-flex justify-content-between flex-column-fluid flex-column w-100 mw-450px">
        <!--begin::Header-->
        <div class="d-flex flex-stack py-0">
            <!--begin::Back link-->
            <!--end::Back link-->
            <!--begin::Sign Up link-->
            <div class="m-0">
                <span class="text-gray-500 fw-bold fs-5 me-2" data-kt-translate="sign-up-head-desc"><?= Yii::$app->lang->t('signup', 'alreadymember') ?> ?</span>
                <a href="<?= Url::to(['site/login']) ?>" class="link-primary fw-bold fs-5" data-kt-translate="sign-up-head-link"><?= Yii::$app->lang->t('signup', 'signin') ?></a>
            </div>
            <!--end::Sign Up link=-->
        </div>
        <!--end::Header-->
        <!--begin::Body-->
        <div class="py-20">
            <!--begin::Form-->

            <?php $form = ActiveForm::begin([
                'id' => 'sighup-form',
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
            <!-- <form class="form w-100" novalidate="novalidate" id="kt_sign_up_form" data-kt-redirect-url="authentication/layouts/fancy/sign-in.html" action="#"> -->
            <!--begin::Heading-->
            <div class="text-start mb-10">
                <!--begin::Title-->
                <h1 class="text-gray-900 mb-3 fs-3x" data-kt-translate="sign-up-title"><?= Yii::$app->lang->t('signup', 'createanaccount') ?> </h1>
                <!--end::Title-->
                <!--begin::Text-->
                <div class="text-gray-500 fw-semibold fs-6" data-kt-translate="general-desc"></div>
                <!--end::Link-->
            </div>
            <!--end::Heading-->
            <!--begin::Input group-->
            <div class="row fv-row mb-2">
                <!--begin::Col-->
                <div class="col-xl-12">
                    <?= $form->field($model, 'name')->textInput(['autofocus' => true, 'class' => 'form-control form-control-lg form-control-solid', 'placeholder' => $model->getAttributeLabel('name')]) ?>

                    <!-- <input class="form-control form-control-lg form-control-solid" type="text" placeholder="First Name" name="first-name" autocomplete="off" data-kt-translate="sign-up-input-first-name" /> -->
                </div>
                <div class="col-xl-12">
                    <?= $form->field($model, 'address')->textInput(['autofocus' => true, 'class' => 'form-control form-control-lg form-control-solid', 'placeholder' => $model->getAttributeLabel('address')]) ?>

                    <!-- <input class="form-control form-control-lg form-control-solid" type="text" placeholder="First Name" name="first-name" autocomplete="off" data-kt-translate="sign-up-input-first-name" /> -->
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <!-- <div class="col-xl-6">
                    <input class="form-control form-control-lg form-control-solid" type="text" placeholder="Last Name" name="last-name" autocomplete="off" data-kt-translate="sign-up-input-last-name" />
                </div> -->
                <!--end::Col-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-5">
                <?= $form->field($model, 'email')->textInput(['autofocus' => false, 'class' => 'form-control form-control-lg form-control-solid', 'placeholder' => $model->getAttributeLabel('email'), 'type' => 'email']) ?>
                <!-- <input class="form-control form-control-lg form-control-solid" type="email" placeholder="Email" name="email" autocomplete="off" data-kt-translate="sign-up-input-email" /> -->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-10" data-kt-password-meter="true">
                <!--begin::Wrapper-->




                <div class="mb-1">
                    <!--begin::Input wrapper-->
                    <div class="position-relative mb-3">
                        <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control form-control-lg form-control-solid', 'placeholder' => $model->getAttributeLabel('password')]) ?>
                        <!-- <input class="form-control form-control-lg form-control-solid" type="password" placeholder="Password" name="password" autocomplete="off" data-kt-translate="sign-up-input-password" /> -->
                        <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                            <i class="ki-duotone ki-eye-slash fs-2"></i>
                            <i class="ki-duotone ki-eye fs-2 d-none"></i>
                        </span>
                    </div>
                    <!--end::Input wrapper-->
                    <!--begin::Meter-->
                    <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                    </div>
                    <!--end::Meter-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Hint-->
                <div class="text-muted" data-kt-translate="sign-up-hint"><?= Yii::$app->lang->t('signup', 'hint1') ?></div>
                <!--end::Hint-->
            </div>
            <!--end::Input group=-->
            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <?= $form->field($model, 'repassword')->passwordInput(
                    ['class' => 'form-control form-control-lg form-control-solid', 'placeholder' => $model->getAttributeLabel('repassword')]
                ) ?>
                <!-- <input class="form-control form-control-lg form-control-solid" type="password" placeholder="Confirm Password" name="confirm-password" autocomplete="off" data-kt-translate="sign-up-input-confirm-password" /> -->
            </div>
            <!--end::Input group-->
            <!--begin::Actions-->
            <div class="d-flex flex-stack">
                <!--begin::Submit-->
                <!-- <button id="kt_sign_up_submit" class="btn btn-primary" data-kt-translate="sign-up-submit"> -->
                <!--begin::Indicator label-->
                <!-- <span class="indicator-label">Submit</span> -->
                <!--end::Indicator label-->
                <!--begin::Indicator progress-->
                <!-- <span class="indicator-progress">Please wait... -->
                <!-- <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span> -->
                <!--end::Indicator progress-->
                <!-- </button> -->

                <?= Html::submitButton(Yii::$app->lang->t('signup', 'signup'), ['class' => 'btn btn-primary', 'name' => 'signup-button', 'data-kt-translate' => "sign-up-submit"]) ?>
                <!--end::Submit-->
                <!--begin::Social-->
                <?php echo $this->render("_social") ?>
                <!--end::Social-->
            </div>
            <!--end::Actions-->
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