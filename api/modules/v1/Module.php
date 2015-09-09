<?php
namespace app\api\modules\v1;

/**
 * Модуль REST API
 * основная задача отдавать сгенерированные файлы pkpass в ответ на правильные запросы
 * @package app\modules\v1
 */
class Module extends \yii\base\Module
{

    public function init()
    {
        parent::init();
        $this->registerErrorHandler();
    }

    private function registerErrorHandler()
    {
        $handler = new components\ApiErrorHandler;
        \Yii::$app->set('errorHandler', $handler);
        $handler->register();
    }
}
