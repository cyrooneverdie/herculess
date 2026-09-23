<?php
//indexpro.php
// var_dump($title);
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $title;
$urladd = Url::to(['create', 'module' => $module, 'type' => $type]);
$formatnumber = 'PO';
switch ($title) {
    case 'Sales Quote':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar14');
        $formatnumber = "QU";
        $urladd = Url::to(['createpro', 'module' => $module, 'type' => $type]);
        break;
    case 'Sales Order':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar15');
        $formatnumber = "ORD";
        $urladd = Url::to(['createpro', 'module' => $module, 'type' => $type]);
        break;
}

?>

<div class="card">
    <div class="card-header border-0 pt-6 sticky-top bg-white shadow-sm"
        style="top: var(--kt-app-header-height, 70px); z-index: 1010;">
        <div class="card-title flex-grow-1">
            <div class="d-flex align-items-center position-relative my-2 w-100">
                <form method="get" action="index" id="search" class="w-100">
                    <div class="position-relative">
                        <span class="position-absolute top-50 start-0 translate-middle-y ms-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"
                                fill="#a1a5b7">
                                <path
                                    d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
                            </svg>
                        </span>
                        <input data-kt-docs-table-filter="search" type="text" name="search"
                            class="form-control form-control-solid w-250px ps-10"
                            placeholder=" <?= Yii::$app->lang->t('home', 'search') ?>" />

                        <span class="position-absolute top-50 end-0 translate-middle-y me-3 d-none" id="clear-search">
                            <i class="ki-duotone ki-cross fs-2 text-gray-500 cursor-pointer" style="opacity: 0.5;"></i>
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-toolbar flex-shrink-0">
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                    data-kt-menu-placement="left-start">
                    <i class="ki-duotone ki-filter fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>Filter
                </button>

                <div class="menu menu-sub menu-sub-dropdown w-sm-500px w-md-600px" data-kt-menu="true"
                    data-kt-menu-id="filter-menu">
                    <div class="px-7 py-5">
                        <div class="fs-5 text-gray-900 fw-bold"><?= Yii::$app->lang->t('extra', 'extra9') ?></div>
                    </div>
                    <div class="separator border-gray-200"></div>
                    <div class="px-7 py-5" data-kt-user-table-filter="form">
                        <form id="filterForm" method="get" action="index">
                            <div class="row">
                                <div class="mb-3">
                                    <div class="btn-group mb-2" role="group" aria-label="Quick Date Filters">
                                        <button type="button" class="btn btn-sm btn-light-primary"
                                            id="btnThisYear"><?= Yii::$app->lang->t('extra', 'extra96') ?></button>
                                        <button type="button" class="btn btn-sm btn-light-primary"
                                            id="btnThisMonth"><?= Yii::$app->lang->t('extra', 'extra97') ?></button>
                                        <button type="button" class="btn btn-sm btn-light-primary"
                                            id="btnThisWeek"><?= Yii::$app->lang->t('extra', 'extra98') ?></button>
                                        <button type="button" class="btn btn-sm btn-light-primary"
                                            id="btnLastYear"><?= Yii::$app->lang->t('extra', 'extra99') ?></button>
                                        <button type="button" class="btn btn-sm btn-light-primary"
                                            id="btnLastMonth"><?= Yii::$app->lang->t('extra', 'extra100') ?></button>
                                        <button type="button" class="btn btn-sm btn-light-primary"
                                            id="btnLastWeek"><?= Yii::$app->lang->t('extra', 'extra101') ?></button>
                                    </div>
                                </div>
                                <div class="md-10 mb-2">
                                    <label class="fw-semibold fs-6 mb-2 mt-3"
                                        for="dateFilter"><?= Yii::$app->lang->t('tran', 'tran_date') ?></label>
                                    <input name="datefilter" class="form-control form-control-solid"
                                        style="cursor:pointer;" id="datefilter"
                                        placeholder="<?= Yii::$app->lang->t('extra', 'extra58') ?>" autocomplete="off">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="fw-semibold fs-6 mb-2"
                                            for="contactFilter"><?= Yii::$app->lang->t('front_home', 'contact') ?></label>
                                        <select id="contactSelect" class="form-select contact" data-control="select2"
                                            name="contact">
                                            <!-- <option value="">All Contacts</option> -->
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="fw-semibold fs-6 mb-2" for="statusFilter">Status</label>
                                        <select id="statusSelect" class="form-select status" data-control="select2"
                                            name="status">
                                            <option value=""><?= Yii::$app->lang->t('extra', 'extra57') ?></option>
                                            <option value="paid"><?= Yii::$app->lang->t('dashboard', 'paid') ?></option>
                                            <option value="unpaid">
                                                <?= Yii::$app->lang->t('cashbackend', 'cashbackend12') ?>
                                            </option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group text-end mt-5">
                                <button type="submit" id="filterButton" class="btn btn-lg btn-primary"><i
                                        class="fa-sharp fa-solid fa-filter"></i></button>
                            </div>
                        </form>
                    </div>
                </div>

                <a href="<?= $urladd ?>" class="btn btn-primary" id="btn-add-tran" data-bs-toggle="modal"
                    data-bs-target="#modal_form_tran">
                    <i class="ki-duotone ki-plus fs-2"></i> <?= Yii::$app->lang->t('cta_add', 'cta_add') ?>
                </a>
            </div>
        </div>
    </div>

    <div class="card-body pt-5">
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

        <table class="table align-left table-row-dashed fs-6 gy-5" id="datatable">
            <thead>
                <tr class="text-start bg-gray-100 fs-6 text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                    <th class="d-none"></th>
                    <th class="text-start w-50px mx-1">
                        <div class="form-check form-check-custom form-check-solid form-check-sm px-3">
                            <input class="form-check-input" type="checkbox" id="select-all">
                        </div>
                    </th>
                    <th class="text-center min-w-50px"><?= Yii::$app->lang->t('produk_table', 'produk_action') ?></th>
                    <?php if ("$module/$type" == "sales/order") { ?>
                        <th class="text-start min-w-50px">Detail</th>
                    <?php } ?>
                    <th class="text-center min-w-100px">Status Approval</th>
                    <?php if ("$module/$type" == "sales/order") { ?>
                        <th class="text-center min-w-100px">Status Proyek</th>
                    <?php } ?>
                    <th class="text-start min-w-175px"><?= Yii::$app->lang->t('tran', 'tran_no') ?></th>
                    <th class="text-start min-w-175px"><?= Yii::$app->lang->t('extra', 'extra54') ?></th>
                    <th class="text-start min-w-150px"><?= Yii::$app->lang->t('tran', 'tran_event') ?></th>
                    <th class="text-start min-w-150px"><?= Yii::$app->lang->t('tran', 'tran_setup') ?></th>
                    <th class="text-start min-w-175px"> <?= Yii::$app->lang->t('tran', 'tran_date') ?></th>
                    <th class="text-start min-w-175px"> <?= Yii::$app->lang->t('tran', 'tran_duedate') ?></th>
                    <?php if ("$module/$type" == "sales/order") { ?>
                        <th class="text-center min-w-150px">
                            <?= Yii::$app->lang->t('purchase_table', 'purchase_statuspembayaran') ?>
                        </th>
                        <th class="text-left min-w-150px">
                            <?= Yii::$app->lang->t('purchase_table', 'purchase_sisatagihan') ?>
                        </th>
                        <th class="text-left min-w-150px">Total</th>
                    <?php } ?>
                    <th class="text-start min-w-100px">Created By</th>
                </tr>
            </thead>
            <tbody class="text-gray-800 fw-semibold text-start">
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modal_form_tran" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-lg-down modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= Yii::$app->lang->t('cta_add', 'cta_add') ?> <?= $title ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-content-tran" class="nopadding">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_list_track" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <?= Yii::$app->function->findByField("tranno", "trans", " and tranid ='" . $model->tranid . "' ") ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-content-track" class="nopadding">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_detail_tran" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" id="modal_detail_tran_header">
                <h2 class="fw-bold">Detail Transaksi</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-detail-content">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade addContactModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    style="z-index:9999!important">
    <div class="modal-dialog modal-lg modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-titles">Add Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_form_po" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-current-tranid="<?= isset($model->tranid) ? $model->tranid : '' ?>">
    <div class="modal-dialog modal-lg modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-content" class="nopadding"></div>
            </div>
        </div>
    </div>
