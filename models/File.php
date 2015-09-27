<?php

namespace app\models;

use Yii;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

/**
 *
 * @property integer $file_id
 * @property string $name
 * @property string $original_name
 * @property string $path

 */
class File extends \yii\db\ActiveRecord
{
    /** @var UploadedFile */
    public $file;
    const FILE_URL = '@web/upload/';
    const FILE_PATH_ALIAS = '@admin';
    const FILE_PATH = '/web/upload/';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%file}}';
    }

    public function atattributeLabels()
    {
        return [
            'file_id' => 'ID Файла',
            'original_name' => 'Оригинальное имя',
            'name' => 'Имя файла',
            'path' => 'Путь к файлу',
        ];
    }

    public function rules()
    {
        return [
            [['original_name', 'path', 'name'], 'safe'],
        ];
    }

    public static function uploadFile($model, $attribute)
    {
        $file = UploadedFile::getInstance($model, $attribute);
        if (!$file) {
            return false;
        } else {
            $model = new static;
            $model->file = $file;
        }
        $model->original_name = $model->file->name;
        $model->name = crc32($file->name.$file->size.$file->extension) . '.' . $model->file->getExtension();
        $model->path = $model->getSavePath() . $model->name;
        if ($model->save()) {
            $file->saveAs(Yii::getAlias($model->getSavePath(true) . $model->name));
        }
        return $model;
    }

    public function getUrlPath()
    {
        return Yii::getAlias(static::FILE_URL . $this->name);
    }

    public function getSavePath($withAlias = false)
    {
        if ($withAlias) {
            return static::FILE_PATH_ALIAS . static::FILE_PATH;
        }
        return static::FILE_PATH;
    }

    public function getPath()
    {
        return Yii::getAlias(static::FILE_PATH_ALIAS.$this->path);
    }
}
