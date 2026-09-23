<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Variant $model
 * @var array $enum
 */

$this->title = 'Variant Detail - ' . ($enum['productname'] ?? 'Unknown Product');
$this->params['breadcrumbs'][] = ['label' => 'Variants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <!-- Header -->
        <div class="card mb-5">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fa-solid fa-box me-2"></i><?= Html::encode($enum['productname'] ?? 'Unknown Product') ?>
                </h3>
                <div class="card-toolbar text-primary">
                    <a href="<?= Url::to(['index']) ?>" class="btn btn-light-primary btn-sm">
                        <i class="fa-solid fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="card">
            <div class="card-body p-10">
                <div class="row g-5 justify-content-center">
                    <div class="col-lg-4">
                        <div class="text-center">
                            <h2 class="fw-bold mb-5"><?= Yii::$app->lang->t('extrasidebar', 'extrasidebar2') ?></h2>
                            <?php if (!empty($images)): ?>
                                <div id="productCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                                    <div class="carousel-indicators">
                                        <?php foreach ($images as $index => $image): ?>
                                            <button type="button" data-bs-target="#productCarousel"
                                                data-bs-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>"
                                                aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                                                aria-label="Slide <?= $index + 1 ?>">
                                            </button>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="carousel-inner bg-light-secondary rounded p-10">
                                        <?php foreach ($images as $index => $image): ?>
                                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                                <img src="<?= !empty($image['documentpath'])
                                                    ? Yii::getAlias('@web') . '/uploads/variant/' . $image['documentpath']
                                                    : Yii::getAlias('@web') . '/assets/media/logos/empty.png' ?>"
                                                    alt="<?= Html::encode($image['documentname'] ?? 'Product Image') ?>"
                                                    class="w-100 h-100 rounded object-fit-contain mh-400px">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <!-- Controls -->
                                    <?php if (count($images) > 1): ?>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel"
                                            data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon bg-dark opacity-75 rounded-circle p-5"
                                                aria-hidden="true"></span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel"
                                            data-bs-slide="next">
                                            <span class="carousel-control-next-icon bg-dark opacity-75 rounded-circle p-5"
                                                aria-hidden="true"></span>
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <?php if (count($images) > 1): ?>
                                    <div class="d-flex gap-2 justify-content-center flex-wrap mb-4">
                                        <?php foreach ($images as $index => $image): ?>
                                            <div class="thumbnail-item cursor-pointer" onclick="goToSlide(<?= $index ?>)">
                                                <img src="<?= Yii::getAlias('@web') . '/uploads/variant/' . $image['documentpath'] ?>"
                                                    alt="Thumbnail <?= $index + 1 ?>"
                                                    class="border border-2 rounded w-80px h-80px object-fit-cover thumbnail-img <?= $index === 0 ? 'border-primary' : 'border-gray-300' ?>"
                                                    data-index="<?= $index ?>">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                            <?php elseif (isset($model['productpict']) && $model['productpict']): ?>
                                <div class="mb-5 bg-light-secondary rounded p-10">
                                    <img src="<?= Yii::getAlias('@web') . '/uploads/variant/' . $model['productpict'] ?>"
                                        alt="Preview Gambar" class="w-100 h-100 rounded object-fit-contain mh-400px">
                                </div>

                            <?php else: ?>
                                <div class="mb-5 bg-light-secondary rounded p-10">
                                    <div class="h-350px d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-image fs-4x text-gray-400"></i>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="row g-5">
                            <div class="col-lg-6">
                                <label class="text-muted fw-semibold fs-7 mb-2">Unit No</label>
                                <div class="bg-light rounded p-3">
                                    <span class="fw-semibold"><?= Html::encode($model->unitno ?? '-') ?></span>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label class="text-muted fw-semibold fs-7 mb-2">Asset No</label>
                                <div class="bg-light rounded p-3">
                                    <span class="fw-semibold"><?= Html::encode($model->asetno ?? '-') ?></span>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label class="text-muted fw-semibold fs-7 mb-2">Serial No</label>
                                <div class="bg-light rounded p-3">
                                    <?= Html::encode($model->serialno ?? '-') ?>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label class="text-muted fw-semibold fs-7 mb-2">Barcode</label>
                                <div class="bg-light rounded p-3">
                                    <?= Html::encode($model->barcode ?? '-') ?>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="text-muted fw-semibold fs-7 mb-2">Series</label>
                                <div class="bg-light rounded p-3">
                                    <?= Html::encode($model->series ?? '-') ?>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label class="text-muted fw-semibold fs-7 mb-2">Condition</label>
                                <div class="bg-light rounded p-3">
                                    <?= Html::encode($enum['condition'] ?? '-') ?>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label class="text-muted fw-semibold fs-7 mb-2">Warehouse</label>
                                <div class="bg-light rounded p-3">
                                    <?= Html::encode($enum['warehouse'] ?? '-') ?>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label class="text-muted fw-semibold fs-7 mb-2">Shelf</label>
                                <div class="bg-light rounded p-3">
                                    <?= Html::encode($enum['shelf'] ?? '-') ?>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label class="text-muted fw-semibold fs-7 mb-2">Purchase Date</label>
                                <div class="bg-light rounded p-3">
                                    <?= $model->purchasedate ? Yii::$app->formatter->asDate($model->purchasedate, 'php:d/m/Y') : '-' ?>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label class="text-muted fw-semibold fs-7 mb-2">Supplier</label>
                                <div class="bg-light rounded p-3">
                                    <?= Html::encode($enum['contact'] ?? '-') ?>
                                </div>
                            </div>
                            <?php if ($model->price): ?>
                                <div class="col-lg-6">
                                    <label class="text-muted fw-semibold fs-7 mb-2">Purchase Price</label>
                                    <div class="bg-light rounded p-3">
                                        <?= Yii::$app->formatter->asCurrency($model->price, 0) ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="col-12">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('produk', 'produk_deskripsi') ?></label>
                                <div class="bg-light rounded p-3 min-h-100px text-pre-wrap">
                                    <?= Html::encode($model['description_variant'] ?? '-') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>