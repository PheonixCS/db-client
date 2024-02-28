<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\DeliveryType $model */

$this->title = 'Create Delivery Type';
$this->params['breadcrumbs'][] = ['label' => 'Delivery Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
