<?php

use yii\helpers\Html;
use yii\helpers\Url;

switch ("$module/$type") {
    case 'sales/quote':
        $urldetail = Url::to(['sales/quote']);
        $this->title = Yii::$app->lang->t('extrasidebar', 'extrasidebar14');
        break;
    case 'sales/order':
        $urldetail = Url::to(['sales/order']);
        $this->title = Yii::$app->lang->t('extrasidebar', 'extrasidebar15');
        break;
    case 'purchase/request':
        $urldetail = Url::to(['purchase/request']);
        $this->title = Yii::$app->lang->t('extrasidebar', 'extrasidebar8');
        break;
    case 'purchase/order':
        $urldetail = Url::to(['sales/order']);
        $this->title = Yii::$app->lang->t('extrasidebar', 'extrasidebar9');
        break;
    case 'purchase/return':
        $urldetail = Url::to(['purchase/return']);
        $this->title = Yii::$app->lang->t('extrasidebar', 'extrasidebar12');
        break;
}

$isSalesType = in_array("$module/$type", ['sales/order', 'sales/quote']);
$isSalesOrder = ("$module/$type" === 'sales/order');

$eventTypeNames = ['Ful Rent', 'Dry Rent', 'Sub Rent', 'Take Away'];
$crewTypeNames = ['pi' => 'PIC', 'op' => 'Operator', 'sb' => 'Standby', 'cr' => 'Crew', 'dr' => 'Driver', 'fe' => 'Freelance'];
$eventStageNames = ['Setup', 'Event', 'Bongkar', 'Antar', 'Tarik'];
$eventStageBadge = ['primary', 'success', 'danger', 'warning', 'info'];

?>

