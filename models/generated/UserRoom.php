<?php

namespace app\models\generated;

use Yii;

/**
 * This is the model class for table "User_Room".
 *
 * @property int $id
 * @property int $id_user
 * @property int $id_room
 * @property string|null $created_at
 *
 * @property Room $room
 * @property User $user
 */
class UserRoom extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'User_Room';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'id_room'], 'required'],
            [['id_user', 'id_room'], 'integer'],
            [['created_at'], 'safe'],
            [['id_room'], 'exist', 'skipOnError' => true, 'targetClass' => Room::class, 'targetAttribute' => ['id_room' => 'id']],
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
            'id_user' => Yii::t('app', 'Id User'),
            'id_room' => Yii::t('app', 'Id Room'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * Gets query for [[Room]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoom()
    {
        return $this->hasOne(Room::class, ['id' => 'id_room']);
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