<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php
$form = ActiveForm::begin([
    'id' => 'form-variant',
    'method' => 'post',
    'action' => $model->isNewRecord
        ? Url::to(['variant/create'])
        : Url::to(['variant/update', 'id' => $model->variantid]),
    'options' => [
        'data-pjax' => false,
    ],
    'validateOnSubmit' => true,
    'enableAjaxValidation' => false,
    'enableClientScript' => $isajax ? false : true,
]);
?>

<div id="modal_scrollable_content" class="modal-body scroll-y">
    <div class="row">
        <div class="mb-7 col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= $form->field($model, 'productid')->dropDownList(
                (!$model->isNewRecord && $enum && !empty($enum['productname']))
                ? [$model->productid => $enum['productname']]
                : [],
                [
                    'class' => 'form-select product-select',
                    'data-control' => 'select2',
                    'required' => true,
                ]
            ) ?>
        </div>

        <div id="extra-fields" class="d-none">
            <div class="row">
                <div class="mb-7 col-lg-4 col-md-4 col-sm-12">
                    <?= $form->field($model, 'unitno')->textInput([
                        'class' => 'form-control unitno',
                        'value' => $model->unitno
                    ]) ?>
                </div>
                <div class="mb-7 col-lg-4 col-md-4 col-sm-12">
                    <?= $form->field($model, 'asetno')->textInput([
                        'class' => 'form-control asetno',
                        'value' => $model->asetno
                    ]) ?>
                </div>
                <div class="mb-7 col-lg-4 col-md-4 col-sm-12">
                    <?= $form->field($model, 'serialno')->textInput([
                        'class' => 'form-control serialno',
                        'value' => $model->serialno
                    ]) ?>
                </div>
            </div>

            <div class="row">
                <div class="mb-7 col-lg-4 col-md-4 col-sm-12">
                    <?= $form->field($model, 'series')->textInput([
                        'class' => 'form-control series',
                        'value' => $model->series
                    ]) ?>
                </div>

                <div class="mb-7 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <?= $form->field($model, 'barcode')
                        ->textInput([
                            'class' => 'form-control variantbarcode',
                            'value' => $model->barcode
                        ]) ?>
                </div>

                <div class="mb-7 col-lg-4 col-md-4 col-sm-12">
                    <label class="fw-semibold fs-7">
                        <?= Yii::$app->lang->t('varianharga', 'purchaseprice') ?>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fa-solid fa-money-bill"></i>
                        </span>
                        <?= $form->field($model, 'price', [
                            'options' => ['tag' => false]
                        ])->textInput([
                                    'class' => 'form-control money border-start-0 rounded-end',
                                    'placeholder' => $model->getAttributeLabel('price'),
                                ])->label(false) ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="mb-7 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <?= $form->field($model, 'condition')->dropDownList(
                        (!$model->isNewRecord && $enum && !empty($enum['condition']))
                        ? [$model->condition => $enum['condition']]
                        : [],
                        [
                            'class' => 'form-select condition',
                            'data-control' => 'select2',
                        ]
                    ) ?>
                </div>
                <div class="mb-7 col-lg-4 col-md-4 col-sm-12 col-xs-12">

                    <?= $form->field($model, 'locationid')->dropDownList(
                        (!$model->isNewRecord && $enum && !empty($enum['location']))
                        ? [$model->locationid => $enum['location']]
                        : [],
                        [
                            'class' => 'form-select location',
                            'data-control' => 'select2',
                        ]
                    ) ?>

                </div>
                <div class="mb-7 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <?= $form->field($model, 'shelf')->dropDownList(
                        (!$model->isNewRecord && $enum && !empty($enum['shelf']))
                        ? [$model->shelf => $enum['shelf']]
                        : [],
                        [
                            'class' => 'form-select shelf',
                            'data-control' => 'select2',
                            'disabled' => true,
                        ]
                    ) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 mb-5">
                    <?= $form->field($model, 'purchasedate', [
                        'template' => '
                            {label}
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
                                {input}
                            </div>
                            {error}
                        ',
                    ])->textInput([
                                'placeholder' => $model->getAttributeLabel('purchasedate'),
                                'class' => 'form-control pickdate',
                                'value' => $model->purchasedate ? date('d/m/Y', strtotime($model->purchasedate)) : '',
                            ]) ?>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12">
                    <?= $form->field($model, 'contact_id')->dropDownList(
                        $model->contact_id ? [$model->contact_id => Yii::$app->function->findByField("jobcompany", "contacts", " and contact_id ='" . $model->contact_id . "' ")] : [],
                        [
                            'class' => 'form-select',
                            'data-control' => 'select2',
                        ]
                    ) ?>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-12">
                    <?= $form->field($model, 'description_variant')->textarea([
                        'class' => 'form-control',
                        'placeholder' => $model->getAttributeLabel('description'),
                        'rows' => 4,
                    ]) ?>
                </div>
            </div>

            <div class="row g-5 mb-5">
                <label class="fs-7">
                    <?= Yii::$app->lang->t('produk_table', 'produk_gambar') ?>
                </label>

                <?= $form->field($model, 'fileUpload')->fileInput([
                    'accept' => 'image/*',
                    'class' => 'd-none',
                    'onchange' => 'previewMainImage(event)',
                ])->label(false) ?>
            </div>

            <div id="document_repeater">
                <div class="d-flex flex-wrap gap-3 document-rows" data-repeater-list="Document">

                    <?php foreach ($modeldocument as $i => $rowdetail): ?>
                        <?php
                        $pathImg = (!empty($rowdetail->documentpath))
                            ? Yii::getAlias('@web') . '/uploads/variant/' . $rowdetail->documentpath . '?t=' . time()
                            : Yii::getAlias('@web') . '/assets/media/logos/default.png';
                        ?>
                        <div data-repeater-item
                            class="card card-flush border border-dashed border-gray-300 position-relative"
                            style="width:150px; height:150px;">

                            <?= Html::activeHiddenInput($rowdetail, "[{$i}]documentid", ['name' => "Document[{$i}][documentid]", 'value' => $rowdetail->documentid]) ?>
                            <?= Html::activeHiddenInput($rowdetail, "[{$i}]ord", ['name' => "Document[{$i}][ord]", 'value' => $rowdetail->ord]) ?>
                            <input type="hidden" name="Document[<?= $i ?>][documentpath_existing]"
                                class="documentpath-existing"
                                value="<?= htmlspecialchars($rowdetail->documentpath ?? '') ?>">

                            <label
                                class="card-body d-flex align-items-center justify-content-start p-2 cursor-pointer h-100">
                                <img src="<?= $pathImg ?>" class="mw-100 mh-100 object-fit-contain preview-image"
                                    data-original="<?= $pathImg ?>">

                                <?= $form->field($rowdetail, "documentpath")->fileInput([
                                    'class' => 'd-none file-input-document',
                                    'accept' => 'image/*',
                                    'onchange' => 'previewImage(event)',
                                ])->label(false) ?>
                            </label>

                            <a href="javascript:;" data-repeater-delete
                                class="btn btn-icon btn-sm btn-light-danger position-absolute top-0 end-0 m-1"
                                style="width:22px; height:22px;"><i class="fas fa-times fs-8"></i>
                            </a>
                        </div>

                    <?php endforeach; ?>
                </div>

                <div class="mt-3">
                    <a href="javascript:;" data-repeater-create class="btn btn-sm btn-light-primary">
                        <i class="ki-duotone ki-plus fs-4 me-1"></i>
                        <?= Yii::$app->lang->t('add', 'add1') ?>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer flex-end">
    <button type="button" class="btn btn-light me-3 text-dark" data-kt-users-modal-action="cancel"
        data-bs-dismiss="modal"><?= Yii::$app->lang->t('back_home', 'chat34') ?></button>
    <?= Html::submitButton($model->isNewRecord ? Yii::$app->lang->t('extra', 'extra16') : Yii::$app->lang->t('extra', 'extra16'), ['id' => 'btnsubmit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

<script>
    window.previewImage = function (event) {
        const input = event.target;
        const file = input.files[0];
        const maxSize = 1 * 1024 * 1024;

        if (!file) return;

        if (file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar',
                text: 'Batas maksimal file adalah 1MB.',
            });
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            const container = input.closest('[data-repeater-item]');
            if (container) {
                const preview = container.querySelector('.preview-image');
                if (preview) {
                    preview.src = e.target.result;
                }
            }
        };
        reader.readAsDataURL(file);
    }


    $(document).ready(function () {
        initMasking();

        <?php
        if ($isajax) {
            ?>
            $('#form-variant').on('submit', function (e) {
                e.preventDefault();

                $('#btnsubmit').prop('disabled', true);
                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#btnsubmit').prop('disabled', false);

                        if (data.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Successful",
                                text: data.pesan
                            }).then(() => {
                                $('#datatable').DataTable().ajax.reload();
                                $('#modal_form_variant').modal('hide');
                            });
                        } else {
                            Swal.fire({
                                icon: "warning",
                                title: "Warning",
                                text: data.pesan
                            });
                        }
                    },
                    error: function (e) {
                        $('#btnsubmit').prop('disabled', false);
                        Swal.fire({
                            icon: "error",
                            title: "Failed",
                            text: "Something went wrong, please call your Administrator!",
                        });
                    }
                });
                return false;
            });
        <?php }
        ?>

        <?php if ($model->isNewRecord): ?>
            $('.product-select').select2({
                placeholder: 'Select Product',
                allowClear: true,
                cache: true,
                ajax: {
                    url: "<?= Url::to(['product/select']) ?>",
                    dataType: 'json',
                    type: 'POST',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term || '',
                            limit: 5,
                            page: params.page || 1,
                            for: 'select2'
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
                },
                minimumInputLength: 0,
                width: '100%',
                dropdownParent: $('#form-variant'),
            });

            $('.product-select').on('select2:select', function (e) {
                let productId = e.params.data.id;

                $('#extra-fields').removeClass('d-none').hide().slideDown();

                $.ajax({
                    url: "<?= Url::to(['variant/barcode']) ?>",
                    type: "GET",
                    data: { id: productId },
                    success: function (data) {
                        if (data.success) {
                            $('.variantbarcode').val(data.barcode);
                            $('.price').val(data.purchaseprice);
                        }
                    }
                });
            });

            $('.product-select').on('select2:clear', function () {
                $('#extra-fields').slideUp();
                $('#extra-fields').find('input, select').val('').trigger('change');
            });

        <?php else: ?>

            $('#extra-fields').removeClass('d-none').show();
        <?php endif; ?>

        function initEnumSelect2(selector, enumtype, addNewText, refSelector = null) {
            $(selector).select2({
                placeholder: 'Select ' + addNewText,
                allowClear: true,
                cache: true,
                ajax: {
                    url: "<?= Url::to(['enum/list']) ?>",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        $value = $(refSelector).val() ?? 'no send';
                        // console.info($value);
                        return {
                            enumtype: enumtype,
                            search: params.term || '',
                            ref: refSelector ? $(refSelector).val() : '',
                            // limit: 5,
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
                dropdownParent: $('#form-variant'),
            })
        };

        initEnumSelect2('.condition', 'condition', 'Condition');
        initEnumSelect2('.location', 'location', 'location');
        initEnumSelect2('.shelf', 'shelf', 'Shelf', '.location');

        <?php if (!$model->isNewRecord && $model->shelf) { ?>
            $('.shelf').prop('disabled', false);
        <?php } ?>

        $('.location').on('select2:select', function (e) {
            let locationid = e.params.data.id;

            $('.shelf').prop('disabled', false).val(null).trigger('change');
        });

        $('.location').on('select2:clear', function () {
            $('.shelf').prop('disabled', true).val(null).trigger('change');
        });

        function repeat(selection) {

            $(selection).repeater({
                initEmpty: false,

                show: function () {
                    $(this).slideDown();

                },

                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            });
        }

        repeat('#document_repeater');

        function selectContact(target, selection, type, positionid) {
            $(selection).select2({
                ajax: {
                    url: "<?= Url::to(['contact/select']) ?>",
                    type: "POST",
                    dataType: "json",
                    data: function (params) {
                        return {
                            contacttype: type,
                            positionid: positionid,
                            type: 'company',
                            search: params.term || '',
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
                        return "Choose";
                    }
                    return param.text;
                },
                templateResult: function (param) {
                    if (!param.id) {
                        return "Choose";
                    }
                    if (param.loading) {
                        return param.text;
                    }
                    var span = document.createElement('span');
                    var template = '';
                    template += param.text;
                    span.innerHTML = template;
                    return span;
                },
                placeholder: "Choose",
                allowClear: true,
                dropdownParent: target
            }).on('select2:open', function () {
                let $dropdown = $('.select2-dropdown');

                // $dropdown.find('.add-new-sup-btn').remove();

                // $dropdown.append(
                //     '<div class="add-new-sup-btn p-3 text-center border-top border-gray-300 bg-light">' +
                //     '<button type="button" class="btn btn-sm btn-primary" ' +
                //     'data-type="' + type + '" data-position="' + positionid + '">' +
                //     '<i class="fas fa-plus-circle me-2"></i> Tambah ' +
                //     '</button>' +
                // );
            });
        }

        selectContact($('#modal_form_variant'), "select[name='Variant[contact_id]']", 'supplier', '');

    });
</script>