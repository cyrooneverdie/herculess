<?php

use yii\helpers\Html;
use yii\helpers\Url;

$leavetype = $leavetype ?? null;
$tab = $tab ?? 'profiledata';

$isAbsent = ($leavetype === 'absent');

$pageTitle = 'Leave Management';
if ($isAbsent) {
    $pageTitle = 'Absent Management';
}

$this->title = $pageTitle;
?>

<style>
    @media (max-width: 767.98px) {
        .card-header.sticky-top {
            top: var(--kt-app-header-height-mobile, 60px);
        }
    }

</style>

<div class="card">
    <div class="card-header border-0 pt-6 sticky-top bg-white shadow-sm"
        style="top: var(--kt-app-header-height, 70px); z-index: 1010;">
        <div class="card-title flex-grow-1">
            <div class="d-flex align-items-center position-relative my-2">
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
                            class="form-control form-control-solid w-100 w-md-170px ps-10" placeholder="Search ...">
                    </div>
                </form>
            </div>
        </div>

        <div class="card-toolbar flex-shrink-0 w-100 w-md-auto mt-3 mt-md-0">
            <div class="d-flex gap-2 flex-wrap flex-column flex-sm-row">
                <button type="button" class="btn btn-light-primary w-100 w-sm-auto" data-kt-menu-trigger="click"
                    data-kt-menu-placement="left-start">
                    <i class="ki-duotone ki-filter fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>Filter
                </button>

                <div class="menu menu-sub menu-sub-dropdown w-100 w-sm-500px w-md-600px" data-kt-menu="true"
                    data-kt-menu-id="filter-menu">
                    <div class="px-7 py-5">
                        <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                    </div>

                    <div class="separator border-gray-200"></div>

                    <div class="px-7 py-5" data-kt-user-table-filter="form">
                        <form id="filterForm" method="get" action="index">
                            <div class="row">
                                <div class="col-md-12 mb-5">
                                    <label class="fw-semibold fs-6 mb-2" for="filter-crew-type">Crew Type</label>
                                    <select id="filter-crew-type" class="form-select" data-control="select2"
                                        name="crew_type">
                                        <option value="">All Crew Types</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mb-5">
                                    <label class="fw-semibold fs-6 mb-2" for="filter-crew">Crew</label>
                                    <select id="filter-crew" class="form-select" data-control="select2" name="crew_id">
                                        <option value="">All Crews</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group text-end mt-5">
                                <button type="submit" id="filterButton" class="btn btn-lg btn-primary w-100 w-sm-auto">
                                    <i class="fa-sharp fa-solid fa-filter"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                $addUrl = Url::to(['leave/create', 'leavetype' => 'leave']);
                $addButtonText = 'Add Leave Request';

                if ($leavetype === 'absent') {
                    $addUrl = Url::to(['absent/create', 'leavetype' => 'absent']);
                    $addButtonText = 'Add Absent Request';
                } elseif ($leavetype === 'parttime') {
                    $addUrl = Url::to(['leave/create', 'leavetype' => 'parttime', 'tab' => $tab]);
                    if ($tab === 'profiledata') {
                        $addButtonText = 'Add Personal Data';
                    } elseif ($tab === 'workattendance') {
                        $addButtonText = 'Add Work Attend';
                    } elseif ($tab === 'parttimepayment') {
                        $addButtonText = 'Add Payment';
                    }
                }
                ?>
                <a href="<?= $addUrl ?>" class="btn btn-primary add w-100 w-sm-auto" id="add-button">
                    <i class="ki-duotone ki-plus fs-2"></i>
                    <?= $addButtonText ?>
                </a>

                <div class="btn-group w-100 w-sm-auto">
                    <button type="button" class="btn btn-primary dropdown-toggle w-100 w-sm-auto" data-bs-toggle="dropdown">
                        <i class="fas fa-download me-2"></i>Export
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" id="btn-export-excel">
                                <i class="fas fa-file-excel me-2"></i>Export to Excel
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body pt-5">
        <div class="btn-group mb-3" id="mass-action-buttons" style="display: none;">
            <button type="button" class="btn btn-danger" id="btn-delete-mass">
                <i class="fas fa-trash me-2"></i> Delete
            </button>
        </div>

        <div>
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="datatable">
                <div class="d-none">
                    <thead>
                        <tr class="text-start bg-gray-100 fw-bold fs-7 text-uppercase gs-0">
                            <th class="d-none"></th>
                            <th class="text-center min-w-50px">
                                <input type="checkbox" id="select-all" class="form-check-input">
                            </th>
                            <th class="text-center min-w-100px"><?= Yii::$app->lang->t('leave', 'leave1') ?></th>
                            <?php if ($isAbsent): ?>
                                <th class="text-center min-w-150px"><?= Yii::$app->lang->t('leave', 'leave11') ?> </th>
                                <th class="text-center min-w-100px"><?= Yii::$app->lang->t('leave', 'leave15') ?> </th>
                                <th class="text-center min-w-130px"><?= Yii::$app->lang->t('leave', 'leave7') ?> </th>
                                <th class="text-center min-w-70px"><?= Yii::$app->lang->t('leave', 'leave65') ?></th>
                                <th class="text-center min-w-110px"><?= Yii::$app->lang->t('leave', 'leave4') ?> </th>
                                <th class="text-center min-w-110px"><?= Yii::$app->lang->t('leave', 'leave5') ?></th>
                                <th class="text-center min-w-90px"><?= Yii::$app->lang->t('leave', 'leave32') ?> </th>
                                <th class="text-center min-w-90px"><?= Yii::$app->lang->t('leave', 'leave33') ?> </th>
                                <th class="text-center min-w-200px"><?= Yii::$app->lang->t('leave', 'leave6') ?></th>
                            <?php else: ?>
                                <th class="text-center min-w-150px">Status Approval</th>
                                <th class="text-center min-w-150px"><?= Yii::$app->lang->t('leave', 'leave11') ?> </th>
                                <th class="text-center min-w-125px"><?= Yii::$app->lang->t('leave', 'leave15') ?> </th>
                                <th class="text-center min-w-150px"><?= Yii::$app->lang->t('leave', 'leave3') ?> </th>
                                <th class="text-center min-w-70px"><?= Yii::$app->lang->t('leave', 'leave65') ?></th>
                                <th class="text-center min-w-125px"><?= Yii::$app->lang->t('leave', 'leave4') ?> </th>
                                <th class="text-center min-w-125px"><?= Yii::$app->lang->t('leave', 'leave5') ?> </th>
                                <th class="text-center min-w-200px"><?= Yii::$app->lang->t('leave', 'leave6') ?></th>
                            <?php endif; ?>
                        </tr>
                    </thead>

                    <tbody class="text-gray-600 fw-semibold"></tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_form_leave" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-sm-down modal-lg modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title-text"><?= Yii::$app->lang->t('leave', 'leave72') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-content" class="nopadding"></div>
            </div>
        </div>
    </div>
