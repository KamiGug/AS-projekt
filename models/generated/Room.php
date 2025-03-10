<?php

namespace app\models\generated;

use Yii;

/**
 * This is the model class for table "Room".
 *
 * @property int $id
 * @property string $name
 * @property string $game_type
 * @property int|null $id_chat
 * @property int $current_player_number
 * @property string $game_history
 * @property string|null $finished_at
 * @property string|null $current_gamestate
 * @property int $seed
 * @property string|null $modified_at
 * @property int|null $created_by
 * @property string|null $created_at
 *
 * @property Chat $chat
 * @property User $createdBy
 * @property UserRoom[] $userRooms
 */
class Room extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Room';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'game_type', 'current_player_number', 'game_history', 'seed'], 'required'],
            [['id_chat', 'current_player_number', 'seed', 'created_by'], 'integer'],
            [['game_history', 'current_gamestate'], 'string'],
            [['finished_at', 'modified_at', 'created_at'], 'safe'],
            [['name'], 'string', 'max' => 16],
            [['game_type'], 'string', 'max' => 40],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['id_chat'], 'exist', 'skipOnError' => true, 'targetClass' => Chat::class, 'targetAttribute' => ['id_chat' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'game_type' => 'Game Type',
            'id_chat' => 'Id Chat',
            'current_player_number' => 'Current Player Number',
            'game_history' => 'Game History',
            'finished_at' => 'Finished At',
            'current_gamestate' => 'Current Gamestate',
            'seed' => 'Seed',
            'modified_at' => 'Modified At',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Chat]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChat()
    {
        return $this->hasOne(Chat::class, ['id' => 'id_chat']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[UserRooms]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserRooms()
    {
        return $this->hasMany(UserRoom::class, ['id_room' => 'id']);
    }
}
