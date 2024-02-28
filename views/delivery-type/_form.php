<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\color\ColorInput;

/** @var yii\web\View $this */
/** @var app\models\DeliveryType $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="delivery-type-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'color')->widget(ColorInput::class, [
		'options' => ['placeholder' => 'Select color ...'],
		'useNative' => true,
		'name' => 'color_11'
	]); ?>

	<div class="form-group">
		<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>