<?php

use yii\helpers\Html;
use yii\helpers\Url;

$leavetype = $leavetype ?? null;
$tab = $tab ?? 'profiledata';

$isAbsent = ($leavetype === 'absent');
$isParttime = ($leavetype === 'parttime');

$pageTitle = 'Leave Management';
if ($isAbsent) {
    $pageTitle = 'Absent Management';
}

$this->title = $pageTitle;
?>

<div class="card mt-5">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
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
                            class="form-control form-control-solid w-170px ps-10" placeholder="Search ...">
                    </div>
                </form>
            </div>
        </div>

        <div class="card-toolbar">
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
                    <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
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
                                <label class="fw-semibold fs-6 mb-2 mt-3" for="dateFilter">Rentang Tanggal</label>
                                <input name="datefilter" class="form-control form-control-solid" style="cursor:pointer;"
                                    id="datefilter" placeholder="<?= Yii::$app->lang->t('extra', 'extra58') ?>"
                                    autocomplete="off">
                            </div>
                            <!-- <div class="row">
                                <div class="col-md-6 mb-5">
                                    <label class="fw-semibold fs-6 mb-2" for="filter-crew-type">Posisi</label>
                                    <select id="filter-crew-type" class="form-select position" data-control="select2"
                                        name="crew_type">
                                        <option value="">Semua Posisi</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-5">
                                    <label class="fw-semibold fs-6 mb-2" for="filter-crew">Kru</label>
                                    <select id="filter-crew" class="form-select employee" data-control="select2"
                                        name="crew_id">
                                        <option value="">Semua Kru</option>
                                    </select>
                                </div>
                            </div> -->
                        </div>

                        <div class="form-group text-end mt-5">
                            <!-- <button type="button" class="btn btn-light" id="btn-reset-filter">Reset</button> -->
                            <button type="submit" id="filterButton" class="btn btn-lg btn-primary">
                                <i class="fa-sharp fa-solid fa-filter"></i> Apply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <?php
            $addUrl = Url::to(['leave/create', 'leavetype' => 'leave']);
            $addButtonText = 'Add Leave Request';

            if ($leavetype === 'absent') {
                $addUrl = Url::to(['absent/createabs', 'leavetype' => 'absent']);
                $addButtonText = 'Add Absent Request';
            }
            ?>

            <!-- <a href="<?= $addUrl ?>" class="btn btn-primary add" id="add-button">
                <i class="ki-duotone ki-plus fs-2"></i>
                Add
            </a> -->

            <?php if (!$isParttime || $tab !== 'parttimepayment'): ?>
                <div class="btn-group ms-3">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-download me-2"></i>Export
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" id="btn-export-excel">
                                <i class="fas fa-file-excel me-2"></i>Export to Excel
                            </a>
                        </li>

                    </ul>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card-body pt-5">
        <div id="liveAlertPlaceholder"></div>

        <div class="btn-group mb-3" id="mass-action-buttons" style="display: none;">
            <button type="button" class="btn btn-danger" id="btn-delete-mass">
                <i class="fas fa-trash me-2"></i>Delete
            </button>
        </div>

        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="datatable">
                <thead>
                    <tr class="text-start bg-gray-100 fw-bold fs-7 text-uppercase gs-0">
                        <th class="d-none"></th>
                        <!-- <th class="text-center min-w-10px">
                            <input type="checkbox" id="select-all" class="form-check-input">
                        </th> -->
                        <th class="text-start min-w-100px"><?= Yii::$app->lang->t('leave', 'leave1') ?></th>

                        <?php if ($isParttime): ?>

                        <?php elseif ($isAbsent): ?>
                            <th class="text-start min-w-150px">Project No</th>
                            <th class="text-start min-w-100px">Nama Acara </th>
                            <th class="text-start min-w-125px"><?= Yii::$app->lang->t('leave', 'leave4') ?> </th>
                            <th class="text-start min-w-125px"><?= Yii::$app->lang->t('leave', 'leave5') ?></th>
                            <th class="text-start min-w-130px">Lokasi </th>

                        <?php else: ?>
                            <th class="text-start min-w-150px"><?= Yii::$app->lang->t('leave', 'leave11') ?> </th>
                        <?php endif; ?>
                    </tr>
                </thead>

                <tbody class="text-gray-600 fw-semibold"></tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_form_leave" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
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

    $(document).ready(function () {
        const params = new URLSearchParams(window.location.search);
        const datefilter = params.get('datefilter');
        const position = params.get('position');
        const employee = params.get('employee');

        if (datefilter !== null) $('input[name="datefilter"]').val(datefilter);
        if (position !== null) $('select[name="position"]').val(position).trigger('change');
        if (employee !== null) $('select[name="employee"]').val(employee).trigger('change');

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
            dropdownParent: $('[data-kt-menu-id="filter-menu"]'), // PENTING!
            placeholder: 'Filter by Position',
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
            dropdownParent: $('[data-kt-menu-id="filter-menu"]'), // PENTING!
            placeholder: 'Filter by Employee',
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

        let columns = [{
            data: "tranid",
            visible: false
        },
        {
            data: null,
            className: "text-center",
            render: function (data, type, row) {
                return `
                    <div class="dropdown text-left dropend">
                        <button class="btn btn-sm btn-light-primary btn-icon neo-orba" 
                                data-id="${row.tranid}"
                                title="Show Crew Details">
                            <i class="fa-solid fa-caret-right"></i>
                        </button>
                        <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                            <i class="fa-sharp fa-solid fa-list"></i>
                        </button>
                        <ul class="dropdown-menu px-2">
                            <li> 
                                <a class="dropdown-item text-hover-success export-single" 
                                   data-id="${row.leaveid}"
                                   data-leavetype="<?= $leavetype ?>"
                                   data-tab="<?= $tab ?>"
                                   style="cursor:pointer;">
                                   <i class="fas fa-file-excel"></i> Export to Excel
                                </a>
                            </li>
                        </ul>
                    </div>
                `;
            }
        }
        ];

        columns.push(
            {
                data: "tranno",
                className: "text-start",
                render: function (data, type, row) {
                    return `
                     <div class="d-flex flex-column align-items-left mx-auto my-auto w-100">
                        <span class="text text-start fw-semibold text-gray-600">${row.tranno || '-'}</span>
                        <small class="text text-start text-gray-800"> <i class="fa-solid fa-user me-2 text-gray-800"></i>
                        ${row.contact_name || '-'}</small>
                        <small class="text text-start text-gray-800"> <i class="fa-solid fa-building me-2 text-gray-800"></i>
                        ${row.jobcompany || '-'}</small>                    
                    </div>`;
                }

            },
            {
                data: "eventname",
                className: 'text-start',
                render: data => renderData(data)
            },
            {
                data: "trandate",
                className: 'text-start',
                render: data => renderData(data)
            },
            {
                data: "tranduedate",
                className: 'text-start',
                render: data => renderData(data)
            },
            {
                data: "locations",
                className: 'text-start',
                render: data => renderData(data)
            },

        );

        $(document).on('click', '#btn-export-excel', function () {
            const isProfileData = (LEAVETYPE_FILTER === 'parttime' && CURRENT_TAB === 'profiledata');

            if (isProfileData) {
                Swal.fire({
                    title: 'Export All Profile Data?',
                    html: `<div class="text-start">
                        <p class="mb-2">Akan mengexport <strong>SEMUA data personal parttime</strong></p>
                        <p class="text-muted small">Tanpa batasan tanggal</p>
                    </div>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-file-excel me-1"></i> Export Sekarang',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const url = `<?= Url::to(['/leave/exportexcel']) ?>?leavetype=${LEAVETYPE_FILTER}&tab=${CURRENT_TAB}`;

                        Swal.fire({
                            title: 'Mohon Tunggu...',
                            html: 'Sedang memproses export data',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        window.location.href = url;

                        setTimeout(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Export Berhasil!',
                                text: 'File Excel sedang diunduh',
                                timer: 2500,
                                showConfirmButton: false
                            });
                        }, 1000);
                    }
                });

            } else {
                Swal.fire({
                    title: 'Export ke Excel',
                    html: `
                    <div class="text-start">
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
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    preConfirm: () => {
                        const start = document.getElementById('export-excel-start-date').value;
                        const end = document.getElementById('export-excel-end-date').value;

                        if (!start || !end) {
                            Swal.showValidationMessage(' Pilih tanggal terlebih dahulu');
                            return false;
                        }
                        if (start > end) {
                            Swal.showValidationMessage(' Tanggal mulai tidak boleh lebih besar dari tanggal selesai');
                            return false;
                        }
                        return {
                            start,
                            end
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const url = `<?= Url::to(['/leave/exportexcel']) ?>?start_date=${result.value.start}&end_date=${result.value.end}&leavetype=${LEAVETYPE_FILTER}&tab=${CURRENT_TAB}`;

                        Swal.fire({
                            title: 'Mohon Tunggu...',
                            html: 'Sedang memproses export data',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        window.location.href = url;

                        setTimeout(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Export Berhasil!',
                                html: `<p>File Excel sedang diunduh</p>
                               <p class="small text-muted mb-0">Periode: ${result.value.start} s/d ${result.value.end}</p>`,
                                timer: 2500,
                                showConfirmButton: false
                            });
                        }, 1000);
                    }
                });
            }
        });

        let table = $("#datatable").DataTable({
            // scrollX: true,
            // scrollCollapse: true,
            // scrollY: '60vh',
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
                url: "<?= Url::to(['leave/listabs']) ?>",
                data: function (d) {
                    d.search = $('input[name="search"]').val();
                    d.leavetype = LEAVETYPE_FILTER;
                    d.tab = CURRENT_TAB;
                    d.crew_type = $('#filter-crew-type').val();
                    d.crew_id = $('#filter-crew').val();
                    d.datefilter = $('input[name="datefilter"]').val();

                }
            },
            columns: columns,
            drawCallback: function (settings) {
                addSubtableExpand();
            },

            initComplete: function () {
                this.api().columns.adjust();
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

        function formatRange(start, end) {
            return start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY');
        }

        function reloadWithRange(start, end) {
            const picker = $('#datefilter').data('daterangepicker');
            if (picker) {
                picker.setStartDate(start);
                picker.setEndDate(end);
            }
            $('#datefilter').val(formatRange(start, end));
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

        const hariIni = moment().format('DD-MM-YYYY');
        const defaultRange = `${hariIni} - ${hariIni}`;

        if (datefilter !== null && datefilter !== '') {
            $('input[name="datefilter"]').val(datefilter);
        } else {
            $('input[name="datefilter"]').val(defaultRange); // Set ke hari ini
        }

        $('#datefilter').daterangepicker({
            locale: {
                format: 'DD-MM-YYYY',
                separator: ' - ',
                applyLabel: 'Apply',
                cancelLabel: 'Clear',
            },
            startDate: datefilter ? moment(datefilter.split(' - ')[0], 'DD-MM-YYYY') : moment(),
            endDate: datefilter ? moment(datefilter.split(' - ')[1], 'DD-MM-YYYY') : moment(),
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

        $("#filterForm").submit(function (e) {
            e.preventDefault();
            $("#datatable").DataTable().ajax.reload();
        });

        $("#add-button").on("click", function (e) {
            e.preventDefault();

            let createUrl = $(this).data('url');
            let modalTitle;

            if (LEAVETYPE_FILTER === 'absent') {
                createUrl = "<?= Url::to(['/leave/createabs']) ?>?leavetype=absent";
                modalTitle = "New Absent Request";

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
                    console.error(' Ajax error:', error);
                    console.error('Response:', xhr.responseText);
                    Swal.fire('Error!', 'Gagal memuat form: ' + error, 'error');
                }
            });
        });

        $(document).on("click", ".edit", function () {
            let id = $(this).data("id");
            let updateUrl, modalTitle;

            if (LEAVETYPE_FILTER === 'absent') {
                updateUrl = "<?= Url::to(['/leave/updateabs']) ?>/" + id + "?leavetype=absent";
                modalTitle = "Edit Absent Request";
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
                    console.error(' Response:', xhr.responseText);
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
            var tranid = button.data('id'); // ID dari row utama
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
                            <i class="fas fa-history me-2"></i> Riwayat Crew di Project
                        </h6>
                        <div id="subtable-container-${tranid}">
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
                url: '<?= Url::to(['leave/crewhistoryabs']) ?>',
                type: 'GET',
                data: {
                    tranid: tranid
                },
                success: function (response) {
                    var container = $(`#subtable-container-${tranid}`);

                    if (response.success && response.data && response.data.length > 0) {
                        var tableHTML = `
                        <table class="table table-sm table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start min-w-50px"></th>
                                    <th class="text-start min-w-100px">Tahap</th>
                                    <th class="text-start min-w-150px">Tanggal Mulai</th>
                                    <th class="text-start min-w-100px">Check In</th>
                                    <th class="text-center min-w-125px">Photo Check In</th>
                                    <th class="text-start min-w-150px">Tanggal Selesai</th>
                                    <th class="text-start min-w-100px">Check Out</th>
                                    <th class="text-center min-w-125px">Photo Check Out</th>
                                    <th class="text-start min-w-125px">Nama</th>
                                    <th class="text-start min-w-100px">Kode</th>
                                    <th class="text-start min-w-100px">Posisi</th>
                                    <th class="text-start min-w-100px">Job</th>
                                    <th class="text-start min-w-100px">Fee</th>
                                    <th class="text-start min-w-100px">Dinas</th>
                                    <th class="text-start min-w-100px">Dinas Fee</th>
                                </tr>
                            </thead>
                            <tbody>`;

                        response.data.forEach(function (item) {
                            let checkInHTML = item.check_in;
                            if (item.check_in !== '-' && item.late_duration && item.late_duration !== 'null') {
                                checkInHTML += `<br><small class="text-danger fw-bold"><i class="fas fa-clock me-1"></i>+${item.late_duration}</small>`;
                            }

                            let checkOutHTML = item.check_out;
                            if (item.check_out !== '-' && item.overtime_duration && item.overtime_duration !== 'null') {
                                checkOutHTML += `<br><small class="text-danger fw-bold"><i class="fas fa-clock me-1"></i>+${item.overtime_duration}</small>`;
                            }

                            let leavedateBadge = '';
                            let leavedate = item.startdate;
                            if (item.check_in === '-' || item.check_in === null) {
                                leavedateBadge = `<span class="badge bg-danger ms-1 text-white p-2">${leavedate}</span>`;
                            } else {
                                leavedateBadge = `<span class="badge bg-success ms-1 text-white p-2">${leavedate}</span>`;
                            }

                            let leaveduedateBadge = '';
                            let leaveduedate = item.enddate;
                            if (item.check_out === '-' || item.check_out === null) {
                                leaveduedateBadge = `<span class="badge bg-danger ms-1 text-white p-2">${leaveduedate}</span>`;
                            } else {
                                leaveduedateBadge = `<span class="badge bg-success ms-1 text-white p-2">${leaveduedate}</span>`;
                            }

                            let actionMenuHTML = '';
                            if (item.leaveid && item.leaveid !== 'null' && item.leaveid !== null) {
                                actionMenuHTML = `
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item text-hover-success edit" 
                                        data-id="${item.leaveid}" style="cursor:pointer;">
                                            <i class="fas fa-edit me-2"></i> Edit
                                        </a>
                                    </li>`;
                            } else {
                                actionMenuHTML = `
                                    <li>
                                        <span class="dropdown-item text-muted disabled">
                                            <i class="fas fa-times-circle me-2"></i> Belum Absen
                                        </span>
                                    </li>`;
                            }

                            tableHTML += `
                                <tr>
                                    <td class="text-start"> 
                                        <div class="dropdown text-center dropend">
                                            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                                <i class="fa-sharp fa-solid fa-list"></i>
                                            </button>
                                            <ul class="dropdown-menu px-2">
                                                ${actionMenuHTML}
                                            </ul>
                                        </div>
                                    </td>
                                    <td class="text-start">${item.eventtypeid}</td>
                                    <td class="text-start">${leavedateBadge}</td>
                                    <td class="text-start">
                                    ${checkInHTML}</td>
                                    <td class="text-center">
                                        ${item.pict_employee ? `
                                            <img src="${item.pict_employee}" 
                                                style="width:45px; height:45px; border-radius:50%; object-fit:cover; border: 2px solid #e4e6ef;" 
                                                alt="${item.crew_name || 'Crew'}"
                                                onerror="this.onerror=null; this.src='/assets/media/svg/avatars/blank.svg';">
                                        ` : `
                                            <img src="/assets/media/svg/avatars/blank.svg" 
                                                style="width:45px; height:45px; border-radius:50%; object-fit:cover; border: 2px solid #e4e6ef;" 
                                                alt="Blank">
                                        `}
                                    </td>
                                    <td class="text-start">${leaveduedateBadge}</td>
                                    <td class="text-start">${checkOutHTML}</td>
                                    <td class="text-center">
                                        ${item.pict_employee2 ? `
                                            <img src="${item.pict_employee2}" 
                                                style="width:45px; height:45px; border-radius:50%; object-fit:cover; border: 2px solid #e4e6ef;" 
                                                alt="${item.crew_name || 'Crew'}"
                                                onerror="this.onerror=null; this.src='/assets/media/svg/avatars/blank.svg';">
                                        ` : `
                                            <img src="/assets/media/svg/avatars/blank.svg" 
                                                style="width:45px; height:45px; border-radius:50%; object-fit:cover; border: 2px solid #e4e6ef;" 
                                                alt="Blank">
                                        `}
                                    </td>
                                    <td class="text-start">${item.crew_name}</td>
                                    <td class="text-start">${item.contact_no}</td>
                                    <td class="text-start">${item.crew_type}</td>
                                    <td class="text-start">${item.job_name}</td>
                                    <td class="text-end">${item.fee}</td>
                                    <td class="text-end">${item.dinas}</td>
                                    <td class="text-end">${item.dinasfee}</td>
                                </tr>`;
                        });

                        tableHTML += `
                            </tbody>
                       
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
                    var container = $(`#subtable-container-${tranid}`);
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