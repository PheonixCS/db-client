<?php

use yii\db\Migration;

/**
 * Class m240103_162656_add_type_to_legal_contractors
 */
class m240103_162656_add_type_to_legal_contractors extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('legal_contractors', 'type', $this->string()->after('name'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn('legal_contractors', 'type');
	}

	/*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240103_162656_add_type_to_legal_contractors cannot be reverted.\n";

        return false;
    }
    */
}
