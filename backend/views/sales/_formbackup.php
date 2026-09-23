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



        <!-- PRICE INPUT CONTAINER -->
        <div id="price-container" class="mb-7 col-lg-4 col-md-4 col-sm-4 col-xs-12 position-relative">
            <label class="fw-semibold fs-6 mb-2">Price</label>
            <div class="position-relative">
                <span class="position-absolute top-50 start-0 translate-middle-y bg-primary text-white px-3 py-4 fw-bold"
                    style="border-radius: 6px 0 0 6px; height: 100%;">
                    RP
                </span>
                <?= $form->field($model, 'price')->textInput([
                    'class' => 'form-control price ps-5 text-end',
                    'placeholder' => 'Enter price',
                    'id' => 'price',
                    'required' => true,
                    'autocomplete' => 'off',
                    'style' => 'border-radius: 6px; padding-left: 50px;'
                ])->label(false) ?>
            </div>
            <div class="invalid-feedback" id="price-feedback">Price is required</div>
        </div>

        <div class="mb-7 col-lg-4 col-md-4 col-sm-4">
            <label class="fw-semibold fs-6 mb-2">Product SKU</label>
            <?= $form->field($model, 'produksku', ['errorOptions' => ['class' => 'text-danger mt-1']])->textInput(['class' => 'form-control', 'placeholder' => 'Product SKU', 'id' => 'produksku', 'required' => true])->label(false) ?>
            <!-- <div class="invalid-feedback" id="sku-feedback">Product Sku is required</div> -->
        </div>

        <div class="mb-7 col-lg-4 col-md-4 col-sm-4 col-xs-12" id="category-container">
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

        <div class="mb-7 col-lg-12 col-md-12 col-sm-12" id="image-container">
            <div class="image-input image-input-outline" id="kt_image_4" data-kt-image-input="true"
                style="background-image: url('https://img1.wsimg.com/isteam/ip/3b5c6067-4366-46be-bbaa-75aff3a98515/placeholder.jpg');">

                <div class="image-input-wrapper" id="image_preview"
                    style="background-image: url('https://img1.wsimg.com/isteam/ip/3b5c6067-4366-46be-bbaa-75aff3a98515/placeholder.jpg');">
                </div>

                <!-- Button Change Avatar -->
                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                    data-kt-image-input-action="change" data-toggle="tooltip" title="Change avatar">
                    <i class="fa fa-pen icon-sm text-muted"></i>
                    <input type="file" id="profile_avatar" name="profile_avatar" accept=".png, .jpg, .jpeg" />
                    <input type="hidden" name="profile_avatar_remove" />
                </label>

                <!-- Button Cancel -->
                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                    data-kt-image-input-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                </span>

                <!-- Button Remove -->
                <span id="remove_avatar" class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-danger"
                    data-kt-image-input-action="remove" data-toggle="tooltip" title="Remove avatar" style="display: none;">
                    <i class="fa fa-trash text-white"></i>
                </span>
            </div>
        </div>



    </div>

    <!-- Checkbox to Toggle Varian Form -->
    <div class="form-group mb-5">
        <div class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input" type="checkbox" id="toggleVariantBtn" />
            <label class="form-check-label fw-semibold fs-6" for="toggleVariantBtn">
                Enable Product Variants
            </label>
        </div>
    </div>

    <!-- Dynamic Varian Form -->
    <div id="varianContainer" style="display: none;">
        <div class="separator separator-dashed my-5"></div>

        <h3 class="text-start mb-5 fw-bold">Product Variants</h3>

        <div class="variant-list">
            <!-- Initial Variant Row -->
            <!-- <div class="variant-item card mb-5 shadow-sm">

            </div> -->
        </div>

        <!-- Add Variant Button -->
        <div class="d-flex justify-content-center my-5">
            <button type="button" id="addVariantBtn" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Variant
            </button>
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveCategoryBtn">Save Category</button>
            </div>
        </div>
    </div>
</div>

<!-- CSS untuk perbaikan tampilan modal -->
<style>
    /* Styling untuk varian */
    .variant-item {
        border: 1px solid #e4e6ef;
        border-radius: 0.475rem;
        transition: all 0.3s ease;
    }

    .variant-item:hover {
        border-color: #009ef7;
        box-shadow: 0 0.1rem 1rem 0.25rem rgba(0, 0, 0, 0.05) !important;
    }

    .delete-variant {
        transition: all 0.3s ease;
    }

    .delete-variant:hover {
        background-color: #f1416c;
        color: white;
    }
</style>

