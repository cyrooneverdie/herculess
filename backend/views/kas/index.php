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
		/* min-width: auto !important; */
		width: 70px !important;
		/* Sesuaikan lebar */
	}
</style>
<div class="app-toolbar py-3 py-lg-6 ms-n8">
	<div class="app-container container-fluid d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<!--begin::Title-->
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0"><?= Yii::$app->lang->t('extrasidebar', 'extrasidebar19') ?></h1>
			<!--end::Title-->
			<!--begin::Breadcrumb-->
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="<?= Url::to(['site/index']) ?>" class="text-muted text-hover-primary"><?= Yii::$app->lang->t('back_home', 'chat2') ?></a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<!--end::Item-->
				<!--begin::Item-->
				<li class="breadcrumb-item text-muted"><?= Yii::$app->lang->t('extrasidebar', 'extrasidebar19') ?></li>
				<!--end::Item-->
			</ul>
			<!--end::Breadcrumb-->
		</div>
	</div>
</div>

<!--begin::Search-->
<div class="card mt-5">
	<div class="card-header border-0 pt-6">
		<div class="card-title">
			<div class="d-flex align-items-center position-relative my-1 mt-lg-5">
				<i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"></i>
				<form method="get" action="index" style="width: 100%;" id="search">
					<span class="position-absolute top-50 start-0 translate-middle-y ms-3">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512" fill="#a1a5b7">
							<path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
						</svg>
					</span>
					<input
						data-kt-docs-table-filter="search"
						type="text"
						name="search"
						class="form-control form-control-solid w-250px ps-13"
						placeholder="<?= Yii::$app->lang->t('kasbackend', 'kasbackend1') ?>"
						autocomplete="off" />
					<span class="position-absolute top-50 end-0 translate-middle-y me-3 d-none" id="clear-search">
						<i class="ki-duotone ki-cross fs-2 text-gray-500 cursor-pointer" style="opacity: 0.5;"></i>
					</span>
				</form>
			</div>
		</div>
		<div class="card-toolbar">
			<!--begin::Filter-->
			<button type="button" class="btn btn-light-primary me-5 mt-5" data-kt-menu-trigger="click" data-kt-menu-placement="left-start" data-kt-menu-id="filter-menu">
				<i class="ki-duotone ki-filter fs-2">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>Filter</button>
			<!--begin::Menu 1-->
			<div class="menu menu-sub menu-sub-dropdown w-sm-500px w-md-600px" data-kt-menu="true" data-kt-menu-id="filter-menu">
				<!--begin::Header-->
				<div class="px-7 py-5">
					<div class="fs-5 text-gray-900 fw-bold"><?= Yii::$app->lang->t('extra', 'extra9') ?></div>
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
							<div class="col-md-12">
								<label class="fw-semibold fs-6 mb-2" for="contactFilter"><?= Yii::$app->lang->t('front_home', 'contact') ?></label>
								<select id="contactSelect" class="form-select contact" data-control="select2" name="contact">
									<!-- <option value="">All Contacts</option> -->
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
			<div class="d-flex mt-5">
				<button type="button" class="btn btn-primary" id="btn-KM" data-bs-target="#modal_form_cash" data-id="KM">
					<i class="ki-duotone ki-plus fs-2"></i> <?= Yii::$app->lang->t('kasbackend', 'kasbackend2') ?>
				</button>
				<button type="button" class="btn btn-danger ms-5" id="btn-KK" data-bs-target="#modal_form_cash" data-id="KK">
					<i class="ki-duotone ki-plus fs-2"></i> <?= Yii::$app->lang->t('kasbackend', 'kasout') ?>
				</button>
			</div>
			<!--end::Add user-->
		</div>
	</div>
	<!--begin::Card body-->
	<div class="card-body pt-5">
		<div id="liveAlertPlaceholder"></div>

		<!-- Mass Action Buttons Group -->
		<div class="btn-group mb-3" id="mass-action-buttons" style="display: none;">
			<button type="button" class="btn btn-danger" id="btn-delete-mass">
				<i class="fas fa-trash me-2"></i><?= Yii::$app->lang->t('back_home', 'chat53') ?>
			</button>
			<button type="button" class="btn btn-success" id="btn-approve-mass">
				<i class="fas fa-check me-2"></i>Approve
			</button>
			<button type="button" class="btn btn-warning" id="btn-cancel-mass">
				<i class="fas fa-ban me-2"></i>Cancel
			</button>
		</div>
		<div class="table-responsive">
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
						<th class="text-center min-w-200px">Nomor Transaksi</th>
						<th class="text-center"><?= Yii::$app->lang->t('cashbackend', 'cashbackend4') ?></th>
						<th class="text-center"><?= Yii::$app->lang->t('cashbackend', 'cashbackend6') ?></th>
						<th class="text-center"><?= Yii::$app->lang->t('cashbackend', 'cashbackend7') ?></th>
						<!-- <th class="text-center"><?= Yii::$app->lang->t('cashbackend', 'cashbackend8') ?></th> -->
					</tr>
				</thead>
				<tbody>
					<!-- Table content will be loaded dynamically -->
				</tbody>
			</table>
		</div>
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
<style>
	.select-checkbox {
		transition: transform 0.35s cubic-bezier(0.25, 1, 0.5, 1),
			opacity 0.35s ease,
			box-shadow 0.35s ease,
			filter 0.35s ease;
	}

	.select-checkbox.checked-anim {
		transform: scale(1.3);
		opacity: 1;
		box-shadow: 0 0 10px rgba(0, 150, 255, 0.4);
		filter: brightness(1.2);
	}

	#mass-action-buttons {
		opacity: 0;
		transform: scale(0.95);
		transition: opacity 0.3s ease, transform 0.3s ease;
		pointer-events: none;
		/* Biar gak bisa diklik pas ngilang */
	}

	#mass-action-buttons.show {
		opacity: 1;
		transform: scale(1);
		pointer-events: auto;
	}
