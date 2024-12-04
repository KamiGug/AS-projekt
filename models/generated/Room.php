<?php

namespace app\models\generated;

use Yii;

/**
 * This is the model class for table "Room".
 *
 * @property int $id
 * @property int $id_game_type
 * @property int|null $id_chat
 * @property string $game_history
 * @property string|null $finished_at
 * @property string|null $current_gamestate
 * @property int|null $created_by
 * @property string|null $created_at
 *
 * @property Chat $chat
 * @property User $createdBy
 * @property GameType $gameType
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
            [['id_game_type', 'game_history'], 'required'],
            [['id_game_type', 'id_chat', 'created_by'], 'integer'],
            [['game_history', 'current_gamestate'], 'string'],
            [['finished_at', 'created_at'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['id_chat'], 'exist', 'skipOnError' => true, 'targetClass' => Chat::class, 'targetAttribute' => ['id_chat' => 'id']],
            [['id_game_type'], 'exist', 'skipOnError' => true, 'targetClass' => GameType::class, 'targetAttribute' => ['id_game_type' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_game_type' => 'Id Game Type',
            'id_chat' => 'Id Chat',
            'game_history' => 'Game History',
            'finished_at' => 'Finished At',
            'current_gamestate' => 'Current Gamestate',
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
     * Gets query for [[GameType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGameType()
    {
        return $this->hasOne(GameType::class, ['id' => 'id_game_type']);
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
