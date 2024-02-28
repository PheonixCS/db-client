<?php

namespace app\models;

use yii\db\ActiveRecord;

class Stage extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'stage';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['name', 'color', 'is_deleted'], 'required'],
			[['name', 'color'], 'string', 'max' => 255],
		];
	}

	public function getColorStyle()
	{
		return 'background-color: ' . $this->color . ';';
	}
}
