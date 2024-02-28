<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Reset Password';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<p>Please choose your new password:</p>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
<?= $form->field($model, 'confirm_password')->passwordInput(['maxlength' => true]) ?>

<div class="form-group">
	<?= Html::submitButton('Reset Password', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>