<?php

namespace app\controllers;

use app\modules\user\models\Authentication\AccessControl;
use app\modules\user\models\Authentication\BanType;
use app\modules\user\models\Authentication\Role;
use app\modules\user\models\Ban\Ban;
use Yii;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */

    //actions that are allowed to be accessed by ALL users (guests included)
    protected $allUsersActions = ['placeholder'];

    //actions that are allowed to be accessed by guests and allowedRoles
    protected $guestActions = ['placeholder'];
    //roles that are allowed to access all actions in the controller except guest only actions
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
                        'roles' => ['?', Role::ROLE_TEMPORARY_PLAYER],
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
//            'denyCallback' => function () {
//                $userRole = Yii::$app->user->getIdentity()?->role;
//                if ($userRole === null || $userRole === Role::ROLE_TEMPORARY_PLAYER) {
//                    return Yii::$app->getResponse();//->redirect('/login');
//                } else {
//                    return Yii::$app->getResponse();//->redirect(['/']);
//                }
//            }
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

    public function beforeAction($action)
    {
        if (parent::beforeAction($action) === false) {
            return false;
        }
        $user = Yii::$app->user?->getIdentity();
        if (
            Yii::$app->user->isGuest === false
            && $action->actionMethod !== 'actionBanned'
            && $user->role !== Role::ROLE_ADMINISTRATOR
            && Ban::isUserCurrentlyBanned($user->id, BanType::BAN_TYPE_ALL)
        ) {
            return $this->redirect(['/user/ban/banned']);
        }
        return true;
    }
}
