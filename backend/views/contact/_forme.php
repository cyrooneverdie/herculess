<?php
// contact

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$typeParam = Yii::$app->request->get('contacttype') ?? $model->contacttype;
$mapType = [
    0 => 'Vendor',
    1 => 'Customer',
    2 => 'Supplier',
    3 => 'Employee',
    4 => 'Payroll',
];
$typeName = $mapType[$typeParam] ?? strtolower(trim($typeParam));

switch ($typeName) {
    case 'Customer':
        $this->title = Yii::$app->lang->t('extrasidebar', 'extrasidebar5');
        break;
    case 'Vendor':
        $this->title = Yii::$app->lang->t('extrasidebar', 'extrasidebar2007');
        break;
    case 'Employee':
        $this->title = Yii::$app->lang->t('extrasidebar', 'extrasidebar110');
        break;
    case 'Supplier':
        $this->title = Yii::$app->lang->t('extrasidebar', 'extrasidebar116');
        break;
    case 'Payroll':
        $this->title = Yii::$app->lang->t('extrasidebar', 'extrasidebar303');
        break;
    default:
        $this->title = ucfirst($typeName);
        break;
}
?>

<?php
$form = ActiveForm::begin([
    'id' => 'form-product',
    'method' => 'post',
    'action' => $model->isNewRecord
        ? Url::to(['contact/create', 'contacttype' => $typeParam])
        : Url::to(['contact/update', 'contact_id' => $model->contact_id, 'contacttype' => $model->contacttype]),
    'options' => [
        'enctype' => 'multipart/form-data',
        'multiple' => true,
        'data-pjax' => false,
    ],
    'validateOnSubmit' => true,
    'enableAjaxValidation' => false,
    'enableClientScript' => $isajax ? false : true,
]);
?>

