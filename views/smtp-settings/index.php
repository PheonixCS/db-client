<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'SMTP Settings';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<p>
	<?= Html::a('Update', ['update'], ['class' => 'btn btn-primary']) ?>
</p>

<?= DetailView::widget([
	'model' => $smtpSettings,
	'attributes' => [
		'host',
		'username',
		'password',
		'port',
		'encryption',
	],
]) ?>