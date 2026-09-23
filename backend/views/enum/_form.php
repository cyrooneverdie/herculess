<?php
$title = $enumtype;
switch ($title) {
    case 'unit':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar30');
        break;
    case 'expedition':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar31');
        break;
    case 'tax':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar32');
        break;
    case 'category':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar33');
        break;
    case 'brand':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar34');
        break;
    case 'type':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar35');
        break;
    case 'spec':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar36');
        break;
    case 'warehouse':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar37');
        break;
    case 'location':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar38');
        break;
    case 'condition':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar39');
        break;
    case 'shelf':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar114');
        break;
    case 'meal':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar45');
        break;
    case 'position':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar47');
        break;
    case 'level':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar48');
        break;
    case 'division':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar49');
        break;
    case 'status':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar50');
        break;
    case 'country':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar25');
        break;
    case 'state':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar26');
        break;
    case 'city':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar27');
        break;
    case 'district':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar28');
        break;
    case 'subcategory':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar115');
        break;
    case 'dinas':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar45');
        break;
}
switch ($refid) {
    case 'position.pi':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar44');
        break;
    case 'position.cr':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar41');
        break;
    case 'position.op':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar42');
        break;
    case 'position.dr':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar118');
        break;
    case 'position.sb':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar119');
        break;
    case 'position.fe':
        $title = Yii::$app->lang->t('extrasidebar', 'extrasidebar43');
        break;
}
$this->title = $title;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<?php
$form = ActiveForm::begin([
    'id' => 'FormValid',
    'method' => 'post',
    'options' => [
        'enctype' => 'multipart/form-data',
        'multiple' => true,
        'data-pjax' => false
    ],
    'validateOnSubmit' => true,
    'enableAjaxValidation' => false,
]);
?>

