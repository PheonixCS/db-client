<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\CaptchaSettings;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class CaptchaSettingsController extends Controller
{
	public function beforeAction($action)
	{
		if ($action->id == 'index' || $action->id == 'update') {
			if (Yii::$app->user->isGuest || Yii::$app->user->identity->is_admin != 1) {
				throw new ForbiddenHttpException('You are not allowed to access this page.'); // ошибка доступа
			}
		}

		return parent::beforeAction($action);
	}
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::class,
				'only' => ['index', 'update'],
				'rules' => [
					[
						'allow' => true,
						'actions' => ['index', 'update'],
						'matchCallback' => function ($rule, $action) {
							return Yii::$app->user->identity->is_admin == 1;
						},
					],
				],
			],
		];
	}

	public function actionIndex()
	{
		$model = CaptchaSettings::findOne(1); // Получаем модель из базы данных

		return $this->render('index', [
			'model' => $model,
		]);
	}
	public function actionUpdate()
	{
		$model = CaptchaSettings::findOne(1); // Получаем модель из базы данных

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', 'Параметры капчи успешно сохранены');
			return $this->redirect(['index']);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}
}
