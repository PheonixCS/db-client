<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%log_changes}}`.
 */
class m240103_002218_create_log_changes_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%log_changes}}', [
			'id' => $this->primaryKey(),
			'model_name' => $this->string(),
			'model_id' => $this->integer(),
			'attribute_name' => $this->string(),
			'old_value' => $this->text(),
			'new_value' => $this->text(),
			'changed_at' => $this->datetime(),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%log_changes}}');
	}
}
