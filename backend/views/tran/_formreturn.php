<?php
// formreturn.php
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
        <div class="col-lg-3 col-md-6 col-sm-12">
            <label class="fw-semibold fs-6 mb-2"><?= Yii::$app->lang->t('tran', 'refid') ?></label>
            <?php
            $refidOptions = [
                'class' => 'form-select refid-select',
                'data-control' => 'select2',
                'data-module' => 'sales',
                'data-type' => ($model->trantype == 'sales/return') ? 'return' : 'order',
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
        <div class="col-lg-3 col-md-6 col-sm-12">
            <label class="fw-semibold fs-6 mb-2"><?= Yii::$app->lang->t('tran', 'tran_no') ?></label>
            <?= $form->field($model, 'tranno')->textInput(['readOnly' => true])->label(false); ?>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12">
            <label class="fw-semibold fs-6 mb-2">
                Date</label>
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

        <div class="col-lg-3 col-md-6 col-sm-12 d-none">
            <label class="fw-semibold fs-6 mb-2">
                <?= Yii::$app->lang->t('produk_table', 'produk_jenis') ?>
            </label>
            <?= $form->field($model, 'eventtype')->dropDownList(
                [
                    '0' => 'Barang Yang Kembali / Tidak Terpakai',
                    '1' => 'Penarikan Barang',
                ],
                [
                    'id' => 'eventtype-select',
                    'class' => 'form-select eventtype-select',
                    'data-control' => 'select2',
                    'value' => '0'
                ]
            )->label(false); ?>
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
                                    <tr class="text-gray-600 fw-bold fs-7 text-uppercase">
                                        <th class="min-w-300px text-start">Produk</th>
                                        <th class="min-w-150px text-center">Send</th>
                                        <th class="min-w-150px text-center">Remain</th>
                                        <th class="min-w-150px text-center">Return</th>
                                        <th class="min-w-100px text-center"></th>
                                    </tr>
                                </thead>

                                <tbody class="detail-rows" data-repeater-list="Trandetail">
                                    <?php if (!empty($modeldetails)): ?>
                                        <?php foreach ($modeldetails as $index => $modeldetail): ?>
                                            <tr data-repeater-item class="align-top event-item">
                                                <td>
                                                    <input type="hidden" name="Trandetail[<?= $index ?>][trandetailid]"
                                                        value="<?= $modeldetail->trandetailid ?? "" ?>" readonly>

                                                    <input type="hidden" name="Trandetail[<?= $index ?>][refid]"
                                                        value="<?= $modeldetail->refid ?? $modeldetail->trandetailid ?? "" ?>"
                                                        readonly>

                                                    <input type="text" name="Trandetail[<?= $index ?>][productname]"
                                                        class="form-control bg-light mb-1" value="<?= $modeldetail->productid
                                                            ? Yii::$app->function->findByField("productname", "products", " and productid ='{$modeldetail->productid}' ")
                                                            : "" ?>" readonly>

                                                    <input type="hidden" name="Trandetail[<?= $index ?>][productid]"
                                                        value="<?= $modeldetail->productid ?? "" ?>">

                                                    <div class="description-container"
                                                        style="<?= $model->eventtype == 0 ? '' : 'display: none;' ?>">
                                                        <div class="d-flex">
                                                            <textarea name="Trandetail[<?= $index ?>][description]" rows="3"
                                                                class="form-control"
                                                                placeholder="Alasan"><?= $modeldetail->description ?? "" ?></textarea>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group send-milik-group">
                                                        <span class="input-group-text" style="width: 70px">Milik</span>
                                                        <input type="number" name="Trandetail[<?= $index ?>][stockmilik]"
                                                            class="form-control bg-light text-center send-milik"
                                                            value="<?= $modeldetail->stockmilik ?? '0' ?>" readonly
                                                            data-product-index="<?= $index ?>">
                                                    </div>
                                                    <div class="input-group send-pinjam-group">
                                                        <span class="input-group-text" style="width: 70px">Pinjam</span>
                                                        <input type="number" name="Trandetail[<?= $index ?>][stockpinjam]"
                                                            class="form-control bg-light text-center send-pinjam"
                                                            value="<?= $modeldetail->stockpinjam ?? '0' ?>" readonly
                                                            data-product-index="<?= $index ?>">
                                                    </div>
                                                    <div class="input-group send-total-group">
                                                        <span class="input-group-text" style="width: 70px">Total</span>
                                                        <input type="number" name="Trandetail[<?= $index ?>][senttotal]"
                                                            class="form-control bg-light text-center send-total"
                                                            value="<?= ($modeldetail->stockmilik ?? 0) + ($modeldetail->stockpinjam ?? 0) ?>"
                                                            readonly data-product-index="<?= $index ?>">
                                                    </div>
                                                </td>
                                                <td>
                                                    <!-- <input type="hidden" name="Trandetail[<?= $index ?>][remain]"> -->
                                                    <input type="hidden" name="Trandetail[<?= $index ?>][before1]"
                                                        value="<?= $modeldetail->amount ?? 0 ?>">
                                                    <div class="input-group remain-milik-group">
                                                        <span class="input-group-text" style="width: 70px">Milik</span>
                                                        <input type="number" name="Trandetail[<?= $index ?>][remain]"
                                                            class="form-control bg-light text-center remain-milik"
                                                            value="<?= $modeldetail->remain ?? 0 ?>" readonly
                                                            data-product-index="<?= $index ?>">
                                                    </div>
                                                    <!-- <input type="hidden" name="Trandetail[<?= $index ?>][remain2]"> -->
                                                    <input type="hidden" name="Trandetail[<?= $index ?>][before2]"
                                                        value="<?= $modeldetail->amount2 ?? 0 ?>">
                                                    <div class="input-group remain-pinjam-group">
                                                        <span class="input-group-text" style="width: 70px">Pinjam</span>
                                                        <input type="number" name="Trandetail[<?= $index ?>][remain2]"
                                                            class="form-control bg-light text-center remain-pinjam"
                                                            value="<?= $modeldetail->remain2 ?? 0 ?>" readonly
                                                            data-product-index="<?= $index ?>">
                                                    </div>
                                                    <div class="input-group remain-total-group">
                                                        <span class="input-group-text" style="width: 70px">Total</span>
                                                        <input type="number" name="Trandetail[<?= $index ?>][remaintotal]"
                                                            class="form-control bg-light text-center remain-total"
                                                            value="<?= ($modeldetail->remain ?? 0) + ($modeldetail->remain2 ?? 0) ?>"
                                                            readonly data-product-index="<?= $index ?>">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group return-milik-group">
                                                        <span class="input-group-text" style="width: 70px">Milik</span>

                                                        <input type="number" name="Trandetail[<?= $index ?>][amount]"
                                                            class="form-control text-center return-milik"
                                                            value="<?= $modeldetail->amount ?? 0 ?>">
                                                    </div>
                                                    <div class="input-group return-pinjam-group">
                                                        <span class="input-group-text" style="width: 70px">Pinjam</span>

                                                        <input type="number" name="Trandetail[<?= $index ?>][amount2]"
                                                            class="form-control text-center return-pinjam"
                                                            value="<?= $modeldetail->amount2 ?? 0 ?>">

                                                    </div>
                                                    <div class="input-group return-total-group">
                                                        <span class="input-group-text" style="width: 70px">Total</span>

                                                        <input type="number" name="Trandetail[<?= $index ?>][freqvalue]"
                                                            class="form-control bg-light text-center return-total" readonly
                                                            value="<?= ($modeldetail->amount ?? 0) + ($modeldetail->amount2 ?? 0) ?>">
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

                            <a href="javascript:;" data-repeater-create
                                class="btn btn-sm btn-primary repeat-parent-btn mt-2">
                                <i class="ki-duotone ki-plus fs-2"></i>
                                <?= Yii::$app->lang->t('add', 'add1') ?>
                            </a>
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
        $('.repeat-parent-btn').hide();

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
        });

        $(document).on('change', "select.refid-select", function (e) {
            const refid = $(this).val();
            if (refid) { }
        });


        $(document).on('input', "input[name*='[amount]'], input[name*='[amount2]']", function () {
            let $this = $(this);
            let row = $this.closest('tr');

            let isMilik = $this.attr('name').includes('[amount]');
            let remainSelector = isMilik ? ".remain-milik" : ".remain-pinjam";

            let remain = parseInt(row.find(remainSelector).val()) || 0;
            let currentVal = parseInt($this.val()) || 0;
            // console.log("Selector:", remainSelector);

            if (currentVal < 0) {
                $this.val(0);
                currentVal = 0;
            }

            if (currentVal > remain) {
                $this.val(remain);
                currentVal = remain;

                Swal.fire({
                    icon: 'warning',
                    title: 'Batas Maksimal',
                    text: 'Jumlah return tidak boleh melebihi sisa (remain): ' + remain,
                    timer: 1500,
                    showConfirmButton: false
                });
            }

            let milikVal = parseFloat(row.find("input[name*='[amount]']").val()) || 0;
            let pinjamVal = parseFloat(row.find("input[name*='[amount2]']").val()) || 0;
            row.find("input[name*='[freqvalue]']").val(milikVal + pinjamVal);
        });

        $('#eventtype-select').on('change', function () {
            toggleDescription();
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

            $dropdown.append(`
            <div class="add-new-prd-btn" style="padding:6px; text-align:center; border-top:1px solid #ddd;">
                <button type="button" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus-circle me-2"></i> Add New Product
                </button>
            </div>
        `);
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
            // row.find('.product-quantity').prop('readonly', false);
            // row.find('.product-quantity').removeClass('bg-light');
            // row.find('.product-sent').prop('readonly', false);
            // row.find('.product-sent').removeClass('bg-light');
            // row.find('.product-remain').prop('readonly', false);
            // row.find('.product-remain').removeClass('bg-light');
            row.find('.product-quantity').val(0);
            row.find('.product-sent').val(0);
            row.find('.product-remain').val(0);
            row.find('.product-receive').val(0);
            row.find('.product-treceived').val(0);
            row.find('.product-send').val(0);
            cekStock(row);
        }).on('select2:unselect', function (e) {

        });

    }

    function repeatNested(selection, inner) {
        $(selection).repeater({
            repeaters: [{
                selector: inner,
                show: function () {
                    $(this).slideDown();
                    setFunction();
                    toggleDescription();
                },

                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            }],

            show: function () {
                $(this).slideDown();
                setFunction();
                toggleDescription();
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });
    }

    function cekStock(row) {
        let productid = row.find('.product-select').val() || '';
        let trandate = $(document).find("input[name='Tran[trandate]']").val();

        let isoDate = formatDateToISO(trandate);

        $.ajax({
            url: "<?= Url::to(['product/cekstock']) ?>",
            type: 'GET',
            data: {
                products: [productid],
                date: `${trandate} - ${trandate}`
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

    function formatDateToISO(dateStr) {
        let parts = dateStr.split('/');
        return `${parts[2]}-${parts[1]}-${parts[0]}`;
    }

    var isNewRecord = <?= $model->isNewRecord ? 'true' : 'false' ?>;

    function setForm() {
        if (<?= json_encode((bool) $isajax) ?>) {
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
                        text: "Ada produk yang jumlah return-nya masih kosong. Hapus produk jika tidak ada barang kembali",
                    });
                    return false;
                }

                if (totalSemuaItem <= 0) {
                    Swal.fire({
                        icon: "warning",
                        title: "Gagal Menyimpan",
                        text: "Jika ingin menyimpan, pastikan minimal ada 1 item dengan jumlah return lebih dari 0.",
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
        setFunction();
        if (isNewRecord) {
            $('.event-item').remove();
        }
        initMasking();
        toggleDescription(true);
        $('#eventtype-select').on('change', function () {
            toggleDescription(false); // false berarti user mengubahnya secara sadar
        });
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
        // alert(refid);
        $('.event-item').remove();

        $.ajax({
            url: "<?= Url::to(['tran/loadreturn']) ?>",
            type: "GET",
            dataType: 'json',
            data: {
                id: refid
            },
            success: function (response) {
                // console.log(response.data);

                response.data.forEach(function (item, index) {
                    const sentMilik = parseInt(item.sent_milik) || 0;
                    const sentPinjam = parseInt(item.sent_pinjam) || 0;
                    const remMilik = parseInt(item.remain_milik) || 0;
                    const remPinjam = parseInt(item.remain_pinjam) || 0;

                    $('#trandetail_repeater .repeat-parent-btn').first().click();
                    // $(document).find("input[name='Trandetail[" + index + "][trandetailid]']").val(item.trandetailid);
                    $(document).find("input[name='Trandetail[" + index + "][refid]']").val(item.trandetailid);
                    $(document).find("input[name='Trandetail[" + index + "][productname]']").val(item.productname);
                    $(document).find("input[name='Trandetail[" + index + "][productid]']").val(item.productid);
                    $(document).find("input[name='Trandetail[" + index + "][remain]']").val(remMilik);
                    $(document).find("input[name='Trandetail[" + index + "][remain2]']").val(remPinjam);
                    $(document).find("input[name='Trandetail[" + index + "][remaintotal]']").val(remMilik + remPinjam);
                    $(document).find("input[name='Trandetail[" + index + "][stockmilik]']").val(sentMilik);
                    $(document).find("input[name='Trandetail[" + index + "][stockpinjam]']").val(sentPinjam);
                    $(document).find("input[name='Trandetail[" + index + "][senttotal]']").val(sentMilik + sentPinjam);
                    $(document).find("input[name='Trandetail[" + index + "][amount]']").val(sentMilik);
                    $(document).find("input[name='Trandetail[" + index + "][amount2]']").val(sentPinjam);
                    $(document).find("input[name='Trandetail[" + index + "][freqvalue]']").val(sentMilik + sentPinjam);

                });
                toggleDescription(false);
            },
            error: function () {
                console.warn('Error get crew');
            }
        });
    }

    function toggleDescription(isInit = false) {
        var selectedValue = $('#eventtype-select').val();

        if (selectedValue == '0') {
            $('.description-container').fadeIn();

            if (isNewRecord || !isInit) {
                $('.return-milik').val(0);
                $('.return-pinjam').val(0);
                $('.return-total').val(0);
            }
        } else {
            $('.description-container').fadeOut();

            if (isNewRecord || !isInit) {
                $('tr.event-item').each(function () {
                    let row = $(this);
                    let remainMilik = parseInt(row.find('.remain-milik').val()) || 0;
                    let remainPinjam = parseInt(row.find('.remain-pinjam').val()) || 0;

                    row.find('.return-milik').val(remainMilik);
                    row.find('.return-pinjam').val(remainPinjam);
                    row.find('.return-total').val(remainMilik + remainPinjam);
                });
            }
        }
    }
</script>