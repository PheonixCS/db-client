<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\YiiAsset;

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="user-create">
	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'name')->textInput() ?>
	<?= $form->field($model, 'password')->passwordInput() ?>
	<?= $form->field($model, 'email')->textInput() ?>
	<?= $form->field($model, 'is_admin')->checkbox(['uncheck' => 0]) ?>

	<div class="form-group">
		<?= Html::submitButton('Create', ['class' => 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>
</div>

<?php
$this->registerCssFile('@web/css/fix/fixwidth.css', ['depends' => [YiiAsset::class]]);
?>