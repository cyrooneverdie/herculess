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
?>

<div class="d-flex flex-column scroll-y px-5 px-10 px-lg-10" id="modal_form_coas_scroll" data-kt-scroll="false" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#modal_form_coas_header" data-kt-scroll-wrappers="#modal_form_coas_scroll" data-kt-scroll-offset="300px">
    <div class="row">
        <!-- COA Name ID -->
        <div class="mb-7 col-lg-12 col-md-12 col-sm-12">
            <label class="fw-semibold fs-6 mb-2">Kode</label>
            <?= $form->field($model, 'coa_no')->textInput(['class' => 'form-control form-control', 'placeholder' => 'COA No', 'required' => true])->label(false) ?>
        </div>

        <!-- COA No -->
        <div class="mb-7 col-lg-12 col-md-12 col-sm-12">
            <label class="fw-semibold fs-6 mb-2">Akun</label>
            <?= $form->field($model, 'coa_name_id')->textInput(['class' => 'form-control', 'placeholder' => 'Nama Akun', 'id' => 'coa_no', 'required' => true])->label(false) ?>
            <div class="invalid-feedback" id="coa_no-feedback">COA No is required</div>
        </div>

        <!-- Category (Select Option) -->
        <div class="mb-7 col-lg-12 col-md-12 col-sm-12">
            <label class="fw-semibold fs-6 mb-2" for="category">Category</label> <!-- Tambahkan lang -->
            <div class="input-group">
                <?= Html::dropDownList(
                    'coa_category', 
                    $model->coa_category,
                    [],
                    [
                        'id' => 'category',
                        'class' => 'form-select',
                        'data-control' => 'select2',
                        'data-placeholder' => 'Pilih Category'
                    ]   
                ) ?>
            </div>
            <div class="invalid-feedback" id="category-feedback">Category is required</div>
        </div>

        <!-- Akun (Select Option) -->
        <div class="mb-7 col-lg-12 col-md-122 col-sm-12">
            <label class="fw-semibold fs-6 mb-2" for="akun">Akun</label>
            <div class="input-group">
                <?= Html::dropDownList(
                    'coa_refid',
                    $model->coa_refid,
                    [],
                    [
                        'id' => 'account',
                        'class' => 'form-select',
                        'data-control' => 'select2',
                        'data-placeholder' => 'Pilih Akun'
                    ]
                ) ?>
            </div>
            <div class="invalid-feedback" id="akun-feedback">Akun is required</div>
        </div>
    </div>
</div>

<div class="text-end pt-10">
    <button type="button" class="btn btn-light me-3" data-kt-users-modal-action="cancel" data-bs-dismiss="modal">Discard</button>
    <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Submit', ['id' => 'btnsubmit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

