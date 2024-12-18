<?php

namespace app\modules\chat\controllers;

use app\controllers\SiteController;

class DefaultController extends SiteController
{
    protected $allUsersActions = ['index'];
    protected $guestActions = ['contact'];
    protected $allowedRoles = ['admin'];
    public function actionIndex()
    {
//        var_dump('asdasd');die;
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
