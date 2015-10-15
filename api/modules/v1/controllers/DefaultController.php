<?php
namespace app\api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use app\api\modules\v1\models\CouponGenerator;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::className(),
            'actions' => [
                'index'  => ['get'],
            ],
        ];

        return $behaviors;
    }

    public function actionIndex()
    {
        $model = new CouponGenerator();
        if ($model->load(Yii::$app->request->get(), '') && $model->validate()) {
            if ($couponPath = $model->generateCoupon()) {
                $headers = Yii::$app->response->headers;
                $headers->set('Pragma', 'no-cache');
                $headers->set('Content-type', 'application/vnd.apple.pkpass');
                $headers->set('Content-length', filesize($couponPath));

                return Yii::$app->response->sendFile($couponPath);
            }
        }
        $errors = $model->firstErrors;
        $error = (string)reset($errors);
        throw new NotFoundHttpException($error);
    }

    public function actionCheck()
    {
        $model = new CouponGenerator();
        if ($model->load(Yii::$app->request->get(), '') && $model->validate()) {
            return $model->getTemplateSendScenario();
        }
        $errors = $model->firstErrors;
        $error = (string)reset($errors);
        throw new NotFoundHttpException($error);
    }
}
