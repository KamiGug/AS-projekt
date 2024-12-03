<?php

namespace app\modules\user\models;

use app\models\database\User;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
//    public $validatePassword;
    public $rememberMe = true;

    private $_user = null;

    public function rules()
    {
        return [
            [['username', 'password'
//                , 'validatePassword'
            ], 'required'],
//            ['password', 'compare', 'compareAttribute' => 'validatePassword'],
            ['rememberMe', 'boolean'],
//            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return array_merge(Parent::attributeLabels(),
            [
                'name' => \Yii::t('app', 'Your name'),
                'email' => \Yii::t('app', 'Your email address'),
                'subject' => \Yii::t('app', 'Subject'),
                'body' => \Yii::t('app', 'Content'),
            ]
        ) ;
    }

//    public function validatePassword($attribute, $params)
//    {
//        if (!$this->hasErrors()) {
//            $user = $this->getUser();
//
//            if (!$user || !$user->validatePassword($this->password)) {
//                $this->addError($attribute, 'Incorrect username or password.');
//            }
//        }
//    }
//
//    public function login()
//    {
//        if ($this->validate()) {
//            // var_dump($this->getUser());die;
//            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
//        }
//        return false;
//    }
//
//    /**
//     * Finds user by [[username]]
//     *
//     * @return User|null
//     */
//    public function getUser(): User|null
//    {
//        if ($this->_user === null) {
//            $this->_user = User::findByUsername($this->username);
//        }
//
//        return $this->_user;
//    }
}
