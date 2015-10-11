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
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'iBeacon management',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $items = [];
    if (!Yii::$app->user->isGuest) {
        if (Yii::$app->user->can(\app\models\User::ROLE_ADMIN)) {
            $items = ArrayHelper::merge($items, [
                ['label' => 'Пользователи', 'url' => ['/user/index']],
                ['label' => 'Шаблоны', 'url' => ['/template/index']],
                ['label' => 'Сгенерированные купоны', 'url' => ['/coupon/index']],
                ['label' => 'Мерчанты', 'url' => ['/merchant/index']],
                ['label' => 'Точки продаж', 'url' => ['/pos/index']],
            ]);
        } else {
            $items = ArrayHelper::merge($items, [
                ['label' => 'Ваши купоны', 'url' => ['/coupon/index']],
                ['label' => 'Валидация купона', 'url' => ['/coupon/validate']],
            ]);
        }
        $items = ArrayHelper::merge($items, [
            [
                'label' => 'Выйти [' . Yii::$app->user->identity->login . ']',
                'url' => ['/user/logout'],
                'linkOptions' => ['data-method' => 'post']
            ]
        ]);
    } else {
        $items = ArrayHelper::merge($items, [
            ['label' => 'Войти', 'url' => ['/user/login']]
        ]);
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
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

        <p class="pull-right">&copy; iBeacon management <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
