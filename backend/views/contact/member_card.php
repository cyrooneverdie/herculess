<?php
use yii\helpers\Html;

$this->title = 'Member Card - ' . $model->contact_name;
?>

<div class="d-flex flex-column flex-center min-vh-100 py-10">

    <div class="bg-dark rounded-4 p-7 shadow-lg text-center mx-auto mb-5" style="width: 340px;">
        <div class="text-start mb-5 ps-2">
            <h2 class="fw-bolder text-white mb-0 fs-2"><?= Html::encode($model->contact_name) ?></h2>
            <span class="text-warning fw-bold fs-7"><?= Html::encode($packageName) ?></span>
        </div>

        <div class="mb-5">
            <span
                class="bg-white bg-opacity-10 border border-white border-opacity-20 text-white fw-bold fs-7 px-5 py-2 rounded-pill">
                <?= Html::encode($model->contact_no) ?>
            </span>
        </div>

        <div class="bg-white p-4 rounded-4 d-inline-block shadow-sm mb-5">
            <img src="<?= $qrUrl ?>" alt="QR Code Member" class="img-fluid rounded-3"
                style="width: 200px; height: 200px;">
        </div>

        <div class="d-flex align-items-center justify-content-center gap-2 mt-2">
            <div class="symbol symbol-30px symbol-circle">
                <img src="<?= Yii::getAlias('@web') . "/assets/media/logos/rbg.png" ?>" alt="Logo" onerror="this.src='https://via.placeholder.com/30'" />
            </div>
            <span class="fw-bolder fs-4 text-white">Herculesfitness</span>
        </div>

    </div>

    <!-- <div class="d-flex gap-3">
        <a href="javascript:history.back()" class="btn btn-light btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
       
    </div> -->

</div>