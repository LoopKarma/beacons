<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\models\Coupon */

$this->title = 'iBeaconManagement';
?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Статистика:</h1>
        <?php if (Yii::$app->user->can(\app\models\User::ROLE_ADMIN)) :?>
            <p class="lead">Сегодня сгенерировано купонов: <?=$model->getCouponCount(date('Y-m-d'))?></p>
        <?php else :?>
            <p class="lead">
                Сегодня у вас сгенерировано купонов:
                <?= $model->getCouponCount(date('Y-m-d'), false, Yii::$app->user->identity->merchant_id)?>
            </p>
            <p class="lead">
                Подтверждено купонов:
                <?= $model->getConfirmedCoupons(date('Y-m-d'), false, Yii::$app->user->identity->merchant_id)?>
            </p>
        <?php endif?>
        <?php if (!Yii::$app->user->can(\app\models\User::ROLE_SELLER)) : ?>
        <p><?= Html::a('Посмотреть последние купоны', ['/coupon/index'], ['class' => 'btn btn-lg btn-success'])?></p>
        <?php endif?>
    </div>
    <div class="body-content">
        <div class="row">
            <?php if (Yii::$app->user->can(\app\models\User::ROLE_ADMIN)) :?>
                <?= $this->render('_statisticaAdmin', [
                    'model' => $model,
                ]) ?>
            <?php elseif (Yii::$app->user->can(\app\models\User::ROLE_MERCHANT)) : ?>
                <?= $this->render('_statisticaMerchant', [
                    'model' => $model,
                ]) ?>
            <?php endif?>
        </div>
    </div>
</div>