</div>

<script>
    let leafletMap = null;
    let marker = null;
    let searchTimeout = null;

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

    $.fn.dataTable.ext.errMode = "none";

    function toggleMassActionButtons() {
        const $btn = $('#mass-action-buttons');

        if ($('.select-checkbox:checked').length > 0) {
            if (!$btn.hasClass('show')) {
                $btn.css('display', 'block');
                setTimeout(() => {
                    $btn.addClass('show');
                }, 10);
            }
        } else {
            $btn.removeClass('show');
            setTimeout(() => {
                $btn.css('display', 'none');
            }, 300); // sesuai durasi transisi CSS
        }
    }

    function showBootstrapAlert(message, type) {
        let alertPlaceholder = document.getElementById("liveAlertPlaceholder");

        if (!alertPlaceholder) {
            alertPlaceholder = document.createElement("div");
            alertPlaceholder.id = "liveAlertPlaceholder";

            alertPlaceholder.className = "position-fixed bottom-0 end-0 p-3";
            alertPlaceholder.style.zIndex = "9999";

            document.body.appendChild(alertPlaceholder);
        }

        let wrapper = document.createElement("div");
        wrapper.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show d-flex align-items-center shadow-lg" role="alert" style="min-width: 300px; margin-bottom: 0;">
            <div class="me-3">
                ${type === 'success' ? '<i class="fas fa-check-circle fa-lg text-success"></i>' : ''}
                ${type === 'danger' ? '<i class="fas fa-exclamation-circle fa-lg text-danger"></i>' : ''}
                ${type === 'warning' ? '<i class="fas fa-exclamation-triangle fa-lg text-warning"></i>' : ''}
                ${type === 'info' ? '<i class="fas fa-info-circle fa-lg text-info"></i>' : ''}
            </div>
            <div>${message}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;

        alertPlaceholder.innerHTML = "";
        alertPlaceholder.append(wrapper);

        setTimeout(function () {
            const alert = bootstrap.Alert.getOrCreateInstance(wrapper.querySelector('.alert'));
            if (alert) alert.close();
        }, 5000);
    }

    $("#select-all").on("click", function () {
        const isChecked = this.checked;

        $("tbody .select-checkbox").each(function () {
            if (isChecked) {
                $(this)
                    .prop("checked", true)
                    .addClass("checked-anim");

                setTimeout(() => {
                    $(this).removeClass("checked-anim");
                }, 400);
            } else {
                $(this).prop("checked", false);
            }
        });

        toggleMassActionButtons();
    });

    $("#datatable tbody").on("change", ".select-checkbox", function () {
        $("#select-all").prop(
            "checked",
            $(".select-checkbox").length === $(".select-checkbox:checked").length
        );
        toggleMassActionButtons();
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

    function performMassAction(action, statusCode) {
        let selectedIds = $(".select-checkbox:checked")
            .map(function () {
                return $(this).val();
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
                    success: function (response) {
                        $("#datatable").DataTable().ajax.reload();
                        $("#mass-action-buttons").hide();
                        $("#select-all").prop("checked", false);

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
                    error: function (xhr) {
                        console.error("Error:", xhr.responseText);
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

    $('#btn-delete-mass').on('click', function () {
        performMassAction('delete', 10);
    });

    $('#btn-approve-mass').on('click', function () {
        performMassAction('approve', 1);
    });

    $('#btn-cancel-mass').on('click', function () {
        performMassAction('cancel', 5);
    });

    $("#filterForm").submit(function (e) {
        e.preventDefault();
        $("#datatable").DataTable().ajax.reload();
        let filterMenu = document.querySelector("[data-kt-menu-id='filter-menu']");
        if (filterMenu) {
            KTMenu.getInstance(filterMenu).hide();
        }
    });

    $(document).ready(function () {

        function selectContact(target, selection, type, positionid, label) {
            let $select = $(target).find(selection);

            $(selection).off('change.contact').off('select2:open.contact').select2({
                ajax: {
                    url: "<?= Url::to(['contact/select']) ?>",
                    type: "POST",
                    dataType: "json",
                    data: function (params) {
                        return {
                            contacttype: type,
                            positionid: positionid,
                            q: params.term,
                            page: params.page,
                            module: "<?= $module ?? 'purchase' ?>"
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.items,
                            pagination: {
                                more: (params.page * 5) < data.totalcount
                            }
                        };
                    },
                    cache: false
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
                templateSelection: function (param) {
                    if (!param.id) {
                        return `Pilih ${label}`;
                    }
                    return param.jobcompany;
                },
                templateResult: function (contact) {
                    if (!contact.id) {
                        return `Pilih ${label}`;
                    }
                    if (contact.loading) {
                        return type === 'customer' ? contact.jobcompany : contact.text;
                    }
                    let html = `
                    <div class="d-flex align-items-start gap-2 py-1">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px;height:32px;font-size:13px;font-weight:600;">
                            ${type === 'customer' ? contact.jobcompany.charAt(0).toUpperCase() : contact.text.charAt(0).toUpperCase()}
                        </div>
                        <div class="d-flex flex-column flex-grow-1 overflow-hidden">
                            <span class="fw-semibold text-dark text-truncate" style="font-size:13px;">
                                ${type === 'customer' ? contact.jobcompany : contact.text}
                            </span>
                            <div class="d-flex flex-wrap gap-1 mt-1">
                       `;

                    html += `
                            </div>
                        </div>
                    </div>
                `;
                    return $(html);
                },
                placeholder: `Pilih ${label}`,
                allowClear: true,
                width: '100%'

            }).on('select2:open.contact', function () {
                let $dropdown = $('.select2-dropdown');

            }).on('change.contact', function (e) {
            });
        }

        selectContact('#filterForm', '#contactSelect', 'customer', null, 'Contact');

        var translate = <?= json_encode(Yii::$app->lang->t('extra', 'extra11')) ?>;
        var translate1 = <?= json_encode(Yii::$app->lang->t('extra', 'extra12')) ?>;
        var translate2 = <?= json_encode(Yii::$app->lang->t('extra', 'extra13')) ?>;
        $("#datatable").DataTable({
            scrollX: false,
            autoWidth: false,
            processing: false,
            serverSide: false,
            lengthMenu: [5, 15, 30, 50, 75, 100],
            pageLength: 5,
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
                    <div style="margin-top: 10px;"><?= Yii::$app->lang->t('extra', 'extra94') ?></div>
                </div>
            `
            },
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected text-center'
            },
            ajax: {
                type: "GET",
                dataSrc: "data",
                url: "<?= \yii\helpers\Url::to(['tran/list']) ?>",
                data: function (d) {
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
                complete: function () {
                    $('.table-loading-overlay').remove();
                }
            },
            columns: [{
                data: "tranid",
                visible: false
            },
            {
                data: null,
                className: "text-center",
                orderable: false,
                render: function (data, type, row) {
                    return `
                    <div class="form-check form-check-custom form-check-solid form-check-sm px-3">
                         <input type="checkbox" class="form-check-input row-checkbox select-checkbox" value="${row.tranid}">
                    </div>
                     `;
                }
            },
            {
                render: function (data, type, row) {
                    let rowModule = '<?= $module ?>';
                    let rowType = '<?= $type ?>';

                    if (row.trantype && row.trantype.includes('/')) {
                        const parts = row.trantype.split('/');
                        rowModule = parts[0];
                        rowType = parts[1];
                    }

                    const isInvoice = row.trantype === 'sales/order';
                    const isUnpaid = row.statuspaid === null || row.statuspaid === '0';
                    const sisa = parseFloat(row.sisa_pembayaran) || 0;
                    const showPaymentBtn = isInvoice && sisa > 0;
                    const isApproved = row.status == 1;

                    const userRoleId = <?= !Yii::$app->user->isGuest && isset(Yii::$app->user->identity->role_id) ? Yii::$app->user->identity->role_id : 'null' ?>;
                    const isSuperAdmin = (userRoleId === 0 || userRoleId === '0');

                    // let paymentLink = '';
                    // if (showPaymentBtn && isUnpaid && isApproved) {
                    //     let kastypeParam = '&kastype=sales';

                    //     paymentLink = `
                    //         <li>
                    //             <a class="dropdown-item text-hover-info"
                    //             href="<?= Url::to(['cash/create']) ?>?id=${row.tranid}${kastypeParam}"
                    //             data-bs-toggle="modal"
                    //             data-bs-target="#modal_form_tran">
                    //             <i class="fas fa-cash-register"></i> <?= (Yii::$app->lang->t('back_layout', 'chat5')) ?>
                    //             </a>
                    //         </li>`;
                    // }

                    let editLink = '';
                    if (row.status === 0 || row.status === 5 || isSuperAdmin) {
                        editLink = `
                            <li>
                                <a class="dropdown-item text-hover-success"
                                    href="<?= Url::to(['updatepro']) ?>?id=${row.tranid}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal_form_tran">
                                <i class="fas fa-edit me-2"></i> <?= Yii::$app->lang->t('back_home', 'chat64') ?>
                                </a>
                            </li>
                        `;
                    }

                    let printLink = '';
                    if (rowModule === 'sales' && rowType === 'order') {
                        printLink = `
                            <li>
                                <a class="dropdown-item text-hover-primary" href="<?= Url::to(['tran/track']) ?>?id=${row.tranid}"
                                  data-bs-toggle="modal" data-bs-target="#modal_list_track">
                                <i class="fa-solid fa-location-dot me-2"></i> Track
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-hover-primary wa-send" href="#" data-tranid="${row.tranid}">
                                <i class="fa-brands fa-whatsapp me-2"></i> Share via WhatsApp
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-hover-warning" href="<?= Url::to(['/report/print']) ?>?id=${row.tranid}&module=${rowModule}&type=${rowType}"
                                    onclick="return confirm('Apakah Anda ingin mendownload data ini?')">
                                    <i class="fa fa-print me-2"></i> <?= Yii::$app->lang->t('contact', 'print') ?> Surat Penagihan
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-hover-info" href="<?= Url::to(['/report/print']) ?>?id=${row.tranid}&module=${rowModule}&type=delivery"
                                    onclick="return confirm('Apakah Anda ingin mendownload Surat Jalan ini?')">
                                    <i class="fa fa-truck me-2"></i> Print Surat Jalan Original
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-hover-dark" href="<?= Url::to(['/report/print']) ?>?id=${row.tranid}&module=${rowModule}&type=delivery2"
                                    onclick="return confirm('Apakah Anda ingin mendownload Surat Jalan ini?')">
                                    <i class="fa-solid fa-truck-pickup me-2"></i> Print Surat Jalan Copy
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-hover-info" href="<?= Url::to(['/report/print']) ?>?id=${row.tranid}&module=${rowModule}&type=pengambilan"
                                    onclick="return confirm('Apakah Anda ingin mendownload Surat Pengambilan ini?')">
                                    <i class="fa fa-box-open me-2"></i> Print Surat Pengambilan Original
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-hover-blue-500" href="<?= Url::to(['/report/print']) ?>?id=${row.tranid}&module=${rowModule}&type=pengambilan2"
                                    onclick="return confirm('Apakah Anda ingin mendownload Surat Pengambilan ini?')">
                                    <i class="fa fa-folder-open me-2"></i> Print Surat Pengambilan Copy
                                </a>
                            </li>
                        `;
                    }

                    return `
                        <div class="dropdown text-center dropend">
                           <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport">
                                <i class="fa-sharp fa-solid fa-list"></i>
                            </button>
                            <ul class="dropdown-menu px-2 scroll-y mh-200px">
                                <li>
                                    <a class="dropdown-item text-hover-primary" href="<?= Url::to(['tran/createtrack']) ?>?id=${row.tranid}">
                                    <i class="fas fa-eye me-2"></i> Detail
                                    </a>
                                </li>
                                ${editLink}
                                <li>
                                    <button class="dropdown-item text-hover-danger delete-tran"
                                            data-id="${row.tranid}">
                                    <i class="fas fa-trash me-2"></i> <?= Yii::$app->lang->t('back_home', 'chat53') ?>
                                    </button>
                                </li>
                                ${printLink}
                            </ul>
                        </div>`;
                }
            },
                <?php if ("$module/$type" == "sales/order") { ?>
                                                {
                    data: "tranid",
                    className: "text-center",
                    render: function (data, type, row) {
                        return `
                        <button class="btn btn-sm btn-light-primary btn-icon neo-orba" data-id="${row.tranid}">
                            <i class="fa-solid fa-caret-right"></i>
                        </button>`;
                    }
                },
                <?php } ?>
        {
                data: "status",
                className: "text-start",
                render: function (data, type, row) {
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
                        },

                    };

                    let statusText = "Unknown";
                    let statusBg = "secondary";
                    let statusIcon = "fa-question-circle";
                    let statusOptions = "";

                    if (statusInfo[data]) {
                        statusText = statusInfo[data].text;
                        statusBg = statusInfo[data].color;
                        statusIcon = statusInfo[data].icon;

                        if (data == 0) {
                            statusOptions = `
                                    <li>
                                        <a class="dropdown-item items update-status d-flex align-items-center" href="#" data-id="${row.tranid}" data-status="1">
                                          <i class="fas fa-check-circle text-success me-2"></i> Approve
                                         </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item items update-status d-flex align-items-center" href="#" data-id="${row.tranid}" data-status="5">
                                            <i class="fas fa-ban text-warning me-2"></i> Cancel
                                        </a>
                                    </li>
                                `;
                        } else if (data == 1) {
                            statusOptions = `
                                    <li>
                                        <a class="dropdown-item items update-status d-flex align-items-center" href="#" data-id="${row.tranid}" data-status="5">
                                            <i class="fas fa-ban text-warning me-2"></i> Cancel
                                        </a>
                                    </li>
                                `;
                        } else if (data == 5) {
                            statusOptions = `
                                    <li>
                                        <a class="dropdown-item items update-status d-flex align-items-center" href="#" data-id="${row.tranid}" data-status="1">
                                            <i class="fas fa-check-circle text-success me-2"></i> Approve
                                        </a>
                                    </li>
                                `;
                        }
                    }

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
                <?php if ("$module/$type" == "sales/order") { ?>
                                                                                                                                                                                                                                                                                                                                                                                                    {
                    data: "statuspro",
                    className: "text-start",
                    render: function (data, type, row) {
                        let statusInfo = {
                            0: {
                                text: "Lead",
                                color: "primary",
                                icon: "fa-clock-rotate-left"
                            },
                            1: {
                                text: "Deal",
                                color: "success",
                                icon: "fa-handshake"
                            },
                            5: {
                                text: "On Progress",
                                color: "info",
                                icon: "fa-spinner"
                            },
                            10: {
                                text: "Follow Up",
                                color: "warning",
                                icon: "fa-comments"
                            },
                            15: {
                                text: "Finish",
                                color: "dark",
                                icon: "fa-check-double"
                            }
                        };

                        let statusText = "Unknown";
                        let statusBg = "secondary";
                        let statusIcon = "fa-question-circle";
                        let statusOptions = "";
                        let isScanOut = row.total_scan_out >= row.total_del && row.total_ret > 0;

                        if (statusInfo[data]) {
                            statusText = statusInfo[data].text;
                            statusBg = statusInfo[data].color;
                            statusIcon = statusInfo[data].icon;


                            if (data == 0 && row.status == 1) {
                                statusOptions = `
                                    <li>
                                        <a class="dropdown-item items update-statuspro d-flex align-items-center" href="#" data-id="${row.tranid}" data-statuspro="1">
                                          <i class="fas fa-handshake text-success me-2"></i> Deal
                                         </a>
                                    </li>
                                `;
                            } else if (data == 1 && row.status == 1) {
                                statusOptions = `
                                    <li>
                                        <a class="dropdown-item items update-statuspro d-flex align-items-center" href="#" data-id="${row.tranid}" data-statuspro="5">
                                            <i class="fas fa-spinner text-info me-2"></i> On Progress                                   
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item items update-statuspro d-flex align-items-center" href="#" data-id="${row.tranid}" data-statuspro="0">
                                            <i class="fas fa-clock-rotate-left text-primary me-2"></i> Lead                                  
                                        </a>
                                    </li>
                                `;
                            } else if (data == 5 && row.status == 1) {
                                statusOptions = `
                                    <li>
                                        <a class="dropdown-item items update-statuspro d-flex align-items-center" href="#" data-id="${row.tranid}" data-statuspro="10">
                                            <i class="fas fa-comments text-warning me-2"></i> Follow Up
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item items update-statuspro d-flex align-items-center" href="#" data-id="${row.tranid}" data-statuspro="1">
                                            <i class="fas fa-handshake text-success me-2"></i> Deal
                                         </a>
                                    </li>
                                     
                                `;
                            } else if (data == 10 && row.status == 1) {
                                let finishOption = '';

                                if (isScanOut) {
                                    finishOption = `
                                        <li>
                                            <a class="dropdown-item items update-statuspro d-flex align-items-center" href="#" data-id="${row.tranid}" data-statuspro="15">
                                                <i class="fas fa-check-circle text-dark me-2"></i> Finish
                                            </a>
                                        </li>`;
                                }

                                statusOptions = `
                                    ${finishOption}
                                `;
                            }
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
                    }
                },
                <?php } ?>
        {
                data: "tranno",
                className: "text-start",
                render: function (data, type, row) {
                    return `
                    <a class="dropdown-item text-hover-success detail-tran" data-bs-toggle="modal" data-bs-target="#modal_form_tran"
                     data-id="${row.tranid}" style="cursor: pointer;">
                        ${data}
                    </a>`;
                }
            },
            {
                data: "contact_name",
                className: "text-start",
                defaultContent: "-",
                render: function (data, type, row) {

                    return `
                        <div class="d-flex flex-column align-items-left">
                            <small class="text text-start text-gray-800">
                            <i class="fa-solid fa-building me-2 text-gray-800"></i>
                            ${row.jobcompany || '-'}</small>
                            <small class="text text-start fw-bold">
                                <i class="fa-solid fa-user me-2 text-gray-800"></i>
                                ${row.piccustomer || '-'}
                            </small>
                            <small class="text text-start fw-bold">
                                <i class="fa-solid fa-phone me-2 text-gray-800"></i>
                                ${row.piccustomer_telp || '-'}
                            </small>
                        </div>
                        `;
                }
            },
            {
                data: "eventname",
                className: "text-start",

            },
            {
                data: "setupdate",
                className: "text-start",
                render: function (data) {
                    if (!data) return '-';
                    const date = new Date(data);
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();
                    return `${day}-${month}-${year}`;
                }
            },
            {
                data: "trandate",
                className: "text-start",
                render: function (data) {
                    if (!data) return '-';
                    const date = new Date(data);
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();
                    return `${day}-${month}-${year}`;
                }
            },
            {
                data: "tranduedate",
                className: "text-start",
                render: function (data) {
                    if (!data) return '-';
                    const date = new Date(data);
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();
                    return `${day}-${month}-${year}`;
                }
            },
                <?php if ("$module/$type" == "sales/order") { ?>
                                            {
                    data: "statuspaid",
                    className: "text-center",
                    render: function (data, type, row) {
                        let totalPaidValue = parseFloat(row.total_invoice) || 0;
                        let grandTotal = parseFloat(row.grandtotal) || 0;

                        let sisaTagihan = grandTotal - totalPaidValue;
                        // console.log( "Total Paid:", totalPaidValue, "Sisa Tagihan:", sisaTagihan, "Grand Total:", grandTotal);

                        let badgeClass = 'bg-secondary';
                        let badgeText = 'Unknown';

                        if (grandTotal === 0) {
                            badgeClass = 'bg-light-info text-info';
                            badgeText = '<?= Yii::$app->lang->t('tran', 'pricenotset') ?>';
                        } else if (grandTotal === sisaTagihan && grandTotal >= 0) {
                            badgeClass = 'bg-danger text-white';
                            badgeText = '<?= Yii::$app->lang->t('cashbackend', 'cashbackend12') ?>'; //unpaid
                        } else if (sisaTagihan > 0) {
                            badgeClass = 'bg-warning text-white';
                            badgeText = '<?= Yii::$app->lang->t('tran', 'partialpaid') ?>';
                        } else if (sisaTagihan <= 0 && grandTotal > 0) {
                            badgeClass = 'bg-success text-white';
                            badgeText = '<?= Yii::$app->lang->t('dashboard', 'paid') ?>'; //paid
                        }

                        return `
                            <div class="d-flex flex-column align-items-center mx-auto my-auto w-100">
                                <span class="badge ${badgeClass} p-4">${badgeText}</span>
                            </div>`;

                    }
                },
                {
                    data: "sisa_invoice",
                    className: "text-end",
                    render: function (data) {
                        let val = parseFloat(data) || 0;
                        return `<span class="text text-end">${val.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 })}</span>`;
                    }
                },
                {
                    data: "grandtotal",
                    className: "text-end",
                    render: function (data) {
                        return data ?
                            `<span class="text text-end">${parseFloat(data).toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 })}</span>` :
                            "-";
                    }
                },
                <?php } ?>
            {
                data: "name",
                className: "text-start",
                render: function (data) {
                    return `<span class="text text-start" > ${data || '-'}</span > `;
                }
            }
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).css('cursor', 'pointer');

                let rowModule = '<?= $module ?>'; // Default module
                let rowType = '<?= $type ?>'; // Default type

                if (data.trantype && data.trantype.includes('/')) {
                    const parts = data.trantype.split('/');
                    rowModule = parts[0];
                    rowType = parts[1];
                }

                $(row).attr('data-url', `<?= Url::to(['detail']) ?> ? id = ${data.tranid} & module=${rowModule} & type=${rowType} `);
            },
            initComplete: function () {
                addNestedDropdown();
                const url = $(this).attr('data-url');
                // console.log(url);
                if (url) {
                    window.location.href = url;
                }
                $('#datatable tbody').on('click', 'tr', function (e) {
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

        function addNestedDropdown() {
            $(document).on('click', '.neo-orba', function (e) {
                e.stopPropagation();

                var button = $(this);
                var parentId = button.data('id');
                var row = button.closest('tr');
                var expandRow = row.next('tr.expanded-row');

                if (expandRow.length && expandRow.is(':visible')) {
                    expandRow.remove();
                    button.html('<i class="fa-solid fa-caret-right"></i>');
                    return;
                }

                $('tr.expanded-row').remove();
                $('button.neo-orba').html('<i class="fa-solid fa-caret-right"></i>');

                if (row.hasClass('child-row')) {
                    if (expandRow.length && expandRow.is(':visible')) {
                        expandRow.remove();
                        return;
                    }
                    button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                }

                if (expandRow.length && expandRow.is(':visible')) {
                    expandRow.find('tr.expanded-row').remove();
                    expandRow.remove();
                    return;
                }

                $('tr.expanded-row').remove();

                button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

                var subTableHTML = `
                <tr class="expanded-row">
                    <td colspan="10">
                        <div class="m-3 row">
                            <div class="col-md-6 ps-3 border-end border-gray-300 pe-3">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 sub-datatable"
                                    id="sub-table-${parentId}">
                                    <thead>
                                        <tr class="text-gray-500 bg-gray-100 border-bottom border-gray-200 fs-7 text-uppercase">
                                            <th class="text-start">Product</th>
                                            <th class="text-center">Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-800 fw-semibold text-center"></tbody>
                                </table>
                            </div>
                    </td>
                </tr>`;

                row.after(subTableHTML);
                button.html('<i class="fa-solid fa-caret-down"></i>');

                var subTableReturn = $(`#sub-table-${parentId}`).DataTable({
                    processing: true,
                    serverSide: true,
                    lengthMenu: [5, 10, 25, 50],
                    pageLength: 5,
                    ajax: {
                        url: `/tran/listdetail`,
                        type: 'GET',
                        data: function (d) {
                            d.id = parentId;
                            d.module = "sales";
                            d.type = "order"
                        }
                    },
                    columns: [
                        {
                            data: "productname",
                            className: "text-start",
                            render: function (data, type, row) {
                                return `
                                    <div class="d-flex flex-column align-items-left">
                                        <span class="text text-start fw-bold">${row.productname}</span>
                                        <small class="text text-start text-gray-800">${row.description}</small>
                                    </div>
                                `;
                            }
                        },
                        {
                            data: "amount",
                            className: "text-center",
                            render: function (data) {
                                return `<span class="text text-center">${data || '-'}</span>`;
                            }
                        },


                    ],
                    order: [],
                    columnDefs: [{
                        orderable: false,
                        targets: '_all'
                    }]
                    // order: [
                    //     [2, 'asc']
                    // ]
                });
            });
        }

        $(document).on("click", ".update-status", function (e) {
            e.preventDefault();

            let tranId = $(this).data("id");
            let newStatus = $(this).data("status");
            let button = $(this).closest(".dropdown").find("button");

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

            if (statusInfo[newStatus]) {
                const statusText = statusInfo[newStatus].text;
                const statusBg = statusInfo[newStatus].color;
                const statusIcon = statusInfo[newStatus].icon;

                button.attr('class', `btn btn-${statusBg} btn-sm px-3 py-2 d-flex align-items-center justify-content-center mx-auto`);
                button.html(`
                    <i class="fas ${statusIcon} me-2"></i>
                    <span>${statusText}</span>
                    <i class="fas fa-chevron-down ms-2 opacity-50" style="font-size: 0.8em;"></i>
                `);
            }

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
                success: function (response) {
                    if (response.success) {
                        let alertType;
                        console.log(newStatus);
                        if (newStatus == 1) {
                            alertType = "success"; // Default
                        } else {
                            alertType = 'warning'
                        }

                        showBootstrapAlert("<?= Yii::$app->lang->t('extra', 'extra63') . " " ?>" + response.statusText, alertType);
                        $("#datatable").DataTable().ajax.reload(null, false);
                        $("#datatable-po").DataTable().ajax.reload(null, false);
                    } else {
                        showBootstrapAlert("<?= Yii::$app->lang->t('extra', 'extra64') . " " ?>" + response.message, "danger");

                        $("#datatable").DataTable().ajax.reload(null, false);
                        $("#datatable-po").DataTable().ajax.reload(null, false);
                    }
                },
                error: function (xhr) {
                    let errorMsg = "Terjadi kesalahan saat memperbarui status.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg += " " + xhr.responseJSON.message;
                    }

                    showBootstrapAlert(errorMsg, "danger");
                    $("#datatable").DataTable().ajax.reload(null, false);
                }
            });
        });

        $(document).on("click", ".update-statuspro", function (e) {
            e.preventDefault();

            let tranId = $(this).data("id");
            let newStatus = $(this).data("statuspro");
            let button = $(this).closest(".dropdown").find("button");

            let statusInfo = {
                0: {
                    text: "Lead",
                    color: "primary",
                    icon: "fa-clock-rotate-left"
                },
                1: {
                    text: "Deal",
                    color: "success",
                    icon: "fa-handshake"
                },
                5: {
                    text: "On Progress",
                    color: "info",
                    icon: "fa-spinner"
                },
                10: {
                    text: "Follow Up",
                    color: "warning",
                    icon: "fa-comments"
                },
                15: {
                    text: "Finish",
                    color: "dark",
                    icon: "fa-check-double"
                }
            };

            if (statusInfo[newStatus]) {
                const statusText = statusInfo[newStatus].text;
                const statusBg = statusInfo[newStatus].color;
                const statusIcon = statusInfo[newStatus].icon;

                button.attr('class', `btn btn-${statusBg} btn-sm px-3 py-2 d-flex align-items-center justify-content-center mx-auto`);
                button.html(`
                    <i class="fas ${statusIcon} me-2"></i>
                    <span>${statusText}</span>
                    <i class="fas fa-chevron-down ms-2 opacity-50" style="font-size: 0.8em;"></i>
                `);
            }

            $.ajax({
                url: "<?= Url::to(['/tran/updatestatuspro']) ?>",
                type: "POST",
                data: {
                    id: tranId,
                    statuspro: newStatus,
                    _csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
                },
                headers: {
                    "X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
                },
                success: function (response) {
                    if (response.success) {
                        let alertType;
                        console.log(newStatus);
                        if (newStatus == 1) {
                            alertType = "success"; // Default
                        } else {
                            alertType = 'warning'
                        }

                        showBootstrapAlert("<?= Yii::$app->lang->t('extra', 'extra63') . " " ?>" + response.statusText, alertType);

                        $("#datatable").DataTable().ajax.reload(null, false);
                    } else {
                        showBootstrapAlert("<?= Yii::$app->lang->t('extra', 'extra64') . " " ?>" + response.message, "danger");

                        $("#datatable").DataTable().ajax.reload(null, false);
                    }
                },
                error: function (xhr) {
                    let errorMsg = "Terjadi kesalahan saat memperbarui status.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg += " " + xhr.responseJSON.message;
                    }

                    showBootstrapAlert(errorMsg, "danger");
                    $("#datatable").DataTable().ajax.reload(null, false);
                }
            });
        });

        function formatRange(start, end) {
            return start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY');
        }

        function reloadWithRange(start, end) {
            $('#datefilter').val(formatRange(start, end));
            $('#datefilter').data('daterangepicker').setStartDate(start);
            $('#datefilter').data('daterangepicker').setEndDate(end);
            $("#datatable").DataTable().ajax.reload();
        }

        $('#btnThisWeek').click(function () {
            const start = moment().startOf('week');
            const end = moment().endOf('week');
            reloadWithRange(start, end);
        });

        $('#btnLastWeek').click(function () {
            const start = moment().subtract(1, 'week').startOf('week');
            const end = moment().subtract(1, 'week').endOf('week');
            reloadWithRange(start, end);
        });

        $('#btnThisMonth').click(function () {
            const start = moment().startOf('month');
            const end = moment().endOf('month');
            reloadWithRange(start, end);
        });

        $('#btnLastMonth').click(function () {
            const start = moment().subtract(1, 'month').startOf('month');
            const end = moment().subtract(1, 'month').endOf('month');
            reloadWithRange(start, end);
        });

        $('#btnThisYear').click(function () {
            const start = moment().startOf('year');
            const end = moment().endOf('year');
            reloadWithRange(start, end);
        });

        $('#btnLastYear').click(function () {
            const start = moment().subtract(1, 'year').startOf('year');
            const end = moment().subtract(1, 'year').endOf('year');
            reloadWithRange(start, end);
        });

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

        $('#datefilter').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
            $("#datatable").DataTable().ajax.reload();
        });

        $('#datefilter').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            $("#datatable").DataTable().ajax.reload();
        });

        $(document).on('click', '.daterangepicker', function (e) {
            e.stopPropagation();
        });

        $('[data-kt-menu-id="filter-menu"]').on('hidden.bs.dropdown', function () {
            if ($('#datefilter').data('daterangepicker')) {
                $('#datefilter').data('daterangepicker').hide();
            }
        });

        $("#search").submit(function (e) {
            e.preventDefault();
            $("#datatable").DataTable().ajax.reload();
        });

        $("#filterForm").submit(function (e) {
            e.preventDefault();
            $("#datatable").DataTable().ajax.reload();
        });

        $(document).on('click', 'a[data-bs-toggle="modal"]', function (e) {
            e.preventDefault();
            const url = $(this).attr('href');
            const target = $(this).attr('data-bs-target');

            if (!url) {
                return;
            }

            if (target === '#modal_form_tran') {
                $('#modal-content-tran').html('<div class="text-center p-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading...</p></div>');

                const isEdit = url.includes('update') || url.includes('edit');

                const module = new URLSearchParams(url.split('?')[1]).get('module') || '<?= $module ?>';
                const type = new URLSearchParams(url.split('?')[1]).get('type') || '<?= $type ?>';
                const add = <?= json_encode(Yii::$app->lang->t('cta_add', 'cta_add')) ?>;
                const title = <?= json_encode($title) ?>;
                if (isEdit) {
                    $('.modal-title').text(`Edit ${title} `);
                } else {
                    $('.modal-title').text(`${add} ${title} `);
                }

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        penomoran: "<?= $formatnumber ?>"
                    },
                    success: function (data) {
                        let parser = new DOMParser();
                        let doc = parser.parseFromString(data, 'text/html');

                        $('#modal-content-tran').html(doc.body.innerHTML);

                        $(target).find('.modal-dialog').css('max-width', '95%');
                    },
                    error: function () {
                        $('#modal-content-tran').html('<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>Error loading form.</div>');
                    }
                });
            } else if (target === '#modal_list_track') {
                $('#modal-content-track').html('<div class="text-center p-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading...</p></div>');

                const isEdit = url.includes('update') || url.includes('edit');

                const module = new URLSearchParams(url.split('?')[1]).get('module') || '<?= $module ?>';
                const type = new URLSearchParams(url.split('?')[1]).get('type') || '<?= $type ?>';
                const add = <?= json_encode(Yii::$app->lang->t('cta_add', 'cta_add')) ?>;
                const title = <?= json_encode($title) ?>;
                if (isEdit) {
                    $('.modal-title').text(`Edit `);
                } else {
                    $('.modal-title').text(`${add} `);
                }

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        penomoran: "<?= $formatnumber ?>"
                    },
                    success: function (data) {
                        let parser = new DOMParser();
                        let doc = parser.parseFromString(data, 'text/html');

                        $('#modal-content-track').html(doc.body.innerHTML);

                    },
                    error: function () {
                        $('#modal-content-track').html('<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>Error loading form.</div>');
                    }
                });
            }

        });

        var deletemessage1 = "<?= Yii::$app->lang->t('extra', 'extra44') ?>";
        var deletemessage2 = "<?= Yii::$app->lang->t('extra', 'extra45') ?>";
        var deletemessage3 = "<?= Yii::$app->lang->t('extra', 'extra46') ?>";
        var deletemessage3koma1 = "<?= Yii::$app->lang->t('extra', 'extra46.1') ?>";
        var deletemessage4 = "<?= Yii::$app->lang->t('back_home', 'chat34') ?>";
        var deletemessage5 = "<?= Yii::$app->lang->t('back_home', 'chat53') ?>";

        $(document).on('click', '.detail-tran', function () {
            var id = $(this).data('id')
            $.ajax({
                url: '<?= Url::to(['updatepro']) ?>?id=' + id,
                success: function (data) {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(data, 'text/html');
                    $('#modal-content-tran').html(doc.body.innerHTML);
                    $('#modal-content-tran').find('input, select, textarea, button').prop('disabled', true);
                    $('#modal_form_tran').modal('show');
                },
                error: function () {
                    $('#modal-content-tran').html('<p>Error loading form.</p>');
                }
            });
        });

        $(document).on('click', '.delete-tran', function () {
            var tranId = $(this).data('id');

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
                        success: function (response) {
                            $('#datatable').DataTable().ajax.reload();

                            Swal.fire({
                                title: "<?= Yii::$app->lang->t('extra', 'extra62') ?>",
                                text: "<?= Yii::$app->lang->t('extra', 'extra59') ?>",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function (xhr) {
                            console.error('Error:', xhr.responseText);

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

        $(document).on('click', '.add-new-ctc-btn button', function () {
            Swal.fire({
                title: 'Loading...',
                text: 'Memuat data',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            var type = $(this).data('type');
            var position = $(this).data('position');

            $.get('/contact/create', {
                contacttype: type,
                position: position,
                type: 'master'
            }, function (html) {
                Swal.close();
                $('.addContactModal .modal-body').html(html);
                $('.addContactModal').modal('show');
            });

        });

        $(document).on('click', '.add-new-prd-btn button', function () {
            Swal.fire({
                title: 'Loading...',
                text: 'Memuat data',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            $.get('/product/create', { origin: 'select2' }, function (html) {
                Swal.close();
                $('.addContactModal .modal-body').html(html);
                $('.addContactModal').modal('show');
            });
        });

        $(document).on('click', '.btn-product', function () {
            let tranid = $(this).data('tranid');

            loadReferenceDetail(tranid);
        });

        $(document).on('click', '.add-po button', function () {
            var refid = $(this).attr('data-refid');
            // console.log('refid:', refid);

            if (!refid || refid === '') {
                Swal.fire({
                    icon: "warning",
                    title: "Warning",
                    text: "Project tidak ditemukan atau belum disimpan."
                });
                return false;
            }

            Swal.fire({
                title: 'Loading...',
                text: 'Memuat data',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.get('/tran/createpo', {
                refid: refid,
                penomoran: "PO"
            }, function (html) {
                Swal.close();
                $('#modal_form_po .modal-body').html(html);

                var add = <?= json_encode(Yii::$app->lang->t('cta_add', 'cta_add')) ?>;
                $('#modal_form_po .modal-title').text(`${add} PO`);

                $('#modal_form_po').attr('data-current-tranid', refid);
                $('#modal_form_po').modal('show');

                if (typeof setFormPo === 'function') {
                    setFormPo('#modal_form_po');
                }
            });

        });

        $(document).on('click', '.detail-po', function () {
            var $this = $(this);
            var targetUrl = '/tran/updatepo';
            var tranId = $this.attr('data-id');

            if (!tranId || tranId === "undefined") {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'ID Transaksi tidak valid.'
                });
                return;
            }

            Swal.fire({
                title: 'Loading...',
                text: 'Memuat detail data',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.get(targetUrl, { id: tranId, penomoran: "PO" }, function (html) {
                Swal.close();

                $('#modal_form_po .modal-body').html(html);
                $('#modal_form_po').attr('data-current-tranid', tranId);
                $('#modal_form_po .modal-title').text("Detail PO");

                $('#modal_form_po .modal-body').find('input, select, textarea, button').prop('disabled', true);

                $('#modal_form_po').modal('show');

                if (typeof setFormPo === 'function') {
                    setFormPo('#modal_form_po');
                    $('#modal_form_po .modal-body').find('input, select, textarea, button').prop('disabled', true);
                }
            }).fail(function () {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat detail data PO.' });
            });
        });

        $(document).on('click', '.btn-edit-po', function () {
            var $this = $(this);
            var targetUrl = $this.attr('data-url') || '/tran/updatepo';

            Swal.fire({
                title: 'Loading...',
                text: 'Memuat data edit',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            var tranId = $this.attr('data-tranid');

            if (!tranId || tranId === "undefined") {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'ID Transaksi untuk diedit tidak valid.'
                });
                return;
            }

            $.get(targetUrl, { id: tranId, penomoran: "PO" }, function (html) {
                Swal.close();
                $('#modal_form_po .modal-body').html(html);
                $('#modal_form_po').attr('data-current-tranid', tranId);
                $('#modal_form_po .modal-title').text("Edit PO");
                $('#modal_form_po').modal('show');

                if (typeof setFormPo === 'function') {
                    setFormPo('#modal_form_po');
                }
            }).fail(function () {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat form Edit PO.' });
            });
        });

        $(document).on('click', '.delete-po', function () {
            var tranId = $(this).data('id');

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
                        success: function (response) {
                            if ($('#datatable-po').length) {
                                $('#datatable-po').DataTable().ajax.reload();
                            } else {
                                $('#datatable').DataTable().ajax.reload();
                            }

                            Swal.fire({
                                title: "<?= Yii::$app->lang->t('extra', 'extra62') ?>",
                                text: "<?= Yii::$app->lang->t('extra', 'extra59') ?>",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function (xhr) {
                            console.error('Error:', xhr.responseText);

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

        $(document).on('click', '.wa-send', function (e) {
            e.preventDefault();
            const tranid = $(this).data('tranid');

            if (!tranid) {
                alert('tranid is missing. Data is broken upstream.');
                return;
            }

            $.ajax({
                url: '<?= Url::to(['/tran/getwhatsappnumber']) ?>',
                type: 'POST',
                data: {
                    tranid: tranid
                },
                success: function (response) {
                    const waUrl = `https://wa.me/?text=${encodeURIComponent(response.message)}`;
                    window.open(waUrl, '_blank');
                },
                error: function () {
                    showBootstrapAlert("<?= Yii::$app->lang->t('extra', 'extra65') ?>", "danger");
                }
            })
        });
    });

</script>