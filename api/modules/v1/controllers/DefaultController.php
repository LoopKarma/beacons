<?php
namespace app\api\modules\v1\controllers;

use app\api\modules\v1\models\CouponGenerator;
use app\models\CouponTemplate;
use app\models\Merchant;
use app\models\Pos;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\VarDumper;
use yii\rest\Controller;
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
                header('Pragma: no-cache');
                header('Content-type: application/vnd.apple.pkpass');
                header('Content-length: '.filesize($couponPath));
                header('Content-Disposition: attachment; filename="'.$couponPath.'"');
                echo file_get_contents($couponPath);
            }
        }

        return $model->getErrors();
    }
}
