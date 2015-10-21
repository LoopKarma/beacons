<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>



    <?= $form->field($model, 'login')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'value' => '']);?>

    <?= $form->field($model, 'role')->dropDownList(
        [
            \app\models\User::ROLE_MERCHANT => 'мерчант',
            \app\models\User::ROLE_SELLER => 'продавец',
            \app\models\User::ROLE_ADMIN => 'администратор',
        ],
        [
            'onchange' => "
                if ($(this).val() == 'administrator') {
                    $('#user-merchant_id').prop('disabled', true);
                } else {
                    $('#user-merchant_id').prop('disabled', false);
                }"
        ]
    ) ?>

    <?php //= $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'merchant_id')
        ->dropDownList(\app\models\Merchant::getMerchantList(), ['prompt' => 'Выберите мерчанта']) ?>

    <?= $form->field($model, 'active')->checkbox(['label' => 'Активен'])->label(false) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить изменения', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
