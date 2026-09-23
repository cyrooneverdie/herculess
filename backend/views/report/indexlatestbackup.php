<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>
<style>
	.btn-custom {
		background-color: transparent !important;
		border: none !important;
		box-shadow: none !important;
		padding: 0 !important;
		/* Hapus padding biar gak nambah ukuran */
		display: inline-flex;
		/* Biar tombolnya ngepas sama ikon */
		align-items: center;
		justify-content: center;
	}

	.btn-custom i {
		font-size: 16px;
		/* Sesuaikan ukuran ikon */
		color: #bbb;
		/* Warna default */
	}

	.btn-custom:hover i,
	button {
		background-color: white !important;
		/* Warna kuning saat hover */
	}

	#favorite li {
		list-style: none;
		display: flex;
		align-items: center;
		gap: 10px;
		/* Mirip ant-space-gap */
		padding: 10px;
		border-radius: 5px;
		text-decoration: none;
		color: #0d6efd;
		border: 1px solid transparent;
		/* Border ada, tapi tidak terlihat */
		transition: background-color 0.3s ease-in-out;
	}

	#favorite li:hover {
		border-color: #0d6efd;
		color: white;
	}

	#favorite i {
		font-size: 16px;
		/* Mirip dengan Ant Design */
	}

	.report-menu li {
		display: flex;
		align-items: center;
		gap: 10px;
		/* Mirip ant-space-gap */
		padding: 10px;
		border-radius: 5px;
		text-decoration: none;
		color: #0d6efd;
		border: 1px solid transparent;
		/* Border ada, tapi tidak terlihat */
		transition: background-color 0.3s ease-in-out;
	}

	.report-menu li:hover {
		border-color: #0d6efd;
		color: white;
	}

	.report-menu i {
		font-size: 16px;
		/* Mirip dengan Ant Design */
	}
</style>

<!--begin::Toolbar-->
<div id="" class="app-toolbar py-3 py-lg-6 ms-n8">
	<!--begin::Toolbar container-->
	<div id="" class="app-container container-fluid d-flex flex-stack">
		<!--begin::Page title-->
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<!--begin::Title-->
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0"><?= Yii::$app->lang->t('extrasidebar', 'extrasidebar21') ?></h1>
			<!--end::Title-->
			<!--begin::Breadcrumb-->
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<!--begin::Item-->
				<li class="breadcrumb-item text-muted">
					<a href="<?= Url::to(['site/index']) ?>" class="text-muted text-hover-primary"><?= Yii::$app->lang->t('back_home', 'chat2') ?></a>
				</li>
				<!--end::Item-->
				<!--begin::Item-->
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<!--end::Item-->
				<!--begin::Item-->
				<li class="breadcrumb-item text-muted"><?= Yii::$app->lang->t('extrasidebar', 'extrasidebar21') ?></li>
				<!--end::Item-->
			</ul>
			<!--end::Breadcrumb-->
		</div>
		<!--end::Page title-->
		<!--begin::Actions-->
		<!--end::Actions-->
	</div>
	<!--end::Toolbar container-->
</div>

