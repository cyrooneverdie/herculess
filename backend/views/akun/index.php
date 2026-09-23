<?php 
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\widgets\ActiveForm;
	use yii\grid\GridView;
	use yii\data\ActiveDataProvider;

?>
<style>
	#datatable th,
	#datatable td {
		white-space: nowrap;
		min-width: 150px;
		/* Atur minimum lebar */
	}

	/* Atur agar kolom pertama tidak ikut min-width */
	#datatable th:first-child,
	#datatable td:first-child {
		min-width: auto !important;
		width: 50px !important;
		/* Sesuaikan lebar */
	}
</style>
<div class="card">
	<div class="card-header border-0 pt-6">
		<div class="card-title">
			<div class="d-flex align-items-center position-relative my-1">
				<form method="get" action="index" id="search" class="w-100">
					<div class="position-relative">
						<!-- Search icon properly positioned inside the form -->
						<span class="position-absolute top-50 start-0 translate-middle-y ms-3">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512" fill="#a1a5b7">
								<path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
							</svg>
						</span>
						<input
							data-kt-docs-table-filter="search"
							type="text"
							name="search"
							class="form-control form-control-solid w-250px ps-10"
							placeholder="<?= Yii::$app->lang->t('home', 'textakun') ?>" />

						<!-- Optional: Clear search button -->
						<span class="position-absolute top-50 end-0 translate-middle-y me-3 d-none" id="clear-search">
							<i class="ki-duotone ki-cross fs-2 text-gray-500 cursor-pointer" style="opacity: 0.5;"></i>
						</span>
					</div>
				</form>
			</div>
		</div>

		<div class="card-toolbar">
			<!-- Filter button - Fix: Use data-kt-menu-trigger="click" -->
			<button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="left-start">
				<i class="ki-duotone ki-filter fs-2">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>Filter
			</button>

			<!-- Filter Menu - Move it right after the button -->
			<div class="menu menu-sub menu-sub-dropdown w-sm-500px w-md-600px" data-kt-menu="true" data-kt-menu-id="filter-menu">
				<!-- Header -->
				<div class="px-7 py-5">
					<div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
				</div>
				<!-- Separator -->
				<div class="separator border-gray-200"></div>
				<!-- Content -->
				<div class="px-7 py-5" data-kt-user-table-filter="form">
					<form id="filterForm" method="get" action="index">
						<!-- Input group -->
						<div class="row">

							<!-- <div class="md-10">
								<label class="fw-semibold fs-6 mb-2 mt-3" for="dateFilter">Date Range:</label>
								<input name="datefilter" class="form-control form-control-solid" style="cursor:pointer;" id="datefilter" placeholder="Pick date range">
							</div> -->

							<div class="col-md-12 col-lg-12">
								<label class="fw-semibold fs-6 mb-2" for="categoryFilter">Category</label>
								<select id="CategorySelect2" class="form-select Category" data-control="select2" name="Category">
									<option selected><?= $model->Category['coa_name_id'] ?? '' ?></option>
								</select>
							</div>
						</div>
						<div class="form-group text-end mt-5">
							<button type="submit" id="filterButton" class="btn btn-lg btn-primary"><i class="fa-sharp fa-solid fa-filter"></i></button>
						</div>
					</form>
				</div>
			</div>

			<!-- Add product button -->
			<button type="button" class="btn btn-primary" id="btn-add-akun" data-bs-toggle="modal" data-bs-target="#modal_form_akun">
				<i class="ki-duotone ki-plus fs-2"></i> <?= Yii::$app->lang->t('home', 'addakun') ?>
			</button>
		</div>
	</div>

	<div class="card-body pt-5">
		<table class="table align-middle table-row-dashed fs-6 gy-5" id="datatable">
			<thead>
				<tr class="text-start bg-gray-100 fs-6 text-gray-500 fw-bold fs-7 text-uppercase gs-0">
					<th class="text-center">
						<input type="checkbox" id="select-all">
					</th>
					<th class="text-center min-w-50px">action</th>
					<th class="text-center min-w-100px">Kode</th>
					<th class="text-center min-w-100px">Akun</th>
					<th class="text-center min-w-100px">saldo</th>
					<th class="text-center min-w-50px">status</th>
				</tr>
			</thead>
			<tbody class="text-gray-600 fw-semibold text-center">

			</tbody>
		</table>
	</div>
