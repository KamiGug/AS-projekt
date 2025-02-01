<?php

/** @var yii\web\View $this */

use app\modules\game\models\GameTypes;
use app\modules\game\models\RoomSearch;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$model = new RoomSearch;

?>
<div id="room-list-bar">
    <?php $form = ActiveForm::begin([
        'id' => 'room-search-form',
//        'action' => ['/game/room/list'],
        'method' => 'post',
        'options' => ['onsubmit' => 'return false;'],
    ]); ?>
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'name')->textInput(['placeholder' => 'Room Name'])->label(false) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'game_type')->dropDownList(
                GameTypes::getGameTypeNames(),
                ['prompt' => 'Select Game Type']
            )->label(false) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'userPlaying')->textInput(['placeholder' => 'Player Name'])->label(false) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'sort')->dropDownList([
                'name ASC' => Yii::t('app', 'Name (A-Z)'),
                'name DESC' => Yii::t('app', 'Name (Z-A)'),
                'game_type ASC' => Yii::t('app', 'Game Type (A-Z)'),
                'game_type DESC' => Yii::t('app', 'Game Type (Z-A)'),
                'created_at ASC' => Yii::t('app', 'Created Date (Oldest First)'),
                'created_at DESC' => Yii::t('app', 'Created Date (Newest First)'),
            ], ['prompt' => 'Sort By'])->label(false) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'itemCount')->dropDownList([
                '5' => 5,
                '10' => 10,
                '20' => 20,
                '50' => 50,
            ], ['prompt' => Yii::t('app', 'Items per page')])->label(false) ?>
        </div>

        <div class="form-group col-md-2 d-flex justify-content-around">
            <?= Html::submitButton('<img style="height: 16px; width: 16px;" src="/icons/magnifying-glass-white.svg" alt="search">', ['class' => 'btn btn-primary', 'id' => 'room-search-btn']) ?>
            <?= Html::button('<img style="height: 16px; width: 16px;" src="/icons/refresh-white.svg" alt="refresh">', ['class' => 'btn btn-primary', 'id' => 'room-search-refresh-btn']) ?>
        </div>

    </div>

    <?php ActiveForm::end(); ?>
</div>
