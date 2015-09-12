<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\PosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'pos_id') ?>

    <?= $form->field($model, 'merchant_id') ?>

    <?= $form->field($model, 'create_date') ?>

    <?= $form->field($model, 'update_date') ?>

    <?= $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'beacon_identifier') ?>

    <?php // echo $form->field($model, 'major') ?>

    <?php // echo $form->field($model, 'minor') ?>

    <div class="form-group">
        <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Сбросить', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
