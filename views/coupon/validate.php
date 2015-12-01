<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\forms\CouponValidate */

$this->title = 'Валидация купона';
if (Yii::$app->user->can(\app\models\User::ROLE_MERCHANT)) {
    $this->params['breadcrumbs'][] = ['label' => 'Купоны', 'url' => ['index']];
}
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="coupon-template-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'serialNumber')
        ->label('Введите ID номер купона (номер, указанный на лицевой стороне купона, предъявляемого покупателем )')?>

    <div class="form-group">
        <?= Html::submitButton('Подтвердить купон', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php $form::end()?>

</div>
