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
            'create_date',
            'update_date',
            [
                'attribute' => 'active',
                'value' => $model->active ? 'Да' : 'Нет',
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
            'beacon_relevant_text',
            'barcode_format',
            'barcode_message_encoding',
            [
                'attribute' => 'icon',
                'format' => 'raw',
                'value' => $model->iconFile ? Html::img($model->iconFile->getUrlPath()) : false,
            ],
            [
                'attribute' => 'icon_retina',
                'format' => 'raw',
                'value' => $model->iconRetinaFile ? Html::img($model->iconRetinaFile->getUrlPath()) : false,
            ],
            [
                'attribute' => 'logo',
                'format' => 'raw',
                'value' => $model->logoFile ? Html::img($model->logoFile->getUrlPath()) : false,
            ],
            [
                'attribute' => 'logo_retina',
                'format' => 'raw',
                'value' => $model->logoRetinaFile ? Html::img($model->logoRetinaFile->getUrlPath()) : false,
            ],
            [
                'attribute' => 'strip_image',
                'format' => 'raw',
                'value' => $model->stripImageFile ? Html::img($model->stripImageFile->getUrlPath()) : false,
            ],
            [
                'attribute' => 'strip_image_retina',
                'format' => 'raw',
                'value' => $model->stripImageRetinaFile ? Html::img($model->stripImageRetinaFile->getUrlPath()) : false,
            ],
        ],
    ]) ?>

</div>
