				<!--begin::Wrapper GetnoSetting-->
				<div class="position-relative">
					<!--begin::GetnoSetting -->
					<button type="button" class="btn btn-light-secondary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-id="getno-menu">
						<i class="fa-solid fa-gear"></i>
					</button>
					<!--begin::MenuGetnoSetting-->
					<div class="menu menu-sub menu-sub-dropdown w-500px w-md-325px" data-kt-menu="true" data-kt-menu-id="getno-menu">
						<!--begin::Header-->
						<div class="px-7 py-5">
							<div class="fs-5 text-gray-900 fw-bold">Number Settings</div>
						</div>
						<!--end::Header-->
						<!--begin::Separator-->
						<div class="separator border-gray-200"></div>
						<!--end::Separator-->
						<!--begin::Content-->
						<div class="px-7 py-5" data-kt-user-table-filter="form">
							<form id="noForm" method="post" action="getno">
								<div class="row">
									<div class="md-10">
										<label for="oldcode">Current Setting</label>
										<input class="form-control form-control-solid" type="input" value="<?= $model['contact_no'] ?>" class="code" name="Contact[contact_no]" readonly />
									</div>
									<label for="newcode" class="mt-4 mb-3">New Setting</label>
									<div class="row nopadding">
										<div class="col-lg-4 col-md-4">
											<input type="text" id="prefix" placeholder="Text" class="form-control">
										</div>
										<div class="col-lg-8 col-md-8">
											<input type="text" id="newcode" placeholder="Code" name="regkode" class="form-control" readonly>
										</div>
									</div>
								</div>
								<button type="button" id="save" class="btn btn-primary mt-3 float-end me-6"><i class="fa-solid fa-floppy-disk"></i></button>
							</form>
						</div>
						<!--end::Content-->
					</div>
					<!--end::MenuGetnoSetting-->
					<!--end::GetnoSetting -->
				</div>
				<!--end::Wrapper GetnoSetting-->


<?php

use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\Html;
use common\widgets\Alert;
use common\models\GlobalFunction;
use karnbrockgmbh\modal\Modal;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\Contact;
use common\models\Enums;


?>

<!-- <style>
	*{
		max-width: 100%;
	}
</style> -->
<div class="app-toolbar py-3 py-lg-6">
	<!--begin::Toolbar container-->
	<div class="app-container container-xxl d-flex flex-stack">
		<!--begin::Page title-->
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0"><?= Yii::$app->lang->t('back_home', 'chat25') ?></h1>

			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">

				<li class="breadcrumb-item text-muted">
					<a href="index.html" class="text-muted text-hover-primary"><?= Yii::$app->lang->t('back_home', 'chat26') ?></a>
				</li>

				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>

				<li class="breadcrumb-item text-muted"><?= Yii::$app->lang->t('back_home', 'chat25') ?></li>

			</ul>

			<?php //echo Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) 
			?>
			<?= Alert::widget() ?>
			<!--end::Breadcrumb-->
		</div>
		<!--end::Page title-->
		<!--begin::Actions-->

		<!--end::Actions-->
	</div>
	<!--end::Toolbar container-->
