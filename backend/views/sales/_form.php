<?php

use yii\helpers\Json;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$detailsDataJson = isset($detailsData) ? Json::encode($detailsData) : '[]';
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
<div class="d-flex flex-column scroll-y px-5 px-lg-10" id="modal_form_tran_scroll">
    <div class="row">
        <div class="mb-7 col-lg-6 col-md-6 col-sm-6">
            <?= $form->field($model, 'tranno')->textInput(['placeholder' => $model->getAttributeLabel('tranno'), 'readOnly' => true]); ?>
        </div>

        <div class="mb-7 col-lg-6 col-md-6 col-sm-6">
            <?= $form->field($model, 'contactid', [
                'errorOptions' => ['class' => 'text-danger mt-3'],
            ])->dropDownList(
                $model->contact ? [$model->contact['contact_id'] => $model->contact['contact_name']] : [],
                [
                    'id' => 'contactSelect',
                    'class' => 'form-select contacts',
                    'data-control' => 'select2',
                    'placeholder' => Yii::$app->lang->t('tran', 'tran_no'),
                ]
            ); ?>
        </div>
    </div>

    <div class="row">
        <div class="mb-7 col-lg-6 col-md-6 col-sm-6">
            <?= $form->field($model, 'trandate', [
                'errorOptions' => ['class' => 'text-danger mt-3'],
            ])->textInput([
                'placeholder' => Yii::$app->lang->t('tran', 'tran_date'),
                'id' => 'tranDate',
                'required' => true,
            ]); ?>
        </div>
        <div class="mb-7 col-lg-6 col-md-6 col-sm-6">
            <?= $form->field($model, 'tranduedate', [
                'errorOptions' => ['class' => 'text-danger mt-3'],
            ])->textInput([
                'placeholder' => Yii::$app->lang->t('tran', 'tran_duedate'),
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
    <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel" data-bs-dismiss="modal"><?= Yii::$app->lang->t('back_home','chat34') ?></button>
    <?= Html::submitButton($model->isNewRecord ? Yii::$app->lang->t('extra','extra16') : Yii::$app->lang->t('extra','extra16'), ['id' => 'btnsubmit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
<!--end::Actions-->
<!--end::Form-->
<?php ActiveForm::end(); ?>

<script>
    var formIsSubmitting = false;

    // Detail manager - handle details like variant manager
    var detailManager = {
        addNewDetail: function() {
            const detailCount = $('.product-item').length;
            const newIndex = detailCount;

            // Hide empty message if visible
            $("#empty-row").hide();

            const newDetail = `
                <tr class="product-item">
                    <td class='col-lg-4 col-md-4 col-sm-4'>
                        <select class="form-select product-select" required>
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <input type="number" name="details[${newIndex}][jumlah]" class="form-control product-quantity" min="1" value="1" required>
                    </td>
                    <td>
                        <input type="text" name="details[${newIndex}][harga]" class="form-control product-price" min="0" value="0" readonly>
                    </td>
                    <td>
                        <span class="product-subtotal">0</span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-icon delete-detail btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

            $('#product-rows').append(newDetail);

            // Initialize select2 for the new row
            this.initializeProductSelect(newIndex);

            // Update all row numbers
            this.renumberDetails();
        },

        initializeProductSelect: function(index) {
            if (typeof $.fn.select2 !== 'function') {
                console.error('Select2 plugin is not loaded.');
                return;
            }

            $('.product-item').eq(index).find('.product-select').select2({
                    ajax: {
                        url: "<?= \yii\helpers\Url::to(['sales/varianlist']) ?>",
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

    // Get SKU either from the item object or from the option's data attribute
    let sku = item.sku;

    // If SKU is undefined, try to get it from the selected option's data
    if (sku === undefined) {
        const $option = $(item.element);
        sku = $option.data('sku') || '';
    }

    var $selection = $(
        "<div class='select2-result-item clearfix'>" +
        (sku ? "<strong><i class='fa-solid fa-barcode'></i> <span style='color : black; opacity : 0.75;'>" + sku + " </span></strong><br>" : "") +
        "<strong>" + item.text + "</strong><br>" +
        "</div>"
    );

    // Customize how the selected item will appear
    return $selection;
}
                }).on('select2:select', function(e) {
                const varianId = e.params.data.id;
                const $row = $(this).closest('.product-item');

                // Store details for later use
                $(this).data('sku', e.params.data.sku || '');
                $(this).data('text', e.params.data.text || '');

                // Set the varianid in a hidden input
                if (!$row.find('input[name$="[varianid]"]').length) {
                    $row.append(`<input type="hidden" name="details[${index}][varianid]" value="${varianId}">`);
                } else {
                    $row.find('input[name$="[varianid]"]').val(varianId);
                }

                // Fetch product price
                $.ajax({
                    url: "<?= \yii\helpers\Url::to(['sales/getprice']) ?>",
                    type: "GET",
                    data: {
                        id: varianId
                    },
                    success: function(response) {
                        if (response.success) {
                            const price = parseInt(response.price);
                            $row.find('.product-price').val(detailManager.formatNumber(price));
                            detailManager.updateRowSubtotal($row);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching price:", error);
                        $row.find('.product-price').val('0');
                        detailManager.updateRowSubtotal($row);
                    }
                });
            });

            // Set up quantity change handler
            $('.product-item').eq(index).find('.product-quantity').on('change', function() {
                detailManager.updateRowSubtotal($(this).closest('.product-item'));
            });
        },

        updateRowSubtotal: function($row) {
            const quantity = parseInt($row.find('.product-quantity').val()) || 0;
            const price = parseInt($row.find('.product-price').val().replace(/[^\d]/g, '')) || 0;
            const subtotal = quantity * price;

            $row.find('.product-subtotal').text(this.formatNumber(subtotal));

            this.calculateTotal();
            this.updateTransactionDetails();
        },

        calculateTotal: function() {
            let total = 0;

            $('.product-item').each(function() {
                const quantity = parseInt($(this).find('.product-quantity').val()) || 0;
                const price = parseInt($(this).find('.product-price').val().replace(/[^\d]/g, '')) || 0;
                total += quantity * price;
            });

            $('#total-amount').text(this.formatNumber(total));
        },

        updateTransactionDetails: function() {
            const details = [];

            $('.product-item').each(function(index) {
                const $productSelect = $(this).find('.product-select');
                const varianId = $(this).find('input[name$="[varianid]"]').val() || $productSelect.val();

                if (varianId) {
                    const priceValue = $(this).find('.product-price').val().replace(/\./g, '');
                    const quantityValue = parseInt($(this).find('.product-quantity').val()) || 0;
                    const price = parseInt(priceValue) || 0;

                    const detail = {
                        varianid: varianId,
                        jumlah: quantityValue,
                        harga: price,
                        itemsubtotal: quantityValue * price
                    };

                    // Include existing trandetailid if present
                    const trandetailid = $(this).find('input[name$="[trandetailid]"]').val();
                    if (trandetailid) {
                        detail.trandetailid = trandetailid;
                    }

                    details.push(detail);
                }
            });

            $('#transaction-details').val(JSON.stringify(details));
            console.log("Updated transaction details:", details); // Add logging for debugging
        },

        renumberDetails: function() {
            $('.product-item').each(function(index) {
                $(this).find('input, select').each(function() {
                    const name = $(this).attr('name');
                    if (name) {
                        const newName = name.replace(/details\[\d+\]/, `details[${index}]`);
                        $(this).attr('name', newName);
                    }
                });
            });
        },

        formatNumber: function(number) {
            return Number(number).toLocaleString('id-ID');
        },

        initializeExistingDetails: function(details) {
    // Clear existing rows
    $("#product-rows").empty();

    if (!details || details.length === 0) {
        // Add empty row
        $("#product-rows").append('<tr id="empty-row"><td colspan="5" class="text-center text-muted">Belum ada produk yang ditambahkan</td></tr>');
        detailManager.addNewDetail();
        return;
    }

    $("#empty-row").hide();

    details.forEach((detail, index) => {
        const newRow = `
        <tr class="product-item">
            <td class='col-lg-4 col-md-4 col-sm-4'>
                <select class="form-select product-select" required>
                    <!-- Empty select that will be filled programmatically -->
                </select>
                <input type="hidden" name="details[${index}][varianid]" value="${detail.varianid}">
                <input type="hidden" name="details[${index}][trandetailid]" value="${detail.trandetailid || ''}">
            </td>
            <td>
                <input type="number" name="details[${index}][jumlah]" class="form-control product-quantity" min="1" value="${detail.jumlah}" required>
            </td>
            <td>
                <input type="text" name="details[${index}][harga]" class="form-control product-price" value="${this.formatNumber(detail.harga)}" readonly>
            </td>
            <td>
                <span class="product-subtotal">${this.formatNumber(detail.itemsubtotal)}</span>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-icon delete-detail btn-danger">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
        `;

        $("#product-rows").append(newRow);

        // Get select element
        const $select = $('.product-item').eq(index).find('.product-select');

        // Initialize select2 on the empty select
        this.initializeProductSelect(index);

        // Create the data object with all required properties
        const optionData = {
            id: detail.varianid,
            text: detail.deskripsi,
            sku: detail.sku || '',
            selected: true
        };

        // Create a new option
        const newOption = new Option(optionData.text, optionData.id, true, true);

        // Add option to select and trigger change for select2 to pick it up
        $select.append(newOption);

        // The crucial part: set the data object with the SKU property
        // This alters select2's internal data cache for this option
        $select.find('option:selected').data('sku', optionData.sku);

        // Also set data directly on select element for your event handlers
        $select.data('sku', optionData.sku);
        $select.data('text', optionData.text);

        // Trigger change to refresh select2
        $select.trigger('change');
    });

    // Update total
    this.calculateTotal();
    this.updateTransactionDetails();
}
    };

    $(document).ready(function() {
        // Initialize the form
        initForm();

        function initForm() {
            // Initialize contact select2
            if (typeof $.fn.select2 === 'function') {
                $(".contacts").select2({
                    ajax: {
                        url: "<?= \yii\helpers\Url::to(['sales/contactlist']) ?>",
                        type: "GET",
                        dataType: "json",
                        data: function(params) {
                            return {
                                q: params.term,
                            };
                        },
                        processResults: function(data) {
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
            }

            // Initialize datepickers
            if (typeof flatpickr === 'function') {
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
                console.warn('Flatpickr not loaded. Using basic date inputs.');
                $("#tranDate").val("<?= date('d-m-Y') ?>");
                $("#tranDueDate").val("<?= date('d-m-Y', strtotime('+30 days')) ?>");
            }

            // Initialize existing details
            var existingDetails = <?= $detailsDataJson ?>;
            detailManager.initializeExistingDetails(existingDetails);

            // Setup event handlers
            setupEventHandlers();

            // Setup form submission
            setupFormSubmission();
        }

        function formatContact(contact) {
            if (!contact.id) return contact.text;

            var $container = $(
                '<div class="select2-result-contact clearfix">' +
                '<div class="select2-result-contact__name">' + contact.text + '</div>' +
                (contact.contact_phone1 ? '<div class="select2-result-contact__phone"><i class="fa fa-phone me-1"></i> ' + contact.contact_phone1 + '</div>' : '') +
                (contact.contact_email1 ? '<div class="select2-result-contact__email"><i class="fa fa-envelope me-1"></i> ' + contact.contact_email1 + '</div>' : '') +
                '</div>'
            );

            return $container;
        }

        function formatContactSelection(contact) {
            return contact.text || contact.id;
        }

        function setupEventHandlers() {
            // Add product button
            $("#add-product-row").on("click", function() {
                detailManager.addNewDetail();
            });

            // Delete detail button - use event delegation like in variant manager
            $(document).off('click', '.delete-detail').on('click', '.delete-detail', function() {
                $(this).closest('.product-item').remove();
                detailManager.renumberDetails();

                if ($('.product-item').length === 0) {
                    $("#empty-row").show();
                }

                detailManager.calculateTotal();
                detailManager.updateTransactionDetails();
            });
        }

        function setupFormSubmission() {
            // Reset submission flag
            formIsSubmitting = false;

            // Unbind any existing handlers
            $('#FormValidTran').off('submit');

            // Attach the submit handler
            $('#FormValidTran').on('submit', function(e) {
                e.preventDefault();

                if (formIsSubmitting) {
                    return false;
                }

                formIsSubmitting = true;

                try {
                    // Validation logic
                    let isValid = true;
                    let contactSelect = $('.contacts');

                    if (!contactSelect.val()) {
                        contactSelect.addClass('is-invalid');
                        isValid = false;
                    } else {
                        contactSelect.removeClass('is-invalid');
                    }

                    if ($('.product-item').length === 0) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: "Error!",
                                text: "Anda perlu menambahkan minimal satu produk ke transaksi.",
                                icon: "error",
                                confirmButtonColor: "#d33",
                                confirmButtonText: "OK"
                            }).then(() => {
                                formIsSubmitting = false;
                            });
                        } else {
                            alert("Anda perlu menambahkan minimal satu produk ke transaksi.");
                            formIsSubmitting = false;
                        }
                        isValid = false;
                    }

                    if (!isValid) {
                        formIsSubmitting = false;
                        return false;
                    }

                    // Update transaction details
                    detailManager.updateTransactionDetails();

                    // Log the form data for debugging
                    console.log("Form data (details):", $('#transaction-details').val());

                    // Visual feedback
                    let btn = $('#btnsubmit');
                    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');

                    // AJAX submission
                    $.ajax({
                        url: $('#FormValidTran').attr('action'),
                        type: 'POST',
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            console.log("Server response:", response); // Add logging for debugging

                            if (response && response.success) {
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

                                // Close modal
                                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                                    var modalElement = document.getElementById('modal_form_tran');
                                    if (modalElement) {
                                        var modalInstance = bootstrap.Modal.getInstance(modalElement);
                                        if (modalInstance) {
                                            modalInstance.hide();
                                        }
                                    }
                                } else if (typeof $.fn.modal === 'function' && $('#modal_form_tran').length) {
                                    $('#modal_form_tran').modal('hide');
                                } else {
                                    $('#modal_form_tran').hide();
                                }

                                // Reload data
                                if ($('#datatable').length > 0 && $.fn.DataTable) {
                                    $('#datatable').DataTable().ajax.reload();
                                } else {
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 1000);
                                }
                            } else {
                                // Improved error handling
                                var errorMessage = "Terjadi kesalahan saat menyimpan transaksi.";

                                // Check if we have a specific error message
                                if (response && response.pesan) {
                                    errorMessage = response.pesan;
                                }

                                // If the error message is empty, provide a generic message
                                if (!errorMessage || errorMessage === "") {
                                    errorMessage = "Terjadi kesalahan yang tidak teridentifikasi. Silakan cek log sistem.";
                                    console.error("Empty error message received. Full response:", response);
                                }

                                if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        title: "Error!",
                                        html: errorMessage, // Use html to properly render HTML in error message
                                        icon: "error",
                                        confirmButtonColor: "#d33",
                                        confirmButtonText: "OK"
                                    });
                                } else {
                                    alert(errorMessage);
                                }
                                btn.prop('disabled', false).html('Simpan');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", status, error);
                            console.error("Response:", xhr.responseText);

                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: "Error!",
                                    text: "Terjadi kesalahan saat mengirim data: " + error,
                                    icon: "error",
                                    confirmButtonColor: "#d33",
                                    confirmButtonText: "OK"
                                });
                            } else {
                                alert("Terjadi kesalahan saat mengirim data: " + error);
                            }
                            btn.prop('disabled', false).html('Simpan');
                        },
                        complete: function() {
                            formIsSubmitting = false;
                        }
                    });
                } catch (error) {
                    console.error('Unexpected error:', error);
                    formIsSubmitting = false;

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: "Error!",
                            text: "Terjadi kesalahan tak terduga: " + error.message,
                            icon: "error",
                            confirmButtonColor: "#d33",
                            confirmButtonText: "OK"
                        });
                    } else {
                        alert("Terjadi kesalahan tak terduga: " + error.message);
                    }

                    // Re-enable submit button
                    $('#btnsubmit').prop('disabled', false).html('Simpan');
                }

                return false;
            });

            // Additional protection for submit button
            $('#btnsubmit').on('click', function() {
                if (formIsSubmitting) {
                    return false;
                }
            });
        }
    });
</script>
