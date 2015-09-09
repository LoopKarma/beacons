<?php
namespace app\api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return Yii::$app->response->sendFile(Yii::getAlias('@app').'/pass.pkpass');
    }
}
