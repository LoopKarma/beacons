<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\BarcodeMessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сообщения Barcode';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="barcode-message-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(
            '<i class="glyphicon glyphicon-open"></i> Загрузить сообщения Barcode',
            ['upload'],
            ['class' => 'btn btn-info']
        )?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'message_id',
            [
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
            ],
            'message',
            [
                'attribute' => 'utilize',
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
                'class' => 'yii\grid\ActionColumn',
            ],
        ],
    ]); ?>

</div>
