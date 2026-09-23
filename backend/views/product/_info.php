<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$isDeal = ($type == 'deal');
$themeColor = $isDeal ? 'success' : 'primary';
$themeIcon = $isDeal ? 'ki-check-circle' : 'ki-book-open';
?>

<?php $form = ActiveForm::begin([
    'id' => 'form-product',
    'method' => 'get',
    'action' => Url::to(['product/info']),
    'options' => ['enctype' => 'multipart/form-data', 'data-pjax' => false],
]); ?>

<div id="modal_scrollable_content" class="scroll-y me-n5 pe-5" style="max-height: 60vh;">
    <div class="row">
        <?php if (!empty($history)): ?>
            <div class="d-flex flex-stack">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-40px symbol-circle me-4">
                        <div class="symbol-label bg-light-<?= $themeColor ?>">
                            <i class="ki-duotone <?= $themeIcon ?> fs-2x text-<?= $themeColor ?>">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="text-gray-800 fw-bold fs-6">
                            <?= $isDeal ? 'Confirm / Deal Inventory' : 'Booked / Lead Inventory' ?>
                        </span>
                        <span class="text-muted fw-semibold fs-7">Total: <?= array_sum(array_column($history, 'amount')); ?>
                            units</span>
                    </div>
                </div>
            </div>

            <div class="separator separator-dashed my-5"></div>

            <div class="d-flex flex-column gap-5">
                <?php foreach ($history as $item): ?>
                    <div class="d-flex align-items-center mb-3 mx-3">
                        <div class="symbol symbol-circle symbol-20px me-4">
                            <i class="fa fa-genderless text-<?= $themeColor ?> fs-1"></i>
                        </div>

                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 fw-bold fs-6 mb-1">
                                        <?= $item['tranno'] ?? 'TRX-UNKNOWN' ?>
                                    </span>
                                    <div class="d-flex flex-row gap-2">
                                        <span class="badge badge-light-secondary text-dark fw-bold border border-secondary">
                                            <i
                                                class="fa-solid fa-user me-2 text-dark fw-bold"></i><?= $item['contact_name'] ?? 'N/A' ?>
                                        </span>
                                        <span class="badge badge-light-secondary text-dark fw-bold border border-secondary">
                                            <i
                                                class="fa-solid fa-building me-2 text-dark fw-bold"></i><?= $item['jobcompany'] ?? 'N/A' ?>
                                        </span>
                                    </div>
                                </div>

                                <span class="badge badge-light-<?= $themeColor ?> fw-bold">
                                    +<?= $item['amount'] ?>
                                </span>
                            </div>
                            <div class="text-muted fw-semibold fs-7 mt-2">
                                <i class="fa-solid fa-location-dot me-2"></i>
                                <?= $item['locations'] ?? 'N/A' ?>
                                <span class="mx-2">|</span>
                                <i class="fa-solid fa-calendar-days me-2"></i>
                                <?= date('d M Y', strtotime($item['setupdate'])) ?> -
                                <?= date('d M Y', strtotime($item['withdrawaldate'])) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6 mx-3">
                <i class="ki-duotone ki-information-5 fs-2tx text-warning me-4">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                </i>
                <div class="d-flex flex-stack flex-grow-1">
                    <div class="fw-semibold">
                        <!-- <h4 class="text-gray-900 fw-bold">Inventory Tidak Tersedia</h4> -->
                        <div class="fs-6 text-gray-700">
                            Produk <strong class="text-dark"><?= $model->productname ?></strong>
                            tidak ada <?= $isDeal ? 'pemakaian' : 'booking' ?>
                            pada tanggal <span class="badge badge-secondary"><?= $date ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php ActiveForm::end(); ?>