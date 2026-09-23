<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

$detailsDataJson = isset($detailsData) ? Json::encode($detailsData) : '[]';
// var_dump($detailsDataJson); // Debugging line to check the JSON data
// var_dump($module);
?>

<?php
$form = ActiveForm::begin([
    'id' => 'FormValidTran',
    'method' => 'post',
    'options' => [
        'enctype' => 'multipart/form-data',
    ],
    'validateOnSubmit' => true,
]);
?>
<style>
    .product-item td {
        vertical-align: middle !important;
        padding-top: 6px !important;
        padding-bottom: 6px !important;
    }

    .product-item .form-control,
    .product-item .form-select,
    .product-item .input-group-text {
        height: 36px !important;
        font-size: 14px !important;
        padding: 10px 15px !important;
        line-height: 1.5 !important;
    }

    .input-group-sm>.form-control,
    .input-group-sm>.form-select,
    .input-group-sm>.input-group-text {
        height: 30px !important;
    }

    /* Rata‑kanan khusus semua kolom angka di tabel item */
    .product-item .product-price,
    .product-item .product-subtotal,
    .product-item .product-disc,
    .product-item .product-total-after-disc {
        text-align: right !important;
    }

    .form-control.product-disc:focus {
        border-color: rgb(63, 193, 200) !important;
        box-shadow: none !important;
        outline: none !important;
    }
</style>


<!-- Hidden fields for module and type -->
<input type="hidden" name="module" value="<?= $module ?? 'purchase' ?>">
<input type="hidden" name="type" value="<?= $type ?? 'request' ?>">

<!--begin::Form-->
<!--begin::Scroll-->
<div class="d-flex flex-column scroll-y px-5 px-lg-10" id="modal_form_tran_scroll">
    <div class="d-flex flex-row py-10">
        <div class="row w-75">
            <div class="row">


                <!-- Left Side: Transaction Number and Contact -->
                <div class="mb-7 col-lg-3 col-md-3 col-sm-3">
                    <?= $form->field($model, 'tranno')->textInput(['readOnly' => false]); ?>
                </div>

                <div class="mb-7 col-lg-8 col-md-8 col-sm-8">
                    <?= $form->field($model, 'contactid', [
                        'errorOptions' => ['class' => 'text-danger mt-3'],
                    ])->dropDownList(
                        $model->contact ? [$model->contact['contact_id'] => $model->contact['contact_name']] : [],
                        [
                            'id' => 'contactSelect',
                            'class' => 'form-select contacts',
                            'data-control' => 'select2',
                            'placeholder' => Yii::$app->lang->t('tran', 'tran_no'),
                        ]
                    ); ?>
                </div>



            </div>

            <div class="row">
                <div class="mb-7 col-lg-8 col-md-8 col-sm-8">
                    <?= $form->field($model, 'refid', [
                        'errorOptions' => ['class' => 'text-danger mt-3'],
                    ])->dropDownList(
                        $model->refid ? [$model->ref['refid'] => $model->ref['tranid']] :  [],
                        [
                            'id' => 'refidSelect',
                            'class' => 'form-select refid',
                            'data-control' => 'select2',
                            'placeholder' => 'Referensi',
                        ]
                    ); ?>
                </div>
            </div>
        </div>


        <div class="row w-25">
            <!-- Left Side: Transaction Date -->
            <!-- Right Side: Due Date Dropdown -->
            <div class="row">

                <div class="mb-7 col-lg-12 col-md-12 col-sm-12">
                    <!-- <div class="col-12"> -->
                    <?= $form->field($model, 'term', [
                        'errorOptions' => ['class' => 'text-danger mt-3'],
                    ])->dropDownList(
                        [
                            '0' => 'COD',
                            '15' => 'Net 15',
                            '30' => 'Net 30',
                            '45' => 'Net 45',
                            '60' => 'Net 60',
                            '7' => 'Net 7',
                        ],
                        [
                            'id' => 'term',
                            'class' => 'form-select',
                            'data-control' => 'select2',
                            'placeholder' => Yii::$app->lang->t('tran', 'tran_no'),
                        ]
                    ); ?>
                </div>
                <!-- </div> -->
            </div>

            <div class="row">
                <div class="mb-7 col-lg-6 col-md-6 col-sm-6">
                    <?= $form->field($model, 'trandate', [
                        'errorOptions' => ['class' => 'text-danger mt-3'],
                    ])->textInput([
                        'placeholder' => Yii::$app->lang->t('tran', 'tran_date'),
                        'id' => 'tranDate',
                        'required' => true,
                    ]); ?>
                </div>
                <div class="mb-7 col-lg-6 col-md-6 col-sm-12">
                    <?= $form->field($model, 'tranduedate', [
                        'errorOptions' => ['class' => 'text-danger mt-3'],
                        'labelOptions' => ['style' => 'white-space: nowrap; width: 100%;']
                    ])->textInput([
                        'placeholder' => Yii::$app->lang->t('tran', 'tran_duedate'),
                        'id' => 'tranDueDate',
                    ]); ?>
                </div>
            </div>

        </div>
    </div>



    <!-- Product Items Section -->
    <div class=" mb-7 mt-5">
        <!-- <div class="card-header">
            <h3 class="card-title"><?= Yii::$app->lang->t('produk_detail', 'produk_detail') ?></h3>
            <div class="card-toolbar flex">


                <div class="form-check form-check-custom form-check-solid me-5">
                    <input
                        class="form-check-input price-include-tax"
                        type="checkbox"
                        id="price-include-tax"
                        name="Tran[price-include-tax]"
                        <?php if ($model->priceincludetax): ?>checked<?php endif; ?> />

                    <label class="form-check-label fw-bold" for="price-include-tax">
                        Price Include Tax
                    </label>
                </div>

            </div>
        </div> -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="products-table">
                    <thead>
                        <tr class="text-gray-600 fw-bold fs-7 text-uppercase gs-0">
                            <th style="width:250px;"><?= Yii::$app->lang->t('extra', 'extra38')  ?></th>
                            <th style="width:200px;"><?= Yii::$app->lang->t('purchase_table', 'purchase_deskripsi')  ?></th>
                            <th style="width:100px;">Qty</th>
                            <th style="width:150px;"><?= Yii::$app->lang->t('produk', 'produk_price')  ?></th>
                            <th style="width:180px;">Sub total</th>
                            <th style="width:100px;"><?= Yii::$app->lang->t('extra', 'extra5')  ?></th>
                            <th style="width:100px;"><?= Yii::$app->lang->t('extra', 'extra70')  ?></th>
                            <th style="width:180px;"><?= Yii::$app->lang->t('extra', 'extra71')  ?></th>
                            
                        </tr>
                    </thead>
                    <tbody id="product-rows">
                        <!-- Product rows will be added here dynamically -->
                        <tr id="empty-row">
                            <td colspan="9" class="text-center text-muted">Belum ada produk yang ditambahkan</td>
                        </tr>
                    </tbody>
                    <tfoot>

                        <tr>
                            <td colspan="5">
                                <!-- <td> -->
                                <button type="button" id="add-product-row" class="btn btn-sm btn-primary">
                                    <i class="ki-duotone ki-plus fs-2"></i> <?= Yii::$app->lang->t('home', 'addproduk') ?>
                                </button>
                            </td>
                            <!-- </td> -->
                            <td colspan="2" class="text-end fw-bold">Sub Total (+):</td>
                            <td>
                                <input type="text" name="Tran[subtotal]" class="form-control bg-light text-end tfoot-subtotal" value="0" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                            <td colspan="2" class="text-end fw-bold"><?= Yii::$app->lang->t('extra', 'extra72')  ?> (-):</td>
                            <td>
                                <input type="text" name="Tran[disc]" class="form-control bg-light text-end tfoot-disc" value="0" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                            <td colspan="2" class="text-end fw-bold"><?= Yii::$app->lang->t('extra', 'extra71')  ?>:</td>
                            <td>
                                <input type="text" name="Tran[totalafterdisc]" class="form-control bg-light text-end tfoot-totalafterdisc" value="0" readonly>
                            </td>
                        </tr>
                    </tfoot>

                </table>
            </div>

            <!-- Hidden input to store details as JSON -->
            <input type="hidden" name="details" id="transaction-details" value="">
        </div>
    </div>

    <table>
        <tbody>
            <!-- Baris PPN -->
            <tr class="border-top border-gray-300">
                <td style="width:60%;height:60px;"></td>

                <td>
                    <select class="form-select">
                        <option>PPN</option>
                    </select>
                </td>

                <td>
                    <div class="d-flex align-items-center">
                        <span class="me-2">(+)</span>
                     
                        <input type="text"
                            name="Tran[ppnamount]"
                            class="form-control bg-light tfoot-ppn text-end"
                            value="0"
                            readonly>
                    </div>
                </td>
            </tr>

            <!-- Baris PPH -->
            <tr class="border-top border-gray-300">
                <td style="height:60px;"></td>

                <td>
                    <select class="form-select">
                        <option>PPH</option>
                    </select>
                </td>

                <td>
                    <div class="d-flex align-items-center">
                        <span class="me-2">(-)</span>
                        <input type="text"
                            name="Tran[pphamount]"
                            class="form-control bg-light tfoot-pph text-end"
                            value="0"
                            readonly>
                    </div>
                </td>
            </tr>

            <!-- Baris Other Discount -->
            <tr class="border-top border-gray-300">
                <td style="height:60px;"></td>

                <td>
                    <select class="form-select">
                        <option>Other Discount</option>
                    </select>
                </td>

                <td>
                    <div class="d-flex align-items-center">
                        <span class="me-2">(-)</span>
                        <input type="text"
                            name="Tran[otherdiscount]"
                            class="form-control bg-light text-end"
                            value="0"
                            readonly>
                    </div>
                </td>
            </tr>

            <!-- Baris Delivery Charge -->
            <tr class="border-top border-gray-300">
                <td style="height:60px;"></td>

                <td>
                    <select class="form-select">
                        <option>Delivery Charge</option>
                    </select>
                </td>

                <td>
                    <div class="d-flex align-items-center">
                        <span class="me-2">(+)</span>
                        <input type="text"
                            name="Tran[deliverycharge]"
                            class="form-control bg-light text-end"
                            value="0"
                            readonly>
                    </div>
                </td>
            </tr>

            <!-- Baris Grand Total -->
            <tr class="border-top border-gray-300">
                <td style="height:60px;"></td>
                <td class="text-end fw-bold">Grand&nbsp;Total:</td>

                <td>
                    <input type="text"
                        name="Tran[grandtotal]"
                        class="form-control bg-light fw-bold text-end"
                        value="0"
                        readonly>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Notes/Additional Information Section -->
    <div class="row">
        <div class="col-12 mb-7">
            <?= $form->field($model, 'note', [
                'errorOptions' => ['class' => 'text-danger mt-3'],
            ])->textarea([
                'placeholder' => Yii::$app->lang->t('tran', 'tran_note'),
                'rows' => 3,
                'class' => 'form-control',
            ]); ?>

        </div>
    </div>
