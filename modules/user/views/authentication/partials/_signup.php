<?php

use app\modules\user\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var User $model */

$form = ActiveForm::begin([
    'action' => ['/user/authentication/signup'],
    'method' => 'POST',
    'id' => 'signup-form',
    'fieldConfig' => [
        'template' => "{label}\n{input}\n{error}",
        'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
        'inputOptions' => ['class' => 'col-lg-3 form-control'],
        'errorOptions' => ['class' => 'col-lg-7 text-danger'],
    ],
    'enableClientValidation' => false,
]);
?>

<?= $form->field($model, 'username')->textInput(['autofocus' => true]); ?>
<?= $form->field($model, 'email')->textInput(); ?>
<?= $form->field($model, 'visible_name')->textInput(); ?>
<?= $form->field($model, 'password')->passwordInput(); ?>
<?= $form->field($model, 'confirmPassword')->passwordInput(); ?>

<?= Html::submitButton(Yii::t('app', 'sign up'), ['class' => 'btn btn-primary']); ?>
<?php ActiveForm::end(); ?>
