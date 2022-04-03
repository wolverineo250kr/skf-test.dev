<?php

namespace common\models\ingredient;

use Yii;

class Ingredient extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%ingredient}}';
    }

    public function rules()
    {
        return [
            [['id', 'type_id'], 'integer'],
            [['title'], 'string'],
            [['type_id', 'title', 'price'], 'required'],
        ];
    }

    public function getIngredientTypes()
    {
        return $this->hasMany(IngredientTypes::class, ['type_id' => 'id']);
    }

    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'type_id' => 'Тип ингредиента',
            'title' => 'Название',
            'price' => 'Стоимость'
        ];
    }
}