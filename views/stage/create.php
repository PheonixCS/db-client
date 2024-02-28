<?php

use yii\helpers\Html;
use yii\web\YiiAsset;

/* @var $this yii\web\View */
/* @var $model app\models\Stage */

$this->title = 'Добавить стадию';
$this->params['breadcrumbs'][] = ['label' => 'Стадии', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stage-create">

	<h1><?php Html::encode($this->title) ?></h1>

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>

<?php
$this->registerCssFile('@web/css/fix/fixwidth.css', ['depends' => [YiiAsset::class]]);
?>