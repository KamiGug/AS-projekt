<?php

/** @var yii\web\View $this */
?>
<div class="room-list-element-wrapper" >
    <div class="room-list-element list row" data-id="${id}">
        <div class="list-item-name col-md-3">
            <strong>${name}</strong>
        </div>

        <div class="list-item-gameType col-md-1">
            ${gameType}
        </div>

        <div class="list-item-players col-md-5">
            <div class="row">
                <div class="col-md-6 blue-player-element player-element">${player1}</div>
                <div class="col-md-6 red-player-element player-element">${player2}</div>
            </div>
            <div class="row">
                <div class="col-md-6 yellow-player-element player-element">${player4}</div>
                <div class="col-md-6 green-player-element player-element">${player3}</div>
            </div>
        </div>

        <div class="list-item-createdAt col-md-2">
            ${createdAt}
        </div>
    </div>
</div>
