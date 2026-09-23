<?php
$title = $enumtype;
switch ($title) {
	case 'unit':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar30');
		break;
	case 'expedition':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar31');
		break;
	case 'tax':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar32');
		break;
	case 'category':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar33');
		break;
	case 'brand':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar34');
		break;
	case 'type':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar35');
		break;
	case 'spec':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar36');
		break;
	case 'warehouse':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar37');
		break;
	case 'location':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar38');
		break;
	case 'condition':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar39');
		break;
	case 'shelf':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar114');
		break;
	case 'meal':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar45');
		break;
	case 'position':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar47');
		break;
	case 'level':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar48');
		break;
	case 'division':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar49');
		break;
	case 'status':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar50');
		break;
	case 'country':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar25');
		break;
	case 'state':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar26');
		break;
	case 'city':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar27');
		break;
	case 'district':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar28');
		break;
	case 'subcategory':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar115');
		break;
	case 'dinas':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar45');
		break;
}
switch ($searchModel->refid) {
	case 'position.pi':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar44');
		break;
	case 'position.cr':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar41');
		break;
	case 'position.op':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar42');
		break;
	case 'position.dr':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar118');
		break;
	case 'position.sb':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar119');
		break;
	case 'position.fe':
		$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar43');
		break;
}
$this->title = $title;

use yii\helpers\Html;
use yii\helpers\Url;
// var_dump($enumtype);
// var_dump($searchModel->refid);

?>
<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
	<?= Html::encode($this->title) ?>
</h1>

