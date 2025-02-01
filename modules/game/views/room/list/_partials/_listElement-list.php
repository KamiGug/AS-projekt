<?php

/** @var yii\web\View $this */
?>
<div class="room-list-element-wrapper" >
    <div class="room-list-element list row" data-id="${id}">
        <div class="list-item-name col-md-5">
            <strong>${name}</strong>
        </div>

        <div class="list-item-gameType col-md-4">
            ${gameType}
        </div>

        <div class="list-item-createdAt col-md-3">
            ${createdAt}
        </div>
    </div>
</div>
