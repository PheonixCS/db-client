<?php

use app\models\DeliveryType;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Delivery Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-type-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<p>
		<?= Html::a('Create Delivery Type', ['create'], ['class' => 'btn btn-success']) ?>
	</p>


	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],

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
			[

				'class' => 'yii\grid\ActionColumn',
				'template' => '{update} {delete}',
				'urlCreator' => function ($action, DeliveryType $model, $key, $index, $column) {
					return Url::toRoute([$action, 'id' => $model->id]);
				}
			],
		],
	]); ?>


</div>