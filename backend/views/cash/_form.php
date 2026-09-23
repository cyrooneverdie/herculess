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

<div class="d-flex flex-column scroll-y px-5 px-lg-10" id="modal_form_asset_scroll">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-12 px-1">
            <label class="fs-7"><?= Yii::$app->lang->t('tran', 'tran_no') ?></label>
            <?= $form->field($model, 'cashnumber')->textInput(['readOnly' => true])->label(false); ?>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-12 px-1">
            <label class="fs-7"><?= Yii::$app->lang->t('tran', 'refid') ?></label>
            <?= $form->field($model, 'refid', [
                'errorOptions' => ['class' => 'text-danger mt-3'],
            ])->dropDownList(
                    $model->refid ? [$model->refid => ($model->invoice ? $model->invoice->tranno : $model->refid)] : [],
                    [
                        'class' => 'form-select refid-select',
                        'data-control' => 'select2',
                        'disabled' => !empty($model->refid), // Agar readonly jika sudah ada isinya
                        'prompt' => 'Pilih Invoice...'
                    ]
                )->label(false); ?>

            <?php if (!empty($model->refid)): ?>
                <?= Html::activeHiddenInput($model, 'refid') ?>
            <?php endif; ?>
        </div>

        <div class="col-lg-2 col-md-6 col-sm-12 px-1">
            <label class="fs-7">
                <?= Yii::$app->lang->t('tran', 'invoice_date') ?>
            </label>
            <?= $form->field($model, 'cashdate')->textInput(['class' => 'form-control pickdate'])->label(false) ?>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 px-1">
            <label class="fs-7">
                <?= Yii::$app->lang->t('extra', 'extra21') ?>
            </label>
            <?= $form->field($model, 'accountid', [
                'errorOptions' => ['class' => 'text-danger mt-3'],
            ])->dropDownList(
                    (!empty($model->akun) && isset($model->akun['coa_id'])) ?
                    [$model->akun['coa_id'] => $model->akun['coa_name_id'] ?? $model->akun['text'] ?? ''] : [],
                    [
                        'class' => 'form-select akun-select2',
                        'data-control' => 'select2',
                        'placeholder' => Yii::$app->lang->t('tran', 'tran_no'),
                    ]
                )->label(false); ?>
        </div>
    </div>

    <div class="my-5">
        <div class="card shadow-sm mb-4 rounded-lg" id="trandetail_repeater">
            <div class="card-header bg-light py-3">
                <div class="d-flex w-100 align-items-center justify-content-between">
                    <div>
                        <i class="fas fa-file-invoice text-primary me-2"></i>
                        <h5 class="mb-0 fw-bold d-inline">
                            <?= Yii::$app->lang->t('cashbackend', 'cashbackend17') ?>
                        </h5>
                    </div>
                    <div class="text-end">
                        <a href="javascript:;" data-repeater-create class="btn btn-sm btn-primary repeat-parent-btn">
                            <i class="fas fa-plus fs-3"></i>
                            <?= Yii::$app->lang->t('add', 'add1') ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-gray-600 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-150px"><?= Yii::$app->lang->t('extra', 'extra18') ?></th>
                                <th class="min-w-200px">Detail</th>
                                <!-- <th class="min-w-100px"><?= Yii::$app->lang->t('produk', 'detail_qty') ?></th> -->
                                <th class="min-w-100px">Total</th>
                                <!-- <th class="min-w-150px"><?= Yii::$app->lang->t('back_layout', 'chat5') ?></th> -->
                            </tr>
                        </thead>

                        <tbody data-repeater-list="Trandetail">
                            <?php if (!empty($modeldetails)): ?>
                                <?php foreach ($modeldetails as $index => $detail): ?>
                                    <tr class="detail-item" data-repeater-item>

                                        <td class="align-top">
                                            <select name="accountid" class="form-select akun-select" data-control="select2">
                                                <?php if (!empty($defaultAkun)): ?>
                                                    <option value="<?= $defaultAkun['coa_id'] ?>" selected>
                                                        <?= $defaultAkun['text'] ?>
                                                    </option>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                        <td class="align-top">
                                            <input type="text" class="form-control" name="cashdetailtype"
                                                value="<?= isset($detail['tranno']) ? 'Pembayaran - ' . $detail['tranno'] : '' ?>">
                                        </td>
                                        <!-- <td class="align-top">
                                            <input type="number" name="amount" class="form-control product-quantity" value="1">
                                        </td> -->
                                        <td class="align-top">
                                            <input type="text" name="price" class="form-control money price"
                                                value="<?= $detail['remaining_price'] ?? 0 ?>">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr data-repeater-item>
                                    <td class="text-center align-top pt-3">
                                        <div class="badge rounded-circle bg-primary row-number text-white"
                                            style="width:25px;height:25px;line-height:20px;">1</div>
                                    </td>
                                    <td class="align-top">
                                        <select name="accountid" class="form-select akun-select" data-control="select2">
                                            <?php if (!empty($defaultAkun)): ?>
                                                <option value="<?= $defaultAkun['coa_id'] ?>" selected>
                                                    <?= $defaultAkun['text'] ?>
                                                </option>
                                            <?php endif; ?>
                                        </select>
                                    </td>
                                    <td class="align-top"><input type="text" class="form-control" name="cashdetailtype">
                                    </td>
                                    <!-- <td class="align-top"><input type="number" name="amount"
                                            class="form-control product-quantity" value="1"></td> -->

                                    <td class="align-top"><input type="text" name="price" class="form-control money price"
                                            value="0"></td>
                                    <!-- <td class="align-top"><input type="text" name="itemsubtotaltax"
                                            class="form-control money itemsubtotal" readonly value="0"></td> -->
                                  
                                </tr>
                            <?php endif; ?>
                        </tbody>

                        <tfoot class="border-top">
                            <tr>
                                <td colspan="2" class="text-end fw-bold py-3">
                                    Total
                                </td>
                                <td class="py-3">
                                    <input type="text" name="subtotal_calc"
                                        class="form-control bg-light text-end money subtotal-all min-w-150px" value="0"
                                        readonly>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-7">
                <label class="f-7" for="">
                    <?= Yii::$app->lang->t('extra', 'extra19') ?>
                </label>
                <?= $form->field($model, 'note', [
                    'errorOptions' => ['class' => 'text-danger mt-3'],
                ])->textarea([
                            'placeholder' => Yii::$app->lang->t('extra', 'extra19'),
                            'rows' => 3,
                            'class' => 'form-control',
                        ])->label(false); ?>
            </div>
        </div>
    </div>

    <div class="text-end pt-10">
        <button type="reset" class="btn btn-light me-3 btnsubmit" data-kt-users-modal-action="cancel"
            data-bs-dismiss="modal">
            <?= Yii::$app->lang->t('back_home', 'chat34') ?>
        </button>
        <?= Html::submitButton($model->isNewRecord ? Yii::$app->lang->t('extra', 'extra16') : Yii::$app->lang->t('extra', 'extra16'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $('.repeat-parent-btn').hide();
        initRepeater();
        setForm();
        setTotal();
    });

    function setForm() {
        if (<?php echo $isajax; ?>) {
            var form = $('#tran-form');

            form.on('submit', function (e) {
                e.preventDefault();
                $('.btnsubmit').prop('disabled', true);

                var formData = form.serialize();

                $.ajax({
                    url: form.attr("action"),
                    type: form.attr("method"),
                    data: formData,
                    success: function (data) {
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

                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', xhr.responseText);
                        Swal.fire({
                            icon: "error",
                            title: "Failed",
                            html: "Something went wrong: " + error,
                        });

                    }
                });
            });
        }

        defaultDetail = {
            'amount': '0',
            'price': '0',
            "freqvalue": "0",
            "itemsubtotal": "0"
        }

        setFunction();
    }


    function initRepeater() {
        $('#trandetail_repeater').repeater({
            initEmpty: false,
            show: function () {
                $(this).slideDown();

                $(this).find('.select2-container').remove();
                $(this).find('.akun-select, .akun-select2')
                    .removeClass('select2-hidden-accessible')
                    .removeAttr('data-select2-id')
                    .attr('tabindex', '0');

                $(this).find('.product-quantity').val(1);
                $(this).find('.price').val('0');
                $(this).find('.itemsubtotal').val('0');

                setFunction();
            },
            hide: function (deleteElement) {
                $(this).slideUp(deleteElement, function () {
                    setTotal();
                });
            }
        });
    }

    function setFunction() {
        $('.akun-select').not('.select2-hidden-accessible').each(function () {
            let $this = $(this);
            $this.select2({
                placeholder: 'Pilih Akun',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: '<?= Url::to(['akunlist']) ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term || '',
                            page: params.page || 1, type: '2'
                        };
                    },
                    processResults: function (data) {
                        return { results: data.data.map(i => ({ id: i.coa_id, text: i.text })) };
                    }
                }
            });
        });
        $('.akun-select2').not('.select2-hidden-accessible').each(function () {
            let $this = $(this);
            $this.select2({
                placeholder: 'Pilih Akun',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: '<?= Url::to(['akunlist']) ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term || '', page: params.page || 1,
                            type: '1'
                        };
                    },
                    processResults: function (data) {
                        return { results: data.data.map(i => ({ id: i.coa_id, text: i.text })) };
                    }
                }
            });
        });

        initMasking();

    }

    function setTotal() {
        let totalAll = 0;

        $('[data-repeater-item]:visible').each(function () {
            let $row = $(this);

            let qty = parseFloat($row.find('.product-quantity').val()) || 0;
            let price = parseFloat($row.find('.price').val().replace(/[^0-9.-]/g, '')) || 0;

            let subtotal = price;
            totalAll += subtotal;

            $row.find('.itemsubtotal').val(subtotal.toFixed(2));

        });

        $("input[name='subtotal_calc']").val(totalAll.toFixed(2));

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

    $(document).on('input change', '.price, .product-quantity', function () {
        setTotal();
    });

</script>