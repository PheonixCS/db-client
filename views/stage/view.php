<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\web\YiiAsset;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Stage', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="user-view">
	<?= DetailView::widget([
		'model' => $model,
		'attributes' => [
			'id',
			'name',
			'color',
		],
	]) ?>
</div>

<?php
$this->registerCssFile('@web/css/fix/fixwidth.css', ['depends' => [YiiAsset::class]]);
?>