<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\web\YiiAsset;
use kartik\datetime\DateTimePicker;
use kartik\icons\FontAwesomeAsset;
use kartik\select2\Select2;

$this->title = 'Создать счет';

$this->registerCssFile('https://use.fontawesome.com/releases/v5.3.1/css/all.css');
$this->registerJsFile('https://use.fontawesome.com/releases/v5.3.1/js/all.js', ['defer' => true, 'crossorigin' => 'anonymous']);
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

<h1 class="text-center"><?= Html::encode($this->title) ?></h1>

<?php
$maxNumber = $model::find()->max('numberCheck') ?? 0; // Получение максимального значения из таблицы
$defaultValue = $maxNumber + 1;
if ($defaultValue < 1000) {
	$defaultValue = 1000;
} ?>

<?php $form = ActiveForm::begin(); ?>
<?= $form->field($model, 'numberCheck')
	->textInput(['value' => $defaultValue, 'readonly' => true, 'style' => 'pointer-events: none;']); ?>
<?= $form->field($model, 'payment')
	->radioList([
		'Безналичный' => 'Безналичный',
		'Наличный' => 'Наличный',
		'Пластиковые карты' => 'Пластиковые карты',
		'Яндекс деньги' => 'Яндекс деньги'
	], [
		'class' => 'payment-radio-container', 'value' => '1',
		'unselect' => null
	])->label("Тип оплаты:"); ?>
<div class="form-group products">
	<div class="product-template" style="display:none;">
		<table class="table table-product" style="width: 80%;">
			<thead>
				<tr class="product-header1.1" style="display:none;">
					<th>Код</th>
					<th>Название</th>
				</tr>
			</thead>
			<tbody>
				<tr class="product-tr1.1" style="display:none;">
					<th style="width: 20%;"><?= $form->field($modelProduct, 'code[0]')->textinput(['rows' => 1, 'cols' => 1, 'disabled' => true])->label(""); ?></th>
					<th><?= $form->field($modelProduct, 'name[0]')->textarea(['style' => 'height: 182px;', 'disabled' => true])->label(""); ?></th>
				</tr>
			</tbody>
		</table>
		<table class="table table-product">
			<thead>
				<tr class="product-header1.2" style="display:none;">
					<th>Цена</th>
					<th>Колличество</th>
					<th>Единица измерения</th>
					<th>Сумма</th>
				</tr>
			</thead>
			<tbody>
				<tr class="product-tr1.2" style="display:none;">
					<th><?= $form->field($modelProduct, 'price[0]')
								->textInput(['disabled' => true, 'class' => 'form-control p-price'])
								->label(""); ?></th>
					<th><?= $form->field($modelProduct, 'quantity[0]')
								->textInput(['disabled' => true, 'class' => 'form-control p-quantity'])
								->label(""); ?></th>
					<th><?= $form->field($modelProduct, 'unit_of_measurement[0]')
								->textInput(['disabled' => true, 'value' => 'шт'])
								->label(""); ?></th>
					<th><?= $form->field($modelProduct, 'amount[0]')
								->textInput(['readonly' => true, 'class' => 'form-control p-amount', 'style' => 'pointer-events: none;', 'disabled' => true])
								->label(""); ?></th>
				</tr>
				<tr style="display:none;">
					<th></th>
					<th></th>
					<th></th>
					<th>
						<div class="col-1 product-delete btn btn-danger btn-sm" style="width: 50px;">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
								<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
								<path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
							</svg>
						</div>
					</th>
				</tr>
			</tbody>
		</table>
	</div>
	<h3 class="">Товары</h3>
	<div class="conteiner-Product row">


	</div>
	<?= Html::buttonInput('Добавить товар', ['class' => 'btn btn-dark product-add']); ?>
</div>
<?= $form->field($model, 'deliveryAddress')
	->textarea(['style' => 'height: 100px;']); ?>
<?= $form->field($model, 'costDelivery')
	->textInput(); ?>
<?= $form->field($model, 'dateDelivery')->widget(DateTimePicker::class, [
	'options' => ['class' => 'form-control'],
	'attribute' => 'dateDelivery',
	'pluginOptions' => [
		'format' => 'yyyy-mm-dd hh:ii',
		'todayHighlight' => true,
		'autoclose' => true,
	],
	//'value' => $model->dateDelivery ? date('Y-m-d H:i', $model->dateDelivery) : '',
])->label("Дата доставки") ?>
<?=
$form->field($model, 'delivery_type')->dropDownList(
	\yii\helpers\ArrayHelper::map(\app\models\DeliveryType::find()->all(), 'id', 'name'),
	['prompt' => 'Выберите тип доставки']
);
//$form->field($model, 'delivery_type')
//->textInput(); 
?>

