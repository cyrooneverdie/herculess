<?php
$this->title = $title;
$formatnumber = 'INV';
switch ($title) {
    case 'Purchase Request':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar8');
        $formatnumber = "RQ";
        break;
    case 'Purchase Order':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar9');
        $formatnumber = "ORD";
        break;
    case 'Purchase Delivery':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar10');
        $formatnumber = "D";
        break;
    case 'Purchase Invoice':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar11');
        $formatnumber = "INV";
        break;
    case 'Purchase Return':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar12');
        $formatnumber = "RTN";
        break;
    case 'Sales Quote':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar14');
        $formatnumber = "QU";
        break;
    case 'Sales Order':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar15');
        $formatnumber = "ORD";
        break;
    case 'Sales Delivery':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar16');
        $formatnumber = "D";
        break;
    case 'Sales Invoice':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar17');
        $formatnumber = "INV";
        break;
    case 'Sales Return':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar18');
        $formatnumber = "RTN";
        break;
}
// var_dump($title);
use yii\helpers\Html;
use yii\helpers\Url;
?>


<div class="card mt-5">
    <!-- Card header -->
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <!-- Search Box -->
            <div class="d-flex align-items-center position-relative my-2">
                <form method="get" action="index" id="search" class="w-100">
                    <div class="position-relative">
                        <!-- Search icon -->
                        <span class="position-absolute top-50 start-0 translate-middle-y ms-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512" fill="#a1a5b7">
                                <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
                            </svg>
                        </span>
                        <input
                            data-kt-docs-table-filter="search"
                            type="text"
                            name="search"
                            class="form-control form-control-solid w-250px ps-10"
                            placeholder=" <?= Yii::$app->lang->t('search_purchase', 'search_purchase') ?>" />

                        <!-- Clear search button -->
                        <span class="position-absolute top-50 end-0 translate-middle-y me-3 d-none" id="clear-search">
                            <i class="ki-duotone ki-cross fs-2 text-gray-500 cursor-pointer" style="opacity: 0.5;"></i>
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card toolbar -->
        <div class="card-toolbar d-flex gap-5">
            <!-- Filter button -->
            <button type="button" class="btn btn-light-primary me-3" id="codenumber" data-bs-target="#modal_kode">
                <?= Yii::$app->lang->t('extra', 'extra79') ?>
            </button>
            <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="left-start">
                <i class="ki-duotone ki-filter fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>Filter
            </button>

            <!-- Filter Menu -->
            <div class="menu menu-sub menu-sub-dropdown w-sm-500px w-md-600px" data-kt-menu="true" data-kt-menu-id="filter-menu">
                <!-- Header -->
                <div class="px-7 py-5">
                    <div class="fs-5 text-gray-900 fw-bold"><?= Yii::$app->lang->t('extra', 'extra9') ?></div>
                </div>
                <!-- Separator -->
                <div class="separator border-gray-200"></div>
                <!-- Content -->
                <div class="px-7 py-5" data-kt-user-table-filter="form">
                    <form id="filterForm" method="get" action="index">
                        <!-- Input group -->
                        <div class="row">
                            <div class="mb-3">
                                <div class="btn-group mb-2" role="group" aria-label="Quick Date Filters">
                                    <button type="button" class="btn btn-sm btn-light-primary" id="btnThisYear"><?= Yii::$app->lang->t('extra', 'extra96') ?></button>
                                    <button type="button" class="btn btn-sm btn-light-primary" id="btnThisMonth"><?= Yii::$app->lang->t('extra', 'extra97') ?></button>
                                    <button type="button" class="btn btn-sm btn-light-primary" id="btnThisWeek"><?= Yii::$app->lang->t('extra', 'extra98') ?></button>
                                    <button type="button" class="btn btn-sm btn-light-primary" id="btnLastYear"><?= Yii::$app->lang->t('extra', 'extra99') ?></button>
                                    <button type="button" class="btn btn-sm btn-light-primary" id="btnLastMonth"><?= Yii::$app->lang->t('extra', 'extra100') ?></button>
                                    <button type="button" class="btn btn-sm btn-light-primary" id="btnLastWeek"><?= Yii::$app->lang->t('extra', 'extra101') ?></button>
                                </div>
                            </div>
                            <div class="md-10 mb-2">
                                <label class="fw-semibold fs-6 mb-2 mt-3" for="dateFilter"><?= Yii::$app->lang->t('tran', 'tran_date') ?></label>
                                <input name="datefilter" class="form-control form-control-solid" style="cursor:pointer;" id="datefilter" placeholder="<?= Yii::$app->lang->t('extra', 'extra58') ?>" autocomplete="off">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="fw-semibold fs-6 mb-2" for="contactFilter"><?= Yii::$app->lang->t('front_home', 'contact') ?></label>
                                    <select id="contactSelect" class="form-select contact" data-control="select2" name="contact">
                                        <!-- <option value="">All Contacts</option> -->
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-semibold fs-6 mb-2" for="statusFilter">Status</label>
                                    <select id="statusSelect" class="form-select status" data-control="select2" name="status">
                                        <option value=""><?= Yii::$app->lang->t('extra', 'extra57') ?></option>
                                        <option value="paid"><?= Yii::$app->lang->t('dashboard', 'paid') ?></option>
                                        <option value="unpaid"><?= Yii::$app->lang->t('cashbackend', 'cashbackend12') ?></option>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="form-group text-end mt-5">
                            <button type="submit" id="filterButton" class="btn btn-lg btn-primary"><i class="fa-sharp fa-solid fa-filter"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Add transaction button with href -->
            <a href="<?= Url::to(['create', 'module' => $module, 'type' => $type, 'numbercode' => $formatnumber]) ?>" class="btn btn-primary" id="btn-add-tran" data-bs-toggle="modal" data-bs-target="#modal_form_tran">
                <i class="ki-duotone ki-plus fs-2"></i> <?= Yii::$app->lang->t('cta_add', 'cta_add') ?> <?= $title ?>
            </a>
        </div>
    </div>

    <!-- Card body -->
    <div class="card-body pt-5">
        <!-- Alert Placeholder -->
        <div id="liveAlertPlaceholder"></div>

        <!-- Mass Action Buttons Group -->
        <div class="btn-group mb-3" id="mass-action-buttons" style="display: none;">
            <button type="button" class="btn btn-danger" id="btn-delete-mass">
                <i class="fas fa-trash me-2"></i>Delete
            </button>
            <button type="button" class="btn btn-success" id="btn-approve-mass">
                <i class="fas fa-check me-2"></i>Approve
            </button>
            <button type="button" class="btn btn-warning" id="btn-cancel-mass">
                <i class="fas fa-ban me-2"></i>Cancel
            </button>
        </div>

        <!-- Table -->
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="datatable">
            <thead>
                <tr class="text-start bg-gray-100 fs-6 text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                    <th class="d-none"></th>
                    <th class="text-center min-w-100px">
                        <input type="checkbox" id="select-all">
                    </th>
                    <th class="text-center min-w-50px"><?= Yii::$app->lang->t('produk_table', 'produk_action') ?></th>
                    <th class="text-center min-w-150px">Status</th>

                    <th class="text-center min-w-200px"> <?= Yii::$app->lang->t('tran', 'tran_contactid') ?></th>
                    <!-- <th class="text-center min-w-200px"> Referensi </th> -->
                    <th class="text-center min-w-150px"> <?= Yii::$app->lang->t('tran', 'tran_date') ?></th>
                    <th class="text-center min-w-200px"> <?= Yii::$app->lang->t('tran', 'tran_duedate') ?></th>
                    <th class="text-center min-w-200px"><?= Yii::$app->lang->t('tran', 'tran_no') ?></th>
                    <th class="text-center min-w-150px"> <?= Yii::$app->lang->t('purchase_table', 'purchase_statuspembayaran') ?></th>
                    <th class="text-center min-w-150px"> <?= Yii::$app->lang->t('purchase_table', 'purchase_sisatagihan') ?></th>
                    <th class="text-center min-w-150px">Total</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 fw-semibold text-center">
                <!-- Table content will be loaded dynamically -->
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modal_kode" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> <?= Yii::$app->lang->t('extra', 'extra79') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-content-number" class="p-4">
                    <div class="row g-4">
                        <div class="d-flex justify-content-center align-items-center mb-3">
                            <span class="badge bg-primary text-light fs-5 px-4 py-2 rounded-pill shadow" id="preview-code">
                                <?= Html::encode($previewcode) ?>
                            </span>
                        </div>

                        <div class="numberlist" id="numberlist">
                            <?php foreach ($numbercode as $code): ?>
                                <div class="col-md-3" id="model-content-number-list">
                                    <div class="alert alert-primary shadow-sm p-3 rounded-3 text-center template-option"
                                        role="button"
                                        style="cursor: pointer;"
                                        data-template="<?= Html::encode($code['template']) ?>">
                                        <?= Html::encode($code['template']) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6 d-flex align-items-center">
                                <a href="javascript:void(0);" id="addcodenumber" class="btn btn-outline-primary w-100 h-100 d-flex justify-content-center align-items-center rounded-3 fw-bold fs-4 shadow-sm text-hover-light">
                                    + <?= Yii::$app->lang->t('cta_add', 'cta_add') . " " . Yii::$app->lang->t('extra', 'extra79') ?>
                                </a>
                            </div>

                            <div class="col-md-6">
                                <div class="btn btn-outline-primary w-100 h-100 d-flex justify-content-center align-items-center rounded-3 fw-bold fs-4 shadow-sm text-hover-light template-option" data-template="<?= $formatnumber ?>" data-reset="1" style="cursor:pointer">
                                    <?= Yii::$app->lang->t('extra', 'extra78') ?> (<?= $formatnumber ?>)
                                </div>
                            </div>
                        </div>

                        <div id="form-tambah-template" class="col-md-12 d-none">
                            <div class="card border border-primary shadow-sm p-4 rounded-4">
                                <div class="mb-3">
                                    <label for="input-template" class="form-label fw-semibold"><?= Yii::$app->lang->t('extra', 'extra79') ?></label>
                                    <input type="text" id="input-template" class="form-control" placeholder="Contoh: CT, ETC, ct, kl" autocomplete="off">
                                </div>
                                <div class="text-end">
                                    <button id="simpan-template" class="btn btn-primary px-4">
                                        <i class="bi bi-save me-1"></i> <?= Yii::$app->lang->t('extra', 'extra16') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transaction Modal -->
