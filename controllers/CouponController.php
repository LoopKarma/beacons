<?php

namespace app\controllers;

use app\models\forms\CouponValidate;
use Yii;
use app\models\Coupon;
use app\models\search\CouponSearch;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * CouponController implements the CRUD actions for Coupon model.
 */
class CouponController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => [\app\models\User::ROLE_ADMIN],
                    ],
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => [\app\models\User::ROLE_ADMIN, \app\models\User::ROLE_MERCHANT],
                    ],
                    [
                        'actions' => ['validate'],
                        'allow' => true,
                        'roles' => [\app\models\User::ROLE_MERCHANT, \app\models\User::ROLE_SELLER],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionValidate()
    {
        $model = new CouponValidate();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->validateCoupon()) {
                $message =  'Купон с серийным номером ' . $model->serialNumber . ' успешно погашен';
                $type = 'success';
            } else {
                $message =  'Произошла ошибка при подтверждении купона';
                $type = 'danger';
            }
            Yii::$app->session->setFlash($type, $message, false);
        }

        return $this->render('validate', ['model' => $model]);
    }

    /**
     * Lists all Coupon models.
     * @return mixed
     */
    public function actionIndex($csv = false)
    {
        if (Yii::$app->user->identity->merchant_id) {
            $searchModel = new CouponSearch(['merchant_id' => Yii::$app->user->identity->merchant_id]);
        } else {
            $searchModel = new CouponSearch();
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if ($csv) {
            $headers = Yii::$app->response->headers;
            $headers->set('Pragma', 'no-cache');
            $headers->set('Content-type', 'text/csv');
            $headers->set('Expires', '0');
            $cswWriter = $searchModel->generateCsv($dataProvider->query);
            if ($cswWriter) {
                Yii::$app->response->sendContentAsFile(
                    iconv('utf-8', 'windows-1251', $cswWriter->getContent()),
                    'coup_report_' . date('d.m.Y_H:i:s') . '.csv'
                );
            } else {
                Yii::$app->session->addFlash('danger', 'Не удалось создать файл.');
            }
        } else {
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Displays a single Coupon model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->user->can('view coupon', ['coupon' => $model])) {
            return $this->render('view', [
                'model' => $model,
            ]);
        } else {
            throw new ForbiddenHttpException('Нет доступа');
        }
    }

    /**
     * Creates a new Coupon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Coupon();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->coupon_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Coupon model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->coupon_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Coupon model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Coupon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Coupon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Coupon::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
