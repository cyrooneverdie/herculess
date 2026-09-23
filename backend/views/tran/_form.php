<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

?>

<?php
$form = ActiveForm::begin([
    'id' => 'tran-form',
    'method' => 'post',
    'options' => [
        'enctype' => 'multipart/form-data',
    ],
    'validateOnSubmit' => false,
]);
?>
<?= $form->field($model, 'trantype')->hiddenInput()->label(false); ?>

<div class="d-flex flex-column scroll-y px-5 px-lg-10" id="modal_form_asset_scroll">
    <div class="row">
        <div class="col-lg-2 col-md-4 col-sm-12 px-1">
            <label class="fs-7"><?= Yii::$app->lang->t('tran', 'tran_no') ?></label>
            <?= $form->field($model, 'tranno')->textInput(['readOnly' => true])->label(false); ?>
        </div>

        <div class="col-lg-3 col-md-4 col-sm-12 px-1">
            <label class="fs-7"><?= Yii::$app->lang->t('tran', 'refid') ?></label>
            <?= $form->field($model, 'refid', [
                'errorOptions' => ['class' => 'text-danger mt-3'],
            ])->dropDownList(
                    $model->refid && $model->ref ? [$model->ref->tranid => $model->ref->tranno] : [],
                    [
                        'class' => 'form-select refid-select',
                        'data-control' => 'select2'
                    ]
                )->label(false); ?>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-12 px-1">
            <label class="fs-7">
                <?= Yii::$app->lang->t('extra', 'extra54') ?>
            </label>

            <?= $form->field($model, 'contact_id', [
                'errorOptions' => ['class' => 'text-danger mt-3'],
            ])->dropDownList(
                    $model->contact_id ? [$model->contact_id => Yii::$app->function->findByField("jobcompany", "contacts", " and contact_id ='" . $model->contact_id . "' ")] : [],
                    [
                        'class' => 'form-select',
                        'data-control' => 'select2',
                        'disabled' => !$model->isNewRecord ? true : false,
                    ]
                )->label(false); ?>
        </div>

        <div class="mb-2 col-lg-2 col-md-4 col-sm-12">
            <label class="fs-7">
                <?= Yii::$app->lang->t('tran', 'invoice_date') ?>
            </label>

            <?= $form->field($model, 'trandate')->textInput(['class' => 'form-control pickdate'])->label(false) ?>
        </div>

        <div class="mb-7 col-lg-2 col-md-4 col-sm-12">
            <label class="fs-7">
                <?= Yii::$app->lang->t('tran', 'invoice_duedate') ?>
            </label>
            <?= $form->field($model, 'tranduedate')->textInput(['class' => 'form-control pickdate'])->label(false); ?>
        </div>

    </div>

    <div class="my-5">
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade p-3 show active " id="detail" role="tabpanel" aria-labelledby="detail-tab">
                    <div
                        class="form-check form-switch form-check-custom form-check-success form-check-solid d-flex flex-row-reverse align-items-center gap-2 head-detail">
                        <?= $form->field($model, 'priceincludetax')->hiddenInput()->label(false) ?>
                        <input class="form-check-input" type="checkbox" value="" id="kt_flexSwitchCustomDefault_1_1"
                            <?= $model->priceincludetax === 1 ? 'checked' : '0'; ?>>
                        <label class="form-check-label" for="kt_flexSwitchCustomDefault_1_1">
                            Tax Include Price
                        </label>
                    </div>

                    <div class="table-responsive" id="trandetail_repeater">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-gray-600 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">
                                        <?= Yii::$app->lang->t('extrasidebar', 'extrasidebar2') ?>
                                    </th>
                                    <th class="min-w-150px"><?= Yii::$app->lang->t('produk', 'detail_qty') ?></th>
                                    <th class="min-w-80px">Period(Day)</th>
                                    <th class="min-w-150px"><?= Yii::$app->lang->t('front_home', 'price') ?></th>
                                    <th class="min-w-150px">Sub Total</th>
                                    <th class="min-w-80px"><?= Yii::$app->lang->t('extra', 'extra5') ?></th>
                                    <th class="min-w-100px"><?= Yii::$app->lang->t('extra', 'extra70') ?></th>
                                    <th class="min-w-150px"><?= Yii::$app->lang->t('extra', 'extra71') ?></th>
                                </tr>
                            </thead>

                            <tbody class="detail-rows" data-repeater-list="Trandetail">
                                <?php if (!empty($modeldetails)): ?>
                                    <?php foreach ($modeldetails as $index => $modeldetail): ?>
                                        <tr class="detail-item" data-repeater-item>
                                            <td class="align-top">
                                                <div class="d-flex flex-column">
                                                    <input type="hidden" name="trandetailid"
                                                        value="<?= $modeldetail->trandetailid ?? '' ?>">

                                                    <input type="hidden" name="refid" value="<?= $modeldetail->refid ?? '' ?>">

                                                    <input type="hidden" name="trandetailid" class="form-control"
                                                        value="<?= $modeldetail->trandetailid ?? "" ?>" readonly>

                                                    <select name="productid" class="form-select product-select mb-2 required"
                                                        style="width: 100%;">
                                                        <option value="<?= $modeldetail->productid ?? "" ?>" selected>
                                                            <?= $modeldetail->productid ? Yii::$app->function->findByField("productname", "products", " and productid ='" . $modeldetail->productid . "' ") : "" ?>
                                                        </option>
                                                    </select>

                                                    <div class="d-flex">
                                                        <textarea name="description" rows="3" class="form-control"
                                                            placeholder="Deskripsi"><?= $modeldetail->description ?? "" ?></textarea>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-top">
                                                <div class="input-group product-quantity-group">
                                                    <span class="input-group-text" style="width:70px">Qty</span>
                                                    <input type="number" name="amount" class="form-control product-quantity"
                                                        placeholder="Qty" value="<?= $modeldetail->amount ?? '0' ?>">
                                                </div>
                                                <!-- <div class="input-group product-stock-group">
                                                    <span class="input-group-text" style="width: 70px">Stock</span>
                                                    <input type="number" name="stockmilik"
                                                        class="form-control bg-light product-stock" readonly value="<?= $model->isNewRecord && $modeldetail->productid ?
                                                            $modeldetail->product->getStock(
                                                                DateTime::createFromFormat('d/m/Y H:i', $model->setupdate)->format('Y-m-d'),
                                                                $model->withdrawaldate,
                                                                $model->tranid
                                                            )['ready'] : $modeldetail->stockmilik ?? '0'; ?>">
                                                </div>
                                                <div class="d-flex">
                                                    <span
                                                        class="text-danger info-stock <?= !$model->isNewRecord && $modeldetail->amount2 < 0 ? '' : 'd-none' ?>">Stock
                                                        Kurang
                                                        <?= $modeldetail->amount2 ?? '0' ?>
                                                    </span>
                                                    <input type="text" name="amount2" class="form-control product-stock2"
                                                        style="visibility: hidden; width:1px;"
                                                        value="<?= $modeldetail->amount2 ?? '0' ?>">
                                                </div> -->
                                            </td>
                                            <td class="align-top">
                                                <input type="number" name="freqvalue" class="form-control product-freq-value"
                                                    min="1" placeholder="Freq" value="<?= $modeldetail->freqvalue ?? '0' ?>">

                                            </td>

                                            <td class="align-top">
                                                <input type="text" name="price" class="form-control money product-price price"
                                                    value="<?= $modeldetail->price ?? 0 ?>">
                                            </td>

                                            <td class="align-top">
                                                <input type="text" name="itemsubtotaltax"
                                                    class="form-control money itemsubtotal" placeholder="Subtotal Tax" readonly
                                                    value="<?= $modeldetail->itemsubtotaltax ?? 0 ?>">
                                            </td>

                                            <td class="align-top">
                                                <input type="number" name="itemdiscpersen"
                                                    class="form-control col-md-4 itemdiscpersen" placeholder="Disc %"
                                                    value="<?= $modeldetail->itemdiscpersen ?? 0 ?>">

                                            </td>

                                            <td class="align-top">
                                                <select name="itemtaxid" class="form-select itemtaxid" style="min-width:100px;">
                                                    <option value="">...</option>

                                                    <option value="PPN" data-value="11" data-cut="0"
                                                        <?= (isset($modeldetail->itemtaxid) && $modeldetail->itemtaxid == 'PPN') ? 'selected' : '' ?>>PPN
                                                    </option>

                                                    <option value="PPH" data-value="2" data-cut="1"
                                                        <?= (isset($modeldetail->itemtaxid) && $modeldetail->itemtaxid == 'PPH') ? 'selected' : '' ?>>PPH
                                                    </option>
                                                </select>

                                                <input type="hidden" name="itemtax" class="form-control money itemtax"
                                                    value="<?= $modeldetail->itemtax ?? 0 ?>">
                                            </td>

                                            <td class="align-top">
                                                <input type="hidden" name="itemtotal"
                                                    class="form-control money itemtotal bg-light" readonly value="0">
                                                <input type="text" name="itemtotaltax"
                                                    class="form-control money itemtotaltax bg-light" placeholder="Total Aft Tax"
                                                    readonly value="<?= $modeldetail->itemtotaltax ?? 0 ?>">
                                            </td>
                                            <td class="align-top">
                                                <button type="button" data-repeater-delete
                                                    class="btn btn-sm btn-icon btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>

                                            </td>
                                        </tr>

                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>

                        </table>
                        <button type="button" data-repeater-create
                            class="btn btn-sm btn-primary repeat-parent-btn mt-2">
                            <i class="ki-duotone ki-plus fs-2"></i>
                            <?= Yii::$app->lang->t('add', 'add1') ?>
                        </button>
                    </div>

                    <div class="d-flex flex-column align-items-end mt-3" style="min-width:350px;">

                        <div class="d-flex align-items-center justify-content-end gap-2 mb-1 border-top pt-2 w-100">
                            <span class="fw-bold text-end" style="min-width:180px;">Sub Total (+):</span>
                            <input type="text" name="Tran[subtotal]"
                                class="form-control bg-light text-end tfoot-subtotal money" style="width:200px;"
                                value="<?= $model->subtotal ?? 0 ?>" readonly>
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-2 mb-1 w-100">
                            <span class="fw-bold text-end"
                                style="min-width:180px;"><?= Yii::$app->lang->t('extra', 'extra72') ?> (-):</span>
                            <input type="text" name="Tran[disc]" class="form-control bg-light text-end tfoot-disc money"
                                style="width:200px;" value="<?= $model->disc ?? 0 ?>" readonly>
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-2 mb-1 w-100">
                            <span class="fw-bold text-end"
                                style="min-width:180px;"><?= Yii::$app->lang->t('extra', 'extra71') ?>:</span>
                            <input type="text" name="Tran[totalafterdisc]"
                                class="form-control bg-light text-end tfoot-totalafterdisc money" style="width:200px;"
                                value="<?= $model->totalafterdisc ?? 0 ?>" readonly>
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-2 mb-1 border-top pt-2 w-100">
                            <select class="form-select form-select-sm" style="width:120px;">
                                <option>PPN</option>
                            </select>
                            <span class="input-group-text">(+)</span>
                            <input type="text" name="Tran[ppnamount]"
                                class="form-control bg-light tfoot-ppn text-end money" style="width:200px;"
                                value="<?= $model->ppnamount ?? 0 ?>" readonly>
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-2 mb-1 w-100">
                            <select class="form-select form-select-sm" style="width:120px;">
                                <option>PPH</option>
                            </select>
                            <span class="input-group-text">(-)</span>
                            <input type="text" name="Tran[pphamount]"
                                class="form-control bg-light tfoot-pph text-end money" style="width:200px;"
                                value="<?= $model->pphamount ?? 0 ?>" readonly>
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-2 mb-1 w-100">
                            <select class="form-select form-select-sm" style="width:120px;">
                                <option>Other Discount</option>
                            </select>
                            <span class="input-group-text">(-)</span>
                            <input type="text" name="Tran[otherdiscount]" class="form-control bg-light text-end money"
                                style="width:200px;" value="<?= $model->otherdiscount ?? 0 ?>" readonly>
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-2 mb-1 w-100">
                            <select class="form-select form-select-sm" style="width:120px;">
                                <option>Delivery Charge</option>
                            </select>
                            <span class="input-group-text">(+)</span>
                            <input type="text" name="Tran[deliverycharge]" class="form-control bg-light text-end money"
                                style="width:200px;" value="<?= $model->deliverycharge ?? 0 ?>" readonly>
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-2 mb-1 border-top pt-2 w-100">
                            <span class="fw-bold fs-5 text-end" style="min-width:180px;">Grand Total:</span>
                            <input type="text" name="Tran[grandtotal]"
                                class="form-control bg-light fw-bold text-end fs-5 money" style="width:200px;"
                                value="<?= $model->grandtotal ?? 0 ?>" readonly>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-7">
                <?= $form->field($model, 'note', [
                    'errorOptions' => ['class' => 'text-danger mt-3'],
                ])->textarea([
                            'placeholder' => Yii::$app->lang->t('tran', 'tran_note'),
                            'rows' => 3,
                            'class' => 'form-control',
                        ]); ?>

            </div>
        </div>
    </div>
    <div class="text-end pt-10">
        <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel"
            data-bs-dismiss="modal"><?= Yii::$app->lang->t('back_home', 'chat34') ?></button>
        <?= Html::submitButton($model->isNewRecord ? Yii::$app->lang->t('extra', 'extra16') : Yii::$app->lang->t('extra', 'extra16'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        setForm();

        $(document).on('change', '#kt_flexSwitchCustomDefault_1_2', function () {
            if (!this.checked) return;

            applyAllTax();
        });

        $(document).on('select2:select', "select[name='alltax']", function () {
            if (!$('#kt_flexSwitchCustomDefault_1_2').is(':checked')) return;

            applyAllTax();
        });

        $(document).on('input change', '.product-luas, .product-quantity', function (e) {
            let $input = $(this);
            let row = $(this).closest('tr');

            clearTimeout($input.data('timer'));
            $input.data('timer', setTimeout(function () {
                if ($input.hasClass('product-quantity')) {
                    fetchProductPrice(row, 'luas');
                } else if ($input.hasClass('product-luas')) {
                    fetchProductPrice(row, 'qty');
                }
            }, 500));
            setTotal();
        });
    });

    function setForm() {
        if (<?php echo $isajax; ?>) {
            var form = $('#tran-form');
            form.on('submit', function (e) {
                e.preventDefault();

                var disabledElements = form.find(':input:disabled');
                disabledElements.prop('disabled', false);

                form.find('.number-input, .number-display').each(function () {
                    var $this = $(this);
                    var val = $this.val();

                    if (val && typeof val === 'string') {
                        var cleanValue = val.replace(/\./g, '').replace(',', '.');
                        $this.val(cleanValue);
                    }
                });

                var formData = form.serialize();
                disabledElements.prop('disabled', true);

                $.ajax({
                    url: form.attr("action"),
                    type: form.attr("method"),
                    data: formData,
                    success: function (data) {
                        // console.log('Response:', data);

                        if (data['success']) {
                            Swal.fire({
                                icon: "success",
                                title: "Successful",
                                html: data['pesan']
                            }).then(() => {
                                $('#modal_form_tran').modal('hide');
                                if (typeof $('#datatable').DataTable === 'function') {
                                    $('#datatable').DataTable().ajax.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: "warning",
                                title: "Warning",
                                html: data['pesan']
                            });

                            setTimeout(function () {
                                form.find('.number-input, .number-display').each(function () {
                                    var $this = $(this);
                                    var val = parseFloat($this.val()) || 0;
                                    if ($this.hasClass('number-input')) {
                                        $this.data('original-value', val);
                                    }
                                    $this.val(formatLocalizedNumber(val));
                                });
                            }, 100);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', xhr.responseText);
                        Swal.fire({
                            icon: "error",
                            title: "Failed",
                            html: "Something went wrong: " + error,
                        });

                        disabledElements.prop('disabled', true);
                        form.find('.number-input, .number-display').each(function () {
                            var $this = $(this);
                            var val = parseFloat($this.val()) || 0;
                            if ($this.hasClass('number-input')) {
                                $this.data('original-value', val);
                            }
                            $this.val(formatLocalizedNumber(val));
                        });
                    }
                });
            });
        }

        let currentModule = '<?= $module ?>';
        let currentType = '<?= $type ?>';

        selectContact($('#modal_form_tran'), "select[name='Tran[contact_id]']", 'vendor', '');
        selectReference($('#modal_form_tran'), ".refid-select", currentModule, currentType); repeat('#trandetail_repeater');

        repeat('#trandetail_repeater');
        setFunction();
    }

    function selectContact(target, selection, type, positionid) {
        $(selection).select2({
            ajax: {
                url: "<?= \yii\helpers\Url::to(['contact/select']) ?>",
                type: "POST",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        contacttype: type,
                        positionid: positionid,
                        search: params.term || '',
                        q: params.term,
                        page: params.page,
                        module: "<?= $module ?? 'purchase' ?>",
                    };
                },
                processResults: function (data, params) {
                    // console.log('Data contact:', data); // untuk debugging
                    params.page = params.page || 1;
                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 5) < data.totalcount,
                        },
                    };
                },
                cache: false,
            },
            placeholder: "Pilih Contact",
            minimumInputLength: 0,
            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: function (contact) {
                if (!contact.id) return "Pilih Contact";
                if (contact.loading) return contact.text;

                var $container = $(`
                <div class="select2-result-contact clearfix p-2">
                    <div class="select2-result-contact__name fw-semibold mb-1">
                        <i class="fa fa-user me-1 text-primary"></i> ${contact.jobcompany || contact.text}
                    </div>
                  
                </div>
            `);

                return $container;
            },
            templateSelection: function (contact) {
                return contact.jobcompany || contact.text || "Pilih Contact";
            },
            dropdownParent: target,
            allowClear: true,
        }).on('select2:open', function (e) {
            const selectId = $(this).attr('id') || '';
            if (!selectId.includes('contact')) return;

            let $dropdown = $('.select2-dropdown');
            $dropdown.find('.add-new-ctc-btn').remove();
            $dropdown.append(`
                <div class="add-new-ctc-btn" 
                    style="padding:6px; text-align:center; border-top:1px solid #ddd; background:#fafafa;">
                    <button type="button" 
                            class="btn btn-sm btn-primary" 
                            data-type="${type}">
                        <i class="fas fa-plus-circle me-2"></i> Tambah Contact Baru
                    </button>
                </div>
            `);
        }).on('change', function (e) {
            // console.log('Contact terpilih:', $(this).val());
        });
    }

    function selectReference(target, selection, module, type) {
        $(selection).select2({
            ajax: {
                url: "<?= Url::to(['tran/select']) ?>",
                type: "POST",
                dataType: "json",
                data: function (params) {
                    return {
                        module: module,
                        type: type,
                        search: params.term || '',
                        q: params.term,
                        page: params.page,
                        _csrf: yii.getCsrfToken()
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
                    return "Pilih Referensi";
                }
                return param.text;
            },
            templateResult: function (param) {
                if (!param.id) {
                    return param.text;
                }
                if (param.loading) return param.text;

                const $container = $(`
                <div class="d-flex align-items-start gap-2 py-1">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px;height:32px;font-size:13px;font-weight:600;">
                        ${param.name ? param.name.charAt(0).toUpperCase() : param.text.charAt(0).toUpperCase()}
                    </div>
                    <div class="d-flex flex-column flex-grow-1 overflow-hidden">
                        <span class="fw-semibold text-dark text-truncate" style="font-size:13px;">${param.text}</span>
                        <div class="d-flex flex-wrap gap-1 mt-1">
                            ${param.name ? `<span class="badge rounded-pill bg-info bg-opacity-10 text-info" style="font-size:11px;"><i class="fa fa-user me-1 text-info"></i>${param.name}</span>` : ''}
                            ${param.company ? `<span class="badge rounded-pill bg-primary bg-opacity-10 text-primary" style="font-size:11px;"><i class="fa fa-building me-1 text-primary"></i>${param.company}</span>` : ''}
                            ${param.trandate ? `<span class="badge rounded-pill bg-success bg-opacity-10 text-success" style="font-size:11px;"><i class="fa fa-calendar me-1 text-success"></i>${param.trandate}</span>` : ''}
                        </div>
                    </div>
                </div>
                `);

                return $container;
            },
            placeholder: "Pilih Referensi",
            allowClear: true,
            dropdownParent: target
        }).on('change', function (e) {
            var refid = $(this).val();
            // console.log('Referensi terpilih:', refid); // Debug: cek nilai refid
            if (refid) {
                loadReferenceDetail(refid);
            }
        });
    }

    function repeat(selection) {
        $(selection).repeater({
            initEmpty: <?= $model->isNewRecord ? 'true' : 'false' ?>,

            show: function () {
                $row = $(this);
                $row.slideDown();
                $row.find('input[type="text"][name*="[amount]"]').val('0').trigger('change');
                $row.find('input[type="text"][name*="[price]"]').val('0').trigger('change');
                $row.find('input[type="number"][name*="[itemdiscpersen]"]').val('0').trigger('change');
                $row.find('input[type="text"][name*="[itemsubtotaltax]"]').val('0').trigger('change');
                setFunction();
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
                setTimeout(setTotal, 400); // tunggu animasi selesai
            }
        });
    }

    function loadReferenceDetail(refid) {
        $('.detail-item').remove();

        Swal.fire({
            title: 'Loading...',
            text: 'Memuat data referensi',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: "<?= \yii\helpers\Url::to(['tran/listdetail']) ?>",
            type: "GET",
            dataType: 'json',
            data: {
                id: refid,
                module: 'sales',
                type: 'order',
                select: 'inv'
            },
            success: function (response) {
                Swal.close();

                if (response.data.length > 0) {
                    let headerData = response.data[0];
                    let isIncludeTax = parseInt(headerData.priceincludetax);

                    $("input[name='Tran[priceincludetax]']").val(isIncludeTax);

                    $('#kt_flexSwitchCustomDefault_1_1').prop('checked', isIncludeTax === 1);
                }

                response.data.forEach(function (item, index) {
                    $('#trandetail_repeater [data-repeater-create]').first().click();
                    let $product = $(document).find("select[name='Trandetail[" + index + "][productid]']");
                    $product.html(`<option value="${item.productid}" selected>${item.productname}</option>`);
                    $product.val(item.productid);
                    $(document).find("input[name='Trandetail[" + index + "][amount]']").val(item.remaining);
                    // $(document).find("input[name='Trandetail[" + index + "][stockmilik]']").val(item.stockmilik);
                    $(document).find("input[name='Trandetail[" + index + "][price]']").val(item.price);
                    $(document).find("input[name='Trandetail[" + index + "][itemsubtotaltax]']").val(item.itemsubtotaltax);
                    $(document).find("input[name='Trandetail[" + index + "][itemdiscpersen]']").val(item.itemdiscpersen);
                    $(document).find("input[name='Trandetail[" + index + "][freqvalue]']").val(item.freqvalue).prop('disabled', true);

                    let $taxSelect = $(document).find("select[name='Trandetail[" + index + "][itemtaxid]']");
                    $taxSelect.val(item.itemtaxid).trigger('change');

                    // $(document).find("textarea[name='Trandetail[" + index + "][description]']").val(item.description);
                    // $(document).find("input[name='Trandetail[" + index + "][itemtotaltax]']").val(item.itemtotaltax);
                    // $(document).find("input[name='Tran[totalafterdisc]']").val(item.totalafterdisc);
                    // $(document).find("input[name='Tran[subtotal]']").val(item.subtotal);
                    // $(document).find("input[name='Tran[grandtotal]']").val(item.grandtotal);

                    $("select[name='Tran[contact_id]']").each(function () {
                        let $select = $(this);
                        if (item.jobcompany && item.contact_id) {
                            let newOption = new Option(item.jobcompany, item.contact_id, true, true);
                            $select.append(newOption).trigger('change');

                            $select.next('.select2-container').find('.select2-selection')
                                .addClass('pe-none bg-secondary opacity-75');
                        }
                    });

                    let rowParent = $(document)
                        .find("input[name='Trandetail[" + index + "][amount]']")
                        .closest('tr[data-repeater-item]');

                });

                fetchProductPrice(row, 'qty');

            }
        });
    }

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
            placeholder: "Choose Product",
            allowClear: true,
            width: '100%',
            containerCss: { "display": "block", width: "100%" },
            dropdownParent: $('#modal_form_tran')
        })
            .on('select2:open.product', function () {
                let $dropdown = $('.select2-dropdown');
            })
            .on('change', function (e) {
                // console.log('selected');
                let val = $(this).val();
                let row = $(this).closest('tr');

                if (val) {
                    fetchProductPrice(row, 'qty');
                } else {
                    row.find('.product-stock').val(0);
                }

            }).on('select2:unselect', function (e) {
                let row = $(this).closest('tr');
                row.find('.product-stock').val(0);
            });

        initMasking();
    }

    function formatLocalizedNumber(number) {
        return number.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function setTotal() {
        var sum_subtotal = 0;
        var sum_itemdisc = 0;
        var sum_tax_ppn = 0;
        var sum_tax_pph = 0;
        var sum_gross_subtotal = 0;

        var taxInclude = $("#kt_flexSwitchCustomDefault_1_1").is(":checked");

        $('tbody.detail-rows > tr').each(function () {
            var $row = $(this);

            var itemprice = parseFloat(($row.find("input[name*='[price]']").val() || "").replace(/[^0-9.-]/g, '')) || 0;
            var itemqty = parseFloat(($row.find("input[name*='[amount]']").val() || "0")) || 0;
            var freq = parseFloat(($row.find("input[name*='[freqvalue]']").val() || "1")) || 1;
            var itemdiscpersen = parseFloat(($row.find("input[name*='[itemdiscpersen]']").val() || "0")) || 0;

            var subtotal = itemprice * itemqty * freq;
            var itemdisc = subtotal * (itemdiscpersen / 100);
            var subtotal_afterdisc = subtotal - itemdisc;

            var $taxSelect = $row.find("select[name*='[itemtaxid]']");
            var taxType = $taxSelect.val();
            var ppnRate = parseFloat($taxSelect.find("option[value='PPN']").data("value")) || 0;
            var pphRate = parseFloat($taxSelect.find("option[value='PPH']").data("value")) || 0;

            var taxPPN = 0, taxPPH = 0;

            if (taxInclude) {
                if (taxType === "PPN") {
                    var base = subtotal_afterdisc / (1 + (ppnRate / 100));
                    taxPPN = subtotal_afterdisc - base;
                    subtotal_afterdisc = base;
                } else if (taxType === "PPH") {
                    var base = subtotal_afterdisc / (1 + (pphRate / 100));
                    taxPPH = subtotal_afterdisc - base;
                    subtotal_afterdisc = base;
                } else if (taxType === "PPN+PPH") {
                    var base = subtotal_afterdisc / (1 + (ppnRate / 100));
                    taxPPN = subtotal_afterdisc - base;
                    subtotal_afterdisc = base;
                    taxPPH = subtotal_afterdisc * (pphRate / 100);
                }
            } else {
                if (taxType === "PPN") {
                    taxPPN = subtotal_afterdisc * (ppnRate / 100);
                } else if (taxType === "PPH") {
                    taxPPH = subtotal_afterdisc * (pphRate / 100);
                } else if (taxType === "PPN+PPH") {
                    taxPPN = subtotal_afterdisc * (ppnRate / 100);
                    taxPPH = subtotal_afterdisc * (pphRate / 100);
                }
            }

            var totalAftTax = taxInclude ? (subtotal_afterdisc + taxPPN - taxPPH) : (subtotal_afterdisc + taxPPN - taxPPH);

            $row.find("input[name*='[itemsubtotaltax]']").val(subtotal_afterdisc.toFixed(2));
            $row.find("input[name*='[itemtax]']").val((taxPPN - taxPPH).toFixed(2));
            $row.find("input[name*='[itemtotaltax]']").val(totalAftTax.toFixed(2));

            sum_gross_subtotal += subtotal; // Simpan nilai kotor asli
            sum_subtotal += subtotal_afterdisc;
            sum_itemdisc += itemdisc;
            sum_tax_ppn += taxPPN;
            sum_tax_pph += taxPPH;
        });

        var otherdiscount = parseFloat(($("input[name='Tran[otherdiscount]']").val() || "").replace(/[^0-9.-]/g, '')) || 0;
        var deliverycharge = parseFloat(($("input[name='Tran[deliverycharge]']").val() || "").replace(/[^0-9.-]/g, '')) || 0;

        // --- PERBAIKAN LOGIKA SUMMARY DI SINI ---
        var total_afterdisc = 0;

        if (taxInclude) {
            // Jika Pajak Include, nilai sum_subtotal sudah bersih dari diskon item (karena sudah jadi DPP)
            total_afterdisc = sum_subtotal;
        } else {
            // Jika Pajak Exclude, kurangi total kotor dengan total diskon item
            total_afterdisc = sum_gross_subtotal - sum_itemdisc;
        }

        var grandtotal = total_afterdisc + sum_tax_ppn - sum_tax_pph - otherdiscount + deliverycharge;

        // Output Master Form
        $("input[name='Tran[subtotal]']").val(sum_subtotal.toFixed(2));
        $("input[name='Tran[disc]']").val(sum_itemdisc.toFixed(2));
        $("input[name='Tran[totalafterdisc]']").val(total_afterdisc.toFixed(2));
        $("input[name='Tran[ppnamount]']").val(sum_tax_ppn.toFixed(2));
        $("input[name='Tran[pphamount]']").val(sum_tax_pph.toFixed(2));
        $("input[name='Tran[grandtotal]']").val(grandtotal.toFixed(2));
    }

    function fetchProductPrice(row, trigger) {
        let productid = row.find('.product-select').val() || '';
        if (!productid) return;

        let qty = parseFloat(row.find('.product-quantity').val().replace(/[^0-9.-]/g, '')) || 1;
        let trandate = $(document).find("input[name='Tran[setupdate]']").val();
        let tranduedate = $(document).find("input[name='Tran[tranduedate]']").val();

        $.ajax({
            url: "<?= Url::to(['product/price']) ?>",
            type: "GET",
            dataType: 'json',
            data: {
                productid: productid,
                qty: qty,
                trigger: trigger,
                trandate: trandate,
                tranduedate: tranduedate
            },
            success: function (response) {
                if (response.success) {
                    row.find('.product-quantity').val(response.data.qty);
                    row.find('.product-stock').val(response.data.stock);

                    if (response.data.price) {
                        row.find('.product-price').val(response.data.price);
                    }

                    setTotal();
                }
            },
            error: function () {
                console.warn('Gagal ambil harga produk');
            }
        });
    }

    $(document).on('input change', '.product-price, .itemdiscpersen, .product-quantity, .itemtaxid, .otherdiscount', function (e) {
        setTotal();
    });

    $(document).on('change', '#kt_flexSwitchCustomDefault_1_1', function () {
        setTotal();

        if (!this.checked) {
            $("input[name='Tran[priceincludetax]']").val('0');
        } else {
            $("input[name='Tran[priceincludetax]']").val('1');

        }
    });


    function applyAllTax() {
        let $all = $("select[name='alltax']");
        let allVal = $all.val();
        if (!allVal) return;

        let data = $all.select2('data')[0];
        let allText = data ? data.text : '';
        let allAmount = data ? data.amount : 0;

        $("select[name*='[itemtaxid]']").each(function () {
            let $s = $(this);

            if ($s.find("option[value='" + allVal + "']").length === 0) {
                let opt = new Option(allText || allVal, allVal, true, true);
                $(opt).attr('data-amount', allAmount);
                $s.append(opt);
            }

            $s.val(allVal).trigger('change');

            let row = $s.closest('tr');
            row.find('.itemtax').val(allAmount);
        });

        setTotal();
    }
</script>