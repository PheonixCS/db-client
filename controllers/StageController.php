<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;
use app\models\Stage;
use app\models\StageSearch;
use yii\web\ForbiddenHttpException;

class StageController extends Controller
{
	public function beforeAction($action)
	{
		$allowedActions = ['create', 'delete', 'update', 'view', 'index',];

		if (in_array($action->id, $allowedActions)) {
			if (Yii::$app->user->isGuest) {
				throw new ForbiddenHttpException('You are not allowed to access this page.'); // ошибка доступа
			}
		}

		return parent::beforeAction($action);
	}
	// ...

	public function actionIndex()
	{
		$searchModel = new StageSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['is_deleted' => 0]);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionCreate()
	{
		$model = new Stage();

		if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
			return $this->redirect(['index']);
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

	public function actionDelete($id)
	{
		$stageObj = $this->findModel($id);
		$stageObj->is_deleted = 1;
		$stageObj->save();
		return $this->redirect(['index']);
	}
	public function actionView($id)
	{
		$model = Stage::findOne($id);

		return $this->render('view', [
			'model' => $model,
		]);
	}

	protected function findModel($id)
	{
		if (($model = Stage::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}
}
