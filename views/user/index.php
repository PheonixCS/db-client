<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\web\YiiAsset;

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'columns' => [
		'name',
		'password',
		[
			'class' => 'yii\grid\ActionColumn',
			'template' => '{view} {delete}',
		],
	],
]); ?>

<?= LinkPager::widget([
	'pagination' => $dataProvider->pagination,
]); ?>

<?php
$this->registerCssFile('@web/css/fix/fixwidth.css', ['depends' => [YiiAsset::class]]);
?>