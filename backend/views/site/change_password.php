<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Change Password';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <div>
                        <?= Html::a('<i class="bi bi-arrow-left"></i> Kembali', ['index'], ['class' => 'text-white text-decoration-none']) ?>
                    </div>
                    <h5 class="mb-0">
                        <i class="bi bi-key"></i> Change Password
                    </h5>
                </div>

                <div class="card-body">
                    <?php if (Yii::$app->session->hasFlash('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= Yii::$app->session->getFlash('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (Yii::$app->session->hasFlash('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= Yii::$app->session->getFlash('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php $form = ActiveForm::begin(['id' => 'change-password-form']); ?>

                    <?= $form->field($model, 'oldpassword')->passwordInput([
                        'maxlength' => true,
                        'placeholder' => 'Masukkan password lama',
                        'class' => 'form-control'
                    ])->label('Old Password') ?>

                    <?= $form->field($model, 'passwordnow')->passwordInput([
                        'maxlength' => true,
                        'placeholder' => 'Masukkan password baru (min. 6 karakter)',
                        'class' => 'form-control'
                    ])->label('New Password') ?>

                    <?= $form->field($model, 'password')->passwordInput([
                        'maxlength' => true,
                        'placeholder' => 'Ketik ulang password baru',
                        'class' => 'form-control'
                    ])->label('Confirm Password') ?>

                    <div class="form-group mt-4">
                        <?= Html::submitButton('Change', ['class' => 'btn btn-primary btn-block w-100']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: none;
    border-radius: 8px;
}

.card-header {
    border-top-left-radius: 8px !important;
    border-top-right-radius: 8px !important;
}
</style>