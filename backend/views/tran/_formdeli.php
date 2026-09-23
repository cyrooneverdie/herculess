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
        <input type="hidden" id="current-tran-id"
            value="<?= $model->isNewRecord ? '' : Html::encode($model->tranid) ?>">

        <div class="mb-7 col-lg-3 col-md-4 col-sm-12">
            <label class="fw-semibold fs-6 mb-2"><?= Yii::$app->lang->t('tran', 'refid') ?></label>
            <?php
            $refidOptions = [
                'class' => 'form-select refid-select',
                'data-control' => 'select2',
                'data-module' => 'sales',
                'data-type' => 'order',
                'data-target' => $model->trantype
            ];
            ?>
            <?= $form->field($model, 'refid', [
                'errorOptions' => ['class' => 'text-danger mt-3'],
            ])->dropDownList(
                    $model->refid && $model->ref ? [$model->ref->tranid => $model->ref->tranno] : [],
                    $refidOptions
                )->label(false); ?>
        </div>
        <div class="mb-7 col-lg-3 col-md-4 col-sm-12">
            <label class="fw-semibold fs-6 mb-2"><?= Yii::$app->lang->t('tran', 'tran_no') ?></label>
            <?= $form->field($model, 'tranno')->textInput(['readOnly' => true])->label(false); ?>
        </div>

        <div class="mb-7 col-lg-3 col-md-4 col-sm-12">
            <label class="fw-semibold fs-6 mb-2">
                <?= Yii::$app->lang->t('extra', $model->trantype == 'purchase/delivery' ? 'po3' : 'delivery5') ?></label>
            <?= $form->field($model, 'trandate', [
                'errorOptions' => ['class' => 'text-danger mt-3'],
            ])->textInput([
                        'placeholder' => Yii::$app->lang->t('extra', 'delivery5'),
                        'required' => true,
                        'class' => 'form-control pickdate',
                    ])->label(false); ?>
        </div>

        <div class="mb-7 col-lg-3 col-md-6 col-sm-12">
            <label class="fw-semibold fs-6 mb-2">
                Crew Gudang
            </label>
            <?php
            $currentUserId = Yii::$app->user->id;
            $isWarehouseCrew = false;

            if ($currentUserId) {
                $sqlCheck = "SELECT c.divisionid 
                     FROM users u
                     INNER JOIN contacts c ON c.contact_id = u.contact_id 
                     WHERE u.userid = '{$currentUserId}'";

                $userDivision = Yii::$app->db->createCommand($sqlCheck)->queryScalar();

                $isWarehouseCrew = ($userDivision === 'division.warehouse');
            }

            echo $form->field($model, 'contact_id')->dropDownList(
                $model->contact_id ? [$model->contact_id => Yii::$app->function->findByField("contact_name", "contacts", " and contact_id ='" . $model->contact_id . "' ")] : [],
                [
                    'class' => 'form-select',
                    'data-control' => 'select2',
                    'required' => true,
                    'disabled' => $isWarehouseCrew,
                ]
            )->label(false);
            ?>
        </div>
    </div>

    <div class=" mb-7 mt-5">
        <div class="card-body">
            <ul class="nav nav-tabs flex-nowrap text-nowrap" role="tablist">
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0 active"
                        data-bs-toggle="tab" data-bs-target="#detail" type="button" role="tab" aria-controls="detail"
                        aria-selected="false">
                        <th><?= Yii::$app->lang->t('produk', 'produk_transaksi') ?></th>
                    </button>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade p-3 show active" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                    <div id="trandetail_repeater">
                        <div class="table-responsive">
                            <table class="table table-row-dashed align-middle">
                                <thead>
                                    <tr class="text-gray-600 fw-bold fs-7 text-uppercase ">
                                        <th class="min-w-150px text-start">Produk</th>
                                        <th class="min-w-100px text-center">Qty</th>
                                        <th class="min-w-100px text-center">Sent</th>
                                        <th class="min-w-100px text-center">Remain</th>
                                        <th class="min-w-150px text-center">Send Milik</th>
                                        <th class="min-w-150px text-center">Send Pinjam</th>
                                        <th class="min-w-100px text-center"></th>
                                    </tr>
                                </thead>

                                <tbody class="detail-rows" data-repeater-list="Trandetail">
                                    <?php if (!empty($modeldetails)): ?>
                                        <?php foreach ($modeldetails as $index => $modeldetail): ?>
                                            <tr data-repeater-item class="align-top event-item"
                                                data-has-remain="<?= !empty($modeldetail->refid) ? 1 : 0 ?>">
                                                <td>
                                                    <input type="hidden" name="trandetailid"
                                                        value="<?= $modeldetail->trandetailid ?? "" ?>" readonly>

                                                    <input type="hidden" name="refid" value="<?= $modeldetail->refid ?? "" ?>"
                                                        readonly>

                                                    <select name="productid" class="form-select product-select mb-2">
                                                        <option value="<?= $modeldetail->productid ?? "" ?>" selected>
                                                            <?= $modeldetail->productid ? Yii::$app->function->findByField("productname", "products", " and productid ='" . $modeldetail->productid . "' ") : "" ?>
                                                        </option>
                                                    </select>

                                                    <div class="d-flex">
                                                        <textarea name="description" rows="3" class="form-control"
                                                            placeholder="Deskripsi"><?= $modeldetail->description ?? "" ?></textarea>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <input type="number" name="remain2"
                                                        class="form-control bg-light text-center product-quantity"
                                                        value="<?= $modeldetail->remain2 ?? '0' ?>" readonly
                                                        data-product-index="<?= $index ?>">
                                                </td>
                                                <td>
                                                    <input type="number" name="freqvalue"
                                                        class="form-control bg-light text-center product-sent"
                                                        value="<?= $modeldetail->freqvalue ?? '0' ?>" readonly
                                                        data-product-index="<?= $index ?>">
                                                </td>
                                                <td>
                                                    <input type="number" name="remain"
                                                        class="form-control bg-light text-center product-remain"
                                                        value="<?= $modeldetail->remain ?? '0' ?>" readonly
                                                        data-product-index="<?= $index ?>">
                                                </td>

                                                <td>
                                                    <input type="hidden" name="before1"
                                                        value="<?= $modeldetail->amount ?? '0' ?>">
                                                    <div class="input-group product-quantity-group">

                                                        <span class="input-group-text" style="width: 70px">Send</span>
                                                        <input type="number" name="amount"
                                                            class="form-control text-center product-send-milik"
                                                            value="<?= $modeldetail->amount ?? '0' ?>"
                                                            data-product-index="<?= $index ?>">
                                                    </div>
                                                    <div class="input-group product-stock-group">
                                                        <span class="input-group-text" style="width: 70px">Stock</span>
                                                        <input type="number" name="stockmilik"
                                                            class="form-control text-center bg-light product-stock-milik" value="<?= ($model->isNewRecord && isset($modeldetail->product))
                                                                ? $modeldetail->product->getStock($model->trandate, $model->trandate, $model->tranid)['onHandMilik']
                                                                : $modeldetail->stockmilik ?? '0' ?>"
                                                            data-product-index="<?= $index ?>" readonly>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="before2"
                                                        value="<?= $modeldetail->amount2 ?? '0' ?>">
                                                    <div class="input-group product-quantity-group">
                                                        <span class="input-group-text" style="width: 70px">Send</span>
                                                        <input type="number" name="amount2"
                                                            class="form-control text-center product-send-pinjam"
                                                            value="<?= $modeldetail->amount2 ?? '0' ?>"
                                                            data-product-index="<?= $index ?>">
                                                    </div>
                                                    <div class="input-group product-stock-group d-none">
                                                        <span class="input-group-text" style="width: 70px">Stock</span>
                                                        <input type="number" name="stockpinjam"
                                                            class="form-control text-center bg-light product-stock-pinjam"
                                                            value="<?= $model->isNewRecord && $modeldetail->productid
                                                                ? $modeldetail->product->getStock($model->trandate, $model->trandate, $model->tranid)['onHandPinjam']
                                                                : $modeldetail->stockpinjam ?? '0' ?>"
                                                            data-product-index="<?= $index ?>" readonly>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-icon btn-danger"
                                                        data-repeater-delete>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mb-7">
        <label class="fw-semibold fs-6 mb-2">
            <?= Yii::$app->lang->t('purchase_table', 'purchase_deskripsi') ?>
        </label>
        <?= $form->field($model, 'note', [
            'errorOptions' => ['class' => 'text-danger mt-3'],
        ])->textarea(
                [
                    'rows' => 3,
                    'class' => 'form-control',
                ]
            )->label(false); ?>
    </div>

    <div class="text-end pt-10">
        <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel"
            data-bs-dismiss="modal"><?= Yii::$app->lang->t('back_home', 'chat34') ?></button>
        <?= Html::submitButton($model->isNewRecord ? Yii::$app->lang->t('extra', 'extra16') : Yii::$app->lang->t('extra', 'extra16'), ['id' => 'btnsubmit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        setForm();

        $('.detail-rows tr').each(function () {
            applyStockRules($(this));
        });

        $('.refid-select').select2({
            ajax: {
                url: "<?= Url::to(['tran/select']) ?>",
                type: "POST",
                dataType: "json",
                data: function (params) {
                    const el = $('.refid-select');
                    return {
                        search: params.term || '',
                        q: params.term,
                        page: params.page,
                        module: el.data('module'),
                        type: el.data('type'),
                        target: el.data('target')
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
                return param.text || "Choose Reference";
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
            placeholder: "Choose Reference",
            allowClear: true,
            dropdownParent: $('#modal_form_tran')
        }).on('select2:select', function (e) {
            generateDeli();
            const refid = e.params.data.id;
            if (!refid) return;

            const poTranid = e.params.data.po_tranid || '';
            const pdTranid = e.params.data.pd_tranid || '';

            if (poTranid && pdTranid === '') {
                Swal.fire({
                    title: "Ada PO",
                    text: "Cek Stock In PO",
                    icon: "warning",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "OK"
                });
            }

        });

        $(document).on('input', '.product-send-milik, .product-send-pinjam', function () {
            let row = $(this).closest('tr');

            let inputMilik = parseFloat(row.find('.product-send-milik').val()) || 0;
            let inputPinjam = parseFloat(row.find('.product-send-pinjam').val()) || 0;
            let totalInput = inputMilik + inputPinjam;

            let remain = parseFloat(row.find('.product-remain').val()) || 0;
            let stockMilik = parseFloat(row.find('.product-stock-milik').val()) || 0;
            let stockPinjam = parseFloat(row.find('.product-stock-pinjam').val()) || 0;

            let productId = row.find('.product-select').val();
            let usedMilikOther = 0, usedPinjamOther = 0;
            if (productId) {
                $('.detail-rows tr[data-repeater-item]').not(row).each(function () {
                    let r = $(this);
                    if (r.find('.product-select').val() === productId) {
                        usedMilikOther += parseFloat(r.find('.product-send-milik').val()) || 0;
                        usedPinjamOther += parseFloat(r.find('.product-send-pinjam').val()) || 0;
                    }
                });
            }
            let availMilik = Math.max(0, stockMilik - usedMilikOther);
            let availPinjam = Math.max(0, stockPinjam - usedPinjamOther);
            let totalAvailStock = availMilik + availPinjam;

            let maxCap = (remain > 0) ? Math.min(remain, totalAvailStock) : totalAvailStock;

            let isExceed = totalInput > maxCap || inputMilik > availMilik || inputPinjam > availPinjam;

            if (isExceed) {
                if (totalInput > maxCap) {
                    if ($(this).hasClass('product-send-milik')) {
                        let maxMilik = Math.min(availMilik, maxCap - inputPinjam);
                        row.find('.product-send-milik').val(Math.max(0, maxMilik));
                    } else if ($(this).hasClass('product-send-pinjam')) {
                        let maxPinjam = Math.min(availPinjam, maxCap - inputMilik);
                        row.find('.product-send-pinjam').val(Math.max(0, maxPinjam));
                    }
                } else {
                    if (inputMilik > availMilik) row.find('.product-send-milik').val(availMilik);
                    if (inputPinjam > availPinjam) row.find('.product-send-pinjam').val(availPinjam);
                }

                let textMsg = '';
                if (remain > 0 && totalInput > remain) {
                    textMsg = 'Jumlah send tidak boleh melebihi sisa yang harus dikirim (Remain: ' + remain + ').';
                } else {
                    textMsg = 'Jumlah send tidak boleh melebihi stok yang tersedia (' + totalAvailStock + ').';
                }

                Swal.fire({
                    icon: 'warning',
                    title: 'Batas Maksimal Tercapai',
                    text: textMsg,
                    timer: 1800,
                    showConfirmButton: false
                });
            }

            applyStockRules(row);

            if (productId) {
                $('.detail-rows tr[data-repeater-item]').not(row).each(function () {
                    let r = $(this);
                    if (r.find('.product-select').val() === productId) {
                        applyStockRules(r);
                    }
                });
            }
        });

    });

    function setFunction() {
        $(".product-select").select2({
            ajax: {
                url: "<?= \yii\helpers\Url::to(['product/search']) ?>",
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
            dropdownParent: $('#trandetail_repeater'),

        }).on('select2:open', function () {
            let $dropdown = $('.select2-dropdown');
            $dropdown.find('.add-new-prd-btn').remove();

        }).on('select2:unselect', function (e) {
            let row = $(this).closest('tr');
            row.find(".product-buttons").hide();
            row.find('.product-quantity').val(0);
        }).on('change', function (e) {
            let val = $(this).val();
            let row = $(this).closest('tr');

            if (val) {
                row.find(".product-buttons").show();

            } else {
                row.find(".product-buttons").hide();

            }
            row.find('.product-quantity').val(0);
            row.find('.product-sent').val(0);
            row.find('.product-remain').val(0);
            row.find('.product-receive').val(0);
            row.find('.product-treceived').val(0);
            row.find('.product-send-milik').val(0);
            row.find('.product-send-pinjam').val(0);
            cekStock(row);

            if (val) {
                $('.detail-rows tr[data-repeater-item]').not(row).each(function () {
                    let r = $(this);
                    if (r.find('.product-select').val() === val) {
                        applyStockRules(r);
                    }
                });
            }
        }).on('select2:unselect', function (e) {
        });

    }

    function repeatNested(selection, inner) {
        $(selection).repeater({
            show: function () {
                $(this).slideDown();
                $(this).attr('data-has-remain', 0);
                $(this).find('.product-quantity').val(0);
                $(this).find('.product-sent').val(0);
                $(this).find('.product-remain').val(0);
                $(this).find('.product-send-milik').val(0);
                $(this).find('.product-send-pinjam').val(0);
                $(this).find('.product-stock-milik').val(0);
                $(this).find('.product-stock-pinjam').val(0);
                setFunction();
            },
            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });
    }

    function cekStock(row) {
        let productid = row.find('.product-select').val() || '';
        let trandate = $(document).find("input[name='Tran[trandate]']").val();
        let tranid = $('#current-tran-id').val() || '';
        // console.info("Cek stock untuk productid:", productid, "trandate:", trandate, "tranid:", tranid);

        let isoDate = formatDateToISO(trandate);

        $.ajax({
            url: "<?= \yii\helpers\Url::to(['product/cekstock']) ?>",
            type: 'GET',
            data: {
                products: [productid],
                date: `${trandate} - ${trandate}`,
                tranid: tranid
            },
            success: function (response) {
                let stockData = response.data[0].stock;
                console.info(isoDate);

                if (stockData && stockData[isoDate]) {
                    row.find('.product-stock-milik').val(stockData[isoDate].onHandMilik);
                    row.find('.product-stock-pinjam').val(stockData[isoDate].onHandPinjam);
                } else {
                    row.find('.product-stock-milik').val(0);
                    row.find('.product-stock-pinjam').val(0);
                }

                applyStockRules(row);

            },
            error: function (xhr, status, error) {
                $('#btnsubmit').prop('disabled', false);
                Swal.fire({
                    icon: "error",
                    title: "Failed",
                    html: "Something went wrong!",
                });
            }
        });
    }

    function applyStockRules(row) {
        let productId = row.find('.product-select').val() || null;
        let stockMilik = parseFloat(row.find('.product-stock-milik').val()) || 0;
        let stockPinjam = parseFloat(row.find('.product-stock-pinjam').val()) || 0;
        let remain = parseFloat(row.find('.product-remain').val()) || 0;
        let hasRemain = row.attr('data-has-remain') == '1';

        let sendMilik = row.find('.product-send-milik');
        let sendPinjam = row.find('.product-send-pinjam');

        let lockMilik = stockMilik <= 0;
        let lockPinjam = stockPinjam <= 0;

        sendMilik.prop('readonly', lockMilik)
            .toggleClass('bg-light-danger', lockMilik)
            .css('cursor', lockMilik ? 'not-allowed' : 'auto');

        sendPinjam.prop('readonly', lockPinjam)
            .toggleClass('bg-light-danger', lockPinjam)
            .css('cursor', lockPinjam ? 'not-allowed' : 'auto');

        if (lockMilik) sendMilik.val(0);
        if (lockPinjam) sendPinjam.val(0);

        let usedMilikOther = 0, usedPinjamOther = 0;
        if (productId) {
            $('.detail-rows tr[data-repeater-item]').not(row).each(function () {
                let r = $(this);
                if ((r.find('.product-select').val() || null) === productId) {
                    usedMilikOther += parseFloat(r.find('.product-send-milik').val()) || 0;
                    usedPinjamOther += parseFloat(r.find('.product-send-pinjam').val()) || 0;
                }
            });
        }

        let availMilik = Math.max(0, stockMilik - usedMilikOther);
        let availPinjam = Math.max(0, stockPinjam - usedPinjamOther);

        let valMilik = Math.max(0, parseFloat(sendMilik.val()) || 0);
        let valPinjam = Math.max(0, parseFloat(sendPinjam.val()) || 0);

        valMilik = Math.min(valMilik, availMilik);
        valPinjam = Math.min(valPinjam, availPinjam);

        let totalCap = hasRemain
            ? Math.min(remain, availMilik + availPinjam)
            : (availMilik + availPinjam);

        let total = valMilik + valPinjam;

        if (total > totalCap && totalCap >= 0) {
            let excess = total - totalCap;
            let reducePinjam = Math.min(excess, valPinjam);
            valPinjam -= reducePinjam;
            excess -= reducePinjam;
            valMilik = Math.max(0, valMilik - excess);
        }

        sendMilik.val(valMilik);
        sendPinjam.val(valPinjam);
    }

    function formatDateToISO(dateStr) {
        let parts = dateStr.split('/');
        return `${parts[2]}-${parts[1]}-${parts[0]}`;
    }

    function setForm() {
        if (<?php echo $isajax; ?>) {
            var form = $('#tran-form');
            form.on('submit', function (e) {
                e.preventDefault();

                let totalSemuaItem = 0;
                let adaBarisKosong = false;

                form.find("tr[data-repeater-item]").each(function () {
                    let row = $(this);
                    let amount = parseFloat(row.find("input[name*='[amount]']").val()) || 0;
                    let amount2 = parseFloat(row.find("input[name*='[amount2]']").val()) || 0;

                    if (amount <= 0 && amount2 <= 0) {
                        adaBarisKosong = true;
                        return false;
                    }

                    totalSemuaItem += (amount + amount2);
                });

                if (adaBarisKosong) {
                    Swal.fire({
                        icon: "warning",
                        title: "Gagal Menyimpan",
                        text: "Ada produk yang jumlah sendnya 0. Pastikan semua produk memiliki jumlah send lebih dari 0.",
                    });
                    return false;
                }

                if (totalSemuaItem <= 0) {
                    Swal.fire({
                        icon: "warning",
                        title: "Gagal Menyimpan",
                        text: "Jika ingin menyimpan, pastikan minimal ada 1 item dengan jumlah send lebih dari 0.",
                    });
                    return false;
                }

                $('#btnsubmit').prop('disabled', true);

                var formData = form.serializeArray();
                var variantData = formData.filter(item => item.name.includes('variants') || item.name.includes('Tranvariant'));

                $.ajax({
                    url: form.attr("action"),
                    type: form.attr("method"),
                    data: form.serialize(),
                    success: function (data) {
                        $('#btnsubmit').prop('disabled', false);
                        if (data['success']) {
                            Swal.fire({
                                icon: "success",
                                title: "Successful",
                                html: data['pesan']
                            });
                            $('#modal_form_tran').modal('hide');
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
                        $('#btnsubmit').prop('disabled', false);
                        Swal.fire({
                            icon: "error",
                            title: "Failed",
                            html: "Something went wrong!",
                        });
                    }
                });
            });
        }

        selectContact($('#modal_form_tran'), "select[name='Tran[contact_id]']", 'employee', 'warehouse', 'Crew');

        repeatNested('#trandetail_repeater', '.inner-repeater');
        <?php if ($model->isNewRecord): ?>
            $('.event-item').remove();
        <?php endif; ?>
        setFunction();
        initMasking();
    }

    function selectContact(target, selection, type, divisionid, label) {
        let $select = $(target).find(selection);

        $select.off('change.contact').off('select2:open.contact').select2({
            ajax: {
                url: "<?= Url::to(['contact/select']) ?>",
                type: "POST",
                dataType: "json",
                data: function (params) {
                    return {
                        contacttype: type,
                        divisionid: divisionid,
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
                    return `Choose ${label}`;
                }
                return param.text;
            },
            templateResult: function (contact) {
                if (!contact.id) {
                    return `Choose ${label}`;
                }
                if (contact.loading) {
                    return type === 'customer' ? contact.contact_name : contact.text;
                }
                let html = `
                    <div class="d-flex align-items-start gap-2 py-1">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px;height:32px;font-size:13px;font-weight:600;">
                            ${type === 'customer' ? contact.contact_name.charAt(0).toUpperCase() : contact.text.charAt(0).toUpperCase()}
                        </div>
                        <div class="d-flex flex-column flex-grow-1 overflow-hidden">
                            <span class="fw-semibold text-dark text-truncate" style="font-size:13px;">
                                ${type === 'customer' ? contact.contact_name : contact.text}
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
            placeholder: `Choose ${label}`,
            allowClear: true,
            width: '100%',
            dropdownParent: $(target).closest('.modal-content').length ? $(target).closest('.modal-content') : $(target)
        }).on('select2:open.contact', function () {
            let $dropdown = $('.select2-dropdown');
            $dropdown.find('.add-new-ctc-btn').remove();
            $dropdown.find('.add-new-prd-btn').remove();

            $dropdown.append(`
                <div class="add-new-ctc-btn" style="padding:6px; text-align:center; border-top:1px solid #ddd;">
                    <button type="button" 
                            class="btn btn-sm btn-primary" 
                            data-type="${type}"
                            data-position="${divisionid}">
                        <i class="fas fa-plus-circle me-2"></i> Add New ${label}
                    </button>
                </div>
            `);
        }).on('change.contact', function (e) {
            // console.log($(this));
        });
    }

    function generateDeli() {
        let refid = $("select[name='Tran[refid]']").val();
        $('.event-item').remove();

        $.ajax({
            url: "<?= Url::to(['tran/listtranvariant']) ?>",
            type: "GET",
            dataType: 'json',
            data: {
                id: refid
            },
            success: function (response) {
                $(document).find("select[name='Tran[eventtype]']").val(response.data[0].eventtype);
                response.data.forEach(function (item, index) {
                    $('#trandetail_repeater .repeat-parent-btn').first().click();
                    $(document).find("input[name='Trandetail[" + index + "][refid]']").val(item.refid);
                    $(document).find("input[name='Trandetail[" + index + "][trandetailid]']").val(item.trandetailid);
                    $(document).find("input[name='Trandetail[" + index + "][tranid]']").val(item.tranid);
                    $(document).find("textarea[name='Trandetail[" + index + "][description]']").val(item.description);
                    $(document).find("input[name='Trandetail[" + index + "][productname]']").val(item.productname);
                    let $product = $(document).find("select[name='Trandetail[" + index + "][productid]']");
                    $product.html(`<option value="${item.productid}" selected>${item.productname}</option>`);
                    $product.val(item.productid);

                    $(document).find("input[name='Trandetail[" + index + "][remain2]']").val(item.qty);
                    $(document).find("input[name='Trandetail[" + index + "][freqvalue]']").val(item.sent);
                    $(document).find("input[name='Trandetail[" + index + "][remain]']").val(item.remain);
                    $(document).find("input[name='Trandetail[" + index + "][amount]']").val(0);
                    $(document).find("input[name='Trandetail[" + index + "][amount2]']").val(0);

                    let rowParent = $(document)
                        .find("input[name='Trandetail[" + index + "][amount]']")
                        .closest('tr[data-repeater-item]');

                    rowParent.attr('data-has-remain', 1);
                    cekStock(rowParent);

                });
            },
            error: function () {
                console.warn('Error get crew');
            }
        });
    }

</script>