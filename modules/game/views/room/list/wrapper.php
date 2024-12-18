<?php

use app\modules\game\models\RoomList;

/** @var yii\web\View $this */
/** @var string $type */

?>
<?php
switch ($type) {
    case (RoomList::TYPE_LIST):
        echo $this->render('_partials/_listWrapper-list');
        break;
    case (RoomList::TYPE_TILESET):
        echo $this->render('_partials/_listWrapper-tile');
        break;
}
