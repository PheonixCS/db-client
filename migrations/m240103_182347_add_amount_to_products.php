<?php

use yii\db\Migration;

/**
 * Class m240103_182347_add_amount_to_products
 */
class m240103_182347_add_amount_to_products extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('products', 'amount', $this->decimal(10, 2)->after('unit_of_measurement'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn('products', 'amount');
	}

	/*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240103_182347_add_amount_to_products cannot be reverted.\n";

        return false;
    }
    */
}
