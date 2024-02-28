<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class SupplierSearch extends Supplier
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['company_name', 'website', 'phone', 'email', 'working_hours', 'warehouse_address', 'manager', 'b2b_login', 'b2b_password', 'delivery', 'return_policy', 'payment_method', 'vat_handling'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Supplier::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'vat_handling', $this->vat_handling]);

        // if($this->company_name) { 
        //     $this->company_name = (string)$this->company_name; 
        //     $company_name1 = mb_strtoupper(mb_substr($this->company_name, 0, 1)) . mb_substr($this->company_name, 1); 
        //     $company_name2 = mb_strtolower(mb_substr($this->company_name, 0, 1)) . mb_substr($this->company_name, 1); 
        //     $query->andFilterWhere(['like', 'company_name', '%' . $company_name1 . '%', false]);
        //     $query->orwhere(['like', 'company_name', '%' . $company_name2 . '%', false]);
        // }
        $query->andFilterWhere(['is_deleted' => 0]);
        if($this->company_name) {
            $company_name1 = mb_strtoupper(mb_substr($this->company_name, 0, 1)) . mb_substr($this->company_name, 1); 
            $company_name2 = mb_strtolower(mb_substr($this->company_name, 0, 1)) . mb_substr($this->company_name, 1); 
            $query->andFilterWhere(['or', ['like', 'company_name', $company_name1], ['like', 'company_name', $company_name2]]);
        } 
        if($this->phone) { 
            $this->phone = (string)$this->phone; 
            $phone1 = mb_strtoupper(mb_substr($this->phone, 0, 1)) . mb_substr($this->phone, 1); 
            $phone2 = mb_strtolower(mb_substr($this->phone, 0, 1)) . mb_substr($this->phone, 1); 
            $query->andFilterWhere(['or', ['like', 'phone', $phone1], ['like', 'phone', $phone2]]);
        }
        if($this->website) { 
            $this->website = (string)$this->website; 
            $website1 = mb_strtoupper(mb_substr($this->website, 0, 1)) . mb_substr($this->website, 1); 
            $website2 = mb_strtolower(mb_substr($this->website, 0, 1)) . mb_substr($this->website, 1); 
            $query->andFilterWhere(['or', ['like', 'website', $website1], ['like', 'website', $website2]]);
        }
        if($this->email) { 
            $this->email = (string)$this->email; 
            $email1 = mb_strtoupper(mb_substr($this->email, 0, 1)) . mb_substr($this->email, 1); 
            $email2 = mb_strtolower(mb_substr($this->email, 0, 1)) . mb_substr($this->email, 1); 
            $query->andFilterWhere(['or', ['like', 'email', $email1], ['like', 'email', $email2]]);
        }
        if($this->working_hours) { 
            $this->working_hours = (string)$this->working_hours; 
            $working_hours1 = mb_strtoupper(mb_substr($this->working_hours, 0, 1)) . mb_substr($this->working_hours, 1); 
            $working_hours2 = mb_strtolower(mb_substr($this->working_hours, 0, 1)) . mb_substr($this->working_hours, 1); 
            $query->andFilterWhere(['or', ['like', 'working_hours', $working_hours1], ['like', 'working_hours', $working_hours2]]);
        }
        if($this->warehouse_address) { 
            $this->warehouse_address = (string)$this->warehouse_address; 
            $warehouse_address1 = mb_strtoupper(mb_substr($this->warehouse_address, 0, 1)) . mb_substr($this->warehouse_address, 1); 
            $warehouse_address2 = mb_strtolower(mb_substr($this->warehouse_address, 0, 1)) . mb_substr($this->warehouse_address, 1); 
            $query->andFilterWhere(['or', ['like', 'warehouse_address', $warehouse_address1], ['like', 'warehouse_address', $warehouse_address2]]);
        }
        if($this->manager) { 
            $this->manager = (string)$this->manager; 
            $manager1 = mb_strtoupper(mb_substr($this->manager, 0, 1)) . mb_substr($this->manager, 1); 
            $manager2 = mb_strtolower(mb_substr($this->manager, 0, 1)) . mb_substr($this->manager, 1); 
            $query->andFilterWhere(['or', ['like', 'manager', $manager1], ['like', 'manager', $manager2]]);
        }
        if($this->b2b_login) { 
            $this->b2b_login = (string)$this->b2b_login; 
            $b2b_login1 = mb_strtoupper(mb_substr($this->b2b_login, 0, 1)) . mb_substr($this->b2b_login, 1); 
            $b2b_login2 = mb_strtolower(mb_substr($this->b2b_login, 0, 1)) . mb_substr($this->b2b_login, 1); 
            $query->andFilterWhere(['or', ['like', 'b2b_login', $b2b_login1], ['like', 'b2b_login', $b2b_login2]]);
        }
        if($this->b2b_password) { 
            $this->b2b_password = (string)$this->b2b_password; 
            $b2b_password1 = mb_strtoupper(mb_substr($this->b2b_password, 0, 1)) . mb_substr($this->b2b_password, 1); 
            $b2b_password2 = mb_strtolower(mb_substr($this->b2b_password, 0, 1)) . mb_substr($this->b2b_password, 1); 
            $query->andFilterWhere(['or', ['like', 'b2b_password', $b2b_password1], ['like', 'b2b_password', $b2b_password2]]);
        }
        if($this->delivery) { 
            $this->delivery = (string)$this->delivery; 
            $delivery1 = mb_strtoupper(mb_substr($this->delivery, 0, 1)) . mb_substr($this->delivery, 1); 
            $delivery2 = mb_strtolower(mb_substr($this->delivery, 0, 1)) . mb_substr($this->delivery, 1); 
            $query->andFilterWhere(['or', ['like', 'delivery', $delivery1], ['like', 'delivery', $delivery2]]);
        }
        if($this->return_policy) { 
            $this->return_policy = (string)$this->return_policy; 
            $return_policy1 = mb_strtoupper(mb_substr($this->return_policy, 0, 1)) . mb_substr($this->return_policy, 1); 
            $return_policy2 = mb_strtolower(mb_substr($this->return_policy, 0, 1)) . mb_substr($this->return_policy, 1); 
            $query->andFilterWhere(['or', ['like', 'return_policy', $return_policy1], ['like', 'return_policy', $return_policy2]]);
        }

        if($this->payment_method) { 
            $this->payment_method = (string)$this->payment_method; 
            $payment_method1 = mb_strtoupper(mb_substr($this->payment_method, 0, 1)) . mb_substr($this->payment_method, 1); 
            $payment_method2 = mb_strtolower(mb_substr($this->payment_method, 0, 1)) . mb_substr($this->payment_method, 1); 
            $query->andFilterWhere(['or', ['like', 'payment_method', $payment_method1], ['like', 'payment_method', $payment_method2]]);
        }
        if($this->vat_handling) { 
            $this->vat_handling = (string)$this->vat_handling; 
            $vat_handling1 = mb_strtoupper(mb_substr($this->vat_handling, 0, 1)) . mb_substr($this->vat_handling, 1); 
            $vat_handling2 = mb_strtolower(mb_substr($this->vat_handling, 0, 1)) . mb_substr($this->vat_handling, 1); 
            $query->andFilterWhere(['or', ['like', 'vat_handling', $vat_handling1], ['like', 'vat_handling', $vat_handling2]]);
        }
        return $dataProvider;
    }
}
