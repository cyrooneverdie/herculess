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
            <?= $form->field($model, 'productcode')->textInput(['class' => 'form-control form-control-solid', 'readonly' => true])->label(false) ?>
        </div>

        <div class="mb-7 col-lg-4 col-md-4 col-sm-4">
            <label class="fw-semibold fs-6 mb-2">Product Name</label>
            <?= $form->field($model, 'description')->textInput(['class' => 'form-control', 'placeholder' => 'Product Name', 'id' => 'description', 'required' => true])->label(false) ?>
            <div class="invalid-feedback" id="description-feedback">Product name is required</div>
        </div>

        <div class="mb-7 col-lg-6 col-md-6 col-sm-6">
            <label class="fw-semibold fs-6 mb-2" for="productType">Product Type</label>
            <?= $form->field($model, 'type')->dropDownList(
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
                <?= $form->field($model, 'categoryid')->dropDownList(
                    $model->category ? [$model->category['categoryid'] => $model->category['categoryname']] : [],
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

<!-- Category Modal - Positioned outside the product modal for proper stacking -->
<div class="modal" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true" data-bs-backdrop="static">
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
                    <!-- <div class="mb-3">
                        <label for="categoryCode" class="form-label">Category Code</label>
                        <input type="text" class="form-control" id="categoryCode" placeholder="Enter category code (optional)">
                    </div> -->
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
    function setNo() {
        var ob1 = document.querySelector("input[name='Product[productcode]']");

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
                setTimeout(function() {
                    $("#category").val(null).trigger("change");

                    // Open category modal
                    var categoryModal = new bootstrap.Modal(document.getElementById('addCategoryModal'));
                    categoryModal.show();
                }, 1);

                return "Select Category";
            }

            // Tampilkan tombol edit/delete jika kategori dipilih
            setTimeout(function() {
                $(".category-buttons").show();
            }, 100);

            return category.text;
        }
    }

    $(document).ready(function() {
        // Initialize elements
        setNo();
        form();

        // Focus on category name when modal opens
        $('#addCategoryModal').on('shown.bs.modal', function() {
            $('#categoryName').focus();
            $('#categoryName').removeClass("is-invalid");
        });

        // Handle modal close to ensure proper cleanup
        $('#addCategoryModal').on('hidden.bs.modal', function() {
            // Reset form
            $("#categoryName").val("");
            $("#categoryName").removeClass("is-invalid");

            // Reset modal title and save button
            $("#addCategoryModalLabel").text("Add New Category");
            $("#saveCategoryBtn").text("Save Category");
            $("#editCategoryId").val("");

            // Make sure only the product modal backdrop remains
            $('.modal-backdrop').not(':first').remove();

            // Ensure body still has modal-open class for the product modal
            if ($('#modal_form_produk').hasClass('show')) {
                $('body').addClass('modal-open');
            }
        });

        // Handler untuk Edit Category Button
        $(document).on("click", ".edit-category", function() {
            var categoryId = $("#category").val();
            var categoryName = $("#category option:selected").text();

            if (!categoryId || categoryId === "add_new_category") return;

            // Reset modal state sebelum dibuka
            $("#addCategoryModal").find('.modal-dialog').removeClass('modal-dialog-scrollable');

            // Pastikan tidak ada modal backdrop tambahan
            $('.modal-backdrop').not(':first').remove();

            // Ubah judul modal
            $("#addCategoryModalLabel").text("Edit Category");

            // Isi form dengan data kategori yang ada
            $("#categoryName").val(categoryName);

            // Kosongkan feedback error sebelumnya
            $("#categoryName").removeClass("is-invalid");
            $("#categoryNameFeedback").text("");

            // Ambil data kategori
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
                        // $("#categoryCode").val(response.data.kategoricode || "");

                        // Simpan ID kategori dalam input hidden
                        $("#editCategoryId").val(categoryId);

                        // Tampilkan modal
                        var categoryModal = new bootstrap.Modal(document.getElementById('addCategoryModal'));
                        categoryModal.show();

                        // Ubah teks tombol save
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

        // Handler untuk Delete Category Button
        $(document).on("click", ".delete-category", function() {
            var categoryId = $("#category").val();
            var categoryName = $("#category option:selected").text();

            if (!categoryId || categoryId === "add_new_category") return;

            // Konfirmasi dengan SweetAlert
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
                                // Hapus opsi dari select dan reset
                                $("#category option[value='" + categoryId + "']").remove();
                                $("#category").val(null).trigger("change");
                                $(".category-buttons").hide();

                                // Tampilkan notifikasi sukses
                                Swal.fire({
                                    title: "Terhapus!",
                                    text: "Kategori berhasil dihapus.",
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    title: "Gagal!",
                                    text: response.message || "Gagal menghapus kategori.",
                                    icon: "error",
                                    confirmButtonColor: "#d33",
                                    confirmButtonText: "OK"
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: "Error!",
                                text: "Terjadi kesalahan saat menghapus data.",
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
        $("#categoryName").on("keydown", function(e) {
            if (e.key === "Enter") {
                e.preventDefault();
                $("#saveCategoryBtn").click();
            }
        });

        // Save category button handler
        $("#saveCategoryBtn").on("click", function() {
            var categoryName = $("#categoryName").val().trim();
            // var categoryCode = $("#categoryCode").val().trim();
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

            // URL berbeda untuk edit dan add
            var url = editCategoryId ?
                "<?= \yii\helpers\Url::to(['/kategori/editkategori']) ?>" :
                "<?= \yii\helpers\Url::to(['/kategori/addkategori']) ?>";

            // Data untuk request
            var requestData = {
                name: categoryName,
                // code: categoryCode,
                _csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
            };

            // Tambahkan ID jika ini adalah edit
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
                            // Metode 1: Perbarui opsi dalam DOM secara langsung
                            $("#category option[value='" + editCategoryId + "']").text(categoryName);

                            // Metode 2: Lebih aman - Hapus dan tambahkan ulang opsi
                            var select2Instance = $("#category").data('select2');
                            if (select2Instance) {
                                // Hapus opsi lama
                                $("#category option[value='" + editCategoryId + "']").remove();

                                // Tambahkan opsi baru dengan nama yang sudah diupdate
                                var newOption = new Option(categoryName, editCategoryId, true, true);
                                $("#category").append(newOption);

                                // Trigger change untuk memperbarui tampilan select2
                                $("#category").trigger('change');
                            }

                            // Tampilkan notifikasi
                            toastr.success("Category updated successfully!");
                        } else {
                            // Add new option and select it
                            var newOption = new Option(response.data.categoryname, response.data.categoryid, true, true);
                            $("#category").append(newOption).trigger('change');

                            toastr.success("Category added successfully!");
                        }

                        // Close the modal properly using bootstrap
                        var categoryModal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
                        if (categoryModal) {
                            categoryModal.hide();

                            // Ensure only one backdrop remains
                            setTimeout(function() {
                                $('.modal-backdrop').not(':first').remove();

                                // Ensure body still has modal-open class for the product modal
                                if ($('#modal_form_produk').hasClass('show')) {
                                    $('body').addClass('modal-open');
                                }
                            }, 150);
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
            if ($('#description').val().trim() === '') {
                $('#description').addClass('is-invalid');
                isValid = false;
            } else {
                $('#description').removeClass('is-invalid');
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
            $('#modal_form_produk').modal('hide');

            // Reload the DataTable to reflect changes
            $('#datatable').DataTable().ajax.reload();
        }
    });
</script>
