<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pos */

$this->title = 'Update Pos: ' . ' ' . $model->pos_id;
$this->params['breadcrumbs'][] = ['label' => 'Pos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pos_id, 'url' => ['view', 'id' => $model->pos_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
