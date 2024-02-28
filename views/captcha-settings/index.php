<?php

use app\models\CaptchaSettings;
use yii\helpers\Html;

$model = CaptchaSettings::findOne(1);
$this->title = 'Параметры капчи';
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h1><?= Html::encode($this->title) ?></h1>
	</div>
	<div class="panel-body">
		<p><strong>Site Key:</strong> <?= Html::encode($model->site_key) ?></p>
		<p><strong>Secret Key:</strong> <?= Html::encode($model->secret_key) ?></p>
		<p><?= Html::a('Редактировать', ['update'], ['class' => 'btn btn-primary']) ?></p>
	</div>
</div>