<?php

namespace common\models;

use Yii;
use yii\base\Model;
use common\models\User;

class ForgotPassword extends Model
{
    public $email;

    public function rules()
    {
        return [
            ['email', 'required', 'message' => 'Email tidak boleh kosong.'],
            ['email', 'email', 'message' => 'Format email tidak valid.'],
            ['email', 'exist', 'targetClass' => '\common\models\User', 'targetAttribute' => 'email', 'message' => 'Email tidak ditemukan.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
        ];
    }

    public function sendEmail()
    {
        $user = User::findOne(['email' => $this->email]);
        if (!$user) {
            return false;
        }

        // Generate token (misal: password_reset_token di table User)
        $user->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
        if (!$user->save(false)) {
            return false;
        }

        // Kirim email
        return Yii::$app->mailer->compose()
            ->setTo($this->email)
            ->setFrom([Yii::$app->params['adminEmail'] => 'Support'])
            ->setSubject('Reset Password')
            ->setTextBody("Klik link berikut untuk reset password: " .
                Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]))
            ->send();
    }
}
