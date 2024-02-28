<?php
$this->registerCssFile('https://use.fontawesome.com/releases/v5.3.1/css/all.css');
$this->registerJsFile('https://use.fontawesome.com/releases/v5.3.1/js/all.js', ['defer' => true, 'crossorigin' => 'anonymous']);
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">



<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>



<style>
	.nav>li>a:hover{
		background-color: #fff0 !important;
	    	/* Замените этот цвет на желаемый цвет подсветки */
	}
    .nav-link.show{
		color: #222529 !important;
		/* Замените этот цвет на желаемый цвет подсветки */
	}
	.nav-item>form {
		padding-top: 6px;
	}

	main>.container {
		padding: 0;
	}
</style>


<?php

use yii\grid\GridView;
use \yii\bootstrap5\LinkPager;
use yii\widgets\Pjax;
use yii\web\YiiAsset;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\daterange\DateRangePicker;
use kartik\datetime\DateTimePicker;
use yii\httpclient\debug\SearchModel;

$this->title = "Счета";
echo '<h1>Счета</h1>';
echo '<a class="btn btn-success btn-createCheck" href="/check/create">Создать счет</a>';
echo '<div id="clock"></div><br><br><br>';
?>

<style>
	.btn-createCheck {
		float: left;
	}

	#clock {
		float: right;
		font-size: 24px;
		font-weight: bold;
		padding-right: 20px;
	}

	@media screen and (max-width: 768px) {
		#clock {
			font-size: 18px;
		}
	}

	@media screen and (max-width: 480px) {
		#clock {
			font-size: 16px;
		}
	}
</style>

<script>
	function updateTime() {
		var now = new Date();
		var hours = now.getHours();
		var minutes = now.getMinutes();
		var seconds = now.getSeconds();
		var date = now.getDate();
		var month = now.getMonth() + 1;
		var year = now.getFullYear();

		// Дополнительный код, чтобы добавить ведущие нули к часам, минутам и секундам
		hours = ("0" + hours).slice(-2);
		minutes = ("0" + minutes).slice(-2);
		seconds = ("0" + seconds).slice(-2);
		date = ("0" + date).slice(-2);
		month = ("0" + month).slice(-2);

		var timeString = hours + ":" + minutes + ":" + seconds;
		var dateString = date + "-" + month + "-" + year;

		document.getElementById("clock").innerHTML = timeString + "<br>" + dateString;

		// Обновление времени каждую секунду
		setTimeout(updateTime, 1000);
	}

	// Запуск функции updateTime при загрузке страницы
	window.onload = updateTime;
</script>



<?php