</div>
<div id="kt_app_content" class="app-content flex-column-fluid">
	<!--begin::Content container-->
	<div id="kt_app_content_container" class="app-container container-xxl">
		<!--begin::Card-->
		<div class="card">
			<!--begin::Card header-->
			<div class="card-header border-0 pt-6">
				<!--begin::Card title-->
				<div class="card-title">
					<!--begin::Search-->
					<div class="d-flex align-items-center position-relative my-1">
						<i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"></i>
						<form method="get" style="width: 100%;">
							<input
								type="text"
								name="search"
								value="<?php
										if (isset($_GET['search'])) {
											echo Yii::$app->request->get('Contact')['search'] ??  $_GET['search'];
										} else {
											echo "";
										}
										?>"
								class="form-control form-control-solid w-250px ps-13"
								placeholder="<?= Yii::$app->lang->t('back_home', 'chat58') ?>" />
						</form>
					</div>
					<!--end::Search-->

				</div>
				<!--begin::Card title-->
				<!--begin::Card toolbar-->
				<div class="card-toolbar">
					<!--begin::Toolbar-->
					<div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
						<!--begin::Filter-->
						<button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
							<i class="ki-duotone ki-filter fs-2">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>Filter</button>
						<!--begin::Menu 1-->
						<div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
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
								<!--begin::Input group-->
								<?php
								$genderList = \common\models\Contact::getGenderList();
								$religionList = \common\models\Contact::getReligionList();
								$marriedList = \common\models\Contact::getMarriedList();
								$form = ActiveForm::begin([
									'method' => 'get',
									'options' => ['data-pjax' => true],
									'action' => ['index'], // Sesuaikan dengan action yang menangani filter
								]); ?>

								<div class="row">
									<div class="md-10">
										<?= $form->field($searchModel, 'contact_gender')->dropDownList(
											$genderList
										) ?>
									</div>
									<div class="md-10">
										<?= $form->field($searchModel, 'contact_married')->dropDownList(
											$marriedList
										) ?>
									</div>
									<div class="md-10">
										<?= $form->field($searchModel, 'contact_religion')->dropDownList(
											$religionList
										) ?>
									</div>
								</div>

								<div class="form-group text-end">
									<?= Html::submitButton('Filter', ['class' => 'btn btn-primary']) ?>
									<?= Html::a('Reset', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
								</div>

								<?php ActiveForm::end(); ?>

								<!--end::Input group-->
							</div>
							<!--end::Content-->
						</div>
						<!--end::Menu 1-->
						<!--end::Filter-->
						<!--begin::Add user-->
						<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_form_contact" id="load-modal">
							<i class="ki-duotone ki-plus fs-2"></i> <?= Yii::$app->lang->t('back_home', 'chat59') ?>
							<!-- <\?= Html::a('<i class="ki-duotone ki-plus fs-2"></i> Add Contact', 
							['create'], 
							['class' => 'tambah text-white']
							)
						?> -->
						</button>
						<div class="modal fade" id="modal_form_contact" tabindex="-1" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-lg mw-1000px">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title"> Contact</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<div id="modal-content">

										</div>
									</div>
								</div>
							</div>
						</div>
						<!--end::Add user-->
					</div>
					<!--end::Toolbar-->
				</div>
				<!--end::Card header-->
				<!--begin::Card body-->
				<div class="card-body py-4">
					<?php
					echo GridView::widget([
						'dataProvider' => $dataProvider,
						// 'filterModel' => $searchModel,
						'summary' => false,
						'emptyText' => 'Data tidak ditemukan.',
						'emptyTextOptions' => ['class' => 'text text-center text-muted fw-bold mt-5'], // Menambahkan kelas CSS 
						'tableOptions' => ['class' => 'table align-middle table-row-dashed fs-6 gy-5'], // Tambahkan class yang sesuai
						'columns' => [
							[
								'attribute' => 'contact_name',
								'format' => 'raw',
								'label' => Yii::$app->lang->t('back_home', 'chat19'),
								'enableSorting' => false, // Mematikan sorting hanya di kolom ini
								'contentOptions' => ['class' => 'text-start text-muted fw-bold'],
								'headerOptions' => ['class' => 'text-start text-muted fw-bold fs-7 text-uppercase gs-0 min-w-125px'],
								'content' => function ($contact) {
									$email = $contact['contact_email1'];
									$email2 = $contact['contact_email2'];
									return Html::a(Html::encode($contact['contact_name']), '#', ['class' => 'text-gray-800 text-hover-primary mb-1']) .
										'<br><span>' . Html::encode($email) . '</span>';
								}
							],
							[
								'attribute' => 'contact_phone1',
								'format' => 'raw',
								'label' => Yii::$app->lang->t('back_home', 'chat60'),
								'enableSorting' => false, // Mematikan sorting hanya di kolom ini
								'contentOptions' => ['class' => ''],
								'headerOptions' => ['class' => 'text-start text-muted fw-bold fs-7 text-uppercase gs-0 min-w-125px'],
								'value' => function ($contact) {
									$phone = $contact['contact_phone1'];
									return Html::tag('div', Html::encode($phone), ['class' => "badge badge-light-success fw-bold"]);
								},
							],
							[
								'attribute' => 'contact_gender',
								'format' => 'raw',
								'label' => Yii::$app->lang->t('back_home', 'chat61'),
								'enableSorting' => false, // Mematikan sorting hanya di kolom ini
								'contentOptions' => ['class' => ''],
								'headerOptions' => ['class' => 'text-start text-muted fw-bold fs-7 text-uppercase gs-0 min-w-125px'],
								'value' => function ($contact) {
									$genderName = $contact->gender->enum_name;
									$badgeClass = $genderName === 'Female' ? 'bg-pink' : ($genderName === 'Male' ? 'bg-blue' : 'badge-dark');
									return Html::tag('div', Html::encode($genderName), ['class' => "badge fw-bold $badgeClass"]);
								},
							],
							[
								'attribute' => 'contact_married',
								'format' => 'raw',
								'label' => 'Status',
								'enableSorting' => false, // Mematikan sorting hanya di kolom ini
								'contentOptions' => ['class' => ''],
								'headerOptions' => ['class' => 'text-start text-muted fw-bold fs-7 text-uppercase gs-0 min-w-125px'],
								'value' => function ($contact) {
									$married = $contact->married->enum_name;
									$badgeClass = $married === 'Married' ? 'bg-pink' : ($married === 'Single' ? 'bg-blue' : 'badge-dark');
									return Html::tag('div', Html::encode($married), ['class' => "badge fw-bold $badgeClass"]);
								},
							],
							[
								'attribute' => 'contact_religion',
								'format' => 'raw',
								'label' => Yii::$app->lang->t('back_home', 'chat20'),
								'enableSorting' => false, // Mematikan sorting hanya di kolom ini
								'contentOptions' => ['class' => ''],
								'headerOptions' => ['class' => 'text-start text-muted fw-bold fs-7 text-uppercase gs-0 min-w-125px'],
								'value' => function ($contact) {
									$religion = $contact->religion->enum_name;
									return Html::tag('div', Html::encode($religion), ['class' => "badge fw-bold"]);
								},
							],
							[
								'class' => 'yii\grid\ActionColumn',
								'header' => Yii::$app->lang->t('back_home', 'chat52'),
								'headerOptions' => ['class' => 'text-end text-muted fw-bold fs-7 text-uppercase gs-0 min-w-125px py-4'],
								'contentOptions' => ['class' => 'text-center'], // Pastikan ini ada
								'template' => '{dropdown}', // Pastikan ini tidak salah
								'buttons' => [
									'dropdown' => function ($url, $contact) {
										return '
										<div class="dropdown">
											<button class="btn btn-light btn-active-light-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
												Actions
											</button>
											<ul class="dropdown-menu">
												<li>
													' . Html::a(
											'<i class="fas fa-edit"></i> Edit',
											'javascript:void(0);', // Hindari pemuatan halaman
											// [ \yii\helpers\Url::to(['update']) , 'contact_id' => $contact->contact_id],
											// ['class' => 'dropdown-item', 'title' => 'Edit'],
											[
												'class' => 'dropdown-item edit-contact',
												'title' => 'Edit',
												'data-bs-toggle' => 'modal',
												'data-bs-target' => '#modal_form_contact',
												'data-id' => $contact->contact_id, // Simpan ID ke atribut data
											],
										) . '
												</li>
												<li>
													' . Html::a(
											'<i class="fas fa-trash"></i> Hapus',
											['contact/delete', 'id' => $contact->contact_id],
											[
												'class' => 'dropdown-item',
												'title' => 'Hapus',
												'data-confirm' => 'Apakah anda yakin menghapus data ini?',
												'data-method' => 'post',
											]
										) . '
												</li>
											</ul>
										</div>';
									},
								],
							],
						]
					]);
					?>
					<!-- Pagination -->
					<?= \yii\widgets\LinkPager::widget([
						'pagination' => $pagination,
						'options' => ['class' => 'pagination justify-content-end'],
						'linkOptions' => ['class' => 'page-link'],
						'pageCssClass' => 'page-item',
						'activePageCssClass' => 'active',
						'firstPageLabel' => '<i class="ki-duotone ki-double-arrow-left fs-5"></i>',
						'lastPageLabel' => '<i class="ki-duotone ki-double-arrow-right fs-5"></i>',
						'prevPageLabel' => '<i class="ki-duotone ki-arrow-left fs-5"></i>',
						'nextPageLabel' => '<i class="ki-duotone ki-arrow-right fs-5"></i>',
					]) ?>
				</div>
				<!--end::Card body-->
			</div>
			<!--end::Card-->
		</div>
		<!--end::Content container-->
	</div>
	<!--end::Content-->
