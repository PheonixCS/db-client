<?php

use yii\grid\GridView;
use \yii\bootstrap5\LinkPager;
use yii\widgets\Pjax;

$this->title = "Счета";
echo '<h1>Счета</h1>';
echo '<a class="btn btn-success btn-createCheck" href="/check/create">Создать счет</a>';


Pjax::begin([
	'id' => 'my-gridview-pjax',
	'enablePushState' => false, // Отключаем изменение URL при обновлении AJAX
]);

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel, // используйте модель фильтрации
	'pager' => ['class' => \yii\bootstrap5\LinkPager::class],
	'columns' => [
		[
			'attribute' => 'who',
			'contentOptions' => ['class' => 'table_class'],
			'content' => function ($data) {
				if ($data->is_deleted) {
					return '<span style="text-decoration: line-through;">' . $data->who . '</span>';
				} else {
					return $data->who;
				}
			},
		],
		[
			'attribute' => 'payment',
			'contentOptions' => ['class' => 'table_class'],
			'content' => function ($data) {
				if ($data->is_deleted) {
					return '<span style="text-decoration: line-through;">' . $data->payment . '</span>';
				} else {
					return $data->payment;
				}
			},
		],
		[
			'attribute' => 'numberCheck',
			'value' => 'numberCheck',
			'headerOptions' => ['class' => 'text-center'],
			'contentOptions' => ['class' => 'text-center'],
			'content' => function ($data) {
				if ($data->is_deleted) {
					return '<span style="text-decoration: line-through;">' . $data->numberCheck . '</span>';
				} else {
					return $data->numberCheck;
				}
			},
		],
		[
			'attribute' => 'name',
			'contentOptions' => ['class' => 'nameTDClass text-center'],
			'content' => function ($data) use ($products) {

				$productIds = explode(',', $data->name);
				$productList = $products->find()
					->where(['id' => $productIds])
					->all();
				$table = '<table class="table tableProviders">';
				$table .= '<tbody>';
				foreach ($productList as $product) {
					$table .= '<tr>';
					$table .= '<td>' . $product->name . '</td>';
					// Создайте кнопку-значок вопроса с использованием Bootstrap
					$buttonId = 'questionButton_' . $data->id;
					$questionIcon = '<td style="display: flex;width:100%;justify-content: flex-end;">' .
						'<a id="' . $buttonId . '" tabindex="0" ' .
						'class="btn btn-sm btn-secondary questionButton" data-bs-toggle="popover" data-bs-placement="top"' .
						'data-bs-content="количество: ' . $product->quantity . '">' .
						'<i class="bi bi-question-square"></i>' .
						'</a>';
					$table .= $questionIcon . '</td>';
					$table .= '</tr>';
				}
				$table .= '</tbody>';
				$table .= '</table>';
				//$table = '<button class="tooltip-button-org">?</button>';
				if ($data->is_deleted) {
					return '<span style="text-decoration: line-through;">' . $table . '</span>';
				} else {
					return $table;
				}
			}
		],
		[
			'attribute' => 'sum',
			'value' => 'sum',
			'headerOptions' => ['class' => 'text-center'],
			'contentOptions' => ['class' => 'text-center'],
			'content' => function ($data) {
				if ($data->is_deleted) {
					return '<span style="text-decoration: line-through;">' . $data->sum . '</span>';
				} else {
					return $data->sum;
				}
			},
		],
		[
			'attribute' => 'organization',
			'contentOptions' => ['class' => 'nameTDClass'],
			'content' => function ($data) use ($organization) {
				$org = $organization->findOne($data->organization);
				$table = '<table class="table tableProviders table-bordered">';
				$table .= '<tbody>';
				if ($org) {
					if ($org->type == "individual") {
						$table .= '<tr>';
						$table .= '<td>' . $org->contact_person . '</td>';
						$table .= '</tr>';
						$table .= '<tr>';
						$table .= '<td>' . $org->phone1 . '</td>';
						$table .= '</tr>';
						$table .= '<tr>';
						$table .= '<td>' . $org->email . '</td>';
						$table .= '</tr>';
					} else {
						$table .= '<tr>';
						$table .= '<td>' . $org->company . '</td>';
						$table .= '</tr>';
						$table .= '<tr>';
						$table .= '<td>' . $org->contact_person . '</td>';
						$table .= '</tr>';
						$table .= '<tr>';
						$table .= '<td>' . $org->phone1 . '</td>';
						$table .= '</tr>';
						$table .= '<tr>';
						$table .= '<td>' . $org->email . '</td>';
						$table .= '</tr>';
					}
				}
				$table .= '</tbody>';
				$table .= '</table>';
				//$table =<button class="tooltip-button-org">?</button>
				if ($data->is_deleted) {
					return '<span style="text-decoration: line-through;">' . $table . '</span>';
				} else {
					return $table;
				}
			}
		],
		[
			'attribute' => 'deliveryAddress',
			'headerOptions' => ['class' => 'text-center'],
			'contentOptions' => ['class' => 'text-center'],
			'content' => function ($data) {
				if ($data->is_deleted) {
					return '<span style="text-decoration: line-through;">' . $data->deliveryAddress . '</span>';
				} else {
					return $data->deliveryAddress;
				}
			},
		],
		[
			'attribute' => 'stage_comment',
			'headerOptions' => ['class' => 'text-center'],
			'contentOptions' => ['class' => 'text-center'],
			'content' => function ($data) {
				if ($data->is_deleted) {
					return '<span style="text-decoration: line-through;">' . $data->stage_comment . '</span>';
				} else {
					return $data->stage_comment;
				}
			},
		],
		[
			'attribute' => 'dateDelivery',
			'headerOptions' => ['class' => 'text-center'],
			'contentOptions' => ['class' => 'text-center'],
			'content' => function ($data) {
				if ($data->is_deleted) {
					return '<span style="text-decoration: line-through;">' . $data->dateDelivery . '</span>';
				} else {
					return $data->dateDelivery;
				}
			},
		],
		[
			'attribute' => 'costDelivery',
			'headerOptions' => ['class' => 'text-center'],
			'contentOptions' => ['class' => 'text-center'],
			'content' => function ($data) {
				if ($data->is_deleted) {
					return '<span style="text-decoration: line-through;">' . $data->costDelivery . '</span>';
				} else {
					return $data->costDelivery;
				}
			},
		],
		[
			'attribute' => 'delivery_type',
			'headerOptions' => ['class' => 'text-center'],
			'contentOptions' => function ($data) {
				$delivery_type = \app\models\DeliveryType::findOne($data->delivery_type);
				return ['style' => 'background-color:' . $delivery_type->color . '!important;', 'class' => 'text-center'];
			},
			'content' => function ($data) use ($delivery_types) {
				if ($data->is_deleted) {
					$delivery_type = array_filter($delivery_types, function ($item) use ($data) {
						$item->id == $data->delivery_type;
						return '<span style="text-decoration: line-through;">' . $item->id . '</span>';
					});
					$delivery_name = !empty($delivery_type) ? reset($delivery_type)->name : '';
					return '<span style="text-decoration: line-through;">' . $delivery_name . '</span>';
				} else {
					$delivery_type = array_filter($delivery_types, function ($item) use ($data) {
						return $item->id == $data->delivery_type;
					});
					$delivery_name = !empty($delivery_type) ? reset($delivery_type)->name : '';
					return '<span>' . $delivery_name . '</span>';
				}
			}
		],
		[
			'attribute' => 'stage',
			'contentOptions' => function ($data) use ($stageModel) {
				$stage = $stageModel->findOne($data->stage);
				return ['style' => 'background-color:' . $stage->color . '!important;'];
			},
			'content' => function ($data) use ($stageModel) {
				$stage = $stageModel->findOne($data->stage);
				if ($stage) {
					$block = '<div>';
					$block .= $stage->name;
					$block .= '</div>';
				}
				if ($data->is_deleted) {
					return '<span style="text-decoration: line-through;">' . $block . '</span>';
				} else {
					return $block;
				}
			}
		],
		[
			'attribute' => 'provider',
			'format' => 'raw',
			'encodeLabel' => false,
			'contentOptions' => ['class' => 'text-center providerTD'],
			'value' => function ($data) use ($providers, $suppliers, $suppliersData) {
				$providerIds = explode(',', $data->provider);
				$table = '<table class="table tableProviders table-bordered providerTable_' . $data->id . '">';
				$table .= '<tbody>';
				foreach ($providerIds as $providerId) {
					$provider = $providers->findOne($providerId);
					if ($provider) {
						$table .= '<tr class="supplier_' . $providerId . '">';
						$supplier = $suppliers->findOne($provider->suppliers);
						$table .= '<td class="supId supplier_' . $providerId . '" abbr="' . $providerId . '">' . $supplier->company_name . '</td>';
						$table .= '<td class="modId supplier_' . $providerId . '" abbr="' . $data->id . '" class="vertical-center">';
						$table .= '<a class="supplier_' . $providerId . '" href="/check/providerdelete" data-provider-id="' . $providerId . '" data-data-id="' . $data->id . '"><i class="bi bi-x"></i></a>';
						$table .= '</td>';
						$table .= '</tr>';
					}
				}
				$table .= '</tbody>';
				$table .= '</table>';
				$table .= '<br>';
				$table .= '<button type="button" class="btn btn-primary toggleForm btn-sm">+</button>';
				$table .= '<form class="supplierForm-' . $data->id . '" style="display:none">';
				$table .= '<div class="form-group">';
				$table .= '<select class="form-control supplierSelect" name="providerId-' . $data->id . '">';
				foreach ($suppliersData as $supplier) {
					$table .= '<option value="' . $supplier->id . '">' . $supplier->company_name . '</option>';
				}
				$table .= '</select>';
				$table .= '</div>';
				$table .= '<div class="form-group">';
				$table .= '<input type="text" class="form-control" name="checkNumber" placeholder="Номер счета закупки">';
				$table .= '</div>';
				$table .= '<div class="form-group">';
				$table .= '<input type="text" class="form-control" name="checkSum" placeholder="Сумма счета">';
				$table .= '</div>';
				$table .= '<div class="form-group">';
				$table .= '<input type="text" class="form-control" name="dateOfpayment" placeholder="Дата оплаты">';
				$table .= '</div>';
				$table .= '<input type="hidden" name="dataId" value="' . $data->id . '">';
				$table .= '<button type="submit" data=' . $data->id . ' class="btn btn-primary addSuppCheck">Добавить</button>';
				$table .= '</form>';
				$table .= '<br>';
				if ($data->is_deleted) {
					return '<span style="text-decoration: line-through;">' . $table . '</span>';
				} else {
					return $table;
				}
			},
		],
		[
			'attribute' => 'profit',
			'headerOptions' => ['class' => 'text-center'],
			'contentOptions' => ['class' => 'text-center'],
			'content' => function ($data) {
				if ($data->is_deleted) {
					return '<span style="text-decoration: line-through;">' . $data->profit . '</span>';
				} else {
					return $data->profit;
				}
			},
		],
		[
			'attribute' => 'comment',
			'headerOptions' => ['class' => 'text-center'],
			'contentOptions' => ['class' => 'text-center'],
			'content' => function ($data) {
				if ($data->is_deleted) {
					return '<span style="text-decoration: line-through;">' . $data->comment . '</span>';
				} else {
					return $data->comment;
				}
			},
		],
		['class' => 'yii\grid\ActionColumn'],
	],
]);
Pjax::end();
?>

