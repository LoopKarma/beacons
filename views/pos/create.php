<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Pos */

$this->title = 'Create Pos';
$this->params['breadcrumbs'][] = ['label' => 'Pos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
