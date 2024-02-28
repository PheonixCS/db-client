<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
// $this->registerLinkTag(['rel' => 'stylesheet', 'href' => "https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css"]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
</head>

<body class="d-flex flex-column h-100">
	<?php $this->beginBody() ?>

	<header id="header">
		<?php
		NavBar::begin([
			'brandLabel' => Yii::$app->name,
			'brandUrl' => Yii::$app->homeUrl,
			// 'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
			'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
		]);

		$items = [
			Yii::$app->user->isGuest
				? ['label' => 'Войти', 'url' => ['/site/login']]
				: '<li class="nav-item">'
				. Html::beginForm(['/site/logout'])
				. Html::submitButton(
					'Выйти (' . Yii::$app->user->identity->name . ')',
					['class' => 'nav-link btn btn-link logout']
				)
				. Html::endForm()
				. '</li>'
		];

		if (!Yii::$app->user->isGuest) {
			$items[] = ['label' => 'Счета', 'url' => ['/check/index']];
			$items[] = ['label' => 'Контрагенты', 'url' => ['/contractors/index']];
			$items[] = ['label' => 'Поставщики', 'url' => ['/suppliers/index']];
			$items[] = [
				'label' => 'Настройки',
				'icon' => 'menu-down',
				'items' => [
					['label' => 'Способ доставки', 'url' => ['/delivery-type/index']],
					['label' => 'Пользователи', 'url' => ['/user/index']],
					['label' => 'Стадии', 'url' => ['/stage/index']],
					['label' => 'Капча', 'url' => ['/captcha-settings/index']],
					['label' => 'Почта', 'url' => ['/smtp-settings/index']],
					['label' => 'Статистика', 'url' => ['/chart/index']],
				]
			];
		}

		echo Nav::widget([
			'options' => ['class' => 'navbar-nav'],
			'items' => $items
		]);

		NavBar::end();
		?>
	</header>

	<main id="main" class="flex-shrink-0" role="main">
		<div class="container">
			<?php if (!empty($this->params['breadcrumbs'])) : ?>
				<?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
			<?php endif ?>
			<?= Alert::widget() ?>
			<?= $content ?>
		</div>
	</main>

	<footer id="footer" class="mt-auto py-3 bg-light">
		<div class="container">
			<div class="row text-muted">
				<div class="col-md-6 text-center text-md-start">&copy; Web DB Client <?= date('Y') ?></div>
			</div>
		</div>
	</footer>

	<?php $this->endBody() ?>
</body>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

</html>
<?php $this->endPage() ?>