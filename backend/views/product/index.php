<?php
$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar2');
$this->title = $title;

use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="card">
	<div class="card-header border-0 pt-6 sticky-top bg-white shadow-sm"
		style="top: var(--kt-app-header-height, 70px); z-index: 1010;">
		<div class="card-title flex-grow-1">
			<div class="d-flex align-items-center position-relative my-2 w-100">
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
							class="form-control form-control-solid w-100 w-md-250px ps-10"
							placeholder="<?= Yii::$app->lang->t('home', 'search') ?>" autocomplete="off" />
						<span class="position-absolute top-50 end-0 translate-middle-y me-3 d-none" id="clear-search">
							<i class="ki-duotone ki-cross fs-2 text-gray-500 cursor-pointer" style="opacity: 0.5;"></i>
						</span>
					</div>
				</form>
			</div>
		</div>

		<div class="card-toolbar flex-shrink-0">
			<div class="d-flex gap-2 flex-wrap">
				<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
					<i class="ki-duotone ki-filter fs-2">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
					Filter
				</button>

				<a href="/product/create" id="btn-add" class="btn btn-primary">
					<i class="ki-duotone ki-plus fs-2"></i> <?= Yii::$app->lang->t('home', 'add') ?>
				</a>
			</div>
		</div>
	</div>

	<div class="card-body">
		<button type="button" class="btn btn-danger text-light d-none mb-3" id="btn-hapusmassal">
			<?= Yii::$app->lang->t('back_home', 'chat53') ?>
		</button>
		<table class="table table-row-bordered fs-6 gy-5" id="datatable">
			<thead>
				<tr
					class="text-start text-gray-500 bg-gray-100 fw-bold fs-7 text-uppercase gs-0 border-bottom border-gray-200">
					<th class="text-center min-w-50px">
						<div class="form-check form-check-custom form-check-solid form-check-sm">
							<input class="form-check-input" type="checkbox" id="select-all">
						</div>
					</th>
					<th class="text-center min-w-80px"><?= Yii::$app->lang->t('produk_table', 'produk_action') ?>
					</th>
					<th class="text-start min-w-250px"><?= Yii::$app->lang->t('tax', 'label1') ?></th>
					</th>
					<th class="text-end min-w-150px">Harga
					</th>
					<th class="text-start min-w-150px">Fitur
					</th>
					<th class="text-start min-w-250px"><?= Yii::$app->lang->t('produk', 'produk_deskripsi') ?></th>
					</th>
				</tr>
			</thead>
			<tbody class="text-gray-800 fw-semibold text-center border-bottom border-gray-200">
			</tbody>
		</table>
	</div>
</div>

<div class="modal fade" id="modal_form_produk" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg-down modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= Yii::$app->lang->t('extrasidebar', 'extrasidebar13') ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div id="modal-content" class="nopadding">
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_form_variant" tabindex="-1" aria-labelledby="modalFormVariantLabel" aria-hidden="true"
	data-bs-backdrop="static" data-bs-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalFormVariantLabel">
					<?= Yii::$app->lang->t('extrasidebar', 'extrasidebar113') ?>
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div id="modal-content-variant" class="nopadding">

				</div>
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
					<label class="form-label fw-semibold text-gray-700 mb-3">Kategori</label>
					<select id="filter-category" class="form-select form-select-lg">
						<option value="">Pilih Kategori</option>
					</select>
				</div>

				<div class="mb-4">
					<label class="form-label fw-semibold text-gray-700 mb-3">Unit</label>
					<select id="filter-unit" class="form-select form-select-lg">
						<option value="">Pilih Unit</option>
					</select>
				</div>
			</div>
			<div class="modal-footer border-0 pt-0">
				<button type="button" class="btn btn-light" id="btn-reset-filter">Reset</button>
				<button type="button" class="btn btn-primary" id="btn-apply-filter">Apply</button>
			</div>
		</div>
	</div>
</div>

