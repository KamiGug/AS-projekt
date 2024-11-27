<?php

namespace app\models\generated;

use Yii;

/**
 * This is the model class for table "User".
 *
 * @property int $id
 * @property int|null $id_role
 * @property string $username
 * @property string|null $email
 * @property string $password
 * @property string $visible_name
 * @property string|null $modified_at
 * @property int|null $modified_by
 * @property string|null $created_at
 * @property int|null $created_by
 *
 * @property ChatParticipant[] $chatParticipants
 * @property ChatParticipant[] $chatParticipants0
 * @property Chat[] $chats
 * @property User $createdBy
 * @property Message[] $messages
 * @property User $modifiedBy
 * @property Role $role
 * @property Role[] $roles
 * @property Room[] $rooms
 * @property UserFlaggedMessage[] $userFlaggedMessages
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
            [['id_role', 'modified_by', 'created_by'], 'integer'],
            [['username', 'password', 'visible_name'], 'required'],
            [['modified_at', 'created_at'], 'safe'],
            [['username', 'email'], 'string', 'max' => 40],
            [['password'], 'string', 'max' => 150],
            [['visible_name'], 'string', 'max' => 30],
            [['username'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['id_role'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['id_role' => 'id']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['modified_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_role' => Yii::t('app', 'Id Role'),
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'visible_name' => Yii::t('app', 'Visible Name'),
            'modified_at' => Yii::t('app', 'Modified At'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * Gets query for [[ChatParticipants]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChatParticipants()
    {
        return $this->hasMany(ChatParticipant::class, ['id_chat' => 'id']);
    }

    /**
     * Gets query for [[ChatParticipants0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChatParticipants0()
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
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::class, ['id' => 'id_role']);
    }

    /**
     * Gets query for [[Roles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(Role::class, ['modified_by' => 'id']);
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
     * Gets query for [[UserFlaggedMessages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserFlaggedMessages()
    {
        return $this->hasMany(UserFlaggedMessage::class, ['id_user' => 'id']);
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