<style>
	.container {
		margin: 0;
		padding-left: 10px !important;
		padding-right: 0 !important;
		overflow-x: auto;
		width: 100% !important;
		max-width: 100% !important;
	}

	a {
		text-decoration: none;
	}

	.providerTD {
		width: 200px;
		padding: 0 !important;

		display: flex;
		flex-direction: column;
		align-items: center;
	}

	.tableProviders {
		margin: 0 !important;
	}

	td.vertical-center {
		vertical-align: middle;
	}

	.nameTDClass {
		padding: 0 !important;
	}

	#footer {
		display: none;
	}

	.beautifier {
		position: relative;
		display: inline-block;
		cursor: pointer;
	}

	.beautifier .beautifier-inner {
		background-color: #000;
		color: #fff;
		padding: 8px;
		border-radius: 4px;
	}

	.tooltip-button-org {
		position: relative;
		display: inline-block;
		width: 25px;
		height: 25px;
		border: none;
		background-color: transparent;
		color: #333;
		font-size: 18px;
		font-weight: bold;
		line-height: 1;
		cursor: pointer;
		outline: none;
	}

	.tooltip-button-org:hover,
	.tooltip-button-org:focus {
		color: #fff;
		background-color: #333;
		border-radius: 50%;
	}
</style>


<script>
	$(document).ready(function() {
		setInterval(function() {
			$.pjax.reload({
				container: '#my-gridview-pjax'
			});
		}, 60000); // Обновление каждую минуту (60000 миллисекунд)
	});
	$(document).ready(function() {
		$('[data-bs-toggle="popover"]').popover();
		$(document).on('click', '.questionButton', function(e) {
			e.preventDefault();
			e.stopPropagation();

			// Проверка, открыта ли подсказка
			if ($(this).attr('aria-describedby')) {
				// Закрыть подсказку, если она уже открыта
				$(this).popover('hide');
			} else {
				// Открыть подсказку, если она закрыта
				$(this).popover('show');
			}
		});
		$(document).on('click', function(e) {
			var target = $(e.target);
			// Проверяем, является ли цель клика элементом всплывающей подсказки или ее родителем
			if (!target.is('.questionButton, .questionButton *')) {
				// Если цель клика не является всплывающей подсказкой или ее родителем, скрываем подсказку
				$('.questionButton').popover('hide');
			}
		});
	});


	$('body').on('click', '.providerTD a', function(e) {
		e.preventDefault();
		var supplierId = $(this).closest('tr').find('.supId').attr('abbr');
		var checkModelId = $(this).closest('tr').find('.modId').attr('abbr');
		console.log('.supplier_' + supplierId);
		$.ajax({
			url: '/check/providerdelete',
			type: 'post',
			data: {
				providerId: supplierId,
				dataId: checkModelId
			},
			success: function(response) {
				$('.supplier_' + supplierId).remove();
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.error('AJAX Error: ' + textStatus);
			}
		});
	});

	$(document).on('click', '.addSuppCheck', function(e) {
		e.preventDefault();
		$id = $(this).attr('data')
		console.log($id);
		//supplierForm-' . $data->id . '
		$form = $('.supplierForm-' + $id);
		// Получите выбранное значение из select
		var providerId = $form.find('select.supplierSelect').val();

		// Получите другие данные из формы
		var checkSum = $form.find('input[name="checkSum"]').val();
		var checkNumber = $form.find('input[name="checkNumber"]').val();
		var dateOfpayment = $form.find('input[name="dateOfpayment"]').val();
		var dataId = $form.find('input[name="dataId"]').val();
		var addButton = $form.find('.addSuppCheck');
		var tableCell = addButton.closest('td');
		$.ajax({
			url: '/check/suppcheckadd',
			type: 'POST',
			data: {
				providerId: providerId,
				checkSum: checkSum,
				checkNumber: checkNumber,
				dateOfpayment: dateOfpayment,
				dataId: dataId
			},
			success: function(response) {
				// Получите родительскую таблицу с индексом dataId
				var targetTable = $('.providerTable_' + $id);
				// Обработка успешного выполнения запроса
				var ididid = response.supplierId;
				// Добавление новой строки в таблицу
				var newRow = '<tr class="supplier_' + response.supplierId + '">';
				newRow += '<td class="supId" abbr="' + ididid + '">' + response.supplierName + '</td>';
				newRow += '<td class="modId" abbr="' + response.dataId + '" class="vertical-center">';
				newRow += '<a href="/check/providerdelete" data-provider-id="' + response.providerId + '" data-data-id="' + response.dataId + '"><i class="bi bi-x"></i></a>';
				newRow += '</td>';
				newRow += '</tr>';

				// Добавьте новую строку только в целевую таблицу
				targetTable.find('tbody').append(newRow);
			}
		});
	});

	// При загрузке страницы прячем формы
	document.addEventListener("DOMContentLoaded", function() {
		var forms = document.querySelectorAll(".supplierForm");
		forms.forEach(function(form) {
			form.style.display = "none";
		});
	});

	// При клике на кнопку сворачиваем/разворачиваем форму
	document.addEventListener("click", function(event) {
		if (event.target.classList.contains("toggleForm")) {
			var form = event.target.nextElementSibling;
			if (form.style.display === "none") {
				form.style.display = "block";
			} else {
				form.style.display = "none";
			}
		}
	});
</script>