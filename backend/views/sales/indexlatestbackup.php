<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
// use karnbrockgmbh\modal\Modal;	
?>
<!--begin::Content container-->
<!--begin::Card-->
<!-- <div class="card"> -->
<!--begin::Card header-->
<!-- <div class="card-header border-0 pt-6"> -->
<!--begin::Card title-->
<!-- <div class="card-title"> -->
<!--begin::Search-->
<div class="d-flex align-items-center position-relative my-1">
	<i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"></i>
	<form method="get" action="index" style="width: 100%;" id="search">
		<input
			data-kt-docs-table-filter="search"
			type="text"
			name="search"
			class="form-control form-control-solid w-250px ps-13"
			placeholder="<?= Yii::$app->lang->t('back_home', 'chat58') ?>" />
	</form>
</div>
<!--end::Search-->
<!-- </div> -->
<!--begin::Card title-->
<!--begin::Card toolbar-->
<!-- <div class="card-toolbar"> -->
<!--begin::Toolbar-->
<div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
	<!--begin::Filter-->
	<button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="left-start" data-kt-menu-id="filter-menu">
		<i class="ki-duotone ki-filter fs-2">
			<span class="path1"></span>
			<span class="path2"></span>
		</i>Filter</button>
	<!--begin::Menu 1-->
	<div class="menu menu-sub menu-sub-dropdown w-sm-500px w-md-600px" data-kt-menu="true" data-kt-menu-id="filter-menu">
		<!--begin::Header-->
		<div class="px-7 py-5">
			<div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
		</div>
		<!--end::Header-->
		<!--begin::Separator-->
		<div class="separator border-gray-200"></div>
		<!--end::Separator-->
		<!--begin::Content-->
		<div class="px-7 py-5" data-kt-user-table-filter="form">
			<form id="filterForm" method="get" action="index">
				<!--begin::Input group-->
				<div class="row">
					<div class="md-10">
						<label class="fw-semibold fs-6 mb-2 mt-3" for="dateFilter">Date Range:</label>
						<input name="datefilter" class="form-control form-control-solid" style="cursor:pointer;" id="datefilter" placeholder="Pick date range">
					</div>
					<div class="col-md-4 col-lg-4">
						<label class="fw-semibold fs-6 mb-2" for="genderFilter">Gender</label>
						<select id="select2" class="form-select gender" data-control="select2" name="gender">
							<option selected><?= $model->gender['enumtext_en'] ?? '' ?></option>
						</select>
					</div>
					<div class="col-md-4 col-lg-4">
						<label class="fw-semibold fs-6 mb-2" for="marriedFilter">Status</label>
						<select id="marriedselect2" class="form-select married" data-control="select2" name="married">
							<option selected><?= $model->married['enumtext_en'] ?? '' ?></option>
						</select>
					</div>
					<div class="col-md-4 col-lg-4">
						<label class="fw-semibold fs-6 mb-2" for="reliFilter">Religion:</label>
						<select id="reliselect2" class="form-select reli" data-control="select2" name="reli">
							<option selected><?= $model->religion['enumtext_en'] ?? '' ?></option>
						</select>
					</div>
					<div class="form-group row mt-5">
						<div class="checkbox-inline d-flex">
							<label class="checkbox checkbox-success checkbox-rounded w-200px">
								<input type="checkbox" name="isvendor" />
								<span></span>
								<label class="fw-semibold fs-6 mb-2" for="customerFilter">Customer</label>
							</label>
							<label class="checkbox checkbox-success checkbox-rounded">
								<input type="checkbox" name="iscustomer" />
								<span></span>
								<label class="fw-semibold fs-6 mb-2" for="vendorFilter">Vendor</label>
							</label>
						</div>
					</div>
				</div>

				<div class="form-group text-end mt-5">
					<button type="submit" id="filterButton" class="btn btn-lg btn-primary"><i class="fa-sharp fa-solid fa-filter"></i></button>
				</div>
				<!--end::Input group-->
			</form>
		</div>
		<!--end::Content-->
	</div>
	<!--end::Menu 1-->
	<!--end::Filter-->
	<!--begin::Add user-->
	<button type="button" class="btn btn-primary" id="btn-add-user" data-bs-toggle="modal" data-bs-target="#modal_form_contact">
		<i class="ki-duotone ki-plus fs-2"></i> <?= Yii::$app->lang->t('back_home', 'chat59') ?>
	</button>
	<div class="modal fade" id="modal_form_contact" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"> Contact</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div id="modal-content" class="nopadding pe-10">

					</div>
				</div>
			</div>
		</div>
	</div>
	<!--end::Add user-->
