<?php

namespace app\modules\user\models\search;

use app\modules\user\models\Authentication\BanType;
use app\modules\user\models\Ban\Ban;
use app\modules\user\models\User;
use yii\data\ActiveDataProvider;

class BanSearch extends Ban
{
    public $bannedNickname;
    public $endedBefore;
    public $startedAfter;
    public $issuedByNickname;
    public $searchedType;
    public $banned_visible_name;
    public $issued_by_visible_name;

    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['activeBans'], 'boolean'],
                [['bannedNickname', 'issuedByNickname', 'searchedType'], 'string'],
                [['startedAfter', 'endedBefore'], 'date', 'format' => 'php:Y-m-d'],
                [['bannedNickname', 'endedBefore', 'startedAfter', 'searchedType', 'issuedByNickname'], 'safe'],
            ]
        );
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => [
                'bannedNickname',
                'issuedByNickname',
                'endedBefore',
                'startedAfter',
                'searchedType',
                'banned_visible_name',
                'issued_by_visible_name',
            ],
        ];
    }

    public function search($itemCount) : ActiveDataProvider
    {
        $query = self::find()
            ->joinWith([
                'user AS bannedUser',
                'issuedBy AS issuerUser'
            ])
            ->select([
                'Ban.*',
                'bannedUser.visible_name AS banned_visible_name',
                'issuerUser.visible_name AS issued_by_visible_name'
            ]);

        if ($this->bannedNickname != null) {
            $query->andWhere(['in', 'id_user', User::getIdsByVisibleNameLike($this->bannedNickname)]);
        }

        if ($this->issuedByNickname != null) {
            $query->andWhere(['in', 'issued_by', User::getIdsByVisibleNameLike(self::sanitizeCharacters($this->issuedByNickname))]);
        }

        if ($this->searchedType != null && array_key_exists($this->searchedType, BanType::getBanTypes())) {
            $query = $query->andWhere(['=', 'type', $this->searchedType]);
        }

        if ($this->endedBefore != null) {
            $query->andWhere(['<=', 'until', $this->endedBefore]);
        }

        if ($this->startedAfter != null) {
            $query->andWhere(['>=', 'since', $this->startedAfter]);
        }

        $sort = [
            'defaultOrder' => [
                'id' => SORT_DESC,
            ],
            'attributes' => [
                'id' => [
                    'asc' => ['id' => SORT_ASC],
                    'desc' => ['id' => SORT_DESC],
                ],
                'banned_visible_name' => [
                    'asc' => ['bannedUser.visible_name' => SORT_ASC],
                    'desc' => ['bannedUser.visible_name' => SORT_DESC],
                ],
                'issued_by_visible_name' => [
                    'asc' => ['issuerUser.visible_name' => SORT_ASC],
                    'desc' => ['issuerUser.visible_name' => SORT_DESC],
                ],
                'since' => [
                    'asc' => ['since' => SORT_ASC],
                    'desc' => ['since' => SORT_DESC],
                ],
                'until' => [
                    'asc' => ['until' => SORT_ASC],
                    'desc' => ['until' => SORT_DESC],
                ],
                'type' => [
                    'asc' => ['type' => SORT_ASC],
                    'desc' => ['type' => SORT_DESC],
                ],
            ],
        ];

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $itemCount,
            ],
            'sort' => $sort,
        ]);
    }
}
