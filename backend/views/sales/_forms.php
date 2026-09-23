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
        'data-pjax' => false // Changed to false to prevent Pjax reload
    ],
    'validateOnSubmit' => true,
    'enableAjaxValidation' => false, // Set to false to handle validation in JavaScript
]);
?>
<!--begin::Form-->

<!--begin::Scroll-->
<div class="d-flex flex-column scroll-y px-5 px-lg-10" id="modal_form_product_scroll" data-kt-scroll="false" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#modal_form_product_header" data-kt-scroll-wrappers="#modal_form_product_scroll" data-kt-scroll-offset="300px">
    <!--begin::Input group-->
    <div class="row">
        <div class="mb-7 col-lg-2 col-md-2 col-sm-2">
            <label class="fw-semibold fs-6 mb-2">Code</label>
            <?= $form->field($model, 'produkkode')->textInput(['class' => 'form-control form-control-solid', 'readonly' => true])->label(false) ?>
        </div>

        <div class="mb-7 col-lg-4 col-md-4 col-sm-4">
            <label class="fw-semibold fs-6 mb-2">Product Name</label>
            <?= $form->field($model, 'deskripsi')->textInput(['class' => 'form-control', 'placeholder' => 'Product Name', 'id' => 'deskripsi', 'required' => true])->label(false) ?>
            <div class="invalid-feedback" id="deskripsi-feedback">Product name is required</div>
        </div>

        <div class="mb-7 col-lg-6 col-md-6 col-sm-6">
            <label class="fw-semibold fs-6 mb-2" for="productType">Product Type</label>
            <?= $form->field($model, 'jenis')->dropDownList(
                $model->jenis ? [$model->jenis => $model->jenisProduk['enumtext_id'] ?? 'Product Type'] : [],
                [
                    'placeholder' => 'Product Type',
                    'class' => 'form-select',
                    'id' => 'productType',
                    'data-control' => 'select2',
                    'required' => true
                ]
            )->label(false) ?>
            <div class="invalid-feedback" id="productType-feedback">Product type is required</div>
        </div>
    </div>

    <div class="row">
        <div class="mb-7 col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label class="fw-semibold fs-6 mb-2">Price</label>
            <?= $form->field($model, 'price')->textInput([
                'class' => 'form-control price',
                'placeholder' => 'Price',
                'id' => 'price',
                'value' => number_format($model->price ?? 0, 0, ',', '.'),
                'required' => true
            ])->label(false) ?>
            <div class="invalid-feedback" id="price-feedback">Price is required</div>
        </div>

        <div class="mb-7 col-lg-6 col-md-6 col-sm-6 col-xs-12" id="category-container">
            <label class="fw-semibold fs-6 mb-2">Category</label>
            <div class="input-group">
                <?= $form->field($model, 'kategoriid')->dropDownList(
                    $model->kategori ? [$model->kategori['kategoriid'] => $model->kategori['kategorinama']] : [],
                    [
                        'placeholder' => 'Select Category',
                        'class' => 'form-select w-200px',
                        'id' => 'category',
                        'data-control' => 'select2'
                    ]
                )->label(false) ?>
                <div class="category-buttons ms-2 mt-1" style="display: none;">
                    <button type="button" class="btn btn-sm btn-icon btn-primary edit-category me-1" title="Edit Category">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-icon btn-danger delete-category" title="Delete Category">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Scroll-->

<!--begin::Actions-->
<div class="text-end pt-10">
    <button type="button" class="btn btn-light me-3" data-kt-users-modal-action="cancel" data-bs-dismiss="modal">Discard</button>
    <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Submit', ['id' => 'btnsubmit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
<!--end::Actions-->

<?php ActiveForm::end(); ?>

