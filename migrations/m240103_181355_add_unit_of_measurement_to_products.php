<?php

use yii\db\Migration;

/**
 * Class m240103_181355_add_unit_of_measurement_to_products
 */
class m240103_181355_add_unit_of_measurement_to_products extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('products', 'unit_of_measurement', $this->string()->after('price'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn('products', 'unit_of_measurement');
	}

	/*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240103_181355_add_unit_of_measurement_to_products cannot be reverted.\n";

        return false;
    }
    */
}
