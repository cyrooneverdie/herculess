<?php
$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar100');
$this->title = $title;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

// Ambil session untuk mendapatkan companyid
$session = Yii::$app->session;

if ($modelsubs->package == 'Basic') {
	$paketicon = ' <i class="fa-solid fa-certificate fs-2 text-primary"></i>';  // Ikon Basic
} elseif ($modelsubs->package == 'Advanced') {
	$paketicon = ' <i class="fa-solid fa-gem fs-2 text-primary"></i>';  // Ikon Advanced
} elseif ($modelsubs->package == 'Enterprise') {
	$paketicon = ' <i class="fa-solid fa-crown fs-2 text-primary"></i>';  // Ikon Enterprise
} else {
	$paketicon = '<span class="text-danger">NULL</span>';  // Jika package tidak sesuai
}
?>
<?php
$form = ActiveForm::begin(
	[
		'id' => 'FormValid',
		'method' => 'post',
		'options' => [
			'enctype' => 'multipart/form-data',
			'multiple' => true,
		],
		'validateOnSubmit' => true,
	]
);
?>

<div class="app-toolbar py-3 py-lg-6 ms-n8">
	<div class="app-container container-fluid d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<!--begin::Title-->
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0"><?= Yii::$app->lang->t('back_home', 'chat6') ?></h1>
			<!--end::Title-->
			<!--begin::Breadcrumb-->
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="<?= Url::to(['site/index']) ?>" class="text-muted text-hover-primary"><?= Yii::$app->lang->t('back_home', 'chat2') ?></a>
				</li>
				<!--begin::Item-->
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<!--end::Item-->
				<!--begin::Item-->
				<li class="breadcrumb-item text-muted"><?= Yii::$app->lang->t('back_home', 'chat6') ?></li>
				<!--end::Item-->
			</ul>
			<!--end::Breadcrumb-->
		</div>
	</div>
