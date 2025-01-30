<?php

use app\modules\user\models\Ban\Ban;
use yii\web\View;

/** @var View $this */
/** @var Ban $ban */
/** @var string|null $issuer */

?>
<h1>You have been banned!</h1>
<p>You are banned until <?= $ban->until?>. This ban has started at <?= $ban->since?>.</p>
<?php if ($issuer !== null) : ?>
    <p>You have been banned by <?= $issuer?></p>
<?php endif; ?>
<?php if ($ban->reason != null && strlen($ban->reason)) : ?>
    <p>You have been banned due to: <?= $ban->reason ?></p>
<?php endif; ?>
