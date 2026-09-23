<?php
$this->title = $title;

use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="app-toolbar py-3 py-lg-6 px-0 mx-0 ms-n8">
    <div class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0"><?= Yii::$app->lang->t('extrasidebar', 'extrasidebar13') ?></h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= Url::to(['site/index']) ?>" class="text-muted text-hover-primary"><?= Yii::$app->lang->t('back_home', 'chat2') ?></a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted"><?= Yii::$app->lang->t('extrasidebar', 'extrasidebar13') ?></li>
            </ul>
            <!-- ?php //echo Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) 
			?>
			?= Alert::widget() ?> -->
        </div>
    </div>
</div>
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

                        <!-- Clear search button -->
                        <span class="position-absolute top-50 end-0 translate-middle-y me-3 d-none" id="clear-search">
                            <i class="ki-duotone ki-cross fs-2 text-gray-500 cursor-pointer" style="opacity: 0.5;"></i>
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card toolbar -->
        <div class="card-toolbar">
            <!-- Filter button -->
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
                    <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                </div>
                <!-- Separator -->
                <div class="separator border-gray-200"></div>
                <!-- Content -->
                <div class="px-7 py-5" data-kt-user-table-filter="form">
                    <form id="filterForm" method="get" action="index">
                        <div class="row">
                            <div class="md-10">
                                <label class="fw-semibold fs-6 mb-2 mt-3" for="datefilter">Date Range:</label>
                                <input name="datefilter" class="form-control form-control-solid" style="cursor:pointer;" id="datefilter" placeholder="Pick date range">
                            </div>
                            <div class="col-md-12">
                                <label class="fw-semibold fs-6 mb-2" for="contactSelect">Contact</label>
                                <select id="contactSelect" class="form-select contact" data-control="select2" name="contact">
                                    <option value="">All Contacts</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group text-end mt-5">
                            <button type="submit" id="filterButton" class="btn btn-lg btn-primary"><i class="fa-sharp fa-solid fa-filter"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Add transaction button with href -->
            <a href="<?= Url::to(['create', 'type' => $type]) ?>" class="btn btn-primary" id="btn-add-tran" data-bs-toggle="modal" data-bs-target="#modal_form_tran">
                <i class="ki-duotone ki-plus fs-2"></i> Add <?= $title ?>
            </a>
        </div>
    </div>

    <!-- Card body -->
    <div class="card-body pt-5">
        <!-- Table -->
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="datatable">
            <thead>
                <tr class="text-start bg-gray-100 fs-6 text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                    <th class="text-center min-w-50px">Action</th>
                    <th class="text-center min-w-100px">No. Transaksi</th>
                    <th class="text-center min-w-200px">Customer</th>
                    <th class="text-center min-w-150px">Tanggal</th>
                    <th class="text-center min-w-150px">Jatuh Tempo</th>
                    <th class="text-center min-w-150px">Total</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 fw-semibold text-center">
                <!-- Table content will be loaded dynamically -->
            </tbody>
        </table>
    </div>
</div>

<!-- Transaction Modal -->
<div class="modal fade" id="modal_form_tran" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-lg-down modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add <?= $title ?></h5>
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


