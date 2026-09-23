<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Product Detail - ' . $model['productname'];
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <!-- Header -->
        <div class="card mb-5">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fa-solid fa-box me-2"></i><?= Html::encode($model['productname']) ?>
                </h3>
                <div class="card-toolbar text-primary">
                    <a href="<?= Url::to(['index']) ?>" class="btn btn-sm btn-light-primary">
                        <i class="fa-solid fa-arrow-left me-2"></i><?= Yii::$app->lang->t('contact', 'back') ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="card">
            <div class="card-body p-10">
                <div class="row g-5">
                    <!-- Gambar Produk -->
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
                                                    ? Yii::getAlias('@web') . '/uploads/produk/' . $image['documentpath']
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
                                                <img src="<?= Yii::getAlias('@web') . '/uploads/produk/' . $image['documentpath'] ?>"
                                                    alt="Thumbnail <?= $index + 1 ?>"
                                                    class="border border-2 rounded w-80px h-80px object-fit-cover thumbnail-img <?= $index === 0 ? 'border-primary' : 'border-gray-300' ?>"
                                                    data-index="<?= $index ?>">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                            <?php elseif (isset($model['productpict']) && $model['productpict']): ?>
                                <div class="mb-5 bg-light-secondary rounded p-10">
                                    <img src="<?= Yii::getAlias('@web') . '/uploads/produk/' . $model['productpict'] ?>"
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

                    <!-- Detail Produk -->
                    <div class="col-lg-8">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('enum', 'code') ?></label>
                                <div class="bg-light rounded p-3">
                                    <span class="fw-semibold"><?= Html::encode($model['productcode'] ?? '-') ?></span>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('produk', 'label2') ?></label>
                                <div class="bg-light rounded p-3">
                                    <span class="fw-semibold"><?= Html::encode($model['productname'] ?? '-') ?></span>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('category', 'label1') ?></label>
                                <div class="bg-light rounded p-3">
                                    <?= Html::encode($model['categoryname'] ?? '-') ?>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('subcategory', 'label1') ?></label>
                                <div class="bg-light rounded p-3">
                                    <?= Html::encode($model['subcategoryname'] ?? '-') ?>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('type', 'label1') ?></label>
                                <div class="bg-light rounded p-3">
                                    <?= Html::encode($model['typename'] ?? '-') ?>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('brand', 'label1') ?></label>
                                <div class="bg-light rounded p-3">
                                    <?= Html::encode($model['brandname'] ?? '-') ?>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('produk', 'produk_display') ?></label>
                                <div class="bg-light rounded p-3">
                                    <?= Html::encode($model['specname'] ?? '-') ?>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('unit', 'label1') ?></label>
                                <div class="bg-light rounded p-3">
                                    <?= Html::encode($model['unitname'] ?? '-') ?>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label class="text-muted fw-semibold fs-7 mb-2">
                                    <?= $viewType === 'index' ? Yii::$app->lang->t('varianharga', 'purchaseprice') : Yii::$app->lang->t('front_home', 'price') ?>
                                </label>
                                <div class="bg-light rounded p-3">
                                    <?= $model['purchaseprice'] !== null
                                        ? Yii::$app->formatter->asCurrency($model['purchaseprice'])
                                        : '-' ?>
                                </div>
                            </div>
                            <?php if ($viewType === 'index'): ?>
                                <div class="col-lg-6">
                                    <label
                                        class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('varianharga', 'sellprice') ?></label>
                                    <div class="bg-light rounded p-3">
                                        <?= $model['sellprice'] !== null
                                            ? Yii::$app->formatter->asCurrency($model['sellprice'])
                                            : '-' ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="col-lg-12">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('series', 'label1') ?></label>
                                <div class="bg-light rounded p-3">
                                    <?= Html::encode($model['series'] ?? '-') ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <label
                                    class="text-muted fw-semibold fs-7 mb-2"><?= Yii::$app->lang->t('produk', 'produk_deskripsi') ?></label>
                                <div class="bg-light rounded p-3 min-h-100px text-pre-wrap">
                                    <?= Html::encode($model['description_product'] ?? '-') ?>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>

<script>
    // Function untuk navigasi ke slide tertentu
    function goToSlide(index) {
        const carousel = new bootstrap.Carousel(document.getElementById('productCarousel'));
        carousel.to(index);
    }

    // Update border thumbnail saat carousel berubah
    const carouselElement = document.getElementById('productCarousel');
    if (carouselElement) {
        carouselElement.addEventListener('slide.bs.carousel', function (e) {
            // Remove active border dari semua thumbnail
            document.querySelectorAll('.thumbnail-img').forEach(img => {
                img.classList.remove('border-primary');
                img.classList.add('border-gray-300');
            });

            // Add active border ke thumbnail yang aktif
            const activeIndex = e.to;
            const activeThumbnail = document.querySelector(`.thumbnail-img[data-index="${activeIndex}"]`);
            if (activeThumbnail) {
                activeThumbnail.classList.remove('border-gray-300');
                activeThumbnail.classList.add('border-primary');
            }
        });
    }
</script>