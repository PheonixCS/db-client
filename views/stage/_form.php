<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\color\ColorInput;
/* @var $this yii\web\View */
/* @var $model app\models\Stage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stage-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'color')->widget(ColorInput::class, [
		'options' => ['placeholder' => 'Select color ...'],
		'useNative' => true,
		'name' => 'color_11'
	]); ?>
	<div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>