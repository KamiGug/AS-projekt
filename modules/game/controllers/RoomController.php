<?php

namespace app\modules\game\controllers;

use app\controllers\SiteController;
use app\modules\game\models\Room;
use app\modules\game\models\RoomList;
use app\modules\game\models\UserRoom;
use app\modules\user\models\Authentication\Role;
use Yii;
use yii\helpers\Url;
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
    public function actionJoin($id)
    {

    }

    public function actionRejoin(): false|string
    {
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

    public function actionInitRoom($id)
    {
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
