<?php
$title = Yii::$app->lang->t('back_home', 'chat22');
$this->title = $title;
use common\models\Contact;
use common\models\Enum;
use backend\controllers\ContactController;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\Alert;
use yii\helpers\Url;
?>

<?php

// Ambil role_id dari user yang sedang login
$roleId = Yii::$app->user->identity->role_id;
$roleName = Yii::$app->function->findByField("enum_name", "enums", " and enum_type='users_roles' and enum_no = '$roleId'");
?>
<div class="app-toolbar py-3 py-lg-6 ms-n8">
	<div class="app-container container-fluid d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<!--begin::Title-->
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0"><?= Yii::$app->lang->t('back_home', 'chat22') ?></h1>
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
				<li class="breadcrumb-item text-muted"><?= Yii::$app->lang->t('back_home', 'chat22') ?></li>
				<!--end::Item-->
			</ul>
			<!--end::Breadcrumb-->
		</div>
	</div>
</div>
<!-- <div id="kt_app_content" class="app-content flex-column-fluid"> -->
<!--begin::Content container-->
<!-- <div id="kt_app_content_container" class="app-container container-xxl"> -->
<!--begin::Navbar-->
<?php
$subsMsg = Yii::$app->session->getFlash('subssuccess');
$changeSubsMsg = Yii::$app->session->getFlash('changesubssuccess');
$changeSubsMsg2 = Yii::$app->session->getFlash('changeprofilesuccess');
$changeSubsMsg3 = Yii::$app->session->getFlash('resetsuccess');
$changeSubsMsg4 = Yii::$app->session->getFlash('resetfailed');
?>

<?php if ($subsMsg || $changeSubsMsg || $changeSubsMsg2 || $changeSubsMsg3): ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<?= $subsMsg ?: $changeSubsMsg ?: $changeSubsMsg2 ?: $changeSubsMsg3 ?>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
<?php elseif ($changeSubsMsg4): ?>
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		<?= $changeSubsMsg4 ?>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
<?php endif; ?>


