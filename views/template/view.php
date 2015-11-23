<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CouponTemplate */

$this->title = 'Шаблон [' . $model->template_id . ']';
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-template-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->template_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->template_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Хотите удалить шаблон? Процедуру нельзя отменить',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'template_id',
            'name',
            'create_date',
            'update_date',
            [
                'attribute' => 'active',
                'value' => $model->active ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'send_unlimited',
                'value' => $model->send_unlimited ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'send_scenario',
                'value' => $model->send_scenario == $model::SEND_ON_ENTER ? 'На входе' : 'На выходе',
            ],
            [
                'attribute' => 'merchant_id',
                'value' => "{$model->merchant->name} [{$model->merchant->merchant_id}]",
            ],
            'organization_name',
            'team_identifier',
            'logo_text',
            [
                'label' => 'Точки продаж',
                'format' => 'raw',
                'value' => '<p>'.implode('</p><p>', $model->getPoses('address')).'</p>'
            ],
            'description',
            [
                'attribute' => 'coupon',
                'format' => 'raw',
                'value' => '<pre>' . $model->coupon . '</pre>'
            ],
            [
                'attribute' => 'without_barcode',
                'value' => $model->without_barcode ? 'Баркода нет' : 'Баркод есть',
            ],
            [
                'attribute' => 'show_serial',
                'value' => $model->show_serial ? 'да' : 'нет',
            ],
            [
                'attribute' => 'do_not_generate_messages',
                'value' => $model->do_not_generate_messages ? 'да' : 'нет',
            ],
            [
                'attribute' => 'beacon_relevant_text',
                'visible' => !$model->without_barcode,
            ],
            [
                'attribute' => 'barcode_format',
                'visible' => !$model->without_barcode,
            ],
            [
                'attribute' => 'barcode_message_encoding',
                'visible' => !$model->without_barcode,
            ],
            'background_color',
            'foreground_color',
            'label_color',
            [
                'attribute' => 'icon',
                'format' => 'raw',
                'value' => ($path = $model->getFileUrlPath('icon')) ? Html::img($path) : false,
            ],
            [
                'attribute' => 'icon2x',
                'format' => 'raw',
                'value' => ($path = $model->getFileUrlPath('icon2x')) ? Html::img($path) : false,
            ],
            [
                'attribute' => 'icon3x',
                'format' => 'raw',
                'value' => ($path = $model->getFileUrlPath('icon3x')) ? Html::img($path) : false,
            ],
            [
                'attribute' => 'logo',
                'format' => 'raw',
                'value' => ($path = $model->getFileUrlPath('logo')) ? Html::img($path) : false,
            ],
            [
                'attribute' => 'logo2x',
                'format' => 'raw',
                'value' => ($path = $model->getFileUrlPath('logo2x')) ? Html::img($path) : false,
            ],
            [
                'attribute' => 'logo3x',
                'format' => 'raw',
                'value' => ($path = $model->getFileUrlPath('logo3x')) ? Html::img($path) : false,
            ],
            [
                'attribute' => 'strip',
                'format' => 'raw',
                'value' => ($path = $model->getFileUrlPath('strip')) ? Html::img($path) : false,
            ],
            [
                'attribute' => 'strip2x',
                'format' => 'raw',
                'value' => ($path = $model->getFileUrlPath('strip2x')) ? Html::img($path) : false,
            ],
            [
                'attribute' => 'strip3x',
                'format' => 'raw',
                'value' => ($path = $model->getFileUrlPath('strip3x')) ? Html::img($path) : false,
            ],
        ],
    ]) ?>

</div>
