<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Coupon */

$this->title = 'Купон [' . $model->coupon_id . ']';
$this->params['breadcrumbs'][] = ['label' => 'Купоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$isMerchant = isset(Yii::$app->user->identity->merchant_id);
?>
<div class="coupon-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'coupon_id',
            'create_date',
            'serial_number',
            [
                'attribute' => 'merchant_id',
                'value' => "{$model->merchant->name} [{$model->merchant->merchant_id}]",
                'visible' => !$isMerchant
            ],
            [
                'attribute' => 'uuid',
                'visible' => !$isMerchant
            ],
            [
                'attribute' => 'pos_id',
                'visible' => !$isMerchant
            ],
            [
                'label' => 'Адрес точки',
                'value' => $model->pos->address ?: '-',
            ],
            [
                'attribute' => 'major',
                'visible' => !$isMerchant
            ],
            [
                'attribute' => 'minor',
                'visible' => !$isMerchant
            ],
            'client',
            'message',
            [
                'attribute' => 'template_id',
                'visible' => !$isMerchant
            ],
            [
                'label' => 'Название шаблона',
                'value' => $model->template->name ?: '-',
            ],
        ],
    ]) ?>

</div>