</div>
<!--end::Toolbar-->
<!-- </div> -->
<!--end::Card header-->
<!--begin::Card body-->
<div class="card-body px-4 table-responsive">
	<table class="table table-align-middle table-row-dashed fs-6 gy-5 text-muted fw-bold mt-5" id="datatable" style="width:100%;min-height:50px">
		<thead>
			<th class="text-center">Action</th>
			<th class="text-center ">Code</th>
			<th class="text-center w-125px">Nama</th>
			<th class="text-center w-125px">Email</th>
			<th class="text-center w-125px">Phone Number</th>
			<th class="text-center w-125px">Gender</th>
			<th class="text-center w-125px">Status</th>
			<th class="text-center w-125px">Religion</th>
		</thead>
		<tbody>

		</tbody>
	</table>
</div>
<!--end::Card body-->
<!-- </div> -->
<!--end::Card-->
<!-- </div> -->
<!--end::Content container-->
<!--end::Content-->
<!--end::Content wrapper-->

<script>
	function setNo() {
		var ob1 = document.querySelector("input[name='regkode']");

		if (ob1 && ob1.value.trim() === "") {
			fetch("<?= Url::to(['contact/getno'], true); ?>", {
					method: "get"
				})
				.then(response => response.json())
				.then(data => {
					ob1.value = data.regno; // Set nomor yang didapat dari backend
				})
				.catch(error => console.error("Error fetching number:", error));
		}
	}

	$.fn.dataTable.ext.errMode = "none"; // Matikan semua warning DataTables
	$(document).ready(function() {
		console.log(typeof jQuery.fn.yiiActiveForm);
		setNo();
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
			allowClear: true
		});

		$("#select2").select2({
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

		$("#marriedselect2").select2({
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

		$("#reliselect2").select2({
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

		$("#eduselect2").select2({
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

		$("#datatable").DataTable({
			scrollX: true,
			autoWidth: false,
			processing: true,
			serverSide: false, // Jika ingin server-side, ubah ke true
			lengthMenu: [5, 15, 25, 50],
			pageLength: 5, // Set default ke 5
			order: [], // Tidak ada kolom yang diurutkan saat load pertama
			ajax: {
				type: "GET",
				dataSrc: "data", // Pastikan ini sesuai dengan response JSON
				url: "/contact/list",
				data: function(d) {
					d.gender = $('select[name="gender"]').val();
					d.married = $('select[name="married"]').val();
					d.reli = $('select[name="reli"]').val();
					d.tipe = $('select[name="tipe[]"]').val();
					d.search = $('input[name="search"]').val();
					d.isvendor = $('input[name="isvendor"]').is(":checked") ? 1 : null;
					d.iscustomer = $('input[name="iscustomer"]').is(":checked") ? 1 : null;
					d.datefilter = $('input[name="datefilter"]').val();

					//console.log("Data yang dikirim ke server:", d); // Cek apakah search masuk
				}
			}, // URL ke Yii2 API
			columns: [{
					data: null,
					render: function(data, type, row) {
						return `
                        <div class="dropdown text-center dropend">
                            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                <i class="fa-sharp fa-solid fa-list"></i>
                            </button>
                            <ul class="dropdown-menu">
                            	<li><a class="dropdown-item text-hover-success btn-light" id="edit-contact" data-bs-toggle="modal" data-bs-target="#modal_form_contact" data-id="${row.contact_id}"style="cursor: pointer;"><i class="fas fa-edit"></i> Edit</a></li>
								<li><button href="javascript:void(0);" class="dropdown-item text-hover-danger delete-contact" data-id="${row.contact_id}"><i class="fas fa-trash" style="cursor: pointer;"></i> Hapus</button></li>
                            </ul>
                        </div>`;
					}
				},
				{
					data: "contact_no",
				},
				{
					data: "contact_name",
					defaultContent: "Ga ada nama"
				},
				{
					data: "contact_email1",
					render: function(data) {
						return `<span class="text-muted fw-bold">${data}</span>`
					}
				},
				{
					data: "contact_phone1",
					render: function(data) {
						return `<span class="badge badge-light-success fw-bold">${data}</span>`
					}
				},
				// {
				// 	data: "tipe",
				// 	title: "Type",
				// 	defaultContent: "-",
				// 	render: function(data) {
				// 		return data ?
				// 			`<span class="badge fw-bold">${data}</span>` :
				// 			`<span class="text-muted text-uppercase fw-bold fs-6">No Data</span>`;
				// 	}
				// },
				{
					data: "gender",
					render: function(data) {
						return `<span class="badge bg-${data === 'Male' ? 'pink' :'blue'}  text-center fw-bold">${data ?? ''}</span>`
					}
				},
				{
					data: "married",
					render: function(data) {
						return `<span class="badge bg-${data === 'Married' ? 'pink' :'blue'} text-center fw-bold">${data ?? ''}</span>`
					}
				},
				{
					data: "religion",
					render: function(data) {
						return `<span class="badge text-center fw-bold">${data ?? ''}</span>`
					}
				}
			],
		});
		$("#datefilter").css("text-align", "center").daterangepicker({
			timePicker: true,
			autoUpdateInput: false, // Tidak mengisi otomatis input
			startDate: moment().startOf("hour").subtract(1, "month"),
			endDate: moment().startOf("hour").add(31, "hour"),
			locale: {
				format: "YYYY/MM/DD"
			}
		});

		// Update nilai hanya ketika user memilih tanggal
		$("#datefilter").on("apply.daterangepicker", function(ev, picker) {
			$(this).val(picker.startDate.format("YYYY/MM/DD") + " - " + picker.endDate.format("YYYY/MM/DD"));
			$("#datatable").DataTable().ajax.reload();
		});

		// Event ketika tombol clear ditekan
		$("#datefilter").on("cancel.daterangepicker", function(ev, picker) {
			$(this).val(""); // Kosongkan input
			$("#datatable").DataTable().ajax.reload(); // Reload data table
		});

		$("#search").submit(function(e) {
			e.preventDefault(); // Jangan biarkan form reload halaman
			$("#datatable").DataTable().ajax.reload();
		});

		$("#filterForm").submit(function(e) {
			e.preventDefault(); // Jangan biarkan form reload halaman
			$("#datatable").DataTable().ajax.reload();
		});

		// $('#modal_form_contact').kbModalAjax({
		// 	url: '<?= \yii\helpers\Url::to(["create"]) ?>'
		// })

		// $('#modal_form_contact').kbModalAjax({
		// 	url: '<?= \yii\helpers\Url::to(["update"]) ?>?contact_id=6f69dd28-d52e-434f-8649-096f0cb909f2'
		// })

		$(document).on('click', '#edit-contact', function() {
			console.log(typeof jQuery.fn.yiiActiveForm);
			var id = $(this).data('id')
			$.ajax({
				url: '<?= \yii\helpers\Url::to(['update']) ?>?contact_id=' + id,
				success: function(data) {
					var $html = $('<div>').html(data);
					console.log(typeof jQuery.fn.yiiActiveForm);
					var scripts = $html.find('script[src]');

					// Masukkan konten modal tanpa script yang punya src
					$('#modal-content').html($html.find('*').not('script[src]'));
					$('#modal_form_contact').modal('show');

					// Load ulang hanya script yang punya src, kecuali yang sudah ada
					scripts.each(function() {
						var scriptSrc = $(this).attr('src');

						// **Cek jika script belum ada & jangan hapus apa pun**
						if ($('script[src="' + scriptSrc + '"]').length === 0) {
							var script = document.createElement('script');
							script.src = scriptSrc;
							script.async = false;
							document.body.appendChild(script);
						}
					});
				},
				error: function() {
					$('#modal-content').html('<p>Error loading form.</p>');
				}
			});
		});

		$('#btn-add-user').on('click', function() {
			$.ajax({
				url: '<?= \yii\helpers\Url::to(['create']) ?>',
				success: function(data) {
					var $html = $('<div>').html(data); // Bungkus dalam elemen div sementara
					var scripts = $html.find('script[src]'); // Ambil semua elemen script

					// **1. Hapus ActiveForm lama sebelum load form baru**
					if ($('#FormValid').data('yiiActiveForm')) {
						$('#FormValid').yiiActiveForm('destroy').remove();
					}

					// **2. Bersihkan event handler lama**
					$('#modal-content').find('*').off();
					$('#modal-content').empty(); // Kosongkan modal agar JS lama hilang

					// **3. Masukkan hanya konten modal tanpa script**
					$('#modal-content').html($html.find('*').not('script[src]'));

					console.log(scripts);

					// **4. Eksekusi ulang skrip inline dengan globalEval**
					scripts.each(function() {
						if ($(this).attr('src')) {
							// Jika script eksternal, cek apakah sudah ada
							if (!$('script[src="' + $(this).attr('src') + '"]').length) {
								$.getScript($(this).attr('src'));
							}
						} else {
							// Jika script inline, eksekusi ulang
							$.globalEval($(this).html());
						}
					});

					// **5. Tampilkan modal**
					$('#modal_form_contact').modal('show');
				},
				error: function() {
					$('#modal-content').html('<p>Error loading form.</p>');
				}
			});
		});

		// $('#btn-add-user').on('click', function() {
		// 	$.ajax({
		// 		url: '<?= \yii\helpers\Url::to(['create']) ?>',
		// 		success: function(data) {
		// 			var $html = $('<div>').html(data); // Bungkus dalam elemen div sementara
		// 			var scripts = $html.find('script'); // Ambil semua elemen script

		// 			// Masukkan hanya konten modal tanpa script
		// 			$('#modal-content').html($html.find('*').not('script[src]'));

		// 			// Eksekusi skrip inline dan eksternal
		// 			scripts.each(function() {
		// 				if ($(this).attr('src')) {
		// 					// Jika script memiliki src, tambahkan ke body
		// 					$.getScript($(this).attr('src'));
		// 				} else {
		// 					// Jika script inline, eksekusi langsung
		// 					$.globalEval($(this).html());
		// 				}
		// 			});

		// 			// Tampilkan modal
		// 			$('#modal_form_contact').modal('show');

		// 		},
		// 		error: function() {
		// 			$('#modal-content').html('<p>Error loading form.</p>');
		// 		}
		// 	});
		// });



		$(document).on('click', '.delete-contact', function() {
			var contactId = $(this).data('id'); // Ambil ID kontak
			console.log('Menghapus ID:', contactId);

			if (!contactId) {
				Swal.fire({
					title: "Error!",
					text: "ID tidak ditemukan! Periksa tombol yang diklik.",
					icon: "error",
					confirmButtonColor: "#d33",
					confirmButtonText: "OK"
				});
				return;
			}

			Swal.fire({
				title: "Yakin ingin hapus?",
				text: "Data yang dihapus tidak bisa dikembalikan!",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#d33",
				cancelButtonColor: "#3085d6",
				confirmButtonText: "Ya, hapus!",
				cancelButtonText: "Batal"
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: '<?= Url::to(['/contact/delete']) ?>',
						type: 'post', // Harus POST
						data: {
							id: contactId,
							_csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
						},
						headers: {
							"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
						},
						success: function(response) {
							console.log('Response:', response);

							// Reload DataTable
							$('#datatable').DataTable().ajax.reload();

							// Tampilkan notifikasi sukses
							Swal.fire({
								title: "Terhapus!",
								text: "Data berhasil dihapus.",
								icon: "success",
								timer: 2000,
								showConfirmButton: false
							});
						},
						error: function(xhr) {
							console.error('Error:', xhr.responseText);

							// Tampilkan notifikasi error
							Swal.fire({
								title: "Gagal!",
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
	});
</script>

<!--end::Body-->
<style>
	.bg-pink {
		background-color: pink;
		color: black;
	}

	.bg-blue {
		background-color: blue;
		color: white;
	}

	.pagination .page-item .page-link {
		border: none;
		color: #5e6278;
		font-weight: 500;
		border-radius: 6px;
		margin: 0 4px;
		padding: 8px 16px;
	}

	.pagination .page-item.active .page-link {
		background-color: #009ef7;
		color: #fff;
		box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
	}

	.pagination .page-item.disabled .page-link {
		color: #ccc;
		pointer-events: none;
	}

	.pagination .page-link:hover {
		background-color: #f4f4f4;
	}
</style>