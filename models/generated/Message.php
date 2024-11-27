<?php

namespace app\models\generated;

use Yii;

/**
 * This is the model class for table "Message".
 *
 * @property int $id
 * @property int|null $id_chat
 * @property int|null $created_by
 * @property string|null $created_at
 * @property string $contents
 *
 * @property Chat $chat
 * @property User $createdBy
 * @property FlaggedMessage[] $flaggedMessages
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_chat', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['contents'], 'required'],
            [['contents'], 'string'],
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
            'id' => Yii::t('app', 'ID'),
            'id_chat' => Yii::t('app', 'Id Chat'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'contents' => Yii::t('app', 'Contents'),
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
     * Gets query for [[FlaggedMessages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFlaggedMessages()
    {
        return $this->hasMany(FlaggedMessage::class, ['id_message' => 'id']);
    }
}