<?php if (empty($isajax) || !$isajax): ?>
    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
        <?= Html::encode($this->title) ?>
    </h1>
    <div class="card mt-5">
        <div class="card-body">
        <?php endif; ?>
        <div class="d-flex flex-column scroll-y px-5 px-10 px-lg-10" id="modal_form_enum_scroll" data-kt-scroll="false"
            data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
            data-kt-scroll-dependencies="#modal_form_enum_header" data-kt-scroll-wrappers="#modal_form_enum_scroll"
            data-kt-scroll-offset="300px">
            <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message): ?>
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <?= $message ?>
                </div>
            <?php endforeach; ?>
            <?php
            if ($enumtype === 'job') { ?>
                <?= $form->field($model, 'refid')->hiddenInput()->label(false) ?>
            <?php }
            ?>
            <div class="row">
                <!-- Form Input State -->
                <?php if ($enumtype == 'state') { ?>
                    <div class="mb-5 col-lg-12 col-md-12 col-sm-12">
                        <label class="fw-semibold fs-6"><?= Yii::$app->lang->t('country', 'label1') ?></label>
                        <?= $form->field($model, 'refid', [
                            'errorOptions' => ['class' => 'text-danger mt-3'],
                        ])->dropDownList(
                                $model->enum ? [$model->enum['enumid'] => $model->enum['enumtext_id']] : [], // Opsi dari relasi
                                [
                                    'id' => 'Select',
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
                                    'id' => 'Select',
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
                                    'id' => 'Select',
                                    'class' => 'form-select citySelect',
                                    'data-control' => 'select2',
                                    'data-placeholder' => Yii::$app->lang->t('city', 'choose1')
                                ]
                            )->label(false) ?>
                    </div>
                <?php } ?>
                <!-- Form Input District -->
                <?php if ($enumtype == 'type') { ?>
                    <div class="mb-5 col-lg-12 col-md-12 col-sm-12">
                        <label class="fw-semibold fs-6"><?= Yii::$app->lang->t('subcategory', 'label1') ?></label>
                        <?= $form->field($model, 'refid', [
                            'errorOptions' => ['class' => 'text-danger mt-3'],
                        ])->dropDownList(
                                $model->enum ? [$model->enum['enumid'] => $model->enum['enumtext_id']] : [], // Opsi dari relasi
                                [
                                    'id' => 'Select',
                                    'class' => 'form-select subcategorySelect',
                                    'data-control' => 'select2',
                                    'data-placeholder' => Yii::$app->lang->t('tax', 'choose1')
                                ]
                            )->label(false) ?>
                    </div>
                <?php } ?>

                <!-- Form Input Type & Specification -->
                <?php if (in_array($enumtype, ['spec', 'subcategory'])) { ?>
                    <div class="mb-5 col-lg-12 col-md-12 col-sm-12">
                        <label class="fw-semibold fs-6"><?= Yii::$app->lang->t('category', 'label1') ?></label>
                        <?= $form->field($model, 'refid', [
                            'errorOptions' => ['class' => 'text-danger mt-3'],
                        ])->dropDownList(
                                $model->enum ? [$model->enum['enumid'] => $model->enum['enumtext_id']] : [], // Opsi dari relasi
                                [
                                    'id' => 'Select',
                                    'class' => 'form-select categorySelect',
                                    'data-control' => 'select2',
                                    'data-placeholder' => Yii::$app->lang->t('category', 'choose1')
                                ]
                            )->label(false) ?>
                    </div>
                <?php } ?>

                <!-- Form Input Code Name Category, Brand, Type, Spec -->
                <?php if (in_array($enumtype, ['unit', 'category', 'brand', 'type', 'spec', 'warehouse', 'location', 'condition', 'shelf', 'job', 'dinas', 'position', 'level', 'division', 'status', 'subcategory'])) { ?>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <label class="fw-semibold fs-6"><?= Yii::$app->lang->t('cashbackend', 'cashbackend2') ?></label>
                        <?= $form->field($model, 'enum_code_id', [
                            'errorOptions' => ['class' => 'text-danger mt-3'],
                        ])->textInput(['class' => 'form-control', 'placeholder' => Yii::$app->lang->t('cashbackend', 'cashbackend2'), 'required' => true])->label(false) ?>
                    </div>
                <?php } ?>

                <div class="col-lg-12 col-md-12 col-sm-12">
                    <label class="fw-semibold fs-6"><?= Yii::$app->lang->t($enumtype, 'label1') ?></label>
                    <?= $form->field($model, 'enumtext_id', [
                        'errorOptions' => ['class' => 'text-danger mt-3'],
                    ])->textInput(['class' => 'form-control', 'placeholder' => Yii::$app->lang->t($enumtype, 'label1'), 'required' => true])->label(false) ?>
                </div>

                <?php if (in_array($enumtype, ['job', 'dinas'])) { ?>
                    <div class="mb-9 col-lg-12 col-md-12 col-sm-12">
                        <label class="fw-semibold fs-6"><?= Yii::$app->lang->t('operator', 'label2') ?></label>
                        <?= $form->field($model, 'amount', [])->textInput(['class' => 'form-control money', 'placeholder' => Yii::$app->lang->t('operator', 'label2'), 'required' => true,])->label(false) ?>
                    </div>
                <?php } ?>

                <?php if ($enumtype == 'crew') { ?>
                    <div class="mb-9 col-lg-12 col-md-12 col-sm-12">
                        <label class="fw-semibold fs-6"><?= Yii::$app->lang->t($enumtype, 'label2') ?></label>
                        <?= $form->field($model, 'amount', [])->textInput(['class' => 'form-control money', 'placeholder' => Yii::$app->lang->t($enumtype, 'label3'), 'required' => true,])->label(false) ?>
                    </div>
                <?php } ?>
                <?php if ($enumtype == 'crew') { ?>
                    <div class="mb-9 col-lg-12 col-md-12 col-sm-12">
                        <label class="fw-semibold fs-6"><?= Yii::$app->lang->t($enumtype, 'label3') ?></label>
                        <?= $form->field($model, 'amount2', [])->textInput(['class' => 'form-control money', 'placeholder' => Yii::$app->lang->t($enumtype, 'label3'), 'required' => true,])->label(false) ?>
                    </div>
                <?php } ?>
            </div>
            <?php if ($enumtype == 'tax') { ?>
                <div class="mb-9 col-lg-12 col-md-12 col-sm-12">
                    <label class="fw-semibold fs-6">
                        <?= Yii::$app->lang->t($enumtype, 'label2') ?>
                    </label>
                    <?= $form->field($model, 'amount', [])->textInput(['class' => 'form-control', 'placeholder' => Yii::$app->lang->t($enumtype, 'label2'), 'required' => true,])->label(false) ?>
                </div>
            <?php } ?>
        </div>

        <div class="text-end pt-10">
            <?php if ($isajax): ?>
                <button type="button" class="btn btn-light me-3" data-kt-users-modal-action="cancel"
                    data-bs-dismiss="modal">Discard</button>
            <?php endif; ?>
            <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Submit', ['id' => 'btnsubmit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        <?php if (empty($isajax) || !$isajax): ?>
        </div>
    </div>
