<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'timeZone' => 'Europe/Moscow',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableSession' => false,
//            'enableAutoLogin' => true,
//            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'home/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
//                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/v1/auth'],

                '' => 'home/index',
//                'users/init' => 'home/users-init',
//                'login' => 'home/login',
//                'admin' => 'home/admin',
//                'seller' => 'home/seller',
//                'create/product' => 'home/create-product',

                'api/v1' => 'api/v1/auth/index',
                'api/v1/login' => 'api/v1/auth/login',
                'api/v1/logout' => 'api/v1/auth/logout',
                'api/v1/signup' => 'api/v1/auth/signup',
                'api/v1/verify-email' => 'api/v1/auth/verify-email',
                'api/v1/resend-verification-email' => 'api/v1/auth/resend-verification-email',
                'api/v1/request-password-reset' => 'api/v1/auth/request-password-reset',
                'api/v1/reset-password' => 'api/v1/auth/reset-password',



//                'posts' => 'post/index',
//                'post/<id:\d+>' => 'post/view',
//                'pattern' => 'posts',
//                'route' => 'post/index',
//                'suffix' => '.json',


            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
//            'dateFormat' => 'php:Y-m-d',
//            'datetimeFormat' => 'php:Y-m-d H:i:s',
//            'timeFormat' => 'php:H:i:s',
//            'defaultTimeZone' => 'Europe/Moscow',
            'timeZone' => 'Europe/Moscow',
        ],
    ],
    'params' => $params,
];
