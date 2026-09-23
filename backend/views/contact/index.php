<?php

use yii\helpers\Html;
use yii\helpers\Url;

$typeParam = Yii::$app->request->get('contacttype') ?? Yii::$app->request->get('contact_typeid');

$mapType = [
    0 => 'Vendor',
    1 => 'Customer',
    2 => 'Supplier',
    3 => 'Employee',
    4 => 'Payroll',
];

$typeName = $mapType[$typeParam] ?? strtolower(trim((string) $typeParam));

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

<div class="card">
    <div class="card-header border-0 pt-6 sticky-top bg-white shadow-sm" style="top: 70px; z-index: 999;">
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
                            placeholder="<?= Yii::$app->lang->t('extra', 'extra02') ?>">

                        <span class="position-absolute top-50 end-0 translate-middle-y me-3 d-none" id="clear-search">
                            <i class="ki-duotone ki-cross fs-2 text-gray-500 cursor-pointer" style="opacity: 0.5;"></i>
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-toolbar flex-shrink-0">
            <div class="d-flex gap-2 flex-wrap">
                <?php if ($typeName == 'employee') { ?>
                    <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                        data-kt-menu-placement="left-start">
                        <i class="ki-duotone ki-filter fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>Filter
                    </button>

                    <div class="menu menu-sub menu-sub-dropdown w-sm-500px w-md-600px" data-kt-menu="true"
                        data-kt-menu-id="filter-menu-contact">
                        <div class="px-7 py-5">
                            <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                        </div>
                        <div class="separator border-gray-200"></div>
                        <div class="px-7 py-5">
                            <form id="filterForm">
                                <div class="row">
                                    <!-- Filter Karyawan -->
                                    <div class="col-md-12 mb-5">
                                        <label class="fw-semibold fs-6 mb-2">Karyawan</label>
                                        <select id="filter-employee" class="form-select" data-control="select2">
                                            <option value="">Semua Karyawan</option>
                                        </select>
                                    </div>
                                    <!-- Filter Order Status -->
                                    <div class="col-md-12 mb-5">
                                        <label class="fw-semibold fs-6 mb-2">Status Order</label>
                                        <select id="filter-order-status" class="form-select">
                                            <option value="">Semua Status</option>
                                            <option value="has_order">Ada Order</option>
                                            <option value="no_order">Tidak Ada Order</option>
                                        </select>
                                    </div>
                                    <!-- Filter Jabatan -->
                                    <div class="col-md-12 mb-5">
                                        <label class="fw-semibold fs-6 mb-2">Jabatan</label>
                                        <select id="filter-position" class="form-select" data-control="select2">
                                            <option value="">Pilih Jabatan</option>
                                        </select>
                                    </div>
                                    <!-- Filter Divisi -->
                                    <div class="col-md-12 mb-5">
                                        <label class="fw-semibold fs-6 mb-2">Divisi</label>
                                        <select id="filter-division" class="form-select" data-control="select2">
                                            <option value="">Pilih Divisi</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group text-end mt-5">
                                    <button type="button" class="btn btn-light me-2" id="btn-reset-filter">Reset</button>
                                    <button type="submit" id="btn-apply-filter" class="btn btn-lg btn-primary">
                                        <i class="fa-sharp fa-solid fa-filter"></i> Filter
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($access['tambah']) { ?>
                    <a href="<?= $contacttype === 'customer' ? Url::to(['contact/create', 'contacttype' => strtolower($typeName)]) : Url::to(['contact/createem', 'contacttype' => strtolower($typeName)]) ?>"
                        class="btn btn-primary add" id="add-button">
                        <i class="ki-duotone ki-plus fs-2"></i>
                        <?= Yii::$app->lang->t('cta_add', 'cta_add') ?>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="card-body pt-5">
        <div id="liveAlertPlaceholder"></div>
        <div class="btn-group mb-3" id="mass-action-buttons" style="display: none;">
            <button type="button" class="btn btn-danger" id="btn-delete-mass">
                <i class="fas fa-trash me-2"></i><?= Yii::$app->lang->t('back_home', 'chat53') ?>
            </button>
        </div>

        <table class="table align-middle table-row-dashed fs-6 gy-5" id="datatable">
            <thead>
                <tr class="text-start bg-gray-100 fs-6 text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                    <th class="d-none"></th>
                    <th class="text-center min-w-50px px-5">
                        <div class="form-check form-check-custom form-check-solid form-check-sm">
                            <input type="checkbox" id="select-all" class="form-check-input m-0">
                        </div>
                    </th>
                    <th class="text-start min-w-100px px-5"><?= Yii::$app->lang->t('cashbackend', 'cashbackend1') ?>
                    </th>
                    <th class="text-start min-w-125px px-5"><?= Yii::$app->lang->t('contact', 'contact_no') ?></th>
                    <?php if (in_array($typeName, ['employee', 'payroll'])) { ?>
                        <th class="text-start min-w-150px px-5"><?= Yii::$app->lang->t('contact', 'contact_name') ?></th>
                        <!-- <th class="text-start min-w-150px px-5"><?= Yii::$app->lang->t('extra', 'extra27') ?></th> -->
                        <th class="text-start min-w-150px px-5"><?= Yii::$app->lang->t('contact', 'contact_phone1') ?></th>
                        <th class="text-start min-w-130px px-5"><?= Yii::$app->lang->t('contact', 'contact_email1') ?></th>
                        <th class="text-start min-w-100px px-5"><?= Yii::$app->lang->t('contact', 'position') ?></th>
                        <!-- <th class="text-start min-w-100px px-5"><?= Yii::$app->lang->t('contact', 'level') ?></th> -->
                        <!-- <th class="text-start min-w-100px px-5"><?= Yii::$app->lang->t('contact', 'devision') ?></th> -->
                        <th class="text-start min-w-200px px-5"><?= Yii::$app->lang->t('status', 'label1') ?></th>
                    <?php } else { ?>

                        <th class="text-start min-w-150px px-5">
                            <?= Yii::$app->lang->t('contact', 'contact_name') ?>
                        </th>
                        <th class="text-start min-w-150px px-5">
                            Package
                        </th>
                        <th class="text-start min-w-150px px-5">
                            <?= Yii::$app->lang->t('contact', 'contact_phone1') ?>
                        </th>
                        <th class="text-start min-w-130px px-5">
                            <?= Yii::$app->lang->t('contact', 'contact_email1') ?>
                        </th>

                    <?php } ?>
                    <th class="text-start min-w-120px px-5"><?= Yii::$app->lang->t('contact', 'status') ?></th>
                    <!-- <?php if ($typeName == 'employee') { ?>
                        <th class="text-start min-w-150px px-5">Status Register face</th>
                    <?php } ?> -->
                </tr>
            </thead>
            <tbody class="text-gray-800 fw-semibold text-center"></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="filterModalLabel">Opsi Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <div class="mb-4">
                    <label class="form-label fw-semibold text-gray-700 mb-3">Jabatan</label>
                    <select id="filter-position-modal" class="form-select form-select-lg">
                        <option value="">Pilih Jabatan</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold text-gray-700 mb-3">Divisi</label>
                    <select id="filter-division-modal" class="form-select form-select-lg">
                        <option value="">Pilih Divisi</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" id="btn-reset-filter-modal">Reset</button>
                <button type="button" class="btn btn-primary" id="btn-apply-filter-modal">Apply</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_form_contact" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-lg-down modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title-text">
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-content" class="nopadding">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addMasterModal" tabindex="-1" role="dialog" aria-labelledby="addMasterModalLabel"
    aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content"></div>
    </div>
</div>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let currentFilters = {
        employee: '',
        orderStatus: '',
        position: '',
        division: ''
    };

    function loadFilterOptions() {
        $('#filter-employee').select2({
            ajax: {
                url: '<?= Url::to(['/contact/list']) ?>',
                type: 'GET',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        contacttype: 'employee',
                        search: params.term || '',
                        for: 'select2',
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.data.map(function (item) {
                            return {
                                id: item.contact_id,
                                text: item.contact_no + ' - ' + item.contact_name
                            };
                        }),
                        pagination: {
                            more: data.pagination && data.pagination.more
                        }
                    };
                },
                cache: true
            },
            placeholder: 'Pilih Karyawan',
            allowClear: true,
            minimumInputLength: 0,
            dropdownParent: $('#filterForm')
        });

        $('#filter-position').select2({
            ajax: {
                url: '<?= Url::to(['/enum/list']) ?>',
                type: 'GET',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        enumtype: 'position',
                        search: params.term || '',
                        for: 'select2',
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.data.map(function (item) {
                            return {
                                id: item.enumid,
                                text: item.enumtext_id
                            };
                        }),
                        pagination: {
                            more: data.pagination && data.pagination.more
                        }
                    };
                },
                cache: true
            },
            placeholder: 'Pilih Jabatan',
            allowClear: true,
            minimumInputLength: 0,
            dropdownParent: $('#filterForm')
        });

        $('#filter-division').select2({
            ajax: {
                url: '<?= Url::to(['/enum/list']) ?>',
                type: 'GET',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        enumtype: 'division',
                        search: params.term || '',
                        for: 'select2',
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.data.map(function (item) {
                            return {
                                id: item.enumid,
                                text: item.enumtext_id
                            };
                        }),
                        pagination: {
                            more: data.pagination && data.pagination.more
                        }
                    };
                },
                cache: true
            },
            placeholder: 'Pilih Divisi',
            allowClear: true,
            minimumInputLength: 0,
            dropdownParent: $('#filterForm')
        });

        if (currentFilters.employee) {
            $.ajax({
                url: '<?= Url::to(['/contact/list']) ?>',
                type: 'GET',
                data: { contact_id: currentFilters.employee, contacttype: 'employee', for: 'select2' },
                success: function (response) {
                    if (response.data && response.data.length > 0) {
                        var option = new Option(
                            response.data[0].contact_no + ' - ' + response.data[0].contact_name,
                            response.data[0].contact_id,
                            true, true
                        );
                        $('#filter-employee').append(option).trigger('change');
                    }
                }
            });
        }

        if (currentFilters.position) {
            $.ajax({
                url: '<?= Url::to(['/enum/list']) ?>',
                type: 'GET',
                data: { enumtype: 'position', id: currentFilters.position, for: 'select2' },
                success: function (response) {
                    if (response.data && response.data.length > 0) {
                        var option = new Option(response.data[0].enumtext_id, response.data[0].enumid, true, true);
                        $('#filter-position').append(option).trigger('change');
                    }
                }
            });
        }

        if (currentFilters.division) {
            $.ajax({
                url: '<?= Url::to(['/enum/list']) ?>',
                type: 'GET',
                data: { enumtype: 'division', id: currentFilters.division, for: 'select2' },
                success: function (response) {
                    if (response.data && response.data.length > 0) {
                        var option = new Option(response.data[0].enumtext_id, response.data[0].enumid, true, true);
                        $('#filter-division').append(option).trigger('change');
                    }
                }
            });
        }

        if (currentFilters.orderStatus) {
            $('#filter-order-status').val(currentFilters.orderStatus);
        }
    }

    function showBootstrapAlert(message, type) {
        let alertPlaceholder = document.getElementById("liveAlertPlaceholder");
        if (!alertPlaceholder) {
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

        setTimeout(function () {
            const alert = bootstrap.Alert.getOrCreateInstance(wrapper.querySelector('.alert'));
            if (alert) alert.close();
        }, 5000);
    }

    $(document).on("click", ".update-status", function (e) {
        e.preventDefault();

        let contactid = $(this).data("id");
        let newStatus = $(this).data("status");
        let button = $(this).closest(".dropdown").find("button");

        let statusInfo = {
            0: {
                text: "Close",
                color: "primary",
                icon: "fa-xmark-circle"
            },
            1: {
                text: "Open",
                color: "success",
                icon: "fa-check-circle"
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
            url: "<?= Url::to(['/contact/updatestatus']) ?>",
            type: "POST",
            data: {
                id: contactid,
                status: newStatus,
                _csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
            },
            headers: {
                "X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
            },
            success: function (response) {
                if (response.success) {
                    let alertType;
                    // console.log(newStatus);
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

    $(document).on('keyup input', 'input[name="search"]', function () {
        if ($("#datatable").length && $.fn.DataTable.isDataTable("#datatable")) {
            $("#datatable").DataTable().ajax.reload(null, false);
        }
    });

    function initData() {
        $("#datatable").DataTable({
            scrollX: false,
            autoWidth: false,
            fixedColumns: true,
            processing: true,
            serverSide: true,
            lengthMenu: [5, 15, 30, 50, 75, 100],
            pageLength: 5,
            language: {
                info: <?= json_encode(Yii::$app->lang->t('extra', 'extra12')) ?>,
                infoEmpty: <?= json_encode(Yii::$app->lang->t('extra', 'extra13')) ?>,
                emptyTable: `
                    <div style="text-align: center; padding: 20px 0;">
                        <img width='250px' src='https://cdni.iconscout.com/illustration/premium/thumb/employee-is-unable-to-find-sensitive-data-illustration-download-in-svg-png-gif-file-formats--no-found-misplaced-files-business-pack-illustrations-8062128.png'/>
                        <div style="font-weight: bold; font-size: 16px; margin-top: 8px;"><?= Yii::$app->lang->t('extra', 'extra11') ?></div>
                    </div>
                `,
                zeroRecords: `
                    <div style="text-align: center; padding: 20px 0;">
                        <img width='250px' src='https://cdni.iconscout.com/illustration/premium/thumb/employee-is-unable-to-find-sensitive-data-illustration-download-in-svg-png-gif-file-formats--no-found-misplaced-files-business-pack-illustrations-8062128.png'/>
                        <div style="font-weight: bold; font-size: 16px; margin-top: 8px;"><?= Yii::$app->lang->t('extra', 'extra11') ?></div>
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
                url: "/contact/list",
                data: function (d) {
                    const params = new URLSearchParams(window.location.search);
                    const search = $('input[name="search"]').val();
                    const contacttype = params.get('contacttype') || params.get('contact_typeid') || '<?= strtolower($typeName) ?>';

                    d.search = search;
                    d.contacttype = contacttype;
                    d.filter_employee = currentFilters.employee;
                    d.filter_order_status = currentFilters.orderStatus;
                    d.filter_position = currentFilters.position;
                    d.filter_division = currentFilters.division;

                    if (search) {
                        params.set('search', search);
                    } else {
                        params.delete('search');
                    }

                    const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
                    window.history.replaceState({}, '', newUrl);
                }
            },
            columns: [
                {
                    data: 'contact_id',
                    visible: false
                },
                {
                    data: null,
                    className: "text-center px-5",
                    orderable: false,
                    render: function (data, type, row) {
                        return `
                            <div class="form-check form-check-custom form-check-solid form-check-sm">
                                <input type="checkbox" class="select-checkbox form-check-input m-0" value="${row.contact_id}">
                            </div>`;
                    }
                },
                {
                    data: null,
                    className: "text-start px-5",
                    render: function (data, type, row) {
                        <?php
                        $btndetail = '<li><a href="/contact/detail?contacttype=' . $typeName . '&contact_id=${row.contact_id}" class="dropdown-item text-hover-info btn-light"><i class="fas fa-eye"></i> Detail </a></li>';
                        $btnubah = $access['ubah'] ? '<li><a href="/contact/update?contacttype=' . $typeName . '&contact_id=${row.contact_id}" class="dropdown-item text-hover-success btn-light edit" data-id="${row.contact_id}" style="cursor:pointer;"><i class="fas fa-edit"></i> Edit </a></li>' : '';
                        $btnhapus = $access['hapus'] ? '<li><a href="javascript:void(0);" class="dropdown-item text-hover-danger delete" data-id="${row.contact_id}" style="cursor: pointer;"><i class="fas fa-trash"></i> Delete </a></li>' : '';

                        $isCustomer = (strtolower($contacttype) === 'customer');
                        ?>

                        var showMemberCard = (row.contacttype && row.contacttype.toLowerCase() === 'customer') || <?= json_encode($isCustomer) ?>;

                        return `
                        <div class="dropdown text-left dropend">
                            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                <i class="fa-sharp fa-solid fa-list"></i>
                            </button>
                            <ul class="dropdown-menu px-2">
                                <?php echo $btnubah; ?>
                                <?php echo $btnhapus; ?>
                                ${showMemberCard ? `
                                <li>
                                    <a href="<?= \yii\helpers\Url::to(['contact/membercard']) ?>?contact_id=${row.contact_id}" target="_blank" class="dropdown-item text-hover-info btn-light">
                                        <i class="fa-solid fa-id-badge me-1"></i> Member Card 
                                    </a>
                                </li>
                                ` : ''}
                            </ul>
                        </div>`;
                    },
                },
                {
                    data: "contact_no",
                    className: 'text-start px-5',
                    render: function (data, type, row) {
                        if (!data) return "";
                        return `<a href="/contact/detail?contact_id=${row.contact_id}" class="dropdown-item text-hover-success">${data}</a>`;
                    }
                },

                <?php if (in_array($typeName, ['employee'])) { ?>
                            
                                        {
                        data: "contact_name",
                        className: 'text-start px-5',
                        render: function (data, type, row) {
                            return "<span>" + (data || '') + "</span>";
                        }
                    },
                    {
                        data: "contact_phone1",
                        className: 'text-start px-5',
                        render: function (data, type, row) {
                            return "<span>" + (data || '') + "</span>";
                        }
                    },
                    // {
                    //     data: "jobcompany",
                    //     className: 'text-start px-5',
                    //     render: function (data, type, row) {
                    //         return "<span>" + (data || '') + "</span>";
                    //     }
                    // },
                    {
                        data: "contact_email1",
                        className: 'text-start px-5',
                        render: function (data, type, row) {
                            return "<span>" + (data || '') + "</span>";
                        }
                    },
                    {
                        data: "position",
                        className: 'text-start px-5',
                        render: function (data, type, row) {
                            return "<span>" + (data || '') + "</span>";
                        }
                    },
                    // {
                    //     data: "level",
                    //     className: 'text-start px-5',
                    //     render: function (data, type, row) {
                    //         return "<span>" + (data || '') + "</span>";
                    //     }
                    // },
                    // {
                    //     data: "division",
                    //     className: 'text-start px-5',
                    //     render: function (data, type, row) {
                    //         return "<span>" + (data || '') + "</span>";
                    //     }
                    // },
                    {
                        data: "contract",
                        className: 'text-start px-5',
                        render: function (data, type, row) {
                            return "<span>" + (data || '') + "</span>";
                        }
                    },

                <?php } else { ?>
                                                //                                                             {
                                                //     data: "jobcompany",
                                                //     className: 'text-start px-5',
                                                //     render: function (data, type, row) {
                                                //         return "<span>" + (data || '') + "</span>";
                                                //     }
                                                // },
                                                {
                        data: "contact_name",
                        className: 'text-start px-5',
                        render: function (data, type, row) {
                            return "<span>" + (data || '') + "</span>";
                        }
                    },
                    {
                        data: "packagename",
                        className: 'text-start px-5',
                        render: function (data, type, row) {
                            return "<span>" + (data || '') + "</span>";
                        }
                    },
                    {
                        data: "contact_phone1",
                        className: 'text-start px-5',
                        render: function (data, type, row) {
                            return "<span>" + (data || '') + "</span>";
                        }
                    },
                    {
                        data: null,
                        className: 'text-start px-5',
                        render: function (data, type, row) {
                            var email = row.contact_email1 || '';
                            var category = row.businesscategory || '';

                            return '<div>' + email + '</div>' +
                                '<small class="text-muted">' + category + '</small>';
                        }
                    },
                <?php } ?>
                  {
                    data: 'contact_status',
                    className: 'text-center px-5',
                    render: function (data) {
                        return data == 1
                            ? `<span class="badge badge-success">Active</span>`
                            : `<span class="badge badge-danger">Inactive</span>`;
                    }
                },
                // <?php if ($typeName == 'employee') { ?>
                    //                                         {
                    //         data: "status_register",
                    //         className: "text-start px-5",
                    //         render: function (data, type, row) {
                    //             let statusInfo = {
                    //                 0: { text: "Close", color: "primary", icon: "fa-xmark-circle" },
                    //                 1: { text: "Open", color: "success", icon: "fa-check-circle" }
                    //             };

                    //             let statusText = "Unknown";
                    //             let statusBg = "secondary";
                    //             let statusIcon = "fa-question-circle";
                    //             let statusOptions = "";

                    //             if (statusInfo[data] !== undefined) {
                    //                 statusText = statusInfo[data].text;
                    //                 statusBg = statusInfo[data].color;
                    //                 statusIcon = statusInfo[data].icon;

                    //                 if (data == 0) {
                    //                     statusOptions = `
                //                     <li>
                //                         <a class="dropdown-item update-status d-flex align-items-center" href="#"
                //                            data-id="${row.contact_id}" data-status="1">
                //                             <i class="fas fa-check-circle text-success me-2"></i> Open
                //                         </a>
                //                     </li>`;
                    //                 } else if (data == 1) {
                    //                     statusOptions = `
                //                     <li>
                //                         <a class="dropdown-item update-status d-flex align-items-center" href="#"
                //                            data-id="${row.contact_id}" data-status="0">
                //                             <i class="fas fa-xmark-circle text-primary me-2"></i> Close
                //                         </a>
                //                     </li>`;
                    //                 }
                    //             }

                    //             return `
                //             <div class="dropdown dropend">
                //                 <button class="btn btn-${statusBg} btn-sm px-3 py-2 d-flex align-items-center justify-content-center mx-auto"
                //                         type="button" data-bs-toggle="dropdown" aria-expanded="false"
                //                         style="min-width: 110px; border-radius: 6px;">
                //                     <i class="fas ${statusIcon} me-2"></i>
                //                     <span>${statusText}</span>
                //                     <i class="fas fa-chevron-down ms-2 opacity-50" style="font-size: 0.8em;"></i>
                //                 </button>
                //                 <ul class="dropdown-menu py-2 shadow-sm">${statusOptions}</ul>
                //             </div>`;
                    //         }
                    //     },
                    // <?php } ?>

            ],
            drawCallback: function (settings) {
                addSubtableExpand();
            },

            initComplete: function () {
                addSubtableExpand();
            }
        });

        $("#select-all").on("click", function () {
            $("tbody .select-checkbox").prop("checked", this.checked);
            toggleMassActionButtons();
        });

        $("#datatable tbody").on("change", ".select-checkbox", function () {
            $("#select-all").prop(
                "checked",
                $(".select-checkbox").length === $(".select-checkbox:checked").length
            );
            toggleMassActionButtons();
        });
    }

    function toggleMassActionButtons() {
        if ($('.select-checkbox:checked').length > 0) {
            $('#mass-action-buttons').show();
        } else {
            $('#mass-action-buttons').hide();
        }
    }

    function addSubtableExpand() {
        $('.neo-orba').off('click').on('click', function (e) {
            e.stopPropagation();

            var button = $(this);
            var contact_id = button.data('id');
            var parentRow = button.closest('tr');
            var expandRow = parentRow.next('tr.expanded-row');

            if (expandRow.length && expandRow.is(':visible')) {
                expandRow.remove();
                button.html('<i class="fa-solid fa-caret-right"></i>');
                return;
            }

            $('tr.expanded-row').remove();
            $('button.neo-orba').html('<i class="fa-solid fa-caret-right"></i>');
            button.html('<span class="spinner-border spinner-border-sm" role="status"></span>');

            var totalColumns = $('#datatable thead th').length;

            var subTableHTML = `
                <tr class="expanded-row">
                    <td colspan="${totalColumns}">
                        <div class="m-3">
                           
                            <div class="d-flex align-items-center mb-3">
                                <h6 class="mb-0 text-gray-700">
                                    <i class="fas fa-history me-2"></i> Riwayat Crew di Project
                                </h6>
                                <span class="badge bg-primary ms-3 text-white" id="total-count-${contact_id}">Loading...</span>
                            </div>
                            <div id="subtable-container-${contact_id}" class="table-responsive">
                                <div class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Memuat data...</p>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>`;

            parentRow.after(subTableHTML);
            button.html('<i class="fa-solid fa-caret-down"></i>');

            // $.ajax({
            //     url: '<?= Url::to(['contact/photos']) ?>',
            //     type: 'GET',
            //     data: { contact_id: contact_id },
            //     dataType: 'json',
            //     success: function (response) {
            //         var photoContainer = $('#photo-container-' + contact_id);

            //         if (response.photos && response.photos.length > 0) {
            //             var html = '';
            //             $.each(response.photos, function (i, photo) {
            //                 html += `
            //                 <img src="${photo.url}"
            //                     alt="Foto Register ${i + 1}"
            //                     class="img-thumbnail"
            //                     style="width:100px; height:100px; object-fit:cover;">`;
            //                     });
            //             photoContainer.html(html);
            //         } else {
            //             photoContainer.html('<span class="text-muted">Tidak ada foto terdaftar.</span>');
            //         }
            //     },
            //     error: function () {
            //         $('#photo-container-' + contact_id).html(
            //             '<span class="text-danger">Gagal memuat foto.</span>'
            //         );
            //     }
            // });

            $.ajax({
                url: '<?= Url::to(['contact/crewhistory']) ?>',
                type: 'GET',
                data: { contact_id: contact_id },
                dataType: 'json',
                success: function (response) {
                    var container = $(`#subtable-container-${contact_id}`);
                    var totalBadge = $(`#total-count-${contact_id}`);

                    if (response.success && response.data && response.data.length > 0) {
                        totalBadge.text(response.total + ' events');

                        var tableHTML = `
                            <table class="table table-sm table-hover table-bordered align-middle">
                                <thead class="table-light bg-secondary">
                                    <tr>
                                        <th class="text-center" style="width: 100px;">Tgl Mulai</th>
                                        <th class="text-center" style="width: 100px;">Tgl Selesai</th>
                                        <th class="text-center" style="width: 120px;">No. Transaksi</th>
                                        <th>Nama Acara</th>
                                        <th>Lokasi</th>
                                        <th class="text-center" style="width: 100px;">Job</th>
                                        <th class="text-center" style="width: 100px;">Jabatan</th>
                                        <th class="text-end" style="width: 120px;">Fee</th>
                                        <th class="text-end" style="width: 120px;">Dinas Fee</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                        response.data.forEach(function (item) {
                            tableHTML += `
                                <tr>
                                    <td class="text-center">${item.startdate || '-'}</td>
                                    <td class="text-center">${item.enddate || '-'}</td>
                                    <td class="text-center"><span class="badge bg-info text-white">${item.tranno || '-'}</span></td>
                                    <td>${item.eventname || '-'}</td>
                                    <td>${item.locations || '-'}</td>
                                    <td class="text-center">${item.job_name || '-'}</td>
                                    <td class="text-center">${item.crew_type || '-'}</td>
                                    <td class="text-end fw-bold ${item.fee !== '-' ? 'text-success' : ''}">${item.fee}</td>
                                    <td class="text-end fw-bold ${item.dinasfee !== '-' ? 'text-primary' : ''}">${item.dinasfee}</td>
                                </tr>`;
                        });

                        tableHTML += `</tbody></table>`;
                        container.html(tableHTML);
                    } else {
                        totalBadge.text('0 events');
                        container.html(`
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-3x mb-3 text-gray-400"></i><br>
                                <h6 class="text-gray-600">Tidak ada riwayat crew di Sales Order</h6>
                                ${response.message ? `<small class="text-gray-500">${response.message}</small>` : ''}
                            </div>`);
                    }
                },
                error: function (xhr, status, error) {
                    var container = $(`#subtable-container-${contact_id}`);
                    var totalBadge = $(`#total-count-${contact_id}`);

                    totalBadge.text('Error');
                    container.html(`
                        <div class="text-center text-danger py-4">
                            <i class="fas fa-exclamation-circle fa-3x mb-3"></i><br>
                            <h6>Gagal memuat data</h6>
                            <small>${error}</small>
                            <div class="mt-3">
                                <button class="btn btn-sm btn-light-primary retry-load" data-id="${contact_id}">
                                    <i class="fas fa-sync-alt me-1"></i> Coba Lagi
                                </button>
                            </div>
                        </div>`);

                    console.error('Error loading crewhistory:', error);
                    console.error('Response:', xhr.responseText);
                }
            });
        });

        $(document).on('click', '.retry-load', function () {
            var contact_id = $(this).data('id');
            var button = $(`button.neo-orba[data-id="${contact_id}"]`);
            button.trigger('click');
        });
    }

    function performMassAction(action, statusCode) {
        let selectedIds = $(".select-checkbox:checked")
            .map(function () { return $(this).val(); })
            .get();

        if (selectedIds.length === 0) {
            Swal.fire({
                title: "Tidak Ada Data Terpilih!",
                text: "Pilih minimal satu data untuk diproses.",
                icon: "warning",
                confirmButtonColor: "#d33",
                confirmButtonText: "OK"
            });
            return;
        }

        Swal.fire({
            title: `Yakin ingin hapus?`,
            text: `Anda akan hapus ${selectedIds.length} data.`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6e7d88",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= Url::to(['/contact/deletemassal']) ?>',
                    type: "POST",
                    data: {
                        ids: selectedIds,
                        _csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
                    },
                    headers: {
                        "X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
                    },
                    success: function (response) {
                        $('#datatable').DataTable().ajax.reload(null, false);
                        $("#mass-action-buttons").hide();
                        $("#select-all").prop("checked", false);
                        Swal.fire({
                            title: "Terhapus!",
                            text: "Data berhasil dihapus.",
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function (xhr) {
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

    $("#filterForm").submit(function (e) {
        e.preventDefault();
        currentFilters.employee = $('#filter-employee').val();
        currentFilters.orderStatus = $('#filter-order-status').val();
        currentFilters.position = $('#filter-position').val();
        currentFilters.division = $('#filter-division').val();

        $('#datatable').DataTable().ajax.reload();

        let filterMenu = document.querySelector("[data-kt-menu-id='filter-menu-contact']");
        if (filterMenu && KTMenu.getInstance(filterMenu)) {
            KTMenu.getInstance(filterMenu).hide();
        }
    });

    $('#btn-reset-filter').on('click', function () {
        $('#filter-employee').val(null).trigger('change');
        $('#filter-order-status').val('');
        $('#filter-position').val(null).trigger('change');
        $('#filter-division').val(null).trigger('change');
        currentFilters = { employee: '', orderStatus: '', position: '', division: '' };
        $('#datatable').DataTable().ajax.reload();
    });

    $(document).on('click', '.edit', function (e) {
        e.preventDefault();
        let contactId = $(this).data('id');

        $.ajax({
            url: '<?= Url::to(['contact/update']) ?>',
            type: 'GET',
            data: { contact_id: contactId },
            success: function (data) {
                let parser = new DOMParser();
                let doc = parser.parseFromString(data, 'text/html');
                $('#modal-content').html(doc.body.innerHTML);
                $('#modal-title').html("<?= Yii::$app->lang->t('front_home', 'contact_edit') ?>");
                $('#modal_form_contact').modal('show');
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    title: "Error!",
                    text: "Gagal memuat form.",
                    icon: "error",
                    confirmButtonColor: "#d33",
                    confirmButtonText: "OK"
                });
            }
        });
    });

    $(document).on('click', '.delete', function () {
        var contactId = $(this).data('id');

        Swal.fire({
            title: "Yakin ingin hapus?",
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6e7d88",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= Url::to(['/contact/delete']) ?>',
                    type: 'POST',
                    data: {
                        id: contactId,
                        _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
                    },
                    headers: {
                        "X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
                    },
                    success: function (response) {
                        $('#datatable').DataTable().ajax.reload();
                        Swal.fire({
                            title: "Terhapus!",
                            text: "Data berhasil dihapus.",
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            title: "Gagal!",
                            text: "Terjadi kesalahan saat menghapus data.",
                            icon: "error",
                            confirmButtonColor: "#d33",
                            confirmButtonText: "OK"
                        });
                    }
                });
            }
        });
    });

    $("#search").submit(function (e) {
        e.preventDefault();
        $("#datatable").DataTable().ajax.reload();
    });

    $(document).ready(function () {
        initData();

        <?php if ($typeName == 'employee') { ?>
            loadFilterOptions();
            $('#filterForm').on('shown.bs.modal', function () {
                if ($('#filter-employee').hasClass('select2-hidden-accessible')) {
                    $('#filter-employee').select2('destroy');
                }
                if ($('#filter-position').hasClass('select2-hidden-accessible')) {
                    $('#filter-position').select2('destroy');
                }
                if ($('#filter-division').hasClass('select2-hidden-accessible')) {
                    $('#filter-division').select2('destroy');
                }
                loadFilterOptions();
            });
        <?php } ?>

        $('#add-button').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('href'),
                type: 'GET',
                success: function (data) {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(data, 'text/html');
                    $('#modal-content').html(doc.body.innerHTML);
                    $('#modal_form_contact').modal('show');
                },
                error: function () {
                    $('#modal-content').html('<p>Error loading form.</p>');
                }
            });
        });
    });
</script>