Pjax::begin([
	'id' => 'my-gridview-pjax',
	'clientOptions' => [
		'skipOuterContainers' => true,
	],
]);

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel, // используйте модель фильтрации
	'pager' => ['class' => \yii\bootstrap5\LinkPager::class],
	'options' => ['style' => 'width: 100%; max-width: 100%;'],
	'tableOptions' => ['class' => 'table table-striped table-bordered'],
	'columns' => [
		[
			'attribute' => 'who',
			'headerOptions' => ['class' => 'text-center center-vertically'],
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
			'filter' => ArrayHelper::map(\app\models\Check::find()->asArray()->all(), 'payment', 'payment'),
			// 'filterOptions' => ['class' => 'form-control text-center center-vertically'],
			// 'filterInputOptions' => ['class' => 'form-control text-center center-vertically'],
			'headerOptions' => ['class' => 'text-center center-vertically'],
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
            'headerOptions' => ['class' => 'text-center center-vertically'],
            'contentOptions' => ['class' => 'text-center', 'style' => 'width: 100px;'],
            'content' => function ($data) {
                $creationDate = isset($data->date_crated) ? date('Y-m-d', $data->date_crated) : 'undefined';
                $creationTime = isset($data->date_crated) ? date('H:i', $data->date_crated) : 'undefined';

                $output = '';

                if ($data->is_deleted) {
                    $output .= '<span style="text-decoration: line-through;"><b>' . $data->numberCheck . '</b></span>';
                } else {
                    $output .= '<b>'.$data->numberCheck.'</b>';
                }

                $output .= '<br>';
                $output .= $creationDate . '<br>';
                $output .= $creationTime;

                return $output;
            },
        ],
		[
			'attribute' => 'name',
			'headerOptions' => ['class' => 'text-center center-vertically'],
			'contentOptions' => ['class' => 'nameTDClass text-center', 'style' => 'height: 40px;'],
			'content' => function ($data) use ($products) {

				$productIds = explode(',', $data->name);
				$productList = $products->find()
					->where(['id' => $productIds])
					->all();
				$table = '<table class="table tableProviders">';
				$table .= '<tbody>';
				// Устанавливаем фиксированную высоту для ячеек в таблице
				$cellStyle1 = 'style="word-break: break-all; min-width: 100px; height: 40px;"';
				$cellStyle2 = 'style="height: 40px;"';
				foreach ($productList as $product) {
					$table .= '<tr>';
					$table .= '<td ' . $cellStyle1 . '>' . $product->name . '</td>';
					$table .= '<td ' . $cellStyle2 . '>';
					// Создайте кнопку-значок вопроса с использованием Bootstrap
					$buttonId = 'questionButton_' . $data->id;
					$questionIcon = '<div style="position: relative;">' .
						'<a id="' . $buttonId . '" tabindex="0" ' .
						'class="btn btn-sm btn-secondary questionButton">' .
						'<i class="bi bi-question-square"></i>' .
						'</a>' .
						'</div>';

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
			'headerOptions' => ['class' => 'text-center center-vertically'],
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
			'headerOptions' => ['class' => 'text-center center-vertically', 'style' => 'word-break: break-all;'],
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
			'encodeLabel' => false,
			'headerOptions' => ['class' => 'text-center center-vertically', 'style' => 'word-break: break-all;'],
			'label' => 'Адрес <br> доставки',
			'contentOptions' => ['class' => 'text-center', 'style' => 'word-break: break-all; min-width: 100px;'],
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
			'encodeLabel' => false,
			'headerOptions' => ['class' => 'text-center center-vertically', 'style' => 'word-break: break-all;'],
			'label' => 'Коментарий к <br> стадии <br> доставки',
			'contentOptions' => ['class' => 'text-center', 'style' => 'word-break: break-all; min-width: 110px;'],
			'content' => function ($data) {
                if(isset($data->stage_comment) && $data->stage_comment){
                    if ($data->is_deleted) {
                        return '<span style="text-decoration: line-through;">' . $data->stage_comment . '</span>';
                    } else {
                        $block = "<div class='editTable editTable-stageComment editTable-" . $data->id . "'>" . $data->stage_comment . "</div>";
                        return $block;
                    }
                }  
                else {
                    $block = "<div class='editTable editTable-stageComment editTable-" . $data->id . "'>" . "?" . "</div>";
                    return $block;
                }
			},
		],
		[
			'attribute' => 'dateDelivery',
			'encodeLabel' => false,
			'headerOptions' => ['class' => 'text-center center-vertically', 'style' => 'word-break: break-all;'],
			'label' => 'Дата и <br> время <br> доставки',
			'contentOptions' => ['class' => 'text-center', 'style' => 'word-break: break-all; min-width: 90px;'],
			'filter' => DateTimePicker::widget([
				'bsVersion' => '5.x',
				'model' => $searchModel,
				'attribute' => 'dateDelivery',
				'language' => 'ru',
				'pluginOptions' => [
					'format' => 'dd-mm-yyyy',
					'autoclose' => true,
					'todayBtn' => true,
					'minView' => 2,
					'range' => true,
				],
			]),
			'filter' => DateRangePicker::widget([
				'bsVersion' => '5.x',
				'model' => $searchModel,
				'attribute' => 'dateDelivery',
				'convertFormat' => true,
				'language' => 'ru',
				'pluginOptions' => [
					'timePicker' => true,
					'timePickerIncrement' => 30,
					'locale' => [
						'format' => 'Y-m-d h:i'
					]
				]
			]),
			'content' => function ($data) {
				if ($data->dateDelivery) {
					if ($data->is_deleted) {
						return '<span style="text-decoration: line-through;">' . date('d-m-Y h:i', $data->dateDelivery) . '</span>';
					} else {
						$block = "<div class='editTable editTable-dateDelivery editTable-" . $data->id . "'>" . date('d-m-Y H:i', $data->dateDelivery) . "</div>";
						return $block;
					}
				} else {
                    $block = "<div class='editTable editTable-dateDelivery editTable-" . $data->id . "'>" . "?" . "</div>";
                    return $block; 
                }
			},
		],
		[
			'attribute' => 'costDelivery',
			'encodeLabel' => false,
			'headerOptions' => ['class' => 'text-center center-vertically', 'style' => 'word-break: break-all; min-width: 110px'],
			'label' => 'Стоимость <br> доставки',
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
			'filter' => ArrayHelper::map(\app\models\DeliveryType::find()->where(['is_deleted' => 0])->asArray()->all(), 'name', 'name'),
			'encodeLabel' => false,
			'headerOptions' => ['class' => 'text-center center-vertically', 'style' => 'word-break: break-all;'],
			'label' => 'Тип <br> доставки',
			'contentOptions' => function ($data) {
				$delivery_type = \app\models\DeliveryType::findOne($data->delivery_type);
				if (isset($delivery_type)) {
					return ['style' => 'background-color:' . $delivery_type->color . '!important;', 'class' => 'text-center'];
				} else return ['style' => 'background-color:' . '#fff' . '!important;', 'class' => 'text-center'];
			},
			'content' => function ($data) use ($delivery_types) {
				if ($data->delivery_type) {
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
				} else {
                    return '<span>' . "?". '</span>';
                };
			}
		],
		[
			'attribute' => 'stage',
			'headerOptions' => ['class' => 'text-center center-vertically'],
			'filter' => ArrayHelper::map($stageModel->find()->where(['is_deleted' => 0])->asArray()->all(), 'name', 'name'),

			'contentOptions' => function ($data) use ($stageModel) {
				$stage = $stageModel->findOne($data->stage);
				if ($stage) {
					return ['style' => 'background-color:' . $stage->color . '!important;'];
				}
				return [];
			},

			'content' => function ($data) use ($stageModel) {
				$stage = $stageModel->findOne($data->stage);
				if ($stage) {
					$block = '<div>';
					$block .= $stage->name;
					$block .= '</div>';

					if ($data->is_deleted) {
						return '<span style="text-decoration: line-through;">' . $block . '</span>';
					} else {
						return $block;
					}
				}
				return "";
			}
		],

		[
			'attribute' => 'provider',
			'format' => 'raw',
			'encodeLabel' => false,
			'headerOptions' => ['class' => 'text-center center-vertically'],
			'contentOptions' => ['class' => 'text-center providerTD'],
			'value' => function ($data) use ($providers, $suppliers, $suppliersData) {
			    if($data->provider){
				    $providerIds = explode(',', $data->provider);
			    }
			    else {
			        $providerIds = [];
			    }
				$table = '<table class="table tableProviders table-bordered providerTable_' . $data->id . '">';
				$table .= '<thead>';
				$table .= '<tr>';
				$table .= '<th class="center-vertically">Поставщик</th>';
				$table .= '<th style="word-break: break-all; min-width: 100px;" class="center-vertically">Номер<br>счета<br>закупки</th>';
				$table .= '<th class="center-vertically">Сумма</th>';
				$table .= '<th class="center-vertically">Дата оплаты</th>';
				$table .= '<th class="center-vertically"> <button type="button" id="addSuppl-' . $data->id . '" class="addSupp btn btn-primary toggleForm btn-sm">+</button></th>';
				$table .= '</tr>';
				$table .= '</thead>';
				$table .= '<tbody>';
				foreach ($providerIds as $providerId) {
					$provider = $providers->findOne($providerId);
					if ($provider) {
						$table .= '<tr class="supplier_' . $providerId . '">';
						$supplier = $suppliers->findOne($provider->suppliers);
						$table .= '<td class="center-vertically editTable editTable-SuppCheck supId supplier_' . $providerId . '" abbr="' . $providerId . '" checkid=' . $data->id . '>' . $supplier->company_name . '</td>';
						$table .= '<td class="center-vertically editTable editTable-SuppCheck supId supplier_' . $providerId . '" abbr="' . $providerId . '" checkid=' . $data->id . '>' . $provider->checkNumber . '</td>';
						$table .= '<td class="center-vertically editTable editTable-SuppCheck supId supplier_' . $providerId . '" abbr="' . $providerId . '" checkid=' . $data->id . '>' . $provider->checkSum . '</td>';
						if ($provider->dateOfpayment) {
							$table .= '<td style="word-break: break-all; min-width: 100px;" class="center-vertically editTable editTable-SuppCheck supId supplier_' . $providerId . '" abbr="' . $providerId . '" checkid=' . $data->id . '>' . date('d-m-Y H:i', $provider->dateOfpayment) . '</td>';
						} else {
							$table .= '<td style="word-break: break-all; min-width: 100px;" class="center-vertically editTable editTable-SuppCheck supId supplier_' . $providerId . '" abbr="' . $providerId . '" checkid=' . $data->id . '></td>';
						}
						$table .= '<td style="display:none;" class="center-vertically modId supplier_' . $providerId . '" abbr="' . $data->id . '" class="vertical-center"></td>';
						//$table .= '<td><a class="info-suppl supplier_' . $providerId . '" href="" data-provider-id="' . $providerId . '" data-data-id="' . $data->id . '"><i class="bi bi-patch-question-fill"></i></a></td>';
						$table .= '<td class="center-vertically"><a class="center-vertically dell-suppl supplier_' . $providerId . '" href="/check/providerdelete" data-provider-id="' . $providerId . '" data-data-id="' . $data->id . '"><i class="bi bi-x"></i></a></td>';
						//$table .= '</td>';
						$table .= '</tr>';
					}
				}
				$table .= '</tbody>';
				$table .= '</table>';
				$table .= '<br>';
				//$table .= '<button type="button" id="addSuppl-' . $data->id . '" class="addSupp btn btn-primary toggleForm btn-sm">+</button>';
			    
			    if(isset($table)){
				if ($data->is_deleted) {
					return '<span style="text-decoration: line-through;">' . $table . '</span>';
				} else {
					return $table;
				}
			        
			    }
			    else {
			       return '?'; 
			    }
			},
		],
		[
			'attribute' => 'profit',
			'headerOptions' => ['class' => 'text-center center-vertically'],
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
			'encodeLabel' => false,
			'headerOptions' => ['class' => 'text-center center-vertically', 'style' => 'word-break: break-all;'],
			'label' => 'Коментарий',
			'contentOptions' => ['class' => 'text-center', 'style' => 'word-break: break-all; min-width: 110px;'],
			'content' => function ($data) {
                if($data->comment ) {
                    if ($data->is_deleted) {
                        return '<span style="text-decoration: line-through;">' . $data->comment . '</span>';
                    } else {
                        $block = "<div class='editTable editTable-comment editTable-" . $data->id . "'>" . $data->comment . "</div>";
                        return $block;
                    }
                }
                else {
                    $block = "<div class='editTable editTable-comment editTable-" . $data->id . "'>" . "?" . "</div>";
                    return $block;
                }
			},
		],
		['class' => 'yii\grid\ActionColumn'],
	],
]);
Pjax::end();
?>

