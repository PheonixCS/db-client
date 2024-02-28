<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "delivery_type".
 *
 * @property int $id
 * @property string $name
 * @property string|null $color
 */
class DeliveryType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'delivery_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'is_deleted'], 'required'],
            [['name', 'color'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'color' => 'Color',
        ];
    }
}
