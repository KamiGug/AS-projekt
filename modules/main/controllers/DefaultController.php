<?php

namespace app\modules\main\controllers;

use app\controllers\SiteController;

class DefaultController extends SiteController
{
    public function actionIndex()
    {
//        var_dump('asdasd');die;
        return $this->render('index');
    }
}
