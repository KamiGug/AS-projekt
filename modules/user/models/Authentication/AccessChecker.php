<?php

namespace app\modules\user\models\Authentication;

use app\modules\user\models\User;
use yii\rbac\CheckAccessInterface;

class AccessChecker implements CheckAccessInterface
{
    public function checkAccess($userId, $permissionName, $params = []) : bool
    {
        if (array_key_exists($permissionName, Role::getRoles())) {
            return User::getRole($userId) === $permissionName;
        }
        return false;
    }
}
