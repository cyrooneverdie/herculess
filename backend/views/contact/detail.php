<?php

use yii\helpers\Html;
use yii\helpers\Url;

$typeParam = Yii::$app->request->get('contacttype') ?? $model->contacttype;

$mapType = [
    1 => 'Customer',
    0 => 'Vendor',
    3 => 'Employee',
    2 => 'Supplier',
];

$typeName = $mapType[$typeParam] ?? strtolower(trim($typeParam));

switch ($typeName) {
    case 'Customer':
        $this->title = "Customer Detail - {$model->contact_name}";
        break;
    case 'Vendor':
        $this->title = "Vendor Detail - {$model->contact_name}";
        break;
    case 'Employee':
        $this->title = "Employee Detail - {$model->contact_name}";
        break;
    case 'Supplier':
        $this->title = "Supplier Detail - {$model->contact_name}";
        break;
    default:
        $this->title = ucfirst($typeName);
        break;
}

$website = trim($model->website);
if ($website && !preg_match('/^https?:\/\//', $website)) {
    $website = 'https://' . $website;
    $model->website = $website;
}

?>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <!-- Header -->
        <div class="card mb-5">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fa-solid fa-user me-2"></i><?= Html::encode($model->contact_name) ?>
                </h3>
                <div class="card-toolbar text-primary">
                    <a href="<?= Url::to(['index', 'contacttype' => strtolower($typeName)]) ?>"
                        class="btn btn-sm btn-light-primary">
                        <i class="fa-solid fa-arrow-left me-2"></i><?= Yii::$app->lang->t('contact', 'back') ?>
                    </a>
                </div>
            </div>
        </div>

        <?php if ($typeName != 'employee'): ?>
            <div class="card shadow-sm mb-7">
                <div class="card-header min-h-50px bg-light-primary">
                    <div class="card-title">
                        <i class="fas fa-address-card text-primary fs-4 me-2"></i>
                        <h3 class="fw-bold text-gray-800 fs-6 mb-0"><?= Yii::$app->lang->t('contact', 'binformation') ?>
                        </h3>
                    </div>
                </div>
                <div class="card-body py-5">
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'contact_no') ?></label>
                            <div class="bg-light rounded p-3">
                                <span class="fw-bold text-gray-800"><?= Html::encode($model->contact_no ?? '-') ?></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'jobcompany') ?></label>
                            <div class="bg-light rounded p-3">
                                <i class="fas fa-building text-muted me-1 fs-8"></i>
                                <?= Html::encode($model->jobcompany ?? '-') ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'contact_phone2') ?></label>
                            <div class="bg-light rounded p-3">
                                <?php if ($model->contact_phone2): ?>
                                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $model->contact_phone2) ?>"
                                        target="_blank" class="text-primary fw-semibold">
                                        <i class="fa-solid fa-phone me-1"></i><?= Html::encode($model->contact_phone2) ?>
                                    </a>
                                <?php else: ?> - <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'businesscategory') ?></label>
                            <div class="bg-light rounded p-3">
                                <?= Html::encode($model->businesscategory ?? '-') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5">
                        <?php if ($typeName == 'vendor' || $typeName == 'supplier'): ?>
                            <div class="col-md-3">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('produk_table', 'produk_jenis') ?></label>
                                <div class="bg-light rounded p-3 text-primary fw-bold">
                                    <?php
                                    $typeOptions = ['1' => 'Perorangan', '2' => 'Perusahaan', '3' => 'Pemerintah'];
                                    echo $typeOptions[$model->type] ?? '-';
                                    ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-3">
                            <label class="text-muted fw-semibold fs-7 mb-2">Website</label>
                            <div class="bg-light rounded p-3">
                                <?php if ($model->website): ?>
                                    <a href="<?= Html::encode($model->website) ?>" target="_blank"
                                        class="text-primary text-decoration-underline">
                                        <i class="fa-solid fa-globe me-1"></i><?= Html::encode($model->website) ?>
                                    </a>
                                <?php else: ?> - <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>

            <div class="card shadow-sm mb-7">
                <div class="card-header min-h-50px bg-light-primary">
                    <div class="card-title">
                        <i class="fas fa-user-tie text-primary fs-4 me-2"></i>
                        <h3 class="fw-bold text-gray-800 fs-6 mb-0">Informasi Karyawan</h3>
                    </div>
                </div>
                <div class="card-body py-5">
                    <div class="row g-5">
                        <div class="col-md-3">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'contact_no') ?></label>
                            <div class="bg-light rounded p-3">
                                <span class="fw-bold text-gray-800"><?= Html::encode($model->contact_no ?? '-') ?></span>
                            </div>
                        </div>
                        <?php if ($model->personid): ?>
                            <div class="col-md-4">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'person') ?></label>
                                <div class="bg-light rounded p-3">
                                    <?= Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='{$model->personid}' ") ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-5">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'contact_name') ?></label>
                            <div class="bg-light rounded p-3">
                                <i class="fas fa-user text-muted me-1 fs-8"></i>
                                <?= Html::encode($model->contact_name ?? '-') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm mb-7">
            <div class="card-header min-h-50px bg-light-info">
                <div class="card-title">
                    <i class="fas fa-id-card text-info fs-4 me-2"></i>
                    <h3 class="fw-bold text-gray-800 fs-6 mb-0">Kontak & Detail</h3>
                </div>
            </div>
            <div class="card-body py-5">
                <div class="row g-5 mb-4">
                    <?php if ($typeName != 'employee'): ?>
                        <div class="col-lg-3 col-md-6">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'contact_name') ?></label>
                            <div class="bg-light rounded p-3">
                                <i class="fas fa-user text-muted me-1 fs-8"></i>
                                <?= Html::encode($model->contact_name ?? '-') ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="col-lg-3 col-md-6">
                        <label
                            class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'contact_email1') ?></label>
                        <div class="bg-light rounded p-3">
                            <?php if ($model->contact_email1): ?>
                                <i class="fa-solid fa-envelope me-1"></i><?= Html::encode($model->contact_email1) ?>
                            <?php else: ?> - <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label
                            class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'contact_phone1') ?></label>
                        <div class="bg-light rounded p-3">
                            <?php if ($model->contact_phone1): ?>
                                <i class="fa-solid fa-phone me-1"></i><?= Html::encode($model->contact_phone1) ?>
                            <?php else: ?> - <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($typeName != 'employee'): ?>
                        <div class="col-lg-3 col-md-6">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'jobposition') ?></label>
                            <div class="bg-light rounded p-3">
                                <?php if ($model->jobposition): ?>
                                    <?= Html::encode($model->jobposition) ?>
                                <?php else: ?> -
                                <?php endif; ?>
                            </div>

                        </div>
                    <?php endif; ?>

                    <?php if ($typeName == 'employee'): ?>
                        <div class="col-lg-3 col-md-6">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'contact_birth') ?></label>
                            <div class="bg-light rounded p-3">
                                <i class="fas fa-map-marker-alt text-muted me-1 fs-8"></i>
                                <?= Html::encode($model->contact_bop ?? '-') ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'contact_bod') ?></label>
                            <div class="bg-light rounded p-3">
                                <i class="fas fa-calendar text-muted me-1 fs-8"></i>
                                <?= $model->contact_bod ? Yii::$app->formatter->asDate($model->contact_bod) : '-' ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($typeName != 'employee' && $model->npwpfile): ?>
                    <div class="row g-5">
                        <div class="col-lg-2 col-md-4">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'filenpwp') ?></label>
                            <div class="card card-flush border border-dashed border-gray-300"
                                style="width:150px; height:150px;">
                                <div class="card-body d-flex align-items-center justify-content-center p-2">
                                    <img src="<?= Yii::getAlias('@web') . '/uploads/contact/' . $model->npwpfile ?>"
                                        alt="NPWP" class="mw-100 mh-100 rounded object-fit-contain" style="cursor:pointer;"
                                        onclick="window.open(this.src, '_blank')">
                                </div>
                            </div>
                            <small class="text-muted d-block mt-1"><i class="fa-solid fa-info-circle me-1"></i>Click to view
                                full size</small>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>


        <?php if ($typeName == 'employee'): ?>

            <div class="card shadow-sm mb-7">
                <div class="card-header min-h-50px bg-light-warning">
                    <div class="card-title">
                        <i class="fas fa-user-circle text-warning fs-4 me-2"></i>
                        <h3 class="fw-bold text-gray-800 fs-6 mb-0">Data Pribadi</h3>
                    </div>
                </div>
                <div class="card-body py-5">
                    <div class="row g-5 mb-5">
                        <?php if ($model->contact_gender): ?>
                            <div class="col-md-4">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'contact_gender') ?></label>
                                <div class="bg-light rounded p-3">
                                    <?= Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='{$model->contact_gender}' ") ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($model->contact_married): ?>
                            <div class="col-md-4">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'contact_married') ?></label>
                                <div class="bg-light rounded p-3">
                                    <?= Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='{$model->contact_married}' ") ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($model->contact_religion): ?>
                            <div class="col-md-4">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'contact_religion') ?></label>
                                <div class="bg-light rounded p-3">
                                    <?= Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='{$model->contact_religion}' ") ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="row g-5">
                        <?php if ($model->contactphoto): ?>
                            <div class="col-lg-2 col-md-4 text-center">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2 d-block"><?= Yii::$app->lang->t('contact', 'contactphoto') ?></label>
                                <div class="d-flex flex-column align-items-center justify-content-center border border-dashed border-gray-300 rounded bg-light-secondary w-100"
                                    style="height:120px">
                                    <img src="<?= Yii::getAlias('@web') . '/uploads/contact/' . $model->contactphoto ?>"
                                        class="w-100 h-100 rounded object-fit-contain" style="cursor:pointer;"
                                        onclick="window.open(this.src, '_blank')" alt="Foto">
                                </div>
                                <small class="text-muted mt-1 d-block"><i class="fa-solid fa-info-circle me-1"></i>Click to view
                                    full size</small>
                            </div>
                        <?php endif; ?>
                        <?php if ($model->nametagfile): ?>
                            <div class="col-lg-2 col-md-4 text-center">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2 d-block"><?= Yii::$app->lang->t('contact', 'nametag') ?></label>
                                <div class="d-flex flex-column align-items-center justify-content-center border border-dashed border-gray-300 rounded bg-light-secondary w-100"
                                    style="height:120px">
                                    <img src="<?= Yii::getAlias('@web') . '/uploads/contact/' . $model->nametagfile ?>"
                                        class="w-100 h-100 rounded object-fit-contain" style="cursor:pointer;"
                                        onclick="window.open(this.src, '_blank')" alt="Nametag">
                                </div>
                                <small class="text-muted mt-1 d-block"><i class="fa-solid fa-info-circle me-1"></i>Click to view
                                    full size</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($model->address) || !empty($model->countryid)): ?>
            <div class="card shadow-sm mb-7">
                <div class="card-header min-h-50px bg-light-success">
                    <div class="card-title">
                        <i class="fas fa-map-marked-alt text-success fs-4 me-2"></i>
                        <h3 class="fw-bold text-gray-800 fs-6 mb-0"><?= Yii::$app->lang->t('extra', 'extra36') ?></h3>
                    </div>
                </div>
                <div class="card-body py-5">

                    <?php if ($typeName != 'employee'): ?>
                        <div class="d-flex align-items-center mb-5 gap-2">
                            <i class="fa-solid fa-building text-primary fs-5"></i>
                            <span
                                class="fw-bold text-gray-700 fs-7 text-uppercase ls-1"><?= Yii::$app->lang->t('contact', 'compadd') ?></span>
                            <div class="separator separator-dashed flex-grow-1"></div>
                        </div>
                    <?php endif; ?>

                    <div class="row g-5 mb-5">
                        <div class="col-md-4">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('country', 'label1') ?></label>
                            <div class="bg-light rounded p-3">
                                <?= $model->countryid ? Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='{$model->countryid}' ") : '-' ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('state', 'label1') ?></label>
                            <div class="bg-light rounded p-3">
                                <?= $model->stateid ? Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='{$model->stateid}' ") : '-' ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('city', 'label1') ?></label>
                            <div class="bg-light rounded p-3">
                                <?= $model->cityid ? Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='{$model->cityid}' ") : '-' ?>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-4">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('district', 'label1') ?></label>
                            <div class="bg-light rounded p-3">
                                <?= $model->districtid ? Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='{$model->districtid}' ") : '-' ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'subdistrictid') ?></label>
                            <div class="bg-light rounded p-3">
                                <?= Html::encode($model->subdistrictid ?? '-') ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'zip') ?></label>
                            <div class="bg-light rounded p-3">
                                <?= Html::encode($model->zip ?? '-') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-12">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'address') ?></label>
                            <div class="bg-light rounded p-3" style="min-height: 60px;">
                                <?= Html::encode($model->address ?? '-') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Address (non-employee only) -->
                    <?php if ($typeName != 'employee' && (!empty($model->billaddress) || !empty($model->billcountry))): ?>
                        <div class="separator separator-dashed my-5"></div>

                        <div class="d-flex align-items-center mb-5 gap-2">
                            <i class="fa-solid fa-money-bill text-warning fs-5"></i>
                            <span
                                class="fw-bold text-gray-700 fs-7 text-uppercase ls-1"><?= Yii::$app->lang->t('contact', 'billadd') ?></span>
                            <div class="separator separator-dashed flex-grow-1"></div>
                        </div>

                        <div class="row g-5 mb-5">
                            <div class="col-md-4">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('country', 'label1') ?></label>
                                <div class="bg-light rounded p-3">
                                    <?= $model->billcountry ? Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='{$model->billcountry}' ") : '-' ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('state', 'label1') ?></label>
                                <div class="bg-light rounded p-3">
                                    <?= $model->billstate ? Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='{$model->billstate}' ") : '-' ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('city', 'label1') ?></label>
                                <div class="bg-light rounded p-3">
                                    <?= $model->billcity ? Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='{$model->billcity}' ") : '-' ?>
                                </div>
                            </div>
                        </div>

                        <div class="row g-5 mb-5">
                            <div class="col-md-4">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('district', 'label1') ?></label>
                                <div class="bg-light rounded p-3">
                                    <?= $model->billdistrict ? Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='{$model->billdistrict}' ") : '-' ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'subdistrictid') ?></label>
                                <div class="bg-light rounded p-3">
                                    <?= Html::encode($model->billsubdistrict ?? '-') ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'zip') ?></label>
                                <div class="bg-light rounded p-3">
                                    <?= Html::encode($model->billzip ?? '-') ?>
                                </div>
                            </div>
                        </div>

                        <div class="row g-5">
                            <div class="col-12">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'address') ?></label>
                                <div class="bg-light rounded p-3" style="min-height: 60px;">
                                    <?= Html::encode($model->billaddress ?? '-') ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        <?php endif; ?>

        <?php if ($typeName == 'employee'): ?>
            <?php if (!empty($model->bankaccount_no) || !empty($model->npwpno)): ?>
                <div class="card shadow-sm mb-7">
                    <div class="card-header min-h-50px bg-light-danger">
                        <div class="card-title">
                            <i class="fas fa-wallet text-danger fs-4 me-2"></i>
                            <h3 class="fw-bold text-gray-800 fs-6 mb-0"><?= Yii::$app->lang->t('extra', 'extra110') ?></h3>
                        </div>
                    </div>
                    <div class="card-body py-5">
                        <div class="row g-5 mb-5">
                            <?php if (!empty($model->bankaccount_no)): ?>
                                <div class="col-lg-3 col-md-6">
                                    <label
                                        class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'norek') ?></label>
                                    <div class="bg-light rounded p-3"><?= Html::encode($model->bankaccount_no) ?></div>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($model->bankname)): ?>
                                <div class="col-lg-3 col-md-6">
                                    <label
                                        class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'bank') ?></label>
                                    <div class="bg-light rounded p-3"><?= Html::encode($model->bankname) ?></div>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($model->npwpno)): ?>
                                <div class="col-lg-3 col-md-6">
                                    <label
                                        class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'nonpwp') ?></label>
                                    <div class="bg-light rounded p-3"><?= Html::encode($model->npwpno) ?></div>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($model->bpjstkno)): ?>
                                <div class="col-lg-3 col-md-6">
                                    <label
                                        class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'bpjstk') ?></label>
                                    <div class="bg-light rounded p-3"><?= Html::encode($model->bpjstkno) ?></div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="row g-5 mb-5">
                            <?php if (!empty($model->bpjstktype)): ?>
                                <div class="col-lg-3 col-md-6">
                                    <label
                                        class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'bpjstktype') ?></label>
                                    <div class="bg-light rounded p-3"><?= Html::encode($model->bpjstktype) ?></div>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($model->bpjs_kesno)): ?>
                                <div class="col-lg-3 col-md-6">
                                    <label
                                        class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'bpjskes') ?></label>
                                    <div class="bg-light rounded p-3"><?= Html::encode($model->bpjs_kesno) ?></div>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($model->bpjs_kestype)): ?>
                                <div class="col-lg-3 col-md-6">
                                    <label
                                        class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'bpjskestype') ?></label>
                                    <div class="bg-light rounded p-3"><?= Html::encode($model->bpjs_kestype) ?></div>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($model->insurancename)): ?>
                                <div class="col-lg-3 col-md-6">
                                    <label
                                        class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'asuransi') ?></label>
                                    <div class="bg-light rounded p-3"><?= Html::encode($model->insurancename) ?></div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="row g-5 mb-5">
                            <?php if (!empty($model->insuranceother)): ?>
                                <div class="col-lg-3 col-md-6">
                                    <label
                                        class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'asuransilain') ?></label>
                                    <div class="bg-light rounded p-3"><?= Html::encode($model->insuranceother) ?></div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($model->npwpfile): ?>
                            <div class="row g-5">
                                <div class="col-lg-2 col-md-4 text-center">
                                    <label
                                        class="text-muted fw-semibold fs-7 mb-2 d-block"><?= Yii::$app->lang->t('contact', 'filenpwp') ?></label>
                                    <div class="d-flex flex-column align-items-center justify-content-center border border-dashed border-gray-300 rounded bg-light-secondary w-100"
                                        style="height:120px">
                                        <img src="<?= Yii::getAlias('@web') . '/uploads/contact/' . $model->npwpfile ?>"
                                            class="w-100 h-100 rounded object-fit-contain" style="cursor:pointer;"
                                            onclick="window.open(this.src, '_blank')" alt="NPWP">
                                    </div>
                                    <small class="text-muted mt-1 d-block"><i class="fa-solid fa-info-circle me-1"></i>Click to view
                                        full size</small>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($model->relativename)): ?>
                <div class="card shadow-sm mb-7">
                    <div class="card-header min-h-50px bg-light-danger">
                        <div class="card-title">
                            <i class="fas fa-ambulance text-danger fs-4 me-2"></i>
                            <h3 class="fw-bold text-gray-800 fs-6 mb-0"><?= Yii::$app->lang->t('extra', 'extra111') ?></h3>
                        </div>
                    </div>
                    <div class="card-body py-5">
                        <div class="row g-5">
                            <div class="col-lg-4 col-md-6">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'contact_name') ?></label>
                                <div class="bg-light rounded p-3">
                                    <i class="fas fa-user text-muted me-1 fs-8"></i>
                                    <?= Html::encode($model->relativename) ?>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'relativerelation') ?></label>
                                <div class="bg-light rounded p-3">
                                    <?php
                                    $relationOptions = [
                                        '0' => 'Orang Tua',
                                        '1' => 'Suami/Istri',
                                        '2' => 'Anak',
                                        '3' => 'Saudara',
                                        '4' => 'Lainnya',
                                    ];
                                    echo isset($relationOptions[$model->relativerelation]) ? $relationOptions[$model->relativerelation] : '-';
                                    ?>
                                </div>
                            </div>
                            <?php if (!empty($model->relativephone)): ?>
                                <div class="col-lg-4 col-md-6">
                                    <label
                                        class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'contact_phone1') ?></label>
                                    <div class="bg-light rounded p-3">
                                        <i class="fa-solid fa-phone me-1"></i><?= Html::encode($model->relativephone) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>


        <div class="card shadow-sm mb-7">
            <div class="card-header min-h-50px bg-light-primary">
                <div class="card-title">
                    <i class="fas fa-file-alt text-primary fs-4 me-2"></i>
                    <h3 class="fw-bold text-gray-800 fs-6 mb-0"><?= Yii::$app->lang->t('extra', 'extra37') ?></h3>
                </div>
            </div>
            <div class="card-body py-5">
                <div class="row g-5 mb-5">
                    <?php if (!empty($model->idtype)): ?>
                        <div class="col-lg-3 col-md-5">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'idtype') ?></label>
                            <div class="bg-light rounded p-3">
                                <?= $model->idtype ? Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='{$model->idtype}' ") : '-' ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($model->idnumber)): ?>
                        <div class="col-lg-5 col-md-7">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'idnumber') ?></label>
                            <div class="bg-light rounded p-3">
                                <?= Html::encode($model->idnumber) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($typeName == 'employee'): ?>
                        <div class="col-lg-4 col-md-5">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'education') ?></label>
                            <div class="bg-light rounded p-3">
                                <?= $model->contact_education ? Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='{$model->contact_education}' ") : '-' ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($typeName == 'employee'): ?>
                    <div class="row g-5 mb-5">
                        <div class="col-md-4">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('status', 'label1') ?></label>
                            <div class="bg-light rounded p-3">
                                <?= $model->contractid ? Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='{$model->contractid}' ") : '-' ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'jobstart') ?></label>
                            <div class="bg-light rounded p-3">
                                <i class="fas fa-calendar-check text-muted me-1 fs-8"></i>
                                <?= $model->jobstart ? Yii::$app->formatter->asDate($model->jobstart) : '-' ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'jobend') ?></label>
                            <div class="bg-light rounded p-3">
                                <i class="fas fa-calendar-times text-muted me-1 fs-8"></i>
                                <?= $model->jobend ? Yii::$app->formatter->asDate($model->jobend) : '-' ?>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-4">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('level', 'label1') ?></label>
                            <div class="bg-light rounded p-3">
                                <?= $model->levelid ? Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='{$model->levelid}' ") : '-' ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('contact', 'devision') ?></label>
                            <div class="bg-light rounded p-3">
                                <?= $model->divisionid ? Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='{$model->divisionid}' ") : '-' ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('position', 'label1') ?></label>
                            <div class="bg-light rounded p-3">
                                <?= $model->positionid ? Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='{$model->positionid}' ") : '-' ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($model->idfile): ?>
                    <div class="row g-5 mb-5">
                        <div class="col-lg-2 col-md-4 text-center">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2 d-block"><?= Yii::$app->lang->t('contact', 'contactpict') ?></label>
                            <div class="card card-flush border border-dashed border-gray-300"
                                style="width:150px; height:150px;">
                                <div class="card-body d-flex align-items-center justify-content-center p-2">
                                    <img src="<?= Yii::getAlias('@web') . '/uploads/contact/' . $model->idfile ?>"
                                        alt="Identity Document" class="mw-100 mh-100 rounded object-fit-contain"
                                        style="cursor:pointer;" onclick="window.open(this.src, '_blank')">
                                </div>
                            </div>

                            <small class="text-muted mt-1 d-block"><i class="fa-solid fa-info-circle me-1"></i>Click to view
                                full size</small>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($model->contactnote)): ?>
                    <div class="row g-5">
                        <div class="col-12">
                            <label
                                class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('tran', 'tran_note') ?></label>
                            <div class="bg-light rounded p-3" style="min-height: 60px;">
                                <?= nl2br(Html::encode($model->contactnote)) ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card shadow-sm mb-7">
            <div class="card-header min-h-50px bg-light-info">
                <div class="card-title">
                    <i class="fas fa-images text-info fs-4 me-2"></i>

                    <h3 class="fw-bold text-gray-800 fs-6 mb-0"><?= Yii::$app->lang->t('contact', 'moreimage') ?></h3>
                </div>
            </div>
            <div class="card-body py-5">
                <div class="row g-4">
                    <?php if (!empty($modeldocument)): ?>
                        <?php
                        $defaultImgUrl = Yii::getAlias('@web') . '/assets/media/logos/default.png';
                        ?>
                        <?php foreach ($modeldocument as $i => $rowdetail): ?>
                            <?php
                            $fileName = $rowdetail->documentpath;
                            $imgUrl = Yii::getAlias('@web') . '/uploads/contact/' . $fileName;
                            $physicalPath = Yii::getAlias('@webroot') . '/uploads/contact/' . $fileName;

                            if (empty($fileName) || !file_exists($physicalPath)) {
                                $imgUrl = $defaultImgUrl;
                            }
                            ?>
                            <div class="col-lg-2 col-md-3 col-sm-6">
                                <div class="card card-flush border border-dashed border-gray-300"
                                    style="width:150px; height:150px;">
                                    <div class="card-body d-flex align-items-center justify-content-center p-2">
                                        <img src="<?= $imgUrl ?>" alt="Document <?= $i + 1 ?>"
                                            class="mw-100 mh-100 object-fit-contain rounded" style="cursor:pointer;"
                                            onclick="window.open(this.src, '_blank')"
                                            onerror="this.onerror=null; this.src='<?= $defaultImgUrl ?>';">
                                    </div>
                                </div>
                                <small class="text-muted mt-1 d-block text-center">
                                    <i class="fa-solid fa-info-circle me-1"></i>Click to view full size
                                </small>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <p class="text-muted fs-7">-</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>