<?php

use yii\grid\GridView;

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => [
		// Колонки вашей таблицы
		// ...
		// ...
	],
]);
