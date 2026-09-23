<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::$app->lang->t('extra', 'extra22');
?>
<div class="app-toolbar py-3 py-lg-6 ms-n8">
	<div class="app-container container-fluid d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<!--begin::Title-->
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
				<?= Yii::$app->lang->t('extra', 'extra22') ?></h1>
			<!--end::Title-->
			<!--begin::Breadcrumb-->
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="<?= Url::to(['site/index']) ?>"
						class="text-muted text-hover-primary"><?= Yii::$app->lang->t('back_home', 'chat2') ?></a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted"><?= Yii::$app->lang->t('extra', 'extra22') ?></li>
			</ul>
			<!--end::Breadcrumb-->
		</div>
	</div>
</div>

<div class="card mt-5">
	<div class="card-header">
		<?php if ($roleName == 'Superadmin' || $roleName == 'Admin'): ?>
			<div class="card-title">
				<h4><?= Yii::$app->lang->t('extra', 'extra22') ?></h4>
			</div>
		<?php else: ?>
			<div class="card-title d-flex flex-row justify-content-between w-100">
				<h4><?= Yii::$app->lang->t('extra', 'extra22') ?></h4>
				<button type="button" class="btn btn-primary" id="btn-join-company" data-bs-target="#modal_join_company">
					Join The Exist Company
				</button>
			</div>
		<?php endif; ?>
	</div>
	<div class="card-body row justify-content-start">
		<?php foreach ($data as $company): ?>
			<a href="<?= Url::to(['site/index?companyid=']) . $company['companyid'] ?>" class="company-item col-md-4 mt-3"
				data-id="<?= $company['companyid'] ?>">
				<div
					class="card bg-secondary hoverable d-flex justify-content-center text-white text-center p-3 w-100 h-100">
					<h4 class="d-flex flex-row justify-content-center align-items-center">
						<?php
						$statusInfo = [
							1 => ['text' => 'Active', 'type' => 'success'],
							10 => ['text' => 'Inactive', 'type' => 'danger']
						];

						$currentStatus = $company['status'] ?? null;
						$status = $statusInfo[$currentStatus]['text'] ?? 'Unknown';
						$type = $statusInfo[$currentStatus]['type'] ?? 'secondary';
						?>

						<span class="bullet bullet-dot bg-<?= $type ?> w-10px h-10px me-2"></span>
						<?= $status ?>
					</h4>
					<img alt="Logo"
						src="<?= !empty($company['company_photo']) ? $company['company_photo'] : Yii::getAlias('@web') . "/assets/media/logos/rbg.png" ?>"
						class="w-100px mx-auto d-block" />
					<h5 class="fw-bold"><?= Html::encode($company['nama_perusahaan']) ?></h5>
					<div class="row gap-5 justify-content-center">
						<?php if (count($data) > 1): ?>
							<button class="btn btn-icon btn-sm btn-danger btn-delete" data-id="<?= $company['companyid'] ?>">
								<i class="bi bi-trash"></i>
							</button>
						<?php endif; ?>

						<button class="btn btn-icon btn-sm btn-success btn-edit" data-id="<?= $company['companyid'] ?>">
							<i class="fa-solid fa-pen-to-square"></i>
						</button>

						<?php if ($roleName == 'Superadmin' || $roleName == 'Admin'): ?>
							<button class="btn btn-icon btn-sm btn-primary btn-status" data-id="<?= $company['companyid'] ?>">
								<i class="fa-solid fa-circle mx-auto"></i>
							</button>
						<?php endif; ?>
					</div>
				</div>
			</a>
		<?php endforeach; ?>

		<!-- Add Company Card -->
		<div class="col-md-4 mt-3">
			<a href="<?= Url::to(['company/create']) ?>"
				class="card bg-dark hoverable d-flex justify-content-center text-white text-center p-3 w-100 h-200px"
				id="create">
				<div class="fs-1">+</div>
				<p><?= Yii::$app->lang->t('extra', 'extra23') ?></p>
			</a>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_join_company" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"> Join A Company</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div id="modal-content-join" class="nopadding">
					<p>Please Enter A Company Id From The Existing Company</p>
					<form action="<?= Url::to(['company/joincompany']) ?>" class="form" method="post">
						<input type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
							value="<?= Yii::$app->request->csrfToken ?>">
						<input type="text" class="form-control" placeholder="Enter Code" name="companycode"
							id="companycode" autocomplete="off">
						<button type="submit" class="btn btn-primary me-2 px-6 mt-5 float-end"> Confirm</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_form_company" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header" id="modal_form_company_header">
				<h5 class="modal-title" id="modal_form_company_title">Perusahaan</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div id="modal_form_company_content">
					<div class="text-center p-10">
						<span class="spinner-border" role="status"></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).on('click', '#create', function (e) {
		e.preventDefault();
		loadCompanyForm($(this).attr('href'), 'Tambah Perusahaan');
	});

	$(document).on('click', '.btn-edit', function (e) {
		e.preventDefault();
		e.stopPropagation();

		let companyId = $(this).data('id');
		let url = "<?= Url::to(['company/update']) ?>?id=" + companyId;

		loadCompanyForm(url, 'Edit Perusahaan');
	});

	function loadCompanyForm(url, title) {
		$('#modal_form_company_title').text(title);
		$('#modal_form_company_content').html(
			'<div class="text-center p-10"><span class="spinner-border" role="status"></span></div>'
		);
		$('#modal_form_company').modal('show');

		$.ajax({
			url: url,
			type: 'GET',
			cache: false,
			success: function (data) {
				$('#modal_form_company_content').html(data);

				if (typeof KTImageInput !== 'undefined') {
					KTImageInput.createInstances();
				}
			},
			error: function (xhr, status, error) {
				$('#modal_form_company').modal('hide');
				console.error(xhr.responseText);
				alert("Gagal memuat form: " + error);
			}
		});
	}

	$(document).on('click', '.company-item', function (e) {
		if ($(e.target).closest('button').length > 0) {
			e.preventDefault();
		}
	});

	var deletemessage1 = "<?= Yii::$app->lang->t('extra', 'extra44') ?>";
	var deletemessage2 = "<?= Yii::$app->lang->t('extra', 'extra45') ?>";
	var deletemessage3 = "<?= Yii::$app->lang->t('extra', 'extra46') ?>";
	var deletemessage3koma1 = "<?= Yii::$app->lang->t('extra', 'extra46.1') ?>";
	var deletemessage4 = "<?= Yii::$app->lang->t('back_home', 'chat34') ?>";
	var deletemessage5 = "<?= Yii::$app->lang->t('back_home', 'chat53') ?>";

	$(document).on('click', '.btn-status', function (e) {
		e.preventDefault();
		e.stopPropagation();

		let companyId = $(this).data('id');

		Swal.fire({
			title: "<?= Yii::$app->lang->t('extra', 'extra14') ?> Status",
			text: "Are you sure you want to change this company's status?",
			icon: "question",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "<?= Yii::$app->lang->t('extradouble', 'double2') ?>",
			cancelButtonText: "<?= Yii::$app->lang->t('back_home', 'chat34') ?>"
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "<?= Url::to(['company/status']) ?>?id=" + companyId,
					type: 'POST',
					data: {
						id: companyId,
						'<?= Yii::$app->request->csrfParam ?>': '<?= Yii::$app->request->csrfToken ?>'
					},
					headers: {
						"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
					},
					success: function (response) {
						if (response.success) {
							Swal.fire({
								title: "Success!",
								text: response.message || "<?= Yii::$app->lang->t('extra', 'extra93') ?>",
								icon: "success",
								confirmButtonText: "OK"
							}).then(() => {
								updateCompanyList();
								reloadCompanyDropdown();
							});
						} else {
							Swal.fire("Error!", response.message || "Failed to change status", "error");
						}
					},
					error: function () {
						Swal.fire("Error!", "An error occurred while changing status", "error");
					}
				});
			}
		});
	});

	$(document).on('click', '.btn-delete', function (e) {
		e.preventDefault();
		e.stopPropagation();

		let companyId = $(this).data('id');

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
					url: '<?= Url::to(['company/delete']) ?>?id=' + companyId,
					type: 'POST',
					data: {
						id: companyId,
						'<?= Yii::$app->request->csrfParam ?>': '<?= Yii::$app->request->csrfToken ?>'
					},
					headers: {
						"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
					},
					success: function (response) {
						let isSuccess = false;
						let message = "Berhasil Terhapus!";

						if (typeof response === 'string' && (response.indexOf('company-item') !== -1 || response.indexOf('app-container') !== -1)) {
							isSuccess = true;
						} else if (response && (response.success === true || response.success === "true" || response.status === 'success')) {
							isSuccess = true;
							if (response.message) {
								message = response.message;
							}
						}

						if (isSuccess) {
							Swal.fire({
								title: "<?= Yii::$app->lang->t('back_home', 'chat57') ?>!",
								text: message,
								icon: "success",
								confirmButtonText: "OK"
							}).then(() => {
								updateCompanyList();
								reloadCompanyDropdown();
							});
						} else {
							let errMsg = (response && response.message) ? response.message : "Gagal menghapus perusahaan.";
							Swal.fire("Error!", errMsg, "error");
						}
					},
					error: function (xhr, status, error) {
						let errMsg = "Terjadi kesalahan saat menghapus.";
						if (xhr.responseJSON && xhr.responseJSON.message) {
							errMsg = xhr.responseJSON.message;
						} else if (xhr.responseText) {
							let match = xhr.responseText.match(/<section class="error-message">([\s\S]*?)<\/section>/) ||
								xhr.responseText.match(/<div class="alert alert-danger">([\s\S]*?)<\/div>/) ||
								xhr.responseText.match(/<h1>(.*?)<\/h1>/) ||
								xhr.responseText.match(/<td class="name">Exception<\/td>\s*<td class="value">([\s\S]*?)<\/td>/);
							if (match && match[1]) {
								errMsg = match[1].replace(/<[^>]*>/g, '').trim();
							}
						}
						Swal.fire("Error!", errMsg, "error");
					}
				});
			}
		});
	});

	function updateCompanyList() {
		var url = "<?= Url::to(['company/index']) ?>";

		history.pushState(null, '', url);

		$.ajax({
			url: url,
			type: 'GET',
			cache: false,
			success: function (data) {
				reloadCompanyDropdown();
				$('.app-container.utama').html(data);
			},
			error: function (xhr, status, error) {
				alert("Gagal memuat halaman: " + error);
			}
		});
	}

	function reloadCompanyDropdown() {
		$.ajax({
			url: "<?= Yii::$app->urlManager->createUrl(['company/getcompany']) ?>",
			type: "GET",
			success: function (response) {
				if (response.success) {
					$("#companyDrop").html(response.html).addClass('mb-3 me-n1');
				}
			},
			error: function (xhr, status, error) {
				console.error("Gagal memperbarui dropdown:", error);
			}
		});
	}

	$(document).on('click', '#btn-join-company', function (e) {
		e.preventDefault();
		$('#modal_join_company').modal('show');
	});
</script>