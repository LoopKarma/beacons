<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Pos */

$this->title = $model->pos_id;
$this->params['breadcrumbs'][] = ['label' => 'Точки продаж', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pos-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->pos_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->pos_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Хотите удалить точку продаж? Процедуру нельзя отменить.',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'pos_id',
            [
                'attribute' => 'merchant_id',
                'value' => "{$model->merchant->name} [{$model->merchant->merchant_id}]"
            ],
            'create_date',
            'update_date',
            'address',
            'beacon_identifier',
            'major',
            'minor',
        ],
    ]) ?>

</div>
