<?php

namespace backend\controllers;

use Yii;
use backend\models\User;
use backend\models\UserSearch;
use backend\models\Doctors;
//use backend\models\UserInfo;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionCreate()
    {

        $model = new User();
        if ($model->load(Yii::$app->request->post()) && $model->save() ) {
            $post = Yii::$app->request->post();
            $model->birth = strtotime( ($post['User']['birth']) );
            //$model->fullname = $post['User']['fullname'];
            //$model->telefon = $post['User']['telefon'];
            //$model->pasport_details = $post['User']['pasport_details'];
            $model->email = $model->username;
            if( $post['User']['password']!='' ) {
                $model->setPassword($post['User']['password']);
            }
            $userEmail = $post['User']['username'];

            $msg = 'Здравствуйте,  Уважаемый(-ая) '. $post['User']['fullname'].' <br><br>Вы успешно прошли регистрацию в системе <br><br>Доступ для начала работы в системе:<br> <br> Адрес  для входа: http://doctors.ortholiner.kz/ <br><br>Ваш логин: '.$userEmail.'<br><br> Ваш пароль: '. $post['User']['password'] . '<br><br> Изменить Ваши Персональный данные, Вы можете в личном кабинете. <br> Если у Вас возникнут вопросы, Вы можете обратиться к нам написав письмо на ortholiner@smartforce.kz <br><hr>С уважением,  Зарина Кидиралиева <br>Руководитель web-проектов Лаборатории Smartfotce <br>+7 707 700 32 02 <br>+7 707 042 70 18 <br>Г. Астана, пр. Туран 19/1, каб 505';
            // $from = 'ortholiner@smartforce.kz';
            if ($model->save()){
                //$subject = 'Регистрация на Ortholiner';
                $filepath = Yii::getAlias('@backend/web/uploads/instr.docx');


                $send_email = true;

                if($send_email) {

                    Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setTo($userEmail)
                        ->setSubject('Регистрация на Ortholiner')
                        ->setHtmlBody($msg)
                        ->attach($filepath, ['Инструкция_для_работы_в_личном_кабинете.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                        ->send();
                }

            }
            // после создания переходить к списку пользователей
            return $this->redirect(['user/index']);
        } else {

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();

            $model->birth = strtotime( ($post['User']['birth']) );

            $model->fullname = $post['User']['fullname'];
            $model->telefon = $post['User']['telefon'];
            $model->pasport_details = $post['User']['pasport_details'];
            if( $post['User']['password']!='' ) {

                $model->setPassword($post['User']['password']);
            }


            if($model->save()){

                if(!empty($post['User']['clinics'])) {
                    $model->setClinics($post['User']['clinics']);
                }
                // после обновления переходить к списку пользователей
                return $this->redirect(['user/index']);

            }else{

                print_r($model->getErrors()); die;
            }

        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    protected function findModel($id)
    {

        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
}

    