</div>

<script>
    const LEAVETYPE_FILTER = '<?= $leavetype ?? "" ?>';
    const CURRENT_TAB = '<?= $tab ?? "profiledata" ?>';

    function formatCrewOption(item) {
        if (!item.id) return item.text;

        if (item.leave_warning === 'pending') {
            return `
            <span>
                ${item.text}
                <span class="badge badge-warning ms-2" title="Memiliki pengajuan cuti pending">
                    ⚠ Cuti Pending
                </span>
            </span>
        `;
        }
        return item.text;
    }

    function initCrewSelect2WithLeaveCheck(selector, checkDate) {

        if (!checkDate) {
            const d = new Date();
            checkDate = d.getFullYear() + '-' +
                String(d.getMonth() + 1).padStart(2, '0') + '-' +
                String(d.getDate()).padStart(2, '0');
        }

        $(selector).select2({
            placeholder: 'Pilih Employee...',
            allowClear: true,
            dropdownParent: $(selector).closest('.modal'),
            width: '100%',

            templateResult: formatCrewOption,
            templateSelection: item => item.text || item.id,
            escapeMarkup: m => m,

            ajax: {
                url: '/leave/select',
                type: 'POST',
                dataType: 'json',
                delay: 300,
                data: params => ({
                    q: params.term || '',
                    page: params.page || 1,
                    check_date: checkDate,
                    _csrf: $('meta[name="csrf-token"]').attr('content')
                }),
                processResults: data => ({
                    results: data.items.map(i => ({
                        id: i.id,
                        text: i.text,
                        contact_no: i.contact_no,
                        leave_warning: i.leave_warning
                    })),
                    pagination: { more: data.pagination?.more }
                }),
                cache: true
            }
        }).on('select2:select', function (e) {

            const data = e.params.data;
            $('#employee_code').val(data.contact_no || 'N/A');

            if (data.leave_warning === 'pending') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    html: `<p>${data.text} memiliki <strong>pengajuan cuti yang belum disetujui</strong>.</p>`,
                    confirmButtonText: 'Mengerti'
                });
            }
        });
    }

    var deletemessage1 = "<?= Yii::$app->lang->t('extra', 'extra44') ?>";
    var deletemessage2 = "<?= Yii::$app->lang->t('extra', 'extra45') ?>";
    var deletemessage3 = "<?= Yii::$app->lang->t('extra', 'extra46') ?>";
    var deletemessage3koma1 = "<?= Yii::$app->lang->t('extra', 'extra46.1') ?>";
    var deletemessage4 = "<?= Yii::$app->lang->t('back_home', 'chat34') ?>";
    var deletemessage5 = "<?= Yii::$app->lang->t('back_home', 'chat53') ?>";

    $(document).ready(function () {
        function renderData(data, defaultValue = '-') {
            if (data === null || data === undefined || data === '' || data === 'null') {
                return defaultValue;
            }
            return data;
        }

        function renderBadge(data, badgeClass = 'info', defaultValue = '-') {
            if (data === null || data === undefined || data === '' || data === 'null') {
                return `<span class="text-muted">${defaultValue}</span>`;
            }
            return `<span class="badge badge-${badgeClass}">${data}</span>`;
        }

        $('#filter-crew-type').select2({
            dropdownParent: $('[data-kt-menu-id="filter-menu"]'),
            placeholder: 'Filter by Crew Type',
            allowClear: true,
            ajax: {
                url: '<?= Url::to(['leave/crewtypelist']) ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.items,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            }
        });

        $('#filter-crew').select2({
            dropdownParent: $('[data-kt-menu-id="filter-menu"]'),
            placeholder: 'Filter by Crew',
            allowClear: true,
            ajax: {
                url: '<?= Url::to(['leave/crewlist']) ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        crew_type: $('#filter-crew-type').val(),
                        page: params.page || 1
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.items,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            }
        });

        $("#filterForm").on("submit", function (e) {
            e.preventDefault();

            table.ajax.reload();

            let filterMenu = document.querySelector("[data-kt-menu-id='filter-menu']");
            if (filterMenu) {
                KTMenu.getInstance(filterMenu).hide();
            }
        });

        $('#filter-crew-type').on('change', function () {
            $('#filter-crew').val(null).trigger('change');
        });

        let columns = [
            {
                data: 'leaveid',
                visible: false
            },
            {
                data: null,
                className: "text-center",
                orderable: false,
                render: function (data, type, row) {
                    return `<div class="form-check justify-content-center d-flex align-items-center m-0">
                        <input type="checkbox" class="select-row form-check-input m-0" value="${row.leaveid}">
                    </div>`;
                }
            },
            {
                data: null,
                className: "text-center w-40px",
                render: function (data, type, row) {
                    return `
                    <div class="dropdown text-left dropend">
                        <button class="btn btn-sm btn-light-primary btn-icon neo-orba" 
                                data-id="${row.leaveid}"
                                title="Show Crew Details">
                            <i class="fa-solid fa-caret-right"></i>
                        </button>
                        <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                            <i class="fa-sharp fa-solid fa-list"></i>
                        </button>
                        <ul class="dropdown-menu px-2">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item text-hover-success edit" 
                                   data-id="${row.leaveid}" style="cursor:pointer;">
                                   <i class="fas fa-edit"></i> Edit
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item text-hover-danger delete" data-id="${row.leaveid}">
                                    <i class="fas fa-trash me-2"></i> Delete
                                </a>
                            </li>
                            <li>
                            <a class="dropdown-item text-hover-primary" href="<?= Url::to(['print']) ?>?jenis=3&id=${row.leaveid}&leavetype=${LEAVETYPE_FILTER}" target="_blank">
                                <i class="fa fa-print"></i> Print
                            </a>
                            </li>
                            <li> 
                                <a class="dropdown-item text-hover-success" href="<?= Url::to(['print']) ?>?jenis=3&id=${row.leaveid}&jenisreport=excel&leavetype=${LEAVETYPE_FILTER}" target="_blank">
                                    <i class="fas fa-file-excel"></i> Export to Excel
                                </a>
                            </li>
                        </ul>
                    </div>
                `;
                }
            }
        ];

        <?php if ($leavetype === 'absent'): ?>
            columns.push({
                data: "contact_name",
                className: 'text-start',
                render: data => renderData(data, '<span class="text-muted">No name</span>')
            }, {
                data: "employee_code",
                className: 'text-center',
                render: data => renderBadge(data, 'light')
            }, {
                data: "leavetype_name",
                className: 'text-start',
                render: data => renderData(data)
            }, {
                data: "pict_employee",
                className: 'text-center',
                width: '70px',
                render: function (data, type, row) {
                    const photoUrl = (data && data.trim() !== "" && data !== "blank.png" && data !== "null") ?
                        `/uploads/leave/${data}` :
                        `/assets/media/svg/avatars/blank.svg`;

                    const altText = row.contact_name || 'Employee';
                    return `<img src="${photoUrl}" style="width:45px; height:45px; border-radius:50%; object-fit:cover; border: 2px solid #e4e6ef;" alt="${altText}" onerror="this.onerror=null; this.src='/assets/media/svg/avatars/blank.svg';">`;
                }
            }, {
                data: "leavedate",
                className: 'text-start',
                render: data => renderData(data)
            }, {
                data: "leaveduedate",
                className: 'text-start',
                render: data => renderData(data)
            }, {
                data: "check_in",
                className: 'text-center',
                render: function (data, type, row) {
                    if (!data || data === 'null' || data === '') return '-';
                    const time = data.includes(' ') ? data.split(' ')[1].substring(0, 5) : data.substring(0, 5);
                    let html = `<span class="badge badge-light-primary">${time}</span>`;
                    if (row.late_duration && row.late_duration !== 'null') {
                        html += `<br><small class="text-danger fw-bold"><i class="fas fa-clock me-1"></i>+${row.late_duration}</small>`;
                    }
                    return html;
                }
            }, {
                data: "check_out",
                className: 'text-center',
                render: function (data, type, row) {
                    if (!data || data === 'null' || data === '') return '-';
                    const time = data.includes(' ') ? data.split(' ')[1].substring(0, 5) : data.substring(0, 5);
                    let html = `<span class="badge badge-light-info">${time}</span>`;
                    if (row.overtime_duration && row.overtime_duration !== 'null') {
                        html += `<br><small class="text-success fw-bold"><i class="fas fa-clock me-1"></i>+${row.overtime_duration}</small>`;
                    }
                    return html;
                }
            }, {
                data: "leave_reason",
                className: 'text-center',
                render: function (data) {
                    if (!data || data === 'null' || data === '') return '-';
                    return data.length > 50 ? `<span title="${data}">${data.substring(0, 50)}...</span>` : data;
                }
            }

            );
        <?php else: ?>
            columns.push
                ({
                    data: "leave_status",
                    className: "text-start",
                    render: function (data, type, row) {
                        let statusInfo = {
                            0: {
                                text: "Menunggu Persetujuan",
                                color: "primary",
                                icon: "fa-solid fa-clock"
                            },
                            1: {
                                text: "Approved",
                                color: "success",
                                icon: "fa-check-circle"
                            },
                            2: {
                                text: "Cancelled",
                                color: "warning",
                                icon: "fa-ban"
                            },
                            3: {
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
                                        <a class="dropdown-item items update-status d-flex align-items-center" href="#" data-id="${row.leaveid}" data-status="1">
                                          <i class="fas fa-check-circle text-success me-2"></i> Approve
                                         </a>
                                    </li>
                                    <li><a class="dropdown-item items update-status d-flex align-items-center" href="#" data-id="${row.leaveid}" data-status="2">
                                        <i class="fas fa-ban text-warning me-2"></i> Cancel
                                    </a></li>
                                `;
                            } else if (data == 1) {
                                statusOptions = `
                                    <li>
                                    <a class="dropdown-item items update-status d-flex align-items-center" href="#" data-id="${row.leaveid}" data-status="2">
                                        <i class="fas fa-ban text-warning me-2"></i> Cancel
                                    </a>
                                   
                                    <li>
                                        <a class="dropdown-item items update-status d-flex align-items-center" href="#" data-id="${row.leaveid}" data-status="0">
                                            <i class="fa-solid fa-clock text-primary me-2"></i> Pending
                                        </a>
                                    </li>

                                `;
                            } else if (data == 2 || data == 3) {
                                statusOptions = `
                                    <li><a class="dropdown-item items update-status d-flex align-items-center" href="#" data-id="${row.leaveid}" data-status="1">
                                        <i class="fas fa-check-circle text-success me-2"></i> Approve
                                    </a></li>
                                     <li>
                                    

                                `;
                            }
                        }

                        return `
                        <div class="dropdown dropend">
                            <button class="btn btn-${statusBg} btn-sm px-3 py-2 d-flex align-items-center justify-content-center mx-auto text-nowrap" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="min-width: 110px; border-radius: 6px;">
                                <i class="fas ${statusIcon} me-2"></i>
                                <span>${statusText}</span>
                                <i class="fas fa-chevron-down ms-2 opacity-50" style="font-size: 0.8em;"></i>
                            </button>
                            <ul class="dropdown-menu py-2 shadow-sm">${statusOptions}</ul>
                        </div>`;
                    }
                }, {
                    data: "contact_name",
                    className: 'text-center',
                    render: data => renderData(data)
                }, {
                    data: "employee_code",
                    className: 'text-center',
                    render: data => renderBadge(data, 'light')
                }, {
                    data: "leavetype_name",
                    className: 'text-center',
                    render: data => renderData(data)
                }, {
                    data: "pict_employee",
                    className: 'text-center',
                    width: '70px',
                    render: function (data, type, row) {
                        const photoUrl = (data && data.trim() !== "" && data !== "blank.png" && data !== "null") ?
                            `/uploads/leave/${data}` :
                            `/assets/media/svg/avatars/blank.svg`;

                        const altText = row.contact_name || 'Employee';
                        return `<img src="${photoUrl}" style="width:45px; height:45px; border-radius:50%; object-fit:cover; border: 2px solid #e4e6ef;" alt="${altText}" onerror="this.onerror=null; this.src='/assets/media/svg/avatars/blank.svg';">`;
                    }
                }, {
                    data: "leavedate",
                    className: 'text-center',
                    render: data => renderData(data)
                }, {
                    data: "leaveduedate",
                    className: 'text-center',
                    render: data => renderData(data)
                }, {
                    data: "leave_reason",
                    className: 'text-center',
                    render: function (data) {
                        if (!data || data === 'null' || data === '') return '-';
                        return data.length > 50 ? `<span title="${data}">${data.substring(0, 50)}...</span>` : data;
                    }
                }

                );
        <?php endif; ?>

        $(document).on('click', '.delete', function () {
            let id = $(this).data('id');

            if (!id) {
                Swal.fire({
                    title: "<?= Yii::$app->lang->t('extra', 'extra60') ?>",
                    text: "<?= Yii::$app->lang->t('extra', 'extra61') ?>",
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
                        url: '<?= Url::to(['/leave/delete']) ?>' + '?id=' + id,
                        type: 'POST',
                        data: {
                            _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
                        },
                        headers: {
                            "X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
                        },
                        success: function (response) {
                            if (!response.success) {
                                Swal.fire({
                                    title: "Error!",
                                    text: response.pesan || 'Terjadi kesalahan',
                                    icon: "error",
                                    confirmButtonColor: "#d33",
                                    confirmButtonText: "OK"
                                });
                                return;
                            }

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

        $(document).on('click', '#btn-export-excel', function () {
            const isProfileData = (LEAVETYPE_FILTER === 'parttime' && CURRENT_TAB === 'profiledata');

            if (isProfileData) {
                Swal.fire({
                    title: 'Export All Profile Data?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-file-excel me-1"></i> Export Sekarang',
                    confirmButtonColor: '#28a745'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const url = `<?= Url::to(['/leave/print']) ?>?jenis=3&jenisreport=excel&leavetype=${LEAVETYPE_FILTER}`;
                        window.open(url, '_blank');
                    }
                });
            } else {
                Swal.fire({
                    title: 'Export ke Excel',
                    html: `<div class="text-start">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Mulai</label>
                        <input type="date" id="export-excel-start-date" class="form-control" value="<?= date('Y-m-01') ?>">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold">Tanggal Selesai</label>
                        <input type="date" id="export-excel-end-date" class="form-control" value="<?= date('Y-m-t') ?>">
                    </div>
                </div>`,
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-file-excel me-1"></i> Export',
                    confirmButtonColor: '#28a745',
                    preConfirm: () => {
                        const start = document.getElementById('export-excel-start-date').value;
                        const end = document.getElementById('export-excel-end-date').value;
                        if (!start || !end) return Swal.showValidationMessage('⚠️ Pilih tanggal terlebih dahulu');
                        if (start > end) return Swal.showValidationMessage('⚠️ Tanggal mulai tidak valid');
                        return { start, end };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const url = `<?= Url::to(['/leave/print']) ?>?jenis=3&jenisreport=excel&leavetype=${LEAVETYPE_FILTER}&start_date=${result.value.start}&end_date=${result.value.end}`;
                        window.open(url, '_blank');
                    }
                });
            }
        });

        let table = $("#datatable").DataTable({
            scrollX: true,
            scrollCollapse: true,
            processing: true,
            serverSide: true,
            lengthMenu: [5, 15, 30, 50],
            pageLength: 15,
            autoWidth: false,
            language: {
                emptyTable: `<div style="text-align: center; padding: 20px 0;">
                <img width='250px' src='https://cdni.iconscout.com/illustration/premium/thumb/employee-is-unable-to-find-sensitive-data-illustration-download-in-svg-png-gif-file-formats--no-found-misplaced-files-business-pack-illustrations-8062128.png'/>
                <div style="font-weight: bold; font-size: 16px; margin-top: 8px;">No data available</div>
            </div>`,
                zeroRecords: `<div style="text-align: center; padding: 20px 0;">
                <img width='250px' src='https://cdni.iconscout.com/illustration/premium/thumb/employee-is-unable-to-find-sensitive-data-illustration-download-in-svg-png-gif-file-formats--no-found-misplaced-files-business-pack-illustrations-8062128.png'/>
                <div style="font-weight: bold; font-size: 16px; margin-top: 8px;">No matching records found</div>
            </div>`
            },
            ajax: {
                type: "GET",
                url: "<?= Url::to(['leave/list']) ?>",
                data: function (d) {
                    d.search = $('input[name="search"]').val();
                    d.leavetype = LEAVETYPE_FILTER;
                    d.tab = CURRENT_TAB;
                    d.crew_type = $('#filter-crew-type').val();
                    d.crew_id = $('#filter-crew').val();
                }
            },
            columns: columns,
            drawCallback: function (settings) {
                addSubtableExpand();
            },

            initComplete: function () {
                addSubtableExpand();
            }
        });

        $("#search").on("submit", function (e) {
            e.preventDefault();
            table.ajax.reload();
        });


        function showModal() {
            new bootstrap.Modal(document.getElementById("modal_form_leave")).show();
        }


        $("#add-button").on("click", function (e) {
            e.preventDefault();

            let createUrl = $(this).data('url');
            let modalTitle;

            if (LEAVETYPE_FILTER === 'absent') {
                createUrl = "<?= Url::to(['/absent/create']) ?>?leavetype=absent";
                modalTitle = "New Absent Request";
            } else if (LEAVETYPE_FILTER === 'parttime') {
                if (CURRENT_TAB === 'profiledata') {
                    createUrl = "<?= Url::to(['/parttime/personal/create']) ?>?leavetype=parttime&tab=profiledata";
                    modalTitle = "New Personal Data";
                } else if (CURRENT_TAB === 'workattendance') {
                    createUrl = "<?= Url::to(['/parttime/attendance/create']) ?>?leavetype=parttime&tab=workattendance";
                    modalTitle = "New Work Attendance";
                } else if (CURRENT_TAB === 'parttimepayment') {
                    createUrl = "<?= Url::to(['/parttime/payment/create']) ?>?leavetype=parttime&tab=parttimepayment";
                    modalTitle = "New Payment";
                }
            } else {
                createUrl = "<?= Url::to(['leave/create']) ?>?leavetype=leave";
                modalTitle = "New Leave Request";
            }

            $.ajax({
                url: createUrl,
                success: function (data) {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(data, "text/html");
                    $("#modal-content").html(doc.body.innerHTML);
                    $("#modal-title-text").html(modalTitle);
                    showModal();
                    initFormSubmitHandler(table);
                },
                error: function (xhr, status, error) {
                    console.error('Response:', xhr.responseText);
                    Swal.fire('Error!', 'Gagal memuat form: ' + error, 'error');
                }
            });
        });

        $(document).on("click", ".edit", function () {
            let id = $(this).data("id");
            let updateUrl, modalTitle;

            if (LEAVETYPE_FILTER === 'absent') {
                updateUrl = "<?= Url::to(['/leave/update']) ?>/" + id + "?leavetype=absent";
                modalTitle = "Edit Absent Request";
            } else if (LEAVETYPE_FILTER === 'parttime') {
                updateUrl = "<?= Url::to(['leave/update']) ?>/" + id + "?leavetype=parttime&tab=" + CURRENT_TAB;
                modalTitle = CURRENT_TAB === 'profiledata' ? "Edit Personal Data" :
                    CURRENT_TAB === 'workattendance' ? "Edit Work Attendance" : "Edit Payment";
            } else {
                updateUrl = "<?= Url::to(['leave/update']) ?>/" + id + "?leavetype=leave";
                modalTitle = "Edit Leave Request";
            }


            $.ajax({
                url: updateUrl,
                type: 'GET',
                success: function (data) {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(data, "text/html");
                    $("#modal-content").html(doc.body.innerHTML);
                    $("#modal-title-text").html(modalTitle);
                    showModal();
                    setTimeout(() => initFormSubmitHandler(table), 300);
                },
                error: function (xhr) {
                    console.error('❌ Response:', xhr.responseText);
                    Swal.fire('Error!', 'Gagal memuat form: ' + (xhr.responseText || 'Unknown error'), 'error');
                }
            });
        });

        $(document).on("click", ".delete", function () {
            let id = $(this).data("id");
            Swal.fire({
                title: "Delete?",
                text: "Apakah Anda yakin ingin menghapus data ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonText: "Batal",
                confirmButtonText: "Delete"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("<?= Url::to(['leave/delete']) ?>?id=" + id, {
                        _csrf: "<?= Yii::$app->request->csrfToken ?>"
                    }, function (response) {
                        if (response.success) {
                            table.ajax.reload(null, false);
                            Swal.fire("Deleted!", "Data berhasil dihapus.", "success");
                        } else {
                            Swal.fire("Error!", response.message, "error");
                        }
                    });
                }
            });
        });

        function toggleMassDelete() {
            $("#mass-action-buttons").toggle($(".select-row:checked").length > 0);
        }

        $(document).on("change", ".select-row", function () {
            $("#select-all").prop("checked", $(".select-row").length === $(".select-row:checked").length);
            toggleMassDelete();
        });

        $("#select-all").on("change", function () {
            $(".select-row").prop("checked", this.checked);
            toggleMassDelete();
        });

        $("#datatable").on("draw.dt", toggleMassDelete);

        $("#btn-delete-mass").on("click", function () {
            let selected = $(".select-row:checked").map(function () {
                return $(this).val();
            }).get();

            if (selected.length === 0) {
                Swal.fire("Tidak ada data yang dipilih", "", "warning");
                return;
            }

            Swal.fire({
                title: "Delete?",
                text: `Apakah Anda yakin ingin menghapus ${selected.length} data?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonText: "Batal",
                confirmButtonText: "Delete"
            }).then((res) => {
                if (res.isConfirmed) {
                    $.post("<?= Url::to(['leave/deletemassal']) ?>", {
                        ids: selected,
                        _csrf: "<?= Yii::$app->request->csrfToken ?>"
                    }, function () {
                        table.ajax.reload(null, false);
                        Swal.fire("Deleted!", "Semua data berhasil dihapus.", "success");
                    });
                }
            });
        });

        $(document).on("click", ".update-status", function (e) {
            e.preventDefault();

            let leaveId = $(this).data("id");
            let newStatus = $(this).data("status");
            let button = $(this).closest(".dropdown").find("button");

            let statusInfo = {
                0: {
                    text: "Menunggu Persetujuan",
                    color: "primary",
                    icon: "fa-file-alt"
                },
                1: {
                    text: "Approved",
                    color: "success",
                    icon: "fa-check-circle"
                },
                2: {
                    text: "Cancelled",
                    color: "warning",
                    icon: "fa-ban"
                },
                3: {
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
                url: "<?= Url::to(['/leave/updatestatus']) ?>",
                type: "POST",
                data: {
                    id: leaveId,
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
                            alertType = "success";
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

    });

    $(document).on('click', '.export-single', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        const leavetype = $(this).data('leavetype') || LEAVETYPE_FILTER;
        const tab = $(this).data('tab') || CURRENT_TAB;

        if (!id) {
            Swal.fire('Error', 'ID tidak ditemukan!', 'error');
            return;
        }

        const exportUrl = `<?= Url::to(['leave/exportsingle']) ?>?id=${id}&leavetype=${leavetype}&tab=${tab}`;

        Swal.fire({
            title: 'Exporting...',
            text: 'Sedang memproses data',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = exportUrl;
        document.body.appendChild(iframe);

        setTimeout(() => {
            document.body.removeChild(iframe);
            Swal.fire({
                icon: 'success',
                title: 'Export Berhasil!',
                timer: 2000,
                showConfirmButton: false
            });
        }, 2000);
    });

    function addSubtableExpand() {
        $('.neo-orba').off('click').on('click', function (e) {
            e.stopPropagation();

            var button = $(this);
            var leaveid = button.data('id');
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
                        <h6 class="mb-3 text-gray-700">
                            <i class="fas fa-history me-2"></i> Riwayat Crew di Sales Order
                        </h6>
                        <div id="subtable-container-${leaveid}">
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

            $.ajax({
                url: '<?= Url::to(['leave/crewhistory']) ?>',
                type: 'GET',
                data: {
                    leaveid: leaveid
                },
                success: function (response) {
                    var container = $(`#subtable-container-${leaveid}`);

                    if (response.success && response.data && response.data.length > 0) {
                        var tableHTML = `
                    <table class="table table-sm table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">Tanggal Mulai</th>
                                <th class="text-center">Tanggal Selesai</th>
                                <th class="text-center">No. SO</th>
                                <th class="text-center">Event</th>
                                <th class="text-center">Lokasi</th>
                                <th class="text-center">Jenis Crew</th>
                                <th class="text-center">Jabatan</th>
                                <th class="text-center">Fee</th>
                            </tr>
                        </thead>
                        <tbody>`;

                        response.data.forEach(function (item) {
                            tableHTML += `
                        <tr>
                            <td class="text-center">${item.startdate}</td>
                            <td class="text-center">${item.enddate}</td>
                            <td class="text-center">${item.tranno}</td>
                            <td>${item.eventname}</td>
                            <td>${item.locations}</td>
                            <td class="text-center">${item.crew_type}</td>
                            <td class="text-center">${item.job_name}</td>
                            <td class="text-end">${item.fee}</td>
                        </tr>`;
                        });

                        tableHTML += `
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="9" class="text-end">
                                    <span class="badge bg-primary">Total: ${response.total} events</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>`;

                        container.html(tableHTML);
                    } else {
                        container.html(`
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                            Tidak ada riwayat crew di Sales Order
                            ${response.message ? `<br><small>${response.message}</small>` : ''}
                        </div>
                    `);
                    }
                },
                error: function (xhr, status, error) {
                    var container = $(`#subtable-container-${leaveid}`);
                    container.html(`
                    <div class="text-center text-danger py-4">
                        <i class="fas fa-exclamation-circle fa-2x mb-2"></i><br>
                        Gagal memuat data<br>
                        <small>${error}</small>
                    </div>
                `);

                    console.error('Error loading crewhistory:', error);
                    console.error('Response:', xhr.responseText);
                }
            });
        });
    }

    function initFormSubmitHandler(table) {
        let isSubmitting = false;

        $(document).off('submit', '#form-leave').on('submit', '#form-leave', function (e) {
            e.preventDefault();
            if (isSubmitting) return false;

            if (LEAVETYPE_FILTER === 'parttime' && CURRENT_TAB === 'parttimepayment') {
                const leavedate = $('#leavedate_payment').val();
                const workDates = $('#work_dates').val();
                const paymentMethod = $('#payment').val();
                const total = $('#total').val();
                const paymentDate = $('#payment_date').val();
                const status = $('#status').val();

                if (!leavedate || leavedate === '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validasi',
                        text: 'Pilih tanggal kerja terlebih dahulu!'
                    });
                    return false;
                }

                if (!workDates || workDates.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validasi',
                        text: 'Pilih minimal 1 tanggal kerja yang akan dibayar!'
                    });
                    return false;
                }


                if (!leavedate && workDates.length > 0) {
                    $('#leavedate_payment').val(workDates[0]);
                    console.log('Auto-set leavedate:', workDates[0]);
                }
            }

            isSubmitting = true;
            let formData = new FormData(this);

            $('#btnsubmit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        $('#modal_form_leave').modal('hide');
                        table.ajax.reload(null, false);
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire('Gagal!', response.message, 'error');
                    }
                },
                error: () => Swal.fire('Error!', 'Tidak dapat terhubung ke server', 'error'),
                complete: () => {
                    isSubmitting = false;
                    $('#btnsubmit').prop('disabled', false).html('Submit Request');
                }
            });
        });
    }

</script>