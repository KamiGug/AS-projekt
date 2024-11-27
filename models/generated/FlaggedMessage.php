<?php

namespace app\models\generated;

use Yii;

/**
 * This is the model class for table "FlaggedMessage".
 *
 * @property int $id
 * @property int|null $id_message
 * @property string|null $created_at
 *
 * @property Message $message
 * @property UserFlaggedMessage[] $userFlaggedMessages
 */
class FlaggedMessage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'FlaggedMessage';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_message'], 'integer'],
            [['created_at'], 'safe'],
            [['id_message'], 'exist', 'skipOnError' => true, 'targetClass' => Message::class, 'targetAttribute' => ['id_message' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_message' => Yii::t('app', 'Id Message'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * Gets query for [[Message]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessage()
    {
        return $this->hasOne(Message::class, ['id' => 'id_message']);
    }

    /**
     * Gets query for [[UserFlaggedMessages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserFlaggedMessages()
    {
        return $this->hasMany(UserFlaggedMessage::class, ['id_flagged' => 'id']);
    }
}
