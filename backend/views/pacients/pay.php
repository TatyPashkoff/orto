<?php

use yii\helpers\Html;
use yii\grid\GridView;

use backend\models\Doctors;
use backend\models\Plans;
use backend\models\User;
use backend\models\Orders;
use backend\models\Clinics;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PacientsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пациенты';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .need_paid{
        background:#ffcc00;
        width:100% !important;
        height:100% !important;
        padding:8px 5px!important;
        margin:0px !important;
    }
    .out_paid{
        background:#ce8483;
        color:#fff;
        width:100% !important;
        height:100% !important;
        padding:8px 5px !important;
        margin:0px !important;
    }

</style>

<div class="pacients-index">

<!--    <h1>--><?//= (Yii::$app->controller->action->id == 'pay')? Html::encode('Оплата пациентов') : Html::encode($this->title) ?><!--</h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

 <?php
    $role = Yii::$app->user->identity->role ;


    /*if( $role == 4 || $role == 1 || $role == 2 ) { ?>

        <p>
            <?= Html::a('Добавить пациента', ['create'], ['class' => 'btn btn-success']); ?>
        </p>
    <?php } */?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'pager' => [
            'firstPageLabel' => 'Первая',
            'lastPageLabel' => 'Последняя',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            // 'type_paid',
            // 'var_paid',
            [
                'attribute'=>'vp_paid',
                'label' => 'Оплата за ВП',
                //'filter'=> 'vp_paid',
                'content'=>function($data){
                    return $data->getPaid();
                },
            ],
            [
                'attribute'=>'paket',
                'label'=>'Пакет',
                //'format' =>'html',
                //'filter' => \backend\models\Price::find()->select('paket_name')->asArray()->all(),
                'content'=>function($data){
                    return $data->getPaket() ;
                },
            ],
            [
                'attribute'=>'var_paid',
                'content'=>function($data){
                    $var_paid = ['Не задано','Рассрочка','Полная','Бесплатно'];
                    if( $pay = \backend\models\Payments::find()->where(['pacient_id'=> $data->id, 'status'=>'0'])->one()) {
                        return $var_paid[(int)$pay->var_paid];
                    }
                    return $var_paid[0];

                },
            ],
            [ 'attribute'=>'date_paid',
                'format'=>'html',
                'content'=>function($data){
                    // дата оплаты следующая по план графику
                    if( $pay = \backend\models\Payments::find()->where(['pacient_id'=> $data->id, 'status'=>'0'])->one()) {
                        if ( $pay->var_paid == 3 ){
                            return 'Бесплатно';
                       // }elseif(  $pay->status_paid == 1) {
                        //    return 'Все оплачено';
                        }
                       // if ( $data->getDebt() == 'Все оплачено' )                       return 'Все оплачено';

                    }


                    if( $paid = \backend\models\Payments::getNextPaid($data->id) ){
                        if((int)$paid['ready']==1){
                            return 'Все оплачено';
                        }
                    }
                    /*if( $plan_graf = \backend\models\Payments::find()->where(['pacient_id'=>$data->id])->one() ) {
                        // сорт. по убыванию статуса подтв.
                        // берем первую подтвержденную дату
                        if( $pay_items = \backend\models\PaymentsItems::find()->where(['payment_id' => $plan_graf->id])->orderBy('status DESC')->one() ) {
                            return $pay_items->date;
                        }
                    }*/
                    $class = '';


                    $_date = (int) $paid['date'];
                    if($_date == 0){ // если дата не задана
                        $class = '';
                    }elseif(  time() >= $_date - 86400*3 && time() <= $_date ){
                        $class = 'need_paid';
                    }elseif( $_date < time() ){
                        $class = 'out_paid';
                    }

                    $date = date('d-m-Y',$paid['date']);


                    //if( (int) $paid['date'] == 0 ){
                    //    $date =  'Не задано';
                    //}else {
                    //}
                    if( $_date==0 ) return '<span> Не задано </span>';

                    return '<span class="'.$class.'">' . $date . '</span>';
                }
            ],
            [
                'attribute'=>'sum_paid',
                'content'=>function($data){
                    if( $pay = \backend\models\Payments::find()->where(['pacient_id'=> $data->id, 'status'=>'0'])->one()) {
                        if ($pay->var_paid == 3){
                            return 'Бесплатно';
                        //}elseif( $pay->status_paid == 1) {
                         //   return 'Все оплачено';
                        }
                        //if ( $data->getDebt() == 'Все оплачено' ){                            return 'Все оплачено';


                    }

                    if( $paid = \backend\models\Payments::getNextPaid($data->id) ){
                        if((int)$paid['ready']==1){
                            return 'Все оплачено';
                        }else{
                            $class = '';
                            $_date = (int) $paid['date'];
                            if($_date == 0) {
                                $class = '';
                            }elseif(  time() >= $_date - 86400*3 && time() <= $_date ){
                                $class = 'need_paid';
                            }elseif( (int)$paid['date'] < time() ){
                                $class = 'out_paid';
                            }

                            return '<span class="'.$class.'">' . $paid['sum'] . '</span>';

                        }
                    }
                    /*if( $plan_graf = \backend\models\Payments::find()->where(['pacient_id'=>$data->id])->one() ) {
                        // сорт. по убыванию статуса подтв.
                        // берем первую подтвержденную дату
                        if( $pay_items = \backend\models\PaymentsItems::find()->where(['payment_id' => $plan_graf->id])->orderBy('status DESC')->one() ) {
                            return $pay_items->date;
                        }
                    }*/
                    return 0;
                    /* $sum = 0;
                    if( $plan_graf = \backend\models\Payments::find()->where(['pacient_id'=>$data->id])->one() ) {
                       if( $pay_items = \backend\models\PaymentsItems::find()->where(['payment_id' => $plan_graf->id])->all() ) {
                           $sum = $plan_graf->downpay; // + предоплата
                           foreach ($pay_items as $item) {
                               // + подтверждено бухгалтером
                               if ($item->status_paid == 1) {
                                   $sum += $item->sum;
                               }
                           }
                       }
                        //$sum -= $plan_graf->sum_discount; // скидка
                    }
                    return $sum; */
                },
            ],
            [
                'attribute'=>'debt',
                'label' => 'Задолженность',
                'content'=>function($data){
                    if( $pay = \backend\models\Payments::find()->where(['pacient_id'=> $data->id, 'status'=>'0'])->one()) {
                        if ($pay->var_paid == 3){
                            return 'Бесплатно';
                        //}elseif( $pay->status_paid == 1) {
                         //   return 'Все оплачено';
                        }


                    }

                    return $data->getDebt();
                },
            ],  

            [
                'attribute'=>'dogovor',
                'label' =>'Договор',
                'content'=>function($data){
                    if(strlen($data->dogovor)) {
                        //return '  <a class="btn btn-primary"  download href="'.$data->dogovor.'">Скачать</a>';
                        return '<a class="btn btn-primary" download href="/pacients/download-dogovor?id='.$data->id.'">Скачать</a>';
                    }
                },
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update}',

                'buttons' => [

                    'update' => function ($url, $model, $key) {
                        $role = Yii::$app->user->identity->role;
                        if($role >=1 ) { // только врач бух админ и дир
                            return Html::a(
                                Html::tag('span', null, ['class' => 'glyphicon glyphicon-signal'])
                                , null
                                , ['href' => Url::to(['pacients/plan-graph', 'id' => $model->id]),
                                    //'class' => 'print-btn',
                                    'data-url' => Url::to(['pacients/plan-graph', 'id' => $model->id])
                                ]
                            );
                        }
                    },

                ]
            ]




                // 'diagnosis',
            // 'result',
            // 'files',

        ],
    ]); ?>
</div>
