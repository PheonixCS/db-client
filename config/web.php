<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
	'id' => 'basic',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log'],
	'aliases' => [
		'@bower' => '@vendor/bower-asset',
		'@npm'   => '@vendor/npm-asset',
	],
	'components' => [
		'assetManager' => [
			'bundles' => [
				'kartik\form\ActiveFormAsset' => [
					'bsDependencyEnabled' => false // do not load bootstrap assets for a specific asset bundle
				],
			],
		],

		'configs' => [
			'class' => 'app\components\ConfigComponent',
		],
		'mailer' => [
			'class' => 'yii\swiftmailer\Mailer',
			'useFileTransport' => false,
			'viewPath' => '@app/mail',
		],
		'reCaptcha' => [
			'class' => 'himiklab\yii2\recaptcha\ReCaptchaConfig',
			'siteKeyV3' => '',
			'secretV3' => '',
			// 'site_key' => '6Lf9cDUpAAAAAGzxN-barvEnUJ0ZDLmsl4ZzoHlV',
			// 'secret_key' => '6Lf9cDUpAAAAAFyVb9y5iRu01dPm1CHnPTaVNWXS',
		],
		'request' => [
			// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
			'cookieValidationKey' => 't72_wqMjGWNk2J8S7rHG00OmauBRY1Ve',
		],
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
		'user' => [
			'identityClass' => 'app\models\User',
			'enableAutoLogin' => true,
		],
		'errorHandler' => [
			'errorAction' => 'site/login',
		],
		'log' => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'db' => $db,

		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [
				'user/create' => 'user/create',
				'check' => 'check/index',
				'check/delete' => 'check/delete',
				'' => '/check',
				'smtp' => '/smtp/index',
				'check/table' => '/check/table'
			],
		],

	],
	'params' => $params,
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][] = 'debug';
	$config['modules']['debug'] = [
		'class' => 'yii\debug\Module',
		// uncomment the following to add your IP if you are not connecting from localhost.
		'allowedIPs' => ['127.0.0.1', '::1','194.87.103.161'],
	];

	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class' => 'yii\gii\Module',
		// uncomment the following to add your IP if you are not connecting from localhost.
		'allowedIPs' => ['127.0.0.1', '::1', '194.87.103.161'],
	];
}

return $config;