<div class="modal" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm">
                    <input type="hidden" id="editCategoryId" value="">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="categoryName" placeholder="Enter category name">
                        <div class="invalid-feedback" id="categoryNameFeedback">
                            Please enter a valid category name.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="categoryCode" class="form-label">Category Code</label>
                        <input type="text" class="form-control" id="categoryCode" placeholder="Enter category code (optional)">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveCategoryBtn">Save Category</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var modalState = {
        isProductModalOpen: false,
        isCategoryModalOpen: false,
        originalBackdrop: null
    };

    function cleanupModals() {
        // Remove any extra backdrops
        if (document.querySelectorAll('.modal-backdrop').length > 1) {
            var backdrops = document.querySelectorAll('.modal-backdrop');
            for (var i = 1; i < backdrops.length; i++) {
                backdrops[i].remove();
            }
        }

        // Fix backdrop opacity
        if (document.querySelector('.modal-backdrop')) {
            document.querySelector('.modal-backdrop').style.opacity = '0.5';
        }

        // If no modals are visible, clean up completely
        if (document.querySelectorAll('.modal.show').length === 0) {
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            document.body.style.paddingRight = '';

            // Reset modal state
            modalState.isProductModalOpen = false;
            modalState.isCategoryModalOpen = false;
        }
    }

    var modalHandlers = {
        openCategoryModal: function(isEdit, categoryId, categoryName) {
            isEdit = isEdit || false;
            categoryId = categoryId || '';
            categoryName = categoryName || '';

            $("#categoryName").val(isEdit ? categoryName : "").removeClass("is-invalid");
            $("#categoryNameFeedback").text("");
            $("#editCategoryId").val(isEdit ? categoryId : "");
            $("#addCategoryModalLabel").text(isEdit ? "Edit Category" : "<?= Yii::$app->lang->t('kategori', 'cta_kategori') ?>");
            $("#saveCategoryBtn").text(isEdit ? "Update Category" : "Save Category");

            isProductModalOpen = $("#modal_form_produk").hasClass('show');

            $("#addCategoryModal").addClass('show').css('display', 'block');
            $('body').addClass('modal-open').css('overflow', 'hidden');

            if ($('.modal-backdrop').length === 0) {
                $('body').append('<div class="modal-backdrop fade show"></div>');
            }

            isCategoryModalOpen = true;

            setTimeout(function() {
                $('#categoryName').focus();
            }, 300);
        },

        closeCategoryModal: function() {
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

    function form() {
        // Select2 Category
        $("#category").select2({
            ajax: {
                url: "<?= \yii\helpers\Url::to(['coas/categorylist']) ?>",
                type: "GET",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term || "",
                    };
                },
                processResults: function(data) {
                    // Make sure data.results exists
                    if (!data.results) {
                        data.results = [];
                    }

                    // Add the "Add New Category" option
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
            templateResult: formatCategory,
            templateSelection: formatCategorySelection,
            dropdownParent: $("#modal_form_coas") 
        }).on('select2:select', function(e) {
            var selectedId = e.params.data.id;
            if (selectedId && selectedId !== "add_new_category") {
                $(".category-buttons").show();
            } else {
                $(".category-buttons").hide();
            }
        }).on('select2:unselect', function() {
            $(".category-buttons").hide();
        }).on('select2:clear', function() {
            $(".category-buttons").hide();
        });

        function formatCategory(category) {
            if (!category.id) {
                return category.text;
            }

            if (category.id === "add_new_category") {
                return '<div style="color: #009ef7; font-weight: bold;"><i class="fas fa-plus-circle me-2"></i>' + category.text + '</div>';
            }

            return category.text;
        }

        function formatCategorySelection(category) {
            if (!category.id) {
                return category.text;
            }

            if (category.id === "add_new_category") {
                // We don't want to actually select this option
                $("#category").val(null).trigger("change");

                // Open the category modal without manipulating backdrops
                openCategoryModal();

                return "Select Category";
            }

            // Show edit/delete buttons if category is selected
            $(".category-buttons").show();
            return category.text;
        }

        // Select2 Account
        $("#account").select2({
            ajax: {
                url: "<?= \yii\helpers\Url::to(['coas/accountlist']) ?>", // Ubah URL sesuai endpoint yang kamu buat untuk akun
                type: "GET",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term || "",
                    };
                },
                processResults: function(data) {
                    if (!data.results) {
                        data.results = [];
                    }

                    return {
                        results: data.results
                    };
                },
                cache: false
            },
            placeholder: "Pilih Akun",
            allowClear: true,
            escapeMarkup: function(markup) {
                return markup;
            },
            dropdownParent: $("#modal_form_coas")
        }).on('select2:select', function(e) {
            var selectedId = e.params.data.id;
            $(".account-buttons").show();
        }).on('select2:unselect', function() {
            $(".account-buttons").hide();
        }).on('select2:clear', function() {
            $(".account-buttons").hide();
        });

    }

    function openCategoryModal(isEdit = false) {
        // Make sure the modal is properly configured
        var categoryModal = document.getElementById('addCategoryModal');
        categoryModal.style.zIndex = '1060';

        // Set the title based on mode
        document.getElementById('addCategoryModalLabel').textContent = isEdit ? 'Edit Category' : 'Add New Category';

        // Make sure we keep track of modal state
        modalState.isCategoryModalOpen = true;

        // Open the modal using Bootstrap's API
        var modalInstance = new bootstrap.Modal(categoryModal);
        modalInstance.show();

        // Focus on category name
        document.getElementById('categoryName').focus();
    }

    $(document).ready(function() {
        form();

        $('#modal_form_coas').css('z-index', '1050');
        $('#addCategoryModal').css('z-index', '1060');

        $(document).on('shown.bs.modal', '#modal_form_coas', function() {
            modalState.isProductModalOpen = true;

            // Ensure this modal has the right z-index
            $(this).css('z-index', '1050');

            // Store reference to the original backdrop
            modalState.originalBackdrop = document.querySelector('.modal-backdrop');
            if (modalState.originalBackdrop) {
                modalState.originalBackdrop.style.zIndex = '1040';
                modalState.originalBackdrop.style.opacity = '0.5';
            }
        });

        $(document).on('hide.bs.modal', '#modal_form_coas', function() {
            modalState.isProductModalOpen = false;
        });

        $(document).on('hidden.bs.modal', '#modal_form_coas', function() {
            cleanupModals();
        });

        $(document).on('shown.bs.modal', '#addCategoryModal', function() {
            $('#categoryName').focus();
            $('#categoryName').removeClass("is-invalid");

            // Ensure this modal is in front
            $(this).css('z-index', '1060');

            // Set all backdrops to proper opacity
            $('.modal-backdrop').css('opacity', '0.5');

            modalState.isCategoryModalOpen = true;
        });

        $(document).on('hidden.bs.modal', '#addCategoryModal', function() {
            // Reset form
            $("#categoryName").val("");
            $("#categoryCode").val("");
            $("#categoryName").removeClass("is-invalid");

            // Reset modal title and save button
            $("#addCategoryModalLabel").text("Add New Category");
            $("#saveCategoryBtn").text("Save Category");
            $("#editCategoryId").val("");

            modalState.isCategoryModalOpen = false;

            cleanupModals();
        });

        $(document).on("click", ".edit-category", function() {
            var categoryId = $("#category").val();
            var categoryName = $("#category option:selected").text();

            if (!categoryId || categoryId === "add_new_category") return;

            $("#categoryName").removeClass("is-invalid");
            $("#categoryNameFeedback").text("");

            $("#categoryName").val(categoryName);

            $.ajax({
                url: "<?= \yii\helpers\Url::to(['/coas/#']) ?>", // Belum Bisa --Butuh action khusus kategori`
                type: "GET",
                data: {
                    id: categoryId,
                    _csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#categoryCode").val(response.data.kategoricode || "");

                        // Store category ID in hidden input
                        $("#editCategoryId").val(categoryId);

                        // Open the category modal
                        openCategoryModal(true);

                        // Change save button text
                        $("#saveCategoryBtn").text("Update Category");
                    } else {
                        toastr.error("Failed to get category data.");
                    }
                },
                error: function() {
                    toastr.error("An error occurred. Please try again.");
                }
            });
        });

        $(document).on("click", ".delete-category", function() {
            var categoryId = $("#category").val();
            var categoryName = $("#category option:selected").text();

            if (!categoryId || categoryId === "add_new_category") return;

            Swal.fire({
                title: "Are you sure you want to delete?",
                text: `Category "${categoryName}" will be deleted. Deleted data cannot be recovered!`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "<?= \yii\helpers\Url::to(['/coas/#']) ?>", // Belum Bisa --Butuh action khusus kategori
                        type: "POST",
                        data: {
                            id: categoryId,
                            _csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                // Remove option from select and reset
                                $("#category option[value='" + categoryId + "']").remove();
                                $("#category").val(null).trigger("change");
                                $(".category-buttons").hide();

                                // Show success notification
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Category has been successfully deleted.",
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    title: "Failed!",
                                    text: response.message || "Failed to delete category.",
                                    icon: "error",
                                    confirmButtonColor: "#d33",
                                    confirmButtonText: "OK"
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: "Error!",
                                text: "An error occurred while deleting data.",
                                icon: "error",
                                confirmButtonColor: "#d33",
                                confirmButtonText: "OK"
                            });
                        }
                    });
                }
            });
        });

        $("#categoryName, #categoryCode").on("keydown", function(e) {
            if (e.key === "Enter") {
                e.preventDefault();
                if ($(this).attr("id") === "categoryName") {
                    $("#categoryCode").focus();
                } else {
                    $("#saveCategoryBtn").click();
                }
            }
        });

        $("#saveCategoryBtn").on("click", function() {
            var categoryName = $("#categoryName").val().trim();
            var categoryCode = $("#categoryCode").val().trim();
            var editCategoryId = $("#editCategoryId").val();

            if (categoryName === "") {
                $("#categoryName").addClass("is-invalid");
                $("#categoryNameFeedback").text("Please enter a category name.");
                return;
            }

            var btn = $(this);
            var originalText = btn.html();
            btn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

            var url = editCategoryId ?
                "<?= \yii\helpers\Url::to(['/coas/editkategori']) ?>" : // Belum Bisa --Butuh action khusus kategori
                "<?= \yii\helpers\Url::to(['/coas/addkategori']) ?>"; // Belum Bisa --Butuh action khusus kategori
        
            var requestData = {
                name: categoryName,
                code: categoryCode,
                _csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
            };

            // Add ID if this is an edit operation
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
                            // Remove old option
                            $("#category option[value='" + editCategoryId + "']").remove();

                            // Add new option with updated name
                            var newOption = new Option(categoryName, editCategoryId, true, true);
                            $("#category").append(newOption);

                            // Trigger change to update select2 display
                            $("#category").trigger('change');

                            // Show notification
                            toastr.success("Category updated successfully!");
                        } else {
                            // Add new option and select it
                            var newOption = new Option(response.data.kategorinama, response.data.kategoriid, true, true);
                            $("#category").append(newOption).trigger('change');

                            toastr.success("Category added successfully!");
                        }

                        // Close the category modal properly using bootstrap
                        var categoryModal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
                        if (categoryModal) {
                            categoryModal.hide();
                        }
                    } else {
                        // Show error
                        $("#categoryName").addClass("is-invalid");
                        $("#categoryNameFeedback").text(response.message || "Failed to save category.");
                    }
                },
                error: function() {
                    toastr.error("An error occurred. Please try again.");
                },
                complete: function() {
                    // Reset button state
                    btn.prop("disabled", false).html(originalText);
                }
            });
        });

        $('#FormValid').on('submit', function(e) { // Harus diubah Data nya
            e.preventDefault();

            let isValid = true;

            // Check required fields
            if ($('#coa_name_id').val() === '') {
                $('#coa_name_id').addClass('is-invalid');
                isValid = false;
            } else {
                $('#coa_name_id').removeClass('is-invalid');
            }

            /* if ($('#productType').val() === null || $('#productType').val() === '') {
                $('#productType').next('.select2-container').addClass('is-invalid');
                isValid = false;
            } else {
                $('#productType').next('.select2-container').removeClass('is-invalid');
            }

            if ($('#price').val().trim() === '' || $('#price').val().trim() === '0') {
                $('#price').addClass('is-invalid');
                isValid = false;
            } else {
                $('#price').removeClass('is-invalid');
            } */

            if (isValid) {
                submitCoasFormAjax();
            }

            return false;
        });

        function submitCoasFormAjax() {
            var formData = new FormData($('#FormValid')[0]);
            console.log(formData);

            $('#btnsubmit').prop('disabled', true);
            $('#btnsubmit').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');

            $.ajax({
                url: $('#FormValid').attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#btnsubmit').prop('disabled', false);
                    $('#btnsubmit').html('Submit');

                    if (typeof response === 'string') {
                        try {
                            response = JSON.parse(response);
                        } catch (e) {
                            if (response.includes('success')) {
                                handleSuccessResponse();
                                return;
                            }
                        }
                    }

                    if (response.success) {
                        handleSuccessResponse();
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: response.message || "There was an error saving the product.",
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                },
                error: function(xhr, status, error) {
                    $('#btnsubmit').prop('disabled', false);
                    $('#btnsubmit').html('Submit');

                    Swal.fire({
                        title: "Error!",
                        text: "There was an error processing your request.",
                        icon: "error",
                        confirmButtonText: "OK"
                    });

                    console.error("AJAX Error:", status, error);
                    console.log("Response:", xhr.responseText);
                }
            });
        }

        function handleSuccessResponse() {
            Swal.fire({
                title: "Success!",
                text: "Product has been saved successfully.",
                icon: "success",
                timer: 2000,
                showConfirmButton: false
            });

            // Close the modal
            bootstrap.Modal.getInstance(document.getElementById('modal_form_coas')).hide();

            setTimeout(function() {
                cleanupModals();

                if ($.fn.DataTable.isDataTable('#datatable')) {
                    $('#datatable').DataTable().ajax.reload();
                }
            }, 300);
        }
    });

    var categoryManager = {
        setupEvents: function() {
            $(document).off('click', '.edit-category').on('click', '.edit-category', function() {
                var categoryId = $("#category").val();
                var categoryName = $("#category option:selected").text();

                if (categoryId && categoryId !== "add_new_category") {
                    modalHandlers.openCategoryModal(true, categoryId, categoryName);
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
                        $.ajax({
                            url: "<?= \yii\helpers\Url::to(['/kategori/delete']) ?>",
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

                                    showAlert(
                                        "Terhapus!",
                                        "Kategori berhasil dihapus.",
                                        "success",
                                        2000
                                    );
                                } else {
                                    showAlert(
                                        "Gagal!",
                                        response.message || "Gagal menghapus kategori.",
                                        "error"
                                    );
                                }
                            },
                            error: function() {
                                showAlert(
                                    "Error!",
                                    "Terjadi kesalahan saat menghapus data.",
                                    "error"
                                );
                            }
                        });
                    }
                });
            });

            $("#categoryName").off('keydown').on("keydown", function(e) {
                if (e.key === "Enter") {
                    e.preventDefault();
                    $("#saveCategoryBtn").click();
                }
            });

            $("#saveCategoryBtn").off('click').on("click", function() {
                if (isSavingCategory) return;

                var categoryName = $("#categoryName").val().trim();
                var editCategoryId = $("#editCategoryId").val();

                if (categoryName === "") {
                    $("#categoryName").addClass("is-invalid");
                    $("#categoryNameFeedback").text("Please enter a category name.");
                    return;
                }

                isSavingCategory = true;

                var btn = $(this);
                var originalText = btn.html();
                btn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

                var url = editCategoryId ?
                    "<?= \yii\helpers\Url::to(['/kategori/editkategori']) ?>" :
                    "<?= \yii\helpers\Url::to(['/kategori/addkategori']) ?>";

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
                                var newOption = new Option(response.data.kategorinama, response.data.kategoriid, true, true);
                                $("#category").append(newOption).trigger('change');

                                toastr.success("Category added successfully!");
                            }

                            modalHandlers.closeCategoryModal();
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
            });

            // Modal close buttons
            $(document).off('click', '.btn-close, [data-bs-dismiss="modal"]').on('click', '.btn-close, [data-bs-dismiss="modal"]', function() {
                var modalId = $(this).closest('.modal').attr('id');

                if (modalId === 'addCategoryModal') {
                    modalHandlers.closeCategoryModal();
                    return false;
                }
            });
        }
    };

    function setupModalEvents() {
        $('#modal_form_produk').off('shown.bs.modal').on('shown.bs.modal', function() {
            isProductModalOpen = true;
        });

        $('#modal_form_produk').off('hidden.bs.modal').on('hidden.bs.modal', function() {
            isProductModalOpen = false;

            if (!isCategoryModalOpen) {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('overflow', '');
            }
        });
    }

    $(document).ready(function() {
        productManager.setNo();
        productManager.initForm();

        variantManager.setupVariantEvents();

        variantManager.initializeExistingVariants(existingVariants);

        formHandler.setupSubmission();

        categoryManager.setupEvents();

        setupModalEvents();

        $('.field-produk-produkkode, .field-deskripsi, .field-namaproduk').removeClass('required');
    });
</script>