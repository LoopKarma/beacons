<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Merchant */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Мерчанты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="merchant-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->merchant_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->merchant_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Хотите удалить мерчанта? Процедуру нельзя отменить.',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'merchant_id',
            'create_date',
            'update_date',
            'uuid',
            'major',
            'name',
            'description',
            'pass_type_id',
            [
                'attribute' => 'cert_files',
                'format' => 'html',
                'value' => $model->certFile ?
                    $model->certFile->original_name. ' ['.$model->certFile->file_id . ']' : false
            ]
        ],
    ]) ?>

</div>
