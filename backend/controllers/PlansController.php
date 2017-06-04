<?php

namespace backend\controllers;


use backend\models\UploadFile;
use Yii;
use backend\models\Payments;
use backend\models\Events;
use backend\models\Plans;
use yii\data\Pagination;
use backend\models\Orders;
use backend\models\Doctors;
use backend\models\Pacients;
use backend\models\User;
use backend\models\Alerts;
use backend\models\Assign;
use backend\models\PlansSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

use yii\helpers\ArrayHelper;


use yii\data\ActiveDataProvider;
use app\rbac\Plan;

/**
 * PlansController implements the CRUD actions for Plans model.
 */
class PlansController extends Controller
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
     * Lists all Plans models.
     * @return mixed
     */
    public function actionIndex()
    {
        $role = Yii::$app->user->identity->role ;
        $user_id = Yii::$app->user->id;

        $searchModel = new PlansSearch();
        $query = '';
        if( $role == 4 || $role ==2 || $role == 3) { // админы, бух, мед.дир


            $dataProvider = new ActiveDataProvider([
                'query' => Plans::find()->joinWith('pacient'),

            ]);


        } elseif($role == 0) { // техники

            // поиск нзначенных пациентов по таблице assign
            /*$assign = Assign::getPacientsByDoctorId($user_id);

            $query = Pacients::find();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            $query->andWhere(['id'=>$assign]);
            */

            $dataProvider = new ActiveDataProvider([
                'query' => Plans::find()->joinWith('pacient')->where(['creater' => $user_id]),
            ]);

        }else{

           $pat =  Pacients::getPacientsByDoctorId($user_id);
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            if($pat) {

                $ids=ArrayHelper::map($pat, 'id', 'id');
                // показать только свои заказы
                $dataProvider = new ActiveDataProvider([
                    'query' => Plans::find()->joinWith('pacient')->where(['IN', 'pacient_id', $ids])->andWhere(['approved' => 1]),
                ]);
            }
        }
        $dataProvider->pagination->pageSize=10;

        $dataProvider->setSort([
            'attributes' => [
                'pacient' => [
                    'asc' =>    [  'orto_pacients.name' => SORT_ASC ],
                    'desc' =>   [   'orto_pacients.name' => SORT_DESC ],

                ],
                'version'
            ],

        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionView($id)
    {
        return $this->redirect(['update', 'id' => $id]);
    }

    /**
     * Creates a new Plans model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Plans();

        $user_id = Yii::$app->user->id;
        $role = Yii::$app->user->identity->role;

        if ($model->load(Yii::$app->request->post()) ) {


            $countArr = Plans::find()->select('id')->where(['pacient_id' => $model['pacient_id']])->asArray()->all();

            $model->version = $model->pacient_id.'.'.(count($countArr)); // новая версия плана при сохранении

            $model->created_at = time();

            //if(!Plan::choseCreate($role)) {

            $model->creater = $user_id; // текущий техник, создавший план
          // }
            //    if($role==0) $model->doctor_id = $user_id; // только текущий техник, создавший план
            $model->files = '1';


            if( $model->save()) {

                $doctor = User::findOne($user_id);
                $pacient = Pacients::findOne($model->pacient_id);

                $msg = 'Здравствуйте, Уважаемый(-ая)' . $pacient->doctor->fullname . '! Виртуальный план (версия ' . $model->version . ') для пациента ' . $pacient->name . ' создан.<br>Ознакомиться с ним Вы можете в личном кабинете.<br><br>С уважением, ' . $pacient->tehnik->fullname;

                $files = new UploadFile();
                $files->imageFiles = UploadedFile::getInstances($model, 'files');
                if ($files->imageFiles) {
                    $files->upload(Yii::getAlias("@backend/web/uploads/plans/" . $model->id));
                }

                // запись событий пользователя
                $event = new Events();
                $event->pacient_id = $model['pacient_id'];
                $event->event = $pacient::$EVENT_VP_CREATE; // plan - создание Виртуального Плана
                $event->text = $msg;
                $event->date = time();
                $event->save();



                // на почту админу и доктору
                $send_email = true;

                if ( $send_email) {



                    //echo $user->email . ' ' . $msg;                    exit;
                    //$user_email = 'webdocswp@gmail.com';

                    // $user_email = 'uz.cx@mail.ru';
                    //  $user_email = 'detinnov16@yandex.ru';
                    /*  $user_email = 'fashiontest@mail.ru';


                    $to = $user_email; // where you want to send the mail
                    $from = Yii::$app->params['adminEmail']; // a valid address on your domain
                    $subject = 'Comment form';
                    / *$message = 'From: ' . 'test' . "\r\n\r\n";
                    $message .= 'Comments: ' . 'comments';
                    $headers = "From: $from\r\nReply-to: $from"; * /


                    $message = 'hello';
                    $headers = 'From: ' . $from . "\r\n" .
                        'Reply-To: ' . $from . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();


                    //$to = 'email@mail.ru';
                    //$from = 'email@yandex.ru';
                    //$subject = 'Табе пакет';
                    $subject = '=?utf-8?b?'. base64_encode($subject) .'?=';
                    $headers = "Content-type: text/plain; charset=\"utf-8\"\r\n";
                    $headers .= "From: <". $from .">\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Date: ". date('D, d M Y h:i:s O') ."\r\n";
                    $message = 'Вот такое вот письмо';


                    // mail($to, $subject, $message, $headers);

                    if ( mail($to, $subject, $message, $headers) ) {
                        echo 'Your message has been sent.';
                    }

                    exit;
                    */




                    if ( $admins = User::getAdmins()) {
                        //$cur_time = time();
                        foreach ($admins as $admin) {
                            // отправить сообщение админам, что произведено подтверждение оплата заказа на производство
                            /*$alert = new Alerts();
                            $alert->text = $msg;
                            $alert->date = $cur_time;
                            $alert->doctor_id_from = $user_id;
                            $alert->doctor_id_to = $admin->id; // админу
                            $alert->save();*/
                            Alerts::alertTo($admin->id, $msg);

                            // отправка на почту - email
                            // email должен существовать
                            if ($admin->email != '' && !Yii::$app->mailer->compose()
                                    ->setFrom(Yii::$app->params['adminEmail'])
                                    ->setTo($admin->email)
                                    ->setSubject('Создание виртуального плана на Ortholiner')
                                    ->setHtmlBody($msg)
                                    ->send()
                            ) {
                                echo 'Ошибка отправки сообщения на почту: ' . $admin->email;
                            }
                        }
                    }

                }
                
                return $this->redirect(['update', 'id' => $model->id]);
            }else{
                print_r($model->errors);

                exit('err save plans');
            }
            
        } else { // первый раз только создание плана
            //$get = Yii::$app->request->get();
            //vd($get);

            if( $role != 0) {
                $pacients = Pacients::getList();
            }else {


                if ($pacients_id = Assign::find()->select('pacient_id')->where(['status' => '0'])->andWhere(['or',
                    ['level_1_doctor_id' => $user_id],
                    ['level_2_doctor_id' => $user_id],
                    ['level_3_doctor_id' => $user_id],
                    ['level_4_doctor_id' => $user_id],
                    ['level_5_doctor_id' => $user_id],
                ])->all()
                ) {

                    foreach ($pacients_id as $item) {
                        $pacients_ids[] = $item->pacient_id;
                    }

                    // все пациенты, для которых разрешено создание ВП
                    // или если создание ВП бесплатное
                    $pacients = Pacients::getList(['id' => $pacients_ids, 'vp_enable' => '1']);

                    /*foreach($pacients as $p){
                         echo $p->id . ', ';
                    }*/
                   // print_r($pacients);


                } else {
                    $pacients = [];
                }
            }
            $orders = Orders::getList();
            $doctors = User::getDoctors();

            $title = '';

            /*
            $order_id = '';
          if($get[1]){
                $order_id = $get[1];
                $order = Orders::findOne(['id' => $order_id]);
                $countPlans = Plans::find()->count();
                //vd($countPlans);
                if($countPlans > 1)
                    $title = "Создания альтернативного плана";
                $model->order_id = $order_id;
                $model->doctor_id = $order->doctor_id;
                $model->pacient_id = $order->pacient_code;
                $model->level_3_doctor_id = $order->level_3_doctor_id;
            }*/
            return $this->render('create', [
                'model' => $model,
                'pacients' => $pacients,
                'orders' => $orders,
                'doctors' => $doctors,
                'title' => $title,
            ]);
        }
    }

    /**
     * Updates an existing Plans model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->fileList = $this->getFileList($model->id);

        $user_id = Yii::$app->user->identity->id;

        if(Yii::$app->user->identity->role != 0){
            $pacients = Pacients::getList();
        }else {
            // поиск id назначенных пациентов для данного user
            if ($pacients_id = Assign::find()->select('pacient_id')->where(['status' => '0'])->andWhere(['or',
                ['level_1_doctor_id' => $user_id],
                ['level_2_doctor_id' => $user_id],
                ['level_3_doctor_id' => $user_id],
                ['level_4_doctor_id' => $user_id],
                ['level_5_doctor_id' => $user_id],
            ])->all()
            ) {

                foreach ($pacients_id as $item) {
                    $pacients_ids[] = $item->pacient_id;
                }
                // или если создание ВП бесплатное
                $pacients = Pacients::getList(['id' => $pacients_ids, 'vp_enable' => '1']);

            } else {
                $pacients = [];
            }
        }

        if ($model->load(Yii::$app->request->post()) ){
             $model->files = '1';

             if($model->save()) {
                 $files = new UploadFile();
                 $files->imageFiles = UploadedFile::getInstances($model, 'files');
                 if ($files->imageFiles) {
                     $files->upload(Yii::getAlias("@backend/web/uploads/plans/" . $model->id));
                 }
                 // return $this->redirect(['update', 'id' => $model->id]);
             }

            $cancel_vp = Yii::$app->request->post('cancel_vp');

            // нажата кнопка Отказать ВП
            if( $cancel_vp=='1' ){
                $this->actionCancel($model->id);
            }

           /* if( $model->approved ) { // если план подтвержден оправить врачу

                // пациент
                $pacient = Pacients::findOne($model->pacient_id);
                // отправка email вврачу
                $user = User::findOne($pacient->doctor_id);

                //$doctor = User::findOne($user_id);

                $msg = 'Здравствуйте, Уважаемый(-ая)' . $pacient->doctor->fullname . '! Виртуальный план (версия ' . $model->version . ') для пациента ' . $pacient->name . ' создан.<br>Ознакомиться с ним Вы можете в личном кабинете.<br><br>С уважением, ' . $pacient->tehnik->fullname;

                if ($user->email != '' && !Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setTo($user->email)
                        ->setSubject('Создание виртуального плана на Ortholiner')
                        ->setHtmlBody($msg)
                        ->send()
                ) {
                    echo 'Ошибка отправки сообщения на почту: ' . $user->email;
                } else {
                    // echo ' send ok';
                }

                Alerts::alertTo($pacient->doctor_id, $msg);

            }*/

            return $this->redirect(['update', 'id' => $model->id]);

        }

        return $this->render('update', [
            'model' => $model,
            'pacients' =>$pacients
        ]);
    }

    /*
     * Медицинский директор утверждает
     * виртуальный план лечения
     * */
    public function actionApprove($id)
    {
        $role = Yii::$app->user->identity->role;
        $user_id = Yii::$app->user->id;
        if($role == 2|| $role == 4){
            $model = $this->findModel($id);

            if (Yii::$app->request->isPost) {
                $post = Yii::$app->request->post();
                $model->approved = $post['Plans']['approved'];


                if($model->approved) {
                    $msg = 'Виртуальный план версии ' . $model->version . ' утвержден мед. директором';
                }else{
                    $msg = 'Виртуальный план версии ' . $model->version . ' НЕ утвержден мед. директором';
                }
                // Отправляет сообщение пользователям
                Alerts::alertTo($model->doctor_id,$msg);

                Alerts::alertTo($model->creater, $msg);

                // $send_email = true;

                /* if(!$send_email) { // только технику
                    $users = User::find()->where(['id' => [$model->creater]])->all();
                }else { // техникку и врачу
                    $users = User::find()->where(['id' => [$model->doctor_id, $model->creater]])->all();
                } */

                // пациент
                $pacient = Pacients::findOne($model->pacient_id);
                // отправка email вврачу

                // техник и доктор
                $ids = [$pacient->tehnik->id,$pacient->doctor_id];


                $users = User::find()->where(['id'=>$ids])->all();//  $model->doctor_id);

                // print_r($users);exit;

                // email врача и техника
                    if ($users) {
                        foreach ($users as $user) {
                            if ($user->email != '' && !Yii::$app->mailer->compose()
                                    ->setFrom(Yii::$app->params['adminEmail'])
                                    ->setTo($user->email)
                                    ->setSubject('Виртуальный план на Ortholiner')
                                    ->setHtmlBody($msg)
                                    ->send()
                            ) {
                                echo 'Ошибка отправки сообщения на почту: ' . $user->email;
                            }
                            //echo 'отправка на почту '.$user->email ;
                            //echo $msg;
                        }
                    }

                /*Yii::$app->mailer->compose()
                    ->setFrom(Yii::$app->params['adminEmail'])
                    ->setTo('proger.integratic.1505@gmail.com')
                    ->setSubject('Виртуальный план на Ortholiner')
                    ->setHtmlBody($msg)
                    ->send();*/

                //exit;

                if($model->save()) {
                    return $this->redirect(['pacients/update', 'id' => $model->pacient_id]); // назад перейти к пациенту
                }else{
                    echo '<meta charset="UTF-8">';
                    print_r($model->getErrors());
                    exit;
                }

            } else {

                return $this->render('approve', [
                    'model' => $model,
                ]);
            }

        }else {
            echo('Это действие может выполнять только мед. директор');
            //sleep(3);
            return $this->redirect('plans/index');
        }

    }

    /*
     * Врач оценивает его (ВП) и в
     * случае принятия, утверждает его

     * Если врач требует коррекции виртуального плана
     * лечения он оставляет комментарий с описанием
     * требуемой коррекции
     * */
    public function actionReady($id)
    {
        $role = Yii::$app->user->identity->role;
        $user_id = Yii::$app->user->id;

        if($role == 1){
                $model = $this->findModel($id);
           // if (Yii::$app->request->isPost) {
               // $post = Yii::$app->request->post();
         //  echo $id; exit;

                $model->ready = 1; //$post['Plans']['ready'];
                $model->cancel = 0; // Отменен 0-нет 1-да отказ

                /*if($model->ready == 1){
                    $model->correct = '1';
                }else{
                    $model->correct = $post['Plans']['correct'];
                    $model->comments = $post['Plans']['comments'];
                }*/
                //vd($model);
                if($model->save()){

                    $msg = "Виртуальный план {$model->version} утвержден врачем.";
                    // Отправляет сообщение пользователям
                    Alerts::alertTo($model->creater, $msg); // технику создавшему ВП
                    //Alerts::alertTo(0, $msg);

                    // email техника
                   if( $user = User::findOne($model->creater) ) {

                       if ($user->email != '' && !Yii::$app->mailer->compose()
                               ->setFrom(Yii::$app->params['adminEmail'])
                               ->setTo($user->email)
                               ->setSubject('Утверждение виртуального плана на Ortholiner')
                               ->setHtmlBody($msg)
                               ->send()
                       ) {
                           echo 'Ошибка отправки сообщения на почту: ' . $user->email;
                       }
                   }


                    if ($admins = User::getAdmins()) {
                        $cur_time = time();
                        foreach ($admins as $admin) {
                            // отправить сообщение админам, что произведено подтверждение оплата заказа на производство
                            $alert = new Alerts();
                            $alert->text = $msg;
                            $alert->date = $cur_time;
                            $alert->doctor_id_from = $user_id;
                            $alert->doctor_id_to = $admin->id; // админу
                            $alert->save();

                            // отправка на почту - email
                            // email должен существовать
                            if ( $admin->email !='' && ! Yii::$app->mailer->compose()
                                    ->setFrom(Yii::$app->params['adminEmail'])
                                    ->setTo($admin->email)
                                    ->setSubject('Утверждение виртуального плана на Ortholiner')
                                    ->setHtmlBody($msg)
                                    ->send()
                            ){
                                echo 'Ошибка отправки сообщения на почту: ' . $admin->email;
                            }
                        }
                    }

                    return $this->redirect(['update', 'id' => $model->id]);
                }else{
                    print_r($model->errors); exit;
                }

          /*  } else {
                return $this->render('ready', [
                    'model' => $model,
                ]);
            }*/
        }else{
            echo 'Это действие может делать только врач';
            return $this->redirect('plans/index');
        }
    }

    // отмена-возврат ВП врачем
    public function actionCancel($id)
    {
        $role = Yii::$app->user->identity->role;

        if($role == 1){ // только врач
            $model = $this->findModel($id);

                $model->cancel = 1; // Отменен 0-нет 1-да отказ
                $model->ready = 0;
                $model->correct = '0';
               // $model->approved = '0'; // отметка мед.директора

                if($model->save()){

                    $pacient = Pacients::findOne($model->pacient_id);
                    // Отправляет сообщение пользователям
                    $msg = "Виртуальный план {$model->version} НЕ утвержден врачом для пациента: " . $pacient->name .'<br><strong>Причина:</strong><br>' . $model->cancel_msg;

                    // Отправляет сообщение пользователям
                    Alerts::alertTo($model->creater, $msg); // технику создавшему ВП

                    // email техника
                    if($user = User::findOne($model->creater)) {

                        if ($user->email != '' && !Yii::$app->mailer->compose()
                                ->setFrom(Yii::$app->params['adminEmail'])
                                ->setTo($user->email)
                                ->setSubject('Утверждение виртуального плана на Ortholiner')
                                ->setHtmlBody($msg)
                                ->send()
                        ) {
                            echo 'Ошибка отправки сообщения на почту: ' . $user->email;
                        }
                    }

                    if ($admins = User::getAdmins()) {
                        foreach ($admins as $admin) {
                            // отправить сообщение админам, что произведено подтверждение оплата заказа на производство
                            Alerts::alertTo($admin->id, $msg); // технику создавшему ВП

                            // отправка на почту - email
                            // email должен существовать
                            if ( $admin->email !='' && ! Yii::$app->mailer->compose()
                                    ->setFrom(Yii::$app->params['adminEmail'])
                                    ->setTo($admin->email)
                                    ->setSubject('Утверждение виртуального плана на Ortholiner')
                                    ->setHtmlBody($msg)
                                    ->send()
                            ){
                                echo 'Ошибка отправки сообщения на почту: ' . $admin->email;
                            }
                        }
                    }

                }else{
                    echo '<meta charset="utf-8">';
                    vd($model->errors);exit;
                }

        }
       // return $this->redirect(['update', 'id' => $id]);
    }

    /*
     * Если врач требует коррекции виртуального плана
     * лечения он оставляет комментарий с описанием
     * требуемой коррекции
     * */
    /*public function actionCorrect($id)
    {
        $role = Yii::$app->user->identity->role;
        if($role == 1){
            $model = $this->findModel($id);
            if (Yii::$app->request->isPost) {
                $post = Yii::$app->request->post();
                $model->ready = $post['Plans']['ready'];
                if($model->save())
                    return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('correct', [
                    'model' => $model,
                ]);
            }
        }else{
            echo 'Это действие может делать только врач';
            return $this->redirect('plans/index');
        }
    }*/

    /**
     * Deletes an existing Plans model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    public function actionDeleteItem()
    {
       // $role = Yii::$app->user->identity->role;
        $id = Yii::$app->request->get('id');

            $file = Yii::$app->request->get('file');
            $path = Yii::getAlias("@backend/web/uploads/plans/" . $id . '/' . $file);

            if (is_file($path)) unlink($path); // удаление файла

        return $this->redirect(['/plans/update','id'=>$id]);
    }

    public function actionPrint($id)
    {

        $model = Plans::findOne($id);
        $this->layout = 'print';
        $model->fileList = $this->getFileList($model->id);

        $this->view->title = Yii::t('app', 'Print order {0}', $model->id);
        $pacients = Pacients::findOne($model->pacient_id);
//        var_dump($pacients);die;
        $doctor  = $pacients->getDoctor();

        echo  $this->render('print', [
            'model' => $model,
            'pacients' => $pacients,
            'doctor' => $doctor,
            // 'user'  => $user
        ]); die;
    }

    /**
     * Finds the Plans model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Plans the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Plans::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function getFileList($id){
        $files = [];
        $path = Yii::getAlias("@backend/web/uploads/plans/" . $id);
        if(is_dir($path)) {
            $dh = opendir($path);
            while (false !== ($filename = readdir($dh))) {
                if ($filename != '.' && $filename != '..') $files[] = $filename;
            }
            return $files;
        }
        return '';

    }

    public function actionDownload($file) {
        $mimetype='application/octet-stream';
        $id = Yii::$app->request->get('id');
        $filename = Yii::getAlias("@backend/web/uploads/plans/".$id) . '/' . $file;
        if (!file_exists($filename)) return 'Файл не найден';


        header('HTTP/1.1 200 Ok');

        $etag=md5($filename);
        $etag=substr($etag, 0, 8) . '-' . substr($etag, 8, 7) . '-' . substr($etag, 15, 8);
        header('ETag: "' . $etag . '"');

        header('Accept-Ranges: bytes');
        header('Content-Length: ' . (filesize($filename)));

        header('Connection: close');
        header('Content-Type: ' . $mimetype);
        header('Last-Modified: ' . gmdate('r', filemtime($filename)));
        header('Content-Disposition: attachment; filename="' . basename($filename) . '";');
        echo file_get_contents($filename);
        return 0;
    }


    public function actionDownloadplan($id)
    {


        // ini_set("mbstring.func_overload","0");

        $path = Yii::getAlias("@vendor/mpdf60/mpdf.php");

       // echo $path;
        include($path);

        // $mpdf=new \mPDF('');
        $mpdf = new \mPDF('utf-8', 'A4', '10', 'Arial', 0, 0, 5, 5, 5, 5);
        $mpdf->showImageErrors = true;
        $mpdf->autoScriptToLang = true;
        $mpdf->baseScript = 1;	// Use values in classes/ucdn.php  1 = LATIN
        $mpdf->autoVietnamese = true;

        //$mpdf->ResetMargins();
        //$mpdf->setCSS('@backend/web/css/print.css');
        //$mpdf->SetMargins(20,20,20);
        // $mpdf->autoArabic = true;

        $mpdf->autoLangToFont = true;


        $model = Plans::findOne($id);
        $this->layout = 'empty'; // шаблон
        $model->fileList = $this->getFileList($model->id);

        $this->view->title = Yii::t('app', 'Print order {0}', $model->id);
        $pacients = Pacients::findOne($model->pacient_id);
        $doctor  = $pacients->getDoctor();


         $content = $this->render('print', [
            'model' => $model,
            'pacients' => $pacients,
            'doctor' => $doctor,
             'download_pdf' => true,
            // 'user'  => $user
        ]);

        $mpdf->WriteHTML($content);
        /*$pathdir  = Yii::getAlias("@frontend/web/uploads/");
        //BaseFileHelper::createDirectory($pathdir);
        $destination = $pathdir. 'plan_' . $model->pacient_id . '.pdf';
         */
        $filename = 'plan_' . $model->pacient_id . '.pdf';
        // $mpdf->Output($destination ,'I');
        $mpdf->Output( $filename ,'D');
        exit;

    }
    public function actionDownloadplan_2($id) {



        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');

        ob_start();
            $model = Plans::findOne($id);
            $this->layout = 'print';
            $model->fileList = $this->getFileList($model->id);

            $this->view->title = Yii::t('app', 'Print order {0}', $model->id);
            $pacients = Pacients::findOne($model->pacient_id);
            $doctor  = $pacients->getDoctor();

            echo 'test';

            /* echo  $this->render('print', [
                'model' => $model,
                'pacients' => $pacients,
                'doctor' => $doctor,
                // 'user'  => $user
            ]); */
            $content = ob_get_contents();
        ob_end_clean();


        //echo $content;exit;

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
            'content' => $content,//$this->render('print', ['model'=>$model]),
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.img-circle {border-radius: 50%;}',
            'options' => [
                'title' => '', //$model->,
                'subject' => 'PDF'
            ],
            'methods' => [
                //'SetHeader' => ['<img src="/images/inspire2_logo_20.png" class="img-circle"> Школа брейк данса INSPIRE||inspire2.ru'],
                //'SetFooter' => ['|{PAGENO}|'],
            ]
        ]);
        echo 'ok';

        return $pdf->render();


        /* $mimetype='application/octet-stream';
        $id = Yii::$app->request->get('id');
        $filename = Yii::getAlias("@backend/web/uploads/plans/".$id) . '/' . $file;
        if (!file_exists($filename)) return 'Файл не найден';


        header('HTTP/1.1 200 Ok');

        $etag=md5($filename);
        $etag=substr($etag, 0, 8) . '-' . substr($etag, 8, 7) . '-' . substr($etag, 15, 8);
        header('ETag: "' . $etag . '"');

        header('Accept-Ranges: bytes');
        header('Content-Length: ' . (filesize($filename)));

        header('Connection: close');
        header('Content-Type: ' . $mimetype);
        header('Last-Modified: ' . gmdate('r', filemtime($filename)));
        header('Content-Disposition: attachment; filename="' . basename($filename) . '";');
        echo file_get_contents($filename);
        return 0;*/
    }

    // не отправляет сообщения ?? 29,10,2016
    public function actionApply($id)
    {
        $is_status = Yii::$app->request->post('ss');
        $approved = Yii::$app->request->post('approved');
        $ready = Yii::$app->request->post('ready');
//        print_r(Yii::$app->request->post());

        if($is_status=='approved'){
            if( $plan = $this->findModel($id) ) {
                $plan->approved = ($approved=='true')?'0':'1';
                $plan->save();
                echo 'ok';
                exit;
            }
        }
        if($is_status=='ready'){
            if( $plan = $this->findModel($id) ) {
                $plan->ready = ($ready=='true')?'0':'1';
                if( $pacient = Pacients::findOne($plan->pacient_id) ){
                    $pacient->vp_enable = $plan->ready;
                    $pacient->save();
                }
                $plan->save();
                echo 'ok';
                exit;
            }
        }
    }
}
