<?php

namespace app\modules\game\models;

use Yii;
use yii\db\ActiveRecord;

class UserRoom extends \app\models\generated\UserRoom
{
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
}