<div class="col-3">
	<?php
	foreach ($dataContractors as $contractor) {
		if ($contractor->type == "legal") {
			$contractorList[$contractor->id] = $contractor->company;
		} else {
			$contractorList[$contractor->id] = $contractor->contact_person;
		}
	}
	//echo $form->field($model, 'organization')->dropDownList($contractorList, ['class' => 'form-control contractorSelect'])->label("Контрагент");
	if (!isset($contractorList)){
        $contractorList = [];
    }
    echo $form->field($model, 'organization')->widget(Select2::className(), [ 
        'data' => $contractorList, 
        'options' => ['placeholder' => 'Выберите контрагента ...', 'class' => 'form-control contractorSelect'], 
        'pluginOptions' => [ 
            'allowClear' => true, 
            'minimumInputLength' => 0, 
            'tags' => true, 
        ],
        'value' => !empty($contractorList) ? array_key_first($contractorList) : null,
    ])->label('Контрагент'); 

    ?>
</div>
<?= Html::buttonInput('Новый контрагент', ['class' => 'btn btn-dark', 'id' => 'contractor-add']); ?>
<br>
<br>
<?= Html::buttonInput('Отмена', ['class' => 'btn btn-danger contractor-dell', 'style' => 'display:none;']); ?>
<br>
<div class="contractorForm" style="display:none">
	<strong>
		<p>Тип контрагента
			<br>
			<input class="radioInput-individual radio" type="radio" name="formType[0]" value="individual" checked> Физ. лицо</input>
			<input class="radioInput-legal radio" type="radio" name="formType[0]" value="legal"> Юр. лицо</input>
		</p>
	</strong>
	<div class="individualForm">
		<h4>Данные контрагента</h4>
		<?= $form->field($modelContractor, "contact_person[0]", ['options' => ['class' => 'form-group']])
			->textInput(['disabled' => true])
			->label("Контактное лицо"); ?>

		<?= $form->field($modelContractor, "phone1[0]", ['options' => ['class' => 'form-group']])
			->textInput(['disabled' => true])
			->label("Телефон 1"); ?>

		<?= $form->field($modelContractor, "phone2[0]", ['options' => ['class' => 'form-group']])
			->textInput(['disabled' => true])
			->label("Телефон 2"); ?>

		<?= $form->field($modelContractor, "email[0]", ['options' => ['class' => 'form-group']])
			->textInput(['disabled' => true])
			->label("E-mail"); ?>
	</div>
	<div class="legalForm">
		<h4>Данные контрагента</h4>
		<?= $form->field($modelContractor, "company[0]", ['options' => ['class' => 'form-group']])
			->textInput(['disabled' => true])
			->label("Компания"); ?>

		<?= $form->field($modelContractor, "company_consignee[0]", ['options' => ['class' => 'form-group']])
			->textInput(['disabled' => true])
			->label("Компания грузополучатель"); ?>

		<?= $form->field($modelContractor, "company_address_consignee[0]", ['options' => ['class' => 'form-group']])
			->textInput(['disabled' => true])
			->label("Адрес компании грузополучателя"); ?>

		<?= $form->field($modelContractor, "iin[0]", ['options' => ['class' => 'form-group']])
			->textInput(['disabled' => true])
			->label("ИИН/КПП"); ?>

		<?= $form->field($modelContractor, "legal_address[0]", ['options' => ['class' => 'form-group']])
			->textInput(['disabled' => true])
			->label("Юридический адрес"); ?>

		<?= $form->field($modelContractor, "same_address[0]")->checkbox(["label" => "Совпадает с фактическим", 'disabled' => true]); ?>

		<?= $form->field($modelContractor, "actual_address[0]", ['options' => ['class' => 'form-group']])
			->textInput(['disabled' => true])
			->label("Фактический адрес"); ?>

		<?= $form->field($modelContractor, "contact_person[0]", ['options' => ['class' => 'form-group']])
			->textInput(['disabled' => true])
			->label("Контактное лицо"); ?>

		<?= $form->field($modelContractor, "phone1[0]", ['options' => ['class' => 'form-group']])
			->textInput(['disabled' => true])
			->label("Телефон 1"); ?>

		<?= $form->field($modelContractor, "phone2[0]", ['options' => ['class' => 'form-group']])
			->textInput(['disabled' => true])
			->label("Телефон 2"); ?>

		<?= $form->field($modelContractor, "email[0]", ['options' => ['class' => 'form-group']])
			->textInput(['disabled' => true])
			->label("E-mail"); ?>
	</div>

