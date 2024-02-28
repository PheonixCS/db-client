<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Check;
use \yii\helpers\VarDumper;
use yii\data\ArrayDataProvider;
use kartik\daterange\DateRangeBehavior;

class CheckSearch extends Check
{
	/**
	 * {@inheritdoc}
	 */

	public $pageSize = 5;

	public $createTimeRange;
	public $createTimeStart;
	public $createTimeEnd;

	public function behaviors()
	{
		return [
			[
				'class' => DateRangeBehavior::class,
				'attribute' => 'dateDelivery',
				'dateStartAttribute' => 'createTimeStart',
				'dateEndAttribute' => 'createTimeEnd',
			]
		];
	}

	public function rules()
	{
		return [
			[['createTimeRange'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
			[$this->attributes(), 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = Check::find();
		//$query->andFilterWhere(['like', 'attribute_name', $this->attribute_name]);
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);
		if (!$this->validate()) {
			return $dataProvider;
		}

		$query->andFilterWhere([
			'payment' => $this->payment,
			'numberCheck' => $this->numberCheck,
			'sum' => $this->sum,
		]);
		$query->andFilterWhere(['like', 'who', $this->who])
			->andFilterWhere(['like', 'deliveryAddress', $this->deliveryAddress])
			->andFilterWhere(['like', 'comment', $this->comment])
			//->andFilterWhere(['like', 'dateDelivery', $this->dateDelivery])
			->andFilterWhere(['like', 'costDelivery', $this->costDelivery])
			->andFilterWhere(['like', 'sending', $this->sending])
			->andFilterWhere(['like', 'profit', $this->profit])
            //->andFilterWhere(['like', 'stage_comment',  $this->stage_comment])
			->andFilterWhere(['like', 'costDelivery', $this->costDelivery])
			->andFilterWhere(['like', 'deliveryAddress', $this->deliveryAddress]);

        if($this->comment) { 
            $this->comment = (string)$this->comment; 
            $comment1 = mb_strtoupper(mb_substr($this->comment, 0, 1)) . mb_substr($this->comment, 1); 
            $comment2 = mb_strtolower(mb_substr($this->comment, 0, 1)) . mb_substr($this->comment, 1); 
            $query->where(['like', 'comment', '%' . $comment1 . '%', false]);
            $query->orwhere(['like', 'comment', '%' . $comment2 . '%', false]);
        }
        if($this->stage_comment) { 
            $this->stage_comment = (string)$this->stage_comment; 
            $stComment_1 = mb_strtoupper(mb_substr($this->stage_comment, 0, 1)) . mb_substr($this->stage_comment, 1); 
            $stComment_2 = mb_strtolower(mb_substr($this->stage_comment, 0, 1)) . mb_substr($this->stage_comment, 1); 
            $query->andFilterWhere(['or', ['like', 'stage_comment', $stComment_1], 
                                        'stage_comment LIKE "%,' . $stComment_1 . ',%"', 
                                        ['like', 'stage_comment', $stComment_2]]);
        }
		if ($this->dateDelivery) {
			// $selectedDate = strtotime($this->dateDelivery);
			// $startTimestamp = mktime(0, 0, 0, date('n', $selectedDate), date('j', $selectedDate), date('Y', $selectedDate));
			// $endTimestamp = mktime(23, 59, 59, date('n', $selectedDate), date('j', $selectedDate), date('Y', $selectedDate));
			// // VarDumper::dump($startTimestamp);
			// // VarDumper::dump("   ");
			// // VarDumper::dump($endTimestamp);
			// // die;
			// $query->andWhere(['between', 'dateDelivery', $startTimestamp, $endTimestamp]);
			$query->andFilterWhere(['>=', 'dateDelivery', $this->createTimeStart])
				->andFilterWhere(['<', 'dateDelivery', $this->createTimeEnd]);
		}
		if ($this->name) {
			$productIds = Product::find()->select('id')->where(['like', 'name', '%' . (string)$this->name . '%', false])->column();
			$conditions = [];
			foreach ($productIds as $productId) {
				$conditions[] = 'name LIKE "%,' . $productId . ',%" OR name LIKE "' . $productId . ',%" OR name LIKE "%,' . $productId . '"';
			}
			$query->andWhere(implode(' OR ', $conditions));
		}
		if ($this->organization) {
            $this->organization = (string)$this->organization;
            $org_1 = mb_strtoupper(mb_substr($this->organization, 0, 1)) . mb_substr($this->organization, 1);
            $org_2 = mb_strtolower(mb_substr($this->organization, 0, 1)) . mb_substr($this->organization, 1);
			$orgCompanyIds = LegalContractor::find()->select('id')
				->where(['like', 'company', '%' . $org_1 . '%', false])
                ->orwhere(['like', 'company', '%' . $org_2 . '%', false])
				->orWhere(['like', 'contact_person', '%' . $org_1 . '%', false])
                ->orWhere(['like', 'contact_person', '%' . $org_2 . '%', false])
				->orWhere(['like', 'Phone1', '%' . (string)$this->organization . '%', false])
				->orWhere(['like', 'email', '%' . (string)$this->organization . '%', false])
				->column();
			$conditions = [];
			foreach ($orgCompanyIds as $orgId) {
				$conditions[] = 'organization = "' . $orgId . '"';
			}
			if ($conditions == []) {
				$query->andWhere('1=0');
			} else {
				$query->andWhere(implode(' OR ', $conditions));
			}
		}
		if ($this->delivery_type) {
			$delivery_typeIds = DeliveryType::find()->select('id')
				->where(['like', 'name', '%' . (string)$this->delivery_type . '%', false])
				->column();
			$conditions = [];
			foreach ($delivery_typeIds as $delivery_typeId) {
				$conditions[] = 'delivery_type = "' . $delivery_typeId . '"';
			}
			if ($conditions == []) {
				$query->andWhere('1=0');
			} else {
				$query->andWhere(implode(' OR ', $conditions));
			}
		}
		if ($this->stage) {
			$stageIds = Stage::find()->select('id')
				->where(['like', 'name', '%' . (string)$this->stage . '%', false])
				->column();
			$conditions = [];
			foreach ($stageIds as $stageId) {
				$conditions[] = 'stage = "' . $stageId . '"';
			}
			if ($conditions == []) {
				$query->andWhere('1=0');
			} else {
				$query->andWhere(implode(' OR ', $conditions));
			}
		}
		if ($this->provider) {
            $prov_1 = mb_strtoupper(mb_substr((string)$this->provider, 0, 1)) . mb_substr((string)$this->provider, 1);
            $prov_2 = mb_strtolower(mb_substr((string)$this->provider, 0, 1)) . mb_substr((string)$this->provider, 1);            
			$supplierIds = Supplier::find()->select('id')
				->where(['like', 'company_name', '%' . $prov_1 . '%', false])
                ->orwhere(['like', 'company_name', '%' . $prov_2 . '%', false])
				->column();
			$suppCheckIds = SuppCheck::find()
				->select('id')
				->where(['in', 'suppliers', $supplierIds])
				->column();
			$conditions = [];
			foreach ($suppCheckIds  as $suppCheckId) {
				$conditions[] = 'provider LIKE "%,' . $suppCheckId . ',%" OR provider LIKE "' . $suppCheckId . ',%" OR provider LIKE "%,' . $suppCheckId . '"';
			}
			if ($conditions == []) {
				$query->andWhere('1=0');
			} else {
				$query->andWhere(implode(' OR ', $conditions));
			}
		}
		//VarDumper::dump($params);
		return $dataProvider;
	}
	public function attributes()
	{
		return array_merge(parent::attributes(), [
			'numberCheck',
		]);
	}
}
