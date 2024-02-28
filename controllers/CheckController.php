<?php

namespace app\controllers;

use yii\helpers\Json;
use yii\web\Controller;
use app\models\Check;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\data\Sort;
use app\models\Product;
use app\models\Stage;
use app\models\suppCheck;
use app\models\Supplier;
use app\models\CheckSearch;
use Yii;
use yii\web\Response;
use app\models\IndividualContractor;
use app\models\LegalContractor;
use app\models\LogChanges;
use app\models\LogChangesSearch;
use yii\helpers\VarDumper;
use DateTime;
use yii\web\ForbiddenHttpException;
use DateTimeZone;

class CheckController extends Controller
{
	public function beforeAction($action)
	{
		$allowedActions = ['index', 'update', 'getsuppcheck', 'getvalue', 'getsupplieroptions', 'providerdelete', 'suppcheckadd', 'create', 'delete', 'ajaxgetproduct', 'getcontractors', 'savecontractor', 'search', 'getorganizationinfo', 'view', 'updateactiveform'];

		if (in_array($action->id, $allowedActions)) {
			if (Yii::$app->user->isGuest) {
				throw new ForbiddenHttpException('You are not allowed to access this page.'); // ошибка доступа
			}
		}

		return parent::beforeAction($action);
	}
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => \yii\filters\VerbFilter::class,
				'actions' => [
					'delete' => ['post'],
				],
			],
		];
	}

	public function rules()
	{
		return [
			// Other rules
			['class' => 'yii\rest\UrlRule', 'controller' => 'check'],
			['pattern' => 'check/delete', 'route' => 'check/delete', 'verb' => 'POST'],
		];
	}

	public function actionIndex()
	{
		$this->view->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css');
		// $this->view->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
		$providersModel = new SuppCheck();
		$suppliersModel = new Supplier();
		$suppliersData = Supplier::find()->all();
		$productModel = new Product();
		$legalModel = new LegalContractor();
		$stageModel = new Stage();

		$searchModel = new CheckSearch();
		$delivery_types = \app\models\DeliveryType::find()->all();

		// var_dump(yii::$app->request->get());
		// die;
		//$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider = $searchModel->search(yii::$app->request->get());

		$dataProvider->sort->defaultOrder = [
			'numberCheck' => SORT_DESC,
		];
		$dataProvider->pagination->defaultPageSize = 30;


		$hiddenBlocksData = [];
		$dats = $dataProvider->getModels();
		foreach ($dats as $data) {
			$productIds = explode(',', $data->name);
			$productList = $productModel->find()->where(['id' => $productIds])
				->all();
			foreach ($productList as $product) {
				$hiddenBlocksData[$data->id] = [
					'data' => $data->id,
					'productName' => $product->name,
					'quantity' => $product->quantity,
					'price' => $product->price,
					'totalPrice' => $product->quantity * $product->price,
				];
			}
		}

		return $this->render('table', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
			'providers' => $providersModel,
			'suppliers' => $suppliersModel,
			'products' => $productModel,
			'organization' => $legalModel,
			'stageModel' => $stageModel,
			'suppliersData' => $suppliersData,
			'delivery_types' => $delivery_types,
			'hiddenBlocksData' => $hiddenBlocksData,
		]);
	}
	public function actionGetsuppcheck()
	{
		$supplierId = Yii::$app->request->post('supplierId');

		$suppCheck = suppCheck::find()
			->where(['id' => $supplierId])
			->one();
		// var_dump($suppCheck);
		// die;
		if (isset($suppCheck)) {
			$checkNumber = (string)$suppCheck->checkNumber;
			$checkAmount = (string)$suppCheck->checkSum;
			$paymentDate = (string)$suppCheck->dateOfpayment;
			Yii::$app->response->format = Response::FORMAT_JSON;
			return [
				'checkNumber' => $checkNumber,
				'checkAmount' => $checkAmount,
				'paymentDate' => $paymentDate,
			];
		} else {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return  [
				'error' => 'Check not found',
			];
		}

		Yii::$app->end();
	}
	public function actionGetValue()
	{
		$idCheck = Yii::$app->request->post('checkId');
		$attr = Yii::$app->request->post('attr');
		$model = Check::findOne($idCheck);
		Yii::$app->response->format = Response::FORMAT_JSON;
		return [
			'value' => $model->$attr,
		];
	}
	public function actionGetSupplierOptions()
	{
		$suppliersData = Supplier::find()->where(['is_deleted' => 0])->all();

		$options = [];
		foreach ($suppliersData as $supplier) {
			$options[] = [
				'value' => $supplier->id,
				'text' => $supplier->company_name,
			];
		}

		return Json::encode($options);
	}
	public function actionProviderdelete()
	{
		$providerId = $_POST['providerId'];
		$dataId = $_POST['dataId'];

		$deletedProvider = suppCheck::findOne($providerId);
		$deletedProviderSum = $deletedProvider->checkSum;


		$checkModel = Check::findOne($dataId);
		$checkModel->profit += $deletedProviderSum;

		// Удаление поставщика из поля "provider"
		$providers = explode(',', $checkModel->provider);
		$key = array_search($providerId, $providers);
		if ($key !== false) {
			unset($providers[$key]);
			$oldAttributes = $checkModel->getOldAttributes();
			$checkModel->provider = implode(',', $providers);
			$checkModel->save(false);
			$newAttributes = $checkModel->getAttributes();
			// Логирование изменений в модели Check
			foreach ($oldAttributes as $attribute => $oldValue) {
				$newValue = $newAttributes[$attribute];
				if ($oldValue != $newValue) {
					$logChange = new LogChanges();
					$logChange->model_name = Check::class;
					$logChange->model_id = $checkModel->id;
					$logChange->attribute_name = $attribute;
					$logChange->old_value = $oldValue;
					$logChange->new_value = $newValue;
					$logChange->changed_at = strtotime(date('Y-m-d H:i:s'));
					$logChange->who = Yii::$app->user->identity->name;
					$logChange->save();
				}
			}
		}
		//$checkModel->sum 
	}
	public function actionSuppcheckadd()
	{
		$model = new SuppCheck();

		// Получите данные из запроса
		$providerId = Yii::$app->request->post('providerId');
		$dataId = Yii::$app->request->post('dataId');
		$checkSum = Yii::$app->request->post('checkSum');
		$checkNumber = Yii::$app->request->post('checkNumber');
		//$dateOfpayment = Yii::$app->request->post('dateOfpayment');
		//return (var_dump(Yii::$app->request->post('dateOfpayment')));
		if (Yii::$app->request->post('dateOfpayment')) {
			$dateOfpayment = DateTime::createFromFormat('d-m-Y H:i', Yii::$app->request->post('dateOfpayment'));

			$dateOfpayment = $dateOfpayment->getTimestamp();
		} else {
			$dateOfpayment = NULL;
		}
		//return (var_dump($dateOfpayment));
		// Заполните модель данными из формы
		$model->suppliers = $providerId;
		$model->checkSum = $checkSum;
		$model->checkNumber = $checkNumber;
		$model->dateOfpayment = $dateOfpayment;

		// Сохраните модель
		$model->save();

		// Получите модель Check и добавьте нового провайдера к полю provider
		$check = Check::findOne($dataId);
		if ($check) {
			$check->profit = $check->profit - $model->checkSum;
			$oldAttributes = $check->getOldAttributes();
			$check->provider .= $model->id . ',';
			$check->save();
			$newAttributes = $check->getAttributes();
			// Логирование изменений в модели Check
			foreach ($oldAttributes as $attribute => $oldValue) {
				$newValue = $newAttributes[$attribute];
				if ($oldValue != $newValue) {
					$logChange = new LogChanges();
					$logChange->model_name = Check::class;
					$logChange->model_id = $check->id;
					$logChange->attribute_name = $attribute;
					$logChange->old_value = $oldValue;
					$logChange->new_value = $newValue;
					$logChange->changed_at = strtotime(date('Y-m-d H:i:s'));
					$logChange->who = Yii::$app->user->identity->name;
					$logChange->save();
				}
			}
		}

		$supplier = Supplier::findOne($providerId);

		// Верните ответ в формате JSON с необходимыми данными
		Yii::$app->response->format = Response::FORMAT_JSON;
		return [
			'providerId' => $providerId,
			'dataId' => $dataId,
			'supplierName' => $supplier->company_name,
			'supplierId' => $model->id
		];
	}
	public function actionCreate()
	{
		$model = new Check();
		$modelProduct = new Product();
		$modelContractor = new LegalContractor();
		$dataContractors = LegalContractor::find()->where(['is_deleted' => 0])->all();
		$modelProviders = new suppCheck();
		$suppliersData = Supplier::find()->where(['is_deleted' => 0])->all();
		$stageData = Stage::find()->where(['is_deleted' => 0])->all();

		//$flSuppCheckSave = false;
		//$flSuppProductSave = false;

		if (Yii::$app->request->post()) {
			$suppCheckList = Yii::$app->request->post('suppCheck');
			$products = Yii::$app->request->post('Product');
			$contractors = Yii::$app->request->post('LegalContractor');
			$pay = 0;
			if ($suppCheckList) {
				$count = count($suppCheckList['suppliers']);
				for ($i = 0; $i < $count; $i++) {
					// Получаем данные для каждого элемента
					$supplier = new suppCheck();
					$supplier->suppliers = $suppCheckList['suppliers'][$i];
					$supplier->checkSum = $suppCheckList['checkSum'][$i];
					if ($supplier->checkSum != NULL) {
						$pay += $supplier->checkSum;
					}
					$supplier->checkNumber = $suppCheckList['checkNumber'][$i];
					$supplier->dateOfpayment = strtotime($suppCheckList['dateOfpayment'][$i]);
					$supplier->save(false);
					$model->provider = (string)$model->provider . (string)$supplier->id . ',';
				}
			}
			// сохранение контр агента если тип формы legal или individual
			if (isset(Yii::$app->request->post('Check')['organization'])) {
				$model->organization = Yii::$app->request->post('Check')['organization'];
			} else {
				if ($contractors) {
					$count = count($contractors['contact_person']);
					$forms = Yii::$app->request->post('formType');
					for ($i = 0; $i < $count; $i++) {
						if ($forms[$i] == "legal") {
							$contractor = new LegalContractor();
							$contractor->company = $contractors['company'][$i];
							$contractor->company_consignee = $contractors['company_consignee'][$i];
							$contractor->company_address_consignee = $contractors['company_address_consignee'][$i];
							$contractor->iin = $contractors['iin'][$i];
							//$contractor->legal_address = $contractors['legal_address'][$i];
							$contractor->same_address = $contractors['same_address'][$i];
							$contractor->actual_address = $contractors['actual_address'][$i];
							if ($contractor->same_address) {
								$contractor->legal_address = $contractor->actual_address;
							} else {
								$contractor->legal_address = $contractors['legal_address'][$i];
							}
							$contractor->contact_person = $contractors['contact_person'][$i];
							$contractor->phone1 = $contractors['phone1'][$i];
							$contractor->phone2 = $contractors['phone2'][$i];
							$contractor->email = $contractors['email'][$i];
							$contractor->check_id = 0;
							$contractor->type = "legal";
							$contractor->is_deleted = 0;
							if (!$contractor->save()) {
								Yii::$app->session->setFlash('error', 'Все поля контр агента обязательны для заполнения');
								return $this->redirect(Yii::$app->request->referrer);
							}
							$model->organization = $contractor->id; // вот тут если контрактор может быть не один добавить список id
						} else if ($forms[$i] == "individual") {
							$contractor = new LegalContractor();
							$contractor->company = "empty";
							$contractor->company_consignee = " ";
							$contractor->company_address_consignee = " ";
							$contractor->iin = " ";
							$contractor->legal_address = " ";
							$contractor->same_address = 0;
							$contractor->actual_address = " ";
							$contractor->contact_person = $contractors['contact_person'][$i];
							$contractor->phone1 = $contractors['phone1'][$i];
							$contractor->phone2 = $contractors['phone2'][$i];
							$contractor->email = $contractors['email'][$i];
							$contractor->check_id = 0;
							$contractor->type = "individual";
							$contractor->is_deleted = 0;
							if (!$contractor->save()) {
								Yii::$app->session->setFlash('error', 'Все поля контр агента обязательны для заполнения');
								//return Json::encode(['success' => false, 'error' => $contractor->getErrors()]);
								return $this->redirect(Yii::$app->request->referrer);
							}
							$model->organization = $contractor->id; // вот тут если контрактор может быть не один добавить список id
						}
					}
				}
			}
			// работа с товарами.
			$sum = 0;
			if ($products) {
				$count = count($products['code']);
				for ($i = 0; $i < $count; $i++) {
					$product = new Product();
					$product->code = $products['code'][$i];
					$product->name = $products['name'][$i];
					$product->price = $products['price'][$i];
					if (!isset($products['price'][$i])) {
						$product->price = 0;
					}
					// var_dump($products['quantity'][$i]);
					// die;
					if ($products['quantity'][$i] == NULL && $products['quantity'][$i] == "") {
						$product->quantity = 0;
					} else {
						$product->quantity = $products['quantity'][$i];
					}

					$sum = (float)$sum + (float)$product->price * (float)$product->quantity;
					$product->unit_of_measurement = $products['unit_of_measurement'][$i];
					$product->check_id = 0;
					//$flSuppProductSave = true;
					$product->save(false);
					$model->name = $model->name . $product->id . ',';
				}
			}
			$model->who = Yii::$app->user->identity->name;
			$model->sum = $sum;
			$model->sending = 0;

			$model->dateOfPayment = 0;
			$model->delivery_time = 0;
			$model->load(Yii::$app->request->post());
			$model->dateDelivery = strtotime($model->dateDelivery);
			$model->payment_order_date = strtotime($model->payment_order_date);

			// определение не обязательных полей.
			if (!(isset($model->costDelivery) && $model->costDelivery != NULL)) {
				$model->costDelivery = 0;
			}
			if (isset($sum)) {
				$sum = 0;
			}
			if (isset($pay)) {
				$pay = 0;
			}
			//$model->profit = $sum - $pay - $model->costDelivery;
			$result = $this->calculateProfit($model);
			$model->profit = $result['profit'];
            $currentDateTime = new DateTime('now', new DateTimeZone('Europe/Moscow'));
            $model->date_crated = $currentDateTime->getTimestamp();
			if (!$model->save(false)) {
				Yii::$app->session->setFlash('error', 'Некоторые поля которые вы заполнили имеют не верный тип');
				return $this->redirect(Yii::$app->request->referrer);
			}
			return $this->redirect(['/check']);
		}

		return $this->render('create', [
			'model' => $model,
			'modelProduct' => $modelProduct,
			'modelContractor' => $modelContractor,
			'modelProviders' => $modelProviders,
			'suppliersData' => $suppliersData,
			'stageData' => $stageData,
			'dataContractors' => $dataContractors
		]);
	}
	public function actionDelete()
	{
		$id = Yii::$app->request->get('id');
		$check = Check::findOne($id);
		$check->is_deleted = true;
		if ($check->save()) {
			// Логирование изменений в модели Check
			$logChange = new LogChanges();
			$logChange->model_name = Check::class;
			$logChange->model_id = $check->id;
			$logChange->attribute_name = "is_deleted";
			$logChange->old_value = "0";
			$logChange->new_value = "1";
			$logChange->changed_at = strtotime(date('Y-m-d H:i:s'));
			$logChange->who = Yii::$app->user->identity->name;
			$logChange->save();
		} else {
			var_dump($check->errors);
			die;
		}


		$this->view->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css');
		//$this->view->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
		$providersModel = new SuppCheck();
		$suppliersModel = new Supplier();
		$suppliersData = Supplier::find()->all();
		$productModel = new Product();
		$legalModel = new LegalContractor();
		$stageModel = new Stage();

		$searchModel = new CheckSearch();

		$dataProvider = $searchModel->search(yii::$app->request->get());
		$dataProvider->sort->defaultOrder = [
			'numberCheck' => SORT_DESC,
		];
		$dataProvider->pagination->defaultPageSize = 30;
		$delivery_types = \app\models\DeliveryType::find()->all();

		$hiddenBlocksData = [];
		$dats = $dataProvider->getModels();
		foreach ($dats as $data) {
			$productIds = explode(',', $data->name);
			$productList = $productModel->find()->where(['id' => $productIds])
				->all();
			foreach ($productList as $product) {
				$hiddenBlocksData[$data->id] = [
					'data' => $data->id,
					'productName' => $product->name,
					'quantity' => $product->quantity,
					'price' => $product->price,
					'totalPrice' => $product->quantity * $product->price,
				];
			}
		}

		return $this->render('table', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
			'providers' => $providersModel,
			'suppliers' => $suppliersModel,
			'products' => $productModel,
			'organization' => $legalModel,
			'stageModel' => $stageModel,
			'suppliersData' => $suppliersData,
			'delivery_types' => $delivery_types,
			'hiddenBlocksData' => $hiddenBlocksData,
		]);
	}
	public function actionAjaxGetProduct()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;

		$checkId = Yii::$app->request->get('check_id');
		$products = Product::findAll(['check_id' => $checkId]);

		if ($products) {
			return [
				'success' => true,
				'products' => $products,
			];
		} else {
			return [
				'success' => false,
				'products' => null,
			];
		}
	}
	public function actionGetContractors($type)
	{
		// Получите список контрагентов в зависимости от типа (individual или legal)
		if ($type == 'individual') {
			$contractors = IndividualContractor::find()->all();
		} elseif ($type == 'legal') {
			$contractors = LegalContractor::find()->all();
		} else {
			$contractors = [];
		}

		// Отправьте JSON-ответ со списком контрагентов
		return Json::encode($contractors);
	}

	public function actionGetContractor($id)
	{
		// Находим контрактора по id
		$contractor = LegalContractor::findOne($id);

		if ($contractor) {
			$data = [
				'type' => "Физ. Лицо",
				'contact_person' => $contractor->contact_person,
				'phone1' => $contractor->phone1,
				'phone2' => $contractor->phone2,
				'email' => $contractor->email,
			];

			if ($contractor->type === 'legal') {
				$data['type'] = "Юр. Лицо";
				$data['company'] = $contractor->company;
				$data['company_consignee'] = $contractor->company_consignee;
				$data['company_address_consignee'] = $contractor->company_address_consignee;
				$data['iin'] = $contractor->iin;
				$data['legal_address'] = $contractor->legal_address;
				$data['same_address'] = $contractor->same_address;
				$data['actual_address'] = $contractor->actual_address;
			}

			return $this->asJson($data);
		} else {
			return $this->asJson(['error' => 'Контрактор не найден']);
		}
	}


	public function actionSaveContractor()
	{
		// Получение типа контрагента из POST-данных
		$type = Yii::$app->request->post('type');

		// Создайте новую модель контрагента в зависимости от выбранного типа (individual или legal)
		if ($type == 'individual') {
			$contractor = new IndividualContractor();
			$contractor->contact_person = Yii::$app->request->post('contact_person');
			$contractor->phone1 = Yii::$app->request->post('phone1');
			$contractor->phone2 = Yii::$app->request->post('phone2');
			$contractor->email = Yii::$app->request->post('email');
			$contractor->check_id = 0;
		} elseif ($type == 'legal') {
			$contractor = new LegalContractor();
			$contractor->contact_person = Yii::$app->request->post('contact_person');
			$contractor->phone1 = Yii::$app->request->post('phone1');
			$contractor->phone2 = Yii::$app->request->post('phone2');
			$contractor->email = Yii::$app->request->post('email');
			$contractor->company = Yii::$app->request->post('name');
			$contractor->company_consignee = Yii::$app->request->post('company_consignee');
			$contractor->company_address_consignee = Yii::$app->request->post('company_address_consignee');
			$contractor->iin = Yii::$app->request->post('taxnumber');
			$contractor->legal_address = Yii::$app->request->post('legal_address');
			$contractor->same_address = Yii::$app->request->post('same_address');
			$contractor->actual_address = Yii::$app->request->post('actual_address');
			$contractor->check_id = 0;
		} else {
			return Json::encode(['success' => false, 'error' => 'Invalid contractor type']);
		}
		// Сохраните модель контрагента в базе данных
		if ($contractor->save()) {
			// Верните ID сохраненного контрагента в качестве JSON-ответа
			return Json::encode(['success' => true, 'type' => $type, 'contractor' => $contractor]);
		} else {
			return Json::encode(['success' => false, 'error' => $contractor->getErrors()]);
		}
	}
	public function actionSearch($table, $column, $searchValue)
	{
		if ($table == "individual_contractors") {
			// Делим строку поиска на отдельные слова
			$keywords = explode(' ', $searchValue);

			$query = IndividualContractor::find()
				->andWhere(['like', $column, $keywords]);

			$contractors = $query->all();

			// возвращение результата в формате JSON
			Yii::$app->response->format = Response::FORMAT_JSON;
			return $contractors;
		} else if ($table == "legal_contractors") {
			// Делим строку поиска на отдельные слова
			$searchValue = urldecode($searchValue);
			$keywords = explode(' ', $searchValue);
			$query = LegalContractor::find()
				->andWhere(['like', $column, $keywords]);

			$contractors = $query->all();

			// возвращение результата в формате JSON
			Yii::$app->response->format = Response::FORMAT_JSON;
			return $contractors;
		}
	}
	public function actionGetOrganizationInfo($checkId)
	{
		$individualContractor = IndividualContractor::find()->where(["like", "check_id", $checkId])->one();
		$legalContractor = LegalContractor::find()->where(["like", "check_id", $checkId])->one();

		$organizationInfo = [];

		if ($individualContractor !== null) {
			$organizationInfo = [
				'contact_person' => $individualContractor->contact_person,
				'phone1' => $individualContractor->phone1,
				'phone2' => $individualContractor->phone2,
				'email' => $individualContractor->email,
				'type' => 'IndividualContractor',
			];
		} else if ($legalContractor !== null) {
			$organizationInfo = [
				'company' => $legalContractor->company,
				'contact_person' => $legalContractor->contact_person,
				'phone1' => $legalContractor->phone1,
				'phone2' => $legalContractor->phone2,
				'email' => $legalContractor->email,
				'type' => 'LegalContractor',
			];
		}

		if (!empty($organizationInfo)) {
			return json_encode(['success' => true, 'organizationInfo' => $organizationInfo]);
		} else {
			return json_encode(['success' => false, 'message' => 'Не удалось найти информацию по организации']);
		}
	}
	public function actionUpdate($id)
	{
		$model = new Check();
		$modelProduct = new Product();
		$modelContractor = new LegalContractor();
		$dataContractors = LegalContractor::find()->where(['is_deleted' => 0])->all();
		$modelProviders = new suppCheck();
		$suppliersData = Supplier::find()->where(['is_deleted' => 0])->all();
		$stageData = Stage::find()->where(['is_deleted' => 0])->all();

		$model = Check::findOne($id);
		if($model->provider){
		$suppCheckIds = explode(',', $model->provider);
		}
		else {
		    $suppCheckIds = [];
		}
		$suppChecks = SuppCheck::find()->where(['id' => $suppCheckIds])->all();

		$productIds = explode(',', $model->name);
		$productsData = Product::find()->where(['id' => $productIds])->all();

		$supplierIds = [];
		foreach ($suppChecks as $suppCheck) {
			$supplierIds[] = $suppCheck->suppliers;
		}

		$suppliers = Supplier::find()->where(['id' => $supplierIds])->all();

		if ($model === null) {
			throw new NotFoundHttpException('The requested page does not exist.');
		}

		if ($model->load(Yii::$app->request->post())) {
			$oldAttributes = $model->getOldAttributes(); // Сохраняем старые атрибуты модели перед сохранением

			// Получаем данные из запроса
			$supp = Yii::$app->request->post('Supp');
			if (isset($supp)) {
				$suppDel = Yii::$app->request->post('Supp')['del'];

				// Проверяем, что данные существуют
				if (!empty($suppDel)) {
					// Удаляем совпадающие идентификаторы в $model->provider
					$providerIds = explode(',', $model->provider);
					foreach ($suppDel as $index => $id) {
						if (($key = array_search($id, $providerIds)) !== false) {
							unset($providerIds[$key]);
						}
					}
					$model->provider = implode(',', $providerIds);

					// // Удаляем объекты модели Product с совпадающими id
					// suppCheck::deleteAll(['id' => $providerIds]);
				}
			}

			$suppCheckList = Yii::$app->request->post('suppCheck');
			// сохранение поставщика
			if ($suppCheckList) {
				if (isset($suppCheckList['suppliers']['new'])) {
					$count = count($suppCheckList['suppliers']['new']);
					for ($i = 0; $i < $count; $i++) {
						// Получаем данные для каждого элемента
						$supplier = new suppCheck();
						$supplier->suppliers = $suppCheckList['suppliers']['new'][$i];
						$supplier->checkSum = $suppCheckList['checkSum']['new'][$i];
						$supplier->checkNumber = $suppCheckList['checkNumber']['new'][$i];
						$supplier->dateOfpayment = strtotime($suppCheckList['dateOfpayment']['new'][$i]);
						$supplier->save(false);
						$model->provider = (string)$model->provider . (string)$supplier->id . ',';
						//$this->logCreateObj($supplier);
					}
				}
				$attributesToLog = ['suppliers', 'checkNumber', 'dateOfpayment', 'checkSum'];

				if (isset($suppCheckList['suppliers']['old'])) {
					$count = count($suppCheckList['suppliers']['old']);
					for ($i = 0; $i < $count; $i++) {
						if (!isset($suppCheckList['suppCheckId']['old'][$i])) {
							continue;
						}
						// Получаем данные для каждого элемента
						$supplierId = $suppCheckList['suppCheckId']['old'][$i];

						// Находим существующий объект по идентификатору
						$supplier = suppCheck::findOne($supplierId);
						if (!$supplier) {
							continue; // Пропускаем итерацию, если объект не найден
						}

						$oldAttributesSupplier = $supplier->getOldAttributes();
						$supplier->dateOfpayment = strtotime($suppCheckList['dateOfpayment']['old'][$i]);
						// Обновляем свойства объекта
						$attributesToUpdate = [];
						foreach ($attributesToLog as $attribute) {
							if (!isset($suppCheckList[$attribute]['old'][$i])) {
								continue;
							}
							if ($attribute == "dateOfpayment") {
							} else {
								$supplier->$attribute = $suppCheckList[$attribute]['old'][$i];
							}

							// Проверяем, изменился ли атрибут
							if ($oldAttributesSupplier[$attribute] !== $supplier->$attribute) {
								$attributesToUpdate[] = $attribute;
							}

							$supplierAttributes[] = $supplier->$attribute;
						}
						// Если есть измененные атрибуты, выполняем сохранение и логирование
						if (!empty($attributesToUpdate)) {
							// Сохраняем объект Product
							$supplier->save(false);


							// Логируем измененные атрибуты
							$oldValuesProd = [];
							$newValuesProd = [];
							foreach ($attributesToUpdate as $attribute) {
								$oldValuesProd[$attribute] = $oldAttributesSupplier[$attribute];
								$newValuesProd[$attribute] = $supplier->$attribute;
							}
							// var_dump($attributesToUpdate);
							// die;
							$this->loggerChanges($supplier, $oldValuesProd, $newValuesProd);
						}
					}
				}
			}
			// сохранение контр агента если тип формы legal или individual
			$contractors = Yii::$app->request->post('LegalContractor');
			if (isset(Yii::$app->request->post('Check')['organization'])) {
				$oldValuesOrg = [];
				$newValuesOrg = [];
				$oldValuesOrg['organization'] =  $model->organization;
				$newValuesOrg['organization'] =	Yii::$app->request->post('Check')['organization'];
				$this->loggerChanges($model, $oldValuesOrg, $newValuesOrg);
				$model->organization = Yii::$app->request->post('Check')['organization'];
			} else {
				if ($contractors) {
					$count = count($contractors['contact_person']);
					$forms = Yii::$app->request->post('formType');
					for ($i = 0; $i < $count; $i++) {
						if ($forms[$i] == "legal") {
							$contractor = new LegalContractor();
							$contractor->company = $contractors['company'][$i];
							$contractor->company_consignee = $contractors['company_consignee'][$i];
							$contractor->company_address_consignee = $contractors['company_address_consignee'][$i];
							$contractor->iin = $contractors['iin'][$i];
							//$contractor->legal_address = $contractors['legal_address'][$i];
							$contractor->same_address = $contractors['same_address'][$i];
							$contractor->actual_address = $contractors['actual_address'][$i];
							if ($contractor->same_address) {
								$contractor->legal_address = $contractor->actual_address;
							} else {
								$contractor->legal_address = $contractors['legal_address'][$i];
							}
							$contractor->contact_person = $contractors['contact_person'][$i];
							$contractor->phone1 = $contractors['phone1'][$i];
							$contractor->phone2 = $contractors['phone2'][$i];
							$contractor->email = $contractors['email'][$i];
							$contractor->check_id = 0;
							$contractor->type = "legal";
							$contractor->is_deleted = 0;
							if (!$contractor->save()) {
								Yii::$app->session->setFlash('error', 'Все поля контр агента обязательны для заполнения');
								return $this->redirect(Yii::$app->request->referrer);
							}
							$model->organization = $contractor->id; // вот тут если контрактор может быть не один добавить список id
						} else if ($forms[$i] == "individual") {
							$contractor = new LegalContractor();
							$contractor->company = "empty";
							$contractor->company_consignee = " ";
							$contractor->company_address_consignee = " ";
							$contractor->iin = " ";
							$contractor->legal_address = " ";
							$contractor->same_address = 0;
							$contractor->actual_address = " ";
							$contractor->contact_person = $contractors['contact_person'][$i];
							$contractor->phone1 = $contractors['phone1'][$i];
							$contractor->phone2 = $contractors['phone2'][$i];
							$contractor->email = $contractors['email'][$i];
							$contractor->check_id = 0;
							$contractor->type = "individual";
							$contractor->is_deleted = 0;
							if (!$contractor->save()) {
								Yii::$app->session->setFlash('error', 'Все поля контр агента обязательны для заполнения');
								return $this->redirect(Yii::$app->request->referrer);
							}
							$oldValuesOrg = [];
							$newValuesOrg = [];
							$oldValuesOrg['organization'] = $model->organization;
							$newValuesOrg['organization'] = $contractor->id;
							$this->loggerChanges($model, $oldValuesOrg, $newValuesOrg);
							$model->organization = $contractor->id; // вот тут если контрактор может быть не один добавить список id
						}
					}
				}
			}
			// работа с товарами.
			$productD = Yii::$app->request->post('ProductD');
			if (isset($productD)) {
				$deletedIds = Yii::$app->request->post('ProductD')['del'];
				// Проверяем на пустоту и выполняем удаление
				if (!empty($deletedIds)) {

					// Получаем текущее значение поля name
					$currentIds = explode(',', $model->name);

					// Удаляем переданные id из текущего значения
					foreach ($deletedIds as $deletedId) {
						$key = array_search($deletedId, $currentIds);
						if ($key !== false) {
							unset($currentIds[$key]);
						}
					}

					// Обновляем поле name с новым значением
					$oldModelName = $model->name;
					$model->name = implode(',', $currentIds);
					// Удаляем объекты модели Product с совпадающими id
					// Product::deleteAll(['id' => $deletedIds]);
				}
			}
			// тут собственно создаем новые товары или обновляем старые.
			$products = Yii::$app->request->post('Product');
			if ($products) {
				if (isset($products['code']['new'])) {
					$count = count($products['code']['new']);
					for ($i = 0; $i < $count; $i++) {
						$product = new Product();
						$product->code = $products['code']['new'][$i];
						$product->name = $products['name']['new'][$i];
						$product->price = $products['price']['new'][$i];
						$product->quantity = $products['quantity']['new'][$i];
						$product->unit_of_measurement = $products['unit_of_measurement']['new'][$i];
						$product->check_id = 0;
						if ($product->validate()) {
							if (!$product->save()) {
								Yii::$app->session->setFlash('error', 'Ошибка в заполнении полей продукта');
								return $this->redirect(Yii::$app->request->referrer);
							}
							//$this->logCreateObj($product);
							$model->name = $model->name . $product->id . ',';
						} else {
							Yii::$app->session->setFlash('error', 'Ошибка в заполнении полей продукта');
							return $this->redirect(Yii::$app->request->referrer);
						}
					}
				}
				$attributesToLog = ['code', 'name', 'price', 'quantity', 'unit_of_measurement'];

				if (isset($products['code']['old'])) {
					$count = count($products['code']['old']);
					for ($i = 0; $i < $count; $i++) {
						// Получаем данные для каждого элемента
						if (!isset($products['id']['old'][$i])) {
							continue;
						}
						$productId = $products['id']['old'][$i];

						// Находим существующий объект по идентификатору
						$product = Product::findOne($productId);
						if (!$product) {
							continue; // Пропускаем итерацию, если объект не найден
						}

						// Создаем временную копию объекта модели для получения старых значений
						$oldAttributesProd = $product->getOldAttributes();

						// Обновляем свойства объекта
						$attributesToUpdate = [];
						foreach ($attributesToLog as $attribute) {
							$product->$attribute = $products[$attribute]['old'][$i];
							// Проверяем, изменился ли атрибут
							if ($oldAttributesProd[$attribute] !== $product->$attribute) {
								$attributesToUpdate[] = $attribute;
							}
						}

						// Если есть измененные атрибуты, выполняем сохранение и логирование
						if (!empty($attributesToUpdate)) {
							// Сохраняем объект Product
							if (!$product->save()) {
								Yii::$app->session->setFlash('error', 'Ошибка в заполнении полей продукта');
								return Json::encode(['success' => false, 'error' => $contractor->getErrors()]);
							}

							// Логируем измененные атрибуты
							$oldValuesProd = [];
							$newValuesProd = [];
							foreach ($attributesToUpdate as $attribute) {
								$oldValuesProd[$attribute] = $oldAttributesProd[$attribute];
								$newValuesProd[$attribute] = $product->$attribute;
							}
							// var_dump($attributesToUpdate);
							// die;
							$this->loggerChanges($product, $oldValuesProd, $newValuesProd);
						}
					}
				}
			}
			$result = $this->calculateProfit($model);
			$model->profit = $result['profit'];
			$model->sum = $result['totalPrice'];


			$newAttributes = $model->getAttributes(); // Получаем новые атрибуты после сохранения
			$newAttributes['payment_order_date'] = (string)strtotime($newAttributes['payment_order_date']);
			$newAttributes['dateDelivery'] = (string)strtotime($newAttributes['dateDelivery']);

			$model->dateDelivery = strtotime($model->dateDelivery);
			$model->payment_order_date = strtotime($model->payment_order_date);




			// $oldAttributes['dateDelivery'] = (string)strtotime($oldAttributes['dateDelivery']);
			if ($model->save(false)) {




				// Логирование изменений в модели Check
				if (isset($oldModelName)) {
					$oldAttributes['name'] = $oldModelName;
				}
				foreach ($oldAttributes as $attribute => $oldValue) {
					$newValue = $newAttributes[$attribute];
					if ($oldValue != $newValue) {
						$logChange = new LogChanges();
						$logChange->who = Yii::$app->user->identity->name;
						$logChange->model_name = Check::class;
						$logChange->model_id = $model->id;
						$logChange->attribute_name = $attribute;
						$logChange->old_value = (string)$oldValue;
						$logChange->new_value = (string)$newValue;
						$logChange->changed_at = strtotime(date('Y-m-d H:i:s'));
						$logChange->save();
					}
				}

				return $this->redirect(['check/']);
			} else {
				Yii::$app->session->setFlash('error', 'Некоторые поля которые вы заполнили имеют не верный тип');
				return $this->redirect(Yii::$app->request->referrer);
			}
		}

		return $this->render('update', [

			'model' => $model,
			'suppChecks' => $suppChecks, // объекты модели suppCheck связи поставщиков и счетов
			'suppliers' => $suppliers, // объекты модели поставщиков
			'modelProduct' => $modelProduct,
			'productsData' => $productsData, // получаем объекты прикрепелнных товаров
			'dataContractors' => $dataContractors,
			'modelContractor' => $modelContractor,
			'stageData' => $stageData,
			'suppliersData' => $suppliersData,
			'modelProviders' => $modelProviders
		]);
	}
	public function actionView($id)
	{
		$model = Check::findOne($id);

		// $logChangesSuppCheck = LogChanges::find()
		// 	->andWhere(['model_id', $id])
		// 	->where(['in', 'model_id', $suppCheckIds])
		// 	->andWhere(['model_name' => SuppCheck::class])
		// 	->orderBy(['changed_at' => SORT_DESC])
		// 	->all();
		if (isset($model->provider)) {
			$suppCheckIds = explode(',', $model->provider);
			$logChangesSuppCheck = LogChanges::find()
				->andWhere(['model_id', $id])
				->where(['in', 'model_id', $suppCheckIds])
				->andWhere(['model_name' => SuppCheck::class])
				->orderBy(['changed_at' => SORT_DESC])
				->all();
		} else {
			$logChangesSuppCheck = NULL;
			// $logChangesSuppCheck = LogChanges::find()
			// 	->andWhere(['model_id', $id])
			// 	->andWhere(['model_name' => SuppCheck::class])
			// 	->orderBy(['changed_at' => SORT_DESC])
			// 	->all();
		}
		$check = Check::findOne($id);
		if (isset($model->provider)) {
			$productIds = explode(',', $check->name);
		}
		$query = LogChanges::find()
			->where(['model_id' => $id, 'model_name' => 'app\models\Check']);

		// if (isset($productIds)) {
		// 	$query->orWhere(['model_id' => $productIds, 'model_name' => 'Таблица счетов(Товары)']);
		// }
		// if (isset($suppCheckIds)) {
		// 	$query->orWhere(['model_id' => $suppCheckIds, 'model_name' => 'Таблица счетов']);
		// }
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => ['pageSize' => 30],
		]);

		$searchModel = new LogChangesSearch();
		$id = Yii::$app->request->get('id'); // Получаем значение id из URL
		$dataProvider = $searchModel->search(yii::$app->request->get());
		$dataProvider->query->andWhere(['model_id' => $id]); // Добавляем условие для id
		$dataProvider->sort->defaultOrder = [
			'changed_at' => SORT_DESC,
		];
		$dataProvider->pagination->defaultPageSize = 30;
		//VarDumper::dump($dataProvider);
		return $this->render('view', [
			'searchModel' => $searchModel,
			'model' => $model,
			'dataProvider' => $dataProvider,
			'logChangesSuppCheck' => $logChangesSuppCheck,
		]);
	}
	public function actionUpdateActiveForm()
	{
		// Получите данные из запроса
		$suppCheckId = Yii::$app->request->post('suppCheckId');
		if (isset($suppCheckId)) {
			$checkNumber = Yii::$app->request->post('checkNumber');
			$checkAmount = Yii::$app->request->post('checkAmount');
			$checkDate = Yii::$app->request->post('checkDate');
			$checkID = Yii::$app->request->post('check');

			// $checkDate = DateTime::createFromFormat('d-m-Y H:i', Yii::$app->request->post('checkDate'));
			// $checkDate = $checkDate->getTimestamp();
			if ($checkDate != 0) {
				$checkDate = DateTime::createFromFormat('d-m-Y H:i', $checkDate);
				$checkDate  = $checkDate->getTimestamp();
			} else {
				$checkDate = NULL;
			}
			$model = SuppCheck::findOne($suppCheckId);
			$oldAttributes = $model->getOldAttributes();

			// Заполните модель данными из формы
			$model->checkSum = $checkAmount;
			$model->checkNumber = $checkNumber;
			$model->dateOfpayment = $checkDate;
			// var_dump($oldAttributes);
			// die;
			// Сохраните модель
			if ($model->save()) {
				$newAttributes = $model->getAttributes();
				// Логирование изменений в модели
				foreach ($oldAttributes as $attribute => $oldValue) {
					$newValue = $newAttributes[$attribute];
					if ($oldValue != $newValue) {
						$logChange = new LogChanges();
						$logChange->model_name = "Таблица счетов";
						$logChange->model_id = $model->id;
						$logChange->attribute_name = $attribute;
						$logChange->old_value = (string)$oldValue;
						$logChange->new_value = (string)$newValue;
						$logChange->changed_at = strtotime(date('Y-m-d H:i:s'));
						$logChange->who = Yii::$app->user->identity->name;
						$logChange->save();
					}
				}
				$checkObj = Check::findOne($checkID);

				$result = $this->calculateProfit($checkObj);
				$checkObj->profit = $result['profit'];
				$checkObj->save(false);
				Yii::$app->response->format = Response::FORMAT_JSON;
				return [
					'success' => true,
				];
			} else {
				return [
					'success' => false,
				];
			}
		}
		$stage_comment = Yii::$app->request->post('stage_comment');
		if (isset($stage_comment)) {
			$checkId = Yii::$app->request->post('checkId');
			$model = Check::findOne($checkId);
			$oldAttributes = $model->getOldAttributes();
			$model->stage_comment = $stage_comment;
			if ($model->save()) {
				$newAttributes = $model->getAttributes();
				// Логирование изменений в модели
				foreach ($oldAttributes as $attribute => $oldValue) {
					$newValue = $newAttributes[$attribute];
					if ($oldValue != $newValue) {
						$logChange = new LogChanges();
						$logChange->model_name = "app\models\Check";
						$logChange->model_id = $model->id;
						$logChange->attribute_name = $attribute;
						$logChange->old_value = (string)$oldValue;
						$logChange->new_value = (string)$newValue;
						$logChange->changed_at = strtotime(date('Y-m-d H:i:s'));
						$logChange->who = Yii::$app->user->identity->name;
						$logChange->save();
					}
				}

				Yii::$app->response->format = Response::FORMAT_JSON;
				return [
					'success' => true,
				];
			} else {
				return [
					'success' => false,
				];
			}
		}
		$comment = Yii::$app->request->post('comment');
		if (isset($comment)) {
			$checkId = Yii::$app->request->post('checkId');
			$model = Check::findOne($checkId);
			$oldAttributes = $model->getOldAttributes();
			$model->comment = $comment;
			if ($model->save()) {
				$newAttributes = $model->getAttributes();
				// Логирование изменений в модели
				foreach ($oldAttributes as $attribute => $oldValue) {
					$newValue = $newAttributes[$attribute];
					if ($oldValue != $newValue) {
						$logChange = new LogChanges();
						$logChange->model_name = "app\models\Check";
						$logChange->model_id = $model->id;
						$logChange->attribute_name = $attribute;
						$logChange->old_value = (string)$oldValue;
						$logChange->new_value = (string)$newValue;
						$logChange->changed_at = strtotime(date('Y-m-d H:i:s'));
						$logChange->who = Yii::$app->user->identity->name;
						$logChange->save();
					}
				}

				Yii::$app->response->format = Response::FORMAT_JSON;
				return [
					'success' => true,
				];
			} else {
				return [
					'success' => false,
				];
			}
		}

		$dateDelivery = Yii::$app->request->post('dateDelivery');
		if (isset($dateDelivery)) {
			$checkId = Yii::$app->request->post('checkId');
			$model = Check::findOne($checkId);
			$oldAttributes = $model->getOldAttributes();
			$model->dateDelivery = $dateDelivery;
			$dateDelivery = DateTime::createFromFormat('d-m-Y H:i', $dateDelivery);
			$model->dateDelivery  = $dateDelivery->getTimestamp();
			if ($model->save()) {
				$newAttributes = $model->getAttributes();
				// Логирование изменений в модели
				foreach ($oldAttributes as $attribute => $oldValue) {
					$newValue = $newAttributes[$attribute];
					if ($oldValue != $newValue) {
						$logChange = new LogChanges();
						$logChange->model_name = "app\models\Check";
						$logChange->model_id = $model->id;
						$logChange->attribute_name = $attribute;
						$logChange->old_value = (string)$oldValue;
						$logChange->new_value = (string)$newValue;
						$logChange->changed_at = strtotime(date('Y-m-d H:i:s'));
						$logChange->who = Yii::$app->user->identity->name;
						$logChange->save();
					}
				}

				Yii::$app->response->format = Response::FORMAT_JSON;
				return [
					'success' => true,
				];
			} else {
				return [
					'success' => false,
				];
			}
		}
	}

	/// приватная секция
	// функция логирования создания нового объекта
	public function logCreateObj($model)
	{
		$logModel = new LogChanges();
		$logModel->model_name = $logModel->getModelName($model::className());
		$logModel->model_id = $model->id;
		$logModel->attribute_name = 'Создание записи';
		$logModel->old_value = null;
		$logModel->new_value = null;
		$logModel->who = Yii::$app->user->identity->name;
		$logModel->changed_at = date('Y-m-d H:i:s');
		$logModel->save();
	}
	// функция логирования изменений в произвольной модель
	// на вход подается модель и два ассоциативных масива. Старые атрибуты и Новые атрибуты.
	public function loggerChanges($model, $oldValues, $newValues)
	{
		foreach ($oldValues as $attribute => $oldValue) {
			if ($oldValue != $newValues[$attribute]) {
				$logModel = new LogChanges();
				$logModel->model_name = $logModel->getModelName($model::className());
				$logModel->model_id = $model->id;
				$logModel->attribute_name = $attribute;
				$logModel->old_value = $oldValue;
				$logModel->who = Yii::$app->user->identity->name;
				$logModel->new_value = $newValues[$attribute];
				$logModel->changed_at = strtotime(date('Y-m-d H:i:s'));
				$logModel->save(false);
			}
		}
	}
	public function calculateProfit($check)
	{
		$result = [];

		// Парсим строку с id элементов модели Product
		$productIds = explode(',', $check->name);

		// Получаем сумму значений поля price объектов модели Product
		// $totalPrice = Product::find()
		// 	->where(['id' => $productIds])
		// 	->sum('price');
		$totalPrice = Product::find()
			->select(['SUM(price * quantity)']) // использование SUM и умножение price на quantity
			->where(['id' => $productIds])
			->scalar(); // получение единственного значения
		// Получаем сумму значений поля checkSum объектов модели suppCheck
		//$idproviders = [];
		if(isset($check->provider)){
		   $idproviders = explode(',', $check->provider);
		}else{
		    $idproviders = [];
		}
		$totalSuppCheckSum = suppCheck::find()
			->where(['id' => $idproviders])
			->sum('checkSum');

		// Рассчитываем прибыль
		$profit = $totalPrice - $check->costDelivery - $totalSuppCheckSum;

		$result['totalPrice'] = $totalPrice;
		$result['totalSuppCheckSum'] = $totalSuppCheckSum;
		$result['profit'] = $profit;

		return $result;
	}
}