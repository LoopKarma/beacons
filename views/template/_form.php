<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\CouponTemplate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coupon-template-form">

    <?php $form = ActiveForm::begin([
        'options'=>['enctype'=>'multipart/form-data']
    ]); ?>

    <?= $form->field($model, 'merchant_id')->dropDownList(\app\models\Merchant::getMerchantList()) ?>

    <div class="form-group field-coupontemplate-pos has-success">
        <?= Html::label('Точки продаж') ?>
        <?= select2\Select2::widget([
            'name' => 'pos',
            'value' => $model->getPoses(),
            'data' => \app\models\Pos::getPointsArray(),
            'options' => ['placeholder' => 'Выберите точки продаж'],
            'pluginOptions' => [
                'allowClear' => true,
                'multiple' => true,
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

    <?php $isQR = ($model->barcode_format == 'PKBarcodeFormatQR')?>

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

    <?php if ($file = $model->iconFile) {
        $filePluginOptions['initialPreview'] =  Html::img($file->getUrlPath());
    } else {
        $filePluginOptions['initialPreview'] = false;
    }?>
    <?= $form->field($model, 'icon')->widget(FileInput::className(), [
        'pluginOptions' => ArrayHelper::merge($filePluginOptions, [
            'maxImageHeight' => 29,
            'maxImageWidth' => 29,
        ]),
    ])?>


    <?php if ($file = $model->iconRetinaFile) {
        $filePluginOptions['initialPreview'] =  Html::img($file->getUrlPath());
    } else {
        $filePluginOptions['initialPreview'] = false;
    }?>
    <?= $form->field($model, 'icon_retina')->widget(FileInput::className(), [

        'pluginOptions' => ArrayHelper::merge($filePluginOptions, [
            'maxImageHeight' => 58,
            'maxImageWidth' => 58,
        ])
    ])?>


    <?php if ($file = $model->logoFile) {
        $filePluginOptions['initialPreview'] =  Html::img($file->getUrlPath());
    } else {
        $filePluginOptions['initialPreview'] = false;
    }?>
    <?= $form->field($model, 'logo')->widget(FileInput::className(), [
        'pluginOptions' => ArrayHelper::merge($filePluginOptions, [
            'maxImageHeight' => 50,
            'maxImageWidth' => 160,
        ])
    ])?>

    <?php if ($file = $model->logoRetinaFile) {
        $filePluginOptions['initialPreview'] =  Html::img($file->getUrlPath());
    } else {
        $filePluginOptions['initialPreview'] = false;
    }?>
    <?= $form->field($model, 'logo_retina')->widget(FileInput::className(), [
        'pluginOptions' => ArrayHelper::merge($filePluginOptions, [
            'maxImageHeight' => 100,
            'maxImageWidth' => 312,
        ])
    ])?>


    <?php if ($file = $model->stripImageFile) {
        $filePluginOptions['initialPreview'] =  Html::img($file->getUrlPath());
    } else {
        $filePluginOptions['initialPreview'] = false;
    }?>
    <?= $form->field($model, 'strip_image')->widget(FileInput::className(), [
        'pluginOptions' => ArrayHelper::merge($filePluginOptions, [
            'maxImageHeight' => ($isQR ? 110 : 123),
            'maxImageWidth' => 312,
        ])
    ])?>

    <?php if ($file = $model->stripImageRetinaFile) {
        $filePluginOptions['initialPreview'] =  Html::img($file->getUrlPath());
    } else {
        $filePluginOptions['initialPreview'] = false;
    }?>
    <?= $form->field($model, 'strip_image_retina')->widget(FileInput::className(), [
        'pluginOptions' => ArrayHelper::merge($filePluginOptions, [
            'maxImageHeight' => ($isQR ? 220 : 246),
            'maxImageWidth' => 624,
        ])
    ])?>

    <?= $form->field($model, 'active')->checkbox(['label' => 'Активен'])->label(false) ?>

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

    $('#coupontemplate-barcode_format').on('change', function(){
        if (this.value == 'PKBarcodeFormatPDF417') {
            setStripSize(false);
        } else {
            setStripSize(true);
        }
    });

    function setStripSize(withQR){
        var stripImageInput = $('#coupontemplate-strip_image');
        var stripImageRetinaInput = $('#coupontemplate-strip_image_retina');

        var stripImageElem = stripImageInput.attr('data-krajee-fileinput');
        console.log(stripImageElem);
        var stripImageRetinaElem = stripImageRetinaInput.attr('data-krajee-fileinput');

        if (withQR) {
            setSize(stripImageElem, 312, 110);
            setSize(stripImageRetinaElem, 624, 220);
        } else {
            setSize(stripImageElem, 312, 123);
            setSize(stripImageRetinaElem, 624, 246);
        }
    }

    function setSize(elem, width, height) {
        if (elem.length) {
            elem.maxImageHeight = width;
            elem.maxImageWidth = height;
        }
    }
});
JS;
$this->registerJs($script);
?>