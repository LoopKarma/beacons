<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CouponSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Шаблоны';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-template-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать новый шаблон', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'template_id',
            'name',
            [
                'attribute' => 'active',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    $value = $model->{$column->attribute};
                    switch ($value) {
                        case 1:
                            $class = 'success';
                            $label = 'Активен';
                            break;
                        default:
                            $class = 'default';
                            $label = 'Неактивен';
                    };
                    $html = Html::tag('span', Html::encode($label), ['class' => 'label label-' . $class]);
                    return $value === null ? $column->grid->emptyCell : $html;
                }
            ],
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
                    [
                        'class'=>'form-control',
                        'prompt' => 'Выберите мерчанта'
                    ]
                ),
            ],
            // 'pos',
            // 'organization_name',
            // 'team_identifier',
            // 'logo_text',
            // 'description',
            // 'coupon_fields:ntext',
            // 'beacon_relevant_text',
            // 'barcode_format',
            // 'barcode_message_encoding',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
