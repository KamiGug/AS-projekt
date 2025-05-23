<?php
/** @var yii\web\View $this */

use app\modules\game\widgets\game\GameWidget;
use app\modules\game\widgets\newRoom\NewRoomWidget;
use app\modules\user\models\Authentication\Role;

?>

<?php if (
    !Yii::$app->user->isGuest
//    && Yii::$app->user->getIdentity()->role !== Role::ROLE_ADMINISTRATOR
) : ?>
    <?= GameWidget::widget([]); ?>
    <?= NewRoomWidget::widget(); ?>

<?php endif; ?>
