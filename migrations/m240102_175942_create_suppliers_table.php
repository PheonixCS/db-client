<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%suppliers}}`.
 */
class m240102_175942_create_suppliers_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%suppliers}}', [
			'id' => $this->primaryKey(),
			'company_name' => $this->string(),
			'website' => $this->string(),
			'phone' => $this->string(),
			'email' => $this->string(),
			'working_hours' => $this->string(), // расписание работы
			'warehouse_address' => $this->string(), // адрес склада
			'manager' => $this->string(),
			'b2b_login' => $this->string(),
			'b2b_password' => $this->string(),
			'delivery' => $this->string(), // доставка
			'return_policy' => $this->string(), // возврат товара
			'payment_method' => $this->string(), // форма оплаты
			'vat_handling' => $this->string(), // работа с НДС
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%suppliers}}');
	}
}
