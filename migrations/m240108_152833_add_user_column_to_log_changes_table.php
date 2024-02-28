<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%log_changes}}`.
 */
class m240108_152833_add_user_column_to_log_changes_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('log_changes', 'who', $this->string()->after('changed_at'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn('log_changes', 'who');
	}
}
