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
                [],
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
            <div class="d-flex align-items-center">
                <i class="fas fa-file-invoice text-primary me-2"></i>
                <h5 class="mb-0 fw-bold">Data Pembayaran</h5>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle rounded-lg overflow-hidden" id="details-table">
                    <thead class="table-light text-nowrap border-2">
                        <tr>
                        <th width="10%" class="ps-4 py-3">No</th>
<th width="20%" class="py-3">Akun</th>
<th width="30%" class="py-3">Detail</th>
<th width="20%" class="py-3">Jumlah</th>
<th width="10%" class="py-3">Harga</th>
<th width="10%" class="text-end pe-4 py-3">Tagihan</th>

                        </tr>
                    </thead>
                    <tbody id="detail-rows" class="border-2">
                        <!-- Default row with detail form (always displayed) -->
                        <tr class="detail-item" data-varianid="">
                            <td class="border text-center">1</td>
                            <td class="border">
                                <select id="row-akun-0" name="details[0][akunid]" class="form-select detail-akun"
                                    data-control="select2" placeholder="Pilih Akun">
                                    <option value="">Pilih Akun</option>
                                </select>
                            </td>
                            <td class="border">
                                <input type="text" class="form-control detail-description" name="details[0][cashdetailtype]" value="Pembayaran Invoice">
                            </td>
                            <td class="border">
                                <input type="text" class="form-control detail-jumlah" name="details[0][jumlah]" value="Pembayaran Invoice">
                            </td>
                            <td class="border">
                                <input type="text" class="form-control detail-harga" name="details[0][harga]" value="Pembayaran Invoice">
                            </td>
                            <td class="border">
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control text-end detail-amount" name="details[0][value]" value="0">
                                </div>
                            </td>
                            <input type="hidden" name="details[0][qty]" value="1">
                            <input type="hidden" name="details[0][varianid]" value="">
                            <input type="hidden" name="details[0][invoiceid]" value="">
                            <input type="hidden" name="details[0][invoiceno]" value="">
                        </tr>
                    </tbody>
                    <tfoot class="border-top">
                        <tr class="bg-light text-dark">
                            <td colspan="3" class="text-end ps-4 py-3">SubTotal</td>
                            <td class="text-end pe-4 py-3" id="total-tagihan">0</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end ps-4 py-3">Jumlah Bayar</td>
                            <td class="pe-4 py-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">Rp</span>
                                    <input type="text" id="total-payment" class="form-control form-control-lg text-end border-start-0"
                                        name="Cash[totalpaid]" value="0">
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

        // Toggle invoice mode
        setInvoiceMode: function(isInvoiceMode) {
            $('#invoice-mode').val(isInvoiceMode ? '1' : '0');

            // Make fields readonly when in invoice mode
            if (isInvoiceMode) {
                // $('.detail-akun').prop('disabled', true);
                $('.detail-description').prop('readonly', true);
                $('.detail-amount').prop('readonly', true);
            } else {
                $('.detail-akun').prop('disabled', false);
                $('.detail-description').prop('readonly', false);
                $('.detail-amount').prop('readonly', false);

                // Reset to a single row when clearing invoice
                if ($("#detail-rows tr").length > 1) {
                    $("#detail-rows tr:not(:first)").remove();
                }

                // Reset values in the first row
                const firstRow = $("#detail-rows tr:first");
                firstRow.find('.detail-description').val('Pembayaran Invoice');
                firstRow.find('.detail-amount').val('0');
                firstRow.find('.detail-akun').val(null).trigger('change');
                $('input[name="details[0][qty]"]').val('1');
                $('input[name="details[0][varianid]"]').val('');
                $('input[name="details[0][invoiceid]"]').val('');
                $('input[name="details[0][invoiceno]"]').val('');

                this.updateTotals(0);
            }
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
                            return {
                                results: data.data.map(function(akun) {
                                    return {
                                        id: akun.id,
                                        text: akun.text
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
                // Don't clear the default row, just update the invoiceid and invoiceno fields
                $('input[name="details[0][invoiceid]"]').val(invoiceId || '');
                $('input[name="details[0][invoiceno]"]').val(invoiceNo || '');
                this.updateTotals(0);
                return;
            }

            // Set to invoice mode
            this.setInvoiceMode(true);

            // Clear existing rows except the first one
            // $("#detail-rows tr:not(:first)").remove();

            // Calculate total amount from all details
            // Clear existing rows except the first one
            // $("#detail-rows tr:not(:first)").remove();// Hapus baris pertama (first row) saja



            let totalTagihan = 0;

            // Loop through invoice details and create rows
            invoiceDetails.forEach((detail, index) => {
                // For the first row, update existing row
                // For subsequent rows, create new rows
                // if (index === 0) {
                    // Update first row
                    // $("#detail-rows tr:first").find('.detail-description').val(`${detail.deskripsi || 'Produk'} - ${invoiceNo}`);
                    // $('input[name="details[0][invoiceid]"]').val(invoiceId || '');
                    // $('input[name="details[0][invoiceno]"]').val(invoiceNo || '');
                    // $('input[name="details[0][varianid]"]').val(detail.varianid || '');
                    // $('.detail-amount').first().val(this.formatNumber(detail.itemsubtotal || 0));
                // } else {
                    // Clone the first row and modify it for additional details
                    const newRow = $("#detail-rows tr:first").clone();

                    // Update row number
                    newRow.find('td:first').text(index + 1);

                    // Update form field names with correct index
                    newRow.find('input, select').each(function() {
                        const name = $(this).attr('name');
                        if (name) {
                            $(this).attr('name', name.replace('[0]', '[' + index + ']'));
                        }

                        const id = $(this).attr('id');
                        if (id && id.startsWith('row-akun-')) {
                            $(this).attr('id', 'row-akun-' + index);
                        }
                    });

                    // Update values
                    newRow.find('.detail-description').val(`${detail.deskripsi || 'Produk'} - ${invoiceNo}`);
                    newRow.find('.detail-jumlah').val(detail.jumlah || 0);
                    newRow.find('.detail-harga').val(detail.harga || 0);
                    newRow.find('.detail-amount').val(this.formatNumber(detail.itemsubtotal || 0));
                    newRow.find('input[name="details[' + index + '][invoiceid]"]').val(invoiceId || '');
                    newRow.find('input[name="details[' + index + '][invoiceno]"]').val(invoiceNo || '');
                    newRow.find('input[name="details[' + index + '][varianid]"]').val(detail.varianid || '');

                    // Add the new row to the table
                    $("#detail-rows").append(newRow);
                    this.initAkunSelect2(newRow.find('.detail-akun'), index);

                    // Initialize select2 for new row

                    // Setup number formatting for new amount inputs
                    this.setupDetailNumberFormat(newRow.find('.detail-amount'));
                // }

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

        // Update totals based on payment amount
        updateTotals: function(totalTagihan) {
            $('#total-tagihan').text(this.formatNumber(totalTagihan));

            // Update sum of all detail amounts
            let totalDetailAmount = 0;
            $('.detail-amount').each(function() {
                totalDetailAmount += detailManager.parseNumber($(this).val());
            });

            // Only update if the totals don't match
            if (Math.abs(totalDetailAmount - totalTagihan) > 0.01) {
                // Distribute the total amount proportionally across detail rows
                const rows = $('.detail-amount');
                if (rows.length > 0) {
                    // If only one row, it gets the full amount
                    if (rows.length === 1) {
                        rows.first().val(this.formatNumber(totalTagihan));
                    } else {
                        // Otherwise distribute proportionally
                        const currentTotal = totalDetailAmount;
                        rows.each(function(i) {
                            if (i === rows.length - 1) {
                                // Last row gets any remainder to avoid rounding issues
                                const otherRowsTotal = rows.slice(0, -1).toArray().reduce((sum, el) => {
                                    return sum + detailManager.parseNumber($(el).val());
                                }, 0);
                                $(this).val(detailManager.formatNumber(totalTagihan - otherRowsTotal));
                            } else {
                                const currentValue = detailManager.parseNumber($(this).val());
                                const proportion = currentValue / currentTotal;
                                $(this).val(detailManager.formatNumber(totalTagihan * proportion));
                            }
                        });
                    }
                }
            }
        },

        // Update cash details for submission
        updateCashDetails: function() {
            const details = [];
            const totalPayment = parseFloat($("#total-payment").val().replace(/\./g, '')) || 0;

            // Get all detail rows
            $("#detail-rows tr").each(function(index) {
                const row = $(this);
                const akunId = row.find('.detail-akun').val() || '';
                const description = row.find('.detail-description').val() || 'Pembayaran Invoice';
                const invoiceId = row.find('input[name^="details["][name$="[invoiceid]"]').val() || '';
                const invoiceNo = row.find('input[name^="details["][name$="[invoiceno]"]').val() || '';
                const varianId = row.find('input[name^="details["][name$="[varianid]"]').val() || '';
                const amount = detailManager.parseNumber(row.find('.detail-amount').val());

                // Create a detail object
                const detail = {
                    cashdetailtype: description,
                    qty: 1,
                    amount: amount,
                    paid: 0,
                    ord: index + 1,
                    iscut: 0,
                    akunid: akunId,
                    varianid: varianId,
                    invoiceid: invoiceId,
                    invoiceno: invoiceNo
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
                if (hasInvoice) {
                    this.setInvoiceMode(true);
                }

                // Clear existing rows except the first one
                $("#detail-rows tr:not(:first)").remove();

                // Loop through details and create rows
                initialDetailsData.forEach((detail, index) => {
                    console.log(detail.akunid);
                    // For the first row, update existing row
                    // For subsequent rows, create new rows
                    if (index === 0) {
                        // Update first row
                        const firstRow = $("#detail-rows tr:first");

                        // If we have an akunid, create a new option and select it
                        if (detail.akunid) {
                            console.log(detail.akunid);
                            var newOption = new Option(detail.akunid, detail.akunid, true, true);
                            firstRow.find('.detail-akun').append(newOption).trigger('change');
                        }

                        firstRow.find('.detail-description').val(detail.cashdetailtype || 'Pembayaran Invoice');
                        firstRow.find('.detail-amount').val(this.formatNumber(detail.amount || 0));

                        // Set hidden fields
                        $('input[name="details[0][qty]"]').val(detail.qty || 1);
                        $('input[name="details[0][varianid]"]').val(detail.varianid || '');
                        $('input[name="details[0][invoiceid]"]').val(detail.invoiceid || '');
                        $('input[name="details[0][invoiceno]"]').val(detail.invoiceno || '');
                    } else {
                        // Clone the first row and modify it for additional details
                        const newRow = $("#detail-rows tr:first").clone();

                        // Update row number
                        newRow.find('td:first').text(index + 1);

                        // Update form field names with correct index
                        newRow.find('input, select').each(function() {
                            const name = $(this).attr('name');
                            if (name) {
                                $(this).attr('name', name.replace('[0]', '[' + index + ']'));
                            }

                            const id = $(this).attr('id');
                            if (id && id.startsWith('row-akun-')) {
                                $(this).attr('id', 'row-akun-' + index);
                            }
                        });

                        // Update values
                        newRow.find('.detail-description').val(detail.cashdetailtype || 'Pembayaran Invoice');
                        newRow.find('.detail-amount').val(this.formatNumber(detail.amount || 0));
                        newRow.find('input[name="details[' + index + '][qty]"]').val(detail.qty || 1);
                        newRow.find('input[name="details[' + index + '][varianid]"]').val(detail.varianid || '');
                        newRow.find('input[name="details[' + index + '][invoiceid]"]').val(detail.invoiceid || '');
                        newRow.find('input[name="details[' + index + '][invoiceno]"]').val(detail.invoiceno || '');

                        // Add the new row to the table
                        $("#detail-rows").append(newRow);

                        // Initialize select2 for new row
                        // this.initAkunSelect2(newRow.find('.detail-akun'), index);

                        // If we have an akunid, create a new option and select it
                        if (detail.akunid) {
                            var newOption = new Option(detail.akunid, detail.akunid, true, true);
                            newRow.find('.detail-akun').append(newOption).trigger('change');
                        }

                        // Setup number formatting for new amount inputs
                        this.setupDetailNumberFormat(newRow.find('.detail-amount'));
                    }

                    // Add to total
                    this.totalAmount += parseFloat(detail.amount || 0);
                });

                // Store the previous payment amount
                this.previousPayment = this.totalAmount;

                // Update UI
                this.updateTotals(this.totalAmount);

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
        detailManager.setupDetailNumberFormat('.detail-amount');

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

                    // Set back to editable mode
                    detailManager.setInvoiceMode(false);

                    detailManager.invoiceDetails = [];
                    detailManager.totalAmount = 0;
                    detailManager.updateTotals(0);
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
                                        id: akun.id,
                                        text: akun.text
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

                // Initialize detail akun select2
                // detailManager.initAkunSelect2('.detail-akun', 0);
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

            // Update cash details when detail inputs change
            $(document).on('change', '.detail-akun, .detail-description, .detail-amount', function() {
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
