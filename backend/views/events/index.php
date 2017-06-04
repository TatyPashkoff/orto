<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\EventsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'События';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="events-index">

<!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php // Html::a('Create Events', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'firstPageLabel' => 'Первая',
            'lastPageLabel' => 'Последняя',
        ],
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            ['attribute' => 'pacient_id',
                'content'=>function($model){
                    $pacient = \backend\models\Pacients::findOne($model->pacient_id);
                    return  $pacient->name;
            }],
            [
                'attribute' => 'event',
                'content'=>function($model){
                    $arr = ['0' => 'Добавлен пациент', '1' => 'Создан план-график оплаты',
                        '2' => 'Создан виртуальный план', '3' => 'Создан заказ', '4' => 'Произведена оплата',
                        '5' =>'Доставка заказа',
                        '6' =>'Доставка завершена',
                        '7' =>'Назначен техник',
                        '8' =>'Возврат диагностического материала',
                        '9' =>'Принят диагностический материал'
                    ];
                    return  $arr[ $model->event ];
                }
            ],

            ['attribute' => 'date',
            'content'=>function($model){
                return  date('d-m-Y',$model->date);
            }],

            ['class' => 'yii\grid\ActionColumn',

                'template' => '{delete}',
                /*'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        $role = Yii::$app->user->identity->role;

                        if ($role == 1 || $role == 4  || $role == 3) {

                            return Html::a(
                                Html::tag('span', null, ['class' => 'glyphicon glyphicon-list'])
                                , null
                                , ['href' => Url::to(['/events/index', 'id' => $model->id]),
                                    'data-url' => Url::to(['/events/index', 'id' => $model->id])
                                ]
                            );
                        }

                    },*/

            ]
        ],
    ]); ?>
</div>
