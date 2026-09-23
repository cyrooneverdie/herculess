<?php
use yii\helpers\Html;

$this->title = 'Verification success';
?>

<div class="site-email-verified">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Thank you! Your email has been successfully verified.</p>
    <p><?= Html::a('Login Now', ['site/login'], ['class' => 'btn btn-primary']) ?></p>
</div>
