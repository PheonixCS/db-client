<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%delivery_type}}`.
 */
class m240106_073020_create_delivery_type_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%delivery_type}}', [
			'id' => $this->primaryKey(),
			'name' => $this->string()->notNull(),
			'color' => $this->string(),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%delivery_type}}');
	}
}
