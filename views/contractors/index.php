<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\YiiAsset;

$this->title = 'Контрагенты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contractors-index">
	<h1><?= Html::encode($this->title) ?></h1>

	<h2>Физические лица</h2>
	<?= GridView::widget([
		'dataProvider' => $individualDataProvider,
		'columns' => [
			'contact_person',
			'phone1',
			'phone2',
			'email',
			['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
		],
	]); ?>
	<?= Html::a('Добавить контр агента', ['create', 'contractor_type' => 'individual'], ['class' => 'btn btn-success']) ?>
	<h2>Юридические лица</h2>

	<?= GridView::widget([
		'dataProvider' => $legalDataProvider,
		'columns' => [
			'company',
			'iin',
			'legal_address',
			'actual_address',
			'contact_person',
			'phone1',
			'phone2',
			'email',
			['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
		],
	]); ?>
	<?= Html::a('Добавить контр агента', ['create', 'contractor_type' => 'legal'], ['class' => 'btn btn-success']) ?>
</div>

<?php
$this->registerCssFile('@web/css/fix/fixwidth.css', ['depends' => [YiiAsset::class]]);
?>