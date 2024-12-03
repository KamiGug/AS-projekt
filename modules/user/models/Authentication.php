<?php

namespace app\modules\user\models;

use app\models\database\User;
use Yii;
use yii\base\Model;

class Authentication extends Model
{
    public function handleLogin($username, $password, $rememberMe) : bool
    {
        $user = User::findByUsername($username);
        $loggedIn = false;
        if ($user !== null && $user->validatePassword($password)) {
            if (Yii::$app->user->login($user, $rememberMe ? 3600*24*30 : 0)) {
                $loggedIn = true;
            }
        }
        if (!$loggedIn) {
            $this->addError('password', 'Incorrect username or password!');
            return false;
        }
        return true;
    }
}
