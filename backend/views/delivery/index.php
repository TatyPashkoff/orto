<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Orders;
use backend\models\Pacients;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\DeliverySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Доставка';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-index">

<!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php if( Yii::$app->user->identity->role == 0 ) echo Html::a('Добавить доставку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'pager' => [
            'firstPageLabel' => 'Первая',
            'lastPageLabel' => 'Последняя',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
            [
                'attribute' => 'order_id',
                'label' => 'Номер заказа',
                'content'=>function($model){
                    if( $order = Orders::findOne($model->order_id) ) {
                        return  $order->num;
                    }
                }
            ],
            [
                'attribute' => 'pacient_id',
                'label' => 'Пациент',
                'content'=>function($model){
                    if( $res = Pacients::findOne($model->pacient_id) ) {
                        return  $res->code;
                    }
                }
            ],
            [
                'attribute' => 'status_paid',
                'label' => 'Статус заказа',
                /*'filter' => [
                    '0'=>'dd','1'=>'ddd',
                ],*/
                'content'=>function($model){

                    // разность между стоимостью Пакета и оплаченной суммой

                    if( $pacient = Pacients::findOne($model->pacient_id) ) {
                        //$var_paid = (int) $pacient->var_paid;
                        $sum = $pacient->getPaketSum(); // нужно найти стоимость всего заказа ?? по ВП? по прайсу?
                        $res = $sum - $pacient->sum_paid;
                        if( $res == 0 ){
                            return 'Оплачено ' ;
                        }else {
                            return 'Не оплачено';// . $sum;
                        }
                    }
                }
            ],
            'delivery_all',
            'delivery_ready',
            'date_delivery',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',],
        ],
    ]); ?>
</div>
