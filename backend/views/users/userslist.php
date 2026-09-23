<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\Alert;
use yii\widgets\LinkPager;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

$this->title = Yii::$app->lang->t('extrasidebar', 'extrasidebar23');
?>

<div class="card">
	<div class="card-header border-0 pt-6 sticky-top bg-white shadow-sm"
		style="top: var(--kt-app-header-height, 70px); z-index: 1010;">
		<div class="card-title flex-grow-1">
			<div class="d-flex align-items-center position-relative my-1">
				<div class="card-title d-flex justify-content-end">
					<form method="get" style="width: 100%;" id="search">
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
								placeholder="<?= Yii::$app->lang->t('back_home', 'chat51') ?>" autocomplete="off" />

							<span class="position-absolute top-50 end-0 translate-middle-y me-3 d-none"
								id="clear-search">
								<i class="ki-duotone ki-cross fs-2 text-gray-500 cursor-pointer"
									style="opacity: 0.5;"></i>
							</span>
						</div>
					</form>

				</div>
			</div>
		</div>

		<div class="card-toolbar flex-shrink-0">
			<div class="d-flex gap-2 flex-wrap">
				<button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
					data-kt-menu-placement="left-start" data-kt-menu-id="filter-menu">
					<i class="ki-duotone ki-filter fs-2">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>Filter</button>

				<a href="<?= Url::to(['users/create']) ?>" id="btn-add-users" class="btn btn-primary btn-add-users"
					data-url="<?= \yii\helpers\Url::to(['users/create']) ?>">
					<i class="ki-duotone ki-plus fs-2"></i><?= Yii::$app->lang->t('add', 'add1') ?>
				</a>

				<div class="menu menu-sub menu-sub-dropdown w-sm-500px w-md-600px" data-kt-menu="true"
					data-kt-menu-id="filter-menu-contact">
					<div class="px-7 py-5">
						<div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
					</div>
					<div class="separator border-gray-200"></div>
					<div class="px-7 py-5">
						<form id="filterForm">
							<div class="row">

								<div class="col-md-12 mb-5">
									<label class="fw-semibold fs-6 mb-2">Jabatan</label>
									<select id="filter-position" class="form-select">
										<option value="">Pilih Jabatan</option>
									</select>
								</div>

							</div>
							<div class="form-group text-end mt-5">
								<button type="button" class="btn btn-light me-2" id="btn-reset-filter">Reset</button>
								<button type="submit" id="btn-apply-filter" class="btn btn-lg btn-primary">
									<i class="fa-sharp fa-solid fa-filter"></i> Filter
								</button>
							</div>
						</form>
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="card-body">
		<table class="table align-middle table-row-dashed fs-6 gy-5" id="datatable">
			<thead>
				<tr class="text-start bg-gray-100  fs-6 text-gray-500 fw-bold fs-7 text-uppercase gs-0">
					<th class="text-center min-w-50px"><?= Yii::$app->lang->t('cashbackend', 'cashbackend1') ?></th>
					<th><?= Yii::$app->lang->t('back_home', 'chat27') ?></th>
					<th><?= Yii::$app->lang->t('contact', 'position') ?></th>
					<th><?= Yii::$app->lang->t('front_home', 'contact') ?></th>
					<th><?= Yii::$app->lang->t('back_home', 'chat28') ?></th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>

