<?php

use app\models\Supplier;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\web\YiiAsset;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */



$this->registerCssFile('https://use.fontawesome.com/releases/v5.3.1/css/all.css');
$this->registerJsFile('https://use.fontawesome.com/releases/v5.3.1/js/all.js', ['defer' => true, 'crossorigin' => 'anonymous']);
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">



<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<?php
$this->title = 'Поставщики';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<p>
		<?= Html::a('Добавить поставщика', ['create'], ['class' => 'btn btn-success']) ?>
	</p>


	<?= GridView::widget([
		'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['style' => 'width: 100%; max-width: 100%;'],
        'pager' => ['class' => \yii\bootstrap5\LinkPager::class],
        'tableOptions' => ['class' => 'table table-striped table-bordered'],
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'company_name',
                'label' => 'Наименование компании',
            ],
            [
                'attribute' => 'website',
                'label' => 'Веб-сайт',
            ],
            [
                'attribute' => 'phone',
                'label' => 'Телефон',
            ],
            [
                'attribute' => 'email',
                'label' => 'Email',
                'format' => 'email',
            ],
            [
                'attribute' => 'working_hours',
                'label' => 'Рабочие часы',
            ],
            [
                'attribute' => 'warehouse_address',
                'label' => 'Адрес склада',
            ],
            [
                'attribute' => 'manager',
                'label' => 'Менеджер',
            ],
            [
                'attribute' => 'b2b_login',
                'label' => 'Логин B2B',
            ],
            [
                'attribute' => 'b2b_password',
                'label' => 'Пароль B2B',
            ],
            [
                'attribute' => 'delivery',
                'label' => 'Доставка',
            ],
            [
                'attribute' => 'return_policy',
                'label' => 'Политика возврата',
            ],
            [
                'attribute' => 'payment_method',
                'label' => 'Способ оплаты',
            ],
            [
                'attribute' => 'vat_handling',
                'label' => 'Обработка НДС',
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{update} {delete}',
                'urlCreator' => function ($action, Supplier $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
		],
	]); ?>


</div>

<?php
$this->registerCssFile('@web/css/fix/fixwidth.css', ['depends' => [YiiAsset::class]]);
?>

<style>
    .container {
		margin: 0;
		padding-left: 10px !important;
		padding-right: 0 !important;
		/* overflow-x: auto; */
		width: 100% !important;
		max-width: 100% !important;
	}
    a {
		text-decoration: none;
	}
    td.vertical-center {
		vertical-align: middle;
	}
    #footer {
		display: none;
	}
</style>