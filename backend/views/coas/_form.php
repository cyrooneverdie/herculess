<?php

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

$categoryName = $categoryName ?? '';
?>

<div class="d-flex flex-column scroll-y px-5 px-10 px-lg-10" id="modal_form_coas_scroll" data-kt-scroll="false"
    data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
    data-kt-scroll-dependencies="#modal_form_coas_header" data-kt-scroll-wrappers="#modal_form_coas_scroll"
    data-kt-scroll-offset="300px">
    <div class="mb-7 col-lg-12 col-md-12 col-sm-12">
        <label class="fw-semibold fs-6 mb-2"><?= Yii::$app->lang->t('coas', 'coas1') ?></label>
        <?= $form->field($model, 'coa_no', [
            'errorOptions' => ['class' => 'text-danger mt-3'],
        ])->textInput(['class' => 'form-control form-control', 'placeholder' => Yii::$app->lang->t('coas', 'coas10'), 'required' => true])->label(false) ?>
    </div>

    <div class="mb-7 col-lg-12 col-md-12 col-sm-12">
        <label class="fw-semibold fs-6 mb-2"><?= Yii::$app->lang->t('coas', 'coas9') ?></label>
        <?= $form->field($model, 'coa_name_id', [
            'errorOptions' => ['class' => 'text-danger mt-3'],
        ])->textInput(['class' => 'form-control', 'placeholder' => Yii::$app->lang->t('coas', 'coas7'), 'id' => 'coa_no', 'required' => true])->label(false) ?>
        <div class="invalid-feedback" id="coa_no-feedback">Account is required</div>
    </div>
    <div class="mb-7 col-lg-12 col-md-12 col-sm-12">
        <label class="fw-semibold fs-6 mb-2" for="category"><?= Yii::$app->lang->t('coas', 'coas3') ?></label>
        <div class="input-group">
            <?= Html::dropDownList(
                'coa_category',
                $model->coa_category,
                !empty($model->coa_category) ? [$model->coa_category => (!empty($categoryName) ? $categoryName : $model->coa_category)] : [],
                [
                    'id' => 'category',
                    'class' => 'form-select',
                    'data-control' => 'select2',
                    'data-placeholder' => Yii::$app->lang->t('coas', 'coas5')
                ]
            ) ?>

            <?= $form->field($model, 'coa_id', ['template' => '{input}', 'options' => ['class' => 'd-none']])
                ->hiddenInput(['id' => 'coa_id_hidden'])
                ->label(false); ?>
        </div>
        <div class="invalid-feedback" id="category-feedback">Category is required</div>
    </div>

</div>

<div class="text-end pt-10">
    <button type="button" class="btn btn-light me-3" data-kt-users-modal-action="cancel"
        data-bs-dismiss="modal">Discard</button>
    <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Submit', ['id' => 'btnsubmit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

