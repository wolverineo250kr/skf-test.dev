<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

$httpHost = filter_input(INPUT_SERVER, 'HTTP_HOST');
$hostPath = array_reverse(explode('.', $httpHost));
$host = $hostPath[1] . '.' . $hostPath[0];

return [
    'id' => 'apiskftest',
    'basePath' => dirname(__DIR__),
    'language' => 'ru',
    'bootstrap' => ['log'],
    'name' => 'test',
    'controllerNamespace' => 'api\controllers',
    'components' => [
        'session' => [
            'cookieParams' => [
                'lifetime' => 2592000,
                'domain' => 'api.' . $host,
                'httpOnly' => true,
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\api\v1\User',
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_identity-apiskftest',
                'httpOnly' => true,
                'domain' => 'api.' . $host,
            ],
        ],
        'request' => [
            'cookieValidationKey' => 'iМDBHН8gAVWZntlQ3GwLaS8tТwso',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                [
                    'pattern' => '/1.0/food/construct',
                    'route' => '/food/construct',
                ]
            ],
        ],
    ],
    'params' => $params,
];
