<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

/*
	* @property int id
	* @property string name
	* @property string password
	* @property string email
	* @property string authKet
	* @property int is_admin
	* @property timestamp created_at
	* @property timestamp update_at
*/

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
	public static function tableName()
	{
		return 'users';
	}
	private static $users;

	public function rules()
	{
		return [
			[['name', 'password', 'email'], 'required'],
			[['name', 'password', 'password_reset_token'], 'string', 'max' => 255],
			[['name'], 'unique'],
			['email', 'email'],
			['is_admin', 'boolean']
		];
	}
	/**
	 * {@inheritdoc}
	 */

	public static function findIdentity($id)
	{
		return User::findOne($id);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function findIdentityByAccessToken($token, $type = null)
	{
		foreach (self::$users as $user) {
			if ($user['accessToken'] === $token) {
				return new static($user);
			}
		}

		return null;
	}

	/**
	 * Finds user by username
	 *
	 * @param string $username
	 * @return static|null
	 */
	public static function findByUsername($name)
	{
		return static::findOne(['name' => $name]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAuthKey()
	{
		return $this->authKey;
	}

	/**
	 * {@inheritdoc}
	 */
	public function validateAuthKey($authKey)
	{
		return $this->authKey === $authKey;
	}

	/**
	 * Validates password
	 *
	 * @param string $password password to validate
	 * @return bool if password provided is valid for current user
	 */
	public function validatePassword($password)
	{
		return Yii::$app->security->validatePassword($password, $this->password);
	}

	public function HashPassword($password)
	{
		$this->password = $this->security->generatePasswordHash($password);
	}

	public function generatePasswordResetToken()
	{
		$this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
		$this->save();
	}

	public static function findByPasswordResetToken($token)
	{
		return static::findOne(['password_reset_token' => $token]);
	}

	public function removePasswordResetToken()
	{
		$this->password_reset_token = null;
	}
	public function setPassword($password)
	{
		$this->password =  Yii::$app->security->generatePasswordHash($password);
		$this->save();
	}
}
