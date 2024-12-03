<?php

namespace app\models\database;

use Yii;
use yii\web\IdentityInterface;

class User extends \app\models\generated\User implements IdentityInterface
{
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_SIGNUP = 'signup';
    const SCENARIO_EDIT_SELF = 'self-edit';
    const SCENARIO_EDIT_ADMIN = 'admin-edit';
    const SCENARIO_PROFILE_SHOW = 'show-profile';

    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['username', 'password', 'email', 'visible_name'], 'required'],
                ['email', 'email', 'message' => Yii::t('app', 'Invalid email')],
            ]
        );
    }

    public function scenarios()
    {
        return array_merge(
            parent::scenarios(),
            [
                self::SCENARIO_LOGIN => ['username', 'password'],
                self::SCENARIO_SIGNUP => ['username', 'password', 'email', 'visible_name'],
                self::SCENARIO_EDIT_SELF => ['username', 'password', 'visible_name'],
                self::SCENARIO_EDIT_ADMIN => ['username', 'password', 'email', 'visible_name', 'id_role'],
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

    public static function findByUsername($username) : User|null {
        return static::findOne(['username' => $username]);
    }

    public function validatePassword($password) : bool {
        return Yii::$app->getSecurity()->validatePassword(
            $password,
            $this->password
        );
    }
    // public $id;
    // public $username;
    // public $password;
    // public $authKey;
    // public $accessToken;

    // private static $users = [
    //     '100' => [
    //         'id' => '100',
    //         'username' => 'admin',
    //         'password' => 'admin',
    //         'authKey' => 'test100key',
    //         'accessToken' => '100-token',
    //     ],
    //     '101' => [
    //         'id' => '101',
    //         'username' => 'demo',
    //         'password' => 'demo',
    //         'authKey' => 'test101key',
    //         'accessToken' => '101-token',
    //     ],
    // ];


    // /**
    //  * {@inheritdoc}
    //  */
    // public static function findIdentity($id)
    // {
    //     return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    // }

    // /**
    //  * {@inheritdoc}
    //  */
    // public static function findIdentityByAccessToken($token, $type = null)
    // {
    //     foreach (self::$users as $user) {
    //         if ($user['accessToken'] === $token) {
    //             return new static($user);
    //         }
    //     }

    //     return null;
    // }

    // /**
    //  * Finds user by username
    //  *
    //  * @param string $username
    //  * @return static|null
    //  */
    // public static function findByUsername($username)
    // {
    //     foreach (self::$users as $user) {
    //         if (strcasecmp($user['username'], $username) === 0) {
    //             return new static($user);
    //         }
    //     }

    //     return null;
    // }

    // /**
    //  * {@inheritdoc}
    //  */
    // public function getId()
    // {
    //     return $this->id;
    // }

    // /**
    //  * {@inheritdoc}
    //  */
    // public function getAuthKey()
    // {
    //     return $this->authKey;
    // }

    // /**
    //  * {@inheritdoc}
    //  */
    // public function validateAuthKey($authKey)
    // {
    //     return $this->authKey === $authKey;
    // }

    // /**
    //  * Validates password
    //  *
    //  * @param string $password password to validate
    //  * @return bool if password provided is valid for current user
    //  */
    // public function validatePassword($password)
    // {
    //     return $this->password === $password;
    // }
}