<div class="row g-5 gx-xl-10 mb-5 mb-xl-10 mt-5">
	<div class="d-flex gap-5 flex-column flex-column-fluid">
		<div class="card mb-5 d-flex text-center">
			<div class="card-header border-0 pt-6 text-center align-items-center justify-content-center">
				<div class="card-title">
					<h1 class="text text-center mb-5"><?= Yii::$app->lang->t('report', 'report1') ?></h1>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header border-0 text-center">
				<div class="card-title">
					<h2><?= Yii::$app->lang->t('report', 'report2') ?></h2>
				</div>
			</div>
			<div class="card-body nopadding mt-n10">
				<div id="favorite">
					<ul class="list-unstyled">

					</ul>
				</div>
			</div>
		</div>
		<!-- Wrapper untuk menempatkan card secara horizontal -->
		<div class="d-flex gap-5 justify-content-start">
			<div class="card w-50">
				<div class="card-header border-0 pt-6 text-center">
					<div class="card-title">
						<h3 class="text text-dark text-hover-primary" style="cursor: pointer;"><?= Yii::$app->lang->t('report', 'report3') ?></h3>
					</div>
				</div>
				<div class="card-body">
					<ul class="list-unstyled report-menu">
						<li class="mb-3" data-id="1">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="<?= Url::to(['report/laporan']) ?>?type=campuran" class="text text-primary campuran">
								<?= Yii::$app->lang->t('report', 'report4') ?>
							</a>
						</li>
						<li class="mb-3" data-id="2">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary cashflow-link" data-type="cashflow">
								<?= Yii::$app->lang->t('report', 'report5') ?>
							</a>
						</li>
						<li class="mb-3" data-id="3">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary profandlost-link" data-type="profandlost">
								<?= Yii::$app->lang->t('report', 'report6') ?>
							</a>
						</li>
						<li class="mb-3" data-id="4">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary equitymovement-link" data-type="equitymovement">
								<?= Yii::$app->lang->t('report', 'report7') ?>
							</a>
						</li>
						<li class="mb-3" data-id="5">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary executivesummary-link" data-type="executivesummary">
								<?= Yii::$app->lang->t('report', 'report8') ?>
							</a>
						</li>
						<li class="mb-3" data-id="6">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary payable-per-contact-link" data-type="payable-per-contact">
								<?= Yii::$app->lang->t('report', 'report9') ?>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="card w-50 h-350px">
				<div class="card-header border-0 pt-6 text-center">
					<div class="card-title">
						<h3 class="text text-dark text-hover-primary" style="cursor: pointer;"><?= Yii::$app->lang->t('report', 'report10') ?></h3>
					</div>
				</div>
				<div class="card-body">
					<ul class="list-unstyled report-menu">
						<li class="mb-3" data-id="7">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary banksummary-link" data-type="banksummary">
								<?= Yii::$app->lang->t('report', 'report11') ?>
							</a>
						</li>
						<li class="mb-3" data-id="8">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary generalledger-link" data-type="generalledger">
								<?= Yii::$app->lang->t('report', 'report12') ?>
							</a>
						</li>
						<li class="mb-3" data-id="9">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary journal-link" data-type="journal">
								<?= Yii::$app->lang->t('report', 'report13') ?>
							</a>
						</li>
						<li class="mb-3" data-id="10">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary trialbalance-link" data-type="trialbalance">
								<?= Yii::$app->lang->t('report', 'report14') ?>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="d-flex gap-5 justify-content-start">
			<div class="card w-50">
				<div class="card-header border-0 pt-6 text-center">
					<div class="card-title">
						<h3 class="text text-dark text-hover-primary" style="cursor: pointer;"><?= Yii::$app->lang->t('extrasidebar', 'extrasidebar13') ?></h3>
					</div>
				</div>
				<div class="card-body">
					<ul class="list-unstyled report-menu">
						<li class="mb-3" data-id="11">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary salesdetail-link" data-type="salesdetail">
								<?= Yii::$app->lang->t('report', 'report15') ?>
							</a>
						</li>
						<li class="mb-3" data-id="12">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary oldreceive-link" data-type="oldreceive">
								<?= Yii::$app->lang->t('report', 'report16') ?>
							</a>
						</li>
						<li class="mb-3" data-id="13">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary customerinvoices-link" data-type="customerinvoices">
								<?= Yii::$app->lang->t('report', 'report17') ?>
							</a>
						</li>
						<li class="mb-3" data-id="14">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary productprofit-link" data-type="productprofit">
								<?= Yii::$app->lang->t('report', 'report18') ?>
							</a>
						</li>
						<li class="mb-3" data-id="15">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary invoprofit-link" data-type="infoprofit">
								<?= Yii::$app->lang->t('report', 'report19') ?>
							</a>
						</li>
						<li class="mb-3" data-id="16">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary income-per-customer-link" data-type="income-per-customer">
								<?= Yii::$app->lang->t('report', 'report20') ?>
							</a>
						</li>
						<li class="mb-3" data-id="17">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary sales-per-product-link" data-type="sales-per-product">
								<?= Yii::$app->lang->t('report', 'report21') ?>
							</a>
						</li>
						<li class="mb-3" data-id="18">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary order-per-product-link" data-type="order-per-product">
								<?= Yii::$app->lang->t('report', 'report22') ?>
							</a>
						</li>
						<li class="mb-3" data-id="19">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary sales-per-sales-peson-link" data-type="sales-per-sales-peson">
								<?= Yii::$app->lang->t('report', 'report23') ?>
							</a>
						</li>
						<li class="mb-3" data-id="20">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary salesdelivery-link" data-type="salesdelivery">
								<?= Yii::$app->lang->t('report', 'report24') ?>
							</a>
						</li>
						<li class="mb-3" data-id="21">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary shipping-cost-per-expedition-link" data-type="shipping-cost-per-expedition">
								<?= Yii::$app->lang->t('report', 'report25') ?>
							</a>
						</li>
						<li class="mb-3" data-id="22">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary invoicepayment-link" data-type="invoicepayment">
								<?= Yii::$app->lang->t('report', 'report26') ?>
							</a>
						</li>
						<li class="mb-3" data-id="23">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary sales-per-product-category-link" data-type="sales-per-product-category">
								<?= Yii::$app->lang->t('report', 'report27') ?>
							</a>
						</li>
						<li class="mb-3" data-id="24">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary product-sales-per-customer-link" data-type="product-sales-per-customer">
								<?= Yii::$app->lang->t('report', 'report28') ?>
							</a>
						</li>
						<li class="mb-3" data-id="25">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary sales-per-period-link" data-type="sales-per-period">
								<?= Yii::$app->lang->t('report', 'report29') ?>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="card w-50 h-600px">
				<div class="card-header border-0 pt-6 text-center">
					<div class="card-title">
						<h3 class="text text-dark text-hover-primary" style="cursor: pointer;"><?= Yii::$app->lang->t('extrasidebar', 'extrasidebar7') ?></h3>
					</div>
				</div>
				<div class="card-body">
					<ul class="list-unstyled report-menu">
						<li class="mb-3" data-id="26">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary purchasesdetail-link" data-type="purchasesdetail">
								<?= Yii::$app->lang->t('report', 'report30') ?>
							</a>
						</li>
						<li class="mb-3" data-id="27">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary oldpayable-link" data-type="oldpayable">
								<?= Yii::$app->lang->t('report', 'report31') ?>
							</a>
						</li>
						<li class="mb-3" data-id="28">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary vendorinvoices-link" data-type="vendorinvoices">
								<?= Yii::$app->lang->t('report', 'report32') ?>
							</a>
						</li>
						<li class="mb-3" data-id="29">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary purchase-per-product-link" data-type="purchase-per-product">
								<?= Yii::$app->lang->t('report', 'report33') ?>
							</a>
						</li>
						<li class="mb-3" data-id="30">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary purchases-per-supplier-link" data-type="purchases-per-supplier">
								<?= Yii::$app->lang->t('report', 'report34') ?>
							</a>
						</li>
						<li class="mb-3" data-id="31">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary purchasesdelivery-link" data-type="purchasesdelivery">
								<?= Yii::$app->lang->t('report', 'report35') ?>
							</a>
						</li>
						<li class="mb-3" data-id="32">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary purchases-invoice-payment-link" data-type="purchases-invoice-payment">
								<?= Yii::$app->lang->t('report', 'report36') ?>
							</a>
						</li>
						<li class="mb-3" data-id="33">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary product-purchases-per-vendor-link" data-type="product-purchases-per-vendor">
								<?= Yii::$app->lang->t('report', 'report37') ?>
							</a>
						</li>
						<li class="mb-3" data-id="34">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary purchases-per-period-link" data-type="purchases-per-period">
								<?= Yii::$app->lang->t('report', 'report38') ?>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="d-flex gap-5 justify-content-start">
			<div class="card w-50">
				<div class="card-header border-0 pt-6 text-center">
					<div class="card-title">
						<h3 class="text text-dark text-hover-primary" style="cursor: pointer;"><?= Yii::$app->lang->t('report', 'report39') ?></h3>
					</div>
				</div>
				<div class="card-body">
					<ul class="list-unstyled report-menu">
						<li class="mb-3" data-id="35">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary expenses-per-contact-link" data-type="expenses-per-contact">
								<?= Yii::$app->lang->t('report', 'report40') ?>
							</a>
						</li>
						<li data-id="36">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary witholdingtax-link" data-type="witholdingtax">
								<?= Yii::$app->lang->t('report', 'report41') ?>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="card w-50">
				<div class="card-header border-0 pt-6 text-center">
					<div class="card-title">
						<h3 class="text text-dark text-hover-primary" style="cursor: pointer;"><?= Yii::$app->lang->t('report', 'report42') ?></h3>
					</div>
				</div>
				<div class="card-body">
					<ul class="list-unstyled report-menu">
						<li class="mb-3" data-id="37">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary salestax-link" data-type="salestax">
								<?= Yii::$app->lang->t('report', 'report43') ?>
							</a>
						</li>
						<li data-id="38">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary expenses-claim-detail-link" data-type="expenses-claim-detailment">
								<?= Yii::$app->lang->t('report', 'report44') ?>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="d-flex gap-5 justify-content-start">
			<div class="card w-50">
				<div class="card-header border-0 pt-6 text-center">
					<div class="card-title">
						<h3 class="text text-dark text-hover-primary" style="cursor: pointer;"><?= Yii::$app->lang->t('report', 'report45') ?></h3>
					</div>
				</div>
				<div class="card-body">
					<ul class="list-unstyled report-menu">
						<li class="mb-3" data-id="39">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="<?= Url::to(['report/laporan']) ?>?type=stock" class="text text-primary stock-link" data-type="stock">
								<?= Yii::$app->lang->t('report', 'report46') ?>
							</a>
						</li>
						<li class="mb-3" data-id="40">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary stockmove-link" data-type="stockmovement">
								<?= Yii::$app->lang->t('report', 'report47') ?>
							</a>
						</li>
						<li class="mb-3" data-id="41">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary warehouse-link" data-type="warehouse">
								<?= Yii::$app->lang->t('report', 'report48') ?>
							</a>
						</li>
						<li class="mb-3" data-id="42">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary warehousemovement-link" data-type="warehousemovement">
								<?= Yii::$app->lang->t('report', 'report49') ?>
							</a>
						</li>
						<li class="mb-3" data-id="43">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary reportproduction-link" data-type="reportproduction">
								<?= Yii::$app->lang->t('report', 'report50') ?>
							</a>
						</li>
						<li class="mb-3" data-id="44">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary stock-adjustment-report-link" data-type="stock-adjustment-report">
								<?= Yii::$app->lang->t('report', 'report51') ?>
							</a>
						</li>
						<li class="mb-3" data-id="45">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary warehouse-transfer-report-link" data-type="warehouse-transfer-report">
								<?= Yii::$app->lang->t('report', 'report52') ?>
							</a>
						</li>
						<li class="mb-3" data-id="46">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary serial-number-movement-report-link" data-type="serial-number-movement-report">
								<?= Yii::$app->lang->t('report', 'report53') ?>
							</a>
						</li>
						<li class="mb-3" data-id="47">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary serial-number-stock-per-warehouse-report-link" data-type="serial-number-stock-per-warehouse-report">
								<?= Yii::$app->lang->t('report', 'report54') ?>
							</a>
						</li>
						<li class="mb-3" data-id="48">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary product-item-expires-soon-report-link" data-type="product-item-expires-soon-report">
								<?= Yii::$app->lang->t('report', 'report55') ?>
							</a>
						</li>
						<li class="mb-3" data-id="49">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary product-item-age-report-link" data-type="product-item-age-report">
								<?= Yii::$app->lang->t('report', 'report56') ?>
							</a>
						</li>
						<li class="mb-3" data-id="50">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary serial-number-history-report-link" data-type="serial-number-history-report">
								<?= Yii::$app->lang->t('report', 'report57') ?>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="card w-50 h-300px">
				<div class="card-header border-0 pt-6 text-center">
					<div class="card-title">
						<h3 class="text text-dark text-hover-primary" style="cursor: pointer;"><?= Yii::$app->lang->t('report', 'report58') ?></h3>
					</div>
				</div>
				<div class="card-body">
					<ul class="list-unstyled report-menu">
						<li class="mb-3" data-id="51">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary fixed-assets-summary-link" data-type="fixed-assets-summary">
								<?= Yii::$app->lang->t('report', 'report59') ?>
							</a>
						</li>
						<li class="mb-3" data-id="52">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary fixed-assets-detail-link" data-type="fixed-assets-detailment">
								<?= Yii::$app->lang->t('report', 'report60') ?>
							</a>
						</li>
						<li class="mb-3" data-id="53">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary fixed-assets-disposal-link" data-type="fixed-assets-disposalment">
								<?= Yii::$app->lang->t('report', 'report61') ?>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="d-flex gap-5 justify-content-start">
			<div class="card w-50">
				<div class="card-header border-0 pt-6 text-center">
					<div class="card-title">
						<h3 class="text text-dark text-hover-primary" style="cursor: pointer;"><?= Yii::$app->lang->t('report', 'report62') ?></h3>
					</div>
				</div>
				<div class="card-body">
					<ul class="list-unstyled report-menu">
						<li class="mb-3" data-id="54">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary budget-management-link" data-type="budget-management">
								<?= Yii::$app->lang->t('report', 'report63') ?>
							</a>
						</li>
						<li class="mb-3" data-id="55">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary budget-profit-loss-link" data-type="budget-profit-loss">
								<?= Yii::$app->lang->t('report', 'report64') ?>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="card w-50 h-200px">
				<div class="card-header border-0 pt-6 text-center">
					<div class="card-title">
						<h3 class="text text-dark text-hover-primary" style="cursor: pointer;">POS</h3>
					</div>
				</div>
				<div class="card-body">
					<ul class="list-unstyled report-menu">
						<li class="mb-3" data-id="56">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary shift-report-link" data-type="shift-report">
								<?= Yii::$app->lang->t('report', 'report65') ?>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="d-flex gap-5 justify-content-start">
			<div class="card w-50">
				<div class="card-header border-0 pt-6 text-center">
					<div class="card-title">
						<h3 class="text text-dark text-hover-primary" style="cursor: pointer;"><?= Yii::$app->lang->t('report', 'report66') ?></h3>
					</div>
				</div>
				<div class="card-body">
					<ul class="list-unstyled report-menu">
						<li class="mb-3" data-id="57">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary attachments-link" data-type="attachments">
								Attachment
							</a>
						</li>
						<li class="mb-3" data-id="58">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="<?= Url::to(['report/laporan']) ?>?type=export" class="text text-primary export-link" data-type="export">
								<?= Yii::$app->lang->t('report', 'report67') ?>
							</a>
						</li>
						<li class="mb-3" data-id="59">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary import-link" data-type="import">
								Import
							</a>
						</li>
						<li class="mb-3" data-id="60">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary duty-stamp-link" data-type="duty-stamp">
								<?= Yii::$app->lang->t('report', 'report68') ?>
							</a>
						</li>
						<li class="mb-3" data-id="61">
							<button type="button" class="btn btn-light btn-custom"><i class="fa-regular fa-star"></i></button>
							<a href="#" class="text text-primary activity-team-report-link" data-type="activity-team-report">
								<?= Yii::$app->lang->t('report', 'report69') ?>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div id="nothing" class="d-none" style="text-align: center; padding: 20px 0;">
			<img width='250px' src='https://cdni.iconscout.com/illustration/premium/thumb/employee-is-unable-to-find-sensitive-data-illustration-download-in-svg-png-gif-file-formats--no-found-misplaced-files-business-pack-illustrations-8062128.png' />
			<div style="font-weight: bold; font-size: 16px; margin-top : 8px;"> Data not found</div>
		</div>
	</div>
