<?php

use app\modules\user\models\Authentication\BanType;
use app\modules\user\models\Ban\Ban;
use yii\helpers\Html;use yii\widgets\ActiveForm;

$model = new Ban;

?>

$(document).ready(() => {
    const banForm = (id) => {
        return `<div id="ban-form-wrapper" class="floating-form-wrapper">
    <div id="ban-form" class="floating-form">
        <h3>Ban User</h3>
        <?php $form = ActiveForm::begin([
            'action' => ['/user/ban/ban'],
            'method' => 'post',
        ]); ?>
            <?= $form->field($model, 'id_user')->hiddenInput([
                'value' => '${id}'
            ])->label(false)?>
            <?= $form->field($model, 'type')->dropDownList(
                BanType::getBanTypes(),
                ['prompt' => 'Select Ban Type']
            ) ?>
            <?= $form->field($model, 'until')->input('date') ?>
            <?= $form->field($model, 'reason')->textInput(
                [
                    'placeholder' => Yii::t('app', 'Reason')
                ]
            ) ?>
            <div class="form-group">
                <?= Html::submitButton('Create', ['class' => 'btn btn-success']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>`
    }

    const viewBanForm = (id) => {
        $('body').append(banForm(id));
    }

    const hideBanForm = () => {
        $('#ban-form-wrapper').remove();
    }

    $(document).on('click', '#ban-form', function(event) {
        event.stopPropagation();
    });

    $(document).on('click', '#close-ban-form', function() {
        hideBanForm();
        event.stopPropagation();
    });

    $(document).on('click', '#ban-form-wrapper', function() {
        hideBanForm();
        event.stopPropagation();
    });

    $(document).on('click', 'a.ban-button', function(event) {
        event.preventDefault();
        const params = new URL(event.currentTarget.href).searchParams;
        const id = params.get("id");
        console.log('Opening Ban Form for ID:', id);
        viewBanForm(id);
    });

    $(document).on('submit', '#ajax-ban-form', function(event) {
        event.preventDefault();
        const formData = $(this).serialize();
        $.ajax({
            url: "/user/ban/ban",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                setTimeout(hideBanForm, 2000);
            },
            error: function() {
            }
        });
    });
});
