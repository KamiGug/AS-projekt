<?php

namespace app\modules\game\models;

use \app\models\generated\Room as Base;
use Yii;

class Room extends Base
{
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
//                ['email', 'email', 'message' => Yii::t('app', 'Invalid email')],
//                [
//                    'password',
//                    'compare',
//                    'compareAttribute' => 'confirmPassword',
//                    'on' => self::SCENARIO_SIGNUP,
//                    'message' => \Yii::t('app', 'Passwords must match'),
//                ],
//                [['confirmPassword'], 'required', 'on' => self::SCENARIO_SIGNUP],
            ]
        );
    }

    public function scenarios()
    {
        return array_merge(
            parent::scenarios(),
            [
//                self::SCENARIO_LOGIN => ['username', 'password'],
//                self::SCENARIO_SIGNUP => [
//                    'id',
//                    'username',
//                    'password',
//                    'confirmPassword',
//                    'email',
//                    'visible_name',
//                    'role'
//                ],
//                self::SCENARIO_EDIT_SELF => ['username', 'password', 'visible_name'],
//                self::SCENARIO_EDIT_ADMIN => ['username', 'password', 'email', 'visible_name', 'role'],
//                self::SCENARIO_PROFILE_SHOW => ['visible_name'],
            ]
        );
    }

    public function attributeLabels()
    {
        return array_merge(
            parent::rules(),
            [
                'game_type' => Yii::t('app', 'Game'),
                'game_history' => Yii::t('app', 'Game history'),
                'finished_at' => Yii::t('app', 'Finished'),
                'current_gamestate' => Yii::t('app', 'Current board state'),
                'created_by' => Yii::t('app', 'The room was created by '),
                'created_at' => Yii::t('app', 'Created'),
            ]
        );
    }
}
