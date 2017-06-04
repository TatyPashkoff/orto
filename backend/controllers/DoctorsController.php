<?php

namespace backend\controllers;

use Yii;
use backend\models\Doctors;
use backend\models\DoctorsSearch;
use backend\models\User;
use backend\models\DoctorClinic;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DoctorsController implements the CRUD actions for Doctors model.
 */
class DoctorsController extends Controller
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
        ];
    }

    /**
     * Lists all Doctors models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DoctorsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $role = Yii::$app->user->identity->role;
            if($role != 4) { // только админ
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionCabinet($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {
            $post = Yii::$app->request->post();
            $model->age = strtotime( ($post['Doctors']['age']) );
            $model->firstname = $post['Doctors']['firstname'];
            //$model->clinics = json_encode($post['Doctors']['clinics']);
            //$session = Yii::$app->session;

            //$session->set('clinic_id',$model->clinic_id);

            if( $model->password ){
                // смена пароля пользователя - врача, техника ...
                $user = User::findIdentity($model->id);
                $user->setPassword($model->password);

                $user->save();
            }

            if($model->save()){
                if(!empty($post['Doctors']['clinics'])) {
                    $model->setClinics($post['Doctors']['clinics']);
                }
            }else {
                vd($model->errors);
            }
        }
        $clinics = $model->getClinicsByDoctor();
        //vd($clinics);
        $clinicArr = [];
        foreach($clinics as $clinic){
            $clinicArr[] = $clinic['click_id'];
        }
        $model->clinics = $clinicArr;
        //vd($clinicArr, false);
        return $this->render('update', [
            'clinicArr' => $clinicArr,
            'model' => $model,
        ]);
    }
    /**
     * Displays a single Doctors model.
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
     * Creates a new Doctors model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Doctors();

        if ($model->load(Yii::$app->request->post()) ) {
            $post = Yii::$app->request->post();
            $model->age = strtotime($post['Doctors']['age']);
            $model->clinics = json_encode($post['Doctors']['clinics']);

            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                vd($model->errors);
            }


        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Doctors model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {
            $post = Yii::$app->request->post();
            $model->age = strtotime( ($post['Doctors']['age']) );
            $model->firstname = $post['Doctors']['firstname'];
            //$model->clinics = json_encode($post['Doctors']['clinics']);
            //$session = Yii::$app->session;

            //$session->set('clinic_id',$model->clinic_id);
            
            if( $model->password ){
                // смена пароля пользователя - врача, техника ...
                $user = User::findIdentity($model->id);
                $user->setPassword($model->password);

                $user->save();
            }
            
            if($model->save()){
                if(!empty($post['Doctors']['clinics'])) {
                    $model->setClinics($post['Doctors']['clinics']);
                }
            }else {
                vd($model->errors);
            }
        }
        $clinics = $model->getClinicsByDoctor();
        //vd($clinics);
        $clinicArr = [];
        foreach($clinics as $clinic){
            $clinicArr[] = $clinic['click_id'];
        }
        $model->clinics = $clinicArr;
        //vd($clinicArr, false);
        return $this->render('update', [
            'clinicArr' => $clinicArr,
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Doctors model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Doctors model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Doctors the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Doctors::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
