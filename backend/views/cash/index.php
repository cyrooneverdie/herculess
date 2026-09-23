<?php
$title = Yii::$app->lang->t('cashbackend', 'cashbackend9');
$this->title = $title;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>


<!--begin::Search-->
<div class="card mt-5">
	<div class="card-header border-0 pt-6">
		<div class="card-title">
			<div class="d-flex align-items-center position-relative my-1 mt-lg-5">
				<i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"></i>
				<form method="get" action="index" style="width: 100%;" id="search">
					<span class="position-absolute top-50 start-0 translate-middle-y ms-3">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"
							fill="#a1a5b7">
							<path
								d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
						</svg>
					</span>
					<input data-kt-docs-table-filter="search" type="text" name="search"
						class="form-control form-control-solid w-250px ps-13"
						placeholder="<?= Yii::$app->lang->t('kasbackend', 'kasbackend1') ?>" autocomplete="off" />
					<span class="position-absolute top-50 end-0 translate-middle-y me-3 d-none" id="clear-search">
						<i class="ki-duotone ki-cross fs-2 text-gray-500 cursor-pointer" style="opacity: 0.5;"></i>
					</span>
				</form>
			</div>
		</div>
		<div class="card-toolbar">
			<button type="button" class="btn btn-light-primary me-5 mt-5" data-kt-menu-trigger="click"
				data-kt-menu-placement="left-start" data-kt-menu-id="filter-menu">
				<i class="ki-duotone ki-filter fs-2">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>Filter</button>
			<div class="menu menu-sub menu-sub-dropdown w-sm-500px w-md-600px" data-kt-menu="true"
				data-kt-menu-id="filter-menu">
				<div class="px-7 py-5">
					<div class="fs-5 text-gray-900 fw-bold"><?= Yii::$app->lang->t('extra', 'extra9') ?></div>
				</div>
				<div class="separator border-gray-200"></div>
				<div class="px-7 py-5" data-kt-user-table-filter="form">
					<form id="filterForm" method="get" action="index">
						<div class="row">
							<div class="col-md-12">
								<label class="fw-semibold fs-6 mb-2"
									for="contactFilter"><?= Yii::$app->lang->t('front_home', 'contact') ?></label>
								<select id="contactSelect" class="form-select contact" data-control="select2"
									name="contact">
								</select>
							</div>
						</div>
						<div class="form-group text-end mt-5">
							<button type="submit" id="filterButton" class="btn btn-lg btn-primary"><i
									class="fa-sharp fa-solid fa-filter"></i></button>
						</div>
					</form>
				</div>
			</div>
			<!-- <div class="d-flex mt-5">
				<a href="<?= Url::to(['create', 'kastype' => 'KM', 'type' => 1]) ?>" class="btn btn-primary btn-kas"
					data-url="<?= Url::to(['create', 'kastype' => 'KM', 'type' => 1]) ?>">
					<i class="ki-duotone ki-plus fs-2"></i>
					<?= Yii::$app->lang->t('kasbackend', 'kasbackend2') ?>
				</a>

				<a href="<?= Url::to(['create', 'kastype' => 'KK', 'type' => 0]) ?>" class="btn btn-danger ms-5 btn-kas"
					data-url="<?= Url::to(['create', 'kastype' => 'KK', 'type' => 0]) ?>">
					<i class="ki-duotone ki-plus fs-2"></i>
					<?= Yii::$app->lang->t('kasbackend', 'kasout') ?>
				</a>
			</div> -->
		</div>
	</div>
	<div class="card-body pt-5">
		<div id="liveAlertPlaceholder"></div>

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
							<div class="form-check form-check-custom form-check-solid form-check-sm px-3">
								<input class="form-check-input" type="checkbox" id="select-all">
							</div>
						</th>
						<th class="text-center"><?= Yii::$app->lang->t('cashbackend', 'cashbackend1') ?></th>
						<!-- <th class="text-center">Status</th> -->
						<th class="text-center"><?= Yii::$app->lang->t('cashbackend', 'cashbackend2') ?></th>
						<th class="text-center"><?= Yii::$app->lang->t('cashbackend', 'cashbackend3') ?></th>
						<th class="text-center min-w-200px"><?= Yii::$app->lang->t('tran', 'reference') ?>
						</th>
						</th>
						<th class="text-center"><?= Yii::$app->lang->t('cashbackend', 'cashbackend4') ?></th>
						<!-- <th class="text-center"><?= Yii::$app->lang->t('cashbackend', 'cashbackend6') ?></th> -->
						<th class="text-center"><?= Yii::$app->lang->t('cashbackend', 'cashbackend7') ?></th>
					</tr>
				</thead>
				<tbody>
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

