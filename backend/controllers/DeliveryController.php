<?php

namespace backend\controllers;

use app\rbac\Order;
use backend\models\PaymentsItems;
use Yii;
use backend\models\User;
use backend\models\Assign;
use backend\models\Alerts;
use backend\models\Orders;
use backend\models\Pacients;
use backend\models\Payments;
use backend\models\Delivery;
use backend\models\DeliverySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;


/**
 * DeliveryController implements the CRUD actions for Delivery model.
 */
class DeliveryController extends Controller
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
     * Lists all Delivery models.
     * @return mixed
     */
    public function actionIndex()
    {

        $role = Yii::$app->user->identity->role;
        $user_id = Yii::$app->user->id;
        $searchModel = new DeliverySearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if($role==0) {
            // показать только свои заказы
            $dataProvider = new ActiveDataProvider([
                'query' => Delivery::find()
                    ->where(['tehnik_id' => $user_id])
                //->orderBy('date DESC'),
                /*'pagination' => [
                    'pageSize' => 20,
                    ],*/
            ]);
        }else{
            $dataProvider = new ActiveDataProvider([
                'query' => Delivery::find()
            ]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Delivery model.
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
     * Creates a new Delivery model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Delivery();
        $user_id = Yii::$app->user->id;
        
        // получение id или объектов (2-параметр) всех пациентов, назначенныx данному технику для 5-уровня
        $pacients_ids = Assign::getPacientsByDoctorId($user_id);
        //print_r($pacients_ids) ;
        // получение всех завершенных заказов order_ready=1 для пациентов
        $pacients = [];
        if( $_orders = Orders::find()->where(['pacient_code'=>$pacients_ids,'order_ready'=>'1'])->all() ) {

            $pacients_ids = []; // очистка от пред. данных
            foreach ($_orders as $order) {
                $pacients_ids[] = $order->pacient_code;
            }

            // print_r($pacients_ids) ; exit;
            // выбор пациентов с завершенными заказами
            if ( ! $pacients = Pacients::find()->select('id,name')->where(['id' => $pacients_ids])->asArray()->all()) {
                $pacients = [];
            }
        }

        if ($model->load(Yii::$app->request->post()) ){

            $model->tehnik_id = $user_id; // техник, создавший доставку


            //echo $model->pacient_id . ' ' . $model->order_id; exit;
            //echo '<pre>';
            //echo $model->pacient_id;
           //print_r([$pacients_ids,$_orders]);

            // поиск номера заказа по id пациента
            foreach($_orders as $order){
                if( $order->pacient_code == $model->pacient_id){
                    $model->order_id = $order->id;
                    break;
                }
            }
            // $pacients_ids[]

            //echo  $order->pacient_code . ' ' . $model->order_id ;exit;

            if( ! $model->save() ){
                print_r($model->getErrors());
            }

            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'orders' => $_orders,
                'pacients' => $pacients,
                
            ]);
        }
    }

    /**
     * Updates an existing Delivery model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        $user_id = Yii::$app->user->id;

        // получение id или объектов (2-параметр) всех пациентов, назначенныx данному технику для 5-уровня
        $pacients_ids = Assign::getPacientsByDoctorId($user_id);
         //print_r($pacients_ids) ;
        // получение всех завершенных заказов order_ready=1 для пациентов
        $pacients = [];
        if( $_orders = Orders::find()->where(['pacient_code'=>$pacients_ids,'order_ready'=>'1'])->all() ) {

            $pacients_ids = []; // очистка от пред. данных
            foreach ($_orders as $order) {
                $pacients_ids[] = $order->pacient_code;
            }

            // print_r($pacients_ids) ; exit;
            // выбор пациентов с завершенными заказами
            if ( ! $pacients = Pacients::find()->select('id,name')->where(['id' => $pacients_ids])->asArray()->all()) {
                $pacients = [];
            }
        }


        if ($model->load($post) ){

            $model->date_delivery = date('Y-m-d',strtotime($post['Delivery']['date_delivery'])) ;

            // поиск номера заказа по id пациента
            foreach($_orders as $order){
                if( in_array($order->pacient_code,$pacients_ids)){
                    $model->order_id = $order->id;
                    break;
                }
            }

            // поиск виртуального плана для получения кол-ва заказанных моделей
            // $plans = Plans::find()->where(['pacient_id'=>$model->pacient_id,'order_id'=>$model->order_id])->one();

            if( ! $order = Orders::findOne($model->order_id) ){
                $order = new Orders();
            }
                $alert = new Alerts();
                $alert->doctor_id_from = $user_id; // от техника текущего уровня 5
                $alert->doctor_id_to = $order->doctor_id; // врачу заказа
                $alert->date = time();
                $alert->type=0;

            if( ! $pacient = Pacients::find()->where(['id'=>$model->pacient_id])->one() ) {
                $pacient = new Pacients();
            }

            $msg = 'Здравствуйте, Уважаемый(-ая) '. $pacient->doctor->fullname .'!<br>Ваш заказ для пациента '. $pacient->name . ' готов.<br>Наш курьер свяжется с Вами в ближайшее время для уточнения времени доставки.<br><br>С уважением, ORTHOLINER.';

            // если кол-во по заказу соответствует кол-ву произведенных моделей
            if($model->delivery_ready == $model->delivery_all){
                $model->status = 1; // доставка завершена
                // установка статуса 1 в план графике о завершении заказа на заданную дату
                if( $pay = Payments::find()->where(['pacient_id'=>$model->pacient_id,'order_id'=>$model->order_id,'status'=>'0'])->one() ) {

                    if($pay_items = PaymentsItems::find()->where(['payment_id'=>$pay->id])->all()) {

                        foreach ($pay_items as $item) {
                            //echo $item->date .'='. $model->date_delivery . '<br>' ;
                            if ($item->date == $model->date_delivery) {
                                // дата оплаты и дата заказа должны соответствовать и берутся из план-графика payments
                                // и сами даты оплаты из paymentItems
                                $item->status = 1; // изготовление завершено
                                $item->save();
                                break; // 1 шт
                            }
                        }
                    }
                }

                // проверка кол-ва изготовленных изделий по плану
                $pay = Payments::getPaymentReadyItems($model->order_id,$model->pacient_id);

                if( $pay['ready'] == $pay['all'] && $pay['all'] !=0  ) {
                    // по плану изготовлены все модели
                    // поиск всех заказов (связанных) по пациенту и статусу 0 (не завершен) для установки флага статуса 1 - заказ завершен
                    if ($orders = Order::find()->where(['pacient_id' => $model->pacient_id, 'status' => '0'])->all()) {
                        foreach($orders as $item){
                            $item->status = 1;
                            $item->save();
                        }
                    }
                }
                $send_email = true;

                if($send_email) {
                    // email врача
                    if ($user = User::findOne($order->doctor_id)) {

                        if ($user->email != '' && !Yii::$app->mailer->compose()
                                ->setFrom(Yii::$app->params['adminEmail'])
                                ->setTo($user->email)
                                ->setSubject('Доставка заказа на Ortholiner')
                                ->setHtmlBody($msg)
                                ->send()
                        ) {
                            echo 'Ошибка отправки сообщения на почту: ' . $user->email;
                        }
                    }
                }

                if ($admins = User::getAdmins()) {

                    foreach ($admins as $admin) {
                        // email должен существовать
                        if ($admin->email != '' && !Yii::$app->mailer->compose()
                                ->setFrom(Yii::$app->params['adminEmail'])
                                ->setTo($admin->email)
                                ->setSubject('Доставка заказа на Ortholiner')
                                ->setHtmlBody($msg)
                                ->send()
                        ) {
                            echo '<meta charset="UTF-8">';
                            echo 'Ошибка отправки сообщения на почту: ' . $admin->email;
                        }
                    }
                }

            }elseif( $pacient->var_paid == 2 ){ // если оплата частями и еще не все заказы выполнены по план графику

                // $alert->text = 'По заказу № ' .$order->num . ' изготовлено ' . $model->delivery_ready . ' изделий из ' . $model->delivery_all ;
                // план график
                if( $pay = Payments::find()->where(['pacient_id'=>$model->pacient_id,'order_id'=>$model->order_id])->one() ) {

                    // даты в план графике
                    if ($pay_items = PaymentsItems::find()->where(['payment_id' => $pay->id])->orderBy('date ASC')->all()) {

                        $date_delivery = strtotime($model->date_delivery);
                        $pay_date = 0;
                        // нужно найти следующую дату заказа (от текущей)
                        foreach ($pay_items as $item) {
                            $pay_date = sttotime($item->date);
                            if (sttotime($item->date) > $date_delivery) break;
                        }

                       // $msg = 'Следующий заказ через 10 дней.';
                        $msg .= '<br>Не забудьте сделать оплату согласно графику погашения. В случае отсутствия оплаты заказ доставлен не будет.<br>Если у Вас возникли вопросы, свяжитесь с администратором по почте admin_ortholiner@smartforce.kz<br>C уважением, Зарина Кидиралиева';

                        // напоминание о заказе
                        $alert_2 = new Alerts();
                        $alert_2->doctor_id_from = $user_id; // от техника текущего уровня 5
                        $alert_2->doctor_id_to = $order->doctor_id; // врачу заказа
                        $alert_2->text = $msg;
                        $alert_2->type = 1;
                        $alert_2->date = $pay_date - 10 * 86400; // дата за 10 дней до заказа
                        $alert_2->save();

                        // на момент оплаты до следующей даты может быть больше 10 дней, поэтому сообщение на почту не отправляется
                       // отправка по email после вызова функции sendReminders в AlertsController

                    }
                }

            }

            //$alert->text = 'По заказу № ' .$order->num . ' изготовлены все модели (' . $model->delivery_all . ' шт.)' ;
            // $alert->text = $model->date_delivery . ' будет доставка заказа для пациента ' . $pacient->name . '.';
            if( $pacient->var_paid == 2 ) { // вариант оплаты полная=2, частичная=1, бесплатная=0
                $alert->text = $msg;
            }
            $alert->save();

   

            if( ! $model->save()) {
                echo 'err save delivery'; exit;
            }
            return $this->redirect(['update', 'id' => $model->id]);

        } else {
            return $this->render('update', [
                'model' => $model,
                'orders' => $_orders,
                'pacients' => $pacients,

            ]);
        }
    }

    /**
     * Deletes an existing Delivery model.
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
     * Finds the Delivery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Delivery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Delivery::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
