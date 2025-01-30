<?php

namespace app\modules\user\models\search;


use Yii;
use yii\grid\ActionColumn;
use yii\helpers\Url;

class BanActionColumn extends ActionColumn
{
    public $template = '{revoke}';

    public function init()
    {
        parent::init();
        $this->urlCreator = function ($action, $model, $key, $index) {
            return match ($action) {
                'revoke' => Url::to(['/user/ban/revoke', 'id' => $key]),
                default => '#',
            };
        };
    }

    protected function initDefaultButtons(): void
    {
        $this->initDefaultButton('revoke', 'trash', [
            'data-confirm' => Yii::t('yii', 'Are you sure you want to revoke this ban?'),
        ]);
    }

    protected function renderDataCellContent($model, $key, $index)
    {
        return '<div class="d-flex justify-content-around">'
            . parent::renderDataCellContent($model, $key, $index)
            . '</div>';
    }
}
