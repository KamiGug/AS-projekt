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
use app\modules\game\models\RoomSearch;
use app\modules\game\models\UserRoom;
use app\modules\user\models\Authentication\Role;
use Yii;
use yii\web\Cookie;
use yii\web\HttpException;
use yii\web\Response;

class RoomController extends SiteController
{
//    protected $allUsersActions = ['list'];

//    protected $guestActions = ['join'];


    protected $allowedRoles = [
//        '@',
        Role::ROLE_ADMINISTRATOR,
        Role::ROLE_PLAYER,
        Role::ROLE_MODERATOR
    ];

    public function actionInitList()
    {
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
            'listFooter' => $this->renderPartial('list/footer'),
            'paginationElementEnabled' => $this->renderPartial('list/_partials/_pagination-element-enabled'),
            'paginationElementDisabled' => $this->renderPartial('list/_partials/_pagination-element-disabled'),
        ]);
    }

    public function actionList($page = null)
    {
        if (!Yii::$app->request->isAjax) {
            return $this->redirect('/');
        }
        $page = (int) $page;
        $model = new RoomSearch;
        $model->load($this->request->post());
        $count = $model->itemCount;
        if ($count !== null && strlen( (string) $count)) {
            $count = (int) $count;
            if ($count < 1) {
                $count = Room::ROOM_LIST_PAGE_DEFAULT_LENGTH;
            }
            if (Yii::$app->request->cookies->getValue('countPerPageRoomList') != $count) {
                Yii::$app->response->cookies->add(new Cookie([
                    'name' => 'countPerPageRoomList',
                    'value' => $count,
                ]));
            }
        } else {
            if (Yii::$app->request->cookies->has('countPerPageRoomList') === false) {
                Yii::$app->response->cookies->add(new Cookie([
                    'name' => 'countPerPageProfiles',
                    'value' => Room::ROOM_LIST_PAGE_DEFAULT_LENGTH,
                ]));
                $count = Room::ROOM_LIST_PAGE_DEFAULT_LENGTH;
            } else {
                $count = (int) Yii::$app->request->cookies->getValue('countPerPageRoomList');
            }
        }
        $model = new RoomSearch;
        $model->load($this->request->post());
        $model->itemCount = $count;
        $model->pageNumber = $page;
        $model->validate();
        $model->validate('timestamp', false);

        return json_encode($model->search());
    }

    public function actionNew()
    {
        if (!Yii::$app->request->isAjax) {
            return $this->redirect('/');
        }
        $room = new Room();
        $room->load($this->request->post());
        if ($room->validate() && $room->save()) {
            Yii::$app->session->setFlash('success', 'Successfully created a room');
        } else {
            Yii::$app->session->setFlash('error', 'An error has occurred while creating a room.');
        }
        $room->join(Yii::$app->user->getId());
        return json_encode(['id' => $room->id]);
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
        UserRoom::leaveRoom($userRoom);
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
