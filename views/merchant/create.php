<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Merchant */

$this->title = 'Создать Мерчанта';
$this->params['breadcrumbs'][] = ['label' => 'Мерчанты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="merchant-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
