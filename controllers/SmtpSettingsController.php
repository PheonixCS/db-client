<?php

namespace app\controllers;

use Yii;
use app\models\SmtpSettings;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

class SmtpSettingsController extends Controller
{

	public function beforeAction($action)
	{
		$allowedActions = ['create', 'update', 'index',];

		if (in_array($action->id, $allowedActions)) {
			if (Yii::$app->user->isGuest) {
				throw new ForbiddenHttpException('You are not allowed to access this page.'); // ошибка доступа
			}
		}

		return parent::beforeAction($action);
	}

	public function actionIndex()
	{
		$smtpSettings = SmtpSettings::findOne(1); // предположим, что настройки находятся в строке с ID 1

		if (!$smtpSettings) {
			throw new NotFoundHttpException('SMTP settings not found.');
		}

		return $this->render('index', [
			'smtpSettings' => $smtpSettings,
		]);
	}

	public function actionCreate()
	{
		$model = new SmtpSettings();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

	public function actionUpdate()
	{
		$model = SmtpSettings::findOne(1); // предположим, что настройки находятся в строке с ID 1

		if (!$model) {
			throw new NotFoundHttpException('SMTP settings not found.');
		}

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}
}
