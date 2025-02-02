<?php

namespace app\modules\game\models;
use app\models\DBDate;
use \app\models\generated\Room as Base;
use app\modules\user\models\User;
use Yii;
use yii\db\ActiveRecord;
use yii\web\HttpException;

class Room extends Base
{
    const SCENARIO_CREATE = 'create';
    const ROOM_LIST_PAGE_DEFAULT_LENGTH = 10;

    public function init()
    {
        parent::init();
        if ($this->isNewRecord) {
            //todo: create chat
            $this->current_player_number = UserRoom::SPECTATOR_NUMBER;
            $this->seed = rand();
            $this->game_history = '[]';
        }
    }

    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                ['game_type', 'in', 'range' => array_keys(GameTypes::GAME_TYPE_MAP)],
                ['name', 'string', 'min' => 3],
                [['name', 'game_type'], 'safe'],
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
                self::SCENARIO_CREATE => ['name', 'game_type']
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

    /**
     * @throws HttpException
     */

    public function playerJoined($userId) : bool
    {
        return UserRoom::playerJoinedRoom($userId, $this->id);
    }

    public static function getById($roomId) : ActiveRecord|null
    {
        $room = null;
        try {
            $room = Room::find()
                ->where(['id' => $roomId])
                ->one();
        } catch (\Throwable|\Exception $e) { }
        return $room;
    }

    public function getPlayerCount() : int
    {
        return UserRoom::getCountByRoomId($this->id, false);
    }

    public function join($userId) : bool
    {
        if ($this->playerJoined($userId)) {
            return true;
        }
        return UserRoom::addUserToRoom($userId, $this->id);
    }

    public function getPlayers()
    {
        return $this->hasMany(User::class, ['id' => 'id_user'])->via('userRooms');
    }
}
