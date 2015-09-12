<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CouponSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coupon-template-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'template_id') ?>

    <?= $form->field($model, 'create_date') ?>

    <?= $form->field($model, 'update_date') ?>

    <?= $form->field($model, 'active') ?>

    <?= $form->field($model, 'merchant_id') ?>

    <?php // echo $form->field($model, 'pos') ?>

    <?php // echo $form->field($model, 'organization_name') ?>

    <?php // echo $form->field($model, 'team_identifier') ?>

    <?php // echo $form->field($model, 'logo_text') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'coupon_fields') ?>

    <?php // echo $form->field($model, 'beacon_relevant_text') ?>

    <?php // echo $form->field($model, 'barcode_format') ?>

    <?php // echo $form->field($model, 'barcode_message_encoding') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
