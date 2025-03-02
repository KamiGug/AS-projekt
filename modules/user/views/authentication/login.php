<?php

use app\modules\user\models\Authentication\LoginForm;


/** @var yii\web\View $this */
/** @var LoginForm $model */


$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('partials/_login', [
    'model' => $model
]); ?>
