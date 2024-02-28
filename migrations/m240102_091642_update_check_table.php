<?php

use yii\db\Migration;

/**
 * Class m240102_091642_update_check_table
 */
class m240102_091642_update_check_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		// Добавление поля "время доставки" (часы, минуты)
		$this->addColumn('check', 'delivery_time', $this->time()->after('organization_id'));

		// Добавление поля "тип доставки"
		$this->addColumn('check', 'delivery_type', $this->string()->after('delivery_time'));

		// Добавление поля "комментарий к стадии"
		$this->addColumn('check', 'stage_comment', $this->text()->after('stage_id'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		// Удаление поля "комментарий к стадии"
		$this->dropColumn('check', 'stage_comment');

		// Удаление поля "тип доставки"
		$this->dropColumn('check', 'delivery_type');

		// Удаление поля "время доставки"
		$this->dropColumn('check', 'delivery_time');
	}

	/*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240102_091642_update_check_table cannot be reverted.\n";

        return false;
    }
    */
}
