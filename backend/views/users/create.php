<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $model app\models\Contact */

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['userslist']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Render form partial -->
<?= $this->render('_form', [
    'model' => $model,
    'modelContact' => $modelContact,
    'modelmenu' => $modelmenu,
]) ?>

</div>