<div class="card mb-5 mb-xl-10 mt-5">
	<div class="card-body pt-9 pb-0">
		<!--begin::Details-->
		<div class="d-flex flex-wrap flex-sm-nowrap">
			<div class="me-7 mb-4">
				<div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">

					<img src="<?= Yii::$app->user->identity->avatar ? Yii::$app->user->identity->avatar : Yii::getAlias('@web') . '/assets/media/avatars/blank.png' ?>" alt="image" />
					<div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div>
				</div>
			</div>
			<div class="flex-grow-1">
				<div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
					<div class="d-flex flex-column">
						<div class="d-flex align-items-center mb-2">
							<?php if (!Yii::$app->user->isGuest): ?>
								<a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">
									<?= Yii::$app->user->identity->name ?>
								</a>
								<a href="#">
									<i class="ki-duotone ki-verify fs-1 text-primary">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</a>
							<?php else: ?>
								<span class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">Guest</span>
							<?php endif; ?>
						</div>

					</div>
				</div>
				<div>
					<div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
						<a href="#" class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
							<i class="ki-duotone ki-profile-circle fs-4 me-1">
								<span class="path1"></span>
								<span class="path2"></span>
								<span class="path3"></span>
							</i><?= $roleName ?></a>
						<a href="#" class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
							<i class="ki-duotone ki-geolocation fs-4 me-1">
								<span class="path1"></span>
								<span class="path2"></span>
							</i><?= Yii::$app->user->identity->address ?></a>
						<a href="#" class="d-flex align-items-center text-gray-500 text-hover-primary mb-2">
							<i class="ki-duotone ki-sms fs-4">
								<span class="path1"></span>
								<span class="path2"></span>
							</i><?= Yii::$app->user->identity->username ?></a>
					</div>
				</div>
			</div>
		</div>
		<!--end::Info-->
		<div>
			<ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">

				<!--begin::Nav item-->
				<!--li class="nav-item mt-2">
								<a class="nav-link text-active-primary ms-0 me-10 py-5 active" href="<?php echo Url::to(['users/profile'], true); ?>"><?= Yii::$app->lang->t('back_home', 'chat3') ?></a>
							</li-->
				<li class="nav-item mt-2">
					<a class="nav-link text-active-primary ms-0 me-10 py-5 active" id="overview-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true"><?= Yii::$app->lang->t('back_home', 'chat3') ?></a>
				</li>
				<!--end::Nav item-->
				<!--begin::Nav item-->
				<!--li class="nav-item mt-2">
								<a class="nav-link text-active-primary ms-0 me-10 py-5" href="<?php echo Url::to(['users/settings'], true); ?>"><?= Yii::$app->lang->t('back_home', 'chat4') ?></a>
							</li-->
				<li class="nav-item mt-2">
					<a class="nav-link text-active-primary ms-0 me-10 py-5" id="settings-tab" data-bs-toggle="tab" href="#settings" role="tab" aria-controls="settings" aria-selected="false"><?= Yii::$app->lang->t('back_home', 'chat4') ?></a>
				</li>
				<!--end::Nav item-->
				<!--begin::Nav item-->
				<!--li class="nav-item mt-2">
								<a class="nav-link text-active-primary ms-0 me-10 py-5" href="<?php echo Url::to(['contact/security'], true); ?>"><?= Yii::$app->lang->t('back_home', 'chat5') ?></a>
							</li>
							<end::Nav item->
							<--begin::Nav item->
							<li class="nav-item mt-2">
								<a class="nav-link text-active-primary ms-0 me-10 py-5" href="<?php echo Url::to(['contact/activity'], true); ?>"><?= Yii::$app->lang->t('back_home', 'chat7') ?></a>
							</li-->

				<!--end::Nav item->
							<-begin::Nav item->
							<li class="nav-item mt-2">
								<a class="nav-link text-active-primary ms-0 me-10 py-5" href="<?php echo Url::to(['users/billing'], true); ?>"><?= Yii::$app->lang->t('back_home', 'chat6') ?></a>
							</li-->
				<li class="nav-item mt-2">
					<a class="nav-link text-active-primary ms-0 me-10 py-5" id="billing-tab" data-bs-toggle="tab" href="#billing" role="tab" aria-controls="billing" aria-selected="false"><?= Yii::$app->lang->t('back_home', 'chat6') ?></a>
				</li>
				<!--end::Nav item->
							<!-begin::Nav item->
							<li class="nav-item mt-2">
								<a class="nav-link text-active-primary ms-0 me-10 py-5" href="account/statements.html">Statements</a>
							</li>
							<--end::Nav item->
							<--begin::Nav item>
							<li class="nav-item mt-2">
								<a class="nav-link text-active-primary ms-0 me-10 py-5" href="account/referrals.html">Referrals</a>
							</li>
							<--end::Nav item->
							<--begin::Nav item->
							<li class="nav-item mt-2">
								<a class="nav-link text-active-primary ms-0 me-10 py-5" href="account/api-keys.html">API Keys</a>
							</li>
							<--end::Nav item->
							<--begin::Nav item->
							<li class="nav-item mt-2">
								<a class="nav-link text-active-primary ms-0 me-10 py-5" href="account/logs.html">Logs</a>
							</li-->
			</ul>
		</div>
	</div>
