<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Product extends ActiveRecord
{
	public static function tableName()
	{
		return 'products';
	}

	public function rules()
	{
		return [
			[['code', 'quantity', 'price', 'check_id'], 'required', 'message' => 'Обязательбное поле'],
			[['name'], 'default'],
			[['quantity', 'check_id'], 'integer', 'message' => 'Неверный тип данных'],
			[['price'], 'number', 'message' => 'ошибка 3'],
			[['code', 'name', 'unit_of_measurement'], 'string', 'max' => 255, 'message' => 'Неверный тип данных'],
			[['amount'], 'double', 'message' => 'Неверный тип данных'],
		];
	}

	public function attributeLabels()
	{
		return [
			'code' => 'Код товара',
			'name' => 'Наименование',
			'quantity' => 'Количество',
			'price' => 'Цена',
			'check_id' => 'Индекс счета',
			'unit_of_measurement' => 'Единицы измерения'
		];
	}

	public function getCheck()
	{
		return $this->hasOne(Check::class, ['id' => 'check_id']);
	}
	public static function searchByCheckId($checkId)
	{
		return self::find()
			->where(['check_id' => $checkId])
			->all();
	}

	public function afterDelete()
	{
		parent::afterDelete();

		// Добавьте свою логику для удаления связанных записей, если необходимо
	}
}
