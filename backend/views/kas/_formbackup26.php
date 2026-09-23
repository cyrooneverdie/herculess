<?php

use yii\helpers\Json;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$detailsDataJson = isset($detailsData) ? Json::encode($detailsData) : '[]';
$invoiceDataJson = isset($invoiceData) ? Json::encode($invoiceData) : 'null';
$invoiceDetailsJson = isset($invoiceDetails) ? Json::encode($invoiceDetails) : '[]';
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
                <label class="form-label">No</label>
                <?= $form->field($model, 'kasnomor', ['template' => '{input}{error}', 'options' => ['class' => '']])
                    ->textInput(['class' => 'form-control', 'readonly' => true])
                    ->label(false); ?>
            </div>
        </div>

        <!-- Ref -->
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">Ref</label>
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
                <label class="form-label">Tanggal</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <?= $form->field($model, 'kasdate', ['template' => '{input}{error}', 'options' => ['class' => '']])
                        ->textInput(['class' => 'form-control', 'id' => 'cashDate'])
                        ->label(false); ?>
                </div>
            </div>
        </div>
    </div>


    <!-- Baris untuk Akun ID -->
    <div class="row mb-4">
        <div class="col-md-3">
            <?= $form->field($model, 'akunid', [
                'errorOptions' => ['class' => 'text-danger mt-3'],
            ])->dropDownList(
                // $model->akunid, // Nilai saat ini
                $model->akun ? [$model->akun['coa_id'] => $model->akun['coa_name_id']] : [], // Opsi dari relasi akun
                // [],
                [
                    'id' => 'akunSelect',
                    'class' => 'form-select akun-main',
                    'data-control' => 'select2',
                    'placeholder' => Yii::$app->lang->t('tran', 'tran_no'),
                ]
            ); ?>
        </div>
    </div>

    <!-- Data Pembayaran -->
    <div class="card shadow-sm mb-4 rounded-lg">
        <div class="card-header bg-light py-3">
            <div class="d-flex w-100 align-items-center justify-content-between">
                <div>
                    <i class="fas fa-file-invoice text-primary me-2"></i>
                    <h5 class="mb-0 fw-bold d-inline">Data Pembayaran</h5>
                </div>
                <button type="button" id="btn-add-payment" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Tambah Pembayaran
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle rounded-lg overflow-hidden" id="details-table">
                    <thead class="table-light text-nowrap border-2">
                        <tr>
                            <th width="5%" class="ps-4 py-3">No</th>
                            <th width="20%" class="py-3">Akun</th>
                            <th width="25%" class="py-3">Detail</th>
                            <th width="15%" class="py-3">Jumlah</th>
                            <th width="15%" class="py-3">Harga</th>
                            <th width="15%" class="text-end pe-4 py-3">Tagihan</th>
                            <th width="5%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="detail-rows" class="border-2">
                        <!-- Rows will be added dynamically -->
                    </tbody>
                    <tfoot class="border-top">
                        <tr class="bg-light text-dark">
                            <td colspan="5" class="text-end ps-4 py-3">SubTotal</td>
                            <td class="text-end pe-4 py-3" id="total-tagihan">0</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-end ps-4 py-3">Jumlah Bayar</td>
                            <td class="pe-4 py-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">Rp</span>
                                    <input type="text" id="total-payment" class="form-control form-control-lg text-end border-start-0"
                                        name="Cash[totalpaid]" value="0">
                                </div>
                            </td>
                            <td></td>
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
                <?= $form->field($model, 'catatan', ['template' => '{input}{error}', 'options' => ['class' => '']])
                    ->textarea(['rows' => 3, 'class' => 'form-control', 'placeholder' => 'Keterangan'])
                    ->label(false); ?>
            </div>
        </div>
    </div>

    <!-- Hidden input to store details as JSON -->
    <input type="hidden" id="cash-details" name="cash_details" value="[]">
    <input type="hidden" id="contact-hidden" name="contactid" value="">
    <input type="hidden" id="is-edit-mode" value="<?= !$model->isNewRecord ? '1' : '0' ?>">
    <input type="hidden" id="invoice-mode" value="0">
    <input type="hidden" id="row-counter" value="0">

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
    <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Update', ['id' => 'btnsubmit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

