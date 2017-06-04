<?php

namespace backend\controllers;

use Yii;
use backend\models\Alerts;
use backend\models\AlertsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AlertsController implements the CRUD actions for Alerts model.
 */
class AlertsController extends Controller
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
     * Lists all Alerts models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AlertsSearch();
        $role = Yii::$app->user->identity->role;
        //if($role == 2 || $role == 4){
        //    $dataProvider = $searchModel->search0(Yii::$app->request->queryParams);
        //}else{
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //}


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Alerts model.
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
     * Creates a new Alerts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Alerts();

        $date = Yii::$app->request->post('Alerts');

        $date = isset($date['date']) ? strtotime($date['date']) : 0;

        //print_r(Yii::$app->request->post());exit;
        if ($model->load(Yii::$app->request->post()) ) {
            $model->doctor_id_from = Yii::$app->user->id;
            $model->date = $date;
            if (!$model->save()) {
                print_r($model->getErrors());
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Alerts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ){
            $model->doctor_id_from = Yii::$app->user->id;
            //$date = Yii::$app->request->post('Alerts');
            ///print_r($date); exit;
            //$model->date = strtotime($date['date']);
            if(!$model->save()) print_r($model->getErrors());

            return $this->redirect(['index']);
        } else {
            $model->read_status = 1; // прочитано, как только просмотрен
            if(!$model->save()) print_r($model->getErrors());

            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Alerts model.
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
     * Finds the Alerts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Alerts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Alerts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    // отправка нопоминаний
    // date в формате timestamp без времени
    public function actionSendReminders($date = false){

        if( ! $date ) $date = strtotime( date('d-m-Y',time()) ); // проверка по датам без времени

        // выбрать все напоминания не прочтенные на сегодняшнюю дату
        if($alerts = Alerts::find()->where(['type'=>'1','read_status'=>'0', 'date'=> $date ])->all()) {
            foreach ($alerts as $alert) {
                $alert->type = 0; // из напоминания в сообщение (сделать 0=видимым для получателя)  type = 1 не отображается

                $msg = $alert->text; // сообщение

                $alert->save();

                // Отправка письма врачу

                // email врача
                $user = User::findOne($alerts->doctor_id_to);

                if ( $user->email !='' && ! Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setTo($user->email)
                        ->setSubject('Напоминание на Ortholiner')
                        ->setHtmlBody($msg)
                        ->send()
                ){
                    echo 'Ошибка отправки сообщения на почту: ' . $user->email;
                }


                if ($admins = User::getAdmins()) {

                    $msg = $user->fullname . ' отправлено напоминание:<br>' . $msg;
                    foreach ($admins as $admin) {
                        // email должен существовать
                        if ($admin->email != '' && !Yii::$app->mailer->compose()
                                ->setFrom(Yii::$app->params['adminEmail'])
                                ->setTo($admin->email)
                                ->setSubject('Напоминание на Ortholiner')
                                ->setHtmlBody($msg)
                                ->send()
                        ) {
                            echo '<meta charset="UTF-8">';
                            echo 'Ошибка отправки сообщения на почту: ' . $user->email;
                        }
                    }
                }

            }
        }
    }


}
