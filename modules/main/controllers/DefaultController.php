<?php

namespace app\modules\main\controllers;

use app\controllers\SiteController;
use app\modules\user\models\Authentication\Role;

class DefaultController extends SiteController
{
    protected $allUsersActions = ['index'];
    protected $guestActions = ['contact'];
    protected $allowedRoles = [Role::ROLE_ADMINISTRATOR];
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionContact()
    {
        return $this->render('contact');
    }
}
