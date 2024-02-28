<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Create SMTP Settings';
$this->params['breadcrumbs'][] = ['label' => 'SMTP Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'host')->textInput() ?>

<?= $form->field($model, 'username')->textInput() ?>

<?= $form->field($model, 'password')->passwordInput() ?>

<?= $form->field($model, 'port')->textInput() ?>

<?= $form->field($model, 'encryption')->textInput() ?>

<div class="form-group">
	<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>