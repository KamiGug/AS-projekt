<?php

/** @var yii\web\View $this */

// Yii assets, Yii asset bundles

use app\modules\game\widgets\game\AssetBundle;

AssetBundle::register($this);

?>
<div class="d-flex justify-content-center align-content-center">
    <div
        id="game-wrapper"
        class="overlay"
    >
        <div class="loader"></div>
    </div>
</div>
