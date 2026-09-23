<?php
$title = Yii::$app->lang->t('coas', 'coas9');
$this->title = $title;

use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="card mt-5">
	<div class="card-header border-0 pt-6">
		<div class="card-title">
			<div class="d-flex align-items-center position-relative my-2">
				<form method="get" action="index" id="search" class="w-100">
					<div class="position-relative">
						<span class="position-absolute top-50 start-0 translate-middle-y ms-3">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"
								fill="#a1a5b7">
								<path
									d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
							</svg>
						</span>
						<input data-kt-docs-table-filter="search" type="text" name="search"
							class="form-control form-control-solid w-250px ps-10"
							placeholder="<?= Yii::$app->lang->t('extra', 'extra31') ?>">

						<span class="position-absolute top-50 end-0 translate-middle-y me-3 d-none" id="clear-search">
							<i class="ki-duotone ki-cross fs-2 text-gray-500 cursor-pointer" style="opacity: 0.5;"></i>
						</span>
					</div>
				</form>
			</div>
		</div>

		<div class="card-toolbar">
			<button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
				data-kt-menu-placement="left-start">
				<i class="ki-duotone ki-filter fs-2">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>Filter
			</button>

			<div class="menu menu-sub menu-sub-dropdown w-sm-500px w-md-600px" data-kt-menu="true"
				data-kt-menu-id="filter-menu">
				<div class="px-7 py-5">
					<div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
				</div>

				<div class="separator border-gray-200"></div>

				<div class="px-7 py-5" data-kt-user-table-filter="form">
					<form id="filterForm" method="get" action="index">
						<div class="row">
							<div class="col-md-12 col-lg-12">
								<label class="fw-semibold fs-6 mb-2"
									for="categoryFilter"><?= Yii::$app->lang->t('coas', 'coas3') ?></label>
								<select id="categorySelect2" class="form-select category" data-control="select2"
									name="category">
									<option selected><?php $model->category['text'] ?? ''; ?></option>
								</select>
							</div>
						</div>

						<div class="form-group text-end mt-5 d-flex justify-content-end gap-2">
							<button type="button" id="resetFilterButton" class="btn btn-light-danger">
								<i class="fa-solid fa-rotate-left me-1"></i>Reset
							</button>
							<button type="submit" id="filterButton" class="btn btn-lg btn-primary"><i
									class="fa-sharp fa-solid fa-filter"></i></button>
						</div>
					</form>
				</div>
			</div><a href="<?= \yii\helpers\Url::to(['create']) ?>" id="btn-add-coas"
				class="btn btn-primary btn-add-coas" data-url="<?= \yii\helpers\Url::to(['create']) ?>">
				<i class="ki-duotone ki-plus fs-2"></i>
				<?= Yii::$app->lang->t('extra', 'extra32') ?>
			</a>

		</div>
	</div>

	<div class="card-body pt-5">
		<div id="liveAlertPlaceholder"></div>
		<div class="btn-group mb-3" id="mass-action-buttons" style="display: none;">
			<button type="button" class="btn btn-danger" id="btn-delete-mass">
				<i class="fas fa-trash me-2"></i><?= Yii::$app->lang->t('back_home', 'chat53') ?>
			</button>
		</div>

		<table class="table align-middle table-row-dashed fs-6 gy-5" id="datatable">
			<thead>
				<tr class="text-start bg-gray-100 fs-6 text-gray-500 fw-bold fs-7 text-uppercase gs-0">
					<th class="d-none"></th>
					<th class="text-center min-w-50px">
						<div class="form-check form-check-custom form-check-solid form-check-sm px-3">
							<input class="form-check-input" type="checkbox" id="select-all">
						</div>
					</th>
					<th class="text-center min-w-50px">
						<?= Yii::$app->lang->t('cashbackend', 'cashbackend1') ?>
					</th>
					<th class="text-start min-w-125px"><?= Yii::$app->lang->t('coas', 'coas1') ?></th>
					<th class="text-start min-w-250px"><?= Yii::$app->lang->t('coas', 'coas2') ?></th>
					<th class="text-start min-w-150px"><?= Yii::$app->lang->t('coas', 'coas3') ?></th>
					<th class="text-center min-w-100px">Saldo</th>
				</tr>
			</thead>
			<tbody class="text-gray-600 fw-semibold">

			</tbody>
		</table>
	</div>
