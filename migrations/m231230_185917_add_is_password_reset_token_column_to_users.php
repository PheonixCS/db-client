<?php

use yii\db\Migration;

/**
 * Class m231230_185917_add_is_password_reset_token_column_to_users
 */
class m231230_185917_add_is_password_reset_token_column_to_users extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('{{%users}}', 'password_reset_token', $this->string(256)->null());
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m231230_185917_add_is_password_reset_token_column_to_users cannot be reverted.\n";

		return false;
	}

	/*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231230_185917_add_is_password_reset_token_column_to_users cannot be reverted.\n";

        return false;
    }
    */
}