<script>
    $(document).ready(function() {
        const params = new URLSearchParams(window.location.search);

        const contact = params.get('contact');
        const search = params.get('search');
        const datefilter = params.get('datefilter');
        const type = params.get('type');

        if (contact !== null) $('select[name="contact"]').val(contact).trigger('change');
        if (search !== null) $('input[name="search"]').val(search);
        if (datefilter !== null) $('input[name="datefilter"]').val(datefilter);
    });

    $.fn.dataTable.ext.errMode = "none"; // Disable all DataTables warnings

    $(document).ready(function() {
        // Initialize Select2 for Contacts
        $("#contactSelect").select2({
            ajax: {
                url: "<?= \yii\helpers\Url::to(['sales/contactlist']) ?>",
                type: "GET",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
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
            placeholder: "Pilih Contact",
            allowClear: true,
            templateResult: formatContact,
            templateSelection: formatContactSelection,
            dropdownParent: $('#modal_form_tran').length ? $('#modal_form_tran') : $(document.body)
        });

        // Format contact display in dropdown
        function formatContact(contact) {
            if (!contact.id) return contact.text;

            // Create more informative display showing phone and email
            var $container = $(
                '<div class="select2-result-contact clearfix">' +
                '<div class="select2-result-contact__name">' + contact.text + '</div>' +
                (contact.contact_phone1 ? '<div class="select2-result-contact__phone"><i class="fa fa-phone me-1"></i> ' + contact.contact_phone1 + '</div>' : '') +
                (contact.contact_email1 ? '<div class="select2-result-contact__email"><i class="fa fa-envelope me-1"></i> ' + contact.contact_email1 + '</div>' : '') +
                '</div>'
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
            lengthMenu: [5, 15, 25, 50],
            pageLength: 5,
            order: [],
            language: {
                info: `${translate1}`,
                infoEmpty: `${translate2}`,
                emptyTable: `
                <div style="text-align: center; padding: 20px 0;">
                   <img width='250px' src='https://cdni.iconscout.com/illustration/premium/thumb/employee-is-unable-to-find-sensitive-data-illustration-download-in-svg-png-gif-file-formats--no-found-misplaced-files-business-pack-illustrations-8062128.png'/>
                    <div style="font-weight: bold; font-size: 16px; margin-top : 8px;"> ${translate}</div>
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
                    <div style="margin-top: 10px;">Memuat data...</div>
                </div>
            `
            },
            initComplete: function() {
                $('.dataTables_empty').closest('.dataTables_scroll').addClass('table-initialized');
            },
            // In your DataTable initialization
            ajax: {
                type: "GET",
                dataSrc: "data",
                url: "<?= \yii\helpers\Url::to(['sales/list']) ?>",
                data: function(d) {
                    // Ambil nilai filter
                    const contact = $('select[name="contact"]').val();
                    const search = $('input[name="search"]').val();
                    const datefilter = $('input[name="datefilter"]').val();
                    const type = '<?= $type ?>';

                    // Kirim ke server
                    d.contact = contact;
                    d.search = search;
                    d.datefilter = datefilter;
                    d.type = type;

                    // Tambahkan ke URL browser (tanpa reload)
                    const params = new URLSearchParams();
                    if (contact) params.set('contact', contact);
                    if (search) params.set('search', search);
                    if (datefilter) params.set('datefilter', datefilter);
                    params.set('type', type);

                    const newUrl = window.location.pathname + '?' + params.toString();
                    window.history.replaceState({}, '', newUrl); // 👈 update URL tanpa reload
                },
                complete: function() {
                    $('.table-loading-overlay').remove();
                }
            },
            columns: [{
                    data: null,
                    render: function(data, type, row) {
                        return `
                        <div class="dropdown text-center dropend">
                            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                <i class="fa-sharp fa-solid fa-list"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item text-hover-success" href="<?= Url::to(['update']) ?>?id=${row.tranid}" data-bs-toggle="modal" data-bs-target="#modal_form_tran"><i class="fas fa-edit"></i> Edit</a></li>
                                <li><button class="dropdown-item text-hover-danger delete-tran" data-id="${row.tranid}"><i class="fas fa-trash"></i> Hapus</button></li>
                           <li>
                            <a href="<?= Url::to(['sales/detail', 'id' => $row->tranid]) ?>"
                            class="dropdown-item text-hover-primary btn-light"
                            style="cursor:pointer;">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            </li>
                            </ul>
                        </div>`;
                    }
                },
                {
                    data: "tranno",
                    render: function(data) {
                        return `<span class="text text-align-center">${data}</span>`;
                    }
                },
                {
                    data: "contact_name",
                    defaultContent: "-",
                    render: function(data) {
                        return data ? `${data}` : `No Data`;
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
                    data: "total",
                    render: function(data) {
                        return data ?
                            `${parseFloat(data).toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 })}` :
                            "-";
                    }
                }
            ],
        });

        // DateRangePicker setup
        $("#datefilter").css("text-align", "center").daterangepicker({
            timePicker: true,
            autoUpdateInput: false,
            startDate: moment().startOf("hour").subtract(1, "month"),
            endDate: moment().startOf("hour").add(31, "hour"),
            locale: {
                format: "YYYY/MM/DD"
            }
        });

        // Update value only when user selects a date
        $("#datefilter").on("apply.daterangepicker", function(ev, picker) {
            $(this).val(picker.startDate.format("YYYY/MM/DD") + " - " + picker.endDate.format("YYYY/MM/DD"));
            $("#datatable").DataTable().ajax.reload();
        });

        // Event when clear button is pressed
        $("#datefilter").on("cancel.daterangepicker", function(ev, picker) {
            $(this).val(""); // Clear input
            $("#datatable").DataTable().ajax.reload(); // Reload data table
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

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        // Use DOMParser to parse HTML
                        let parser = new DOMParser();
                        let doc = parser.parseFromString(data, 'text/html');

                        // Insert content into the modal
                        $('#modal-content').html(doc.body.innerHTML);
                    },
                    error: function() {
                        $('#modal-content').html('<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>Error loading form.</div>');
                    }
                });
            } else if (target === '#modal_detail_tran') {
                $('#modal-detail-content').html('<div class="text-center p-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading...</p></div>');

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
                title: "Yakin ingin hapus?",
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= Url::to(['/sales/delete']) ?>',
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
                                title: "Terhapus!",
                                text: "Data berhasil dihapus.",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr.responseText);

                            // Show error notification
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
    });
</script>

