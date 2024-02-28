<?php

namespace app\models;

use yii\db\ActiveRecord;

class SmtpSettings extends ActiveRecord
{
	public static function tableName()
	{
		return 'smtp_settings'; // замените на имя вашей таблицы настроек SMTP
	}

	public function rules()
	{
		return [
			[['host', 'username', 'password', 'port'], 'required'],
			[['host', 'username', 'password', 'encryption'], 'string', 'max' => 255],
			['port', 'integer'],
		];
	}

	public function attributeLabels()
	{
		return [
			'host' => 'Host',
			'username' => 'Username',
			'password' => 'Password',
			'port' => 'Port',
			'encryption' => 'Encryption',
		];
	}
}
