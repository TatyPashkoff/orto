<?php

namespace backend\controllers;

use backend\models\Payments;
use backend\models\PaymentsItems;
use Yii;
use backend\models\Assign;
use backend\models\User;
use backend\models\OrderPay;
use backend\models\Plans;
use backend\models\Price;
use backend\models\Pacients;
use backend\models\Events;
use backend\models\Options;
use backend\models\Clinics;
use backend\models\Orders;
use backend\models\Alerts;
use backend\models\OrdersSearch;
use backend\models\OrdersQuery;
use yii\console\Exception;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

use backend\models\UploadFile;
use yii\helpers\BaseFileHelper;
use yii\web\UploadedFile;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller
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
     * Lists all Orders models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrdersSearch();

        $role = Yii::$app->user->identity->role ;
        $user_id = Yii::$app->user->id;

        //echo $role . ' ' ;


        $sort = Yii::$app->request->get('sort');
        $direct = preg_match( '[-]',$sort) ? ' DESC' : ' ASC';
        $sort = trim($sort,'-');

        if(!isset($sort) || $sort ==''){
            $direct = '';
            $sort = '';

        }
        //echo $sort . ' ' . $direct; //exit;

        if( $role == 4 || $role ==2 || $role == 3) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }elseif ($role == 0){

            // найти пацентов для данного техника,
            // у которых оплата:  бесплатно, платно или рассрочка, а также внесена сумма оплаты
/*
            if( $plan_graph = Payments::find()->where(['pacient_id'=>$id, 'order_id'=>$model->order_id])->one() ){
                // получить все элементы план графика
                $plan_items = PaymentsItems::find()->where(['payment_id'=>$plan_graph->id])->all();
            }else{
                $plan_graph = new Payments();
                $plan_items = [];
            }            */



            // сортировка по имени пациента
            $order_pacients = ($sort == 'pacient_code') ? 'name' . $direct : '';


            // получение всех пациентов назначенные данному технику
            $pacients_id = Assign::getPacientsByDoctorIdForOrder($user_id);
            $pac_ret = [];
            if( $pacients_ret = Pacients::find()->select('id')->where(['id'=>$pacients_id,'product_id'=>'2'])->asArray()->orderBy($order_pacients)->all() ){

                foreach($pacients_ret as $p){
                    $pac_ret[] = $p['id'];
                }
            }


            foreach($pacients_id as $k => $id) {
                // последняя подтв. дата оплаты
                $paid = Payments::getLastPaid( $id );// $model->pacient->id); // массив дата-date и сумма - sum
                if($paid['date']=='0') {
                    if(! in_array($pacients_id[$k], $pac_ret)) {
                        //echo 'искл. ' . $pacients_id[$k];
                        unset($pacients_id[$k]); // удалить пациента из списка, т.к. не подтв. оплата
                    }
                }
            }

            //print_r($pacients_id);

           /* if($pacients_code = Pacients::find()->select('code')->orWhere(['var_paid'=>3])->orWhere(['and',['<','var_paid','3'],['>','sum_paid','0']])->all()) {                                  // 3 - бесплатно

                //print_r($pacients_code);
               // exit;
                foreach ($pacients_code as $pacient) {
                    $pacients_codes[] = $pacient->code;
                }
            }else{
                $pacients_codes = [];
            }*/
            //$var_paid = ['Бесплатно','Частичная','Полная'];


            // показать только свои заказы
            $dataProvider = new ActiveDataProvider([
                'query' => Orders::find()->
                    where(['creater'=>$user_id])->
                    andWhere(['pacient_code'=>$pacients_id])
                    //->orderBy( $sort . $direct),

            ]);

            /*$dataProvider->setSort([
                'attributes' => [
                    'id',
                    'doctor_id' => [
                        'asc' => ['doctor_id' => SORT_ASC],
                        'desc' => ['doctor_id' => SORT_DESC],
                        'label' => 'ФИО врача',
                        'default' => SORT_ASC
                    ],
                ]
            ]);*/

            $searchModel = new OrdersSearch();
            
        }else{
            // показать только свои заказы
            $dataProvider = new ActiveDataProvider([
                'query' => Orders::find()
                ->where(['doctor_id'=>$user_id])
                ->andWhere(['order_status'=>'1']) // только отправленные техником
                ->orderBy( $sort . $direct),

            ]);

            $searchModel = new OrdersSearch();
        }
        // get the posts in the current page
        // $posts = $provider->getModels();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * Displays a single Orders model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->redirect(['update', 'id' => $id]);
    }

    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $role = Yii::$app->user->identity->role ;
        $user_id = Yii::$app->user->id;
        if($role == 3){
            throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
        }
        //$model = $this->orderCreate();
        $model = new Orders();
        //$model_vp = new Plans() ;//::find()->where([''=>''])->one();

        //$plan_graf = new Payments();
        $order_date = time(); // текущая дата
        //$status_paid = ['id'=>false,'status_paid'=> 0];


        // получение всех пациентов назначенных данному технику
        $pacients_id = Assign::getPacientsByDoctorIdForOrder($user_id);


       // print_r($pacients_id); exit;
        $pac_ret = [];

        // пациенты с ретейнером
        if( $pacients_ret = Pacients::find()->select('id')->where(['id'=>$pacients_id,'product_id'=>'2'])->asArray()->all() ){
            foreach($pacients_ret as $p){
                $pac_ret[] = $p['id'];
            }
        }

        // echo '<pre>';        print_r($pacients_id);

        $_paid = ['date'=>$order_date,'sum'=>'0'];
        foreach($pacients_id as $k => $id) {
            // последняя подтв. дата оплаты
            $paid = Payments::getLastPaid( $id );// $model->pacient->id); // массив дата-date и сумма - sum

            if($paid['ready']==1) continue;

            if($paid['date']=='0') {
                if(! in_array($pacients_id[$k], $pac_ret)) {
                  // echo ' не подтв оплата ' . $pacients_id[$k];
                    unset($pacients_id[$k]); // удалить пациента из списка, т.к. не подтв. оплата

                }
            }else if($paid['date']<time() && $paid['sum'] == 0 ){
                // просрочена дата оплаты и она не уплочена
               // echo ' просрочена дата ' . $pacients_id[$k] . ' ' ;
                unset($pacients_id[$k]); // удалить пациента из списка, т.к. просрочена оплата
            }else{ //  if($paid['date']<time() && $paid['sum'] == 0 ){
               // 10 дней до следующей даты оплаты
                $paid = Payments::getNextPaid( $id );
                if( $paid['date']-864000 < time() ) {
                    // прошло больше чем 10 дней до даты следующей оплаты
                    //echo '>10 дней' . $pacients_id[$k] . ' ' ;
                    //unset($pacients_id[$k]); // удалить пациента из списка, т.к. просрочена оплата
                }
            }
            $_paid = $paid;
        }
        // print_r($pacients_id); exit;

       // print_r($paid);exit;


        // только техник ???
        if (Yii::$app->request->isPost && $role != 1) {
            $model_files = $model->files;

            $model->load(Yii::$app->request->post());

            // макс. значение номера для сквозной нумерации
            $num = Orders::find()->select('num')->max('num');
            $num++;
            //echo $num;exit;
            $model->num = $num;


            $model->date = date('Y-m-d',$order_date);

            $files = Yii::$app->request->post('files');
            if( is_null($files) ) $model->files = $model_files;
            //$cid = Yii::$app->session['id_clinic'];
            $cid = 0;
            if( isset($_COOKIE['id_clinic']) ) $cid = (int)$_COOKIE['id_clinic'];

            $model->clinics_id = $cid;
            $model->creater = Yii::$app->user->id; //30.09


            if ( $model->save() ){
                $files = new UploadFile();
                if ($files->imageFiles = UploadedFile::getInstances($model, 'files')) {
                    $path = Yii::getAlias("@backend/web/uploads/orders/" . $model->id);
                    if ($files->upload($path)) {
                        $f = [];
                        $i = 0;
                        foreach ($files->imageFiles as $file) {
                            $f[$i]['name'] = $file->name;
                            $f[$i]['size'] = $file->size;
                            $f[$i]['type'] = $file->type;
                            $i++;
                        }
                    }
                }

                if( $pacient = Pacients::find()->select('id')->where(['id'=> $model->pacient_code])->one() ){
                    $event = new Events();
                    $event->pacient_id = $pacient->id;
                    $event->event = $pacient::$EVENT_ORDER_CREATE ; // заказ
                    $event->date = time();
                    $event->save();
                }

                return $this->redirect(['update', 'id' => $model->id]);
            }else{
                echo '<div class="form-group"><a class="btn btn-success" href="/">Назад</a></div>';
                print_r($model->errors);
            }
        } else {
            //$model->price = $clinic->;
            return $this->render('create', [
                'model' => $model,
                'pacients_id' => $pacients_id,
                '_paid' => $_paid,
                ]);
        }
    }


 

    /**
     * Updates an existing Orders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $role = Yii::$app->user->identity->role ;
        if($role == 1 ){
            throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
        }
        $model = $this->orderCreate($id);
       // $model_vp = new Plans();//::find()->where([''=>''])->one();
        

        // если админ или мед.дир взять id техника, который создал отчет
        if($role==2 || $role==4){
            $user_id = $model->creater;
        }else {
            $user_id = Yii::$app->user->id;
        }
        // нужно получить список всех пациентов, у которых имеется подтверждение оплаты на дату заказа
        // по таблице assign найти всех пациентов назначенных данному технику
        // для каждого пациента по таблице payments найти последнюю оплаченную дату (даты у каждого пациента могут быть разные)
        // эта (последняя) дата проставляется в дату заказа
        

        // выбор получение всех пациентов назначенные данному технику
        $pacients_id = Assign::getPacientsByDoctorIdForOrder($user_id);
        $pac_ret = [];

        if( $pacients_ret = Pacients::find()->select('id')->where(['id'=>$pacients_id,'product_id'=>'2'])->asArray()->all() ){
            foreach($pacients_ret as $p){
                $pac_ret[] = $p['id'];
            }
        }



        $_paid = ['date'=>$model->date,'sum'=>'0'];
        foreach($pacients_id as $k => $id) {
            // последняя подтв. дата оплаты
            $paid = Payments::getLastPaid( $id );// $model->pacient->id); // массив дата-date и сумма - sum

            if($paid['ready']==1) continue;

            if($paid['date']===0) {
                // удалить если не ретационная каппа
                if(! in_array($pacients_id[$k], $pac_ret)) {
                    unset($pacients_id[$k]); // удалить пациента из списка, т.к. не подтв. оплата
                }
            }elseif($paid['date']<time() && $paid['sum'] ==0 ){ //$paid['ready']!='1' ){
                // просрочена дата оплаты 15.11.2016
                unset($pacients_id[$k]); // удалить пациента из списка, т.к. просрочена оплата
            }elseif($model->pacient_code==$id){
                $_paid = $paid; // данные текущего пациента (дата и сумма)
            }
        }
        //print_r($pacients_id ); exit;
        if(isset($_COOKIE['id_clinic'])) {
            $cid = (int) $_COOKIE['id_clinic']; // Yii::$app->session['id_clinic'];
            $clinic = Clinics::find()->where(['id' => $cid])->one(); // общие опции задаются админом
        }else{
            $clinic = new Clinics();
        }


        if(isset($clinic->model_price))
            $model->price = $clinic->model_price;
        if(isset($clinic->model_discount))
            $model->discount = $clinic->model_discount;
        //$model->save();

        if (Yii::$app->request->isPost) {
            return $this->redirect(['update', 'id' => $model->id, 'clinic' => $clinic ]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'clinic' => $clinic,
                'pacients_id' => $pacients_id,
                '_paid' => $_paid,
                ]);
        }
    }

    public function orderCreate($id=false)
    {

        $role = Yii::$app->user->identity->role;

        $cur_time = time();
        // статус отправки заказа на следующий уровеь обработки пользователями (техники, врачи, мед.дир.)
        // $sending = Yii::$app->request->post('sending');

        // если админ или мед.дир взять id техника, который создал отчет
        $user_id = Yii::$app->user->id;

        if($id){
            $model = $this->findModel($id);
            $old_files = ( ! is_null($model->files) && $model->files != '' ) ? $model->files : ''; // не сохр. из-за пустого массива
            $model->fileList = $this->getFileList($model->id);
            if($role==2 || $role==4){
                $user_id = $model->creater; // админ
            }
        }else{
            $model = new Orders();
            $old_files = '';
        }

        if($model_price = Price::findOne($model->tarif_plan)) {
            $model->price_by_plan = $model_price->price;
        }

        if ( $model->load(Yii::$app->request->post()) ){

            $model->files = $old_files;



            $post = Yii::$app->request->post('Orders');
            //echo $post['date'];
            //exit;
            
            $model->date = date('Y-m-d',time());// strtotime( $post['date'] ));
            //$model->date_paid = date('Y-m-d',strtotime( $post['date_paid'] ));

            if( $model->save() ) {

                if( $order_pay = Yii::$app->request->post('order_pay') ) {

                    // запись новых дат и сумм от бухгалтера
                    for ($k = 0; $k < count($order_pay['date']); $k++) {

                        if (isset($order_pay['date'][$k])) {
                            $op = new OrderPay();
                            $op->date_paid = date('Y-m-d', strtotime($order_pay['date'][$k]));// $order_date[$k];
                            $op->sum_paid = isset($order_pay['sum'][$k]) ? $order_pay['sum'][$k] : 0;
                            $op->order_id = $model->id;
                            $op->pacient_id = $model->pacient->id;
                            $op->save();
                        }
                    }
                }

                if($role ==1 ||$role ==2 || $role==4) { // если врач, админ или мед.дир сохраняют
                    if ($model->admin_check == 1 /*&& $sending == 1*/ ) {
                        // отправить сообщение технику, что разрешено производство заказа
                        $alert = new Alerts();
                        $alert_text = 'Разрешено производство заказа ' . $model->num . '<br><a href="'. Url::to(['/orders/update?id=' . $model->id]) .'">Перейти к заказу</a>';
                        $alert->text = $alert_text;
                        $alert->date =  $cur_time ;
                        $alert->doctor_id_to = $model->creater; // технику, создавшему заказ
                        // если админ вошел и отправляет, его id, иначе Id техника
                        $alert->doctor_id_from = ($role ==2 || $role==4)? Yii::$app->user->id : $user_id; // от тек. пользователя
                        $alert->save();
                    }
                }
                if($role ==0){ // сохраняет техник
                    if ($model->order_ready == 1 /*&& $sending == 1*/ ) { // производство завершено

                        $_date = Payments::getNextPaid( $model->pacient->id ); // следующая не подтв. дата в план графике
                        // отправить сообщение врачу за 10 дней, что произвде заказ на производство для подтверждения
                        $alert = new Alerts();

                        $alert_text = date('d-m-Y',strtotime($_date['date'])) ." будет доставка заказа для пациента {$model->pacient->name}.<br>";

                        if( Payments::getVarPaid($model->pacient->id) == 1 ) { // частичная оплата
                            $alert_text .= "Не забудьте сделать оплату согласно графику погашения. В случае отсутствия оплаты заказ доставлен не будет.<br>";
                        }
                        $alert_text .= "Если у Вас возникли вопросы, свяжитесь с администратором по почте ortholiner@smartforce.kz<br><br>C уважением, Зарина Кидиралиева";

                        $alert->text = $alert_text;
                        $alert->date = (int)$_date - 7*86400; // сообщение за 7 дней
                        // если эта дата последняя в план графике, то вернется 0
                        $alert->doctor_id_to = $model->doctor_id; // доктору 
                        $alert->doctor_id_from = $user_id;
                        $alert->type = 1; // сообщение для напоминания о предстоящей предоплате
                        $alert->save();
                    }else if ($model->order_status == 1 /*&& $sending == 1*/ ) { // заказ создан и отправлен ?
                        // отправить сообщение врачу, что создан заказ на производство для подтверждения
                        $alert = new Alerts();
                        $alert_text = 'Заказ № ' . $model->num . ' на производство создан.<br>Требуется разрешение на производство.<br><a href="'. Url::to(['/orders/update?id=' . $model->id]) .'">Перейти к заказу</a>';
                        $alert->text = $alert_text;
                        $alert->date = $cur_time ;
                        $alert->doctor_id_to = $model->doctor_id; // доктору
                        $alert->doctor_id_from = $user_id;
                        $alert->save();
                    }
                }
                if($role == 3 /*&& $sending == 1*/ ){ // сохраняет бухгалтер

                    // нужно проверить предоплату !

                    // отправить сообщение врачу, что создан заказ на производство для подтверждения
                    $alert = new Alerts();
                    $alert_text = 'Оплата заказа № ' . $model->num . ' произведена.<br><a href="'. Url::to(['/orders/update?id=' . $model->id]) .'">Перейти к заказу</a>';
                    $alert->text = $alert_text;
                    $alert->date =  $cur_time ;
                    $alert->doctor_id_to = $model->doctor_id; // доктору
                    $alert->doctor_id_from = $user_id;
                    $alert->save();

                    // отправить событие для пациента об оплате заказа
                    $event = new Events();
                    $event->pacient_id = $model->pacient->id;
                    $event->event = Pacients::$EVENT_PAYMENT ; // оплата
                    $event->date =  $cur_time ;
                    $event->save();

                }

                if($role==0 && $model->order_status != 1 ){
                    // если техник создает заказ и не указывает , время от времени сохраняет его
                    // не отправлять

                }else {

                    // отправлять все сообщения админам ???
                    if ($admins = User::getAdmins()) {

                        foreach ($admins as $admin) {
                            // отправить сообщение админам, что произведена оплата заказа на производство
                            $alert = new Alerts();
                            $alert->text = $alert_text;
                            $alert->date = $cur_time;
                            $alert->doctor_id_to = $admin->id; // админу
                            $alert->doctor_id_from = $user_id;
                            $alert->save();
                        }
                    }
                }

                $files = new UploadFile();

                if ($files->imageFiles = UploadedFile::getInstances($model, 'files')) {
                    $path = Yii::getAlias("@backend/web/uploads/orders/" . $model->id);
                    if ($files->upload($path)) {
                        /*$f = [];
                        $i = 0;
                        foreach ($files->imageFiles as $file) {
                            $f[$i]['name'] = $file->name;
                            $f[$i]['size'] = $file->size;
                            $f[$i]['type'] = $file->type;
                            $i++;
                        }*/
                        // $model->files = '';// json_encode($f);
                    }
                }
            }else { //$model->save();
               print_r($model->getErrors());
                echo 'err no save'; die;
            }

        }else{
            //echo 'err no load'; die;
        }

        return $model;

    }

    
    // вместо этой функции используется assign,  так и таблица assign со всеми назначениями техников и их статусами и результатами
    public function actionAppointment($id)
    {
        $model = $this->findModel($id);

        //$this->title = 'Назначает зубных техников';
        //vd($model);
        $role = Yii::$app->user->identity->role;
        if($model->order_status == 1 && ($role ==2 || $role ==4)){
            if(Yii::$app->request->isPost){
                $post = Yii::$app->request->post();
                //vd($post);
                $model->level_1_doctor_id = $post['Orders']['level_1_doctor_id'];
                $model->level_2_doctor_id = $post['Orders']['level_2_doctor_id'];
                $model->level_4_doctor_id = $post['Orders']['level_4_doctor_id'];
                $model->level_3_doctor_id = $post['Orders']['level_3_doctor_id'];
                $model->level_5_doctor_id = $post['Orders']['level_5_doctor_id'];

                // Отправляет сообщение пользователям
                Alerts::alertTo($model->level_1_doctor_id);
                Alerts::alertTo($model->level_2_doctor_id);
                Alerts::alertTo($model->level_3_doctor_id);
                Alerts::alertTo($model->level_4_doctor_id);
                Alerts::alertTo($model->level_5_doctor_id);

                if($model->save()){
                    return $this->redirect(['update', 'id' => $model->id]);
                }else{
                    vd($model->errors);
                }

            }else{
                return $this->render('appointment', ['id' => $model->id, 'model' => $model ]);
            }
        }else{
            return $this->redirect(['update','id'=>$id, 'model'=>$model ]);
        }
        //return $this->redirect(['update','id'=>$id, 'model'=>$model ]);
    }

    /*
     * выставленния отметки при завершении
     * */
    public function actionCheck($id)
    {
        $model = $this->findModel($id);

        //$this->title = 'Назначает зубных техников';
        $role = Yii::$app->user->identity->role;
        $user_id = Yii::$app->user->id;
        $level_id_doctors_arr = [
            $model->level_1_doctor_id,
            $model->level_2_doctor_id,
            $model->level_3_doctor_id,
            $model->level_4_doctor_id,
            $model->level_5_doctor_id,
        ];
        if(in_array($role, [0,2,4]) && in_array($user_id, $level_id_doctors_arr) ){
            if(Yii::$app->request->isPost){
                $post = Yii::$app->request->post();

                if(trim($post['Orders']['level_1_status']) != ""){
                    $model->level_1_status = $post['Orders']['level_1_status'];
                    $model->level_1_result = $post['Orders']['level_1_result'];
                    // Отправляет сообщение пользователям
                    Alerts::alertTo($model->level_1_doctor_id);
                }else if(trim($post['Orders']['level_2_status']) != ""){
                    $model->level_2_status = $post['Orders']['level_2_status'];
                    $model->level_2_result = $post['Orders']['level_2_result'];
                    // Отправляет сообщение пользователям
                    Alerts::alertTo($model->level_2_doctor_id);
                }else if(trim($post['Orders']['level_3_status']) != ""){
                    $model->level_3_status = $post['Orders']['level_3_status'];
                    $model->level_3_result = $post['Orders']['level_3_result'];
                    // Отправляет сообщение пользователям
                    Alerts::alertTo($model->level_4_doctor_id);
                }else if(trim($post['Orders']['level_4_status']) != ""){
                    $model->level_4_status = $post['Orders']['level_4_status'];
                    $model->level_4_result = $post['Orders']['level_4_result'];
                    // Отправляет сообщение пользователям
                    Alerts::alertTo($model->level_4_doctor_id);
                }else if(trim($post['Orders']['level_5_status']) != ""){
                    $model->level_5_status = $post['Orders']['level_5_status'];
                    $model->level_5_result = $post['Orders']['level_5_result'];
                    // Отправляет сообщение пользователям
                    Alerts::alertTo($model->level_5_doctor_id);
                }


                if($model->save()){
                    return $this->redirect(['update', 'id' => $model->id]);
                }else{
                    print_r($model->errors);
                }

            }else{
                //$this->title = '1';
                return $this->render('check', ['id' => $model->id, 'model' => $model ]);
            }
        }else{
            return $this->redirect(['update','id'=>$id, 'model'=>$model ]);
        }
        //return $this->redirect(['update','id'=>$id, 'model'=>$model ]);
    }


    public function actionApply($id)
    {
        $model = $this->findModel($id);
        if(true || $model->status_paid != 0){
            $model->order_status = 1; // заказ отправлен админу
            $model->save();

            $pacient = Pacients::findOne(['code'=>$model->pacient_code]);
            $doctor = User::findOne(['id'=>$model->doctor_id]);
            $clinic = Clinics::findOne( $doctor->clinic_id );
           // echo  $doctor->id . ' ' .  $doctor->clinic_id ; exit;
           // print_r([$clinic, $doctor]); exit;

            $alert_text =
'<h3>Добавлен новый пациент</h3>' .
'<br><strong>Дата:</strong> ' . date('Y-m-d',time()) .
'<br><strong>ФИО врача:</strong> ' . $doctor->fullname .
'<br><strong>Клиника:</strong> ' . $clinic->title .
'<br><strong>ФИО пациента:</strong> ' . $pacient->name;

            // отправлять все сообщения админам ???
            if($admins = User::getAdmins()) {

                foreach ($admins as $admin) {
                    // отправить сообщение админам, что добавлен пациент и заказ
                    $alert = new Alerts();
                    $alert->text = $alert_text;
                    $alert->date =  time() ;
                    $alert->doctor_id_to = $admin->id; // админу
                    $alert->doctor_id_from = $model->doctor_id;
                    $alert->save();
                }
            }
            return $this->redirect(['update','id'=>$id, 'model'=>$model ]);
        }else{
            Yii::$app->session->setFlash('error', []);
            return $this->redirect(['update','id'=>$id, 'model'=>$model ]);
        }
    }

    /**
     * Pre Paid an existing Orders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionPrepaid($id)
    {
        $model = $this->findModel($id);

        //$this->title = 'Назначает зубных техников';
        $role = Yii::$app->user->identity->role;
        if($role == 3 || $role == 4){
            if(Yii::$app->request->isPost){
                $post = Yii::$app->request->post();
                $model->status_paid = $post['Orders']['status_paid'];

                // Отправляет сообщение пользователям
                Alerts::alertTo(0);

                if($model->save()){
                    return $this->redirect(['update', 'id' => $model->id]);
                }else{
                    print_r($model->errors);
                }
            }else{
                return $this->render('prepaid', ['id' => $model->id, 'model' => $model ]);
            }
        }else{
            return $this->redirect(['update','id'=>$id, 'model'=>$model ]);
        }
    }

    /**
     * Частичное оплата.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionPaid($id)
    {
        $model = $this->findModel($id);

        $role = Yii::$app->user->identity->role;
        if($role == 1 || $role == 4){
            if(Yii::$app->request->isPost){
                $post = Yii::$app->request->post();
                //vd($model->attributes, false);
                $model->sum_paid = ($model->sum_paid + $post['Orders']['sum_paid']);
                $model->date_paid = $post['Orders']['date_paid'];
                if($model->sum_paid == $model->price){
                    $model->status_paid = '2';
                }
                //vd($model->attributes);
                // Отправляет сообщение пользователям
                Alerts::alertTo(0);

                if($model->save()){
                    return $this->redirect(['update', 'id' => $model->id]);
                }else{
                    print_r($model->errors);
                }
            }else{
                //vd($model);
                return $this->render('paid', ['id' => $model->id, 'model' => $model ]);
            }
        }else{
            return $this->redirect(['update','id'=>$id, 'model'=>$model ]);
        }
    }


    public function getFileList($id){

        $path = Yii::getAlias("@backend/web/uploads/orders/" . $id);
        if(is_dir($path)) {
            $dh = opendir($path);
            while (false !== ($filename = readdir($dh))) {
                if ($filename != '.' && $filename != '..') $files[] = $filename;
            }
            return $files;
        }
        return '';

    }


    /**
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $role = Yii::$app->user->identity->role ;
        if($role == 1  || $role == 3){
            throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
        }
        $model = $this->findModel($id);
        if($role ==  0 && $model->creater !=  Yii::$app->user->identity->id  ) {

            throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
        }
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionDownload($file) {
        $mimetype='application/octet-stream';
        $id = Yii::$app->request->get('id');
        $filename = Yii::getAlias("@backend/web/uploads/orders/".$id) . '/' . $file;
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

    public function actionPrint($id) {

        // $user = User::findModel()->where(['id' => 13]);
        $model = $this->findModel($id);

        $this->layout = 'print';
        $this->view->title = Yii::t('app', 'Print order {0}', $model->num);

        return $this->render('print', [
            'model' => $model,
            // 'user'  => $user
            ]);
    }

    public function actionGetplan(){

        // ajax получение ВП по выбранному пациенту из списка в форме заказа Order
        $uid = (int) Yii::$app->request->post('uid');

        if( $vplans =  Plans::find()->where(['pacient_id'=>$uid,'approved'=>'1','ready'=>'1'])->all() ){
            $res = '';
            foreach($vplans as $item){
                $res .= '<option value="' . $item->id . '">' . $item->version .'</option>';
            }
            echo $res;
        }else{
            echo '0';
        }

        exit;

    }

    public function actionGetmodels(){

        // ajax получение моделей ВП по выбранному пациенту из списка в форме заказа Order
        $vpid = (int) Yii::$app->request->post('id');

        $res =[];
        if( $vp = Plans::find()->where(['id'=>$vpid])->one() ){

            $res['status'] = 1;
            $res['e_vc']=(int)$vp->count_elayners_vc;
            $res['a_vc']=(int)$vp->count_attachment_vc;
            $res['c_vc']=(int)$vp->count_checkpoint_vc;
            $res['r_vc']=(int)$vp->count_reteiners_vc;

            $res['e_nc']=(int)$vp->count_elayners_nc;
            $res['a_nc']=(int)$vp->count_attachment_nc;
            $res['c_nc']=(int)$vp->count_checkpoint_nc;
            $res['r_nc']=(int)$vp->count_reteiners_nc;

            $res['models_count'] = (int)$vp->getModelsCount($vpid);
            $res['tarif_plan'] = $vp->getTarifPlan($vpid);
            echo json_encode($res);
        }else{
            $res['status'] = $vpid;
            echo json_encode($res);
        }

        exit;

    }

    // кол-во заказанных моделей
    public function actionGetordercount(){

        // ajax получение ВП по выбранному пациенту из списка в форме заказа Order
        $id = (int) Yii::$app->request->post('id'); // pacient_id
        /*if( $order = Orders::find()->where(['pacient_code'=>$id,'order_ready'=>'1']) ) {
        }*/
        if ($count = Orders::getReady($id)) {
            echo json_encode(['status' => '1', 'count' => $count]);
            exit;
        }
        echo json_encode(['status'=>'0']);;


        exit;

    }

    // номера заказов для данного пациента
    public function actionGetnum(){

        // ajax получение номеров заказа по выбранному пациенту из списка в форме отчета о затартах
        $id = (int) Yii::$app->request->post('id'); // pacient_id

        if ($orders = Orders::find()->where(['pacient_code'=>$id])->all() ) {
            $_orders = '';
            foreach($orders as $order){
                $_orders .= '<option value="' . $order->num .'">' . $order->num . '</option>';
            }
            echo json_encode(['status' => '1', 'orders' => $_orders]);
            exit;
        }
        echo json_encode(['status'=>'0']);;


        exit;

    }



}
