<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%captcha_settings}}`.
 */
class m231230_180353_create_captcha_settings_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%captcha_settings}}', [
			'id' => $this->primaryKey(),
			'site_key' => $this->string()->notNull(),
			'secret_key' => $this->string()->notNull()
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%captcha_settings}}');
	}
}
