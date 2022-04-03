<?php

namespace api\controllers;

use api\models\food\ConstructForm;
use Yii;
use yii\bootstrap\ActiveForm;
use api\components\MainController;

class FoodController extends MainController
{
    public function actionConstruct()
    {
        $dataModel = new ConstructForm();

        $dataModel->load(["ConstructForm" => Yii::$app->request->post()]);

        if (!$dataModel->validate()) {
            return $dataModel->sendResponse(0, $dataModel->errors);
        }

        return $dataModel->run();
    }

    public function actionError()
    {
        return '404 метод не найден';
    }

}
