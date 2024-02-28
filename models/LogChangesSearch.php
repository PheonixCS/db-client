<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LogChanges;
use kartik\daterange\DateRangeBehavior;

class LogChangesSearch extends Model
{
	public $model_name;
	public $model_id;
	public $attribute_name;
	public $old_value;
	public $new_value;
	public $changed_at;
	public $who;

	public $createTimeRange;
	public $createTimeStart;
	public $createTimeEnd;

	public function behaviors()
	{
		return [
			[
				'class' => DateRangeBehavior::class,
				'attribute' => 'changed_at',
				'dateStartAttribute' => 'createTimeStart',
				'dateEndAttribute' => 'createTimeEnd',
			]
		];
	}

	public function rules()
	{
		return [
			[['model_name', 'attribute_name', 'old_value', 'new_value', 'changed_at', 'who'], 'string'],
			[['model_id'], 'integer'],
			[
				['changed_at', 'attribute_name', 'who'], 'safe'
			],
			[['createTimeRange'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
		];
	}

	public function search($params)
	{
		$query = LogChanges::find(); // Замените `YourLogChangesModel` на имя вашей модели данных

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		$this->load($params);
		if (!$this->validate()) {
			return $dataProvider;
		}

		$query->andFilterWhere([
			'model_name' => $this->model_name,
			'attribute_name' => $this->attribute_name,


		]);
		if ($this->changed_at) {
			// var_dump($this->createTimeStart);
			// var_dump("   ");
			// var_dump($this->createTimeEnd);
			// die;
			$query->andFilterWhere(['>=', 'changed_at', $this->createTimeStart])
				->andFilterWhere(['<', 'changed_at', $this->createTimeEnd]);
		}



		$query->andFilterWhere(['like', 'attribute_name', $this->attribute_name])
			->andFilterWhere(['like', 'who', $this->who]);

		return $dataProvider;
	}
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'model_name' => 'Название таблицы',
			'model_id' => 'ID элемента таблицы',
			'attribute_name' => 'Измененное поле',
			'old_value' => 'Old Value',
			'new_value' => 'New Value',
			'changed_at' => 'Изменено',
			'code' => 'Код',
		];
	}
	public function getAttributeList()
	{
		// // преобразуйте обратно в оригинальные значения атрибута
		// $originalValues = [
		// 	'Поставщик' => 'provider',
		// 	'Действия' => 'is_deleted',
		// 	'Комментарий' => 'comment',
		// 	'Дата/время' => 'change_at'
		// 	// другие соответствия значений
		// ];

		// преобразуйте обратно в оригинальные значения атрибута
		$originalValues = [
			'' => 'Сбросить',
			'provider' => 'Поставщик',
			'is_deleted' => 'Действия',
			'comment' => 'Комментарий',
			'code' => 'Код',
			'delivery_type' => 'Тип доставки',
			'stage_comment' => 'Комментарий к стадии',
			'payment_order_date' => 'Дата платежного поручения',
			'delivery_time' => 'Время доставки',
			'deliveryAddress' => 'Адрес доставки',
			'dateDelivery' => 'Дата доставки',
			'costDelivery' => 'Стоимость доставки',
			'checkSum' => 'Сумма счета поставки',
			'dateOfpayment' => 'Дата платежа',
			'checkNumber' => 'Номер счета поставки',
			'stage' => 'Стадия',
			'price' => 'Цена товара',
			'quantity' => 'Количество товара',
			'payment_order' => 'Платежное поручение',
			'name' => 'Товары',
			'sum' => 'Сумма счета',
			'payment' => 'Оплата',
			'organization' => 'Контрагент',
			'profit' => 'Прибыль',
		];

		return $originalValues;
	}
}
