<?php

namespace app\modules\user\widgets\ban;

use yii\base\Widget;

class BanWidget extends Widget
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
