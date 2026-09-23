<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<!-- Transaction Info Section -->
<div class="card mb-7 shadow-sm">
    <div class="card-header bg-light">
        <h3 class="card-title"><i class="fas fa-receipt me-2"></i>Informasi Transaksi</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                <label class="fw-semibold text-muted">Nomor Transaksi</label>
                <div class="fw-bold fs-6 d-flex align-items-center">
                    <span class="badge bg-primary text-white me-2">#</span>
                    <?= $model->tranno ?>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                <label class="fw-semibold text-muted">Vendor</label>
                <div class="fw-bold fs-6 d-flex align-items-center">
                    <i class="fas fa-user-tie me-2 text-primary"></i>
                    <?= !empty($details[0]['contact_name']) ? $details[0]['contact_name'] : 'Tidak ada' ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                <label class="fw-semibold text-muted">Tanggal Transaksi</label>
                <div class="fw-bold fs-6 d-flex align-items-center">
                    <i class="fas fa-calendar-alt me-2 text-success"></i>
                    <?= Yii::$app->formatter->asDate($model->trandate, 'php:d-m-Y') ?>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                <label class="fw-semibold text-muted">Tanggal Jatuh Tempo</label>
                <div class="fw-bold fs-6 d-flex align-items-center">
                    <i class="fas fa-clock me-2 text-warning"></i>
                    <?= $model->tranduedate ? Yii::$app->formatter->asDate($model->tranduedate, 'php:d-m-Y') : 'Tidak ada' ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Items Section -->
<div class="card mb-7 shadow-sm">
    <div class="card-header bg-light">
        <h3 class="card-title"><i class="fas fa-box-open me-2"></i>Detail Produk</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start bg-gray-100 fs-6 text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-250px">Produk</th>
                        <th class="min-w-100px text-center">Jumlah</th>
                        <th class="min-w-100px text-end">Harga Per Item</th>
                        <th class="min-w-100px text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($details as $detail) : ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <!-- Product Image with Default Fallback -->
                                    <div class="symbol symbol-50px me-3">
                                        <?php if (!empty($detail['produkfoto'])): ?>
                                            <img src="<?= Yii::getAlias('@web/uploads/products/') . $detail['produkfoto'] ?>" alt="<?= Html::encode($detail['deskripsi']) ?>" class="img-fluid rounded">
                                        <?php else: ?>
                                            <div class="symbol-label bg-light-primary">
                                                <i class="fas fa-box fs-2x text-primary"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="d-flex flex-column">
                                        <strong class="text-dark fs-6"><?= Html::encode($detail['deskripsi']) ?></strong>
                                        <div class="mt-1">
                                            <span class="badge bg-light text-dark me-2">
                                                <i class="fas fa-barcode me-1"></i> <?= Html::encode($detail['sku']) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light fs-6 px-3 py-2"><?= $detail['jumlah'] ?></span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex flex-column align-items-end">
                                    <span class="badge bg-light-primary text-primary fs-6 px-3 py-2">
                                        Rp <?= Yii::$app->formatter->asDecimal($detail['harga'], 0) ?>
                                    </span>
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="d-flex flex-column align-items-end">
                                    <span class="badge bg-light-success text-success fs-6 px-3 py-2">
                                        Rp <?= Yii::$app->formatter->asDecimal($detail['itemsubtotal'], 0) ?>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="border-top border-gray-200">
                        <td colspan="3" class="text-end fw-bold fs-6">Total:</td>
                        <td class="text-end">
                            <span class="badge bg-success text-white fw-bolder fs-5 px-4 py-2">
                                Rp <?= Yii::$app->formatter->asDecimal($total, 0) ?>
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
