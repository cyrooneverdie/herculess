<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\Enum;
?>

<?php
$list = 'Variants';
// $conditions = [];
if (
    $model->tran->trantype == 'sales/return'
    ||
    (
        $model->tran->trantype == 'purchase/delivery'
        &&
        $model->tran->reftype == 'sales/order'
    )
) {
    $conditions = Enum::find()
        ->select(['enumid', 'enumtext_id'])
        ->where(['enumtype' => 'condition'])
        ->andWhere(['<>', 'status', '10'])
        ->all();
}

$form = ActiveForm::begin([
    'id' => 'detail-variant-form',
    'action' => Url::to(['variant/variantdeli', 'refid' => $model->trandetailid]),
    'method' => 'post',
    'validateOnSubmit' => false,
]);
?>
<div class="containter px-5">
    <div id="content" style="max-height: 70vh; overflow-y: auto; overflow-x: hidden;">
        <div class="table-responsive">
            <table class="table table-bordered align-middle" id="variant_repeater">
                <thead class="table-light">
                    <?php if ($model->tran->trantype == 'sales/delivery') { ?>
                        <tr>
                            <th class="align-middle text-center" rowspan="2" style="width: 30%">Product</th>
                            <th class="text-center" colspan="2">Barcode</th>
                        </tr>
                        <tr>
                            <th style="width: 35%">Milik</th>
                            <th style="width: 35%">Pinjam</th>
                        </tr>
                    <?php } elseif (
                        $model->tran->trantype == 'sales/return' ||
                        (
                            $model->tran->trantype == 'purchase/delivery'
                            &&
                            $model->tran->reftype == 'sales/order'
                        )
                    ) { ?>
                        <tr>
                            <th class="align-middle text-center" rowspan="2" style="width: 30%">Product</th>
                            <th class="text-center" colspan="<?= count($conditions) ?>">Condition</th>
                            <th class="align-middle text-center" rowspan="2" style="width: 30%">Location</th>
                        </tr>
                        <tr>
                            <?php foreach ($conditions as $condition) { ?>
                                <th class="text-center"><?= $condition->enumtext_id ?></th>
                            <?php } ?>
                        </tr>
                    <?php } else { ?>
                        <tr>
                            <th style="width:40%">Product</th>
                            <th class="text-center" colspan="2">Barcode</th>
                        </tr>
                    <?php } ?>

                </thead>
                <tbody data-repeater-list="Variant" class="return-rows">
                    <?php
                    $isReturn =
                        (
                            $model->tran->trantype == 'sales/return'
                            ||
                            (
                                $model->tran->trantype == 'purchase/delivery'
                                &&
                                $model->tran->reftype == 'sales/order'
                            )
                        );
                    ?>

                    <?php if (!$isReturn): ?>
                        <tr>
                            <td class="align-top">
                                <strong class="ps-3"><?= Html::encode($model->product->productname) ?></strong>
                            </td>
                            <td class="align-top">
                                <div class="d-flex flex-column"
                                    style="max-height: 50vh; overflow-y: auto; overflow-x: hidden;">
                                    <?php
                                    $variantsToDisplay = ($model->tran->trantype == 'purchase/delivery') ?
                                        $model->variants : $model->getTranvariant()->andWhere(['status' => 1])->all();

                                    foreach ($variantsToDisplay as $index => $item):
                                        if ($model->tran->trantype == 'purchase/delivery' && $item->status == 10)
                                            continue;
                                        ?>
                                        <div data-repeater-item>
                                            <input type="hidden" name="variantid" value="<?= $item->variantid ?>">
                                            <input type="text" name="barcode" class="form-control mb-2"
                                                value="<?= $item->barcode ?>" placeholder="Barcode" readonly>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <?php if ($model->tran->trantype == 'sales/delivery'): ?>
                                <td class="align-top">
                                    <div class="d-flex flex-column"
                                        style="max-height: 50vh; overflow-y: auto; overflow-x: hidden;">
                                        <?php foreach ($model->getTranvariant()->andWhere(['status' => null])->all() as $indexDeli => $tranvariant): ?>
                                            <div data-repeater-item>
                                                <input type="hidden" name="variantid" value="<?= $tranvariant->variantid ?>">
                                                <input type="text" name="barcode" class="form-control mb-2"
                                                    value="<?= $tranvariant->barcode ?>" placeholder="Barcode">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>

                    <?php else: ?>
                        <tr>
                            <td class="align-top bg-light" colspan="<?= count($conditions) + 2 ?>">
                                <strong class="ps-3"><?= Html::encode($model->product->productname) ?></strong>
                            </td>
                        </tr>
                        <?php
                        $avilableId = $model->getVariantsId();
                        // var_dump(count($model->tranvariant));
                        // die();
                        foreach ($model->tranvariant as $index => $tranvariant): ?>
                            <tr data-repeater-item>
                                <td>
                                    <?php $vid = $tranvariant->variantid ?: ($avilableId[$index] ?? '') ?>
                                    <input type="hidden" name="tranvariantid" value="<?= $tranvariant->tranvariantid ?>">
                                    <input type="hidden" name="variantid" value="<?= $vid ?>">
                                    <input type="text" name="barcode" class="form-control"
                                        value="<?= $tranvariant->variant->barcode ?? '' ?>" placeholder="Barcode" readonly>
                                </td>

                                <?php foreach ($conditions as $cIndex => $condition): ?>
                                    <td class="align-middle text-center">
                                        <input class="form-check-input" type="radio" name="condition"
                                            value="<?= $condition->enumid ?>" <?= ((string) ($tranvariant->variant->condition ?? '') === (string) $condition->enumid) ? 'checked' : '' ?> required>
                                    </td>
                                <?php endforeach; ?>

                                <td>
                                    <select name="locationid" class="form-select location-select">
                                        <option value="<?= $tranvariant->variant->locationid ?? '' ?>" selected>
                                            <?= isset($tranvariant->variant->locationid) ? Yii::$app->function->findByField(
                                                "enumtext_id",
                                                "enum",
                                                "and enumid ='" . $tranvariant->variant->locationid . "' and enumtype='location'"
                                            ) : 'Select Location' ?>
                                        </option>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if (
        $model->tran->trantype == 'sales/return' ||
        (
            $model->tran->trantype == 'purchase/delivery'
            &&
            $model->tran->reftype == 'sales/order'
        )
    ) { ?>
        <div class="text-end pt-10">
            <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel"
                data-bs-dismiss="modal"><?= Yii::$app->lang->t('back_home', 'chat34') ?></button>
            <?= Html::submitButton($model->isNewRecord ? Yii::$app->lang->t('extra', 'extra16') : Yii::$app->lang->t('extra', 'extra16'), ['id' => 'btnsubmit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>
</div>
<?php ActiveForm::end(); ?>

<script>
    $(document).ready(function () {
        setForm();
    });
    function setForm() {

        if (<?php echo $isajax; ?>) {
            var form = $('#detail-variant-form');
            form.on('submit', function (e) {
                e.preventDefault();
                // console.log(form.serializeArray());return;
                var formData = form.serialize();
                $.ajax({
                    url: form.attr("action"),
                    type: form.attr("method"),
                    data: formData,
                    success: function (data) {
                        // console.log(data);
                        if (data['success']) {
                            Swal.fire({
                                icon: "success",
                                title: "Successful",
                                html: data['pesan']
                            });
                            $('#modal_list_track').modal('hide');
                        } else {
                            Swal.fire({
                                icon: "warning",
                                title: "Warning",
                                html: data['pesan']
                            });

                        }
                    },
                    error: function (e) {
                        Swal.fire({
                            icon: "error",
                            title: "Failed",
                            html: data['pesan'],
                        });
                    }
                });
            });
        }

        repeat('#variant_repeater');

        $('.location-select').each(function () {
            selectEnum(this, 'location', 'Location');
        });

    }

    function repeat(selection) {
        $(selection).repeater({
            initEmpty: false,
            show: function () {
                $row = $(this);
                $row.slideDown();
                selectEnum($row.find('.location-select'), 'location', 'Location', '');
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });
    }

    function selectEnum(selector, enumtype, addNewText, refid = null) {
        $(selector).select2({
            placeholder: 'Select ' + addNewText,
            allowClear: true,
            cache: true,
            ajax: {
                url: "<?= Url::to(['enum/list']) ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    let data = {
                        enumtype: enumtype,
                        search: params.term || '',
                        page: params.page || 1,
                        for: 'select2'
                    };

                    if (refid) {
                        if (typeof refid === 'function') {
                            data.refid = refid($(this));
                        } else {
                            data.refid = refid;
                        }
                    }

                    return data;
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;

                    var results = data.data.map(function (item) {
                        return {
                            id: item.enumid,
                            text: item.enumtext_id,
                            amount: item.amount,
                        };
                    });

                    return {
                        results: results,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                }
            },
            dropdownParent: $('#modal_list_track')
        });

    };


</script>