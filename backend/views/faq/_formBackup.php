<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Faq $model */
/** @var yii\widgets\ActiveForm $form */
?>

 <div class="faq-form card shadow-sm">
    <div class="card-body">
        <?php $form = ActiveForm::begin([
            'id' => 'faq-form',
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'validationUrl' => ['validate'], // pastikan kamu buat actionValidate()
            'options' => ['class' => 'row g-3', 'data-ajax' => 1],
        ]); ?>

        <!-- Tempat alert client-side -->
        <div id="form-alert" class="alert alert-danger d-none" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        Semua field wajib diisi!
        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'question')->textInput([
                'maxlength' => true,
                'placeholder' => 'Masukkan pertanyaan',
                'class' => 'form-control'
            ])->label('Pertanyaan') ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'answer')->textarea([
                'rows' => 4,
                'placeholder' => 'Masukkan jawaban...',
                'class' => 'form-control'
            ])->label('Jawaban') ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'faqtype')->dropDownList([
                'pengguna' => 'Pengguna',
                'vendor' => 'Vendor',
                'mitra' => 'Mitra',
            ], [
                'prompt' => 'Pilih Kategori',
                'class' => 'form-select'
            ])->label('Kategori FAQ') ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'view_count')->input('number', [
                'placeholder' => 'Jumlah view',
                'class' => 'form-control'
            ])->label('Jumlah Dilihat') ?>
        </div>

        <div class="col-md-12 text-end mt-3">
            <?= Html::submitButton(
                $model->isNewRecord ? ' Simpan' : '✏️ Perbarui',
                ['class' => 'btn btn-primary px-4']
            ) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
 </div>

    <?php
    $js = <<<JS
    $('#faq-form').on('submit', function(e) {
        // Ambil nilai field
        const question = $('#faq-question').val().trim();
        const answer = $('#faq-answer').val().trim();
        const type = $('#faq-type').val();

        // Jika ada yang kosong, tampilkan alert dan hentikan submit
        if (!question || !answer || !type) {
        $('#form-alert').removeClass('d-none'); // Tampilkan alert
        e.preventDefault(); // Hentikan submit form
        } else {
        $('#form-alert').addClass('d-none'); // Sembunyikan jika tidak ada masalah
        }
    });
    JS;

    $this->registerJs($js);
    ?>
