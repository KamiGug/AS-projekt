<?php

use app\modules\user\models\User;
use yii\web\View;

/** @var View $this */
/** @var User $model */

$attributesToView = [
    'visible_name',
    'created_at',
]
?>

<div>
    <?php foreach ($attributesToView as $attribute): ?>
        <?php if (isset($model[$attribute]) === false): ?>
            <?php continue; ?>
        <?php endif; ?>
        <div class="row">
            <label
                    for="profile-<?= $attribute ?>"
                    class="col-md-4 col-12"
            >
                <?= $model->getAttributeLabel($attribute) ?>
            </label>
            <div
                    id="profile-<?= $attribute ?>"
                    class="col-md-8 col-12"
            >
                <?= $model->$attribute ?>
            </div>
        </div>
        <hr>

    <?php endforeach; ?>
</div>