<style>
	.center-vertically {
		vertical-align: middle;
	}


	.container {
		margin: 0;
		padding-left: 10px !important;
		padding-right: 0 !important;
		/* overflow-x: auto; */
		width: 100% !important;
		max-width: 100% !important;
	}

	a {
		text-decoration: none;
	}

	.providerTD {
		padding: 0 !important;

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

	.supplierForm {
		background-color: rgb(147 147 147);
		/* Задаем полупрозрачный цвет фона */
		border: none;
		/* Убираем границу */
		border-radius: 5px;
		/* Добавляем закругление углов */
		padding: 10px;
		/* Добавляем отступы */
		width: 200px;
		/* Устанавливаем ширину формы */
		font-size: 16px;
		/* Задаем размер шрифта */
		color: #333;
		/* Задаем цвет текста */
	}

	.supplierFormEdit {
		background-color: rgb(147 147 147);
		/* Задаем полупрозрачный цвет фона */
		border: none;
		/* Убираем границу */
		border-radius: 5px;
		/* Добавляем закругление углов */
		padding: 10px;
		/* Добавляем отступы */
		width: 200px;
		/* Устанавливаем ширину формы */
		font-size: 16px;
		/* Задаем размер шрифта */
		color: #fff;
		/* Задаем цвет текста */
	}

	.supplierFormEdit option {
		background-color: rgba(255, 255, 255, 0.8);
		/* Задаем цвет фона опций с полупрозрачностью */
		color: #fff;
		/* Задаем цвет текста опций */
	}

	.supplierForm option {
		background-color: rgba(255, 255, 255, 0.8);
		/* Задаем цвет фона опций с полупрозрачностью */
		color: #333;
		/* Задаем цвет текста опций */
	}
</style>
<script>
	$(document).ready(function() {

		var hiddenBlocksData = <?= json_encode($hiddenBlocksData) ?>;
		setInterval(function() {
            $('.daterangepicker').hide();
			$.pjax.reload({
				container: '#my-gridview-pjax'
			});
		}, 60000); // Обновление каждую минуту (60000 миллисекунд)




		$(document).on('click', '.questionButton', function() {
			var buttonId = $(this).attr('id');
			var hiddenBlockId = 'hiddenBlock-' + buttonId;
			var buttonPosition = $(this).offset();

			if ($('#' + hiddenBlockId).length) {
				$('#' + hiddenBlockId).remove();
			} else {
				var hiddenBlockData = <?= json_encode($hiddenBlocksData) ?>;
				console.log(hiddenBlockData);
				console.log(buttonId);
				var numbers = buttonId.split('_')[1];
				var hiddenBlockHtml = '<div id="' + hiddenBlockId + '" style="width: 210px; position: absolute;" class="hiddenBlock">' +
					'<div class="card">' +
					'<div class="card-header text-center">Детали товара</div>' +
					'<div class="card-body">' +
					'<div><strong>Количество:</strong> ' + hiddenBlockData[numbers].quantity + '</div>' +
					'<div><strong>Наименование:</strong> ' + hiddenBlockData[numbers].productName + '</div>' +
					'<div><strong>Цена за ед.:</strong> ' + hiddenBlockData[numbers].price + '</div>' +
					'<div><strong>Общая цена:</strong> ' + hiddenBlockData[numbers].totalPrice + '</div>' +
					'</div>' +
					'</div>' +
					'</div>';

				$('body').append(hiddenBlockHtml);
				var hiddenBlock = $('#' + hiddenBlockId);
				var buttonWidth = $(this).outerWidth();
				hiddenBlock.css({
					top: buttonPosition.top,
					left: buttonPosition.left + buttonWidth + 10 // добавляем 10 пикселей для отступа
				});
			}
		});




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
				// $('.questionButton').popover('hide');
			}
		});
	});


	$('body').on('click', '.providerTD a', function(e) {
		if ($(this).hasClass("dell-suppl")) {
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
					$.pjax.reload({
						container: '#my-gridview-pjax'
					});
					//$('.supplier_' + supplierId).remove();
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.error('AJAX Error: ' + textStatus);
				}
			});
		} else {
			$buttonPosition = $(this).offset();
			$buttonWidth = $(this).outerWidth();
			e.preventDefault();
			var buttonIdSupp = $(this).attr('class');
			var hiddenBlockIdSupp = buttonIdSupp.split(' ')[1].split('_')[1];
			// Нужно получить:
			// номер счета закупки
			// сумма счет

			$.ajax({
				url: '/check/getsuppcheck',
				type: 'POST',
				data: {
					supplierId: hiddenBlockIdSupp
				},
				success: function(response) {
					var checkNumber = response.checkNumber;
					var checkAmount = response.checkAmount;
					var paymentDate = response.paymentDate;
					//console.log(response);

					var paymentDate = new Date(parseInt(paymentDate) * 1000);
					var formattedDate = paymentDate.toLocaleString('ru-RU', {
						timeZone: 'UTC',
						hour12: false
					});
					// $paymentDate = date('d-m-Y H:i', strtotime(paymentDate));

					var hiddenBlockHtmlSuppl = '<div id="sup' + hiddenBlockIdSupp + '" style="width: 210px; position: absolute;" class="hiddenBlock">' +
						'<div class="card">' +
						'<div class="card-header text-center">Детали поставщика</div>' +
						'<div class="card-body">' +
						'<div><strong>№ Счета:</strong> ' + checkNumber + '</div>' +
						'<div><strong>Сумма счета:</strong> ' + checkAmount + '</div>' +
						'<div><strong>Дата оплаты.:</strong> ' + formattedDate + '</div>' +
						'</div>' +
						'</div>' +
						'</div>';


					if ($('#sup' + hiddenBlockIdSupp).length) {
						$('#sup' + hiddenBlockIdSupp).remove();
					} else {
						$hiddenBlock = $('#' + hiddenBlockIdSupp);
						$('body').append(hiddenBlockHtmlSuppl);
						$('#sup' + hiddenBlockIdSupp).css({
							top: $buttonPosition.top,
							left: $buttonPosition.left + $buttonWidth + 10 // добавляем 10 пикселей для отступа
						});
					}
				},
				error: function(xhr, status, error) {
					console.error(error);
				}
			});

			// var hiddenBlockHtmlSupp =
			'<div id="' + hiddenBlockIdSupp + '" style="width: 210px; position: absolute;" class="hiddenBlock">' +
				'<div class="card">' +

				'</div>' +
				'</div>';

		}
	});

	$('body').on('click', '.addSupp', function(e) {
		e.preventDefault();
		var buttonPosition = $(this).offset();
		$dataId = $(this).attr('id').split('-')[1];
		//console.log($dataId);
		$form = '<form class="text-center supplierForm supplierForm-' + $dataId + '" style="width: 210px; position: absolute;">';
		$form += '<div class="form-group">';
		$form += '<select class="form-control supplierSelect" name="providerId-' + $dataId + '">';
		$.ajax({
			url: '/check/get-supplier-options',
			type: 'GET',
			dataType: 'json',
			success: function(response) {
				var options = response;
				$.each(options, function(index, option) {
					$option = $('<option>').val(option.value).text(option.text);
					$('.supplierSelect').append($option);
				});
			},
			error: function(xhr, status, error) {
				console.error(error);
			}
		});

		var dateTimePickerHtml = '<div class="input-group date" id="checkDateContainer">';
		dateTimePickerHtml += '<input type="text" class="form-control" id="checkDate" name="dateOfpayment" required>';
		dateTimePickerHtml += '</div>';

		$form += '</select>';
		$form += '</div>';
		$form += '<div class="form-group">';
		$form += '<input type="text" class="form-control" name="checkNumber" placeholder="Номер счета закупки">';
		$form += '</div>';
		$form += '<div class="form-group">';
		$form += '<input type="text" class="form-control" name="checkSum" placeholder="Сумма счета">';
		$form += '</div>';
		$form += '<div class="form-group">';
		//$form += '<input type="text" class="form-control" name="dateOfpayment" placeholder="Дата оплаты">';
		$form += dateTimePickerHtml; // Заменить поле на виджет DateTimePicker
		$form += '</div>';
		$form += '<input type="hidden" name="dataId" value="' + $dataId + '">';
		$form += '<button type="submit" data=' + $dataId + ' class="btn btn-primary addSuppCheck">Добавить</button>';
		$form += '</form>';
		if ($('.supplierForm-' + $dataId).length) {
			$('.supplierForm-' + $dataId).remove();
		} else {
			$('body').append($form);

			var hiddenBlock = $('.supplierForm-' + $dataId);
			hiddenBlock.find('#checkDate').datetimepicker({
				bsVersion: '5',
				format: 'dd-mm-yyyy hh:ii', // Формат даты без букв
				// buttons: {
				// 	showToday: true,
				// },
				locale: 'ru', // Указываем локаль для корректного отображения месяцев на русском языке (если необходимо)
				//widgetParent: '#checkDateContainer', // Указываем родительский элемент виджета
				keepOpen: false, // Установите значение false для автоматического закрытия виджета
			}).on('change.datetimepicker', function() {
				$(this).datetimepicker('hide'); // Закрытие виджета после изменения значения
			});
			var buttonWidth = $(this).outerWidth();
			hiddenBlock.css({
				top: buttonPosition.top,
				left: buttonPosition.left + buttonWidth + 10 // добавляем 10 пикселей для отступа
			});
		}
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
		//var dateOfpayment = $form.find('#checkDate').datetimepicker('date');
		console.log(dateOfpayment);
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
				if ($('.supplierForm-' + dataId).length) {

					$('.supplierForm-' + dataId).remove();
				}
				$.pjax.reload({
					container: '#my-gridview-pjax'
				});
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
</script>

<?php $this->registerJsFile('@web/js/check/editTable.js', ['depends' => [YiiAsset::class]]); ?>