<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%contractors}}`.
 */
class m240101_162057_create_contractors_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		// Таблица для физических лиц
		$this->createTable('{{%individual_contractors}}', [
			'id' => $this->primaryKey(),
			'contact_person' => $this->string()->notNull(),
			'phone1' => $this->string()->notNull(),
			'phone2' => $this->string(),
			'email' => $this->string(),
			'check_id' => $this->string(),
		]);

		// Таблица для юридических лиц
		$this->createTable('{{%legal_contractors}}', [
			'id' => $this->primaryKey(),
			'company' => $this->string()->notNull(),
			'company_consignee' => $this->string(),
			'company_address_consignee' => $this->string(),
			'iin' => $this->string(),
			'legal_address' => $this->string(),
			'same_address' => $this->boolean()->defaultValue(1),
			'actual_address' => $this->string(),
			'contact_person' => $this->string()->notNull(),
			'phone1' => $this->string()->notNull(),
			'phone2' => $this->string(),
			'email' => $this->string(),
			'check_id' => $this->string(),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%individual_contractors}}');
		$this->dropTable('{{%legal_contractors}}');
	}
}
