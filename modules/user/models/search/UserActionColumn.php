<?php

namespace app\modules\user\models\search;

use yii\grid\ActionColumn;
use yii\helpers\Url;

class UserActionColumn extends ActionColumn
{
    public function init()
    {
        parent::init();

        $this->urlCreator = function ($action, $model, $key, $index) {
            if ($action === 'view') {
                return Url::to(['/profile/' . $key]);
            }
            return '#';
        };
    }

    protected function initDefaultButtons(): void
    {
        $this->initDefaultButton('view', 'eye-open');
    }

    protected function renderDataCellContent($model, $key, $index)
    {
        return '<div class="d-flex justify-content-around">'
            . parent::renderDataCellContent($model, $key, $index)
            . '</div>';
    }
}
