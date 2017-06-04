<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

use yii\helpers\ArrayHelper;
use \backend\models\Doctors;
use \backend\models\Clinics;
use \backend\models\Pacients;
use \backend\models\User;
use \backend\models\Assign;
use  app\rbac\Order;


$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;

if( isset($data) && $pacients = Assign::getPacientsByDoctorId($data->creater) ) {
    $where = ['id' => $pacients];
    $pacients = Pacients::getList($where);
}else{
    $pacients = null;
}
?>
<div class="orders-index">

<!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php if(Order::canEdit(Yii::$app->user->identity->role )) :?>
    <p>
        <?= Html::a('Добавить заказ', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php endif; ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'pager' => [
            'firstPageLabel' => 'Первая',
            'lastPageLabel' => 'Последняя',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'num',
            'date',
            //'group_id',
            //'doctor_id',
            [
                'attribute'=>'doctor_id',
                'filter'=>User::getDoctors(),
                'content'=>function($data){
                    return $data->getDoctorFirstname();
                },
                //'value'=>function ($model, $index, $widget) { return $model->doctor->firstname; }
            ],
            //'clinics_id',
            [
                'attribute'=>'clinics_id',
                'filter'=>Clinics::getList(),
                'contentOptions' => ['class' => 'clinic'],
                'content'=>function($data){
                    return $data->getClinicTitle();
                },
                //'value'=>function ($model, $index, $widget) { return $model->clinic->title; }
            ],
            //'pacient_code',
            [
                'attribute'=>'pacient_code',
                'filter'=>$pacients,
                'content'=>function($data){
                    if(isset($data->pacient)) {
                        return $data->pacient->name; //$data->getPacientCode();
                    }
                    return '';
                },
                //'value'=>function ($model, $index, $widget) { return $model->pacient->code; }
            ],
            [
                'attribute' => 'type_paid',
                'filter'=> [
                    '0' => 'Без оплаты',
                    '1' => 'Рассрочка',
                    '2' => 'Полная оплата',
                    '3' => 'Бесплатно',
                ],
                'content'=>function($model){
                    $roles = [
                        '' => '',
                        '0' => 'Без оплаты',
                        '1' => 'Рассрочка',
                        '2' => 'Полная оплата',
                        '3' => 'Бесплатно',
                    ];

                    if( $pay = \backend\models\Payments::find()->where(['pacient_id'=> $model->pacient_code, 'status'=>'0'])->one()) {
                        return  $roles[ $pay->var_paid];
                    }

                    return  $roles[ 0 ];
                }
            ],
            [
                'attribute' => 'status_paid',
                'filter'=> [
                    '0' => 'Не оплачено',
                    '1' => 'Оплачено полностью',
                ],
                'content'=>function($model){
                    $roles = [
                        '' => '',
                        '0' => 'Не оплачено',
                        '1' => 'Оплачено полностью',                      
                        
                    ];
                    $paid = 0;
                    if( $pay = \backend\models\Payments::find()->where(['pacient_id'=> $model->pacient_code, 'status'=>'0'])->one()) {

                        // бесплатно все оплачено и подтверждено
                        if( $pay->var_paid == 3 ) { // || $pay->status_paid == 1)
                            return $roles[1];
                        }


                        // получить все элементы план графика
                        if ($plan_items = \backend\models\PaymentsItems::find()->where(['payment_id' => $pay->id])->all()) {
                            $cnt = 0;
                            foreach ($plan_items as $item) {
                                $cnt += (int)$item->status_paid; // просуммировать все статусы 0+1+0+1+1 = 3 из 5
                            }
                            // если кол-во оплаченных равно кол-ву дат оплаты
                            // значит все оплачено
                            if($cnt == count($plan_items)) $paid = 1;
                        }else{
                            // если нет элементов оплаты и предоплата подтверждена
                            // и не бесплатно -3, значит оплата прошла
                            if( $pay->var_paid !=3 && $pay->status_paid ==1 ){
                                $paid = 1;//['ready'=>1,'date' => $pay->date_downpay, 'sum' => $pay->downpay]; // подтверждение оплаты на последнюю оплаченную дату
                            }

                        }

                    }

                    return  $roles[ $paid ];
                }
            ],

            [
                'attribute' => 'order_status',
                'filter'=> [
                    '0' => 'Не отправлен',
                    '1' => 'Отправлен',
                ],
                'content'=>function($model){
                    $roles = [
                        '' => '',
                        '0' => 'Не отправлен',
                        '1' => 'Отправлен',
                    ];
                    return  $roles[ $model->order_status];
                }
            ],


            ['class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {assign} {delete} {print}',
                        'buttons' => [
                            'print' => function($url, $model, $key) {
                                return Html::a(
                                    Html::tag('span',null,['class'=>'glyphicon glyphicon-print'])
                                    ,null
                                    ,['href'=>Url::to(['orders/print', 'id' => $model->id]),//'javascript:void(0)',
                                        'class' => 'print-btn',
                                        'data-url'=>Url::to(['orders/print', 'id' => $model->id])
                                    ]
                                );
                            },                            						
                            ]
                        ],

        ],
    ]); ?>
</div>
