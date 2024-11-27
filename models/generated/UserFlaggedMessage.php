<?php

namespace app\models\generated;

use Yii;

/**
 * This is the model class for table "User_FlaggedMessage".
 *
 * @property int $id
 * @property int $id_flagged
 * @property int|null $id_user
 * @property string $comment
 * @property string|null $created_at
 *
 * @property FlaggedMessage $flagged
 * @property User $user
 */
class UserFlaggedMessage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'User_FlaggedMessage';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_flagged', 'comment'], 'required'],
            [['id_flagged', 'id_user'], 'integer'],
            [['comment'], 'string'],
            [['created_at'], 'safe'],
            [['id_flagged'], 'exist', 'skipOnError' => true, 'targetClass' => FlaggedMessage::class, 'targetAttribute' => ['id_flagged' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_flagged' => Yii::t('app', 'Id Flagged'),
            'id_user' => Yii::t('app', 'Id User'),
            'comment' => Yii::t('app', 'Comment'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * Gets query for [[Flagged]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFlagged()
    {
        return $this->hasOne(FlaggedMessage::class, ['id' => 'id_flagged']);
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
