<?php

use yii\helpers\Html;
use yii\web\YiiAsset;

$this->title = 'Добавить контрагента';
$this->params['breadcrumbs'][] = ['label' => 'Контрагенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contractors-create">

	<h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render(($contractorType === 'individual') ? 'form' : '_form', ['model' => $model, 'contractor_type' => $contractorType ? 'legal' : 'individual']) ?>


</div>

<?php
$this->registerCssFile('@web/css/fix/fixwidth.css', ['depends' => [YiiAsset::class]]);
?>