</div>
<!--end::Content wrapper-->
<!--begin::Footer-->

<!--end::Footer-->
</div>
<!--end:::Main-->
</div>
<!--end::Wrapper-->
</div>
<!--end::Page-->
</div>
<!--end::App-->

<!--begin::Modals-->
<!--end::Modals-->


<!--begin::Javascript-->
<!-- <script>
	var hostUrl = "assets/";
</script> -->
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<!-- <script src="assets/plugins/global/plugins.bundle.js"></script>
<script src="assets/js/scripts.bundle.js"></script> -->
<!--end::Global Javascript Bundle-->
<!--begin::Vendors Javascript(used for this page only)-->
<!-- <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script> -->
<!--end::Vendors Javascript-->
<!--begin::Custom Javascript(used for this page only)-->
<!-- <script src="assets/js/custom/apps/user-management/users/list/table.js"></script>
<script src="assets/js/custom/apps/user-management/users/list/export-users.js"></script>
<script src="assets/js/custom/apps/user-management/users/list/add.js"></script>
<script src="assets/js/widgets.bundle.js"></script>
<script src="assets/js/custom/widgets.js"></script>
<script src="assets/js/custom/apps/chat/chat.js"></script>
<script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
<script src="assets/js/custom/utilities/modals/create-app.js"></script>
<script src="assets/js/custom/utilities/modals/users-search.js"></script> -->
<!--end::Custom Javascript-->
<!--end::Javascript-->
</body>
<script>
	function setfunction() {
		// $('.create').click(function(e) {
		// 	e.preventDefault();
		// 	$('#modal_form_contact').modal('show').find('.modal-content').load($(this).attr('href'));
		// 	$('#modal_form_contact').removeAttr('tabindex');

		// });


		// $('.update').click(function(e) {
		// 	e.preventDefault();
		// 	$('#modal_form_contact').modal('show').find('.modal-content').load($(this).attr('href'));
		// });


		$('.printall').click(function(e) {
			e.preventDefault();
			var link = $(this).attr('href');
			var jenis = $("#jenis").val();

			var filterdate = $("input[name='filterdate']").val() === undefined ? "" : $("input[name='filterdate']").val();
			var filtersearch = $("input[name='filter']").val() === undefined ? "" : $("input[name='filter']").val();
			var filterkategori = $("#kategoriid").select2('val') === undefined ? "" : $("#kategoriid").select2('val');
			//   var filterstatus = $("#status").select2('val') === undefined ? "" : $("#status").select2('val');
			//            alert(filtersalesman);
			var filterall = "";
			filterall += "&jenis=" + jenis;
			filterall += "&filterdate=" + filterdate;
			filterall += "&filtersearch=" + filtersearch;
			filterall += "&filterkategori=" + filterkategori;
			//      filterall += "&filterstatus=" + filterstatus;
			//  alert(link + filterall);
			krajeeDialog.confirm("Download data ini?", function(result) {
				if (result) { // ok button was pressed
					//                    alert($(this).attr('href'));
					window.location.href = link + filterall;
				} else { // confirmation was cancelled
					// execute your code for cancellation
				}
			});

			return;
		});

	}

	$('#modal_form_contact').on('kbModalSubmit', function(event, data, status, xhr) {
		if (data['success']) {
			$('#modal_form_contact').modal('hide');
			$.pjax.reload({
				container: '#grid-contact',
				timeout: 6000,
				type: 'GET'
			});
		} else {
			krajeeDialogCust.alert(data['pesan']);
		}
	});

	// 	$('#modal_form_contact').on('hidden.bs.modal', function () {
	//     $(this).find('form')[0].reset();
	// });

	$(document).on('click', '[data-bs-target="#modal_form_contact"]', function() {
		$.ajax({
			url: '<?= \yii\helpers\Url::to(['create']) ?>',
			success: function(data) {
				$('#modal-content').html(data); // Muat konten form ke modal
				$('#modal_form_contact').modal('show');
			},
			error: function() {
				$('#modal-content').html('<p>Error loading form.</p>');
			}
		});
	});

	$(document).on('click', '[data-bs-target="#modal_form_contact"].edit-contact', function() {
		$.ajax({
			url: '<?= \yii\helpers\Url::to(['update']) ?>?contact_id=' + $(this).data('id'),
			success: function(data) {
				$('#modal-content').html(data); // Muat konten form ke modal
				$('#modal_form_contact').modal('show');
			},
			error: function() {
				$('#modal-content').html('<p>Error loading form.</p>');
			}
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

</html>