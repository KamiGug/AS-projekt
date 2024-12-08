<?php

use app\modules\user\models\Authentication\LoginForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var LoginForm $model */


$form = ActiveForm::begin([
    'action' => ['/user/authentication/login'],
    'method' => 'POST',
    'id' => 'login-form',
    'fieldConfig' => [
        'template' => "{label}\n{input}\n{error}",
        'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
        'inputOptions' => ['class' => 'col-lg-3 form-control'],
        'errorOptions' => ['class' => 'col-lg-7 text-danger'],
    ],

    'enableClientValidation' => true,
]);
?>

<?= $form->field($model, 'username')->textInput(['autofocus' => true]); ?>
<?= $form->field($model, 'password')->passwordInput(); ?>
<?= $form->field($model, 'rememberMe')->checkbox([
    'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
]) ?>

<?= Html::submitButton(Yii::t('app', 'login'), ['class' => 'btn btn-primary']); ?>
<?php ActiveForm::end(); ?>
