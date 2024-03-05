<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\web\YiiAsset;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;

$this->title = 'Редактировать счет';
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
	->textInput(['readonly' => true, 'style' => 'pointer-events: none;']); ?>
<?= $form->field($model, 'payment')
	->radioList([
		'Безналичный' => 'Безналичный',
		'Наличный' => 'Наличный',
		'Пластиковые карты' => 'Пластиковые карты',
		'Яндекс деньги' => 'Яндекс деньги'
	], [
		'class' => 'payment-radio-container',
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
					<th style="width: 20%;"><?= $form->field($modelProduct, 'code[new][0]')->textinput(['rows' => 1, 'cols' => 1, 'disabled' => true])->label(""); ?></th>
					<th><?= $form->field($modelProduct, 'name[new][0]')->textarea(['style' => 'height: 182px;', 'disabled' => true])->label(""); ?></th>
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
					<th><?= $form->field($modelProduct, 'price[new][0]')
								->textInput(['disabled' => true, 'class' => 'form-control p-price'])
								->label(""); ?></th>
					<th><?= $form->field($modelProduct, 'quantity[new][0]')
								->textInput(['disabled' => true, 'class' => 'form-control p-quantity'])
								->label(""); ?></th>
					<th><?= $form->field($modelProduct, 'unit_of_measurement[new][0]')
								->textInput(['disabled' => true, 'value' => 'шт'])
								->label(""); ?></th>
					<th><?= $form->field($modelProduct, 'amount[new][0]')
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
		<?php foreach ($productsData as $index => $productData) : ?>
			<?= $form->field($modelProduct, "id[old][{$index}]")->hiddenInput(['value' => $productData->id])->label(false); ?>
			<div class="product-active">
				<table class="table table-product" style="width: 80%;">
					<thead>
						<tr class="product-header1.1">
							<th>Код</th>
							<th>Название</th>
						</tr>
					</thead>
					<tbody>
						<tr class="product-tr1.1">
							<th style="width: 20%;">
								<?= $form->field($modelProduct, "code[old][{$index}]")->textInput(['value' => $productData->code])->label(""); ?>
							</th>
							<th>
								<?= $form->field($modelProduct, "name[old][{$index}]")->textarea(['style' => 'height: 182px;', 'value' => $productData->name])->label(""); ?>
							</th>
						</tr>
					</tbody>
				</table>
				<table class="table table-product">
					<thead>
						<tr class="product-header1.2">
							<th>Цена</th>
							<th>Количество</th>
							<th>Единица измерения</th>
							<th>Сумма</th>
						</tr>
					</thead>
					<tbody>
						<tr class="product-tr1.2">
							<th>
								<?= $form->field($modelProduct, "price[old][{$index}]")->textInput(['class' => 'form-control p-price', 'value' => $productData->price])->label(""); ?>
							</th>
							<th>
								<?= $form->field($modelProduct, "quantity[old][{$index}]")->textInput(['class' => 'form-control p-quantity', 'value' => $productData->quantity])->label(""); ?>
							</th>
							<th>
								<?= $form->field($modelProduct, "unit_of_measurement[old][{$index}]")->textInput(['value' => $productData->unit_of_measurement])->label(""); ?>
							</th>
							<th>
								<?= $form->field($modelProduct, "amount[old][{$index}]")->textInput(['readonly' => true, 'class' => 'form-control p-amount', 'style' => 'pointer-events: none;', 'value' => $productData->amount])->label(""); ?>
							</th>
						</tr>
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th>
								<div id="delete-action-<?= $index ?>">
									<div class="col-1 product-delete btn btn-danger btn-sm" style="width: 50px;" onclick="deleteProduct(<?= $index ?>)">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
											<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"></path>
											<path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"></path>
										</svg>
									</div>
								</div>
								<div id="cancel-delete-action-<?= $index ?>" style="display: none;">
									<div class="btn btn-secondary btn-sm" onclick="cancelDeleteProduct(<?= $index ?>)">Отменить удаление</div>
								</div>
							</th>
						</tr>
					</tbody>
				</table>
			</div>
			<script>
				$(document).ready(function() {

					// Обработчик изменения цены
					$(".p-price").on('change', function() {
						$p = $(this).val();
						$q = $(this).closest('tr').find('.p-quantity').val();
						$a = $q * $p;
						$(this).closest('tr').find('.p-amount').val($a);
					});

					// Обработчик изменения количества
					$(".p-quantity").on('change', function() {
						$q = $(this).val();
						$p = $(this).closest('tr').find('.p-price').val();
						$a = $q * $p;
						$(this).closest('tr').find('.p-amount').val($a);
					});
				});
			</script>

		<?php endforeach; ?>
	</div>
	<script>
		function deleteProduct(index) {
			// Получаем значения полей
			var idField = document.getElementById("product-id-old-" + index);
			var codeField = document.getElementById("product-code-old-" + index);
			var nameField = document.getElementById("product-name-old-" + index);
			var priceField = document.getElementById("product-price-old-" + index);
			var quantityField = document.getElementById("product-quantity-old-" + index);
			var unitField = document.getElementById("product-unit_of_measurement-old-" + index);

			// Скрываем кнопку удаления, показываем кнопку отмены удаления
			document.getElementById("delete-action-" + index).style.display = "none";
			document.getElementById("cancel-delete-action-" + index).style.display = "block";

			// Скрываем поля
			idField.disabled = true;
			codeField.disabled = true;
			nameField.disabled = true;
			priceField.disabled = true;
			quantityField.disabled = true;
			unitField.disabled = true;

			// Добавляем скрытое поле с значением productDel[{$index}] = id продукта
			var productDelInput = document.createElement("input");
			productDelInput.type = "hidden";
			productDelInput.name = "ProductD[del][" + index + "]";
			productDelInput.value = idField.value;
			document.getElementById("w0").appendChild(productDelInput);
		}

		function cancelDeleteProduct(index) {
			// Получаем значения полей
			var idField = document.getElementById("product-id-old-" + index);
			var codeField = document.getElementById("product-code-old-" + index);
			var nameField = document.getElementById("product-name-old-" + index);
			var priceField = document.getElementById("product-price-old-" + index);
			var quantityField = document.getElementById("product-quantity-old-" + index);
			var unitField = document.getElementById("product-unit_of_measurement-old-" + index);

			// Показываем кнопку удаления, скрываем кнопку отмены удаления
			document.getElementById("delete-action-" + index).style.display = "block";
			document.getElementById("cancel-delete-action-" + index).style.display = "none";

			// Возвращаем поля в активное состояние
			idField.disabled = false;
			codeField.disabled = false;
			nameField.disabled = false;
			priceField.disabled = false;
			quantityField.disabled = false;
			unitField.disabled = false;

			// Удаляем скрытое поле
			var productDelInput = document.querySelector("input[name='ProductD[del][" + index + "]']");
			if (productDelInput) {
				document.getElementById("w0").removeChild(productDelInput);
			}
		}
	</script>

	<?php echo Html::buttonInput('Добавить товар', ['class' => 'btn btn-dark product-add']); ?>
</div>
<?= $form->field($model, 'deliveryAddress')
	->textarea(['style' => 'height: 100px;']); ?>
<?= $form->field($model, 'costDelivery')
	->textInput(); ?>
<?php
//$model->dateDelivery = date('Y-m-d H:i', $model->dateDelivery);

if ($model->dateDelivery) {
	$model->dateDelivery = date('Y-m-d H:i', $model->dateDelivery);
} else {
	$model->dateDelivery = "";
}
echo $form->field($model, 'dateDelivery')->widget(DateTimePicker::class, [
	'name' => 'dp_1',
	'options' => ['class' => 'form-control'],
	'pluginOptions' => [
		'format' => 'yyyy-mm-dd hh:ii',
		'todayHighlight' => true,
		'autoclose' => true,

	],

])->label("Дата доставки")
?>
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
    if (!isset($contractorList)){
        $contractorList = [];
    }
    if(!isset($supplierList)) {
        $supplierList = [];
    }
	//echo $form->field($model, 'organization')->dropDownList($contractorList, ['class' => 'form-control contractorSelect'])->label("Контрагент");
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
if(!isset($stageList)){
    $stageList = [];
}
echo $form->field($model, 'stage')->dropDownList($stageList, ['style' => 'height: 50px;']);
?>
<?= $form->field($model, 'stage_comment')->textarea(['style' => 'height: 100px;']); ?>

<div class="row">
	<div class="col-6">
		<?= $form->field($model, 'payment_order')->textInput(); ?>
	</div>
	<div class="col-6">
		<?php

		// $form->field($model, 'payment_order_date')->widget(DatePicker::class, [
		// 	'options' => ['class' => 'form-control']
		// ]);
		if ($model->payment_order_date) {
			$model->payment_order_date = date('Y-m-d H:i', $model->payment_order_date);
		} else {
			$model->payment_order_date = "";
		}
		echo $form->field($model, 'payment_order_date')->widget(DateTimePicker::class, [
			'name' => 'dp_1',
			'options' => ['class' => 'form-control'],
			'pluginOptions' => [
				'format' => 'yyyy-mm-dd hh:ii',
				'todayHighlight' => true,
				'autoclose' => true,

			],

		])->label("Дата платежного поручения")
		?>
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
			echo $form->field($modelProviders, 'suppliers[new][0]')->dropDownList($supplierList, ['disabled' => true]);
			?>
		</div>
		<div class="col-3">
			<?= $form->field($modelProviders, "checkSum[new][0]")->textInput(['disabled' => true]) ?>
		</div>
		<div class="col-2">
			<?= $form->field($modelProviders, "checkNumber[new][0]")->textInput(['disabled' => true]) ?>
		</div>
		<div class="col-3">
			<?= $form->field($modelProviders, "dateOfpayment[new][0]")->textInput(['disabled' => true]) ?>
		</div>
		<div class="col-1 provider-delete btn btn-danger btn-sm">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
				<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
				<path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
			</svg>
		</div>
	</div>
	<div class="provider-container">
		<?php if ($suppChecks) { ?>
			<div class="row" style="align-items: flex-start;">
				<div class="col-3"><b>Поставщик</b></div>
				<div class="col-3"><b>Сумма счета</b></div>
				<div class="col-2"><b>Номер счета</b></div>
				<div class="col-3"><b>Дата оплаты</b></div>
				<div class="col-1"></div>
			</div>
		<?php } ?>
		<?php foreach ($suppChecks as $index => $suppCheck) : ?>
			<div class="row" style="align-items: flex-start;">
				<div class="col-3">
					<?php
					$supplierList = [];
					foreach ($suppliers as $supplier) {
						$supplierList[$supplier->id] = $supplier->company_name;
					}
					echo $form->field($modelProviders, "suppliers[old][{$index}]")->dropDownList($supplierList, ['value' => $suppCheck->suppliers])->label('');
					?>
				</div>
				<div class="col-3 providerCol">
					<?= $form->field($modelProviders, "checkSum[old][{$index}]")->textInput(['value' => $suppCheck->checkSum])->label(""); ?>
				</div>
				<div class="col-2 providerCol">
					<?= $form->field($modelProviders, "checkNumber[old][{$index}]")->textInput(['value' => $suppCheck->checkNumber])->label(""); ?>
				</div>
				<div class="col-3 providerCol">
					<?php

					$dateOfPayment = $suppCheck->dateOfpayment ? date('d-m-Y H:i', $suppCheck->dateOfpayment) : '';
					//$modelProviders->dateOfpayment[0][$index] = $dateOfPayment;
					echo $form->field($modelProviders, "dateOfpayment[old][{$index}]")->widget(DateTimePicker::class, [
						'name' => 'dateOfpayment',
						'options' => ['class' => 'form-control'],
						'pluginOptions' => [
							'format' => 'dd-mm-yyyy hh:ii',
							'todayHighlight' => true,
							'autoclose' => true,
						],
					])->label("");
					?>

					<script>
						$(document).ready(function() {
							var dateOfPayment = '<?php echo $dateOfPayment; ?>';
							//alert(1);
							$('input[name="suppCheck[dateOfpayment][old][<?php echo $index; ?>]"]').val(dateOfPayment);
						});
					</script>

					<?php
					//echo $form->field($modelProviders, "dateOfpayment[old][{$index}]")->textInput(['value' => $suppCheck->dateOfpayment])->label("");

					?>
					<?= $form->field($modelProviders, "suppCheckId[old][{$index}]")->hiddenInput(['value' => $suppCheck->id])->label(""); ?>
				</div>
				<div class="col-1 providerCol">
					<div id="delete-action-sup-<?= $index ?>">
						<div class="provider-delete btn btn-danger btn-sm text-center" onclick="deleteSupplier(<?= $index ?>)">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
								<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"></path>
								<path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"></path>
							</svg>
						</div>
					</div>
					<div id="cancel-delete-action-sup-<?= $index ?>" style="display: none;">
						<div class="cancel-delete btn btn-secondary btn-sm text-center" onclick="cancelDeleteSupplier(<?= $index ?>)">
							Отменить удаление
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<script>
		function deleteSupplier(index) {
			// Получаем значения полей
			var supplierField = document.getElementById("suppcheck-suppliers-old-" + index);
			var checkSumField = document.getElementById("suppcheck-checksum-old-" + index);
			var checkNumberField = document.getElementById("suppcheck-checknumber-old-" + index);
			var dateOfPaymentField = document.getElementById("suppcheck-dateofpayment-old-" + index);
			var supplierCheckIdField = document.getElementById("suppcheck-suppcheckid-old-" + index);

			// Скрываем кнопку удаления, показываем кнопку отмены удаления
			document.getElementById("delete-action-sup-" + index).style.display = "none";
			document.getElementById("cancel-delete-action-sup-" + index).style.display = "block";
			console.log(supplierField);
			// Удаляем данные из полей
			supplierField.disabled = true;
			checkSumField.disabled = true;
			checkNumberField.disabled = true;;
			dateOfPaymentField.disabled = true;

			// Добавляем скрытое поле с ID поставщика для удаления
			var supplierDelInput = document.createElement("input");
			supplierDelInput.type = "hidden";
			supplierDelInput.name = "Supp[del][" + index + "]";
			supplierDelInput.value = supplierCheckIdField.value;
			console.log(supplierDelInput);
			document.getElementById("w0").appendChild(supplierDelInput);
		}

		function cancelDeleteSupplier(index) {
			// Получаем значения полей
			var supplierField = document.getElementById("suppcheck-suppliers-old-" + index);
			var checkSumField = document.getElementById("suppcheck-checksum-old-" + index);
			var checkNumberField = document.getElementById("suppcheck-checknumber-old-" + index);
			var dateOfPaymentField = document.getElementById("suppcheck-dateofpayment-old-" + index);

			// Скрываем кнопку "Отменить удаление", показываем кнопку "Удалить"
			// Скрываем кнопку удаления, показываем кнопку отмены удаления
			document.getElementById("delete-action-sup-" + index).style.display = "block";
			document.getElementById("cancel-delete-action-sup-" + index).style.display = "none";

			// Восстанавливаем значения полей
			supplierField.disabled = false;
			checkSumField.disabled = false;
			checkNumberField.disabled = false;
			dateOfPaymentField.disabled = false;

			// Удаляем скрытое поле с ID поставщика для удаления
			var supplierDelInput = document.querySelector("input[name='Supp[del][" + index + "]']");
			if (supplierDelInput) {
				document.getElementById("w0").removeChild(supplierDelInput);
			}
		}
	</script>
	<?= Html::buttonInput('Добавить поставщика', ['class' => 'btn btn-dark provider-add']); ?>
</div>
<?= $form->field($model, "comment")->textarea(['style' => 'height: 100px;']) ?>
<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']); ?>
<?php ActiveForm::end(); ?>

<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js" integrity="sha256-eTyxS0rkjpLEo16uXTS0uVCS4815lc40K2iVpWDvdSY=" crossorigin="anonymous"></script>
<?php
YiiAsset::register($this);
$this->registerJsFile('@web/js/update/updateProduct.js', ['depends' => [YiiAsset::class]]);
$this->registerJsFile('@web/js/update/updateOrg.js', ['depends' => [YiiAsset::class]]);
$this->registerJsFile('@web/js/update/updateSuppl.js', ['depends' => [YiiAsset::class]]);
$this->registerCssFile('@web/css/update/update.css', ['depends' => [YiiAsset::class]]);
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

	#contractor-data-container {
		margin-top: 10px;
	}

	.provider-header {
		display: flex;
		justify-content: space-between;
		background-color: #f0f0f0;
		padding: 10px;
		font-weight: bold;
	}

	.provider-cell {
		flex: 1;
	}
	.form-control {
        height: 46px;
	}
</style>

<script>
	$(document).ready(function() {



		function setDefaultAmount() {
			// Обходим все строки таблицы
			$(".conteiner-Product").each(function() {
				$price = $(this).find('.p-price').val();
				$quantity = $(this).find('.p-quantity').val();
				$amount = $quantity * $price;
				$(this).find('.p-amount').val($amount);
			});
			//alert(1);
		}
		// Вызываем функцию при загрузке страницы
		setDefaultAmount();

		var $contractorSelect = $('.contractorSelect');
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
            $(this).closest('.form-group').next('#contractor-data-container').remove();
			$('<div id="contractor-data-container"></div>').insertAfter($(this).closest('.form-group'));

			// Обновляем данные контрактора
			updateContractorData(selectedId);
		});
	});
</script>