<?php
// product

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Document;

?>

<?php
$form = ActiveForm::begin([
    'id' => 'form-product',
    'method' => 'post',
    'action' => $model->isNewRecord
        ? Url::to(['product/create'])
        : Url::to(['product/update', 'id' => $model->productid]),
    'options' => [
        'enctype' => 'multipart/form-data',
        'multiple' => true,
        'data-pjax' => false
    ],
    'fieldConfig' => [
        'labelOptions' => ['class' => 'form-label fw-bold text-gray-700 fs-7'],
    ],
    'validateOnSubmit' => true,
    'enableAjaxValidation' => false,
    'enableClientScript' => $isajax ? false : true,
]);
?>

<style>
    .swal2-container {
        z-index: 100000 !important;
    }
</style>

<div class="d-flex flex-column scroll-y px-5 px-lg-10" id="modal_form_product_scroll" data-kt-scroll="false"
    data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
    data-kt-scroll-dependencies="#modal_form_product_header" data-kt-scroll-wrappers="#modal_form_product_scroll"
    data-kt-scroll-offset="300px">

    <div class="card card-bordered p-6 mb-5">
        <div class="row g-4">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <?= $form->field($model, 'productname')->textInput([
                    'class' => 'form-control',
                    'placeholder' => 'Contoh: Paket 1 Bulan',
                    'required' => true,
                ]) ?>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card card-bordered-solid p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <label class="form-label fw-bold mb-0" for="type-switch">Paket Drop-in</label>
                            <div class="text-muted fs-7">Bayar perkunjungan tanpa membership aktif</div>
                        </div>
                        <div class="form-check form-switch form-check-custom form-check-success form-check-solid">
                            <?= $form->field($model, 'type', [
                                'template' => "{input}\n{error}",
                                'options' => ['tag' => false]
                            ])->checkbox([
                                        'class' => 'form-check-input',
                                        'id' => 'type-switch',
                                        'uncheck' => 0,
                                        'value' => 1,
                                        'checked' => (int) $model->type === 1,
                                    ], false) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-bordered p-5 mb-5 durasi-membership">
        <label class="form-label fw-bolder text-gray-700 text-uppercase fs-7 mb-4">
            Durasi Membership
        </label>

        <?= $form->field($model, 'duration_days')->hiddenInput(['id' => 'final-duration-days'])->label(false) ?>

        <div class="row g-3 mb-4">
            <?php
            $options = [
                '1_day' => '1 Hari',
                '1_week' => '1 Minggu',
                '2_weeks' => '2 Minggu',
                '1_month' => '1 Bulan',
                '3_months' => '3 Bulan',
                '6_months' => '6 Bulan',
                '1_year' => '1 Tahun',
                'custom' => '<i class="bi bi-pencil me-1"></i> Kustom',
            ];

            foreach ($options as $val => $label):
                $checked = ($model->duration_type === $val) ? 'checked' : '';
                ?>
                <div class="col-lg-3 col-md-4 col-6">
                    <input type="radio" class="btn-check" name="duration_option" value="<?= $val ?>" id="dur_<?= $val ?>"
                        <?= $checked ?>>
                    <label
                        class="btn btn-outline-primary btn-active-dark p-2 w-100 text-center"
                        for="dur_<?= $val ?>">
                        <?= $label ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="custom-duration-container" class="bg-light rounded p-4 d-none">
            <div class="row g-3 align-items-center">
                <div class="col-md-3 col-4">
                    <input type="number" id="custom-value" class="form-control form-control-solid text-center fw-bold"
                        value="<?= $model->custom_duration_value ?? 2 ?>" min="1">
                </div>
                <div class="col-md-6 col-8">
                    <select id="custom-unit" class="form-select form-select-solid fw-bold">
                        <option value="days" <?= ($model->custom_duration_unit === 'days') ? 'selected' : '' ?>>Hari
                        </option>
                        <option value="weeks" <?= ($model->custom_duration_unit === 'weeks') ? 'selected' : '' ?>>Minggu
                        </option>
                        <option value="months" <?= ($model->custom_duration_unit === 'months' || empty($model->custom_duration_unit)) ? 'selected' : '' ?>>Bulan</option>
                        <option value="years" <?= ($model->custom_duration_unit === 'years') ? 'selected' : '' ?>>Tahun
                        </option>
                    </select>
                </div>
                <div class="col-md-3 col-12 text-end text-md-start">
                    <span class="text-muted fs-6 fw-semibold">= <span id="custom-total-days"
                            class="fw-bold text-dark">60</span> hari</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-bordered p-6 mb-5">
        <div class="row g-4">
            <div class="col-lg-6 col-md-6 col-sm-12 d-none harga-perkunjungan">
                <label class="form-label fw-bold text-gray-700 fs-7">
                    Harga per Kunjungan
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="fa-solid fa-money-bill"></i>
                    </span>
                    <?= $form->field($model, 'purchaseprice', [
                        'options' => ['tag' => false]
                    ])->textInput([
                                'class' => 'form-control money border-start-0 rounded-end',
                                'placeholder' => 'Rp. 300.000',
                            ])->label(false) ?>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 harga-perperiode">
                <label class="form-label fw-bold text-gray-700 fs-7">
                    Harga per Periode
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="fa-solid fa-money-bill"></i>
                    </span>
                    <?= $form->field($model, 'purchaseprice', [
                        'options' => ['tag' => false]
                    ])->textInput([
                                'class' => 'form-control money border-start-0 rounded-end',
                                'placeholder' => 'Rp. 300.000',
                            ])->label(false) ?>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 harga-pendaftaran">
                <label class="form-label fw-bold text-gray-700 fs-7">
                    Biaya Pendaftaran
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="fa-solid fa-money-bill"></i>
                    </span>
                    <?= $form->field($model, 'sellprice', [
                        'options' => ['tag' => false]
                    ])->textInput([
                                'class' => 'form-control money border-start-0 rounded-end',
                                'placeholder' => 'Rp. 0',
                            ])->label(false) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-bordered p-6 mb-5 kuota-batasan">
        <div class="mb-5">
            <h5 class="fw-bolder text-gray-800 text-uppercase fs-6 mb-1">Kuota & Batasan</h5>
            <span class="text-muted fs-7">Atur apa saja yang termasuk di paket ini dan berapa batasnya</span>
        </div>

        <div class="py-4 border-bottom border-gray-200">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="symbol symbol-40px bg-light-primary p-2 rounded">
                        <i class="bi bi-qr-code-scan fs-3 text-primary"></i>
                    </div>
                    <div>
                        <div class="fw-bolder fs-6 text-gray-800">Kunjungan</div>
                        <div class="text-muted fs-7" id="visit-subtitle">Maks 10 kunjungan per periode paket</div>
                    </div>
                </div>
                <div class="nav-group bg-light p-1 rounded-pill">
                    <input type="radio" class="btn-check" name="Visit[mode]" value="unlimited" id="visit_mode_unlimited"
                        <?= ($model->visit_mode === 'unlimited' || empty($model->visit_mode)) ? 'checked' : '' ?>>
                    <label class="btn btn-sm btn-color-gray-600 btn-active-primary rounded-pill px-2 py-1 fs-8 fw-bold"
                        for="visit_mode_unlimited">Unlimited</label>

                    <input type="radio" class="btn-check" name="Visit[mode]" value="custom" id="visit_mode_custom"
                        <?= ($model->visit_mode === 'custom') ? 'checked' : '' ?>>
                    <label class="btn btn-sm btn-color-gray-600 btn-active-primary rounded-pill px-2 py-1 fs-8 fw-bold"
                        for="visit_mode_custom">Custom</label>
                </div>
            </div>

            <div id="visit-custom-container" class="d-none">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="flex-grow-1">
                        <?= $form->field($model, 'visit_limit')->textInput([
                            'class' => 'form-control form-control-solid rounded-pill text-start ps-5',
                            'id' => 'input-visit-limit'
                        ])->label(false) ?>
                    </div>
                    <div class="nav-group bg-light p-1 rounded-pill">
                        <input type="radio" class="btn-check" name="Visit[period]" value="periode"
                            id="visit_period_periode" <?= ($model->visit_period === 'periode' || empty($model->visit_period)) ? 'checked' : '' ?>>
                        <label
                            class="btn btn-sm btn-color-gray-600 btn-active-primary rounded-pill px-2 py-1 fs-8 fw-bold"
                            for="visit_period_periode">Per Periode</label>

                        <input type="radio" class="btn-check" name="Visit[period]" value="bulan" id="visit_period_bulan"
                            <?= ($model->visit_period === 'bulan') ? 'checked' : '' ?>>
                        <label
                            class="btn btn-sm btn-color-gray-600 btn-active-primary rounded-pill px-2 py-1 fs-8 fw-bold"
                            for="visit_period_bulan">Per Bulan</label>
                    </div>
                </div>

                <div class="bg-light rounded p-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-eye-slash fs-3 text-gray-600"></i>
                        <div>
                            <div class="fw-bolder fs-6 text-gray-800">Habis kunjungan = expired</div>
                            <div class="text-muted fs-7">Kuota habis &rarr; member masuk masa perpanjangan (H-7)</div>
                        </div>
                    </div>
                    <div class="nav-group bg-light p-1 rounded-pill">
                        <input type="radio" class="btn-check" name="Visit[expired_status]" value="0"
                            id="expired_nonaktif" <?= ((int) $model->visit_expired_status === 0 || empty($model->visit_expired_status)) ? 'checked' : '' ?>>
                        <label
                            class="btn btn-sm btn-color-gray-600 btn-active-primary rounded-pill px-2 py-1 fs-8 fw-bold"
                            for="expired_nonaktif">Nonaktif</label>

                        <input type="radio" class="btn-check" name="Visit[expired_status]" value="1" id="expired_aktif"
                            <?= ((int) $model->visit_expired_status === 1) ? 'checked' : '' ?>>
                        <label
                            class="btn btn-sm btn-color-gray-600 btn-active-primary rounded-pill px-2 py-1 fs-8 fw-bold"
                            for="expired_aktif">Aktif</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-4 border-bottom border-gray-200">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="symbol symbol-40px bg-light-primary p-2 rounded">
                        <i class="bi bi-person-workspace fs-3 text-primary"></i>
                    </div>
                    <div>
                        <div class="fw-bolder fs-6 text-gray-800">Sesi Coaching</div>
                        <div class="text-muted fs-7" id="coaching-subtitle">Maks 10 sesi per paket</div>
                    </div>
                </div>
                <div class="nav-group bg-light p-1 rounded-pill">
                    <input type="radio" class="btn-check" name="Coaching[mode]" value="none" id="coaching_mode_none"
                        <?= ($model->coaching_mode === 'none' || empty($model->coaching_mode)) ? 'checked' : '' ?>>
                    <label class="btn btn-sm btn-color-gray-600 btn-active-primary rounded-pill px-2 py-1 fs-8 fw-bold"
                        for="coaching_mode_none">Tidak Ada</label>

                    <input type="radio" class="btn-check" name="Coaching[mode]" value="unlimited"
                        id="coaching_mode_unlimited" <?= ($model->coaching_mode === 'unlimited') ? 'checked' : '' ?>>
                    <label class="btn btn-sm btn-color-gray-600 btn-active-primary rounded-pill px-2 py-1 fs-8 fw-bold"
                        for="coaching_mode_unlimited">Unlimited</label>

                    <input type="radio" class="btn-check" name="Coaching[mode]" value="custom" id="coaching_mode_custom"
                        <?= ($model->coaching_mode === 'custom') ? 'checked' : '' ?>>
                    <label class="btn btn-sm btn-color-gray-600 btn-active-primary rounded-pill px-2 py-1 fs-8 fw-bold"
                        for="coaching_mode_custom">Custom</label>
                </div>
            </div>

            <div id="coaching-custom-container" class="d-flex align-items-center gap-3 d-none">
                <div class="flex-grow-1">
                    <?= $form->field($model, 'coaching_limit')->textInput([
                        'class' => 'form-control form-control-solid rounded-pill text-start ps-5',
                        'id' => 'input-coaching-limit'
                    ])->label(false) ?>
                </div>
                <div class="nav-group bg-light p-1 rounded-pill">
                    <input type="radio" class="btn-check" name="Coaching[period]" value="periode"
                        id="coaching_period_periode" <?= ($model->coaching_period === 'periode' || empty($model->coaching_period)) ? 'checked' : '' ?>>
                    <label class="btn btn-sm btn-color-gray-600 btn-active-primary rounded-pill px-2 py-1 fs-8 fw-bold"
                        for="coaching_period_periode">Per Periode</label>

                    <input type="radio" class="btn-check" name="Coaching[period]" value="bulanan"
                        id="coaching_period_bulanan" <?= ($model->coaching_period === 'bulanan') ? 'checked' : '' ?>>
                    <label class="btn btn-sm btn-color-gray-600 btn-active-primary rounded-pill px-2 py-1 fs-8 fw-bold"
                        for="coaching_period_bulanan">Bulanan</label>
                </div>
            </div>
        </div>

        <div class="pt-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="symbol symbol-40px bg-light-primary p-2 rounded">
                        <i class="bi bi-calendar-event fs-3 text-primary"></i>
                    </div>
                    <div>
                        <div class="fw-bolder fs-6 text-gray-800">Kuota Booking</div>
                        <div class="text-muted fs-7" id="booking-subtitle">Maks 10 booking per minggu</div>
                    </div>
                </div>
                <div class="nav-group bg-light p-1 rounded-pill">
                    <input type="radio" class="btn-check" name="Booking[mode]" value="none" id="booking_mode_none"
                        <?= ($model->booking_mode === 'none' || empty($model->booking_mode)) ? 'checked' : '' ?>>
                    <label class="btn btn-sm btn-color-gray-600 btn-active-primary rounded-pill px-2 py-1 fs-8 fw-bold"
                        for="booking_mode_none">Tidak Ada</label>

                    <input type="radio" class="btn-check" name="Booking[mode]" value="unlimited"
                        id="booking_mode_unlimited" <?= ($model->booking_mode === 'unlimited') ? 'checked' : '' ?>>
                    <label class="btn btn-sm btn-color-gray-600 btn-active-primary rounded-pill px-2 py-1 fs-8 fw-bold"
                        for="booking_mode_unlimited">Unlimited</label>

                    <input type="radio" class="btn-check" name="Booking[mode]" value="custom" id="booking_mode_custom"
                        <?= ($model->booking_mode === 'custom') ? 'checked' : '' ?>>
                    <label class="btn btn-sm btn-color-gray-600 btn-active-primary rounded-pill px-2 py-1 fs-8 fw-bold"
                        for="booking_mode_custom">Custom</label>
                </div>
            </div>

            <div id="booking-custom-container" class="d-flex align-items-center gap-3 d-none">
                <div class="flex-grow-1">
                    <?= $form->field($model, 'booking_limit')->textInput([
                        'class' => 'form-control form-control-solid rounded-pill text-start ps-5',
                        'id' => 'input-booking-limit'
                    ])->label(false) ?>
                </div>
                <div class="nav-group bg-light p-1 rounded-pill">
                    <input type="radio" class="btn-check" name="Booking[period]" value="mingguan"
                        id="booking_period_mingguan" <?= ($model->booking_period === 'mingguan' || empty($model->booking_period)) ? 'checked' : '' ?>>
                    <label class="btn btn-sm btn-color-gray-600 btn-active-primary rounded-pill px-2 py-1 fs-8 fw-bold"
                        for="booking_period_mingguan">Mingguan</label>

                    <input type="radio" class="btn-check" name="Booking[period]" value="bulanan"
                        id="booking_period_bulanan" <?= ($model->booking_period === 'bulanan') ? 'checked' : '' ?>>
                    <label class="btn btn-sm btn-color-gray-600 btn-active-primary rounded-pill px-2 py-1 fs-8 fw-bold"
                        for="booking_period_bulanan">Bulanan</label>

                    <input type="radio" class="btn-check" name="Booking[period]" value="periode"
                        id="booking_period_periode" <?= ($model->booking_period === 'periode') ? 'checked' : '' ?>>
                    <label class="btn btn-sm btn-color-gray-600 btn-active-primary rounded-pill px-2 py-1 fs-8 fw-bold"
                        for="booking_period_periode">Per Periode</label>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-bordered p-6 mb-5 tanggal-seragam-container">
        <div class="mb-5">
            <h5 class="fw-bolder text-gray-800 text-uppercase fs-6 mb-1">Tanggal Seragam</h5>
            <span class="text-muted fs-7">Atur tipe tanggal berakhir paket dan tanggal jatuh tempo</span>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-5">
            <div class="nav-group nav-group-fluid bg-light p-1 rounded-pill w-100 d-flex">
                <input type="radio" class="btn-check" name="Tanggal[mode]" value="normal" id="date_mode_normal"
                    <?= ($model->date_mode === 'normal' || empty($model->date_mode)) ? 'checked' : '' ?>>

                <label
                    class="btn btn-sm btn-color-white btn-active-light-primary btn-outline-primary rounded-pill px-4 py-3 fw-bold text-start w-50"
                    for="date_mode_normal">
                    <span class="d-block fw-bolder fs-6 text-gray-800">Normal</span>
                    <span class="d-block text-muted fs-7 fw-normal">End date ikuti durasi paket</span>
                </label>

                <input type="radio" class="btn-check" name="Tanggal[mode]" value="seragam" id="date_mode_seragam"
                    <?= ($model->date_mode === 'seragam') ? 'checked' : '' ?>>

                <label
                    class="btn btn-sm btn-color-white btn-active-light-primary btn-outline-primary rounded-pill px-4 py-3 fw-bold text-start w-50"
                    for="date_mode_seragam">
                    <span class="d-block fw-bolder fs-6 text-gray-800">Seragam</span>
                    <span class="d-block text-muted fs-7 fw-normal">Semua member berakhir tanggal sama</span>
                </label>
            </div>
        </div>

        <div class="bg-light rounded p-5 d-none row g-3 tanggal-seragam">
            <div class="col-md-6">
                <div class="form-group mb-0">
                    <label class="form-label fw-bold text-gray-700 fs-7 mb-2">Tanggal berakhir tiap bulan</label>
                    <div class="input-group">
                        <span
                            class="input-group-text bg-body border-0 ps-5 rounded-start-pill text-gray-600 fw-bold">Tgl</span>
                        <?= $form->field($model, 'exp_date', [
                            'template' => '{input}',
                            'options' => ['tag' => false]
                        ])->textInput([
                                    'class' => 'form-control rounded-end-pill ps-3 input-tgl-berakhir',
                                    'placeholder' => '15',
                                ]) ?>
                    </div>
                    <?= $form->field($model, 'exp_date', ['template' => '{error}'])->textInput(['style' => 'display:none']) ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-0">
                    <label class="form-label fw-bold text-gray-700 fs-7 mb-2">Maks bonus hari (Member Baru)</label>
                    <div class="input-group">
                        <span
                            class="input-group-text bg-body border-0 ps-5 rounded-start-pill text-gray-600 fw-bold">Tgl</span>
                        <?= $form->field($model, 'bonus_date', [
                            'template' => '{input}',
                            'options' => ['tag' => false]
                        ])->textInput([
                                    'class' => 'form-control rounded-end-pill ps-3 input-tgl-bonus',
                                    'placeholder' => '15',
                                ]) ?>
                    </div>
                    <?= $form->field($model, 'bonus_date', ['template' => '{error}'])->textInput(['style' => 'display:none']) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-bordered p-6 mb-5">
        <div class="mb-5">
            <h5 class="fw-bolder text-gray-800 text-uppercase fs-6 mb-1">Deskripsi & Fitur</h5>
        </div>

        <div class="mb-5">
            <label class="form-label fw-bold text-gray-700 fs-7">
                Deskripsi <span class="text-muted fw-normal">(Opsional)</span>
            </label>
            <?= $form->field($model, 'description_product', ['template' => '{input}{error}'])->textarea([
                'class' => 'form-control form-control-solid rounded-3 fs-7',
                'rows' => 3,
                'placeholder' => 'Deskripsi singkat paket ini...'
            ]) ?>
        </div>

        <div id="kt_repeater_features">
            <label class="form-label fw-bold text-gray-700 fs-7 mb-2">Fitur yang Termasuk</label>

            <div data-repeater-list="Fitur" class="d-flex flex-column gap-2 mb-3">
                <?php
                $features = !empty($model->features)
                    ? (is_array($model->features) ? $model->features : json_decode($model->features, true))
                    : ['Akses penuh fasilitas', 'Check-in QR code'];

                if (!empty($features) && is_array($features)):
                    foreach ($features as $ftr):
                        ?>
                        <div data-repeater-item
                            class="feature-item d-flex align-items-center bg-light rounded-pill px-3 py-1 border border-gray-200">
                            <i class="bi bi-check-lg text-success fs-6 me-2"></i>
                            <input type="text" name="name"
                                class="form-control form-control-flush fs-8 text-gray-800 fw-semibold bg-transparent py-1"
                                value="<?= Html::encode(is_array($ftr) ? ($ftr['name'] ?? '') : $ftr) ?>">
                            <button type="button" data-repeater-delete
                                class="btn btn-icon btn-sm btn-active-light-danger rounded-circle ms-auto w-25px h-25px">
                                <i class="bi bi-x-lg fs-7"></i>
                            </button>
                        </div>
                        <?php
                    endforeach;
                else:
                    ?>
                    <div data-repeater-item
                        class="feature-item d-flex align-items-center bg-light rounded-pill px-3 py-1 border border-gray-200">
                        <i class="bi bi-check-lg text-success fs-6 me-2"></i>
                        <input type="text" name="name"
                            class="form-control form-control-flush fs-8 text-gray-800 fw-semibold bg-transparent py-1"
                            placeholder="Nama fitur...">
                        <button type="button" data-repeater-delete
                            class="btn btn-icon btn-sm btn-active-light-danger rounded-circle ms-auto w-25px h-25px">
                            <i class="bi bi-x-lg fs-7"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <button type="button" data-repeater-create class="btn btn-sm btn-light-primary rounded-pill">
                <i class="bi bi-plus-lg fs-6"></i> Tambah Fitur
            </button>
        </div>

    </div>
