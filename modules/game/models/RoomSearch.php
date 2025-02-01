<?php

namespace app\modules\game\models;

use app\models\DBDate;
use app\modules\user\models\User;
use yii\web\HttpException;

class RoomSearch extends Room
{
    const SORTABLE_VALUES = ['id', 'name', 'game_type', 'created_at'];
    const DEFAULT_SORT_VALUE = 'id ASC';
    public $userPlaying;
    public $sort;
    public $pageNumber;
    public $maxPageCount = null;
    public $itemCount;
    public $timestamp;

    public function rules()
    {
        return
            array_merge(
            parent::rules(),
            [
                [['sort'], 'string'],
                [['pageNumber', 'itemCount'], 'integer'],
                [['pageNumber', 'itemCount'], 'required'],
                [['timestamp'], 'date'],
                [['sort', 'pageNumber', 'itemCount', 'timestamp', 'game_type', 'userPlaying'], 'safe'],
                ['pageNumber', function ($attribute, $params, $validator) {
                        if (isset($params['maxPageCount']) === false) {
                            $this->maxPageCount = self::getPageCount($this->itemCount);
                        }
                        if ($this->$attribute < 1) {
                            $this->$attribute = 0;
                        } else {
                            if ($this->$attribute >= $this->maxPageCount) {
                                $this->$attribute = $this->maxPageCount - 1;
                            } else {
                                $this->$attribute--;
                            }
                        }
                    }
                ],
                ['sort', function ($attribute, $params, $validator) {
                    if ($this->$attribute === null) {
                        $this->$attribute = self::DEFAULT_SORT_VALUE;
                        return;
                    }
                    $matches = [];
                    $pattern = '/^([a-zA-Z_]+) (ASC|DESC)$/';
                    if (
                        !preg_match($pattern, $this->$attribute, $matches)
                        || !in_array($matches[1], self::SORTABLE_VALUES)
                    ) {
                        $this->$attribute = self::DEFAULT_SORT_VALUE;
                    }
                }],
            ]
        );
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => [
                'userPlaying',
                'game_type',
                'name',
                'pageNumber',
                'itemCount',
                'sort'
            ]
        ];
    }

//    public function attributeLabels()
//    {
//        return array_merge(
//            parent::rules(),
//            [
//            ]
//        );
//    }

    public function search()
    {
        if ($this->timestamp === null) {
            $this->timestamp = DBDate::getCurrentDate();
        }
        if ($this->maxPageCount === 0) {
            return [
                'rooms' => [],
                'timestamp' => $this->timestamp,
                'page' => 1,
            ];
        }

        $query = self::find()
            ->where(['<', 'created_at', $this->timestamp]);

        //filters
        if ($this->name != null) {
            $query->andWhere(['like', 'name', $this->name]);
        }

        if ($this->game_type != null) {
            $query->andWhere(['=', 'game_type', $this->game_type]);
        }

        if ($this->userPlaying != null) {
            $userQuery = User::find()->select('id')->where(['like', 'visible_name', $this->userPlaying]);
            $userRoomQuery = UserRoom::find()->select('id_room')
                ->where(['in', 'id_user', $userQuery])
                ->andWhere(['left_at' => null])
                ->andWhere(['<>', 'player_number', UserRoom::SPECTATOR_NUMBER]);
            $query = $query->andWhere(['in', 'id', $userRoomQuery]);
        }

        $query
            ->orderBy($this->sort)
            ->offset($this->pageNumber * $this->itemCount)
            ->limit($this->itemCount);

        $result = [];

        foreach ($query->all() as $room) {
            $result[$room->id] = [
                'id' => $room->id,
                'name' => $room->name,
                'gameType' => $room->game_type,
                'createdAt' => $room->created_at,
//                'createdBy' => $room->created_by,
//                'id_chat' => $room->id_chat,
            ];
        }

        $roomsPlayers = UserRoom::getActivePlayerNamesAndNumbersFromRooms(array_keys($result));

        foreach ($roomsPlayers as $roomId => $roomPlayers) {
            $result[$roomId]['players'] = $roomPlayers;
        }

        return [
            'rooms' => array_values($result),
            'pageCount' => $this->maxPageCount,
            'timestamp' => $this->timestamp,
            'page' => $this->pageNumber + 1,
        ];
    }

    public static function getPageCount($itemCountPerPage) : null|int
    {
        $itemCount = null;
        try {
            $itemCount = self::find()
                ->where(['finished_at' => null])
                ->count();
        } catch (\Throwable|\Exception) {
            $itemCount = null;
        }
        if ($itemCount === null) {
            throw new HttpException(500, 'The database is temporarily unresponsive.');
        }
        return $itemCount / $itemCountPerPage + ($itemCount % $itemCountPerPage === 0 ? 0 : 1);
    }
}
