<?php

namespace backend\controllers;

use Yii;
use backend\models\Banners;
use backend\models\BannersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;

use backend\models\UploadFile;
use yii\helpers\BaseFileHelper;
use yii\web\UploadedFile;
/**
 * BannersController implements the CRUD actions for Banners model.
 */
class BannersController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],

            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],

                'rules' => [

                    [
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->getAdminRole() == Yii::$app->user->identity->getRole();
                        },
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

        ];
    }

    /**
     * Lists all Banners models.
     * @return mixed
     */
    public function actionIndex()
    {
        $role = Yii::$app->user->identity->role ;
        $user_id = Yii::$app->user->id;

        $searchModel = new BannersSearch();
       
        if( $role == 4 || $role ==2 ) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }elseif ($role == 0){
            $dataProvider = new ActiveDataProvider([
                'query' => Banners::find()->
                where(['level_3_doctor_id' => $user_id]),
            ]);
            $searchModel = new BannersSearch();
        }else{
            // показать только свои заказы
            $dataProvider = new ActiveDataProvider([
                'query' => Orders::find()->
                where(['doctor_id'=>$user_id])->
                orderBy('date DESC'),
                /*'pagination' => [
                    'pageSize' => 20,
                    ],*/
            ]);
            $searchModel = new BannersSearch();
        }

        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Banners model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Banners model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Banners();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $files = new UploadFile();
            $files->imageFiles = UploadedFile::getInstances($model, 'files');
            if ($files->imageFiles) {
                $files->upload(Yii::getAlias("@backend/web/uploads/banners/" . $model->id) );
            }
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Banners model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {
            $files = new UploadFile();
            $files->imageFiles = UploadedFile::getInstances($model, 'files');
            //$files->imageFiles
            //vd($files->getExtension());
            //vd($files->imageFiles[0]->name);
            
            if ($files->imageFiles) {
                $files->upload(Yii::getAlias("@backend/web/uploads/banners/" . $model->id));
            }

            if(isset($files->imageFiles[0])) $model->banner = $files->imageFiles[0]->name;

            if(!$model->save()) print_r($model->getErrors());

            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            $model->fileList = $model->getFileList($model->id);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Banners model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    public function actionDeleteItem()
    {
        $role = Yii::$app->user->identity->role;
        if( $role == 2 || $role ==4 ) {
            // только админ или мед.дир может управлять баннерами
            $id = Yii::$app->request->get('id');
            $file = Yii::$app->request->get('file');
            $path = Yii::getAlias("@backend/web/uploads/banners/" . $id . '/' . $file);

            if (is_file($path)) unlink($path); // удаление файла
        }
        return $this->redirect(['/banners/update','id'=>$id]);
    }
    /**
     * Finds the Banners model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Banners the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Banners::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    

}
