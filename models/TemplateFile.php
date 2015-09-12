<?php

namespace app\models;

use Yii;

class TemplateFile extends File
{
    const FILE_URL = '@webroot/upload/templ/';
    const FILE_PATH_ALIAS = '@admin/web';
    const FILE_PATH = '/upload/templ/';

    public function rules()
    {
        return [
            [['file'], 'file', 'extensions' => 'jpg, jpeg, png'],
        ];
    }
}