<div class="card mt-5">
	<div class="card-header border-0 pt-6 sticky-top bg-white shadow-sm"
		style="top: var(--kt-app-header-height, 70px); z-index: 1010;">
		<div class="card-title flex-grow-1">
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
							placeholder="<?= Yii::$app->lang->t('extra', 'extra02') ?>">

						<span class="position-absolute top-50 end-0 translate-middle-y me-3 d-none" id="clear-search">
							<i class="ki-duotone ki-cross fs-2 text-gray-500 cursor-pointer" style="opacity: 0.5;"></i>
						</span>
					</div>
				</form>
			</div>
		</div>

		<div class="card-toolbar flex-shrink-0">
			<div class="d-flex gap-2 flex-wrap">
				<?php if (in_array($enumtype, ['state', 'city', 'district', 'type', 'spec', 'subcategory'])) { ?>
					<button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
						data-kt-menu-placement="left-start">
						<i class="ki-duotone ki-filter fs-2">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>Filter
					</button>
				<?php } ?>

				<div class="menu menu-sub menu-sub-dropdown w-sm-500px w-md-600px" data-kt-menu="true"
					data-kt-menu-id="filter-menu">
					<div class="px-7 py-5">
						<div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
					</div>

					<div class="separator border-gray-200"></div>

					<div class="px-7 py-5" data-kt-user-table-filter="form">
						<form id="filterForm" method="get" action="index">
							<div class="row">

								<!-- Filter State -->
								<?php if (in_array($enumtype, ['spec', 'subcategory'])) { ?>
									<div class="col-md-12 mb-5">
										<label class="fw-semibold fs-6 mb-2" for="categorySelect">Category</label>
										<select id="categorySelect" class="form-select category" data-control="select2"
											name="refid_category">
											<?php if (isset($model->refid_category['id']) && isset($model->refid_category['text'])): ?>
												<option value="<?= $model->refid_category['id'] ?>" selected>
													<?= $model->refid_category['text'] ?>
												</option>
											<?php endif; ?>
										</select>
									</div>
								<?php } ?>
								<?php if ($enumtype == 'type') { ?>
									<div class="col-md-12 mb-5">
										<label class="fw-semibold fs-6 mb-2" for="subcategorySelect">Subcategory</label>
										<select id="subcategorySelect" class="form-select subcategory"
											data-control="select2" name="refid_subcategory">
											<?php if (isset($model->refid_subcategory['id']) && isset($model->refid_subcategory['text'])): ?>
												<option value="<?= $model->refid_subcategory['id'] ?>" selected>
													<?= $model->refid_subcategory['text'] ?>
												</option>
											<?php endif; ?>
										</select>
									</div>
								<?php } ?>

								<!-- Filter State -->
								<?php if ($enumtype == 'state') { ?>
									<div class="col-md-12 mb-5">
										<label class="fw-semibold fs-6 mb-2" for="countrySelect">Country</label>
										<select id="countrySelect" class="form-select country" data-control="select2"
											name="refid_country">
											<?php if (isset($model->refid_country['id']) && isset($model->refid_country['text'])): ?>
												<option value="<?= $model->refid_country['id'] ?>" selected>
													<?= $model->refid_country['text'] ?>
												</option>
											<?php endif; ?>
										</select>
									</div>
								<?php } ?>

								<!-- Filter City -->
								<?php if ($enumtype == 'city') { ?>
									<div class="col-md-12 mb-5">
										<label class="fw-semibold fs-6 mb-2" for="stateSelect">State</label>
										<select id="stateSelect" class="form-select state" data-control="select2"
											name="refid_state">
											<?php if (isset($model->refid_state['id']) && isset($model->refid_state['text'])): ?>
												<option value="<?= $model->refid_state['id'] ?>" selected>
													<?= $model->refid_state['text'] ?>
												</option>
											<?php endif; ?>
										</select>
									</div>
								<?php } ?>

								<!-- Filter district -->
								<?php if ($enumtype == 'district') { ?>
									<div class="col-md-12 mb-5">
										<label class="fw-semibold fs-6 mb-2" for="citySelect">City</label>
										<select id="citySelect" class="form-select city" data-control="select2"
											name="refid_city">
											<?php if (isset($model->refid_city['id']) && isset($model->refid_city['text'])): ?>
												<option value="<?= $model->refid_city['id'] ?>" selected>
													<?= $model->refid_city['text'] ?>
												</option>
											<?php endif; ?>
										</select>
									</div>
								<?php } ?>
							</div>

							<div class="form-group text-end mt-5">
								<button type="submit" id="filterButton" class="btn btn-lg btn-primary"><i
										class="fa-sharp fa-solid fa-filter"></i>Filter</button>
							</div>
						</form>
					</div>
				</div>

				<a href="<?= Url::to(["create/$enumtype"]) ?>" class="btn btn-primary add"
					data-url="<?= Url::to(["create/$enumtype"]) ?>">
					<i class="ki-duotone ki-plus fs-2"></i>
					<?= Yii::$app->lang->t('enum', 'button1') ?>
				</a>
			</div>
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
					<th class="text-center min-w-50px mx-1">
						<div class="form-check form-check-custom form-check-solid form-check-sm px-3">
							<input class="form-check-input" type="checkbox" id="select-all">
						</div>
					</th>
					<!-- <th class="text-center">+</th> -->
					<th class="text-start min-w-100px"><?= Yii::$app->lang->t('cashbackend', 'cashbackend1') ?></th>
					<?php if (in_array($enumtype, ['unit', 'category', 'brand', 'type', 'spec', 'warehouse', 'location', 'condition', 'shelf', 'job', 'dinas', 'position', 'level', 'division', 'status', 'subcategory'])) { ?>
						<th class="text-start min-w-50px"><?= Yii::$app->lang->t('code', 'label1') ?></th>
					<?php } ?>
					<th class="text-start min-w-50px"><?= Yii::$app->lang->t($enumtype, 'label1') ?></th>
					<?php if (in_array($enumtype, ['dinas', 'job'])) { ?>
						<th class="text-start min-w-50px"><?= Yii::$app->lang->t('operator', 'label2') ?></th>
					<?php } ?>
					<?php if ($enumtype == 'crew') { ?>
						<th class="text-start min-w-50px"><?= Yii::$app->lang->t($enumtype, 'label2') ?></th>
					<?php } ?>
					<?php if ($enumtype == 'crew') { ?>
						<th class="text-start min-w-50px"><?= Yii::$app->lang->t($enumtype, 'label3') ?></th>
					<?php } ?>
					<?php if ($enumtype == 'type') { ?>
						<th class="text-start min-w-50px"><?= Yii::$app->lang->t('subcategory', 'label1') ?></th>
					<?php } ?>
					<?php if (in_array($enumtype, ['spec', 'subcategory'])) { ?>
						<th class="text-start min-w-150px"><?= Yii::$app->lang->t('category', 'label1') ?></th>
					<?php } ?>
					<?php if ($enumtype == 'state') { ?>
						<th class="text-start min-w-150px"><?= Yii::$app->lang->t('country', 'label1') ?></th>
					<?php } ?>
					<?php if ($enumtype == 'city') { ?>
						<th class="text-start min-w-150px"><?= Yii::$app->lang->t('state', 'label1') ?></th>
					<?php } ?>
					<?php if ($enumtype == 'district') { ?>
						<th class="text-start min-w-150px"><?= Yii::$app->lang->t('city', 'label1') ?></th>
					<?php } ?>
					<?php if ($enumtype == 'tax') { ?>
						<th class="text-start min-w-100px"><?= Yii::$app->lang->t('tax', 'label2') ?></th>
						<!-- <th class="text-left min-w-100px"><?= Yii::$app->lang->t('tax', 'cut1') ?></th> -->
						<!-- <th class="text-left min-w-150px"><?= Yii::$app->lang->t('tax', 'sell1') ?></th> -->
						<!-- <th class="text-left min-w-150px"><?= Yii::$app->lang->t('tax', 'buy1') ?></th> -->
					<?php } ?>

				</tr>
			</thead>
			<tbody class="text-gray-600 fw-semibold text-center">

			</tbody>
		</table>
	</div>