</div>
<div class="contractor-container"></div>
<br>
<br>
<?php
foreach ($stageData as $stage) {
	$stageList[$stage->id] = $stage->name;
}
echo $form->field($model, 'stage')->dropDownList($stageList, ['style' => 'height: 50px;']);
?>
<?= $form->field($model, 'stage_comment')->textarea(['style' => 'height: 100px;']); ?>

<div class="row">
	<div class="col-6">
		<?= $form->field($model, 'payment_order')->textInput(); ?>
	</div>
	<div class="col-6">
		<?= $form->field($model, 'payment_order_date')->widget(DateTimePicker::class, [
			'options' => ['class' => 'form-control'],
			'pluginOptions' => [
				'format' => 'yyyy-mm-dd hh:ii',
				'todayHighlight' => true,
				'autoclose' => true,
			]
		])->label("Дата платежного поручения") ?>
	</div>
</div>

<div class="form-group providers">
	<h3 class="">Поставщики</h3>
	<div class="row providersEditForm" style="align-items: center; display:none">
		<div class="col-3">
			<?php
			foreach ($suppliersData as $supplier) {
				$supplierList[$supplier->id] = $supplier->company_name;
			}
			echo $form->field($modelProviders, 'suppliers[0]')->dropDownList($supplierList, ['disabled' => true]);
			?>
		</div>
		<div class="col-3">
			<?= $form->field($modelProviders, "checkSum[0]")->textInput(['disabled' => true]) ?>
		</div>
		<div class="col-2">
			<?= $form->field($modelProviders, "checkNumber[0]")->textInput(['disabled' => true]) ?>
		</div>
		<div class="col-3">
			<?=
			//$form->field($modelProviders, "dateOfpayment[0]")->textInput(['disabled' => true]);

			$form->field($modelProviders, "dateOfpayment[0]")->widget(DateTimePicker::class, [
				'options' => ['class' => 'form-control'],
				'pluginOptions' => [
					'format' => 'yyyy-mm-dd hh:ii',
					'todayHighlight' => true,
					'autoclose' => true,
				],
				'disabled' => true
			])->label("Дата оплаты") ?>
		</div>
		<div class="col-1 provider-delete btn btn-danger btn-sm">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
				<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
				<path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
			</svg>
		</div>
	</div>
	<div class="provider-container"></div>
	<?= Html::buttonInput('Добавить поставщика', ['class' => 'btn btn-dark provider-add']); ?>
</div>
<?= $form->field($model, "comment")->textarea(['style' => 'height: 100px;']) ?>
<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']); ?>
<?php ActiveForm::end(); ?>

<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js" integrity="sha256-eTyxS0rkjpLEo16uXTS0uVCS4815lc40K2iVpWDvdSY=" crossorigin="anonymous"></script>
<?php
YiiAsset::register($this);
$this->registerJsFile('@web/js/check/createProduct.js', ['depends' => [YiiAsset::class]]);
$this->registerJsFile('@web/js/check/createOrg.js', ['depends' => [YiiAsset::class]]);
$this->registerJsFile('@web/js/check/createSuppl.js', ['depends' => [YiiAsset::class]]);
$this->registerCssFile('@web/css/check/main.css', ['depends' => [YiiAsset::class]]);
?>

<style>
	.payment-radio-container {
		display: flex;
		justify-content: space-around;
	}

	.conteiner-Product {
		display: flex;
	}

	.product-active {
		display: flex;
	}

	.individualForm {
		padding: 5px;
		background-color: #e3e3e3;
		border-radius: 10px;
		border: solid 1px #c2c2c2;
		box-shadow: initial;
	}

	.legalForm {
		padding: 5px;
		background-color: #e3e3e3;
		border-radius: 10px;
		border: solid 1px #c2c2c2;
		box-shadow: initial;
	}

	.container {
		width: 100%;
		max-width: 100%;
		margin: 0;
	}
	.form-control {
        height: 46px;
	}
