<?php
$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar113');
$this->title = $title;

use yii\helpers\Html;
use yii\helpers\Url;

?>
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #printable-barcode-area,
        #printable-barcode-area * {
            visibility: visible;
        }

        #printable-barcode-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 20px;
        }

        .modal-header,
        .modal-footer,
        .alert,
        .btn,
        button {
            display: none !important;
        }

        .barcode-card {
            page-break-inside: avoid;
            margin-bottom: 20px;
        }

        @page {
            size: A4;
            margin: 1cm;
        }
    }

    .barcode-card {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        background: white;
        transition: all 0.3s ease;
        height: 100%;
    }

    .barcode-card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .barcode-container {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 6px;
        margin: 15px 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100px;
        border: 2px dashed #dee2e6;
    }

    .barcode-info {
        border-top: 2px solid #f0f0f0;
        padding-top: 12px;
        margin-top: 12px;
    }
</style>

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
                            placeholder="<?= Yii::$app->lang->t('extra', 'extra02') ?>" autocomplete="off" />

                        <span class="position-absolute top-50 end-0 translate-middle-y me-3 d-none" id="clear-search">
                            <i class="ki-duotone ki-cross fs-2 text-gray-500 cursor-pointer" style="opacity: 0.5;"></i>
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-toolbar flex-shrink-0">
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                    data-bs-target="#modal_filter">
                    <i class="ki-duotone ki-filter fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>Filter</button>

                <a href="/variant/create" id="btn-add-variant" class="btn btn-primary me-3">
                    <i class="ki-duotone ki-plus fs-2"></i><?php echo Yii::$app->lang->t('add', 'add1'); ?>
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <button type="button" class="btn btn-danger text-light d-none mb-3" id="btn-hapusmassal">
                <i class="fas fa-trash me-2"></i><?= Yii::$app->lang->t('back_home', 'chat53') ?>
            </button>
            <button type="button" class="btn btn-success ms-3 mb-3" id="btn-print-selected" style="display:none;">
                <i class="ki-duotone ki-printer fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Print Selected (<span id="selected-count">0</span>)
            </button>
            <table class="table align-middle table-row-bordered fs-6 gy-5" id="datatable">
                <thead>
                    <tr
                        class="text-start text-gray-500 bg-gray-100 fw-bold fs-7 text-uppercase gs-0 border-bottom border-gray-200">
                        <th class="text-center min-w-50px">
                            <div class="form-check form-check-custom form-check-solid form-check-sm mx-3">
                                <input class="form-check-input" type="checkbox" id="select-all">
                            </div>
                        </th>
                        <th class="text-center min-w-50px">
                            <?= Yii::$app->lang->t('produk_table', 'produk_action') ?>
                        </th>
                        <!-- <th class="text-center w-10px">
                            <i class="ki-duotone ki-barcode fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </th> -->
                        <th class="text-start min-w-130px">
                            <?= Yii::$app->lang->t('produk_table', 'produk_gambar') ?>
                        </th>
                        <th class="text-start min-w-250px"><?= Yii::$app->lang->t('produk', 'label2') ?></th>
                        <th class="text-start min-w-100px">
                            <?= Yii::$app->lang->t('series', 'label1') ?>
                        </th>
                        <th class="text-start min-w-100px"><?= Yii::$app->lang->t('produk', 'produk_nounit') ?>
                        </th>
                        <th class="text-start min-w-150px"><?= Yii::$app->lang->t('produk', 'produk_asset') ?></th>
                        <th class="text-start min-w-100px"><?= Yii::$app->lang->t('produk', 'produk_serial') ?>
                        </th>
                        <th class="text-start min-w-100px"><?= Yii::$app->lang->t('variant_table', 'barcode') ?>
                        </th>
                        <th class="text-start min-w-100px"><?= Yii::$app->lang->t('variant_table', 'kondisi') ?>
                        </th>
                        <th class="text-start min-w-100px"><?= Yii::$app->lang->t('variant_table', 'lokasi') ?>
                        </th>
                        <th class="text-start min-w-100px"><?= Yii::$app->lang->t('variant_table', 'rak') ?></th>
                        <th class="text-start min-w-150px">
                            <?= Yii::$app->lang->t('varianharga', 'purchaseprice') ?>
                        </th>
                        <th class="text-start min-w-175px"><?= Yii::$app->lang->t('front_home', 'purchasedate') ?>
                        </th>
                        <th class="text-start min-w-200px"><?= Yii::$app->lang->t('tran', 'supplier') ?></th>
                        <th class="text-start min-w-250px">
                            <?= Yii::$app->lang->t('produk', 'produk_deskripsi') ?>
                        </th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 fw-semibold text-center border-bottom border-gray-200">
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_form_variant" tabindex="-1" aria-labelledby="modalFormVariantLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormVariantLabel">
                    <?= Yii::$app->lang->t('extrasidebar', 'extrasidebar113') ?>
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

