<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\IndividualContractor;
use app\models\LegalContractor;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;

class ContractorsController extends Controller
{
	public function beforeAction($action)
	{
		$allowedActions = ['create', 'update', 'delete', 'index',];

		if (in_array($action->id, $allowedActions)) {
			if (Yii::$app->user->isGuest) {
				throw new ForbiddenHttpException('You are not allowed to access this page.'); // ошибка доступа
			}
		}

		return parent::beforeAction($action);
	}
	public function actionCreate($contractor_type)
	{
		$model = new LegalContractor();
		$contractorType = Yii::$app->request->get('contractor_type');
		if ($model->load(Yii::$app->request->post())) {
			if ($contractorType == "legal") {
				$model->type = "legal";
				$model->is_deleted = 0;
				if ($model->save(false)) {
					Yii::$app->session->setFlash('success', 'Контрагент успешно создан.');
				} else {
					Yii::$app->session->setFlash('error', 'Контрагент не создан.');
				}
				//Yii::$app->session->setFlash('success', 'Контрагент успешно создан.');
				return $this->redirect(['index']);
			} else {
				$model->type = "individual";
				$model->company = "empty";
				$model->company_consignee = " ";
				$model->company_address_consignee = " ";
				$model->iin = " ";
				$model->legal_address = " ";
				$model->same_address = 0;
				$model->actual_address = " ";
				$model->is_deleted = 0;
				if ($model->save(false)) {
					Yii::$app->session->setFlash('success', 'Контрагент успешно создан.');
				} else {
					Yii::$app->session->setFlash('error', 'Контрагент не создан.');
				}
				return $this->redirect(['index']);
			}
		}

		return $this->render('create', [
			'model' => $model,
			'contractorType' => $contractorType
		]);
	}



	public function actionUpdate($id)
	{
		$model = LegalContractor::findOne($id);
		$request = Yii::$app->request;

		if ($model->load($request->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', 'Данные контрагента успешно обновлены.');
			return $this->redirect(['index']);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}


	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->is_deleted = 1;
		$model->save();
		return $this->redirect(['contractors/index']);
		// Успешное удаление
	}

	public function actionIndex()
	{
		$individualDataProvider = new ActiveDataProvider([
			'query' => LegalContractor::find()->where(['type' => 'individual'])->andWhere(['is_deleted' => 0]),
		]);
		$legalDataProvider = new ActiveDataProvider([
			'query' => LegalContractor::find()->where(['type' => 'legal'])->andWhere(['is_deleted' => 0]),
		]);

		return $this->render('index', [
			'individualDataProvider' => $individualDataProvider,
			'legalDataProvider' => $legalDataProvider,
		]);
	}


	protected function findModel($id)
	{
		if (($model = IndividualContractor::findOne($id)) !== null) {
			return $model;
		}

		if (($model = LegalContractor::findOne($id)) !== null) {
			return $model;
		}
	}
}