</style>



<script>
	$(document).ready(function() {
		var $contractorSelect = $('.contractorSelect'); ///!!!!
		var defaultId = $contractorSelect.val(); // Значение id по умолчанию
		// Функция для обновления данных контрактора
		function updateContractorData(id) {
			if (id === '') {
				// Если поле пустое, очищаем контейнер данных
				$('#contractor-data-container').empty();
				return;
			}

			// Отправляем AJAX-запрос на получение данных контрактора
			$.ajax({
				url: '/check/get-contractor',
				method: 'GET',
				data: {
					id: id
				},
				dataType: 'json',
				success: function(data) {
					if (data) {
						// Формируем html-разметку данных контрактора
						var html = '<div class="contractor-form-exist card">';
						html += '<div class="card-header">Данные контрактора</div>';
						html += '<div class="card-body">';

						html += '<div class="form-group">';
						html += '<label for="inputType">Тип</label>';
						html += '<input type="text" class="form-control" id="inputType" value="' + data.type + '" readonly>';
						html += '</div>';

						html += '<div class="form-group">';
						html += '<label for="inputContactPerson">Contact Person</label>';
						html += '<input type="text" class="form-control" id="inputContactPerson" value="' + data.contact_person + '" readonly>';
						html += '</div>';

						if (data.type === 'Юр. Лицо') {
							html += '<div class="form-group">';
							html += '<label for="inputCompany">Компания</label>';
							html += '<input type="text" class="form-control" id="inputCompany" value="' + data.company + '" readonly>';
							html += '</div>';

							html += '<div class="form-group">';
							html += '<label for="inputCompanyConsignee">Компания грузоперевозчик</label>';
							html += '<input type="text" class="form-control" id="inputCompanyConsignee" value="' + data.company_consignee + '" readonly>';
							html += '</div>';

							html += '<div class="form-group">';
							html += '<label for="inputCompanyConsignee">Адрес компании грузоперевозчика</label>';
							html += '<input type="text" class="form-control" id="inputCompanyAddressConsignee" value="' + data.company_address_consignee + '" readonly>';
							html += '</div>';

							html += '<div class="form-group">';
							html += '<label for="inputCompanyConsignee">ИИН</label>';
							html += '<input type="text" class="form-control" id="inputIin" value="' + data.iin + '" readonly>';
							html += '</div>';

							html += '<div class="form-group">';
							html += '<label for="inputCompanyConsignee">Юридический адрес</label>';
							html += '<input type="text" class="form-control" id="inputLegalAddress" value="' + data.legal_address + '" readonly>';
							html += '</div>';

							html += '<div class="form-group">';
							html += '<label for="inputCompanyConsignee">Фактический адрес</label>';
							html += '<input type="text" class="form-control" id="inputActualAddress" value="' + data.actual_address + '" readonly>';
							html += '</div>';

							// Добавьте здесь остальные поля для юридического контрактора

						}
						html += '<div class="form-group">';
						html += '<label for="inputPhone1">Phone 1</label>';
						html += '<input type="text" class="form-control" id="inputPhone1" value="' + data.phone1 + '" readonly>';
						html += '</div>';

						html += '<div class="form-group">';
						html += '<label for="inputPhone2">Phone 2</label>';
						html += '<input type="text" class="form-control" id="inputPhone2" value="' + data.phone2 + '" readonly>';
						html += '</div>';

						html += '<div class="form-group">';
						html += '<label for="inputPhone2">Email</label>';
						html += '<input type="text" class="form-control" id="inputEmail" value="' + data.email + '" readonly>';
						html += '</div>';

						html += '</div>';

						// Обновляем контейнер данных
						$('#contractor-data-container').html(html);
					}

				}
			});
		}
		updateContractorData(defaultId);
		$('<div id="contractor-data-container"></div>').insertAfter($contractorSelect);
		// Обработчик события "изменение поля контрагента"
		$contractorSelect.on('change', function() {
			var selectedId = $(this).val();

			// Удаляем и создаем заново контейнер данных
			// Удаляем и создаем заново контейнер данных
            $(this).closest('.form-group').next('#contractor-data-container').remove();
			$('<div id="contractor-data-container"></div>').insertAfter($(this).closest('.form-group'));

			// Обновляем данные контрактора
			updateContractorData(selectedId);
		});
	});
</script>