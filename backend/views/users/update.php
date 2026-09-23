<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $modelmenu common\models\Usermenu[] */

$this->title = 'Update User: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['userslist']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'      => $model,
        // kalau ada model contact juga dikirim
        'modelContact' => isset($modelContact) ? $modelContact : null,
        'modelmenu'  => $modelmenu,
        'isajax'     => $isajax,
    ]) ?>

</div>
