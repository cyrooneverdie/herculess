<?php
//echo date("Y-m-d hh:ii:ss");
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>

<?php
$form = ActiveForm::begin(
	[
		'id' => 'FormValid',
		'method' => 'post',
		'options' => ['enctype' => 'multipart/form-data'],
		'validateOnSubmit' => true,
		'options' => [
			'data-pjax' => true
		]
	]
);
// echo "masuk";
?>
<!--begin::Form-->

<!--begin::Scroll-->
<div class="d-flex flex-column scroll-y px-5 px-lg-10" id="modal_form_contact_scroll" data-kt-scroll="false" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#modal_form_contact_header" data-kt-scroll-wrappers="#modal_form_contact_scroll" data-kt-scroll-offset="300px">
	<!--begin::Input group-->

	<!--end::Input group-->
	<!--begin::Input group-->
	<div class="row">
		<div class="mb-7 col-lg-2 col-md-2 col-sm-2">
			<!--begin::Label-->
			<label class="fw-semibold fs-6 mb-2">Code</label>
			<!--end::Label-->
			<!--begin::Input-->
			<input class="form-control form-control-solid" type="input" value="<?= $model->contact_no ?>" class="code" name="Contact[contact_no]" readonly />
			<!--end::Input-->
		</div>
		<div class="mb-7 col-lg-4 col-md-4 col-sm-4">
			<!-- begin::Label-->
			<label class="fw-semibold fs-6 mb-2">Full Name</label>
			<!--end::Label -->
			<!--begin::Input-->
			<input class="form-control" type="text" placeholder="Full Name" value="<?= $model->contact_name ?>" class="fullname" id="fullname" name="Contact[contact_name]" />
			<!--end::Input-->
		</div>
		<!--end::Input group-->
		<div class="mb-7 col-lg-6 col-md-6 col-sm-6">
			<label class="fw-semibold fs-6 mb-2" for="contactType">Contact Type:</label>
			<select id="contactType" class="form-select" data-control="select2" multiple="multiple" name="Contact[contact_typeid][]">
				<option value="contacttype.cs" <?= ($model->contact_iscustomer) ? 'selected' : '' ?>>Customer</option>
				<option value="contacttype.vn" <?= ($model->contact_isvendor) ? 'selected' : '' ?>>Vendor</option>
			</select>
		</div>
	</div>
	<div class="row">
		<!--begin::Input group-->
		<div class="mb-7 col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<!--begin::Label-->
			<label class="fw-semibold fs-6 mb-2">Email</label>
			<!--end::Label-->
			<!--begin::Input-->
			<input class="form-control mail" id="mail" type="email" placeholder="Email" value="<?= $model->contact_email1 ?? '' ?>" name="Contact[contact_email1]" />
			<!--end::Input-->
		</div>
		<!--end::Input group-->
		<!--begin::Input group-->
		<div class="mb-7 col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<!--begin::Label-->
			<label class="fw-semibold fs-6 mb-2 ">Phone</label>
			<!--end::Label-->
			<!--begin::Input-->
			<input class="form-control phone" type="tel" placeholder="Phone Number" id="phone" value="<?= $model->contact_phone1 ?? '' ?>" name="Contact[contact_phone1]" />
			<!--end::Input-->
		</div>
		<!--end::Input group-->
	</div>
	<div class="form-group row">
		<!--begin::Input group-->
		<div class="mb-10 col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<label class="form-label fs-6 fw-semibold">Gender:</label>
			<select id="gender2" class="form-select gender" data-control="select2" name="Contact[contact_gender]">
				<option value="<?= $model->gender['enumid'] ?? '' ?>" selected><?= $model->gender['enumtext_en'] ?? '' ?></option>
			</select>
		</div>

		<div class="mb-10 col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<label class="form-label fs-6 fw-semibold">Married:</label>
			<select id="married2" class="form-select married" data-control="select2" name="Contact[contact_married]">
				<option value="<?= $model->married['enumid'] ?? '' ?>" selected><?= $model->married['enumtext_en'] ?? '' ?></option>
			</select>
		</div>
		<div class="mb-10 col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<label class="fw-semibold fs-6 mb-2">Religion:</label>
			<select id="reli2" class="form-select reli" data-control="select2" name="Contact[contact_religion]">
				<option value="<?= $model->religion['enumid'] ?? '' ?>" selected><?= $model->religion['enumtext_en'] ?? '' ?></option>
			</select>
		</div>
	</div>

	<a id="toggleButton" class="btn btn-primary w-200px btn-start" style="cursor:pointer;">Detail Kontak</a>
	<!-- Alamat Kontak -->
	<div id="additionalFields" class="d-none mt-5">
		<h3 for="titleAlamat" class="text text-start mb-7 mt-5 fw-bold">Alamat Kontak</h3>
		<div class="form-group mb-7 col-md-12 col-lg-12">
			<!--begin::Label-->
			<label class="fw-semibold fs-6 mb-2">Country</label>
			<!--end::Label-->
			<!--begin::Input-->
			<select id="country2" class="form-select country" data-control="select2" name="Contact[countryid]">
				<option value="<?= $model->country['enumid'] ?? '' ?>" selected><?= $model->country['enumtext_id'] ?? '' ?></option>
			</select>
			<!--end::Input-->
		</div>
		<div class="form-group row">
			<div class="mb-7 col-md-6 col-lg-6 col-sm-6">
				<!--begin::Label-->
				<label class="fw-semibold fs-6 mb-2">State</label>
				<!--end::Label-->
				<!--begin::Input-->
				<select id="state2" class="form-select state" data-control="select2" name="Contact[stateid]">
					<option value="<?= $model->state['enumid'] ?? '' ?>" selected><?= $model->state['enumtext_en'] ?? '' ?></option>
				</select>
				<!--end::Input-->
			</div>
			<!--end::Input group-->
			<!--end::Input group-->
			<div class="form-group mb-7 col-md-6 col-lg-6 col-sm-6">
				<!--begin::Label-->
				<label class="fw-semibold fs-6 mb-2">City</label>
				<!--end::Label-->
				<!--begin::Input-->
				<select id="city2" class="form-select edu" data-control="select2" name="Contact[cityid]">
					<option value="<?= $model->city['enumid'] ?? '' ?>" selected><?= $model->city['enumtext_en'] ?? '' ?></option>
				</select>
				<!--end::Input-->
			</div>
		</div>
		<div class="form-group row">
			<div class="mb-7 col-md-6 col-sm-6 col-lg-6">
				<!--begin::Label-->
				<label class="fw-semibold fs-6 mb-2">Kabupaten</label>
				<!--end::Label-->
				<!--begin::Input-->
				<select id="dist2" class="form-select state" data-control="select2" name="Contact[districtid]">
					<option value="<?= $model->distrik['enumid'] ?? '' ?>" selected><?= $model->distrik['enumtext_en'] ?? '' ?></option>
				</select>
				<!--end::Input-->
			</div>
			<!--end::Input group-->
			<!--end::Input group-->
			<div class="form-group mb-7 col-md-6 col-sm-6 col-lg-6">
				<!--begin::Label-->
				<label class="fw-semibold fs-6 mb-2">Kelurahan</label>
				<!--end::Label-->
				<!--begin::Input-->
				<select id="subdist2" class="form-select edu" data-control="select2" name="Contact[subdistrictid]" readonly>
					<option value="<?= $model->subdistrik['enumid'] ?? '' ?>" selected><?= $model->subdistrik['enumtext_en'] ?? '' ?></option>
				</select>
				<!--end::Input-->
			</div>
		</div>
		<div class="form-group row">
			<div class="mb-7 col-lg-8 col-md-8 col-sm-8">
				<!-- begin::Label-->
				<label class="fw-semibold fs-6 mb-2">Alamat Rumah</label>
				<!--end::Label -->
				<!--begin::Input-->
				<textarea class="form-control" type="text" placeholder="Nomor Identitas" value="<?= $model->address ?>" id="address" name="Contact[address]" rows="3"></textarea>
				<!--end::Input-->
			</div>
			<div class="mb-7 col-lg-4 col-md-4 col-sm-4">
				<!-- begin::Label-->
				<label class="fw-semibold fs-6 mb-2">Kode Pos</label>
				<!--end::Label -->
				<!--begin::Input-->
				<textarea class="form-control" type="text" placeholder="Kode Pos" value="<?= $model->zip ?>" id="address" name="Contact[zip]" rows="3"></textarea>
				<!--end::Input-->
			</div>
		</div>
		<h3 for="titleLainnya" class="text text-start mb-7 mt-5 fw-bold">Detail Lainnya</h3>
		<div class="form-group row">
			<!--end::Input group-->
			<div class="form-group mb-7 col-md-4 col-sm-4 col-lg-4">
				<!--begin::Label-->
				<label class="fw-semibold fs-6 mb-2">Tipe Identitas</label>
				<!--end::Label-->
				<!--begin::Input-->
				<select id="idtype2" class="form-select idtype" data-control="select2" name="Contact[idtype]">
					<option value="<?= $model->idtype['enumid'] ?? '' ?>" selected><?= $model->idtype['enumtext_en'] ?? '' ?></option>
				</select>
				<!--end::Input-->
			</div>
			<div class="mb-7 col-lg-8 col-md-8 col-sm-8">
				<!-- begin::Label-->
				<label class="fw-semibold fs-6 mb-2">Nomor Identitas</label>
				<!--end::Label -->
				<!--begin::Input-->
				<input class="form-control" type="text" placeholder="Nomor Identitas" value="<?= $model->idnumber ?>" id="idnumber" name="Contact[idnumber]" />
				<!--end::Input-->
			</div>
		</div>
		<div class="form-group row">
			<!--end::Input group-->
			<div class="form-group mb-7 col-md-4 col-sm-4 col-lg-4">
				<!--begin::Label-->
				<label class="fw-semibold fs-6 mb-2">Jabatan Kerja </label>
				<!--end::Label-->
				<!--begin::Input-->
				<select id="job2" class="form-select job" data-control="select2" name="Contact[jobposition]">
					<option value="<?= $model->job['enumid'] ?? '' ?>" selected><?= $model->job['enumtext_en'] ?? '' ?></option>
				</select>
				<!--end::Input-->
			</div>
			<div class="mb-7 col-lg-8 col-md-8 col-sm-8">
				<!-- begin::Label-->
				<label class="fw-semibold fs-6 mb-2">Nama Perusahaan</label>
				<!--end::Label -->
				<!--begin::Input-->
				<input class="form-control" type="text" placeholder="Nama Perusahaan" value="<?= $model->jobcompany ?>" id="job" name="Contact[jobcompany]" />
				<!--end::Input-->
			</div>
		</div>
		<div class="form-group row">
			<div class="mb-7 col-md-6 col-sm-6  col-lg-6">
				<!--begin::Label-->
				<label class="fw-semibold fs-6 mb-2">Masuk Kerja</label>
				<!--end::Label-->
				<!--begin::Input-->
				<input class="form-control jobstart" placeholder="Tanggal Masuk Kerja" id="jobstart" value="<?= $model->jobstart ? Yii::$app->formatter->asDate($model->jobstart, "php:d-m-Y") : "" ?>" name="Contact[jobstart]" />
				<button type="button" id="clear-jobstart" class="btn btn-light mt-3" style="display: none;">X</button>
				<!--end::Input-->
			</div>
			<!--end::Input group-->
			<div class="mb-7 col-md-6 col-sm-6  col-lg-6">
				<!--begin::Label-->
				<label class="fw-semibold fs-6 mb-2">Keluar Kerja</label>
				<!--end::Label-->
				<!--begin::Input-->
				<input class="form-control jobend" placeholder="Tanggal Keluar Kerja" id="jobend" value="<?= $model->jobend ? Yii::$app->formatter->asDate($model->jobend, "php:d-m-Y") : "" ?>" name="Contact[jobend]" />
				<button type="button" id="clear-jobend" class="btn btn-light mt-3" style="display: none;">X</button>
				<!--end::Input-->
			</div>
			<!--end::Input group-->
		</div>
		<div class="form-group row">
			<div class="mb-7 col-md-6 col-sm-6  col-lg-6">
				<!--begin::Label-->
				<label class="fw-semibold fs-6 mb-2">Birth</label>
				<!--end::Label-->
				<!--begin::Input-->
				<input class="form-control bod" placeholder="Tanggal Lahir" id="bod" value="<?= $model->contact_bod ? Yii::$app->formatter->asDate($model->contact_bod, "php:d-m-Y") : "" ?>" name="Contact[contact_bod]" />
				<button type="button" id="clear-bod" class="btn btn-light mt-3" style="display: none;">X</button>
				<!--end::Input-->
			</div>
			<!--end::Input group-->
			<!--end::Input group-->
			<div class="form-group mb-7 col-md-6 col-sm-6 col-lg-6">
				<!--begin::Label-->
				<label class="fw-semibold fs-6 mb-2">Education</label>
				<!--end::Label-->
				<!--begin::Input-->
				<select id="edu2" class="form-select edu" data-control="select2" name="Contact[contact_education]">
					<option value="<?= $model->education['enumid'] ?? '' ?>" selected><?= $model->education['enumtext_en'] ?? '' ?></option>
				</select>
				<!--end::Input-->
			</div>
		</div>
	</div>
	<!-- End Alamat Kontak -->
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
		var ob1 = document.querySelector("input[name='Contact[contact_no]']");

		if (ob1 && ob1.value.trim() === "") {
			fetch("<?= Url::to(['contact/getno'], true); ?>", {
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

	function form() {
		//untuk form
		console.log(typeof jQuery.fn.yiiActiveForm);
		$("#job2").select2({
			ajax: {
				url: "<?= \yii\helpers\Url::to(['contact/joblist']) ?>",
				type: "GET",
				dataType: "json",
				delay: 250,
				data: function(params) {
					return {
						q: params.term, // Kirim input user ke backend
					};
				},
				processResults: function(data) {
					return {
						results: data.results
					};
				},
				cache: true
			},
			placeholder: "Tipe Identitas",
			allowClear: true,
		});
		$("#job2").on("change", function() {
			console.log($(this).val()); // Pastikan nilai yang dipilih dikirim sebagai array
		});

		$("#idtype2").select2({
			ajax: {
				url: "<?= \yii\helpers\Url::to(['contact/idtypelist']) ?>",
				type: "GET",
				dataType: "json",
				delay: 250,
				data: function(params) {
					return {
						q: params.term, // Kirim input user ke backend
					};
				},
				processResults: function(data) {
					return {
						results: data.results
					};
				},
				cache: true
			},
			placeholder: "Tipe Identitas",
			allowClear: true,
		});
		$("#idtype2").on("change", function() {
			console.log($(this).val()); // Pastikan nilai yang dipilih dikirim sebagai array
		});

		$("#contactType").select2({
			ajax: {
				url: "<?= \yii\helpers\Url::to(['contact/typelist']) ?>",
				type: "GET",
				dataType: "json",
				delay: 250,
				data: function(params) {
					return {
						q: params.term, // Kirim input user ke backend
					};
				},
				processResults: function(data) {
					return {
						results: data.results
					};
				},
				cache: true
			},
			placeholder: "Tipe Contact",
			allowClear: true,
			multiple: true, // Pastikan multiple selection diaktifkan
		});
		$("#contactType").on("change", function() {
			console.log($(this).val()); // Pastikan nilai yang dipilih dikirim sebagai array
		});

		$("#gender2").select2({
			ajax: {
				url: "<?= \yii\helpers\Url::to(['contact/genderlist']) ?>",
				type: "GET",
				dataType: "json",
				delay: 250,
				data: function(params) {
					return {
						q: params.term, // Kirim input user ke backend
					};
				},
				processResults: function(data) {
					return {
						results: data.results
					};
				},
				cache: true
			},
			placeholder: "Pilih Gender",
			allowClear: true
		});

		$("#married2").select2({
			ajax: {
				url: "<?= \yii\helpers\Url::to(['contact/marriedlist']) ?>",
				type: "GET",
				dataType: "json",
				delay: 250,
				data: function(params) {
					return {
						q: params.term, // Kirim input user ke backend
					};
				},
				processResults: function(data) {
					return {
						results: data.results
					};
				},
				cache: true
			},
			placeholder: "Status Anda",
			allowClear: true
		});

		$("#reli2").select2({
			ajax: {
				url: "<?= \yii\helpers\Url::to(['contact/relilist']) ?>",
				type: "GET",
				dataType: "json",
				delay: 250,
				data: function(params) {
					return {
						q: params.term, // Kirim input user ke backend
					};
				},
				processResults: function(data) {
					return {
						results: data.results
					};
				},
				cache: true
			},
			placeholder: "Status Anda",
			allowClear: true
		});

		$("#edu2").select2({
			ajax: {
				url: "<?= \yii\helpers\Url::to(['contact/edulist']) ?>",
				type: "GET",
				dataType: "json",
				delay: 250,
				data: function(params) {
					return {
						q: params.term, // Kirim input user ke backend
					};
				},
				processResults: function(data) {
					return {
						results: data.results
					};
				},
				cache: true
			},
			placeholder: "Status Anda",
			allowClear: true
		});
		let selectedCountry = null;
		let selectedState = null;
		let selectedCity = null;
		let selectedDistrict = null;

		// INISIALISASI SEMUA SELECT2 SEKALI SAJA
		$("#state2").select2({
			placeholder: "Pilih Provinsi",
			allowClear: true
		});

		$("#city2").select2({
			placeholder: "Pilih Kota",
			allowClear: true
		});

		$("#dist2").select2({
			placeholder: "Pilih Kabupaten",
			allowClear: true
		});

		$("#subdist2").select2({
			disabled: true,
			placeholder: "Belum Tersedia",
			allowClear: true
		});

		// KETIKA COUNTRY DIPILIH
		$("#country2").select2({
			ajax: {
				url: "<?= \yii\helpers\Url::to(['contact/countrylist']) ?>",
				type: "GET",
				dataType: "json",
				delay: 250,
				data: function(params) {
					return {
						q: params.term
					};
				},
				processResults: function(data) {
					return {
						results: data.results
					};
				},
				cache: true
			},
			placeholder: "Pilih Negara",
			allowClear: true
		}).on("select2:select", function(e) {
			selectedCountry = e.params.data.id;
			$("#state2").val(null).trigger("change");
			$("#city2").val(null).trigger("change");
			$("#dist2").val(null).trigger("change");
			$("#subdist2").val(null).trigger("change");

			// UBAH AJAX UNTUK STATE
			$("#state2").select2({
				ajax: {
					url: "<?= \yii\helpers\Url::to(['contact/statelist']) ?>",
					type: "GET",
					dataType: "json",
					delay: 250,
					data: function(params) {
						return {
							q: params.term,
							refid: selectedCountry
						};
					},
					processResults: function(data) {
						return {
							results: data.results
						};
					},
					cache: true
				},
				placeholder: "Pilih Provinsi",
				allowClear: true
			});
		});

		// KETIKA STATE DIPILIH
		$("#state2").on("change", function() {
			selectedState = $(this).val();
			$("#city2").val(null).trigger("change");
			$("#dist2").val(null).trigger("change");
			$("#subdist2").val(null).trigger("change");

			// UBAH AJAX UNTUK CITY
			$("#city2").select2({
				ajax: {
					url: "<?= \yii\helpers\Url::to(['contact/citylist']) ?>",
					type: "GET",
					dataType: "json",
					delay: 250,
					data: function(params) {
						return {
							q: params.term,
							refid: selectedState
						};
					},
					processResults: function(data) {
						return {
							results: data.results
						};
					},
					cache: true
				},
				placeholder: "Pilih Kota",
				allowClear: true
			});
		});

		// KETIKA CITY DIPILIH
		$("#city2").on("change", function() {
			selectedCity = $(this).val();
			$("#dist2").val(null).trigger("change");
			$("#subdist2").val(null).trigger("change");

			// UBAH AJAX UNTUK DISTRICT
			$("#dist2").select2({
				ajax: {
					url: "<?= \yii\helpers\Url::to(['contact/distriklist']) ?>",
					type: "GET",
					dataType: "json",
					delay: 250,
					data: function(params) {
						return {
							q: params.term,
							refid: selectedCity
						};
					},
					processResults: function(data) {
						return {
							results: data.results
						};
					},
					cache: true
				},
				placeholder: "Pilih Kabupaten",
				allowClear: true
			});
		});

		// KETIKA DISTRICT DIPILIH
		$("#dist2").on("change", function() {
			selectedDistrict = $(this).val();
			$("#subdist2").val(null).trigger("change");

			// UBAH AJAX UNTUK SUBDISTRICT
			$("#subdist2").select2({
				disabled: true,
				ajax: {
					url: "<?= \yii\helpers\Url::to(['contact/subdistriklist']) ?>",
					type: "GET",
					dataType: "json",
					delay: 250,
					data: function(params) {
						return {
							q: params.term,
							refid: selectedDistrict
						};
					},
					processResults: function(data) {
						return {
							results: data.results
						};
					},
					cache: true
				},
				placeholder: "Belum Tersedia",
				allowClear: true
			});
		});

		flatpickr($('#jobstart')[0], {
			allowClear: true,
			enableTime: false,
			dateFormat: "d-m-Y",
			placeholder: "Tanggal Masuk Kerja",
			onChange: function(selectedDates, dateStr, instance) {
				$("#clear-jobstart").toggle(!!dateStr); // Tampilkan tombol kalau ada isi
			}
		});

		flatpickr($('#jobend')[0], {
			allowClear: true,
			enableTime: false,
			dateFormat: "d-m-Y",
			placeholder: "Tanggal Keluar Kerja",
			onChange: function(selectedDates, dateStr, instance) {
				$("#clear-jobend").toggle(!!dateStr); // Tampilkan tombol kalau ada isi
			}
		});

		flatpickr($('#bod')[0], {
			allowClear: true,
			enableTime: false,
			dateFormat: "d-m-Y",
			placeholder: "Tanggal Lahir",
			onChange: function(selectedDates, dateStr, instance) {
				$("#clear-bod").toggle(!!dateStr); // Tampilkan tombol kalau ada isi
			}
		});

		// Event untuk clear
		$("#clear-jobstart").on("click", function() {
			$('#jobstart')[0]._flatpickr.clear();
			$(this).hide();
		});

		$("#clear-jobend").on("click", function() {
			$('#jobend')[0]._flatpickr.clear();
			$(this).hide();
		});

		$("#clear-bod").on("click", function() {
			$('#bod')[0]._flatpickr.clear();
			$(this).hide();
		});

		$('form').on('submit', function(e) {
			$('#bod, #jobstart, #jobend').each(function() {
				let val = $(this).val();
				if (val) {
					let parts = val.split('-');
					let formattedDate = `${parts[2]}-${parts[1]}-${parts[0]}`; // Ubah ke yyyy-MM-dd
					console.log(`Formatted ${this.id}:`, formattedDate); // Debug hasil format
					$(this).val(formattedDate); // Ubah nilai input sebelum submit
				}
			});
			e.preventDefault(); // Jangan dipakai kalau mau kirim form
		});
	}

	$(document).ready(function() {
		setNo();
		form();
		$(document).on("click", "#toggleButton", function() {
			let additionalFields = document.getElementById("additionalFields");
			if (additionalFields.classList.contains("d-none")) {
				additionalFields.classList.remove("d-none");
				this.textContent = "Sembunyikan";
			} else {
				additionalFields.classList.add("d-none");
				this.textContent = "Detail Kontak";
			}
		});


		FormValidation.formValidation(
			document.getElementById('FormValid'), {
				fields: {
					"Contact[contact_name]": {
						validators: {
							notEmpty: {
								message: 'Masukin Namanya lah, kocak'
							},
							stringLength: {
								min: 3,
								max: 20,
								message: 'Mana ada orang namanya cuma 2' // Harus antara 3-20 karakter
							},
							regexp: {
								regexp: /^[a-zA-Z0-9_ ]+$/, // Perbolehkan spasi
								message: 'Username can only contain letters, numbers, and underscores' // Hanya huruf, angka, dan underscore
							}
						}
					},
					"Contact[contact_typeid][]": {
						validators: {
							notEmpty: {
								message: 'Minimal Milih Satu lah, Kocak! Ni orang apa? Hantu?'
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