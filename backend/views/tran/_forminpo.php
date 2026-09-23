<?php
//formin.php
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

$reftypeOriginal = $model->reftype;

if (!$model->isNewRecord) {
    if ($model->trantype === 'purchase/delivery' && $model->eventtype === null) {
        $reftypeOriginal = isset($model->ref) ? $model->ref->trantype : ($model->reftype ?: 'purchase/order');
    }
}
?>
<?= $form->field($model, 'trantype')->hiddenInput()->label(false); ?>
<?= $form->field($model, 'eventtype')->hiddenInput()->label(false); ?>
<?= $form->field($model, 'reftype')->hiddenInput(['id' => 'reftype_original', 'value' => $reftypeOriginal ?? ''])->label(false); ?>

<div class="d-flex flex-column scroll-y px-5 px-lg-10" id="modal_form_asset_scroll">
    <div class="row">
        <div class="mb-7 col-lg-4 col-md-4 col-sm-12">
            <label class="fw-semibold fs-6 mb-2"><?= Yii::$app->lang->t('tran', 'refid') ?></label>
            <?= $form->field($model, 'refid', [
                'errorOptions' => ['class' => 'text-danger mt-3'],
            ])->dropDownList(
                    $model->refid && $model->ref ? [$model->ref->tranid => $model->ref->tranno] : [],
                    [
                        'class' => 'form-select refid-select',
                        'data-control' => 'select2',
                    ]
                )->label(false); ?>
        </div>
        <div class="mb-7 col-lg-4 col-md-4 col-sm-12">
            <label class="fw-semibold fs-6 mb-2"><?= Yii::$app->lang->t('tran', 'tran_no') ?></label>
            <?= $form->field($model, 'tranno')->textInput(['readOnly' => true])->label(false); ?>
        </div>
        <div class="mb-7 col-lg-4 col-md-4 col-sm-12">
            <label class="fw-semibold fs-6 mb-2">Date</label>
            <?= $form->field($model, 'trandate', [
                'errorOptions' => ['class' => 'text-danger mt-3'],
            ])->textInput([
                        'placeholder' => Yii::$app->lang->t('extra', 'delivery5'),
                        'required' => true,
                        'class' => 'form-control pickdate',
                    ])->label(false); ?>
        </div>
    </div>

    <div class="mb-7">
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
                            <table class="table table-row-dashed align-middle" id="table-repeater-detail">
                                <thead>
                                    <tr class="text-gray-600 fw-bold fs-7 text-uppercase text-center header-standard">
                                        <th class="min-w-200px text-start">Produk</th>
                                        <th class="min-w-100px text-center">Qty</th>
                                        <th class="min-w-100px text-center">Total Received</th>
                                        <th class="min-w-100px text-center">Remain</th>
                                        <th class="min-w-200px text-center">Receive</th>
                                        <th class="min-w-50px"></th>
                                    </tr>
                                </thead>

                                <tbody class="detail-rows" data-repeater-list="Trandetail">
                                    <?php if (!empty($modeldetails)): ?>
                                        <?php foreach ($modeldetails as $index => $modeldetail): ?>
                                            <tr data-repeater-item class="align-top event-item">

                                                <td>
                                                    <input type="hidden" name="trandetailid"
                                                        value="<?= $modeldetail->trandetailid ?? "" ?>">
                                                    <input type="hidden" name="tranid"
                                                        value="<?= $modeldetail->tranid ?? "" ?>">
                                                    <input type="hidden" name="refid"
                                                        value="<?= $modeldetail->refid ?? $modeldetail->trandetailid ?? "" ?>">

                                                    <div class="product-select-container">
                                                        <select name="productid" class="form-select product-select mb-2">
                                                            <option value="<?= $modeldetail->productid ?? "" ?>" selected>
                                                                <?= $modeldetail->productid ? Yii::$app->function->findByField("productname", "products", " and productid ='" . $modeldetail->productid . "' ") : "" ?>
                                                            </option>
                                                        </select>
                                                    </div>
                                                </td>

                                                <td class="text-center">
                                                    <div class="view-standard">
                                                        <input type="number" name="amount"
                                                            class="form-control bg-light text-center product-quantity"
                                                            value="<?= $modeldetail->amount ?? '0' ?>" readonly>
                                                    </div>

                                                </td>

                                                <td class="text-center">
                                                    <div class="view-standard">
                                                        <input type="number" name="amount2"
                                                            class="form-control bg-light text-center product-treceived"
                                                            value="<?= $modeldetail->amount2 ?? '0' ?>" readonly>
                                                    </div>
                                                </td>

                                                <td class="text-center view-standard">
                                                    <input type="number" name="remain"
                                                        class="form-control bg-light text-center product-remain"
                                                        value="<?= $modeldetail->remain ?? '0' ?>" readonly>
                                                </td>

                                                <td class="text-center">
                                                    <div class="view-standard">
                                                        <input type="number" name="remain2"
                                                            class="form-control text-center product-receive"
                                                            value="<?= $modeldetail->remain2 ?? '0' ?>">
                                                    </div>

                                                </td>

                                                <td class="text-center">
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
                                <i class="fa fa-plus fs-4 me-1"></i> Add
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
        setFunction();

        $('.detail-rows tr').each(function () {
            applyStockRules($(this));
        });

        $(document).on('input', '.product-receive', function () {
            let $this = $(this);
            let row = $this.closest('tr');

            let remain = parseInt(row.find(".product-remain").val()) || 0;
            let currentVal = parseInt($this.val()) || 0;

            if (currentVal < 0) {
                $this.val(0);
                currentVal = 0;
            }

            if (remain > 0 && currentVal > remain) {
                $this.val(remain);
                currentVal = remain;

                Swal.fire({
                    icon: 'warning',
                    title: 'Batas Maksimal',
                    text: 'Jumlah receive tidak boleh melebihi sisa (remain): ' + remain,
                    timer: 1500,
                    showConfirmButton: false
                });
            }

            applyStockRules(row);
        });

    });

    // function getSelectedProductIds() {
    //     let selectedIds = [];
    //     $(".product-select").each(function () {
    //         let val = $(this).val();
    //         if (val) {
    //             selectedIds.push(val.toString());
    //         }
    //     });
    //     return selectedIds;
    // }

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
            row.find('.product-treceived').val(0);
            row.find('.product-send').val(0);
            applyStockRules(row);

        });

    }

    function repeatNested(selection, inner) {
        $(selection).repeater({
            show: function () {
                $(this).slideDown();
                $(this).find('.product-quantity').val(0);
                $(this).find('.product-treceived').val(0);
                $(this).find('.product-remain').val(0);
                $(this).find('.product-receive').val(0);
                setFunction();
            },
            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });
    }

    function setForm() {
        if (<?php echo $isajax; ?>) {
            var form = $('#tran-form');
            form.on('submit', function (e) {
                e.preventDefault();
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

        setTimeout(function () {
            let reftype = $('#reftype_original').val();
            // console.log("Reftype Original saat load (Ready):", reftype);
            if (reftype) {
                applyRefTypeUI(reftype);
            }
        }, 50);
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

    function applyRefTypeUI(reftype) {
        if (reftype === 'sales/order') {
            $('.header-return').removeClass('d-none');
            $('.header-standard').addClass('d-none');
            $('.crew-gudang').removeClass('d-none');

            $('#table-repeater-detail .view-return').removeClass('d-none').find('input').prop('disabled', false);
            $('#table-repeater-detail .view-standard').addClass('d-none').find('input').prop('disabled', true);
            $('#table-repeater-detail .product-select-container').addClass('d-none');
            $('#table-repeater-detail .product-name-text').removeClass('d-none');

        } else if (reftype === 'purchase/order') {
            $('.header-standard').removeClass('d-none');
            $('.header-return').addClass('d-none');

            $('#table-repeater-detail .view-standard').removeClass('d-none').find('input').prop('disabled', false);
            $('#table-repeater-detail .view-return').addClass('d-none').find('input').prop('disabled', true);
            $('#table-repeater-detail .product-select-container').removeClass('d-none');
            $('#table-repeater-detail .product-name-text').addClass('d-none');
        }
    }

    function generateDeli(refid, reftypeOriginal) {
        if (!refid) {
            refid = $("select[name='Tran[refid]']").val();
        }
        if (!refid) return;

        $('#trandetail_repeater [data-repeater-item]').remove();

        let ajaxUrl = "";
        if (reftypeOriginal === 'sales/order') {
            ajaxUrl = "<?= Url::to(['tran/loadreturn']) ?>";
        } else {
            ajaxUrl = "<?= Url::to(['tran/loadstockin']) ?>";
        }

        $.ajax({
            url: ajaxUrl,
            type: "GET",
            dataType: 'json',
            data: { id: refid },
            success: function (response) {
                applyRefTypeUI(reftypeOriginal);

                if (!response.data || response.data.length === 0) return;

                response.data.forEach(function (item, index) {
                    $('#trandetail_repeater [data-repeater-create]').click();
                    let $latestRow = $('#trandetail_repeater [data-repeater-item]').last();
                    $latestRow.addClass('event-item');

                    if (reftypeOriginal === 'sales/order') {
                        $latestRow.find('.view-return').removeClass('d-none').find('input').prop('disabled', false);
                        $latestRow.find('.view-standard').addClass('d-none').find('input').prop('disabled', true);

                        let $productSelect = $latestRow.find("select[name$='[productid]']");
                        if ($productSelect.length) {
                            $productSelect.html(`<option value="${item.productid}" selected>${item.productname}</option>`);
                            $productSelect.val(item.productid).trigger('change');
                        }

                        $latestRow.find('.product-select-container').addClass('d-none');
                        $latestRow.find('.product-name-text').removeClass('d-none');

                        const sentMilik = parseInt(item.sent_milik) || 0;
                        const sentPinjam = parseInt(item.sent_pinjam) || 0;
                        const remMilik = parseInt(item.remain_milik) || 0;
                        const remPinjam = parseInt(item.remain_pinjam) || 0;

                        $latestRow.find("input[name$='[refid]']").val(item.trandetailid);
                        $latestRow.find("input[name$='[productname]']").val(item.productname);
                        $latestRow.find("input[name$='[productid]']").val(item.productid);

                        $latestRow.find(".send-milik").val(sentMilik);
                        $latestRow.find(".send-pinjam").val(sentPinjam);
                        $latestRow.find(".send-total").val(sentMilik + sentPinjam);

                        $latestRow.find(".remain-milik").val(remMilik);
                        $latestRow.find(".remain-pinjam").val(remPinjam);
                        $latestRow.find(".remain-total").val(remMilik + remPinjam);

                        $latestRow.find(".return-milik").val(remMilik);
                        $latestRow.find(".return-pinjam").val(remPinjam);
                        $latestRow.find(".return-total").val(remMilik + remPinjam);

                    } else {
                        $latestRow.find('.view-standard').removeClass('d-none').find('input').prop('disabled', false);
                        $latestRow.find('.view-return').addClass('d-none').find('input').prop('disabled', true);
                        $latestRow.find('.product-select-container').removeClass('d-none');
                        $latestRow.find('.product-name-text').addClass('d-none');

                        $latestRow.find("input[name$='[trandetailid]']").val(item.trandetailid);
                        $latestRow.find("input[name$='[tranid]']").val(item.tranid);
                        $latestRow.find("input[name$='[refid]']").val(item.trandetailid);

                        let $productSelect = $latestRow.find("select[name$='[productid]']");
                        if ($productSelect.length) {
                            $productSelect.html(`<option value="${item.productid}" selected>${item.productname}</option>`);
                            $productSelect.val(item.productid).trigger('change');
                        }

                        $latestRow.find(".product-quantity").val(item.amount_po);
                        $latestRow.find(".product-treceived").val(item.amount_in || 0);
                        $latestRow.find(".product-remain").val(item.remain || 0);
                    }
                });

                if (typeof setFunction === "function") {
                    setFunction();
                }
            },
            error: function () {
                console.warn('Error get reference details');
            }
        });
    }

    $('.refid-select').select2({
        ajax: {
            url: "<?= Url::to(['tran/select']) ?>",
            type: "POST",
            dataType: "json",
            data: function (params) {
                return {
                    search: params.term || '',
                    q: params.term,
                    page: params.page,
                    module: 'purchase',
                    type: 'delivery',
                    target: 'purchase/delivery',
                    subtype: 2
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
        escapeMarkup: function (markup) { return markup; },
        templateSelection: function (param) { return param.text || "Choose Reference"; },
        templateResult: function (param) {
            if (!param.id || param.loading) return param.text;

            return $(`
            <div class="d-flex align-items-start gap-2 py-1">
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px;height:32px;font-size:13px;font-weight:600;">
                    ${param.contact_name ? param.contact_name.charAt(0).toUpperCase() : param.text.charAt(0).toUpperCase()}
                </div>
                <div class="d-flex flex-column flex-grow-1 overflow-hidden">
                    <span class="fw-semibold text-dark text-truncate" style="font-size:13px;">${param.text}</span>
                    <div class="d-flex flex-wrap gap-1 mt-1">
                        ${param.contact_name ? `<span class="badge rounded-pill bg-info bg-opacity-10 text-info" style="font-size:11px;"><i class="fa fa-user me-1 text-info"></i>${param.contact_name}</span>` : ''}
                        ${param.jobcompany ? `<span class="badge rounded-pill bg-primary bg-opacity-10 text-primary" style="font-size:11px;"><i class="fa fa-building me-1 text-primary"></i>${param.jobcompany}</span>` : ''}
                        ${param.trandate ? `<span class="badge rounded-pill bg-success bg-opacity-10 text-success" style="font-size:11px;"><i class="fa fa-calendar me-1 text-success"></i>${param.trandate}</span>` : ''}
                    </div>
                </div>
            </div>
        `);
        },
        placeholder: "Choose Reference",
        allowClear: true,
        dropdownParent: $('#tran-form')
    }).on('select2:select', function (e) {
        const selectedData = e.params.data;
        const refid = selectedData.id;
        let originalReftype = selectedData.reftype;

        let dbTrantype = 'purchase/delivery';
        $('input[name="Tran[trantype]"]').val(dbTrantype);

        if (selectedData && selectedData.eventtype !== undefined) {
            $("input[name='Tran[eventtype]']").val(selectedData.eventtype);
            // console.log("Eventtype berhasil diset ke: " + selectedData.eventtype);
        }

        if (selectedData.eventtype === null || selectedData.eventtype === "" || selectedData.eventtype === undefined) {
            $('#reftype_original').val(originalReftype);
            // console.log("Eventtype null, reftype disimpan:", originalReftype);
        } else {
            $('#reftype_original').val('purchase/request');
            // console.log("Eventtype tidak null, reftype dikosongkan");
        }

        if (!refid) return;
        generateDeli(refid, originalReftype);
    });

    function applyStockRules(row) {
        let remain = parseInt(row.find(".product-remain").val()) || 0;
        let receiveInput = row.find(".product-receive");
        let val = parseInt(receiveInput.val()) || 0;

        if (val < 0) {
            receiveInput.val(0);
            return;
        }

        if (remain > 0 && val > remain) {
            receiveInput.val(remain);
        }
    }

</script>