</div>

<div class="modal fade" id="modal_form_akun" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Akun</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div id="modal-content" class="nopadding">

				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal_form_akun" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-fullscreen-lg-down modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Akun</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div id="modal-content" class="nopadding">
					<!-- Product form content will be loaded here -->
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	function setNo() {
		var ob1 = document.querySelector("input[name='akunKode']");

		if (ob1 && ob1.value.trim() === "") {
			fetch("<?= Url::to(['akun/getno'], true); ?>", {
					method: "get"
				})
				.then(response => response.json())
				.then(data => {
					ob1.value = data.no;
				})
				.catch(error => console.error("Error fetching number:", error));
		}
	}

	$.fn.dataTable.ext.errMode = "none";
	$(document).ready(function() {
		console.log(typeof jQuery.fn.yiiActiveForm);
		setNo();

		$("#CategorySelect2").select2({
			ajax: {
				url: "<?= \yii\helpers\Url::to(['akun/categorylist']) ?>",
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
			placeholder: "Pilih Category",
			allowClear: true
		});

		$("#datatable").DataTable({
			scrollX: true,
			autoWidth: false,
			processing: false,
			serverSide: false,
			lengthMenu: [5, 15, 25, 50],
			pageLength: 5,
			order: [],
			language: {
				emptyTable: `
					<div style="text-align: center; padding: 20px 0;">
					<img width='250px' src='https://cdni.iconscout.com/illustration/premium/thumb/employee-is-unable-to-find-sensitive-data-illustration-download-in-svg-png-gif-file-formats--no-found-misplaced-files-business-pack-illustrations-8062128.png'/>
						<div style="font-weight: bold; font-size: 16px; margin-top : 8px;"> No data available in table</div>
					</div>
				`,
						zeroRecords: `
					<div style="text-align: center; padding: 20px 0;">
					<img width='250px' src='https://cdni.iconscout.com/illustration/premium/thumb/employee-is-unable-to-find-sensitive-data-illustration-download-in-svg-png-gif-file-formats--no-found-misplaced-files-business-pack-illustrations-8062128.png'/>
						<div style="font-weight: bold; font-size: 16px; margin-top : 8px;">No data available in table</div>
					</div>
				`,
						loadingRecords: `
					<div style="text-align: center; padding: 20px 0;">
						<div class="spinner-border text-primary" role="status">
							<span class="visually-hidden">Loading...</span>
						</div>
						<div style="margin-top: 10px;">Memuat data...</div>
					</div>
				`
			},
			initComplete: function() {
				$('.dataTables_empty').closest('.dataTables_scroll').addClass('table-initialized');
			},
			ajax: {
				type: "GET",
				dataSrc: "data",
				url: "/akun/list",
				data: function(d) {
					console.log(d);
					d.search = $('input[name="search"]').val();
				},
				complete: function() {
					$('.table-loading-overlay').remove();
				}
			},

			columns: [
				{
					data: null,
					className: "text-center",
					orderable: false,
					render: function(data, type, row) {
						return `<input type="checkbox" class="select-checkbox" value="${row.coa_id}">`;
					}
				},{
					data: null,
					render: function(data, type, row) {
						console.log(row);
						return `
							<div class="dropdown text-center dropend">
								<button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
									<i class="fa-sharp fa-solid fa-list"></i>
								</button>
								<ul class="dropdown-menu">
									<li><a class="dropdown-item text-hover-success btn-light" id="edit-akun" data-bs-toggle="modal" data-bs-target="#modal_form_akun" data-id="${row.coa_id}"style="cursor: pointer;"><i class="fas fa-edit"></i> Edit</a></li>
									<li><button href="javascript:void(0);" class="dropdown-item text-hover-danger delete-akun" data-id="${row.coa_id}"><i class="fas fa-trash" style="cursor: pointer;"></i> Hapus</button></li>
								</ul>
							</div>
						`;
					}
				},
				{
					data: "coa_no",
					render: function(data) {
						return `<span class="text text-align-center">${data}</span>`;
					}
				},
				{
					data: "coa_name_id",
					render: function(data) {
						return `${data || 'N/A'}`;
					}
				},
				{
					data: "coa_type",
					defaultContent: "-",
					render: function(data) {
						return data ? `${data}` : `No Data`;
					}
				},
				{
					data: "coa_status",
					render: function(data) {
						return data == 1 ?
							'<span class="badge badge-success">Aktif</span>' :
							'<span class="badge badge-danger">Non-Aktif</span>';
					}
				}
			],
		});

		$("#datefilter").css("text-align", "center").daterangepicker({
			timePicker: true,
			autoUpdateInput: false,
			startDate: moment().startOf("hour").subtract(1, "month"),
			endDate: moment().startOf("hour").add(31, "hour"),
			locale: {
				format: "YYYY/MM/DD"
			}
		});

		// $("#datefilter").on("apply.daterangepicker", function(ev, picker) {
		// 	$(this).val(picker.startDate.format("YYYY/MM/DD") + " - " + picker.endDate.format("YYYY/MM/DD"));
		// 	$("#datatable").DataTable().ajax.reload();
		// });

		// $("#datefilter").on("cancel.daterangepicker", function(ev, picker) {
		// 	$(this).val(""); // Kosongkan input
		// 	$("#datatable").DataTable().ajax.reload(); // Reload data table
		// });

		$("#search").submit(function(e) {
			e.preventDefault();
			$("#datatable").DataTable().ajax.reload();
		});

		$("#filterForm").submit(function(e) {
			e.preventDefault(); 
			$("#datatable").DataTable().ajax.reload();
		});

		$(document).on('click', '#edit-akun', function() {
			var id = $(this).data('id')
			$.ajax({
				url: '<?= \yii\helpers\Url::to(['update']) ?>?id=' + id,
				success: function(data) {
					// Gunakan DOMParser untuk memparsing HTML
					let parser = new DOMParser();
					let doc = parser.parseFromString(data, 'text/html');
					// Masukkan konten yang telah dibersihkan ke modal tanpa mengubah struktur form
					$('#modal-content').html(doc.body.innerHTML);
					// console.log("Select2: ", typeof $.fn.select2);
					$('#modal_form_akun').modal('show');
				},
				error: function() {
					$('#modal-content').html('<p>Error loading form.</p>');
				}
			});
		});

		$('#btn-add-akun').on('click', function() {
			$.ajax({
				url: '<?= \yii\helpers\Url::to(['create']) ?>',
				success: function(data) {
					// Gunakan DOMParser untuk memparsing HTML
					let parser = new DOMParser();
					let doc = parser.parseFromString(data, 'text/html');

					// Masukkan konten yang telah dibersihkan ke modal tanpa mengubah struktur form
					$('#modal-content').html(doc.body.innerHTML);
					console.log("Select2: ", typeof $.fn.select2);
					$('#modal_form_akun').modal('show');
				},
				error: function() {
					$('#modal-content').html('<p>Error loading form.</p>');
				}
			});
		});

		$(document).on('click', '.delete-akun', function() {
			var coa_id = $(this).data('id'); // Ambil ID akun
			console.log('Menghapus ID:', coa_id);

			if (!coa_id) {
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
						url: '<?= Url::to(['/akun/delete']) ?>',
						type: 'post', // Harus POST
						data: {
							id: coa_id,
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