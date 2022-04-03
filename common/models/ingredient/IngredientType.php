<?php

namespace common\models\ingredient;

use common\models\ingredient\Ingredient;
use Yii;

class IngredientType extends \yii\db\ActiveRecord
{
    const DISABLED = 0;
    const ACTIVE = 1;

    public static function tableName()
    {
        return '{{%ingredient_type}}';
    }

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title', 'code'], 'required'],
            [['title', 'code'], 'string'],
        ];
    }

    public function getIngredients()
    {
        return $this->hasMany(Ingredient::class, ['type_id' => 'id']);
    }

    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'title' => 'Название',
            'code' => 'Код'
        ];
    }
}