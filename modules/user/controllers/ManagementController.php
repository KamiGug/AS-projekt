<?php

namespace app\modules\user\controllers;

use app\controllers\SiteController;
use app\modules\user\models\Authentication\Role;
use app\modules\user\models\search\UserSearch;
use app\modules\user\models\User;
use Yii;
use yii\web\Cookie;
use yii\web\Response;

class ManagementController extends SiteController
{
    protected $allUsersActions = ['profile'];
    protected $allowedRoles = [
        Role::ROLE_ADMINISTRATOR,
        Role::ROLE_PLAYER,
        Role::ROLE_MODERATOR,
    ];
    public function actionProfile($id) : Response|string
    {
        $model = User::findOne($id);

        return $this->render('profile', [
            'model' => $model,
            'own' => $model->id === Yii::$app->user->id,
            'isAdmin' => Yii::$app->user->getIdentity()?->role === Role::ROLE_ADMINISTRATOR,
        ]);
    }

    public function actionEditProfile($id) : Response|string
    {
        if (
            (int) $id !== (int) Yii::$app->user->getIdentity()?->getId()
            && Yii::$app->user->getIdentity()?->role !== Role::ROLE_ADMINISTRATOR
        ) {
            return $this->redirect('/');
        }
        $user = User::findOne($id);
        $model = new User();
        $model->id = $user->id;
        $model->role = $user->role;
        $model->scenario = Yii::$app->user->getIdentity()?->role === Role::ROLE_ADMINISTRATOR
        && (int) $id !== (int) Yii::$app->user->getIdentity()?->getId()
            ? User::SCENARIO_EDIT_ADMIN
            : User::SCENARIO_EDIT_SELF;
        if ($model->load($this->request->post()) && $model->validate()) {
            $user->fillWithNonEmptyAttributes($model);
            if ($user->save()) {
                Yii::$app->session->setFlash('success', 'User information updated successfully.');
            } else {
                Yii::$app->session->setFlash('error', 'Failed to update user information.');
            }
        }
        return $this->render('editProfile', [
            'model' => $model,
        ]);
    }

    public function actionProfiles($page = null, $count = null) : Response|string
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
            if (Yii::$app->request->cookies->getValue('countPerPageProfiles') != $count) {
                Yii::$app->response->cookies->add(new Cookie([
                    'name' => 'countPerPageProfiles',
                    'value' => $count,
                ]));
            }
        } else {
            if (Yii::$app->request->cookies->has('countPerPageProfiles') === false) {
                Yii::$app->response->cookies->add(new Cookie([
                    'name' => 'countPerPageProfiles',
                    'value' => USER::LIST_COUNT_PER_PAGE_DEFAULT,
                ]));
                $count = USER::LIST_COUNT_PER_PAGE_DEFAULT;
            } else {
                $count = (int) Yii::$app->request->cookies->getValue('countPerPageProfiles');
            }
        }

        $searchModel = new UserSearch();
        $isAdmin = Yii::$app->user->getIdentity()?->role === Role::ROLE_ADMINISTRATOR;
        $searchModel->load(Yii::$app->request->get());
        if ($isAdmin === false) {
            $searchModel->role = Role::ROLE_PLAYER;
        }
        $searchModel->validate();

        return $this->render('profileList', [
            'list' => $searchModel->search($count),
            'countPerPage' => $count,
            'searchModel' => $searchModel,
            'isAdmin' => $isAdmin,
        ]);
    }
}
