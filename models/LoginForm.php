<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\CaptchaSettings;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class LoginForm extends Model
{
	public $name;
	public $password;
	public $rememberMe = true;
	public $email;
	private $_user = false;
	public $reCaptcha;


	public function init()
	{
		$captchaSettings = new CaptchaSettings();
		parent::init();
		Yii::$app->set('reCaptcha', [
			'class' => 'himiklab\yii2\recaptcha\ReCaptchaConfig',
			'siteKeyV3' => $captchaSettings->getSiteKey(),
			'secretV3' => $captchaSettings->getSecretKey(),
		]);
	}


	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{

		return [
			[['name', 'password'], 'required'],
			['rememberMe', 'boolean'],
			['password', 'validatePassword'],
			[
				['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator3::className(),
				'threshold' => 0.8,
				'action' => 'homepage',
			],
		];
	}

	/**
	 * Validates the password.
	 * This method serves as the inline validation for password.
	 *
	 * @param string $attribute the attribute currently being validated
	 * @param array $params the additional name-value pairs given in the rule
	 */
	public function validatePassword($attribute, $params)
	{
		if (!$this->hasErrors()) {
			$user = $this->getUser();

			if (!$user || !$user->validatePassword($this->password)) {
				$this->addError($attribute, 'Incorrect username or password.');
			}
		}
	}

	/**
	 * Logs in a user using the provided username and password.
	 * @return bool whether the user is logged in successfully
	 */
	public function login()
	{
		if ($this->validate()) {
			return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
		}
		return false;
	}

	/**
	 * Finds user by [[username]]
	 *
	 * @return User|null
	 */
	public function getUser()
	{
		if ($this->_user === false) {
			$this->_user = User::findByUsername($this->name);
		}

		return $this->_user;
	}
}
