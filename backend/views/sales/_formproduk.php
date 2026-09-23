<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<?php
$form = ActiveForm::begin(
	[
		'id' => 'FormValid',
		'method' => 'post',
		'options' => ['enctype' => 'multipart/form-data'],
		//	'validateOnSubmit' => true,
		'options' => [
			'data-pjax' => true
		]
	]
);
?>
<!--begin::Form-->

<!--begin::Scroll-->
<div class="d-flex flex-column scroll-y px-5 px-lg-10" id="modal_form_product_scroll" data-kt-scroll="false" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#modal_form_product_header" data-kt-scroll-wrappers="#modal_form_product_scroll" data-kt-scroll-offset="300px">
	<!--begin::Input group-->

	<!--end::Input group-->
	<!--begin::Input group-->
	<div class="row">
		<div class="mb-7 col-lg-2 col-md-2 col-sm-2">
			<!--begin::Label-->
			<label class="fw-semibold fs-6 mb-2">Code</label>
			<!--end::Label-->
			<!--begin::Input-->
			<input class="form-control form-control-solid" type="input" value="<?= $model->produkkode ?>" class="code" name="Produk[produkkode]" readonly />
			<!--end::Input-->
		</div>
		<div class="mb-7 col-lg-4 col-md-4 col-sm-4">
			<!-- begin::Label-->
			<label class="fw-semibold fs-6 mb-2">Product Name</label>
			<!--end::Label -->
			<!--begin::Input-->
			<input class="form-control" type="text" placeholder="Product Name" value="<?= $model->deskripsi ?>" class="deskripsi" id="deskripsi" name="Produk[deskripsi]" />
			<!--end::Input-->
		</div>
		<!--end::Input group-->
		<div class="mb-7 col-lg-6 col-md-6 col-sm-6">
			<label class="fw-semibold fs-6 mb-2" for="productType">Product Type:</label>
			<select id="productType" class="form-select" data-control="select2" name="Produk[jenis]">
				<option value="<?= $model->jenis['enumid'] ?? '' ?>" selected><?= $model->jenis['enumtext_id'] ?? '' ?></option>
			</select>
		</div>
	</div>
	<div class="row">
		<!--begin::Input group-->
		<div class="mb-7 col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<!--begin::Label-->
			<label class="fw-semibold fs-6 mb-2">Price</label>
			<!--end::Label-->
			<!--begin::Input-->
			<input class="form-control price" id="price" type="text" placeholder="Price" value="<?= number_format($model->price ?? 0, 0, ',', '.') ?>" name="Produk[price]" />
			<!--end::Input-->
		</div>
		<!--end::Input group-->
		<!--begin::Input group-->
		<div class="mb-7 col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<!--begin::Label-->
			<label class="fw-semibold fs-6 mb-2 ">Category</label>
			<!--end::Label-->
			<!--begin::Input-->
			<select id="category" class="form-select category" data-control="select2" name="Produk[kategoriid]">
				<option value="<?= $model->kategori['kategoriid'] ?? '' ?>" selected><?= $model->kategori['kategorinama'] ?? '' ?></option>
			</select>
			<!--end::Input-->
		</div>
		<!--end::Input group-->
	</div>
	<div class="form-group row">

	</div>

	<a id="toggleButton" class="w-200px text-start" style="cursor:pointer;">+ Show More</a>
	<!-- Product Details -->
	<div id="additionalFields" class="d-none mt-5">
		<h3 for="titleDetails" class="text text-start mb-7 mt-5 fw-bold">Product Details</h3>
		<div class="form-group mb-7 col-md-12 col-lg-12">
			<!--begin::Label-->
			<label class="fw-semibold fs-6 mb-2">Brand</label>
			<!--end::Label-->
			<!--begin::Input-->
			<select id="brand" class="form-select brand" data-control="select2" name="Produk[brandid]">
				<option value="<?= $model->brand['enumid'] ?? '' ?>" selected><?= $model->brand['enumtext_id'] ?? '' ?></option>
			</select>
			<!--end::Input-->
		</div>
		<div class="form-group row">
			<div class="mb-7 col-md-6 col-lg-6 col-sm-6">
				<!--begin::Label-->
				<label class="fw-semibold fs-6 mb-2">SKU</label>
				<!--end::Label-->
				<!--begin::Input-->
				<input class="form-control" type="text" placeholder="SKU Code" value="<?= $model->sku ?? '' ?>" id="sku" name="Produk[sku]" />
				<!--end::Input-->
			</div>
			<!--end::Input group-->
			<!--end::Input group-->
			<div class="form-group mb-7 col-md-6 col-lg-6 col-sm-6">
				<!--begin::Label-->
				<label class="fw-semibold fs-6 mb-2">Barcode</label>
				<!--end::Label-->
				<!--begin::Input-->
				<input class="form-control" type="text" placeholder="Barcode" value="<?= $model->barcode ?? '' ?>" id="barcode" name="Produk[barcode]" />
				<!--end::Input-->
			</div>
		</div>
		<div class="form-group row">
			<div class="mb-7 col-lg-12 col-md-12 col-sm-12">
				<!-- begin::Label-->
				<label class="fw-semibold fs-6 mb-2">Description</label>
				<!--end::Label -->
				<!--begin::Input-->
				<textarea class="form-control" type="text" placeholder="Product Description" value="<?= $model->description ?? '' ?>" id="description" name="Produk[description]" rows="3"></textarea>
				<!--end::Input-->
			</div>
		</div>
		<h3 for="titlePricing" class="text text-start mb-7 mt-5 fw-bold">Pricing Details</h3>
		<div class="form-group row">
			<!--end::Input group-->
			<div class="form-group mb-7 col-md-4 col-sm-4 col-lg-4">
				<!--begin::Label-->
				<label class="fw-semibold fs-6 mb-2">Cost Price</label>
				<!--end::Label-->
				<!--begin::Input-->
				<input class="form-control" type="number" placeholder="Cost Price" value="<?= $model->costprice ?? 0 ?>" id="costprice" name="Produk[costprice]" />
				<!--end::Input-->
			</div>
			<div class="mb-7 col-lg-4 col-md-4 col-sm-4">
				<!-- begin::Label-->
				<label class="fw-semibold fs-6 mb-2">Wholesale Price</label>
				<!--end::Label -->
				<!--begin::Input-->
				<input class="form-control" type="number" placeholder="Wholesale Price" value="<?= $model->wholesaleprice ?? 0 ?>" id="wholesaleprice" name="Produk[wholesaleprice]" />
				<!--end::Input-->
			</div>
			<div class="mb-7 col-lg-4 col-md-4 col-sm-4">
				<!-- begin::Label-->
				<label class="fw-semibold fs-6 mb-2">Tax (%)</label>
				<!--end::Label -->
				<!--begin::Input-->
				<input class="form-control" type="number" placeholder="Tax Percentage" value="<?= $model->tax ?? 0 ?>" id="tax" name="Produk[tax]" />
				<!--end::Input-->
			</div>
		</div>

	</div>
	<!-- End Product Details -->
