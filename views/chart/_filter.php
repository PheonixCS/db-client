<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
	'method' => 'get',
]);

echo $form->field($model, 'startDate')->textInput(['type' => 'date'])->label('Начальная дата');
echo $form->field($model, 'endDate')->textInput(['type' => 'date'])->label('Конечная дата');
echo Html::submitButton('Применить', ['class' => 'btn btn-primary']);

ActiveForm::end();

echo 'Суммарная прибыль: ' . $totalProfit;
