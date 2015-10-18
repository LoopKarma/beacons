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
                'attribute' => 'confirmed',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    $value = $model->{$column->attribute};
                    switch ($value) {
                        case 1:
                            $class = 'success';
                            $label = 'Да';
                            break;
                        default:
                            $class = 'default';
                            $label = 'Нет';
                    };
                    $html = Html::tag('span', Html::encode($label), ['class' => 'label label-' . $class]);
                    return $value === null ? $column->grid->emptyCell : $html;
                }
            ],
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
                'label' => 'Имя Мерчанта',
                'attribute' => 'merchant_id',
                'value' => function ($model, $key, $index, $column) {
                    /** @var $model \app\models\CouponTemplate */
                    if ($model->merchant) {
                        return $model->merchant->name;
                    } else {
                        return '';
                    }
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'merchant_id',
                    \app\models\Merchant::getMerchantList(),
                    ['class'=>'form-control']
                ),
                'visible' => !$isMerchant
            ],
            [
                'label' => 'Название шаблона',
                'attribute' => 'templateName',
                'value' => 'template.name',
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
