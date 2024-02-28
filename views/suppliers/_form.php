<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Supplier $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="supplier-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'company_name')->textInput(['maxlength' => true])->label("Название компании") ?>

	<?= $form->field($model, 'website')->textInput(['maxlength' => true])->label("Сайт") ?>

	<?= $form->field($model, 'phone')->textInput(['maxlength' => true])->label("Телефон") ?>

	<?= $form->field($model, 'email')->textInput(['maxlength' => true])->label("Почта") ?>

	<?= $form->field($model, 'working_hours')->textInput(['maxlength' => true])->label("Режим работы") ?>

	<?= $form->field($model, 'warehouse_address')->textInput(['maxlength' => true])->label("Адрес склада") ?>

	<?= $form->field($model, 'manager')->textInput(['maxlength' => true])->label("Менеджер") ?>

	<?= $form->field($model, 'b2b_login')->textInput(['maxlength' => true])->label("Б2б логин") ?>

	<?= $form->field($model, 'b2b_password')->textInput(['maxlength' => true])->label("Б2б пароль") ?>

	<?= $form->field($model, 'delivery')->textInput(['maxlength' => true])->label("Доставка") ?>

	<?= $form->field($model, 'return_policy')->textInput(['maxlength' => true])->label("Возврат товара") ?>

	<?= $form->field($model, 'payment_method')->textInput(['maxlength' => true])->label("Форма оплаты") ?>

	<?= $form->field($model, 'vat_handling')->textInput(['maxlength' => true])->label("Работа НДС") ?>

	<div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>