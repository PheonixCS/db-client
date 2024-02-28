<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Forgot Password';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<p>Please enter your email address. We will send you instructions on how to reset your password.</p>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

<div class="form-group">
	<?= Html::submitButton('Reset Password', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>