</style>
<!--end::Card-->
<script>
	// Menambahkan event listener untuk modal
	$('#modal_form_cash').on('shown.bs.modal', function() {
		var modalDialog = $(this).find('.modal-dialog');
		modalDialog.css('max-width', '95%'); // Memaksa modal menjadi 95% lebar
	});

	// Initialize Select2 for Contacts

	function toggleMassActionButtons() {
		const $btn = $('#mass-action-buttons');

		if ($('.select-checkbox:checked').length > 0) {
			if (!$btn.hasClass('show')) {
				$btn.css('display', 'block');
				setTimeout(() => {
					$btn.addClass('show');
				}, 10); // delay kecil biar transition kebaca
			}
		} else {
			$btn.removeClass('show');
			setTimeout(() => {
				$btn.css('display', 'none');
			}, 300); // sesuai durasi transisi CSS
		}
	}

	$.fn.dataTable.ext.errMode = "none"; // Matikan semua warning DataTables
	$(document).ready(function() {
		// console.log($('#contactSelect'));
		$('#contactSelect').select2({
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
			placeholder: <?= json_encode(Yii::$app->lang->t('extra', 'extra8')) ?>,
			allowClear: true,
			templateResult: formatContact,
			templateSelection: formatContactSelection,
			// dropdownParent: $('#modal_form_cash').length ? $('#modal_form_cash') : $(document.body)
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

		var translate = <?= json_encode(Yii::$app->lang->t('extra', 'extra11')) ?>;
		var translate1 = <?= json_encode(Yii::$app->lang->t('extra', 'extra12')) ?>;
		var translate2 = <?= json_encode(Yii::$app->lang->t('extra', 'extra13')) ?>;
		var translate3 = <?= json_encode(Yii::$app->lang->t('back_home', 'chat53')) ?>;
		$("#datatable").DataTable({
			scrollX: true,
			autoWidth: false,
			processing: false,
			serverSide: false, // Jika ingin server-side, ubah ke true
			lengthMenu: [15, 30, 50, 75, 100],
			pageLength: 15, // Set default ke 5
			order: [], // Tidak ada kolom yang diurutkan saat load pertama

			language: {
				info: `${translate1}`,
				infoEmpty: `${translate2}`,
				emptyTable: `
            <div style="text-align: center; padding: 20px 0;">
               <img width='250px' src='https://cdni.iconscout.com/illustration/premium/thumb/employee-is-unable-to-find-sensitive-data-illustration-download-in-svg-png-gif-file-formats--no-found-misplaced-files-business-pack-illustrations-8062128.png'/>
                <div style="font-weight: bold; font-size: 16px; margin-top : 8px;">${translate}</div>
            </div>
        `,
				zeroRecords: `
            <div style="text-align: center; padding: 20px 0;">
               <img width='250px' src='https://cdni.iconscout.com/illustration/premium/thumb/employee-is-unable-to-find-sensitive-data-illustration-download-in-svg-png-gif-file-formats--no-found-misplaced-files-business-pack-illustrations-8062128.png'/>
                <div style="font-weight: bold; font-size: 16px; margin-top : 8px;"><?= Yii::$app->lang->t('extra', 'extra11') ?></div>
            </div>
        `,
				loadingRecords: `
            <div style="text-align: center; padding: 20px 0;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div style="margin-top: 10px;"><?= Yii::$app->lang->t('extra', 'extra94') ?></div>
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
					d.contact = $('select[name="contact"]').val();
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
						return `<div style="display: flex; justify-content: center; align-items: center; margin-left:8px;">
                    <input type="checkbox" class="select-checkbox" value="${row.cashid}">
                </div>`;
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
                            <ul class="dropdown-menu px-2">
                            	<li><a class="dropdown-item text-hover-success btn-light" id="edit-contact" data-bs-toggle="modal" data-bs-target="#modal_form_cash" data-id="${row.cashid}"style="cursor: pointer;"><i class="fas fa-edit"></i> Edit</a></li>
								<li><button href="javascript:void(0);" class="dropdown-item text-hover-danger delete-contact" data-id="${row.cashid}"><i class="fas fa-trash" style="cursor: pointer;"></i> ${translate3}</button></li>
                            </ul>
                        </div>`;
					}
				},
				{
					data: "status",
					className: "text-center",
					render: function(data, type, row) {
						// Tentukan status text dan warna berdasarkan nilai status
						let statusInfo = {
							0: {
								text: "Draf",
								color: "primary",
								icon: "fa-file-alt"
							},
							1: {
								text: "Approved",
								color: "success",
								icon: "fa-check-circle"
							},
							5: {
								text: "Cancelled",
								color: "warning",
								icon: "fa-ban"
							},
							10: {
								text: "Rejected",
								color: "danger",
								icon: "fa-times-circle"
							}
						};

						// Default jika status tidak dikenali
						let statusText = "Unknown";
						let statusBg = "secondary";
						let statusIcon = "fa-question-circle";
						let statusOptions = "";

						// Jika status dikenali, gunakan nilai yang sesuai
						if (statusInfo[data]) {
							statusText = statusInfo[data].text;
							statusBg = statusInfo[data].color;
							statusIcon = statusInfo[data].icon;

							// Buat opsi dropdown berdasarkan status saat ini
							if (data == 0) {
								statusOptions = `
									<li><a class="dropdown-item update-status d-flex align-items-center" href="#" data-id="${row.cashid}" data-status="1">
										<i class="fas fa-check-circle text-success me-2"></i> Approve
									</a></li>
									<li><a class="dropdown-item update-status d-flex align-items-center" href="#" data-id="${row.cashid}" data-status="5">
										<i class="fas fa-ban text-warning me-2"></i> Cancel
									</a></li>
								`;
							} else if (data == 1) {
								statusOptions = `
									<li><a class="dropdown-item update-status d-flex align-items-center" href="#" data-id="${row.cashid}" data-status="5">
										<i class="fas fa-ban text-warning me-2"></i> Cancel
									</a></li>
								`;
							} else if (data == 5) {
								statusOptions = `
									<li><a class="dropdown-item update-status d-flex align-items-center" href="#" data-id="${row.cashid}" data-status="1">
										<i class="fas fa-check-circle text-success me-2"></i> Approve
									</a></li>
								`;
							}else if (data == 10) {
								statusOptions = `
									<li><a class="dropdown-item update-status d-flex align-items-center" href="#" data-id="${row.cashid}" data-status="0">
										<i class="fas fa-file-alt text-primary me-2"></i> Draf
									</a></li>
								`;
							}
						}

						// Return status dengan desain yang diperbarui
						return `
							<div class="dropdown dropend">
								<button class="btn btn-${statusBg} btn-sm px-3 py-2 d-flex align-items-center justify-content-center mx-auto"
										type="button" data-bs-toggle="dropdown" aria-expanded="false"
										style="min-width: 110px; border-radius: 6px;">
									<i class="fas ${statusIcon} me-2"></i>
									<span>${statusText}</span>
									<i class="fas fa-chevron-down ms-2 opacity-50" style="font-size: 0.8em;"></i>
								</button>
								<ul class="dropdown-menu px-2 py-2 shadow-sm">${statusOptions}</ul>
							</div>`;
					}
				},
				{
					data: "cashno",
					className: "text-start",
					// render: function(data) {
					// 	return data ? `${data}` : `No Data`;
					// }
				},
				{
					data: "contact_name",
					className: "text-start",
					defaultContent: "-",
					render: function(data) {
						return data ? `${data}` : `No Data`;
					}
				},
				{
					data: "tranno",
					className: "text-start",
					// render: function(data) {
					// 	return data ? `${data}` : `No Data`;
					// }
				},
				{
					data: "kasdate",
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
				// {
				// 	data: "kasduedate",
				// 	className: "text-center",
				// 	render: function(data) {
				// 		if (!data) return '-';
				// 		// Convert date format if needed
				// 		const date = new Date(data);
				// 		const day = String(date.getDate()).padStart(2, '0');
				// 		const month = String(date.getMonth() + 1).padStart(2, '0');
				// 		const year = date.getFullYear();
				// 		return `${day}-${month}-${year}`;
				// 	}
				// },
				{
					data: "subtotal",
					className: "text-end",
					render: function(data) {
						return "Rp." + Intl.NumberFormat('id-ID').format(data);
					}
				},
				{
					data: "total",
					className: "text-end",
					render: function(data) {
						return "Rp." + Intl.NumberFormat('id-ID').format(data);
					}
				},
				// {
				// 	data: "sisa_pembayaran",
				// 	className: "text-center",
				// 	render: function(data) {
				// 		let formattedNumber = "Rp." + Intl.NumberFormat('id-ID').format(Math.abs(data));
				// 		let label = data < 0 ? "<?= Yii::$app->lang->t('cashbackend', 'cashbackend14') ?>" : "<?= Yii::$app->lang->t('cashbackend', 'cashbackend13') ?>";
				// 		let color = data < 0 ? "text-success" : "text-danger"; // Warna hijau untuk kelebihan, merah untuk kurang bayar
				// 		return `<span class="${color}">${label}: ${formattedNumber}</span>`;
				// 	}
				// },
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

			let cashId = $(this).data("id");
			let newStatus = $(this).data("status");
			let button = $(this).closest(".dropdown").find("button");

			// Status info untuk UI
			let statusInfo = {
				0: {
					text: "Waiting",
					color: "primary",
					icon: "fa-file-alt"
				},
				1: {
					text: "Approved",
					color: "success",
					icon: "fa-check-circle"
				},
				5: {
					text: "Cancelled",
					color: "warning",
					icon: "fa-ban"
				},
				10: {
					text: "Rejected",
					color: "danger",
					icon: "fa-times-circle"
				}
			};

			// Set sementara untuk UI feedback
			if (statusInfo[newStatus]) {
				const statusText = statusInfo[newStatus].text;
				const statusBg = statusInfo[newStatus].color;
				const statusIcon = statusInfo[newStatus].icon;

				// Update tampilan tombol
				button.attr('class', `btn btn-${statusBg} btn-sm px-3 py-2 d-flex align-items-center justify-content-center mx-auto`);
				button.html(`
					<i class="fas ${statusIcon} me-2"></i>
					<span>${statusText}</span>
					<i class="fas fa-chevron-down ms-2 opacity-50" style="font-size: 0.8em;"></i>
				`);
			}

			// Kirim request ke endpoint kas
			$.ajax({
				url: "<?= Url::to(['/kas/updatestatus']) ?>",
				type: "POST",
				data: {
					id: cashId,
					status: newStatus,
					_csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
				},
				headers: {
					"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
				},
				success: function(response) {
					if (response.success) {
						let alertType;
						if (newStatus == 1) {
							alertType = "success"; // Default
						} else if (newStatus == 5) {
							alertType = "warning";
						} else {
							alertType = "info";
						}

						// Tampilkan notifikasi sukses
						showBootstrapAlert("<?= Yii::$app->lang->t('extra', 'extra63') . " " ?>" + response.statusText, alertType);

						// Reload DataTable tanpa reset pagination
						$("#datatable").DataTable().ajax.reload(null, false);
					} else {
						// Tampilkan pesan error
						showBootstrapAlert("<?= Yii::$app->lang->t('extra', 'extra64') . " " ?>" + response.message, "danger");

						// Reload DataTable untuk mengembalikan status asli
						$("#datatable").DataTable().ajax.reload(null, false);
					}
				},
				error: function(xhr) {
					// Tampilkan pesan error lengkap jika tersedia
					let errorMsg = "Terjadi kesalahan saat memperbarui status.";
					if (xhr.responseJSON && xhr.responseJSON.message) {
						errorMsg += " " + xhr.responseJSON.message;
					}

					showBootstrapAlert(errorMsg, "danger");

					// Reload DataTable untuk mengembalikan status asli
					$("#datatable").DataTable().ajax.reload(null, false);
				}
			});
		});

		// Fungsi untuk menampilkan alert Bootstrap dengan ikon dan auto-dismiss
		function showBootstrapAlert(message, type) {
			let alertPlaceholder = document.getElementById("liveAlertPlaceholder");
			if (!alertPlaceholder) {
				// Jika element alert placeholder tidak ada, buat baru dan masukkan ke card-body
				alertPlaceholder = document.createElement("div");
				alertPlaceholder.id = "liveAlertPlaceholder";
				document.querySelector(".card-body").prepend(alertPlaceholder);
			}

			let wrapper = document.createElement("div");
			wrapper.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show d-flex align-items-center" role="alert">
                    <div class="me-3">
						${type === 'success' ? '<i class="fas fa-check-circle fa-lg text-success"></i>' : ''}
						${type === 'danger' ? '<i class="fas fa-exclamation-circle fa-lg text-danger"></i>' : ''}
						${type === 'warning' ? '<i class="fas fa-exclamation-triangle fa-lg text-warning"></i>' : ''}
						${type === 'info' ? '<i class="fas fa-info-circle fa-lg text-info"></i>' : ''}
                    </div>
                    <div>${message}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;

			alertPlaceholder.innerHTML = ""; // Hapus alert sebelumnya
			alertPlaceholder.append(wrapper);

			// Auto dismiss alert setelah 5 detik
			setTimeout(function() {
				const alert = bootstrap.Alert.getOrCreateInstance(wrapper.querySelector('.alert'));
				if (alert) alert.close();
			}, 5000);
		}

		// Select All checkbox di thead
		// $("#select-all").on("click", function() {
		// 	$("tbody .select-checkbox").prop("checked", this.checked);
		// 	toggleMassActionButtons();
		// });

		$("#select-all").on("click", function() {
			const isChecked = this.checked;

			$("tbody .select-checkbox").each(function() {
				if (isChecked) {
					$(this)
						.prop("checked", true)
						.addClass("checked-anim");

					// Biar efek tetap smooth, delay penghapusan class sedikit lebih lama
					setTimeout(() => {
						$(this).removeClass("checked-anim");
					}, 400);
				} else {
					$(this).prop("checked", false);
				}
			});

			toggleMassActionButtons();
		});

		// Update checkbox Select All jika ada perubahan di checkbox per baris
		$("#datatable tbody").on("change", ".select-checkbox", function() {
			$("#select-all").prop(
				"checked",
				$(".select-checkbox").length === $(".select-checkbox:checked").length
			);
			toggleMassActionButtons();
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
				type: 'get',
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

		$('#modal_form_cash').on('hidden.bs.modal', function() {
			$(document).find('input, select')
				.prop('readonly', false)
				.prop('disabled', false)
				.removeClass('select2-readonly'); // Ini kalau kamu pakai class khusus untuk gaya read-only Select2
		});


		var deletemessage1 = "<?= Yii::$app->lang->t('extra', 'extra44') ?>";
		var deletemessage2 = "<?= Yii::$app->lang->t('extra', 'extra45') ?>";
		var deletemessage3 = "<?= Yii::$app->lang->t('extra', 'extra46') ?>";
		var deletemessage3koma1 = "<?= Yii::$app->lang->t('extra', 'extra46.1') ?>";
		var deletemessage4 = "<?= Yii::$app->lang->t('back_home', 'chat34') ?>";
		var deletemessage5 = "<?= Yii::$app->lang->t('back_home', 'chat53') ?>";
		var deletemessage6 = "<?= Yii::$app->lang->t('extra', 'extra65') ?>";
		var deletemessage7 = "<?= Yii::$app->lang->t('extra', 'extra66') ?>";
		var deletemessage8 = "<?= Yii::$app->lang->t('extradouble', 'double2') ?>";
		// Function to perform mass actions (approve, delete, cancel)
		function performMassAction(action, statusCode) {
			let selectedIds = $(".select-checkbox:checked")
				.map(function() {
					return $(this).val(); // Get ID from checked checkboxes
				})
				.get();

			if (selectedIds.length === 0) {
				Swal.fire({
					title: "<?= Yii::$app->lang->t('extra', 'extra60') ?>",
					text: "<?= Yii::$app->lang->t('extra', 'extra61') ?>",
					icon: "warning",
					confirmButtonColor: "#d33",
					confirmButtonText: "OK"
				});
				return;
			}

			let actionText, actionColor, actionIcon, confirmText;

			// Set text and style based on action
			switch (action) {
				case 'delete':
					actionText = deletemessage5;
					actionColor = "#d33";
					actionIcon = "warning";
					confirmText = deletemessage5;
					break;
				case 'approve':
					actionText = "approve";
					actionColor = "#34e04b";
					actionIcon = "question";
					confirmText = `${deletemessage8}, approve!`;
					break;
				case 'cancel':
					actionText = "cancel";
					actionColor = "#f39c12";
					actionIcon = "question";
					confirmText = `${deletemessage8}, cancel!`;
					break;
			}

			Swal.fire({
				title: `${deletemessage6} ${actionText}?`,
				text: `${deletemessage7} ${actionText} ${selectedIds.length} data.`,
				icon: actionIcon,
				showCancelButton: true,
				confirmButtonColor: actionColor,
				cancelButtonColor: "#6e7d88",
				confirmButtonText: confirmText,
				cancelButtonText: "Batal"
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: '<?= Url::to(['/kas/massaction']) ?>',
						type: "POST",
						data: {
							ids: selectedIds,
							action: action,
							status: statusCode,
							_csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
						},
						headers: {
							"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
						},
						success: function(response) {
							// Reload DataTable
							$("#datatable").DataTable().ajax.reload();

							// Hide mass action buttons after success
							$("#mass-action-buttons").hide();
							$("#select-all").prop("checked", false);

							// Show success notification
							let successTitle, successText, successIcon;
							switch (action) {
								case 'delete':
									successTitle = "<?= Yii::$app->lang->t('back_home', 'chat57') ?>";
									successText = "<?= Yii::$app->lang->t('extra', 'extra59') ?>";
									successIcon = "success";
									break;
								case 'approve':
									successTitle = "Berhasil!";
									successText = "<?= Yii::$app->lang->t('extra', 'extra67') ?>";
									successIcon = "success";
									break;
								case 'cancel':
									successTitle = "Berhasil!";
									successText = "<?= Yii::$app->lang->t('extra', 'extra68') ?>";
									successIcon = "success";
									break;
							}

							Swal.fire({
								title: successTitle,
								text: successText,
								icon: successIcon,
								timer: 2000,
								showConfirmButton: false
							});
						},
						error: function(xhr) {
							console.error("Error:", xhr.responseText);

							// Show error notification
							Swal.fire({
								title: "Gagal!",
								title: "<?= Yii::$app->lang->t('extra', 'extra60') ?>",
								text: "<?= Yii::$app->lang->t('extra', 'extra61') ?>",
								icon: "error",
								confirmButtonColor: "#d33",
								confirmButtonText: "OK"
							});
						}
					});
				}
			});
		}

		// Handle mass delete button click
		$('#btn-delete-mass').on('click', function() {
			performMassAction('delete', 10); // 10 = delete status code
		});

		// Handle mass approve button click
		$('#btn-approve-mass').on('click', function() {
			performMassAction('approve', 1); // 1 = approve status code
		});

		// Handle mass cancel button click
		$('#btn-cancel-mass').on('click', function() {
			performMassAction('cancel', 5); // 5 = cancel status code
		});

		$('#btn-hapusmassal').on('click', function() {
			let selectedIds = $(".select-checkbox:checked")
				.map(function() {
					return $(this).val(); // Ambil nilai ID dari checkbox yang dicentang
				})
				.get();

			if (selectedIds.length === 0) {
				Swal.fire({
					title: "<?= Yii::$app->lang->t('extra', 'extra60') ?>",
					text: "<?= Yii::$app->lang->t('extra', 'extra61') ?>",
					icon: "warning",
					confirmButtonColor: "#d33",
					confirmButtonText: "OK"
				});
				return;
			}

			Swal.fire({
				title: deletemessage1,
				text: deletemessage3 + " " + selectedIds.length + " " + deletemessage3koma1,
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#d33",
				cancelButtonColor: "#3085d6",
				confirmButtonText: deletemessage5,
				cancelButtonText: deletemessage4
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
								title: "<?= Yii::$app->lang->t('extra', 'extra62') ?>",
								text: "<?= Yii::$app->lang->t('extra', 'extra59') ?>",
								icon: "success",
								timer: 2000,
								showConfirmButton: false
							});
						},
						error: function(xhr) {
							console.error("Error:", xhr.responseText);

							// Tampilkan notifikasi error
							Swal.fire({
								title: "<?= Yii::$app->lang->t('extra', 'extra60') ?>",
								text: "<?= Yii::$app->lang->t('extra', 'extra61') ?>",
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
					title: "<?= Yii::$app->lang->t('extra', 'extra60') ?>",
					text: "<?= Yii::$app->lang->t('extra', 'extra61') ?>",
					icon: "error",
					confirmButtonColor: "#d33",
					confirmButtonText: "OK"
				});
				return;
			}

			Swal.fire({
				title: deletemessage1,
				text: deletemessage2,
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#d33",
				cancelButtonColor: "#3085d6",
				confirmButtonText: deletemessage5,
				cancelButtonText: deletemessage4
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
								title: "<?= Yii::$app->lang->t('extra', 'extra62') ?>",
								text: "<?= Yii::$app->lang->t('extra', 'extra59') ?>",
								icon: "success",
								timer: 2000,
								showConfirmButton: false
							});
						},
						error: function(xhr) {
							console.error('Error:', xhr.responseText);

							// Tampilkan notifikasi error
							Swal.fire({
								title: "<?= Yii::$app->lang->t('extra', 'extra60') ?>",
								text: "<?= Yii::$app->lang->t('extra', 'extra61') ?>",
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
	/* Existing styles */
	/* .pagination.page-item.page-link {
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
	} */

	/* New styles for mass action buttons */
	/* #mass-action-buttons {
		margin-bottom: 15px;
	}

	#mass-action-buttons .btn {
		margin-right: 8px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 0.6rem 1.2rem;
		border-radius: 0.475rem;
		transition: all 0.2s ease;
		font-weight: 500;
		box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
	}

	#mass-action-buttons .btn-danger {
		background-color: #f1416c;
		border-color: #f1416c;
	}

	#mass-action-buttons .btn-success {
		background-color: #50cd89;
		border-color: #50cd89;
	}

	#mass-action-buttons .btn-warning {
		background-color: #ffac1b;
		border-color: #ffac1b;
	}

	#mass-action-buttons .btn:hover {
		filter: brightness(90%);
		/* transform: translateY(-1px);
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
	} */

	/* Status button styling */
	.dropdown button[data-bs-toggle="dropdown"] {
		transition: all 0.3s ease;
	}

	.dropdown button[data-bs-toggle="dropdown"]:hover {
		filter: brightness(95%);
	}

	/* Alert styling */
	.alert {
		border-radius: 8px;
		box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
	}

	/* Hover effect on dropdown items */
	.dropdown-item {
		transition: all 0.2s ease;
	}

	.dropdown-item:hover {
		border-radius: 4px;
		/* margin: 0 5px; */
		transform: translateX(5px);
	}

	.dropdown-item.text-hover-success:hover {
		background-color: rgba(80, 205, 137, 0.1);
	}

	.dropdown-item.text-hover-danger:hover {
		background-color: rgba(241, 65, 108, 0.1);
	}

	.dropdown-item.text-hover-warning:hover {
		background-color: rgba(255, 172, 27, 0.1);
	}

	.dropdown-item.text-hover-primary:hover {
		background-color: rgba(0, 158, 247, 0.1);
	}
</style>