</div>
<!--end::Scroll-->

<!--begin::Actions-->
<div class="text-end pt-10">
    <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel" data-bs-dismiss="modal"><?= Yii::$app->lang->t('back_home', 'chat34') ?></button>
    <?= Html::submitButton($model->isNewRecord ?  Yii::$app->lang->t('extra', 'extra16') : Yii::$app->lang->t('extra', 'extra16'), ['id' => 'btnsubmit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <!-- ?= Html::submitButton($model->isNewRecord ?  Yii::$app->lang->t('btn_submit', 'btn_submit') : "Update", ['id' => 'btnsubmit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?> -->
</div>
<!--end::Actions-->
<!--end::Form-->
<?php ActiveForm::end(); ?>

<div class="modal" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel"><?= Yii::$app->lang->t('cta_add', 'cta_add') . " " . Yii::$app->lang->t('back_home', 'chat25') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm">
                    <input type="hidden" id="editCategoryId" value="">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label"><?= Yii::$app->lang->t('back_home', 'chat19') ?></label>
                        <input type="text" class="form-control" id="categoryName" placeholder="<?= Yii::$app->lang->t('back_home', 'chat19')  ?>">
                        <div class="invalid-feedback" id="categoryNameFeedback">
                            <?= Yii::$app->lang->t('back_home', 'chat19')  ?>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= Yii::$app->lang->t('back_home', 'chat34') ?></button>
                <button type="button" class="btn btn-primary" id="saveCategoryBtn"><?= Yii::$app->lang->t('extra', 'extra16') ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    var isProductModalOpen = false;
    var isSavingCategory = false;
    var formIsSubmitting = false;
    var currentModule = '<?= $module ?? 'purchase' ?>';
    var currentType = '<?= $type ?? 'request' ?>';

    if (currentType === 'quote' || currentType === 'request') {
        $('#refidSelect').closest('.row').hide(); // Sembunyikan elemen dropdown jika type adalah quote atau request
    }

    var categoryManager = {
        setupEvents: function() {
            // Edit category button
            $(document).off('click', '.edit-category').on('click', '.edit-category', function() {
                var categoryId = $("#category").val();
                var categoryName = $("#category option:selected").text();

                if (categoryId && categoryId !== "add_new_category") {
                    modalHandlers.openCategoryModal(true, categoryId, categoryName);
                }
            });

            // Delete category button
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

            // Handle Enter key in category name field
            $("#categoryName").off('keydown').on("keydown", function(e) {
                if (e.key === "Enter") {
                    e.preventDefault();
                    $("#saveCategoryBtn").click();
                }
            });

            // Save category button
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

                // Show loading state
                var btn = $(this);
                var originalText = btn.html();
                var moduletype = <?= json_encode($module) ?>;
                btn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

                var url = editCategoryId ?
                    "<?= \yii\helpers\Url::to(['/kategori/editkategori']) ?>" :
                    "<?= \yii\helpers\Url::to(['/contact/addvendor']) ?>";

                var requestData = {
                    name: categoryName,
                    moduletype: moduletype,
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
                                // Update existing option
                                var select2Instance = $("#category").data('select2');
                                if (select2Instance) {
                                    $("#category option[value='" + editCategoryId + "']").remove();
                                    var newOption = new Option(categoryName, editCategoryId, true, true);
                                    $("#category").append(newOption).trigger('change');
                                }

                                toastr.success("Category updated successfully!");
                            } else {
                                // Add new option
                                var newOption = new Option(response.data.contact_name, response.data.contact_id, true, true);
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

    // Modal events
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

    var modalHandlers = {
        openCategoryModal: function(isEdit, categoryId, categoryName) {
            isEdit = isEdit || false;
            categoryId = categoryId || '';
            categoryName = categoryName || '';

            $("#categoryName").val(isEdit ? categoryName : "").removeClass("is-invalid");
            $("#categoryNameFeedback").text("");
            $("#editCategoryId").val(isEdit ? categoryId : "");
            $("#addCategoryModalLabel").text(isEdit ? "<?= 'Edit ' . Yii::$app->lang->t('back_home', 'chat25') ?>" : "<?= Yii::$app->lang->t('cta_add', 'cta_add') . " " . Yii::$app->lang->t('back_home', 'chat25') ?>");
            $("#saveCategoryBtn").text("<?= Yii::$app->lang->t('extra', 'extra16') ?>");

            isProductModalOpen = $("#modal_form_tran").hasClass('show');

            $("#addCategoryModal").addClass('show').css('display', 'block');
            $("#addCategoryModal .modal-dialog").css("max-width", "600px");
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

    // Detail manager - handle details like variant manager
    var detailManager = {




        addNewDetail: function() {
            const detailCount = $('.product-item').length;
            const newIndex = detailCount;

            // Hide empty message if visible
            $("#empty-row").hide();

            const newDetail = `
    <tr class="product-item">
        <td>
            <select class="form-select product-select" required>
                <option value="">Choose Product</option>
            </select>
        </td>
        <td>
            <input type="text" name="details[${newIndex}][description]" class="form-control product-description" placeholder="Description">
        </td>
        <td>
            <input type="number" name="details[${newIndex}][jumlah]" class="form-control product-quantity" min="1" value="1" required>
        </td>
        <td>
            <input type="text" name="details[${newIndex}][harga]" class="form-control product-price" min="0" value="0" readonly>
        </td>
        <td>
            <input type="text" class="form-control product-subtotal bg-light" value="0" readonly>
        </td>
        <td>
            <div class="input-group input-group-sm product-discount-group">
                <input type="number" name="details[${newIndex}][disc]" class="form-control product-disc text-end" min="0" value="0">
                <input type="hidden" name="details[${newIndex}][itemdisctotal]" class="product-disc-input" value="0" readonly>
            </div>
        </td>
        <td>
            <select class="form-select product-tax" name="details[${newIndex}][tax]" required>
                <option value="0">...</option>
                <option value="1">PPN</option>
                <option value="2">PPH</option>
            </select>
        </td>
        <td style="width:120px;">
            <input type="text" name="details[${newIndex}][total-after-disc]" class="form-control product-total-after-disc bg-light" value="0" readonly>
        </td>

      <td>
  <button type="button" class="btn btn-sm btn-icon delete-detail btn-danger"
    style="${newIndex === 0 ? 'visibility: hidden;' : ''}">
    <i class="fas fa-trash"></i>
  </button>
     </td>

    </tr>
    `;

            $('#product-rows').append(newDetail);

            // Initialize select2 for the new row
            this.initializeProductSelect(newIndex);

            // Update all row numbers
            this.renumberDetails();
            this.updateTfootSummary();
        },

        initializeProductSelect: function(index) {
            if (typeof $.fn.select2 !== 'function') {
                console.error('Select2 plugin is not loaded.');
                return;
            }

            $('.product-item').eq(index).find('.product-select').select2({
                ajax: {
                    url: "<?= \yii\helpers\Url::to(['varianlist']) ?>",
                    type: "GET",
                    dataType: "json",
                    data: function(params) {
                        return {
                            q: params.term, // Send the search term to filter results
                            module: currentModule // Pass the current module
                        };
                    },
                    processResults: function(data) {
                        console.log("Received data:", data);
                        return {
                            results: data.results // Process and return the results for the dropdown
                        };
                    },
                    cache: true
                },
                placeholder: <?= json_encode(Yii::$app->lang->t('extra', 'extra43')) ?>,
                allowClear: true,
                dropdownParent: $('#FormValidTran').length ? $('#FormValidTran') : $(document.body),

                // Customize the result item template
                templateResult: function(item) {
                    if (item.loading) {
                        return item.produk;
                    }
                    // Customize the display for the result item
                    var $container = $(
                        "<div class='select2-result-item clearfix'>" +
                        "<strong><i class='fa-solid fa-barcode'></i> <span style='color : black; opacity : 0.75;'>" + item.sku + " </span></strong><br>" +
                        "<strong>" + item.produk + " - " + item.deskripsi + "</strong><br>" +
                        "</div>"
                    );

                    return $container;
                },

                // Customize the selected item template
                templateSelection: function(item) {
                    if (!item.id) {
                        return "Pilih Produk";
                    }

                    let namaProduk = item.produk;

                    // Jika produk tidak ditemukan, coba ambil dari data elemen
                    if (namaProduk === undefined && item.element) {
                        const $option = $(item.element);
                        namaProduk = $option.data('produk') || '';
                    }

                    var $selection = $(
                        "<div style='max-width : 150px !important' class='max-w-150px'>" +
                        "<strong>" + (namaProduk || item.text || '') + "</strong><br>" +
                        "</div>"
                    );

                    return $selection;
                }
            }).on('select2:select', function(e) {
                const varianId = e.params.data.id;
                const $row = $(this).closest('.product-item');

                // Store details for later use
                $(this).data('sku', e.params.data.sku || '');
                $(this).data('text', e.params.data.text || '');


                // Set the varianid in a hidden input
                if (!$row.find('input[name$="[varianid]"]').length) {
                    $row.append(`<input type="hidden" name="details[${index}][varianid]" value="${varianId}">`);
                } else {
                    $row.find('input[name$="[varianid]"]').val(varianId);
                }

                $row.find('.product-description').val(e.params.data.deskripsi || '');

                // Fetch product price
                $.ajax({
                    url: "<?= \yii\helpers\Url::to(['getprice']) ?>",
                    type: "GET",
                    data: {
                        id: varianId,
                        module: currentModule // Pass the current module to get correct price
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log("Price response:", response);
                            const price = parseFloat(response.price).toFixed(2); // Memastikan 2 angka di belakang koma
                            console.log("Price:", price);
                            // Format harga dengan Inputmask
                            $row.find('.product-price').val(detailManager.formatNumber(price)); // Format angka menjadi Rupiah
                            detailManager.updateRowSubtotal($row);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching price:", error);
                        $row.find('.product-price').val('0');
                        detailManager.updateRowSubtotal($row);
                    }
                });
            });

            // Set up quantity change handler
            // $('.product-item').eq(index).find('.product-quantity').on('change', function() {
            //     detailManager.updateRowSubtotal($(this).closest('.product-item'));
            // });

            // $('.product-item').eq(index).find('.product-disc').on('change', function() {
            //     detailManager.updateRowDisc($(this).closest('.product-item'));
            // });

            // $('.product-item').eq(index).find('.product-tax').on('change', function() {
            //     detailManager.updateRowTax($(this).closest('.product-item'));
            // });
            // Pastikan semua event handler terdaftar
            const $row = $('.product-item').eq(index);
            $row.find('.product-quantity').off('change').on('change', function() {
                detailManager.updateRowSubtotal($(this).closest('.product-item'));
            });

            $row.find('.product-disc').off('change').on('change', function() {
                detailManager.updateRowDisc($(this).closest('.product-item'));
            });

            $row.find('.product-tax').off('change').on('change', function() {
                detailManager.updateRowTax($(this).closest('.product-item'));
            });

            // Trigger update pertama kali
            detailManager.updateRowSubtotal($row);
        },


        // Ganti fungsi ini di detailManager:
        updateRowSubtotal: function($row) {
            const quantity = parseFloat($row.find('.product-quantity').val()) || 0;
            // Perbaikan parsing harga - handle format currency dengan benar
            const priceStr = $row.find('.product-price').val().replace(/[^\d,-]/g, '').replace(',', '.');
            const price = parseFloat(priceStr) || 0;

            const subtotal = quantity * price;
            $row.find('.product-subtotal').val(this.formatNumber(subtotal));

            this.updateRowDisc($row);
            this.updateTfootSummary();
        },

        updateRowDisc: function($row) {
            const subtotal = parseFloat($row.find('.product-subtotal').val().replace(/\./g, '').replace(',', '.')) || 0;
            const disc = parseFloat($row.find('.product-disc').val().replace(/\./g, '').replace(',', '.')) || 0;

            const totalDisc = parseFloat((subtotal * (disc / 100)));
            $row.find('.product-disc-text').text(this.formatNumber(totalDisc));
            $row.find('.product-disc-input').val(this.formatNumber(totalDisc));

            const totalAfterDisc = subtotal - totalDisc;
            // Update total after discount field
            console.log("Total After Discount:", totalAfterDisc);
            $row.find('.product-total-after-disc').val(this.formatNumber(totalAfterDisc.toFixed(2)));

            // Selalu update semua kalkulasi ketika discount berubah
            this.updateTfootSummary();
            this.updateRowTax($row);
        },

        updateRowTax: function($row) {
            // Get the tax selection (0, 1, or 2)
            const taxSelection = $row.find('.product-tax').val();

            // We don't need to calculate anything here, just trigger the summary update
            this.updateTfootSummary();
        },

        updateTfootSummary: function() {
            console.log("Updating footer summary...");
            const isPriceIncludeTax = $('.price-include-tax').is(':checked');
            console.log("Price Include Tax:", isPriceIncludeTax);

            let subtotal = 0,
                totalDisc = 0,
                totalAfterDisc = 0,
                totalPPN = 0,
                totalPPH = 0;

            $('.product-item').each((index, item) => {
                const $row = $(item);

                // Get all values
                const quantity = parseFloat($row.find('.product-quantity').val()) || 0;
                const priceStr = $row.find('.product-price').val().replace(/[^\d,-]/g, '').replace(',', '.');
                let price = parseFloat(priceStr) || 0;
                const discPercent = parseFloat($row.find('.product-disc').val()) || 0;
                const taxValue = parseInt($row.find('.product-tax').val()) || 0;

                // Debug log
                console.log(`Row ${index}: Qty=${quantity}, Price=${price}, Disc=${discPercent}%, Tax=${taxValue}`);

                // Handle price include tax for PPN items
                if (isPriceIncludeTax && taxValue === 1) {
                    // If price includes 11% PPN, calculate price before tax
                    price = price / 1.11;
                }

                // Calculate row values
                const rowSubtotal = quantity * price;
                const discAmount = rowSubtotal * (discPercent / 100);
                const afterDisc = rowSubtotal - discAmount;

                // Calculate taxes
                let ppnAmount = 0,
                    pphAmount = 0;
                if (taxValue === 1) {
                    ppnAmount = isPriceIncludeTax ? (rowSubtotal - (rowSubtotal / 1.11)) : (afterDisc * 0.11);
                }
                if (taxValue === 2) {
                    pphAmount = afterDisc * 0.02;
                }

                // Add to totals
                subtotal += rowSubtotal;
                totalDisc += discAmount;
                totalAfterDisc += afterDisc;
                totalPPN += ppnAmount;
                totalPPH += pphAmount;
            });

            // Update display
            $('.tfoot-subtotal').val(this.formatNumber(subtotal));
            $('.tfoot-disc').val(this.formatNumber(totalDisc));
            $('.tfoot-totalafterdisc').val(this.formatNumber(totalAfterDisc));
            $('.tfoot-ppn').val(this.formatNumber(totalPPN));
            $('.tfoot-pph').val(this.formatNumber(totalPPH));

            // Calculate grand total
            const grandTotal = totalAfterDisc + totalPPN - totalPPH;
            console.log(`Grand Total: ${totalAfterDisc} + ${totalPPN} - ${totalPPH} = ${grandTotal}`);

            $('input[name="Tran[grandtotal]"]').val(this.formatNumber(grandTotal));
        },

        // updateTfootSummary: function() {
        //     console.log("Updating footer summary...");
        //     let subtotal = 0,
        //         totalDisc = 0,
        //         totalAfterDisc = 0,
        //         totalPPN = 0,
        //         totalPPH = 0;

        //     $('.product-item').each((index, item) => {
        //         const $row = $(item);

        //         // Pastikan semua nilai terbaca dengan benar
        //         const quantity = parseFloat($row.find('.product-quantity').val()) || 0;
        //         const priceStr = $row.find('.product-price').val().replace(/[^\d,-]/g, '').replace(',', '.');
        //         const price = parseFloat(priceStr) || 0;
        //         const discPercent = parseFloat($row.find('.product-disc').val()) || 0;
        //         const taxValue = parseInt($row.find('.product-tax').val()) || 0;

        //         // Debug log
        //         console.log(`Row ${index}: Qty=${quantity}, Price=${price}, Disc=${discPercent}%, Tax=${taxValue}`);

        //         // Hitung ulang semua nilai
        //         const rowSubtotal = quantity * price;
        //         const discAmount = rowSubtotal * (discPercent / 100);
        //         const afterDisc = rowSubtotal - discAmount;

        //         let ppnAmount = 0,
        //             pphAmount = 0;
        //         if (taxValue === 1) ppnAmount = afterDisc * 0.11;
        //         if (taxValue === 2) pphAmount = afterDisc * 0.10;

        //         subtotal += rowSubtotal;
        //         totalDisc += discAmount;
        //         totalAfterDisc += afterDisc;
        //         totalPPN += ppnAmount;
        //         totalPPH += pphAmount;
        //     });

        //     // Update tampilan
        //     $('.tfoot-subtotal').val(this.formatNumber(subtotal));
        //     $('.tfoot-disc').val(this.formatNumber(totalDisc));
        //     $('.tfoot-totalafterdisc').val(this.formatNumber(totalAfterDisc));
        //     $('.tfoot-ppn').val(this.formatNumber(totalPPN));
        //     $('.tfoot-pph').val(this.formatNumber(totalPPH));

        //     // Hitung grand total
        //     const grandTotal = totalAfterDisc + totalPPN - totalPPH;
        //     console.log(`Grand Total Calculation: ${totalAfterDisc} + ${totalPPN} - ${totalPPH} = ${grandTotal}`);

        //     $('input[name="Tran[grandtotal]"]').val(this.formatNumber(grandTotal));
        // },
        // Alternative solution using arrow functions (which preserve "this" context)
        // updateTfootSummary: function() {
        //     console.log("Updating footer summary...");
        //     let subtotal = 0,
        //         totalDisc = 0,
        //         totalAfterDisc = 0,
        //         totalPPN = 0,
        //         totalPPH = 0;

        //     // Check if price includes tax
        //     const isPriceIncludeTax = $('.price-include-tax').is(':checked');
        //     // console.log("Price Include Tax:", isPriceIncludeTax);
        //     // Set hidden input value based on checkbox state
        //     // $('.price-include-tax').val(isPriceIncludeTax ? 1 : 0);
        //     // Using arrow function to preserve "this" context
        //     $('.product-item').each((index, item) => {
        //         const $row = $(item);

        //         // Get base values
        //         const quantity = parseFloat($row.find('.product-quantity').val()) || 0;
        //         const priceStr = $row.find('.product-price').val() || '0';
        //         let price = parseFloat(priceStr.replace(/\./g, '').replace(',', '.')) || 0;

        //         // Get discount percent
        //         const discPercent = parseFloat($row.find('.product-disc').val()) || 0;

        //         // Get tax type
        //         const taxValue = parseInt($row.find('.product-tax').val() || 0);

        //         // Handle price if it includes tax already - ONLY for PPN (tax type 1)
        //         let priceExclTax = price;
        //         if (isPriceIncludeTax && taxValue === 1) {
        //             // If price includes PPN (11%), calculate the price excluding tax
        //             priceExclTax = price / 1.11;
        //         }
        //         // For PPH (tax type 2), we always use the original price, regardless of the checkbox

        //         // Calculate subtotal based on price
        //         const rowSubtotal = quantity * priceExclTax;

        //         // Calculate discount amount based on subtotal
        //         const discAmount = rowSubtotal * (discPercent / 100);

        //         // Calculate total after discount
        //         const afterDisc = rowSubtotal - discAmount;
        //         console.log("afterdisc:", afterDisc);

        //         // Calculate taxes based on post-discount amount
        //         let ppnAmount = 0;
        //         let pphAmount = 0;

        //         if (taxValue === 1) {
        //             // PPN is 11% of amount after discount
        //             ppnAmount = (Math.round((afterDisc * 0.11) * 100) / 100);
        //             totalPPN += ppnAmount;
        //         } else if (taxValue === 2) {
        //             // PPH is 10% of amount after discount
        //             pphAmount = (Math.round((afterDisc * 0.10) * 100) / 100);
        //             totalPPH += pphAmount;
        //         }

        //         console.log("PPN Amount:", ppnAmount);
        //         console.log("PPH Amount:", pphAmount);


        //         // Add to running totals
        //         subtotal += rowSubtotal;
        //         totalDisc += discAmount;
        //         totalAfterDisc += afterDisc;

        //         console.log("total after disccc:", totalAfterDisc)
        //         console.log("subtotal:", subtotal);
        //         console.log("row subtotal:", rowSubtotal);
        //         // Update row's subtotal display
        //         $row.find('.product-subtotal').val(this.formatNumber(rowSubtotal));
        //         // $row.find('.product-subtotal').val(this.formatNumber(rowSubtotal));
        //         $row.find('.product-total-after-disc').val(this.formatNumber(afterDisc));


        //         // Update row's discount display
        //         $row.find('.product-disc-text').text(this.formatNumber(discAmount));
        //         $row.find('.product-disc-input').val(this.formatNumber(discAmount));

        //         // Update row's total after discount display
        //         const rowTotal = afterDisc + (taxValue === 1 ? ppnAmount : 0) - (taxValue === 2 ? pphAmount : 0);
        //         // $row.find('.product-total-after-disc').val(this.formatNumber(rowTotal));
        //         console.log("Row Total:", rowTotal);
        //     });

        //     // Update the summary fields
        //     $('.tfoot-subtotal').val(this.formatNumber(subtotal));
        //     $('.tfoot-disc').val(this.formatNumber(totalDisc));
        //     $('.tfoot-totalafterdisc').val(this.formatNumber(totalAfterDisc));

        //     // Update PPN and PPH fields
        //     $('.tfoot-ppn').val(this.formatNumber(totalPPN));
        //     $('.tfoot-pph').val(this.formatNumber(totalPPH));

        //     // Calculate and update grand total
        //     const grandTotal = (Math.round((totalAfterDisc + totalPPN - totalPPH) * 100) / 100);
        //     console.log("Grand Total:", grandTotal);
        //     console.log("totalppn:", totalPPN);
        //     console.log("totalpph:", totalPPH);

        //     // Update grand total if there's a field for it
        //     const flooredNumber = Math.floor(grandTotal * 100) / 100;
        //     console.log("Floored Grand Total:", flooredNumber);
        //     $('table:last tbody tr:last td:last input').val(this.formatNumber(flooredNumber));
        // },

        // Event handlers for price include tax functionality
        initializePriceIncludeTax: function() {
            // Add event handler for the "Price Include Tax" checkbox
            $(document).on('change', '.price-include-tax', function() {
                console.log("Price Include Tax changed to:", $(this).is(':checked'));
                detailManager.updateTfootSummary();
            });

            // Add delegation for product item changes
            $(document).on('change', '.product-quantity, .product-price, .product-disc, .product-tax , .price-include-tax', function() {
                console.log("Product item changed:", $(this).closest('.product-item'));
                // alert("Product item changed:", $(this).closest('.product-item'));
                detailManager.updateTfootSummary();
            });
        },

        updateAllRowCalculations: function() {
            $('.product-item').each((index, row) => {
                const $row = $(row);
                this.updateRowSubtotal($row);
            });
        },

        calculateTotal: function() {
            let total = 0;

            $('.product-item').each(function() {
                const quantity = parseInt($(this).find('.product-quantity').val()) || 0;
                const price = parseInt($(this).find('.product-price').val().replace(/[^\d]/g, '')) || 0;
                total += quantity * price;
            });

            $('#total-amount').text(this.formatNumber(total));
            $('#total-amount-input').val(this.formatNumber(total));
        },

        updateTransactionDetails: function() {
            const details = [];

            $('.product-item').each(function(index) {
                const $productSelect = $(this).find('.product-select');
                const varianId = $(this).find('input[name$="[varianid]"]').val() || $productSelect.val();

                if (varianId) {
                    const priceValue = $(this).find('.product-price').val().replace(/\./g, '');
                    const quantityValue = parseInt($(this).find('.product-quantity').val()) || 0;
                    const price = parseInt(priceValue) || 0;

                    const detail = {
                        varianid: varianId,
                        jumlah: quantityValue,
                        harga: $(this).find('.product-price').val().replace(/\./g, ''),
                        disc: $(this).find('.product-disc').val() || 0,
                        deskripsi: $(this).find('.product-description').val() || '',
                        itemdisctotal: $(this).find('.product-disc-input').val() || 0,
                        totalafterdisc: $(this).find('.product-total-after-disc').val() || 0,
                        itemdiscpersen: $(this).find('.product-disc').val() || 0,
                        tax_type: $(this).find('.product-tax').val() || 0,
                        itemsubtotal: $(this).find('.product-subtotal').val().replace(/\./g, ''),
                    };

                    // Include existing trandetailid if present
                    const trandetailid = $(this).find('input[name$="[trandetailid]"]').val();
                    if (trandetailid) {
                        detail.trandetailid = trandetailid;
                    }

                    details.push(detail);
                }
            });

            $('#transaction-details').val(JSON.stringify(details));
            console.log("Updated transaction details:", details); // Add logging for debugging
        },

        renumberDetails: function() {
            $('.product-item').each(function(index) {
                $(this).find('input, select').each(function() {
                    const name = $(this).attr('name');
                    if (name) {
                        const newName = name.replace(/details\[\d+\]/, `details[${index}]`);
                        $(this).attr('name', newName);
                    }
                });
            });
        },

        formatNumber: function(number) {
            return Number(number).toLocaleString('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        },


        initializeExistingDetails: function(details) {
            // Clear existing rows
            $("#product-rows").empty();
            console.log("Existing details:", details);

            if (!details || details.length === 0) {
                // Add empty row
                $("#product-rows").append('<tr id="empty-row"><td colspan="5" class="text-center text-muted">Belum ada produk yang ditambahkan</td></tr>');
                detailManager.addNewDetail();
                return;
            }

            $("#empty-row").hide();

            details.forEach((detail, index) => {
                console.log("Detail:", detail);
                const newRow = `
                <tr class="product-item">
                
                    <td class='col-lg-4 col-md-4 col-sm-4'>
                        <select class="form-select product-select" required>
                            <!-- Empty select that will be filled programmatically -->
                        </select>
                        <input type="hidden" name="details[${index}][varianid]" value="${detail.varianid}">
                        <input type="hidden" name="details[${index}][trandetailid]" value="${detail.trandetailid || ''}">
                    </td>
                    <td>
                    <input type="text" name="deskripsi" class="form-control product-description" value="${detail.itemtype || detail.deskripsi}">
                    </td>
                    <td>
                        <input type="number" name="details[${index}][jumlah]" class="form-control product-quantity" min="1" value="${detail.jumlah}" required>
                    </td>
                    <td>
                        <input type="text" name="details[${index}][harga]" class="form-control product-price" value="${this.formatNumber(detail.harga)}" >
                    </td>
                    <td>
                      <input type="text" class="form-control product-subtotal" name="details[${index}[subtotal]" value='${this.formatNumber(detail.itemsubtotal)}' readonly>
                    </td>
                     <td>
            <div class="d-flex">
                <input type="text" name="details[${index}][disc]" class="form-control rounded-start product-disc" min="0" value="${detail.itemdiscpersen}" style="width:30%; border-right:none;">
                    <div class="input-group-text product-disc-text bg-light text-end border-start-0" style="width:70%; border-radius:0 5px 5px 0;">0</div>
                <input type="hidden" name="details[${index}][itemdisctotal]" class="form-control product-disc-input" value="${detail.itemdisctotal}" >
            </div>
        </td>
        <td>
        <select class="form-select product-tax" name="details[${index}][tax]" required>
    <option value="0" ${detail.taxtype == 0 ? 'selected' : ''}>...</option>
    <option value="1" ${detail.taxtype == 1 ? 'selected' : ''}>PPN</option>
    <option value="2" ${detail.taxtype == 2 ? 'selected' : ''}>PPH</option>
</select>
        </td>
        <td style="width:120px;">
            <input type="text" name="details[${index}][total-after-disc]" class="form-control product-total-after-disc bg-light" value="0" readonly>
        </td>
                    ${index === 0 ?`
                    <td>
                    <button type="button" class="btn btn-sm btn-icon delete-detail btn-danger">
                    <i class="fas fa-trash"></i>
                    </button>
                    </td> ` : ''
                }
                </tr>
                `;

                $("#product-rows").append(newRow);

                // Get select element
                const $select = $('.product-item').eq(index).find('.product-select');

                // Initialize select2 on the empty select
                this.initializeProductSelect(index);

                // Create the data object with all required properties
                console.log("Detail option:", detail);
                console.log(detail.varianid)
                const optionData = {
                    id: detail.varianid,
                    text: detail.deskripsi,
                    sku: detail.sku || '',
                    produk: detail.namaproduk || '',
                    selected: true
                };

                // Create a new option element
                // Create a new option element
                const newOption = new Option(optionData.text, optionData.id, optionData.selected, optionData.selected);

                // Add option to select
                $select.append(newOption);

                // Set additional data for select2 - add ALL needed properties
                $select.find('option[value="' + optionData.id + '"]').data({
                    'sku': optionData.sku,
                    'produk': optionData.produk,
                    'deskripsi': optionData.text
                });

                // Trigger change to refresh select2 and ensure it's aware of new data
                $select.trigger('change.select2');
                const $row = $('.product-item').last();
                detailManager.updateRowSubtotal($row);
            });

            // Update total
            setTimeout(() => {
                detailManager.updateTfootSummary();
            }, 100);
            this.updateAllRowCalculations(); // Add this line
            this.calculateTotal();
            this.updateTransactionDetails();
        }
    };

    $(document).ready(function() {

        $('input').attr('autocomplete', 'off');
        $('.field-tran-tranno, .field-contactSelect, .field-tranDate').removeClass('required');
        categoryManager.setupEvents();
        // Initialize the form
        initForm();

        function initForm() {
            // Initialize contact select2
            if (typeof $.fn.select2 === 'function') {
                var moduletype = <?= json_encode($module) ?>;
                var placeholder = "<?= Yii::$app->lang->t('extradouble', 'double1') ?>"
                if (moduletype == 'purchase') {
                    var placeholder = "Vendor";
                } else {
                    var placeholder = "<?= Yii::$app->lang->t('extradouble', 'double1') ?>"
                }
                $(".contacts").select2({
                    ajax: {
                        url: "<?= \yii\helpers\Url::to(['contactlist']) ?>",
                        type: "GET",
                        dataType: "json",
                        data: function(params) {
                            return {
                                q: params.term,
                                module: currentModule // Pass current module to filter contacts
                            };
                        },
                        processResults: function(data) {
                            // Mapping data dari server
                            console.log(data);
                            const results = data.data.map(function(contact) {
                                return {
                                    id: contact.contact_id,
                                    text: contact.contact_name,
                                    contact_jobcompany: contact.jobcompany,
                                    contact_phone1: contact.contact_phone1 || '',
                                    contact_email1: contact.contact_email1 || ''
                                };
                            });

                            // Tambahkan opsi untuk "Add New Category"
                            if (moduletype == 'purchase') {
                                results.push({
                                    id: "add_new_category",
                                    text: "+ Add New Vendor"
                                });
                            } else {
                                results.push({
                                    id: "add_new_category",
                                    text: "+ Add New Customer"
                                });
                            }

                            return {
                                results: results
                            };
                        },
                        cache: true

                    },
                    placeholder: placeholder,
                    allowClear: true,
                    templateResult: formatContact,
                    templateSelection: formatContactSelection,
                    dropdownParent: $('#modal_form_tran').length ? $('#modal_form_tran') : $(document.body)
                });


                $(".refid").select2({
                    ajax: {
                        url: "<?= \yii\helpers\Url::to(['reflist']) ?>",
                        type: "GET",
                        dataType: "json",
                        data: function(params) {
                            return {
                                q: params.term, // Term pencarian
                                module: currentModule, // Kirimkan module
                                type: currentType // Kirimkan type
                            };
                        },
                        processResults: function(data) {
                            console.log("Received data:", data);
                            return {
                                results: data.results.map(function(ref) {
                                    console.log("Ref data:", ref);
                                    return {
                                        id: ref.id,
                                        text: ref.tranno,
                                        // contact_phone1: contact.contact_phone1 || '',
                                        // contact_email1: contact.contact_email1 || ''
                                    };
                                })
                            };
                        },
                        cache: true
                    },
                    placeholder: "Reference",
                    allowClear: true,
                    templateResult: formatContact,
                    templateSelection: formatContactSelection,
                    dropdownParent: $('#modal_form_tran').length ? $('#modal_form_tran') : $(document.body)
                });

                // $("#term").on('change', function() {
                //     var term = $(this).val();
                //     var days = parseInt(term);

                //     if (isNaN(days)) {
                //         console.error("Invalid term value:", term);
                //         $('#tranDueDate').val(''); // Clear the field on error
                //         return;
                //     }

                //     // Calculate the new date
                //     var currentDate = new Date();
                //     currentDate.setDate(currentDate.getDate() + days);

                //     // Format the date as dd-mm-yyyy
                //     var day = String(currentDate.getDate()).padStart(2, '0');
                //     var month = String(currentDate.getMonth() + 1).padStart(2, '0');
                //     var year = currentDate.getFullYear();
                //     var formattedDate = day + '-' + month + '-' + year;

                //     // Update the due date field
                //     $('#tranDueDate').val(formattedDate);

                //     console.log("Term:", term, "Due date:", formattedDate);

                //     // Trigger change event to handle updates if user changes tranDueDate manually
                //     $('#tranDueDate').trigger('change');
                // });

                // Perbaikan fungsi term
                $("#term").on('change', function() {
                    // Dapatkan nilai term dan parse sebagai integer
                    const termDays = parseInt($(this).val()) || 0;

                    // Dapatkan tanggal transaksi
                    const tranDateStr = $('#tranDate').val();

                    if (!tranDateStr) {
                        console.warn('Transaction date is empty');
                        return;
                    }

                    try {
                        // Parse tanggal dari format dd-mm-yyyy
                        const [day, month, year] = tranDateStr.split('-');
                        const tranDate = new Date(`${year}-${month}-${day}`);

                        // Validasi tanggal
                        if (isNaN(tranDate.getTime())) {
                            throw new Error('Invalid transaction date');
                        }

                        // Tambahkan hari sesuai term
                        const dueDate = new Date(tranDate);
                        dueDate.setDate(dueDate.getDate() + termDays);

                        // Format kembali ke dd-mm-yyyy
                        const formattedDueDate = [
                            String(dueDate.getDate()).padStart(2, '0'),
                            String(dueDate.getMonth() + 1).padStart(2, '0'),
                            dueDate.getFullYear()
                        ].join('-');

                        // Set nilai dan update flatpickr jika ada
                        $('#tranDueDate').val(formattedDueDate);

                        const dueDatePicker = $('#tranDueDate')[0]._flatpickr;
                        if (dueDatePicker) {
                            dueDatePicker.setDate(formattedDueDate, true);
                        }

                    } catch (error) {
                        console.error('Error calculating due date:', error);
                    }
                });

                // Trigger perubahan saat tanggal transaksi berubah
                $('#tranDate').on('change', function() {
                    if ($('#term').val()) {
                        $('#term').trigger('change');
                    }
                });

                // Optional: Handle manually changing tranDueDate and updating the date
                // $('#tranDueDate').on('change', function() {
                //     console.log("Due date manually changed to:", $(this).val());
                // });

                // });
            }

            // if (typeof $.fn.select2 === 'function') {

            // }

            // if (typeof $.fn.select2 === 'function') {
            //     $("#tranduedate").select2({
            //         data: [{
            //                 id: 'Net 7',
            //                 text: 'Net 7'
            //             },
            //             {
            //                 id: 'Net 15',
            //                 text: 'Net 15'
            //             },
            //             {
            //                 id: 'Net 30',
            //                 text: 'Net 30'
            //             },
            //             {
            //                 id: 'Net 45',
            //                 text: 'Net 45'
            //             },
            //             {
            //                 id: 'Net 60',
            //                 text: 'Net 60'
            //             }
            //         ],
            //         placeholder: "Select a due date",
            //         allowClear: true,
            //         templateSelection: function(data) {
            //             return data.text; // Customize the selection template if needed
            //         },
            //         dropdownParent: $('#modal_form_tran').length ? $('#modal_form_tran') : $(document.body)
            //     });

            //     // When the user selects an option
            //     $('#tranduedate').on('change', function() {
            //         const selectedOption = $(this).val(); // Get selected option text
            //         const currentDate = new Date();

            //         // Mapping options to days
            //         const daysMapping = {
            //             'Net 7': 7,
            //             'Net 15': 15,
            //             'Net 30': 30,
            //             'Net 45': 45,
            //             'Net 60': 60
            //         };

            //         console.log(selectedOption)

            //         // Get the number of days to add from the mapping
            //         const daysToAdd = daysMapping[selectedOption] || 0;

            //         // Add days to current date
            //         currentDate.setDate(currentDate.getDate() + daysToAdd);

            //         // Set the value of the field to the calculated date in YYYY-MM-DD format
            //         $(this).val(currentDate.toISOString().split('T')[0]);

            //         // Log the calculated date
            //         console.log(currentDate.toISOString().split('T')[0]);
            //     });
            // }

            // Initialize datepickers
            if (typeof flatpickr === 'function') {
                $("#tranDate").flatpickr({
                    allowClear: true,
                    enableTime: false,
                    dateFormat: "d-m-Y",
                    defaultDate: "<?= Yii::$app->formatter->asDate($model->trandate, 'php:d-m-Y'); ?>"
                });

                $("#tranDueDate").flatpickr({
                    allowClear: true,
                    enableTime: false,
                    dateFormat: "d-m-Y",
                    defaultDate: "<?= Yii::$app->formatter->asDate($model->tranduedate, 'php:d-m-Y'); ?>"
                });
            }
            // else {
            //     console.warn('Flatpickr not loaded. Using basic date inputs.');
            //     $("#tranDate").val("?= date('d-m-Y') ?>");
            //     $("#tranDueDate").val("?= date('d-m-Y', strtotime('+30 days')) ?>");
            // }

            // Initialize existing details
            var existingDetails = <?= $detailsDataJson ?>;
            // console.log();
            detailManager.initializeExistingDetails(existingDetails);

            // Setup event handlers
            setupEventHandlers();

            // Setup form submission
            setupFormSubmission();
            detailManager.initializePriceIncludeTax();

        }

        function formatContact(contact) {
            if (!contact.id) return contact.text;

            // Cek jika item adalah opsi untuk tambah kategori
            if (contact.id === "add_new_category") {
                return $(
                    '<div class="select2-result-contact clearfix" style="color: #009ef7; font-weight: bold;">' +
                    '<i class="fas fa-plus-circle me-2"></i>' + contact.text +
                    '</div>'
                );
            }
            // console.log(contact);
            // Template normal untuk contact
            var $container = $(
                '<div class="select2-result-contact clearfix">' +
                '<div class="select2-result-contact__name"><i class="fa fa-user me-1"></i>' + contact.text + '</div>' +
                (contact.contact_phone1 ? '<div class="select2-result-contact__phone"><i class="fa fa-phone me-1"></i> ' + contact.contact_phone1 + '</div>' : '') +
                (contact.contact_email1 ? '<div class="select2-result-contact__email"><i class="fa fa-envelope me-1"></i> ' + contact.contact_email1 + '</div>' : '') +
                (contact.contact_jobcompany ? '<div class="select2-result-contact__email"><i class="fa fa-building me-1"></i> ' + contact.contact_jobcompany + '</div>' : '') +
                '</div>'
            );

            return $container;
        }


        function formatContactSelection(contact) {
            if (!contact.id) return contact.text || '';

            // Sembunyikan/tampilkan tombol tergantung pilihan
            // if (contact.id !== "add_new_category") {
            //     setTimeout(function() {
            //         $(".category-buttons").show();
            //     }, 100);
            // } else {
            //     setTimeout(function() {
            //         $(".category-buttons").hide();
            //     }, 100);
            // }

            // Jika pilih "+ Add New Category"
            if (contact.id === "add_new_category") {
                setTimeout(function() {
                    // Reset pilihan dan buka modal tambah kategori
                    $("#category").val(null).trigger("change");
                    if (typeof modalHandlers !== 'undefined' && modalHandlers.openCategoryModal) {
                        modalHandlers.openCategoryModal(false);
                    }
                }, 1);

                return "Select Category";
            }

            // Default tampilkan nama kontak
            return contact.text || contact.id;
        }


        function setupEventHandlers() {
            // Add product button
            $("#add-product-row").on("click", function() {
                detailManager.addNewDetail();
            });

            // Delete detail button - use event delegation
            $(document).off('click', '.delete-detail').on('click', '.delete-detail', function() {
                $(this).closest('.product-item').remove();
                detailManager.renumberDetails();

                if ($('.product-item').length === 0) {
                    $("#empty-row").show();
                }

                // Call updateTfootSummary instead of calculateTotal to properly update all calculations
                detailManager.updateTfootSummary();
                // detailManager.updateTransactionDetails();
            });
        }

        function setupFormSubmission() {
            // Reset submission flag
            formIsSubmitting = false;

            // Unbind any existing handlers
            $('#FormValidTran').off('submit');

            // Attach the submit handler
            $('#FormValidTran').on('submit', function(e) {
                e.preventDefault();

                if (formIsSubmitting) {
                    return false;
                }

                formIsSubmitting = true;

                try {
                    // Validation logic
                    let isValid = true;
                    let contactSelect = $('.contacts');

                    if (!contactSelect.val()) {
                        contactSelect.addClass('is-invalid');
                        isValid = false;
                    } else {
                        contactSelect.removeClass('is-invalid');
                    }

                    if ($('.product-item').length === 0) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: "Error!",
                                text: "Anda perlu menambahkan minimal satu produk ke transaksi.",
                                icon: "error",
                                confirmButtonColor: "#d33",
                                confirmButtonText: "OK"
                            }).then(() => {
                                formIsSubmitting = false;
                            });
                        } else {
                            alert("Anda perlu menambahkan minimal satu produk ke transaksi.");
                            formIsSubmitting = false;
                        }
                        isValid = false;
                    }

                    if (!isValid) {
                        formIsSubmitting = false;
                        return false;
                    }

                    // Update transaction details
                    detailManager.updateTransactionDetails();

                    // Log the form data for debugging
                    console.log("Form data (details):", $('#transaction-details').val());

                    // Visual feedback
                    let btn = $('#btnsubmit');
                    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');

                    // AJAX submission
                    $.ajax({
                        url: $('#FormValidTran').attr('action'),
                        type: 'POST',
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            console.log("Server response:", response);

                            if (response && response.success) {
                                if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        title: "Success!",
                                        text: "Transaksi berhasil disimpan.",
                                        icon: "success",
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                } else {
                                    alert("Transaksi berhasil disimpan.");
                                }

                                // Close modal
                                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                                    var modalElement = document.getElementById('modal_form_tran');
                                    if (modalElement) {
                                        var modalInstance = bootstrap.Modal.getInstance(modalElement);
                                        if (modalInstance) {
                                            modalInstance.hide();
                                        }
                                    }
                                } else if (typeof $.fn.modal === 'function' && $('#modal_form_tran').length) {
                                    $('#modal_form_tran').modal('hide');
                                } else {
                                    $('#modal_form_tran').hide();
                                }

                                // Reload data
                                if ($('#datatable').length > 0 && $.fn.DataTable) {
                                    $('#datatable').DataTable().ajax.reload();
                                } else {
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 1000);
                                }
                            } else {
                                // Improved error handling
                                var errorMessage = "Terjadi kesalahan saat menyimpan transaksi.";

                                // Check if we have a specific error message
                                if (response && response.pesan) {
                                    errorMessage = response.pesan;
                                }

                                // If the error message is empty, provide a generic message
                                if (!errorMessage || errorMessage === "") {
                                    errorMessage = "Terjadi kesalahan yang tidak teridentifikasi. Silakan cek log sistem.";
                                    console.error("Empty error message received. Full response:", response);
                                }

                                if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        title: "Error!",
                                        html: errorMessage, // Use html to properly render HTML in error message
                                        icon: "error",
                                        confirmButtonColor: "#d33",
                                        confirmButtonText: "OK"
                                    });
                                } else {
                                    alert(errorMessage);
                                }
                                btn.prop('disabled', false).html('Simpan');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", status, error);
                            console.error("Response:", xhr.responseText);

                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: "Error!",
                                    text: "Terjadi kesalahan saat mengirim data: " + error,
                                    icon: "error",
                                    confirmButtonColor: "#d33",
                                    confirmButtonText: "OK"
                                });
                            } else {
                                alert("Terjadi kesalahan saat mengirim data: " + error);
                            }
                            btn.prop('disabled', false).html('Simpan');
                        },
                        complete: function() {
                            formIsSubmitting = false;
                        }
                    });
                } catch (error) {
                    console.error('Unexpected error:', error);
                    formIsSubmitting = false;

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: "Error!",
                            text: "Terjadi kesalahan tak terduga: " + error.message,
                            icon: "error",
                            confirmButtonColor: "#d33",
                            confirmButtonText: "OK"
                        });
                    } else {
                        alert("Terjadi kesalahan tak terduga: " + error.message);
                    }

                    // Re-enable submit button
                    $('#btnsubmit').prop('disabled', false).html('Simpan');
                }

                return false;
            });

            // Additional protection for submit button
            $('#btnsubmit').on('click', function() {
                if (formIsSubmitting) {
                    return false;
                }
            });
        }
    });
</script>