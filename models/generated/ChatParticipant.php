<?php

namespace app\models\generated;

use Yii;

/**
 * This is the model class for table "ChatParticipant".
 *
 * @property int $id
 * @property int $id_user
 * @property int $id_chat
 * @property string|null $created_at
 *
 * @property Chat $chat
 * @property User $user
 */
class ChatParticipant extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ChatParticipant';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'id_chat'], 'required'],
            [['id_user', 'id_chat'], 'integer'],
            [['created_at'], 'safe'],
            [['id_chat'], 'exist', 'skipOnError' => true, 'targetClass' => Chat::class, 'targetAttribute' => ['id_chat' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'Id User',
            'id_chat' => 'Id Chat',
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }
}
