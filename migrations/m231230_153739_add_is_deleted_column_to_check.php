<?php

use yii\db\Migration;

/**
 * Class m231230_153739_add_is_deleted_column_to_check
 */
class m231230_153739_add_is_deleted_column_to_check extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('{{%check}}', 'is_deleted', $this->boolean()->defaultValue(false));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m231230_153739_add_is_deleted_column_to_check cannot be reverted.\n";

		return false;
	}

	/*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231230_153739_add_is_deleted_column_to_check cannot be reverted.\n";

        return false;
    }
    */
}
