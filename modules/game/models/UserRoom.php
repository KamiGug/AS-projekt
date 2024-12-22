<?php

namespace app\modules\game\models;

use app\models\exceptions\DBException;
use Yii;
use yii\db\ActiveRecord;
use yii\web\HttpException;

class UserRoom extends \app\models\generated\UserRoom
{
    const SPECTATOR_NUMBER = -1;

    public function attributeLabels()
    {
        return array_merge(
            parent::rules(),
            [
                'player_number' => Yii::t('app', 'Number of the player'),
                'left_at' => Yii::t('app', 'Left the room'),
                'created_at' => Yii::t('app', 'Joined'),
            ]
        );
    }

    public static function getPlayerCurrentRoomConnection($userId) : null|ActiveRecord
    {
        return UserRoom::find()
            ->where(['id_user' => $userId])
            ->andWhere(['left_at' => null])
            ->one();
    }

    public static function playerJoinedRoom($userId, $roomId) : bool
    {
        $playerJoinedRoom = false;
        try {
            $playerJoinedRoom = (int) (UserRoom::find()
                ->where(['id_user' => $userId])
                ->andWhere(['id_room' => $roomId])
                ->andWhere(['left_at' => null])
                ->count()) > 0;
        } catch (\Throwable|\Exception $e) {

        }
        return $playerJoinedRoom;
    }

    public static function getCountByRoomId($roomId, $countSpectators) : int
    {
        $query = self::find()
            ->where(['id_room' => $roomId])
            ->andWhere(['left_at' => null]);
        if (!$countSpectators) {
            $query = $query->andWhere(['<>', 'player_number', self::SPECTATOR_NUMBER]);
        }
        try {
            return $query->count();
        } catch (\Exception|\Throwable) {
            throw new DBException();
        }
    }

    public static function getSingleUserRoom($userId, $roomId) : ActiveRecord|null
    {
        $userRoom = null;
        try {
            $userRoom = self::find()
                ->where(['id_user' => $userId])
                ->andWhere(['id_room' => $roomId])
                ->orderBy(['created_at' => 'DESC'])
                ->one();

        } catch (\Exception|\Throwable) {
            throw new DBException();
        }
        return $userRoom;
    }

    public static function addUserToRoom($userId, $roomId) : bool
    {
        $add = new self();
        $add->id_user = $userId;
        $add->id_room = $roomId;
        $add->player_number = self::SPECTATOR_NUMBER;
        return $add->save();
    }

    public function makePlayer($playerNumber) : bool
    {
        //todo: change existing this->left_at as current timestamp and add new UserRoom with number (after checking if available ofc)
        //todo: add saving to move history of a game
        return false;
    }

    public function makeSpectator() : bool
    {
        //todo: change existing this->left_at as current timestamp and add new UserRoom with -1
        //todo: add saving to move history of a game
        return false;
    }
}
