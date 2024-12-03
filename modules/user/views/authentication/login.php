
<?php

use app\modules\user\models\LoginForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var LoginForm $loginModel */

/** @var yii\web\View $this */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

?>

<?php
//todo: turn into a partial!
    $form = ActiveForm::begin([
            'action' => ['/user/authentication/auth-login'],
            'method' => 'POST',
            'id' => 'login-form',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                'inputOptions' => ['class' => 'col-lg-3 form-control'],
                'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
            ],

//            'enableClientValidation' => true,
        ]);
?>

<?= $form->field($loginModel, 'username')->textInput(['autofocus' => true]); ?>
<?= $form->field($loginModel, 'password')->passwordInput(); ?>
<?= $form->field($loginModel, 'rememberMe')->checkbox([
    'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
]) ?>

<?= Html::submitButton(Yii::t('app', 'login'), ['class' => 'btn btn-primary']); ?>
<?php ActiveForm::end(); ?>

