<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\forms\CouponValidate */

$this->title = 'Валидация купона';
$this->params['breadcrumbs'][] = ['label' => 'Купоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="coupon-template-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'serialNumber')?>

    <div class="form-group">
        <?= Html::submitButton('Подтвердить купон', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php $form::end()?>

</div>
