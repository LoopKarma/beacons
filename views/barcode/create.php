<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\BarcodeMessage */

$this->title = 'Создать сообщение для Barcode';
$this->params['breadcrumbs'][] = ['label' => 'Сообщения Barcode', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="barcode-message-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
