<?php
namespace app\controllers;

use app\models\search\TemplateSearch;
use Yii;
use app\models\TemplatePos;
use app\models\TemplateFile;
use app\models\CouponTemplate;
use app\models\search\CouponSearch;
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
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
        if ($post && $model->load($post)) {
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
        if ($post && $model->load($post)) {
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
        $icon = TemplateFile::uploadFile($model, 'icon');
        if ($icon) {
            $model->icon = $icon->file_id;
        }
        $iconRetina = TemplateFile::uploadFile($model, 'icon_retina');
        if ($iconRetina) {
            $model->icon_retina = $iconRetina->file_id;
        }
        $logo = TemplateFile::uploadFile($model, 'logo');
        if ($logo) {
            $model->logo = $logo->file_id;
        }
        $logoRetina = TemplateFile::uploadFile($model, 'logo_retina');
        if ($logoRetina) {
            $model->logo_retina = $logoRetina->file_id;
        }
        $stripImage = TemplateFile::uploadFile($model, 'strip_image');
        if ($stripImage) {
            $model->strip_image = $stripImage->file_id;
        }
        $stripImageRetina = TemplateFile::uploadFile($model, 'strip_image_retina');
        if ($stripImageRetina) {
            $model->strip_image_retina = $stripImageRetina->file_id;
        }
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
}
