<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\ArrayHelper;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('/files/logo_white.png', ['class' => 'nav-logo']),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $items = [];
    if (!Yii::$app->user->isGuest) {
        if (Yii::$app->user->can(\app\models\User::ROLE_ADMIN)) {
            $items = ArrayHelper::merge($items, [
                ['label' => '<i class="glyphicon glyphicon-user"></i> Пользователи', 'url' => ['/user/index']],
                ['label' => '<i class="glyphicon glyphicon-modal-window"></i> Шаблоны', 'url' => ['/template/index']],
                ['label' => '<i class="glyphicon glyphicon-tag"></i> Купоны', 'url' => ['/coupon/index']],
                ['label' => '<i class="glyphicon glyphicon-barcode"></i> Сообщения', 'url' => ['/barcode/index']],
                ['label' => '<i class=" glyphicon glyphicon-briefcase"></i> Мерчанты', 'url' => ['/merchant/index']],
                ['label' => '<i class=" glyphicon glyphicon-home"></i> Точки продаж', 'url' => ['/pos/index']],
            ]);
        } elseif (Yii::$app->user->can(\app\models\User::ROLE_MERCHANT)) {
            $items = ArrayHelper::merge($items, [
                [
                    'label' => '<i class="glyphicon glyphicon-tag"></i> Статистика',
                    'url' => ['/coupon/index']
                ],
                [
                    'label' => '<i class="glyphicon glyphicon-ok-circle"></i> Валидация купона',
                    'url' => ['/coupon/validate']
                ],
            ]);
        } else {
            $items = ArrayHelper::merge($items, [
                [
                    'label' => '<i class="glyphicon glyphicon-ok-circle"></i> Валидация купона',
                    'url' => ['/coupon/validate']
                ],
            ]);
        }
        $items = ArrayHelper::merge($items, [
            [
                'label' => '<i class=" glyphicon glyphicon-off"></i> Выйти [' . Yii::$app->user->identity->login . ']',
                'url' => ['/user/logout'],
                'linkOptions' => ['data-method' => 'post']
            ]
        ]);
    } else {
        $items = ArrayHelper::merge($items, [
            ['label' => '<i class=" glyphicon glyphicon-lock"></i> Войти', 'url' => ['/user/login']]
        ]);
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => $items,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?php foreach (Yii::$app->session->getAllFlashes() as $type => $flash) : ?>
            <div class="alert alert-<?= $type ?>"><?= $flash ?></div>
        <?php endforeach ?>

        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left"></p>

        <p class="pull-right">&copy; Найди Купон <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
