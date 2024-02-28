<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use Yii;

/**
 * This is the model class for table "individual_contractors".
 *
 * @property int $id
 * @property string $contact_person
 * @property string $phone1
 * @property string|null $phone2
 * @property string|null $email
 */
class IndividualContractor extends \yii\db\ActiveRecord
{

	public $contractor_type;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'individual_contractors';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['contact_person', 'phone1'], 'required'],
			[['contact_person', 'phone1', 'phone2', 'email'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'contact_person' => 'Contact Person',
			'phone1' => 'Phone1',
			'phone2' => 'Phone2',
			'email' => 'Email',
		];
	}
	public static function getIndividualDataProvider()
	{
		return new ActiveDataProvider([
			'query' => self::find(),
		]);
	}
}