<div class="modal-content">
    <div class="modal-body p-0">
        <div class="d-flex flex-column px-5 px-lg-8 py-6">

            <div class="d-flex align-items-center justify-content-between mb-6 pb-5 border-bottom border-1">
                <div>
                    <h2 class="fw-bold fs-3 text-gray-900 mb-2">
                        <?= Html::encode($model->tranno) ?>
                    </h2>
                    <span class="text-muted fs-7">
                        Dibuat
                        <?php if ($model->createdat): ?>
                            &nbsp;·&nbsp; <?= Yii::$app->formatter->asDatetime($model->createdat) ?>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <!-- <span class="badge badge-light-success py-2 px-3">
                        <i class="fa-solid fa-circle-check me-1"></i> Confirmed
                    </span> -->
                    <a href="<?= $urldetail ?>" class="btn btn-sm btn-black fw-bold">
                        <i class="fa fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-5">
                <div class="card-header bg-secondary border-0 py-1">
                    <h3 class="card-title fw-bold fs-5 text-black mb-0">
                        <i class="fa-solid fa-circle-info me-2 text-black"></i>
                        Informasi Transaksi
                    </h3>
                </div>
                <div class="card-body p-0">
                    <?php if ($isSalesType): ?>
                        <!-- Sales Type: 5 columns info strip -->
                        <div class="row g-0 border-top border-1">
                            <div class="col-lg-auto flex-grow-1 px-5 py-4 border-end border-1">
                                <div class="text-uppercase text-muted fw-bold fs-8 letter-spacing mb-2">
                                    <?= Yii::$app->lang->t('tran', 'tran_no') ?>
                                </div>
                                <div class="fw-bold fs-6 text-gray-900">
                                    <?= $model->tranno ?? '-' ?>
                                </div>
                            </div>
                            <div class="col-lg-5 px-5 py-4 border-end border-1">
                                <div class="text-uppercase text-muted fw-bold fs-8 letter-spacing mb-2">
                                    <?= Yii::$app->lang->t('extrasidebar', 'extrasidebar5') ?>
                                </div>
                                <?php if ($model->contact_id): ?>
                                    <div class="d-flex flex-column gap-2">
                                        <div class="fs-7 text-gray-800">
                                            <i class="fa-solid fa-building me-2 text-muted"></i>
                                            <span class="fw-semibold">
                                                <?= Yii::$app->function->findByField("jobcompany", "contacts", " and contact_id ='{$model->contact_id}' ") ?>
                                            </span>
                                        </div>
                                       
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </div>
                            <div class="col-lg-auto flex-grow-1 px-5 py-4 border-end border-1">
                                <div class="text-uppercase text-muted fw-bold fs-8 letter-spacing mb-2">
                                    <?= Yii::$app->lang->t('tran', 'tran_date') ?>
                                </div>
                                <div class="fw-semibold text-gray-800">
                                    <?= $model->trandate ? Yii::$app->formatter->asDate($model->trandate) : '-' ?>
                                </div>
                            </div>
                            <div class="col-lg-auto flex-grow-1 px-5 py-4 border-end border-1">
                                <div class="text-uppercase text-muted fw-bold fs-8 letter-spacing mb-2">
                                    <?= Yii::$app->lang->t('tran', 'tran_term') ?>
                                </div>
                                <div class="fw-semibold text-gray-800">
                                    <?= $model->term ?? '-' ?>
                                </div>
                            </div>
                            <div class="col-lg-auto flex-grow-1 px-5 py-4">
                                <div class="text-uppercase text-muted fw-bold fs-8 letter-spacing mb-2">
                                    <?= Yii::$app->lang->t('tran', 'tran_duedate') ?>
                                </div>
                                <div class="fw-semibold text-gray-800">
                                    <?= $model->tranduedate ? Yii::$app->formatter->asDate($model->tranduedate) : '-' ?>
                                </div>
                            </div>
                        </div>

                        <div class="row g-0 border-top border-1">
                            <div class="col-lg-auto flex-grow-1 px-5 py-4 border-end border-1">
                                <div class="text-uppercase text-muted fw-bold fs-8 letter-spacing mb-2">
                                    PIC Customer
                                </div>
                                <div class="fw-semibold text-gray-800">
                                    <?= $model->piccustomer ?? '-' ?>
                                </div>
                            </div>
                            <div class="col-lg-auto flex-grow-1 px-5 py-4 border-end border-1">
                                <div class="text-uppercase text-muted fw-bold fs-8 letter-spacing mb-2">
                                    No. Telp PIC Customer
                                </div>
                                <div class="fw-semibold text-gray-800">
                                    <?= $model->piccustomer_telp ?? '-' ?>
                                </div>
                            </div>
                            <div class="col-lg-auto flex-grow-1 px-5 py-4 border-end border-1">
                                <div class="text-uppercase text-muted fw-bold fs-8 letter-spacing mb-2">
                                    <?= Yii::$app->lang->t('tran', 'tran_setup') ?>
                                </div>
                                <div class="fw-semibold text-gray-800">
                                    <?= $model->setupdate ? Yii::$app->formatter->asDatetime($model->setupdate) : '-' ?>
                                </div>
                            </div>
                            <div class="col-lg-auto flex-grow-1 px-5 py-4">
                                <div class="text-uppercase text-muted fw-bold fs-8 letter-spacing mb-2">
                                    <?= Yii::$app->lang->t('tran', 'tran_withdrawal') ?>
                                </div>
                                <div class="fw-semibold text-gray-800">
                                    <?= $model->withdrawaldate ? Yii::$app->formatter->asDatetime($model->withdrawaldate) : '-' ?>
                                </div>
                            </div>
                        </div>

                    <?php else: ?>
                        <!-- Purchase Type: 4 columns info strip -->
                        <div class="row g-0 border-top border-1">
                            <div class="col-lg-auto flex-grow-1 px-5 py-4 border-end border-1">
                                <div class="text-uppercase text-muted fw-bold fs-8 letter-spacing mb-2">
                                    <?= Yii::$app->lang->t('tran', 'tran_no') ?>
                                </div>
                                <div class="fw-bold fs-6 text-gray-900">
                                    <?= $model->tranno ?? '-' ?>
                                </div>
                            </div>
                            <div class="col-lg-auto flex-grow-1 px-5 py-4 border-end border-1">
                                <div class="text-uppercase text-muted fw-bold fs-8 letter-spacing mb-2">
                                    <?= Yii::$app->lang->t('back_home', 'chat25') ?>
                                </div>
                                <div class="fw-semibold text-gray-800">
                                    <?= $model->contact_id
                                        ? Yii::$app->function->findByField("contact_name", "contacts", " and contact_id ='{$model->contact_id}' ")
                                        : '-' ?>
                                </div>
                            </div>
                            <div class="col-lg-auto flex-grow-1 px-5 py-4 border-end border-1">
                                <div class="text-uppercase text-muted fw-bold fs-8 letter-spacing mb-2">
                                    <?= Yii::$app->lang->t('cashbackend', 'cashbackend16') ?>
                                </div>
                                <div class="fw-semibold text-gray-800">
                                    <?= $model->trandate ? Yii::$app->formatter->asDate($model->trandate) : '-' ?>
                                </div>
                            </div>
                            <!-- <div class="col-lg-auto flex-grow-1 px-5 py-4">
                                <div class="text-uppercase text-muted fw-bold fs-8 letter-spacing mb-2">
                                    Type
                                </div>
                                <div class="fw-semibold text-gray-800">
                                    <?= (isset($model->eventtype) && $model->eventtype == '0')
                                        ? 'Milik'
                                        : (isset($model->eventtype) && $model->eventtype == '1' ? 'Pinjam' : '-') ?>
                                </div>
                            </div> -->
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($isSalesOrder): ?>
                <div class="card shadow-sm border-0 mb-5">
                    <div class="card-header bg-secondary border-0 py-4">
                        <h3 class="card-title fw-bold fs-6 text-black mb-0">
                            <i class="fa fa-user me-2 text-black"></i>
                            PIC Lapangan
                        </h3>
                    </div>
                    <div class="card-body p-5">
                        <div class="row g-3">
                            <?php
                            $picList = [
                                'PIC 1' => [
                                    'nama' => $model->pic1 ?? null,
                                    'telp' => $model->telppic1 ?? null,
                                ],
                                'PIC 2' => [
                                    'nama' => $model->pic2 ?? null,
                                    'telp' => $model->telppic2 ?? null,
                                ],
                                'PIC 3' => [
                                    'nama' => $model->pic3 ?? null,
                                    'telp' => $model->telppic3 ?? null,
                                ],
                                'Manager OP' => [
                                    'nama' => $model->operatorid ? Yii::$app->function->findByField("contact_name", "contacts", " and contact_id ='{$model->operatorid}' ") : null,
                                    'telp' => null,
                                ],
                                'Warehouse Manager' => [
                                    'nama' => $model->storemanid ? Yii::$app->function->findByField("contact_name", "contacts", " and contact_id ='{$model->storemanid}' ") : null,
                                    'telp' => null,
                                ],
                            ];

                            foreach ($picList as $role => $info):
                                if (empty($info['nama']))
                                    continue;
                                ?>
                                <div class="col-md-4 col-lg-3">
                                    <div class="bg-light rounded-2 p-3">
                                        <div class="text-uppercase text-muted fw-bold fs-9 letter-spacing mb-1">
                                            <?= Html::encode($role) ?>
                                        </div>
                                        <div class="fw-bold text-gray-800 text-truncate fs-7">
                                            <i class="fa-solid fa-user me-2 text-muted"></i>
                                            <?= Html::encode($info['nama']) ?>
                                        </div>
                                        <?php if (!empty($info['telp'])): ?>
                                            <div class="text-muted fs-8 mt-1">
                                                <i class="fa fa-phone me-2 text-muted"></i>
                                                <?= Html::encode($info['telp']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($isSalesType): ?>
                <div class="card shadow-sm border-0 mb-5">
                    <div class="card-header bg-secondary border-0 py-4">
                        <h3 class="card-title fw-bold fs-6 text-black mb-0">
                            <i class="fa fa-calendar me-2 text-black"></i>
                            Informasi Event
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="row g-0 border-top border-1">
                            <div class="col-lg px-3 py-4 border-end border-1">
                                <div class="text-uppercase text-muted fw-bold fs-8 letter-spacing mb-2">
                                    Nama Event
                                </div>
                                <div class="fw-semibold text-gray-800">
                                    <?= Html::encode($model->eventname ?? '-') ?>
                                </div>
                            </div>
                            <div class="col-lg px-5 py-4 border-end border-1">
                                <div class="text-uppercase text-muted fw-bold fs-8 letter-spacing mb-2">
                                    Lokasi
                                </div>
                                <div class="fw-semibold text-gray-800">
                                    <?= Html::encode($model->locations ?? '-') ?>
                                </div>
                            </div>
                            <div class="col-lg-auto px-5 py-4 border-end border-1">
                                <div class="text-uppercase text-muted fw-bold fs-8 letter-spacing mb-2">
                                    Tipe Event
                                </div>
                                <div class="fw-semibold text-gray-800">
                                    <?= $eventTypeNames[$model->eventtype] ?? '-' ?>
                                </div>
                            </div>
                            <div class="col-lg-auto px-5 py-4">
                                <div class="text-uppercase text-muted fw-bold fs-8 letter-spacing mb-2">
                                    Link Maps
                                </div>
                                <div class="fw-semibold">
                                    <?php if ($model->linkmap): ?>
                                        <a href="<?= Html::encode($model->linkmap) ?>" target="_blank"
                                            class="text-primary text-decoration-none d-inline-flex align-items-center gap-1">
                                            <i class="fa-solid fa-map-location-dot"></i>
                                            Buka Maps
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- PIC Card (Sales Order only) -->
            <?php if ($isSalesOrder): ?>
                <div class="card shadow-sm border-0 mb-5">
                    <div class="card-header bg-secondary border-0 py-4">
                        <h3 class="card-title fw-bold fs-6 text-black mb-0">
                            <i class="fa fa-user me-2 text-black"></i>
                            PIC Event
                        </h3>
                    </div>
                    <div class="card-body p-5">
                        <div class="row g-3">
                            <?php
                            $picList = [
                                'AE' => $model->aeid ?? null,
                                'Manager OP' => $model->operatorid ?? null,
                                'Warehouse Manager' => $model->storemanid ?? null,
                                'PIC 1' => $model->pic1id ?? null,
                                'PIC 2' => $model->pic2id ?? null,
                            ];
                            foreach ($picList as $role => $contactId): ?>
                                <div class="col-md-4 col-lg-3">
                                    <div class="bg-light rounded-2 p-3">
                                        <div class="text-uppercase text-muted fw-bold fs-9 letter-spacing mb-2">
                                            <?= Html::encode($role) ?>
                                        </div>
                                        <div class="fw-semibold text-gray-800 text-truncate">
                                            <?= $contactId
                                                ? Html::encode(Yii::$app->function->findByField("contact_name", "contacts", " and contact_id ='{$contactId}' "))
                                                : '–' ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0 mb-5">
                <div class="card-body p-0">
                    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x px-5 py-4 border-bottom border-1"
                        role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active py-3 fw-bold" data-bs-toggle="tab" href="#tab_products"
                                role="tab">
                                <i class="fa fa-cubes me-2"></i>Products
                            </a>
                        </li>
                        <?php if ($isSalesOrder): ?>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link py-3 fw-bold" data-bs-toggle="tab" href="#tab_crew" role="tab">
                                    <i class="fa fa-users me-2"></i>Crew
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link py-3 fw-bold" data-bs-toggle="tab" href="#tab_po" role="tab">
                                    <i class="fa fa-file-invoice me-2"></i>PO
                                    <?php if (!empty($modelpos)): ?>
                                        <span class="badge badge-light-primary ms-2">
                                            <?= count($modelpos) ?>
                                        </span>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content px-5 py-6">
                        <div class="tab-pane fade show active" id="tab_products" role="tabpanel">
                            <div class="row g-1 rounded-2 border border-1 mb-5 overflow-hidden">
                                <div class="col-auto flex-grow-1">
                                    <div class="bg-light-gray py-3 px-4 h-100">
                                        <div class="text-uppercase text-muted fw-bold fs-9 letter-spacing mb-2">
                                            Sub Total
                                        </div>
                                        <div class="fw-bold fs-6 text-gray-900">
                                            <?= Yii::$app->formatter->asDecimal($model->subtotal ?? 0, 2) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto flex-grow-1">
                                    <div class="bg-light-gray py-3 px-4 h-100">
                                        <div class="text-uppercase text-muted fw-bold fs-9 letter-spacing mb-2">
                                            Discount
                                        </div>
                                        <div class="fw-bold fs-6 text-danger">
                                            (<?= Yii::$app->formatter->asDecimal($model->disc ?? 0, 2) ?>)
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto flex-grow-1">
                                    <div class="bg-light-gray py-3 px-4 h-100">
                                        <div class="text-uppercase text-muted fw-bold fs-9 letter-spacing mb-2">
                                            PPN
                                        </div>
                                        <div class="fw-bold fs-6 text-gray-900">
                                            <?= Yii::$app->formatter->asDecimal($model->ppnamount ?? 0, 2) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto flex-grow-1">
                                    <div class="bg-light-gray py-3 px-4 h-100">
                                        <div class="text-uppercase text-muted fw-bold fs-9 letter-spacing mb-2">
                                            PPH
                                        </div>
                                        <div class="fw-bold fs-6 text-danger">
                                            (<?= Yii::$app->formatter->asDecimal($model->pphamount ?? 0, 2) ?>)
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto flex-grow-1">
                                    <div class="bg-light-purple py-3 px-4 h-100">
                                        <div class="text-uppercase text-muted fw-bold fs-9 letter-spacing mb-2">
                                            Grand Total
                                        </div>
                                        <div class="fw-bold fs-6 text-primary">
                                            <?= Yii::$app->formatter->asDecimal($model->grandtotal ?? 0, 2) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Products Table -->
                            <div class="table-responsive">
                                <table class="table table-sm align-middle border border-1 rounded">
                                    <thead class="bg-light-gray fw-bold">
                                        <tr>
                                            <th style="width:30%" class="ps-3">
                                                <?= Yii::$app->lang->t('extrasidebar', 'extrasidebar2') ?>
                                            </th>
                                            <th class="text-center" style="width:60px">
                                                <?= Yii::$app->lang->t('produk', 'detail_qty') ?>
                                            </th>
                                            <?php if ($isSalesType): ?>
                                                <th class="text-center" style="width:90px">Period (Day)</th>
                                            <?php endif; ?>
                                            <th class="text-end" style="width:90px">
                                                <?= Yii::$app->lang->t('front_home', 'price') ?>
                                            </th>
                                            <th class="text-end" style="width:90px">Sub Total</th>
                                            <th class="text-center" style="width:70px">
                                                <?= Yii::$app->lang->t('extra', 'extra5') ?>
                                            </th>
                                            <th class="text-center" style="width:80px">
                                                <?= Yii::$app->lang->t('extra', 'extra70') ?>
                                            </th>
                                            <th class="text-end pe-3" style="width:100px">
                                                <?= Yii::$app->lang->t('extra', 'extra71') ?>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-top border-1">
                                        <?php if (!empty($modeldetails)): ?>
                                            <?php foreach ($modeldetails as $detail): ?>
                                                <tr class="border-bottom border-1">
                                                    <td class="ps-3">
                                                        <div class="fw-semibold text-gray-800">
                                                            <?= Html::encode($detail->product->productname ?? ($detail->productname ?? '-')) ?>
                                                        </div>
                                                        <?php if ($detail->description): ?>
                                                            <div class="text-muted fs-8 mt-2 lh-sm">
                                                                <?= nl2br(Html::encode($detail->description)) ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center fw-semibold">
                                                        <?= $detail->amount ?? 0 ?>
                                                    </td>
                                                    <?php if ($isSalesType): ?>
                                                        <td class="text-center text-gray-800">
                                                            <?= $detail->freqvalue ?? 0 ?>
                                                        </td>
                                                    <?php endif; ?>
                                                    <td class="text-end text-nowrap text-gray-800">
                                                        <?= Yii::$app->formatter->asDecimal($detail->price ?? 0, 2) ?>
                                                    </td>
                                                    <td class="text-end text-nowrap fw-semibold text-gray-800">
                                                        <?= Yii::$app->formatter->asDecimal($detail->itemsubtotaltax ?? 0, 2) ?>
                                                    </td>
                                                    <td class="text-center text-gray-800">
                                                        <?= $detail->itemdiscpersen ?? 0 ?>%
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($detail->itemtaxid): ?>
                                                            <span class="badge badge-light-primary fs-9 py-1 px-2">
                                                                <?= Html::encode($detail->itemtaxid) ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-end fw-bold text-gray-900 pe-3 text-nowrap">
                                                        <?= Yii::$app->formatter->asDecimal($detail->itemtotaltax ?? 0, 2) ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="<?= $isSalesType ? 8 : 7 ?>"
                                                    class="text-center text-muted py-8">
                                                    <i class="fa fa-cube fa-2x text-gray-300 mb-3 d-block"></i>
                                                    <span class="fw-semibold fs-7">Belum ada produk</span>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot class="bg-light-gray fw-bold border-top border-2">
                                        <tr>
                                            <td colspan="<?= $isSalesType ? 7 : 6 ?>" class="text-end ps-3">Sub Total
                                            </td>
                                            <td class="text-end pe-3 text-nowrap">
                                                <?= Yii::$app->formatter->asDecimal($model->subtotal ?? 0, 2) ?>
                                            </td>
                                        </tr>
                                        <tr class="border-bottom border-1">
                                            <td colspan="<?= $isSalesType ? 7 : 6 ?>" class="text-end ps-3">Discount
                                            </td>
                                            <td class="text-end text-danger pe-3 text-nowrap">
                                                (<?= Yii::$app->formatter->asDecimal($model->disc ?? 0, 2) ?>)
                                            </td>
                                        </tr>
                                        <tr class="border-bottom border-1">
                                            <td colspan="<?= $isSalesType ? 7 : 6 ?>" class="text-end ps-3">PPN (+)</td>
                                            <td class="text-end pe-3 text-nowrap">
                                                <?= Yii::$app->formatter->asDecimal($model->ppnamount ?? 0, 2) ?>
                                            </td>
                                        </tr>
                                        <tr class="border-bottom border-1">
                                            <td colspan="<?= $isSalesType ? 7 : 6 ?>" class="text-end ps-3">PPH (-)</td>
                                            <td class="text-end text-danger pe-3 text-nowrap">
                                                (<?= Yii::$app->formatter->asDecimal($model->pphamount ?? 0, 2) ?>)
                                            </td>
                                        </tr>
                                        <tr class="border-top border-2">
                                            <td colspan="<?= $isSalesType ? 7 : 6 ?>" class="text-end ps-3">
                                                <span class="text-gray-800">Grand Total</span>
                                            </td>
                                            <td class="text-end pe-3 text-nowrap">
                                                <span class="fs-5 text-primary">
                                                    <?= Yii::$app->formatter->asDecimal($model->grandtotal ?? 0, 2) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <?php if ($isSalesOrder): ?>

                            <div class="tab-pane fade" id="tab_crew" role="tabpanel">
                                <?php if (!empty($modelevents)): ?>
                                    <?php foreach ($modelevents as $item):
                                        $event = $item['event'];
                                        $eventCrews = $item['crews'];
                                        $stageIndex = (int) ($event->eventtypeid ?? 99);
                                        $stageName = $eventStageNames[$stageIndex] ?? 'Unknown';
                                        $stageBadge = $eventStageBadge[$stageIndex] ?? 'secondary';
                                        ?>
                                        <div class="mb-6">
                                            <div class="d-flex align-items-center gap-3 mb-4">
                                                <span class="badge badge-light-<?= $stageBadge ?> py-2 px-3 fs-8 fw-bold">
                                                    <?= Html::encode($stageName) ?>
                                                </span>
                                                <span class="text-gray-600 fs-7 fw-semibold">
                                                    <i class="fa fa-clock me-2 text-muted"></i>
                                                    <?= Yii::$app->formatter->asDatetime($event->startdate) ?>
                                                    &nbsp;–&nbsp;
                                                    <?= Yii::$app->formatter->asDatetime($event->enddate) ?>
                                                </span>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-sm align-middle border border-1 rounded">
                                                    <thead class="bg-light-gray fw-bold">
                                                        <tr>
                                                            <th style="width:90px" class="ps-3">Posisi</th>
                                                            <th>Crew</th>
                                                            <th>No. HP</th>
                                                            <th>Job</th>
                                                            <th class="text-end" style="width:80px">Fee</th>
                                                            <th>Dinas</th>
                                                            <th class="text-end pe-3" style="width:100px">Dinas Fee</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="border-top border-1">
                                                        <?php if (!empty($eventCrews)): ?>
                                                            <?php foreach ($eventCrews as $crew):
                                                                $phone = '';
                                                                if ($crew->crewid) {
                                                                    $rawPhone = Yii::$app->function->findByField(
                                                                        "contact_phone1",
                                                                        "contacts",
                                                                        " AND contact_id = '{$crew->crewid}'"
                                                                    );
                                                                    $phone = preg_replace('/[^0-9]/', '', $rawPhone);
                                                                    if (str_starts_with($phone, '0')) {
                                                                        $phone = '62' . substr($phone, 1);
                                                                    }
                                                                }
                                                                $crewLabel = $crewTypeNames[$crew->crewtypeid] ?? '-';
                                                                ?>
                                                                <tr class="border-bottom border-1">
                                                                    <td class="ps-3">
                                                                        <span class="badge badge-light-info fs-9 py-1 px-2">
                                                                            <?= Html::encode($crewLabel) ?>
                                                                        </span>
                                                                    </td>
                                                                    <td class="fw-semibold text-gray-800">
                                                                        <?= $crew->crewid
                                                                            ? Html::encode(Yii::$app->function->findByField("contact_name", "contacts", " and contact_id ='{$crew->crewid}' "))
                                                                            : '-' ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php if (!empty($phone)): ?>
                                                                            <a data-phone="<?= Html::encode($phone) ?>"
                                                                                class="d-inline-flex align-items-center gap-1 text-success fw-semibold fs-7 text-decoration-none"
                                                                                style="cursor:pointer">
                                                                                <i class="fa-brands fa-whatsapp"></i>
                                                                                <?= Html::encode($phone) ?>
                                                                            </a>
                                                                        <?php else: ?>
                                                                            <span class="text-muted">-</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td class="text-gray-800">
                                                                        <?= $crew->jobid
                                                                            ? Html::encode(Yii::$app->function->findByField("enumtext_id", "enum", "and enumid ='" . $crew->jobid . "'"))
                                                                            : '-' ?>
                                                                    </td>
                                                                    <td class="text-end text-nowrap text-gray-800">
                                                                        <?= $crew->fee ? Yii::$app->formatter->asDecimal($crew->fee, 0) : '-' ?>
                                                                    </td>
                                                                    <td class="text-gray-800">
                                                                        <?= $crew->dinasid
                                                                            ? Html::encode(Yii::$app->function->findByField("enumtext_id", "enum", "and enumid ='" . $crew->dinasid . "'"))
                                                                            : '-' ?>
                                                                    </td>
                                                                    <td class="text-end text-nowrap pe-3 text-gray-800">
                                                                        <?= $crew->dinasfee ? Yii::$app->formatter->asDecimal($crew->dinasfee, 0) : '-' ?>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <tr>
                                                                <td colspan="7" class="text-center text-muted py-8">
                                                                    <i class="fa fa-user-slash fa-2x text-gray-300 mb-3 d-block"></i>
                                                                    <span class="fs-7">Belum ada crew untuk sesi ini</span>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center text-muted py-12">
                                        <i class="fa fa-users fa-3x text-gray-300 mb-4 d-block"></i>
                                        <p class="fw-semibold fs-6 mb-1">Belum ada jadwal crew</p>
                                        <p class="fs-7 text-muted">Crew akan ditampilkan setelah dijadwalkan</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="tab-pane fade" id="tab_po" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table align-middle border border-1 rounded">
                                        <thead class="bg-light-gray fw-bold">
                                            <tr>
                                                <th style="width:15%">No PO</th>
                                                <th style="width:20%">Vendor</th>
                                                <th style="width:15%">Tanggal</th>
                                                <th style="width:15%" class="text-end">Total</th>
                                                <th style="width:10%" class="text-center">Status</th>
                                                <th style="width:5%" class="text-center">Dibuat</th>
                                            </tr>
                                        </thead>
                                        <tbody class="border-top border-1">
                                            <?php if (!empty($modelpos)): ?>
                                                <?php foreach ($modelpos as $po): ?>
                                                    <tr class="border-bottom border-1">
                                                        <td class="fw-semibold text-primary">
                                                            <a href="<?= Url::to(['tran/createtrack']) ?>?id=<?= $po->tranid ?>"
                                                                class="text-decoration-none">
                                                                <?= Html::encode($po->tranno) ?>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <div class="fw-semibold text-gray-800">
                                                                <?= Html::encode($po->contact_name ?? '-') ?>
                                                            </div>
                                                            <small class="text-muted">
                                                                <?= Html::encode($po->jobcompany ?? '-') ?>
                                                            </small>
                                                        </td>

                                                        <td class="text-gray-800">
                                                            <?= $po->trandate ? Yii::$app->formatter->asDate($po->trandate) : '-' ?>
                                                        </td>
                                                        <td class="text-end fw-semibold text-gray-900">
                                                            <?= Yii::$app->formatter->asDecimal($po->grandtotal ?? 0, 0) ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            $statusMap = [
                                                                0 => ['Draft', 'primary'],
                                                                1 => ['Approved', 'success'],
                                                                5 => ['Cancelled', 'warning'],
                                                                10 => ['Rejected', 'danger']
                                                            ];
                                                            $status = $statusMap[$po->status] ?? ['Unknown', 'secondary'];
                                                            ?>
                                                            <span class="badge badge-light-<?= $status[1] ?> py-1 px-2">
                                                                <?= $status[0] ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center text-muted fs-8">
                                                            <?= Html::encode($po->createdby ?? '-') ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted py-8">
                                                        <i class="fa fa-file-alt fa-2x text-gray-300 mb-3 d-block"></i>
                                                        <span class="fw-semibold fs-7">Belum ada PO</span>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <?php if ($model->note): ?>
                <div class="card shadow-sm border-0 mb-2">
                    <div class="card-header bg-light-primary border-0 py-4">
                        <h3 class="card-title fw-bold fs-6 text-gray-700 mb-0">
                            <i class="fa-solid fa-note-sticky me-2 text-primary"></i>
                            Catatan
                        </h3>
                    </div>
                    <div class="card-body p-5">
                        <div class="alert alert-light-primary border border-1 border-primary rounded-2 p-4 m-0">
                            <div class="text-primary lh-lg" style="font-size: 13px;">
                                <?= nl2br(Html::encode($model->note)) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>