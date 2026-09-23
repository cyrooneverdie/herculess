<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin(
	[
		'id' => 'FormValid',
		'method' => 'post',
		'options' => [
			'enctype' => 'multipart/form-data',
			'multiple' => true,
		],
		'validateOnSubmit' => true,
	]
);

$kastype = Yii::$app->request->get('kastype', ''); // Ambil langsung dari parameter URL
?>
<!--begin::Scroll-->
<div class="d-flex flex-column scroll-y px-5 px-lg-10" id="modal_form_kasbank_scroll" data-kt-scroll="false" data-kt-scroll-activate="false" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#modal_form_kasbank_header" data-kt-scroll-wrappers="#modal_form_kasbank_scroll" data-kt-scroll-offset="300px">
	<!--begin::Input group-->
	<!--end::Input group-->
	<!--begin::Input group-->
	<div class="row">
		<div class="mb-7 col-lg-2 col-md-12 col-sm-12">
			<?= $form->field($model, 'kasnomor')->textInput(['placeholder' => 'no',  'name' => 'Kas[kasnomor]', 'readOnly' => true]); ?>
		</div>
		<div class="mb-7 col-md-5 col-sm-5 col-lg-5">
			<!--begin::Label-->
			<?= $form->field($model, 'tgladd')->textInput(['placeholder' => 'Tanggal Kas Dibuat', 'id' => 'tgladd']); ?>
			<button type="button" id="clear-tgladd" class="btn btn-light mt-3 d-none">Cancel</button>
			<!--end::Input-->
		</div>
		<div class="mb-7 col-md-5 col-sm-5 col-lg-5">
			<!--begin::Label-->
			<?= $form->field($model, 'transferdate')->textInput(['placeholder' => Yii::$app->lang->t('kasbackend','transferdate'), 'id' => 'transferdate']); ?>
			<button type="button" id="clear-transferdate" class="btn btn-light mt-3" style="display: none;">Cancel</button>
			<!--end::Input-->
		</div>
	</div>
	<div class="form-group">
		<div class="mb-7 col-lg-12 col-md-12 col-sm-12">

			<?= $form->field($model, 'catatan', [
				'errorOptions' => ['class' => 'text-danger mt-3'],
			])->textArea([
				'placeholder' => Yii::$app->lang->t('kasbackend','catatan'),
				// 'class' => 'form-control w-900px',
				'id' => 'catatan',
				'required' => true,
			]);
			?>
		</div>
	</div>
	<div class="form-group row">
		<div class="mb-7 col-lg-6 col-md-6 col-sm-6">
			<?= $form->field($model, 'nomorrekening', [])->textInput([
				'placeholder' => Yii::$app->lang->t('kasbackend','nomorrekening'),
				'class' => 'form-control',
				'required' => true,
			]);
			?>
		</div>
		<div class="mb-7 col-lg-6 col-md-6 col-sm-6">
			<?= $form->field($model, 'jenis')->dropDownList(
				[],
				[
					'class' => 'form-select',
					'id' => 'jenis',
					'data-control' => 'select2',
					'required' => true,
				]
			);
			?>
		</div>
	</div>
	<div id="kt_repeater_1">
		<div data-repeater-list="kt_docs_repeater_advanced">
			<?php if (!empty($kasdetails)): ?>
				<?php foreach ($kasdetails as $index => $detail): ?>
					<div data-repeater-item class="form-group row align-items-center px-5 py-6">
						<div class="col-md-1 mb-5">
							<label class="form-label">No</label>
							<input type="text" class="form-control item-number text-center" name="nomor" value="<?= $index + 1 ?>" readonly />
						</div>
						<div class="col-md-4">
							<label class="form-label"><?= Yii::$app->lang->t('kasbackend', 'value') ?></label>
							<div class="input-group">
								<span class="input-group-text bg-primary text-white">Rp</span>
								<input type="text" class="form-control format-harga"
									name="kt_docs_repeater_advanced[<?= $index ?>][harga]"
									value="<?= number_format($detail->value, 0, ',', '.') ?>" />
							</div>
						</div>
						<div class="col-md-3">
							<a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-8">
								<i class="la la-trash-o"></i> Hapus
							</a>
						</div>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				<!-- Template kosong untuk data baru -->
				<div data-repeater-item class="form-group row align-items-center">
					<div class="col-md-1">
						<label class="form-label">No</label>
						<input type="text" class="form-control item-number text-center" name="nomor" readonly />
					</div>
					<div class="col-md-4">
						<label class="form-label"><?= Yii::$app->lang->t('kasbackend', 'value') ?></label>
						<div class="input-group">
							<span class="input-group-text bg-primary text-white">Rp</span>
							<input type="text" class="form-control format-harga"
								name="kt_docs_repeater_advanced[0][harga]" />
						</div>
					</div>
					<div class="col-md-3">
						<a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-8">
							<i class="la la-trash-o"></i> Hapus
						</a>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<button type="button" data-repeater-create class="btn btn-sm btn-light-primary mt-3">
			<i class="la la-plus"></i> <?= Yii::$app->lang->t('kasbackend', 'newline') ?>
		</button>
	</div>

	<div class="form-group mt-3">
		<label class="form-label fw-bold"><?= Yii::$app->lang->t('kasbackend', 'total') ?></label>
		<div class="input-group">
			<span class="input-group-text currency-symbol bg-primary text-white">Rp</span>
			<input type="text" id="total-penerimaan" class="form-control text-end" value="0" readonly />
		</div>
	</div>
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

