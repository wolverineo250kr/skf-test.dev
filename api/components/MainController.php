<?php

namespace api\components;

use yii\filters\auth\QueryParamAuth;
use yii\rest\Controller;
use yii\web\Response;

class MainController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['authMethods'] = [
            [
                'class' => QueryParamAuth::class,
                'tokenParam' => 'key'
            ]
        ];

        $behaviors['rateLimiter'] = [
            'class' => RateLimiter::class,
            'enableRateLimitHeaders' => true,
        ];

        // 8) результат выполнения необходимо вернуть в виде объекта формата JSON;
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;

        return $behaviors;
    }
}