<?php

namespace app\models\generated;

use Yii;

/**
 * This is the model class for table "User".
 *
 * @property int $id
 * @property string|null $username
 * @property string|null $email
 * @property string|null $password
 * @property string|null $visible_name
 * @property string $role
 * @property string|null $modified_at
 * @property int|null $modified_by
 * @property string|null $created_at
 * @property int|null $created_by
 *
 * @property ChatParticipant[] $chatParticipants
 * @property Chat[] $chats
 * @property User $createdBy
 * @property FlaggedMessage[] $flaggedMessages
 * @property Message[] $messages
 * @property User $modifiedBy
 * @property Room[] $rooms
 * @property UserRoom[] $userRooms
 * @property User[] $users
 * @property User[] $users0
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'User';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role'], 'required'],
            [['modified_at', 'created_at'], 'safe'],
            [['modified_by', 'created_by'], 'integer'],
            [['username', 'email', 'role'], 'string', 'max' => 40],
            [['password'], 'string', 'max' => 150],
            [['visible_name'], 'string', 'max' => 30],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['visible_name'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['modified_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'visible_name' => 'Visible Name',
            'role' => 'Role',
            'modified_at' => 'Modified At',
            'modified_by' => 'Modified By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * Gets query for [[ChatParticipants]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChatParticipants()
    {
        return $this->hasMany(ChatParticipant::class, ['id_user' => 'id']);
    }

    /**
     * Gets query for [[Chats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChats()
    {
        return $this->hasMany(Chat::class, ['created_by' => 'id']);
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
     * Gets query for [[FlaggedMessages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFlaggedMessages()
    {
        return $this->hasMany(FlaggedMessage::class, ['flagged_by' => 'id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[ModifiedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModifiedBy()
    {
        return $this->hasOne(User::class, ['id' => 'modified_by']);
    }

    /**
     * Gets query for [[Rooms]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRooms()
    {
        return $this->hasMany(Room::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[UserRooms]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserRooms()
    {
        return $this->hasMany(UserRoom::class, ['id_user' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Users0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers0()
    {
        return $this->hasMany(User::class, ['modified_by' => 'id']);
    }
}
