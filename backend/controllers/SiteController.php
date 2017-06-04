<?php
namespace backend\controllers;

use backend\models\ReportsPacients;
use backend\models\ReportsTable1;
use backend\models\ReportsTable2;
use backend\models\ReportsTable3;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

use yii\helpers\ArrayHelper;

use backend\models\Doctors;
use backend\models\Clinics;
use backend\models\Banners;
use backend\models\User;

// пациенты
use backend\models\Pacients;
// все заказы
use backend\models\Orders;
// все план-графики оплаты
use backend\models\Payments;
// все элементы оплат
use backend\models\PaymentsItems;
// все готовые изделия  - доставки
use backend\models\Delivery;
// все ВП
use backend\models\Plans;
// все отчеты
use backend\models\Reports;
// price
use backend\models\Price;


/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'cabinet', 'loadorder','testemail','test'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    public function actionCabinet($id)
    {
        if (($model = User::findOne($id)) === null) {

            throw new NotFoundHttpException('The requested page does not exist.');
        }



        if ($model->load(Yii::$app->request->post())) {

            $post = Yii::$app->request->post();

            $model->birth = strtotime( date('Y-m-d',strtotime($post['User']['birth'])));

            if( ! $model->save() ){
                print_r($model->getErrors());exit;
            }
        }


        return $this->render('_form', [
            'model' => $model
        ]);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $role = Yii::$app->user->identity->role;
        $clinics = null;
        $clinicArr = [];
        $banners = null;
        if ($role == 1) {
            if (isset($_GET['id_clinic'])&&(int)$_GET['id_clinic']>0) {
                $id = $_GET['id_clinic'];
                //Yii::$app->session['id_clinic'] = $id;

                setcookie('id_clinic',$id,time()+86400);

            }
            /*if (isset($_GET['id_clinic']) && (int)$_GET['id_clinic']>0) {
                $cookies = Yii::$app->response->cookies;

                $cookies->add(new Cookie([
                    'name' => 'id_clinic',
                    'value' => $_GET['id_clinic'],
                    'domain' => 'doctors.ortholiner.kz',
                    'expire' => time() + 86400,
                ]));

            }*/
            //vd(Yii::$app->session['id_clinic']);
            //vd($session->get('id_clinic'));

            //$model = new Doctors();
            $model = new Doctors();
            $clinicsObj = $model->getClinics(false);

            foreach ($clinicsObj as $clinic) {
                $clinicArr[] = $clinic->click_id;
            }

            if (! $clinics = Clinics::find()->select(['id', 'title'])->where(['in', 'id', $clinicArr])->all() ) {
                $clinics = [];
            }
            
            if (! $banners = Banners::find()->select(['id'])->where(['status' => 1])->all() ){
                $banners = [];
            }

            if(isset($id)) return $this->redirect('index');
        }

        //print_r($clinics);//, $clinicsObj ] );

//vd($banners);
        return $this->render('index', [
            'clinics' => $clinics,
            'clinicArr' => $clinicArr,
            'banners' => $banners,
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        
        // вместо cron оповещенеи за 10 дней до следующей доставки
         
        
        
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    public function actionLogout()
    {

        Yii::$app->user->logout();
        // очистка клиники при выходе врача из кабинета
        setcookie('id_clinic','',0, '/', 'doctors.ortholiner.kz');
        setcookie('id_clinic','',0, '/');

        return $this->goHome();
    }

   // общий отчет доступен только для админа
    // формирует csv файл и скачивает его
    public function actionLoadorder(){

        $mimetype='application/octet-stream';

        $filename = Yii::getAlias("@backend/web/uploads/") . 'main_order.csv';
        // создание или перезапись файла
        $f = fopen($filename,'w');

        // заголовок
        $str = '№пп;Дата;Код пациента;Клиника;ФИО Пациента;ФИО Врача;Виртуальный план;;Пакет/измененный пакет;;;;;;;;;;Заказ на изготовление;;;;;;;;;;Оплата;;;;Изготовлено;;;;;;Использование материалов;;;;;;;;;;;Остаток на начало периода;;Остаток на конец периода' ."\r\n";
        $str .= ';;;;;;Дата оплаты;Оплата (тенге);Кол-во моделей;Кол-во элайнеров (кап);Кол-во Chekpoint (кап);Кол-во аттачментов (кап);Кол-во ретейнеров;Всего кап;Пакет;Общая сумма;Cкидка (сумма);Способ оплаты за пакет;Наименование продукта;Дата;Этап ВЧ;Этап НЧ;Кол-во моделей;Кол-во элайнеров (кап);Кол-во Chekpoint (кап);Кол-во аттачментов (кап);Кол-во ретейнеров;Всего кап;Общая задолженность;Подлежит к оплате по заказу;Оплачено по заказу;Дата оплаты;Дата;Кол-во моделей;Кол-во элайнеров (кап);Кол-во Chekpoint (кап);Кол-во аттачментов (кап);Кол-во ретейнеров;МЕД 690;;Support 705;;Erkodur 01;Erkodur 01-frezee;Erkodur 08;Erkodur 06;Коробка;Контейнер;Упаковочный пакет;МЕД 1 690;Support 1 705;МЕД 2 690;Support 2 705'. "\r\n";
        $str .= ';;;;;;;;;;;;;;;;;;(ретейнер, элайнер, бесплатная коррекция, платная коррекция, клинические испытания, капы от бруксизма, замена элайнера);;;;;;;;;;;;;;;;;;;;Затраченное кол-во на 1 модель, гр;Всего затрачено;Затраченное кол-во на 1 модель, гр;Всего затрачено;шт;шт;шт;шт;шт;шт;шт;'. "\r\n";
        // если задано условие по диапазону дат
        $_ids = [];
        // только уникальные значения кодов пациентов
        if ($ids = Orders::find()->select('pacient_code')->distinct()->where(['status'=>'0'])->all()) {
            foreach($ids as $id){
                $_ids[] = $id->pacient_code;
            }
        }
        //print_r($ids);exit;
        if( ! $pacients = Pacients::find()->where(['id'=>$_ids])->all() ) {

            $_res = implode(',', $ids);

            // Нет данных для отчета
            Yii::$app->session->setFlash('error', 'Нет данных для отчета! ' . $_res);

            return $this->redirect('index');
        }

                  // все заказы
       // $orders = Orders::find()->all();
        // все план-графики оплаты
        //$payments = Payments::find()->all();
        // все элементы оплат
        //$pay_items = PaymentsItems::find()->all();
        // все готовые изделия  - доставки
        //$delivery = Delivery::find()->all();
        // все ВП
        //$plans = Plans::find()->all();
        // все отчеты
        //$reports = Reports::find()->all();

        // цена пакета
        // $price = ArrayHelper::map(Price::find()->select('id,price')->asArray()->all(), 'id', 'price');

        $product_type = ['0' => 'Элайнер',
            '1' => 'Платная коррекция элайнера',
            '2' => 'Бесплатная коррекция элайнера',
            '3' => 'Ретейнер',
            '4' => 'Бесплатный ретейнер',
            '5' => 'Клинические исследования'];

        // вариант оплаты
        $payments_var_paid = ['0'=>'Не задано','1'=>'Рассрочка','2'=>'Полная оплата', '3'=>'Бесплатно'];

        $npp=0; // № строки

            foreach ($pacients as $pacient) {
               // $npp++;

                $vp = $pacient->getVP();
                // Виртуальный план
                /*if(!$plan = Plans::find()->where(['pacient_id'=>$pacient->id,'approved'=>'1','ready'=>'1','cancel'=>'0'])->one()){
                    $plan = new Plans();
                }*/

                /* if (!$order = Orders::find()->where(['pacient_code' => $pacient->id, 'status' => '0'])->one()) {
                    // $order = new Orders();
                    continue;
                } */

                // если у пациента есть заказы // у каждого пациента свой код, даже если он второй раз
                //if ( $orders = Orders::find()->where(['pacient_code' => $pacient->id, 'status' => '0'])->all() ) {

                // взять только 1 последний заказ по дате
                // if ($orders = Orders::find()->where(['pacient_code' => $pacient->id])->groupBy('pacient_code')->all()) {

                // по всем заказам пациента
                if ($orders = Orders::find()->where(['pacient_code' => $pacient->id])->all()) {

                    foreach ($orders as $order) {
                        $npp++;
                        $order_type = isset($order->type) ? $product_type[$order->type] : '';

                        // оплата за ВП
                        if ($payments = Payments::find()->where(['pacient_id' => $pacient->id, 'status' => '0'])->one()) {
                            $sum_discount = $payments->sum_discount;
                            $var_paid = $payments->var_paid;
                            $pay = $payments::getTarifAndPaid($pacient->id);


                        } else {
                            $sum_discount = '0';
                            $var_paid = 0;
                        }

                        if ($var_paid == '') $var_paid = 0;

                        $vp_sum = $vp['var'] == 'Бесплатно' ? $vp['var'] : $vp['sum'];

                        /* из пациента */
                        $str .= $npp . ';' . date('d-m-Y', $pacient->date) . ';' . $pacient->id . ';' . $pacient->getClinicTitle() . ';' . $pacient->name . ';' . $pacient->doctor->fullname . ';' .

                        /* из ВП */
                        $vp['date'] . ';' . $vp_sum . ';';

                        if ($plan = Plans::find()->where(['pacient_id' => $pacient->id, 'approved' => '1', 'ready' => '1', 'cancel' => '0'])->one()) {
                            $tarif_plan = $plan->getTarifPlanByPacient($plan->pacient_id);
                            $str .= $plan->getModelsCount($plan->id) . ';' . ((int)$plan->count_elayners_vc + (int)$plan->count_elayners_nc) . ';' .
                                ((int)$plan->count_attachment_vc + (int)$plan->count_attachment_nc) . ';' .
                                ((int)$plan->count_checkpoint_vc + (int)$plan->count_checkpoint_nc) . ';' .
                                ((int)$plan->count_reteiners_vc + (int)$plan->count_reteiners_nc) . ';' .
                                //'всего кап;' . 'общая сумма;' .
                                $plan->getCapCount($plan->id) . ';' .
                                $tarif_plan['name'] . ';' .
                                $tarif_plan['sum'] . ';' .
                                $sum_discount . ';' . $payments_var_paid[$var_paid] . ';' . $order_type . ';';
                        } else {
                            //$str .= 'моделей;эл;ат;чек;рет;кап;общ.сумма;скидка;статус;'; // пустые поля
                            $str .= '-;-;-;-;-;-;-;-;-;'; // пустые поля
                        }
                        /* из заказа */
                        if (isset($order)) { // если записи существуют
                            // ВЧ и НЧ значения в виде 1-5 проставляются в одном поле поэтому конкатенация , если в поле есть значения они будут присоединены остальные пустые
                            // спереди ' ' чтобы excel дату не форматировал из значения 1-5 1.05
                            $str .=
                                $order->date . ';' .
                                ' ' . trim($order->stage_elayners_vc . $order->stage_attachment_vc . $order->stage_checkpoint_vc . $order->stage_reteiners_vc) . ';' .
                                ' ' . trim($order->stage_elayners_nc . $order->stage_attachment_nc . $order->stage_checkpoint_nc . $order->stage_reteiners_nc) . ';' .
                                $order->getModelsCount($order->id) . ';' .
                                ((int)$order->count_elayners_vc + (int)$order->count_elayners_nc) . ';' .
                                ((int)$order->count_attachment_vc + (int)$order->count_attachment_nc) . ';' .
                                ((int)$order->count_checkpoint_vc + (int)$order->count_checkpoint_nc) . ';' .
                                ((int)$order->count_reteiners_vc + (int)$order->count_reteiners_nc) . ';' .
                                /* всего кап = эл+атач+чекп БЕЗ ретейнеров */
                                /*((int)$order->count_elayners_vc + (int)$order->count_elayners_nc +
                                (int)$order->count_attachment_vc + (int)$order->count_attachment_nc +
                                (int)$order->count_checkpoint_vc + (int)$order->count_checkpoint_nc) . ';';*/
                                $order->getCapCount($order->id) . ';';
                            $order_ready = $order->order_ready;

                        } else {
                            $order_ready = 0;
                            // $str .= 'дата;этап вч;этап нч;моделей;эл;ат;чек;рет;кап;'; // пустые поля
                            $str .= '-;-;-;-;-;-;-;-;-;'; // пустые поля
                        }
                        /* оплата нужно рассчитывать */
                        // ? Общая задолженность
                        // + Подлежит к оплате по заказу
                        // + Оплачено по заказу
                        // + Дата оплаты
                        // + Долг по заказу
                        // + Дата планируемого погашения

                        /* массив pay возвращает:
                               sum_price - стоимость пакета по прайсу
                               sum_discount - стоимость с учетом скидки
                               sum_need_by_plan - план
                               sum_paid - факт
                               sum_need_by_plan - подлежит к оплате по заказу
                               sum_paid - оплачено по заказу
                               date_last_paid - дата оплаты
                               debt - долг по заказу
                               date_next_paid - дата планируемого погашения
                       */
                        //$pay['debt'] . ';' // задолженность - убрать

                        $debt = $pay['sum_need_by_plan'] == 'Все оплачено' ? 'Все оплачено' : $pay['debt'];

                        // общая задолженность; подлежит к оплате; оплачено
                        $str .= $pay['sum_need_by_plan'] . ';' . $debt . ';' . /* $pay['sum_paid']*/ $pacient->getDebt() . ';' . date('d-m-Y', strtotime($pay['date_last_paid'])) . ';'; /*. date('d-m-Y', strtotime($pay['date_next_paid'])) . ';';*/

                        /* изготовлено из delivery - Доставка */
                        // нужно в модель delivery добавить дополнительные поля по изделиям vc и nc
                        /*if($delivery = Delivery::find()->where(['pacient_id'=>$pacient->id,'status'=>'0'])->one()) {
                            $str .= $delivery->date_delivery;
                        }else{
                            $str .= '-;-;-;-;-;-;-;-;-;-;'; // пустые поля
                        }*/

                        $delivery_models_count = $order->getModelsCount($order->id); // изготовленных моделей


                        // заказ с завершенными моделями, если текущий $order не завершенный
                        if ($order_ready == 0 && ! $order = Orders::find()->where(['pacient_code' => $pacient->id, 'order_ready' => '1'])->one()) {
                            // готовые заказы по данному пациенту
                            $order = new Orders();
                            $str .= '-;-;' .
                                '-;' .
                                '-;' .
                                '-;' .
                                '-;';

                        }else{
                            $str .= $order->date . ';' . $delivery_models_count . ';' .
                                ((int)$order->count_elayners_vc + (int)$order->count_elayners_nc) . ';' .
                                ((int)$order->count_attachment_vc + (int)$order->count_attachment_nc) . ';' .
                                ((int)$order->count_checkpoint_vc + (int)$order->count_checkpoint_nc) . ';' .
                                ((int)$order->count_reteiners_vc + (int)$order->count_reteiners_nc) . ';';
                        }

                        // затраты на производство

                        // поиск отчета для данного пациента
                        // предполагается что 1 отчет для 1 пациента
                        $report_set = false;


                        // выбрать отчет по пациенту и номеру заказа
                        if ($report_pac = ReportsPacients::find()->with('report')
                                ->where(['pacient_id' => $pacient->id,'order_id'=>$order->num])
                                ->andWhere(['>','report_id','0'])
                                ->one()) {
                            $report_set = true;
                        }else{
                            $report_pac = new ReportsPacients();
                        }

                       // echo 'find report:  pacient=' .$pacient->id. ' order=' . $order->num  . ' report=' . $report_pac->id . ' status=' .(int) $report_set . '<br>';
                       // print_r($report_pac);                         exit;

                        if ($report_set && $report = Reports::find()->where(['id' => $report_pac->report_id, 'report_type' => '1'])->one()) {
                            //  print_r($report);
                            // med - supp - erkodur только по текущемо пациенту
                            if ($tab2 = ReportsTable2::find()->where(['report_id' => $report->id, 'pacient_id' => $pacient->id])->orderBy('material_id ASC')->all()) {
                                foreach ($tab2 as $tab) { // erkodur 0.1 - 0.6 ...
                                    // первые 4 колонки для med и support
                                    if ( $tab->material_id == 0 ) continue ; // пропустить id пациента
                                    if ($tab->material_id == 1 || $tab->material_id == 2) {
                                        $str .= ' ' . $tab->value . ';';
                                        $str .= ' '. $delivery_models_count * $tab->value . ';'; //* $tab->value .';'; // > 0 ? $tab->value / $delivery_models_count : 0;
                                       // $str .= ';';
                                    } else {
                                        $str .= ' ' . $tab->value . ';';
                                    }
                                }
                            } else {
                                // $str .= 'med;sup;med2;sup2;er;er;er;er;кор;конт;уп;'; // пустые поля
                                $str .= '-;-;-;-;-;-;-;-;-;-;-;'; // пустые поля
                            }
                            // table 1 остаток на начало и конец периода
                            if ($tab1 = ReportsTable1::find()->where(['report_id' => $report->id])->all()) {
                                foreach ($tab1 as $tab) { // begin, end
                                    $str .= $tab->begin . ';' . $tab->end . ';';
                                }
                            } else {
                                $str .= '-;-;-;-;'; // пустые поля
                            }

                        } else {
                            // $str .= 'med;sup;med2;sup2;er;er;er;er;кор;конт;уп;beg;end;beg2;end2;'; // пустые поля
                            $str .= '-;-;-;-;-;-;-;-;-;-;-;-;-;-;-;'; // пустые поля
                        }

                        //  exit;
                        /* остатки */
                        /*if( $ostat = Reports::find()->where(['like','pacients',$pacient->id])->andWhere(['status'=>'0', 'type'=>'2'])->one() ){
                            // table 3 остатки
                            if( $tab3 = ReportsTable3::find()->where(['report_id'=>$ostat->id])->all() ){
                                $str .= 'x;x;x;x;';
                            }else{
                                $str .= '-;-;-;-;'; // пустые поля
                            }

                        }else{
                            $str .= '-;-;-;-;'; // пустые поля
                        }*/

                        $str .= "\r\n";

                    } // each по всем заказам orders пациента

                } // если есть заказы orders по пациенту

            } // each pacients

           // echo $str;
        // exit;

            // преобразовать данные в кодировку windows-1251
            $str = iconv('UTF-8', 'windows-1251', $str);
            fwrite($f, $str);
            fclose($f);

            if (!file_exists($filename)) {
                echo 'Файл не найден: ' . $filename;
                exit;
            }

            header('HTTP/1.1 200 Ok');

            $etag = md5($filename);
            $etag = substr($etag, 0, 8) . '-' . substr($etag, 8, 7) . '-' . substr($etag, 15, 8);
            header('ETag: "' . $etag . '"');

            header('Accept-Ranges: bytes');
            header('Content-Length: ' . (filesize($filename)));

            header('Connection: close');
            header('Content-Type: ' . $mimetype);
            header('Last-Modified: ' . gmdate('r', filemtime($filename)));
            header('Content-Disposition: attachment; filename="Отчет.csv";'); // имя отчета
            echo file_get_contents($filename);

            exit;




    } // loadorder


    public function actionSorting(){
        //declaring the sort object
        $sort = new Sort([
            'attributes' => [
                'user',
                'id',
                'level'
            ],
        ]);
        //retrieving all users
        $models = MyUser::find()
            ->orderBy($sort->orders)
            ->all();
        return $this->render('sorting', [
            'models' => $models,
            'sort' => $sort,
        ]);
    }


    public function actionTest(){

        exit; // dec.2016

        // заполнение данными таблицы report_pacients
        //$rep_pac = ReportsPacients::find()->all();
        $reports = Reports::find()->with('items')->all();

        echo '<pre>';
        print_r($reports);


        foreach ($reports as $rep){

            $pac = json_decode($rep->pacients);
            $ord = json_decode($rep->order_num);
            $i=0;
            foreach ($rep->items as $item) {

                if($item->pacient_id == $pac[$i] ) {
                    $item->order_id = $ord[$i];
                    echo $rep->id . ' '.  $item->pacient_id . ' ' . $item->order_id . '<br>';
                    $item->save();
                }
                $i++;
            }
        }


        exit;
        
        

    }


    public  function actionTestemail(){
        $msg = "Здравствуйте, Уважаемый(-ая) Сарбупин Нурлан
Вы успешно прошли регистрацию в системе 
Доступ для начала работы в системе:";

        if ( !Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo('trubnikov.r@list.ru')
                ->setSubject('Тестовое письмо на Ortholiner')
                ->setHtmlBody($msg)
                ->send()
        ) {
            echo 'Ошибка отправки сообщения на почту: trubnikov.r@list.ru';
        }else{
            echo 'send OK';
        }
        exit;

    }

}
