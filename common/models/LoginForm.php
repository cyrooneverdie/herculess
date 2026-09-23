<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $subscription;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {

        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {

        return [
            'username' => Yii::$app->lang->t('signin', 'username'),
            'password' => Yii::$app->lang->t('signin', 'password'),
            // 'email' => Yii::$app->lang->t('signup', 'email'),
            // 'subs_id'
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            
            // $pw = crypt('SAVE', '$2y$13$GRqvUHGOu52R2wMRU73FPeG0wAl62CNwU/z/BP45SgUW4LqaI1Xk.');
            // $pw2 = $user->validatePassword($this->password);
            if (!$user || !$user->validatePassword($this->password)) {
                // var_dump($pw,$pw2);die;
                $this->addError($attribute, 'Incorrect username/email or password.');
            }
            // elseif ($user->status == User::STATUS_INACTIVE) {
            //     $this->addError($attribute, 'Your account is not yet verified');
            // }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            // var_dump($this->validate());die;
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user == null) {
            // $this->_user = User::findOne(['name' => $this->username, 'email' => $this->email]);
            $this->_user = User::find()
                ->where([
                    'or',
                    ['name' => $this->username],
                    ['email' => $this->username],
                ])
                //->andWhere(['<>', 'status', User::STATUS_DELETED]) // Exclude deleted accounts
                ->one();
        }
        return $this->_user;
    }
}
