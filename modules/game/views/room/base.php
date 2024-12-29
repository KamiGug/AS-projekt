<?php
/** @var yii\web\View $this */

use yii\helpers\Html;


$path = Yii::$app->basePath . '/web/web-assets/game/base/js';
foreach (array_diff(scandir($path), array('.', '..')) as $jsFile) {
    echo HTML::jsFile('/web-assets/game/base/js/' . $jsFile);
}
$path = Yii::$app->basePath . '/web/web-assets/game/base/css';
foreach (array_diff(scandir($path), array('.', '..')) as $cssFile) {
    echo HTML::cssFile('/web-assets/game/base/js/' . $cssFile);
}

throw new \app\models\exceptions\NoSuchGameTypeException();
?>
<div id="game-board">
    base board game
</div>


