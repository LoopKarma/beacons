<?php
namespace app\controllers;

use app\models\Pos;
use app\models\search\TemplateSearch;
use Yii;
use app\models\TemplatePos;
use app\models\TemplateFile;
use app\models\CouponTemplate;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class TemplateController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'get-merchant-pos'],
                        'allow' => true,
                        'roles' => [\app\models\User::ROLE_ADMIN],
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

    /**
     * Lists all CouponTemplate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TemplateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CouponTemplate model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CouponTemplate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CouponTemplate(['scenario' => 'create']);
        $post = Yii::$app->request->post();
        if ($post && $model->load($post) && $this->checkPos($model, $post)) {
            $this->uploadFiles($model);
            if ($model->save()) {
                if (isset($post['pos'])) {
                    $this->updatePos($model, $post);
                }
                return $this->redirect(['view', 'id' => $model->template_id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CouponTemplate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if (isset($post['pos'])) {
            $this->checkPos($model, $post);
        }
        if ($post && $model->load($post) && !$model->hasErrors()) {
            $this->uploadFiles($model);
            if ($model->save()) {
                if (isset($post['pos'])) {
                    $this->updatePos($model, $post);
                }
                return $this->redirect(['view', 'id' => $model->template_id]);
            }

        }
        return $this->render('update', [
            'model' => $model,
        ]);

    }

    /**
     * Deletes an existing CouponTemplate model.
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
     * Finds the CouponTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CouponTemplate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CouponTemplate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function uploadFiles(ActiveRecord $model)
    {
        $model->icon = TemplateFile::uploadFile($model, 'icon')->file_id ?: $model->icon;
        $model->icon2x = TemplateFile::uploadFile($model, 'icon2x')->file_id ?: $model->icon2x;
        $model->icon3x = TemplateFile::uploadFile($model, 'icon3x')->file_id ?: $model->icon3x;

        $model->logo = TemplateFile::uploadFile($model, 'logo')->file_id ?: $model->logo;
        $model->logo2x = TemplateFile::uploadFile($model, 'logo2x')->file_id ?: $model->logo2x;
        $model->logo3x = TemplateFile::uploadFile($model, 'logo3x')->file_id ?: $model->logo3x;

        $model->strip = TemplateFile::uploadFile($model, 'strip')->file_id ?: $model->strip;
        $model->strip2x = TemplateFile::uploadFile($model, 'strip2x')->file_id ?: $model->strip2x;
        $model->strip3x = TemplateFile::uploadFile($model, 'strip3x')->file_id ?: $model->strip3x;
    }

    public function actionGetMerchantPos($merchantId)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (Yii::$app->request->isAjax && $merchantId) {
            $items = Pos::find()
                ->select('pos_id as id, address as text')
                ->where(['merchant_id' => $merchantId])
                ->asArray()
                ->all();

            $out['results'] = array_values($items);
        }
        return $out;
    }

    protected function updatePos(ActiveRecord $model, $post)
    {
        TemplatePos::deleteAll(['template_id' => $model->template_id]);
        foreach ($post['pos'] as $pos) {
            $posTemplate = new TemplatePos();
            $posTemplate->template_id = $model->template_id;
            $posTemplate->pos_id = $pos;
            $posTemplate->save();
        }
    }

    protected function checkPos(ActiveRecord $model, $post)
    {
        if ($post['pos']) {
            $groups = Pos::find()
                ->select('merchant_id')
                ->where(['pos_id' => $post['pos']])
                ->groupBy('merchant_id')
                ->asArray()
                ->all();

            $posMerchant = $groups[key($groups)]['merchant_id'];
            if (count($groups) != 1) {
                $model->addError(
                    'merchant_id',
                    'Выбранные точки продаж относятся к разным мерчантам. Проверьте точки продаж'
                );
                return false;
            } elseif ($posMerchant != $post['CouponTemplate']['merchant_id']) {
                $model->addError(
                    'merchant_id',
                    'Выбранные точки продаж не принадлежат выбранному мерчанту'
                );
                return false;
            }
        }
        return true;

    }
}