<script>
	$(document).ready(function () {
		initMasking();
		const params = new URLSearchParams(window.location.search);

		const contact = params.get('contact');
		const search = params.get('search');

		if (contact !== null) {
			$('select[name="contact"]').val(contact).trigger('change');
		}

		if (search !== null) {
			$('input[name="search"]').val(search);
		}
	});

	$('#modal_form_cash').on('shown.bs.modal', function () {
		var modalDialog = $(this).find('.modal-dialog');
		modalDialog.css('max-width', '95%'); // Memaksa modal menjadi 95% lebar
	});

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
	$(document).ready(function () {
		$('#contactSelect').select2({
			ajax: {
				url: "<?= \yii\helpers\Url::to(['tran/contactlist']) ?>",
				type: "GET",
				dataType: "json",
				delay: 250,
				data: function (params) {
					return {
						q: params.term,
					};
				},
				processResults: function (data) {
					// Transform data from {"data":[...]} to format expected by Select2
					return {
						results: data.data.map(function (contact) {
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
				dataSrc: "data",
				url: "<?= \yii\helpers\Url::to(['cash/list']) ?>",
				data: function (d) {
					const contact = $('select[name="contact"]').val();
					const search = $('input[name="search"]').val();

					d.contact = contact;
					d.search = search;

					const params = new URLSearchParams();
					if (contact) params.set('contact', contact);
					if (search) params.set('search', search);
					const newUrl = window.location.pathname + '?' + params.toString();
					window.history.replaceState({}, '', newUrl);
				}
			},
			columns: [{
				data: "cashid",
				visible: false
			},
			{
				data: null,
				className: "text-center",
				orderable: false,
				render: function (data, type, row) {
					return `
					<div class="form-check form-check-custom form-check-solid form-check-sm px-3">
						<input type="checkbox" class="form-check-input row-checkbox select-checkbox" value="${row.cashid}">
					</div>`;
				}
			},
			{
				data: null, render: function (data, type, row) {
					const editUrl = "<?= \yii\helpers\Url::to(['update']) ?>" + `?id=${row.cashid}`;

					return `
					<div class="dropdown text-center dropend">
						<button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
						<i class="fa-sharp fa-solid fa-list"></i>
						</button>

						<ul class="dropdown-menu px-2">
						<li>
							<a href="${editUrl}"
							class="dropdown-item text-hover-success btn-light edit-contact"
							data-id="${row.cashid}"
							data-url="${editUrl}"
							style="cursor:pointer;">
							<i class="fas fa-edit"></i> Edit
							</a>
						</li>
						<li>
							<button class="dropdown-item text-hover-danger delete-contact"
									data-id="${row.cashid}"
									style="cursor:pointer;">
							<i class="fas fa-trash"></i> ${translate3}
							</button>
						</li>
						</ul>
					</div>`;
				}
			},
			// {
			// 	data: "status",
			// 	className: "text-center",
			// 	render: function (data, type, row) {
			// 		let statusInfo = {
			// 			0: {
			// 				text: "Draf",
			// 				color: "primary",
			// 				icon: "fa-file-alt"
			// 			},
			// 			1: {
			// 				text: "Approved",
			// 				color: "success",
			// 				icon: "fa-check-circle"
			// 			},
			// 			5: {
			// 				text: "Cancelled",
			// 				color: "warning",
			// 				icon: "fa-ban"
			// 			},
			// 			10: {
			// 				text: "Rejected",
			// 				color: "danger",
			// 				icon: "fa-times-circle"
			// 			}
			// 		};

			// 		let statusText = "Unknown";
			// 		let statusBg = "secondary";
			// 		let statusIcon = "fa-question-circle";
			// 		let statusOptions = "";

			// 		if (statusInfo[data]) {
			// 			statusText = statusInfo[data].text;
			// 			statusBg = statusInfo[data].color;
			// 			statusIcon = statusInfo[data].icon;

			// 			if (data == 0) {
			// 				statusOptions = `
			// 						<li><a class="dropdown-item update-status d-flex align-items-center" href="#" data-id="${row.cashid}" data-status="1">
			// 							<i class="fas fa-check-circle text-success me-2"></i> Approve
			// 						</a></li>
			// 						<li><a class="dropdown-item update-status d-flex align-items-center" href="#" data-id="${row.cashid}" data-status="5">
			// 							<i class="fas fa-ban text-warning me-2"></i> Cancel
			// 						</a></li>
			// 					`;
			// 			} else if (data == 1) {
			// 				statusOptions = `
			// 						<li><a class="dropdown-item update-status d-flex align-items-center" href="#" data-id="${row.cashid}" data-status="5">
			// 							<i class="fas fa-ban text-warning me-2"></i> Cancel
			// 						</a></li>
			// 					`;
			// 			} else if (data == 5) {
			// 				statusOptions = `
			// 						<li><a class="dropdown-item update-status d-flex align-items-center" href="#" data-id="${row.cashid}" data-status="1">
			// 							<i class="fas fa-check-circle text-success me-2"></i> Approve
			// 						</a></li>
			// 					`;
			// 			} else if (data == 10) {
			// 				statusOptions = `
			// 						<li><a class="dropdown-item update-status d-flex align-items-center" href="#" data-id="${row.cashid}" data-status="0">
			// 							<i class="fas fa-file-alt text-primary me-2"></i> Draf
			// 						</a></li>
			// 					`;
			// 			}
			// 		}

			// 		return `
			// 			<div class="dropdown dropend">
			// 				<button class="btn btn-${statusBg} btn-sm px-3 py-2 d-flex align-items-center justify-content-center mx-auto"
			// 						type="button" data-bs-toggle="dropdown" aria-expanded="false"
			// 						style="min-width: 110px; border-radius: 6px;">
			// 					<i class="fas ${statusIcon} me-2"></i>
			// 					<span>${statusText}</span>
			// 					<i class="fas fa-chevron-down ms-2 opacity-50" style="font-size: 0.8em;"></i>
			// 				</button>
			// 				<ul class="dropdown-menu px-2 py-2 shadow-sm">${statusOptions}</ul>
			// 			</div>`;
			// 	}
			// },
			{
				data: "cashnumber",
				className: "text-center",
				render: function (data) {
					return data ? `${data}` : `No Data`;
				}
			},
			{
				data: "contact_name",
				className: "text-center",
				defaultContent: "-",
				render: function (data) {
					return data ? `${data}` : `No Data`;
				}
			},
			{
				data: "tranno",
				className: "text-center",
				render: function (data) {
					return data ? `${data}` : `No Data`;
				}
			},
			{
				data: "cashdate",
				className: "text-center",
				render: function (data) {
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
			// 	data: "subtotal",
			// 	className: "text-center",
			// 	render: function (data) {
			// 		return "Rp." + Intl.NumberFormat('id-ID').format(data);
			// 	}
			// },
			{
				data: "total",
				className: "text-center",
				render: function (data) {
					return "Rp." + Intl.NumberFormat('id-ID').format(data);
				}
			},
			],
		});

		$(document).on("click", ".update-status", function (e) {
			e.preventDefault();

			let cashId = $(this).data("id");
			let newStatus = $(this).data("status");
			let button = $(this).closest(".dropdown").find("button");

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

			if (statusInfo[newStatus]) {
				const statusText = statusInfo[newStatus].text;
				const statusBg = statusInfo[newStatus].color;
				const statusIcon = statusInfo[newStatus].icon;

				button.attr('class', `btn btn-${statusBg} btn-sm px-3 py-2 d-flex align-items-center justify-content-center mx-auto`);
				button.html(`
					<i class="fas ${statusIcon} me-2"></i>
					<span>${statusText}</span>
					<i class="fas fa-chevron-down ms-2 opacity-50" style="font-size: 0.8em;"></i>
				`);
			}

			$.ajax({
				url: "<?= Url::to(['/cash/updatestatus']) ?>",
				type: "POST",
				data: {
					id: cashId,
					status: newStatus,
					_csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
				},
				headers: {
					"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
				},
				success: function (response) {
					if (response.success) {
						let alertType;
						if (newStatus == 1) {
							alertType = "success"; // Default
						} else if (newStatus == 5) {
							alertType = "warning";
						} else {
							alertType = "info";
						}

						showBootstrapAlert("<?= Yii::$app->lang->t('extra', 'extra63') . " " ?>" + response.statusText, alertType);

						$("#datatable").DataTable().ajax.reload(null, false);
					} else {
						showBootstrapAlert("<?= Yii::$app->lang->t('extra', 'extra64') . " " ?>" + response.message, "danger");

						$("#datatable").DataTable().ajax.reload(null, false);
					}
				},
				error: function (xhr) {
					let errorMsg = "Terjadi kesalahan saat memperbarui status.";
					if (xhr.responseJSON && xhr.responseJSON.message) {
						errorMsg += " " + xhr.responseJSON.message;
					}

					showBootstrapAlert(errorMsg, "danger");

					$("#datatable").DataTable().ajax.reload(null, false);
				}
			});
		});

		function showBootstrapAlert(message, type) {
			let alertPlaceholder = document.getElementById("liveAlertPlaceholder");
			if (!alertPlaceholder) {
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

			setTimeout(function () {
				const alert = bootstrap.Alert.getOrCreateInstance(wrapper.querySelector('.alert'));
				if (alert) alert.close();
			}, 5000);
		}

		$("#select-all").on("click", function () {
			const isChecked = this.checked;

			$("tbody .select-checkbox").each(function () {
				if (isChecked) {
					$(this)
						.prop("checked", true)
						.addClass("checked-anim");

					setTimeout(() => {
						$(this).removeClass("checked-anim");
					}, 400);
				} else {
					$(this).prop("checked", false);
				}
			});

			toggleMassActionButtons();
		});

		$("#datatable tbody").on("change", ".select-checkbox", function () {
			$("#select-all").prop(
				"checked",
				$(".select-checkbox").length === $(".select-checkbox:checked").length
			);
			toggleMassActionButtons();
		});

		$("#search").submit(function (e) {
			e.preventDefault(); // Jangan biarkan form reload halaman
			$("#datatable").DataTable().ajax.reload();
		});

		$("#filterForm").submit(function (e) {
			e.preventDefault(); // Jangan biarkan form reload halaman
			$("#datatable").DataTable().ajax.reload();
			let filterMenu = document.querySelector("[data-kt-menu-id='filter-menu']");
			if (filterMenu) {
				KTMenu.getInstance(filterMenu).hide();
			}
		});

		$(document).on('click', '.edit-contact', function (e) {
			if (e.which !== 1) return;
			e.preventDefault();

			const url = $(this).data('url');

			$.ajax({
				url: url,
				success: function (data) {
					const doc = new DOMParser().parseFromString(data, 'text/html');
					$('#modal-content').html(doc.body.innerHTML);
					// $('input, select').prop('disabled', true);
					$('#modal_form_cash').modal('show');
				},
				error: function () {
					$('#modal-content').html('<p>Error loading form.</p>');
				}
			});
		});

		$(document).on('click', 'a.btn-kas', function (e) {
			if (e.which !== 1) return;      // biarkan selain klik kiri
			e.preventDefault();             // tahan navigasi default

			const url = $(this).data('url'); // sama dengan href

			$.ajax({
				url: url,
				type: 'get',
				success: function (data) {
					const doc = new DOMParser().parseFromString(data, 'text/html');
					$('#modal-content').html(doc.body.innerHTML);
					$('#modal_form_cash').modal('show');
				},
				error: function () {
					$('#modal-content').html('<p>Error loading form.</p>');
				}
			});
		});


		$('#modal_form_cash').on('hidden.bs.modal', function () {
			$(document).find('input, select')
				.prop('readonly', false)
				.prop('disabled', false)
				.removeClass('select2-readonly');
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
			let selectedIds = $(".select-checkbox:checked")
				.map(function () {
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
						url: '<?= Url::to(['/cash/massaction']) ?>',
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
						success: function (response) {
							$("#datatable").DataTable().ajax.reload();
							$("#mass-action-buttons").hide();
							$("#select-all").prop("checked", false);

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
						error: function (xhr) {
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

		$('#btn-delete-mass').on('click', function () {
			performMassAction('delete', 10);
		});

		$('#btn-approve-mass').on('click', function () {
			performMassAction('approve', 1);
		});

		$('#btn-cancel-mass').on('click', function () {
			performMassAction('cancel', 5);
		});

		$('#btn-hapusmassal').on('click', function () {
			let selectedIds = $(".select-checkbox:checked")
				.map(function () {
					return $(this).val(); 
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
						url: '<?= Url::to(['/cash/deletemassal']) ?>', 
						type: "POST",
						data: {
							ids: selectedIds,
							_csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
						},
						headers: {
							"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
						},
						success: function (response) {

							$("#datatable").DataTable().ajax.reload();

							$("#btn-hapusmassal").hide();

							Swal.fire({
								title: "<?= Yii::$app->lang->t('extra', 'extra62') ?>",
								text: "<?= Yii::$app->lang->t('extra', 'extra59') ?>",
								icon: "success",
								timer: 2000,
								showConfirmButton: false
							});
						},
						error: function (xhr) {
							console.error("Error:", xhr.responseText);

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

		$(document).on('click', '.delete-contact', function () {
			var contactId = $(this).data('id'); 

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
						url: '<?= Url::to(['/cash/delete']) ?>',
						type: 'post',
						data: {
							id: contactId,
							_csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
						},
						headers: {
							"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
						},
						success: function (response) {
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
						error: function (xhr) {
							console.error('Error:', xhr.responseText);

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