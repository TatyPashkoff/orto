<?php

namespace backend\controllers;

use backend\models\Orders;
use Yii;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

use backend\models\Assign;
use backend\models\Reports;

use backend\models\ReportsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use backend\models\User;
use backend\models\Payments;
use backend\models\Pacients;
use backend\models\Materials;
use backend\models\ReportsMaterials;
use backend\models\ReportsPacients;

use backend\models\ReportsTable1;
use backend\models\ReportsTable2;
use backend\models\ReportsTable3;
/**
 * ReportsController implements the CRUD actions for Reports model.
 */
class ReportsController extends Controller
{
    /**
     * @inheritdoc
     */
   /* public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                   'delete' => ['POST'],
                ],
            ],
        ];
    }*/

    /**
     * Lists all Reports models.
     * @return mixed
     */
    public function actionIndex()
    {
        //if( Yii::$app->user->identity->role == 4) {
            $searchModel = new ReportsSearch();

            $page = Yii::$app->request->get('report-page');

            $query = Reports::find();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);


            if(isset($page)) {
                if ($page == '2') {
                    $query->where(['report_type'=>'2']);
                } else {
                    $query->where(['report_type'=>'1']);
                    $page = 1;
                }
            }else{
                $query->where(['report_type'=>'1']);
                $page = 1;
            }


