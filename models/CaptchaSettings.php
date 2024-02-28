<?php

namespace app\models;

use yii\db\ActiveRecord;

class CaptchaSettings extends ActiveRecord
{
	public function rules()
	{
		return [
			[['site_key', 'secret_key'], 'required'],
			[['site_key', 'secret_key'], 'string'],
		];
	}
	public function safeAttributes()
	{
		return ['site_key', 'secret_key'];
	}

	public static function tableName()
	{
		return 'captcha_settings';
	}
	public static function getSiteKey()
	{
		$model = self::findOne(1);
		return $model->site_key;
	}

	public static function getSecretKey()
	{
		$model = self::findOne(1);
		return $model->secret_key;
	}
}
