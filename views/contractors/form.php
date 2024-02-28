<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\YiiAsset;

$form = ActiveForm::begin(); ?>
<?= $form->field($model, 'contact_person')->textInput()->label("Контактное лицо") ?>

<?= $form->field($model, 'phone1')->textInput()->label("Телефон 1") ?>

<?= $form->field($model, 'phone2')->textInput()->label("Телефон 2")  ?>

<?= $form->field($model, 'email')->textInput()->label("Почта") ?>

<?= $form->field($model, 'type')->hiddenInput(['value' => 'individual'])->label(false) ?>

<div class="form-group">
	<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

<?php
$this->registerCssFile('@web/css/fix/fixwidth.css', ['depends' => [YiiAsset::class]]);
?>