</div>

<script>
	$('.stock-link').on('click', function(e) {
		e.preventDefault(); // Mencegah reload
		var url = $(this).attr('href'); // Ambil URL dari href

		// Ubah URL di browser tanpa reload
		history.pushState(null, '', url);

		// Ambil halaman target via AJAX
		$.ajax({
			url: url,
			type: 'GET',
			success: function(data) {
				$('.app-container.container-xxl').html(data); // Ganti seluruh isi halaman
			},
			error: function(xhr, status, error) {
				alert("Gagal memuat halaman: " + error);
			}
		});
	});

	$('.export-link').on('click', function(e) {
		e.preventDefault(); // Mencegah reload
		var url = $(this).attr('href'); // Ambil URL dari href

		// Ubah URL di browser tanpa reload
		history.pushState(null, '', url);

		// Ambil halaman target via AJAX
		$.ajax({
			url: url,
			type: 'GET',
			success: function(data) {
				$('.app-container.container-xxl').html(data); // Ganti seluruh isi halaman
			},
			error: function(xhr, status, error) {
				alert("Gagal memuat halaman: " + error);
			}
		});
	});

	$('.campuran').on('click', function(e) {
		e.preventDefault(); // Mencegah reload
		var url = $(this).attr('href'); // Ambil URL dari href

		// Ubah URL di browser tanpa reload
		history.pushState(null, '', url);

		// Ambil halaman target via AJAX
		$.ajax({
			url: url,
			type: 'GET',
			success: function(data) {
				$('.app-container.container-xxl').html(data); // Ganti seluruh isi halaman
			},
			error: function(xhr, status, error) {
				alert("Gagal memuat halaman: " + error);
			}
		});
	});

	// Tangani tombol back/forward di browser
	window.onpopstate = function() {
		location.reload(); // Reload ketika user klik tombol "Back"
	};

	$(document).ready(function() {
		const favoriteContainer = $('#favorite');

		// Load favorit dari localStorage saat halaman dimuat
		let favorites = JSON.parse(localStorage.getItem('favorites')) || [];

		function renderFavorites() {
			favoriteContainer.empty(); // Kosongkan favorit sebelum render ulang
			favorites.forEach(item => {
				let listItem = $(`[data-id="${item.id}"]`).clone(); // Clone dari daftar asli
				listItem.find('i').removeClass('fa-regular').addClass('fa-solid'); // Pastikan bintang solid
				favoriteContainer.append(listItem);
				$(`[data-id="${item.id}"]`).find('i').removeClass('fa-regular').addClass('fa-solid'); // Update ikon di list asli
			});
		}

		renderFavorites(); // Render favorit saat halaman dimuat

		$('.btn-custom').on('click', function() {
			let button = $(this);
			let icon = button.find('i');
			let listItem = button.closest('li'); // Ambil parent <li>
			let itemId = listItem.data('id'); // ID unik untuk tiap item
			let itemText = listItem.find('a').text(); // Nama laporan

			if (icon.hasClass('fa-regular')) {
				// Ubah ikon jadi solid (favorit)
				icon.removeClass('fa-regular').addClass('fa-solid');

				// Cek apakah sudah ada di favorit, jika belum tambahkan
				if (!favorites.some(fav => fav.id === itemId)) {
					favorites.push({
						id: itemId,
						text: itemText
					});
					localStorage.setItem('favorites', JSON.stringify(favorites));
					renderFavorites();
				}
			} else {
				// Ubah ikon jadi tidak favorit
				icon.removeClass('fa-solid').addClass('fa-regular');

				// Hapus dari favorit berdasarkan ID
				favorites = favorites.filter(fav => fav.id !== itemId);
				localStorage.setItem('favorites', JSON.stringify(favorites));
				renderFavorites();
			}
		});
	});
</script>