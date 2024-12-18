<?php

namespace app\modules\game\widgets\game;

use yii\base\Widget;

class GameWidget extends Widget
{
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('index');
    }
}
