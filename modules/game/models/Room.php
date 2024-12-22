<?php

namespace app\modules\game\models;

use app\models\DBDate;
use \app\models\generated\Room as Base;
use Yii;
use yii\web\HttpException;
use function PHPUnit\Framework\throwException;

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

    /**
     * @throws HttpException
     */
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

    // $page - page number passed in the argument assumes pages numbered [1..N] (intuitive); N - pageCount
    // pages are numbered [0..N-1] (programmatically simpler); N - pageCount
    // timestamp is passed as to avoid refreshing room list on changing page
    // (like changing page and having only page number change on a rare occasion)
    public static function getRoomsPage($page, $itemCount, $timestamp = null, $sortOrder = null) : array|null
    {
        if ($timestamp === null) {
            $timestamp = DBDate::getCurrentDate();
        }
        $maxPageCount = self::getPageCount($itemCount);
        if ($maxPageCount === 0) {
            return [
                'rooms' => [],
                'timestamp' => $timestamp,
                'page' => 1,
            ];
        }
        if ($page < 1) {
            $page = 0;
        } else {
            if ($page >= $maxPageCount) {
                $page = $maxPageCount - 1;
            } else {
                $page--;
            }
        }

        $rooms = self::find()
            ->where(['finished_at' => null])
            ->where(['<', 'created_at', $timestamp])
            ->offset($page * $itemCount)
            ->limit($itemCount)
            ->all();
//            ->count();




        return [
            'rooms' => array_map(function ($item) {
                return $item->prepareForList();
            }, $rooms),
            'timestamp' => $timestamp,
            'page' => $page + 1,
            'pageCount' => $maxPageCount,
        ];
    }

    //todo: add some sort of $sortOrderToArgument

    public function prepareForList() : array
    {
        return [
            'id' => $this->id,
            'gameType' => $this->game_type,
            'createdAt' => $this->created_at,
            'createdBy' => $this->created_by,
        ];
    }
}
