<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "suppCheck".
 *
 * @property int $id
 * @property string $suppliers
 * @property float $checkSum
 * @property int $checkNumber
 * @property string $dateOfpayment
 */
class suppCheck extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'suppCheck';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['suppliers'], 'required', 'message' => '5'],
			[['checkSum', 'checkNumber', 'dateOfpayment'], 'default'],
			[['checkSum'], 'number', 'message' => '4'],
			[['checkNumber'], 'integer', 'message' => '3'],
			[['dateOfpayment'], 'safe', 'message' => '2'],
			[['suppliers'], 'string', 'max' => 255, 'message' => '1'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'suppliers' => 'Поставщик',
			'checkSum' => 'Сумма счета',
			'checkNumber' => 'Номер счета',
			'dateOfpayment' => 'Дата оплаты',
		];
	}
	public function getModelName()
	{
		return "Таблица связи поставщиков с счетами.";
	}
}
