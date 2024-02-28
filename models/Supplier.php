<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "suppliers".
 *
 * @property int $id
 * @property string|null $company_name
 * @property string|null $website
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $working_hours
 * @property string|null $warehouse_address
 * @property string|null $manager
 * @property string|null $b2b_login
 * @property string|null $b2b_password
 * @property string|null $delivery
 * @property string|null $return_policy
 * @property string|null $payment_method
 * @property string|null $vat_handling
 */
class Supplier extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'suppliers';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
            [['is_deleted'], 'required'],
			[
                ['company_name', 'website', 'phone', 'email', 'working_hours', 'warehouse_address', 'manager', 'b2b_login', 'b2b_password', 'delivery', 'return_policy', 'payment_method', 'vat_handling'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'company_name' => 'Company Name',
			'website' => 'Website',
			'phone' => 'Phone',
			'email' => 'Email',
			'working_hours' => 'Working Hours',
			'warehouse_address' => 'Warehouse Address',
			'manager' => 'Manager',
			'b2b_login' => 'B2b Login',
			'b2b_password' => 'B2b Password',
			'delivery' => 'Delivery',
			'return_policy' => 'Return Policy',
			'payment_method' => 'Payment Method',
			'vat_handling' => 'Vat Handling',
		];
	}
}
