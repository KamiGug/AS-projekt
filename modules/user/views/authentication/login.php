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




<?php if (strtoupper(YII_ENV) === 'DEV') : ?>
    <a href="/user/authentication/temp-player?id=4">the first</a>
    <a href="/user/authentication/temp-player?id=5">the second</a>
<?php endif; ?>