<script>
	var currentFilters = {
		category: '',
		unit: ''
	};

	var translate = <?= json_encode(Yii::$app->lang->t('extra', 'extra11')) ?>;
	var translate1 = <?= json_encode(Yii::$app->lang->t('extra', 'extra12')) ?>;
	var translate2 = <?= json_encode(Yii::$app->lang->t('extra', 'extra13')) ?>;
	var translate3 = <?= json_encode(Yii::$app->lang->t('extra', 'extra_copy')) ?>;
	var translate4 = <?= json_encode(Yii::$app->lang->t('extra', 'extra39')) ?>;
	var translate5 = <?= json_encode(Yii::$app->lang->t('extra', 'extra29')) ?>;
	var translate6 = <?= json_encode(Yii::$app->lang->t('extra', 'extra30')) ?>;
	var translate7 = <?= json_encode(Yii::$app->lang->t('back_home', 'chat53')) ?>;
	var deletemessage1 = "<?= Yii::$app->lang->t('extra', 'extra44') ?>";
	var deletemessage2 = "<?= Yii::$app->lang->t('extra', 'extra45') ?>";
	var deletemessage3 = "<?= Yii::$app->lang->t('extra', 'extra46') ?>";
	var deletemessage3koma1 = "<?= Yii::$app->lang->t('extra', 'extra46.1') ?>";
	var deletemessage4 = "<?= Yii::$app->lang->t('back_home', 'chat34') ?>";
	var deletemessage5 = "<?= Yii::$app->lang->t('back_home', 'chat53') ?>";

	$(document).ready(function () {
		const params = new URLSearchParams(window.location.search);
		const search = params.get('search');
		if (search !== null) {
			$('input[name="search"]').val(search);
		}

		initializeDataTable();
		setupEventHandlers();

		function initializeDataTable() {
			$.fn.dataTable.ext.errMode = "none";

			$("#datatable").DataTable({
				scrollX: false,
				autoWidth: false,
				fixedColumns: true,
				processing: true,
				serverSide: true,
				lengthMenu: [5, 10, 15, 30, 50, 75, 100],
				pageLength: 5,
				order: [],
				responsive: false,
				language: {
					info: translate1,
					infoEmpty: translate2,
					emptyTable: `
						<div style="text-align: center; padding: 20px 0;">
							<img width='250px' src='https://cdni.iconscout.com/illustration/premium/thumb/employee-is-unable-to-find-sensitive-data-illustration-download-in-svg-png-gif-file-formats--no-found-misplaced-files-business-pack-illustrations-8062128.png'/>
							<div style="font-weight: bold; font-size: 16px; margin-top : 8px;">${translate}</div>
						</div>`,
					zeroRecords: `
						<div style="text-align: center; padding: 20px 0;">
							<img width='250px' src='https://cdni.iconscout.com/illustration/premium/thumb/employee-is-unable-to-find-sensitive-data-illustration-download-in-svg-png-gif-file-formats--no-found-misplaced-files-business-pack-illustrations-8062128.png'/>
							<div style="font-weight: bold; font-size: 16px; margin-top : 8px;">${translate}</div>
						</div>`,
					loadingRecords: `
						<div style="text-align: center; padding: 20px 0;">
							<div class="spinner-border text-primary" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							<div style="margin-top: 10px;"><?= Yii::$app->lang->t('extra', 'extra94') ?></div>
						</div>`
				},
				select: {
					style: 'multi',
					selector: 'td:first-child input[type="checkbox"]',
					className: 'row-selected text-center'
				},
				ajax: {
					type: "GET",
					dataSrc: "data",
					url: "/product/list",
					data: function (d) {
						d.view_type = 'index';
						const search = $('input[name="search"]').val();
						d.search = search;
						d.filter_category = currentFilters.category;
						d.filter_unit = currentFilters.unit;
						const params = new URLSearchParams(window.location.search);
						params.set('search', search);
						const newUrl = window.location.pathname + '?' + params.toString();
						window.history.replaceState({}, '', newUrl);
					},
					complete: function () {
						$('.table-loading-overlay').remove();
					}
				},
				columns: [
					{
						data: null,
						className: "text-center align-middle",
						orderable: false,
						render: function (data, type, row) {
							return `
							<div class="form-check form-check-custom form-check-solid form-check-sm">
								<input type="checkbox" class="form-check-input" value="${row.productid}">
							</div>`;
						}
					},
					{
						data: null,
						className: "text-center align-middle",
						render: function (data, type, row) {
							const updateUrl = "<?= Url::to(['update']) ?>?id=" + row.productid;
							return `
							<div class="dropdown text-center dropend">
								<button class="btn btn-light btn-sm btn-icon" type="button" data-bs-toggle="dropdown">
									<i class="fa-sharp fa-solid fa-list"></i>
								</button>
								<ul class="dropdown-menu">
									<li>
										<a href="<?= Url::to(['product/detail']) ?>?id=${row.productid}" class="dropdown-item text-hover-primary">
											<i class="fas fa-eye me-2"></i> Detail
										</a>
									</li>
									<li>
										<a href="${updateUrl}" class="dropdown-item text-hover-success edit" data-id="${row.productid}" data-url="${updateUrl}">
											<i class="fas fa-edit me-2"></i> Edit
										</a>
									</li>
									<li>
										<a href="javascript:void(0);" class="dropdown-item text-hover-danger delete" data-id="${row.productid}">
											<i class="fas fa-trash me-2"></i> ${translate7}
										</a>
									</li>
									<li>
										<a href="javascript:void(0);" class="dropdown-item text-hover-info copy-text-btn" data-description="${row.description_product || ''}">
											<i class="fas fa-copy me-2"></i> ${translate3}
										</a>
									</li>
								</ul>
							</div>`;
						}
					},
					{ data: "productname", className: "text-start align-middle" },
					{
						data: 'purchaseprice',
						className: 'text-end align-middle',
						render: function (data) {
							return data ?
								`<span class="text text-end">${parseFloat(data).toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 })}</span>` :
								"-";
						}
					},
					{
						data: "features",
						className: "text-start align-middle",
						render: function (data, type, row) {
							if (!data) return '<span class="text-muted fs-7">-</span>';

							try {
								const features = typeof data === 'string' ? JSON.parse(data) : data;

								if (!Array.isArray(features) || features.length === 0) {
									return '<span class="text-muted fs-7">-</span>';
								}

								return features.map(item =>
									`<span class="badge badge-light-primary fw-bold me-1 mb-1 fs-8">${item}</span>`
								).join('');

							} catch (e) {
								return `<span class="text-muted fs-7">${data}</span>`;
							}
						}
					},
					{
						data: "description_product",
						className: "text-start align-middle",
						render: function (data) {
							if (!data) return "<span class='text-muted'>-</span>";
							let maxLength = 50;
							let shortText = data.length > maxLength ? data.substring(0, maxLength) + "..." : data;
							return `<span class="text-gray-800" title="${data}">${shortText}</span>`;
						}
					},
				],
			});
		}

		function setupEventHandlers() {
			$("#select-all").on("click", function () {
				const isChecked = this.checked;
				$("tbody .form-check-input").each(function () {
					$(this).prop("checked", isChecked);
				});
				hapusmassal();
			});

			$("#datatable tbody").on("change", ".form-check-input", function () {
				$("#select-all").prop(
					"checked",
					$(".form-check-input").length === $(".form-check-input:checked").length
				);
				hapusmassal();
			});

			$("#search").submit(function (e) {
				e.preventDefault();
				$("#datatable").DataTable().ajax.reload();
			});

			$('#btn-apply-filter').on('click', function () {
				currentFilters.category = $('#filter-category').val();
				currentFilters.unit = $('#filter-unit').val();

				$('#datatable').DataTable().ajax.reload();
				$('#filterModal').modal('hide');
			});

			$('#btn-reset-filter').on('click', function () {
				$('#filter-category').val('');
				$('#filter-unit').val('');
				currentFilters.category = '';
				currentFilters.unit = '';

				$('#datatable').DataTable().ajax.reload();
				$('#filterModal').modal('hide');
			});

			$('#filterModal').on('show.bs.modal', function () {
				loadFilterOptions();
			});

			function loadFilterOptions() {
				$.ajax({
					url: '<?= Url::to(['/product/categorylist']) ?>',
					type: 'GET',
					dataType: 'json',
					success: function (data) {
						var categorySelect = $('#filter-category');
						categorySelect.empty().append('<option value="">Pilih Kategori</option>');

						if (data.results && data.results.length > 0) {
							$.each(data.results, function (index, item) {
								categorySelect.append(
									$('<option></option>').val(item.id).text(item.text)
								);
							});
						}

						if (currentFilters.category) {
							categorySelect.val(currentFilters.category);
						}
					},
					error: function (xhr, status, error) {
						console.error('Error loading categories:', error);
					}
				});

				$.ajax({
					url: '<?= Url::to(['/product/unitlist']) ?>',
					type: 'GET',
					dataType: 'json',
					success: function (data) {
						var unitSelect = $('#filter-unit');
						unitSelect.empty().append('<option value="">Pilih Unit</option>');

						if (data.results && data.results.length > 0) {
							$.each(data.results, function (index, item) {
								unitSelect.append(
									$('<option></option>').val(item.id).text(item.text)
								);
							});
						}

						if (currentFilters.unit) {
							unitSelect.val(currentFilters.unit);
						}
					},
					error: function (xhr, status, error) {
						console.error('Error loading units:', error);
					}
				});
			}

			$(document).on('click', '.copy-text-btn', function () {
				const table = $('#datatable').DataTable();
				const rowData = table.row($(this).closest('tr')).data();

				if (!rowData || !rowData.description_product) {
					Swal.fire({
						icon: 'info',
						title: 'No text',
						text: 'Tidak ada teks yang bisa disalin.',
						timer: 1500,
						showConfirmButton: false
					});
					return;
				}

				const textToCopy = rowData.description_product.trim();

				navigator.clipboard.writeText(textToCopy)
					.then(() => {
						Swal.fire({
							icon: 'success',
							title: 'Copied!',
							text: '<?= Yii::$app->lang->t('back_home', 'succes_copy') ?>',
							timer: 1500,
							showConfirmButton: false
						});
					})
					.catch(() => {
						const tempTextarea = $('<textarea>');
						$('body').append(tempTextarea);
						tempTextarea.val(textToCopy).select();
						document.execCommand('copy');
						tempTextarea.remove();

						Swal.fire({
							icon: 'success',
							title: 'Copied!',
							text: '<?= Yii::$app->lang->t('back_home', 'succes_copy') ?>',
							timer: 1500,
							showConfirmButton: false
						});
					});
			});

			$(document).on('click', '.edit', function (e) {
				if (!e.ctrlKey && !e.metaKey && !e.shiftKey && e.which === 1) {
					e.preventDefault();
					const url = $(this).data('url');

					$.ajax({
						url: url,
						success: function (html) {
							const parser = new DOMParser();
							const dom = parser.parseFromString(html, 'text/html');
							$('#modal-content').html(dom.body.innerHTML);
							$('#modal_form_produk').modal('show');
						},
						error: function () {
							$('#modal-content').html('<p class="text-danger">Gagal memuat form.</p>');
						}
					});
				}
			});

			$('#btn-add').on('click', function (e) {
				e.preventDefault();

				$.ajax({
					url: $(this).attr('href'),
					success: function (data) {
						let parser = new DOMParser();
						let doc = parser.parseFromString(data, 'text/html');
						$('#modal-content').html(doc.body.innerHTML);
						$('#modal_form_produk').modal('show');
					},
					error: function () {
						$('#modal-content').html('<p>Error loading form.</p>');
					}
				});
			});

			$('#btn-hapusmassal').on('click', function () {
				let selectedIds = $(".form-check-input:checked")
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
							url: '<?= Url::to(['/product/deletemassal']) ?>',
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
								$("#btn-hapusmassal").addClass("d-none");
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
			});

			$(document).on('click', '.delete', function () {
				let id = $(this).data('id');

				if (!id) {
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
							url: '<?= Url::to(['/product/delete']) ?>' + '?id=' + id,
							type: 'POST',
							data: {
								_csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
							},
							headers: {
								"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
							},
							success: function (response) {
								if (!response.success) {
									Swal.fire({
										title: "Error!",
										text: response.pesan || 'Terjadi kesalahan',
										icon: "error",
										confirmButtonColor: "#d33",
										confirmButtonText: "OK"
									});
									return;
								}

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

		}

		function hapusmassal() {
			const $btn = $('#btn-hapusmassal');

			if ($('.form-check-input:checked').length > 0) {
				$btn.removeClass('d-none').addClass('show');
			} else {
				$btn.removeClass('show').addClass('d-none');
			}
		}

	});


</script>