</div>
<div class="tab-content">
	<div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
		<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
			<!--begin::Card header-->
			<div class="card-header cursor-pointer">
				<!--begin::Card title-->
				<div class="card-title m-0">
					<h3 class="fw-bold m-0"><?= Yii::$app->lang->t('back_home', 'chat9') ?></h3>
				</div>
				<!--end::Card title-->
				<!--begin::Action-->
				<!--a class="btn btn-sm btn-primary align-self-center" id="settings-tab" data-bs-toggle="tab" href="#settings" role="tab" aria-controls="settings" aria-selected="false">
							<?= Yii::$app->lang->t('back_home', 'chat8') ?>
						</a-->
				<!--end::Action-->
			</div>
			<div class="card-body p-9">
				<div class="row mb-7">
					<!--begin::Label-->
					<label class="col-lg-4 fw-semibold text-muted"><?= Yii::$app->lang->t('back_home', 'chat10') ?></label>
					<!--end::Label-->
					<!--begin::Col-->
					<div class="col-lg-8">
						<span class="fw-bold fs-6 text-gray-800">
							<?=
							Yii::$app->user->identity->name
							?>
						</span>
					</div>
				</div>
				<div class="row mb-7">
					<!--begin::Label-->
					<label class="col-lg-4 fw-semibold text-muted">Email</label>
					<!--end::Label-->
					<!--begin::Col-->
					<div class="col-lg-8 fv-row">
						<span class="fw-semibold text-gray-800 fs-6"><?= Yii::$app->user->identity->username ?></span> <!--contactno-->
					</div>
					<!--end::Col-->
				</div>

				<div class="row mb-7">
					<!--begin::Label-->
					<label class="col-lg-4 fw-semibold text-muted">Role</label>
					<!--end::Label-->
					<!--begin::Col-->
					<div class="col-lg-8">
						<span class="fw-bold fs-6 text-gray-800">
							<?=
							$roleName
							?>
						</span>
					</div>
					<!--end::Col-->
				</div>
				<!-- <di v class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6"> -->
				<!--begin::Icon-->
				<!-- <i class="ki-duotone ki-information fs-2tx text-warning me-4">
						<span class="path1"></span>
						<span class="path2"></span>
						<span class="path3"></span>
					</i>
					<div class="d-flex flex-stack flex-grow-1">
						<div class="fw-semibold">
							<h4 class="text-gray-900 fw-bold"><?= Yii::$app->lang->t('back_home', 'chat23') ?></h4>
							<div class="fs-6 text-gray-700"><?= Yii::$app->lang->t('back_home', 'chat24') ?>
								<a class="fw-bold" href="<?= Url::to(['users/changebilling']) ?>"><?= Yii::$app->lang->t('back_home', 'chat11') ?></a>.
							</div>
						</div>
					</div>
				</div> -->
			</div>
		</div>
	</div> <!--ini batas tab overview-->
	<div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings">
		<div class="card mb-5 mb-xl-10">
			<!--begin::Card header-->
			<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
				<!--begin::Card title-->
				<div class="card-title m-0">
					<h3 class="fw-bold m-0"><?= Yii::$app->lang->t('back_home', 'chat9') ?></h3>
				</div>
				<!--end::Card title-->
			</div>
			<!--begin::Card header-->
			<!--begin::Content-->
			<?php
			// $genderList = \common\models\Contact::getGenderList();
			// $marriedList = \common\models\Contact::getMarriedList();
			$form = ActiveForm::begin([
				'options' => ['enctype' => 'multipart/form-data'],
				'action' => ['profile', 'userid' => $user->userid], // Pastikan ini benar
				'method' => 'post',
			]);
			// var_dump($user->userid);
			?>
			<div id="kt_account_settings_profile_details" class="collapse show">
				<!--begin::Form-->
				<!-- <form id="kt_account_profile_details_form" class="form"> -->
				<!--begin::Card body-->
				<div class="card-body border-top p-9">
					<!--begin::Input group-->
					<div class="row mb-6">
						<!--begin::Label-->
						<label class="col-lg-4 col-form-label fw-semibold fs-6"><?= Yii::$app->lang->t('back_home', 'chat12') ?></label>
						<!--end::Label-->
						<!--begin::Col-->
						<div class="col-lg-8">
							<!--begin::Image input-->
							<div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('<?= Yii::$app->user->identity->avatar ? Yii::$app->user->identity->avatar : Yii::getAlias('@web') . '/assets/media/svg/avatars/blank.png' ?>')">
								<!--begin::Preview existing avatar-->
								<div class="image-input-wrapper w-125px h-125px" style="background-image: url('<?= Yii::$app->user->identity->avatar ? Yii::$app->user->identity->avatar : Yii::getAlias('@web') . '/assets/media/svg/avatars/blank.png' ?>')"></div>
								<!--end::Preview existing avatar-->
								<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
									<i class="ki-duotone ki-pencil fs-7">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
									<input type="file" name="avatar" accept="image/*" />
								</label>

							</div>
							<div class="form-text mb-6"><?= Yii::$app->lang->t('back_home', 'chat13') ?></div>
						</div>
						<!--end::Col-->
						</label>
						<!--end::Label-->
						<!--begin::Cancel-->
						<!--end::Remove-->
					</div>
					<!--end::Image input-->

					<!--begin::Hint-->
					<!--end::Hint-->

					<!--begin::Input group-->
					<div class="row mb-6">
						<!--begin::Label-->
						<!-- Full Name -->
						<label class="col-lg-4 col-form-label fw-semibold fs-6">
							<?= Yii::$app->lang->t('back_home', 'chat10') ?>
						</label>

						<div class="col-lg-8">
							<div class="row">
								<div class="col-lg-12 fv-row">
									<?= $form->field($user, 'name')->textInput([
										'class' => 'form-control form-control-lg form-control-solid mb-3 mb-lg-0',
										'placeholder' => 'Full name'
									])->label(false)->textInput(['value' => $user->name]) ?>
								</div>
							</div>
						</div>
					</div>

					<div class="row mb-6">
						<!--begin::Label-->
						<!-- Full Name -->
						<label class="col-lg-4 col-form-label fw-semibold fs-6">
							<?= Yii::$app->lang->t('contact', 'address') ?>
						</label>

						<div class="col-lg-8">
							<div class="row">
								<div class="col-lg-12 fv-row">
									<?= $form->field($user, 'address')->textInput([
										'class' => 'form-control form-control-lg form-control-solid mb-3 mb-lg-0',
										'placeholder' => 'Full name'
									])->label(false)->textInput(['value' => $user->address]) ?>
								</div>
							</div>
						</div>
					</div>

					<div class="row mb-6">
						<!--begin::Label-->
						<label class="col-lg-4 col-form-label fw-semibold fs-6">Email</label>
						<!--end::Label-->
						<!--begin::Col-->
						<div class="col-lg-8 fv-row">
							<?= $form->field($user, 'username')->textInput([
								'class' => 'form-control form-control-lg form-control-solid mb-3 mb-lg-0',
								'placeholder' => 'Email',
								'readonly' => true,
								'value' => $user->username
							])->label(false) ?>
						</div>
						<!--end::Col-->
					</div>
				</div>
				<!--end::Col-->
				<div class="card-footer d-flex justify-content-end py-6 px-9">
					<button type="reset" class="btn btn-light btn-active-light-primary me-2">Reset</button>
					<?= Html::submitButton(Yii::$app->lang->t('back_home', 'chat18'), ['class' => 'btn btn-primary', 'id' => 'post']) ?>

				</div>
				<!--end::Actions-->
				<!-- </form> -->
				<?php ActiveForm::end(); ?>
			</div>

		</div>
		<!--end::Card body-->
		<div class="card mb-5 mb-xl-10">
			<!--begin::Card header-->
			<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_signin_method">
				<div class="card-title m-0">
					<h3 class="fw-bold m-0"><?= Yii::$app->lang->t('back_home', 'chat16') ?></h3>
				</div>
			</div>
			<!--end::Card header-->
			<!--begin::Content-->
			<div id="kt_account_settings_signin_method" class="collapse show">
				<!--begin::Card body-->
				<div class="card-body border-top p-9">
					<!--begin::Password-->
					<div class="d-flex flex-wrap align-items-center mb-10">
						<!--begin::Label-->
						<div id="kt_signin_password">
							<div class="fs-6 fw-bold mb-1"><?= Yii::$app->lang->t('back_home', 'chat17') ?></div>
							<div class="fw-semibold text-gray-600">************</div>
						</div>
						<!--end::Label-->
						<!--begin::Action-->
						<div id="kt_signin_password_button" class="ms-auto">
							<!-- <button class="btn btn-light btn-active-light-primary" data-bs-toggle="modal" data-bs-target="#kt_signin_password_edit">?= Yii::$app->lang->t('back_home', 'chat17') ?></button> -->
							<button class="btn btn-light btn-active-light-primary" data-bs-toggle="modal" data-bs-target="#kt_reset_password"><?= Yii::$app->lang->t('back_home', 'chat17') ?></button>
						</div>
						<!--end::Action-->
						<!-- Modal Reset Password -->
						<div id="kt_reset_password" class="modal fade" tabindex="-1" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title"><?= Yii::$app->lang->t('back_home', 'chat17') ?></h5>
									</div>
									<div class="modal-body">
										<form class="kt_reset_password_form" class="form" action="<?= Yii::$app->urlManager->createUrl(['/users/resetpassword']) ?>" method="post">
											<input type="hidden" name="_csrf-backend" value="<?= Yii::$app->request->getCsrfToken() ?>" />
											<div class="mb-3">
												<label for="oldpassword" class="form-label"><?= Yii::$app->lang->t('back_home', 'chat36') ?></label>
												<input type="password" class="form-control" name="oldpassword" id="oldpassword">
											</div>
											<div class="mb-3">
												<label for="passwordnow" class="form-label"><?= Yii::$app->lang->t('back_home', 'chat35') ?></label>
												<input type="password" class="form-control" name="passwordnow" id="passwordnow">
											</div>
											<div class="mb-3">
												<label for="repassword" class="form-label"><?= Yii::$app->lang->t('signup', 'repassword') ?></label>
												<input type="password" class="form-control" name="repassword" id="repassword">
											</div>
											<button id="kt_reset_password_submit" type="submit" class="btn btn-primary me-2 px-6"><?= Yii::$app->lang->t('back_home', 'chat37') ?></button>
											<button type="button" class="btn btn-secondary tex-dark" data-bs-dismiss="modal"><?= Yii::$app->lang->t('back_home', 'chat34') ?></button>
										</form>
									</div>
								</div>
							</div>
						</div>
						<!-- End Modal Reset Password -->
						<!-- Modal Change Password -->
						<!-- <div id="kt_signin_password_edit" class="modal fade" tabindex="-1" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title"></h5>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div class="modal-body">
												<form id="kt_signin_change_password" class="form">
													<div class="mb-3">
														<label for="currentpassword" class="form-label"><?= Yii::$app->lang->t('back_home', 'chat30') ?></label>
														<input type="password" class="form-control" name="currentpassword" id="currentpassword">
													</div>
													<div class="mb-3">
														<label for="newpassword" class="form-label"><?= Yii::$app->lang->t('back_home', 'chat31') ?></label>
														<input type="password" class="form-control" name="newpassword" id="newpassword">
													</div>
													<div class="mb-3">
														<label for="confirmpassword" class="form-label"><?= Yii::$app->lang->t('back_home', 'chat32') ?></label>
														<input type="password" class="form-control" name="confirmpassword" id="confirmpassword">
													</div>
													<meta name="csrf-token" content="<?= Yii::$app->request->csrfToken ?>"/>
													<button id="kt_password_submit" type="button" class="btn btn-primary me-2 px-6"><?= Yii::$app->lang->t('back_home', 'chat33') ?></button>
													<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= Yii::$app->lang->t('back_home', 'chat34') ?></button>
												</form>
											</div>
										</div>
									</div>
								</div>	 -->
						<!-- End::Modal Change Password -->
					</div>
					<!--end::Password-->

					<!--begin::Notice-->
					<!-- <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6"> -->
					<!--begin::Icon-->
					<!-- <i class="ki-duotone ki-shield-tick fs-2tx text-primary me-4">
							<span class="path1"></span>
							<span class="path2"></span>
						</i> -->
					<!--end::Icon-->
					<!--begin::Wrapper-->
					<!-- <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap"> -->
					<!--begin::Content-->
					<!-- <div class="mb-3 mb-md-0 fw-semibold">
								<h4 class="text-gray-900 fw-bold"><?= Yii::$app->lang->t('back_home', 'chat38') ?></h4>
								<div class="fs-6 text-gray-700 pe-7"><?= Yii::$app->lang->t('back_home', 'chat39') ?></div>
							</div> -->
					<!--end::Content-->
					<!--begin::Action-->
					<!-- <a href="#" class="btn btn-primary px-6 align-self-center text-nowrap" data-bs-toggle="modal" data-bs-target="#kt_modal_two_factor_authentication"><?= Yii::$app->lang->t('back_home', 'chat40') ?></a> -->
					<!--end::Action-->
					<!-- </div> -->
					<!--end::Wrapper-->
					<!-- </div> -->
					<!--end::Notice-->
				</div>
				<!--end::Card body-->
			</div>
			<!--end::Content-->
		</div>
		<!-- <div class="card mb-5 mb-xl-10"> -->
		<!--begin::Card header-->
		<!-- <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_connected_accounts" aria-expanded="true" aria-controls="kt_account_connected_accounts">
				<div class="card-title m-0">
					<h3 class="fw-bold m-0"><?= Yii::$app->lang->t('back_home', 'chat41') ?></h3>
				</div>
			</div> -->
		<!--end::Card header-->
		<!--begin::Content-->
		<!-- <div id="kt_account_settings_connected_accounts" class="collapse show"> -->
		<!--begin::Card body-->
		<!-- <div class="card-body border-top p-9"> -->
		<!--begin::Notice-->
		<!-- <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 p-6"> -->
		<!--begin::Icon-->
		<!-- <i class="ki-duotone ki-design-1 fs-2tx text-primary me-4"></i> -->
		<!--end::Icon-->
		<!--begin::Wrapper-->
		<!-- <div class="d-flex flex-stack flex-grow-1"> -->
		<!--begin::Content-->
		<!-- <div class="fw-semibold">
								<div class="fs-6 text-gray-700"><?= Yii::$app->lang->t('back_home', 'chat42') ?>
									<a href="#" class="fw-bold"><?= Yii::$app->lang->t('back_home', 'chat43') ?></a>
								</div>
							</div> -->
		<!--end::Content-->
		<!-- </div> -->
		<!--end::Wrapper-->
		<!-- </div> -->
		<!--end::Notice-->
		<!--begin::Items-->
		<!-- <div class="py-2"> -->
		<!--begin::Item-->
		<!-- <div class="d-flex flex-stack">
							<div class="d-flex">
								<img src="assets/media/svg/brand-logos/google-icon.svg" class="w-30px me-6" alt="" />
								<div class="d-flex flex-column">
									<a href="#" class="fs-5 text-gray-900 text-hover-primary fw-bold">Google</a>
									<div class="fs-6 fw-semibold text-gray-500"><?= Yii::$app->lang->t('back_home', 'chat44') ?></div>
								</div>
							</div>
							<div class="d-flex justify-content-end">
								<div class="form-check form-check-solid form-check-custom form-switch">
									<input class="form-check-input w-45px h-30px" type="checkbox" id="googleswitch" checked="checked" />
									<label class="form-check-label" for="googleswitch"></label>
								</div>
							</div>
						</div>

					</div> -->
		<!--end::Items-->
		<!-- </div> -->
		<!--end::Card body-->
		<!--begin::Card footer-->
		<!-- <div class="card-footer d-flex justify-content-end py-6 px-9">
					<button class="btn btn-light btn-active-light-primary me-2"><?= Yii::$app->lang->t('back_home', 'chat45') ?></button>
					<button class="btn btn-primary"><?= Yii::$app->lang->t('back_home', 'chat46') ?></button>
				</div> -->
		<!--end::Card footer-->
		<!-- </div> -->
		<!--end::Content-->
		<!-- </div> -->

		<div class="card">
			<!--begin::Card header-->
			<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_deactivate" aria-expanded="true" aria-controls="kt_account_deactivate">
				<div class="card-title m-0">
					<h3 class="fw-bold m-0"><?= Yii::$app->lang->t('back_home', 'chat47') ?></h3>
				</div>
			</div>
			<!--end::Card header-->
			<!--begin::Content-->
			<div id="kt_account_settings_deactivate" class="collapse show">
				<!--begin::Form-->
				<form id="kt_account_deactivate_form" class="form">
					<!--begin::Card body-->
					<div class="card-body border-top p-9">
						<!--begin::Notice-->
						<div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-9 p-6">
							<!--begin::Icon-->
							<i class="ki-duotone ki-information fs-2tx text-warning me-4">
								<span class="path1"></span>
								<span class="path2"></span>
								<span class="path3"></span>
							</i>
							<!--end::Icon-->
							<!--begin::Wrapper-->
							<div class="d-flex flex-stack flex-grow-1">
								<!--begin::Content-->
								<div class="fw-semibold">
									<h4 class="text-gray-900 fw-bold"><?= Yii::$app->lang->t('back_home', 'chat48') ?></h4>
									<div class="fs-6 text-gray-700"><?= Yii::$app->lang->t('back_home', 'chat49') ?>
										<br />
										<a class="fw-bold" href="#"><?= Yii::$app->lang->t('back_home', 'chat43') ?></a>
									</div>
								</div>
								<!--end::Content-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Notice-->
						<!--begin::Form input row-->
						<div class="form-check form-check-solid fv-row">
							<input name="deactivate" class="form-check-input" type="checkbox" value="" id="deactivate" />
							<label class="form-check-label fw-semibold ps-2 fs-6" for="deactivate"><?= Yii::$app->lang->t('back_home', 'chat50') ?></label>
						</div>
						<!--end::Form input row-->
					</div>
					<!--end::Card body-->
					<!--begin::Card footer-->
					<div class="card-footer d-flex justify-content-end py-6 px-9">
						<button id="kt_account_deactivate_account_submit" type="button" class="btn btn-danger fw-semibold" disabled><?= Yii::$app->lang->t('back_home', 'chat47') ?></button>
					</div>
					<!--end::Card footer-->
				</form>
				<!--end::Form-->
			</div>
			<!--end::Content-->
		</div>

	</div>
	<div class="tab-pane fade" id="billing" role="tabpanel" aria-labelledby="billing">
		<div class="card">
			<div class="card-header">
				<div class="card-title"><?= Yii::$app->lang->t('extra', 'extra26') ?></div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table align-middle table-row-dashed fs-6 gy-5">
						<thead>
							<tr>
								<th class="fw-bold min-w-150px"><?= Yii::$app->lang->t('extra', 'extra27') ?></th>
								<th class="fw-bold min-w-200px"><?= Yii::$app->lang->t('extra', 'extra28') ?></th>
								<th class="fw-bold min-w-200px"><?= Yii::$app->lang->t('extra', 'extra3') ?></th>
								<th class="fw-bold min-w-125px"><?= Yii::$app->lang->t('billing', 'billing2') ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($companies as $company): ?>
								<tr>
									<td><?= Html::encode($company['nama_perusahaan']) ?></td>

									<td class="ps-15">
										<?php if ($company['subs_status'] == 1): ?>
											<span class="badge badge-success">
												<?= Yii::$app->lang->t('extra', 'extra29') ?>
											</span>
										<?php else: ?>
											<span class="badge badge-danger">
												<?= Yii::$app->lang->t('extra', 'extra30') ?>
											</span>
										<?php endif; ?>
									</td>

									<td>
										<?php
										if ($company['package_name'] == 'Free' || $company['subs_status'] == 10) {
											echo "<span class='badge badge-secondary'> Free</span>";
										} elseif ($company['package_name'] == 'Basic') {
											echo "<span class='badge badge-primary'>" . $company['package_name'] . "</span>";
										} elseif ($company['package_name'] == 'Advanced') {
											echo "<span class='badge badge-info'>" . $company['package_name'] . "</span>";
										} elseif ($company['package_name'] == 'Enterprise') {
											echo "<span class='badge badge-warning'>" . $company['package_name'] . "</span>";
										} elseif ($company['package_name'] == null) {
											echo "-";
										}
										?>
									</td>

									<td>
										<?php
										$start = new DateTime($company['tglstartsubs']);
										$end = new DateTime($company['tglendsubs']);
										$interval = $start->diff($end);
										if ($company['type'] == 'mon' && $company['subs_status'] == 1) {
											$totalMonths = ($interval->y * 12) + $interval->m;
											echo $totalMonths . ' ' . Yii::$app->lang->t('front_home', 'mon');
										} elseif ($company['type'] == 'year' && $company['subs_status'] == 1) {
											$totalMonths = ($interval->y) + $interval->m;
											echo $totalMonths . ' ' . Yii::$app->lang->t('front_home', 'year');
										} else {
											echo Yii::$app->lang->t('extra', 'extra33');
										}
										?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="card mt-10">
			<div class="card-header">
				<div class="card-title d-flex justify-content-between w-100">
					<?= Yii::$app->lang->t('extra', 'extra73') ?>
					<a href="<?= Url::to(['users/printhistory']) ?>" class="btn btn-primary">
						<i class="fa-solid fa-print"></i> Print
					</a>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table align-middle table-row-dashed fs-6 gy-5">
						<thead>
							<tr>
								<th class="fw-bold"><?= Yii::$app->lang->t('extra', 'extra3') ?></th>
								<th class="fw-bold"><?= Yii::$app->lang->t('front_home', 'price') ?></th>
								<th class="fw-bold"><?= Yii::$app->lang->t('tran', 'tran_date') ?></th>
								<th class="fw-bold">Status</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($histories as $history): ?>
								<tr>
									<td>
										<?php
										if ($history['itemtype'] == null) {
											echo "<span class='badge badge-secondary'> Error</span>";
										} elseif ($history['itemtype'] == 'Basic') {
											echo "<span class='badge badge-primary'>" . $history['itemtype'] . "</span>";
										} elseif ($history['itemtype'] == 'Advanced') {
											echo "<span class='badge badge-info'>" . $history['itemtype'] . "</span>";
										} elseif ($history['itemtype'] == 'Enterprise') {
											echo "<span class='badge badge-warning'>" . $history['itemtype'] . "</span>";
										}
										?>
									</td>

									<td>
										Rp.<?= is_numeric($history['subtotal']) ? number_format($history['subtotal'], 2, ',', '.') : '-' ?>
									</td>

									<td>
										<?= Html::encode($history['createdat'])	?>
									</td>

									<td>
										<?php
										if ($history['statuspaid'] == null || $history['statuspaid'] == 0) {
											echo "<span class='badge badge-danger'>" . Yii::$app->lang->t('cashbackend', 'cashbackend12') . "</span>";
										} else {
											echo "<span class='badge badge-success'>" . Yii::$app->lang->t('dashboard', 'paid') . "</span>";
										}
										?>
									</td>

								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<!--end::details View-->
