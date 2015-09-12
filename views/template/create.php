<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CouponTemplate */

$this->title = 'Создать Шаблон';
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-template-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
