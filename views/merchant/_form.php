<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model app\models\Merchant */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="merchant-form">

    <?php $form = ActiveForm::begin([
        'options'=>['enctype'=>'multipart/form-data']
    ]); ?>

    <?= $form->field($model, 'uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'major')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pass_type_id')->textInput(['maxlength' => true]) ?>

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
        'allowedFileExtensions' => ['p12'],
        'language' => 'ru'
    ] ?>
    <?php if ($file = $model->certFile) {
        $filePluginOptions['initialCaption'] =  $file->original_name;
    }?>
    <?= $form->field($model, 'cert_files')->widget(FileInput::className(), [
        'pluginOptions' => $filePluginOptions
    ])?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить изменения', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
