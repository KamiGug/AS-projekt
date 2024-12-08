<?php

namespace app\modules\user\models\Authentication;

use app\modules\user\models\User;
use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe;

    private $_user = null;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
//            ['password', 'compare', 'compareAttribute' => 'validatePassword'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return array_merge(Parent::attributeLabels(),
            [
                'username' => \Yii::t('app', 'Username'),
                'password' => \Yii::t('app', 'Password'),
                'rememberMe' => \Yii::t('app', 'Remember me'),
            ]
        ) ;
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

//    public function handleLogin($username, $password, $rememberMe) : bool
//    {
//        $user = getUser();
//        $loggedIn = false;
//        if ($user !== null && $user->validatePassword($password)) {
//            if () {
//                $loggedIn = true;
//            }
//        }
//        if (!$loggedIn) {
//            $this->addError('password', 'Incorrect username or password!');
//            return false;
//        }
//        return true;
//    }

    public function getUser() : User|null
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsernameOrEmail($this->username);
        }
        return $this->_user;
    }
}
