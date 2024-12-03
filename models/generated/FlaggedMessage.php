<?php

namespace app\models\generated;

use Yii;

/**
 * This is the model class for table "FlaggedMessage".
 *
 * @property int $id
 * @property int|null $id_message
 * @property int|null $flagged_by
 * @property string $comment
 * @property string|null $created_at
 *
 * @property User $flaggedBy
 * @property Message $message
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
            [['id_message', 'flagged_by'], 'integer'],
            [['comment'], 'required'],
            [['comment'], 'string'],
            [['created_at'], 'safe'],
            [['flagged_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['flagged_by' => 'id']],
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
            'flagged_by' => Yii::t('app', 'Flagged By'),
            'comment' => Yii::t('app', 'Comment'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * Gets query for [[FlaggedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFlaggedBy()
    {
        return $this->hasOne(User::class, ['id' => 'flagged_by']);
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
}