<script>
	var kastype = <?= json_encode($kastype) ?>;
	var url = "<?= Url::to(['kas/getno'], true); ?>?kastype=" + (kastype === "KK" ? "KK" : "KM");

	function setNo() {
		var ob1 = document.querySelector("input[name='Kas[kasnomor]']"); // Perbaiki name-nya

		if (ob1 && ob1.value.trim() === "") {
			fetch(url, {
					method: "POST",
					headers: {
						"X-CSRF-Token": "<?= Yii::$app->request->getCsrfToken() ?>"
					}
				})
				.then(response => response.json())
				.then(data => {
					ob1.value = data.no; // Set nomor yang didapat dari backend
				})
				.catch(error => console.error("Error fetching number:", error));
		}
	}

	function repeater() {
		var repeater = $('#kt_repeater_1').repeater({
			initEmpty: false,
			defaultValues: {
				'text-input': ''
			},
			show: function() {
				$(this).slideDown();
				updateItemNumbers();
				formatHarga();
				hitungTotalHarga(); // Hitung total setelah item ditambahkan
			},
			hide: function(deleteElement) {
				$(this).slideUp(deleteElement);
				setTimeout(updateItemNumbers, hitungTotalHarga, 300); // Beri jeda agar item benar-benar terhapus sebelum update nomor
			}
		});

		function formatNumber(number) {
			return Number(number.replace(/\D/g, '')).toLocaleString('id-ID');
		}

		function updateItemNumbers() {
			$('#kt_repeater_1 [data-repeater-item]').each(function(index) {
				$(this).find('.item-number').val(index + 1); // Nomor urut mulai dari 1
			});
		}

		function formatHarga() {
			$('.format-harga').off('input').on('input', function() {
				let value = $(this).val();
				$(this).val(formatNumber(value));
			});
		}

		function hitungTotalHarga() {
			let total = 0;

			$('.format-harga').each(function() {
				let angka = $(this).val().replace(/\D/g, ''); // Ambil angka tanpa format
				total += angka ? parseInt(angka) : 0;
			});

			$('.total-harga').val(formatNumber(total.toString())); // Set total dengan format
		}
		// Panggil fungsi format harga untuk elemen yang sudah ada
		formatHarga();
		hitungTotalHarga();
		updateItemNumbers();
	}

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
		if ($("#jenis").length && !$("#jenis").data('select2')) {
			initSelect2("#jenis", "<?= \yii\helpers\Url::to(['contact/joblist']) ?>", "Pilih Posisi Pekerjaan");
		}
	}

	function form() {

		flatpickr(`#tgladd`, {
			allowClear: true,
			dropdownParent: $("#FormValid"),
			enableTime: true,
			dateFormat: "d-m-Y H:i:s",
			defaultDate: new Date(), // Menggunakan waktu sekarang
			time_24hr: true // Opsional, biar pakai format 24 jam
		});

		flatpickr("#transferdate", {
			allowClear: true,
			dropdownParent: $("#FormValid"),
			enableTime: false,
			dateFormat: "d-m-Y",
			onChange: function(selectedDates, dateStr, instance) {
				if (dateStr) {
					$("#clear-transferdate").show(); // Tampilkan tombol jika ada tanggal yang dipilih
				} else {
					$("#clear-transferdate").hide(); // Sembunyikan tombol jika kosong
				}
			}
		});

		$("#clear-transferdate").on("click", function() {
			$("#transferdate")[0]._flatpickr.clear();
			$(this).hide();
		});
	}

	function setupSubmission() {
		// Handle form submission
		$('#btnsubmit').off('click').on('click', function(e) {
			e.preventDefault();

			if (!window.isSubmitting) {
				$('#FormValid').submit();
			}

			return false;
		});

		$('#FormValid').off('submit').on('submit', function(e) {
			e.preventDefault();

			if (window.isSubmitting) {
				console.log('Form is already being submitted, ignoring additional submit');
				return false;
			}

			// Validate form
			let isValid = true;

			if (isValid) {
				window.isSubmitting = true;
				submitProductForm();
			}

			return false;
		});
	}

	function submitProductForm() {
		// Disable button and show loading state
		$('#btnsubmit').prop('disabled', true)
			.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');

		// Prepare form data
		const formData = new FormData($('#FormValid')[0]);

		// Send AJAX request
		$.ajax({
			url: $('#FormValid').attr('action'),
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: function(response) {
				// Reset flags and button
				window.isSubmitting = false;
				$('#btnsubmit').prop('disabled', false).html('Submit');

				// Parse response if it's a string
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

				// Handle JSON response
				if (response && response.success) {
					handleSuccessResponse();
				} else {

				}
			},
			error: function(xhr, status, error) {
				// Reset flags and button
				window.isSubmitting = false;
				$('#btnsubmit').prop('disabled', false).html('Submit');
				console.error("AJAX Error:", status, error);
			}
		});
	}

	function handleSuccessResponse() {
		Swal.fire({
			title: 'Berhasil',
			text: "Data Berhasil Di Tambah",
			icon: "success",
			timer: 2000,
			showConfirmButton: true,
			confirmButtonText: "OK"
		});

		// Close the modal
		$('#modal_form_kasbank').modal('hide');

		// Reload DataTable or page
		if ($.fn.DataTable.isDataTable('#datatable')) {
			$('#datatable').DataTable().ajax.reload();
		} else {
			setTimeout(function() {
				window.location.reload();
			}, 2000);
		}
	}

	$(document).ready(function() {
		// Fungsi untuk menghitung total penerimaan
		function hitungTotal() {
			let total = 0;
			$(".format-harga").each(function() {
				let nilai = $(this).val().replace(/\./g, ""); // Hilangkan titik (format angka)
				total += parseFloat(nilai) || 0; // Tambahkan ke total
			});

			// Format angka dan update ke input total
			$("#total-penerimaan").val(total.toLocaleString("id-ID"));
		}

		// Saat harga diinput atau berubah, hitung ulang total
		$(document).on("input", ".format-harga", function() {
			hitungTotal();
		});

		// Saat menambah item, hitung ulang total
		$(document).on("click", "[data-repeater-create]", function() {
			setTimeout(hitungTotal, 100); // Delay agar elemen baru dikenali
		});

		// Saat menghapus item, hitung ulang total setelah elemen dihapus
		$(document).on("click", "[data-repeater-delete]", function() {
			setTimeout(hitungTotal, 100);
		});

		// Hitung ulang saat halaman pertama kali dimuat
		hitungTotal();
		setNo();
		form();
		repeater();
		select();
		setupSubmission();
		$('input').attr('autocomplete', 'off');
		$('.field-name , .field-contactType').removeClass('required');
	});
</script>