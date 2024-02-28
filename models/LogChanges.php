<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "log_changes".
 *
 * @property int $id
 * @property string $model_name
 * @property int $model_id
 * @property string $attribute_name
 * @property string|null $old_value
 * @property string|null $new_value
 * @property string $changed_at
 */
class LogChanges extends ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'log_changes';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['model_name', 'model_id', 'attribute_name', 'who'], 'required'],
			[['model_id'], 'integer'],
			[['old_value', 'new_value'], 'string'],
			[['changed_at'], 'safe'],
			[['model_name', 'attribute_name'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'model_name' => 'Название таблицы',
			'model_id' => 'ID элемента таблицы',
			'attribute_name' => 'Измененное поле',
			'old_value' => 'Old Value',
			'new_value' => 'New Value',
			'changed_at' => 'Изменено',
			'code' => 'Код',
			'who' => 'Кто',
		];
	}
	public function getModelName($model)
	{
		$modelNames = [
			'app\models\Check' => 'Таблица счетов',
			'app\models\suppCheck' => 'Таблица счетов',
			'app\models\Product' => 'Таблица счетов(Товары)',
			// Добавьте другие модели и их названия в соответствии со своей логикой
		];

		return isset($modelNames[$model]) ? $modelNames[$model] : $model;
	}
}
