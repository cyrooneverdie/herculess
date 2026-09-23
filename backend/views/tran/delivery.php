<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

?>
<div class="row g-3" style="flex-wrap: wrap;">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <?php
        $form = ActiveForm::begin([
            'id' => 'tran-form',
            'method' => 'post',
            'options' => [
                'enctype' => 'multipart/form-data',
            ],
            'validateOnSubmit' => false,
        ]);
        $this->title = Yii::$app->lang->t('extrasidebar', 'extrasidebar122');
        ?>

        <div class="card shadow-sm mb-2">
            <div class="card-body py-4 px-4">
                <div class="row">
                    <!-- <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                        <?= $form->field($modelvariants, 'type')->dropDownList(
                            [
                                '1' => 'Stock In',
                                '2' => 'Stock Out',
                                '3' => 'Return Stock',
                            ],
                            [
                                'class' => 'form-select type-select',
                                'data-control' => 'select2',
                                'prompt' => 'Pilih Tipe...',
                            ]
                        )->label(false); ?>
                    </div>

                    <div class="col-lg-8 col-md-6 col-sm-12 mb-4">
                        <?= $form->field($modelvariants, 'refid')->dropDownList(
                            $modelvariants->refid && $modelvariants->ref ? [$modelvariants->ref->tranid => $modelvariants->ref->tranno] : [],
                            [
                                'class' => 'form-select refid-select',
                                'data-control' => 'select2',
                                'data-module' => ($modelvariants->type == '0') ? 'purchase' : 'sales',
                                'data-type' => ($modelvariants->type == '0') ? 'delivery' : ($modelvariants->type == '1' ? 'order' : ($modelvariants->type == '2' ? 'return' : '')),
                                'data-target' => ($modelvariants->type == '0') ? 'purchase/delivery' : ($modelvariants->type == '1' ? 'sales/delivery' : ($modelvariants->type == '2' ? 'sales/return' : '')),
                            ]
                        )->label(false); ?>
                    </div> -->

                    <div class="col-12 mb-4">
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <input type="text" class="form-control" id="barcode-scanner-input"
                                placeholder="Scan barcode di sini atau ketik kode barcode..." autocomplete="off">

                            <div class="d-flex gap-2">
                                <button class="btn btn-light-primary w-100 text-nowrap" type="button"
                                    id="btn-scan-barcode">
                                    <i class="fas fa-barcode"></i> Scan
                                </button>
                                <button class="btn btn-light-success w-100 text-nowrap" type="button"
                                    id="btn-open-camera">
                                    <i class="fas fa-camera"></i> Kamera
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="card shadow-sm">
            <div class="card-header border-0">
                <div class="card-title">
                    <div class="d-flex align-items-left position-relative my-2">
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
                                    class="form-control form-control-solid w-250px ps-10" placeholder="Search" />

                                <span class="position-absolute top-50 end-0 translate-middle-y me-3 d-none"
                                    id="clear-search">
                                    <i class="ki-duotone ki-cross fs-2 text-gray-500 cursor-pointer"
                                        style="opacity: 0.5;"></i>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card-toolbar d-flex gap-5">
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
                                            <button type="button" class="btn btn-sm btn-light-primary" id="btnThisYear">
                                                <?= Yii::$app->lang->t('extra', 'extra96') ?>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light-primary"
                                                id="btnThisMonth">
                                                <?= Yii::$app->lang->t('extra', 'extra97') ?>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light-primary" id="btnThisWeek">
                                                <?= Yii::$app->lang->t('extra', 'extra98') ?>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light-primary" id="btnLastYear">
                                                <?= Yii::$app->lang->t('extra', 'extra99') ?>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light-primary"
                                                id="btnLastMonth">
                                                <?= Yii::$app->lang->t('extra', 'extra100') ?>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light-primary" id="btnLastWeek">
                                                <?= Yii::$app->lang->t('extra', 'extra101') ?>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="md-10 mb-2">
                                        <label class="fw-semibold fs-6 mb-2 mt-3" for="dateFilter">
                                            <?= Yii::$app->lang->t('tran', 'tran_date') ?>
                                        </label>
                                        <input name="datefilter" class="form-control form-control-solid"
                                            style="cursor:pointer;" id="datefilter"
                                            placeholder="<?= Yii::$app->lang->t('extra', 'extra58') ?>"
                                            autocomplete="off">
                                    </div>
                                </div>

                                <div class="form-group text-end mt-5">
                                    <button type="submit" id="filterButton" class="btn btn-lg btn-primary"><i
                                            class="fa-sharp fa-solid fa-filter"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body pt-5">
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

                            <th class="text-start min-w-100px">
                                <?= Yii::$app->lang->t('cashbackend', 'cashbackend16') ?>
                            </th>
                            <th class="text-start min-w-150px">
                                <?= Yii::$app->lang->t('variant_table', 'barcode') ?>
                            </th>
                            <th class="text-start min-w-150px">
                                <?= Yii::$app->lang->t('extrasidebar', 'extrasidebar2') ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 fw-semibold text-start">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cameraModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Barcode/QR</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" id="btn-close-camera"></button>
            </div>
            <div class="modal-body text-center">
                <div id="reader" style="width: 100%;"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script type="text/javascript">
    let html5QrCode = null;

    function startCameraScanner() {
        html5QrCode = new Html5Qrcode("reader");

        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 }
        };

        html5QrCode.start(
            { facingMode: "environment" },
            config,
            onScanSuccess
        ).catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Kamera Gagal Dibuka',
                text: 'Izin kamera ditolak atau perangkat tidak mendukung HTTPS/Kamera.'
            });
            $('#cameraModal').modal('hide');
        });
    }

    function stopCameraScanner() {
        if (html5QrCode && html5QrCode.isScanning) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
            }).catch(err => console.error(err));
        }
    }

    function onScanSuccess(decodedText, decodedResult) {
        let type = $("select[name='Tranvariants[type]']").val();
        let reftype2 = $('.refid-select').attr('data-reftype2') || '';

        stopCameraScanner();
        $('#cameraModal').modal('hide');
        $('#barcode-scanner-input').val(decodedText);

        if (type === '1' && reftype2 === 'purchase/request') {
            searchBarcodeIn(decodedText);
        } else {
            searchProductByBarcode(decodedText);
        }
    }

    $(document).ready(function () {
        setForm();
        setupBarcodeScanner();
        $('.repeat-parent-btn').hide();

        $('#btn-open-camera').on('click', function () {
            $('#cameraModal').modal('show');
            startCameraScanner();
        });

        $('#cameraModal').on('hidden.bs.modal', function () {
            stopCameraScanner();
        });

        const params = new URLSearchParams(window.location.search);
        const search = params.get('search');
        const datefilter = params.get('datefilter');
        const status = params.get('status');

        if (search !== null) $('input[name="search"]').val(search);
        if (datefilter !== null) $('input[name="datefilter"]').val(datefilter);
        if (status !== null) $('select[name="status"]').val(status).trigger('change');

        $('.refid-select').select2({
            ajax: {
                url: "<?= Url::to(['tran/select']) ?>",
                type: "POST",
                dataType: "json",
                data: function (params) {
                    const el = $('.refid-select');
                    const selectedType = $('.type-select').val();

                    return {
                        search: params.term || '',
                        q: params.term,
                        page: params.page,
                        module: (selectedType == '2') ? 'sales' : 'stock',
                        type: (selectedType == '1') ? 'in' : (selectedType == '2' ? 'item' : (selectedType == '3' ? 'return' : '')),
                        target: (selectedType == '1') ? 'stock/in' : (selectedType == '2' ? 'sales/item' : (selectedType == '3' ? 'stock/return' : ''))
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    const currentType = $('.type-select').val();

                    const items = data.items.map(function (item) {
                        item.active_type = currentType;
                        return item;
                    })
                    return {
                        results: items,
                        pagination: {
                            more: (params.page * 100) < data.totalcount
                        }
                    };
                },
                cache: false
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            templateSelection: function (param) {
                return param.text || "Choose Reference";
            },
            templateResult: function (param) {
                if (!param.id) {
                    return param.text;
                }
                if (param.loading) return param.text;

                const selectedType = param.active_type;
                const displayName = (selectedType == '1' && param.reftype2 == 'purchase/request') ? param.contactpd : param.ref_name;
                const displayCompany = (selectedType == '1' && param.reftype2 == 'purchase/request') ? param.companypd : param.ref_company;
                const displayReftype = (selectedType == '1' && param.reftype2 == 'purchase/request') ? 'Stock In PO' : 'Stock In Penarikan';

                let displayReftypeBadge = '';

                if (selectedType == '1') {
                    displayReftypeBadge = `
                        <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning" style="font-size:11px;">
                            <i class="fa fa-tag me-1 text-warning"></i>${displayReftype}
                            </span>
                        `;
                }

                const $container = $(`
                    <div class="d-flex flex-column flex-grow-1 overflow-hidden">
                        <span class="fw-semibold text-dark text-truncate" style="font-size:13px;">${param.text}</span>
                        <div class="d-flex flex-wrap gap-1 mt-1">
                          ${displayReftype ? `
                           ${displayReftypeBadge}
                        ` : ''}
                            ${displayName ? `
                            <span class="badge rounded-pill bg-info bg-opacity-10 text-info" style="font-size:11px;">
                                <i class="fa fa-user me-1 text-info"></i>${displayName}
                            </span>
                        ` : ''}
                            ${displayCompany ? `
                            <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary" style="font-size:11px;">
                                <i class="fa fa-building me-1 text-primary"></i>${displayCompany}
                            </span>
                        ` : ''}
                          
                        ${param.trandate ? `
                            <span class="badge rounded-pill bg-success bg-opacity-10 text-success" style="font-size:11px;">
                                <i class="fa fa-calendar me-1 text-success"></i>${param.trandate}
                            </span>
                        ` : ''}
                        </div>
                    </div>
                `);

                return $container;
            },
            placeholder: "Choose Reference",
            allowClear: true,
            width: '100%'
        }).on('select2:select', function (e) {
            const data = e.params.data;
            const reftype2 = data.reftype2 || '';
            $(this).attr('data-reftype2', reftype2);

            generateDeli();
            $('#datatable').DataTable().ajax.reload();
            $('#barcode-scanner-input').val('').focus();

        }).on('select2:clear', function (e) {
            $(this).attr('data-reftype2', '');

            $('#datatable').DataTable().ajax.reload();
            $('.detail-rows').empty();
            $('.type-select').val(null).trigger('change');
        });

        $(document).on('change', "select.refid-select", function (e) {
            const refid = $(this).val();
            if (refid) {
                $('#datatable').DataTable().ajax.reload();
                $('.detail-rows').empty();
            }
        });

        $(document).on('change', "select.type-select", function (e) {
            const type = $(this).val();
            if (type) {
                $('#datatable').DataTable().ajax.reload();
                $('.detail-rows').empty();
                $('.refid-select').val(null).trigger('change');

                if (type === '2') {
                    $('.btn-cek-container').removeClass('d-none').show();
                    $('.th-peak-container').removeClass('d-none').show();
                    $('.btn-peak-container').removeClass('d-none').show();
                } else {
                    $('.btn-cek-container').addClass('d-none').hide();
                    $('.th-peak-container').addClass('d-none').hide();
                    $('.btn-peak-container').addClass('d-none').hide();
                }
            }
        });

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
                    const search = $('input[name="search"]').val();
                    const datefilter = $('input[name="datefilter"]').val();
                    const refid = $('.refid-select').val();
                    const selectedType = $('.type-select').val();
                    const reftype2 = $('.refid-select').attr('data-reftype2') || '';

                    d.search = search;
                    d.datefilter = datefilter;
                    d.id = refid;
                    d.reftype2 = reftype2;
                    d.module = (selectedType == '2') ? 'sales' : 'stock';
                    d.type = (selectedType == '1') ? 'in' : (selectedType == '2' ? 'item' : (selectedType == '3' ? 'return' : ''));
                    d.target = (selectedType == '1') ? 'stock/in' : (selectedType == '2' ? 'sales/item' : (selectedType == '3' ? 'stock/return' : ''));

                    const params = new URLSearchParams();
                    if (search) params.set('search', search);
                    if (datefilter) params.set('datefilter', datefilter);

                    const newUrl = window.location.pathname + '?' + params.toString();
                },
                dataSrc: function (json) {
                    const refid = $('.refid-select').val();
                    if (!refid) {
                        return [];
                    }
                    return json.data;
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
                data: "trandate",
                className: "text-start",
                render: function (data) {
                    if (!data) return '-';
                    const date = new Date(data);
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();
                    const hours = String(date.getHours()).padStart(2, '0');
                    const minutes = String(date.getMinutes()).padStart(2, '0');
                    return `${day}-${month}-${year} ${hours}:${minutes}`;
                }
            },
            {
                data: "barcode",
                className: "text-start",
                render: function (data) {
                    return `<span class="text text-start">${data || '-'}</span>`;
                }
            },
            {
                data: "productname",
                className: "text-start",
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

        $(document).on('click', '.btn-cek', function () {
            const refid = $("select[name='Tranvariants[refid]']").val();

            if (!refid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Delivery',
                    text: 'Silakan pilih nomor delivery terlebih dahulu.'
                });
                return;
            }

            $.ajax({
                url: "<?= Url::to(['tran/cekscan']) ?>",
                type: "GET",
                data: { refid: refid },
                success: function (response) {
                    if (!response.success) {
                        Swal.fire({ icon: 'error', title: 'Error', text: response.pesan });
                        return;
                    }

                    if (response.finished) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Semua Sudah Di-Scan',
                            html: '<span class="text-success fw-bold">Seluruh item pada delivery ini telah discan.</span>'
                                + '<br><br>' + '<span class="text-muted">Jika Barang Sudah Kembali, Silahkan Input Barang Kembali Pada Menu Return Stock</span></div>'
                        });
                        return;
                    }

                    const rows = response.items.map(item => `
                        <tr>
                            <td class="text-start">${item.productname}</td>
                            <td class="text-center">${item.total_qty}</td>
                            <td class="text-center text-success">${item.total_scan}</td>
                            <td class="text-center text-danger fw-bold">${item.sisa}</td>
                        </tr>
                    `).join('');

                    Swal.fire({
                        icon: 'warning',
                        title: 'Item Belum Di Scan',
                        html: `
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mt-2">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-start">Produk</th>
                                        <th>Qty</th>
                                        <th>Sudah Scan</th>
                                        <th>Sisa</th>
                                    </tr>
                                </thead>
                                <tbody>${rows}</tbody>
                            </table>
                        </div>
                    `,
                        width: 600,
                        confirmButtonText: 'Tutup'
                    });
                },
                error: function () {
                    Swal.fire({ icon: 'error', title: 'Kesalahan Server', text: 'Gagal mengambil data scan.' });
                }
            });
        });

        $(document).on('click', '.btn-peak', function () {
            const $row = $(this).closest('tr');
            const productid = $row.find('.product-productid').val();
            // console.log("Selected Product ID:", productid); // Debug log to check the selected product ID

            if (!productid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Produk Belum Dipilih',
                    text: 'Silakan pilih produk terlebih dahulu.'
                });
                return;
            }

            $.ajax({
                url: "<?= Url::to(['tran/cekbarcode']) ?>",
                type: "GET",
                data: { productid: productid },
                success: function (response) {
                    if (!response.success) {
                        Swal.fire({ icon: 'error', title: 'Error', text: response.pesan });
                        return;
                    }

                    if (!response.items || response.items.length === 0) {
                        Swal.fire({ icon: 'info', title: 'Kosong', text: 'Tidak ada detail varian untuk produk ini.' });
                        return;
                    }

                    const rows = response.items.map(item => `
                        <tr>
                            <td class="text-start">${item.asetno}</td>
                            <td class="text-start">${item.barcode}</td>
                            <td class="text-start">${item.locationid}</td>
                            <td class="text-start">${item.condition}</td>
                        </tr>
                    `).join('');

                    const titleProduk = response.items[0].productname;

                    Swal.fire({
                        title: `${titleProduk}`,
                        html: `
                        <div class="table-responsive">
                            <table class="table table-xl table-bordered mt-2">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-start">No Aset</th>
                                        <th class="text-start"><?= Yii::$app->lang->t('variant_table', 'barcode') ?></th>
                                        <th class="text-start"><?= Yii::$app->lang->t('variant_table', 'lokasi') ?></th>
                                        <th class="text-start"><?= Yii::$app->lang->t('variant_table', 'kondisi') ?></th>
                                    </tr>
                                </thead>
                                <tbody>${rows}</tbody>
                            </table>
                        </div>
                    `,
                        width: 600,
                        confirmButtonText: 'Tutup'
                    });
                },
                error: function () {
                    Swal.fire({ icon: 'error', title: 'Kesalahan Server', text: 'Gagal mengambil data item.' });
                }
            });
        });
    });

    function setFunction() {
        $(".product-select").select2({
            ajax: {
                url: "<?= Url::to(['product/search']) ?>",
                type: "GET",
                dataType: "json",
                data: function (params) {
                    let limit = 10;
                    let page = params.page || 1;

                    return {
                        q: params.term,
                        page: page,
                        limit: limit,
                        offset: (page - 1) * limit,
                        module: "<?= $module ?? 'purchase' ?>"
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.data.map(function (item) {
                            return {
                                id: item.value,
                                text: item.productname,
                                productname: item.productname,
                                productcode: item.productcode,
                                productpict: item.productpict
                            };
                        }),
                        pagination: {
                            more: data.hasMore
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
                    return "Choose Product";
                }
                return param.text;
            },
            templateResult: function (param) {
                if (!param.id) return param.text;
                if (param.loading) return param.text;

                let img = param.productpict ?
                    `/uploads/produk/${param.productpict}` :
                    `/uploads/produk/default.png`;

                return $(`
                <div class="d-flex align-items-center">
                    <img class="w-50px me-2"
                         src="${img}"
                         onerror="this.style.display='none'">

                    <div class="d-flex flex-column">
                        <strong>${param.text}</strong>
                        <span class="text-muted">${param.productcode}</span>
                    </div>
                </div>
            `);
            },
            placeholder: "Choose",
            allowClear: true,
            width: '100%'
        })
            .on('select2:open', function () {
                let $dropdown = $('.select2-dropdown');
                $dropdown.find('.add-new-prd-btn').remove();
            }).on('select2:unselect', function (e) {
                let row = $(this).closest('tr');
                row.find(".product-buttons").hide();
                row.find('.product-quantity').val(0);
            }).on('change', function (e) {
                const productId = $(this).val();
                const row = $(this).closest('tr');

                if (productId) {
                    row.attr('data-product-id', productId);
                    row.find(".product-buttons").show();
                } else {
                    row.removeAttr('data-product-id');
                    row.find(".product-buttons").hide();
                }

                row.find('.product-quantity').val(0);
                row.find('.product-sent').val(0);
                row.find('.product-remain').val(0);
                row.find('.product-receive').val(0);
                row.find('.product-treceived').val(0);
                row.find('.product-send').val(0);
            }).on('select2:unselect', function (e) {
            });
    }

    function repeatNested(selection, inner) {
        $(selection).repeater({
            initEmpty: true,
            repeaters: [{
                selector: inner,
                show: function () {
                    $(this).slideDown();
                    setFunction();
                },

                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            }],

            show: function () {
                $(this).slideDown();
                setFunction();
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });
    }

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

        repeatNested('#trandetail_repeater', '.inner-repeater');
        setFunction();
        initMasking();
    }

    function setupBarcodeScanner() {
        const barcodeInput = $('#barcode-scanner-input');

        function prosesScanBarcode(barcodeValue) {
            let type = $("select[name='Tranvariants[type]']").val();
            let reftype2 = $('.refid-select').attr('data-reftype2') || '';

            if (type === '1' && reftype2 === 'sales/order') {
                searchProductByBarcode(barcodeValue);
            } else if (type === '1' && reftype2 === 'purchase/request') {
                searchBarcodeIn(barcodeValue);
            } else {
                searchProductByBarcode(barcodeValue);
            }
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
        let type = $("select[name='Tranvariants[type]']").val();
        if (!barcode || barcode.trim() === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Barcode Kosong',
                text: 'Silakan masukkan kode barcode'
            });
            $('#barcode-scanner-input').focus().select();
            return;
        }

        setBarcodeInputState(false);

        $.ajax({
            url: "<?= Url::to(['tran/searchproductbybarcode']) ?>",
            type: "GET",
            data: { barcode: barcode, type: type },
            timeout: 10000,
            success: function (response) {
                if (response.success) {
                    $('#barcode-scanner-input').val('');
                    const product = response.product;
                    const variant = response.variant;
                    let existingRow = findProductRow(product.productid);

                    if (existingRow) {
                        let remain = parseInt(existingRow.find('.product-remain').val() || 0);

                        if (remain <= 0) {
                            setBarcodeInputState(true);
                            Swal.fire({
                                icon: 'info',
                                title: 'Produk Sudah Di-Scan Semua',
                                text: 'Tidak ada sisa produk untuk di scan lagi.'
                            });
                            $('#barcode-scanner-input').val('').focus();
                            return;
                        }

                        var trandetailid = existingRow.find('.tran-trandetailid').val();
                        fillRowWithProductForEject(existingRow, product, variant);

                        Swal.fire({
                            title: 'Menyimpan...',
                            text: 'Sedang memproses barcode ' + barcode,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: function () {
                                Swal.showLoading();
                            }
                        });

                        submitForm(trandetailid, function () {
                            setBarcodeInputState(true);
                        });

                    } else {
                        setBarcodeInputState(true);
                        Swal.fire({
                            icon: 'info',
                            title: 'Produk Tidak Ada di Tabel',
                            text: 'Produk ditemukan, tetapi tidak terdaftar dalam daftar item referensi ini.'
                        });
                        $('#barcode-scanner-input').val('').focus();
                    }
                } else {
                    setBarcodeInputState(true);
                    $('#barcode-scanner-input').focus().select();
                    Swal.fire({
                        icon: 'error',
                        title: 'Produk Tidak Ditemukan',
                        text: 'Barcode ' + barcode + ' tidak ditemukan di Gudang atau tidak dalam kondisi baik'
                    });
                }
            },
            error: function (xhr, status, error) {
                setBarcodeInputState(true);
                $('#barcode-scanner-input').focus().select();

                let msg = 'Terjadi kesalahan saat mencari produk';
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

    function searchBarcodeIn(barcode) {
        if (!barcode || barcode.trim() === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Barcode Kosong',
                text: 'Silakan masukkan kode barcode'
            });
            $('#barcode-scanner-input').focus().select();
            return;
        }

        setBarcodeInputState(false);

        $.ajax({
            url: "<?= Url::to(['tran/searchbarcodein']) ?>",
            type: "GET",
            data: { barcode: barcode },
            timeout: 10000,
            success: function (response) {
                if (response.success) {
                    $('#barcode-scanner-input').val('');

                    let targetRow = null;
                    $('.detail-rows tr[data-repeater-item]').each(function () {
                        let remain = parseInt($(this).find('.product-remain').val() || 0);
                        if (remain > 0) {
                            targetRow = $(this);
                            return false;
                        }
                    });

                    if (targetRow) {
                        let rowProductId = targetRow.find('.product-productid').val();
                        if (!response.product.productid) {
                            response.product.productid = rowProductId;
                        }

                        fillRowWithProductForEject(targetRow, response.product, response.variant);
                        let trandetailid = targetRow.find('.tran-trandetailid').val();

                        Swal.fire({
                            title: 'Menyimpan...',
                            text: 'Sedang memproses barcode ' + barcode,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: function () {
                                Swal.showLoading();
                            }
                        });

                        submitForm(trandetailid, function () {
                            setBarcodeInputState(true);
                        });

                    } else {
                        setBarcodeInputState(true);
                        Swal.fire({
                            icon: 'info',
                            title: 'Produk Sudah Di-Scan Semua',
                            text: 'Tidak ada sisa produk untuk di scan lagi.'
                        });
                    }

                } else {
                    setBarcodeInputState(true);
                    $('#barcode-scanner-input').focus().select();
                    Swal.fire({
                        icon: 'error',
                        title: 'Barcode Sudah Ada atau Tidak Ditemukan',
                        text: 'Barcode ' + barcode + ' sudah ada di database'
                    });
                }
            },
            error: function (xhr, status, error) {
                setBarcodeInputState(true);
                $('#barcode-scanner-input').focus().select();

                let msg = 'Terjadi kesalahan saat mencari produk';
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

    function findProductRow(productid) {
        let foundRow = null;
        if (!productid) return null;

        $('.detail-rows tr[data-repeater-item]').each(function () {
            let $row = $(this);
            let currentProductId = $row.find('.product-productid').val();
            if (currentProductId && currentProductId === productid.toString()) {
                foundRow = $row;
                return false;
            }
        });

        return foundRow;
    }

    function fillRowWithProductForEject(row, product, variant) {
        row.find('.product-productid').val(product.productid);
        row.find('.product-name').val(product.productname);
        row.find('.variant-id-input').val(variant.variantid);
        row.find('.variant-barcode-input').val(variant.barcode);
        // console.log("Isi ProductID ke Row:", product.productid);
    }

    function submitForm(trandetailid, onComplete) {
        let type = $("select[name='Tranvariants[type]']").val();
        let reftype2 = $('.refid-select').attr('data-reftype2') || '';

        $.ajax({
            url: type == '1' && reftype2 == 'purchase/request'
                ? "<?= Url::to(['tran/createvariants']) ?>"
                : "<?= Url::to(['tran/createitem']) ?>",
            type: "POST",
            data: $('#tran-form').serialize() + '&trandetailid=' + trandetailid,
            timeout: 15000,
            success: function (response) {
                Swal.close();

                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        html: response.pesan,
                        showConfirmButton: response.finished,
                        timer: response.finished ? null : 1500
                    });

                    let now = new Date();
                    let trandateInput = document.querySelector("input.picktime");
                    if (trandateInput && trandateInput._flatpickr) {
                        trandateInput._flatpickr.setDate(now, true);
                    } else {
                        $(".picktime").val(DatetoStringTime(now));
                    }

                    $('#datatable').DataTable().ajax.reload();
                    generateDeli();
                    $('#barcode-scanner-input').focus().select();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.pesan
                    });
                    $('#barcode-scanner-input').focus().select();
                }

                if (typeof onComplete === 'function') onComplete();
            },
            error: function (xhr, status, error) {
                Swal.close();

                let msg = 'Terjadi kesalahan saat menyimpan data.';
                if (status === 'timeout') {
                    msg = 'Request timeout. Data mungkin tidak tersimpan. Cek koneksi dan coba lagi.';
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Server',
                    text: msg
                });

                $('#barcode-scanner-input').focus().select();
                if (typeof onComplete === 'function') onComplete();
            }
        });
    }

    function generateDeli() {
        let refid = $("select[name='Tranvariants[refid]']").val();
        let type = $("select[name='Tranvariants[type]']").val();
        let reftype2 = $('.refid-select').attr('data-reftype2') || '';
        if (!refid) return;

        $.ajax({
            url: "<?= Url::to(['tran/loadscan']) ?>",
            type: "GET",
            dataType: 'json',
            data: {
                id: refid,
                type: type,
                reftype2: reftype2
            },
            success: function (response) {
                $('.detail-rows').empty();
                $('.unreturn-info').empty();
                $('.btn-cek-container').addClass('d-none').hide();
                $('.btn-peak-container').addClass('d-none').hide();

                let hasUnreturn = false;
                let unreturnItems = [];
                let soTranno = '';

                response.data.forEach(function (item, index) {
                    $('#trandetail_repeater [data-repeater-create]').trigger('click');
                    let $row = $('.detail-rows tr[data-repeater-item]').last();

                    $row.find(".tran-trandetailid").val(item.trandetailid);
                    $row.find(".tran-refid").val(item.refid);
                    $row.find(".product-quantity").val(item.senttotal);
                    $row.find(".product-quantity-stockin").val(item.sent_milik);
                    $row.find(".product-name").val(item.productname);
                    $row.find(".product-productid").val(item.productid);
                    $row.find(".variant-id-input").val(item.variantid);
                    $row.find(".product-sent").val(item.total_variant);
                    $row.find(".product-remain").val(item.senttotal - item.total_variant);

                    if (item.pending_barcodes || item.product_unreturn) {
                        hasUnreturn = true;
                        soTranno = item.so_tranno ?? '';
                        unreturnItems.push({
                            product: item.product_unreturn ?? '-',
                            barcodes: item.pending_barcodes ?? '-'
                        });
                    }
                });

                if (hasUnreturn && (type === '3' || (type === '1' && reftype2 === 'sales/order'))) {
                    let itemsHtml = unreturnItems.map(item => `
                    <div class="d-flex align-items-start gap-2 mb-2">
                        <div class="flex-shrink-0">
                            <div class="text-dark small mb-1">
                                <i class="fas fa-tag me-1 text-muted"></i>${item.product}
                            </div>
                            <div class="text-muted small font-monospace">
                                <i class="fas fa-barcode me-1"></i>${item.barcodes}
                            </div>
                        </div>
                    </div>
                `).join('');

                    $('.unreturn-info').html(`
                    <div class="d-flex align-items-center gap-2 mb-3 pb-2" style="border-bottom: 2px solid #f6c000;">
                        <span class="text-warning p-2 fw-bold">
                            <i class="fas fa-exclamation-triangle me-2 text-warning fw-bold"></i>
                            Barang yang Belum Dikembalikan
                        </span>
                    </div>
                    <div class="rounded-2 overflow-hidden" style="border-left: 4px solid #f6c000;">
                        <div class="px-3 py-2" style="background:#fff3e0;">
                            <strong class="text-dark">
                                <i class="fas fa-file-alt me-2 text-black"></i>No Project: ${soTranno}
                            </strong>
                        </div>
                        <div class="px-3 py-2" style="background:#fff8e1;">
                            ${itemsHtml}
                        </div>
                    </div>
                `);
                }

                if (type === '2') {
                    $('.btn-cek-container').removeClass('d-none').show();
                    $('.btn-peak-container').removeClass('d-none').show();
                } else {
                    $('.btn-cek-container').addClass('d-none').hide();
                    $('.btn-peak-container').addClass('d-none').hide();
                }

            },
            error: function () {
                console.warn('Error get crew');
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
        let type = $("select[name='Tranvariants[type]']").val();
        let reftype2 = $('.refid-select').attr('data-reftype2') || '';

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
                        type: (type == '1') ? 'stock/in' : (type == '2' ? 'sales/item' : (type == '3' ? 'stock/return' : '')),
                        reftype2: reftype2,
                        _csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
                    },
                    headers: {
                        "X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
                    },
                    success: function (response) {
                        $("#datatable").DataTable().ajax.reload();
                        generateDeli();

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