<div class="modal fade" id="modal_form_tran" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-lg-down modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= Yii::$app->lang->t('cta_add', 'cta_add') ?> <?= $title ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-content" class="nopadding">
                    <!-- Transaction form content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal container - Add this to your layout or main index view -->
<div class="modal fade" id="modal_detail_tran" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" id="modal_detail_tran_header">
                <h2 class="fw-bold">Detail Transaksi</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-detail-content">
                    <!-- Content will be loaded here via AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .select-checkbox {
        transition: transform 0.35s cubic-bezier(0.25, 1, 0.5, 1),
            opacity 0.35s ease,
            box-shadow 0.35s ease,
            filter 0.35s ease;
    }

    .select-checkbox.checked-anim {
        transform: scale(1.3);
        opacity: 1;
        box-shadow: 0 0 10px rgba(0, 150, 255, 0.4);
        filter: brightness(1.2);
    }

    #datatable {
        width: 100%;
        border-collapse: collapse;
    }

    #datatable th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
        text-transform: uppercase;
        font-size: 0.75rem;
        /* Tambahkan padding agar lebih rapi */
        padding: 12px 8px;
    }

    /* Penyesuaian padding untuk sel body */
    #datatable td {
        padding: 8px; /* Sedikit lebih kecil dari header jika perlu */
        vertical-align: middle; /* Pastikan konten sel vertikal sejajar di tengah */
    }
    
    /* Pastikan checkbox dan dropdown action terpusat sempurna */
    #datatable .dt-body-center {
        text-align: center;
    }

    /* Tambahkan hover effect untuk row */
    #datatable tr:hover {
        background-color: #f5f5f5;
    }

    /* Styling tambahan untuk ikon action */
    #datatable .dropdown-item {
        white-space: nowrap; /* Mencegah teks melipat jika panjang */
    }

    /* Jika masih ada masalah dengan checkbox header vs body, bisa coba ini */
    #datatable thead th input[type="checkbox"],
    #datatable tbody td input[type="checkbox"] {
        vertical-align: middle;
        margin: 0; /* Hapus margin default browser */
        padding: 0;
    }

    #mass-action-buttons {
        opacity: 0;
        transform: scale(0.95);
        transition: opacity 0.3s ease, transform 0.3s ease;
        pointer-events: none;
        /* Biar gak bisa diklik pas ngilang */
    }

    #mass-action-buttons.show {
        opacity: 1;
        transform: scale(1);
        pointer-events: auto;
    }
