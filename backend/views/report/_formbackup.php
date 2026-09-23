<?php

//echo date("Y-m-d hh:ii:ss");
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap5\BootstrapAsset;
use yii\jui\JuiAsset;
use kartik\select2\Select2Asset;
use common\models\Contact;
use backend\controllers\ContactController;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\icons\Icon;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use kartik\dialog\Dialog;
use kartik\widgets\DatePicker;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\touchspin\TouchSpin;
use kartik\widgets\FileInput;

?>

<?php
// var_dump("masuk");exit;
//$title = $model->isNewRecord ? 'Add' : 'Update';
//$this->title = $title . ' Contact';
$genderList = \common\models\Contact::getGenderList();
$religionList = \common\models\Contact::getReligionList();
$marriedList = \common\models\Contact::getMarriedList();
$educationList = \common\models\Contact::getEducationList();

$form = ActiveForm::begin(
	[
		'id' => 'modal-content',
		'options' => ['enctype' => 'multipart/form-data'],
		'validateOnSubmit' => true,
		'options' => [
			'data-pjax' => true
		]
	]
);
// echo "masuk";
?>
<!--begin::Form-->

<!--begin::Scroll-->
<div class="d-flex flex-column scroll-y px-5 px-lg-10" id="modal_form_contact_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#modal_form_contact_header" data-kt-scroll-wrappers="#modal_form_contact_scroll" data-kt-scroll-offset="300px">
	<!--begin::Input group-->

	<!--end::Input group-->
	<!--begin::Input group-->
	<div class="row">
		<div class="fv-row mb-7 col-lg-2 col-md-2 col-sm-12 col-xs-12 ">
			<!--begin::Label-->
			<label class="fw-semibold fs-6 mb-2">Code</label>
			<!--end::Label-->
			<!--begin::Input-->

			<?= $form->field($model, 'contact_no')->textInput([
				//	'placeholder' => $model->getAttributeLabel('contact_no'),
				'readOnly' => true,
				'class' => 'form-control form-control-solid mb-3 mb-lg-0'
			])->label(false);
			// var_dump($model->contact_no);
			// die;
			?>

			<!--end::Input-->
		</div>
		<div class="col-auto mb-7 col-lg-6  col-md-6 col-sm-6 col-xs-6">
			<!-- begin::Label-->
			<label class="fw-semibold fs-6 mb-2">Full Name</label>
			<!--end::Label -->
			<!--begin::Input-->

			<?= $form->field($model, 'contact_name')->textInput([
				'placeholder' => $model->getAttributeLabel('contact_name'),
				'readOnly' => false,
				'class' => 'form-control form-control-solid mb-3 mb-lg-0'
			])->label(false) ?>

			<!--end::Input-->
		</div>
		<!--end::Input group-->
	</div>
	<!--begin::Input group-->
	<!-- <div class="fv-row mb-7"> -->
	<!--begin::Label-->
	<!-- <label class="fw-semibold fs-6 mb-2">Email 2</label> -->
	<!--end::Label-->
	<!--begin::Input-->
	<!-- 
						?= $form->field($model, 'contact_email2')->textInput([
							'placeholder' => 'Email', 
							'readOnly' => false, 
							'class' => 'form-control form-control-solid mb-3 mb-lg-0'
							])->label(false) ?> -->
	<!--end::Input-->
	<!-- </div> -->
	<!--end::Input group-->
	<div class="row">
		<!--begin::Input group-->
		<div class="fv-row mb-7 col-lg-4 col-md-4 col-sm-12 col-xs-12">
			<!--begin::Label-->
			<label class="fw-semibold fs-6 mb-2 ">Phone</label>
			<!--end::Label-->
			<!--begin::Input-->
			<?= $form->field($model, 'contact_phone1')->textInput([
				'placeholder' => 'No.HP',
				'readOnly' => false,
				'class' => 'form-control form-control-solid mb-3 mb-lg-0'
			])->label(false) ?>
			<!--end::Input-->
		</div>
		<!--end::Input group-->
		<!--begin::Input group-->
		<div class="fv-row mb-7 col-lg-4 col-md-4 col-sm-12 col-xs-12">
			<!--begin::Label-->
			<label class="fw-semibold fs-6 mb-2">Email</label>
			<!--end::Label-->
			<!--begin::Input-->

			<?= $form->field($model, 'contact_email1')->textInput([
				'placeholder' => 'Email',
				'readOnly' => false,
				'class' => 'form-control form-control-solid mb-3 mb-lg-0'
			])->label(false) ?>
			<!--end::Input-->
		</div>
		<!--end::Input group-->
	</div>
	<!--begin::Input group-->
	<!-- <div class="fv-row mb-7"> -->
	<!--begin::Label-->
	<!-- <label class="fw-semibold fs-6 mb-2">Phone 2</label> -->
	<!--end::Label-->
	<!--begin::Input-->
	<!-- 
						?= $form->field($model, 'contact_phone2')->textInput([
							'placeholder' => 'No.WA', 
							'readOnly' => false, 
							'class' => 'form-control form-control-solid mb-3 mb-lg-0'
							])->label(false) ?> -->
	<!--end::Input-->
	<!-- </div> -->
	<div class="fv-row mb-7">
		<!--begin::Label-->
		<label class="fw-semibold fs-6 mb-2">Birth</label>


		<?= $form->field($model, 'contact_bod')->textInput([
			'placeholder' => 'B.O.D',
			'readOnly' => false,
			'class' => 'form-control form-control-solid mb-3 mb-lg-0 datepicker'
		])->label(false) ?>
		<!--end::Label-->
		<!--begin::Input-->
		<!-- ?= $form->field($model, 'contact_bod')->widget(DatePicker::classname(), [
							'type' => DatePicker::TYPE_COMPONENT_PREPEND,
								'options' => [
									'class' => 'form-control form-control-solid mb-3 mb-lg-0',
									'placeholder' => 'Pilih tanggal lahir',
								],
								'clientOptions' => [
									'changeMonth' => true,
									'changeYear' => true,
									'yearRange' => '-100:+0', // Rentang tahun yang tersedia
								],
								'pluginOptions' =>[
									'format' => 'dd/MM/yyyy'
								]
							])->label(false) ?> -->
		<!--end::Input-->
	</div>
	<!--end::Input group-->
	<!--end::Input group-->
	<div class="fv-row mb-7">
		<!--begin::Label-->
		<label class="fw-semibold fs-6 mb-2">Education</label>
		<!--end::Label-->
		<!--begin::Input-->
		<?= $form->field($model, 'contact_education')->dropDownList(
			$educationList,
			[
				'class' => 'form-select form-select-solid fw-bold',
				'data-kt-select2' => 'true',
				'data-control' => 'select2',
				'data-placeholder' => 'Select Education',
				'data-allow-clear' => 'true',
				'data-kt-user-table-filter' => 'education',
				'data-hide-search' => 'true'
			]
		)->label(false) ?>
		<!--end::Input-->
	</div>
	<div class="row">
		<!--begin::Input group-->
		<div class="mb-10 col-lg-4 col-md-4 col-sm-12 col-xs-12">
			<label class="form-label fs-6 fw-semibold">Gender:</label>
			<?= $form->field($model, 'contact_gender')->dropDownList( 	
				[
					'placeholder' => 'Choose your damn gender',
					'class' => 'select2',
					'data-placeholder' => 'Select gender',
				]
			)->label(false) ?>
		</div>

		<div class="mb-10 col-lg-4 col-md-4 col-sm-12 col-xs-12">
			<label class="form-label fs-6 fw-semibold">Married:</label>
			<?= $form->field($model, 'contact_married')->textInput(
				[
					'placeholder' => 'Are single?',
					'class' => 'form-control form-control-solid mb-3 mb-lg-0 datepicker',
					'data-placeholder' => 'Select married',
				]
			)->label(false) ?>
		</div>
		<div class="mb-10 col-lg-4 col-md-4 col-sm-12 col-xs-12">
			<label class="fw-semibold fs-6 mb-2">Religion:</label>
			<?= $form->field($model, 'contact_religion')->dropDownList(
				$religionList,
				[
					'prompt' => 'What is your religion?',
					'class' => 'select2',
					'data-placeholder' => 'Select religion',
				]
			)->label(false) ?>
		</div>
	</div>
