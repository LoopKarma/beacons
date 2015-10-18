<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\BarcodeMessage */

$this->title = 'Сообщение Barcode [' . $model->message_id . ']';
$this->params['breadcrumbs'][] = ['label' => 'Сообщения Barcode', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="barcode-message-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->message_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->message_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены что хотите удалить данное сообщение? Операция необратима.',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'message_id',
            [
                'attribute' => 'merchant_id',
                'value' => "{$model->merchant->name} [{$model->merchant->merchant_id}]",
            ],
            'message',
            [
                'attribute' => 'utilize',
                'value' => $model->utilize ? 'Да' : 'Нет',
            ],
        ],
    ]) ?>

</div>
