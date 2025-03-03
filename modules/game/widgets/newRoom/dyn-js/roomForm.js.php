<?php

use app\modules\game\models\GameTypes;
use app\modules\game\models\Room;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$model = new Room;
$model->scenario = Room::SCENARIO_CREATE;

?>
$(document).ready(() => {

const newRoomForm = () => {
return `
<div id="new-room-form-wrapper" class="floating-form-wrapper">
    <div id="new-room-form" class="floating-form">
        <h3>New Room</h3>
        <?php $form = ActiveForm::begin([
            'action' => ['/game/room/new'],
            'method' => 'post',
            'id' => 'new-room-form-actual'
        ]); ?>


        <?= $form->field($model, 'name')->textInput(['placeholder' => Yii::t('app', 'Enter a name for your room')]) ?>

        <?= $form->field($model, 'game_type')->dropDownList(
            GameTypes::getGameTypeNames(),
            ['prompt' => 'Select Game Type']
        ) ?>

        <div class="form-group">
            <?= Html::submitButton('Create', ['class' => 'btn btn-success', 'id' => 'new-room-form-btn']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
`
}


const viewNewRoomForm = () => {
$('body').append(newRoomForm());
}

const hideNewRoomForm = () => {
$('#new-room-form-wrapper').remove();
}

const handleAttributeErrors = (formAttribute, errors) => {
const formAttributeElement = $(`div.field-room-${formAttribute}`);
formAttributeElement.find('.error').remove();
if (errors != null && errors.length > 0) {
$(' <div class="error text-danger">' +
    errors[0] +
    '</div>').appendTo(formAttributeElement);

}
}

$(document).on('click', '#new-room-form', function(event) {
event.stopPropagation();
});

$(document).on('click', '#close-new-room-form', function(event) {
hideNewRoomForm();
event.stopPropagation();
});

$(document).on('click', '#new-room-form-wrapper', function(event) {
hideNewRoomForm();
event.stopPropagation();
});

$(document).on('click', '#new-room-btn', function(event) {
event.preventDefault();
viewNewRoomForm();
});

$(document).on('click', '#new-room-form-btn', function(event) {
event.preventDefault();
const formData = $('#new-room-form-actual').serialize();
$.ajax({
url: "/game/room/new",
type: "POST",
data: formData,
dataType: "json",
success: function(response) {
setTimeout(hideNewRoomForm, 100);
gameFunctions.joinGame(response.id)
},
error: function(response) {
for (var formAttribute of ['name', 'game_type']) {
handleAttributeErrors(formAttribute, response?.responseJSON?.errors[formAttribute]);
}
}
});
});
});
