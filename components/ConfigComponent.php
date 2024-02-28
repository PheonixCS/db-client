<?php

namespace app\components;

use yii\base\Component;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class Config  extends Component
{ // объявляем класс
	private $_attributes;

	public function init()
	{ // функция инициализации. Если данные не будут переданы в компонент, то он выведет текст "Текст по умолчанию"
		parent::init();
		$this->_attributes = ArrayHelper::map(\app\models\SmtpSettings::findOne(1), 'name', 'val');
	}

	public function __get($name)
	{
		if (array_key_exists($name, $this->_attributes))
			return $this->_attributes[$name];

		return parent::__get($name);
	}
}
