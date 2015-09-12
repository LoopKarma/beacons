<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BarcodeMessage */

$this->title = 'Изменить сообщение Barcode' . ' [' . $model->message_id . ']';
$this->params['breadcrumbs'][] = ['label' => 'Сообщения Barcode', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->message_id, 'url' => ['view', 'id' => $model->message_id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="barcode-message-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
