<?php

use kartik\select2;
use yii\helpers\Html;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\CouponTemplate */
/* @var $form yii\widgets\ActiveForm */

$isQR = ($model->barcode_format == 'PKBarcodeFormatQR');
$posInitValue = !$model->isNewRecord ? $model->getPoses() : null;
$posInitText = !$model->isNewRecord ? $model->getPoses('address') : null;
?>

<div class="coupon-template-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'enctype'=>'multipart/form-data',
            'id' => 'template-form'
        ]
    ]); ?>

    <?php $model->isNewRecord ? $model->active = 1 : null; ?>
    <?= $form->field($model, 'active')->checkbox()?>

    <?= $form->field($model, 'send_unlimited')->checkbox()?>

    <?= $form->field($model, 'merchant_id')->dropDownList(\app\models\Merchant::getMerchantList()) ?>

    <div class="form-group field-coupontemplate-pos has-success">
        <?= Html::label('Точки продаж') ?>
        <?= select2\Select2::widget([
            'name' => 'pos',
            'value' => $posInitValue,
            'initValueText' => $posInitText,
            'options' => ['placeholder' => 'Выберите точки продаж'],
            'pluginOptions' => [
                'allowClear' => false,
                'multiple' => true,
                'ajax' => [
                    'url' => yii\helpers\Url::to(['template/get-merchant-pos']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) {
                        var merchant = $("#coupontemplate-merchant_id").val();
                        return {"merchantId" : merchant};
                    }')
                ],
            ],
            'size' => 'md',
        ]) ?>
        <div class="help-block"></div>
    </div>


    <?= $form->field($model, 'organization_name')->textInput([
        'maxlength' => true,
        'placeholder' => $model::DEF_ORGANIZATION_NAME,
    ]) ?>

    <?= $form->field($model, 'team_identifier')->textInput([
        'maxlength' => true,
        'placeholder' => $model::DEF_TEAM_IDENTIFIER,
    ]) ?>

    <?= $form->field($model, 'logo_text')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'coupon')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'background_color')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'foreground_color')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'beacon_relevant_text')->textInput([
        'maxlength' => true,
        'placeholder' => $model::DEF_BEACON_REALEVANT_TEXT,
    ]) ?>

    <?= $form->field($model, 'without_barcode')->checkbox()?>


    <?= $form->field($model, 'barcode_format')
        ->dropDownList(array_combine($model::BARCODE_FORMAT, $model::BARCODE_FORMAT))?>

    <?= $form->field($model, 'barcode_message_encoding')->textInput([
        'maxlength' => true,
        'placeholder' => $model::DEF_BARCODE_MESSAGE_ENCODING,
    ]) ?>



    <?php $filePluginOptions = [
        'browseClass' => 'btn btn-success',
        'uploadClass' => 'btn btn-info',
        'showRemove' => false,
        'showUpload' => false,
        'showClose' => false,
        'removeClass' => 'btn btn-danger',
        'removeIcon' => '<i class="glyphicon glyphicon-trash"></i>',
        'browseLabel' => 'Выбрать',
        'removeLabel' => 'Удалить',
        'uploadLabel' => 'Загрузить',
        'allowedFileTypes' => ['image'],
        'allowedFileExtensions' => ['png'],
        'language' => 'ru'
    ] ?>

    <?php #icon?>
    <?= $form->field($model, 'icon')->widget(FileInput::className(), [
        'pluginOptions' => ArrayHelper::merge($filePluginOptions, [
            'maxImageWidth' => 29,
            'maxImageHeight' => 29,
            'initialPreview' => $model->getHtmlImage('icon'),
        ]),
    ])?>
    <?= $form->field($model, 'icon2x')->widget(FileInput::className(), [
        'pluginOptions' => ArrayHelper::merge($filePluginOptions, [
            'maxImageWidth' => 58,
            'maxImageHeight' => 58,
            'initialPreview' => $model->getHtmlImage('icon2x'),
        ]),
    ])?>
    <?= $form->field($model, 'icon3x')->widget(FileInput::className(), [
        'pluginOptions' => ArrayHelper::merge($filePluginOptions, [
            'maxImageWidth' => 87,
            'maxImageHeight' => 87,
            'initialPreview' => $model->getHtmlImage('icon3x'),
        ]),
    ])?>

    <?php #logo?>
    <?= $form->field($model, 'logo')->widget(FileInput::className(), [
        'pluginOptions' => ArrayHelper::merge($filePluginOptions, [
            'maxImageWidth' => 160,
            'maxImageHeight' => 50,
            'initialPreview' => $model->getHtmlImage('logo'),
        ]),
    ])?>
    <?= $form->field($model, 'logo2x')->widget(FileInput::className(), [
        'pluginOptions' => ArrayHelper::merge($filePluginOptions, [
            'maxImageWidth' => 320,
            'maxImageHeight' => 100,
            'initialPreview' => $model->getHtmlImage('logo2x'),
        ]),
    ])?>
    <?= $form->field($model, 'logo3x')->widget(FileInput::className(), [
        'pluginOptions' => ArrayHelper::merge($filePluginOptions, [
            'maxImageWidth' => 480,
            'maxImageHeight' => 150,
            'initialPreview' => $model->getHtmlImage('logo3x'),
        ]),
    ])?>

    <?php #strip?>
    <?= $form->field($model, 'strip')->widget(FileInput::className(), [
        'pluginOptions' => ArrayHelper::merge($filePluginOptions, [
            'maxImageWidth' => 320,
            'maxImageHeight' => $isQR ? 110 : 123,
            'initialPreview' => $model->getHtmlImage('strip'),
        ]),
    ])?>
    <?= $form->field($model, 'strip2x')->widget(FileInput::className(), [
        'pluginOptions' => ArrayHelper::merge($filePluginOptions, [
            'maxImageWidth' => 640,
            'maxImageHeight' => $isQR ? 220 : 246,
            'initialPreview' => $model->getHtmlImage('strip2x'),
        ]),
    ])?>
    <?= $form->field($model, 'strip3x')->widget(FileInput::className(), [
        'pluginOptions' => ArrayHelper::merge($filePluginOptions, [
            'maxImageWidth' => 1125,
            'maxImageHeight' => 432,
            'initialPreview' => $model->getHtmlImage('strip3x'),
        ]),
    ])?>


    <div class="form-group">
        <?= Html::submitButton(
            $model->isNewRecord ? 'Сохранить' : 'Сохранить изменения',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php
$script = <<< JS
$(document).ready(function() {
    $('#coupontemplate-without_barcode').on('click', function(){
        if (this.checked) {
            $('.field-coupontemplate-barcode_format').hide();
            $('.field-coupontemplate-barcode_message_encoding').hide();
        } else {
            $('.field-coupontemplate-barcode_format').show();
            $('.field-coupontemplate-barcode_message_encoding').show();
        }
    });
});
JS;
$this->registerJs($script);
?>