<div class="modal fade" id="modal_form_user" tabindex="-1" aria-labelledby="modal-title-user" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-title-user">
					<?= Yii::$app->lang->t('back_home', 'notif33') ?>
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="modal-content-user">
				<p class="text-muted">Loading...</p>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h5 class="modal-title fw-bold" id="filterModalLabel">Opsi Filter</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body pt-3">
				<div class="mb-4">
					<label class="form-label fw-semibold text-gray-700 mb-3">Jabatan</label>
					<select id="filter-position-modal" class="form-select form-select-lg">
						<option value="">Pilih Jabatan</option>
					</select>
				</div>

			</div>
			<div class="modal-footer border-0 pt-0">
				<button type="button" class="btn btn-light" id="btn-reset-filter-modal">Reset</button>
				<button type="button" class="btn btn-primary" id="btn-apply-filter-modal">Apply</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).on('click', '#btn-add-users', function (e) {
		e.preventDefault();

		let url = $(this).data('url');

		$.ajax({
			url: url,
			type: 'GET',
			success: function (data) {
				let parser = new DOMParser();
				let doc = parser.parseFromString(data, 'text/html');
				$('#modal-content-user').html(doc.body.innerHTML);
				$('#modal-title-user').html("<?= Yii::$app->lang->t('back_home', 'user') ?>");
				$('#modal_form_user').modal('show');
			},
			error: function (xhr) {
				$('#modal-content-user').html('<p class="text-danger">Error loading form.</p>');
			}
		});
	});

	let companyId = '<?= Yii::$app->session->get("companyid") ?>'; // Ambil dari session
	var translate = <?= json_encode(Yii::$app->lang->t('extra', 'extra11')) ?>;
	var translate1 = <?= json_encode(Yii::$app->lang->t('extra', 'extra12')) ?>;
	var translate2 = <?= json_encode(Yii::$app->lang->t('extra', 'extra13')) ?>;
	var translate3 = <?= json_encode(Yii::$app->lang->t('extra', 'extra76')) ?>;
	var translate4 = <?= json_encode(Yii::$app->lang->t('extra', 'extra74')) ?>;
	var translate5 = <?= json_encode(Yii::$app->lang->t('back_home_users', 'aksi1')) ?>;
	var translate6 = <?= json_encode(Yii::$app->lang->t('back_home_users', 'aksi2')) ?>;
	var translate7 = <?= json_encode(Yii::$app->lang->t('back_home_users', 'aksi3')) ?>;

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
			emptyTable: companyId ? `
			<div style="text-align: center; padding: 20px 0;">
			   <img width='250px' src='https://cdni.iconscout.com/illustration/premium/thumb/employee-is-unable-to-find-sensitive-data-illustration-download-in-svg-png-gif-file-formats--no-found-misplaced-files-business-pack-illustrations-8062128.png'/>
				<div style="font-weight: bold; font-size: 16px; margin-top : 8px;">${translate}</div>
			</div>
		` : `
			<div style="text-align: center; padding: 20px 0;">
				<div style="font-weight: bold; font-size: 16px; margin-top : 8px;"> Please Make A Company First</div>
			</div>
		`,
			zeroRecords: `
			<div style="text-align: center; padding: 20px 0;">
			   <img width='250px' src='https://cdni.iconscout.com/illustration/premium/thumb/employee-is-unable-to-find-sensitive-data-illustration-download-in-svg-png-gif-file-formats--no-found-misplaced-files-business-pack-illustrations-8062128.png'/>
				<div style="font-weight: bold; font-size: 16px; margin-top : 8px;">${translate}</div>
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
			className: 'row-selected'
		},
		ajax: {
			type: "GET",
			dataSrc: "data",
			url: "/users/list",
			data: function (d) {
				d.search = $('input[name="search"]').val();
				d.role = $('select[name="role"]').val();
				d.position = $('#filter-position').val();
			}
		},
		columns: [
			{
				data: null,
				render: function (data, type, row) {
					const userid = row.userid || ''; // Fallback to empty string if undefined
					const updateUrl = userid
						? '<?= Url::to(['users/update', 'id' => '__USERID__']) ?>'.replace('__USERID__', userid)
						: '#';
					return `
						<div class="dropdown text-center dropend">
						<button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
							<i class="fa-sharp fa-solid fa-list"></i>
								</button>
								<ul class="dropdown-menu px-2">
								<li>
										<button type="button"
											class="dropdown-item text-hover-success btn-update-user"
											data-id="${userid}" ${!userid ? 'disabled' : ''}>
											<i class="fas fa-edit" style="cursor: pointer;"></i> ${translate5}
										</button>
									</li>

									<li><button href="javascript:void(0);" class="dropdown-item text-hover-danger delete-contact" data-id="${userid}"><i class="fas fa-trash" style="cursor: pointer;"></i> ${translate6}</button></li>
									<li>
									<button href="javascript:void(0);" 
											class="dropdown-item text-hover-warning reset-password" 
											data-id="${userid}">
										<i class="fas fa-key" style="cursor: pointer;"></i> ${translate7}
									</button>
									</li>

								</ul>
						</div>`;
				}
			},
			{
				data: null,
				render: function (data, type, row) {
					return `
					<div class="d-flex flex-row align-items-center px-0 py-0">
						<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
						<div class="symbol-label">
							<img src="/assets/media/svg/avatars/blank.svg" alt="Default Avatar" class="w-100" />
						</div>
						</div>

						<div class="d-flex flex-column justify-content-center">
							<a href="#" class="text-gray-800 text-hover-primary mb-0">${row.name}</a>
							<input type="hidden" class="user-email" value="${row.email}" />
						</div>
					</div>
					`;
				}
			},
			{
				data: "role",
			},
			{
				data: "contact_name",
				render: function (data, type, row) {
					return data ? `<span class="text-gray-800">${data}</span>` : ``;
				}
			},
			{
				data: "created_at",
				className: "text-center",
				// render: function(data, type, row) {
				// 	let types = [];

				// 	if (row.contact_isvendor == 1) {
				// 		types.push(`<span class="badge bg-primary fw-bold">Vendor</span>`);
				// 	}
				// 	if (row.contact_iscustomer == 1) {
				// 		types.push(`<span class="badge bg-success fw-bold">Customer</span>`);
				// 	}

				// 	return types.length > 0 ? types.join(" ") : "";
				// }
			},
			{
				data: 'status'
			}
		],
	});

	$("#search").submit(function (e) {
		e.preventDefault();
		$("#datatable").DataTable().ajax.reload();
	});

	$("#filterForm").submit(function (e) {
		e.preventDefault();
		$("#datatable").DataTable().ajax.reload();
		let filterMenu = document.querySelector("[data-kt-menu-id='filter-menu']");
		if (filterMenu) {
			KTMenu.getInstance(filterMenu).hide();
		}
	});

	let currentFilters = { position: '' };

	function loadFilterOptions() {
		if ($('#filter-position').hasClass('select2-hidden-accessible')) {
			$('#filter-position').select2('destroy');
		}

		$('#filter-position').select2({
			ajax: {
				url: '<?= Url::to(['/enum/list']) ?>',
				type: 'GET',
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						enumtype: 'position',
						search: params.term || '',
						for: 'select2',
						page: params.page || 1
					};
				},
				processResults: function (data, params) {
					params.page = params.page || 1;
					return {
						results: data.data.map(function (item) {
							return { id: item.enumid, text: item.enumtext_id };
						}),
						pagination: { more: data.pagination && data.pagination.more }
					};
				},
				cache: true
			},
			placeholder: 'Pilih Jabatan',
			allowClear: true,
			minimumInputLength: 0,
			dropdownParent: $('#filterForm')
		});
	}

	$('[data-kt-menu-trigger="click"][data-kt-menu-id="filter-menu"]').on('click', function () {
		setTimeout(loadFilterOptions, 0);
	});

	loadFilterOptions();
	$('#filterForm').on('shown.bs.modal', function () {

		if ($('#filter-position').hasClass('select2-hidden-accessible')) {
			$('#filter-position').select2('destroy');
		}

		loadFilterOptions();
	});

	$('#btn-reset-filter').on('click', function () {
		$('#filter-position').val(null).trigger('change');
		currentFilters = { position: '' };
		$('#datatable').DataTable().ajax.reload();
	});


	var deletemessage1 = "<?= Yii::$app->lang->t('extra', 'extra44') ?>";
	var deletemessage2 = "<?= Yii::$app->lang->t('extra', 'extra45') ?>";
	var deletemessage3 = "<?= Yii::$app->lang->t('extra', 'extra46') ?>";
	var deletemessage3koma1 = "<?= Yii::$app->lang->t('extra', 'extra46.1') ?>";
	var deletemessage4 = "<?= Yii::$app->lang->t('back_home', 'chat34') ?>";
	var deletemessage5 = "<?= Yii::$app->lang->t('button', 'extra79') ?>";

	var notif1 = "<?= Yii::$app->lang->t('back_home', 'notif1') ?>";
	var notif2 = "<?= Yii::$app->lang->t('back_home', 'notif2') ?>";
	var notif3 = "<?= Yii::$app->lang->t('back_home', 'notif3') ?>";
	var notif4 = "<?= Yii::$app->lang->t('back_home', 'notif4') ?>";
	var notif5 = "<?= Yii::$app->lang->t('back_home', 'notif5') ?>";
	var notif6 = "<?= Yii::$app->lang->t('back_home', 'notif6') ?>";
	var notif7 = "<?= Yii::$app->lang->t('back_home', 'notif7') ?>";
	var notif8 = "<?= Yii::$app->lang->t('back_home', 'notif8') ?>";
	var notif9 = "<?= Yii::$app->lang->t('back_home', 'notif9') ?>";
	var notif10 = "<?= Yii::$app->lang->t('back_home', 'notif10') ?>";
	var notif11 = "<?= Yii::$app->lang->t('back_home', 'notif11') ?>";
	var notif12 = "<?= Yii::$app->lang->t('back_home', 'notif12') ?>";
	var notif13 = "<?= Yii::$app->lang->t('back_home', 'notif13') ?>";
	var notif14 = "<?= Yii::$app->lang->t('back_home', 'notif14') ?>";
	var notif15 = "<?= Yii::$app->lang->t('back_home', 'notif15') ?>";
	var notif16 = "<?= Yii::$app->lang->t('back_home', 'notif16') ?>";
	var notif17 = "<?= Yii::$app->lang->t('back_home', 'notif17') ?>";
	var notif18 = "<?= Yii::$app->lang->t('back_home', 'notif18') ?>";
	var notif19 = "<?= Yii::$app->lang->t('back_home', 'notif19') ?>";
	var notif20 = "<?= Yii::$app->lang->t('back_home', 'notif20') ?>";
	var notif21 = "<?= Yii::$app->lang->t('back_home', 'notif21') ?>";

	var activatemessage2 = "<?= Yii::$app->lang->t('extra', 'activate_confirm_single') ?>";
	var activatemessage3 = "<?= Yii::$app->lang->t('extra', 'extra75') ?>";
	var activatemessage3koma1 = "<?= Yii::$app->lang->t('extra', 'activate_confirm_massal_count') ?>";

	$(document).on('click', '.activate-contact', function () {
		var userId = $(this).data('id');

		if (!userId) {
			Swal.fire({
				title: notif3,
				text: notif4,
				icon: "error",
				confirmButtonColor: "#28a745",
				confirmButtonText: "OK"
			});
			return;
		}

		Swal.fire({
			title: deletemessage1,
			text: activatemessage2,
			icon: "question",
			showCancelButton: true,
			confirmButtonColor: "#28a745",
			cancelButtonColor: "#6c757d",
			confirmButtonText: notif5,
			cancelButtonText: deletemessage4
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '<?= Url::to(['/users/activate']) ?>',
					type: 'post',
					data: {
						id: userId,
						_csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
					},
					headers: {
						"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
					},
					success: function (response) {
						$('#datatable').DataTable().ajax.reload();

						Swal.fire({
							title: notif6,
							text: notif7,
							icon: "success",
							timer: 2000,
							showConfirmButton: false
						});
					},
					error: function (xhr) {
						Swal.fire({
							title: "<?= Yii::$app->lang->t('extra', 'extra60') ?>!",
							text: "<?= Yii::$app->lang->t('extra', 'extra88') ?>.",
							icon: "error",
							confirmButtonColor: "#28a745",
							confirmButtonText: "OK"
						});
					}
				});
			}
		});
	});

	$(document).on('click', '#btn-cancel', function () {
		$('#modal_form_user').modal('hide');
		$('#datatable').DataTable().ajax.reload();
	});

	$(document).on('beforeSubmit', '#user-form', function (e) {
		e.preventDefault();
		var form = $(this);

		$.ajax({
			url: form.attr('action'),
			type: 'post',
			data: form.serialize(),
			success: function (response) {
				$('#modal_form_user').modal('hide'); // tutup modal
				$('#datatable').DataTable().ajax.reload(); // reload DataTable
				Swal.fire({
					title: notif19,
					text: notif8,
					icon: 'success',
					timer: 2000,
					showConfirmButton: false
				});
			},
			error: function () {
				Swal.fire(notif9, notif10, notif9);
			}
		});

		return false;
	});

	$(document).on('click', '.delete-contact', function () {
		var contactId = $(this).data('id'); // Ambil ID kontak

		if (!contactId) {
			Swal.fire({
				title: notif3,
				text: notif12,
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
					url: '<?= Url::to(['/users/delete']) ?>',
					type: 'post', // Harus POST
					data: {
						id: contactId,
						_csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
					},
					headers: {
						"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
					},
					success: function (response) {
						console.log('Response:', response);

						// Reload DataTable
						$('#datatable').DataTable().ajax.reload();

						// Tampilkan notifikasi sukses
						Swal.fire({
							title: notif13,
							text: notif14,
							icon: "success",
							timer: 2000,
							showConfirmButton: false
						});
					},
					error: function (xhr) {
						console.error('Error:', xhr.responseText);

						// Tampilkan notifikasi error
						Swal.fire({
							title: "<?= Yii::$app->lang->t('extra', 'extra60') ?>!",
							text: "<?= Yii::$app->lang->t('extra', 'extra88') ?>.",
							icon: "error",
							confirmButtonColor: "#d33",
							confirmButtonText: "OK"
						});
					}
				});
			}
		});
	});

	$(document).on('click', '.reset-password', function () {
		var userId = $(this).data('id');

		if (!userId) {
			Swal.fire({
				title: notif3,
				text: notif4,
				icon: "error",
				confirmButtonColor: "#d33",
				confirmButtonText: "OK"
			});
			return;
		}

		Swal.fire({
			title: notif15,
			text: notif16,
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: notif17,
			cancelButtonText: notif18
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '<?= Url::to(['/users/resetpassword']) ?>',
					type: 'post',
					data: {
						id: userId,
						_csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
					},
					headers: {
						"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>",
					},
					success: function (response) {
						if (response.success) {
							Swal.fire({
								title: notif19,
								text: response.message,
								icon: "success",
								timer: 2500,
								showConfirmButton: false
							});
						} else {
							Swal.fire("Gagal!", response.message, "error");
						}
					},
					error: function (xhr) {
						Swal.fire(notif3, notif20, notif9);
					}
				});
			}
		});
	});

	$(document).on('click', '.btn-update-user', function (e) {
		e.preventDefault();
		let id = $(this).data('id');
		if (!id) {
			alert(notif21);
			return;
		}
		let url = '<?= Url::to(["users/update"]) ?>?id=' + id;

		$.ajax({
			url: url,
			type: 'GET',
			success: function (data) {
				let parser = new DOMParser();
				let doc = parser.parseFromString(data, 'text/html');
				$('#modal-content-user').html(doc.body.innerHTML);
				$('#modal-title-user').html("<?= Yii::$app->lang->t('back_home', 'edit_user') ?>");
				$('#modal_form_user').modal('show');
			},
			error: function () {
				$('#modal-content-user').html('<p class="text-danger">Error loading form.</p>');
			}
		});
	});

</script>