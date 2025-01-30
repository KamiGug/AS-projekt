<?php


use app\modules\user\models\Authentication\Role;
use app\modules\user\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var User $model */

?>

<h1><?= Yii::t('app', 'Edit profile'); ?></h1>

<?php
$form = ActiveForm::begin([
    'action' => ["/profile/edit/$model->id"],
    'method' => 'POST',
    'id' => 'edit-profile-form',
    'fieldConfig' => [
        'template' => "{label}\n{input}\n{error}",
        'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
        'inputOptions' => ['class' => 'col-lg-3 form-control'],
        'errorOptions' => ['class' => 'col-lg-7 text-danger'],
    ],
    'enableClientValidation' => true,
]);

$isAdmin = Yii::$app->user->getIdentity()?->role === Role::ROLE_ADMINISTRATOR
?>

<?= $form->field($model, 'visible_name')->textInput(['autofocus' => true]); ?>
<?= $form->field($model, 'username')->textInput(); ?>
<?= $form->field($model, 'password')->passwordInput(); ?>
<?= $form->field($model, 'confirmPassword')->passwordInput(); ?>
<?= $form->field($model, 'email')->textInput(); ?>

<?php if ($isAdmin) : ?>
    <?= $form->field($model, 'role')->dropDownList(Role::getRoles()); ?>
<?php else: ?>
    <?= $form->field($model, 'oldPassword')->passwordInput(); ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('app', 'save'), ['class' => 'btn btn-primary']); ?>
<?php ActiveForm::end(); ?>
