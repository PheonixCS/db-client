<?php

use yii\db\Migration;

/**
 * Class m240102_173441_update_check_table
 */
class m240102_173441_update_check_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('check', 'payment_order', $this->string()); // платежное поручение
		$this->addColumn('check', 'payment_order_date', $this->timestamp()); // дата платежного поручения 
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn('check', 'payment_order');
		$this->dropColumn('check', 'payment_order_date');
	}

	/*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240102_173441_update_check_table cannot be reverted.\n";

        return false;
    }
    */
}