</div>

<div class="text-end pt-10">
    <button type="button" class="btn btn-light me-3 text-dark" data-kt-users-modal-action="cancel"
        data-bs-dismiss="modal"><?= Yii::$app->lang->t('back_home', 'chat34') ?></button>
    <?= Html::submitButton($model->isNewRecord ? Yii::$app->lang->t('extra', 'extra16') : Yii::$app->lang->t('extra', 'extra16'), ['id' => 'btnsubmit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

<script>
    $(document).ready(function () {
        initFeatureRepeater();
        toggleTanggalSeragam();

        if (typeof initMasking === "function") {
            initMasking();
        }

        function calculateDays() {
            const selectedOption = $('input[name="duration_option"]:checked').val();
            const $customContainer = $('#custom-duration-container');
            const $finalInput = $('#final-duration-days');
            let totalDays = 0;

            if (!selectedOption) return;

            if (selectedOption === 'custom') {
                const val = parseInt($('#custom-value').val()) || 0;
                const unit = $('#custom-unit').val();

                if (unit === 'days') totalDays = val;
                else if (unit === 'weeks') totalDays = val * 7;
                else if (unit === 'months') totalDays = val * 30;
                else if (unit === 'years') totalDays = val * 365;

                $('#custom-total-days').text(totalDays);
                $customContainer.removeClass('d-none');
            } else {
                $customContainer.addClass('d-none');

                const presetMap = {
                    '1_day': 1,
                    '1_week': 7,
                    '2_weeks': 14,
                    '1_month': 30,
                    '3_months': 90,
                    '6_months': 180,
                    '1_year': 365
                };
                totalDays = presetMap[selectedOption] || 0;
            }

            $finalInput.val(totalDays);
        }

        $(document).on('change', 'input[name="duration_option"]', calculateDays);
        $(document).on('input change', '#custom-value, #custom-unit', calculateDays);

        function toggleQuotaSection(modeName, containerId) {
            let mode = $(`input[name="${modeName}"]:checked`).val();
            let $container = $(`#${containerId}`);

            if (mode === 'custom') {
                $container.removeClass('d-none');
            } else {
                $container.addClass('d-none');
            }
        }

        $(document).on('change', 'input[name="Visit[mode]"]', function () {
            toggleQuotaSection('Visit[mode]', 'visit-custom-container');
        });

        $(document).on('change', 'input[name="Coaching[mode]"]', function () {
            toggleQuotaSection('Coaching[mode]', 'coaching-custom-container');
        });

        $(document).on('change', 'input[name="Booking[mode]"]', function () {
            toggleQuotaSection('Booking[mode]', 'booking-custom-container');
        });

        function toggleTanggalSeragam() {
            const isSeragam = $('#date_mode_seragam').is(':checked');
            $('.tanggal-seragam').toggleClass('d-none', !isSeragam);
        }

        $(document).on('change', 'input[name="Tanggal[mode]"]', toggleTanggalSeragam);

        function dropIn() {
            if ($('#type-switch').is(':checked')) {
                $('.durasi-membership').addClass('d-none');
                $('.kuota-batasan').addClass('d-none');
                $('.tanggal-seragam-container').addClass('d-none');
                $('.harga-perperiode').addClass('d-none');
                $('.harga-pendaftaran').addClass('d-none');
                $('.harga-perkunjungan').removeClass('d-none');
                $('.harga-perperiode input').prop('disabled', true);
                $('.harga-perkunjungan input').prop('disabled', false);
            } else {
                $('.durasi-membership').removeClass('d-none');
                $('.kuota-batasan').removeClass('d-none');
                $('.tanggal-seragam-container').removeClass('d-none');
                $('.harga-perperiode').removeClass('d-none');
                $('.harga-pendaftaran').removeClass('d-none');
                $('.harga-perkunjungan').addClass('d-none');

                $('.harga-perperiode input').prop('disabled', false);
                $('.harga-perkunjungan input').prop('disabled', true);

                toggleTanggalSeragam();
            }
        }

        $(document).on('change', '#type-switch', function () {
            dropIn();
        });

        function initFeatureRepeater() {
            $('#kt_repeater_features').repeater({
                initEmpty: false,
                show: function () {
                    $(this).slideDown();
                },
                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            });
        }

        calculateDays();
        toggleQuotaSection('Visit[mode]', 'visit-custom-container');
        toggleQuotaSection('Coaching[mode]', 'coaching-custom-container');
        toggleQuotaSection('Booking[mode]', 'booking-custom-container');
        toggleTanggalSeragam();
        dropIn();

        <?php if ($isajax): ?>
            $('#form-product').off('submit').on('submit', function (e) {
                e.preventDefault();

                let $btnSubmit = $('#btnsubmit');
                let originalText = $btnSubmit.html();

                $btnSubmit.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Loading...'
                );

                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if (data.success) {
                            if ($.fn.DataTable.isDataTable('#datatable')) {
                                $('#datatable').DataTable().ajax.reload(null, false);
                            }

                            $('.addContactModal, #modal_form_produk').modal('hide');

                            if (data.id && data.productname) {
                                let newOption = new Option(data.productname, data.id, true, true);
                                $('#person').append(newOption).trigger('change');
                            }

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
                    error: function () {
                        Swal.fire({
                            icon: "error",
                            title: "Failed",
                            text: "Something went wrong, please call your Administrator!"
                        });
                    },
                    complete: function () {
                        $btnSubmit.prop('disabled', false).html(originalText);
                    }
                });

                return false;
            });
        <?php endif; ?>
    });
</script>