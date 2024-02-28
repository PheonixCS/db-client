<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\YiiAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\StageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Стадии';

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stage-index">
	<h1><?= \yii\helpers\Html::encode($this->title) ?></h1>

	<p>
		<?= Html::a('Добавить стадию', ['create'], ['class' => 'btn btn-success']) ?>
	</p>

	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],

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
			],
		],
	]); ?>

</div>

<?php
$this->registerCssFile('@web/css/fix/fixwidth.css', ['depends' => [YiiAsset::class]]);
?>