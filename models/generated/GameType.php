<?php

namespace app\models\generated;

use Yii;

/**
 * This is the model class for table "GameType".
 *
 * @property int $id
 * @property string $name
 * @property int $max_players
 *
 * @property Room[] $rooms
 */
class GameType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'GameType';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'max_players'], 'required'],
            [['max_players'], 'integer'],
            [['name'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'max_players' => Yii::t('app', 'Max Players'),
        ];
    }

    /**
     * Gets query for [[Rooms]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRooms()
    {
        return $this->hasMany(Room::class, ['id_game_type' => 'id']);
    }
}
