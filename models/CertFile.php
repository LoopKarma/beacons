<?php

namespace app\models;

use Yii;

class CertFile extends File
{
    const FILE_PATH_ALIAS = '@admin';
    const FILE_PATH = '/files/cert/';

    public function rules()
    {
        return [
            [['file'], 'file'],
        ];
    }
}
