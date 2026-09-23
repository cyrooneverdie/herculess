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
		'action' => $model->isNewRecord
			? Url::to(['company/create'])
			: Url::to(['company/update', 'id' => $model->companyid]),
		'options' => [
			'enctype' => 'multipart/form-data',
			'multiple' => true,
		],
		'validateOnSubmit' => true,
		'enableAjaxValidation' => false,
		'enableClientScript' => $isajax ? false : true,
	]
);
?>

<!--begin::Form-->
<!--begin::Scroll-->
<div class="d-flex flex-column scroll-y px-5 px-lg-10" id="modal_form_contact_scroll" data-kt-scroll="false"
	data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
	data-kt-scroll-dependencies="#modal_form_contact_header" data-kt-scroll-wrappers="#modal_form_contact_scroll"
	data-kt-scroll-offset="300px">
	<!--begin::Input group-->
	<div class="row">
		<div class="mb-7 col-lg-4 col-md-4 col-sm-12">
			<?= $form->field($model, 'nama_perusahaan', [
				'errorOptions' => ['class' => 'text-danger mt-3'],
			])->textInput([
						'placeholder' => $model->getAttributeLabel('nama_perusahaan'),
						'id' => 'nama_perusahaan'
					]); ?>
		</div>
		<div class="mb-7 col-lg-4 col-md-4 col-sm-12">
			<?= $form->field($model, 'email', [
				'errorOptions' => ['class' => 'text-danger mt-3'],
			])->textInput([
						'type' => 'email',
						'placeholder' => $model->getAttributeLabel('email'),
						'class' => 'form-control',
						'id' => 'email'
					]);
			?>
		</div>
		<div class="mb-7 col-lg-4 col-md-4 col-sm-12">
			<?= $form->field($model, 'nomor_telepon', [
				'errorOptions' => ['class' => 'text-danger mt-3'],
			])->textInput(
					[
						'type' => 'tel',
						'class' => 'form-control',
						'id' => 'notel',
						'placeholder' => $model->getAttributeLabel('nomor_telepon'),
					]
				);
			?>
		</div>
	</div>

	<div class="form-group row">
		<div class="col-lg-12 col-md-12 col-sm-12 mb-7">
			<label class="fw-semibold fs-6 mb-5 d-flex justify-content-center">Logo
			</label>

			<!--begin::Image input-->
			<div class="d-flex justify-content-center">
				<div class="image-input image-input-outline" data-kt-image-input="true">
					<!--begin::Preview existing avatar-->
					<div class="image-input-wrapper w-150px h-150px"
						style="background-image: url('<?= !$model->isNewRecord && !empty($model->company_photo) ?
							Yii::getAlias('@web') . $model->company_photo : Yii::getAlias('@web') . "/assets/media/logos/rbg.png" ?>');">
					</div>
					<!--end::Preview existing avatar-->

					<!--begin::Edit-->
					<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
						data-kt-image-input-action="change" data-bs-toggle="tooltip"
						title="<?= Yii::$app->lang->t('extra', 'extra48') ?>">
						<i class="fa fa-cloud-upload-alt fs-7"></i>
						<!--begin::Inputs-->
						<input type="file" name="logo" id="logo-input" accept=".png, .jpg, .jpeg" />
						<input type="hidden" name="logo_remove" />
						<!--end::Inputs-->
					</label>
					<!--end::Edit-->

					<!--begin::Cancel-->
					<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
						data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel">
						<i class="bi bi-x fs-2"></i>
					</span>
					<!--end::Cancel-->

					<!--begin::Remove-->
					<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
						data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove">
						<i class="bi bi-x fs-2"></i>
					</span>
					<!--end::Remove-->
				</div>
			</div>
			<div class="form-text text-center"><?= Yii::$app->lang->t('extra', 'extra49') ?></div>
			<!--end::Image input-->
		</div>
	</div>
	<!--end::Scroll-->
	<!--begin::Actions-->
	<div class="text-end pt-10">
		<?php if ($isajax): ?>
			<button type="button" class="btn btn-light me-3" data-bs-dismiss="modal"><?= Yii::$app->lang->t('extra', 'extra17') ?></button>
		<?php else: ?>
			<a href="<?= Url::to(['company/index']) ?>" class="btn btn-light me-3" id="kembali"><?= Yii::$app->lang->t('extra', 'extra17') ?></a>
		<?php endif; ?>

		<?= Html::submitButton($model->isNewRecord ? Yii::$app->lang->t('extra', 'extra16') : Yii::$app->lang->t('extra', 'extra16'), ['id' => 'btnsubmit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>
	<!--end::Actions-->

	<!--end::Form-->
	<?php ActiveForm::end(); ?>

	<script>
		function initSelect2(elementID, url, placeholder) {
			if (!$(elementID).data('select2')) {
				$(elementID).select2({
					ajax: {
						url: url,
						type: "GET",
						dataType: "json",
						delay: 250,
						data: params => ({
							q: params.term
						}),
						processResults: data => ({
							results: data.results
						}),
						cache: true
					},
					placeholder: placeholder,
					allowClear: true,
					dropdownParent: $("#FormValid")
				});
			}
		}

		function select() {
			if ($("#jumlahkaryawan").length && !$("#jumlahkaryawan").data('select2')) {
				initSelect2("#jumlahkaryawan", "<?= \yii\helpers\Url::to(['company/jumlahkaryawanlist']) ?>", <?= json_encode(Yii::$app->lang->t('kasform', 'kasform2')) ?>);
			}
		}

		function form() { }

		function setupSubmission() {
			$('#FormValid').off('submit').on('submit', function (e) {
				e.preventDefault();

				if (window.isSubmitting) {
					console.log('Form is already being submitted, ignoring additional submit');
					return false;
				}

				let isValid = true;

				if ($('#email').val().trim() === '') {
					$('#email').addClass('is-invalid');
					isValid = false;
				} else {
					$('#email').removeClass('is-invalid');
				}

				if ($('#notel').val().trim() === '') {
					$('#notel').addClass('is-invalid');
					isValid = false;
				} else {
					$('#notel').removeClass('is-invalid');
				}

				if (isValid) {
					window.isSubmitting = true;
					console.log('Submitting form...');
					submitProductForm();
				}

				return false;
			});
		}

		function submitProductForm() {
			$('#btnsubmit').prop('disabled', true)
				.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');

			const formData = new FormData($('#FormValid')[0]);

			$.ajax({
				url: $('#FormValid').attr('action'),
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function (response) {
					window.isSubmitting = false;

					$('#btnsubmit').prop('disabled', false).html('Submit');

					if (response && response.success) {
						handleSuccessResponse(response);
					} else {
						showAlert(
							"Error!",
							(response && response.pesan) || "There was an error saving the data.",
							"error"
						);
					}
				},
				error: function (xhr, status, error) {
					console.error("AJAX Error:", status, error);

					window.isSubmitting = false;
					$('#btnsubmit').prop('disabled', false).html('Submit');

					showAlert(
						"Error!",
						"There was an error processing your request.",
						"error"
					);
				}
			});
		}

		<?php if ($isajax): ?>
		
		function handleSuccessResponse(response) {
			$('#modal_form_company').modal('hide');

			if ($.fn.DataTable && $.fn.DataTable.isDataTable('#datatable')) {
				$('#datatable').DataTable().ajax.reload(null, false);
			}

			if (typeof updateCompanyList === 'function') {
				updateCompanyList();
			}
			if (typeof reloadCompanyDropdown === 'function') {
				reloadCompanyDropdown();
			}

			setTimeout(function () {
				Swal.fire({
					icon: "success",
					title: "<?= Yii::$app->lang->t('extra', 'extra62') ?>",
					text: response.pesan || "<?= Yii::$app->lang->t('extra', 'extra95') ?>"
				});
			}, 300);
		}
		<?php else: ?>
		

		function handleSuccessResponse(response) {
			Swal.fire({
				title: '<?= Yii::$app->lang->t('extra', 'extra62') ?>',
				text: "<?= Yii::$app->lang->t('extra', 'extra95') ?>",
				icon: "success",
				timer: 2000,
				showConfirmButton: true,
				confirmButtonText: "OK"
			}).then(() => {
				var url = "<?= Url::to(['company/index']) ?>";
				history.pushState(null, '', url);

				$.ajax({
					url: url,
					type: 'GET',
					cache: false,
					success: function (data) {
						reloadCompanyDropdown();
						$('.app-container.utama').html(data);
					},
					error: function (xhr, status, error) {
						console.error(xhr.responseText);
						alert("Gagal memuat halaman: " + xhr.status + " (" + error + ")");
					}
				});
			});
		}
		<?php endif; ?>

		function showAlert(title, text, icon) {
			Swal.fire({ icon: icon, title: title, text: text });
		}

		function reloadCompanyDropdown() {
			$.ajax({
				url: "<?= Yii::$app->urlManager->createUrl(['company/getcompany']) ?>",
				type: "GET",
				success: function (response) {
					if (response.success) {
						$("#companyDrop").html(response.html).addClass('mb-3 me-n1');
					}
				},
				error: function (xhr, status, error) {
					console.error("Gagal memperbarui dropdown:", error);
				}
			});
		}

		function toggleButton() {
			$('#toggleButton').on("click", function () {
				let additionalFields = $("#additionalFields");
				if (additionalFields.hasClass("d-none")) additionalFields.removeClass("d-none").hide();
				additionalFields.slideToggle(650);
				$(this).text(additionalFields.is(":hidden") ? "+ Show More" : "- Show Less");
			});
		}

		$(document).ready(function () {
			form();
			select();
			setupSubmission();
			toggleButton();
			$('input').attr('autocomplete', 'off');
			$('.field-nama_lengkap , .field-nama_perusahaan, .field-jumlahkaryawan, .field-jabatanperusahaan, .field-email, .field-notel')
				.removeClass('required');

			if (typeof KTImageInput !== 'undefined') {
				KTImageInput.createInstances();
			}

			<?php if (!$isajax): ?>
			$('#kembali').on('click', function (e) {
				e.preventDefault();
				var url = $(this).attr('href');

				history.pushState(null, '', url);

				$.ajax({
					url: url,
					type: 'GET',
					cache: false,
					success: function (data) {
						$('.app-container.utama').html(data);
					},
					error: function (xhr, status, error) {
						console.error(xhr.responseText);
						alert("Gagal memuat halaman: " + xhr.status + " (" + error + ")");
					}
				});
			});
			<?php endif; ?>
		});
	</script>