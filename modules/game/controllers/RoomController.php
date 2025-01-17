<?php

namespace app\modules\game\controllers;

use app\controllers\SiteController;
use app\models\exceptions\DBException;
use app\models\exceptions\MissingAjaxPropertyException;
use app\models\exceptions\NoSuchGameTypeException;
use app\models\exceptions\NoSuchRoomException;
use app\models\exceptions\NotInTheChosenRoomException;
use app\models\exceptions\PlayerNumberIsTakenException;
use app\models\exceptions\UnknownPlayerException;
use app\modules\game\models\base\BaseGameType;
use app\modules\game\models\ludo\LudoGameType;
use app\modules\game\models\GameTypes;
use app\modules\game\models\Room;
use app\modules\game\models\RoomList;
use app\modules\game\models\UserRoom;
use app\modules\user\models\Authentication\Role;
use Faker\Provider\Base;
use Yii;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\Response;

class RoomController extends SiteController
{
//    protected $allUsersActions = ['rejoin'];

//    protected $guestActions = ['join'];


    protected $allowedRoles = [
//        '@',
        Role::ROLE_ADMINISTRATOR,
        Role::ROLE_PLAYER,
        Role::ROLE_MODERATOR
    ];

    public function actionInitList()
    {
        //todo: add cookie with default settings
        if (!Yii::$app->request->isAjax) {
            return $this->redirect('/');
        }
        $type = RoomList::getType(json_decode($this->request->getRawBody())?->type);

        return json_encode([
            'elementPartial' => $this->renderPartial('list/element',
                ['type' => $type]
            ),
            'listPartial' => $this->renderPartial('list/wrapper',
                ['type' => $type]
            ),
            'listTemplate' => $this->renderPartial('list/template'),
            'listBar' => $this->renderPartial('list/bar'),
            'emptyMessage' => $this->renderPartial('list/empty'),
            'listFooter' => $this->renderPartial('list/footer')
        ]);
    }

    public function actionList() //$page = 0, $itemCount = 25
    {
        if (!Yii::$app->request->isAjax) {
            return $this->redirect('/');
        }
        $body = json_decode($this->request->getRawBody(), true);
        if (!isset($body['pageNumber'])) $body['pageNumber'] = 0;
        if (!isset($body['itemCount'])) $body['itemCount'] = 25;
        if (!isset($body['timestamp'])) $body['timestamp'] = null;
        if (!isset($body['sortOrder'])) $body['sortOrder'] = null;
        return json_encode(Room::getRoomsPage(
            $body['pageNumber'],
            $body['itemCount'],
            $body['timestamp'],
            $body['sortOrder']
        ));
    }

    //Join a room
    //Always join as spectator and have times when allowed to change from spectator to player
    public function actionJoin($id)
    {
        if (!Yii::$app->request->isAjax) {
            return $this->redirect('/');
        }
        $room = Room::getById($id);
        if ($room->join(Yii::$app->user->getId()) === false) {
            throw new HttpException(500, 'Unable to join room');
        }
//            if ($room->getPlayerCount() >= GameTypes::GAME_TYPE_MAP[$room->type]::maxPlayers) {
//                throw new HttpException(403, 'This game has already a maximum number of players');
//            }
//        }
        return json_encode(['id' => $id]);
    }

    public function actionRejoin(): false|string|Response
    {
        if (!Yii::$app->request->isAjax) {
            return $this->redirect('/');
        }
        $userRoom = UserRoom::getPlayerCurrentRoomConnection(Yii::$app->user->getId());

        return json_encode(
            [
                'room' => $userRoom?->id_room,
            ]
        );
    }


    /**
     * @throws NotInTheChosenRoomException
     * @throws DBException
     * @throws UnknownPlayerException
     * @throws NoSuchGameTypeException
     */
    public function actionLeave($id)
    {
        if (!Yii::$app->request->isAjax) {
            return $this->redirect('/');
        }
        if (Yii::$app->user->isGuest) {
            throw new UnknownPlayerException();
        }
        $userRoom = UserRoom::getSingleUserRoom(Yii::$app->user->getId(), $id, true);
        if ($userRoom === null) {
            throw new NotInTheChosenRoomException();
        }
        /** @var UserRoom $userRoom */
        if ($userRoom->player_number !== UserRoom::SPECTATOR_NUMBER) {
            $room = Room::getById($id);
            try {
                /** @var BaseGameType $model */
                $model = new (GameTypes::GAME_TYPE_MAP[$room->game_type])(
                    $userRoom->player_number,
                    UserRoom::getActivePlayerNumbers($id),
                    $room
                );
            } catch (\Throwable|\Exception $e) {
                throw new NoSuchGameTypeException();
            }
            try {
                $model->handlePlayerLeaveHistory($userRoom->player_number);
                $model->updateRoom();
            } catch (\Throwable|\Exception $e) {
                throw $e;
            }
        }
        return $userRoom->removePlayerFromRoom()
            ? json_encode([
                'message' => 'successfully left the room'
            ])
            : json_encode([
                'message' => 'there was a problem when leaving the room'
            ]);
    }