<div class="modal fade" id="modal_filter" tabindex="-1" aria-labelledby="modalFormVariantLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormVariantLabel">
                    Filter
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-content" class="nopadding">
                    <form method="get" action="index" id="filter" class="w-100">
                        <div class="mb-2 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="fw-semibold fs-6 mb-2">
                                <?= Yii::$app->lang->t('produk', 'label2') ?>
                            </label>
                            <?= Html::dropDownList(
                                'productselect',
                                null,
                                [],
                                [
                                    'class' => 'form-select productselect',
                                    'data-control' => 'select2',
                                ]
                            ) ?>
                        </div>
                        <div class="mb-2 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="fw-semibold fs-6 mb-2">
                                <?= Yii::$app->lang->t('variant_table', 'kondisi') ?>
                            </label>
                            <?= Html::dropDownList(
                                'condition',
                                null,
                                [],
                                [
                                    'class' => 'form-select condition',
                                    'data-control' => 'select2',
                                ]
                            ) ?>
                        </div>
                        <div class="mb-2 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="fw-semibold fs-6 mb-2">
                                <?= Yii::$app->lang->t('variant_table', 'lokasi') ?>
                            </label>
                            <?= Html::dropDownList(
                                'locationid',
                                null,
                                [],
                                [
                                    'class' => 'form-select locationid',
                                    'data-control' => 'select2',
                                ]
                            ) ?>
                        </div>
                        <div class="text-end pt-10">
                            <button type="button" class="btn btn-light me-3 text-dark"
                                data-kt-users-modal-action="cancel"
                                data-bs-dismiss="modal"><?= Yii::$app->lang->t('back_home', 'chat34') ?></button>
                            <?= Html::submitButton('Filter', ['id' => 'btnsubmit', 'class' => 'btn btn-primary']) ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_barcode_preview" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center w-100">
                    <h5 class="modal-title me-auto">
                        <i class="ki-duotone ki-barcode fs-2 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Code Preview - Ready to Print & Scan
                    </h5>

                    <select class="form-select form-select-sm w-200px me-3" id="code-type-selector">
                        <option value="barcode">Barcode (CODE128)</option>
                        <option value="qrcode">QR Code</option>
                    </select>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="alert alert-info mx-6 mt-4 mb-0" role="alert">
                <div class="d-flex align-items-center">
                    <i class="ki-duotone ki-information-5 fs-2x me-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1">Code Information</h5>
                        <span>Format: <strong>CODE128/QR</strong> - Scannable with code scanner or smartphone
                            camera</span>
                    </div>
                </div>
            </div>

            <div class="modal-body" id="barcode-preview-content">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Close
                </button>
                <button type="button" class="btn btn-primary" id="btn-export-pdf">
                    <i class="ki-duotone ki-file-down fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Download as PDF
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addMasterModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg px-10">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>