<div class="modal" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel"
    aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel"><?= Yii::$app->lang->t('kategori', 'cta_kategori') ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm">
                    <input type="hidden" id="editCategoryId" value="">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label"><?= Yii::$app->lang->t('coas', 'coas11') ?></label>
                        <input type="text" class="form-control" id="categoryName"
                            placeholder="<?= Yii::$app->lang->t('coas', 'coas12') ?>">
                        <div class="invalid-feedback" id="categoryNameFeedback">
                            <?= Yii::$app->lang->t('coas', 'coas13') ?>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary"
                    id="saveCategoryBtn"><?= Yii::$app->lang->t('coas', 'coas14') ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    var isProductModalOpen = false;
    var isCategoryModalOpen = false;
    var isSubmitting = false;
    var isSavingCategory = false;
    var selectedCategory = null;

    $(document).ready(function() {
        setFormCoas();
    });

    function setFormCoas() {
        initForm();
        setupSubmission();
        setupCategoryEvents();
        setupModalEvents();

        $('.field-coas-coa_no, .field-coa_name_id, .field-coa_no').removeClass('required');
    }

    function initForm() {

        if ($("#category").length && !$("#category").data('select2')) {
            $("#category").select2({
                ajax: {
                    url: "<?= Url::to(['coas/categorylist']) ?>",
                    type: "GET",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term || ""
                        };
                    },
                    processResults: function(data) {
                        if (!data.results) data.results = [];

                        data.results.push({
                            id: "add_new_category",
                            text: "+ Add New Category"
                        });

                        return {
                            results: data.results
                        };
                    },
                    cache: false
                },
                placeholder: "Select Category",
                allowClear: true,
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: function(category) {
                    if (!category.id) return category.text;

                    if (category.id === "add_new_category") {
                        return '<div style="color: #009ef7; font-weight: bold;"><i class="fas fa-plus-circle me-2"></i>' + category.text + '</div>';
                    }

                    return category.text;
                },
                templateSelection: function(category) {
                    if (!category.id) return category.text;

                    if (category.id !== "add_new_category") {
                        setTimeout(function() {
                            $(".category-buttons").show();
                        }, 100);
                    } else {
                        setTimeout(function() {
                            $(".category-buttons").hide();
                        }, 100);
                    }

                    if (category.id === "add_new_category") {
                        setTimeout(function() {
                            $("#category").val(null).trigger("change");
                            openCategoryModal(false);
                        }, 1);

                        return "Select Category";
                    }

                    return category.text;
                },
                dropdownParent: $("#FormValid")
            }).on('select2:select', function(e) {
                selectedCategory = e.params.data.id;

                if (e.params.data.id && e.params.data.id !== "add_new_category") {
                    $(".category-buttons").show();
                } else {
                    $(".category-buttons").hide();
                }

            }).on('select2:unselect select2:clear', function() {
                $(".category-buttons").hide();
            });
        }
    }

    function openCategoryModal(isEdit, categoryId, categoryName) {
        isEdit = isEdit || false;
        categoryId = categoryId || '';
        categoryName = categoryName || '';

        $("#categoryName").val(isEdit ? categoryName : "").removeClass("is-invalid");
        $("#categoryNameFeedback").text("");
        $("#editCategoryId").val(isEdit ? categoryId : "");
        $("#addCategoryModalLabel").text(isEdit ? "Edit Category" : "Add New Category");
        $("#saveCategoryBtn").text(isEdit ? "Update Category" : "Save Category");

        isProductModalOpen = $("#modal_form_coas").hasClass('show');

        $("#addCategoryModal").addClass('show').css('display', 'block');
        $('body').addClass('modal-open').css('overflow', 'hidden');

        if ($('.modal-backdrop').length === 0) {
            $('body').append('<div class="modal-backdrop fade show"></div>');
        }

        isCategoryModalOpen = true;

        setTimeout(function() {
            $('#categoryName').focus();
        }, 300);
    }

    function closeCategoryModal() {
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

    function setupSubmission() {
        $('#btnsubmit').off('click').on('click', function(e) {
            e.preventDefault();

            if (!isSubmitting) {
                $('#FormValid').submit();
            }

            return false;
        });

        $('#FormValid').off('submit').on('submit', function(e) {
            e.preventDefault();

            if (isSubmitting) {
                console.log('Form is already being submitted, ignoring additional submit');
                return false;
            }

            var isValid = true;

            if ($("#coa_no").val() === "") {
                $("#coa_no").addClass("is-invalid");
                isValid = false;
            } else {
                $("#coa_no").removeClass("is-invalid");
            }

            if ($("#coa_name_id").val() === '') {
                $("#coa_name_id").addClass("is-invalid");
                isValid = false;
            } else {
                $("#coa_name_id").removeClass("is-invalid");
            }

            if (isValid) {
                isSubmitting = true;
                submitCoasForm();
            }

            return false;
        });
    }

    function submitCoasForm() {
        $('#btnsubmit').prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');

        var formData = new FormData($('#FormValid')[0]);

        $.ajax({
            url: $('#FormValid').attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                isSubmitting = false;
                $('#btnsubmit').prop('disabled', false).html('Submit');

                try {
                    var data = response;

                    if (typeof response === 'string') {
                        var jsonStartPos = response.indexOf('{');
                        if (jsonStartPos !== -1) {
                            var jsonPart = response.substring(jsonStartPos);
                            data = JSON.parse(jsonPart);
                        } else if (response.includes('success')) {
                            handleSuccessResponse();
                            return;
                        } else {
                            throw new Error("Invalid response format");
                        }
                    }

                    if (data && data.success) {
                        handleSuccessResponse();
                        $()
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error!",
                            text: data.pesan || "There was an error saving the product."
                        });
                    }
                } catch (e) {
                    console.error("Error parsing response:", e);
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "There was an error processing the server response."
                    });
                }
            },
            error: function(xhr, status, error) {
                isSubmitting = false;
                $('#btnsubmit').prop('disabled', false).html('Submit');

                console.error("AJAX Error:", status, error);

                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "There was an error processing your request."
                });
            }
        });
    }

    function handleSuccessResponse() {
        Swal.fire({
            icon: "success",
            title: "Success!",
            text: "Account saved successfully.",
            timer: 2000,
            showConfirmButton: false
        });

        $('#modal_form_coas').modal('hide');
        $('#datatable').DataTable().ajax.reload();

    }

    function setupCategoryEvents() {
        $(document).off('click', '.edit-category').on('click', '.edit-category', function() {
            var categoryId = $("#category").val();
            var categoryName = $("#category option:selected").text();

            if (categoryId && categoryId !== "add_new_category") {
                openCategoryModal(true, categoryId, categoryName);
            }
        });

        $(document).off('click', '.delete-category').on('click', '.delete-category', function() {
            var categoryId = $("#category").val();
            var categoryName = $("#category option:selected").text();

            if (!categoryId || categoryId === "add_new_category") return;

            Swal.fire({
                title: "Yakin ingin hapus?",
                text: `Kategori "${categoryName}" akan dihapus. Data yang dihapus tidak bisa dikembalikan!`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteCategory(categoryId);
                }
            });
        });

        $("#categoryName").off('keydown').on("keydown", function(e) {
            if (e.key === "Enter") {
                e.preventDefault();
                $("#saveCategoryBtn").click();
            }
        });

        $("#saveCategoryBtn").off('click').on('click', function() {
            saveCategory($(this));
        });

        $(document).off('click', '.btn-close, [data-bs-dismiss="modal"]').on('click', '.btn-close, [data-bs-dismiss="modal"]', function() {
            var modalId = $(this).closest('.modal').attr('id');

            if (modalId === 'addCategoryModal') {
                closeCategoryModal();
                return false;
            }
        });
    }

    function deleteCategory(categoryId) {
        $.ajax({
            url: "<?= Url::to(['/coas/deletecategory']) ?>",
            type: "POST",
            data: {
                id: categoryId,
                _csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
            },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    $("#category option[value='" + categoryId + "']").remove();
                    $("#category").val(null).trigger("change");
                    $(".category-buttons").hide();

                    Swal.fire({
                        icon: "success",
                        title: "Terhapus!",
                        text: "Kategori berhasil dihapus.",
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: response.message || "Gagal menghapus kategori."
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "Terjadi kesalahan saat menghapus data."
                });
            }
        });
    }

    function saveCategory(btn) {
        if (isSavingCategory) return;

        var categoryName = $("#categoryName").val().trim();
        var editCategoryId = $("#editCategoryId").val();

        if (categoryName === "") {
            $("#categoryName").addClass("is-invalid");
            $("#categoryNameFeedback").text("Please enter a category name.");
            return;
        }

        isSavingCategory = true;

        var originalText = btn.html();
        btn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

        var url = editCategoryId ?
            "<?= Url::to(['/coas/editcategory']) ?>" :
            "<?= Url::to(['/coas/addcategory']) ?>";

        var requestData = {
            name: categoryName,
            _csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
        };

        if (editCategoryId) {
            requestData.id = editCategoryId;
        }

        $.ajax({
            url: url,
            type: "POST",
            data: requestData,
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    if (editCategoryId) {
                        var select2Instance = $("#category").data('select2');
                        if (select2Instance) {
                            $("#category option[value='" + editCategoryId + "']").remove();
                            var newOption = new Option(categoryName, editCategoryId, true, true);
                            $("#category").append(newOption).trigger('change');
                        }

                        toastr.success("Category updated successfully!");
                    } else {
                        var newOption = new Option(response.data.coa_name_id, response.data.coa_id, true, true);
                        $("#category").append(newOption).trigger('change');

                        toastr.success("Category added successfully!");
                    }

                    closeCategoryModal();
                } else {
                    $("#categoryName").addClass("is-invalid");
                    $("#categoryNameFeedback").text(response.message || "Failed to save category.");
                }
            },
            error: function() {
                toastr.error("An error occurred. Please try again.");
            },
            complete: function() {
                isSavingCategory = false;
                btn.prop("disabled", false).html(originalText);
            }
        });
    }

    function setupModalEvents() {
        $('#modal_form_coas').off('shown.bs.modal').on('shown.bs.modal', function() {
            isProductModalOpen = true;
        });

        $('#modal_form_coas').off('hidden.bs.modal').on('hidden.bs.modal', function() {
            isProductModalOpen = false;

            if (!isCategoryModalOpen) {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('overflow', '');
            }
        });
    }
</script>