<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CouponSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Купоны';
$this->params['breadcrumbs'][] = $this->title;
$isMerchant = isset(Yii::$app->user->identity->merchant_id);
?>
<div class="coupon-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'coupon_id',
            [
                'attribute' => 'create_date',
                'filter' => DatePicker::widget([
                    'model'=>$searchModel,
                    'attribute'=>'create_date',
                    'language' => 'ru',
                    'type' => DatePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-mm-dd'
                    ],
                ])
            ],
            [
                'attribute' => 'merchant_id',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->merchant->name . ' [' . $model->merchant_id . ']';
                },
                'visible' => !$isMerchant
            ],
            [
                'label' => 'Название шаблона',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->template->name ? $model->template->name : false;
                },
                'visible' => $isMerchant,
            ],
            [
                'attribute' => 'template_id',
                'format' => 'html',
                'value' => function ($model) {
                    return 'Шаблон [' . $model->template_id . ']';
                },
                'visible' => !$isMerchant,
            ],
            [
                'attribute' => 'uuid',
                'visible' => !$isMerchant,
            ],
            [
                'attribute' => 'major',
                'visible' => !$isMerchant,
            ],
            [
                'attribute' => 'minor',
                'visible' => !$isMerchant,
            ],
            [
                'attribute' => 'pos_id',
                'format' => 'html',
                'value' => function ($model) {
                    return 'Точка продаж [' . $model->pos_id . ']';
                },
                'visible' => !$isMerchant,
            ],
            [
                'label' => 'Адрес точки продаж',
                'attribute' => 'address',
                'value' => 'pos.address',
            ],
            'client',
            'message',
            'serial_number',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],
        ],
    ]); ?>

</div>
