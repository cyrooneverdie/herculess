<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Users;
use common\models\Company;

$session = Yii::$app->session->get('companyid');
$company = Company::findOne(['companyid' => $session]);
?>
<div class="app-navbar flex-shrink-0">
    <!--begin::User menu-->
    <div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
        <!--begin::Menu wrapper-->
        <div class="cursor-pointer symbol symbol-35px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
            <img src="<?= Yii::$app->user->identity->avatar ? Yii::$app->user->identity->avatar : Yii::getAlias('@web') . '/assets/media/avatars/default-avatar.jpg' ?>" class="rounded-3" alt="user" />
        </div>
        <!--begin::User account menu-->
        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
            <!--begin::Menu item-->
            <div class="menu-item px-3">
                <div class="menu-content d-flex align-items-center px-3">
                    <!--begin::Avatar-->
                    <div class="symbol symbol-50px me-5">
                        <img alt="Logo" src="<?= Yii::$app->user->identity->avatar ? Yii::$app->user->identity->avatar : Yii::getAlias('@web') . '/assets/media/avatars/default-avatar.jpg' ?>" />
                    </div>
                    <!--end::Avatar-->
                    <!--begin::Username-->
                    <div class="d-flex flex-column">
                        <div class="fw-bold d-flex align-items-center fs-5"><?php echo Yii::$app->user->identity->name; ?>
                            <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2"><?php echo Yii::$app->enum->getgrupvw(); ?></span>
                        </div>
                        <a href="#" class="fw-semibold text-muted text-hover-primary fs-7"><?php echo Yii::$app->user->identity->email; ?></a>
                    </div>
                    <!--end::Username-->
                </div>
                <div class="d-flex px-3 mt-5">
                    <div class="d-flex flex-column">
                        <!-- <p>
                            <?= Yii::$app->lang->t('extra', 'extra3') ?> : 
                            <?php
                            if ($company && $company->subs_status == 1) {
                                echo $company->package;
                            } else {
                                echo 'Free';
                            }
                            ?>
                        </p>
                        <p>
                            <?= Yii::$app->lang->t('extra', 'extra1') ?> :
                            <?php
                            if ($company && $company->subs_status == 1 && $company->tglendsubs) {
                                echo Yii::$app->formatter->asDate($company->tglendsubs, "php:d-m-Y");
                            } else {
                                echo Yii::$app->lang->t('extra', 'extra33') ?? '-';
                            }
                            ?>
                        </p>

                        <!-- <a href="<?= Url::to(['users/changebilling']) ?>" class="btn btn-success btn-sm px-3 py-2" style="font-size: 1rem;">
                            <?= Yii::$app->lang->t('extra', 'extra7') ?>
                        </a> -->

                    </div>
                </div>
            </div>
            <!--end::Menu item-->
            <!--begin::Menu separator-->
            <div class="separator my-2"></div>
            <!--end::Menu separator-->
            <!--begin::Menu item-->
            <!-- <div class="menu-item px-5">
                <a href="<?php echo Url::to(['users/profile', 'userid' => Yii::$app->user->identity->userid], true); ?>" class="menu-link px-5">
                    <?= Yii::$app->lang->t('back_layout', 'chat1') ?>
                </a>
            </div> -->

            <!-- <div class="menu-item px-5">
                <a href="<?= Url::to(['users/billing'], true) ?>" class="menu-link px-5"><?= Yii::$app->lang->t('back_layout', 'chat4') ?></a>
            </div> -->

            <!--end::Menu item-->
            <!--begin::Menu item-->
            <!-- <div class="menu-item px-5">
                <a href="apps/projects/list.html" class="menu-link px-5">
                    <span class="menu-text">My Projects</span>
                    <span class="menu-badge">
                        <span class="badge badge-light-danger badge-circle fw-bold fs-7">3</span>
                    </span>
                </a>
            </div> -->
            <!--end::Menu item-->
            <!--begin::Menu item-->
            <!-- <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                <a href="#" class="menu-link px-5">
                    <span class="menu-title"><?= Yii::$app->lang->t('back_layout', 'chat2') ?></span>
                    <span class="menu-arrow"></span>
                </a> -->
            <!--begin::Menu sub-->
            <!-- <div class="menu-sub menu-sub-dropdown w-175px py-4"> -->
            <!--begin::Menu item-->
            <!-- <div class="menu-item px-3">
                    <a href="<?= Url::to(['users/billing'], true) ?>" class="menu-link px-5"><?= Yii::$app->lang->t('back_layout', 'chat3') ?></a>
                </div> -->
            <!--end::Menu item-->
            <!--begin::Menu item-->
            <!--end::Menu item-->
            <!--begin::Menu item-->
            <!-- <div class="menu-item px-3">
                    <a href="account/statements.html" class="menu-link px-5"><?= Yii::$app->lang->t('back_layout', 'chat5') ?></a>
                </div> -->
            <!--end::Menu item-->
            <!--begin::Menu item-->
            <!-- <div class="menu-item px-3">
                    <a href="account/statements.html" class="menu-link d-flex flex-stack px-5"><?= Yii::$app->lang->t('back_layout', 'chat6') ?>
                        <span class="ms-2 lh-0" data-bs-toggle="tooltip" title="View your statements">
                            <i class="ki-duotone ki-information-5 fs-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span></a>
                </div> -->
            <!--end::Menu item-->
            <!--begin::Menu separator-->
            <!-- <div class="separator my-2"></div> -->
            <!--end::Menu separator-->
            <!--begin::Menu item-->
            <!-- <div class="menu-item px-3">
                    <div class="menu-content px-3">
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications" />
                            <span class="form-check-label text-muted fs-7"><?= Yii::$app->lang->t('back_layout', 'chat7') ?></span>
                        </label>
                    </div>
                </div> -->
            <!--end::Menu item-->
            <!-- </div> -->
            <!--end::Menu sub-->
            <!-- </div> -->
            <!--end::Menu item-->

            <!--begin::Menu separator-->
            <div class="separator my-2"></div>
            <!--end::Menu separator-->

            <!--begin::Menu item-->
            <!-- <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                <a href="#" class="menu-link px-5">
                    <span class="menu-title position-relative"><?= Yii::$app->lang->t('back_layout', 'chat8') ?>
                    </span>
                    <span class="menu-arrow"></span>
                </a> -->
            <!--begin::Menu sub-->
            <!-- <div class="menu-sub menu-sub-dropdown w-175px py-4"> -->
            <!--begin::Menu item-->
            <!-- <div class="menu-item px-3">
                        <a href="<?= Yii::$app->urlManager->createUrl(['site/setlang', 'lang' => 'en']) ?>" class="menu-link d-flex px-5">
                            <span class="symbol symbol-20px me-4">
                                <img class="rounded-1" src="<?php echo Yii::$app->getUrlManager()->getBaseUrl() ?>/assets/media/flags/united-states.svg" alt="" />
                            </span><?= Yii::$app->lang->t('back_layout', 'chat9') ?></a>
                    </div> -->
            <!--end::Menu item-->
            <!--begin::Menu item-->
            <!-- <div class="menu-item px-3">
                        <a href="<?= Yii::$app->urlManager->createUrl(['site/setlang', 'lang' => 'id']) ?>" class="menu-link d-flex px-5">
                            <span class="symbol symbol-20px me-4">
                                <img class="rounded-1" src="<?php echo Yii::$app->getUrlManager()->getBaseUrl() ?>/assets/media/flags/indonesia.svg" alt="" />
                            </span><?= Yii::$app->lang->t('back_layout', 'chat10') ?></a>
                    </div> -->
            <!--end::Menu item-->
            <!-- </div> -->
            <!--end::Menu sub-->
            <!-- </div> -->
            <!--end::Menu item-->
            <!--begin::Menu item-->
            <!-- <div class="menu-item px-5 my-1">
                <a href="?php echo Url::to(['users/profile'], true); ?>" class="menu-link px-5"><?= Yii::$app->lang->t('back_layout', 'chat11') ?></a>
            </div> -->
            <!--end::Menu item-->
            <!--begin::Menu item-->
            <div class="menu-item px-3">
                <div class="d-flex justify-content-between align-items-center gap-2">
                    <!-- Tombol Ubah Password (Kiri) -->
                    <?= Html::a(
                        '<i class="bi bi-key"></i> ' . Yii::$app->lang->t('back_home', 'chat17'),
                        ['/site/changepassword'],
                        ['class' => 'btn btn-sm btn-primary flex-grow-1']
                    ) ?>

                    <!-- Tombol Logout (Kanan) -->
                    <?= Html::a(
                        '<i class="bi bi-box-arrow-right"></i> ' . Yii::$app->lang->t('back_layout', 'chat12'),
                        ['/site/logout'],
                        [
                            'data-method' => 'post',
                            'class' => 'btn btn-sm btn-danger flex-grow-1'
                        ]
                    ) ?>
                </div>
            </div>
            <!--end::Menu item-->
        </div>
        <!--end::User account menu-->
        <!--end::Menu wrapper-->
    </div>
    <!--end::User menu-->
    <!--begin::Header menu toggle-->
    <!-- <div class="app-navbar-item d-lg-none ms-2 me-n2" title="Show header menu">
        <div class="btn btn-flex btn-icon btn-active-color-primary w-30px h-30px" id="kt_app_header_menu_toggle">
            <i class="ki-duotone ki-element-4 fs-1">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
    </div> -->
    <!--end::Header menu toggle-->
    <!--begin::Aside toggle-->
    <!--end::Header menu toggle-->
</div>