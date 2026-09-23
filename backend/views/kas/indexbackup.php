<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
// use karnbrockgmbh\modal\Modal;	
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
		width: 70px !important;
		/* Sesuaikan lebar */
	}
</style>
<!--begin::Search-->
<div class="card mt-5">
	<div class="card-header border-0 pt-6">
		<div class="card-title">
			<div class="d-flex align-items-center position-relative my-1">
				<i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"></i>
				<form method="get" action="index" style="width: 100%;" id="search">
					<input
						data-kt-docs-table-filter="search"
						type="text"
						name="search"
						class="form-control form-control-solid w-250px ps-13"
						placeholder="<?= Yii::$app->lang->t('kasbackend', 'kasbackend1') ?>" />
				</form>
			</div>
		</div>
		<div class="card-toolbar">
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
							<div class="col-md-4">
								<label class="fw-semibold fs-6 mb-2" for="genderFilter">Gender</label>
								<select id="select2" class="form-select gender" data-control="select2" name="gender">
									<option selected><?= $model->gender['enumtext_en'] ?? '' ?></option>
								</select>
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
			<button type="button" class="btn btn-primary" id="btn-KM" data-bs-target="#modal_form_cash" data-id="KM">
				<i class="ki-duotone ki-plus fs-2"></i> <?= Yii::$app->lang->t('kasbackend', 'kasbackend2') ?>
			</button>
			<button type="button" class="btn btn-danger ms-5" id="btn-KK" data-bs-target="#modal_form_cash" data-id="KK">
				<i class="ki-duotone ki-plus fs-2"></i> <?= Yii::$app->lang->t('kasbackend', 'kasout') ?>
			</button>
			<!--end::Add user-->
		</div>
	</div>
	<!--begin::Card body-->
	<div class="card-body pt-5">
		<div id="liveAlertPlaceholder"></div>
		<button type="button" class="btn btn-danger text-light mb-3" id="btn-hapusmassal" style="display: none;">Hapus</button>
		<table class="table align-middle table-row-dashed fs-6 gy-5" id="datatable">
			<thead>
				<tr class="text-start bg-gray-100  fs-6 text-gray-500 fw-bold fs-7 text-uppercase gs-0">
					<th class="d-none"></th>
					<th class="text-center">
						<input type="checkbox" id="select-all">
					</th>
					<th class="text-center"><?= Yii::$app->lang->t('cashbackend', 'cashbackend1') ?></th>
					<th class="text-center">Status</th>
					<th class="text-center"><?= Yii::$app->lang->t('cashbackend', 'cashbackend2') ?></th>
					<th class="text-center"><?= Yii::$app->lang->t('cashbackend', 'cashbackend3') ?></th>
					<th class="text-center"><?= Yii::$app->lang->t('cashbackend', 'cashbackend4') ?></th>
					<th class="text-center"><?= Yii::$app->lang->t('cashbackend', 'cashbackend5') ?></th>
					<th class="text-center"><?= Yii::$app->lang->t('cashbackend', 'cashbackend6') ?></th>
					<th class="text-center"><?= Yii::$app->lang->t('cashbackend', 'cashbackend7') ?></th>
					<th class="text-center"><?= Yii::$app->lang->t('cashbackend', 'cashbackend8') ?></th>
				</tr>
			</thead>
			<tbody>
				<!-- Table content will be loaded dynamically -->
			</tbody>
		</table>
	</div>
	<!--end::Card body-->
</div>

<div class="modal fade" id="modal_form_cash" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"> <?= Yii::$app->lang->t('cashbackend', 'cashbackend9') ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div id="modal-content" class="nopadding">

				</div>
			</div>
		</div>
	</div>
