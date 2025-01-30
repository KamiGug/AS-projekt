<?php

namespace app\modules\user\models\search;


use Yii;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;

class AdminActionColumn extends ActionColumn
{
    public $template = '{view} {update} {ban}';

    public function init()
    {
        parent::init();
        $this->icons['ban'] = '<svg viewBox="0 0 14 14" aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:.875em" xmlns="http://www.w3.org/2000/svg"  fill="currentColor" class="bi bi-person-fill-lock"><path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h5v-1a2 2 0 0 1 .01-.2 4.49 4.49 0 0 1 1.534-3.693Q8.844 9.002 8 9c-5 0-6 3-6 4m7 0a1 1 0 0 1 1-1v-1a2 2 0 1 1 4 0v1a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1zm3-3a1 1 0 0 0-1 1v1h2v-1a1 1 0 0 0-1-1"/></svg>';

        $this->urlCreator = function ($action, $model, $key, $index) {
            return match ($action) {
                'view' => Url::to(['/profile/' . $key]),
                'update' => Url::to(['/profile/edit/' . $key]),
                'ban' => Url::to(['/user/ban/ban', 'id' => $key]),
                default => '#',
            };
        };
    }

    protected function initDefaultButtons(): void
    {
        $this->initDefaultButton('view', 'eye-open');
        $this->initDefaultButton('update', 'pencil');
        $this->initDefaultButton('ban', 'ban', [
            'class' => 'ban-button',
        ]);
    }

    protected function renderDataCellContent($model, $key, $index)
    {
        return '<div class="d-flex justify-content-around">'
            . parent::renderDataCellContent($model, $key, $index)
            . '</div>';
    }
}
