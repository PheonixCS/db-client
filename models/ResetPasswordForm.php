<?php

namespace app\models;

use Yii;
use yii\base\Model;

class ResetPasswordForm extends Model
{
	public $password;
	public $confirm_password;

	private $_user;

	public function __construct(User $user, $config = [])
	{
		$this->_user = $user;
		parent::__construct($config);
	}

	public function rules()
	{
		return [
			[['password', 'confirm_password'], 'required'],
			[['password', 'confirm_password'], 'string', 'min' => 6],
			['confirm_password', 'compare', 'compareAttribute' => 'password', 'message' => 'Passwords do not match.'],
		];
	}

	public function attributeLabels()
	{
		return [
			'password' => 'New Password',
			'confirm_password' => 'Confirm New Password',
		];
	}
}