</div>
<?php // Cek apakah companyid dari model sama dengan yang ada di session
if ($modelsubs && $modelsubs->subs_id != null && $modelsubs->status == 1 && $modelsubs->subs_status == 1) :
?>
	<?php
	$now = new \DateTime();
	$end = new \DateTime($modelsubs->tglendsubs);
	if (Yii::$app->session->hasFlash('billing_expired') && $now > $end):
	?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<?= Yii::$app->session->getFlash('billing_expired') ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<div class="card shadow-sm border-0 mt-5">
		<div class="card-header text-white">
			<h3 class="card-title mb-0 d-flex justify-content-between w-100">
				<span><?= $modelsubs->nama_perusahaan ?></span>

				<span><?= $paketicon ?></span>
			</h3>
		</div>
		<div class="card-body d-flex justify-content-center">
			<div class="col-md-8 col-lg-6">
				<div class="card border shadow-sm px-4 py-5 bg-light">
					<div class="mb-4 text-center">
						<h2 class="fw-bold text-primary"><?= Yii::$app->lang->t('billing', 'billing5') ?></h2>
						<p class="text-muted"><?= Yii::$app->lang->t('extra', 'extra2') ?></p>
					</div>

					<ul class="list-group list-group-flush mb-4">
						<li class="list-group-item d-flex justify-content-between">
							<strong>📦 <?= Yii::$app->lang->t('extra', 'extra3') ?> : </strong>
							<span>
								<?php
								// Menentukan ikon berdasarkan tipe paket
								if ($modelsubs->package == 'Basic') {
									echo ' <i class="fa-solid fa-certificate fs-7 text-primary subs-logo"></i>' . ' ' . $modelsubs->package . '   ';  // Ikon Basic
								} elseif ($modelsubs->package == 'Advanced') {
									echo ' <i class="fa-solid fa-gem fs-7 text-primary subs-logo"></i>' . ' ' . $modelsubs->package . '   ';  // Ikon Advanced
								} elseif ($modelsubs->package == 'Enterprise') {
									echo ' <i class="fa-solid fa-crown fs-7 text-primary subs-logo"></i>' . ' ' . $modelsubs->package . '   ';  // Ikon Enterprise
								} else {
									echo '<span class="text-danger">NULL</span>';  // Jika package tidak sesuai
								}
								?>
							</span>
						</li>
						<li class="list-group-item d-flex justify-content-between">
							<strong>⏳ <?= Yii::$app->lang->t('billing', 'billing2') ?> : </strong>
							<span>
								<?php
								$start = new DateTime($modelsubs->tglstartsubs);
								$end = new DateTime($modelsubs->tglendsubs);
								$interval = $start->diff($end);
								if ($modelsubs->type == 'mon') {
									$totalMonths = ($interval->y) + $interval->m;
									echo $totalMonths . ' ' . Yii::$app->lang->t('front_home', 'mon');
								} elseif ($modelsubs->type == 'year') {
									$totalMonths = ($interval->y) + $interval->m;
									echo $totalMonths . ' ' . Yii::$app->lang->t('front_home', 'year');
								} else {
									echo '<span class="text-danger">NULL</span>';
								}
								?>
							</span>
						</li>
						<li class="list-group-item d-flex justify-content-between">
							<strong>💰 <?= Yii::$app->lang->t('billing', 'billing4') ?> : </strong>
							<span class="text-success fw-bold">Rp. <?= number_format($modelsubs->subsprice, 0, ',', '.') ?></span>
						</li>
						<li class="list-group-item d-flex justify-content-between">
							<strong>📅 <?= Yii::$app->lang->t('extra', 'extra1') ?> : </strong>
							<span>
								<?= $modelsubs->tglendsubs ? Yii::$app->formatter->asDate($modelsubs->tglendsubs, "php:d-m-Y") : '<span class="text-muted">Unknown</span>' ?>
							</span>
						</li>
						<li class="list-group-item d-flex justify-content-between">
							<strong>💰 <?= Yii::$app->lang->t('purchase_table', 'purchase_statuspembayaran') ?> : </strong>
							<?php
							if ($modelsubs->statuspaid == 'unpaid') {
								echo "<span class='text-danger fw-bold'>" . Yii::$app->lang->t('cashbackend', 'cashbackend12') . "</span>";
							} else {
								echo "<span class='text-success fw-bold'>" . Yii::$app->lang->t('cashbackend', 'cashbackend10') . "</span>";
							}
							?>
						</li>
					</ul>
					<!-- Tombol (kalau mau diaktifkan nanti) -->

					<div class="d-flex justify-content-end gap-2 mt-3">
						<a href="<?= Url::to(['users/changebilling']) ?>" class="btn btn-success" id="change" data-id="<?= $modelsubs->subs_id ?>"><?= Yii::$app->lang->t('extra', 'extra14') ?></a>
						<a href="<?= Url::to(['users/softdelete']) ?>" class="btn btn-danger" id="statussoftdel" data-id="<?= $modelsubs->companyid ?>"><?= Yii::$app->lang->t('back_home', 'chat34') ?></a>
					</div>

				</div>
			</div>
		</div>
	</div>