<script>
    $(document).ready(function () {
        var translate3 = <?= json_encode(Yii::$app->lang->t('extra', 'extra_copy')) ?>;

        let table = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/variant/list',
                data: function (d) {
                    d.search.value = $('input[name="search"]').val();
                    d.id = $('select[name="productselect"]').val();
                    d.condition = $('select[name="condition"]').val();
                    d.locationid = $('select[name="locationid"]').val();
                }
            },
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected text-center'
            },

            columns: [{
                data: null,
                className: "text-center px-2",
                orderable: false,
                render: function (data, type, row) {
                    return `
                    <div class="form-check form-check-custom form-check-solid form-check-sm px-3">
                    <input type="checkbox" class="form-check-input row-checkbox" value="${row.variantid}">
                    </div>
                    `;
                }
            },
            {
                data: 'variantid',
                className: "text-center",
                render: function (data, type, row) {
                    return `
                    <div class="dropdown text-center dropend">
                        <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                        <i class="fa-sharp fa-solid fa-list"></i>
                        </button>
                        <ul class="dropdown-menu px-2">
                         <li>
                            <a href="<?= \yii\helpers\Url::to(['variant/detail']) ?>?id=${row.variantid}" class="dropdown-item text-hover-primary btn-detail" data-id="${row.variantid}" style="cursor:pointer;">
                                <i class="fas fa-eye me-2"></i> Detail
                            </a>
                        </li>

                        <li>
                            <a href="/variant/update" class="dropdown-item text-hover-success btn-light edit-variant" data-productid="${row.productid}" data-variantid="${row.variantid}" style="cursor:pointer;">
                                 <i class="fas fa-edit me-2"></i> Edit
                            </a>
                        </li>

                        <li>
                            <a href="javascript:void(0);" class="dropdown-item text-hover-info btn-print-barcode" data-id="${row.variantid}" style="cursor:pointer;">
                                <i class="bi bi-upc-scan me-2"></i> Print Code
                            </a>
                        </li>

                        <li>
                            <a href="javascript:void(0);" class="dropdown-item text-hover-danger delete" data-productid="${row.productid}" data-variantid="${row.variantid}" style="cursor:pointer;">
                                 <i class="fas fa-trash me-2"></i> Hapus
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item text-hover-info copy-text-btn" data-description="${row.description || ''}">
                                <i class="fas fa-copy me-2"></i> ${translate3}
                            </a>
                        </li>
                        </ul>
                    </div>
                    `;
                }
            },
            {
                data: 'documentpath',
                className: "text-start align-middle",
                render: function (data, type, row) {
                    let defaultImg = "<?= Yii::getAlias('@web') ?>/assets/media/logos/default.png";
                    let imgSrc = data ? "<?= Yii::getAlias('@web') ?>/uploads/variant/" + data : defaultImg;

                    return `<img src="${imgSrc}" 
                     alt="${row.productname || ''}" 
                     class="w-100px h-100px border border-gray-300 rounded object-fit-contain"
                     onerror="this.onerror=null; this.src='${defaultImg}';">`;
                },
                orderable: false
            },
            {
                data: 'productname',
                className: 'text-start'
            },
            {
                data: 'series',
                className: 'text-start'
            },
            {
                data: 'unitno',
                className: 'text-start'
            },
            {
                data: 'asetno',
                className: 'text-start'
            },
            {
                data: 'serialno',
                className: 'text-start'
            },
            {
                data: 'barcode',
                className: 'text-start'
            },
            {
                data: 'condition',
                className: 'text-start'
            },
            {
                data: 'location',
                className: 'text-start'
            },
            {
                data: 'shelf',
                className: 'text-start'
            },
            {
                data: 'price',
                className: 'text-start',
                render: function (data) {
                    return data ?
                        `<span class="text text-end">${parseFloat(data).toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 })}</span>` :
                        "-";
                }
            },
            {
                data: "purchasedate",
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
                data: "contact_name",
                className: "text-start",
                render: function (data, type, row) {
                    if (!data) return '-';

                    return `
                       <div class="d-flex flex-column align-items-left mx-auto my-auto w-100">
                            <span class="text text-start fw-bold"><i class="fas fa-user me-1"></i> ${row.contact_name}</span>
                            <small class="text text-start text-gray-800"><i class="fas fa-building me-1"></i> ${row.jobcompany}</small>
                        </div>
                        `;
                }
            },
            {
                data: "description",
                className: "text-start align-middle",
                render: function (data) {
                    if (!data) return "<span class='text-muted'>-</span>";
                    let maxLength = 50;
                    let shortText = data.length > maxLength ? data.substring(0, maxLength) + "..." : data;
                    return `<span class="text-gray-800" title="${data}">${shortText}</span>`;
                }
            },
            ],
            order: [],
            columnDefs: [{
                orderable: false,
                targets: '_all'
            }]
        });

        $("#select-all").on("click", function () {
            const isChecked = this.checked;
            $("tbody .form-check-input").each(function () {
                $(this).prop("checked", isChecked);
            });
            hapusmassal();
        });

        $("#datatable tbody").on("change", ".form-check-input", function () {
            $("#select-all").prop(
                "checked",
                $(".form-check-input").length === $(".form-check-input:checked").length
            );
            hapusmassal();
        });

        function reloadTable($selector, modalId) {
            $($selector).on('submit', function (e) {
                e.preventDefault();

                table.ajax.reload(function () {
                    if (modalId) {
                        $(modalId).modal('hide');
                    }
                });
            });
        }

        reloadTable('#search');
        reloadTable('#filter', '#modal_filter');

        $(document).on('click', '.copy-text-btn', function () {
            const table = $('#datatable').DataTable();
            const rowData = table.row($(this).closest('tr')).data();

            if (!rowData || !rowData.description) {
                Swal.fire({
                    icon: 'info',
                    title: 'No text',
                    text: 'Tidak ada teks yang bisa disalin.',
                    timer: 1500,
                    showConfirmButton: false
                });
                return;
            }

            const textToCopy = rowData.description.trim();

            navigator.clipboard.writeText(textToCopy)
                .then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Copied!',
                        text: '<?= Yii::$app->lang->t('back_home', 'succes_copy') ?>',
                        timer: 1500,
                        showConfirmButton: false
                    });
                })
                .catch(() => {
                    const tempTextarea = $('<textarea>');
                    $('body').append(tempTextarea);
                    tempTextarea.val(textToCopy).select();
                    document.execCommand('copy');
                    tempTextarea.remove();

                    Swal.fire({
                        icon: 'success',
                        title: 'Copied!',
                        text: '<?= Yii::$app->lang->t('back_home', 'succes_copy') ?>',
                        timer: 1500,
                        showConfirmButton: false
                    });
                });
        });

        $('#btn-add-variant').on('click', function (e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('href'), // tetap ambil dari href
                success: function (data) {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(data, 'text/html');
                    $('#modal-content').html(doc.body.innerHTML);
                    $('#modal_form_variant').modal('show');
                },
                error: function () {
                    $('#modal-content').html('<p>Error loading form.</p>');
                }
            });
        });

        $(document).on('click', '.delete', function () {
            let productid = $(this).data('productid');
            let variantid = $(this).data('variantid');

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
                        url: '<?= Url::to(['/variant/delete']) ?>',
                        type: 'POST',
                        data: {
                            _csrf: '<?= Yii::$app->request->getCsrfToken() ?>',
                            // productid: productid,
                            variantid: variantid
                        },
                        headers: {
                            "X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
                        },
                        success: function (response) {

                            if (!response.success) {
                                Swal.fire({
                                    title: "Error!",
                                    text: response.pesan,
                                    icon: "error",
                                    confirmButtonColor: "#d33",
                                    confirmButtonText: "OK"
                                });
                                return;
                            }

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
                                text: xhr.responseJSON.pesan,
                                icon: "error",
                                confirmButtonColor: "#d33",
                                confirmButtonText: "OK"
                            });
                        }
                    });
                }
            });
        });

        $(document).on('click', '.edit-variant', function (e) {
            e.preventDefault();
            let variantid = $(this).data('variantid');

            $.ajax({
                url: $(this).attr('href'),
                data: {
                    id: variantid
                },
                success: function (data) {
                    if (typeof data === "object" && data.success === false) {
                        Swal.fire({
                            title: "Error!",
                            text: response.pesan,
                            icon: "error",
                            confirmButtonColor: "#d33",
                            confirmButtonText: "OK"
                        });
                        return;
                    }
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(data, 'text/html');
                    $('#modal-content').html(doc.body.innerHTML);
                    $('#modal_form_variant').modal('show');
                },
                error: function () {
                    $('#modal-content').html('<p>Error loading form.</p>');
                }
            });
        });

        $('.productselect').select2({
            placeholder: "Select Product",
            allowClear: true,
            cache: false,
            ajax: {
                url: "<?= \yii\helpers\Url::to(['product/select']) ?>",
                dataType: 'json',
                type: 'POST',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        limit: 5,
                        page: params.page || 1,
                        for: 'select2'
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
            },
            minimumInputLength: 0,
            width: '100%',
            dropdownParent: $('#modal_filter'),
        });

        function initEnumSelect2(selector, enumtype, addNewText, refSelector = null) {
            $(selector).select2({
                placeholder: 'Select ' + addNewText,
                allowClear: true,
                cache: true,
                ajax: {
                    url: "<?= Url::to(['enum/list']) ?>",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        $value = $(refSelector).val() ?? 'no send';
                        console.info($value);
                        return {
                            enumtype: enumtype,
                            search: params.term || '',
                            ref: refSelector ? $(refSelector).val() : '',
                            // limit: 5,
                            page: params.page || 1,
                            for: 'select2'
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;

                        var results = data.data.map(function (item) {
                            return {
                                id: item.enumid,
                                text: item.enumtext_id
                            };
                        });


                        return {
                            results: results,
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    }
                },
                minimumInputLength: 0,
                width: '100%',
                dropdownParent: $('#modal_filter'),
            })
        };

        initEnumSelect2('.condition', 'condition', 'Condition');
        initEnumSelect2('.locationid', 'location', 'Location');

        function hapusmassal() {
            const $btn = $('#btn-hapusmassal');

            if ($('.form-check-input:checked').length > 0) {
                $btn.removeClass('d-none').addClass('show');
            } else {
                $btn.removeClass('show').addClass('d-none');
            }
        }

        var deletemessage1 = "<?= Yii::$app->lang->t('extra', 'extra44') ?>";
        var deletemessage2 = "<?= Yii::$app->lang->t('extra', 'extra45') ?>";
        var deletemessage3 = "<?= Yii::$app->lang->t('extra', 'extra46') ?>";
        var deletemessage3koma1 = "<?= Yii::$app->lang->t('extra', 'extra46.1') ?>";
        var deletemessage4 = "<?= Yii::$app->lang->t('back_home', 'chat34') ?>";
        var deletemessage5 = "<?= Yii::$app->lang->t('back_home', 'chat53') ?>";
        $('#btn-hapusmassal').on('click', function () {
            let selectedIds = $(".form-check-input:checked")
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
            console.log(selectedIds.length);
            Swal.fire({
                title: deletemessage1,
                text: deletemessage3 + " " + selectedIds.length + " " + deletemessage3koma1,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: deletemessage5,
                cancelButtonText: deletemessage4
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= Url::to(['/variant/deletemassal']) ?>', // Endpoint untuk delete massal
                        type: "POST",
                        data: {
                            ids: selectedIds, // Kirim array ID ke server
                            _csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
                        },
                        headers: {
                            "X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
                        },
                        success: function (response) {
                            console.log("Response:", response);

                            $("#datatable").DataTable().ajax.reload();
                            $("#btn-hapusmassal").addClass("d-none"); // sembunyikan lagi
                            Swal.fire({
                                title: "<?= Yii::$app->lang->t('extra', 'extra62') ?>",
                                text: "<?= Yii::$app->lang->t('extra', 'extra59') ?>",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function (xhr) {
                            console.error("Error:", xhr.responseText);

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

        let currentData = [];
        let currentCodeType = 'barcode'; 

        function togglePrintButton() {
            const checkedCount = $('tbody .row-checkbox:checked').length;
            if (checkedCount > 0) {
                $('#btn-print-selected').fadeIn(0);
                $('#selected-count').text(checkedCount);
            } else {
                $('#btn-print-selected').fadeOut(0);
            }
        }

        $(document).on('change', 'tbody .row-checkbox', function () {
            const totalCheckboxes = $('tbody .row-checkbox').length;
            const checkedCheckboxes = $('tbody .row-checkbox:checked').length;
            $('#select-all').prop('checked', totalCheckboxes === checkedCheckboxes);
            togglePrintButton();
        });

        $(document).on('change', '#select-all', function () {
            $('tbody .row-checkbox').prop('checked', this.checked);
            togglePrintButton();
        });

        $(document).on('change', '#code-type-selector', function () {
            currentCodeType = $(this).val();

            if (currentData.length > 0) {
                generateCodePreview(currentData, currentCodeType);
            }
        });

        $(document).on('click', '#btn-print-selected', function () {
            const selectedIds = $('tbody .row-checkbox:checked')
                .map(function () {
                    return $(this).val();
                })
                .get();

            if (selectedIds.length === 0) {
                Swal.fire({
                    title: 'Tidak Ada Data',
                    text: 'Silakan pilih minimal 1 item untuk print',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                return;
            }

            Swal.fire({
                title: 'Loading...',
                text: 'Memuat ' + selectedIds.length + ' code...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            const table = $('#datatable').DataTable();
            const selectedData = [];

            table.rows().every(function () {
                const rowData = this.data();
                if (selectedIds.includes(rowData.variantid.toString())) {
                    selectedData.push(rowData);
                }
            });

            showCodePreview(selectedData);
        });

        $(document).on('click', '.btn-print-barcode', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const variantId = $(this).data('id');

            Swal.fire({
                title: 'Loading...',
                text: 'Memuat code...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            const table = $('#datatable').DataTable();
            let variantData = null;

            table.rows().every(function () {
                const rowData = this.data();
                if (rowData.variantid.toString() === variantId.toString()) {
                    variantData = rowData;
                    return false;
                }
            });

            if (variantData) {
                showCodePreview([variantData]);
            } else {
                Swal.fire('Error', 'Data tidak ditemukan', 'error');
            }
        });

        function showCodePreview(dataArray) {
            currentData = dataArray;
            currentCodeType = 'barcode'; // default barcode

            Swal.close();
            $('#modal_barcode_preview').modal('show');
            $('#code-type-selector').val('barcode');

            generateCodePreview(dataArray, 'barcode');
        }

        function generateCodePreview(dataArray, codeType) {
            if (!dataArray || dataArray.length === 0) {
                Swal.fire('Error', 'Tidak ada data', 'error');
                return;
            }

            let html = '<div id="printable-barcode-area"><div class="row g-4">';

            dataArray.forEach(function (item, index) {
                const codeValue = item.barcode || 'NO-CODE';
                const productName = item.productname || '-';
                const condition = item.condition || '-';
                const warehouse = item.warehouse || '-';
                const shelf = item.shelf || '-';
                const dimensions = `${item.width || 0} x ${item.length || 0} x ${item.height || 0}`;

                html += `
                    <div class="col-md-6 col-lg-4">
                            <div class="text-center mb-3">
                                <h5 class="mb-2 fw-bold">${productName}</h5>
                            </div>
                            
                            <div class="barcode-container" style="min-height: ${codeType === 'qrcode' ? '180px' : '100px'}">
                                ${codeType === 'barcode' ?
                        `<svg id="code-${index}" class="barcode-svg"></svg>` :
                        `<div id="code-${index}" class="qrcode-container"></div>`
                    }
                            </div>
                            <div class="text-center mt-3">
                                <span class="badge badge-light-primary fs-6">${codeValue}</span>
                            </div>
                            
                    </div>
                    `;
            });

            html += '</div></div>';

            $('#barcode-preview-content').html(html);

            setTimeout(function () {
                dataArray.forEach(function (item, index) {
                    const codeValue = item.barcode || 'NO-CODE';

                    if (codeType === 'barcode') {
                        try {
                            JsBarcode(`#code-${index}`, codeValue, {
                                format: "CODE128",
                                width: 2,
                                height: 60,
                                displayValue: true,
                                fontSize: 14,
                                margin: 10,
                                background: "#ffffff",
                                lineColor: "#000000"
                            });
                        } catch (e) {
                            $(`#code-${index}`).parent().html(
                                '<div class="alert alert-danger m-0">Invalid barcode: ' + codeValue + '</div>'
                            );
                        }
                    } else {
                        try {
                            const qrContainer = $(`#code-${index}`).parent();
                            qrContainer.html(`<div id="qr-${index}" class="d-flex justify-content-center"></div>`);

                            new QRCode(document.getElementById(`qr-${index}`), {
                                text: codeValue,
                                width: 150,
                                height: 150,
                                colorDark: "#000000",
                                colorLight: "#ffffff",
                                correctLevel: QRCode.CorrectLevel.H
                            });
                        } catch (e) {
                            console.error('QR Error:', e);
                            $(`#code-${index}`).parent().html(
                                '<div class="alert alert-danger m-0">QR Error: ' + e.message + '</div>'
                            );
                        }
                    }
                });
            }, 200);
        }

        $(document).on('click', '#btn-export-pdf', function () {
            const btnExport = $(this);
            const originalText = btnExport.html();

            btnExport.prop('disabled', true).html(`
                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                    Generating PDF...
                `);

            const element = document.getElementById('printable-barcode-area');

            html2canvas(element, {
                scale: 2, // Quality (higher = better quality but slower)
                useCORS: true,
                logging: false,
                backgroundColor: '#ffffff'
            }).then(function (canvas) {
                const imgWidth = 210; // A4 width in mm
                const pageHeight = 297; // A4 height in mm
                const imgHeight = (canvas.height * imgWidth) / canvas.width;
                let heightLeft = imgHeight;

                const imgData = canvas.toDataURL('image/png');
                const {
                    jsPDF
                } = window.jspdf;
                const pdf = new jsPDF('p', 'mm', 'a4');

                let position = 0;

                pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;

                // Add additional pages if needed
                while (heightLeft > 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }

                const timestamp = new Date().toISOString().slice(0, 19).replace(/:/g, '-');
                const filename = `${currentCodeType}_${timestamp}.pdf`;

                pdf.save(filename);
                btnExport.prop('disabled', false).html(originalText);

                Swal.fire({
                    title: 'Success!',
                    text: 'PDF berhasil di-download',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });

            }).catch(function (error) {
                console.error('Error generating PDF:', error);

                btnExport.prop('disabled', false).html(originalText);

                Swal.fire({
                    title: 'Error!',
                    text: 'Gagal generate PDF. Coba lagi.',
                    icon: 'error',
                    confirmButtonColor: '#d33'
                });
            });
        });

        $('#modal_barcode_preview').on('hidden.bs.modal', function () {
            $('#barcode-preview-content').html('');
            currentData = [];
            currentCodeType = 'barcode';
        });

        $('#datatable').on('draw.dt', function () {
            togglePrintButton();
        });

        $(document).on('click', '.add-new-sup-btn button', function () {
            var type = $(this).data('type');
            var position = $(this).data('position');

            $.get('/contact/create', {
                contacttype: type,
                position: position,
                type: 'master'
            }, function (html) {
                $('#addMasterModal .modal-content').html(html);
                $('#addMasterModal').modal('show'); 
            });
        });

    });
</script>