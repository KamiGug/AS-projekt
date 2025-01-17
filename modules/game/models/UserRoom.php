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

    public static function getSingleUserRoom($userId, $roomId, $isCurrent = false) : ActiveRecord|null
    {
        $userRoom = null;
        try {
            $userRoom = self::find()
                ->where(['id_user' => $userId])
                ->andWhere(['id_room' => $roomId]);
            if ($isCurrent) {
                $userRoom = $userRoom->andWhere(['left_at' => null]);
            }
            $userRoom = $userRoom
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

    public function removePlayerFromRoom() : bool
    {
        $this->left_at = date("Y-m-d H:i:s");;
        return $this->save();
    }

    public function updatePlayerNumber($playerNumber) : bool
    {
        try {
            $newRecord = new self();
            $this->left_at = date("Y-m-d H:i:s");
            $this->save();
            $newRecord->id_user = $this->id_user;
            $newRecord->id_room = $this->id_room;
            $newRecord->player_number = $playerNumber;
            $newRecord->insert();
        } catch (\Exception|\Throwable $e) {
            ob_start();
            var_dump($e);
            file_put_contents('/tmp/df.log', ob_get_clean() . PHP_EOL, FILE_APPEND);
            return false;
        }
        ob_start();
        var_dump('out');
        file_put_contents('/tmp/df.log', ob_get_clean() . PHP_EOL, FILE_APPEND);
        return true;
    }

//    public function makeSpectator() : bool
//    {
//        try {
//            $newRecord = clone $this;
//            $this->left_at = date("Y-m-d H:i:s");
//            $this->save();
//            $newRecord->player_number = self::SPECTATOR_NUMBER;
//            $newRecord->save();
//        } catch (\Exception|\Throwable $e) {
//            return false;
//        }
//        return true;
//    }

    public static function getActivePlayerNumbers($roomId) : array
    {
        $playerNumbers = [];
        try {
            $playerNumbers = self::find()
                ->where(['id_room' => $roomId])
                ->andWhere(['left_at' => null])
                ->andWhere(['<>', 'player_number', self::SPECTATOR_NUMBER])
                ->orderBy(['player_number' => 'ASC'])
                ->all();
            $playerNumbers = array_map(function ($userRoom) {
                return $userRoom->player_number;
            }, $playerNumbers);
        } catch (\Exception|\Throwable) {
            throw new DBException();
        }
        return $playerNumbers;
    }
}
