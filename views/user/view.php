<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\web\YiiAsset;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="user-view">
	<?= DetailView::widget([
		'model' => $model,
		'attributes' => [
			'id',
			'name',
			'password',
			'email',
			[
				'label' => 'Is Admin',
				'value' => $model->is_admin ? 'Yes' : 'No',
			],
		],
	]) ?>
</div>

<?php
$this->registerCssFile('@web/css/fix/fixwidth.css', ['depends' => [YiiAsset::class]]);
?>