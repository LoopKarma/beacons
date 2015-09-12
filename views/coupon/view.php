<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Coupon */

$this->title = 'Купон [' . $model->coupon_id;
$this->params['breadcrumbs'][] = ['label' => 'Купоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'coupon_id',
            'create_date',
            'serial_number',
            'merchant_id',
            'uuid',
            'pos_id',
            'major',
            'minor',
            'client',
            'message',
            'template_id',
        ],
    ]) ?>

</div>