<!-- <script src="assets/plugins/custom/ktimagecontrol/ktimagecontrol.js"></script> -->

<script type="text/javascript">
    // Cek jika variabel sudah dideklarasikan
    if (typeof window.isProductModalOpen === 'undefined') {
        // Global variables untuk tracking modal
        window.isProductModalOpen = false;
        window.isCategoryModalOpen = false;
    }

    // Fungsi untuk membuka modal kategori dengan cara custom
    function openCategoryModal(isEdit = false, categoryId = '', categoryName = '') {
        // Reset form
        $("#categoryName").val(isEdit ? categoryName : "").removeClass("is-invalid");
        $("#categoryNameFeedback").text("");
        $("#editCategoryId").val(isEdit ? categoryId : "");
        $("#addCategoryModalLabel").text(isEdit ? "Edit Category" : "Add New Category");
        $("#saveCategoryBtn").text(isEdit ? "Update Category" : "Save Category");

        // Mark product modal as active untuk restore setelah kategori ditutup
        window.isProductModalOpen = $("#modal_form_produk").hasClass('show');

        // Tampilkan modal kategori secara manual
        $("#addCategoryModal").addClass('show').css('display', 'block');
        $('body').addClass('modal-open').css('overflow', 'hidden');

        // Tambahkan backdrop jika belum ada
        if ($('.modal-backdrop').length === 0) {
            $('body').append('<div class="modal-backdrop fade show"></div>');
        }

        // Set flag modal kategori terbuka
        window.isCategoryModalOpen = true;

        // Focus pada input form
        setTimeout(function() {
            $('#categoryName').focus();
        }, 300);
    }

    // Fungsi untuk menutup modal kategori dengan cara custom
    function closeCategoryModal() {
        // Hide modal
        $("#addCategoryModal").removeClass('show').css('display', 'none');

        // Reset flag
        window.isCategoryModalOpen = false;

        // Jika product modal masih terbuka, restore status
        if (window.isProductModalOpen) {
            // Pastikan backdrop tetap ada
            if ($('.modal-backdrop').length === 0) {
                $('body').append('<div class="modal-backdrop fade show"></div>');
            }

            // Pastikan body tetap memiliki class modal-open
            $('body').addClass('modal-open').css('overflow', 'hidden');
        } else {
            // Jika tidak ada modal yang terbuka, bersihkan
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css('overflow', '');
        }
    }

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

    function form() {
        // Product Type Select2
        if ($("#productType").length && !$("#productType").data('select2')) {
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
        }

        // Price input formatter - cek jika event belum terpasang
        const priceInput = document.getElementById("price");

        priceInput.addEventListener("input", function(e) {
            let value = e.target.value.replace(/[^\d]/g, ""); // Hanya angka (hapus semua karakter selain angka)

            if (value !== "") {
                e.target.value = formatNumber(value);
            } else {
                e.target.value = ""; // Kosongkan jika tidak ada angka
            }
        });

        // Fungsi untuk memformat angka dengan titik sebagai pemisah ribuan
        function formatNumber(number) {
            return new Intl.NumberFormat("id-ID").format(number);
        }

        // Function to format numbers with dots as thousand separators
        function numberWithDots(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Initialize Category Select2 with custom option
        if ($("#category").length && !$("#category").data('select2')) {
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
        }

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

                    // Gunakan custom function bukan modal BS
                    openCategoryModal(false);
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

    // Implementasi varian produk - dengan check agar tidak duplikat
    function setupVariantFunctionality() {
        // Toggle variant container visibility - cek jika event belum terpasang
        $('#toggleVariantBtn').off('change').on('change', function() {
            $('#varianContainer').slideToggle(300);
        });

        // Add new variant - cek jika event belum terpasang
        $('#addVariantBtn').off('click').on('click', function() {
            addNewVariant();
            togglePriceInput(); // Cek apakah input harga harus disembunyikan
            toggleImageInput()
        });

        // Delete variant (using event delegation)
        $(document).off('click', '.delete-variant').on('click', '.delete-variant', function() {
            if ($('.variant-item').length >= 1) {
                $(this).closest('.variant-item').remove();
                renumberVariants();
                togglePriceInput(); // Cek apakah input harga harus ditampilkan kembali
                toggleImageInput()
            } else {
                toastr.error("Cannot delete the last variant. At least one variant is required.");
            }
        });

        // Function to add a new variant
        function addNewVariant() {
            const variantCount = $('.variant-item').length;
            const newIndex = variantCount;

            const newVariant = `
<div class="variant-item card mb-5 shadow-sm">
    <div class="card-body p-5">
        <div class="d-flex justify-content-between mb-4">
            <h4 class="card-title fw-bold variant-title">Variant #${newIndex + 1}</h4>
            <button type="button" class="btn btn-sm btn-icon btn-danger delete-variant">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="row g-3">
            <!-- Image Container with Dynamic ID -->
            <div class="mb-7 col-lg-12 col-md-12 col-sm-12" id="image-container-${newIndex}">
                <div class="image-input image-input-outline" id="kt_image_${newIndex}" data-kt-image-input="true"
                    style="background-image: url('https://img1.wsimg.com/isteam/ip/3b5c6067-4366-46be-bbaa-75aff3a98515/placeholder.jpg');">

                    <div class="image-input-wrapper"
                        style="background-image: url('https://img1.wsimg.com/isteam/ip/3b5c6067-4366-46be-bbaa-75aff3a98515/placeholder.jpg');">
                    </div>

                    <!-- Button Change Avatar -->
                    <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                        data-kt-image-input-action="change" data-toggle="tooltip" title="Change avatar">
                        <i class="fa fa-pen icon-sm text-muted"></i>
                        <input type="file" name="variants[${newIndex}][profile_avatar]" accept=".png, .jpg, .jpeg" />
                        <input type="hidden" name="variants[${newIndex}][avatar_remove]" value="0" />
                    </label>

                    <!-- Button Cancel -->
                    <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                        data-kt-image-input-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                        <i class="ki ki-bold-close icon-xs text-muted"></i>
                    </span>

                    <!-- Button Remove -->
                    <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-danger"
                        data-kt-image-input-action="remove" data-toggle="tooltip" title="Remove avatar" style="display: none;">
                        <i class="fa fa-trash text-white"></i>
                    </span>
                </div>
            </div>

            <!-- Price -->
            <div class="col-6 mb-4">
                <label class="fw-semibold fs-6 mb-2">Harga Beli</label>
                <div class="position-relative">
                    <span class="position-absolute top-50 start-0 translate-middle-y bg-primary text-white px-3 py-4 fw-bold"
                          style="border-radius: 6px 0 0 6px; height: 100%;">
                        RP
                    </span>
                    <input type="text" name="variants[${newIndex}][hargabeli]" class="form-control varian-harga-beli ps-5 text-end"
                           placeholder="Harga Beli" required autocomplete="off"
                           style="border-radius: 6px; padding-left: 50px;" />
                </div>
                <div class="invalid-feedback">Harga Beli is required</div>
            </div>

            <div class="col-6 mb-4">
                <label class="fw-semibold fs-6 mb-2">Harga Jual</label>
                <div class="position-relative">
                    <span class="position-absolute top-50 start-0 translate-middle-y bg-primary text-white px-3 py-4 fw-bold"
                          style="border-radius: 6px 0 0 6px; height: 100%;">
                        RP
                    </span>
                    <input type="text" name="variants[${newIndex}][hargajual]" class="form-control varian-harga-jual ps-5 text-end"
                           placeholder="Harga Jual" required autocomplete="off"
                           style="border-radius: 6px; padding-left: 50px;" />
                </div>
                <div class="invalid-feedback">Harga Jual is required</div>
            </div>
            <div class="col-12 mb-4">
                <div class="col-6 mb-4">
                <label class="fw-semibold fs-6 mb-2">Harga Grosir</label>
                <div class="position-relative">
                    <span class="position-absolute top-50 start-0 translate-middle-y bg-primary text-white px-3 py-4 fw-bold"
                            style="border-radius: 6px 0 0 6px; height: 100%;">
                        RP
                    </span>
                    <input type="text" name="variants[${newIndex}][hargagrosir]" class="form-control varian-harga-grosir ps-5 text-end"
                            placeholder="Harga Grosir" required autocomplete="off"
                            style="border-radius: 6px; padding-left: 50px;" />
                </div>
                </div>
                <div class="invalid-feedback">Harga Grosir is required</div>
            </div>

            <!-- SKU -->
            <div class="col-md-6 col-lg-4">
                <label class="fw-semibold fs-6 mb-2">SKU</label>
                <input type="text" name="variants[${newIndex}][sku]" class="form-control variant-sku" placeholder="Enter SKU" />
            </div>

            <div class="col-md-6 col-lg-8">
                <label class="fw-semibold fs-6 mb-2">Deskripsi</label>
                <input type="text" name="variants[${newIndex}][deskripsi]" class="form-control" placeholder="Deskripsi" />
            </div>

            <!-- Stok -->
            <div class="col-md-6 col-lg-3" style="display : none">
                <label class="fw-semibold fs-6 mb-2">Stock:</label>
                <input type="number" name="variants[${newIndex}][stok]" class="form-control" placeholder="Enter Stock" min="0" />
            </div>
        </div>
    </div>
</div>
`;

            $('.variant-list').append(newVariant);

            // Initialize the price input formatting for this variant
            initVariantPriceInput(newIndex, 'hargabeli');
            initVariantPriceInput(newIndex, 'hargajual');
            initVariantPriceInput(newIndex, 'hargagrosir');

            // Initialize the image handler for this variant
            initVariantImageHandler(newIndex);

            // Update SKU for newly added variant
            updateVariantSKUs();
        }

        // Add this function to initialize price input formatting
        function initVariantPriceInput(index, type) {
            const priceInput = $(`input[name="variants[${index}][${type}]"]`);

            // Format as currency on input
            priceInput.on('input', function(e) {
                // Remove non-numeric characters
                let value = $(this).val().replace(/[^\d]/g, '');

                // Format with thousand separators
                if (value !== "") {
                    $(this).val(formatNumber(value));
                } else {
                    $(this).val(""); // Empty if no number
                }
            });

            // Format on blur to ensure correct formatting
            priceInput.on('blur', function() {
                // Remove non-numeric characters
                let value = $(this).val().replace(/[^\d]/g, '');

                if (value !== "") {
                    $(this).val(formatNumber(value));
                }
            });
        }

        // Utility function to format numbers with dots (Indonesian format)
        function formatNumber(number) {
            return new Intl.NumberFormat("id-ID").format(number);
        }

        function initVariantImageHandler(index) {
            const imageContainerId = `kt_image_${index}`;
            const avatarElement = document.getElementById(imageContainerId);
            const inputFile = document.querySelector(`#${imageContainerId} input[type="file"]`);
            const imagePreview = document.querySelector(`#${imageContainerId} .image-input-wrapper`);
            const removeButton = document.querySelector(`#${imageContainerId} [data-kt-image-input-action="remove"]`);

            if (!avatarElement || !inputFile || !removeButton || !imagePreview) {
                console.warn(`Elements for image handler #${imageContainerId} not found in DOM.`);
                return;
            }

            // Hide remove button initially
            removeButton.style.display = "none";

            // Check if current image is default
            function isDefaultImage() {
                const currentBg = imagePreview.style.backgroundImage;
                return currentBg.includes('placeholder.jpg');
            }

            // Show/hide remove button based on whether default image is showing
            function updateRemoveButtonVisibility() {
                if (isDefaultImage()) {
                    removeButton.style.display = "none";
                } else {
                    removeButton.style.display = "flex";
                }
            }

            // Check if there's already a non-default image on page load
            updateRemoveButtonVisibility();

            // Event when image is changed
            inputFile.addEventListener("change", function(event) {
                console.log(`File selected for variant #${index}:`, inputFile.files);
                if (inputFile.files && inputFile.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.style.backgroundImage = `url(${e.target.result})`;
                        console.log(`Image preview updated for variant #${index}:`, e.target.result);
                        removeButton.style.display = "flex"; // Show remove button
                    };
                    reader.readAsDataURL(inputFile.files[0]);
                }
            });

            // Event when cancel button is clicked
            const cancelButton = document.querySelector(`#${imageContainerId} [data-kt-image-input-action="cancel"]`);
            if (cancelButton) {
                cancelButton.addEventListener('click', function() {
                    // Reset to default image
                    imagePreview.style.backgroundImage = "url('https://img1.wsimg.com/isteam/ip/3b5c6067-4366-46be-bbaa-75aff3a98515/placeholder.jpg')";
                    removeButton.style.display = "none"; // Hide remove button
                    inputFile.value = ''; // Clear file input

                    // Show message
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Image selection canceled!',
                            icon: 'warning',
                            buttonsStyling: false,
                            confirmButtonText: 'OK',
                            confirmButtonClass: 'btn btn-primary font-weight-bold'
                        });
                    }
                });
            }

            // Event when remove button is clicked
            removeButton.addEventListener('click', function() {
                // Reset file input
                inputFile.value = '';

                // Reset background image to default
                imagePreview.style.backgroundImage = "url('https://img1.wsimg.com/isteam/ip/3b5c6067-4366-46be-bbaa-75aff3a98515/placeholder.jpg')";

                // Add hidden input to mark for removal if needed
                const removeInput = avatarElement.querySelector('input[name^="variants"][name$="[avatar_remove]"]');
                if (removeInput) {
                    removeInput.value = '1';
                }

                // Hide remove button
                removeButton.style.display = "none";

                // Show message
                // if (typeof Swal !== 'undefined') {
                //     Swal.fire({
                //         title: 'Image successfully removed!',
                //         icon: 'success',
                //         buttonsStyling: false,
                //         confirmButtonText: 'Got it!',
                //         confirmButtonClass: 'btn btn-primary font-weight-bold'
                //     });
                // }
            });

            // If KTImageInput is available, initialize it
            if (typeof KTImageInput !== "undefined") {
                try {
                    const avatar = new KTImageInput(avatarElement);
                    console.log(`KTImageInput initialized successfully for variant #${index}.`);

                    // Additional KTImageInput event handlers can be added here if needed
                } catch (error) {
                    console.error(`Error initializing KTImageInput for variant #${index}:`, error);
                }
            } else {
                console.warn("KTImageInput is not defined. Basic image handling will be used instead.");
            }
        }

        // Function to update the image handler for all variants
        function updateAllVariantImageHandlers() {
            $('.variant-item').each(function(index) {
                initVariantImageHandler(index);
            });
        }

        // Fungsi untuk update semua SKU varian berdasarkan produk SKU
        function updateVariantSKUs() {
            const productSKU = $('#produksku').val().trim();

            if (productSKU) {
                $('.variant-item').each(function(index) {
                    const skuField = $(this).find('.variant-sku');
                    const currentSKU = skuField.val().trim();

                    // Periksa apakah SKU varian masih default atau sudah diedit manual oleh user
                    const isDefaultSKU = currentSKU === "" || currentSKU.match(/^.*-\d+$/);

                    // Jika masih menggunakan format lama atau kosong, update dengan SKU baru
                    if (isDefaultSKU) {
                        skuField.val(productSKU + '-' + (index + 1));
                    }
                });
            }
        }

        // Event listener untuk perubahan di Product SKU
        $('#produksku').off('input').on('input', function() {
            updateVariantSKUs();
        });

        // Function to renumber variant titles & update SKU jika ada perubahan
        function renumberVariants() {
            $('.variant-item').each(function(index) {
                $(this).find('.variant-title').text(`Variant #${index + 1}`);

                // Update input names dengan indeks yang benar
                $(this).find('input, select').each(function() {
                    const name = $(this).attr('name');
                    if (name) {
                        const newName = name.replace(/variants\[\d+\]/, `variants[${index}]`);
                        $(this).attr('name', newName);
                    }
                });

                // Update SKU jika masih mengikuti pola SKU produk
                updateVariantSKUs();
            });
        }

        // Fungsi untuk menampilkan atau menyembunyikan input harga
        function togglePriceInput() {
            const variantCount = $('.variant-item').length;
            if (variantCount >= 1) {
                $('#price-container').hide(); // Sembunyikan input harga
            } else {
                $('#price-container').show(); // Tampilkan input harga kembali
            }
        }

        function toggleImageInput() {
            const variantCount = $('.variant-item').length;
            if (variantCount >= 1) {
                $('#image-container').hide(); // Sembunyikan input harga
            } else {
                $('#image-container').show(); // Tampilkan input harga kembali
            }
        }
    }



    // Perbaikan untuk form submit ganda
        function setupFormHandlers() {
            // Cek jika variabel sudah ada di global scope
            if (typeof window.isSubmitting === 'undefined') {
                window.isSubmitting = false;
            }

            // Hapus semua event handler yang mungkin terduplikasi
            $('#FormValid').off('submit');
            $('#btnsubmit').off('click');

            // Event submit form
            $('#FormValid').on('submit', function(e) {
                e.preventDefault();

                // Cek flag submit
                if (window.isSubmitting) {
                    console.log('Form sudah dalam proses submit, abaikan submit tambahan');
                    return false;
                }

                let isValid = true;

                // Check required fields
                if ($('#deskripsi').val().trim() === '') {
                    $('#deskripsi').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#deskripsi').removeClass('is-invalid');
                }

                // Validasi product type jika ada
                if ($('#productType').length && ($('#productType').val() === null || $('#productType').val() === '')) {
                    $('#productType').next('.select2-container').addClass('is-invalid');
                    isValid = false;
                } else if ($('#productType').length) {
                    $('#productType').next('.select2-container').removeClass('is-invalid');
                }

                if ($('#produksku').val().trim() === '' || $('#produksku').val().trim() === '') {
                    $('#produksku').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#produksku').removeClass('is-invalid');
                }

                if ($('#price').val().trim() === '' || $('#price').val().trim() === '0') {
                    $('#price').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#price').removeClass('is-invalid');
                }

                if (isValid) {
                    // Set flag submitting
                    window.isSubmitting = true;

                    // Panggil fungsi submit AJAX
                    submitProductFormAjax();
                }

                return false;
            });

            // Event click pada tombol submit
            $('#btnsubmit').on('click', function(e) {
                e.preventDefault();

                // Cek flag submit
                if (!window.isSubmitting) {
                    // Submit form secara manual
                    $('#FormValid').submit();
                }

                return false;
            });

            // Function untuk submit form via AJAX
            function submitProductFormAjax() {
                // Set tombol disabled
                $('#btnsubmit').prop('disabled', true);
                $('#btnsubmit').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');

                console.log('Submitting form via AJAX...');

                // Prepare form data
                var formData = new FormData($('#FormValid')[0]);

                // Clean price value (remove dots)
                var priceInput = $('#price');
                if (priceInput.length) {
                    var cleanPrice = priceInput.val().replace(/\./g, '');
                    formData.set('Produk[price]', cleanPrice);
                }

                // Add variants data if enabled
                if ($('#toggleVariantBtn').is(':checked')) {
                    var variantsData = [];
                    var variantHarga = [];

                    $('.variant-item').each(function(index) {
                        const sku = $(this).find('input[name^="variants"][name$="[sku]"]').val();
                        // const warna = $(this).find('input[name^="variants"][name$="[warna]"]').val();
                        // const ukuran = $(this).find('input[name^="variants"][name$="[ukuran]"]').val();
                        // const stok = $(this).find('input[name^="variants"][name$="[stok]"]').val() || "0";
                        const deskripsi = $(this).find('input[name^="variants"][name$="[deskripsi]"]').val();
                        const hargabeli = $(this).find('input[name^="variants"][name$="[hargabeli]"]').val().replace(/\./g, '');
                        const hargajual = $(this).find('input[name^="variants"][name$="[hargajual]"]').val().replace(/\./g, '');
                        const hargagrosir = $(this).find('input[name^="variants"][name$="[hargagrosir]"]').val().replace(/\./g, '');
                        // const hargabeli =
                        // const deskripsi = $(this).find
                        // Tambah data variant
                        variantsData.push({
                            sku: sku,
                            // warna: warna,
                            // ukuran: ukuran,
                            // stok: stok,
                            status: "1", // Default active
                            deskripsi: deskripsi,
                        });

                        variantHarga.push({
                            hargabeli: hargabeli,
                            hargajual: hargajual,
                            hargagrosir: hargagrosir
                        })
                    });

                    formData.append('variants', JSON.stringify(variantsData));
                    formData.append('has_variants', "1");
                    formData.append('variantharga', JSON.stringify(variantHarga));
                    // formData.append('')
                } else {
                    formData.append('has_variants', "0");
                }

                // Send AJAX request
                $.ajax({
                    url: $('#FormValid').attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Reset flags dan button
                        window.isSubmitting = false;
                        $('#btnsubmit').prop('disabled', false);
                        $('#btnsubmit').html('Submit');

                        console.log('AJAX response received:', typeof response);

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
                        if (response && response.success) {
                            handleSuccessResponse();
                        } else {
                            // Show error message
                            Swal.fire({
                                title: "Error!",
                                text: response.pesan || "There was an error saving the product.",
                                icon: "error",
                                confirmButtonText: "OK"
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Reset flags dan button
                        window.isSubmitting = false;
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
                if ($.fn.DataTable.isDataTable('#datatable')) {
                    $('#datatable').DataTable().ajax.reload();
                } else {
                    // Reload halaman jika tidak ada datatable
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                }
            }
        }

        $(document).ready(function() {
            var avatarElement = document.getElementById('kt_image_4');
            var inputFile = document.getElementById('profile_avatar');
            var imagePreview = document.getElementById('image_preview');
            var removeButton = document.getElementById('remove_avatar'); // Ambil elemen tombol remove

            if (!avatarElement || !inputFile || !removeButton) {
                console.warn("Element #kt_image_4 atau #profile_avatar tidak ditemukan di DOM.");
                return;
            }

            // Hide remove button initially
            removeButton.style.display = "none";

            // Check if current image is default
            function isDefaultImage() {
                var currentBg = imagePreview.style.backgroundImage;
                return currentBg.includes('placeholder.jpg');
            }

            // Show/hide remove button based on whether default image is showing
            function updateRemoveButtonVisibility() {
                if (isDefaultImage()) {
                    removeButton.style.display = "none";
                } else {
                    removeButton.style.display = "flex";
                }
            }

            // Check if there's already a non-default image on page load
            updateRemoveButtonVisibility();

            // Pastikan KTImageInput telah didefinisikan sebelum digunakan
            if (typeof KTImageInput !== "undefined") {
                var avatar4 = new KTImageInput(avatarElement);
                console.log("KTImageInput initialized successfully.");

                // Event saat gambar diubah
                inputFile.addEventListener("change", function(event) {
                    console.log("File selected:", inputFile.files);
                    if (inputFile.files && inputFile.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.style.backgroundImage = `url(${e.target.result})`;
                            console.log("Image preview updated:", e.target.result);
                            removeButton.style.display = "flex"; // Tampilkan tombol remove
                        };
                        reader.readAsDataURL(inputFile.files[0]);
                    }
                });

                // Event saat tombol "cancel" ditekan
                avatar4.on('cancel', function(imageInput) {
                    Swal.fire({
                        title: 'Image selection canceled!',
                        icon: 'warning',
                        buttonsStyling: false,
                        confirmButtonText: 'OK',
                        confirmButtonClass: 'btn btn-primary font-weight-bold'
                    });

                    // Reset kembali ke gambar default
                    imagePreview.style.backgroundImage = "url('https://img1.wsimg.com/isteam/ip/3b5c6067-4366-46be-bbaa-75aff3a98515/placeholder.jpg')";
                    removeButton.style.display = "none"; // Sembunyikan tombol remove
                });

                // Event saat tombol remove ditekan
                removeButton.addEventListener('click', function() {
                    // Reset file input
                    inputFile.value = '';

                    // Reset background image to default
                    imagePreview.style.backgroundImage = "url('https://img1.wsimg.com/isteam/ip/3b5c6067-4366-46be-bbaa-75aff3a98515/placeholder.jpg')";

                    // Add hidden input to mark for removal if needed
                    var removeInput = avatarElement.querySelector('input[name="profile_avatar_remove"]');
                    if (removeInput) {
                        removeInput.value = '1';
                    }

                    // Hide remove button
                    removeButton.style.display = "none";

                    // Show message
                    // Swal.fire({
                    //     title: 'Image successfully removed!',
                    //     icon: 'success',
                    //     buttonsStyling: false,
                    //     confirmButtonText: 'Got it!',
                    //     confirmButtonClass: 'btn btn-primary font-weight-bold'
                    // });
                });

                // Event saat gambar dihapus using KTImageInput
                avatar4.on('remove', function(imageInput) {
                    // Swal.fire({
                    //     title: 'Image successfully removed!',
                    //     icon: 'error',
                    //     buttonsStyling: false,
                    //     confirmButtonText: 'Got it!',
                    //     confirmButtonClass: 'btn btn-primary font-weight-bold'
                    // });

                    // Reset kembali ke gambar default
                    imagePreview.style.backgroundImage = "url('https://img1.wsimg.com/isteam/ip/3b5c6067-4366-46be-bbaa-75aff3a98515/placeholder.jpg')";
                    removeButton.style.display = "none"; // Sembunyikan tombol remove
                });

            } else {
                console.error("KTImageInput is not defined. Pastikan scripts.bundle.js termuat dengan benar.");

                // If KTImageInput isn't available, set up basic functionality

                // Event saat gambar diubah
                inputFile.addEventListener("change", function(event) {
                    if (inputFile.files && inputFile.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.style.backgroundImage = `url(${e.target.result})`;
                            removeButton.style.display = "flex"; // Tampilkan tombol remove
                        };
                        reader.readAsDataURL(inputFile.files[0]);
                    }
                });

                // Attach direct click handler to remove button
                removeButton.addEventListener('click', function() {
                    inputFile.value = '';
                    imagePreview.style.backgroundImage = "url('https://img1.wsimg.com/isteam/ip/3b5c6067-4366-46be-bbaa-75aff3a98515/placeholder.jpg')";
                    removeButton.style.display = "none";

                    // Set hidden input for removal
                    var removeInput = avatarElement.querySelector('input[name="profile_avatar_remove"]');
                    if (removeInput) {
                        removeInput.value = '1';
                    }

                    // Swal.fire({
                    //     title: 'Image successfully removed!',
                    //     icon: 'error',
                    //     buttonsStyling: false,
                    //     confirmButtonText: 'Got it!',
                    //     confirmButtonClass: 'btn btn-primary font-weight-bold'
                    // });
                });
            }

            // Initialize elements
            setNo();
            form();
            setupVariantFunctionality();
            setupFormHandlers();
            $('.field-produk-produkkode , .field-deskripsi , .field-produksku').removeClass('required');

            // Handler untuk Bootstrap modal events
            $('#modal_form_produk').off('shown.bs.modal').on('shown.bs.modal', function() {
                window.isProductModalOpen = true;
            });

            $('#modal_form_produk').off('hidden.bs.modal').on('hidden.bs.modal', function() {
                window.isProductModalOpen = false;

                // Pastikan tidak ada backdrop yang tertinggal
                if (!window.isCategoryModalOpen) {
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open').css('overflow', '');
                }
            });

            // Atur event untuk custom close modal
            $(document).off('click', '.btn-close, [data-bs-dismiss="modal"]').on('click', '.btn-close, [data-bs-dismiss="modal"]', function() {
                const modalId = $(this).closest('.modal').attr('id');

                if (modalId === 'addCategoryModal') {
                    // Gunakan custom close function untuk modal kategori
                    closeCategoryModal();
                    return false;
                }
            });

            // Handler untuk Edit Category Button
            $(document).off('click', '.edit-category').on('click', '.edit-category', function() {
                var categoryId = $("#category").val();
                var categoryName = $("#category option:selected").text();

                if (!categoryId || categoryId === "add_new_category") return;

                // Gunakan custom open modal
                openCategoryModal(true, categoryId, categoryName);
            });

            // Handler untuk Delete Category Button
            $(document).off('click', '.delete-category').on('click', '.delete-category', function() {
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
            $("#categoryName").off('keydown').on("keydown", function(e) {
                if (e.key === "Enter") {
                    e.preventDefault();
                    $("#saveCategoryBtn").click();
                }
            });

            // Save category button handler - Perbaikan untuk double submit
            // Cek jika variabel sudah ada di global scope
            if (typeof window.isSavingCategory === 'undefined') {
                window.isSavingCategory = false;
            }

            $("#saveCategoryBtn").off('click').on("click", function() {
                // Cek jika sedang proses saving
                if (window.isSavingCategory) {
                    return;
                }

                var categoryName = $("#categoryName").val().trim();
                var editCategoryId = $("#editCategoryId").val();

                // Basic validation
                if (categoryName === "") {
                    $("#categoryName").addClass("is-invalid");
                    $("#categoryNameFeedback").text("Please enter a category name.");
                    return;
                }

                // Set flag saving
                window.isSavingCategory = true;

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
                                // Update existing option in select2
                                var select2Instance = $("#category").data('select2');
                                if (select2Instance) {
                                    // Remove old option
                                    $("#category option[value='" + editCategoryId + "']").remove();

                                    // Add updated option
                                    var newOption = new Option(categoryName, editCategoryId, true, true);
                                    $("#category").append(newOption);

                                    // Trigger change to update select2
                                    $("#category").trigger('change');
                                }

                                // Show notification
                                toastr.success("Category updated successfully!");
                            } else {
                                // Add new option and select it
                                var newOption = new Option(response.data.kategorinama, response.data.kategoriid, true, true);
                                $("#category").append(newOption).trigger('change');

                                toastr.success("Category added successfully!");
                            }

                            // Close modal menggunakan custom function
                            closeCategoryModal();
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
                        // Reset flag dan button state
                        window.isSavingCategory = false;
                        btn.prop("disabled", false).html(originalText);
                    }
                });
            });
        });

    // Tambahkan CSS untuk modal styling
    // Cek jika style belum ditambahkan
    if (!$('#modal-custom-style').length) {
        $('<style id="modal-custom-style">')
            .prop('type', 'text/css')
            .html(`
                /* Styling untuk modal */
                .modal-backdrop.show {
                    opacity: 0.5 !important;
                }

                /* Pastikan body tetap modal-open saat modal aktif */
                body.modal-open {
                    overflow: hidden !important;
                    padding-right: 17px !important;
                }

                /* Styling untuk varian */
                .variant-item {
                    border: 1px solid #e4e6ef;
                    border-radius: 0.475rem;
                    transition: all 0.3s ease;
                }

                .variant-item:hover {
                    border-color: #009ef7;
                    box-shadow: 0 0.1rem 1rem 0.25rem rgba(0, 0, 0, 0.05) !important;
                }

                .delete-variant {
                    transition: all 0.3s ease;
                }

                .delete-variant:hover {
                    background-color: #f1416c;
                    color: white;
                }
            `)
            .appendTo('head');
    }
</script>