<!-- Template for row -->
<template id="payment-row-template">
    <tr class="detail-item payment-row" data-manual="0" data-index="{index}">
        <td class="border text-center">{index}</td>
        <td class="border">
            <select id="row-akun-{index}" name="details[{index}][akunid]" class="form-select detail-akun"
                data-control="select2">
            </select>
        </td>
        <td class="border">
            <input type="text" class="form-control detail-description" name="details[{index}][cashdetailtype]" value="">
        </td>
        <td class="border">
            <input type="text" class="form-control detail-jumlah" name="details[{index}][jumlah]" value="1">
        </td>
        <td class="border">
            <input type="text" class="form-control detail-harga" name="details[{index}][harga]" value="0">
        </td>
        <td class="border">
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="text" class="form-control text-end detail-amount" name="details[{index}][value]" value="0">
            </div>
        </td>
        <td class="border text-center">
            <button type="button" class="btn btn-sm btn-danger btn-delete-row"><i class="fas fa-trash"></i></button>
        </td>
        <input type="hidden" name="details[{index}][qty]" value="1">
        <input type="hidden" name="details[{index}][varianid]" value="">
        <input type="hidden" name="details[{index}][invoiceid]" value="">
        <input type="hidden" name="details[{index}][invoiceno]" value="">
        <input type="hidden" class="is-manual" name="details[{index}][is_manual]" value="0">
    </tr>
</template>

