<?php

namespace app\modules\game\widgets\newRoom;

use yii\base\Widget;

class NewRoomWidget extends Widget
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