<div id="modal_scrollable_content" style="max-height: 60vh; overflow-y: auto; overflow-x: hidden; padding: 15px;">

    <?php if ($typeName == 'employee') { ?>
        <div class="card card-flush border border-dashed border-gray-300 mb-5">
            <div class="card-header min-h-50px bg-light-primary">
                <div class="card-title">
                    <span class="svg-icon svg-icon-primary me-2">
                        <i class="fas fa-user-tie text-primary fs-4"></i>
                    </span>
                    <h3 class="fw-bold text-gray-800 fs-6 mb-0">Informasi Karyawan</h3>
                </div>
            </div>
            <div class="card-body py-5">
                <div class="row g-5">
                    <div class="col-md-3">
                        <label class="required">
                            <?= Yii::$app->lang->t('contact', 'contact_no') ?>
                        </label>
                        <input type="text" id="contact_no" name="Contact[contact_no]" class="form-control"
                            value="<?= $model->contact_no ?? '' ?>" placeholder="EM-001" required>
                    </div>
                    <div class="col-md-4">
                        <label class="fs-7">
                            <?= Yii::$app->lang->t('contact', 'person') ?>
                        </label>
                        <select id="person" name="Contact[personid]" class="form-select person" data-control="select2">
                            <?php if (!$model->isNewRecord): ?>
                                <option value="<?= $model->personid ?>">
                                    <?= Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='" . $model->personid . "' "); ?>
                                </option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="required">
                            <?= Yii::$app->lang->t('contact', 'contact_name') ?>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-user text-muted fs-7"></i>
                            </span>
                            <input type="text" id="contact_name" name="Contact[contact_name]"
                                class="form-control border-start-0"
                                value="<?= !$model->isNewRecord ? ($model->contact_name ?? '') : '' ?>"
                                placeholder="<?= Yii::$app->lang->t('contact', 'contact_name') ?>" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="card card-flush border border-dashed border-gray-300 mb-5">
        <div class="card-header min-h-50px bg-light-info">
            <div class="card-title">
                <i class="fas fa-id-card text-info fs-4 me-2"></i>
                <h3 class="fw-bold text-gray-800 fs-6 mb-0">Kontak & Detail</h3>
            </div>
        </div>
        <div class="card-body py-5">
            <?php if ($typeName != 'employee') { ?>
                <div class="d-flex align-items-center mb-5 gap-2">
                    <i class="fa-solid fa-user text-primary fs-5"></i>
                    <span class="fw-bold text-gray-700 fs-7 text-uppercase ls-1">
                        PIC 1
                    </span>
                    <div class="separator separator-dashed flex-grow-1"></div>
                </div>
            <?php } ?>

            <div class="row g-5 mb-4">
                <?php if ($typeName != 'employee') { ?>
                    <div class="col-lg-3 col-md-6">
                        <label class="required">
                            <?= Yii::$app->lang->t('contact', 'contact_name') ?>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-user text-muted fs-7"></i>
                            </span>
                            <input type="text" id="contact_name" name="Contact[contact_name]"
                                class="form-control border-start-0"
                                value="<?= !$model->isNewRecord ? ($model->contact_name ?? '') : '' ?>"
                                placeholder="<?= Yii::$app->lang->t('contact', 'contact_name') ?>" required>
                        </div>
                    </div>
                <?php } ?>

                <div class="col-lg-3 col-md-6">
                    <label class="fs-7">
                        <?= Yii::$app->lang->t('contact', 'contact_email1') ?>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-envelope text-muted fs-7"></i>
                        </span>
                        <input type="email" id="contact_email1" name="Contact[contact_email1]"
                            class="form-control border-start-0"
                            value="<?= !$model->isNewRecord ? ($model->contact_email1 ?? '') : '' ?>"
                            placeholder="email@contoh.com">
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="fs-7">
                        <?= Yii::$app->lang->t('contact', 'contact_phone1') ?>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-phone text-muted fs-7"></i>
                        </span>
                        <input type="tel" id="contact_phone1" name="Contact[contact_phone1]"
                            class="form-control border-start-0"
                            value="<?= !$model->isNewRecord ? ($model->contact_phone1 ?? '') : '' ?>"
                            placeholder="+6281234567890" pattern="[\+]?[0-9]{10,15}">
                    </div>
                </div>

                <?php if ($typeName != 'employee') { ?>
                    <div class="col-lg-3 col-md-6">
                        <label class="fs-7">
                            <?= Yii::$app->lang->t('contact', 'jobposition') ?>
                        </label>
                        <input type="text" id="jobposition" name="Contact[jobposition]" class="form-control"
                            value="<?= !$model->isNewRecord ? ($model->jobposition ?? '') : '' ?>"
                            placeholder="<?= Yii::$app->lang->t('contact', 'jobposition') ?>">
                    </div>
                <?php } ?>

                <?php if ($typeName == 'employee') { ?>
                    <div class="col-lg-3 col-md-6">
                        <label class="fs-7">
                            <?= Yii::$app->lang->t('contact', 'contact_birth') ?>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-map-marker-alt text-muted fs-7"></i>
                            </span>
                            <input type="text" id="contact_bop" name="Contact[contact_bop]"
                                class="form-control border-start-0"
                                value="<?= !$model->isNewRecord ? ($model->contact_bop ?? '') : '' ?>"
                                placeholder="<?= Yii::$app->lang->t('contact', 'contact_birth') ?>">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="fs-7">
                            <?= Yii::$app->lang->t('contact', 'contact_bod') ?>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-calendar text-muted fs-7"></i>
                            </span>
                            <input type="text" id="contact_bod" name="Contact[contact_bod]"
                                class="form-control border-start-0 pickdate"
                                value="<?= !$model->isNewRecord ? ($model->contact_bod ?? '') : '' ?>"
                                placeholder="DD/MM/YYYY">
                        </div>
                    </div>
                <?php } ?>
            </div>

        </div>
    </div>

    <?php if ($typeName == 'employee') { ?>
        <div class="card card-flush border border-dashed border-gray-300 mb-5">
            <div class="card-header min-h-50px bg-light-warning">
                <div class="card-title">
                    <i class="fas fa-user-circle text-warning fs-4 me-2"></i>
                    <h3 class="fw-bold text-gray-800 fs-6 mb-0">Data Pribadi</h3>
                </div>
            </div>
            <div class="card-body py-5">
                <div class="row g-5 mb-5">
                    <div class="col-md-4">
                        <label class="fs-7">
                            <?= Yii::$app->lang->t('contact', 'contact_gender') ?>
                        </label>
                        <select id="category" name="Contact[contact_gender]" class="form-select category"
                            data-control="select2">
                            <?php if (!$model->isNewRecord): ?>
                                <option value="<?= $model->contact_gender ?>">
                                    <?= Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='" . $model->contact_gender . "' "); ?>
                                </option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="fs-7">
                            <?= Yii::$app->lang->t('contact', 'contact_married') ?>
                        </label>
                        <select id="contact_married" name="Contact[contact_married]" class="form-select contact_married"
                            data-control="select2">
                            <?php if (!$model->isNewRecord): ?>
                                <option value="<?= $model->contact_married ?>">
                                    <?= Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='" . $model->contact_married . "' "); ?>
                                </option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="fs-7">
                            <?= Yii::$app->lang->t('contact', 'contact_religion') ?>
                        </label>
                        <select id="contact_religion" name="Contact[contact_religion]" class="form-select">
                            <?php if (!$model->isNewRecord): ?>
                                <option value="<?= $model->contact_religion ?>">
                                    <?= Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='" . $model->contact_religion . "' "); ?>
                                </option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="row g-5">
                    <div class="col-lg-2 col-md-4 text-center">
                        <label class="d-block mb-2">
                            <?= Yii::$app->lang->t('contact', 'contactphoto') ?>
                        </label>
                        <label for="contactphototemp"
                            class="d-flex flex-column align-items-center justify-content-center border border-dashed border-gray-300 rounded bg-light-secondary cursor-pointer w-100"
                            style="height:120px">
                            <img id="contactphototemps" src="<?= $model->contactphoto
                                ? Yii::getAlias('@web') . '/uploads/contact/' . $model->contactphoto
                                : Yii::getAlias('@web') . '/assets/media/logos/empty.png' ?>"
                                class="w-100 h-100 rounded object-fit-contain" alt="Foto">
                        </label>
                        <?= $form->field($model, 'contactphototemp')->fileInput([
                            'id' => 'contactphototemp',
                            'accept' => 'image/*',
                            'class' => 'd-none',
                            'onchange' => 'previewContactPhotoFile(event)',
                        ])->label(false) ?>
                    </div>
                    <div class="col-lg-2 col-md-4 text-center">
                        <label class="d-block mb-2">
                            <?= Yii::$app->lang->t('contact', 'nametag') ?>
                        </label>
                        <label for="nametagfiletemp"
                            class="d-flex flex-column align-items-center justify-content-center border border-dashed border-gray-300 rounded bg-light-secondary cursor-pointer w-100"
                            style="height:120px">
                            <img id="nametagfiletemps" src="<?= $model->nametagfile
                                ? Yii::getAlias('@web') . '/uploads/contact/' . $model->nametagfile
                                : Yii::getAlias('@web') . '/assets/media/logos/empty.png' ?>"
                                class="w-100 h-100 rounded object-fit-contain" alt="Nametag">
                        </label>
                        <?= $form->field($model, 'nametagfiletemp')->fileInput([
                            'id' => 'nametagfiletemp',
                            'accept' => 'image/*',
                            'class' => 'd-none',
                            'onchange' => 'previewNametagFile(event)',
                        ])->label(false) ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="card card-flush border border-dashed border-gray-300 mb-5">
        <div class="card-header min-h-50px collapsible cursor-pointer rotate" data-bs-toggle="collapse"
            data-bs-target="#address-section">
            <div class="card-title">
                <i class="fas fa-map-marked-alt text-success fs-4 me-2"></i>
                <h3 class="fw-bold text-gray-800 fs-6 mb-0"><?= Yii::$app->lang->t('extra', 'extra36') ?></h3>
            </div>
            <div class="card-toolbar rotate-180">
                <i class="ki-duotone ki-down fs-3"></i>
            </div>
        </div>
        <div id="address-section" class="collapse">
            <div class="card-body py-5">

                <?php if ($typeName != 'employee') { ?>
                    <div class="d-flex align-items-center mb-5 gap-2">
                        <i class="fa-solid fa-building text-primary fs-5"></i>
                        <span class="fw-bold text-gray-700 fs-7 text-uppercase ls-1">
                            <?= Yii::$app->lang->t('contact', 'compadd') ?>
                        </span>
                        <div class="separator separator-dashed flex-grow-1"></div>
                    </div>
                <?php } ?>

                <div class="row g-5 mb-5">
                    <div class="col-md-4">
                        <label class="fs-7">
                            <?= Yii::$app->lang->t('country', 'label1') ?>
                        </label>
                        <select id="country" name="Contact[countryid]" class="form-select country"
                            data-control="select2">
                            <?php if (!$model->isNewRecord): ?>
                                <option value="<?= $model->countryid ?>" selected>
                                    <?= Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='" . $model->countryid . "' "); ?>
                                </option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="fs-7">
                            <?= Yii::$app->lang->t('state', 'label1') ?>
                        </label>
                        <select id="state" name="Contact[stateid]" class="form-select state" data-control="select2"
                            disabled>
                            <?php if (!$model->isNewRecord): ?>
                                <option value="<?= $model->stateid ?>">
                                    <?= Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='" . $model->stateid . "' "); ?>
                                </option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="fs-7">
                            <?= Yii::$app->lang->t('city', 'label1') ?>
                        </label>
                        <select id="city" name="Contact[cityid]" class="form-select city" data-control="select2"
                            disabled>
                            <?php if (!$model->isNewRecord): ?>
                                <option value="<?= $model->cityid ?>">
                                    <?= Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='" . $model->cityid . "' "); ?>
                                </option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="row g-5 mb-5">
                    <div class="col-md-4">
                        <label class="fs-7">
                            <?= Yii::$app->lang->t('district', 'label1') ?>
                        </label>
                        <select id="district" name="Contact[districtid]" class="form-select district"
                            data-control="select2" disabled>
                            <?php if (!$model->isNewRecord): ?>
                                <option value="<?= $model->districtid ?>">
                                    <?= Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='" . $model->districtid . "' "); ?>
                                </option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="fs-7">
                            <?= Yii::$app->lang->t('contact', 'subdistrictid') ?>
                        </label>
                        <input type="text" id="subdistrictid" name="Contact[subdistrictid]" class="form-control"
                            value="<?= !$model->isNewRecord ? ($model->subdistrictid ?? '') : '' ?>"
                            placeholder="<?= Yii::$app->lang->t('subdistrict', 'type') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="fs-7">
                            <?= Yii::$app->lang->t('contact', 'zip') ?>
                        </label>
                        <input type="text" id="zip" name="Contact[zip]" class="form-control"
                            value="<?= !$model->isNewRecord ? ($model->zip ?? '') : '' ?>"
                            placeholder="<?= Yii::$app->lang->t('code', 'type') ?>">
                    </div>
                </div>

                <div class="row g-5 mb-5">
                    <div class="col-md-12">
                        <label class="fs-7">
                            <?= Yii::$app->lang->t('contact', 'address') ?>
                        </label>
                        <textarea id="address" name="Contact[address]" rows="3"
                            class="form-control"><?= !$model->isNewRecord ? ($model->address ?? '') : '' ?></textarea>
                    </div>
                </div>

                <?php if ($typeName != 'employee') { ?>
                    <div class="separator separator-dashed my-5"></div>

                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-money-bill text-warning fs-5"></i>
                            <span class="fw-bold text-gray-700 fs-7 text-uppercase ls-1">
                                <?= Yii::$app->lang->t('contact', 'billadd') ?>
                            </span>
                            <div class="separator separator-dashed" style="width:60px"></div>
                        </div>
                        <div class="form-check form-check-custom form-check-solid form-check-sm">
                            <input class="form-check-input sameAddress" type="checkbox" id="sameAddress">
                            <label class="form-check-label fw-semibold fs-7 text-gray-600" for="sameAddress">
                                <?= Yii::$app->lang->t('contact', 'same') ?>
                            </label>
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-4">
                            <label class="fs-7">
                                <?= Yii::$app->lang->t('country', 'label1') ?>
                            </label>
                            <select id="billcountry" name="Contact[billcountry]" class="form-select country"
                                data-control="select2">
                                <?php if (!$model->isNewRecord): ?>
                                    <option value="<?= $model->billcountry ?>" selected>
                                        <?= Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='" . $model->billcountry . "' "); ?>
                                    </option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="fs-7">
                                <?= Yii::$app->lang->t('state', 'label1') ?>
                            </label>
                            <select id="billstate" name="Contact[billstate]" class="form-select state"
                                data-control="select2" disabled>
                                <?php if (!$model->isNewRecord): ?>
                                    <option value="<?= $model->billstate ?>">
                                        <?= Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='" . $model->billstate . "' "); ?>
                                    </option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="fs-7">
                                <?= Yii::$app->lang->t('city', 'label1') ?>
                            </label>
                            <select id="billcity" name="Contact[billcity]" class="form-select city" data-control="select2"
                                disabled>
                                <?php if (!$model->isNewRecord): ?>
                                    <option value="<?= $model->billcity ?>">
                                        <?= Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='" . $model->billcity . "' "); ?>
                                    </option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-4">
                            <label class="fs-7">
                                <?= Yii::$app->lang->t('district', 'label1') ?>
                            </label>
                            <select id="billdistrict" name="Contact[billdistrict]" class="form-select district"
                                data-control="select2" disabled>
                                <?php if (!$model->isNewRecord): ?>
                                    <option value="<?= $model->billdistrict ?>">
                                        <?= Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='" . $model->billdistrict . "' "); ?>
                                    </option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="fs-7">
                                <?= Yii::$app->lang->t('contact', 'subdistrictid') ?>
                            </label>
                            <input type="text" id="billsubdistrict" name="Contact[billsubdistrict]" class="form-control"
                                value="<?= !$model->isNewRecord ? ($model->billsubdistrict ?? '') : '' ?>"
                                placeholder="<?= Yii::$app->lang->t('subdistrict', 'type') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="fs-7">
                                <?= Yii::$app->lang->t('contact', 'zip') ?>
                            </label>
                            <input type="text" id="billzip" name="Contact[billzip]" class="form-control"
                                value="<?= !$model->isNewRecord ? ($model->billzip ?? '') : '' ?>"
                                placeholder="<?= Yii::$app->lang->t('code', 'type') ?>">
                        </div>
                    </div>

                    <div class="row g-5">
                        <div class="col-md-12">
                            <label class="fs-7">
                                <?= Yii::$app->lang->t('contact', 'address') ?>
                            </label>
                            <textarea id="billaddress" name="Contact[billaddress]" rows="3"
                                class="form-control"><?= !$model->isNewRecord ? ($model->billaddress ?? '') : '' ?></textarea>
                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>

    <?php if ($model->contacttype == 'employee') { ?>
        <div class="card card-flush border border-dashed border-gray-300 mb-5">
            <div class="card-header min-h-50px collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                data-bs-target="#financial-section">
                <div class="card-title">
                    <i class="fas fa-wallet text-danger fs-4 me-2"></i>
                    <h3 class="fw-bold text-gray-800 fs-6 mb-0"><?= Yii::$app->lang->t('extra', 'extra110') ?></h3>
                </div>
                <div class="card-toolbar rotate-180">
                    <i class="ki-duotone ki-down fs-3"></i>
                </div>
            </div>
            <div id="financial-section" class="collapse">
                <div class="card-body py-5">
                    <div class="row g-5 mb-5">
                        <div class="col-lg-3 col-md-6">
                            <label class="fs-7">
                                <?= Yii::$app->lang->t('contact', 'norek') ?>
                            </label>
                            <input type="text" id="bankaccount_no" name="Contact[bankaccount_no]" class="form-control"
                                value="<?= !$model->isNewRecord ? ($model->bankaccount_no ?? '') : '' ?>"
                                placeholder="<?= Yii::$app->lang->t('contact', 'norek') ?>">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="fs-7">
                                <?= Yii::$app->lang->t('contact', 'bank') ?>
                            </label>
                            <input type="text" id="bankname" name="Contact[bankname]" class="form-control"
                                value="<?= !$model->isNewRecord ? ($model->bankname ?? '') : '' ?>"
                                placeholder="<?= Yii::$app->lang->t('contact', 'bank') ?>">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="fs-7">
                                <?= Yii::$app->lang->t('contact', 'nonpwp') ?>
                            </label>
                            <input type="text" id="npwpno" name="Contact[npwpno]" class="form-control"
                                value="<?= !$model->isNewRecord ? ($model->npwpno ?? '') : '' ?>"
                                placeholder="<?= Yii::$app->lang->t('contact', 'nonpwp') ?>">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="fs-7">
                                <?= Yii::$app->lang->t('contact', 'bpjstk') ?>
                            </label>
                            <input type="text" id="bpjstkno" name="Contact[bpjstkno]" class="form-control"
                                value="<?= !$model->isNewRecord ? ($model->bpjstkno ?? '') : '' ?>"
                                placeholder="<?= Yii::$app->lang->t('contact', 'bpjstk') ?>">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-lg-3 col-md-6">
                            <label class="fs-7">
                                <?= Yii::$app->lang->t('contact', 'bpjstktype') ?>
                            </label>
                            <input type="text" id="bpjstktype" name="Contact[bpjstktype]" class="form-control"
                                value="<?= !$model->isNewRecord ? ($model->bpjstktype ?? '') : '' ?>"
                                placeholder="<?= Yii::$app->lang->t('contact', 'bpjstktype') ?>">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="fs-7">
                                <?= Yii::$app->lang->t('contact', 'bpjskes') ?>
                            </label>
                            <input type="text" id="bpjs_kesno" name="Contact[bpjs_kesno]" class="form-control"
                                value="<?= !$model->isNewRecord ? ($model->bpjs_kesno ?? '') : '' ?>"
                                placeholder="<?= Yii::$app->lang->t('contact', 'bpjskes') ?>">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="fs-7">
                                <?= Yii::$app->lang->t('contact', 'bpjskestype') ?>
                            </label>
                            <input type="text" id="bpjs_kestype" name="Contact[bpjs_kestype]" class="form-control"
                                value="<?= !$model->isNewRecord ? ($model->bpjs_kestype ?? '') : '' ?>"
                                placeholder="<?= Yii::$app->lang->t('contact', 'bpjskestype') ?>">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="fs-7">
                                <?= Yii::$app->lang->t('contact', 'asuransi') ?>
                            </label>
                            <input type="text" id="insurancename" name="Contact[insurancename]" class="form-control"
                                value="<?= !$model->isNewRecord ? ($model->insurancename ?? '') : '' ?>"
                                placeholder="<?= Yii::$app->lang->t('contact', 'asuransi') ?>">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-lg-3 col-md-6">
                            <label class="fs-7">
                                <?= Yii::$app->lang->t('contact', 'asuransilain') ?>
                            </label>
                            <input type="text" name="Contact[insuranceother]" class="form-control"
                                value="<?= !$model->isNewRecord ? ($model->insuranceother ?? '') : '' ?>"
                                placeholder="<?= Yii::$app->lang->t('contact', 'asuransilain') ?>">
                        </div>
                    </div>

                    <div class="row g-5">
                        <div class="col-lg-2 col-md-4 text-center">
                            <label class="d-block mb-2">
                                <?= Yii::$app->lang->t('contact', 'filenpwp') ?>
                            </label>
                            <label for="formFile"
                                class="d-flex flex-column align-items-center justify-content-center border border-dashed border-gray-300 rounded bg-light-secondary cursor-pointer w-100"
                                style="height:120px">
                                <img id="npwpfiletemp" src="<?= $model->npwpfile
                                    ? Yii::getAlias('@web') . '/uploads/contact/' . $model->npwpfile
                                    : Yii::getAlias('@web') . '/assets/media/logos/empty.png' ?>"
                                    class="w-100 h-100 rounded object-fit-contain" alt="Preview">
                            </label>
                            <?= $form->field($model, 'npwpfiletemp')->fileInput([
                                'id' => 'formFile',
                                'accept' => 'image/*',
                                'class' => 'd-none',
                                'onchange' => 'previewNpwpFile(event)',
                            ])->label(false) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if ($model->contacttype == 'employee') { ?>
        <div class="card card-flush border border-dashed border-gray-300 mb-5">
            <div class="card-header min-h-50px collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                data-bs-target="#emergency-section">
                <div class="card-title">
                    <i class="fas fa-ambulance text-danger fs-4 me-2"></i>
                    <h3 class="fw-bold text-gray-800 fs-6 mb-0"><?= Yii::$app->lang->t('extra', 'extra111') ?></h3>
                </div>
                <div class="card-toolbar rotate-180">
                    <i class="ki-duotone ki-down fs-3"></i>
                </div>
            </div>
            <div id="emergency-section" class="collapse">
                <div class="card-body py-5">
                    <div class="row g-5">
                        <div class="col-lg-4 col-md-6">
                            <label class="fs-7">
                                <?= Yii::$app->lang->t('contact', 'contact_name') ?>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-user text-muted fs-7"></i>
                                </span>
                                <input type="text" id="relativename" name="Contact[relativename]"
                                    class="form-control border-start-0"
                                    value="<?= !$model->isNewRecord ? ($model->relativename ?? '') : '' ?>"
                                    placeholder="<?= Yii::$app->lang->t('contact', 'contact_name') ?>">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <?= $form->field($model, 'relativerelation', [
                                'template' => '<label class="fs-7">{label}</label>{input}{error}',
                            ])->dropDownList(
                                    ['0' => 'Orang Tua', '1' => 'Suami/Istri', '2' => 'Anak', '3' => 'Saudara', '4' => 'Lainnya'],
                                    ['class' => 'form-select', 'data-control' => 'select2']
                                ) ?>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label class="fs-7">
                                <?= Yii::$app->lang->t('contact', 'contact_phone1') ?>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-phone text-muted fs-7"></i>
                                </span>
                                <input type="tel" id="relativephone" name="Contact[relativephone]"
                                    class="form-control border-start-0"
                                    value="<?= !$model->isNewRecord ? ($model->relativephone ?? '') : '' ?>"
                                    placeholder="+6281234567890" pattern="[\+]?[0-9]{10,15}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="card card-flush border border-dashed border-gray-300 mb-5">
        <div class="card-header min-h-50px collapsible cursor-pointer rotate" data-bs-toggle="collapse"
            data-bs-target="#details-section">
            <div class="card-title">
                <i class="fas fa-file-alt text-primary fs-4 me-2"></i>
                <h3 class="fw-bold text-gray-800 fs-6 mb-0"><?= Yii::$app->lang->t('extra', 'extra37') ?></h3>
            </div>
            <div class="card-toolbar rotate-180">
                <i class="ki-duotone ki-down fs-3"></i>
            </div>
        </div>
        <div id="details-section" class="collapse">
            <div class="card-body py-5">
                <div class="row g-5 mb-5">
                    <div class="col-lg-3 col-md-5">
                        <label class="fs-7">
                            <?= Yii::$app->lang->t('contact', 'idtype') ?>
                        </label>
                        <select id="idtype" name="Contact[idtype]" class="form-select idtype" data-control="select2">
                            <?php if (!$model->isNewRecord): ?>
                                <option value="<?= $model->idtype ?>">
                                    <?= Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='" . $model->idtype . "' "); ?>
                                </option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-lg-5 col-md-7">
                        <label class="fs-7">
                            <?= Yii::$app->lang->t('contact', 'idnumber') ?>
                        </label>
                        <input type="text" id="idnumber" name="Contact[idnumber]" class="form-control"
                            value="<?= !$model->isNewRecord ? ($model->idnumber ?? '') : '' ?>" placeholder="ID Number">
                    </div>
                    <?php if ($model->contacttype == 'employee') { ?>
                        <div class="col-lg-4 col-md-5">
                            <label class="fs-7">
                                <?= Yii::$app->lang->t('contact', 'education') ?>
                            </label>
                            <select id="contact_education" name="Contact[contact_education]"
                                class="form-select contact_education" data-control="select2">
                                <?php if (!$model->isNewRecord && !empty($model->contact_education)): ?>
                                    <option value="<?= $model->contact_education ?>">
                                        <?= Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='" . $model->contact_education . "' "); ?>
                                    </option>
                                <?php endif; ?>
                            </select>
                        </div>
                    <?php } ?>
                </div>

                <?php if ($model->contacttype == 'employee') { ?>
                    <div class="row g-5 mb-5">
                        <div class="col-md-3">
                            <label class="fs-7">
                                <?= Yii::$app->lang->t('status', 'label1') ?>
                            </label>
                            <select id="status" name="Contact[contractid]" class="form-select status"
                                data-control="select2">
                                <?php if (!$model->isNewRecord): ?>
                                    <option value="<?= $model->contractid ?>">
                                        <?= Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='" . $model->contractid . "' "); ?>
                                    </option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="fs-7">
                                <?= Yii::$app->lang->t('contact', 'jobstart') ?>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-calendar-check text-muted fs-7"></i>
                                </span>
                                <input type="text" id="jobstart" name="Contact[jobstart]"
                                    class="form-control border-start-0 pickdate"
                                    value="<?= !$model->isNewRecord ? ($model->jobstart ?? '') : '' ?>"
                                    placeholder="DD/MM/YYYY">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="fs-7">
                                <?= Yii::$app->lang->t('contact', 'jobend') ?>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-calendar-times text-muted fs-7"></i>
                                </span>
                                <input type="text" id="jobend" name="Contact[jobend]"
                                    class="form-control border-start-0 pickdate"
                                    value="<?= !$model->isNewRecord ? ($model->jobend ?? '') : '' ?>"
                                    placeholder="DD/MM/YYYY">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="fs-7">
                                <?= Yii::$app->lang->t('position', 'label1') ?>
                            </label>
                            <select id="position" name="Contact[positionid]" class="form-select position"
                                data-control="select2">
                                <?php if (!$model->isNewRecord): ?>
                                    <option value="<?= $model->positionid ?>">
                                        <?= Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='" . $model->positionid . "' "); ?>
                                    </option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                <?php } ?>

                <div class="row g-5 mb-5">
                    <div class="col-lg-2 col-md-4 text-center">
                        <label class="d-block mb-2">
                            <?= Yii::$app->lang->t('contact', 'contactpict') ?>
                        </label>
                        <div class="card card-flush border border-dashed border-gray-300 cursor-pointer"
                            style="width:150px; height:150px;">
                            <label for="idfiletemp"
                                class="card-body d-flex align-items-center justify-content-center p-2 cursor-pointer h-100">
                                <img id="idfiletemps" src="<?= $model->idfile
                                    ? Yii::getAlias('@web') . '/uploads/contact/' . $model->idfile
                                    : Yii::getAlias('@web') . '/assets/media/logos/empty.png' ?>"
                                    class="mw-100 mh-100 rounded object-fit-contain" alt="Preview">
                            </label>
                        </div>
                        <?= $form->field($model, 'idfiletemp')->fileInput([
                            'id' => 'idfiletemp',
                            'accept' => 'image/*',
                            'class' => 'd-none',
                            'onchange' => 'previewImage(event)',
                        ])->label(false) ?>
                    </div>
                </div>

                <div class="row g-5">
                    <div class="col-md-12">
                        <label class="fs-7">
                            <?= Yii::$app->lang->t('tran', 'tran_note') ?>
                        </label>
                        <textarea id="contactnote" name="Contact[contactnote]" rows="3"
                            class="form-control"><?= !$model->isNewRecord ? ($model->contactnote ?? '') : '' ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade addMasterModalLabel" id="addMasterModal" tabindex="-1" role="dialog"
        aria-labelledby="addMasterModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"></div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-3 pt-5">
        <button type="button" class="btn btn-light btn-sm fw-semibold" data-kt-users-modal-action="cancel"
            data-bs-dismiss="modal">
            <i class="fas fa-times fs-7 me-1"></i>
            <?= Yii::$app->lang->t('back_home', 'chat34') ?>
        </button>
        <?= Html::submitButton(
            '<i class="fas fa-save fs-7 me-1"></i>' . Yii::$app->lang->t('extra', 'extra16'),
            [
                'id' => 'btnsubmit',
                'class' => $model->isNewRecord ? 'btn btn-success btn-sm fw-semibold' : 'btn btn-primary btn-sm fw-semibold',
                'encode' => false
            ]
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php
$script = <<<JS
function toggleSection(buttonId, sectionId) {
  $(buttonId).on('click', function() {
    const section = $(sectionId);
    const icon = $(this).find('i');
    if (section.is(':visible')) {
        section.slideUp();
        icon.removeClass('fa-minus').addClass('fa-plus');
    } else {
        section.slideDown();
        icon.removeClass('fa-plus').addClass('fa-minus');
    }
  });
}

toggleSection('#toggle-address', '#address-section');
toggleSection('#toggle-details', '#details-section');
toggleSection('#toggle-financial', '#financial-section');
toggleSection('#toggle-emergency', '#emergency-section');
toggleSection('#toggle-image', '#image-section');
JS;
$this->registerJs($script);
?>
<script>
    $(document).ready(function () {
        initMasking();

        function initEnumSelect2(selector, enumtype, placeholder, refSelector = null) {
            $(selector).select2({
                placeholder: placeholder,
                allowClear: true,
                ajax: {
                    url: "<?= Url::to(['enum/list']) ?>",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            enumtype: enumtype,
                            refid: refSelector ? $(refSelector).val() : '',
                            search: params.term || '',
                            page: params.page || 1,
                            for: 'select2'
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.data.map(function (item) {
                                return {
                                    id: item.enumid,
                                    text: item.enumtext_id,
                                    code: item.enum_code_id
                                };
                            }),
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 0,
                width: '100%',
                dropdownParent: $('#form-product'),
            }).on('select2:open', function () {
                let $dropdown = $('.select2-dropdown');
                $dropdown.find('.add-new-btn').remove();
                if (enumtype !== 'vendortype') {
                    $dropdown.append(`
                    <div class="add-new-btn" 
                        style="padding:6px;text-align:center;border-top:1px solid #ddd;background:#f8f9fa;">
                        <button type="button" class="btn btn-sm btn-primary add-enum-btn" data-enumtype="${enumtype}">
                            <i class="fas fa-plus-circle me-1"></i> Tambah ${enumtype}
                        </button>
                    </div>
                `);
                }
            });
        }

        initEnumSelect2('#category', 'gender', '<?= Yii::$app->lang->t('contact', 'contact_gender') ?>');
        initEnumSelect2('#contact_married', 'married', '<?= Yii::$app->lang->t('contact', 'contact_married') ?>');
        initEnumSelect2('#country', 'country', '<?= Yii::$app->lang->t('country', 'select') ?>');
        $('#country').on('change', function () {
            $('#state').prop('disabled', true).val(null).trigger('change');
            $('#city').prop('disabled', true).val(null).trigger('change');
            $('#district').prop('disabled', true).val(null).trigger('change');
            initEnumSelect2('#state', 'state', '<?= Yii::$app->lang->t('state', 'select') ?>', '#country');
        });
        $('#state').on('change', function () {
            $('#city').prop('disabled', true).val(null).trigger('change');
            $('#district').prop('disabled', true).val(null).trigger('change');
            initEnumSelect2('#city', 'city', '<?= Yii::$app->lang->t('city', 'select') ?>', '#state');
        });
        $('#city').on('change', function () {
            $('#district').prop('disabled', true).val(null).trigger('change');
            initEnumSelect2('#district', 'district', '<?= Yii::$app->lang->t('contact', 'district') ?>', '#city');
        });

        // BILLING ADDRESS
        initEnumSelect2('#billcountry', 'country', '<?= Yii::$app->lang->t('country1', 'select') ?>');
        $('#billcountry').on('change', function () {
            $('#billstate').prop('disabled', true).val(null).trigger('change');
            $('#billcity').prop('disabled', true).val(null).trigger('change');
            $('#billdistrict').prop('disabled', true).val(null).trigger('change');
            initEnumSelect2('#billstate', 'state', '<?= Yii::$app->lang->t('state1', 'select') ?>', '#billcountry');
        });
        $('#billstate').on('change', function () {
            $('#billcity').prop('disabled', true).val(null).trigger('change');
            $('#billdistrict').prop('disabled', true).val(null).trigger('change');
            initEnumSelect2('#billcity', 'city', '<?= Yii::$app->lang->t('city1', 'select') ?>', '#billstate');
        });
        $('#billcity').on('change', function () {
            $('#billdistrict').prop('disabled', true).val(null).trigger('change');
            initEnumSelect2('#billdistrict', 'district', '<?= Yii::$app->lang->t('district1', 'select') ?>', '#billcity');
        });

        <?php if (!$model->isNewRecord): ?>
            $('#state, #city, #district').prop('disabled', false);
            initEnumSelect2('#state', 'state', 'Select State', '#country');
            initEnumSelect2('#city', 'city', 'Select City', '#state');
            initEnumSelect2('#district', 'district', 'Select District', '#city');
        <?php endif; ?>

        <?php if (!$model->isNewRecord): ?>
            $('#billstate, #billcity, #billdistrict').prop('disabled', false);
            initEnumSelect2('#billstate', 'state', 'Select billstate', '#billcountry');
            initEnumSelect2('#billcity', 'city', 'Select billcity', '#billstate');
            initEnumSelect2('#billdistrict', 'district', 'Select billdistrict', '#billcity');
        <?php endif; ?>

        initEnumSelect2('#idtype', 'idtype', '<?= Yii::$app->lang->t('contact', 'idtype') ?>');
        initEnumSelect2('#division', 'division', '<?= Yii::$app->lang->t('contact', 'devision') ?>');
        initEnumSelect2('#status', 'status', '<?= Yii::$app->lang->t('contact', 'status') ?>');
        initEnumSelect2('#level', 'level', '<?= Yii::$app->lang->t('contact', 'level') ?>');
        initEnumSelect2('#person', 'contactperson', '<?= Yii::$app->lang->t('contact', 'contactperson') ?>');
        initEnumSelect2('#position', 'position', '<?= Yii::$app->lang->t('contact', 'position') ?>');
        initEnumSelect2('#contact_education', 'education', '<?= Yii::$app->lang->t('contact', 'education') ?>');
        initEnumSelect2('#contact_religion', 'religion', '<?= Yii::$app->lang->t('contact', 'contact_religion') ?>');
        initEnumSelect2('.type-select', 'vendortype', '<?= Yii::$app->lang->t('produk_table', 'produk_jenis') ?>');

        function undisabled(selector1, selector2) {
            $(selector1).on('select2:select', function (e) {

                $(selector2).prop('disabled', false).val(null).trigger('change');
            });
        }

        function disabled(selector1, selector2) {
            $(selector1).on('select2:clear', function () {
                $(selector2).prop('disabled', true).val(null).trigger('change');
            });
        }
        undisabled('#country', '#state');
        undisabled('#state', '#city');
        undisabled('#city', '#district');
        disabled('#country', '#state');
        disabled('#country', '#city');
        disabled('#country', '#district');
        disabled('#state', '#city');
        disabled('#state', '#district');
        disabled('#city', '#district');

        undisabled('#billcountry', '#billstate');
        undisabled('#billstate', '#billcity');
        undisabled('#billcity', '#billdistrict');
        disabled('#billcountry', '#billstate');
        disabled('#billcountry', '#billcity');
        disabled('#billcountry', '#billdistrict');
        disabled('#billstate', '#billcity');
        disabled('#billstate', '#billdistrict');
        disabled('#billcity', '#billdistrict');

        function repeat(selection) {
            $(selection).repeater({
                initEmpty: false,

                show: function () {
                    $(this).slideDown();

                },

                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            });
        }

        repeat('#document_repeater');

        <?php
        if ($isajax) {
            ?>
            $('#form-product').on('submit', function (e) {
                e.preventDefault();
                $('#billstate, #billcity, #billdistrict').prop('disabled', false);
                $('#btnsubmit').prop('disabled', true);

                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#btnsubmit').prop('disabled', false);
                        if (data.success) {
                            $('#datatable').DataTable().ajax.reload(null, false);
                            $('.addContactModal').modal('hide');
                            $('.addMasterModalLabel').modal('hide');
                            $('#modal_form_contact').modal('hide');

                            setTimeout(function () {
                                Swal.fire({
                                    icon: "success",
                                    title: "Successful",
                                    text: data.pesan
                                });
                            }, 300);

                        } else {
                            Swal.fire({
                                icon: "warning",
                                title: "Warning",
                                text: data.pesan
                            });
                        }
                    },
                    error: function (e) {
                        $('#btnsubmit').prop('disabled', false);
                        Swal.fire({
                            icon: "error",
                            title: "Failed",
                            text: "Something went wrong, please call you Administrator!",
                        });
                    }
                });
                return false;
            });
        <?php }
        ?>

    });

    $(document).on('click', '.add-new-btn button', function () {
        var enumtype = $(this).data('enumtype');
        $('.select2-hidden-accessible').select2('close');

        $.get('/enum/load', {
            enumtype: enumtype
        }, function (html) {
            $('#addMasterModal .modal-content').html(html);
            $('#addMasterModal').modal('show');
        });
    });

    function previewImage(event) {
        const input = event.target;
        const file = input.files[0];

        if (!file) return;
        const previewId = input.getAttribute('data-preview') || 'previewImage';
        const labelId = input.getAttribute('data-label') || 'previewLabel';
        const imgPreview = document.getElementById(previewId);
        const label = document.getElementById(labelId);

        if (label) label.textContent = file.name;

        const reader = new FileReader();
        reader.onload = function (e) {
            if (imgPreview) imgPreview.src = e.target.result;
        };
        reader.readAsDataURL(file);

        if (imgPreview) {
            setTimeout(() => {
                imgPreview.classList.add('opacity-75');
                setTimeout(() => imgPreview.classList.remove('opacity-75'), 300);
            }, 150);
        }
    }

    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('idfiletemps');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewNpwpFile(event) {
        const input = event.target;
        const preview = document.getElementById('npwpfiletemp');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewNpwpFile2(event) {
        const input = event.target;
        const preview = document.getElementById('npwpfiletemp2');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewNpwpFile3(event) {
        const input = event.target;
        const preview = document.getElementById('npwpfiletemp3');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewContactPhotoFile(event) {
        const input = event.target;
        const preview = document.getElementById('contactphototemps');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewNametagFile(event) {
        const input = event.target;
        const preview = document.getElementById('nametagfiletemps');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewRepeaterImage(event) {
        const input = event.target;
        if (!input.files || !input.files[0]) return;

        const container = input.closest('[data-repeater-item]');
        if (!container) return;

        const preview = container.querySelector('.preview-image');
        const reader = new FileReader();

        reader.onload = e => {
            preview.src = e.target.result;
        };

        reader.readAsDataURL(input.files[0]);
    }

    function setSelect2Value(selector, id, text) {
        if (!id) return;

        let option = new Option(text, id, true, true);
        $(selector).append(option).trigger('change');
    }

    $('.sameAddress').on('change', function () {
        if (this.checked) {

            setSelect2Value(
                '#billcountry',
                $('#country').val(),
                $('#country').select2('data')[0]?.text
            );

            setTimeout(() => {
                setSelect2Value(
                    '#billstate',
                    $('#state').val(),
                    $('#state').select2('data')[0]?.text
                );

                setTimeout(() => {
                    setSelect2Value(
                        '#billcity',
                        $('#city').val(),
                        $('#city').select2('data')[0]?.text
                    );

                    setTimeout(() => {
                        setSelect2Value(
                            '#billdistrict',
                            $('#district').val(),
                            $('#district').select2('data')[0]?.text
                        );
                    }, 300);

                }, 300);
            }, 300);

            $('#billsubdistrict').val($('#subdistrictid').val());
            $('#billzip').val($('#zip').val());
            $('#billaddress').val($('#address').val());

        } else {
            $('#billcountry, #billstate, #billcity, #billdistrict')
                .val(null)
                .trigger('change');

            $('#billsubdistrict, #billzip, #billaddress').val('');
        }
    });

</script>