<?php

use yii\helpers\Html;
use yii\helpers\Url;

$userId = Yii::$app->user->id;

$sql = "SELECT COUNT(*) FROM company WHERE userid = '$userId'";
$userCompanys = Yii::$app->db->createCommand($sql)->queryScalar() >= 5;
?>

<!-- ?php var_dump($_SESSION);exit; ?> -->
<?php if ($userCompanys): ?>
	<div class="dropdown d-flex align-items-stretch me-5 mt-3 ms-auto" id="companyDrop">
		<a href="<?= Url::to(['company/index']) ?>" class="btn btn-flex btn-link" id="dropdown-company"
			aria-expanded="false">
			<span class="me-2">
				<?= Yii::$app->lang->t('extra', 'extra22') ?>
			</span>
		</a>
	</div>
<?php else: ?>
	<div class="dropdown d-flex align-items-stretch me-5 mt-3 ms-auto" id="companyDrop">
		<button class="btn btn-flex btn-link dropdown-toggle" type="button" id="dropdown-company" data-bs-toggle="dropdown"
			aria-haspopup="true" aria-expanded="false">
			<span class="me-2">
				<?php if ($model != NULL) {
					echo $model['nama_perusahaan'];
				} else {
					echo Yii::$app->lang->t('extra', 'extra24');
				} ?>
			</span>
		</button>
		<ul class="dropdown-menu dropdown-menu-end animate slideIn pe-5" aria-labelledby="dropdown-company">
			<?php if (!empty($data)): ?>
				<?php foreach ($data as $company): ?>
					<li>
						<a href="<?= Yii::$app->urlManager->createUrl(['company/index', 'companyid' => $company['companyid']]) ?>"
							class="dropdown-item d-flex px-5">
							<?= Html::encode($company['nama_perusahaan']) ?>
						</a>

						<?php if (false): ?>
							<a href="<?= Yii::$app->urlManager->createUrl(['site/index', 'companyid' => $company['companyid']]) ?>"
								class="dropdown-item d-flex px-5">
								<?= Html::encode($company['nama_perusahaan']) ?>
							</a>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			<?php else: ?>
				<li class="dropdown-item px-5 text-muted"><?= Yii::$app->lang->t('extra', 'extra25') ?></li>
			<?php endif; ?>
			<li>
				<hr class="dropdown-divider">
			</li>
			<li>
				<a href="<?= Yii::$app->urlManager->createUrl(['company/index']) ?>"
					class="dropdown-item d-flex text-primary">
					<i class="bi bi-plus-lg me-2"></i> <?= Yii::$app->lang->t('extra', 'extra22') ?>
				</a>
			</li>
		</ul>
	</div>
<?php endif ?>