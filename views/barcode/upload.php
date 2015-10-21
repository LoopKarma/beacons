<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\forms\MessageUpload */

$this->title = 'Загрузить сообщения Barcode';
$this->params['breadcrumbs'][] = ['label' => 'Сообщения Barcode', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="coupon-template-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'enctype'=>'multipart/form-data',
        ]
    ]); ?>

    <?= $form->field($model, 'merchant_id')
        ->dropDownList(\app\models\Merchant::getMerchantList(), ['prompt' => 'Выберите мерчанта']) ?>

    <?= $form->field($model, 'file')->widget(FileInput::className(), [
        'language' => 'ru',
        'pluginOptions' => [
            'showRemove' => false,
            'showUpload' => false,
            'showClose' => false,
            'showPreview' => false,
        ],
    ])?>

    <div class="form-group">
        <?= Html::submitButton('Загрузить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php $form::end()?>

</div>
