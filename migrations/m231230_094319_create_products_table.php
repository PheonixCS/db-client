<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%products}}`.
 */
class m231230_094319_create_products_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%products}}', [
			'id' => $this->primaryKey(),
			'code' => $this->string()->notNull(),
			'name' => $this->string()->notNull(),
			'price' => $this->decimal(10, 2)->notNull(),
			'quantity' => $this->integer()->notNull(),
			'check_id' => $this->integer()->notNull(),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%products}}');
	}
}
