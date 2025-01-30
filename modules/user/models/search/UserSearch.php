<?php

namespace app\modules\user\models\search;

use app\models\DBDate;
use app\modules\user\models\Authentication\Role;
use app\modules\user\models\User;
use yii\data\ActiveDataProvider;

class UserSearch extends User
{
    public $created_at_start;
    public $created_at_end;
    public function rules()
    {
        return [
            [['visible_name', 'role', 'created_at_start', 'created_at_end', 'id'], 'safe'],
            [['created_at_start', 'created_at_end'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    public function scenarios()
    {
        return [self::SCENARIO_DEFAULT => ['visible_name','role', 'created_at_start', 'created_at_end', 'id']];
    }

    public function search($itemCount) : ActiveDataProvider
    {
        $query = self::find();
        if ($this->visible_name != null) {
            $query = $query->andWhere(['like', 'visible_name', $this->visible_name]);
        }

        if ($this->id != null) {
            $query = $query->andWhere(['=', 'id', (int) $this->id]);
        }

        if ($this->role != null && array_key_exists($this->role, Role::getRoles())) {
            $query = $query->andWhere(['=', 'role', $this->role]);
        }
        if ($this->created_at_start != null && DBDate::validateDate($this->created_at_start)) {
            $query = $query->andWhere(['>=', 'created_at', $this->created_at_start]);
        }
        if ($this->created_at_end != null && DBDate::validateDate($this->created_at_end)) {
            $query = $query->andWhere(['<=', 'created_at', $this->created_at_end]);
        }
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $itemCount,
            ]
        ]);
    }
}
