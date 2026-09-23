<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<?php
$form = ActiveForm::begin([
    'id' => 'FormValidTran',
    'method' => 'post',
    'options' => [
        'enctype' => 'multipart/form-data',
    ],
    'validateOnSubmit' => true,
]);
?>

<!--begin::Form-->
<!--begin::Scroll-->
<div class="d-flex flex-column scroll-y px-5 px-lg-10" id="modal_form_tran_scroll" data-kt-scroll="false" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#modal_form_tran_header" data-kt-scroll-wrappers="#modal_form_tran_scroll" data-kt-scroll-offset="300px">
    <div class="row">
        <div class="mb-7 col-lg-6 col-md-6 col-sm-6">
            <?= $form->field($model, 'tranno')->textInput(['placeholder' => $model->getAttributeLabel('tranno'), 'readOnly' => true]); ?>
        </div>
        <div class="mb-7 col-lg-6 col-md-6 col-sm-6">
            <?= $form->field($model, 'contactid', [
                'errorOptions' => ['class' => 'text-danger mt-3'],
            ])->dropDownList([], [
                'id' => 'contactSelect',
                'class' => 'form-select contacts',
                'data-control' => 'select2',
                'placeholder' => $model->getAttributeLabel('contactid'),
            ]); ?>
        </div>
    </div>

    <div class="row">
        <div class="mb-7 col-lg-6 col-md-6 col-sm-6">
            <?= $form->field($model, 'trandate', [
                'errorOptions' => ['class' => 'text-danger mt-3'],
            ])->textInput([
                'placeholder' => $model->getAttributeLabel('trandate'),
                'id' => 'tranDate',
                'required' => true,
            ]); ?>
        </div>
        <div class="mb-7 col-lg-6 col-md-6 col-sm-6">
            <?= $form->field($model, 'tranduedate', [
                'errorOptions' => ['class' => 'text-danger mt-3'],
            ])->textInput([
                'placeholder' => $model->getAttributeLabel('tranduedate'),
                'id' => 'tranDueDate',
            ]); ?>
        </div>
    </div>

    <!-- Product Items Section -->
    <div class="card mb-7 mt-5">
        <div class="card-header">
            <h3 class="card-title">Detail Produk</h3>
            <div class="card-toolbar">
                <button type="button" id="add-product-row" class="btn btn-sm btn-primary">
                    <i class="ki-duotone ki-plus fs-2"></i> Tambah Produk
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="products-table">
                    <thead>
                        <tr class="text-start bg-gray-100 fs-6 text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-200px">Produk</th>
                            <th class="min-w-100px">Jumlah</th>
                            <th class="min-w-100px">Harga Per Item</th>
                            <th class="min-w-100px">Subtotal</th>
                            <th class="min-w-50px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="product-rows">
                        <!-- Product rows will be added here dynamically -->
                        <tr id="empty-row">
                            <td colspan="5" class="text-center text-muted">Belum ada produk yang ditambahkan</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total:</td>
                            <td id="total-amount" class="fw-bold">0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Hidden input to store details as JSON -->
            <input type="hidden" name="details" id="transaction-details" value="">
        </div>
    </div>

    <!-- Notes/Additional Information Section -->
    <div class="row">
        <div class="col-12 mb-7">
            <div class="form-group">
                <label class="form-label">Catatan Transaksi</label>
                <textarea class="form-control" name="notes" rows="3" placeholder="Tambahkan catatan atau informasi tambahan tentang transaksi ini"></textarea>
            </div>
        </div>
    </div>
</div>
<!--end::Scroll-->

