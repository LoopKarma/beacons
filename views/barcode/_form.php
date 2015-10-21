<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BarcodeMessage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="barcode-message-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if (Yii::$app->user->can(\app\models\User::ROLE_ADMIN)) : ?>
        <?= $form->field($model, 'merchant_id')
            ->dropDownList(\app\models\Merchant::getMerchantList(), ['prompt' => 'Выберите мерчанта']) ?>
    <?php else: ?>
        <?= $form
            ->field($model, 'merchant_id')
            ->textInput(['value' => Yii::$app->user->identity->merchant->merchant_id]) ?>
    <?php endif?>

    <?= $form->field($model, 'message')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить изменения', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
