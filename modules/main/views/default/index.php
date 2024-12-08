<?php
/** @var yii\web\View $this */

?>
<h1>Hello world!</h1>
<?php
if (Yii::$app->user->getIdentity() !== NULL) {
    echo Yii::$app->user->getIdentity()->visible_name;
} else {
    echo 'guest';
}
?>
<br>
<?= Yii::$app->user->isGuest ? 'guest' : 'authed'; ?>

<!--<p>--><?php //= Yii::$app->user ? Yii::$app->user : 'guest' ?><!--</p>-->