    /**
     * @throws DBException
     * @throws NoSuchRoomException
     * @throws MissingAjaxPropertyException
     * @throws NoSuchGameTypeException
     * @throws \Throwable
     */
    public function actionMove($id)
    {
        if (!Yii::$app->request->isAjax) {
            return $this->redirect('/');
        }
        $body = json_decode($this->request->getRawBody(), true);
        if (isset($body['move']) === false) {
            throw new MissingAjaxPropertyException('move');
        }
        $room = Room::getById($id);
        if ($room === null) {
            throw new NoSuchRoomException($id);
        }
        $userRoom = UserRoom::getSingleUserRoom(Yii::$app->user->getId(), $id, true);
        $activePlayerNumbers = UserRoom::getActivePlayerNumbers($id);
        try {
            $model = new (GameTypes::GAME_TYPE_MAP[$room->game_type])(
                $userRoom->player_number,
                $activePlayerNumbers,
                $room
            );
        } catch (\Throwable|\Exception $e) {
            throw new NoSuchGameTypeException();
        }

        try {
            $model->handleMove($body['move']);
        } catch (\Throwable|\Exception $e) {
            throw $e;
        }


        return json_encode([
            'boardState' => $model->getBoardState(),
            'moveList' => $model->getMoveList(),
            'playerNumber' => $userRoom->player_number,
            'lastRefresh' => $room->modified_at,
            'activePlayerNumbers' => json_encode($activePlayerNumbers),
        ]);
    }

    public function actionInitRoom($id): Response|false|string
    {
        if (!Yii::$app->request->isAjax) {
            return $this->redirect('/');
        }
        $room = Room::getById($id);
        if ($room === null) {
            throw new NoSuchRoomException($id);
        }
//        if ($model->playerJoined(Yii::$app->user->getId()) === false) {
//            throw new HttpException(403, 'Player has not joined this game!');
//        }

        return json_encode([
            'board' => $this->renderAjax($room->game_type),
            'id' => $id,
            'roomName' => $room->name,
        ]);
    }

    //Refresh Board State and Move List

    /**
     * @throws DBException
     * @throws NoSuchRoomException
     * @throws NoSuchGameTypeException
     */
    public function actionRefresh($id)
    {
        if (!Yii::$app->request->isAjax) {
            return $this->redirect('/');
        }
        $body = json_decode($this->request->getRawBody(), true);
        $room = Room::getById($id);
        if ($room === null) {
            throw new NoSuchRoomException($id);
        }
        /** @var Room $room */
        if (isset($body['lastRefresh']) && $body['lastRefresh'] >= $room->modified_at) {
            return json_encode([]);
        }


        $userRoom = UserRoom::getSingleUserRoom(Yii::$app->user->getId(), $id, true);
        $activePlayerNumbers = UserRoom::getActivePlayerNumbers($id);

        try {
            $model = new (GameTypes::GAME_TYPE_MAP[$room->game_type])(
                $userRoom->player_number,
                $activePlayerNumbers,
                $room
            );
        } catch (\Throwable|\Exception $e) {
            throw new NoSuchGameTypeException();
        }
        return json_encode([
            'boardState' => $model->getBoardState(),
            'moveList' => $model->getMoveList(),
            'playerNumber' => $userRoom->player_number,
            'lastRefresh' => $room->modified_at,
            'activePlayerNumbers' => json_encode($activePlayerNumbers),
        ]);
    }

    public function actionRestartRoom($id): Response|string
    {
        if (!Yii::$app->request->isAjax) {
            return $this->redirect('/');
        }
        // todo: check if user is "host" if so let them restart the room, else if they are alone also allow them (and make them host)
        // restart = make a new room and connect everyone
        return '';
    }

    /**
     * @throws NotInTheChosenRoomException
     * @throws DBException
     * @throws NoSuchRoomException
     * @throws NoSuchGameTypeException
     * @throws PlayerNumberIsTakenException
     */
    public function actionChangePlayerNumber($roomId, $playerNumber): Response|string
    {
        if (!Yii::$app->request->isAjax) {
            return $this->redirect('/');
        }
        $playerNumber = (int) $playerNumber;
        $userRoom = UserRoom::getSingleUserRoom(Yii::$app->user->getId(), $roomId, true);
        if ($userRoom === null) {
            throw new NotInTheChosenRoomException();
        }
        if ($playerNumber === $userRoom->player_number) {
            return json_encode(['playerNumber' => $playerNumber]);
        }
        $activePlayerNumbers = UserRoom::getActivePlayerNumbers($roomId);
        if (in_array($playerNumber, $activePlayerNumbers)) {
            throw new PlayerNumberIsTakenException();
        }
        $room = Room::getById($roomId);
        if ($room === null) {
            throw new NoSuchRoomException($roomId);
        }
        $originalPlayerNumber = $userRoom->player_number;

        //todo: add transaction over this to the end!
        $userRoom->updatePlayerNumber($playerNumber);
        $activePlayerNumbers = UserRoom::getActivePlayerNumbers($roomId);
//        $userRoom->player_number = $playerNumber;
//        $userRoom->save();
        try {
            $model = new (GameTypes::GAME_TYPE_MAP[$room->game_type])(
                $playerNumber,
                $activePlayerNumbers,
                $room
            );
        } catch (\Throwable|\Exception $e) {
            throw new NoSuchGameTypeException();
        }
        if ($playerNumber === UserRoom::SPECTATOR_NUMBER) {
            $model->handlePlayerLeaveHistory($originalPlayerNumber);
        } else {
            if ($originalPlayerNumber !== UserRoom::SPECTATOR_NUMBER) {
                $model->handlePlayerLeaveHistory($originalPlayerNumber, false);
            }
            $model->handlePlayerJoinHistory($playerNumber);
        }
        $model->updateRoom();
        return json_encode([
            'boardState' => $model->getBoardState(),
            'moveList' => $model->getMoveList(),
            'playerNumber' => $userRoom->player_number,
            'lastRefresh' => $room->modified_at,
            'activePlayerNumbers' => json_encode($activePlayerNumbers),
        ]);
    }
}
