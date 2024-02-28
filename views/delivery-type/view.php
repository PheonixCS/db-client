<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\DeliveryType $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Delivery Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="delivery-type-view">

	<h1><?= Html::encode($this->title) ?></h1>

	<p>
		<?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a('Delete', ['delete', 'id' => $model->id], [
			'class' => 'btn btn-danger',
			'data' => [
				'confirm' => 'Are you sure you want to delete this item?',
				'method' => 'post',
			],
		]) ?>
	</p>

	<?= DetailView::widget([
		'model' => $model,
		'attributes' => [
			'id',
			'name',
			[
				'attribute' => 'color',
				'headerOptions' => ['class' => 'text-center'],
				'contentOptions' => ['class' => 'text-center'],
				'content' => function ($data) {
					return Html::tag('div', '', [
						'style' => [
							'width' => '20px',
							'height' => '20px',
							'background-color' => $data->color,
						],
					]);
				}
			],
		],
	]) ?>

</div>