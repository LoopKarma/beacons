<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CouponTemplate */

$this->title = 'Изменить шаблон : ' . ' ' . $model->template_id;
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->template_id, 'url' => ['view', 'id' => $model->template_id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="coupon-template-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
