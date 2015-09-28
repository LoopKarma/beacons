<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CouponSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Купоны';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'coupon_id',
            [
                'attribute' => 'merchant_id',
                'format' => 'html',
                'value' => function ($model) {
                    return Html::a(
                        'Мерчант [' . $model->merchant_id . ']',
                        ['/merchant/view', 'id' => $model->merchant_id]
                    );
                },
            ],
            [
                'attribute' => 'template_id',
                'format' => 'html',
                'value' => function ($model) {
                    return Html::a(
                        'Шаблон [' . $model->template_id . ']',
                        ['/template/view', 'id' => $model->template_id]
                    );
                },
            ],
            'uuid',
            'major',
            'minor',
            [
                'attribute' => 'pos_id',
                'format' => 'html',
                'value' => function ($model) {
                    return Html::a('Точка продаж [' . $model->pos_id . ']', ['/pos/view', 'id' => $model->pos_id]);
                },
            ],
            'client',
            // 'confirmed',
            //'message',
            'serial_number',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{delete}',
            ],
        ],
    ]); ?>

</div>
