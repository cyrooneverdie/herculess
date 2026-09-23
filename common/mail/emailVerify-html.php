<!-- <php

        use yii\helpers\Html;

        /** @var yii\web\View $this */
        /** @var common\models\User $user */

        $verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verifyemail', 'token' => $user->verification_token]);
        ?>
<div class="verify-email">
    <p>Hello <= Html::encode($user->username) ?>,</p>

    <p>Follow the link below to verify your email:</p>

    <p><= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
</div> -->

<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\User $user */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verifyemail', 'token' => $user->verification_token]);
$appName = "Dwansoft";
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - <?= Html::encode($appName) ?></title>
    <style type="text/css">
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }

        .logo {
            max-width: 150px;
            height: auto;
        }

        .content {
            padding: 20px;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3498db;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
        }

        .verification-code {
            background: #f4f4f4;
            padding: 10px;
            text-align: center;
            margin: 20px 0;
            font-family: monospace;
            word-break: break-all;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img
                src="https://app.clloo.com/assets/media/logos/defaultnobg.png"
                class="w-100px mx-auto d-block" />
            <h1>Email Verification</h1>
        </div>

        <div class="content">
            <p>Hello <?= Html::encode($user->name ?: $user->username) ?>,</p>

            <p>Thank you for registering with <?= Html::encode($appName) ?>. Please verify your email address to complete your registration.</p>

            <p style="text-align: center;">
                <?= Html::a('Verify Email Address', $verifyLink, ['class' => 'button']) ?>
            </p>

            <p>If the button above doesn't work, copy and paste this link into your browser:</p>

            <div class="verification-code">
                <?= Html::encode($verifyLink) ?>
            </div>

            <p>This verification link will expire in 24 hours.</p>

            <p>If you didn't create an account with us, please ignore this email.</p>
        </div>

        <div class="footer">
            <p>&copy; <?= 2016 ?> <?= Html::encode($appName) ?>. All rights reserved.</p>
            <p>
                <?= Html::a('Dwansoft', Url::home(true)) ?> |
                <?= Html::a('Privacy Policy', 'https://dwansoft.com/') ?> |
                <?= Html::a('Contact Support', 'https://dwansoft.com/') ?>
            </p>
        </div>
    </div>
</body>

</html>