<script>
    var formIsSubmitting = false;

    // Data dari controller untuk edit mode
    var initialDetailsData = <?= $detailsDataJson ?>;
    var initialInvoiceData = <?= $invoiceDataJson ?>;
    var initialInvoiceDetails = <?= $invoiceDetailsJson ?>;
    var isEditMode = $('#is-edit-mode').val() === '1';

    // Detail manager - handle details
    var detailManager = {
        // Setup number formatting for elements
        setupDetailNumberFormat: function(element) {
            $(element).on('input', function(e) {
                let value = $(this).val().replace(/[^\d]/g, "");
                $(this).val(value !== "" ? detailManager.formatNumber(value) : "");
            });

            $(element).on('blur', function() {
                let value = $(this).val().replace(/[^\d]/g, '');
                if (value !== "") {
                    $(this).val(detailManager.formatNumber(value));
                }
            });
        },

        // Store invoice details
        invoiceDetails: [],

        // Total invoice amount
        totalAmount: 0,

        // Menyimpan total pembayaran sebelumnya (untuk mode edit)
        previousPayment: 0,

        // Get next row index
        getNextRowIndex: function() {
            const counter = parseInt($('#row-counter').val() || 0);
            $('#row-counter').val(counter + 1);
            return counter + 1;
        },

        // Add a new manual payment row
        addManualPaymentRow: function() {
            const index = this.getNextRowIndex();
            const template = $('#payment-row-template').html();
            const newRow = template.replace(/{index}/g, index);

            $('#detail-rows').append(newRow);

            // Initialize select2 for the new row
            this.initAkunSelect2($('#row-akun-' + index), index);

            // Setup number formatting
            this.setupDetailNumberFormat($('.detail-amount').last());
            this.setupDetailNumberFormat($('.detail-harga').last());

            // Mark as manual row
            $('.detail-item').last().attr('data-manual', '1');
            $('.is-manual').last().val('1');

            // Setup jumlah and harga calculation
            this.setupCalculation($('.detail-item').last());

            return index;
        },

        // Set up calculation for jumlah * harga = amount
        setupCalculation: function(row) {
            const jumlahInput = row.find('.detail-jumlah');
            const hargaInput = row.find('.detail-harga');
            const amountInput = row.find('.detail-amount');

            const calculateAmount = function() {
                const jumlah = parseFloat(jumlahInput.val().replace(/\./g, '').replace(',', '.')) || 0;
                const harga = parseFloat(hargaInput.val().replace(/\./g, '').replace(',', '.')) || 0;
                const amount = jumlah * harga;
                amountInput.val(detailManager.formatNumber(amount));
                detailManager.updateTotals();
                detailManager.updateCashDetails();
            };

            jumlahInput.on('change', calculateAmount);
            hargaInput.on('change', calculateAmount);

            // Setup number formatting
            jumlahInput.on('blur', function() {
                const value = $(this).val();
                if (value && !isNaN(value)) {
                    $(this).val(parseInt(value));
                }
            });
        },

        // Toggle invoice mode
        setInvoiceMode: function(isInvoiceMode) {
            $('#invoice-mode').val(isInvoiceMode ? '1' : '0');

            // Show/hide buttons and manual rows
            if (isInvoiceMode) {
                // Hide all manual rows
                $('.detail-item[data-manual="1"]').hide();
                // Hide the add payment button
                $('#btn-add-payment').hide();
            } else {
                // Show all manual rows
                $('.detail-item[data-manual="1"]').show();
                // Show the add payment button
                $('#btn-add-payment').show();

                // Remove all invoice rows
                $('.detail-item[data-manual="0"]').remove();
            }
        },

        // Clear all rows
        clearAllRows: function() {
            $("#detail-rows").empty();
            $('#row-counter').val('0');
        },

        // Initialize select2 for an akun dropdown
        initAkunSelect2: function(element, index) {
            if (typeof $.fn.select2 === 'function') {
                $(element).select2({
                    ajax: {
                        url: "<?= \yii\helpers\Url::to(['akunlist']) ?>",
                        type: "GET",
                        dataType: "json",
                        data: function(params) {
                            return {
                                q: params.term
                            };
                        },
                        processResults: function(data) {
                            console.log('Data:', data);
                            return {
                                results: data.data.map(function(akun) {
                                    console.log('Akun:', akun);
                                    return {
                                        id: akun.coa_id,
                                        text: akun.coa_name_id
                                    };
                                })
                            };
                        },
                        cache: true
                    },
                    placeholder: "Pilih Akun",
                    allowClear: true,
                    dropdownParent: $('#modal_form_cash').length ? $('#modal_form_cash') : $(document.body)
                });
            }
        },

        // Add detail from invoice
        addInvoiceDetails: function(invoiceId, invoiceNo, invoiceDetails, contactId) {
            // Reset details and total amount
            this.invoiceDetails = invoiceDetails || [];
            this.totalAmount = 0;

            // Set contact ID for the cash transaction
            $("#contact-hidden").val(contactId);

            if (!invoiceDetails || invoiceDetails.length === 0) {
                return;
            }

            // Set to invoice mode first to hide manual rows
            this.setInvoiceMode(true);

            // Clear all existing rows
            this.clearAllRows();

            // Calculate total amount from all details
            let totalTagihan = 0;

            // Loop through invoice details and create rows
            invoiceDetails.forEach((detail, index) => {
                const rowIndex = this.getNextRowIndex();
                const template = $('#payment-row-template').html();
                const newRow = template.replace(/{index}/g, rowIndex);

                $('#detail-rows').append(newRow);

                // Update the row with invoice details
                const $row = $('.detail-item').last();
                $row.attr('data-manual', '0');
                $row.attr('data-varianid', detail.varianid || '');
                $row.find('.detail-description').val(`${detail.deskripsi || 'Produk'} - ${invoiceNo}`);
                $row.find('.detail-jumlah').val(detail.jumlah || 1);
                $row.find('.detail-harga').val(this.formatNumber(detail.harga || 0));
                $row.find('.detail-amount').val(this.formatNumber(detail.itemsubtotal || 0));
                $row.find('input[name^="details["][name$="[invoiceid]"]').val(invoiceId || '');
                $row.find('input[name^="details["][name$="[invoiceno]"]').val(invoiceNo || '');
                $row.find('input[name^="details["][name$="[varianid]"]').val(detail.varianid || '');
                $row.find('.is-manual').val('0');

                // Initialize select2 for this row
                this.initAkunSelect2($row.find('.detail-akun'), rowIndex);

                // Make invoice rows readonly
                $row.find('input, select').prop('readonly', true);
                // $row.find('select').prop('disabled', true);
                $row.find('.btn-delete-row').hide();

                // Setup number formatting
                this.setupDetailNumberFormat($row.find('.detail-amount'));
                this.setupDetailNumberFormat($row.find('.detail-harga'));

                // Add to total
                totalTagihan += parseFloat(detail.itemsubtotal || 0);
            });

            this.totalAmount = totalTagihan;

            // Update total payment input based on mode
            if (isEditMode && this.previousPayment > 0) {
                $("#total-payment").val(this.formatNumber(this.previousPayment));
            } else {
                $("#total-payment").val(this.formatNumber(this.totalAmount));
            }

            this.updateTotals();
            this.updateCashDetails();
        },

        // Update totals based on payment amount
        updateTotals: function() {
            let totalTagihan = 0;

            // Calculate total from all visible rows
            $('.detail-amount:visible').each(function() {
                totalTagihan += detailManager.parseNumber($(this).val());
            });

            // Update the total display
            $('#total-tagihan').text(this.formatNumber(totalTagihan));

            // Update total payment if needed
            if (!isEditMode) {
                $("#total-payment").val(this.formatNumber(totalTagihan));
            }

            this.totalAmount = totalTagihan;
        },

        // Update cash details for submission
        updateCashDetails: function() {
            const details = [];
            const totalPayment = parseFloat($("#total-payment").val().replace(/\./g, '')) || 0;

            // Get all detail rows (both visible and hidden)
            $("#detail-rows tr").each(function(index) {
                const row = $(this);
                const akunId = row.find('.detail-akun').val() || '';
                const description = row.find('.detail-description').val() || '';
                const invoiceId = row.find('input[name^="details["][name$="[invoiceid]"]').val() || '';
                const invoiceNo = row.find('input[name^="details["][name$="[invoiceno]"]').val() || '';
                const varianId = row.find('input[name^="details["][name$="[varianid]"]').val() || '';
                const amount = detailManager.parseNumber(row.find('.detail-amount').val());
                const jumlah = parseInt(row.find('.detail-jumlah').val()) || 1;
                const harga = detailManager.parseNumber(row.find('.detail-harga').val());
                const isManual = row.attr('data-manual') === '1';

                // Skip hidden manual rows when in invoice mode
                if (isManual && $('#invoice-mode').val() === '1') {
                    return;
                }

                // Create a detail object
                const detail = {
                    cashdetailtype: description,
                    qty: jumlah,
                    harga: harga,
                    amount: amount,
                    paid: 0,
                    ord: index + 1,
                    iscut: 0,
                    akunid: akunId,
                    varianid: varianId,
                    invoiceid: invoiceId,
                    invoiceno: invoiceNo,
                    is_manual: isManual ? 1 : 0
                };

                // If we're in edit mode and have existing details with cashdetailid, preserve it
                if (isEditMode && index < detailManager.invoiceDetails.length && detailManager.invoiceDetails[index].cashdetailid) {
                    detail.cashdetailid = detailManager.invoiceDetails[index].cashdetailid;
                    detail.paid = detailManager.invoiceDetails[index].paid || 0;
                }

                details.push(detail);
            });

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
                this.invoiceDetails = initialDetailsData;

                // Check if this is invoice mode
                const hasInvoice = initialDetailsData.some(d => d.invoiceid);

                // Clear existing rows
                this.clearAllRows();

                // Set invoice mode if needed
                if (hasInvoice) {
                    this.setInvoiceMode(true);
                } else {
                    this.setInvoiceMode(false);
                }

                // Loop through details and create rows
                initialDetailsData.forEach((detail, index) => {
                    const rowIndex = this.getNextRowIndex();
                    const template = $('#payment-row-template').html();
                    const newRow = template.replace(/{index}/g, rowIndex);
                    console.log('New row:', newRow);
                    $('#detail-rows').append(newRow);

                    // Update the row with data
                    const $row = $('.detail-item').last();

                    // Set manual or invoice row
                    const isManual = detail.is_manual === 1;
                    $row.attr('data-manual', isManual ? '1' : '0');
                    $row.find('.is-manual').val(isManual ? '1' : '0');

                    // If we have an akunid, create a new option and select it
                    this.initAkunSelect2($row.find('.detail-akun'), rowIndex);
                    // Modifikasi pada bagian loadExistingData di script form _form.php

                    // Ganti dengan kode:
                    console.log(detail.akun);
                    Boolean(detail.akun)
                    // Replace your existing code in the loadExistingData function with this:
                    if (detail.akun) {
                        // First, create the option element properly
                        var $select = $row.find('.detail-akun');
                        console.log('Select:', $select);
                        // Create a new option but don't append it yet
                        var newOption = new Option(detail.akun.coa_name_id, detail.akun.coa_id, true, true);

                        // Clear any existing options to avoid duplication
                        $select.empty();

                        // Now add the option to the select
                        console.log('New option:', newOption);
                        $select.append(newOption);

                        // Tell Select2 to update its UI - IMPORTANT: use trigger AFTER appending
                        $select.trigger('change.select2');
                    }



                    $row.find('.detail-description').val(detail.cashdetailtype || '');
                    $row.find('.detail-jumlah').val(detail.qty || 1);
                    $row.find('.detail-harga').val(this.formatNumber(detail.harga || 0));
                    $row.find('.detail-amount').val(this.formatNumber(detail.amount || 0));
                    $row.find('input[name^="details["][name$="[qty]"]').val(detail.qty || 1);
                    $row.find('input[name^="details["][name$="[varianid]"]').val(detail.varianid || '');
                    $row.find('input[name^="details["][name$="[invoiceid]"]').val(detail.invoiceid || '');
                    $row.find('input[name^="details["][name$="[invoiceno]"]').val(detail.invoiceno || '');

                    // Setup calculation for manual rows
                  // In the loadExistingData function, modify the section where you handle invoice rows:
if (isManual) {
    this.setupCalculation($row);
} else {
    // For invoice rows, we need to set the value BEFORE making it readonly
    if (detail.akun) {
        var $select = $row.find('.detail-akun');
        var newOption = new Option(detail.akun.coa_name_id, detail.akun.coa_id, true, true);
        $select.empty().append(newOption).trigger('change.select2');
    }

    // AFTER setting the value and triggering the change event, make it readonly
    $row.find('input').prop('readonly', true);
    // Important: Don't disable the select, just make it readonly for visual purposes
    // This is the key difference - don't use prop('disabled', true) on selects with Select2
    $row.find('select').prop('readonly', true).addClass('select2-readonly');
    $row.find('.btn-delete-row').hide();

    // Add CSS to style the readonly Select2
    if (!$('style#select2-readonly-style').length) {
        $('head').append('<style id="select2-readonly-style">' +
            '.select2-readonly + .select2-container { pointer-events: none; }' +
            '.select2-readonly + .select2-container .select2-selection { background-color: #e9ecef; }' +
            '</style>');
    }
}

                    // Setup number formatting
                    this.setupDetailNumberFormat($row.find('.detail-amount'));
                    this.setupDetailNumberFormat($row.find('.detail-harga'));

                    // Add to total
                    this.totalAmount += parseFloat(detail.amount || 0);
                });

                // Store the previous payment amount
                this.previousPayment = this.totalAmount;

                // Update UI
                this.updateTotals();

                // Set jumlah bayar ke nilai sebelumnya jika dalam mode edit
                $("#total-payment").val(this.formatNumber(this.previousPayment));

                this.updateCashDetails();

                return true;
            }
            return false;
        }
    };

    $(document).ready(function() {
        // Setup format for all amount inputs
        detailManager.setupDetailNumberFormat('#total-payment');

        // Add initial row if empty
        if ($('#detail-rows tr').length === 0) {
            detailManager.addManualPaymentRow();
        }

        // Add payment button handler
        $('#btn-add-payment').on('click', function() {
            detailManager.addManualPaymentRow();
        });

        // Delete row button handler
        $(document).on('click', '.btn-delete-row', function() {
            $(this).closest('tr').remove();
            detailManager.updateTotals();
            detailManager.updateCashDetails();
        });

        // Initialize the form
        initForm();

        function initForm() {
            // Initialize invoice select2
            if (typeof $.fn.select2 === 'function') {
                $("#select-invoice").select2({
                    ajax: {
                        url: "<?= \yii\helpers\Url::to(['invoicelist']) ?>",
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
                                        trandate: invoice.trandate_display,
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
                    dropdownParent: $('#modal_form_cash').length ? $('#modal_form_cash') : $(document.body)
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

                    $('#row-counter').val('0');

                    // Remove all existing rows
                    $("#detail-rows").empty();

                    // Set back to editable mode
                    detailManager.setInvoiceMode(false);

                    // Add a new row if there are none
                    if ($('#detail-rows tr:visible').length === 0) {
                        detailManager.addManualPaymentRow();
                    }

                    detailManager.invoiceDetails = [];
                    detailManager.totalAmount = 0;
                    detailManager.updateTotals();
                    $("#total-payment").val("0");
                });

                // Initialize main akunid select2
                $("#akunSelect").select2({
                    ajax: {
                        url: "<?= \yii\helpers\Url::to(['akunlist']) ?>",
                        type: "GET",
                        dataType: "json",
                        data: function(params) {
                            return {
                                q: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.data.map(function(akun) {
                                    return {
                                        id: akun.coa_id,
                                        text: akun.coa_name_id
                                    };
                                })
                            };
                        },
                        cache: true
                    },
                    placeholder: "Pilih Akun",
                    allowClear: true,
                    dropdownParent: $('#modal_form_cash').length ? $('#modal_form_cash') : $(document.body)
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
                $("#cashDate$").val("<?= date('d-m-Y') ?>");
            }

            // Update cash details when detail inputs change
            $(document).on('change', '.detail-akun, .detail-description, .detail-amount, .detail-jumlah, .detail-harga', function() {
                detailManager.updateCashDetails();
            });

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
            if (!invoice.id) return invoice.text;

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
            return invoice.text || invoice.id;
        }

        function setupFormSubmission() {
            // Reset submission flag
            formIsSubmitting = false;

            // Unbind any existing handlers
            $('#FormValidCash').off('submit');

            // Attach the submit handler
            $('#FormValidCash').on('submit', function(e) {
                console.log('Form submission initiated...');
                e.preventDefault();

                if (formIsSubmitting) {
                    return false;
                }

                formIsSubmitting = true;

                try {
                    // Update cash details based on current form values
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
