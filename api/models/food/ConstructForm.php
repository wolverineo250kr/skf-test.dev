<?php

namespace api\models\food;

use common\models\api\v1\ApiLogV1;
use common\models\ingredient\IngredientType;
use yii;
use yii\base\Model;

class ConstructForm extends Model
{
    public $code;

    public function rules()
    {
        return [
            [['code'], 'required'],
            ['code', 'string'],
            ['code', 'isCodeExist'],
            ['code', 'match', 'pattern' => '/^[a-z]+$/i', 'message' => 'Только латинские символы.']
        ];
    }

    public function isCodeExist($attribute)
    {
        foreach (str_split($this->code) as $letter) {
            $ingredientTypes = IngredientType::find()
                ->with(['ingredients'])
                ->asArray()
                ->where(['code' => $letter])
                ->count();

            if (!$ingredientTypes) {
                $this->addError($attribute, 'Код ' . $letter . ' в таблице отсутвует.');
            }
        }
    }

    public function run()
    {
        $ingredientTypes = [];
        foreach (str_split($this->code) as $letter) {
            $ingredientTypes[] = IngredientType::find()
                ->with(['ingredients'])
                ->asArray()
                ->where(['code' => (string) $letter])
                ->one();
        }

        $preArray = [];
        $finalArray = [];
        $prevTypeId = 0;
        foreach ($ingredientTypes as $type) {
            foreach ($type["ingredients"] as $ingredient) {
                $preArray[] = $type['title'] . '-' . $ingredient['title'] . '_' . $ingredient['price'];
                $prevTypeId = $ingredient['type_id'];
            }

            if ($prevTypeId && $prevTypeId == $type['id']) {
                array_push($finalArray, $preArray);
                $preArray = [];
            }
        }

        $finalArray = $this->permutations($finalArray);
        $finalArray = $this->arrayUnique($finalArray);
        $finalArray = $this->normslizeResponse($finalArray);

        return $finalArray;
    }

    private function normslizeResponse(array $array)
    {
        $newResponce = [];
        $price = 0;
        foreach ($array as $value) {
            $products = [];
            foreach ($value as $valueQ) {
                $ingredientType = explode('-', $valueQ)[0];
                $explodedStringQ = explode('_', explode('-', $valueQ)[1]);
                $products[] = [
                    'type' => $ingredientType,
                    'value' => $explodedStringQ[0],
                ];
                $price += (int)$explodedStringQ[1];
            }

            $newResponce[] = [
                'products' => $products,
                'price' => $price
            ];

            $price = 0;
        }

        return $newResponce;
    }

    private function permutations(array $array)
    {
        if (count($array) == 1) return $array[0];
        $output = [];
        foreach (array_shift($array) as $v1) {
            foreach ($this->permutations($array) as $v2) {
                $output[] = array_merge(array($v1), is_array($v2) ? $v2 : array($v2));
            }
        }
        return $output;
    }

    private function arrayUnique(array $array)
    {
        foreach ($array as $keyEach => $finalArrayEach) {
            $arrayA = array_count_values($finalArrayEach);

            foreach ($arrayA as $key => $value) {
                if ($value != 1) {
                    unset($array[$keyEach]);
                }
            }
        }

        return $array;
    }

    public function sendResponse(int $code, $message = '')
    {
        try {
            $log = new ApiLogV1();
            $log->runLogger($code, json_encode($message));
        } catch (Exception $e) {

        }

        return [
            'status' => $code,
            'message' => $message
        ];
    }
}