</div>
<!--end::Scroll-->
<!--begin::Actions-->
<div class="text-end pt-10">
	<button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel" data-bs-dismiss="modal">Discard</button>


	<?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Submit', ['id' => 'btnsubmit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

</div>
<!--end::Actions-->

<!--end::Form-->
<?php ActiveForm::end(); ?>

<!--begin::Javascript-->
<script>
	let activeselector = document.querySelector('#modal-content');
	var hostUrl = "assets/";

	function closeModal() {
		// alert('test')				
		$('#modal_form_contact').modal('hide');
	}

	function setNo() {
		$.ajax({
			url: '<?= Url::to(['contact/getno'], true); ?>',
			type: 'post',
			data: {
				_csrf: '<?= Yii::$app->request->getCsrfToken() ?>',
			},
			success: function(data) {
				var json = $.parseJSON(data);
				$("#<?= Html::getInputId($model, 'contact_no'); ?>").val(json["no"]);
			},
			error: function(xhr, status, error) {
				console.log("AJAX Error:", error);
			}
		});
	}

	$(document).ready(function() {
		var form;
		setNo(); // Memanggil fungsi untuk mendapatkan nomor otomatis

		// $('.datepicker').datepicker({
		// 	format: 'yyyy-mm-dd',
		// 	autoclose: true,
		// 	todayHighlight: true
		// });

		// $('.datepicker').select2({
			$(".select2").select2({
        ajax: {
            url: "<?= \yii\helpers\Url::to(['contact/genderlist']) ?>",
            type: "GET",
            dataType: "json",
            delay: 250,
            data: function (params) {
                return {
                    search: params.term, // Text yang diketik
                    page: params.page || 1 // Pagination
                };
            },
            processResults: function (data, params) {
                return {
                    results: data.results,
                    pagination: {
                        more: data.pagination.more
                    }
                };
            },
            cache: true
        },
        placeholder: "Pilih Data",
        allowClear: true
    });

		startFlatpickr = flatpickr($('.datepicker'), {
            enableTime: false,
            dateFormat: "d-m-Y",
        });

		// var startDateTime = moment(startFlatpickr.selectedDates[0]).format();
		// $('.datepicker').datepicker({
		// 	locale: 'id'
		// });
	});

	$(function() {
		// alert('submit customer');
		if (activeselector != undefined && activeselector != "") {
			// var form = $('#customer-form');
			var form = $('#modal-content');
			// console.log(form);
			form.on('beforeSubmit', function(e) {
				e.preventDefault();
				$.ajax({
					url: form.attr('action'),
					type: form.attr('method'),
					data: new FormData(form[0]),
					mimeType: 'multipart/form-data',
					contentType: false,
					cache: false,
					processData: false,
					dataType: 'json',
					success: function(data) {
						//console.log(data);
						//alert('ID: '+data.success + ' someOtherData:' + data.pesan);

						if (data['success']) {
							$('#modal_form_contact').modal('hide');
							var target = $("#" + activeselector.attr("data-binding"));
							$('<option></option>').attr('selected', true).text(data['name']).val(data['id']).appendTo(target);
							target.trigger('change');
						} else {
							krajeeDialog.alert(data['pesan']);
						}

					}
				});
				return false;
			});
		}
	});
</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)
		<script src="assets/plugins/global/plugins.bundle.js"></script>
		<script src="assets/js/scripts.bundle.js"></script>
		<end::Global Javascript Bundle-->
<!--begin::Vendors Javascript(used for this page only)
		<script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
		<end::Vendors Javascript-->
<!--begin::Custom Javascript(used for this page only)-->
<!--script src="assets/js/custom/apps/user-management/users/list/table.js"></script>
		<script src="assets/js/custom/apps/user-management/users/list/export-users.js"></script>
		<script src="assets/js/custom/apps/user-management/users/list/add.js"></script>
		<script src="assets/js/widgets.bundle.js"></script>
		<script src="assets/js/custom/widgets.js"></script>
		<script src="assets/js/custom/apps/chat/chat.js"></script>
		<script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
		<script src="assets/js/custom/utilities/modals/create-app.js"></script>
		<script src="assets/js/custom/utilities/modals/users-search.js"></script>

		<end::Custom Javascript-->

<!--end::Javascript-->