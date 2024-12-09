<?php

namespace app\modules\user\models;

use app\modules\user\models\Authentication\Role;
use Yii;
use yii\web\IdentityInterface;

class User extends \app\models\generated\User implements IdentityInterface
{
    const SCENARIO_SIGNUP = 'signup';
    const SCENARIO_EDIT_SELF = 'self-edit';
    const SCENARIO_EDIT_ADMIN = 'admin-edit';
    const SCENARIO_PROFILE_SHOW = 'show-profile';

    public string $confirmPassword = '';

    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                ['role', 'in', 'range' => array_keys(Role::getRoles())],
                ['email', 'email', 'message' => Yii::t('app', 'Invalid email')],
                [
                    'password',
                    'compare',
                    'compareAttribute' => 'confirmPassword',
                    'on' => self::SCENARIO_SIGNUP,
                    'message' => \Yii::t('app', 'Passwords must match'),
                ],

                [['confirmPassword'], 'required', 'on' => self::SCENARIO_SIGNUP],

            ]
        );
    }

    public function scenarios()
    {
        return array_merge(
            parent::scenarios(),
            [
//                self::SCENARIO_LOGIN => ['username', 'password'],
                self::SCENARIO_SIGNUP => [
                    'id',
                    'username',
                    'password',
                    'confirmPassword',
                    'email',
                    'visible_name',
                    'role'
                ],
                self::SCENARIO_EDIT_SELF => ['username', 'password', 'visible_name'],
                self::SCENARIO_EDIT_ADMIN => ['username', 'password', 'email', 'visible_name', 'role'],
                self::SCENARIO_PROFILE_SHOW => ['visible_name'],
            ]
        );
    }

    public function attributeLabels()
    {
        return array_merge(
            parent::rules(),
            [
                'username' => Yii::t('app', 'Username'),
                'email' => Yii::t('app', 'Email'),
                'password' => Yii::t('app', 'Password'),
                'visible_name' => Yii::t('app', 'Nickname'),
                'confirmPassword' => Yii::t('app', 'Confirm Password'),
            ]
        );
    }

    public function getAuthKey() : string {
        return sha1(
            'ajBt%81,' . $this->id . $this->created_at . $this->username
        );
    }

    public function validateAuthKey($authKey) : bool {
        return $this->getAuthKey() === $authKey;
    }

    public function getId() {
        return $this->id;
    }

    public static function findIdentity($id) : User|null {
        $user = static::findOne($id);
        return $user ?: null;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public static function findByUsernameOrEmail($field) : User|null {
        return static::findOne(['username' => $field]) ?? static::findOne(['email' => $field]);
    }

    public function validatePassword($password) : bool {
        return Yii::$app->getSecurity()->validatePassword(
            $password,
            $this->password
        );
    }

//    public static function addUser($username, $email, $password, $visibleName, $role, $id = null) : bool
//    {
//        $user = new User();
//        $user->scenario = self::SCENARIO_SIGNUP;
//        if ($id !== null) {
//            $user->id = $id;
//        }
//        $user->username = $username;
//        $user->email = $email;
//        $user->password = Yii::$app->getSecurity()->generatePasswordHash($password);
//        $user->visible_name = $visibleName;
//        $user->role = $role;
//        try {
//            $test = $user->save();
//            ob_start();
//            var_dump('test', $test);
//            file_put_contents('/tmp/dd.log', ob_get_clean() . PHP_EOL, FILE_APPEND);
//            return $test;
//        } catch (\Exception|\Throwable) {
//            return false;
//        }
//    }

    public function signUp() : bool {
        if ($this->validate()) {
            $commited = $this->save();
            if (!$commited) {
                $this->password = $this->confirmPassword;
            }
        }
        if (!$commited) {
            $this->password = $this->confirmPassword;
            $this->confirmPassword = '';
        }
        return $commited;
    }

    public function beforeSave($insert) : bool
    {
        if (parent::beforeSave($insert)) {
            $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
            return true;
        }
        return false;
    }

    public static function getRole($userId) : string|null {
        return static::findOne(['id' => $userId])?->role;
    }
}
