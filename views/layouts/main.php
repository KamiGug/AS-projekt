<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\modules\user\models\Authentication\Role;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
$this->title = 'Kurnik-clone';
$isAdmin = Yii::$app->user->getIdentity()?->role === Role::ROLE_ADMINISTRATOR;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
        ]);
    ?>
    <div class="d-flex justify-content-between w-100">
        <div id="left-nav">
            <?= Nav::widget([
                'options' => ['class' => 'navbar-nav'],
                'items' => [
                    ['label' => $isAdmin
                        ? Yii::t('app', 'Users')
                        : Yii::t('app', 'Players'),
                        'url' => ['/user/management/profiles']],
                    ...($isAdmin
                    ? [
                            ['label' => Yii::t('app', 'Bans'), 'url' => ['/user/ban/list']],
                    ]
                    : []
                    )

                ]
            ]);
            ?>
        </div>

        <div id="right-nav">
            <?= Nav::widget([
                'options' => ['class' => 'navbar-nav'],
                'items' => [
                    ...(Yii::$app->user->isGuest
                        || Yii::$app->user->getIdentity()?->role === Role::ROLE_TEMPORARY_PLAYER
                        ? [
                            ['label' => 'Login', 'url' => ['/login']],
                            ['label' => 'Sign Up', 'url' => ['/signup']],
                        ]
                        : [
                            ['label' => Yii::$app->user->getIdentity()->visible_name, 'url' => ['/profile/' . Yii::$app->user->getIdentity()->getId()]],
                            ['label' => 'Logout', 'url' => ['/logout']],
                        ])
                ]
            ]);
            ?>
        </div>
    </div>

    <?php
        NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; My Company <?= date('Y') ?></div>
            <div class="col-md-6 text-center text-md-end"><a href="https://www.yiiframework.com/">Powered by Yii</a></div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
