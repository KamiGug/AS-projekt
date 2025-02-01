<?php

namespace app\modules\user\controllers;

use app\controllers\SiteController;
use app\modules\game\models\UserRoom;
use app\modules\user\models\Authentication\BanType;
use app\modules\user\models\Authentication\Role;
use app\modules\user\models\Ban\Ban;
use app\modules\user\models\search\BanSearch;
use app\modules\user\models\User;
use Yii;
use yii\helpers\Url;
use yii\web\Cookie;
use yii\web\Response;

class BanController extends SiteController
{
    protected $allUsersActions = ['banned'];
    protected $allowedRoles = [
        Role::ROLE_ADMINISTRATOR,
    ];

    public function actionBan() : string|Response
    {
        $model = new Ban;
        if ($model->load($this->request->post()) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Banned successfully');
            } else {
                Yii::$app->session->setFlash('error', 'An error has occurred while processing ban.');
            }
            if ($model->type === BanType::BAN_TYPE_ALL) {
                UserRoom::leaveAllRooms($model->id_user);
            }
        }
        return $this->redirect(Url::to('/user/management/profiles'));
    }

    public function actionRevoke($id) : string|Response
    {
        $model = Ban::findOne(['id'=>$id]);
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Ban has been revoked.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to revoke the ban.');
        }
        return $this->redirect(Url::to(['/user/ban/list']));
    }

    public function actionBanned() : string|Response
    {
        $user = Yii::$app->user?->getIdentity();
        $ban = Ban::getLastActiveBan($user?->id, BanType::BAN_TYPE_ALL);
        if ($ban === null) {
            return $this->redirect('/');
        }
        $issuer = User::findOne(['id' => $ban->issued_by])?->visible_name;

        return $this->render('banned', [
            'ban' => $ban,
            'issuer' => $issuer,
        ]);
    }

    public function actionList ($page = null, $count = null) : Response|string
    {
        if (isset($page)) {
            $page = (int) $page;
        } else {
            $page = 0;
        }
        if (isset($count)) {
            $count = (int) $count;
        }
        if ($count !== null) {
            if (Yii::$app->request->cookies->getValue('countPerPageBans') != $count) {
                Yii::$app->response->cookies->add(new Cookie([
                    'name' => 'countPerPageBans',
                    'value' => $count,
                ]));
            }
        } else {
            if (Yii::$app->request->cookies->has('countPerPageBans') === false) {
                Yii::$app->response->cookies->add(new Cookie([
                    'name' => 'countPerPageBans',
                    'value' => USER::LIST_COUNT_PER_PAGE_DEFAULT,
                ]));
                $count = USER::LIST_COUNT_PER_PAGE_DEFAULT;
            } else {
                $count = (int) Yii::$app->request->cookies->getValue('countPerPageBans');
            }
        }
        $searchModel = new BanSearch();
        $isAdmin = Yii::$app->user->getIdentity()?->role === Role::ROLE_ADMINISTRATOR;
        $searchModel->load(Yii::$app->request->get());
        if ($isAdmin === false) {
            $searchModel->role = Role::ROLE_PLAYER;
        }
        $searchModel->validate();

        return $this->render('list', [
            'list' => $searchModel->search($count),
            'countPerPage' => $count,
            'searchModel' => $searchModel,
            'isAdmin' => $isAdmin,
        ]);
    }
}
