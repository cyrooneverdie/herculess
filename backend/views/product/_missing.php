<?php

// var_dump($title);
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::$app->lang->t('extrasidebar', 'extrasidebar325');

?>

<div class="card">
    <div class="card-header border-0 pt-6 sticky-top bg-white shadow-sm" style="top: 0; z-index: 1015;">
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
                                        <label class="form-label fw-semibold mb-2" for="productFilter">Produk</label>
                                        <select id="productSelect" class="form-select product" data-control="select2"
                                            name="product">
                                            <!-- <option value="">Pilih Produk</option> -->
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="fw-semibold fs-6 mb-2" for="contactFilter">
                                            <?= Yii::$app->lang->t('extrasidebar', 'extrasidebar5') ?>
                                        </label>
                                        <select id="contactSelect" class="form-select contact" data-control="select2"
                                            name="contact">
                                            <!-- <option value="">All Contacts</option> -->
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
            </div>
        </div>
    </div>

    <div class="card-body pt-5">
        <div id="liveAlertPlaceholder"></div>
        <div class="btn-group mb-3" id="mass-action-buttons" style="display: none;">
            <button type="button" class="btn btn-danger" id="btn-delete-mass">
                <i class="fa-solid fa-trash-arrow-up me-2"></i>Restore
            </button>
        </div>

        <table class="table align-left table-row-dashed fs-6 gy-5" id="datatable">
            <thead>
                <tr class="text-start bg-gray-100 fs-6 text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                    <th class="text-start min-w-100px"><?= Yii::$app->lang->t('extrasidebar', 'extrasidebar2') ?>
                    </th>
                    <th class="text-start w-50px mx-1">
                        <div class="form-check form-check-custom form-check-solid form-check-sm px-3">
                            <input class="form-check-input" type="checkbox" id="select-all">
                        </div>
                    </th>
                    <th class="text-start min-w-75px">Product</th>
                    <th class="text-start min-w-75px">Event Date</th>
                    <th class="text-start min-w-75px">Barcode</th>
                    <th class="text-start min-w-75px">Project No</th>
                    <!-- <th class="text-center min-w-50px">Qty</th> -->
                </tr>
            </thead>
            <tbody class="text-gray-800 fw-semibold text-start">
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        const params = new URLSearchParams(window.location.search);

        const contact = params.get('contact');
        const product = params.get('product');
        const search = params.get('search');
        const datefilter = params.get('datefilter');

        if (contact !== null) $('select[name="contact"]').val(contact).trigger('change');
        if (product !== null) $('select[name="product"]').val(product).trigger('change');
        if (search !== null) $('input[name="search"]').val(search);
        if (datefilter !== null) $('input[name="datefilter"]').val(datefilter);

        $.fn.dataTable.ext.errMode = "none";

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

        var deletemessage5 = "<?= Yii::$app->lang->t('extra', 'extra213') ?>";
        var deletemessage4 = "<?= Yii::$app->lang->t('extra', 'extra212') ?>";
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
                case 'restore':
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
                        url: '<?= Url::to(['/product/restore']) ?>',
                        type: "POST",
                        data: {
                            ids: selectedIds,
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
                                case 'restore':
                                    successTitle = deletemessage5;
                                    successText = deletemessage4;
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
            performMassAction('restore', 10);
        });

        $("#filterForm").submit(function (e) {
            e.preventDefault();
            $("#datatable").DataTable().ajax.reload();
            let filterMenu = document.querySelector("[data-kt-menu-id='filter-menu']");
            if (filterMenu) {
                KTMenu.getInstance(filterMenu).hide();
            }
        });

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

        $('#productSelect').select2({
            placeholder: 'Pilih Produk',
            allowClear: true,
            ajax: {
                url: '<?= Url::to(['/product/select']) ?>',
                dataType: 'json',
                type: 'POST',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term || '',
                        page: params.page || 1,
                        limit: 10
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 10) < data.totalcount
                        }
                    };
                },
                cache: true
            }
        });

        var translate = <?= json_encode(Yii::$app->lang->t('extra', 'extra11')) ?>;
        var translate1 = <?= json_encode(Yii::$app->lang->t('extra', 'extra12')) ?>;
        var translate2 = <?= json_encode(Yii::$app->lang->t('extra', 'extra13')) ?>;
        $("#datatable").DataTable({
            scrollX: false,
            scrollCollapse: true,
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
                url: "<?= \yii\helpers\Url::to(['product/cekmissing']) ?>",
                data: function (d) {
                    const contact = $('select[name="contact"]').val();
                    const product = $('select[name="product"]').val();
                    const search = $('input[name="search"]').val();
                    const datefilter = $('input[name="datefilter"]').val();

                    d.contact = contact;
                    d.product = product;
                    d.search = search;
                    d.datefilter = datefilter;

                    const params = new URLSearchParams();
                    if (contact) params.set('contact', contact);
                    if (product) params.set('product', product);
                    if (search) params.set('search', search);
                    if (datefilter) params.set('datefilter', datefilter);

                    const newUrl = window.location.pathname + '?' + params.toString();
                    window.history.replaceState({}, '', newUrl);
                },
                complete: function () {
                    $('.table-loading-overlay').remove();
                }
            },
            columns: [{
                data: "variantid",
                visible: false
            },
            {
                data: null,
                className: "text-center",
                orderable: false,
                render: function (data, type, row) {
                    return `
                    <div class="form-check form-check-custom form-check-solid form-check-sm px-3">
                         <input type="checkbox" class="form-check-input row-checkbox select-checkbox" value="${row.variantid}">
                    </div>
                     `;
                }
            },
            {
                data: "productname",
                className: "text-start",
                render: function (data) {
                    return `<span class="text text-start">${data || '-'}</span>`;
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
                data: "barcode",
                className: "text-start",
                render: function (data) {
                    return `<span class="text text-start">${data || '-'}</span>`;
                }
            },
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
                // {
                //     data: "qty",
                //     className: "text-center",
                //     render: function (data) {
                //         return `<span class="text text-center" > ${data || '-'}</span > `;
                //     }
                // }
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
                const url = $(this).attr('data-url');
                console.log(url);
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
            e.preventDefault(); // Don't let the form reload the page
            $("#datatable").DataTable().ajax.reload();
        });

        $("#filterForm").submit(function (e) {
            e.preventDefault(); // Don't let the form reload the page
            $("#datatable").DataTable().ajax.reload();
        });


    });
</script>