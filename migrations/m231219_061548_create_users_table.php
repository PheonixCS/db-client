<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m231219_061548_create_users_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%users}}', [
			'id' => $this->primaryKey(),
			'name' => $this->string(256)->notNull(),
			'password' => $this->string(256)->notNull(),
			'email' => $this->string(256)->notNull(),
			'authKey' => $this->string(256)->null(),
			'is_admin' => $this->integer()->defaultValue(0),
			'created_at' => $this->timestamp(),
			'update_at' => $this->timestamp(),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%users}}');
	}
}