<!-- Category Modal - Using data-bs-backdrop="false" to prevent creating a new backdrop -->
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
    // Global variable to track modal state
    var modalState = {
        isProductModalOpen: false,
        isCategoryModalOpen: false,
        originalBackdrop: null
    };

    function setNo() {
        var ob1 = document.querySelector("input[name='Produk[produkkode]']");

        if (ob1 && ob1.value.trim() === "") {
            fetch("<?= Url::to(['produk/getno'], true); ?>", {
                    method: "POST",
                    headers: {
                        "X-CSRF-Token": "<?= Yii::$app->request->getCsrfToken() ?>"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    ob1.value = data.no; // Set product code from backend
                })
                .catch(error => console.error("Error fetching number:", error));
        }
    }

    // Utility function to properly clean up modals to prevent stacking issues
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

    function form() {
        // Product Type Select2
        $("#productType").select2({
            ajax: {
                url: "<?= \yii\helpers\Url::to(['produk/jenislist']) ?>",
                type: "GET",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            },
            placeholder: "Product Type",
            allowClear: true,
            dropdownParent: $("#modal_form_produk") // Fix dropdown rendering inside modal
        });

        // Price input formatter
        $('#price').on('input', function() {
            // Remove non-numeric characters except decimal point
            let value = $(this).val().replace(/[^\d]/g, '');

            // Format with thousand separators
            if (value !== '') {
                // Format number with dots as thousand separators
                $(this).val(numberWithDots(value));
            }
        });

        // Function to format numbers with dots as thousand separators
        function numberWithDots(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Initialize Category Select2 with custom option
        $("#category").select2({
            ajax: {
                url: "<?= \yii\helpers\Url::to(['produk/kategorilist']) ?>",
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
            dropdownParent: $("#modal_form_produk") // Fix dropdown rendering inside modal
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

        // Format category option in dropdown
        function formatCategory(category) {
            if (!category.id) {
                return category.text;
            }

            if (category.id === "add_new_category") {
                return '<div style="color: #009ef7; font-weight: bold;"><i class="fas fa-plus-circle me-2"></i>' + category.text + '</div>';
            }

            return category.text;
        }

        // Format selected category
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
    }

    // Function to safely open the category modal
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
        // Initialize elements
        setNo();
        form();

        // Proper modal setup
        $('#modal_form_produk').css('z-index', '1050');
        $('#addCategoryModal').css('z-index', '1060');

        // ==================== PRODUCT MODAL EVENTS ====================

        // Product modal shown event
        $(document).on('shown.bs.modal', '#modal_form_produk', function() {
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

        // Product modal hide event
        $(document).on('hide.bs.modal', '#modal_form_produk', function() {
            modalState.isProductModalOpen = false;
        });

        // Product modal hidden event
        $(document).on('hidden.bs.modal', '#modal_form_produk', function() {
            cleanupModals();
        });

        // ==================== CATEGORY MODAL EVENTS ====================

        // Category modal shown event
        $(document).on('shown.bs.modal', '#addCategoryModal', function() {
            $('#categoryName').focus();
            $('#categoryName').removeClass("is-invalid");

            // Ensure this modal is in front
            $(this).css('z-index', '1060');

            // Set all backdrops to proper opacity
            $('.modal-backdrop').css('opacity', '0.5');

            modalState.isCategoryModalOpen = true;
        });

        // Category modal hidden event
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

        // Handler for Edit Category Button
        $(document).on("click", ".edit-category", function() {
            var categoryId = $("#category").val();
            var categoryName = $("#category option:selected").text();

            if (!categoryId || categoryId === "add_new_category") return;

            // Reset modal state before opening
            $("#categoryName").removeClass("is-invalid");
            $("#categoryNameFeedback").text("");

            // Fill form with existing category data
            $("#categoryName").val(categoryName);

            // Get category data
            $.ajax({
                url: "<?= \yii\helpers\Url::to(['/kategori/getkategori']) ?>",
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

        // Handler for Delete Category Button
        $(document).on("click", ".delete-category", function() {
            var categoryId = $("#category").val();
            var categoryName = $("#category option:selected").text();

            if (!categoryId || categoryId === "add_new_category") return;

            // Confirm with SweetAlert
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
                        url: "<?= \yii\helpers\Url::to(['/kategori/delete']) ?>",
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

        // Handle tab key navigation in the category modal
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

        // Save category button handler
        $("#saveCategoryBtn").on("click", function() {
            var categoryName = $("#categoryName").val().trim();
            var categoryCode = $("#categoryCode").val().trim();
            var editCategoryId = $("#editCategoryId").val();

            // Basic validation
            if (categoryName === "") {
                $("#categoryName").addClass("is-invalid");
                $("#categoryNameFeedback").text("Please enter a category name.");
                return;
            }

            // Show loading indicator
            var btn = $(this);
            var originalText = btn.html();
            btn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

            // Different URL for edit and add operations
            var url = editCategoryId ?
                "<?= \yii\helpers\Url::to(['/kategori/editkategori']) ?>" :
                "<?= \yii\helpers\Url::to(['/kategori/addkategori']) ?>";

            // Data for request
            var requestData = {
                name: categoryName,
                code: categoryCode,
                _csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
            };

            // Add ID if this is an edit operation
            if (editCategoryId) {
                requestData.id = editCategoryId;
            }

            // Send AJAX request
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

        // Simple validation before form submission
        $('#FormValid').on('submit', function(e) {
            e.preventDefault();

            let isValid = true;

            // Check required fields
            if ($('#deskripsi').val().trim() === '') {
                $('#deskripsi').addClass('is-invalid');
                isValid = false;
            } else {
                $('#deskripsi').removeClass('is-invalid');
            }

            if ($('#productType').val() === null || $('#productType').val() === '') {
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
            }

            if (isValid) {
                submitProductFormAjax();
            }

            return false;
        });

        // Handle form submission
        function submitProductFormAjax() {
            // Prepare form data
            var formData = new FormData($('#FormValid')[0]);

            // Clean price value (remove dots)
            var priceInput = $('#price');
            if (priceInput.length) {
                var cleanPrice = priceInput.val().replace(/\./g, '');
                formData.set('Produk[price]', cleanPrice);
            }

            // Show loading state
            $('#btnsubmit').prop('disabled', true);
            $('#btnsubmit').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');

            // Send AJAX request
            $.ajax({
                url: $('#FormValid').attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Reset button state
                    $('#btnsubmit').prop('disabled', false);
                    $('#btnsubmit').html('Submit');

                    // Check if the response is JSON
                    if (typeof response === 'string') {
                        try {
                            response = JSON.parse(response);
                        } catch (e) {
                            // Not JSON, might be a redirect or HTML
                            // If successful submission returns HTML, we handle it here
                            if (response.includes('success')) {
                                handleSuccessResponse();
                                return;
                            }
                        }
                    }

                    // Handle JSON response
                    if (response.success) {
                        handleSuccessResponse();
                    } else {
                        // Show error message
                        Swal.fire({
                            title: "Error!",
                            text: response.message || "There was an error saving the product.",
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Reset button state
                    $('#btnsubmit').prop('disabled', false);
                    $('#btnsubmit').html('Submit');

                    // Show error message
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
            // Show success message
            Swal.fire({
                title: "Success!",
                text: "Product has been saved successfully.",
                icon: "success",
                timer: 2000,
                showConfirmButton: false
            });

            // Close the modal
            bootstrap.Modal.getInstance(document.getElementById('modal_form_produk')).hide();

            // Clean up after modal is hidden
            setTimeout(function() {
                // Properly clean up the DOM
                cleanupModals();

                // Reload the DataTable to reflect changes
                if ($.fn.DataTable.isDataTable('#datatable')) {
                    $('#datatable').DataTable().ajax.reload();
                }
            }, 300);
        }
    });
</script>
