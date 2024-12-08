<?php

namespace app\modules\user\controllers;

use app\controllers\SiteController;
use app\modules\user\models\Authentication\LoginForm;
use app\modules\user\models\Authentication\Role;
use app\modules\user\models\User;
use Yii;
use yii\web\Response;

class AuthenticationController extends SiteController
{
    protected $allUsersActions = ['temp-player'];
    protected $guestActions = ['login', 'signup'];
    protected $allowedRoles = [
        Role::ROLE_ADMINISTRATOR,
        Role::ROLE_PLAYER,
        Role::ROLE_MODERATOR,
    ];
    public function actionLogin() : Response|string
    {
        if (
            Yii::$app->user->isGuest === false
            && Yii::$app->user->getIdentity()?->role !== Role::ROLE_TEMPORARY_PLAYER
        ) {
            return $this->redirect('/');
        }
        $model = new LoginForm();
        if ($model->load($this->request->post()) && $model->login()) {
                return $this->redirect('/');

        }
        return $this->render('login',
        [
            'model' => $model,
        ]);
    }

    public function actionLogout() : string
    {
        if (Yii::$app->user->isGuest === false) {
            Yii::$app->user->logout();
        }
        return $this->render('logout');
    }
    public function actionSignup() : string|Response
    {
      $model = new User();
      $model->scenario = User::SCENARIO_SIGNUP;
        if ($model->load($this->request->post()) && ($model->role = Role::ROLE_PLAYER) && $model->signup()) {
            return $this->redirect('/login');
        }
        return $this->render('signup', [
            'model' => $model
        ]);
    }

    public function actionTempPlayer($id) : string|Response
    {
        if (
            Yii::$app->user->isGuest === false
            && Yii::$app->user->getIdentity()?->role !== Role::ROLE_TEMPORARY_PLAYER
        ) {
            return $this->redirect('/');
        }
        if (strtoupper(YII_ENV) !== 'DEV') {
            return $this->redirect('/');
        }
        $user = User::findOne($id);
        if ($user->role === Role::ROLE_TEMPORARY_PLAYER) {
            Yii::$app->user->login($user);
        }
        return $this->redirect('/');
    }
}
