<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use Yii;
use yii\helpers\Url;
?>
<?php

switch ($enumtype) {
    case 'category':
        $label = Yii::$app->lang->t('kategori', 'cta_kategori');
        break;
    case 'type':
        $label = Yii::$app->lang->t('type', 'new_type');
        break;
    case 'brand':
        $label = Yii::$app->lang->t('brand', 'new_brand');
        break;
    case 'subcategory':
        $label = Yii::$app->lang->t('subcategory', 'label1');
        break;
    default:
        $label = Yii::$app->lang->t('spec', 'new_spec');
        break;
}

?>
<div class="modal-header">
    <h5 class="modal-title addMasterModalLabel" id="addMasterModalLabel">
        <?= $label ?>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<?php
if ($exsist) {
    ?>
    <div class="modal-body">

        <?php $form = ActiveForm::begin([
            'id' => 'addMasterForm',
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'enableAjaxValidation' => false,
            // 'enableClientScript' => $isajax ? false : true,
            'enableClientScript' => false,
            'method' => 'post',
            'action' => Url::to(['enum/create']),
            'options' => [
                'enctype' => 'multipart/form-data',
                'multiple' => true,
                'data-pjax' => false,
                'data-enumtype' => $enumtype
            ]
        ]); ?>

        <?php if ($enumtype == 'state') { ?>
            <div class="mb-5 col-lg-12 col-md-12 col-sm-12">
                <label class="fw-semibold fs-6"><?= Yii::$app->lang->t('country', 'label1') ?></label>
                <?= $form->field($model, 'refid', [
                    'errorOptions' => ['class' => 'text-danger mt-3'],
                ])->dropDownList(
                        $model->enum ? [$model->enum['enumid'] => $model->enum['enumtext_id']] : [], // Opsi dari relasi
                        [
                            'class' => 'form-select countrySelect',
                            'data-control' => 'select2',
                            'data-placeholder' => Yii::$app->lang->t('country', 'choose1')
                        ]
                    )->label(false) ?>
            </div>
        <?php } ?>

        <!-- Form Input City -->
        <?php if ($enumtype == 'city') { ?>
            <div class="mb-5 col-lg-12 col-md-12 col-sm-12">
                <label class="fw-semibold fs-6"><?= Yii::$app->lang->t('state', 'label1') ?></label>
                <?= $form->field($model, 'refid', [
                    'errorOptions' => ['class' => 'text-danger mt-3'],
                ])->dropDownList(
                        $model->enum ? [$model->enum['enumid'] => $model->enum['enumtext_id']] : [], // Opsi dari relasi
                        [
                            'class' => 'form-select stateSelect',
                            'data-control' => 'select2',
                            'data-placeholder' => Yii::$app->lang->t('state', 'choose1')
                        ]
                    )->label(false) ?>
            </div>
        <?php } ?>

        <!-- Form Input District -->
        <?php if ($enumtype == 'district') { ?>
            <div class="mb-5 col-lg-12 col-md-12 col-sm-12">
                <label class="fw-semibold fs-6"><?= Yii::$app->lang->t('city', 'label1') ?></label>
                <?= $form->field($model, 'refid', [
                    'errorOptions' => ['class' => 'text-danger mt-3'],
                ])->dropDownList(
                        $model->enum ? [$model->enum['enumid'] => $model->enum['enumtext_id']] : [], // Opsi dari relasi
                        [
                            'class' => 'form-select citySelect',
                            'data-control' => 'select2',
                            'data-placeholder' => Yii::$app->lang->t('city', 'choose1')
                        ]
                    )->label(false) ?>
            </div>
        <?php } ?>

        <?php if (in_array($enumtype, ['unit', 'category', 'brand', 'type', 'spec', 'warehouse', 'location', 'condition', 'shelf', 'crew', 'operator', 'switcher', 'leader', 'meal', 'position', 'level', 'division', 'status', 'subcategory'])) { ?>
            <div class="mb-7 col-lg-12 col-md-12 col-sm-12">
                <label class="fw-semibold fs-6 mb-2"><?= Yii::$app->lang->t('cashbackend', 'cashbackend2') ?></label>
                <?= $form->field($model, 'enum_code_id', [
                    'errorOptions' => ['class' => 'text-danger mt-3'],
                ])->textInput(['class' => 'form-control form-control', 'placeholder' => Yii::$app->lang->t('cashbackend', 'cashbackend2'), 'required' => true])->label(false) ?>
            </div>
        <?php } ?>

        <div class="mb-7 col-lg-12 col-md-12 col-sm-12">
            <label class="fw-semibold fs-6 mb-2"><?= $label ?></label>
            <?= $form->field($model, 'enumtext_id')
                ->textInput([
                    'id' => 'enumtext',
                    'placeholder' => $label,
                    'class' => 'form-control'
                ])
                ->label(false) ?>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
                data-bs-dismiss="modal"><?= Yii::$app->lang->t('back_home', 'chat34') ?></button>
            <?= Html::submitButton(Yii::$app->lang->t('extra', 'extra16'), ['id' => 'btnsubmit', 'class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <?php
} else {
    ?>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary"
            data-bs-dismiss="modal"><?= Yii::$app->lang->t('back_home', 'chat34') ?></button>
    </div>
    <?php
}
?>


<script>
    $('#addMasterForm').on('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        let form = $(this);
        let url = form.attr('action') + '?enumtype=' + form.data('enumtype');
        $('#btnsubmit').prop('disabled', true);

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('#btnsubmit').prop('disabled', false);

                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil",
                        text: response.pesan
                    }).then(() => {
                        $('.addEnumMasterModal').modal('hide');
                        $('.addMasterModalLabel').modal('hide');
                        var enumtype = form.data('enumtype');
                        var newOption = new Option(response.name, response.id, true, true);

                        var selectorMap = {
                            'gender': '#category',
                            'married': '#contact_married',
                            'country': '#Select.countrySelect',
                            'state': '#Select.stateSelect',
                            'city': '#Select.citySelect',
                            'district': '#Select.citySelect', 
                            'idtype': '#idtype',
                            'division': '#division',
                            'status': '#status',
                            'level': '#level',
                            'person': '#person',
                            'position': '#position',
                            'education': '#contact_education',
                            'religion': '#contact_religion',
                            'category': '#Select.categorySelect',
                            'vendortype': '.type-select'
                        };

                        var selectSelector = selectorMap[enumtype];
                        if (selectSelector) {
                            var $select = $(selectSelector);
                            $select.empty().append(newOption).val(response.id).trigger('change');
                        }
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.pesan
                    });
                }
            },
            error: function (xhr, status, error) {
                $('#btnsubmit').prop('disabled', false);
                Swal.fire({
                    icon: "error",
                    title: "Gagal",
                    text: "Terjadi kesalahan, hubungi administrator!",
                });
            }
        });

        return false;
    });

    $(document).ready(function () {
        function initEnumSelect2(selector, enumtype, placeholder) {
            $(selector).select2({
                placeholder: placeholder,
                allowClear: true,
                cache: true,
                ajax: {
                    url: "<?= \yii\helpers\Url::to(['enum/list']) ?>",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            enumtype: enumtype,
                            search: params.term || '',
                            page: params.page || 1,
                            for: 'select2'
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;

                        var results = data.data.map(function (item) {
                            return {
                                id: item.enumid,
                                text: item.enumtext_id
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
                minimumInputLength: 0,
                width: '100%',
                dropdownParent: $('#addMasterModal'),
            }).on('select2:open', function () {
                let $dropdown = $('.select2-dropdown');

                $dropdown.find('.add-new-btn').remove();

                $dropdown.append(
                    '<div class="add-new-btn" style="padding: 6px; text-align:center; border-top:1px solid #ddd;">' +
                    '<button type="button" class="btn btn-sm btn-primary" ' +
                    'data-enumtype="' + enumtype + '">' +
                    '<i class="fas fa-plus-circle me-2"></i> Tambah ' + enumtype +
                    '</button>' +
                    '</div>'
                );
            });
        }

        $('#addMasterModal').on('shown.bs.modal', function () {
            initEnumSelect2('.countrySelect', 'country', "<?= Yii::$app->lang->t('country', 'choose1') ?>");
            initEnumSelect2('.stateSelect', 'state', "<?= Yii::$app->lang->t('state', 'choose1') ?>");
            initEnumSelect2('.citySelect', 'city', "<?= Yii::$app->lang->t('city', 'choose1') ?>");
        });
    });
</script>