<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class Check extends ActiveRecord
{
	public $query;
	public function __construct(array $config = [])
	{
		parent::__construct($config);
		$this->query = new Query();
	}
	public static function tableName()
	{
		return "check";
	}
	public function rules()
	{
		$rules = [
			[
				[
					'payment',
				],
				'required',
				'message' => 'Обязательное поле.'
			],
			[
				[
					'name', 'deliveryAddress', 'delivery_time',
					'comment', 'dateDelivery', 'costDelivery',
					'sending', 'stage', 'provider',
					'numberCheck', 'sum',
					'delivery_type', 'stage_comment', 'payment_order',
					'payment_order_date', 'date_crated',
				],
				'default'
			],
			[
				[
					'numberCheck', 'sum'
				],
				'integer',
				'message' => 'Неверный формат - целое число'
			],
			[
				['costDelivery', 'profit'],
				'number',
				'message' => 'Неверный формат - число'
			],
			[
				['dateDelivery', 'dateOfPayment'],
				'safe',
				'message' => 'Неверный формат - дата'
			],
			'organization' => [['organization'], 'string', 'message' => 'Неверный формат'],
		];
		return $rules;
	}
	public function attributeLabels()
	{
		return [
			'name' => 'Наименование',
			'deliveryAddress' => 'Адрес доставки',
			'comment' => 'Комментарий',
			'dateDelivery' => 'Дата время доставки',
			'dateOfPayment' => 'Дата оплаты',
			'who' => 'Кто',
			'costDelivery' => 'Стоимость доставки',
			'sending' => 'Отпр',
			'provider' => 'Поставщики',
			'numberCheck' => 'Cчет',
			'sum' => 'Сумма',
			'profit' => 'Прибыль',
			'organization' => 'Организация',
			'stage' => 'Стадия',
			'payment' => "Оплата",
			'delivery_time' => "Время доставки",
			'delivery_type' => "Тип доставки",
			'stage_comment' => "Коментарий к стадии доставки",
			'payment_order' => "Платежное поручение",
			'payment_order_date' => "Дата платежного поручения",
			// Другие поля модели Check с указанием их наименований на русском языке
		];
	}
	public function getModelName()
	{
		return "Таблица Счетов";
	}
	public function beforeDelete()
	{
		// Добавьте свою логику перед удалением записи из базы данных
		// Например, удаление связанных данных или выполнение других действий
		return parent::beforeDelete();
	}
	public static function findOne($id)
	{
		return self::find()->where(['id' => $id])->one();
	}
	public function getStageColor()
	{
		$stage = Stage::findOne(['id' => $this->stage]);
		if ($stage !== null) {
			return $stage->color;
		}
		return null;
	}
	public function getStageName()
	{
		$stage = Stage::findOne(['id' => $this->stage]);
		if ($stage !== null) {
			return $stage->name;
		}
		return null;
	}
	public function search($params)
	{
		$query = Check::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => 10, // Количество элементов на странице
			],
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		// Дополнительные фильтры поиска
		$query->andFilterWhere(['like', 'attribute', $this->attribute]);

		return $dataProvider;
	}

	public function formatIsDeleted($value, $model, $key)
	{
		if ($model->is_deleted) {
			return '<span style="text-decoration: line-through;">' . $value . '</span>';
		}
		return $value;
	}
	public function getMatchingProductIds($name)
	{
		$productIds = [];

		$productIds  = explode(',', $name);

		return $productIds;
	}
}
