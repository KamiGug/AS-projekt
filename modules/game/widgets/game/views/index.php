<?php

/** @var yii\web\View $this */

// Yii assets, Yii asset bundles

use app\modules\game\widgets\game\AssetBundle;

AssetBundle::register($this);

$height = '60vh';
$width = '60vw';

?>
<div class="d-flex justify-content-center align-content-center">
    <div
        id="game-wrapper"
        style="
            height: <?= $height ?>;
            width: <?= $width ?>;
            border: black 1px solid;
    "
        class="overlay"
    >
        <div class="loader"></div>
    </div>
</div>