<?php else : ?>

	<div class="card mt-5">
		<div class="card-header">
			<div class="card-title d-flex flex-column">
				<h1 class="mt-2"><?= Yii::$app->lang->t('billing', 'billing5') ?></h1>
				<h4 class="mt-5"><?= Yii::$app->lang->t('billing', 'billing3') ?></h4>
			</div>
		</div>
		<div class="card-body">
			<!-- Tabs -->
			<ul class="nav nav-pills mb-9 justify-content-center" id="pills-tab" role="tablist">
				<li class="nav-item" role="presentation">
					<button class="nav-link active" id="pills-monthly-tab" data-bs-toggle="pill" data-bs-target="#pills-monthly" type="button" role="tab" aria-controls="pills-monthly" aria-selected="true">
						<?= Yii::$app->lang->t('front_home', 'mon') ?>
					</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="pills-annual-tab" data-bs-toggle="pill" data-bs-target="#pills-annual" type="button" role="tab" aria-controls="pills-annual" aria-selected="false">
						<?= Yii::$app->lang->t('front_home', 'year') ?>
					</button>
				</li>
			</ul>

			<!-- Tab Contents -->
			<div class="tab-content" id="pills-tabContent">

				<!-- Monthly -->
				<div class="tab-pane fade show active" id="pills-monthly" role="tabpanel" aria-labelledby="pills-monthly-tab">
					<div class="row g-4 justify-content-center">
						<?php foreach ($paket as $package) :
							if ($package['type'] !== 'mon') continue; ?>
							<div class="col-md-6 col-lg-4" style="cursor: pointer;">
								<input type="radio" value="<?= $package['subs_id'] ?>" id="package<?= $package['subs_id'] ?>" name="package" class="package-radio d-none" data-price="<?= $package['total'] ?>" data-type="<?= $package['type'] ?>">
								<label for="package<?= $package['subs_id'] ?>" class="card border-0 custom py-10 px-4 position-relative">
									<div class="card-header text-center">
										<h4 class="fw-bold mb-1"><?= $package['package_name'] ?></h4>
											<h5 class="text-primary">Rp. <?= number_format($package['total'], 0, ',', '.') ?> / <?= Yii::$app->lang->t('front_home', $package['type']) ?></h5>
									</div>
									<div class="package-icon text-center mt-5">
										<?php
										// Menentukan ikon berdasarkan tipe paket
										if ($package['package_name'] == 'Basic') {
											echo '<i class="fa-solid fa-certificate fs-2"></i>';  // Ikon Basic
										} elseif ($package['package_name'] == 'Advanced') {
											echo '<i class="fa-solid fa-gem fs-2"></i>';  // Ikon Advanced
										} elseif ($package['package_name'] == 'Enterprise') {
											echo '<i class="fa-solid fa-crown fs-2"></i>';  // Ikon Enterprise
										}
										?>
									</div>
									<div class="card-body text-center">
										<h5 class="text-black fw-bold"><?= $package['package_name'] ?></h6>
											<div class="text-gray-700 fw-medium mb-2"><?= Yii::$app->lang->t('price_home', $package['description']) ?></div>
											<div class="fw-bold text-primary fs-5">
												Rp <?= number_format($package['total'], 0, ',', '.') ?>
												<span class="fs-6 opacity-50">/ <?= Yii::$app->lang->t('front_home', $package['type']) ?></span>
											</div>
											<div class="w-100 my-3">
												<?php $features = explode(',', $package['features']); ?>
												<?php foreach ($features as $feature) : ?>
													<div class="d-flex align-items-center gap-1 mb-1">
														<i class="fa-solid fa-circle-check text-success"></i>
														<span class="fw-medium text-dark fs-6"><?= Yii::$app->lang->t('fh', trim($feature)) ?></span>
													</div>
												<?php endforeach; ?>
											</div>
											<div class="checkmark position-absolute top-0 end-0 p-3 d-none">
												<i class="fa-solid fa-check-circle text-primary fs-3"></i>
											</div>
									</div>
								</label>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<!-- Annual -->
				<div class="tab-pane fade" id="pills-annual" role="tabpanel" aria-labelledby="pills-annual-tab">
					<div class="row g-4 justify-content-center">
						<?php foreach ($paket as $package) :
							if ($package['type'] !== 'year') continue; ?>
							<div class="col-md-6 col-lg-4" style="cursor: pointer;">
								<input type="radio" value="<?= $package['subs_id'] ?>" id="package<?= $package['subs_id'] ?>" name="package" class="package-radio d-none" data-price="<?= $package['total'] ?>" data-tipe="<?= $package['type'] ?>">
								<label for="package<?= $package['subs_id'] ?>" class="card border-0 custom py-10 px-4 position-relative">
									<div class="card-header text-center">
										<h4 class="fw-bold mb-1"><?= $package['package_name'] ?></h5>
											<h5 class="text-primary">Rp. <?= number_format($package['total'], 0, ',', '.') ?> / <?= Yii::$app->lang->t('front_home', $package['type']) ?></h6>
									</div>
									<div class="package-icon text-center mt-5">
										<?php
										// Menentukan ikon berdasarkan tipe paket
										if ($package['package_name'] == 'Basic') {
											echo '<i class="fa-solid fa-certificate fs-2"></i>';  // Ikon Basic
										} elseif ($package['package_name'] == 'Advanced') {
											echo '<i class="fa-solid fa-gem fs-2"></i>';  // Ikon Advanced
										} elseif ($package['package_name'] == 'Enterprise') {
											echo '<i class="fa-solid fa-crown fs-2"></i>';  // Ikon Enterprise
										}
										?>
									</div>
									<div class="card-body text-center">
										<h5 class="text-black fw-bold"><?= $package['package_name'] ?></h6>
											<div class="text-gray-700 fw-medium mb-2"><?= Yii::$app->lang->t('price_home', $package['description']) ?></div>
											<div class="fw-bold text-primary fs-5">
												Rp <?= number_format($package['total'], 0, ',', '.') ?>
												<span class="fs-6 opacity-50">/ <?= Yii::$app->lang->t('front_home', $package['type']) ?></span>
											</div>
											<div class="w-100 my-3">
												<?php $features = explode(',', $package['features']); ?>
												<?php foreach ($features as $feature) : ?>
													<div class="d-flex align-items-center gap-1 mb-1">
														<i class="fa-solid fa-circle-check text-success"></i>
														<span class="fw-medium text-dark fs-6"><?= Yii::$app->lang->t('fh', trim($feature)) ?></span>
													</div>
												<?php endforeach; ?>
											</div>
											<div class="checkmark position-absolute top-0 end-0 p-3 d-none">
												<i class="fa-solid fa-check-circle text-primary fs-3"></i>
											</div>
									</div>
								</label>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card mt-5">
		<div class="card-header">
			<div class="card-title">
				<h1><?= Yii::$app->lang->t('billing', 'billing1') ?></h1>
			</div>
		</div>
		<div class="card-body">
			<div id="contactPricing">
				<h1 class="text text-center text-muted"><?= Yii::$app->lang->t('extra', 'extra4') ?></h1>
			</div>
		</div>
	</div>

	<div class="card mt-5">
		<div class="card-header">
			<div class="card-title">
				<h1><?= Yii::$app->lang->t('billing', 'billing4') ?></h1>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table align-middle table-row-dashed fs-6 gy-5">
					<thead>
						<th class="min-w-100px"><?= Yii::$app->lang->t('produk', 'produk_nama') ?></th>
						<th class="min-w-150px"><?= Yii::$app->lang->t('billing', 'billing2') ?></th>
						<th class="min-w-150px"><?= Yii::$app->lang->t('front_home', 'price') ?></th>
						<th class="min-w-100px">PPN 11%</th>
						<th class="min-w-150px"><?= Yii::$app->lang->t('kasbackend', 'total') ?></th>
					</thead>
					<tbody>

					</tbody>
				</table>
			</div>

			<div class="d-flex flex-row justify-content-between mt-4">
				<h1><?= Yii::$app->lang->t('kasbackend', 'total') ?> : </h1>
				<h1 id="totalpaid" name="total"></h1>
			</div>
			<!-- Input Hidden untuk total -->
			<?= Html::hiddenInput('total_price', '', ['id' => 'total_price']) ?>
			<?= Html::hiddenInput('contract', '', ['id' => 'contract']) ?>
			<?= Html::hiddenInput('ppn', '', ['id' => 'ppn']) ?>
			<?= Html::hiddenInput('itemdisc', '', ['id' => 'itemdisc']) ?>
			<?= Html::hiddenInput('persendisc', '', ['id' => 'persendisc']) ?>
			<?= Html::hiddenInput('harga', '', ['id' => 'harga']) ?>
			<div class="float-end">
				<?= Html::submitButton($modelsubs->isNewRecord ? Yii::$app->lang->t('extra', 'extra15') : Yii::$app->lang->t('extra', 'extra15'), ['id' => 'btnsubmit', 'class' => $modelsubs->isNewRecord ? 'btn btn-success mt-5' : 'btn btn-primary mt-5']) ?>
			</div>
		</div>
	</div>
	</div>
	<?php ActiveForm::end(); ?>