<!-- </div> -->
<!--end::Content container-->
<!-- </div> -->
<!-- ?php
$this->registerJsFile('@web/js/bootstrap.bundle.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?> -->

<style>
	span.select-info {
		display: none !important;
	}

	/* Hover effect on dropdown items */
	.dropdown-item {
		transition: all 0.2s ease;
	}

	.dropdown-item:hover {
		border-radius: 4px;
		margin: 0 0;
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
<script>
	var deletemessage1 = "<?= Yii::$app->lang->t('extra', 'extra44') ?>";
	var deletemessage2 = "<?= Yii::$app->lang->t('extra', 'extra45') ?>";
	var deletemessage3 = "<?= Yii::$app->lang->t('extra', 'extra46') ?>";
	var deletemessage3koma1 = "<?= Yii::$app->lang->t('extra', 'extra46.1') ?>";
	var deletemessage4 = "<?= Yii::$app->lang->t('back_home', 'chat34') ?>";
	var deletemessage5 = "<?= Yii::$app->lang->t('back_home', 'chat53') ?>";
	var deletemessage6 = "<?= Yii::$app->lang->t('back_home', 'chat47') ?>";

	$('#deactivate').on('change', function() {
		var button = $('#kt_account_deactivate_account_submit');

		if ($(this).is(':checked')) {
			// $('#deactivate').prop('checked', false); // ⬅️ Ini dia yang penting!
			Swal.fire({
				title: deletemessage1,
				// text: "<?= Yii::$app->lang->t('extra', 'extra61') ?>",
				icon: "info",
				showCancelButton: true,
				confirmButtonColor: "#f70505",
				cancelButtonColor: "#3085d6",
				confirmButtonText: deletemessage6,
				cancelButtonText: deletemessage4
			}).then(result => {
				// $('#deactivate').prop('checked', false); ⬅️ Ini dia yang penting!
				if (result.isConfirmed) {
					button.prop('disabled', false);
				} else {
					button.prop('disabled', true);
					$('#deactivate').prop('checked', false); // ⬅️ Ini dia yang penting!
				}
			});
		} else {
			button.prop('disabled', true);
		}
	});

	$('#kt_account_deactivate_account_submit').on('click', function(e) {
		e.preventDefault(); // <--- Ini penting biar gak submit form biasa
		var id = <?= json_encode($user->userid) ?>; // Ambil ID kontak

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
					url: '<?= Url::to(['/users/delete']) ?>',
					type: 'post', // Harus POST
					data: {
						id: id,
						_csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
					},
					headers: {
						"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
					},
					success: function(response) {
						if (response.success) {
							Swal.fire({
								title: "<?= Yii::$app->lang->t('extra', 'extra62') ?>",
								text: "<?= Yii::$app->lang->t('extra', 'extra59') ?>",
								icon: "success",
								timer: 2000,
								showConfirmButton: false
							}).then(() => {
								// Redirect ke logout
								window.location.href = "<?= Url::to(['site/login']) ?>";
							});
						} else {
							Swal.fire({
								title: "<?= Yii::$app->lang->t('extra', 'extra60') ?>",
								text: "<?= Yii::$app->lang->t('extra', 'extra61') ?>",
								icon: "error"
							});
						}
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
</script>