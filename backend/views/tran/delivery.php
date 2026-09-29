<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
$this->title = Yii::$app->lang->t('extrasidebar', 'extrasidebar122');

?>
<div class="row g-3" style="flex-wrap: wrap;">
    <div class="col-lg-4 col-md-4 col-sm-12">
        <?php
        $form = ActiveForm::begin([
            'id' => 'tran-form',
            'method' => 'post',
            'options' => ['enctype' => 'multipart/form-data'],
            'validateOnSubmit' => false,
        ]);
        ?>

        <div class="card card-flush shadow-sm mb-5" style="max-width: 450px;">
            <div class="card-body p-6">

                <div class="d-flex align-items-center justify-content-between mb-5">
                    <span class="fw-bolder fs-6 text-uppercase text-gray-800">
                        <i class="fa-solid fa-qrcode"></i> Scanner
                    </span>
                    <div class="bg-light p-1 rounded-pill d-flex gap-1">
                        <button type="button" class="btn btn-sm btn-dark rounded-pill px-4" id="btn-mode-kamera">
                            <i class="fas fa-camera me-1"></i> Kamera
                        </button>
                    </div>
                </div>

                <div class="border border-dashed border-gray-300 bg-light rounded-4 p-5 text-center d-flex flex-column align-items-center justify-content-center mb-5"
                    style="min-height: 280px;">

                    <div id="reader" class="w-100 rounded-3 overflow-hidden" style="display: none;"></div>

                    <div id="camera-placeholder" class="py-4">
                        <h5 class="fw-bold text-gray-800 mb-1">Kamera Belum Aktif</h5>
                        <p class="text-gray-500 fs-7 mb-4">Izinkan akses kamera saat diminta</p>

                        <div class="btn btn-icon btn-white shadow-sm btn-circle mb-4"
                            style="width: 60px; height: 60px;">
                            <i class="fas fa-expand fs-2 text-gray-600"></i>
                        </div>

                        <div>
                            <div class="bg-body border p-1 rounded-pill d-inline-flex gap-1">
                                <button type="button" class="btn btn-sm btn-color-gray-600 rounded-pill px-4"
                                    id="btn-scan-terus">Scan Terus</button>
                                <button type="button" class="btn btn-sm btn-dark rounded-pill px-4"
                                    id="btn-scan-sekali">Scan Sekali</button>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-danger btn-lg w-100 rounded-pill fw-bolder py-3 mb-5"
                    id="btn-toggle-scan">
                    <i class="fas fa-camera me-2"></i> Mulai Scan
                </button>

                <div class="text-center mb-2">
                    <span class="text-gray-500 fs-7 fw-semibold">Atau Input Manual</span>
                </div>
                <div class="input-group bg-light rounded-pill p-1 border border-gray-300">
                    <span class="input-group-text bg-transparent border-0 ps-4 text-gray-500">
                        <i class="fas fa-search fs-5"></i>
                    </span>
                    <input type="text" class="form-control bg-transparent border-0 shadow-none ps-2"
                        id="barcode-scanner-input" placeholder="Input No Kontak.." autocomplete="off">
                    <button class="btn btn-white rounded-pill px-5 fw-bold shadow-sm text-gray-800" type="button"
                        id="btn-scan-barcode">
                        Scan
                    </button>
                </div>

            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

    <div class="col-lg-8 col-md-8 col-sm-12">
        <div class="card shadow-sm">
            <div class="card-header border-0 py-5 d-flex flex-column align-items-stretch gap-4">

                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 w-100">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fa-solid fa-user-clock me-2 fs-3"></i>
                        <div>
                            <h3 class="fw-bold m-0 text-gray-900">Kehadiran Hari Ini</h3>
                            <span class="text-gray-500 fs-7"><?= date('l, d F Y') ?></span>
                        </div>
                    </div>

                    <div>
                        <button type="button" class="btn btn-light-primary" data-kt-menu-trigger="click"
                            data-kt-menu-placement="bottom-end">
                            <i class="ki-duotone ki-filter fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>Filter
                        </button>

                        <div class="menu menu-sub menu-sub-dropdown w-sm-500px w-md-600px" data-kt-menu="true"
                            data-kt-menu-id="filter-menu">
                            <div class="px-7 py-5">
                                <div class="fs-5 text-gray-900 fw-bold">
                                    <?= Yii::$app->lang->t('extra', 'extra9') ?>
                                </div>
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
                                            <label class="fw-semibold fs-6 mb-2 mt-3" for="datefilter">
                                                <?= Yii::$app->lang->t('tran', 'tran_date') ?>
                                            </label>
                                            <input name="datefilter" class="form-control form-control-solid"
                                                style="cursor:pointer;" id="datefilter"
                                                placeholder="<?= Yii::$app->lang->t('extra', 'extra58') ?>"
                                                autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-group text-end mt-5">
                                        <button type="submit" id="filterButton" class="btn btn-lg btn-primary">
                                            <i class="fa-sharp fa-solid fa-filter"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 w-100">
                    <form method="get" action="index" id="search" onsubmit="return false;">
                        <div class="position-relative">
                            <span class="position-absolute top-50 start-0 translate-middle-y ms-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"
                                    fill="#a1a5b7">
                                    <path
                                        d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
                                </svg>
                            </span>
                            <input data-kt-docs-table-filter="search" type="text" name="search"
                                class="form-control form-control-solid w-200px ps-10" placeholder="Search" />

                            <span class="position-absolute top-50 end-0 translate-middle-y me-3 d-none"
                                id="clear-search">
                                <i class="ki-duotone ki-cross fs-2 text-gray-500 cursor-pointer"
                                    style="opacity: 0.5;"></i>
                            </span>
                        </div>
                    </form>

                    <ul class="nav nav-pills gap-2" role="tablist" id="contact-type-tabs">
                        <li class="nav-item">
                            <button class="nav-link active btn btn-sm bg-black text-white fw-bold px-4 rounded-pill"
                                data-contacttype="customer" type="button">
                                Member <span class="badge badge-sm badge-circle bg-white text-dark ms-2"
                                    id="count-member">0</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                class="nav-link btn btn-sm btn-outline btn-outline-secondary text-gray-700 fw-bold px-4 rounded-pill"
                                data-contacttype="employee" type="button">
                                Coach <span class="badge badge-sm badge-circle bg-light-dark text-dark ms-2"
                                    id="count-coach">0</span>
                            </button>
                        </li>
                    </ul>

                    <input type="hidden" id="filter-contacttype" value="customer">
                </div>
            </div>

            <div class="card-body pt-0">
                <div id="liveAlertPlaceholder"></div>

                <div class="btn-group mb-3" id="mass-action-buttons" style="display: none;">
                    <button type="button" class="btn btn-danger" id="btn-delete-mass">
                        <i class="fas fa-trash me-2"></i>Delete
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
                            <th class="text-start min-w-150px">Nama</th>
                            <th class="text-start min-w-100px">Jam
                            </th>
                            <th class="text-start min-w-100px">Berlaku s/d</th>
                            <th class="text-start min-w-150px">Kunjungan</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 fw-semibold text-start"></tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="modalCheckinUlang" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered max-w-400px">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bolder text-gray-800">Konfirmasi Check-in Ulang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div class="d-flex align-items-center bg-light rounded-4 p-4 mb-4">
                    <div class="symbol symbol-50px me-3">
                        <span class="symbol-label bg-light-success text-success fw-bolder fs-2"
                            id="checkin-avatar">-</span>
                    </div>
                    <div>
                        <h6 class="fw-bolder text-gray-800 mb-0" id="checkin-member-name">-</h6>
                        <span class="text-gray-500 fs-7" id="checkin-member-info">-</span><br>
                        <span class="text-gray-500 fs-7" id="checkin-member-expired">-</span>
                    </div>
                </div>

                <div class="d-flex align-items-center text-warning fw-bold fs-7 mb-4">
                    <i class="fas fa-exclamation-triangle text-warning me-2 fs-6"></i>
                    <span>Status: <strong class="ms-2" id="checkin-status-text">Member sudah check-in hari
                            ini</strong></span>
                </div>

                <div class="d-flex justify-content-between gap-2 mt-5">
                    <button type="button"
                        class="btn btn-sm btn-outline btn-outline-gray-400 text-gray-700 rounded-pill px-3 py-2 flex-grow-1"
                        data-action="abaikan" id="btn-checkin-abaikan">
                        Abaikan
                    </button>
                    <button type="button"
                        class="btn btn-sm btn-outline btn-outline-gray-400 text-gray-700 rounded-pill px-3 py-2 flex-grow-1"
                        data-action="no_deduct" id="btn-checkin-jangan-kurangi">
                        Jangan Kurangi
                    </button>
                    <button type="button" class="btn btn-sm btn-dark rounded-pill px-3 py-2 flex-grow-1"
                        data-action="deduct" id="btn-checkin-kurangi">
                        Kurangi Kuota
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script type="text/javascript">
    let html5QrCode = null;
    let isScanning = false;
    let scanMode = 'sekali';

    function startCameraScanner() {
        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("reader");
        }

        const config = {
            fps: 10,
            qrbox: { width: 220, height: 220 }
        };

        $('#camera-placeholder').hide();
        $('#reader').show();

        html5QrCode.start(
            { facingMode: "environment" },
            config,
            onScanSuccess
        ).then(() => {
            isScanning = true;
            $('#btn-toggle-scan')
                .removeClass('btn-danger')
                .addClass('btn-secondary text-gray-800')
                .html('<i class="fas fa-stop me-2"></i> Stop Scan');
        }).catch(err => {
            stopCameraScanner();
            Swal.fire({
                icon: 'error',
                title: 'Kamera Gagal Dibuka',
                text: 'Izin kamera ditolak atau perangkat tidak mendukung HTTPS/Kamera.'
            });
        });
    }

    function stopCameraScanner() {
        if (html5QrCode && isScanning) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
                resetScanUI();
            }).catch(err => console.error(err));
        } else {
            resetScanUI();
        }
    }

    function resetScanUI() {
        isScanning = false;
        $('#reader').hide();
        $('#camera-placeholder').show();
        $('#btn-toggle-scan')
            .removeClass('btn-secondary text-gray-800')
            .addClass('btn-danger')
            .html('<i class="fas fa-camera me-2"></i> Mulai Scan');
    }

    function onScanSuccess(decodedText, decodedResult) {

        $('#barcode-scanner-input').val(decodedText);

        if (scanMode === 'sekali') {
            stopCameraScanner();
        }

        searchProductByBarcode(decodedText);
    }

    $(document).ready(function () {
        setForm();
        setupBarcodeScanner();

        $('#btn-toggle-scan').on('click', function () {
            if (isScanning) {
                stopCameraScanner();
            } else {
                startCameraScanner();
            }
        });

        $('#btn-scan-sekali').on('click', function () {
            scanMode = 'sekali';
            $(this).removeClass('btn-color-gray-600').addClass('btn-dark');
            $('#btn-scan-terus').removeClass('btn-dark').addClass('btn-color-gray-600');
        });

        $('#btn-scan-terus').on('click', function () {
            scanMode = 'terus';
            $(this).removeClass('btn-color-gray-600').addClass('btn-dark');
            $('#btn-scan-sekali').removeClass('btn-dark').addClass('btn-color-gray-600');
        });

        const params = new URLSearchParams(window.location.search);
        const search = params.get('search');
        const datefilter = params.get('datefilter');

        if (search !== null) $('input[name="search"]').val(search);
        if (datefilter !== null) $('input[name="datefilter"]').val(datefilter);


        var translate = <?= json_encode(Yii::$app->lang->t('extra', 'extra11')) ?>;
        var translate1 = <?= json_encode(Yii::$app->lang->t('extra', 'extra12')) ?>;
        var translate2 = <?= json_encode(Yii::$app->lang->t('extra', 'extra13')) ?>;
        $("#datatable").DataTable({
            scrollX: false,
            autoWidth: false,
            processing: false,
            serverSide: false,
            lengthMenu: [100],
            pageLength: 100,
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
                url: "<?= Url::to(['tran/listbarcode']) ?>",
                data: function (d) {
                    d.search = $('input[name="search"]').val();
                    d.datefilter = $('input[name="datefilter"]').val(); // Tambahkan ini
                    d.contacttype = $('#filter-contacttype').val();
                },
                dataSrc: function (json) {
                    $('#count-member').text(json.count_member || 0);
                    $('#count-coach').text(json.count_coach || 0);
                    return json.data || [];
                },
                complete: function () {
                    $('.table-loading-overlay').remove();
                }
            },
            columns: [{
                data: "primaryid",
                visible: false
            },
            {
                data: null,
                className: "text-center",
                orderable: false,
                render: function (data, type, row) {
                    return `
                    <div class="form-check form-check-custom form-check-solid form-check-sm px-3">
                         <input type="checkbox" class="form-check-input row-checkbox select-checkbox" value="${row.primaryid}">
                    </div>
                     `;
                }
            },
            {
                data: "contact_name",
                className: "text-start",
                render: function (data, type, row) {
                    const name = data || '-';
                    const no = row.contact_no ? `<br><small class="text-muted">${row.contact_no}</small>` : '';
                    return `<div><span class="fw-bold">${name}</span>${no}</div>`;
                }
            },
            {
                data: "trandate",
                className: "text-start",
                render: function (data) {
                    if (!data) return '-';
                    const date = new Date(data);
                    const hours = String(date.getHours()).padStart(2, '0');
                    const minutes = String(date.getMinutes()).padStart(2, '0');
                    return `${hours}.${minutes} WIB`;
                }
            },
            {
                data: "jobend",
                className: "text-start",
                render: function (data) {
                    if (!data) return '-';
                    const date = new Date(data);
                    const day = String(date.getDate()).padStart(2, '0');
                    const monthShort = date.toLocaleString('id-ID', { month: 'short' });
                    const year = date.getFullYear();
                    return `${day} ${monthShort} ${year}`;
                }
            },
            {
                data: "total",
                className: "text-center",
                render: function (data) {
                    return `<span class="fw-bold text-gray-600">${data || 0}x</span>`;
                }
            },
            ],
            initComplete: function () {
                $('#datatable tbody').on('click', 'tr', function (e) {
                    if (
                        $(e.target).closest('.select-checkbox').length ||
                        $(e.target).closest('.dropdown').length ||
                        $(e.target).closest('button').length ||
                        $(e.target).closest('.dropdown-menu').length ||
                        $(e.target).closest('a').length ||
                        $(e.target).is('button') ||
                        $(e.target).is('input') ||
                        $(e.target).is('a')
                    ) {
                        return;
                    }
                });
            }
        });

        $('#contact-type-tabs button').on('click', function () {
            const type = $(this).data('contacttype');

            $('#filter-contacttype').val(type);
            activateTab(type);

            $("#datatable").DataTable().ajax.reload();
        });

        function activateTab(type) {
            $('#contact-type-tabs button').each(function () {
                if ($(this).data('contacttype') === type) {
                    $(this).addClass('active bg-black text-white').removeClass('btn-outline btn-outline-secondary text-gray-700'); $(this).find('.badge').addClass('bg-white text-dark').removeClass('bg-light-dark text-dark');
                } else {
                    $(this).removeClass('active bg-black text-white').addClass('btn-outline btn-outline-secondary text-gray-700'); $(this).find('.badge').removeClass('bg-white text-dark').addClass('bg-light-dark text-dark');
                }
            });
        }

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
            let filterMenu = document.querySelector("[data-kt-menu-id='filter-menu']");
            if (filterMenu) {
                KTMenu.getInstance(filterMenu).hide();
            }
        });
    });

    function setForm() {
        var form = $('#tran-form');
        form.on('submit', function (e) {
            e.preventDefault();
            $('#btn-scan-barcode').prop('disabled', true);

            var formData = form.serializeArray();
            var variantData = formData.filter(item => item.name.includes('variants') || item.name.includes('Tranvariant'));

            $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: form.serialize(),
                success: function (data) {
                    $('#btn-scan-barcode').prop('disabled', false);
                    if (data['success']) {
                        Swal.fire({
                            icon: "success",
                            title: "Successful",
                            html: data['pesan']
                        });
                        form[0].reset();
                        $('#datatable').DataTable().ajax.reload();
                    } else {
                        Swal.fire({
                            icon: "warning",
                            title: "Warning",
                            html: data['pesan']
                        });
                    }
                },
                error: function (xhr, status, error) {
                    $('#btn-scan-barcode').prop('disabled', false);
                    Swal.fire({
                        icon: "error",
                        title: "Failed",
                        html: "Something went wrong!",
                    });
                }
            });
        });

    }

    function setupBarcodeScanner() {
        const barcodeInput = $('#barcode-scanner-input');

        function prosesScanBarcode(barcodeValue) {

            searchProductByBarcode(barcodeValue);
        }

        $('#btn-scan-barcode').on('click', function () {
            prosesScanBarcode(barcodeInput.val());
        });

        barcodeInput.on('keypress', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                prosesScanBarcode($(this).val());
            }
        });

        setTimeout(function () {
            barcodeInput.focus();
        }, 500);
    }

    function setBarcodeInputState(enabled) {
        $('#barcode-scanner-input').prop('disabled', !enabled);
        $('#btn-scan-barcode').prop('disabled', !enabled);
        if (enabled) {
            $('#barcode-scanner-input').focus();
        }
    }

    function searchProductByBarcode(barcode) {
        barcode = (barcode || '').trim();

        if (!barcode) {
            Swal.fire({
                icon: 'warning',
                title: 'Barcode Kosong',
                text: 'Silakan masukkan nomor kontak / barcode'
            });
            $('#barcode-scanner-input').focus().select();
            return;
        }

        if (typeof setBarcodeInputState === "function") setBarcodeInputState(false);

        $.ajax({
            url: "<?= Url::to(['tran/searchproductbybarcode']) ?>",
            type: "GET",
            data: { barcode: barcode },
            timeout: 10000,
            success: function (response) {
                if (response.success) {
                    $('#barcode-scanner-input').val('');
                    const contact = response.product; // Objek data kontak dari server

                    fillContactToForm(contact, barcode);

                    if (typeof scanMode !== 'undefined' && scanMode === 'sekali') {
                        Swal.fire({
                            title: 'Menyimpan...',
                            text: 'Sedang memproses ' + barcode,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: function () {
                                Swal.showLoading();
                            }
                        });
                    }

                    submitForm(function () {
                        if (typeof setBarcodeInputState === "function") setBarcodeInputState(true);
                    });
                } else {
                    if (typeof setBarcodeInputState === "function") setBarcodeInputState(true);
                    $('#barcode-scanner-input').focus().select();
                    Swal.fire({
                        icon: 'error',
                        title: 'Kontak Tidak Ditemukan',
                        text: response.message || ('Nomor / Barcode ' + barcode + ' tidak ditemukan')
                    });
                }
            },
            error: function (xhr, status, error) {
                if (typeof setBarcodeInputState === "function") setBarcodeInputState(true);
                $('#barcode-scanner-input').focus().select();

                let msg = 'Terjadi kesalahan saat mencari kontak';
                if (status === 'timeout') {
                    msg = 'Request timeout. Periksa koneksi jaringan.';
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: msg
                });
            }
        });
    }

    function fillContactToForm(contact, barcode) {
        if ($('#tran-form input[name="Tran[contact_id]"]').length === 0) {
            $('#tran-form').append('<input type="hidden" name="Tran[contact_id]" value="' + (contact.contact_id || '') + '">');
        } else {
            $('#tran-form input[name="Tran[contact_id]"]').val(contact.contact_id || '');
        }

        if ($('#tran-form input[name="Tran[barcode]"]').length === 0) {
            $('#tran-form').append('<input type="hidden" name="Tran[barcode]" value="' + barcode + '">');
        } else {
            $('#tran-form input[name="Tran[barcode]"]').val(barcode);
        }
    }

    function submitForm(onComplete, forceOption = null) {
        let formData = $('#tran-form').serializeArray();

        if (forceOption) {
            formData.push({ name: 'force_option', value: forceOption });
        }

        $.ajax({
            url: "<?= Url::to(['tran/createitem']) ?>",
            type: "POST",
            data: $.param(formData),
            timeout: 15000,
            success: function (response) {
                Swal.close();

                if (!response.success && response.already_checked_in) {
                    const c = response.contact;

                    $('#checkin-avatar').text((c.contact_name || 'M').charAt(0).toUpperCase());
                    $('#checkin-member-name').text(c.contact_name || '-');
                    $('#checkin-member-info').text(`${c.contact_no || '-'} · ${c.package_info || '-'}`);
                    $('#checkin-member-expired').text(c.expired_info || '-');

                    $('#modalCheckinUlang').modal('show');

                    $('#btn-checkin-abaikan').off('click').on('click', function () {
                        $('#modalCheckinUlang').modal('hide');
                        if (typeof onComplete === 'function') onComplete();
                    });

                    $('#btn-checkin-jangan-kurangi').off('click').on('click', function () {
                        $('#modalCheckinUlang').modal('hide');
                        submitForm(onComplete, 'no_deduct');
                    });

                    $('#btn-checkin-kurangi').off('click').on('click', function () {
                        $('#modalCheckinUlang').modal('hide');
                        submitForm(onComplete, 'deduct');
                    });

                    return;
                }

                if (response.success) {
                    let timerTime = (typeof scanMode !== 'undefined' && scanMode === 'terus') ? 1200 : 1500;

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        html: response.pesan,
                        showConfirmButton: (typeof scanMode !== 'undefined' && scanMode === 'sekali'),
                        timer: timerTime
                    });

                    if ($.fn.DataTable.isDataTable('#datatable')) {
                        $('#datatable').DataTable().ajax.reload(null, false);
                    }

                    $('#barcode-scanner-input').val('').focus();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyimpan',
                        text: response.pesan
                    });
                    $('#barcode-scanner-input').focus().select();
                }

                if (typeof onComplete === 'function') onComplete();
            },
            error: function (xhr, status, error) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Server',
                    text: 'Terjadi kesalahan saat menyimpan data.'
                });
                if (typeof onComplete === 'function') onComplete();
            }
        });
    }

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
            }, 300);
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

    var deletemessage5 = "<?= Yii::$app->lang->t('back_home', 'chat53') ?>";
    var deletemessage6 = "<?= Yii::$app->lang->t('extra', 'extra65') ?>";
    var deletemessage7 = "<?= Yii::$app->lang->t('extra', 'extra66') ?>";

    function performMassAction(action, statusCode) {
        let selectedIds = $(".select-checkbox:checked").map(function () {
            return $(this).val();
        }).get();

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

</script>