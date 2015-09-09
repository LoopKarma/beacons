<?php
namespace app\api\modules\v1\components;

use Yii;
use yii\web\Response;
use yii\web\ErrorHandler;

/**
 * Class ApiErrorHandler
 * генерирует правильный ответ в случае ошибки на сервере
 * @package app\modules\v1\components
 */
class ApiErrorHandler extends ErrorHandler
{
    /** @var  null|string */
    private $statusCode;

    /**
     * @param \Exception $exception
     */
    protected function renderException($exception)
    {
        if (Yii::$app->has('response')) {
            $response = Yii::$app->getResponse();
        } else {
            $response = new Response();
        }

        $response->data = $this->convertException($exception);

        if (isset($exception->statusCode)) {
            $this->statusCode = $exception->statusCode;
        }

        $response->setStatusCode($this->statusCode);

        $response->send();
    }

    /**
     * @param $exception
     * @return array
     */
    protected function convertException($exception)
    {
        $controller = Yii::$app->controller;

        if ($controller) {
            $controllerId = Yii::$app->controller->id;
            $action       = Yii::$app->controller->action;

            if ($action) {
                $actionId = Yii::$app->controller->action->id;
            } else {
                $actionId = 'none';
            }

        } else {
            $controllerId   = 'none';
            $actionId       = 'none';
        }


        return [
            'action' => $controllerId . '/' . $actionId,
            'data' => null,
            'extra' => [
                'errorName' => $exception->getName(),
            ],
            'message' => $exception->getMessage(),
        ];
    }
}
