<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class UserController extends Controller
{

	public function beforeAction($action)
	{
		$allowedActions = ['create', 'delete', 'view', 'index',];

		if (in_array($action->id, $allowedActions)) {
			if (Yii::$app->user->isGuest) {
				throw new ForbiddenHttpException('You are not allowed to access this page.'); // ошибка доступа
			}
		}

		return parent::beforeAction($action);
	}

	public function actionCreate()
	{
		$model = new User();
		if ($model->load(Yii::$app->request->post())) {
			$model->is_admin = Yii::$app->request->post('User')['is_admin'];
			$model->password = Yii::$app->getSecurity()->generatePasswordHash($model->password);
			if ($model->save()) {
				return $this->redirect(['index']);
			}
		}
		return $this->render('create', [
			'model' => $model,
		]);
	}


	public function actionDelete($id)
	{
		$model = User::findOne($id);
		$model->delete();

		return $this->redirect(['index']);
	}


	public function actionView($id)
	{
		$model = User::findOne($id);

		return $this->render('view', [
			'model' => $model,
		]);
	}

	public function actionIndex()
	{
		$query = User::find();

		$dataProvider = new \yii\data\ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => 10,
			],
		]);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
		]);
	}
}
