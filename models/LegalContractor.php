<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * This is the model class for table "legal_contractors".
 *
 * @property int $id
 * @property string $company
 * @property string|null $company_consignee
 * @property string|null $company_address_consignee
 * @property string|null $iin
 * @property string|null $legal_address
 * @property bool|null $same_address
 * @property string|null $actual_address
 * @property string $contact_person
 * @property string $phone1
 * @property string|null $phone2
 * @property string|null $email
 * @property int|null $check_id
 */
class LegalContractor extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'legal_contractors';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['company', 'contact_person', 'phone1', 'is_deleted'], 'required'],
			[['same_address'], 'boolean'],
			[['check_id'], 'integer'],
			[['company', 'company_consignee', 'company_address_consignee', 'iin', 'legal_address', 'actual_address', 'contact_person', 'phone1', 'phone2', 'email'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'company' => 'Company',
			'company_consignee' => 'Company Consignee',
			'company_address_consignee' => 'Company Address Consignee',
			'iin' => 'Iin',
			'legal_address' => 'Legal Address',
			'same_address' => 'Same Address',
			'actual_address' => 'Actual Address',
			'contact_person' => 'Contact Person',
			'phone1' => 'Phone1',
			'phone2' => 'Phone2',
			'email' => 'Email',
			'check_id' => 'Check ID',
		];
	}
	public static function getLegalDataProvider()
	{
		return new ActiveDataProvider([
			'query' => self::find()->groupBy('iin'),
		]);
	}
	public static function searchByCheckId($checkId)
	{
		return self::find()
			->where(['check_id' => ',' . $checkId . ',']) // Добавляем запятые перед и после значения
			->orWhere(['LIKE', 'check_id', ',' . $checkId . ',']) // Точное совпадение с началом или концом строки
			->orWhere(['LIKE', 'check_id', ',' . $checkId . ',%', false]) // Точное совпадение с началом строки и содержание значения в середине
			->orWhere(['LIKE', 'check_id', '%,' . $checkId . ',%', false]) // Точное совпадение внутри строки
			->all();
	}
}