            // $dataProvider = $searchModel->search($params);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'page' => $page,
            ]);
       // }


    }

    /**
     * Displays a single Reports model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        //if(Yii::$app->user->identity->role == 4) {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        //}
    }

    /**
     * Creates a new Reports model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        //if(Yii::$app->user->identity->role == 4) {
            $model = new Reports();
            $post = Yii::$app->request->post();

            $user_id = Yii::$app->user->id;

        // страница 1 или 2  заказ или остатки
            if( ! $page = (int)Yii::$app->request->get('report-page') ){
                if(isset($post['report-page'])) $page = (int)$post['report-page'];
            }
            if((int)$page==0) $page=1;

        $_order = [];
        $ostat_med = 0;
        $ostat_sup = 0;
        // остатки на начало периода
        if( $report_ostat = ReportsTable1::find()->orderBy('id DESC')->limit(2)->all() ){
            foreach($report_ostat as $ost) {
                if($ost->material_id==1) {
                    $ostat_med = $ost->end;
                }elseif($ost->material_id==2) {
                    $ostat_sup = $ost->end;
                }
            }
        }


            $pacients= [];
            if ($model->load($post)) {

                // echo '<pre>';                print_r($post);exit;

               if( !$model->save() ){
                   echo 'error save report. ';
                   print_r($model->getErrors());
               }


                $model->report_type = $page;
                $model->date = date('Y-m-d',strtotime($post['Reports']['date'])) ;

                $model->doctor_id = $user_id; // пользователь создавший отчет, может быть и админ

                if($page==1) {
                    if(isset($post['pacients'])) {
                        
                        foreach ($post['pacients'] as $i => $pac) {
                            if ($pac == -1) unset($post['pacients'][$i]); // удалить если выбрано Укажите пациента
                        }
                        
                        // копия всех пациентов в отчете
                        $model->pacients = json_encode($post['pacients']);

                        // копия всех № заказов в отчете
                        $model->order_num = json_encode($post['orders']);



                        $pid = 0;
                        // запись всех пациентов для отчета                        
                        foreach($post['pacients'] as $_pacient_id){
                            $pac_rep = new ReportsPacients() ;
                            $pac_rep->report_id = $model->id;
                            $pac_rep->pacient_id = $_pacient_id;
                            $pac_rep->order_id = $post['orders'][$pid]; // dec 2016
                            $pac_rep->save();
                            $pid++;
                        }
                        /*echo '<pre>';
                        print_r($post);
                        exit;*/
                    }

                    // с проверкой на оплату
                    $pacients_ids = Assign::getPacientsByDoctorId( Yii::$app->user->id); // пациенты только для текущего техника

                    $_paid = ['date'=>'0','sum'=>'0'];
                    foreach($pacients_ids as $k => $pid) {
                        // последняя подтв. дата оплаты
                        $paid = Payments::getLastPaid( $pid );// $model->pacient->id); // массив дата-date и сумма - sum
                        if($paid['date']=='0') {
                            unset($pacients_ids[$k]); // удалить пациента из списка, т.к. не подтв. оплата
                            //}elseif( $model->pacient->id==$id){ // при создании не нужно проверять объекта еще нет
                            //    $_paid = $paid; // данные текущего пациента (дата и сумма)
                        }
                    }

                    // имена пациентов
                    $pacients = ArrayHelper::map(Pacients::find()->where(['id'=>$pacients_ids])->all(), 'id', function ($model, $defaultValue) {
                        return $model->name;
                    });

                    // только свои заказы !!!
                    $order_ids = $post['orders'];

                    // if( $orders = \backend\models\Orders::find()->where(['pacient_code'=>$pacients_ids,'creater'=>$user_id,'status'=>'0'])->orderBy('pacient_code')->all()  ) {
                    if( $orders = \backend\models\Orders::find()->where(['num'=>$order_ids,'creater'=>$user_id,'status'=>'0'])->orderBy('pacient_code')->all()  ) {
                        $_order = $this->getOrderData($orders);
                    }

                }
                $post = Yii::$app->request->post();
                $model->date = date('Y-m-d',strtotime($post['Reports']['date'])) ;
                if($model->save()){

                    $id = $model->id;
                    if(isset($post['Materials']['table1'])) {
                        foreach ($post['Materials']['table1']['material_id'] as $key => $table1) {
                            $table1 = new ReportsTable1();
                            $table1->report_id = $model->id;
                            //  $table1->pacient_id  = $post['Materials']['table1']['pacient_id'][$key];
                            $table1->material_id = $post['Materials']['table1']['material_id'][$key];
                            $table1->total = $post['Materials']['table1']['total'][$key];
                            $table1->model = $post['Materials']['table1']['model'][$key];
                            $table1->norm = $post['Materials']['table1']['norm'][$key];
                            $table1->begin = $post['Materials']['table1']['begin'][$key];
                            $table1->end = $post['Materials']['table1']['end'][$key];
                            $table1->dop = $post['Materials']['table1']['dop'][$key];
                            if (!$table1->save()) print_r($table1->errors);
                        }
                    }
                    if(isset($post['Materials']['table2'])) {
                        // удалить из таблицы 2 данные текущего отчета и записать новые
                        ReportsTable2::deleteAll(['report_id' => $id]);
                        $row = 0;
                        foreach ($post['Materials']['table2'] as $_table) { // по строкам
                            $row++;
                            $k = 0;
                            foreach ($_table as $key => $value) { // по материалам 0-9 0-пациент 1-9-материалы
                                $k++;
                                if ( $k > 10 ||($k == 1 && (int)$value == 0) ) break; // пропуск пустого ряда, если не задан id пациент
                                if ($k == 1) $pacient_id = $value; // id пациента

                                $table2 = new ReportsTable2();
                                $table2->report_id = $id;
                                $table2->material_id = (int)$key; // material_id = 0 - пациенты
                                $table2->value = $value;  // value[0] - id пациента
                                $table2->pacient_id = $pacient_id;
                                $table2->row_id = $row;

                                if (!$table2->save()) {
                                    print_r($table2->getErrors());
                                }
                            }
                        }
                    }

                      //if(isset($post['Materials']['table3'])) {
                    if($page==2){
                        $table3 = new ReportsTable3();
                        $table3->report_id = $id;
                        $table3->data = json_encode($post['Table']);
                        $table3->save();
                    }
                }
                return $this->redirect(['update', 'id' => $model->id,'report-page'=>$page]);
            } else {
                $model->report_type = $page;
                $model->date = date('Y-m-d', time());
                $doctors = User::getDoctors();
                //$pacients = Pacients::getList();
                $materials = Materials::find()->all();
               // $reporstsMaterials = ReportsMaterials::find()->all();

                // с проверкой на оплату
                $pacients_ids = Assign::getPacientsByDoctorId( Yii::$app->user->id); // пациенты только для текущего техника

                $_paid = ['date'=>'0','sum'=>'0'];
                foreach($pacients_ids as $k => $pid) {
                    // последняя подтв. дата оплаты
                    $paid = Payments::getLastPaid( $pid );// $model->pacient->id); // массив дата-date и сумма - sum
                    if($paid['date']=='0') {
                        unset($pacients_ids[$k]); // удалить пациента из списка, т.к. не подтв. оплата
                        //}elseif( $model->pacient->id==$id){ // при создании не нужно проверять объекта еще нет
                        //    $_paid = $paid; // данные текущего пациента (дата и сумма)
                    }
                }

                // 23.11 получить всех пациентов у которых есть заказы - order
                if( $order_pacients = Orders::find()->select('pacient_code')->where(['pacient_code'=>$pacients_ids])->all() ){
                    $pacients_ids = []; // очистка для добавления только тех, у которых есть заказ
                    foreach($order_pacients as $order_pacient){
                        $pacients_ids[] = $order_pacient->pacient_code;
                    }

                }

                // имена пациентов
                $pacients = ArrayHelper::map(Pacients::find()->where(['id'=>$pacients_ids])->all(), 'id', function ($model, $defaultValue) {
                    return $model->name;
                });

                /* if(isset($model->pacients)){
                    $pacients_selected = json_decode($model->pacients);
                }else{*/
                    $pacients_selected = [0];

               // }


                return $this->render('create', [
                    'model' => $model,
                    'pacients' => $pacients,
                    'pacients_selected' => $pacients_selected,
                    'doctors' => $doctors,
                    'materials'  => $materials,
                   // 'reporstsMaterials' => $reporstsMaterials,
                    'page' => $page,
                    'order' => $_order,
                    'ostat' => ['med'=>$ostat_med,'sup'=>$ostat_sup],
                ]);
            }
       // }
    }

    public function actionUpdate($id)
    {
        //if(Yii::$app->user->identity->role == 4) {
            $model = $this->findModel($id);
            $post = Yii::$app->request->post();

        $role = Yii::$app->user->identity->role;
        // если админ или мед.дир взять id техника, который создал отчет
        if($role==2 || $role==4){
            $user_id = $model->doctor_id;
        }else {
            $user_id = Yii::$app->user->id;
        }

        $ostat_med = 0;
        $ostat_sup = 0;
        // остатки на начало периода, из предпоследнего отчета затрат, нужно учитывать, что последний это текущий report_id=id
        if( $report_ostat = ReportsTable1::find()->where(['!=','report_id',$id])->orderBy('id DESC')->limit(2)->all() ){
            foreach($report_ostat as $ost) {
                if($ost->material_id==1) {
                    $ostat_med = $ost->end;
                }elseif($ost->material_id==2) {
                    $ostat_sup = $ost->end;
                }
            }
        }


        $pacients= [];
        $pacients_selected = [];
        $order_ids = [];
        $_order = [];
        // страница 1 или 2  заказ или остатки
        if( ! $page = (int)Yii::$app->request->get('report-page') ){
            if(isset($post['report-page'])) $page = (int)$post['report-page'];
        }
        if((int)$page==0) $page=1;




        if ($model->load($post)) {

            $model->report_type = $page;
            // echo '<pre>';
            // echo json_encode($post['pacients']);
            $model->date = date('Y-m-d',strtotime($post['Reports']['date'])) ;

            if($page==1) {
                if(isset($post['pacients'])) {
                    foreach ($post['pacients'] as $i => $pac) {
                        if ($pac == -1) unset($post['pacients'][$i]); // удалить если выбрано Укажите пациента
                    }

                    // нужно обновить массив в бд
                    $model->pacients = json_encode($post['pacients']);
                    if(isset($post['orders'])) $model->order_num = json_encode($post['orders']);

                    // удалить всех ранее назначенных пациентов и добавить новых
                    ReportsPacients::deleteAll(['report_id'=>$model->id]);

                    foreach($post['pacients'] as $_pacient_id){
                        $pac_rep = new ReportsPacients() ;
                        $pac_rep->report_id = $model->id;
                        $pac_rep->pacient_id = $_pacient_id;
                        $pac_rep->save();
                    }

                }
            }

            if(isset($post['Materials']['table1'])) {
                foreach ($post['Materials']['table1']['material_id'] as $key => $table1) {
                    if ($post['Materials']['table1']['material_id'][$key] == 0) continue;
                    //если поле сущ. взять из бд
                    if (isset($post['Materials']['table1']['id'][$key])) {
                        $table1 = ReportsTable1::find()->where(['id' => $post['Materials']['table1']['id'][$key]])->one();
                    } else {
                        $table1 = new ReportsTable1();
                    }
                    $table1->report_id = $id;
                    // $table1->pacient_id = $post['Materials']['table1']['pacient_id'][$key];
                    $table1->material_id = $post['Materials']['table1']['material_id'][$key];
                    $table1->total = $post['Materials']['table1']['total'][$key];
                    $table1->model = $post['Materials']['table1']['model'][$key];
                    $table1->norm = $post['Materials']['table1']['norm'][$key];
                    $table1->begin = $post['Materials']['table1']['begin'][$key];
                    $table1->end = $post['Materials']['table1']['end'][$key];
                    $table1->dop = $post['Materials']['table1']['dop'][$key];

                    if (!$table1->save()) {
                        echo 'err save tab1';
                        print_r($table1->getErrors());
                        exit;
                    }
                }
            }


            //print_r($post['Materials']['table2']);exit;

            if(isset($post['Materials']['table2'])) {
                // удалить из таблицы 2 данные текущего отчета и записать новые
                ReportsTable2::deleteAll(['report_id' => $id]);
                $row = 0;
                foreach ($post['Materials']['table2'] as $_table) { // по строкам
                    $row++;
                    $k = 0;
                    foreach ($_table as $key => $value) { // по материалам 0-9 0-пациент 1-9-материалы
                        $k++;
                        if ($key>9 || ($k == 1 && (int)$value == 0) ) break; // пропуск пустого ряда, если не задан пациент
                        if ($k == 1) $pacient_id = $value; // id пациента

                        $table2 = new ReportsTable2();
                        $table2->report_id = $id;
                        $table2->material_id = (int)$key; // material_id = 0 - пациенты
                        $table2->value = $value;  // value[0] - id пациента
                        $table2->pacient_id = $pacient_id;
                        $table2->row_id = $row;

                        if (!$table2->save()) {
                            print_r($table2->getErrors());
                        }
                    }
                }
            }


            if($page==2){ // остатки

                $table3 = ReportsTable3::find()->where(['report_id' => $id])->one();


               // print_r($post['Table']); exit;
                $table3->data = json_encode($post['Table']);

                $table3->save();

            }
            if (!$model->save()) {
                echo 'no saved   ';

                print_r($model->getErrors());
                exit;
            }
        } // load

        $doctors = User::getDoctors();

        // $pacients = Pacients::getList(); // пациенты только для текущего техника

        // echo '<pre>';
        //print_r($pacients);

        if($page==1) {
            // id вех пациентов для данного техника из таблицы assign

            // с проверкой на оплату
            $pacients_ids = Assign::getPacientsByDoctorId( Yii::$app->user->id); // пациенты только для текущего техника

            $_paid = ['date'=>'0','sum'=>'0'];
            foreach($pacients_ids as $k => $pid) {
                // последняя подтв. дата оплаты
                $paid = Payments::getLastPaid( $pid );// $model->pacient->id); // массив дата-date и сумма - sum
                if($paid['date']=='0') {
                    unset($pacients_ids[$k]); // удалить пациента из списка, т.к. не подтв. оплата
                    //}elseif( $model->pacient->id==$id){ // при создании не нужно проверять объекта еще нет
                    //    $_paid = $paid; // данные текущего пациента (дата и сумма)
                }
            }

            // 23.11 получить всех пациентов у которых есть заказы - order
            if( $order_pacients = Orders::find()->select('pacient_code')->where(['pacient_code'=>$pacients_ids,'creater'=>$user_id,'status'=>'0'])->all() ){
                $pacients_ids = []; // очистка для добавления только тех, у которых есть заказ
                foreach($order_pacients as $order_pacient){
                    $pacients_ids[] = $order_pacient->pacient_code;
                }

            }

            // имена пациентов
            $pacients = ArrayHelper::map(Pacients::find()->where(['id' => $pacients_ids])->all(), 'id', function ($model, $defaultValue) {
                return $model->name;
            });

            
            if (isset($model->pacients)) {
                $pacients_selected = json_decode($model->pacients);
                // список доступных заказов
                $order_ids = json_decode($model->order_num);
                // оставить только выбранных пациентов

                foreach($pacients_ids as $k=>$p){
                    if( !in_array($p,$pacients_selected) ){
                        unset($pacients_ids[$k]);
                    }
                }

                // print_r( [$pacients_selected , $pacients_ids]);exit;

                // if( $orders = \backend\models\Orders::find()->where(['pacient_code'=>$pacients_ids,'creater'=>$user_id,'status'=>'0'])->orderBy('pacient_code')->all()  ) {
                if( $orders = \backend\models\Orders::find()->where(['num'=>$order_ids,'creater'=>$user_id,'status'=>'0'])->orderBy('pacient_code')->all()  ) {
                    $_order = $this->getOrderData($orders);
                }


            } else {
                $pacients_selected = [0];
            }

            //print_r($_order);exit;

        }

        $materials = Materials::find()->all();
       // $reporstsMaterials = ReportsMaterials::find(['report_id' => $id])->all();

        $table1 = ReportsTable1::find()->where(['report_id' => $id])->all();
        $table2 = ReportsTable2::find()->where(['report_id' => $id])->orderBy('pacient_id, row_id, material_id')->all();
        $table3 = ReportsTable3::find()->where(['report_id' => $id])->one();
        return $this->render('update', [
            'model' => $model,
            'pacients' => $pacients,
            'pacients_selected' => $pacients_selected,
            'order_ids' => $order_ids,
            'doctors' => $doctors,
            'materials'  => $materials,
           // 'reporstsMaterials' => $reporstsMaterials,
            'table1' => $table1,
            'table2' => $table2,
            'table3' => $table3,
            'page' => $page,
            'order' => $_order,
            'ostat' => ['med'=>$ostat_med,'sup'=>$ostat_sup],

        ]);
       // }
    }
    
    public function actionPrint($id,$page=0)
    {

        if($page==0) return false;

        $model = $this->findModel($id);
        $role = Yii::$app->user->identity->role;
        // если админ или мед.дир взять id техника, который создал отчет
        if($role==2 || $role==4){
            $user_id = $model->doctor_id;
        }else {
            $user_id = Yii::$app->user->id;
        }
        $this->layout = 'print';
        
        //$model->fileList = $this->getFileList($model->id);


        // получение списка всех пацеинтов, выбранных в отчете для таблиц расходов t1-t3

        $table1 = ReportsTable1::find()->where(['report_id' => $id])->orderBy('pacient_id')->all();
        $table2 = ReportsTable2::find()->where(['report_id' => $id])->orderBy('pacient_id,material_id')->all();
        $table3 = ReportsTable3::find()->where(['report_id' => $id])->orderBy('material_id')->one();

        $this->view->title = Yii::t('app', 'Печать отчета {0}', $model->id);

        //$_order = [];
        //$_order['count_models'] = 0;
        /* $_order['count_elayners'] = 0;
        $_order['count_attachment'] = 0;
        $_order['count_checkpoint'] = 0;
        $_order['count_reteiners'] = 0;
        $_order['models_count_vc'] = 0;
        $_order['models_count_nc'] = 0;
        $_order['models_count_all'] = 0;
        $_order['type'] = '';*/

        /*
         *
         * id вех пациентов для данного техника из таблицы assign
        // без проверки на оплату ???
        $pacients_ids = Assign::getPacientsByDoctorId( Yii::$app->user->id ); // пациенты только для текущего техника
        // имена пациентов
        $pacients = ArrayHelper::map(Pacients::find()->where(['id'=>$pacients_ids])->all(), 'id', function ($model, $defaultValue) {
            return $model->name;
        });
        */

        // $pacients = \backend\models\Pacients::find(['id' => $pacient_ids])->all();
        // pacient-id = pacient-code // одно и то же
        /* foreach($pacients as $pac){
            $pacient_codes[] = $pac->code;
        }*/
        $pacients_ids = json_decode($model->pacients);
        $pacients = ArrayHelper::map(Pacients::find()->where(['id'=>$pacients_ids])->all(), 'id', function ($model, $defaultValue) {
            return $model->name;
        });        // найти все заказы (незавершенные - status=0) для выбранных пациентов

        $_order = [];


        // список доступных заказов
        $order_ids = json_decode($model->order_num);

        // print_r( [$pacients_selected , $pacients_ids]);exit;

        // if( $orders = \backend\models\Orders::find()->where(['pacient_code'=>$pacients_ids,'creater'=>$user_id,'status'=>'0'])->orderBy('pacient_code')->all()  ) {
        if( $orders = \backend\models\Orders::find()->where(['num'=>$order_ids,'creater'=>$user_id,'status'=>'0'])->orderBy('pacient_code')->all()  ) {
            $_order = $this->getOrderData($orders);
        }

        /* // только свои заказы !!!
        if( $orders = \backend\models\Orders::find()->where(['pacient_code'=>$pacients_ids,'creater'=>$user_id,'status'=>'0'])->orderBy('pacient_code')->all()  ) {
            $_order = $this->getOrderData($orders);
        } */


        $materials = Materials::find()->all();
        //$reporstsMaterials = ReportsMaterials::find(['report_id' => $id])->all();
  

        return  $this->render('print', [
            'model' => $model,
            'order' => $_order,
            'pacients' => $pacients,
            //'reporstsMaterials' => $reporstsMaterials,
            'materials'  => $materials,
            'table1' => $table1,
            'table2' => $table2,
            'table3' => $table3,
            'page' => $page
        ]);
    }


    public function getOrderData(&$orders){
        $_order['count_models'] = 0;
        $_order['count_elayners'] = 0;
        $_order['count_attachment'] = 0;
        $_order['count_checkpoint'] = 0;
        $_order['count_reteiners'] = 0;

        $model_types = ['Э', 'ПК', 'БК', 'Р', 'БР', 'КИ'];

        foreach ($orders as $order) {
            // кол-во заказанных изделий по каждому пациенту по всем заказам - Orders
            if( !isset($_order[$order->pacient_code]['count_models'])) $_order[$order->pacient_code]['count_models'] = 0;
            if( !isset($_order[$order->pacient_code]['count_elayners'])) $_order[$order->pacient_code]['count_elayners'] = 0;
            if( !isset($_order[$order->pacient_code]['count_attachment'])) $_order[$order->pacient_code]['count_attachment'] = 0;
            if( !isset($_order[$order->pacient_code]['count_checkpoint'])) $_order[$order->pacient_code]['count_checkpoint'] = 0;
            if( !isset($_order[$order->pacient_code]['count_reteiners'])) $_order[$order->pacient_code]['count_reteiners'] = 0;
            // сумма по всем пациентам для ИТОГО
            // для суммирования по всем пациентам вывод в ИТОГО


            if( !isset($_order[$order->pacient_code]['type'])) $_order[$order->pacient_code]['type'] = $model_types[(int)$order->type];

            $_order[$order->pacient_code]['count_models'] += (int)$order->count_elayners_vc + (int)$order->count_elayners_nc + (int)$order->count_attachment_vc + (int)$order->count_attachment_nc + /*(int)$order->count_checkpoint_vc + (int)$order->count_checkpoint_nc + */(int)$order->count_reteiners_vc + (int)$order->count_reteiners_nc;

            $_order[$order->pacient_code]['count_elayners'] += (int)$order->count_elayners_vc + (int)$order->count_elayners_nc;
            $_order[$order->pacient_code]['count_attachment'] += (int)$order->count_attachment_vc + (int)$order->count_attachment_nc;
            $_order[$order->pacient_code]['count_checkpoint'] += (int)$order->count_checkpoint_vc + (int)$order->count_checkpoint_nc;
            $_order[$order->pacient_code]['count_reteiners'] += (int)$order->count_reteiners_vc + (int)$order->count_reteiners_nc;


            $_order['count_models'] += (int)$order->count_elayners_vc + (int)$order->count_elayners_nc + (int)$order->count_attachment_vc + (int)$order->count_attachment_nc + /*(int)$order->count_checkpoint_vc + (int)$order->count_checkpoint_nc +*/ (int)$order->count_reteiners_vc + (int)$order->count_reteiners_nc;
            //$_order['count_models'] += (int)$_order[$order->pacient_code]['count_models']; // всего моделей

            $_order['count_elayners'] += (int)$order->count_elayners_vc + (int)$order->count_elayners_nc;
            $_order['count_attachment'] += (int)$order->count_attachment_vc + (int)$order->count_attachment_nc;
            $_order['count_checkpoint'] += (int)$order->count_checkpoint_vc + (int)$order->count_checkpoint_nc;
            $_order['count_reteiners'] += (int)$order->count_reteiners_vc + (int)$order->count_reteiners_nc;
            //$_order['models_count_vc'] += (int)$order->count_models_vc + (int)$order->count_elayners_vc + (int)$order->count_attachment_vc + (int)$order->count_checkpoint_vc + (int)$order->count_reteiners_vc;
            //$_order['models_count_nc'] += (int)$order->count_models_nc + (int)$order->count_elayners_nc + (int)$order->count_attachment_nc + (int)$order->count_checkpoint_nc + (int)$order->count_reteiners_nc;
            //$_order['models_count_all'] = $_order['models_count_vc'] + $_order['models_count_nc'];
            //$_order[$order->pacient_code]['type'] = $model_types[(int)$order->type];

            //echo $_order[$order->pacient_code]['count_models'] . ' ' . $_order['count_models'] . ' ';
        }
        return $_order;

    }

    /**
     * Deletes an existing Reports model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
       // if(Yii::$app->user->identity->role == 4) {
        $page = Yii::$app->request->get('report-page');

            $this->findModel($id)->delete();
            return $this->redirect(['index','report-page'=>$page]);
        //}
    }

    /**
     * Finds the Reports model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reports the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reports::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
    
}