<?php endif; ?>

<?php ActiveForm::end(); ?>

<!-- modal master -->
<div class="modal fade" id="addMasterModal" tabindex="-1" role="dialog" aria-labelledby="addMasterModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

        </div>
    </div>
</div>

<script>
    var isEnumModalOpen = false;
    var isCategoryModalOpen = false;
    var isSubmitting = false;
    var isSavingCategory = false;

    function showAlert(title, text, icon, timer) {
        Swal.fire({
            title: title,
            text: text || "",
            icon: icon || "info",
            timer: timer || undefined,
            showConfirmButton: timer ? false : true,
            confirmButtonText: "OK"
        });
    }

    var modalHandlers = {
        openCategoryModal: function (isEdit, categoryId, categoryName) {
            isEdit = isEdit || false;
            categoryId = categoryId || '';
            categoryName = categoryName || '';

            $("#categoryName").val(isEdit ? categoryName : "").removeClass("is-invalid");
            $("#categoryNameFeedback").text("");
            $("#editCategoryId").val(isEdit ? categoryId : "");
            $("#addCategoryModalLabel").text(isEdit ? "Edit Category" : "Add New Category"); // Tambahkan Lang
            $("#saveCategoryBtn").text(isEdit ? "Update Category" : "Save Category");

            isProductModalOpen = $("#modal_form_enum").hasClass('show');

            $("#addCategoryModal").addClass('show').css('display', 'block');
            $('body').addClass('modal-open').css('overflow', 'hidden');

            if ($('.modal-backdrop').length === 0) {
                $('body').append('<div class="modal-backdrop fade show"></div>');
            }

            isCategoryModalOpen = true;

            setTimeout(function () {
                $('#categoryName').focus();
            }, 300);
        },

        closeCategoryModal: function () {
            $("#addCategoryModal").removeClass('show').css('display', 'none');
            isCategoryModalOpen = false;

            if (isProductModalOpen) {
                if ($('.modal-backdrop').length === 0) {
                    $('body').append('<div class="modal-backdrop fade show"></div>');
                }
                $('body').addClass('modal-open').css('overflow', 'hidden');
            } else {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('overflow', '');
            }
        }
    };

    $(document).ready(function () {
        enumManager.initForm();
        <?php if ($isajax) { ?>
            formHandler.setupSubmission();
        <?php } ?>
        setupModalEvents();
        initMasking();

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
                dropdownParent: '#FormValid',
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

        // panggil untuk masing-masing select
        initEnumSelect2('.countrySelect', 'country', "<?= Yii::$app->lang->t('country', 'choose1') ?>");
        initEnumSelect2('.stateSelect', 'state', "<?= Yii::$app->lang->t('state', 'choose1') ?>");
        initEnumSelect2('.citySelect', 'city', "<?= Yii::$app->lang->t('city', 'choose1') ?>");
        initEnumSelect2('.categorySelect', 'category', "<?= Yii::$app->lang->t('category', 'choose1') ?>");
        initEnumSelect2('.subcategorySelect', 'subcategory', "<?= Yii::$app->lang->t('tax', 'choose1') ?>");

        $(document).on('select2:clear', 'select', function (e) {
            $(this).append('<option value=""></option>').val('').trigger('change');
        });

        $(".refid2Select").select2({
            ajax: {
                url: "<?= \yii\helpers\Url::to(['coas/list']) ?>",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        coa_type: "Account",
                        search: params.term || '',
                        for: "select2",
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;

                    return {
                        results: data.data.map(function (item) {
                            return {
                                id: item.enumid,
                                text: item.enumtext_id
                            };
                        }),
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            },
            placeholder: "<?= Yii::$app->lang->t('tax', 'choose1') ?>",
            minimumInputLength: 0,
            allowClear: true,
            width: '100%',
            dropdownParent: $('#modal_form_enum').length ? $('#modal_form_enum') : $(document.body)
        });
        $(".refid3Select").select2({
            ajax: {
                url: "<?= \yii\helpers\Url::to(['coas/list']) ?>",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        coa_type: "Account",
                        search: params.term || '',
                        for: "select2",
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;

                    return {
                        results: data.data.map(function (item) {
                            return {
                                id: item.enumid,
                                text: item.enumtext_id
                            };
                        }),
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            },
            placeholder: "<?= Yii::$app->lang->t('tax', 'choose1') ?>",
            minimumInputLength: 0,
            allowClear: true,
            width: '100%',
            dropdownParent: $('#modal_form_enum').length ? $('#modal_form_enum') : $(document.body)
        });

        $(document).on('click', '.add-new-btn button', function () {
            var enumtype = $(this).data('enumtype');

            $.get('/enum/load', {
                enumtype: enumtype
            }, function (html) {
                $('#addMasterModal .modal-content').html(html);
                $('#addMasterModal').modal('show');
            });
        });
    });

    var enumManager = {
        initForm: function () {

        }
    };

    <?php if ($isajax) { ?>
        var formHandler = {
            setupSubmission: function () {
                $('#btnsubmit').off('click').on('click', function (e) {
                    e.preventDefault();
                    if (!isSubmitting) $('#FormValid').submit();
                });

                $('#FormValid').off('submit').on('submit', function (e) {
                    e.preventDefault();
                    if (isSubmitting) return false;

                    if ($("#enumtext_id").val() === '') {
                        $("#enumtext_id").addClass("is-invalid");
                        showAlert("Error!", "Field cannot be empty.", "error");
                        return false;
                    } else {
                        $("#enumtext_id").removeClass("is-invalid");
                    }

                    isSubmitting = true;
                    formHandler.submitEnumForm();
                });
            },

            submitEnumForm: function () {
                $('#btnsubmit').prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm" role="status"></span> Processing...');

                $.ajax({
                    url: $('#FormValid').attr('action'),
                    type: 'POST',
                    data: new FormData($('#FormValid')[0]),
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        isSubmitting = false;
                        $('#btnsubmit').prop('disabled', false).html('Submit');

                        try {
                            var data = (typeof response === 'string') ? JSON.parse(response.substring(response.indexOf('{'))) : response;

                            if (data && data.success) {
                                formHandler.handleSuccessResponse();
                                $('#datatable').DataTable().ajax.reload();
                            } else {
                                showAlert("Error!", data.pesan || "There was an error saving the product.", "error");
                            }
                        } catch (e) {
                            console.error("Error parsing response:", e);
                            showAlert("Error!", "There was an error processing the server response.", "error");
                        }
                    },
                    error: function (xhr, status, error) {
                        isSubmitting = false;
                        $('#btnsubmit').prop('disabled', false).html('Submit');
                        console.error("AJAX Error:", status, error);
                        showAlert("Error!", "There was an error when saving data.", "error");
                    }
                });
            },

            handleSuccessResponse: function () {
                showAlert("Success!", "Product has been saved successfully.", "success", 2000);
                $('#modal_form_enum').modal('hide');
            }
        };

        formHandler.setupSubmission();
    <?php } ?>

    function setupModalEvents() {
        $('#modal_form_enum').off('shown.bs.modal').on('shown.bs.modal', function () {
            isProductModalOpen = true;
        });

        $('#modal_form_enum').off('hidden.bs.modal').on('hidden.bs.modal', function () {
            isProductModalOpen = false;

            if (!isCategoryModalOpen) {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('overflow', '');
            }
        });
    }
</script>