<?php endif; ?>
<style>
	.custom {
		transition: all 0.3s ease-in-out;
	}

	.custom:hover {
		cursor: pointer;
		box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.25);
		transform: translateY(-2px);
		background-color: rgba(0, 0, 0, 0.03);
	}

	.package-radio:checked+label {
		border: 3px solid #0b5ed7;
		background-color: rgba(11, 94, 215, 0.15);
		box-shadow: 0px 8px 20px rgba(11, 94, 215, 0.4);
		transform: scale(1.03);
	}

	.package-radio:checked+label:hover {
		background-color: rgba(11, 94, 215, 0.2);
	}

	.contract-radio+label {
		transition: all 0.3s ease-in-out;
		border: 2px solid #dee2e6;
		border-radius: 8px;
		cursor: pointer;
		overflow: hidden;
	}

	.contract-radio+label:hover {
		box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.2);
		transform: translateY(-3px);
		background-color: rgba(0, 0, 0, 0.02);
	}

	.contract-radio:checked+label {
		border: 3px solid #0d6efd;
		background-color: rgba(13, 110, 253, 0.1);
		box-shadow: 0px 8px 20px rgba(13, 110, 253, 0.4);
		transform: scale(1.03);
	}

	.contract-radio:checked+label:hover {
		background-color: rgba(13, 110, 253, 0.15);
	}

	/* Efek label diskon biar lebih menarik */
	.contract-radio+label .position-absolute {
		transition: all 0.3s ease-in-out;
		font-size: 14px;
		border-radius: 0 0 5px 5px;
	}

	.contract-radio:checked+label .position-absolute {
		background-color: #0d6efd !important;
		font-size: 15px;
	}

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
	$('document').ready(function() {
		const radioButtons = document.querySelectorAll(".package-radio");
		const pricingDisplay = document.getElementById("contactPricing");
		const tableBody = document.querySelector(".table tbody");
		const totalpaid = document.getElementById("totalpaid");

		function getContractOptions(packageType) {
			if (packageType === "mon") {
				return [{
						months: 3,
						discount: 0
					},
					{
						months: 6,
						discount: 5
					},
					{
						months: 12,
						discount: 20
					}
				];
			} else if (packageType === "year") {
				return [{
						months: 12,
						discount: 10
					},
					{
						months: 24,
						discount: 25
					}
				];
			} else {
				return [{
						months: 6,
						discount: 2
					},
					{
						months: 12,
						discount: 15
					}
				]; // Default jika tidak ada tipe
			}
		}

		function formatPackageType(packageType) {
			switch (packageType) {
				case "mon":
					return "<?= Yii::$app->lang->t('front_home', 'mon') ?>";
				case "year":
					return "<?= Yii::$app->lang->t('front_home', 'year') ?>";
				default:
					return packageType; // Default jika ada tipe lain
			}
		}

		function updateContractOptions(basePrice, packageType) {
			const contractOptions = getContractOptions(packageType);
			let html = `<div class="row g-4">`;

			contractOptions.forEach(option => {
				const dis = <?= json_encode(Yii::$app->lang->t('extra', 'extra5')) ?>;
				const save = <?= json_encode(Yii::$app->lang->t('billing', 'billing6')) ?>;
				const formattedType = formatPackageType(packageType);
				const discountedPrice = Math.round(basePrice * (1 - option.discount / 100));
				const saving = basePrice - discountedPrice;

				// Format angka ke Rupiah
				const formattedDiscountedPrice = discountedPrice.toLocaleString("id-ID");
				const formattedSaving = saving.toLocaleString("id-ID");
				html += `
				<div class="col-md-6 col-lg-4">
                <input type="radio" id="contract-${option.months}" name="contract" class="contract-radio d-none" 
                    value="${option.months}" data-normalprice="${basePrice}" data-price="${discountedPrice}" data-discount="${option.discount}">
                <label for="contract-${option.months}" class="card border shadow-md p-3 position-relative">
                    <div class="position-absolute top-0 start-0 w-100 text-center text-white fw-bold py-1 
                        ${option.discount > 0 ? 'bg-danger' : 'bg-success'}">
                        ${option.discount > 0 ? `${dis} ${option.discount}%` : `<?= Yii::$app->lang->t('extra', 'extra69') ?>`}
                    </div>
                    <div class="card-body">
                        <h5 class="fw-bold">${option.months} ${formattedType}</h5>
                        <small class="text-gray-500"><s>Rp ${basePrice.toLocaleString("id-ID")} / ${formattedType}</s></small>
                        <h4 class="text-primary">Rp ${formattedDiscountedPrice} / ${formattedType}</h4>
                        ${saving > 0 ? `<small class="text-success">${save} Rp ${formattedSaving}</small>` : ''}
                    </div>
                </label>
            </div>	
        `;
			});

			html += `</div>`;
			pricingDisplay.innerHTML = html;

			// Tambahkan event listener ke radio buttons
			document.querySelectorAll(".contract-radio").forEach(contractRadio => {
				contractRadio.addEventListener("click", function(event) {
					const discountedPrice = this.getAttribute("data-price");
					const discountpersen = this.getAttribute("data-discount");
					const baseprice = this.getAttribute("data-normalprice");
					document.getElementById("itemdisc").value = discountedPrice;
					document.getElementById("persendisc").value = discountpersen;
					document.getElementById("harga").value = baseprice;
					// Jika radio ini sebelumnya sudah dipilih, maka klik lagi akan membatalkan pilihan
					if (this.dataset.wasChecked === "true") {
						this.checked = false; // Uncheck
						this.dataset.wasChecked = "false";

						// Hapus border dari label
						this.parentElement.querySelector("label").classList.remove("border-primary");

						// Reset total harga atau buat jadi kosong
						updateTotalValue(true); // Kirim argumen untuk menandakan "reset"

						return;
					}

					// Set semua radio ke "false" dulu, biar yang lain bisa dipilih tanpa harus klik dua kali
					document.querySelectorAll(".contract-radio").forEach(radio => {
						radio.dataset.wasChecked = "false";
					});

					// Set yang ini jadi "true"
					this.dataset.wasChecked = "true";

					// Reset semua border, lalu tambahkan border ke yang dipilih
					document.querySelectorAll(".contract-radio").forEach(radio => {
						radio.parentElement.querySelector("label").classList.remove("border-primary");
					});
					this.parentElement.querySelector("label").classList.add("border-primary");

					updateTotalValue();
				});
			});

			updateTotalValue();
		}

		function updateTotalValue(reset = false) {
			const selectedPackage = document.querySelector(".package-radio:checked");
			const selectedContract = document.querySelector(".contract-radio:checked");

			const packageName = selectedPackage.nextElementSibling.querySelector("h4").innerText;
			const contractLength = selectedContract ? selectedContract.value : 1; // Default 1 bulan jika belum pilih kontrak
			const pricePerMonth = selectedContract ? parseInt(selectedContract.getAttribute("data-price")) : parseInt(selectedPackage.dataset.price);
			const ppn = Math.round(pricePerMonth * 0.11);
			const totalPrice = (pricePerMonth + ppn) * contractLength;
			const packageType = selectedPackage.getAttribute("data-tipe"); // Ambil package type
			let packageTypeText = packageType === 'mon' ?
				"<?= Yii::$app->lang->t('front_home', 'mon') ?>" :
				"<?= Yii::$app->lang->t('front_home', 'year') ?>";

			tableBody.innerHTML = `
        <tr>
            <td>${packageName}</td>
            <td>${contractLength} ${packageTypeText}</td>
            <td>Rp ${pricePerMonth.toLocaleString("id-ID")} / ${packageTypeText}</td>
            <td>Rp ${ppn.toLocaleString("id-ID")}</td>
            <td class="fw-bold text-primary">Rp ${totalPrice.toLocaleString("id-ID")}</td>
        </tr>
    `;
			totalpaid.innerHTML = `Rp ${totalPrice.toLocaleString("id-ID")}`;
			document.getElementById("total_price").value = totalPrice;
			document.getElementById("contract").value = contractLength;
			document.getElementById("ppn").value = ppn;
		}

		radioButtons.forEach(radio => {
			radio.addEventListener("change", function() {
				document.querySelectorAll(".checkmark").forEach(mark => mark.classList.add("d-none"));
				this.nextElementSibling.querySelector(".checkmark").classList.remove("d-none");

				const basePrice = parseInt(this.getAttribute("data-price"));
				const packageType = this.getAttribute("data-tipe"); // Ambil package type

				updateContractOptions(basePrice, packageType);
			});
		});

		function resetContractOptions() {
			pricingDisplay.innerHTML = `
		<div class="text-muted text-center py-3">
			<h1 class="text text-center text-muted"><?= Yii::$app->lang->t('extra', 'extra4') ?></h1>
		</div>
	`;
			document.getElementById("itemdisc").value = "";
			document.getElementById("persendisc").value = "";
			document.getElementById("harga").value = "";
		}

		document.querySelectorAll('button[data-bs-toggle="pill"]').forEach(tabBtn => {
			tabBtn.addEventListener('shown.bs.tab', function() {
				resetContractOptions(); // Reset kontrak dulu
				updateTotalValue(true); // Reset total juga
			});
		});

		const selectedRadio = document.querySelector(".package-radio:checked");
		if (selectedRadio) {
			selectedRadio.dispatchEvent(new Event("change"));
		}

		$('#change').on('click', function(e) {
			e.preventDefault(); // Mencegah reload
			var url = $(this).attr('href'); // Ambil URL dari href

			// Ubah URL di browser tanpa reload
			history.pushState(null, '', url);

			// Ambil halaman target via AJAX
			$.ajax({
				url: url,
				type: 'GET',
				success: function(data) {
					$('.app-container.utama').html(data); // Ganti seluruh isi halaman
				},
				error: function(xhr, status, error) {
					alert("Gagal memuat halaman: " + error);
				}
			});
		});

		var deletemessage1 = "<?= Yii::$app->lang->t('extra', 'extra44') ?>";
		var deletemessage2 = "<?= Yii::$app->lang->t('extra', 'extra45') ?>";
		var deletemessage3 = "<?= Yii::$app->lang->t('extra', 'extra46') ?>";
		var deletemessage3koma1 = "<?= Yii::$app->lang->t('extra', 'extra46.1') ?>";
		var deletemessage4 = "<?= Yii::$app->lang->t('back_home', 'chat34') ?>";
		var deletemessage5 = "<?= Yii::$app->lang->t('back_home', 'chat53') ?>";
		$('#statussoftdel').on('click', function(e) {
			e.preventDefault(); // Hindari reload

			let subsid = $(this).data('id');
			var url = $(this).attr('href'); // Ambil URL dari href

			console.log(subsid);
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
						url: url,
						type: 'POST',
						data: {
							id: subsid,
							_csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
						},
						headers: {
							"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
						},
						success: function(response) {
							if (response.success) {
								Swal.fire({
									title: "Deleted!",
									text: "Data berhasil dihapus.",
									icon: "success",
									confirmButtonText: "OK"
								}).then(() => {
									// 🔥 Ganti `location.reload()` dengan AJAX update daftar company
									reload();
								});
							} else {
								Swal.fire("Error!", response.message, "error");
							}
						},
						error: function() {
							Swal.fire("Error!", "Terjadi kesalahan saat menghapus.", "error");
						}
					});
				}
			});
		});

		function reload() {
			var url = "<?= Url::to(['users/billing']) ?>"; // Ambil URL dari href

			// Ubah URL di browser tanpa reload
			history.pushState(null, '', url);

			// Ambil halaman target via AJAX
			$.ajax({
				url: url,
				type: 'GET',
				success: function(data) {
					$('.app-container.utama').html(data); // Ganti seluruh isi halaman
				},
				error: function(xhr, status, error) {
					alert("Gagal memuat halaman: " + error);
				}
			});
		}
	});
</script>