</div>
<div class="modal fade" id="modal_form_enum" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-title-text"><?= Yii::$app->lang->t('enum', 'button1') ?></h5>
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
	function initData() {

		$("#Select2").select2({
			ajax: {
				url: "<?= \yii\helpers\Url::to(['enum/list']) ?>",
				type: "GET",
				dataType: "json",
				delay: 250,
				data: function (params) {
					const urlParams = new URLSearchParams(window.location.search);
					const enumtypeFromUrl = urlParams.get("enumtype"); // Ambil enumtype dari URL
					return {
						search: params.term,
						enumtype: enumtypeFromUrl, // Kirim enumtype ke backend Select2
						for: "select2"
					};
				},
				processResults: function (data) {
					var results = data.data.map(function (item) {
						return {
							id: item.enumid,
							text: item.enumtext_id
						};
					});
					return {
						results: results
					};
				},
				cache: true
			},
			placeholder: <?= json_encode(Yii::$app->lang->t('coas', 'coas5')) ?>,
			allowClear: true
		});

		$("#categorySelect").select2({
			ajax: {
				url: "<?= \yii\helpers\Url::to(['enum/list']) ?>",
				dataType: "json",
				delay: 250,
				data: function (params) {
					return {
						enumtype: "category",
						search: params.term || "",
						for: "select2",
						page: params.page || 1
					};
				},
				processResults: function (data, params) {
					params.page = params.page || 1;

					return {
						results: data.data.map(function (item) {
							return {
								id: item.enumid,
								text: item.enumtext_id
							};
						}),
						pagination: {
							more: data.pagination.more
						}
					};
				},
				cache: true
			},
			placeholder: <?= json_encode(Yii::$app->lang->t('coas', 'coas5')) ?>,
			allowClear: true
		});

		$("#subcategorySelect").select2({
			ajax: {
				url: "<?= \yii\helpers\Url::to(['enum/list']) ?>",
				dataType: "json",
				delay: 250,
				data: function (params) {
					return {
						enumtype: "subcategory",
						search: params.term || "",
						for: "select2",
						page: params.page || 1
					};
				},
				processResults: function (data, params) {
					params.page = params.page || 1;

					return {
						results: data.data.map(function (item) {
							return {
								id: item.enumid,
								text: item.enumtext_id
							};
						}),
						pagination: {
							more: data.pagination.more
						}
					};
				},
				cache: true
			},
			placeholder: <?= json_encode(Yii::$app->lang->t('coas', 'coas5')) ?>,
			allowClear: true
		});

		$("#countrySelect").select2({
			ajax: {
				url: "<?= \yii\helpers\Url::to(['enum/list']) ?>",
				dataType: "json",
				delay: 250,
				data: function (params) {
					return {
						enumtype: "country",
						search: params.term,
						for: "select2",
						page: params.page || 1
					};
				},
				processResults: function (data, params) {
					params.page = params.page || 1;

					return {
						results: data.data.map(function (item) {
							return {
								id: item.enumid,
								text: item.enumtext_id
							};
						}),
						pagination: {
							more: data.pagination.more
						}
					};
				},
				cache: true
			},
			placeholder: <?= json_encode(Yii::$app->lang->t('coas', 'coas5')) ?>,
			allowClear: true
		});

		$("#stateSelect").select2({
			ajax: {
				url: "<?= \yii\helpers\Url::to(['enum/list']) ?>",
				dataType: "json",
				delay: 250,
				data: function (params) {
					return {
						enumtype: "state",
						search: params.term,
						for: "select2",
						page: params.page || 1
					};
				},
				processResults: function (data, params) {
					params.page = params.page || 1;

					return {
						results: data.data.map(function (item) {
							return {
								id: item.enumid,
								text: item.enumtext_id
							};
						}),
						pagination: {
							more: data.pagination.more
						}
					};
				},
				cache: true
			},
			placeholder: <?= json_encode(Yii::$app->lang->t('coas', 'coas5')) ?>,
			allowClear: true
		});

		$("#citySelect").select2({
			ajax: {
				url: "<?= \yii\helpers\Url::to(['enum/list']) ?>",
				dataType: "json",
				delay: 250,
				data: function (params) {
					return {
						enumtype: "city",
						search: params.term,
						for: "select2",
						page: params.page || 1
					};
				},
				processResults: function (data, params) {
					params.page = params.page || 1;

					return {
						results: data.data.map(function (item) {
							return {
								id: item.enumid,
								text: item.enumtext_id
							};
						}),
						pagination: {
							more: data.pagination.more
						}
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

		$("#datatable").DataTable({
			scrollX: false,
			autoWidth: false,
			bAutoWidth: false,
			processing: true,
			serverSide: true,
			lengthMenu: [5, 15, 30, 50, 75, 100],
			pageLength: 5, // Set default ke 5
			// order: [],
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
				<div style="font-weight: bold; font-size: 16px; margin-top : 8px;">${translate}</div>
			</div>
			`,
			},
			select: {
				style: 'multi',
				selector: 'td:first-child input[type="checkbox"]',
				className: 'row-selected text-center'
			},
			// Initialize with a custom loading message
			initComplete: function () {
				setfunction();
			},
			ajax: {
				type: "GET",
				dataSrc: "data",
				url: "/enum/list",
				data: function (d) {
					const params = new URLSearchParams(window.location.search);
					const enumtype = params.get("enumtype");
					d.refid = '<?= $searchModel->refid ?>';
					const search = $('input[name="search"]').val();
					const unit = $("#Select2").val(); // Ambil nilai dari Select2
					const refid_country = $("#countrySelect").val();
					const refid_state = $("#stateSelect").val();
					const refid_city = $("#citySelect").val();
					const refid_category = $("#categorySelect").val();
					const refid_subcategory = $("#subcategorySelect").val();

					d.search = search;
					d.unit = unit; // Tambahkan parameter unit
					d.refid_country = refid_country;
					d.refid_state = refid_state;
					d.refid_city = refid_city;
					d.refid_category = refid_category;
					d.refid_subcategory = refid_subcategory;
					d.enumtype = '<?= $enumtype ?>';

					params.set('search', search);
					if (unit) {
						params.set('unit', unit);
					} else {
						params.delete('unit');
					}
					if (refid_country) {
						params.set('refid_country', refid_country);
					} else {
						params.delete('refid_country');
					}
					if (refid_state) {
						params.set('refid_state', refid_state);
					} else {
						params.delete('refid_state');
					}
					if (refid_city) {
						params.set('refid_city', refid_city);
					} else {
						params.delete('refid_city');
					}

					if (refid_category) {
						params.set('refid_category', refid_category);
					} else {
						params.delete('refid_category');
					}

					if (refid_subcategory) {
						params.set('refid_subcategory', refid_subcategory);
					} else {
						params.delete('refid_subcategory');
					}

					const newUrl = window.location.pathname + '?' + params.toString();
					window.history.replaceState({}, '', newUrl);
				},
				complete: function () {
					//$('.table-loading-overlay').remove();
				}
			},

			columns: [{
				data: 'enumid',
				visible: false
			},
			{
				data: null,
				className: "text-center w-40px",
				orderable: false,
				render: function (data, type, row) {
					return `
						 <div class="form-check form-check-custom form-check-solid form-check-sm px-3">
							<input type="checkbox" class="form-check-input row-checkbox select-checkbox" value="${row.enumid}">
						</div>  				
						`;
				}
			},
			{
				data: null,
				className: "text-start w-40px",
				render: function (data, type, row) {
					return `
					<div class="dropdown text-left dropend">
						<button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
							<i class="fa-sharp fa-solid fa-list"></i>
						</button>
							<ul class="dropdown-menu px-2">
								<li>
								<a href="/update/${row.enumtype}/${row.enumid}"  
								class="dropdown-item text-hover-success btn-light edit" 
								data-id="${row.enumid}"
								data-type="${row.enumtype}"
								data-ajax="true"
								target="_blank"
								style="cursor:pointer;">
								<i class="fas fa-edit"></i> Edit
								</a>
								</li>
								<li>
								<a href="javascript:void(0);" 
								class="dropdown-item text-hover-danger delete" 
								data-id="${row.enumid}" 
								style="cursor: pointer;">
								<i class="fas fa-trash"></i> Delete
								</a>
								</li>
							</ul>
					</div>`;
				}
			},
				<?php if (in_array($enumtype, ['unit', 'category', 'brand', 'type', 'spec', 'warehouse', 'location', 'condition', 'shelf', 'job', 'dinas', 'position', 'level', 'division', 'status', 'subcategory'])) { ?> {
					data: "enum_code_id",
					className: 'text-start',
					render: function (data, type, row) {
						return "<span>" + (data || '') + "</span>";
					}
				},
				<?php } ?> {
				data: "enumtext_id",
				className: 'text-start',
				render: function (data, type, row) {
					return "<span>" + (data || '') + "</span>";
				}
			},
				<?php if (in_array($enumtype, ['dinas', 'job'])) { ?> {
					data: "amount",
					className: 'text-start',
					render: function (data) {
						return data ?
							`<span class="text text-end">${parseFloat(data).toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 })}</span>` :
							"-";
					}
				},
				<?php } ?>
				<?php if ($searchModel->refid == 'crew') { ?> {
					data: "amount2",
					className: 'text-start',
					render: function (data, type, row) {
						return "<span>" + formatMoney(data, 0, '') + "</span>";
					}
				},
				<?php } ?>
				<?php if (in_array($enumtype, ['state', 'type', 'spec', 'subcategory'])) { ?> {
					data: "reftext",
					className: 'text-start',
					render: function (data, type, row) {
						return "<span>" + (data || '') + "</span>";
					}
				},
				<?php } ?>
				<?php if ($enumtype == 'city') { ?> {
					data: "reftext2",
					className: 'text-start',
					render: function (data, type, row) {
						return "<span>" + (data || '') + "</span>";
					}
				},
				<?php } ?>
				<?php if ($enumtype == 'district') { ?> {
					data: "reftext3",
					className: 'text-start',
					render: function (data, type, row) {
						return "<span>" + (data || '') + "</span>";
					}
				},
				<?php } ?>
				<?php if ($enumtype == 'tax') { ?> {
					data: "amount",
					className: 'text-start',
					render: function (data, type, row) {
						return "<span>" + formatMoney(data, 0, '') + "</span>";
					}
				},
					// {
					// 	data: "iscut_text",
					// 	className: "text-start",
					// 	orderable: false,
					// 	render: function (data, type, row) {
					// 		return "<span>" + data + "</span>";
					// 	}
					// },
					// {
					// 	data: "coaselltext",
					// 	className: 'text-start',
					// 	render: function (data, type, row) {
					// 		return "<span>" + data + "</span>";
					// 	}
					// },
					// {
					// 	data: "coabuytext",
					// 	className: 'text-start',
					// 	render: function (data, type, row) {
					// 		return "<span>" + data + "</span>";
					// 	}
					// },
				<?php } ?>
			],
		});
	}


	// $.fn.dataTable.ext.errMode = "none";

	function toggleMassActionButtons() {
		if ($('.select-checkbox:checked').length > 0) {
			$('#mass-action-buttons').show();
		} else {
			$('#mass-action-buttons').hide();
		}
	}

	$("#select-all").on("click", function () {
		$("tbody .select-checkbox").prop("checked", this.checked);
		toggleMassActionButtons();
	});

	$("#datatable tbody").on("change", ".select-checkbox", function () {
		$("#select-all").prop(
			"checked",
			$(".select-checkbox").length === $(".select-checkbox:checked").length
		);
		toggleMassActionButtons();
	});

	function performMassAction(action, statusCode) {
		console.log($("tbody .select-checkbox:checked"));
		let selectedIds = $(".select-checkbox:checked")
			.map(function () {
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
					url: '<?= Url::to(['/enum/massaction']) ?>',
					type: "POST",
					data: {
						ids: selectedIds,
						action: 'delete',
						status: 10,
						_csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
					},
					headers: {
						"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
					},
					success: function (response) {

						$('#datatable').DataTable().ajax.reload(null, false);
						console.log("Reload triggered");

						$("#mass-action-buttons").hide();
						$("#select-all").prop("checked", false);

						let successMessage, successText, successIcon;

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
					error: function (xhr) {
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

	$('#btn-delete-mass').on('click', function () {
		performMassAction('delete', 10);
	});

	$("#filterForm").submit(function (e) {
		e.preventDefault();
		$("#datatable").DataTable().ajax.reload();
		let filterMenu = document.querySelector("[data-kt-menu-id='filter-menu']");
		if (filterMenu) {
			KTMenu.getInstance(filterMenu).hide();
		}
	});


	function setfunction() {
		// alert("set function()");

		$("#search").submit(function (e) {
			e.preventDefault();
			$("#datatable").DataTable().ajax.reload();
		});

		$("#filterForm").submit(function (e) {
			e.preventDefault(); // Jangan biarkan form reload halaman
			$("#datatable").DataTable().ajax.reload();
		});

		$("#modal_form_enum").submit(function (e) {
			// alert('Masuk');
			e.preventDefault(); // Jangan biarkan form reload halaman
			$("#datatable").DataTable().ajax.reload();
		});

		$(document).on('click', '.edit', function (e) {
			e.preventDefault();

			let enumid = $(this).data('id'); // pastikan tombol .edit punya data-id
			const enumtype = <?php echo "'" . $enumtype . "'" ?>;

			$.ajax({
				url: '<?= \yii\helpers\Url::to(['update']) ?>',
				type: 'GET',
				data: {
					enumid: enumid,
					enumtype: enumtype
				},
				success: function (data) {
					let parser = new DOMParser();
					let doc = parser.parseFromString(data, 'text/html');
					$('#modal-content').html(doc.body.innerHTML);
					$('#modal-title-text').html("<?= Yii::$app->lang->t('unit', 'button_edit1') ?>");
					$('#modal_form_enum').modal('show');
				},
				error: function (xhr, status, error) {
					console.error("AJAX Error:", {
						status: status,
						error: error,
						response: xhr.responseText
					});
					showAlert("Error!", "Gagal memuat form.", "error");
				}
			});
		});


		$(document).on('click', '.add', function (e) {
			e.preventDefault(); // <- cegah pindah halaman
			const enumtype = <?php echo "'" . $enumtype . "'" ?>;


			$.ajax({
				url: '<?= \yii\helpers\Url::to(['create']) ?>',
				type: 'GET',
				data: {
					enumtype: enumtype,
					refid: '<?= $searchModel->refid ?>'
				}, // <-- PARAMETER DITAMBAHKAN DI SINI
				success: function (data) {
					let parser = new DOMParser();
					let doc = parser.parseFromString(data, 'text/html');
					$('#modal-content').html(doc.body.innerHTML);
					$('#modal_form_enum').modal('show');
				},
				error: function () {
					$('#modal-content').html('<p>Error loading form.</p>');
				}
			});
		});


		$(document).on('click', '.delete', function () {
			var enumid = $(this).data('id');
			// console.log('Menghapus ID:', enumtext_id);

			if (!enumid) {
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
				cancelButtonColor: "#6e7d88",
				confirmButtonText: "Ya, hapus!",
				cancelButtonText: "Batal"
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: '<?= Url::to(['/enum/delete']) ?>',
						type: 'POST',
						data: {
							enumid: enumid,
							enumtype: '<?= $enumtype ?>', // Tambahkan ini
							_csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
						},
						headers: {
							"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
						},
						success: function (response) {
							// if (response.success) {
							$('#datatable').DataTable().ajax.reload();

							Swal.fire({
								title: "Terhapus!",
								text: "Data berhasil dihapus.",
								icon: "success",
								timer: 2000,
								showConfirmButton: false
							});
						},
						error: function (xhr) {
							// console.error('Error:', xhr.responseText);

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
	}

	$(document).ready(function () {
		initData();
	});
</script>