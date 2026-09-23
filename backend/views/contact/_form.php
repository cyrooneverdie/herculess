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
    'id' => 'form-contact',
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

<div class="d-flex flex-column px-5 px-lg-10" id="modal_form_asset_scroll">
    <div class="card card-flush border border-dashed border-gray-300 mb-5">
        <div class="card-header min-h-50px bg-light-info">
            <div class="card-title">
                <i class="fas fa-id-card text-info fs-4 me-2"></i>
                <h3 class="fw-bold text-gray-800 fs-6 mb-0">Identitas Member</h3>
            </div>
        </div>

        <div class="card-body py-5">
            <div class="row g-5">

                <div
                    class="col-lg-3 col-md-4 d-flex flex-column align-items-center justify-content-start text-center border-end-lg pe-lg-5">
                    <label class="form-label fw-bold fs-7 mb-3">Foto Member</label>

                    <div class="card card-flush border border-dashed border-gray-300 cursor-pointer mb-2"
                        style="width: 140px; height: 140px;">
                        <label for="formFile"
                            class="card-body d-flex align-items-center justify-content-center p-2 cursor-pointer h-100">
                            <img id="npwpfiletemp" src="<?= $model->npwpfile
                                ? Yii::getAlias('@web') . '/uploads/contact/' . $model->npwpfile
                                : Yii::getAlias('@web') . '/assets/media/logos/empty.png' ?>"
                                class="mw-100 mh-100 rounded object-fit-contain" alt="Preview">
                        </label>
                    </div>

                    <span class="text-muted fs-8 mb-3">Format: JPG/PNG (Maks 2MB)</span>

                    <?= $form->field($model, 'npwpfiletemp')->fileInput([
                        'id' => 'formFile',
                        'accept' => 'image/*',
                        'class' => 'd-none',
                        'onchange' => 'previewNpwpFile(event)',
                    ])->label(false) ?>

                    <label for="formFile" class="btn btn-sm btn-light-primary fw-semibold">
                        <i class="fas fa-upload me-1"></i> Pilih Foto
                    </label>
                </div>

                <div class="col-lg-9 col-md-8">
                    <div class="row g-4">

                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-7 required">
                                <?= Yii::$app->lang->t('contact', 'contact_no') ?>
                            </label>
                            <input type="text" id="contact_no" name="Contact[contact_no]" class="form-control"
                                value="<?= $model->contact_no ?? '' ?>" placeholder="NO-001" required>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-semibold fs-7 required">
                                <?= Yii::$app->lang->t('contact', 'contact_name') ?>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user text-muted fs-7"></i>
                                </span>
                                <input type="text" id="contact_name" name="Contact[contact_name]" class="form-control"
                                    value="<?= !$model->isNewRecord ? ($model->contact_name ?? '') : '' ?>"
                                    placeholder="<?= Yii::$app->lang->t('contact', 'contact_name') ?>" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7">
                                <?= Yii::$app->lang->t('contact', 'contact_phone1') ?>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-phone text-muted fs-7"></i>
                                </span>
                                <input type="tel" id="contact_phone1" name="Contact[contact_phone1]"
                                    class="form-control"
                                    value="<?= !$model->isNewRecord ? ($model->contact_phone1 ?? '') : '' ?>"
                                    placeholder="+6281234567890" pattern="[\+]?[0-9]{10,15}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7">
                                <?= Yii::$app->lang->t('contact', 'contact_email1') ?>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope text-muted fs-7"></i>
                                </span>
                                <input type="email" id="contact_email1" name="Contact[contact_email1]"
                                    class="form-control"
                                    value="<?= !$model->isNewRecord ? ($model->contact_email1 ?? '') : '' ?>"
                                    placeholder="email@contoh.com">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold fs-7">Paket</label>
                            <select id="person" name="Contact[personid]" class="form-select person"
                                data-control="select2">
                                <?php if (!$model->isNewRecord): ?>
                                    <option value="<?= $model->personid ?>">
                                        <?= Yii::$app->function->findByField("productname", "products", " and productid='" . $model->personid . "' "); ?>
                                    </option>
                                <?php endif; ?>
                            </select>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="card card-flush border border-dashed border-gray-300 mb-5">
        <div class="card-header min-h-50px bg-light-primary">
            <div class="card-title">
                <i class="fas fa-file-alt text-primary fs-4 me-2"></i>
                <h3 class="fw-bold text-gray-800 fs-6 mb-0">Tanggal & Pembayaran</h3>
            </div>

        </div>
        <div class="card-body py-5">
            <div class="row g-5 mb-5">
                <div class="col-md-6">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="fs-7 fw-bold text-gray-700 mb-2">Tanggal Mulai</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-calendar-check text-muted fs-7"></i>
                                </span>
                                <input type="text" id="jobstart" name="Contact[jobstart]"
                                    class="form-control border-start-0 pickdate"
                                    value="<?= $model->isNewRecord ? date('d/m/Y') : ($model->jobstart ?? date('d/m/Y')) ?>"
                                    placeholder="DD/MM/YYYY">
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="fs-7 fw-bold text-gray-700 mb-2">Tanggal Berakhir</label>
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
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="fs-7 mb-2 fw-bold text-gray-700">Status Pembayaran</label>
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="nav-group nav-group-fluid w-100 d-flex gap-2">

                            <input type="radio" class="btn-check" name="Payment[status]" value="belum"
                                id="pay_status_belum" <?= ($model->payment_status === 'belum' || empty($model->payment_status)) ? 'checked' : '' ?>>
                            <label
                                class="btn btn-outline btn-active-dark rounded-4 px-3 py-2 text-start w-50 custom-payment-btn"
                                for="pay_status_belum">
                                <span class="d-block fw-bolder fs-6 title-text">Belum Bayar</span>
                                <span class="d-block text-muted fs-7 fw-normal sub-text">Status menunggu</span>
                            </label>

                            <input type="radio" class="btn-check" name="Payment[status]" value="sudah"
                                id="pay_status_sudah" <?= ($model->payment_status === 'sudah') ? 'checked' : '' ?>>
                            <label
                                class="btn btn-outline btn-active-dark rounded-4 px-3 py-2 text-start w-50 custom-payment-btn"
                                for="pay_status_sudah">
                                <span class="d-block fw-bolder fs-6 title-text">Sudah Bayar</span>
                            </label>

                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="metode-pembayaran-wrapper <?= ($model->payment_status === 'sudah') ? '' : 'd-none' ?>">
                        <label class="fs-7 mb-2 fw-bold text-gray-700">Metode Pembayaran</label>
                        <div class="d-flex align-items-center gap-2">

                            <input type="radio" class="btn-check" name="Payment[method]" value="tunai"
                                id="pay_method_tunai" <?= ($model->payment_method === 'tunai' || empty($model->payment_method)) ? 'checked' : '' ?>>
                            <label
                                class="btn btn-outline btn-active-dark rounded-pill px-3 py-2 fw-bold text-center flex-fill fs-7"
                                for="pay_method_tunai">
                                Tunai
                            </label>

                            <input type="radio" class="btn-check" name="Payment[method]" value="transfer"
                                id="pay_method_transfer" <?= ($model->payment_method === 'transfer') ? 'checked' : '' ?>>
                            <label
                                class="btn btn-outline btn-active-dark rounded-pill px-3 py-2 fw-bold text-center flex-fill fs-7"
                                for="pay_method_transfer">
                                Transfer
                            </label>

                            <input type="radio" class="btn-check" name="Payment[method]" value="qris"
                                id="pay_method_qris" <?= ($model->payment_method === 'qris') ? 'checked' : '' ?>>
                            <label
                                class="btn btn-outline btn-active-dark rounded-pill px-3 py-2 fw-bold text-center flex-fill fs-7"
                                for="pay_method_qris">
                                QRIS
                            </label>

                            <input type="radio" class="btn-check" name="Payment[method]" value="lainnya"
                                id="pay_method_lainnya" <?= ($model->payment_method === 'lainnya') ? 'checked' : '' ?>>
                            <label
                                class="btn btn-outline btn-active-dark rounded-pill px-3 py-2 fw-bold text-center flex-fill fs-7"
                                for="pay_method_lainnya">
                                Lainnya
                            </label>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade addContactModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        style="z-index:9999!important">
        <div class="modal-dialog modal-lg modal-xl">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-titles">Add Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                </div>
            </div>
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
                    url: "<?= Url::to(['product/list']) ?>",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
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
                                    id: item.productid,
                                    text: item.productname,
                                    duration_days: item.duration_days
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
                dropdownParent: $('#form-contact'),
            }).on('select2:open', function () {
                let $dropdown = $('.select2-dropdown');
                $dropdown.find('.add-new-btn').remove();
                $dropdown.append(`
                    <div class="add-new-btn" style="padding:6px;text-align:center;border-top:1px solid #ddd;background:#f8f9fa;">
                        <button type="button" class="btn btn-sm btn-primary add-enum-btn" >
                            <i class="fas fa-plus-circle me-1"></i> Tambah
                        </button>
                    </div>
                `);
            }).on('change', function () {
                calculateEndDate();
            });
        }

        initEnumSelect2('#person', 'contactperson', 'Package');
        calculateEndDate();

        <?php
        if ($isajax) {
            ?>
            $('#form-contact').on('submit', function (e) {
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

    function calculateEndDate() {
        let startDateVal = $('#jobstart').val();
        let selectedData = $('#person').select2('data')[0];

        let durationDays = (selectedData && selectedData.duration_days) ? parseInt(selectedData.duration_days) : 0;
        if (isNaN(durationDays)) durationDays = 0;

        if (startDateVal && durationDays > 0) {
            let parts = startDateVal.split('/');
            if (parts.length === 3) {
                let startDate = new Date(parts[2], parts[1] - 1, parts[0]);

                startDate.setDate(startDate.getDate() + durationDays);

                let day = String(startDate.getDate()).padStart(2, '0');
                let month = String(startDate.getMonth() + 1).padStart(2, '0');
                let year = startDate.getFullYear();

                $('#jobend').val(`${day}/${month}/${year}`);
            }
        }
    }

    $(document).on('change changeDate input', '#jobstart', calculateEndDate);

    $(document).on('change', 'input[name="Payment[status]"]', function () {
        if ($(this).val() === 'sudah') {
            $('.metode-pembayaran-wrapper').removeClass('d-none');
        } else {
            $('.metode-pembayaran-wrapper').addClass('d-none');
        }
    });

    $(document).on('click', '.add-new-btn button', function () {

        $.get('/product/create', function (html) {
            $('.addContactModal .modal-content').html(html);
            $('.addContactModal').modal('show');
        });
    });

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

    function setSelect2Value(selector, id, text) {
        if (!id) return;

        let option = new Option(text, id, true, true);
        $(selector).append(option).trigger('change');
    }


</script>