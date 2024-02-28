<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class StageSearch extends Stage
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id'], 'integer'],
			[['name', 'color'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		return Model::scenarios();
	}

	/**
	 * Создает экземпляр провайдера данных с применением параметров поиска.
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = Stage::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);

		if (!$this->validate()) {
			return $dataProvider;
		}

		$query->andFilterWhere([
			'id' => $this->id,
		]);

		$query->andFilterWhere(['like', 'name', $this->name])
			->andFilterWhere(['like', 'color', $this->color]);

		return $dataProvider;
	}
}
