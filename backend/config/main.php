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
    'bootstrap' => ['log'],
    'modules' => [
        'gridview' => ['class' => 'kartik\grid\Module'],
        'dialog' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@kvdialog/messages',
            'forceTranslation' => true
        ],
        'pdfjs' => [
            'class' => '\yii2assets\pdfjs\Module',
        ],
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['127.0.0.1', '::1', '*.*.*.*', '103.175.221.180', '139.194.11.99'] // adjust this to your needs
            // 'allowedIPs' => ['127.0.0.1', '::1','139.194.3.214'] // adjust this to your needs
        ],
        'imagemanager' => [
            'class' => 'noam148\imagemanager\Module',
            'canUploadImage' => true,
            'canRemoveImage' => function () {
                return true;
            },
            // 'debug' => [
            //     'class' => 'yii\debug\Module',
            //     'allowedIPs' => ['127.0.0.1', '::1', '*.*.*.*','103.175.221.180', '139.194.11.99'] // adjust this to your needs
            // ],
            //add css files (to use in media manage selector iframe)
//            'cssFiles' => [
//                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css',
//            ],
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
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
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                // BACKEND

                '' => 'site/index',
                'dashboard' => 'site/dashboard',
                'login' => 'site/login',
                'signup' => 'site/signup',
                'logout' => 'site/logout',
                'genlang' => 'site/genlang',
                'purchase/request' => 'tran/request',
                'purchase/order' => 'tran/request',
                'purchase/delivery/<type:\d+>' => 'tran/requestpd',
                'purchase/invoice' => 'tran/request',
                'purchase/return' => 'tran/request',
                '<contacttype:(customer|employee|vendor|supplier|payroll)>' => 'contact/index',
                'create/<contacttype:(customer|employee|vendor|supplier|payroll)>' => 'contact/create',
                'update/<contacttype:(customer|employee|vendor|supplier|payroll)>/<contactid:[\w\-]+>' => 'contact/update',

                'sales/quote' => 'tran/request',
                'sales/order' => 'tran/request',
                'sales/delivery' => 'tran/request',
                'sales/invoice' => 'tran/request',
                'sales/return' => 'tran/request',
                'invoice/calendar' => 'tran/request',
                'tran/createitem' => 'tran/createitem',

                'purchase/create' => 'tran/create',
                'sales/create' => 'tran/create',

                'purchase/update' => 'tran/update',
                'sales/update' => 'tran/update',

                'purchase/detail' => 'tran/detail',
                'sales/detail' => 'tran/detail',

                'purchase/delete' => 'tran/delete',
                'sales/delete' => 'tran/delete',

                '<enumtype:\w+>' => 'enum/index',
                'create/<enumtype:\w+>' => 'enum/create',
                'update/<enumtype:\w+>/<enumid:[\w\-]+>' => 'enum/update',

                // ========== PARTTIME ROUTES (Updated) ==========
                'parttime/personal' => 'leave/index',
                'parttime/personal/create' => 'leave/create',
                'parttime/personal/update/<id:[\w\-]+>' => 'leave/update',
                'parttime/personal/list' => 'leave/list',

                'parttime/attendance' => 'leave/index',
                'parttime/attendance/create' => 'leave/create',
                'parttime/attendance/update/<id:[\w\-]+>' => 'leave/update',
                'parttime/attendance/list' => 'leave/list',
                // 'parttime/attendance/selectparttimeattendance' => 'leave/selectparttimeattendance',
                'leave/selectparttimeattendance' => 'leave/selectparttimeattendance',
                'leave/selectparttime' => 'leave/selectparttime',

                'parttime/payment' => 'leave/index',
                'parttime/payment/create' => 'leave/create',
                'parttime/payment/update/<id:[\w\-]+>' => 'leave/update',
                'parttime/payment/list' => 'leave/list',

                'leave/importclockhistory' => 'leave/importclockhistory',
                'leave/import' => 'leave/import',
                'leave/downloadsamplereal' => 'leave/downloadsamplereal',
                'leave/exportbiofinger' => 'leave/exportbiofinger',
                // 'leave/downloadtemplate' => 'leave/downloadtemplate',
                'leave/create' => 'leave/create',
                'leave/update/<id:[\w\-]+>' => 'leave/update',
                'leave/updateabs/<id:[\w\-]+>' => 'leave/updateabs',
                'leave/delete' => 'leave/delete',
                'leave/list' => 'leave/list',
                'leave' => 'leave/index',

                'operational/importclockhistory' => 'operational/importclockhistory',
                'operational/import' => 'operational/import',
                'operational/downloadsamplereal' => 'operational/downloadsamplereal',
                'operational/exportbiofinger' => 'operational/exportbiofinger',
                // 'leave/downloadtemplate' => 'leave/downloadtemplate',
                'operational/create' => 'operational/create',
                'operational/update/<id:[\w\-]+>' => 'operational/update',
                'operational/delete' => 'operational/delete',
                'operational/list' => 'operational/list',
                'operational' => 'operational/index',


                'absent/importclockhistory' => 'leave/importclockhistory',
                'absent/import' => 'leave/import',
                'absent/downloadsamplereal' => 'leave/downloadsamplereal',
                'absent/exportbiofinger' => 'leave/exportbiofinger',
                // 'absent/downloadtemplate' => 'leave/downloadtemplate',
                'absent/create' => 'leave/create',
                'absent/update/<id:[\w\-]+>' => 'leave/updateabs',
                'absent/delete' => 'leave/delete',
                'absent/list' => 'leave/list',
                'absent' => 'leave/index',

                // 'leave' => 'leave/index',
                // 'leave/create' => 'leave/create',
                // 'leave/update/<id:[\w\-]+>' => 'leave/update',
                // 'leave/delete/<id:[\w\-]+>' => 'leave/delete',
                // 'leave/list' => 'leave/list',
                // 'leave/<action:\w+>' => 'leave/<action>',

                // 'absent' => 'leave/index',
                // 'absent/create' => 'leave/create',
                // 'absent/update/<id:[\w\-]+>' => 'leave/update',
                // 'absent/delete/<id:[\w\-]+>' => 'leave/delete',
                // 'absent/list' => 'leave/list',
                // 'absent/<action:\w+>' => 'leave/<action>',

                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<action:\w+>/<slug:[a-zA-Z0-9_\-\.]+>' => 'site/<action>',
                '<controller:\w+>/<action:\w+>/<slug:\d+>' => '<controller>/<action>',
            ],
        ],


    ],
    'params' => $params,
];
