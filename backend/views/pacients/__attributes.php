<?php

use backend\models\Assign;
use backend\models\Plans;
use backend\models\User;
use backend\models\Orders;
use backend\models\Payments;
use backend\models\Events;
use yii\helpers\ArrayHelper;

$attributes = [
//    'date' => 'date:date',
    'date' => [
        'attribute' => 'date',
        'value' => function($model){
            return date('Y.m.d', $model->date);
        }
    ],
    'id' => [
        'attribute'=>'id', // code=id
        'label' => 'Код пациента',
        'content' => function($data){
            return $data->id;
        },
        //'value'=>function ($model, $index, $widget) { return $model->doctor->firstname; }
    ],
    'clinic_id' => [
        'attribute'=>'clinic_id',
        //'filter'=>Clinics::getClinicsByDoctor(),
        'content'=>function($data){
            return $data->getClinicTitle();
        },
        'headerOptions' => ['style' => 'width:300px;'],
        'contentOptions' => ['class' => 'clinic'],
        //'value'=>function ($model, $index, $widget) { return $model->plan->version; }
    ],
    'user' => [
        'attribute' => 'user',
        'value' => 'user.fullname',
        'label' => 'ФИО Врача'
    ],
    'name' => 'name',
    'product_id' => [
        'attribute' => 'product_id',
        'filter'=> ['1' => 'Мужчина', '0' => 'Женщина',],
        'content'=>function($model){
            $products = ArrayHelper::map(backend\models\Products::find()->select(['id', 'title'])->all(), 'id', 'title');

            return  $products[ $model->product_id ];
        }
    ],
    'level' => [
        'attribute' => 'level',
//        'label' => 'Статус',
        'format' =>'raw',
        'content'=>function($model){
            $role = Yii::$app->user->identity->role;
            if( $role == 0 || $role == 1 || $role== 2 || $role == 4) {
                // $user_id = Yii::$app->user->id;
                if($model->status==2){
                    return 'Лечение завершено';
                }



                /*
                статусы

                */
                if( $plan = Orders::find()->where(['pacient_code' => $model['id']])->one()){
                    return 'Лечение: печать КПП'; // зубной техник создал заказ
                }
                if( $plan = Plans::find()->where(['pacient_id' => $model['id']])->one()){
                    if($plan->approved==1 && $plan->ready == 1){
                        return 'ВП утвержден. Ждем подтверждающие документы'; // мед директор и врач утвердили виртуальный план - потестить
                    }
                    elseif($plan->approved==1){
                        return 'Утверждение виртуального плана'; // мед директор утвердил виртуальный план - потестить
                    }
                }
                if( $plan_graf = Payments::find()->where(['pacient_id' => $model['id']])->one()){
                    if($plan_graf->var_paid_vp==1 && $plan_graf->status_paid_vp!=1) {
                        return 'Ожидание оплаты за ВП'; // администратор выбрал способ оплаты за ВП
                    }
                    elseif ($plan_graf->var_paid!=NULL && $plan_graf->var_paid!=3 && $plan_graf->status_paid!=1){
                        return 'Ожидание оплаты за лечение'; // администратор создал график оплаты за лечение - тестить
                    }
                    elseif($plan_graf->status_paid_vp==1 && $plan_graf->status_paid==1){
                        return 'Моделирование виртуального плана'; // бухгалтер подтвердил оплату
                    }
                }
                // берем только одного по текущему level
                if( $assign = Assign::find()->where(['pacient_id'=>$model->id])->one() ) {
                    $levels = [
                        'Новый Пациент',
                        'Диагностика',
                        'Сканирование',
                        'Моделирование',
                        'Лечение: печать КПП',
                        'Отправка',
                         ];
                    //if($assign->level>5){
                    //    return $levels[5];
                    //}else if($assign->level<=0){
                    //    return $levels[0];
                    //}else {
                        return $levels[$assign->level];
                    //}
                }

                // берем текст из событий пациента Events
                if( $event = Events::find()->where(['pacient_id'=>$model->id])->one() ) {
                    return $event->text;
                }
            }
        }
    ],
    'doctor_id' => [
        'attribute'=>'doctor_id',
        'filter'=>User::getDoctors(),
        'visible'=> isset(Yii::$app->user->identity) && Yii::$app->user->identity->role == 1 ?  false:true,
        'content'=>function($data){
            return $data->getDoctorFirstname();
        },
        //'value'=>function ($model, $index, $widget) { return $model->doctor->firstname; }
    ],
    'age' => 'age:date',
    'gender' => [
        'attribute' => 'gender',
        'filter'=> ['1' => 'Мужчина', '0' => 'Женщина',],
        'content'=>function($model){
            $arr = [
                '' => '',
                '1' => 'Мужчина', '0' => 'Женщина',
            ];
            return  $arr[ $model->gender];
        }
    ],
    'status' => [
        'attribute' => 'status',
        'label' => 'Диагностический материал',
        'format' =>'raw',
        'filter'=> ['0' => 'Не назначен', '1' => 'Принят', '2' => 'Возврат'],
        'content'=>function($model){

            //$role = Yii::$app->user->identity->role;
            //$user_id = $model->creator;// Yii::$app->user->id;
            // берем только одного по текущему level
            if( $assign = Assign::find()->where( ['pacient_id'=>$model->id, 'status'=>'0'] )->one() ) { //->andWhere(['or',
                /*                          [ 'level_1_doctor_id' => $user_id ],
                                            [ 'level_2_doctor_id' => $user_id ],
                                            [ 'level_3_doctor_id' => $user_id ],
                                            [ 'level_4_doctor_id' => $user_id ],
                                            [ 'level_5_doctor_id' => $user_id ],
                                        ])->one() ){*/

                $res = ['Не указан','Принят','Возврат']; // 0-1-2
                $status = ['default','success','danger']; // 0-1-2
                $lvl = (int)$assign->level-1;
                if($lvl<1) $lvl=1;
                $level_status = 'level_'.$lvl.'_status';
                if($assign->level>5) {
                    return '<span class="label label-' . $status[0] . '">' . $res[0] . '</span>';
                }else {
                    return '<span class="label label-' . $status[$assign->$level_status] . '">' . $res[$assign->$level_status] . '</span>';
                }
            }

            return  '<span class="label label-default">Не указан.</span>';
        }
    ],
    'delivery' => [
        'attribute' => 'delivery',
        'label' => 'Изготовлено',//'Доставлено',
        'format' =>'raw',
        'content' => function($model){
            $role = Yii::$app->user->identity->role;
            $res = '0 из 0';
            if( $role ==1 || $role==2 || $role == 4) {
                $user_id = Yii::$app->user->id;
                // берем только одного по текущему level
                //$res = \backend\models\Payments::getPaymentReadyItems($model->order_id, $model->id);
                //return $res['ready'] . ' из ' . $res['all'];
                if($order = Orders::find()->where(['pacient_code'=>$model->id,'status'=>'0'])->one()) {

                    if ($plans = Plans::findOne($order->vplan_id) ){ // ->where(['pacient_id' => $model->id, 'status' => '0'])->one()) {
                        $res = $plans->getCapCount() . ' из ' . $order->getCapCount();
                    }
                }
            }
            return $res;
        }
    ],
    'phone' => 'phone',
//            'order_id' => 'order_id',
    'order_id' => [
        'attribute'=>'order_id',
        'filter'=>Orders::getList(),
        'content'=>function($data){
            return 1;
        },
    ],
    'vp_id' => [
        'attribute'=>'vp_id',
        'filter'=>Plans::getList(),
        'content'=>function($data){
            return $data->getPlanVersion();
        },
        //'value'=>function ($model, $index, $widget) { return $model->plan->version; }
    ],

    //'gender',

    // 'status',
    // 'alert_date',
    // 'alert_msg',
    // 'type_paid',
    // 'var_paid',

    // 'diagnosis',
    // 'result',
    // 'files',
];