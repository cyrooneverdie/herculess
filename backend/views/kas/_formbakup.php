<?php

use yii\helpers\Json;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$detailsDataJson = isset($detailsData) ? Json::encode($detailsData) : '[]';
$invoiceDataJson = isset($invoiceData) ? Json::encode($invoiceData) : 'null';
$invoiceDetailsJson = isset($invoiceDetails) ? Json::encode($invoiceDetails) : '[]';
$kastype = Yii::$app->request->get('kastype', ''); // Ambil langsung dari parameter URL
?>

<?php
$form = ActiveForm::begin([
    'id' => 'FormValidCash',
    'method' => 'post',
    'options' => [
        'enctype' => 'multipart/form-data',
    ],
    'validateOnSubmit' => true,
]);
?>

<div class="modal-body">
    <!-- Baris Pertama: No, Ref, Tanggal -->
    <div class="row mb-4">
        <!-- No -->
        <div class="col-md-3">
            <div class="form-group">
                <label class="form-label"><?= Yii::$app->lang->t('cashbackend', 'cashbackend2') ?></label>
                <?= $form->field($model, 'cashno', ['template' => '{input}{error}', 'options' => ['class' => '']])
                    ->textInput(['class' => 'form-control', 'readonly' => true])
                    ->label(false); ?>
            </div>
        </div>

        <!-- Ref -->
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label"><?= Yii::$app->lang->t('cashbackend', 'cashbackend15') ?></label>
                <div class="input-group">
                    <?= Html::dropDownList(
                        'refid',
                        $model->refid, // Nilai saat ini
                        $model->invoice ? [$model->invoice['tranid'] => $model->invoice['tranno']] : [], // Opsi dari relasi
                        [
                            'id' => 'select-invoice',
                            'class' => 'form-select invoice-select',
                            'data-control' => 'select2',
                            'data-placeholder' => 'Pilih Invoice'
                        ]
                    ) ?>
                    <?= $form->field($model, 'refid', ['template' => '{input}', 'options' => ['class' => 'd-none']])
                        ->hiddenInput(['id' => 'refid-hidden'])
                        ->label(false); ?>
                </div>
            </div>
        </div>

        <!-- Tanggal -->
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label"><?= Yii::$app->lang->t('cashbackend', 'cashbackend16') ?></label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <?= $form->field($model, 'cashdate', ['template' => '{input}{error}', 'options' => ['class' => '']])
                        ->textInput(['class' => 'form-control', 'id' => 'cashDate'])
                        ->label(false); ?>
                </div>
            </div>
        </div>
    </div>


    <!-- Data Pembayaran -->
    <div class="card shadow-sm mb-4 rounded-lg">
        <div class="card-header bg-light py-3">
            <div class="d-flex align-items-center">
                <i class="fas fa-file-invoice text-primary me-2"></i>
                <h5 class="mb-0 fw-bold"><?= Yii::$app->lang->t('cashbackend', 'cashbackend17') ?></h5>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle rounded-lg overflow-hidden" id="details-table">
                    <thead class="table-light text-nowrap border-2">
                        <tr>
                            <th width="5%" class="ps-4 py-3">No</th>
                            <th width="65%" class="py-3">Detail</th>
                            <th width="30%" class="text-end pe-4 py-3"><?= Yii::$app->lang->t('cashbackend', 'cashbackend18') ?></th>
                        </tr>
                    </thead>
                    <tbody id="detail-rows" class="border-2">
                        <!-- Detail rows will be added here dynamically -->
                        <tr id="empty-row">
                            <td colspan="3" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-receipt text-muted opacity-50 mb-3 fs-1"></i>
                                    <span class="text-muted"><?= Yii::$app->lang->t('cashbackend', 'cashbackend20') ?></span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="border-top">
                        <tr class="bg-light text-dark">
                            <td colspan="2" class="text-end ps-4 py-3">Total</td>
                            <td class="text-end pe-4 py-3" id="total-tagihan">0</td>
                            <input type="hidden" id="total" name="Kas[subtotal]">
                        </tr>
                        <tr>
                            <td colspan="2" class="text-end ps-4 py-3"><?= Yii::$app->lang->t('cashbackend', 'cashbackend19') ?></td>
                            <td class="pe-4 py-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">Rp</span>
                                    <input type="text" id="total-payment" class="form-control form-control-lg text-end border-start-0"
                                        name="Kas[totalpaid]" value="<?= $model->totalpaid?>">
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Keterangan -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="form-group">
                <label class="form-label">Keterangan</label>
                <?= $form->field($model, 'note', ['template' => '{input}{error}', 'options' => ['class' => '']])
                    ->textarea(['rows' => 3, 'class' => 'form-control', 'placeholder' => 'Keterangan'])
                    ->label(false); ?>
            </div>
        </div>
    </div>

    <!-- Hidden input to store details as JSON -->
    <input type="hidden" name="details" id="cash-details" value="0">
    <!-- Hidden input untuk menyimpan contactid dari invoice -->
    <?= $form->field($model, 'contactid', ['template' => '{input}', 'options' => ['class' => 'd-none']])
        ->hiddenInput(['id' => 'contact-hidden'])
        ->label(false); ?>

    <!-- Hidden input untuk menandai mode edit atau create -->
    <input type="hidden" id="is-edit-mode" value="<?= $model->totalpaid ?>">
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
    <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Update', ['id' => 'btnsubmit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

