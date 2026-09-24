<?php
$httpHost = $_SERVER["HTTP_HOST"] ?? 'localhost';
if ((strpos($httpHost, "localhost") > -1) || (strpos($httpHost, "127.0.0.1") > -1)) {
    defined('SERVER_NAME') or define('SERVER_NAME', "localhost");
} else {
    $parts = explode('.', $httpHost);

    if (count($parts) > 1) {
        $urlserver = $parts[0];
    } else {
        $urlserver = $parts[1] ?? $parts[0];
    }
    $urlserver = str_replace("dev.", "", $urlserver);
    defined('SERVER_NAME') or define('SERVER_NAME', $urlserver);
}




if (SERVER_NAME == "localhost") {
    defined('DB_HOST') or define('DB_HOST', "localhost");
    defined('DB_NAME') or define('DB_NAME', "pg_tot");
    defined('DB_USER') or define('DB_USER', "postgres");
    defined('DB_PORT') or define('DB_PORT', "5433"); // Changed to default postgres port
    defined('DB_PASS') or define('DB_PASS', "postgres");

    // defined('DB_HOST') or define('DB_HOST', "103.127.99.237");
    // defined('DB_NAME') or define('DB_NAME', "pg_clo");
    // defined('DB_USER') or define('DB_USER', "usr_clo");
    // defined('DB_PORT') or define('DB_PORT', "54322");
    // defined('DB_PASS') or define('DB_PASS', "Dwansoft12345!!!123");
    // defined('PATH_JAVA') or define('PATH_JAVA', "/usr/lib/jvm/java-8-openjdk-amd64/jre/lib/ext");
    // defined('PATH_JAVA') or define('PATH_JAVA', "C:/Program Files (x86)/Java/jdk1.7.0_04/jre/lib/ext");
    defined('TITLE') or define('TITLE', "GyM");
    defined('BASEURL_BACKEND') or define('BASEURL_BACKEND', "http://localhost:8081");
    defined('BASEURL_FRONTEND') or define('BASEURL_FRONTEND', "http://localhost:8082");
} else {
    defined('DB_HOST') or define('DB_HOST', "localhost");
    defined('DB_NAME') or define('DB_NAME', "pg_tot");
    defined('DB_USER') or define('DB_USER', "usr_tot");
    defined('DB_PORT') or define('DB_PORT', "54322");
    defined('DB_PASS') or define('DB_PASS', "tot12345!!!123###");
    defined('TITLE') or define('TITLE', "Clloo Business");
    // defined('PATH_JAVA') or define('PATH_JAVA', "/usr/lib/jvm/jre/lib/ext");
    defined('PATH_JAVA') or define('PATH_JAVA', "/opt/lib");


    defined('BASEURL_BACKEND') or define('BASEURL_BACKEND', "https://acc.clloo.com");
    defined('BASEURL_FRONTEND') or define('BASEURL_FRONTEND', "https://acc.clloo.com");
}

$baseurl_backend = BASEURL_BACKEND;
$baseurl_frontend = BASEURL_FRONTEND;

return [
    'language' => 'id',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                    'clientId' => 'YOUR_GOOGLE_CLIENT_ID', // Secret removed for GitHub Push Protection
                    'validateAuthState' => false,
                    //'returnUrl' => 'http://localhost/site/auth?authclient=google',
                    'returnUrl' => 'https://acc.clloo.com/site/auth?authclient=google',
                    'clientSecret' => 'YOUR_GOOGLE_CLIENT_SECRET', // Secret removed for GitHub Push Protection

                ],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '213029966045261',
                    'validateAuthState' => true,
                    'clientSecret' => '5ae3d0a6474fbf5b2d4d458a8ae3b757',
                ],
                /* 'instagram' => [
                    'class' => 'kotchuprik\authclient\Instagram',
                    'clientId' => '4dd17a2c66aa46709e5ee814d9a0eff6',
                    'returnUrl' => 'https://seller.jualanonline.id/site/auth?authclient=instagram',
                    'validateAuthState' => true,
                    'clientSecret' => '2bef0403d5134fb2845e86e4f3512398',
                ],*/
            ],
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'function' => [
            'class' => 'common\components\FunctionClass'
        ],
        'enum' => [
            'class' => 'common\components\EnumClass'
        ],
        'firebase' => [
            'class' => 'common\components\FirebaseClass',
            'serviceAccount' => '@common/config/firebase.json',
        ],
        'lang' => [
            'class' => 'common\components\LangClass'
        ],

        'session' => [
            'class' => 'yii\web\Session',
            'name' => 'GLOBALSESSIONID',
            'savePath' => __DIR__ . '/../../session',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'jsOptions' => ['position' => \yii\web\View::POS_HEAD],
                ],
                'backend\assets\AppAsset' => [
                    'jsOptions' => ['position' => \yii\web\View::POS_HEAD],
                ],
                'frontend\assets\AppAsset' => [
                    'jsOptions' => ['position' => \yii\web\View::POS_HEAD],
                ],
                // 'kartik\form\ActiveFormAsset' => [
                //     'bsDependencyEnabled' => false // do not load bootstrap assets for a specific asset bundle
                // ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en-US',
                ],
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'thousandSeparator' => '.',
            'dateFormat' => 'dd/MM/yyyy',
            'decimalSeparator' => ',',
            'timeFormat' => 'H:i:s',
            'datetimeFormat' => 'dd-MM-yyyy HH:mm:ss',
            'currencyCode' => 'IDR',
            'locale' => 'id-ID',
            'defaultTimeZone' => 'Asia/Jakarta',
            'nullDisplay' => '',
            'numberFormatterSymbols' => [
                // NumberFormatter::CURRENCY_SYMBOL => '',
            ]
        ],
        'urlBackend' => [
            'class' => 'yii\web\urlManager',
            'baseUrl' => $baseurl_backend,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'urlFrontend' => [
            'class' => 'yii\web\urlManager',
            'baseUrl' => $baseurl_frontend,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
    ],
];
