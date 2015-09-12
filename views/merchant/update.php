<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Merchant */

$this->title = 'Изменить Мерчанта: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Мерчанты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->merchant_id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="merchant-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
