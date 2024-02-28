<?php

use miloschuman\highcharts\Highstock;
use yii\web\JsExpression;
use miloschuman\highcharts\SeriesDataHelper;
use yii\widgets\ActiveForm;
use miloschuman\highcharts\Highcharts;
use yii\helpers\ArrayHelper;
use app\models\Stage;
use yii\helpers\Html;

$this->title = 'График';
$this->params['breadcrumbs'][] = $this->title;
?>


<style>
	.card {
		background-color: #f8f9fa;
		/* Нейтральный фон */
		margin-bottom: 10px;
		/* Увеличение расстояния между карточками */
	}

	.btn-form {
		margin-top: 20px;
	}
</style>

<div class="card">
	<div class="card-body">
		<div class="chart-container">
			<?php
			echo Highstock::widget([
				'scripts' => [
					'modules/exporting',
					//'themes/grid-light',
				],
				'setupOptions' => [
					'lang' => [
						'loading' => 'Загрузка...',
						'months' => ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
						'weekdays' => ["Воскресенье", "Понедельник", 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
						'shortMonths' => ['Янв', 'Фев', 'Март', 'Апр', 'Май', 'Июнь', 'Июль', 'Авг', 'Сент', 'Окт', 'Нояб', 'Дек'],
						'exportButtonTitle' => "Экспорт",
						'printButtonTitle' => "Печать",
						'rangeSelectorFrom' => 'С',
						'rangeSelectorTo' => "По",
						'rangeSelectorZoom' => "Период",
						'downloadPNG' => 'Скачать PNG',
						'downloadJPEG' => 'Скачать JPEG',
						'downloadPDF' => 'Скачать PDF',
						'downloadSVG' => 'Скачать SVG',
						'printChart' => 'Напечатать график'
					],
				],
				'options' => [
					'credits' => ['enabled' => false],
					'title' => ['text' => 'Прибыль'],
					'chart' => ['height' => '500px'],

					'rangeSelector' => [
						'selected' => 1,
						'buttons' => [[
							'type' => 'month',
							'count' => 1,
							'text' => '1мес'
						], [
							'type' => 'month',
							'count' => 3,
							'text' => '3мес'
						], [
							'type' => 'month',
							'count' => 6,
							'text' => '6мес'
						], [
							'type' => 'ytd',
							'text' => 'YTD'
						], [
							'type' => 'year',
							'count' => 1,
							'text' => '1год'
						], [
							'type' => 'all',
							'text' => 'Всё'
						]]
					],


					'xAxis' => [
						'type' => 'datetime', // Установим тип оси x как дата/время
						'title' => ['text' => 'Дата'],
						'dateTimeLabelFormats' => [
							'day' => '%e. %b', // Формат меток для дней
							'month' => '%b', // Формат меток для месяце
						],
					],
					'yAxis' => [
						[
							'labels' => [
								'align' => 'right',
							],
							'title' => [
								'text' => '',
								'margin' => 20,
							],
							'height' => '75%',
							'lineWidth' => '1',
							'resize' => [
								'enabled' => true
							]
						],
						[
							'labels' => [
								'align' => 'right',
								'x' => '-3'
							],
							'title' => [
								'text' => '',
								'margin' => 20,
							],
							'top' => '77%',
							'height' => '25%',
							'offset' => 0,
							'lineWidth' => '1',
							'reversed' => true,
							'showFirstLabel' => false,
							'showLastLabel' => true
						],
					],
					'tooltip' => ['split' => true],
					'series' => [
						[
							'type' => 'areaspline',
							'name' => 'Прибыль',
							'data' => new SeriesDataHelper($dataProvider, ['dateDelivery:timestamp', 'profit:float']),
							'yAxis' => 0,
							'marker' => [
								'enabled' => true,
								'radius' => 3,
								'states' => ['hover' => ['radius' => 2]],
							],
							'dataLabels' => [
								'enabled' => true,
							],
						],
					],
				],
			]); ?>
		</div>
		<div class="statistic-container">
			<?php $form = ActiveForm::begin(['method' => 'get']);
			$stageModel = new Stage();
			?>
			<div class="form-group row align-items-center">
				<div class="col-3">
					<label for="start-date">Начальная дата</label>
					<input type="date" id="start-date" class="form-control" name="startDate" value="<?= date('Y-m-d', $startDate) ?>">
				</div>
				<div class="col-3">
					<label for="end-date">Конечная дата</label>
					<input type="date" id="end-date" class="form-control" name="endDate" value="<?= date('Y-m-d', $endDate) ?>">
				</div>
				<div class="col-3">
					<label for="delivery-stage">Стадия доставки</label>
					<?= Html::dropDownList(
						'stage1',
						null,
						ArrayHelper::map($stageModel->find()->where(['is_deleted' => 0])->asArray()->all(), 'name', 'name'),
						[
							'prompt' => $stage1,
							'class' => 'form-control',
							'value' => $stage1, // Указываем значение переменной $stage1
						]
					) ?>
				</div>

				<div class="col-3">
					<button type="submit" class="btn btn-primary btn-form">Применить</button>
				</div>
				<?php ActiveForm::end() ?>
				<p class="total-profit">Суммарная прибыль: <?= $totalProfit ?></p>
			</div>
		</div>
	</div>
</div>
<!-- колличество заказов -->
<!-- сумма заказов -->
<div class="card">
	<div class="card-body">
		<div class="chart-container">
			<?php
			echo Highstock::widget([

				'options' => [
					'title' => ['text' => 'Колличество'],
					'rangeSelector' => [
						'selected' => 1,
						'buttons' => [[
							'type' => 'month',
							'count' => 1,
							'text' => '1мес'
						], [
							'type' => 'month',
							'count' => 3,
							'text' => '3мес'
						], [
							'type' => 'month',
							'count' => 6,
							'text' => '6мес'
						], [
							'type' => 'ytd',
							'text' => 'YTD'
						], [
							'type' => 'year',
							'count' => 1,
							'text' => '1год'
						], [
							'type' => 'all',
							'text' => 'Всё'
						]]
					],
					'yAxis' => [
						['title' => ['text' => 'Колличество'], 'height' => '60%'],
					],
					'series' => [
						[
							'type' => 'areaspline',
							'name' => 'Количество счетов',
							'data' => new SeriesDataHelper($seriesData, ['0:timestamp', '1:int']),
							'yAxis' => 0,
						],
					]
				]
			]);
			?>
		</div>
		<div class="statistic-container">
			<?php $form = ActiveForm::begin(['method' => 'get']) ?>
			<div class="form-group row align-items-center">
				<div class="col-3">
					<label for="start-date">Начальная дата</label>
					<input type="date" id="start-date" class="form-control" name="startDate2" value="<?= date('Y-m-d', $startDate2) ?>">
				</div>
				<div class="col-3">
					<label for="end-date">Конечная дата</label>
					<input type="date" id="end-date" class="form-control" name="endDate2" value="<?= date('Y-m-d', $endDate2) ?>">
				</div>
				<div class="col-3">
					<label for="delivery-stage">Стадия доставки</label>
					<?= Html::dropDownList(
						'stage2',
						null,
						ArrayHelper::map($stageModel->find()->where(['is_deleted' => 0])->asArray()->all(), 'name', 'name'),
						[
							'prompt' => $stage2,
							'class' => 'form-control',
							'value' => $stage2, // Указываем значение переменной $stage1
						]
					) ?>
				</div>
				<div class="col-3">
					<button type="submit" class="btn btn-primary btn-form">Применить</button>
				</div>
				<?php ActiveForm::end() ?>
				<p class="total-profit">Колличество заказов за выбранный период: <?= $totalCount ?></p>
			</div>
		</div>
	</div>
</div>

<!-- колличество заказов -->
<!-- сумма заказов -->
<div class="card">
	<div class="card-body">
		<div class="chart-container">
			<?php
			echo Highstock::widget([

				'options' => [
					'title' => ['text' => 'Сумма'],
					'rangeSelector' => [
						'selected' => 1,
						'buttons' => [[
							'type' => 'month',
							'count' => 1,
							'text' => '1мес'
						], [
							'type' => 'month',
							'count' => 3,
							'text' => '3мес'
						], [
							'type' => 'month',
							'count' => 6,
							'text' => '6мес'
						], [
							'type' => 'ytd',
							'text' => 'YTD'
						], [
							'type' => 'year',
							'count' => 1,
							'text' => '1год'
						], [
							'type' => 'all',
							'text' => 'Всё'
						]]
					],
					'yAxis' => [
						['title' => ['text' => 'Сумма'], 'height' => '100%'],
					],
					'series' => [
						[
							'type' => 'areaspline',
							'name' => 'Сумма',
							'data' => new SeriesDataHelper($summDate, ['0:timestamp', '1:int']),
							'yAxis' => 0,
						],
					]
				]
			]);
			?>
		</div>
		<div class="statistic-container">
			<?php $form = ActiveForm::begin(['method' => 'get']) ?>
			<div class="form-group row align-items-center">
				<div class="col-3">
					<label for="start-date">Начальная дата</label>
					<input type="date" id="start-date" class="form-control" name="startDate3" value="<?= date('Y-m-d', $startDate3) ?>">
				</div>
				<div class="col-3">
					<label for="end-date">Конечная дата</label>
					<input type="date" id="end-date" class="form-control" name="endDate3" value="<?= date('Y-m-d', $endDate3) ?>">
				</div>
				<div class="col-3">
					<label for="delivery-stage">Стадия доставки</label>
					<?= Html::dropDownList(
						'stage3',
						null,
						ArrayHelper::map($stageModel->find()->where(['is_deleted' => 0])->asArray()->all(), 'name', 'name'),
						[
							'prompt' => $stage3,
							'class' => 'form-control',
							'value' => $stage3, // Указываем значение переменной $stage1
						]
					) ?>
				</div>
				<div class="col-3">
					<button type="submit" class="btn btn-primary btn-form">Применить</button>
				</div>
				<?php ActiveForm::end() ?>
				<p class="total-profit">Сумма заказов за выбранный период: <?= $totalSumm ?></p>
			</div>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<!-- Сумма счетов поставок -->
		<div class="chart-container">
			<?php
			echo Highstock::widget([

				'options' => [
					'title' => ['text' => 'Сумма закупок'],
					'rangeSelector' => [
						'selected' => 1,
						'buttons' => [[
							'type' => 'month',
							'count' => 1,
							'text' => '1мес'
						], [
							'type' => 'month',
							'count' => 3,
							'text' => '3мес'
						], [
							'type' => 'month',
							'count' => 6,
							'text' => '6мес'
						], [
							'type' => 'ytd',
							'text' => 'YTD'
						], [
							'type' => 'year',
							'count' => 1,
							'text' => '1год'
						], [
							'type' => 'all',
							'text' => 'Всё'
						]]
					],
					'yAxis' => [
						['title' => ['text' => 'Сумма'], 'height' => '100%'],
					],
					'series' => [
						[
							'type' => 'areaspline',
							'name' => 'Сумма',
							'data' => new SeriesDataHelper($summDateCheck, ['0:timestamp', '1:int']),
							'yAxis' => 0,
						],
					]
				]
			]);
			?>
		</div>
		<div class="statistic-container">
			<?php $form = ActiveForm::begin(['method' => 'get']) ?>
			<div class="form-group row align-items-center">
				<div class="col-3">
					<label for="start-date">Начальная дата</label>
					<input type="date" id="start-date" class="form-control" name="startDate4" value="<?= date('Y-m-d', $startDate4) ?>">
				</div>
				<div class="col-3">
					<label for="end-date">Конечная дата</label>
					<input type="date" id="end-date" class="form-control" name="endDate4" value="<?= date('Y-m-d', $endDate4) ?>">
				</div>
				<div class="col-3">
					<label for="delivery-stage">Стадия доставки</label>
					<?= Html::dropDownList(
						'stage4',
						null,
						ArrayHelper::map($stageModel->find()->where(['is_deleted' => 0])->asArray()->all(), 'name', 'name'),
						[
							'prompt' => $stage4,
							'class' => 'form-control',
							'value' => $stage4, // Указываем значение переменной $stage1
						]
					) ?>
				</div>
				<div class="col-3">
					<button type="submit" class="btn btn-primary btn-form">Применить</button>
				</div>
				<?php ActiveForm::end() ?>
				<p class="total-profit">Сумма закупки за выбранный период: <?= $totalSummCheck ?></p>
			</div>
		</div>
	</div>
</div>