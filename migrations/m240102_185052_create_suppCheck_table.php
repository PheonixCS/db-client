<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%suppCheck}}`.
 */
class m240102_185052_create_suppCheck_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%suppCheck}}', [
			'id' => $this->primaryKey(),
			'suppliers' => $this->string()->notNull(),
			'checkSum' => $this->decimal(10, 2)->notNull(),
			'checkNumber' => $this->integer()->notNull(),
			'dateOfpayment' => $this->date()->notNull(),
		]);
		$this->createIndex(
			'idx-suppCheck-suppliers',
			'suppCheck',
			'suppliers'
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropIndex(
			'idx-suppCheck-suppliers',
			'suppCheck'
		);
		$this->dropTable('{{%suppCheck}}');
	}
}
