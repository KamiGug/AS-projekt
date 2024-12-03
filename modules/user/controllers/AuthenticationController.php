<?php

namespace app\modules\user\controllers;

use app\controllers\SiteController;
use app\modules\user\models\Authentication;
use app\modules\user\models\LoginForm;
use Yii;
use yii\web\HttpException;
use yii\web\Response;

class AuthenticationController extends SiteController
{
    public function actionLogin() : string
    {
//        var_dump('asdasd');die;

        return $this->render('login',
        [
            'loginModel' => new LoginForm(),
        ]);
    }

    public function actionLogout() : string
    {
        if (Yii::$app->user->isGuest === false) {
            Yii::$app->user->logout();
        }
        return $this->render('logout');
    }
    public function actionSignup() : string
    {
//        var_dump('asdasd');die;
        return $this->render('signup');
    }

    public function actionAuthLogin() : Response|string
    {
        if (!$this->request->isPost) {
            throw new HttpException(405, 'authlogin only accepts POST');
        }
        $model = new Authentication();
        $loggedIn = $model->handleLogin(
            $this->request->getBodyParam('LoginForm')['username'],
            $this->request->getBodyParam('LoginForm')['password'],
            $this->request->getBodyParam('LoginForm')['rememberMe'] === '1'
        );
        if ($loggedIn) {
            //todo: change to previous URL
            return $this->redirect('/');
        }
        return $this->redirect('/login');
    }


}
