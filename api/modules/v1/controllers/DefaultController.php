<?php
namespace app\api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\VerbFilter;
use app\api\modules\v1\models\CouponGenerator;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
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
                $headers->set('Content-type', 'application/vnd.apple.pkpass');
                return $this->renderFile($couponPath);
            }
        }
        $error = (string)reset($model->firstErrors);
        throw new NotFoundHttpException($error);
    }
}