</div>
<!--end::Card header-->
<!--end::Card-->
<script>
	// Initialize Select2 for Contacts
	$("#contactSelect").select2({
		ajax: {
			url: "<?= \yii\helpers\Url::to(['purchase/contactlist']) ?>",
			type: "GET",
			dataType: "json",
			delay: 250,
			data: function(params) {
				return {
					q: params.term,
				};
			},
			processResults: function(data) {
				// Transform data from {"data":[...]} to format expected by Select2
				return {
					results: data.data.map(function(contact) {
						return {
							id: contact.contact_id,
							text: contact.contact_name,
							contact_phone1: contact.contact_phone1 || '',
							contact_email1: contact.contact_email1 || ''
						};
					})
				};
			},
			cache: true
		},
		placeholder: "Pilih Contact",
		allowClear: true,
		templateResult: formatContact,
		templateSelection: formatContactSelection,
		dropdownParent: $('#modal_form_cash').length ? $('#modal_form_cash') : $(document.body)
	});

	// Format contact display in dropdown
	function formatContact(contact) {
		if (!contact.id) return contact.text;

		// Create more informative display showing phone and email
		var $container = $(
			'<div class="select2-result-contact clearfix">' +
			'<div class="select2-result-contact__name">' + contact.text + '</div>' +
			(contact.contact_phone1 ? '<div class="select2-result-contact__phone"><i class="fa fa-phone me-1"></i> ' + contact.contact_phone1 + '</div>' : '') +
			(contact.contact_email1 ? '<div class="select2-result-contact__email"><i class="fa fa-envelope me-1"></i> ' + contact.contact_email1 + '</div>' : '') +
			'</div>'
		);

		return $container;
	}

	// Format selected contact display
	function formatContactSelection(contact) {
		return contact.text || contact.id;
	}

	function hapusmassal() {
		if ($('.select-checkbox:checked').length > 0) {
			$('#btn-hapusmassal').show();
		} else {
			$('#btn-hapusmassal').hide();
		}
	}

	$.fn.dataTable.ext.errMode = "none"; // Matikan semua warning DataTables
	$(document).ready(function() {
		$("#datatable").DataTable({
			scrollX: true,
			autoWidth: true,
			processing: true,
			serverSide: false, // Jika ingin server-side, ubah ke true
			lengthMenu: [10, 20, 30, 50],
			pageLength: 10, // Set default ke 5
			order: [], // Tidak ada kolom yang diurutkan saat load pertama

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
			select: {
				style: 'multi',
				selector: 'td:first-child input[type="checkbox"]',
				className: 'row-selected text-center'
			},
			ajax: {
				type: "GET",
				dataSrc: "data", // Pastikan ini sesuai dengan response JSON
				url: "<?= \yii\helpers\Url::to(['kas/list']) ?>",
				data: function(d) {
					d.married = $('select[name="married"]').val();
					d.gender = $('select[name="gender"]').val();
					d.reli = $('select[name="reli"]').val();
					d.tipe = $('select[name="tipe[]"]').val();
					d.search = $('input[name="search"]').val();
					//	d.datefilter = $('input[name="datefilter"]').val();

					//console.log("Data yang dikirim ke server:", d); // Cek apakah search masuk
				}
			}, // URL ke Yii2 API
			columns: [{
					data: "cashid",
					visible: false
				}, // ID transaksi, tidak ditampilkan,
				{
					data: null,
					className: "text-center",
					orderable: false,
					render: function(data, type, row) {
						return `<input type="checkbox" class="select-checkbox" value="${row.cashid}">`;
					}
				},
				{
					data: null,
					render: function(data, type, row) {
						return `
                        <div class="dropdown text-center dropend">
                            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                <i class="fa-sharp fa-solid fa-list"></i>
                            </button>
                            <ul class="dropdown-menu">
								<li><a class="dropdown-item text-hover-primary btn-light" id="bayar" data-id="${row.cashid}"style="cursor: pointer;"><i class="fas fa-edit"></i> Bayar</a></li>
                            	<li><a class="dropdown-item text-hover-success btn-light" id="edit-contact" data-bs-toggle="modal" data-bs-target="#modal_form_cash" data-id="${row.cashid}"style="cursor: pointer;"><i class="fas fa-edit"></i> Edit</a></li>
								<li><button href="javascript:void(0);" class="dropdown-item text-hover-danger delete-contact" data-id="${row.cashid}"><i class="fas fa-trash" style="cursor: pointer;"></i> Hapus</button></li>
                            </ul>
                        </div>`;
					}
				},
				{
					data: "status",
					className: "text-center",
					render: function(data, type, row) {
						let statusText = "";
						let statusOptions = "";
						let statusBg = "";
						// Status berdasarkan angka dari database
						if (data == 0) {
							statusText = "Waiting";
							statusBg = "primary";
							statusOptions = `
                        <li><a class="dropdown-item update-status text-hover-success" href="#" data-id="${row.cashid}" data-status="1"><?= Yii::$app->lang->t('cashbackend', 'cashbackend10') ?></a></li>
                        <li><a class="dropdown-item update-status text-hover-warning" href="#" data-id="${row.cashid}" data-status="5"><?= Yii::$app->lang->t('cashbackend', 'cashbackend11') ?></a></li>
                        <li><a class="dropdown-item update-status text-hover-danger" href="#" data-id="${row.cashid}" data-status="10"><?= Yii::$app->lang->t('cashbackend', 'cashbackend12') ?></a></li>
                    `;
						} else if (data == 1) {
							statusText = "<?= Yii::$app->lang->t('cashbackend', 'cashbackend10') ?>";
							statusBg = "success";
							statusOptions = `
                        <li><a class="dropdown-item update-status text-hover-warning" href="#" data-id="${row.cashid}" data-status="5"><?= Yii::$app->lang->t('cashbackend', 'cashbackend11') ?></a></li>
                        <li><a class="dropdown-item update-status text-hover-danger" href="#" data-id="${row.cashid}" data-status="10"><?= Yii::$app->lang->t('cashbackend', 'cashbackend12') ?></a></li>
                    `;
						} else if (data == 5) {
							statusText = "<?= Yii::$app->lang->t('cashbackend', 'cashbackend11') ?>";
							statusBg = "warning";
							statusOptions = `
							<li><a class="dropdown-item update-status text-hover-danger" href="#" data-id="${row.cashid}" data-status="10"><?= Yii::$app->lang->t('cashbackend', 'cashbackend12') ?></a></li>
							<li><a class="dropdown-item update-status text-hover-success" href="#" data-id="${row.cashid}" data-status="1"><?= Yii::$app->lang->t('cashbackend', 'cashbackend10') ?></a></li>
							`;
						} else if (data == 10) {
							statusText = "<?= Yii::$app->lang->t('cashbackend', 'cashbackend12') ?>";
							statusBg = "danger";
							statusOptions = `
							<li><a class="dropdown-item update-status text-hover-warning" href="#" data-id="${row.cashid}" data-status="5"><?= Yii::$app->lang->t('cashbackend', 'cashbackend11') ?></a></li>
							<li><a class="dropdown-item update-status text-hover-success" href="#" data-id="${row.cashid}" data-status="1"><?= Yii::$app->lang->t('cashbackend', 'cashbackend10') ?></a></li>
							`;
						}

						return `<div class="dropdown">
                            <button class="btn btn-${statusBg} text-white" data-bs-toggle="dropdown" type="button" data-bs-toggle="dropdown">
                                ${statusText}
                            </button>
                            <ul class="dropdown-menu">${statusOptions}</ul>
                        </div>`;
					}
				},
				{
					data: "cashno",
					className: "text-center",
					// render: function(data) {
					// 	return data ? `${data}` : `No Data`;
					// }
				},
				{
					data: "contact_name",
					className: "text-center",
					defaultContent: "-",
					render: function(data) {
						return data ? `${data}` : `No Data`;
					}
				},
				{
					data: "cashdate",
					className: "text-center",
					render: function(data) {
						if (!data) return '-';
						// Convert date format if needed
						const date = new Date(data);
						const day = String(date.getDate()).padStart(2, '0');
						const month = String(date.getMonth() + 1).padStart(2, '0');
						const year = date.getFullYear();
						return `${day}-${month}-${year}`;
					}
				},
				{
					data: "cashduedate",
					className: "text-center",
					render: function(data) {
						if (!data) return '-';
						// Convert date format if needed
						const date = new Date(data);
						const day = String(date.getDate()).padStart(2, '0');
						const month = String(date.getMonth() + 1).padStart(2, '0');
						const year = date.getFullYear();
						return `${day}-${month}-${year}`;
					}
				},
				{
					data: "subtotal",
					className: "text-center",
					render: function(data) {
						return "Rp." + Intl.NumberFormat('id-ID').format(data);
					}
				},
				{
					data: "total",
					className: "text-center",
					render: function(data) {
						return "Rp." + Intl.NumberFormat('id-ID').format(data);
					}
				},
				{
					data: "sisa_pembayaran",
					className: "text-center",
					render: function(data) {
						let formattedNumber = "Rp." + Intl.NumberFormat('id-ID').format(Math.abs(data));
						let label = data < 0 ? "<?= Yii::$app->lang->t('cashbackend', 'cashbackend14') ?>" : "<?= Yii::$app->lang->t('cashbackend', 'cashbackend13') ?>";
						let color = data < 0 ? "text-success" : "text-danger"; // Warna hijau untuk kelebihan, merah untuk kurang bayar
						return `<span class="${color}">${label}: ${formattedNumber}</span>`;
					}
				},
			],
		});

		$(document).on('click', '#bayar', function(e) {
			e.preventDefault(); // Mencegah reload
			let id = $(this).data('id'); // Ambil ID perusahaan yang diklik
			let url = "<?= Url::to(['kas/update']) ?>?id=" + id; // Buat URL dinamis

			// Ubah URL di browser tanpa reload
			history.pushState(null, '', url);

			// Ambil halaman target via AJAX
			$.ajax({
				url: url,
				type: 'GET',
				success: function(data) {
					$('.app-container.container-xxl').html(data); // Ganti seluruh isi halaman
				},
				error: function(xhr, status, error) {
					alert("Gagal memuat halaman: " + error);
				}
			});
		});

		$(document).on("click", ".update-status", function(e) {
			e.preventDefault();

			let cashid = $(this).data("id");
			let newStatus = $(this).data("status");
			let button = $(this).closest(".dropdown").find("button");

			let statusText = "";
			if (newStatus == 1) statusText = "Paid";
			else if (newStatus == 5) statusText = "Partial";
			else if (newStatus == 10) statusText = "Unpaid";

			button.text(statusText); // Update UI sementara

			$.ajax({
				url: "/kas/updatestatus",
				type: "POST",
				data: {
					id: cashid,
					status: newStatus,
					_csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
				},
				headers: {
					"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
				},
				success: function(response) {
					if (response.success) {
						if (newStatus == 1) {
							alertType = "success"; // Approved = Hijau
						} else if (newStatus == 5) {
							alertType = "warning"; // Problem = Kuning
						} else if (newStatus == 10) {
							alertType = "danger"; // Cancel = Merah
						}

						showBootstrapAlert("Status berhasil diperbarui!", alertType);

						$("#datatable").DataTable().ajax.reload(null, false);
					} else {
						showBootstrapAlert("Gagal memperbarui status: " + response.message, "danger");
					}
				},
				error: function() {
					showBootstrapAlert("Terjadi kesalahan saat memperbarui status.", "danger");
				}
			});
		});

		// Fungsi untuk menampilkan alert Bootstrap
		function showBootstrapAlert(message, type) {
			let alertPlaceholder = document.getElementById("liveAlertPlaceholder");
			let wrapper = document.createElement("div");
			wrapper.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;

			alertPlaceholder.innerHTML = ""; // Hapus alert sebelumnya biar nggak numpuk
			alertPlaceholder.append(wrapper);
		}

		// Select All checkbox di thead
		$("#select-all").on("click", function() {
			$("tbody .select-checkbox").prop("checked", this.checked);
			hapusmassal();
		});

		// Update checkbox Select All jika ada perubahan di checkbox per baris
		$("#datatable tbody").on("change", ".select-checkbox", function() {
			$("#select-all").prop(
				"checked",
				$(".select-checkbox").length === $(".select-checkbox:checked").length
			);
			hapusmassal();
		});

		$("#search").submit(function(e) {
			e.preventDefault(); // Jangan biarkan form reload halaman
			$("#datatable").DataTable().ajax.reload();
		});

		$("#filterForm").submit(function(e) {
			e.preventDefault(); // Jangan biarkan form reload halaman
			$("#datatable").DataTable().ajax.reload();
			// Gunakan Metronic API untuk menutup menu
			let filterMenu = document.querySelector("[data-kt-menu-id='filter-menu']");
			if (filterMenu) {
				KTMenu.getInstance(filterMenu).hide();
			}
		});

		$(document).on('click', '#edit-contact', function() {
			var id = $(this).data('id')
			$.ajax({
				url: '<?= \yii\helpers\Url::to(['update']) ?>?id=' + id,
				success: function(data) {
					// Gunakan DOMParser untuk memparsing HTML
					let parser = new DOMParser();
					let doc = parser.parseFromString(data, 'text/html');
					// Masukkan konten yang telah dibersihkan ke modal tanpa mengubah struktur form
					$('#modal-content').html(doc.body.innerHTML);
					$('input').prop('disabled', true);
					$('select').attr('disabled', 'true');
					// console.log("Select2: ", typeof $.fn.select2);
					$('#modal_form_cash').modal('show');
				},
				error: function() {
					$('#modal-content').html('<p>Error loading form.</p>');
				}
			});
		});

		$('#btn-KM, #btn-KK').on('click', function() {
			var kastype = $(this).data('id');
			$.ajax({
				url: '<?= \yii\helpers\Url::to(['create']) ?>',
				type: 'GET',
				data: {
					kastype: kastype
				}, // Kirim tipe kas ke controller
				success: function(data) {
					// Gunakan DOMParser untuk memparsing HTML
					let parser = new DOMParser();
					let doc = parser.parseFromString(data, 'text/html');

					// Masukkan konten yang telah dibersihkan ke modal tanpa mengubah struktur form
					$('#modal-content').html(doc.body.innerHTML);
					$('#modal_form_cash').modal('show');
				},
				error: function() {
					$('#modal-content').html('<p>Error loading form.</p>');
				}
			});
		});

		$('#btn-hapusmassal').on('click', function() {
			let selectedIds = $(".select-checkbox:checked")
				.map(function() {
					return $(this).val(); // Ambil nilai ID dari checkbox yang dicentang
				})
				.get();

			if (selectedIds.length === 0) {
				Swal.fire({
					title: "Tidak Ada Data Terpilih!",
					text: "Pilih minimal satu data untuk dihapus.",
					icon: "warning",
					confirmButtonColor: "#d33",
					confirmButtonText: "OK"
				});
				return;
			}

			Swal.fire({
				title: "Yakin ingin hapus?",
				text: `Anda akan menghapus ${selectedIds.length} data. Data yang dihapus tidak bisa dikembalikan!`,
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#d33",
				cancelButtonColor: "#3085d6",
				confirmButtonText: "Ya, hapus!",
				cancelButtonText: "Batal"
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: '<?= Url::to(['/kas/deletemassal']) ?>', // Endpoint untuk delete massal
						type: "POST",
						data: {
							ids: selectedIds, // Kirim array ID ke server
							_csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
						},
						headers: {
							"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
						},
						success: function(response) {
							// console.log("Response:", response);

							// Reload DataTable
							$("#datatable").DataTable().ajax.reload();

							// Sembunyikan tombol hapus massal setelah sukses
							$("#btn-hapusmassal").hide();

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
							console.error("Error:", xhr.responseText);

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
		})

		$(document).on('click', '.delete-contact', function() {
			var contactId = $(this).data('id'); // Ambil ID kontak

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
						url: '<?= Url::to(['/kas/delete']) ?>',
						type: 'post', // Harus POST
						data: {
							id: contactId,
							_csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
						},
						headers: {
							"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
						},
						success: function(response) {
							// console.log('Response:', response);

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

<style>
	.pagination.page-item.page-link {
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

	.pagination.page-item.disabled.page-link {
		color: #ccc;
		pointer-events: none;
	}

	.pagination.page-link:hover {
		background-color: #f4f4f4;
	}
</style>