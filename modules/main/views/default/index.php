<?php
/** @var yii\web\View $this */

use app\modules\game\widgets\game\GameWidget;

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

<?php if (!Yii::$app->user->isGuest) : ?>
    <?= GameWidget::widget([]); ?>
<?php endif; ?>
