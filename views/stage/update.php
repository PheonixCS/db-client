<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
/* @var $this yii\web\View */
/* @var $model app\models\Stage */

$this->title = 'Редактировать стадию: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Стадии', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="stage-update">

	<h1><?php Html::encode($this->title) ?></h1>

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>

<?php
$this->registerCssFile('@web/css/fix/fixwidth.css', ['depends' => [YiiAsset::class]]);
?>