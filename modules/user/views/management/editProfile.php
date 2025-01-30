<?php


use app\modules\user\models\User;
use yii\web\View;

/** @var View $this */
/** @var User $model */

?>

<?= $this->render($model->scenario === User::SCENARIO_EDIT_ADMIN ? '__editProfileAdmin' : '__editProfileSelf', [
    'model' => $model,
]);

