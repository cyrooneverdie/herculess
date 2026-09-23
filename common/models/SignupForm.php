<?php

namespace common\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\swiftmailer\Mailer;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $name;
    public $address;
    public $email;
    public $password;
    public $repassword;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            // ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['name', 'string', 'min' => 2, 'max' => 255],

            ['address', 'trim'],
            ['address', 'required'],
            // ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['address', 'string', 'min' => 1, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            // ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
            ['email', 'checkuser'],
            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],

            ['repassword', 'required'],
            ['repassword', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
            ['repassword', 'checkrepassword']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::$app->lang->t('signup', 'name'),
            'address' => Yii::$app->lang->t('contact', 'address'),
            'email' => Yii::$app->lang->t('signup', 'email'),
            'password' => Yii::$app->lang->t('signup', 'password'),
            'repassword' => Yii::$app->lang->t('signup', 'repassword'),
        ];
    }

    public function checkrepassword($attribute, $params)
    {

        if ($this->password != $this->repassword) {
            $this->addError($attribute,  Yii::t('app', $attribute) . ' ' . Yii::t('app', 'notvalid') . '.');
        }
    }

    public function checkuser($attribute, $params)
    {
        $filterid = "";
        $id = Yii::$app->function->findByField("userid", "users", $filterid . " and status <> 10 and (username='" . $this->email . "' or email='" . $this->email . "')");
        if ($id != "") {
            $this->addError($attribute, 'This email address has already been taken.');
        }
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {

        if (!$this->validate()) {
            return null;
        }

        $user = new User();

        $user->username = $this->email;
        $user->email = $this->email;
        $user->name = $this->name;
        $user->address = $this->address;
        $user->role_id = '2';
        $user->status = User::STATUS_INACTIVE;
        $user->avatar = '/uploads/avatars/blank.png';
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        return $user->save() && $this->sendEmail($user);
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        try {
            // var_dump(Yii::$app->mailer); // Cek jika mailer terinisialisasi
            // die();

            $cleanEmail = preg_replace('/\s+/', '', $this->email);

            $message = Yii::$app->mailer->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                ->setTo($cleanEmail)
                ->setSubject('Account registration at ' . Yii::$app->name);

            // Kirim email dan periksa apakah berhasil
            // var_dump(bin2hex($this->email));die;

            $sent = $message->send();

            // if (!$sent) {
            //     var_dump(Yii::$app->mailer->getTransport());
            //     die('Email not sent');
            // }
            return $sent;
        } catch (\Exception $e) {
            echo $e->getMessage();
            die;
        }
    }
}
