<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\models\ResetPasswordForm;
use app\models\SmtpSettings;
use yii\helpers\Url;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;

class SiteController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::class,
				'only' => ['logout'],
				'rules' => [
					[
						'actions' => ['logout'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}

	/**
	 * Displays homepage.
	 *
	 * @return string
	 */
	public function actionIndex()
	{
		return $this->render('index');
	}

	/**
	 * Login action.
	 *
	 * @return Response|string
	 */
	public function actionLogin()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$model->login();
			return $this->goBack();
		}
		Yii::$app->session->setFlash('error', 'Войдите для получения доступа к панеле.');
		$model->password = '';
		return $this->render('login', [
			'model' => $model,
		]);
	}

	/**
	 * Logout action.
	 *
	 * @return Response
	 */
	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->goHome();
	}

	/**
	 * Displays contact page.
	 *
	 * @return Response|string
	 */
	public function actionContact()
	{
		$model = new ContactForm();
		if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
			Yii::$app->session->setFlash('contactFormSubmitted');

			return $this->refresh();
		}
		return $this->render('contact', [
			'model' => $model,
		]);
	}

	/**
	 * Displays about page.
	 *
	 * @return string
	 */
	public function actionAbout()
	{
		return $this->render('about');
	}


	public function actionForgotPassword()
	{
		$model = new User();
		if ($model->load(Yii::$app->request->post())) {
			$email = $model->email;
			$user = User::findOne(['email' => $email]);

			if ($user) {
				// Генерация и сохранение нового токена сброса пароля

				$getToken = rand(0, 99999);
				$getTime = date("H:i:s");
				$user->password_reset_token = md5($getToken . $getTime);
				$user->save();

				$resetLink = Url::to(['site/reset-password', 'token' => $user->password_reset_token], true);
				$settings = new SmtpSettings();
				$settings = $settings->findOne(1);
				$mailer = new yii\swiftmailer\Mailer([
					'transport' => [
						'class' => 'Swift_SmtpTransport',
						'host' => $settings->host,
						'username' => $settings->username,
						'password' => $settings->password,
						'port' => $settings->port,
						'encryption' => $settings->encryption,
					],
				]);
				Yii::$app->set('mailer', $mailer);
				Yii::$app->mailer->compose()
					->setFrom($settings->username)
					->setTo($email)
					->setSubject('Password Reset')
					->setHtmlBody('Follow the link to reset your password: ' . $resetLink)
					->send();

				Yii::$app->session->setFlash('success', 'Please check your email for password reset instructions.');
				return $this->redirect(['site/login']);
			}
		} else {
			// var_dump($model->errors);
			// die;
		}

		return $this->render('forgot-password', [
			'model' => $model,
		]);
	}
	public function actionResetPassword($token)
	{
		try {
			$user = User::findOne(['password_reset_token' => $token]);
		} catch (InvalidParamException $e) {
			throw new BadRequestHttpException($e->getMessage());
		}
		$model = new ResetPasswordForm($user);

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {

			$user->setPassword($model->password);
			$user->removePasswordResetToken();
			$user->save();
			Yii::$app->session->setFlash('success', 'Your password has been reset successfully. You can now log in with your new password.');
			return $this->redirect(['site/login']);
		}

		return $this->render('reset-password', [
			'model' => $model,
		]);
	}
}
