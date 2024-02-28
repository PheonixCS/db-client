<?php
$this->registerCssFile('https://use.fontawesome.com/releases/v5.3.1/css/all.css');
$this->registerJsFile('https://use.fontawesome.com/releases/v5.3.1/js/all.js', ['defer' => true, 'crossorigin' => 'anonymous']);
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

<!-- <link href="~/Content/font-awesome.min.css" rel="stylesheet" /> -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

<?php

use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\LogChangesSearch; // подключите вашу модель фильтрации
use app\models\Supplier;
use app\models\suppCheck;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use kartik\daterange\DateRangePicker;

$actionDescriptions = [
	'provider' => 'Действие 1',
	'is_deleted' => 'Счет удален',
	'comment' => 'Изменен комментарий',
	'costDelivery' => 'Изменена стоимость доставки',
	'dateDelivery' => 'Изменена дата доставки',
	'stage' => 'Изменена стадия доставки',
	'payment' => 'Изменен способ оплаты',
	'delivery_time' => 'Изменено время доставки',
	'delivery_type' => 'Изменен тип доставки',
	'stage_comment' => 'Изменен комментарий к стадии',
	'payment_order' => 'Изменено платежное поручение',
	'payment_order_date' => 'Изменена дата платежного поручения',
	'deliveryAddress' => 'Изменен адрес доставки',
	'organization' => 'Изменен контрагент',
	'name' => 'Удален или добавлен продукт',
	'checkSum' => 'Изменена сумма счета',
	'dateOfpayment' => 'Изменена дата оплаты счета поставки',
	'checkNumber' => "Изменен номер счета поставки",
	'sum' => 'Сумма счета',
	'code' => 'Код продукта',
	'price' => 'Цена',
	'quantity' => 'Колличество товара',
	'suppliers' => 'Изменение в поставщиках',

];

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel, // используйте модель фильтрации
	'pager' => ['class' => \yii\bootstrap5\LinkPager::class],
	'columns' => [
		// [
		// 	'attribute' => 'model_name',
		// 	'value' => function ($model) {
		// 		//return $model->model_name;
		// 		return $model->getModelName($model->model_name); // используйте метод модели для получения названия на русском
		// 	},
		// ],
		[
			'attribute' => 'who',
			'value' => function ($model) {
				//return $model->model_name;
				return $model->who; // используйте метод модели для получения названия на русском
			},
		],
		[
			'attribute' => 'attribute_name',
			'filter' => Html::activeDropDownList(
				$searchModel,
				'attribute_name',
				$searchModel->getAttributeList(), // метод в модели фильтрации для получения списка значений атрибута
				['class' => 'form-control', 'prompt' => '']
			),
			'value' => function ($model) { // Отвечает за наимнование атрибутов
				if ($model->attribute_name == 'provider') {
					return 'Поставщик';
				} elseif ($model->attribute_name == 'delivery_type') {
					return 'Тип доставки';
				} elseif ($model->attribute_name == 'stage_comment') {
					return 'Комментарий к стадии';
				} elseif ($model->attribute_name == 'payment_order_date') {
					return 'Дата платежного поручения';
				} elseif ($model->attribute_name == 'delivery_time') {
					return 'Время доставки';
				} elseif ($model->attribute_name == 'deliveryAddress') {
					return 'Адресс доставки';
				} elseif ($model->attribute_name == 'dateDelivery') {
					return 'Дата доставки';
				} elseif ($model->attribute_name == 'costDelivery') {
					return 'Стоимость доставки';
				} elseif ($model->attribute_name == 'is_deleted') {
					return 'Действия';
				} elseif ($model->attribute_name == 'comment') {
					return 'Комментарий';
				} elseif ($model->attribute_name == 'code') {
					return 'Код';
				} elseif ($model->attribute_name == 'checkSum') {
					return 'Сумма счета поставки';
				} elseif ($model->attribute_name == 'dateOfpayment') {
					return 'Дата платежа';
				} elseif ($model->attribute_name == 'checkNumber') {
					return 'Номер счета поставки';
				} elseif ($model->attribute_name == 'stage') {
					return 'Стадия';
				} elseif ($model->attribute_name == 'price') {
					return 'Цена товара';
				} elseif ($model->attribute_name == 'quantity') {
					return 'Колличество товара';
				} elseif ($model->attribute_name == 'payment_order') {
					return 'Платежное поручение';
				} elseif ($model->attribute_name == 'name') {
					return 'Товары';
				} elseif ($model->attribute_name == 'sum') {
					return 'Сумма счета';
				} elseif ($model->attribute_name == 'payment') {
					return 'Оплата';
				} elseif ($model->attribute_name == 'organization') {
					return 'Контрагент';
				} elseif ($model->attribute_name == 'profit') {
					return 'Прибыль';
				} else {
					return $model->attribute_name;
				}
			},
		],
		[
			'attribute' => 'Действие',
			'format' => 'raw',
			'value' => function ($model) use ($actionDescriptions) {
				$oldValue = $model->old_value;
				$newValue = $model->new_value;

				if ($oldValue == $newValue) {
					return 'Нет изменений';
				} elseif (isset($actionDescriptions[$model->attribute_name])) {
					if ($model->attribute_name == 'provider') {
						$deletedSuppliers = [];
						$addedSuppliers = [];

						// Получаем массив удаленных поставщиков
						if ($oldValue !== '') {
						    if($oldValue){
							$oldSuppliers = explode(',', $oldValue);
						    }
						    else{
						       $oldSuppliers = []; 
						    }
							$newSuppliers = explode(',', $newValue);
							$deletedSuppliers = array_diff($oldSuppliers, $newSuppliers);
						}

						// Получаем массив добавленных поставщиков
						if ($newValue !== '') {
							$newSuppliers = explode(',', $newValue);
							if($oldValue){
							$oldSuppliers = explode(',', $oldValue);
						    }
						    else{
						       $oldSuppliers = []; 
						    }
							$addedSuppliers = array_diff($newSuppliers, $oldSuppliers);
						}
						// Создаем массив с расшифровкой действий
						$actionDescriptions = [];
						foreach ($deletedSuppliers as $deletedSupplier) {
							$supplierID = suppCheck::findOne($deletedSupplier);
							$supplier = Supplier::findOne($supplierID->suppliers);
							if ($supplier) {
								$actionDescriptions[] = 'Удален поставщик ' . $supplier->company_name;
							}
						}
						//var_dump(implode(',', $actionDescriptions));
						foreach ($addedSuppliers as $addedSupplier) {
							$supplierID = suppCheck::findOne($addedSupplier);
							if (!isset($supplierID)) {
								continue;
							} else {
								$supplier = Supplier::findOne($supplierID->suppliers);
								if ($supplier) {
									$actionDescriptions[] = 'Добавлен поставщик ' . $supplier->company_name;
								}
							}
						}
						// return $newValue;
						// Формируем и возвращаем строку с расшифровкой
						if ($actionDescriptions) {
							return implode(', ', $actionDescriptions);
						} else {
							return 'Нет изменений в поставщиках';
						}
					}
					if ($model->attribute_name == 'name'  && $model->model_name != "Таблица счетов(Товары)") {
						$deletedSuppliers = [];
						$addedSuppliers = [];

						// Получаем массив удаленных поставщиков
						if ($oldValue !== '') {
							$oldSuppliers = explode(',', $oldValue);
							$newSuppliers = explode(',', $newValue);
							$deletedSuppliers = array_diff($oldSuppliers, $newSuppliers);
						}

						// Получаем массив добавленных поставщиков
						if ($newValue !== '') {
							$newSuppliers = explode(',', $newValue);
							$oldSuppliers = explode(',', $oldValue);
							$addedSuppliers = array_diff($newSuppliers, $oldSuppliers);
						}
						// Создаем массив с расшифровкой действий
						$actionDescriptions = [];
						foreach ($deletedSuppliers as $deletedSupplier) {
							$prodDell = \app\models\Product::findOne($deletedSupplier);
							if ($prodDell) {
								$actionDescriptions[] = 'Удален товар ' . $prodDell->name;
							}
						}
						foreach ($addedSuppliers as $addedSupplier) {
							$prodAdd = \app\models\Product::findOne($addedSupplier);
							if ($prodAdd) {
								$actionDescriptions[] = 'Добавлен товар ' . $prodAdd->name;
							}
						}
						// Формируем и возвращаем строку с расшифровкой
						if ($actionDescriptions) {
							return implode(', ', $actionDescriptions);
						} else {
							return 'Нет изменений в продуктах';
						}
					}
					if ($model->attribute_name == 'comment') {
						$result = "Было:" . $oldValue . "<br>Стало:" . $newValue;
						return $result;
					}
					if ($model->attribute_name == 'is_deleted') {
						if (isset(\app\models\Check::findOne($model->model_id)->numberCheck)) {
							$result = "Счет " . \app\models\Check::findOne($model->model_id)->numberCheck . "  удален";
							return $result;
						}
					}
					if ($model->attribute_name == 'costDelivery') {
						$result = "Изменена стоимость доставки <br>
						Было: " . $oldValue  . "<br>  Стало: " . $newValue;
						return $result;
					}
					if ($model->attribute_name == 'dateDelivery') {
						$result = "Изменена дата доставки <br>
						Было: " . date('d-m-Y H:i:s', (int)$oldValue)  . "<br>  Стало: " . date('d-m-Y H:i:s', (int)$newValue);
						return $result;
					}
					if ($model->attribute_name == 'stage') {
						$result = "Изменена стадия доставки <br>
						Было: " . app\models\Stage::findOne($oldValue)->name  . "<br>  Стало: " . app\models\Stage::findOne($newValue)->name;
						return $result;
					}
					if ($model->attribute_name == 'payment') {
						$result = "Изменен способ оплаты <br>
						Было: " . $oldValue  . "<br>  Стало: " . $newValue;
						return $result;
					}
					if ($model->attribute_name == 'delivery_time') {
						$result = "Изменено время доставки <br>
						Было: " . $oldValue  . "<br>  Стало: " . $newValue;
						return $result;
					}
					if ($model->attribute_name == 'delivery_type') {
						$result = "Изменен тип доставки <br>
						Было: " . \app\models\DeliveryType::findOne($oldValue)->name  . "<br>  Стало: " . \app\models\DeliveryType::findOne($newValue)->name;
						return $result;
					}
					if ($model->attribute_name == 'stage_comment') {
						$result = "Изменен комментарий к стадии доставки <br>
						Было: " . $oldValue  . "<br>  Стало: " . $newValue;
						return $result;
					}
					if ($model->attribute_name == 'payment_order') {
						$result = "Изменено платежное поручение <br>
						Было: " . $oldValue  . "<br>  Стало: " . $newValue;
						return $result;
					}
					if ($model->attribute_name == 'payment_order_date') {
						$result = "Изменена дата платежного поручения <br>
						Было: " . date('d-m-Y H:i:s', (int)$oldValue)  . "<br>  Стало: " . date('d-m-Y H:i:s', (int)$newValue);
						return $result;
					}
					if ($model->attribute_name == 'deliveryAddress') {
						$result = "Изменен адрес доставки <br>
						Было: " . $oldValue  . "<br>  Стало: " . $newValue;
						return $result;
					}
					if ($model->attribute_name == 'organization') {
						if (app\models\LegalContractor::findOne($oldValue)->type == "legal") {
							$oldContr = app\models\LegalContractor::findOne($oldValue)->company;
						} else {
							$oldContr = app\models\LegalContractor::findOne($oldValue)->contact_person;
						}

						if (app\models\LegalContractor::findOne($newValue)->type == "legal") {
							$newContr = app\models\LegalContractor::findOne($newValue)->company;
						} else {
							$newContr = app\models\LegalContractor::findOne($newValue)->contact_person;
						}
						$result = "Изменен контр агент<br>
						Было: " . $oldContr  . "<br>  Стало: " . $newContr;
						return $result;
					}
					if ($model->attribute_name == 'checkSum') {
						$supplierID = suppCheck::findOne($model->model_id);
						$supplier = Supplier::findOne($supplierID->suppliers);
						$result = "Изменена сумма счета поставки " . $supplierID->checkSum . ". Поставщик: " . $supplier->company_name . "<br> 
						Было: " . $oldValue  . "<br>  Стало: " . $newValue;
						return $result;
					}
					if ($model->attribute_name == 'dateOfpayment') {
						$supplierID = suppCheck::findOne($model->model_id);
						$supplier = Supplier::findOne($supplierID->suppliers);
						$result = "Изменена дата оплаты счета поставки " . $supplierID->checkSum . ". Поставщик: " . $supplier->company_name . "<br>
						Было: " . date('d-m-Y H:i:s', (int)$oldValue)  . "<br>  Стало: " . date('d-m-Y H:i:s', (int)$newValue);
						return $result;
					}
					if ($model->attribute_name == 'checkNumber') {
						$supplierID = suppCheck::findOne($model->model_id);
						$supplier = Supplier::findOne($supplierID->suppliers);
						// var_dump($supplierID);
						// die;
						$result = "Изменен номер счета поставки " . $supplierID->checkNumber . ". Поставщик: " . $supplier->company_name . "<br>
						Было: " . $oldValue  . "<br>  Стало: " . $newValue;
						return $result;
					}
					if ($model->attribute_name == 'sum') {
						$result = "Изменена сумма счета <br>
						Было: " . $oldValue  . "<br>  Стало: " . $newValue;
						return $result;
					}
					if ($model->attribute_name == 'name') {
						$prod =  app\models\Product::findOne($model->model_id);
						if (isset($prod)) {
							$result = "Изменено наименование товара <br> с кодом <br>" . app\models\Product::findOne($model->model_id)->code . "
						Было: " . $oldValue  . "<br>  Стало: " . $newValue;
							return $result;
						} else {
							$result = "Старое значение " . $oldValue . " Новое значение:" . $newValue . " ID удаленного объекта:" . $model->model_id;
							return $result;
						}
					}
					if ($model->attribute_name == 'code') {
						$prod =  app\models\Product::findOne($model->model_id);
						if (isset($prod)) {
							$result = "Изменен код товара <br>
						Было: " . $oldValue  . "<br>  Стало: " . $newValue;
							return $result;
						} else {
							$result = "Старое значение " . $oldValue . " Новое значение:" . $newValue . " ID удаленного объекта:" . $model->model_id;
							return $result;
						}
					}
					if ($model->attribute_name == 'price') {
						$prod =  app\models\Product::findOne($model->model_id);
						if (isset($prod)) {
							$result = "Изменена цена товара с кодом <br>" . app\models\Product::findOne($model->model_id)->code . "
						Было: " . $oldValue  . "<br>  Стало: " . $newValue;
							return $result;
						} else {
							$result = "Старое значение " . $oldValue . " Новое значение:" . $newValue . " ID удаленного объекта:" . $model->model_id;
							return $result;
						}
					}
					if ($model->attribute_name == 'quantity') {
						$prod =  app\models\Product::findOne($model->model_id);
						if (isset($prod)) {
							$result = "Изменено колличество товара с кодом <br>" . app\models\Product::findOne($model->model_id)->code . "
						Было: " . $oldValue  . "<br>  Стало: " . $newValue;
							return $result;
						} else {
							$result = "Старое значение " . $oldValue . " Новое значение:" . $newValue . " ID удаленного объекта:" . $model->model_id;
							return $result;
						}
					}
					return $actionDescriptions[$model->attribute_name];
				} else {
					$result = "Было:" . $oldValue . "<br>Стало:" . $newValue;
					return $result;
					//return 'Неизвестное действие';
				}
			},
		],
		[
			'attribute' => 'changed_at',
			'format' => 'html',
			'filter' => DateRangePicker::widget([
				'bsVersion' => '5.x',
				'model' => $searchModel,
				'attribute' => 'changed_at',
				'convertFormat' => true,
				'language' => 'ru',
				'pluginOptions' => [
					'timePicker' => true,
					'timePickerIncrement' => 30,
					'format' => 'd-m-Y H:i',
					'locale' => [
						'format' => 'd-m-Y H:i'
					],
					'timePicker24Hour' => true,
					'buttons' => [
						'clear' => [
							'label' => 'Clear',
							'class' => 'btn btn-danger',
						],
					],
				],

			]),
			'content' => function ($data) {
				$timestamp = (int)$data->changed_at;
				$date = date('d-m-Y H:i:s', $timestamp);
				return $date;
			},
		],

	],
]);