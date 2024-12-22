<?php
/** @var yii\web\View $this */

use yii\helpers\Html;


$path = Yii::$app->basePath . '/web/web-assets/game/ludo/js';
foreach (array_diff(scandir($path), array('.', '..')) as $jsFile) {
    echo HTML::jsFile('/web-assets/game/ludo/js/' . $jsFile);
}
$path = Yii::$app->basePath . '/web/web-assets/game/ludo/css';
foreach (array_diff(scandir($path), array('.', '..')) as $cssFile) {
    echo HTML::cssFile('/web-assets/game/ludo/js/' . $cssFile);
}

?>
<div id="game-board">
    ludo board game
</div>
