<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone' => 'Europe/Moscow',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'authManager' => [
            'class' => \yii\rbac\DbManager::class,
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
];