</style>
<script>
    $(document).ready(function () {
    const params = new URLSearchParams(window.location.search);

    const contact = params.get('contact');
    const search = params.get('search');
    const datefilter = params.get('datefilter');
    const status = params.get('status');

    if (contact !== null) $('select[name="contact"]').val(contact).trigger('change');
    if (search !== null) $('input[name="search"]').val(search);
    if (datefilter !== null) $('input[name="datefilter"]').val(datefilter);
    if (status !== null) $('select[name="status"]').val(status).trigger('change');
});

    $.fn.dataTable.ext.errMode = "none"; // Disable all DataTables warnings

    function toggleMassActionButtons() {
        const $btn = $('#mass-action-buttons');

        if ($('.select-checkbox:checked').length > 0) {
            if (!$btn.hasClass('show')) {
                $btn.css('display', 'block');
                setTimeout(() => {
                    $btn.addClass('show');
                }, 10); // delay kecil biar transition kebaca
            }
        } else {
            $btn.removeClass('show');
            setTimeout(() => {
                $btn.css('display', 'none');
            }, 300); // sesuai durasi transisi CSS
        }
    }


    // Fungsi untuk menampilkan alert Bootstrap dengan ikon dan auto-dismiss
    function showBootstrapAlert(message, type) {
        let alertPlaceholder = document.getElementById("liveAlertPlaceholder");
        if (!alertPlaceholder) {
            // Jika element alert placeholder tidak ada, buat baru dan masukkan ke card-body
            alertPlaceholder = document.createElement("div");
            alertPlaceholder.id = "liveAlertPlaceholder";
            document.querySelector(".card-body").prepend(alertPlaceholder);
        }

        let wrapper = document.createElement("div");
        wrapper.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show d-flex align-items-center" role="alert">
                <div class="me-3">
                    ${type === 'success' ? '<i class="fas fa-check-circle fa-lg text-success"></i>' : ''}
                    ${type === 'danger' ? '<i class="fas fa-exclamation-circle fa-lg text-danger"></i>' : ''}
                    ${type === 'warning' ? '<i class="fas fa-exclamation-triangle fa-lg text-warning"></i>' : ''}
                    ${type === 'info' ? '<i class="fas fa-info-circle fa-lg text-info"></i>' : ''}
                </div>
                <div>${message}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;

        alertPlaceholder.innerHTML = ""; // Hapus alert sebelumnya
        alertPlaceholder.append(wrapper);

        // Auto dismiss alert setelah 5 detik
        setTimeout(function() {
            const alert = bootstrap.Alert.getOrCreateInstance(wrapper.querySelector('.alert'));
            if (alert) alert.close();
        }, 5000);
    }

    $("#select-all").on("click", function() {
        const isChecked = this.checked;

        $("tbody .select-checkbox").each(function() {
            if (isChecked) {
                $(this)
                    .prop("checked", true)
                    .addClass("checked-anim");

                // Biar efek tetap smooth, delay penghapusan class sedikit lebih lama
                setTimeout(() => {
                    $(this).removeClass("checked-anim");
                }, 400);
            } else {
                $(this).prop("checked", false);
            }
        });

        toggleMassActionButtons();
    });

    $("#datatable tbody").on("change", ".select-checkbox", function() {
        $("#select-all").prop(
            "checked",
            $(".select-checkbox").length === $(".select-checkbox:checked").length
        );
        toggleMassActionButtons();
    });

    $('#codenumber').on('click', function() {
        // Panggil preview code dengan type default saat modal dibuka
        $.ajax({
            url: '<?= \yii\helpers\Url::to(['tran/previewcode']) ?>',
            method: 'GET',
            data: {
                type: "<?= $formatnumber ?>"
            },
            success: function(res) {
                if (res.success && res.preview) {
                    $('#preview-code').text(res.preview);
                }
            }
        });

        $('#modal_kode').modal('show');
    });

    $('#addcodenumber').on('click', function() {
        const $form = $('#form-tambah-template');
        const $btn = $(this);

        if ($form.hasClass('d-none')) {
            $form.removeClass('d-none').hide();
        }

        $form.stop(true, true).slideToggle(300, function() {
            // const isVisible = $form.is(':visible');
            $btn.text('+ <?= Yii::$app->lang->t('cta_add', 'cta_add') . " " . Yii::$app->lang->t('extra', 'extra79') ?>');
        });
    });

    $('#simpan-template').on('click', function() {
        let template = $('#input-template').val().trim().toUpperCase(); // Convert ke uppercase dan trim whitespace

        // Validasi: maksimal 3 karakter huruf
        // Validasi dengan SweetAlert2
        if (!template) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?= Yii::$app->lang->t('extra', 'extra80') ?>',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            return;
        }

        if (template.length > 3) {
            Swal.fire({
                icon: 'error',
                title: '<?= Yii::$app->lang->t('extra', 'extra81') ?>',
                text: '<?= Yii::$app->lang->t('extra', 'extra82') ?>',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            return;
        }

        if (!/^[A-Za-z]+$/.test(template)) {
            Swal.fire({
                icon: 'error',
                title: '<?= Yii::$app->lang->t('extra', 'extra83') ?>',
                text: '<?= Yii::$app->lang->t('extra', 'extra84') ?>',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            return;
        }
        let $btn = $(this);
        $btn.html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...')
            .prop('disabled', true);

        $.ajax({
            url: '<?= \yii\helpers\Url::to(['tran/savetemplate']) ?>',
            method: 'GET',
            data: {
                template: template,
            },
            success: function(res) {
                if (res.success) {
                    // Tambah template baru ke modal dan jadikan klik-able
                    $('#numberlist').prepend(`
					<div class="col-md-3">
						<div class="alert alert-primary shadow-sm p-3 rounded-3 text-center template-option"
							style="cursor: pointer;" data-template="${template}" role="button">
							${template}
						</div>
					</div>
				`);

                    Swal.fire({
                        icon: 'success',
                        title: '<?= Yii::$app->lang->t('extra', 'extra62') ?>!',
                        text: '<?= Yii::$app->lang->t('extra', 'extra85') ?>',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    // Reset form
                    $('#input-template').val('');
                    $('#form-tambah-template').slideUp().addClass('d-none');
                    $btn.html('Simpan').prop('disabled', false);
                    // showToast(`✅ Template <strong>${template}</strong> berhasil ditambahkan!`);
                } else {
                    // showToast("⚠️ " + (res.message || 'Gagal menyimpan template.'));
                    $btn.html('Simpan').prop('disabled', false);
                }
            },
            error: function() {
                // showToast("🚨 Terjadi kesalahan saat menyimpan template.");
                $btn.html('Simpan').prop('disabled', false);
            }
        });
    });

    $('#modal-content-number').on('click', '.template-option', function() {
        const selectedTemplate = $(this).data('template');
        const isReset = $(this).data('reset') == 1;

        const urlSetTemplate = isReset ?
            '<?= \yii\helpers\Url::to(['tran/resettemplate']) ?>' :
            '<?= \yii\helpers\Url::to(['tran/settemplate']) ?>';

        const el = $(this);

        $.ajax({
            url: urlSetTemplate,
            method: 'GET',
            data: {
                type: selectedTemplate,
                numberformat: "<?= $formatnumber ?>"
            },
            success: function(res) {
                if (res.success) {
                    $('.template-option')
                        .removeClass('alert-info')
                        .addClass('alert-primary')
                        .find('.badge')
                        .remove();

                    el
                        .removeClass('alert-primary')
                        .addClass('alert-info');

                    // Panggil preview code dengan type yang sesuai
                    $.ajax({
                        url: '<?= \yii\helpers\Url::to(['tran/previewcode']) ?>',
                        method: 'GET',
                        data: {
                            type: selectedTemplate // Kirim template yang dipilih sebagai type
                        },
                        success: function(res2) {
                            if (res2.success && res2.preview) {
                                $('#preview-code')
                                    .fadeOut(150, function() {
                                        $(this).text(res2.preview).fadeIn(150);
                                    });
                            } else {
                                console.error("Gagal mengambil preview code:", res2.message);
                            }
                        },
                        error: function() {
                            console.error("Gagal komunikasi saat ambil preview code.");
                        }
                    });
                } else {
                    console.error("Gagal mengatur template:", res.message);
                }
            },
            error: function() {
                console.error("Terjadi kesalahan saat mengatur template.");
            }
        });
    });

    var deletemessage1 = "<?= Yii::$app->lang->t('extra', 'extra44') ?>";
    var deletemessage2 = "<?= Yii::$app->lang->t('extra', 'extra45') ?>";
    var deletemessage3 = "<?= Yii::$app->lang->t('extra', 'extra46') ?>";
    var deletemessage3koma1 = "<?= Yii::$app->lang->t('extra', 'extra46.1') ?>";
    var deletemessage4 = "<?= Yii::$app->lang->t('back_home', 'chat34') ?>";
    var deletemessage5 = "<?= Yii::$app->lang->t('back_home', 'chat53') ?>";
    var deletemessage6 = "<?= Yii::$app->lang->t('extra', 'extra65') ?>";
    var deletemessage7 = "<?= Yii::$app->lang->t('extra', 'extra66') ?>";
    var deletemessage8 = "<?= Yii::$app->lang->t('extradouble', 'double2') ?>";
    // Function to handle mass actions (approve, delete, cancel)
    function performMassAction(action, statusCode) {
        let selectedIds = $(".select-checkbox:checked")
            .map(function() {
                return $(this).val(); // Ambil nilai ID dari checkbox yang dicentang
            })
            .get();

        if (selectedIds.length === 0) {
            Swal.fire({
                title: "<?= Yii::$app->lang->t('extra', 'extra60') ?>",
                text: "<?= Yii::$app->lang->t('extra', 'extra61') ?>",
                icon: "warning",
                confirmButtonColor: "#d33",
                confirmButtonText: "OK"
            });
            return;
        }

        let actionText, actionColor, actionIcon, confirmText;

        // Set text and style based on action
        switch (action) {
            case 'delete':
                actionText = deletemessage5;
                actionColor = "#d33";
                actionIcon = "warning";
                confirmText = deletemessage5;
                break;
            case 'approve':
                actionText = "approve";
                actionColor = "#34e04b";
                actionIcon = "question";
                confirmText = `${deletemessage8}, approve!`;
                break;
            case 'cancel':
                actionText = "cancel";
                actionColor = "#f39c12";
                actionIcon = "question";
                confirmText = `${deletemessage8}, cancel!`;
                break;
        }

        Swal.fire({
            title: `${deletemessage6} ${actionText}?`,
            text: `${deletemessage7} ${actionText} ${selectedIds.length} data.`,
            icon: actionIcon,
            showCancelButton: true,
            confirmButtonColor: actionColor,
            cancelButtonColor: "#6e7d88",
            confirmButtonText: confirmText,
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= Url::to(['/tran/massaction']) ?>',
                    type: "POST",
                    data: {
                        ids: selectedIds,
                        action: action,
                        status: statusCode,
                        _csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
                    },
                    headers: {
                        "X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
                    },
                    success: function(response) {
                        // Reload DataTable
                        $("#datatable").DataTable().ajax.reload();

                        // Sembunyikan tombol aksi massal setelah sukses
                        $("#mass-action-buttons").hide();
                        $("#select-all").prop("checked", false);

                        // Tampilkan notifikasi sukses
                        let successTitle, successText, successIcon;
                        switch (action) {
                            case 'delete':
                                successTitle = "<?= Yii::$app->lang->t('back_home', 'chat57') ?>";
                                successText = "<?= Yii::$app->lang->t('extra', 'extra59') ?>";
                                successIcon = "success";
                                break;
                            case 'approve':
                                successTitle = "Berhasil!";
                                successText = "<?= Yii::$app->lang->t('extra', 'extra67') ?>";
                                successIcon = "success";
                                break;
                            case 'cancel':
                                successTitle = "Berhasil!";
                                successText = "<?= Yii::$app->lang->t('extra', 'extra68') ?>";
                                successIcon = "success";
                                break;
                        }

                        Swal.fire({
                            title: successTitle,
                            text: successText,
                            icon: successIcon,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr) {
                        console.error("Error:", xhr.responseText);

                        // Tampilkan notifikasi error
                        Swal.fire({
                            title: "Gagal!",
                            text: "Terjadi kesalahan saat memproses data.",
                            icon: "error",
                            confirmButtonColor: "#d33",
                            confirmButtonText: "OK"
                        });
                    }
                });
            }
        });
    }

    // Handle mass delete button click
    $('#btn-delete-mass').on('click', function() {
        performMassAction('delete', 10);
    });

    // Handle mass approve button click
    $('#btn-approve-mass').on('click', function() {
        performMassAction('approve', 1);
    });

    // Handle mass cancel button click
    $('#btn-cancel-mass').on('click', function() {
        performMassAction('cancel', 5);
    });

    $("#filterForm").submit(function(e) {
        e.preventDefault(); // Jangan biarkan form reload halaman
        $("#datatable").DataTable().ajax.reload();
        // Gunakan Metronic API untuk menutup menu
        let filterMenu = document.querySelector("[data-kt-menu-id='filter-menu']");
        if (filterMenu) {
            KTMenu.getInstance(filterMenu).hide();
        }
    });

    $(document).ready(function() {
        // Initialize Select2 for Contacts
        console.log($('#contactSelect'));
        $("#contactSelect").select2({
            ajax: {
                url: "<?= \yii\helpers\Url::to(['tran/contactlist']) ?>",
                type: "GET",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                        module: "<?= $module ?>"
                    };
                },
                processResults: function(data) {
                    // Transform data from {"data":[...]} to format expected by Select2
                    return {
                        results: data.data.map(function(contact) {
                            return {
                                id: contact.contact_id,
                                text: contact.contact_name,
                                contact_phone1: contact.contact_phone1 || '',
                                contact_email1: contact.contact_email1 || ''
                            };
                        })
                    };
                },
                cache: true
            },
            placeholder: <?= json_encode(Yii::$app->lang->t('extra', 'extra8')) ?>,
            allowClear: true,
            templateResult: formatContact,
            templateSelection: formatContactSelection,
            // dropdownParent: $('#modal_form_tran').length ? $('#modal_form_tran') : $(document.body)
        });

        // Format contact display in dropdown
        function formatContact(contact) {
            if (!contact.id) return contact.text;

            // Create more informative display showing phone and email
            var $container = $(
                // '<div class="select2-result-contact clearfix">' +
                '<div class="select2-result-contact__name">' + contact.text + '</div>'
                // (contact.contact_phone1 ? '<div class="select2-result-contact__phone"><i class="fa fa-phone me-1"></i> ' + contact.contact_phone1 + '</div>' : '') +
                // (contact.contact_email1 ? '<div class="select2-result-contact__email"><i class="fa fa-envelope me-1"></i> ' + contact.contact_email1 + '</div>' : '') +
                // '</div>'
            );

            return $container;
        }

        // Format selected contact display
        function formatContactSelection(contact) {
            return contact.text || contact.id;
        }

        var translate = <?= json_encode(Yii::$app->lang->t('extra', 'extra11')) ?>;
        var translate1 = <?= json_encode(Yii::$app->lang->t('extra', 'extra12')) ?>;
        var translate2 = <?= json_encode(Yii::$app->lang->t('extra', 'extra13')) ?>;
        // Initialize DataTable
        $("#datatable").DataTable({
            scrollX: true,
            autoWidth: false,
            processing: false,
            serverSide: false,
            lengthMenu: [15, 30, 50, 75, 100],
            pageLength: 15,
            order: [],
            language: {
                info: `${translate1}`,
                infoEmpty: `${translate2}`,
                emptyTable: `
                <div style="text-align: center; padding: 20px 0;">
                   <img width='250px' src='https://cdni.iconscout.com/illustration/premium/thumb/employee-is-unable-to-find-sensitive-data-illustration-download-in-svg-png-gif-file-formats--no-found-misplaced-files-business-pack-illustrations-8062128.png'/>
                    <div style="font-weight: bold; font-size: 16px; margin-top : 8px;">${translate}</div>
                </div>
            `,
                zeroRecords: `
                <div style="text-align: center; padding: 20px 0;">
                   <img width='250px' src='https://cdni.iconscout.com/illustration/premium/thumb/employee-is-unable-to-find-sensitive-data-illustration-download-in-svg-png-gif-file-formats--no-found-misplaced-files-business-pack-illustrations-8062128.png'/>
                    <div style="font-weight: bold; font-size: 16px; margin-top : 8px;">${translate}</div>
                </div>
            `,
                loadingRecords: `
                <div style="text-align: center; padding: 20px 0;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div style="margin-top: 10px;"><?= Yii::$app->lang->t('extra', 'extra94 ') ?></div>
                </div>
            `
            },
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected text-center'
            },
            // In your DataTable initialization
         ajax: {
    type: "GET",
    dataSrc: "data",
    url: "<?= \yii\helpers\Url::to(['tran/list']) ?>",
    data: function(d) {
        const contact = $('select[name="contact"]').val();
        const search = $('input[name="search"]').val();
        const datefilter = $('input[name="datefilter"]').val();
        const status = $('select[name="status"]').val();

        d.contact = contact;
        d.search = search;
        d.datefilter = datefilter;
        d.status = status;
        d.module = '<?= $module ?>';
        d.type = '<?= $type ?>';

        const params = new URLSearchParams();
        if (contact) params.set('contact', contact);
        if (search) params.set('search', search);
        if (datefilter) params.set('datefilter', datefilter);
        if (status) params.set('status', status);

        const newUrl = window.location.pathname + '?' + params.toString();
        window.history.replaceState({}, '', newUrl);
    },
    complete: function() {
        $('.table-loading-overlay').remove();
    }
}

,
            columns: [{
                    data: "tranid",
                    visible: false
                }, // ID transaksi, tidak ditampilkan, tapi digunakan untuk operasi hapus
                {
                    data: null,
                    className: "text-center",
                    orderable: false,
                    render: function(data, type, row) {
                        return `<input type="checkbox" class="select-checkbox" value="${row.tranid}">`;
                    }
                },
               {
  render: function(data, type, row) {
    let rowModule = '<?= $module ?>';
    let rowType = '<?= $type ?>';

    if (row.trantype && row.trantype.includes('/')) {
        const parts = row.trantype.split('/');
        rowModule = parts[0];
        rowType = parts[1];
    }
    
    // Check if the transaction type is a sales or purchase invoice
    const isInvoice = row.trantype === 'sales/invoice' || row.trantype === 'purchase/invoice';

    // Check if the invoice is still unpaid (statuspaid is null or 0)
    const isUnpaid = row.statuspaid === null || row.statuspaid === '0';

    // Kode yang dimodifikasi
let paymentLink = '';
if (isInvoice && isUnpaid) {
    let kastypeParam = '';
    if (row.trantype === 'sales/invoice') {
        kastypeParam = '&kastype=sales';
    } else if (row.trantype === 'purchase/invoice') {
        kastypeParam = '&kastype=purchase';
    }

    paymentLink = `
        <li>
            <a class="dropdown-item text-hover-info"
               href="<?= Url::to(['kas/create']) ?>?id=${row.tranid}${kastypeParam}"
               data-bs-toggle="modal"
               data-bs-target="#modal_form_tran">
               <i class="fas fa-cash-register"></i> <?= (Yii::$app->lang->t('back_layout', 'chat5')) ?>
            </a>
        </li>`;
}

    return `
      <div class="dropdown text-center dropend">
        <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
          <i class="fa-sharp fa-solid fa-list"></i>
        </button>
        <ul class="dropdown-menu px-2">
          <li>
          <a class="dropdown-item text-hover-primary" href="<?= Url::to(['detail']) ?>?id=${row.tranid}">
          <i class="fas fa-eye"></i> Detail
          </a>
          </li>
          <li>
            <a class="dropdown-item text-hover-success"
                href="<?= Url::to(['update']) ?>?id=${row.tranid}"
                data-bs-toggle="modal"
                data-bs-target="#modal_form_tran">
              <i class="fas fa-edit"></i> <?= (Yii::$app->lang->t('back_home', 'chat64')) ?>
            </a>
          </li>
          <li>
            <a class="dropdown-item text-hover-warning"
                href="<?= Url::to(['duplicate']) ?>?id=${row.tranid}"
                data-bs-toggle="modal"
                data-bs-target="#modal_form_tran">
              <i class="fa-solid fa-copy"></i> <?= (Yii::$app->lang->t('back_home', 'chat133')) ?>
            </a>
          </li>
          <li>
            <button class="dropdown-item text-hover-danger delete-tran"
                     data-id="${row.tranid}">
              <i class="fas fa-trash"></i> <?= (Yii::$app->lang->t('back_home', 'chat53')) ?>
            </button>
          </li>
          <li>
            <a class="dropdown-item text-hover-primary"
                href="<?= Url::to(['print']) ?>?id=${row.tranid}&module=${rowModule}&type=${rowType}">
              <i class="fa fa-print"></i> <?= (Yii::$app->lang->t('contact', 'print')) ?>
            </a>
          </li>
          ${paymentLink}
        </ul>
      </div>`;
}
}
,
                {
                    data: "status",
                    className: "text-center",
                    render: function(data, type, row) {
                        // Tentukan status text dan warna berdasarkan nilai status
                        let statusInfo = {
                            0: {
                                text: "Draft",
                                color: "primary",
                                icon: "fa-file-alt"
                            },
                            1: {
                                text: "Approved",
                                color: "success",
                                icon: "fa-check-circle"
                            },
                            5: {
                                text: "Cancelled",
                                color: "warning",
                                icon: "fa-ban"
                            },
                            10: {
                                text: "Rejected",
                                color: "danger",
                                icon: "fa-times-circle"
                            }
                        };

                        // Default jika status tidak dikenali
                        let statusText = "Unknown";
                        let statusBg = "secondary";
                        let statusIcon = "fa-question-circle";
                        let statusOptions = "";

                        // Jika status dikenali, gunakan nilai yang sesuai
                        if (statusInfo[data]) {
                            statusText = statusInfo[data].text;
                            statusBg = statusInfo[data].color;
                            statusIcon = statusInfo[data].icon;

                            // Buat opsi dropdown berdasarkan status saat ini
                            if (data == 0) {
                                statusOptions = `
                                    <li><a class="dropdown-item items update-status d-flex align-items-center" href="#" data-id="${row.tranid}" data-status="1">
                                        <i class="fas fa-check-circle text-success me-2"></i> Approve
                                    </a></li>
                                    <li><a class="dropdown-item items update-status d-flex align-items-center" href="#" data-id="${row.tranid}" data-status="5">
                                        <i class="fas fa-ban text-warning me-2"></i> Cancel
                                    </a></li>
                                `;
                            } else if (data == 1) {
                                statusOptions = `
                                    <li><a class="dropdown-item items update-status d-flex align-items-center" href="#" data-id="${row.tranid}" data-status="5">
                                        <i class="fas fa-ban text-warning me-2"></i> Cancel
                                    </a></li>
                                `;
                            } else if (data == 5) {
                                statusOptions = `
                                    <li><a class="dropdown-item items update-status d-flex align-items-center" href="#" data-id="${row.tranid}" data-status="1">
                                        <i class="fas fa-check-circle text-success me-2"></i> Approve
                                    </a></li>
                                `;
                            }
                        }

                        // Return status dengan desain yang diperbarui
                        return `
                            <div class="dropdown dropend">
                                <button class="btn btn-${statusBg} btn-sm px-3 py-2 d-flex align-items-center justify-content-center mx-auto"
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                        style="min-width: 110px; border-radius: 6px;">
                                    <i class="fas ${statusIcon} me-2"></i>
                                    <span>${statusText}</span>
                                    <i class="fas fa-chevron-down ms-2 opacity-50" style="font-size: 0.8em;"></i>
                                </button>
                                <ul class="dropdown-menu py-2 shadow-sm">${statusOptions}</ul>
                            </div>`;
                    }
                },
                {
                    data: "contact_name",
                    defaultContent: "-",
                    render: function(data, type, row) {
                        return `
						<div class="d-flex flex-column align-items-center mx-auto my-auto w-100">
						<span class="text text-start fw-bold">${row.contact_name}</span>
						<small class="text text-start text-gray-800">${row.jobcompany}</small>
						</div>
						`;
                    }
                },
                {
                    data: "trandate",
                    render: function(data) {
                        if (!data) return '-';
                        // Convert date format if needed
                        const date = new Date(data);
                        const day = String(date.getDate()).padStart(2, '0');
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const year = date.getFullYear();
                        return `${day}-${month}-${year}`;
                    }
                },
                {
                    data: "tranduedate",
                    render: function(data) {
                        if (!data) return '-';
                        // Convert date format if needed
                        const date = new Date(data);
                        const day = String(date.getDate()).padStart(2, '0');
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const year = date.getFullYear();
                        return `${day}-${month}-${year}`;
                    }
                },
                {
                    data: "tranno",
                    className: "text-center",
                    render: function(data) {
                        return `<span class="text text-start">${data || 'No Data'}</span>`;
                    }
                },
                {
                    data: "statuspaid",
                    className: "text-center",
                    render: function(data) {
                        return `<button class="btn btn-${data ? "success" : "danger"} btn-sm px-3 py-2 d-flex align-items-center justify-content-center mx-auto">${data ? '<?= Yii::$app->lang->t('dashboard', 'paid') ?>' : '<?= Yii::$app->lang->t('cashbackend', 'cashbackend12') ?>'}</button>`;
                    }
                },
                {
                    data: "grandtotal", // Nama data yang diambil untuk 'total'
                    className: "text-center",
                    render: function(data, type, row) {
                        console.log(row);
                        // Asumsi bahwa row.total dan row.totalpaid adalah angka
                        let total = row.total || 0; // Nilai default 0 jika tidak ada data
                        let totalPaid = row.totalpaid || 0; // Nilai default 0 jika tidak ada data
                        let sisaTagihan = total - totalPaid; // Operasi aritmatika pengurangan
                        return `<span class="text text-end">${parseFloat(data).toLocaleString('id-ID' , { style : 'currency' , currency : 'IDR' , minimumFractionDigits : 0 , maximumFractionDigits : 0 })}</span>`;
                    }
                },



                {
                    data: "grandtotal",
                    render: function(data) {
                        return data ?
                            `<span class="text text-end">${parseFloat(data).toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 })}</span>` :
                            "-";
                    }
                }
            ],
            createdRow: function(row, data, dataIndex) {
                // Add cursor pointer style to the row
                $(row).css('cursor', 'pointer');

                // Store URL for navigation
                let rowModule = '<?= $module ?>'; // Default module
                let rowType = '<?= $type ?>'; // Default type

                // Check if data.trantype exists and has a format like "purchase/request"
                if (data.trantype && data.trantype.includes('/')) {
                    const parts = data.trantype.split('/');
                    rowModule = parts[0];
                    rowType = parts[1];
                }

                $(row).attr('data-url', `<?= Url::to(['detail']) ?>?id=${data.tranid}&module=${rowModule}&type=${rowType}`);
            },
            initComplete: function() {
                // Add click event handler to the table rows
                 // Get the data URL from the row attribute
                    const url = $(this).attr('data-url');
                    console.log(url);
                    if (url) { 
                        window.location.href = url;
                    }
                $('#datatable tbody').on('click', 'tr', function(e) {
                    if (
                        $(e.target).closest('.select-checkbox').length || // Checkbox
                        $(e.target).closest('.dropdown').length || // Seluruh dropdown container
                        $(e.target).closest('button').length || // Button elements
                        $(e.target).closest('.dropdown-menu').length || // Dropdown menu
                        $(e.target).closest('a').length || // Link elements
                        $(e.target).is('button') || // Button element itself
                        $(e.target).is('input') || // Input element itself
                        $(e.target).is('a') // Link element itself
                    ) {
                        return;
                    }

                   
                });
            }
        });
        // });

        $('<style>')
            .prop("type", "text/css")
            .html(`
        #datatable tbody tr {
            transition: background-color 0.3s ease; /* Tambahkan transisi untuk perubahan background dan transform */
        }
        #datatable tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.1) !important; /* Warna background lebih gelap saat hover */
            cursor: pointer;
        }
    `)
            .appendTo("head");


        // Event handler untuk update status - Perbaikan dari kas ke purchase
        $(document).on("click", ".update-status", function(e) {
            e.preventDefault();

            let tranId = $(this).data("id");
            let newStatus = $(this).data("status");
            let button = $(this).closest(".dropdown").find("button");

            // Status info untuk UI
            let statusInfo = {
                0: {
                    text: "Draft",
                    color: "primary",
                    icon: "fa-file-alt"
                },
                1: {
                    text: "Approved",
                    color: "success",
                    icon: "fa-check-circle"
                },
                5: {
                    text: "Cancelled",
                    color: "warning",
                    icon: "fa-ban"
                },
                10: {
                    text: "Rejected",
                    color: "danger",
                    icon: "fa-times-circle"
                }
            };

            // Set sementara untuk UI feedback
            if (statusInfo[newStatus]) {
                const statusText = statusInfo[newStatus].text;
                const statusBg = statusInfo[newStatus].color;
                const statusIcon = statusInfo[newStatus].icon;

                // Update tampilan tombol
                button.attr('class', `btn btn-${statusBg} btn-sm px-3 py-2 d-flex align-items-center justify-content-center mx-auto`);
                button.html(`
                    <i class="fas ${statusIcon} me-2 text-${statusBg}"></i>
                    <span>${statusText}</span>
                    <i class="fas fa-chevron-down ms-2 opacity-50" style="font-size: 0.8em;"></i>
                `);
            }

            // Kirim request ke endpoint purchase (bukan kas)
            $.ajax({
                url: "<?= Url::to(['/tran/updatestatus']) ?>",
                type: "POST",
                data: {
                    id: tranId,
                    status: newStatus,
                    _csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
                },
                headers: {
                    "X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
                },
                success: function(response) {
                    if (response.success) {
                        let alertType;
                        console.log(newStatus);
                        if (newStatus == 1) {
                            alertType = "success"; // Default
                        } else {
                            alertType = 'warning'
                        }

                        // Tampilkan notifikasi sukses
                        // console.log(response.statusText); 
                        showBootstrapAlert("<?= Yii::$app->lang->t('extra', 'extra63') . " " ?>" + response.statusText, alertType);

                        // Reload DataTable tanpa reset pagination
                        $("#datatable").DataTable().ajax.reload(null, false);
                    } else {
                        // Tampilkan pesan error
                        showBootstrapAlert("<?= Yii::$app->lang->t('extra', 'extra64') . " " ?>" + response.message, "danger");

                        // Reload DataTable untuk mengembalikan status asli
                        $("#datatable").DataTable().ajax.reload(null, false);
                    }
                },
                error: function(xhr) {
                    // Tampilkan pesan error lengkap jika tersedia
                    let errorMsg = "Terjadi kesalahan saat memperbarui status.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg += " " + xhr.responseJSON.message;
                    }

                    showBootstrapAlert(errorMsg, "danger");

                    // Reload DataTable untuk mengembalikan status asli
                    $("#datatable").DataTable().ajax.reload(null, false);
                }
            });
        });

        // DateRangePicker setup
        // flatpickr("#datefilter", {
        //     mode: "range", // Bisa "single" jika hanya satu tanggal
        //     dateFormat: "d-m-Y", // Format tanggal
        //     locale: {
        //         rangeSeparator: " - " // Biarkan backend menggunakan pemisah "-"
        //     },
        //     onClose: function(selectedDates, dateStr, instance) {
        //         // Trigger reload atau logika filter setelah memilih tanggal
        //         $("#datatable").DataTable().ajax.reload();
        //     },
        //     position: 'below', // Popup muncul di bawah input
        //     opens: 'bottom', // Tentukan popup muncul di bawah input

        //     // Tambahkan opsi untuk tombol clear
        //     onReady: function(selectedDates, dateStr, instance) {
        //         // Tambahkan event listener untuk tombol clear
        //         $('#datefilter').on('click', function() {
        //             // Ketika input diklik, kita bisa kosongkan nilai
        //             if ($(this).val() !== "") {
        //                 $(this).val(""); // Clear the input value
        //                 instance.clear(); // Clear flatpickr
        //                 $("#datatable").DataTable().ajax.reload(); // Reload data table setelah clear
        //             }
        //         });
        //     }
        // });

        // // Agar value berada di tengah input
        // $("#datefilter").css("text-align", "center");

        function formatRange(start, end) {
            return start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY');
        }

        function reloadWithRange(start, end) {
            $('#datefilter').val(formatRange(start, end));
            $('#datefilter').data('daterangepicker').setStartDate(start);
            $('#datefilter').data('daterangepicker').setEndDate(end);
            $("#datatable").DataTable().ajax.reload();
        }

        $('#btnThisWeek').click(function() {
            const start = moment().startOf('week');
            const end = moment().endOf('week');
            reloadWithRange(start, end);
        });

        $('#btnLastWeek').click(function() {
            const start = moment().subtract(1, 'week').startOf('week');
            const end = moment().subtract(1, 'week').endOf('week');
            reloadWithRange(start, end);
        });

        $('#btnThisMonth').click(function() {
            const start = moment().startOf('month');
            const end = moment().endOf('month');
            reloadWithRange(start, end);
        });

        $('#btnLastMonth').click(function() {
            const start = moment().subtract(1, 'month').startOf('month');
            const end = moment().subtract(1, 'month').endOf('month');
            reloadWithRange(start, end);
        });

        $('#btnThisYear').click(function() {
            const start = moment().startOf('year');
            const end = moment().endOf('year');
            reloadWithRange(start, end);
        });

        $('#btnLastYear').click(function() {
            const start = moment().subtract(1, 'year').startOf('year');
            const end = moment().subtract(1, 'year').endOf('year');
            reloadWithRange(start, end);
        });

        // Initialize Date Range Picker
        $('#datefilter').daterangepicker({
            locale: {
                format: 'DD-MM-YYYY',
                separator: ' - ',
                applyLabel: 'Apply',
                cancelLabel: 'Clear',
            },
            autoUpdateInput: false,
            autoApply: false
        });

        // Handle apply event
        $('#datefilter').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
            $("#datatable").DataTable().ajax.reload();
        });

        // Handle cancel event (clear)
        $('#datefilter').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            $("#datatable").DataTable().ajax.reload();
        });

        // Prevent menu close when clicking on the datepicker
        $(document).on('click', '.daterangepicker', function(e) {
            e.stopPropagation();
        });

        // Handle menu close to prevent bugs
        $('[data-kt-menu-id="filter-menu"]').on('hidden.bs.dropdown', function() {
            if ($('#datefilter').data('daterangepicker')) {
                $('#datefilter').data('daterangepicker').hide();
            }
        });

        // Form submissions
        $("#search").submit(function(e) {
            e.preventDefault(); // Don't let the form reload the page
            $("#datatable").DataTable().ajax.reload();
        });

        $("#filterForm").submit(function(e) {
            e.preventDefault(); // Don't let the form reload the page
            $("#datatable").DataTable().ajax.reload();
        });

        // Handle the modal loading for links with data-bs-toggle="modal"
        $(document).on('click', 'a[data-bs-toggle="modal"]', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            const target = $(this).attr('data-bs-target');

            // Different handling based on which modal is being opened
            if (target === '#modal_form_tran') {
                $('#modal-content').html('<div class="text-center p-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading...</p></div>');

                // Check if this is an edit action by looking at the URL
                const isEdit = url.includes('update') || url.includes('edit');

                // Set the modal title based on action type, module, and transaction type
                const module = new URLSearchParams(url.split('?')[1]).get('module') || '<?= $module ?>';
                const type = new URLSearchParams(url.split('?')[1]).get('type') || '<?= $type ?>';
                const add = <?= json_encode(Yii::$app->lang->t('cta_add', 'cta_add')) ?>;
                const title = <?= json_encode($title) ?>;
                // Update the modal title
                if (isEdit) {
                    $('.modal-title').text(`Edit ${title}`);
                } else {
                    $('.modal-title').text(`${add} ${title}`);
                }

                // if (isEdit) {
                //     $('.modal-title').text(`Edit ${module.charAt(0).toUpperCase() + module.slice(1)} ${type.charAt(0).toUpperCase() + type.slice(1)}`);
                // } else {
                //     $('.modal-title').text(`Add ${module.charAt(0).toUpperCase() + module.slice(1)} ${type.charAt(0).toUpperCase() + type.slice(1)}`);
                // }
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        penomoran: "<?= $formatnumber ?>"
                    },
                    success: function(data) {
                        // Use DOMParser to parse HTML
                        let parser = new DOMParser();
                        let doc = parser.parseFromString(data, 'text/html');

                        // Insert content into the modal
                        $('#modal-content').html(doc.body.innerHTML);

                        $(target).find('.modal-dialog').css('max-width', '95%');
                    },
                    error: function() {
                        $('#modal-content').html('<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>Error loading form.</div>');
                    }
                });
            } else if (target === '#modal_detail_tran') {
                $('#modal-detail-content').html('<div class="text-center p-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading...</p></div>');

                // Extract the module and type from the URL
                const urlParams = new URLSearchParams(url.split('?')[1]);
                const module = urlParams.get('module') || '<?= $module ?>';
                const type = urlParams.get('type') || '<?= $type ?>';

                // Update the modal title with module and type info
                $('#modal_detail_tran_header h2.fw-bold').text(`Detail ${module.charAt(0).toUpperCase() + module.slice(1)} ${type.charAt(0).toUpperCase() + type.slice(1)}`);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('#modal-detail-content').html(response);
                    },
                    error: function(xhr) {
                        let errorMessage = "Gagal memuat detail transaksi.";
                        if (xhr.responseJSON && xhr.responseJSON.pesan) {
                            errorMessage = xhr.responseJSON.pesan;
                        }

                        $('#modal-detail-content').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i> ${errorMessage}
                </div>
            `);
                    }
                });
            }

            // Show the modal
            $(target).modal('show');
        });

        var deletemessage1 = "<?= Yii::$app->lang->t('extra', 'extra44') ?>";
        var deletemessage2 = "<?= Yii::$app->lang->t('extra', 'extra45') ?>";
        var deletemessage3 = "<?= Yii::$app->lang->t('extra', 'extra46') ?>";
        var deletemessage3koma1 = "<?= Yii::$app->lang->t('extra', 'extra46.1') ?>";
        var deletemessage4 = "<?= Yii::$app->lang->t('back_home', 'chat34') ?>";
        var deletemessage5 = "<?= Yii::$app->lang->t('back_home', 'chat53') ?>";
        // Delete transaction
        $(document).on('click', '.delete-tran', function() {
            var tranId = $(this).data('id'); // Get transaction ID

            if (!tranId) {
                Swal.fire({
                    title: "Error!",
                    text: "ID tidak ditemukan! Periksa tombol yang diklik.",
                    icon: "error",
                    confirmButtonColor: "#d33",
                    confirmButtonText: "OK"
                });
                return;
            }

            Swal.fire({
                title: deletemessage1,
                text: deletemessage2,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: deletemessage5,
                cancelButtonText: deletemessage4
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= Url::to(['/tran/delete']) ?>',
                        type: 'post',
                        data: {
                            id: tranId,
                            _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
                        },
                        headers: {
                            "X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
                        },
                        success: function(response) {
                            // Reload DataTable
                            $('#datatable').DataTable().ajax.reload();

                            // Show success notification
                            Swal.fire({
                                title: "<?= Yii::$app->lang->t('extra', 'extra62') ?>",
                                text: "<?= Yii::$app->lang->t('extra', 'extra59') ?>",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr.responseText);

                            // Show error notification
                            Swal.fire({
                                title: "<?= Yii::$app->lang->t('extra', 'extra60') ?>",
                                text: "<?= Yii::$app->lang->t('extra', 'extra61') ?>",
                                icon: "error",
                                confirmButtonColor: "#d33",
                                confirmButtonText: "OK"
                            });
                        }
                    });
                }
            });
        });
    });
    
</script>

<style>
    .bg-success {
        background-color: #50cd89;
        color: white;
    }

    .bg-danger {
        background-color: #f1416c;
        color: white;
    }

    .bg-primary {
        background-color: #009ef7;
        color: white;
    }

    .bg-warning {
        background-color: #ffac1b;
        color: white;
    }

    .bg-secondary {
        background-color: #a1a5b7;
        color: white;
    }

    .pagination.page-item.page-link {
        border: none;
        color: #5e6278;
        font-weight: 500;
        border-radius: 6px;
        margin: 0 4px;
        padding: 8px 16px;
    }

    .pagination .page-item.active .page-link {
        background-color: #009ef7;
        color: #fff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    .pagination.page-item.disabled.page-link {
        color: #ccc;
        pointer-events: none;
    }

    .pagination.page-link:hover {
        background-color: #f4f4f4;
    }

    /* Hover effect on dropdown items */
    .dropdown-item {
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        border-radius: 4px;
        margin: 0 5px;
    }

    .dropdown-item.text-hover-success:hover {
        background-color: rgba(80, 205, 137, 0.1);
    }

    .dropdown-item.text-hover-danger:hover {
        background-color: rgba(241, 65, 108, 0.1);
    }

    .dropdown-item.text-hover-warning:hover {
        background-color: rgba(255, 172, 27, 0.1);
    }

    .dropdown-item.text-hover-primary:hover {
        background-color: rgba(0, 158, 247, 0.1);
    }

    /* Status button styling */
    .dropdown button[data-bs-toggle="dropdown"] {
        transition: all 0.3s ease;
    }

    .dropdown button[data-bs-toggle="dropdown"]:hover {
        filter: brightness(95%);
    }

    /* Alert styling */
    .alert {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    /* Mass action buttons styling */
    #mass-action-buttons .btn {
        margin-right: 5px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1rem;
        border-radius: 0.475rem;
        transition: all 0.2s ease;
    }

    #mass-action-buttons .btn-danger {
        background-color: #f1416c;
        border-color: #f1416c;
    }

    #mass-action-buttons .btn-success {
        background-color: #50cd89;
        border-color: #50cd89;
    }

    #mass-action-buttons .btn-warning {
        background-color: #ffac1b;
        border-color: #ffac1b;
    }

    #mass-action-buttons .btn:hover {
        filter: brightness(90%);
    }

    .flatpickr-calendar {
        position: absolute !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        /* Menggeser popup ke tengah */
        z-index: 9999;
        /* Pastikan di atas konten lain */
    }
</style>