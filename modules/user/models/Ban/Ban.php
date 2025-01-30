<?php

namespace app\modules\user\models\Ban;

use app\models\DBDate;
use app\modules\user\models\Authentication\BanType;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

class Ban extends \app\models\generated\Ban
{
    const SCENARIO_ADD = 'add';

    public function init()
    {
        parent::init();
        if ($this->isNewRecord) {
            $this->since = date('Y-m-d'); // Set default date
        }
    }

    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                ['type', 'in', 'range' => array_keys(BanType::getBanTypes())],
                [
                    'until',
                    'compare',
                    'compareAttribute' => 'since',
                    'operator' => '>',
                    'message' => 'The date of ban end must be later than the date of it\'s start.'
                ],
                [['since', 'until'], 'date', 'format' => 'php:Y-m-d'],
            ]
        );
    }

    public function scenarios()
    {
        return array_merge(
            parent::scenarios(),
            [
                self::SCENARIO_ADD => [ 'id', 'id_user', 'type', 'since', 'until', 'issued_by' ],
            ]
        );
    }

    public function attributeLabels()
    {
        return array_merge(
            parent::rules(),
            [
                'type' => Yii::t('app', 'Type of the ban'),
                'since' => Yii::t('app', 'Date of ban start'),
                'until' => Yii::t('app', 'Date of ban end'),
                'issued_by' => Yii::t('app', 'Id of the ban issuer'),
            ]
        );
    }

    public function beforeValidate(): bool
    {
        if (!empty($this->id_user)) {
            $this->id_user = (int) $this->id_user;
        }
        return parent::beforeValidate();
    }

    public function beforeSave($insert) : bool
    {
        if (parent::beforeSave($insert) === false) {
            return false;
        }
        $this->issued_by = Yii::$app->user->getId();
        return true;
    }

    public static function isUserCurrentlyBanned(int $userId, null|string $type = null): bool
    {
        $query = self::find()
            ->where(['id_user' => $userId])
            ->andWhere(['>=', 'until', new Expression('CURRENT_TIMESTAMP')]);

        if ($type !== null) {
            $query->andWhere(['type' => $type]);
        }

        return (bool) $query->count();
    }

    public static function getLastActiveBan(int|null $userId, null|string $type = null) : null|ActiveRecord
    {
        if ($userId === null) {
            return null;
        }
        $query = self::find()
            ->where(['id_user' => $userId])
            ->andWhere(['>=', 'until', new Expression('CURRENT_TIMESTAMP')]);

        if ($type !== null) {
            $query->andWhere(['type' => $type]);
        }
        $query->orderBy(['until' => SORT_DESC]);
        return $query->one();
    }

    public static function sanitizeCharacters($value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
