<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%check}}`.
 */
class m231219_105502_create_check_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%check}}', [
			'id' => $this->primaryKey(),
			'who' => $this->string()->notNull(), /////////////////////////
			'name' => $this->string()->notNull(),																		//
			'organization' => $this->string(),																			//
			'deliveryAddress' => $this->string()->notNull(),												//
			'comment' => $this->string()->notNull(),																//  одно и тоже? 
			'dateDelivery' => $this->timestamp()->notNull(),												//
			'costDelivery' => $this->money()->notNull(),														//
			'sending' => $this->string()->notNull(),																//
			'stage' => $this->string()->notNull(),																	//
			'provider' => $this->string()->notNull(),																//
			'numberCheck' => $this->integer()->defaultValue(1000), ///////////////////
			'sum' => $this->integer(),
			'dateOfPayment' => $this->timestamp()->notNull(),
			'profit' => $this->money()->notNull(),
			'payment' => $this->string()->notNull(),
			// добавить поле контр агент + функционал связанный с контр агентами.
			// платежное поручение.
			// дата платежного поручения.
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%check}}');
	}
}