</div>
<!--end::Scroll-->
<!--begin::Actions-->
<div class="text-end pt-10">
	<button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel" data-bs-dismiss="modal">Discard</button>


	<?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Submit', ['id' => 'btnsubmit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

</div>
<!--end::Actions-->

<!--end::Form-->
<?php ActiveForm::end(); ?>

<script type="text/javascript">
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
		//untuk form
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
		});
		$("#productType").on("change", function() {
			console.log($(this).val());
		});

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

		$("#category").select2({
			ajax: {
				url: "<?= \yii\helpers\Url::to(['produk/kategorilist']) ?>",
				type: "GET",
				dataType: "json",
				delay: 250,
				data: function(params) {
					return {
						q: params.term, // Send user input to backend
					};
				},
				processResults: function(data) {
					return {
						results: data.results
					};
				},
				cache: true
			},
			placeholder: "Select Category",
			allowClear: true,
		});
		$("#category").on("change", function() {
			console.log($(this).val());
		});

		$("#unit").select2({
			ajax: {
				url: "<?= \yii\helpers\Url::to(['produk/unitlist']) ?>",
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
			placeholder: "Select Unit",
			allowClear: true,
		});

		$("#brand").select2({
			ajax: {
				url: "<?= \yii\helpers\Url::to(['produk/brandlist']) ?>",
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
			placeholder: "Select Brand",
			allowClear: true,
		});

		flatpickr($('#mfgdate')[0], {
			allowClear: true,
			enableTime: false,
			dateFormat: "d-m-Y",
			placeholder: "Manufacture Date",
			onChange: function(selectedDates, dateStr, instance) {
				$("#clear-mfgdate").toggle(!!dateStr); // Show button if there's content
			}
		});

		flatpickr($('#expdate')[0], {
			allowClear: true,
			enableTime: false,
			dateFormat: "d-m-Y",
			placeholder: "Expiry Date",
			onChange: function(selectedDates, dateStr, instance) {
				$("#clear-expdate").toggle(!!dateStr); // Show button if there's content
			}
		});

		// Clear date events
		$("#clear-mfgdate").on("click", function() {
			$('#mfgdate')[0]._flatpickr.clear();
			$(this).hide();
		});

		$("#clear-expdate").on("click", function() {
			$('#expdate')[0]._flatpickr.clear();
			$(this).hide();
		});

		$('form').on('submit', function(e) {
			$('#mfgdate, #expdate').each(function() {
				let val = $(this).val();
				if (val) {
					let parts = val.split('-');
					let formattedDate = `${parts[2]}-${parts[1]}-${parts[0]}`; // Convert to yyyy-MM-dd
					console.log(`Formatted ${this.id}:`, formattedDate);
					$(this).val(formattedDate); // Update input value before submit
				}
			});
			// e.preventDefault(); // Remove for actual form submission
		});
	}

	$(document).ready(function() {
		setNo();
		form();
		$('#toggleButton').on("click", function() {
			let additionalFields = document.getElementById("additionalFields");
			if (additionalFields.classList.contains("d-none")) {
				additionalFields.classList.remove("d-none");
				this.textContent = "- Show Less";
			} else {
				additionalFields.classList.add("d-none");
				this.textContent = "+ Show More";
			}
		});

		FormValidation.formValidation(
			document.getElementById('FormValid'), {
				fields: {
					"Produk[deskripsi]": {
						validators: {
							notEmpty: {
								message: 'Product name is required'
							},
							stringLength: {
								min: 3,
								max: 100,
								message: 'Product name must be between 3-100 characters'
							}
						}
					},
					"Produk[price]": {
						validators: {
							notEmpty: {
								message: 'Price is required'
							},
							numeric: {
								message: 'Price must be a number'
							}
						}
					},
					"Produk[jenis]": {
						validators: {
							notEmpty: {
								message: 'Product type is required'
							}
						}
					}
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					bootstrap: new FormValidation.plugins.Bootstrap5(),
					submitButton: new FormValidation.plugins.SubmitButton(),
					defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
				}
			}
		)
	});
</script>
