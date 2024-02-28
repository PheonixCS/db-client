<?php

use yii\helpers\Html;
use yii\web\YiiAsset;

$this->title = 'Редактировать контрагента';
$this->params['breadcrumbs'][] = ['label' => 'Контрагенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contractors-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php if ($model->type === 'individual') {
		echo $this->render("form", ['model' => $model, 'contractor_type' => $model->type]);
	} else {
		echo $this->render("_form", ['model' => $model, 'contractor_type' => $model->type]);
	} ?>


</div>

<?php
$this->registerCssFile('@web/css/fix/fixwidth.css', ['depends' => [YiiAsset::class]]);
?>