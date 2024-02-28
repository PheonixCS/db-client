<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%stage}}`.
 */
class m231230_143203_create_stage_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%stage}}', [
			'id' => $this->primaryKey(),
			'name' => $this->string()->notNull(),
			'color' => $this->string()->notNull(),
		]);

		// Заполните таблицу стадий данными по умолчанию или используйте миграцию для этого.

		// Пример:
		$this->insert('{{%stage}}', [
			'name' => 'Stage 1',
			'color' => '#FF0000',
		]);
		$this->insert('{{%stage}}', [
			'name' => 'Stage 2',
			'color' => '#00FF00',
		]);
		// и т.д.
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%stage}}');
	}
}
