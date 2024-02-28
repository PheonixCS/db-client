<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Contractor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contractors-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'contractor_type')->hiddenInput(['value' => 'individual'])->label(false) ?>


	<?= $form->field($model, 'contact_person')->textInput(['maxlength' => true])->label("Контактное лицо") ?>

	<?= $form->field($model, 'phone1')->textInput(['maxlength' => true])->label("Телефон 1") ?>

	<?= $form->field($model, 'phone2')->textInput(['maxlength' => true])->label("Телефон 2") ?>

	<?= $form->field($model, 'email')->textInput(['maxlength' => true])->label("Почта") ?>

	<?php if ($model instanceof \app\models\LegalContractor) : ?>
		<?= $form->field($model, 'company')->textInput(['maxlength' => true])->label("Компания") ?>

		<?= $form->field($model, 'company_consignee')->textInput(['maxlength' => true])->label("Компания-получатель") ?>

		<?= $form->field($model, 'company_address_consignee')->textInput(['maxlength' => true])->label("Адрес компании получателя") ?>

		<?= $form->field($model, 'iin')->textInput(['maxlength' => true])->label("ИИН") ?>

		<?= $form->field($model, 'actual_address')->textInput(['maxlength' => true])->label("Фактический адрес") ?>

		<?= $form->field($model, 'same_address')->checkbox(['label' => 'Совпадает с фактическим'])->label(false) ?>

		<?= $form->field($model, 'legal_address')->textInput(['maxlength' => true, 'readonly' => !$model->same_address])->label("Юр. Адрес") ?>

		<?php
		$script = <<< JS
$(document).ready(function() {
    var legalAddressField = $('#legalcontractor-legal_address');
    var actualAddressField = $('#legalcontractor-actual_address');
    var sameAddressCheckbox = $('#legalcontractor-same_address');

    // Дублирование значения поля Фактический адрес в поле Юр. Адрес
    sameAddressCheckbox.on('change', function() {
        if ($(this).is(':checked')) {
            legalAddressField.val(actualAddressField.val());
            legalAddressField.prop('readonly', true);
        } else {
            legalAddressField.prop('readonly', false);
        }
    });

    // Обновление значения поля Юр. Адрес при изменении поля Фактический адрес
    actualAddressField.on('input', function() {
        if (sameAddressCheckbox.is(':checked')) {
            legalAddressField.val($(this).val());
        }
    });
});
JS;

		$this->registerJs($script);
		?>



		<script>
			// $(document).ready(function() {
			// 	var legalAddressField = $('#legalcontractor-legal_address');
			// 	var actualAddressField = $('#legalcontractor-actual_address');
			// 	var sameAddressCheckbox = $('#legalcontractor-same_address');

			// 	// Дублирование значения поля Фактический адрес в поле Юр. Адрес
			// 	sameAddressCheckbox.on('change', function() {
			// 		if ($(this).is(':checked')) {
			// 			legalAddressField.val(actualAddressField.val());
			// 			legalAddressField.prop('readonly', true);
			// 		} else {
			// 			legalAddressField.prop('readonly', false);
			// 		}
			// 	});
			// });
		</script>
	<?php endif; ?>

	<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

	<?php ActiveForm::end(); ?>

</div>