<script>
    var formIsSubmitting = false;

    // Data dari controller untuk edit mode
    var initialDetailsData = <?= $detailsDataJson ?>;
    var initialInvoiceData = <?= $invoiceDataJson ?>;
    var initialInvoiceDetails = <?= $invoiceDetailsJson ?>;

    // Detail manager - handle details
    var detailManager = {
        // Store invoice details
        invoiceDetails: [],

        // Total invoice amount
        totalAmount: 0,

        // Menyimpan total pembayaran sebelumnya (untuk mode edit)
        previousPayment: "<?= $model->totalpaid ?>",

        // Add detail from invoice
        addInvoiceDetails: function(invoiceId, invoiceNo, invoiceDetails, contactId) {
            // Clear existing rows
            $("#detail-rows").empty();
            this.invoiceDetails = [];
            this.totalAmount = 0;

            // Set contact ID for the cash transaction
            $("#contact-hidden").val(contactId);

            if (!invoiceDetails || invoiceDetails.length === 0) {
                $("#detail-rows").html('<tr id="empty-row"><td colspan="3" class="text-center text-muted py-4 border">Tidak ada detail yang tersedia untuk invoice ini</td></tr>');
                this.updateTotals(0);
                return;
            }

            // Group invoice details by variant
            const groupedDetails = this.groupDetailsByVariant(invoiceDetails);

            // Add each variant as a row
            let counter = 1;
            for (const variant in groupedDetails) {
                const detail = groupedDetails[variant];
                this.addDetailRow(counter, detail, invoiceId, invoiceNo);
                this.totalAmount += parseFloat(detail.tagihan || 0);
                counter++;
            }

            // Hanya perbarui nilai input jumlah bayar jika dalam mode edit
            // Jika mode create, kita biarkan tetap 0 untuk diisi manual oleh user
            if (this.previousPayment > 0) {
                $("#total-payment").val(this.formatNumber(this.previousPayment));
            } else {
                $("#total-payment").val();
            }

            this.updateTotals(this.totalAmount);
            this.updateCashDetails();
        },

        // Group invoice details by variant ID
        groupDetailsByVariant: function(details) {
            const grouped = {};

            details.forEach(detail => {
                const key = detail.varianid || 'unknown';
                if (!grouped[key]) {
                    grouped[key] = {
                        deskripsi: detail.deskripsi || 'Produk',
                        tagihan: 0,
                        qty: 0,
                        jumlah: 0,
                        varianid: detail.varianid
                    };
                }

                grouped[key].tagihan += parseFloat(detail.itemsubtotal || 0);
                grouped[key].qty += parseInt(detail.jumlah || 0);
            });

            return grouped;
        },

        // Add a detail row
        addDetailRow: function(index, detail, invoiceId, invoiceNo) {
            const deskripsi = detail.deskripsi || 'Produk';
            const tagihan = detail.tagihan || 0;

            const newRow = `
                <tr class="detail-item" data-varianid="${detail.varianid || ''}">
                    <td class="border text-center">${index}</td>
                    <td class="border text-capitalize">${deskripsi}</td>
                    <td class="border text-end detail-tagihan">${this.formatNumber(tagihan)}</td>
                    <input type="hidden" name="details[${index-1}][cashdetailtype]" value="${deskripsi}">
                    <input type="hidden" name="details[${index-1}][qty]" value="${detail.qty || 1}">
                    <input type="hidden" name="details[${index-1}][amount]" value="${tagihan}">
                    <input type="hidden" name="details[${index-1}][varianid]" value="${detail.varianid || ''}">
                    <input type="hidden" name="details[${index-1}][invoiceid]" value="${invoiceId || ''}">
                    <input type="hidden" name="details[${index-1}][invoiceno]" value="${invoiceNo || ''}">
                </tr>
            `;

            $('#detail-rows').append(newRow);

            // Store detail for later use
            this.invoiceDetails.push({
                cashdetailtype: deskripsi,
                qty: detail.qty || 1,
                amount: tagihan,
                paid: 0,
                varianid: detail.varianid || '',
                invoiceid: invoiceId || '',
                invoiceno: invoiceNo || ''
            });
        },

        // Add existing detail row (for edit mode)
        addExistingDetailRow: function(detail, index) {
            const cashdetailid = detail.cashdetailid || '';
            const deskripsi = detail.cashdetailtype || '';
            const amount = detail.amount || 0;

            // Simpan jumlah pembayaran sebelumnya
            this.previousPayment = amount;

            const newRow = `
                <tr class="detail-item" data-detailid="${cashdetailid}">
                    <td class="border text-center">${index + 1}</td>
                    <td class="border text-capitalize">${deskripsi}</td>
                    <td class="border text-end detail-tagihan">${this.formatNumber(amount)}</td>
                    <input type="hidden" name="details[${index}][cashdetailid]" value="${cashdetailid}">
                    <input type="hidden" name="details[${index}][cashdetailtype]" value="${deskripsi}">
                    <input type="hidden" name="details[${index}][qty]" value="${detail.qty || 1}">
                    <input type="hidden" name="details[${index}][amount]" value="${amount}">
                    <input type="hidden" name="details[${index}][varianid]" value="${detail.varianid || ''}">
                    <input type="hidden" name="details[${index}][invoiceid]" value="${detail.invoiceid || ''}">
                    <input type="hidden" name="details[${index}][invoiceno]" value="${detail.invoiceno || ''}">
                </tr>
            `;

            $('#detail-rows').append(newRow);

            // Add to invoiceDetails for later use
            this.invoiceDetails.push({
                cashdetailid: cashdetailid,
                cashdetailtype: deskripsi,
                qty: detail.qty || 1,
                amount: amount,
                paid: detail.paid || 0,
                ord: detail.ord || 1,
                iscut: detail.iscut || 0,
                varianid: detail.varianid || '',
                invoiceid: detail.invoiceid || '',
                invoiceno: detail.invoiceno || ''
            });

            return amount;
        },

        // Update totals based on payment amount
        updateTotals: function(totalTagihan) {
            $('#total-tagihan').text(this.formatNumber(totalTagihan));
            $('#total').val(this.formatNumber(totalTagihan));
        },

        // Update cash details for submission
        updateCashDetails: function() {
            const details = [];
            const totalPayment = parseFloat($("#total-payment").val().replace(/\./g, '')) || 0;

            // If we have details with cashdetailid (edit mode), preserve them
            if (this.invoiceDetails.length > 0 && this.invoiceDetails[0].cashdetailid) {
                // We're in edit mode, preserve the first detail with cashdetailid and update amount
                const firstDetail = this.invoiceDetails[0];
                const detail = {
                    cashdetailid: firstDetail.cashdetailid,
                    cashdetailtype: firstDetail.cashdetailtype,
                    qty: firstDetail.qty,
                    amount: totalPayment, // Update amount to new total payment
                    paid: firstDetail.paid || 0,
                    ord: firstDetail.ord || 1,
                    iscut: firstDetail.iscut || 0
                };
                details.push(detail);
            }
            // Otherwise create a new detail
            else if (this.invoiceDetails.length > 0) {
                const firstDetail = this.invoiceDetails[0];
                // Create a single payment detail with the total amount
                const detail = {
                    cashdetailtype: `Pembayaran untuk ${firstDetail.invoiceno || 'invoice'}`,
                    qty: 1,
                    amount: totalPayment,
                    paid: 0,
                    ord: 1,
                    iscut: 0,
                    varianid: '', // Could be empty or use the first one
                    invoiceid: firstDetail.invoiceid || '',
                    invoiceno: firstDetail.invoiceno || ''
                };
                details.push(detail);
            }

            $('#cash-details').val(JSON.stringify(details));
        },

        // Format number with thousand separator
        formatNumber: function(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        },

        // Parse number (menghilangkan format)
        parseNumber: function(formattedNumber) {
            if (typeof formattedNumber === 'string') {
                return parseFloat(formattedNumber.replace(/\./g, '').replace(/,/g, '.')) || 0;
            }
            return parseFloat(formattedNumber) || 0;
        },

        // Load data for edit mode
        loadExistingData: function() {
            if (initialDetailsData && initialDetailsData.length > 0) {
                $("#detail-rows").empty();
                let totalAmount = 0;

                initialDetailsData.forEach((detail, index) => {
                    const amount = this.addExistingDetailRow(detail, index);
                    totalAmount += parseFloat(amount);
                });

                this.totalAmount = totalAmount;
                this.updateTotals(totalAmount);

                // Set jumlah bayar ke nilai sebelumnya jika dalam mode edit
                if (this.previousPayment != null) {
                    $("#total-payment").val(this.formatNumber(this.previousPayment));
                    console.log(this.previousPayment);
                }

                this.updateCashDetails();

                return true;
            }
            return false;
        }
    };

    $(document).ready(function() {
        $('input').attr('autocomplete', 'off');
        // Format dan validasi input total payment
        // $("#total-payment").on('keypress', function(e) {
        //     // Hanya izinkan angka dan titik
        //     var charCode = (e.which) ? e.which : e.keyCode;
        //     if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        //         return false;
        //     }
        //     return true;
        // });

        // $("#total-payment").on('input', function(e) {
        //     var value = $(this).val().replace(/\./g, '');
        //     if (value !== "") {
        //         $(this).val(detailManager.formatNumber(value));
        //     }
        // });

        const priceInput = document.getElementById('total-payment');
        if (priceInput) {
            priceInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^\d]/g, "");
                priceInput.value = value !== "" ? detailManager.formatNumber(value) : "";
            });

            // Tambahkan event listener untuk blur (saat focus keluar dari input)
            priceInput.addEventListener('blur', function() {
                let value = priceInput.value.replace(/[^\d]/g, '');
                if (value !== "") {
                    priceInput.value = detailManager.formatNumber(value);
                }
            });
        }

        // Initialize the form
        initForm();

        function initForm() {
            var kastype = <?= json_encode($kastype) ?>;
            if (kastype == "KM") {
                var url = "<?= \yii\helpers\Url::to(['invoicelist?kastype=KM']) ?>";
            } else {
                var url = "<?= \yii\helpers\Url::to(['invoicelist?kastype=KK']) ?>";
            }

            // Initialize invoice select2
            if (typeof $.fn.select2 === 'function') {
                $("#select-invoice").select2({
                    ajax: {
                        url: url,
                        type: "GET",
                        dataType: "json",
                        data: function(params) {
                            return {
                                q: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.data.map(function(invoice) {
                                    return {
                                        id: invoice.tranid,
                                        text: invoice.tranno,
                                        trandate: invoice.trandate,
                                        contact_name: invoice.contact_name,
                                        contact_id: invoice.contactid
                                    };
                                })
                            };
                        },
                        cache: true
                    },
                    placeholder: "Pilih Invoice",
                    allowClear: true,
                    templateResult: formatInvoice,
                    templateSelection: formatInvoiceSelection,
                    dropdownParent: $('#FormValidCash').length ? $('#FormValidCash') : $(document.body)
                }).on('select2:select', function(e) {
                    const invoiceId = e.params.data.id;
                    const invoiceNo = e.params.data.text;
                    const contactId = e.params.data.contact_id;

                    // Set the hidden reference id
                    $('#refid-hidden').val(invoiceId);

                    // Fetch invoice details
                    $.ajax({
                        url: "<?= \yii\helpers\Url::to(['invoicedetail']) ?>",
                        type: "GET",
                        dataType: "json",
                        data: {
                            id: invoiceId
                        },
                        success: function(response) {
                            if (response && response.details) {
                                detailManager.addInvoiceDetails(invoiceId, invoiceNo, response.details, contactId);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching invoice details:", error);
                        }
                    });
                }).on('select2:clear', function(e) {
                    $('#refid-hidden').val('');
                    $("#detail-rows").html('<tr id="empty-row"><td colspan="3" class="text-center text-muted py-4 border">Silakan pilih invoice untuk menampilkan data pembayaran</td></tr>');
                    detailManager.invoiceDetails = [];
                    detailManager.totalAmount = 0;
                    detailManager.updateTotals(0);
                    $("#total-payment").val("0");
                });
            }

            // Initialize datepickers
            if (typeof flatpickr === 'function') {
                $("#cashDate").flatpickr({
                    allowClear: true,
                    enableTime: false,
                    dateFormat: "d-m-Y",
                    defaultDate: "<?= date('d-m-Y') ?>"
                });
            } else {
                console.warn('Flatpickr not loaded. Using basic date inputs.');
                $("#cashDate").val("<?= date('d-m-Y') ?>");
            }

            // Setup event handlers
            setupEventHandlers();

            // Setup form submission
            setupFormSubmission();

            // Load data for edit mode
            setupEditMode();
        }

        function setupEditMode() {
            // If we have initialInvoiceData, select it in the dropdown
            if (initialInvoiceData) {
                var newOption = new Option(initialInvoiceData.tranno, initialInvoiceData.tranid, true, true);
                $('#select-invoice').append(newOption).trigger('change');

                // Set the contact hidden field
                $('#contact-hidden').val(initialInvoiceData.contactid);

                // If we have initialInvoiceDetails, load them
                if (initialInvoiceDetails && initialInvoiceDetails.length > 0) {
                    // Use a setTimeout to ensure select2 has fully initialized
                    setTimeout(function() {
                        detailManager.addInvoiceDetails(
                            initialInvoiceData.tranid,
                            initialInvoiceData.tranno,
                            initialInvoiceDetails,
                            initialInvoiceData.contactid
                        );
                    }, 200);
                }
                // Otherwise try to load from initialDetailsData
                else {
                    detailManager.loadExistingData();
                }
            }
            // If we only have detailsData, load them
            else if (initialDetailsData && initialDetailsData.length > 0) {
                detailManager.loadExistingData();
            }
        }

        function formatInvoice(invoice) {
            if (!invoice.id) return invoice.id;

            var $container = $(
                '<div class="select2-result-invoice clearfix">' +
                '<div class="select2-result-invoice__number fw-bold">' + invoice.text + '</div>' +
                (invoice.trandate ? '<div class="select2-result-invoice__date"><i class="fa fa-calendar me-1"></i> ' + invoice.trandate + '</div>' : '') +
                (invoice.contact_name ? '<div class="select2-result-invoice__contact"><i class="fa fa-user me-1"></i> ' + invoice.contact_name + '</div>' : '') +
                '</div>'
            );

            return $container;
        }

        function formatInvoiceSelection(invoice) {
            return  invoice.text|| invoice.id ;
        }

        function setupEventHandlers() {
            // Clear date button
            $(".btn-clear-date").on("click", function() {
                $("#cashDate").val("").trigger("change");
            });
        }

        function setupFormSubmission() {
            // Reset submission flag
            formIsSubmitting = false;

            // Unbind any existing handlers
            $('#FormValidCash').off('submit');

            // Attach the submit handler
            $('#FormValidCash').on('submit', function(e) {
                e.preventDefault();

                if (formIsSubmitting) {
                    return false;
                }

                $('input').prop('disabled', false);
                formIsSubmitting = true;

                try {
                    // Validation logic
                    let isValid = true;
                    let refIdField = $('#refid-hidden');
                    let contactIdField = $('#contact-hidden');
                    let totalPaymentField = $('#total-payment');

                    if (!refIdField.val()) {
                        $('#select-invoice').next('.select2-container').addClass('is-invalid');
                        isValid = false;
                    } else {
                        $('#select-invoice').next('.select2-container').removeClass('is-invalid');
                    }

                    if (!contactIdField.val()) {
                        // Ini akan tersembunyi, jadi tidak perlu validasi visual
                        isValid = false;
                    }

                    // Ubah nilai menjadi angka murni
                    const paymentAmount = detailManager.parseNumber(totalPaymentField.val());
                    if (paymentAmount <= 0) {
                        totalPaymentField.addClass('is-invalid');
                        isValid = false;
                    } else {
                        totalPaymentField.removeClass('is-invalid');
                    }

                    if ($('.detail-item').length === 0) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: "Error!",
                                text: "Anda perlu memilih invoice untuk menambahkan detail transaksi.",
                                icon: "error",
                                confirmButtonColor: "#d33",
                                confirmButtonText: "OK"
                            }).then(() => {
                                formIsSubmitting = false;
                            });
                        } else {
                            alert("Anda perlu memilih invoice untuk menambahkan detail transaksi.");
                            formIsSubmitting = false;
                        }
                        isValid = false;
                    }

                    if (!isValid) {
                        formIsSubmitting = false;
                        return false;
                    }

                    // Update cash details based on total payment
                    detailManager.updateCashDetails();

                    // Visual feedback
                    let btn = $('#btnsubmit');
                    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');

                    // AJAX submission
                    $.ajax({
                        url: $('#FormValidCash').attr('action'),
                        type: 'POST',
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response && response.success) {
                                if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        title: "Success!",
                                        text: "Transaksi kas berhasil disimpan.",
                                        icon: "success",
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                } else {
                                    alert("Transaksi kas berhasil disimpan.");
                                }

                                // Close modal
                                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                                    var modalElement = document.getElementById('modal_form_cash');
                                    if (modalElement) {
                                        var modalInstance = bootstrap.Modal.getInstance(modalElement);
                                        if (modalInstance) {
                                            modalInstance.hide();
                                        }
                                    }
                                } else if (typeof $.fn.modal === 'function' && $('#modal_form_cash').length) {
                                    $('#modal_form_cash').modal('hide');
                                } else {
                                    $('#modal_form_cash').hide();
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
                                var errorMessage = "Terjadi kesalahan saat menyimpan transaksi kas.";

                                // Check if we have a specific error message
                                if (response && response.pesan) {
                                    errorMessage = response.pesan;
                                }

                                if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        title: "Error!",
                                        html: errorMessage,
                                        icon: "error",
                                        confirmButtonColor: "#d33",
                                        confirmButtonText: "OK"
                                    });
                                } else {
                                    alert(errorMessage);
                                }
                                btn.prop('disabled', false).html('Submit');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", status, error);

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
                            btn.prop('disabled', false).html('Submit');
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
                    $('#btnsubmit').prop('disabled', false).html('Submit');
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