<!--begin::Actions-->
<div class="text-end pt-10">
    <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel" data-bs-dismiss="modal">Batal</button>
    <?= Html::submitButton($model->isNewRecord ? 'Simpan' : 'Update', ['id' => 'btnsubmit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
<!--end::Actions-->
<!--end::Form-->
<?php ActiveForm::end(); ?>

<script>
    // Make sure we execute our code after document is fully loaded
    $(document).ready(function() {
        // Make sure Select2 is loaded before initializing it
        // if (typeof $.fn.select2 === 'undefined') {
        //     console.error('Select2 plugin is not loaded. Please include select2.js in your project.');
        //     return;
        // }
        console.log($('#FormValidTran').attr('action'))


        $(".contacts").select2({
            ajax: {
                url: "<?= \yii\helpers\Url::to(['purchase/contactlist']) ?>",
                type: "GET",
                dataType: "json",
                data: function(params) {
                    return {
                        q: params.term,
                    };
                },
                processResults: function(data) {
                    console.log(data); // Tetap tampilkan data untuk debugging

                    // Transformasi struktur data dari {"data":[...]} menjadi format yang diharapkan Select2
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
            placeholder: "<?= $model->getAttributeLabel('contactid') ?>",
            allowClear: true,
            templateResult: formatContact,
            templateSelection: formatContactSelection,
            dropdownParent: $('#modal_form_tran').length ? $('#modal_form_tran') : $(document.body)
        });

        // Format tampilan contact dalam dropdown
        function formatContact(contact) {
            if (!contact.id) return contact.text;

            // Membuat tampilan yang lebih informatif dengan menampilkan nomor telepon dan email
            var $container = $(
                '<div class="select2-result-contact clearfix">' +
                '<div class="select2-result-contact__name">' + contact.text + '</div>' +
                (contact.contact_phone1 ? '<div class="select2-result-contact__phone"><i class="fa fa-phone me-1"></i> ' + contact.contact_phone1 + '</div>' : '') +
                (contact.contact_email1 ? '<div class="select2-result-contact__email"><i class="fa fa-envelope me-1"></i> ' + contact.contact_email1 + '</div>' : '') +
                '</div>'
            );

            return $container;
        }

        // Format tampilan contact yang dipilih
        function formatContactSelection(contact) {
            return contact.text || contact.id;
        }


        // Check if flatpickr is loaded
        if (typeof flatpickr === 'function') {
            // Initialize flatpickr for date inputs
            $("#tranDate").flatpickr({
                allowClear: true,
                enableTime: false,
                dateFormat: "d-m-Y",
                defaultDate: "<?= date('d-m-Y') ?>"
            });

            $("#tranDueDate").flatpickr({
                allowClear: true,
                enableTime: false,
                dateFormat: "d-m-Y",
                defaultDate: "<?= date('d-m-Y', strtotime('+30 days')) ?>"
            });
        } else {
            console.warn('Flatpickr not loaded. Falling back to basic date inputs.');
            // If flatpickr is not available, at least set default values
            $("#tranDate").val("<?= date('d-m-Y') ?>");
            $("#tranDueDate").val("<?= date('d-m-Y', strtotime('+30 days')) ?>");
        }

        // Products table functionality
        let productCounter = 0;
        let oldRow = "";
        // Add product row button click handler
        $("#add-product-row").on("click", function() {
            addProductRow();
        });

        // Function to add a new product row
        function addProductRow() {
            // Hide empty message row if visible
            $("#empty-row").hide();

            const rowId = `product-row-${productCounter++}`;
            const newRow = `
   <tr id="${rowId}" class="product-item">
    <td class='col-lg-4 col-md-4 col-sm-4' style='max-width: 150px; overflow: auto; white-space: nowrap;'>
        <select class="form-select product-select" data-row="${rowId}" required>
            <option value="${oldRow}"></option>
        </select>
    </td>
            <td>
                <input type="number" class="form-control product-quantity" min="1" value="1" required>
            </td>
            <td>
                <input type="text" class="form-control product-price" min="0" value="0" readonly>
            </td>
            <td>
                <span class="product-subtotal">0</span>
            </td>
            <td>
            <button type="button" class="btn btn-sm btn-icon delete-row btn-danger delete-variant" data-row="${rowId}">
                <i class="fas fa-trash"></i>
            </button>

            </td>
        </tr>
        `;

            $("#product-rows").append(newRow);

            // Check if Select2 is available
            if (typeof $.fn.select2 === 'function') {
                // Initialize Select2 for the product dropdown
                $(`#${rowId} .product-select`).select2({
                    ajax: {
                        url: "<?= \yii\helpers\Url::to(['purchase/varianlist']) ?>",
                        type: "GET",
                        dataType: "json",
                        data: function(params) {
                            return {
                                q: params.term, // Send the search term to filter results
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.results // Process and return the results for the dropdown
                            };
                        },
                        cache: true
                    },
                    placeholder: "Pilih Produk",
                    allowClear: true,
                    dropdownParent: $('#modal_form_tran').length ? $('#modal_form_tran') : $(document.body),

                    // Customize the result item template
                    templateResult: function(item) {
                        if (item.loading) {
                            return item.text;
                        }
                        // Customize the display for the result item
                        var $container = $(
                            "<div class='select2-result-item clearfix'>" +
                            "<strong><i class='fa-solid fa-barcode'></i> <span style='color : black; opacity : 0.75;'>" + item.sku + " </span></strong><br>" +
                            "<strong>" + item.text + "</strong><br>" +
                            "</div>"
                        );

                        return $container;
                    },

                    // Customize the selected item template
                    templateSelection: function(item) {
                        if (!item.id) { // If no item is selected, just return the placeholder
                            return "Pilih Produk";
                        }

                        var $selection = $(
                            "<div class='select2-result-item clearfix'>" +
                            "<strong><i class='fa-solid fa-barcode'></i> <span style='color : black; opacity : 0.75;'>" + item.sku + " </span></strong><br>" +
                            "<strong>" + item.text + "</strong><br>" +
                            "</div>"
                        );

                        // Customize how the selected item will appear
                        return $selection; // You can modify this to show additional data, like `sku` or `description`
                    }
                }).on('select2:select', function(e) {
                    // When a product is selected, fetch its price
                    const varianId = e.params.data.id;
                    const rowId = $(this).data('row');

                    // Perbarui oldRow dengan opsi terbaru
                    oldRow = $(`#${rowId} .product-select`).html();

                    // Fetch product price
                    $.ajax({
                        url: "<?= \yii\helpers\Url::to(['purchase/getprice']) ?>",
                        type: "GET",
                        data: {
                            id: varianId
                        },
                        success: function(response) {
                            if (response.success) {
                                const price = parseInt(response.price)
                                const locale = price.toLocaleString('id-ID')
                                // console.log()
                                $(`#${rowId} .product-price`).val(price.toLocaleString('id-ID'));
                                console.log(price, locale)
                                updateRowSubtotal(rowId);
                            }
                        }
                    });
                });
            } else {
                console.error('Select2 plugin is not loaded.');
            }


            // Set up event handlers for quantity changes
            $(`#${rowId} .product-quantity`).on('change', function() {
                updateRowSubtotal(rowId);
            });

            // Set up delete button handler
            $(`#${rowId} .delete-row`).on('click', function() {
                $(`#${$(this).data('row')}`).remove();

                // Show empty row if no products
                if ($('.product-item').length === 0) {
                    $("#empty-row").show();
                }

                calculateTotal();
                updateTransactionDetails();
            });
        }

        // Function to update a row's subtotal
        function updateRowSubtotal(rowId) {
            const quantity = parseInt($(`#${rowId} .product-quantity`).val()) || 0;

            // Remove any non-numeric characters from the price and convert it to an integer
            const price = parseInt($(`#${rowId} .product-price`).val().replace(/[^\d]/g, '')) || 0;

            console.log(price); // Checking the price (should be an integer without formatting)

            const subtotal = quantity * price;

            // Display the subtotal formatted with thousands separator (but no Rupiah formatting)
            $(`#${rowId} .product-subtotal`).text(subtotal.toLocaleString('id-ID'));

            calculateTotal();
            updateTransactionDetails();
        }


        // Function to calculate the total amount
        function calculateTotal() {
            let total = 0;

            $('.product-item').each(function() {
                const quantity = parseInt($(this).find('.product-quantity').val()) || 0;
                const price = parseInt($(this).find('.product-price').val().replace(/[^\d]/g, '')) || 0;
                total += quantity * price;
            });

            $('#total-amount').text(total.toLocaleString('id-ID'));
        }

        // Function to update the hidden transaction details field
        function updateTransactionDetails() {
            const details = [];

            $('.product-item').each(function() {
                const productSelect = $(this).find('.product-select');
                const varianId = productSelect.val();

                if (varianId) {
                    // Bersihkan format angka Indonesia (hapus semua titik pemisah ribuan)
                    const hargaText = $(this).find('.product-price').val().replace(/\./g, '');

                    details.push({
                        varianid: varianId,
                        jumlah: parseInt($(this).find('.product-quantity').val()) || 0,
                        itemsubtotal: parseInt(hargaText) || 0 // Ubah ke parseInt dan gunakan hasil pembersihan format
                    });
                }
            });

            $('#transaction-details').val(JSON.stringify(details));
        }

        // Form submission handler
        $('#FormValidTran').on('submit', function(e) {
            e.preventDefault();

            // Tambahkan di awal fungsi submit setelah e.preventDefault()
            console.log('Form action URL:', $('#FormValidTran').attr('action'));
            console.log('Contact value:', $('.contacts').val());
            console.log('Transaction details:', $('#transaction-details').val());

            let isValid = true;
            let contactSelect = $('.contacts');

            // Check if contact is selected
            if (!contactSelect.val()) {
                contactSelect.addClass('is-invalid');
                isValid = false;
            } else {
                contactSelect.removeClass('is-invalid');
            }

            // Check if at least one product is added
            if ($('.product-item').length === 0) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: "Error!",
                        text: "Anda perlu menambahkan minimal satu produk ke transaksi.",
                        icon: "error",
                        confirmButtonColor: "#d33",
                        confirmButtonText: "OK"
                    });
                } else {
                    alert("Anda perlu menambahkan minimal satu produk ke transaksi.");
                }
                isValid = false;
            } else {
                // Validate each product has a selection
                // $('.product-select').each(function() {
                //     if (!$(this).val()) {
                //         $(this).addClass('is-invalid');
                //         isValid = false;
                //     } else {
                //         $(this).removeClass('is-invalid');
                //     }
                // });
            }

            if (!isValid) return;

            // Format dates if needed
            let tranDate = $('#tranDate').val();
            let tranDueDate = $('#tranDueDate').val();

            // Update hidden details field one final time
            updateTransactionDetails();

            // AJAX submission
            let isSubmitting = false;

            if (isSubmitting) return;
            isSubmitting = true;

            let btn = $('#btnsubmit');
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');

            console.log('Form yang akan dikirim:', new FormData($('#FormValidTran')[0]));

            $.ajax({
                url: $('#FormValidTran').attr('action'),
                type: 'POST',
                data: new FormData($('#FormValidTran')[0]),
                processData: false,
                contentType: false,
                success: function(response) {
                    isSubmitting = false;
                    btn.prop('disabled', false).html('Simpan');
                    console.log('Form data entries:');

                    if (response?.success) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: "Success!",
                                text: "Transaksi berhasil disimpan.",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            alert("Transaksi berhasil disimpan.");
                        }

                        // Better modal handling
                        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                            // Bootstrap 5 way
                            var modalElement = document.getElementById('modal_form_tran');
                            if (modalElement) {
                                var modalInstance = bootstrap.Modal.getInstance(modalElement);
                                if (modalInstance) {
                                    modalInstance.hide();
                                }
                            }
                        } else if (typeof $.fn.modal === 'function' && $('#modal_form_tran').length) {
                            // Bootstrap 4 way (jQuery)
                            try {
                                $('#modal_form_tran').modal('hide');
                            } catch (e) {
                                console.error('Error hiding modal:', e);
                                $('#modal_form_tran').hide();
                            }
                        } else {
                            // Fallback: direct hide
                            console.log('Modal functionality not found, hiding element directly');
                            $('#modal_form_tran').hide();
                        }

                        // Handle datatables
                        if ($('#datatable').length > 0) {
                            if ($.fn.DataTable && typeof $('#datatable').DataTable === 'function') {
                                try {
                                    $('#datatable').DataTable().ajax.reload();
                                } catch (e) {
                                    console.error('Error reloading datatable:', e);
                                    window.location.reload();
                                }
                            } else {
                                window.location.reload();
                            }
                        } else {
                            window.location.reload();
                        }
                    } else {
                        if (typeof Swal !== 'undefined') {
                            console.log('INI YANG ERROR');
                            Swal.fire({
                                title: "Error!",
                                text: response?.pesan || "Terjadi kesalahan saat menyimpan transaksi.",
                                icon: "error",
                                confirmButtonColor: "#d33",
                                confirmButtonText: "OK"
                            });
                        } else {
                            alert(response?.pesan || "Terjadi kesalahan saat menyimpan transaksi.");
                        }
                    }
                },
                error: function(xhr) {
                    isSubmitting = false;
                    btn.prop('disabled', false).html('Simpan');

                    if (typeof Swal !== 'undefined') {
                        console.log('ENGGA LAH! INI YANG ERROR');
                        Swal.fire({
                            title: "Error!",
                            text: "Terjadi kesalahan saat menyimpan transaksi.",
                            icon: "error",
                            confirmButtonColor: "#d33",
                            confirmButtonText: "OK"
                        });
                    } else {
                        alert("Terjadi kesalahan saat menyimpan transaksi.");
                    }
                }
            });
        });

        // Add first product row by default
        addProductRow();
    });
</script>