</div>
<div class="modal fade" id="modal_form_coas" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-fullscreen-md-down modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= Yii::$app->lang->t('coas', 'coas9') ?></h5>
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
	$.fn.dataTable.ext.errMode = "none";

	function toggleMassActionButtons() {
		if ($('.select-checkbox:checked').length > 0) {
			$('#mass-action-buttons').show();
		} else {
			$('#mass-action-buttons').hide();
		}
	}

	$("#select-all").on("click", function() {
		$("tbody .select-checkbox").prop("checked", this.checked);
		toggleMassActionButtons();
	});

	$("#datatable tbody").on("change", ".select-checkbox", function() {
		$("#select-all").prop(
			"checked",
			$(".select-checkbox").length === $(".select-checkbox:checked").length
		);
		toggleMassActionButtons();
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

	function performMassAction(action, statusCode) {
		console.log($("tbody .select-checkbox:checked"));
		let selectedIds = $(".select-checkbox:checked")
			.map(function() {
				return $(this).val();
			})
			.get();

		console.log(selectedIds);
		if (selectedIds.length === 0) {
			Swal.fire({
				title: "Tidak Ada Data Terpilih!",
				text: "Pilih minimal satu data untuk diproses.",
				icon: "warning",
				confirmButtonColor: "#d33",
				confirmButtonText: "OK"
			});
			return;
		}

		let actionText, actionColor, actionIcon, confirmText;

		if (action === 'delete') {
			actionText = "hapus";
			actionColor = "#d33";
			actionIcon = "warning";
			confirmText = "Ya, hapus!";
		}

		Swal.fire({
			title: `Yakin ingin ${actionText}?`,
			text: `Anda akan ${actionText} ${selectedIds.length} data.`,
			icon: actionIcon,
			showCancelButton: true,
			confirmButtonColor: actionColor,
			cancelButtonColor: "#6e7d88",
			confirmButtonText: confirmText,
			cancelButtonText: "Batal"
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '<?= Url::to(['/coas/massaction']) ?>',
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
					/*
											// Versi lama yang pernah dipakai:
											// success: function (response) {
											//     $('#datatable').DataTable().ajax.reload();
											//     $('#mass-action-buttons').hide();
											//     $('#select-all').prop('checked', false);
											//     Swal.fire({
											//         title: 'Terhapus!',
											//         text: 'Data berhasil dihapus.',
											//         icon: 'success'
											//     });
											// }
											*/

					success: function(response) {
						if (!response || response.success !== true) {
							Swal.fire({
								title: "Gagal!",
								text: response && response.message ? response.message : "Tidak ada data yang terhapus.",
								icon: "error",
								confirmButtonColor: "#d33",
								confirmButtonText: "OK"
							});
							return;
						}

						$('#datatable').DataTable().ajax.reload();

						$("#mass-action-buttons").hide();
						$("#select-all").prop("checked", false);

						let successTitle, successText, successIcon;

						if (action === 'delete') {
							successTitle = "Terhapus!";
							successText = "Data berhasil dihapus.";
							successIcon = "success";
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

						Swal.fire({
							title: "Gagal!",
							text: "Terjadi kesalahan saat memproses data.",
							icon: "error",
							confirmButtonColor: "#d33",
							confirmButtonText: "OK"
						});
					}
				});
			}
		});
	}

	$('#btn-delete-mass').on('click', function() {
		performMassAction('delete', 10);
	});

	$('#resetFilterButton').on('click', function() {
		$('#search input[name="search"]').val('');
		$('#filterForm select[name="category"]').val('').trigger('change');

		const table = $('#datatable').DataTable();
		table.search('').columns().search('').draw();
		table.order([]);
		table.ajax.reload();

		const filterMenu = document.querySelector("[data-kt-menu-id='filter-menu']");
		if (filterMenu && KTMenu.getInstance(filterMenu)) {
			KTMenu.getInstance(filterMenu).hide();
		}

		window.history.replaceState({}, '', window.location.pathname);
	});

	$("#filterForm").submit(function(e) {
		e.preventDefault();
		$("#datatable").DataTable().ajax.reload();
		let filterMenu = document.querySelector("[data-kt-menu-id='filter-menu']");
		if (filterMenu) {
			KTMenu.getInstance(filterMenu).hide();
		}
	});

	$(document).ready(function() {
		$("#categorySelect2").select2({
			ajax: {
				url: "<?= \yii\helpers\Url::to(['coas/categorylist']) ?>",
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
			placeholder: <?= json_encode(Yii::$app->lang->t('coas', 'coas5')) ?>,
			allowClear: true
		});

		var translate = <?= json_encode(Yii::$app->lang->t('extra', 'extra11')) ?>;
		var translate1 = <?= json_encode(Yii::$app->lang->t('extra', 'extra12')) ?>;
		var translate2 = <?= json_encode(Yii::$app->lang->t('extra', 'extra13')) ?>;
		var translate3 = <?= json_encode(Yii::$app->lang->t('back_home', 'chat53')) ?>;

		var table = $("#datatable").DataTable({
			scrollX: false,
			autoWidth: false,
			processing: false,
			serverSide: false,
			lengthMenu: [15, 30, 50, 75, 100],
			pageLength: 15,
			order: [],
			language: {
				info: `${translate1}`,
				infoEmpty: `${translate2}`,
				emptyTable: `
					<div style="text-align: center; padding: 20px 0;">
						<img width='250px' src='https://cdni.iconscout.com/illustration/premium/thumb/employee-is-unable-to-find-sensitive-data-illustration-download-in-svg-png-gif-file-formats--no-found-misplaced-files-business-pack-illustrations-8062128.png'/>
						<div style="font-weight: bold; font-size: 16px; margin-top: 8px;"> ${translate}</div>
					</div>
				`,
				zeroRecords: `
					<div style="text-align: center; padding: 20px 0;">
						<img width='250px' src='https://cdni.iconscout.com/illustration/premium/thumb/employee-is-unable-to-find-sensitive-data-illustration-download-in-svg-png-gif-file-formats--no-found-misplaced-files-business-pack-illustrations-8062128.png'/>
						<div style="font-weight: bold; font-size: 16px; margin-top: 8px;">${translate}</div>
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
				url: "/coas/list",
				data: function(d) {
					d.category = $('select[name="category"]').val();
					d.search = $('input[name="search"]').val();
				},
				complete: function() {
					$('.table-loading-overlay').remove();
				}
			},
			columns: [{
					data: 'coa_id',
					visible: false
				},
				{
					data: null,
					className: "text-center",
					orderable: false,
					render: function(data, type, row) {
						return `
					<div class="form-check form-check-custom form-check-solid form-check-sm">
						<input class="form-check-input select-checkbox" type="checkbox" value="${row.coa_id}">
					</div>`;
					}
				},
				{
					data: null,
					render: function(data, type, row) {
						const editUrl = "<?= \yii\helpers\Url::to(['update']) ?>" + `?id=${row.coa_id}`;

						return `
					<div class="dropdown text-center dropend">
						<button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
						<i class="fa-sharp fa-solid fa-list"></i>
						</button>

						<ul class="dropdown-menu px-2">
						<li>
							<a href="${editUrl}"
							class="dropdown-item text-hover-success btn-light edit-contact"
							data-id="${row.coa_id}"
							data-url="${editUrl}"
							style="cursor:pointer;">
							<i class="fas fa-edit"></i> Edit
							</a>
						</li>
						<li>
							<button class="dropdown-item text-hover-danger delete-contact"
									data-id="${row.coa_id}"
									style="cursor:pointer;">
							<i class="fas fa-trash"></i> ${translate3}
							</button>
						</li>
						</ul>
					</div>`;
					}
				},
				{
					data: "coa_no",
					className: 'text-start',
					render: function(data, type, row) {
						return data;
					}
				},
				{
					data: "coa_name",
					className: 'text-start',
					render: function(data, type, row) {
						return `
					<a class="dropdown-item text-hover-success" id="edit-coas" data-bs-toggle="modal" data-bs-target="#modal_form_coas"
					 data-id="${row.coa_id}" style="cursor: pointer;">
						${data}
					</a>`;
					}
				},
				{
					data: "coa_category",
					className: 'text-start',
					render: function(data, type, row) {
						return data;
					}
				},
				{
					data: "saldo",
					className: 'text-center',
					render: function(data, type, row) {
						return data ?
							`<span class="text text-end">${parseFloat(data).toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 })}</span>` :
							"-";
					}
				}
			],
			"initComplete": function(settings, json) {}
		});

		$(document).on('click', '.edit-contact', function(e) {
			if (e.which !== 1) return; // selain klik kiri biarkan browser
			e.preventDefault(); // tahan navigasi

			const url = $(this).data('url');

			$.ajax({
				url: url,
				success: function(data) {
					const doc = new DOMParser().parseFromString(data, 'text/html');
					$('#modal-content').html(doc.body.innerHTML);
					// $('input, select').prop('disabled', true);
					$('#modal_form_coas').modal('show');
				},
				error: function() {
					$('#modal-content').html('<p>Error loading form.</p>');
				}
			});
		});

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
						url: '<?= Url::to(['/coas/delete']) ?>',
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

							$('#datatable').DataTable().ajax.reload();

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

		$("#search").submit(function(e) {
			e.preventDefault();
			$("#datatable").DataTable().ajax.reload();
		});

		$("#filterForm").submit(function(e) {
			e.preventDefault();
			$("#datatable").DataTable().ajax.reload();
		});

		$(document).on('click', '#edit-coas', function() {
			var id = $(this).data('id')
			$.ajax({
				url: '<?= \yii\helpers\Url::to(['update']) ?>?id=' + id,
				success: function(data) {
					let parser = new DOMParser();
					let doc = parser.parseFromString(data, 'text/html');
					$('#modal-content').html(doc.body.innerHTML);
					$('input, select').prop('disabled', true);
					$('#modal_form_coas').modal('show');
				},
				error: function() {
					$('#modal-content').html('<p>Error loading form.</p>');
				}
			});
		});

		$(document).on('click', 'a.btn-add-coas', function(e) {
			if (e.which !== 1) return; // biarkan klik kanan / tengah
			e.preventDefault(); // tahan navigasi

			const url = $(this).data('url');

			$.ajax({
				url: url,
				success: function(data) {
					const doc = new DOMParser().parseFromString(data, 'text/html');
					$('#modal-content').html(doc.body.innerHTML);
					$('#modal_form_coas').modal('show');
				},
				error: function() {
					$('#modal-content').html('<p>Error loading form.</p>');
				}
			});
		});

		$(document).on('click', '.delete-coas', function() {
			var coa_id = $(this).data('id');
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
						url: '<?= Url::to(['/coas/deletecategory']) ?>',
						type: 'post',
						data: {
							id: coa_id,
							_csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
						},
						headers: {
							"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
						},
						success: function(response) {

							$('#datatable').DataTable().ajax.reload();

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