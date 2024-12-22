<?php

namespace app\modules\game\controllers;

use app\controllers\SiteController;
use app\models\exceptions\NoSuchRoomException;
use app\modules\game\models\base\BaseGameType;
use app\modules\game\models\GameTypes;
use app\modules\game\models\Room;
use app\modules\game\models\RoomList;
use app\modules\game\models\UserRoom;
use app\modules\user\models\Authentication\Role;
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


    public function actionLeave($id)
    {

    }


    public function actionMove($id)
    {
        //gets move from post data and passes it to correct model to handle it
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
            'gameTemplate' => $this->renderPartial('game/template'),
//            'board' => $this->renderPartial('../' . ($model->game_type ?? GameTypes::TYPE_BASE) . '/board'),
            'chatWrapper' => $this->renderPartial('game/chat'),
            //'../json/' . ($model->game_type ?? GameTypes::TYPE_BASE) . '.json'
            ...(GameTypes::GAME_TYPE_MAP[$room->game_type]::getTemplates())
        ]);
        // send view-template with empty space for board and chat wrapper
        // for board side: call EmptyBoard and Refresh
        // for chat side: call chat init action
        // * view with RoomWidget
    }

    //Refresh Board State and Move List
    public function actionRefresh($id)
    {

    }
}
