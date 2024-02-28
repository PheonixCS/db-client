<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%smtp_settings}}`.
 */
class m231231_095146_create_smtp_settings_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%smtp_settings}}', [
			'id' => $this->primaryKey(),
			'host' => $this->string()->notNull(),
			'username' => $this->string()->notNull(),
			'password' => $this->string()->notNull(),
			'port' => $this->integer()->notNull(),
			'encryption' => $this->string(),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%smtp_settings}}');
	}
}
