<?php

use app\modules\user\models\User;
use yii\web\View;

/** @var View $this */
/** @var User $model */
/** @var bool $own */
/** @var bool $isAdmin */

?>
<div class="d-flex justify-content-between">
    <h2><?= $model->visible_name ?></h2>
    <?php if ($own || $isAdmin) : ?>
        <a href="/profile/edit/<?= $model->id ?>">
            <button class="btn btn-secondary"><?=Yii::t('app', 'edit profile') ?></button>
        </a>
    <?php endif; ?>
</div>
<hr>
<div class="container">
    <div class="row">
        <div class="col-sm-5 border-end">
            <?php if ($isAdmin) :?>
                <?= $this->render('profilePartials/adminSidebar', [
                        'model' => $model,
                ]) ?>
            <?php elseif ($own) : ?>
                <?= $this->render('profilePartials/selfSidebar', [
                    'model' => $model,
                ]) ?>
            <?php else : ?>
                <?= $this->render('profilePartials/otherSidebar', [
                    'model' => $model,
                ]) ?>
            <?php endif; ?>
        </div>
        <div class="col-sm-7">
            <p>PLACEHOLDER ADDITIONAL </p>
        </div>

    </div>

</div>



<?php //if (strtoupper(YII_ENV) === 'DEV') : ?>
<!--    <a href="/user/authentication/temp-player?id=4">the first</a>-->
<!--    <a href="/user/authentication/temp-player?id=5">the second</a>-->
<?php //endif; ?>
