<?php


use app\modules\user\models\Authentication\Role;
use app\modules\user\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var User $model */

?>

<h1><?= Yii::t('app', Yii::$app->user->getId() === $model->id ? 'Edit your profile' : 'Edit profile as Admin'); ?></h1>

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
?>

<?= $form->field($model, 'visible_name')->textInput(); ?>
<?= $form->field($model, 'username')->textInput(['autofocus' => true]); ?>
<?= $form->field($model, 'email')->textInput(); ?>
<?= $form->field($model, 'role')->dropDownList(Role::getRoles()); ?>

<?= Html::submitButton(Yii::t('app', 'save'), ['class' => 'btn btn-primary']); ?>
<?php ActiveForm::end(); ?>
