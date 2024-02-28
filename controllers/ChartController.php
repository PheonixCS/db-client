<?php

namespace app\controllers;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use app\models\Check;
use app\models\suppCheck;
use app\models\Stage;
use Yii;

class ChartController extends Controller
{
	public $startDate;
	public $endDate;

	public $startDate2;
	public $endDate2;

	public $startDate3;
	public $endDate3;

	// сумма закупки
	public $startDate4;
	public $endDate4;

	public function actionIndex()
	{

		$this->startDate = strtotime(date_default_timezone_get()) - 2678400;
		$this->endDate = strtotime(date_default_timezone_get()) + 2678400;
		$this->startDate2 = strtotime(date_default_timezone_get()) - 2678400;
		$this->endDate2 = strtotime(date_default_timezone_get()) + 2678400;
		$this->startDate3 = strtotime(date_default_timezone_get()) - 2678400;
		$this->endDate3 = strtotime(date_default_timezone_get()) + 2678400;
		$this->startDate4 = strtotime(date_default_timezone_get()) - 2678400;
		$this->endDate4 = strtotime(date_default_timezone_get()) + 2678400;
		if (Yii::$app->request->get('startDate') != NULL) {
			$request = Yii::$app->request;
			$this->startDate = strtotime($request->get('startDate')) ?: strtotime('-1 month'); // Если startDate отсутствует в запросе, устанавливаем значение по умолчанию
			$this->endDate = strtotime($request->get('endDate')) ?: strtotime('+1 month'); // Если endDate отсутствует в запросе, устанавливаем значение по умолчанию
		}
		if (Yii::$app->request->get('startDate2') != NULL) {
		    $request = Yii::$app->request;
			$this->startDate2 = strtotime($request->get('startDate2')) ?: strtotime('-1 month'); // Если startDate отсутствует в запросе, устанавливаем значение по умолчанию
			$this->endDate2 = strtotime($request->get('endDate2')) ?: strtotime('+1 month'); // Если endDate отсутствует в запросе, устанавливаем значение по умолчанию
        }
        if (Yii::$app->request->get('startDate3') != NULL) {
            $request = Yii::$app->request;
			$this->startDate3 = strtotime($request->get('startDate3')) ?: strtotime('-1 month'); // Если startDate отсутствует в запросе, устанавливаем значение по умолчанию
			$this->endDate3 = strtotime($request->get('endDate3')) ?: strtotime('+1 month'); // Если endDate отсутствует в запросе, устанавливаем значение по умолчанию
        }
        if (Yii::$app->request->get('startDate4') != NULL) {
            $request = Yii::$app->request;
			$this->startDate4 = strtotime($request->get('startDate4')) ?: strtotime(date('Y-m-d', strtotime('-1 month'))); // Если startDate отсутствует в запросе, устанавливаем значение по умолчанию
			$this->endDate4 = strtotime($request->get('endDate4')) ?: strtotime(date('Y-m-d', strtotime('+1 month'))); // Если endDate отсутствует в запросе, устанавливаем значение по умолчанию
		}



		$query = Check::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => [
				'defaultOrder' => [
					'dateDelivery' => SORT_ASC,
				],
			],
			'pagination' => false,
		]);
		// Формируем запрос для выборки объектов модели в заданном диапазоне дат
		$query->andFilterWhere(['>=', 'dateDelivery', $this->startDate])
			->andFilterWhere(['<', 'dateDelivery', $this->endDate]);
		// Фильтр для поля stage
		$stage1 = Yii::$app->request->get('stage1');
		if ($stage1) {
			$stageIds = Stage::find()->select('id')->where(['like', 'name', '%' . $stage1 . '%', false])
				->column();
			$conditions = [];
			foreach ($stageIds as $stageId) {
				$conditions[] = 'stage = "' . $stageId . '"';
			}
			if ($conditions == []) {
				$query->andWhere('1=0');
			} else {
				$query->andWhere(implode(' OR ', $conditions));
			}
		} else {
			$stage1 = "Выберите стадию доставки";
		}
		// Вычисляем суммарную прибыль для выбранных объектов модели
		$totalProfit = $query->sum('profit') - $query->sum('costDelivery');
		// даты для профита
		$startDate = $this->startDate;
		$endDate = $this->endDate;


		//////////////////////////////////////////////////////////////////////////////////////////////////
		$seriesData = [];
		// Получаем количество элементов от dataStart до каждой даты на оси X
		$currentDate = $this->startDate2;
		$totalCount = 0;
		while ($currentDate <= $this->endDate2) {
			$query2 = Check::find();
			$query2->andFilterWhere(['>=', 'dateDelivery', $currentDate])->andFilterWhere(['<', 'dateDelivery', $currentDate + 86400]);
			// Фильтр для поля stage
			$stage2 = Yii::$app->request->get('stage2');
			if ($stage2) {
				$stageIds = Stage::find()->select('id')->where(['like', 'name', '%' . $stage2 . '%', false])
					->column();
				$conditions = [];
				foreach ($stageIds as $stageId) {
					$conditions[] = 'stage = "' . $stageId . '"';
				}
				if ($conditions == []) {
					$query2->andWhere('1=0');
				} else {
					$query2->andWhere(implode(' OR ', $conditions));
				}
			} else {
				$stage2 = "Выберите стадию доставки";
			}
			$count = $query2->count();
			$seriesData[] = [$currentDate, $count];

			$currentDate = $currentDate + 86400;
		}
		// даты для колличества счетов
		$startDate2 = $this->startDate2;
		$endDate2 = $this->endDate2;
		$query3 = Check::find();
		$query3->andFilterWhere(['>=', 'dateDelivery', $startDate2])->andFilterWhere(['<', 'dateDelivery', $endDate2  + 86400]);
		if (isset($stage2)) {
			$stageIds = Stage::find()->select('id')->where(['like', 'name', '%' . $stage2 . '%', false])
				->column();
			$conditions = [];
			foreach ($stageIds as $stageId) {
				$conditions[] = 'stage = "' . $stageId . '"';
			}
			if ($conditions == []) {
				$query3->andWhere('1=0');
			} else {
				$query3->andWhere(implode(' OR ', $conditions));
			}
		} else {
            $query3->andWhere('1=1'); // условие для отключения фильтрации по стадии
		}
		$totalCount  = $query3->count();

        if ($totalCount == 0) {
            // Делаем новый запрос на количество заказов без фильтрации по стадии
            $query3 = Check::find()->andFilterWhere(['>=', 'dateDelivery', $startDate2])->andFilterWhere(['<', 'dateDelivery', $endDate2  + 86400]); 
            $newTotalCount = $query3->count();

            // Присваиваем результат нового запроса
            $totalCount = $newTotalCount;
        }
		//////////////////////////////////////////////////////////////////////////////////////////////////



		//////////////////////////////////////////////////////////////////////////////////////////////////
		// Получаем сумму заказов от dataStart до каждой даты на оси X
		$summDate = [];
		$totalSumm = 0;
		$currentDate = $this->startDate3;
		while ($currentDate <= $this->endDate3) {
			$query2 = Check::find();

			// Фильтр для поля stage
			$stage3 = Yii::$app->request->get('stage3');
			if ($stage3) {
				$stageIds = Stage::find()->select('id')->where(['like', 'name', '%' . $stage3 . '%', false])
					->column();
				$conditions = [];
				foreach ($stageIds as $stageId) {
					$conditions[] = 'stage = "' . $stageId . '"';
				}
				if ($conditions == []) {
					$query2->andWhere('1=0');
				} else {
					$query2->andWhere(implode(' OR ', $conditions));
				}
			} else {
				$stage3 = "Выберите стадию доставки";
			}
			$count = $query2->andFilterWhere(['>=', 'dateDelivery', $currentDate])->andFilterWhere(['<', 'dateDelivery', $currentDate + 86400])->sum('sum');

			$summDate[] = [$currentDate, $count];

			$currentDate = $currentDate + 86400;
		}
		$startDate3 = $this->startDate3;
		$endDate3 = $this->endDate3;
		$query3 = Check::find();
		if ($stage3) {
			$stageIds = Stage::find()->select('id')->where(['like', 'name', '%' . $stage3 . '%', false])
				->column();
			$conditions = [];
			foreach ($stageIds as $stageId) {
				$conditions[] = 'stage = "' . $stageId . '"';
			}
			if ($conditions == []) {
				$query3->andWhere('1=0');
			} else {
				$query3->andWhere(implode(' OR ', $conditions));
			}
		} else {
			$stage3 = "Выберите стадию доставки";
		}
		$totalSumm  = $query3->andFilterWhere(['>=', 'dateDelivery', $startDate3])->andFilterWhere(['<', 'dateDelivery', $endDate3  + 86400])->sum('sum');
		
        if ($totalSumm == 0) {
            // Делаем новый запрос на сумму заказов без фильтрации по стадии
            $query3 = Check::find()->andFilterWhere(['>=', 'dateDelivery', $startDate3])->andFilterWhere(['<', 'dateDelivery', $endDate3  + 86400]);
            $newTotalSumm = $query3->sum('sum');

            // Присваиваем результат нового запроса
            $totalSumm = $newTotalSumm;
        }
        //////////////////////////////////////////////////////////////////////////////////////////////////


		//////////////////////////////////////////////////////////////////////////////////////////////////
		// Получаем сумму заказов от dataStart до каждой даты на оси X
		$summDateCheck = [];
		$totalSummCheck = 0;
		$currentDate = $this->startDate4;

		while ($currentDate <= $this->endDate4) {
			$checkSums = 0;
			$query4 = Check::find();
			$currentDate = $currentDate + 86400;
			// Фильтр для поля stage
			$stage4 = Yii::$app->request->get('stage4');
			if ($stage4) {
				$stageIds = Stage::find()->select('id')->where(['like', 'name', '%' . $stage4 . '%', false])
					->column();
				$conditions = [];
				foreach ($stageIds as $stageId) {
					$conditions[] = 'stage = "' . $stageId . '"';
				}
				if ($conditions == []) {
					$query4->andWhere('1=0');
				} else {
					$query4->andWhere(implode(' OR ', $conditions));
				}
			} else {
				$stage4 = "Выберите стадию доставки";
			}
			$checks = $query4->andFilterWhere(['>=', 'dateDelivery', $currentDate])->andFilterWhere(['<', 'dateDelivery', $currentDate + 86400])->all();
			foreach ($checks as $check) {
			    if( $check->provider){
				    $providerIds = explode(',', $check->provider);
			    }
			    else {
			        $providerIds = [];
			    }
				$suppChecks = suppCheck::find()->where(['IN', 'id', $providerIds])->all();
				foreach ($suppChecks as $suppCheck) {
					$checkSums += $suppCheck->checkSum;
				}
			}
			$summDateCheck[] = [$currentDate, $checkSums];
			$totalSummCheck += $checkSums;
			//$currentDate = $currentDate + 86400;
		}
		$startDate4 = $this->startDate4;
		$endDate4 = $this->endDate4;
		//////////////////////////////////////////////////////////////////////////////////////////////////


		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'totalProfit' => $totalProfit,
			'startDate' => $startDate,
			'endDate' => $endDate,
			'stage1' => $stage1,

			'totalCount' => $totalCount,
			'startDate2' => $startDate2,
			'endDate2' => $endDate2,
			'seriesData' => $seriesData,
			'stage2' => $stage2,

			'totalSumm' => $totalSumm,
			'startDate3' => $startDate3,
			'endDate3' => $endDate3,
			'summDate' => $summDate,
			'stage3' => $stage3,

			'totalSummCheck' => $totalSummCheck,
			'startDate4' => $startDate4,
			'endDate4' => $endDate4,
			'summDateCheck' => $summDateCheck,
			'stage4' => $stage4,
		]);
	}
}