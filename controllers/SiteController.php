<?php

namespace app\controllers;

use app\models\AccessControl;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */

    protected $allUsersActions = [];
    protected $guestActions = [];
    protected $allowedRoles = ['@'];
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    //allow all users use actions that are available to all users
                    [
                        'actions' => $this->allUsersActions,
                        'allow' => true,
                    ],
                    //allow guests to use guest only actions
                    [
                        'actions' => $this->guestActions,
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    //forbid guests to use any other actions
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    //allow whitelisted users to use this controller
                    [
                        'allow' => true,
                        'roles' => $this->allowedRoles,
                    ]
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => '\yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => '\yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
}
