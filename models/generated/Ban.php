<?php

namespace app\models\generated;

use Yii;

/**
 * This is the model class for table "Ban".
 *
 * @property int $id
 * @property int $id_user
 * @property string $type
 * @property string $since
 * @property string $until
 * @property string|null $reason
 * @property int|null $issued_by
 *
 * @property User $issuedBy
 * @property User $user
 */
class Ban extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Ban';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'type', 'since', 'until'], 'required'],
            [['id_user', 'issued_by'], 'integer'],
            [['since', 'until'], 'safe'],
            [['type'], 'string', 'max' => 15],
            [['reason'], 'string', 'max' => 50],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_user' => 'id']],
            [['issued_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['issued_by' => 'id']],
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
            'type' => 'Type',
            'since' => 'Since',
            'until' => 'Until',
            'reason' => 'Reason',
            'issued_by' => 'Issued By',
        ];
    }

    /**
     * Gets query for [[IssuedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIssuedBy()
    {
        return $this->hasOne(User::class, ['id' => 'issued_by']);
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
