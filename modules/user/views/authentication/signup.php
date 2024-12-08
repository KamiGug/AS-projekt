<?php

use app\modules\user\models\User;

/** @var yii\web\View $this */
/** @var User $model */



$this->title = 'Register';
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('partials/_signup', [
    'model' => $model
]);
