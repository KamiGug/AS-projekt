<?php

namespace app\models\generated;

use Yii;

/**
 * This is the model class for table "Role".
 *
 * @property int $id
 * @property string $name
 * @property string|null $deactivated_at
 * @property string|null $created_at
 * @property string|null $modified_at
 * @property int|null $modified_by
 *
 * @property User $modifiedBy
 * @property User[] $users
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['deactivated_at', 'created_at', 'modified_at'], 'safe'],
            [['modified_by'], 'integer'],
            [['name'], 'string', 'max' => 20],
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
            'name' => Yii::t('app', 'Name'),
            'deactivated_at' => Yii::t('app', 'Deactivated At'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
            'modified_by' => Yii::t('app', 'Modified By'),
        ];
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
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id_role' => 'id']);
    }
}
