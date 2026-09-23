<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Lupa Password';
?>

<div class="site-forgot-password">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Masukkan email Anda untuk menerima link reset password.</p>

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Kirim Email', ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
