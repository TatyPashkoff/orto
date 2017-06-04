<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DoctorsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Доктора';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctors-index">

<!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->
    <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php  echo Html::a('Добавить доктора', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'pager' => [
            'firstPageLabel' => 'Первая',
            'lastPageLabel' => 'Последняя',
        ],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            //'clinic_id',
            'firstname',
            'age:date',
            //'type',
            [
                'attribute' => 'type',
                'format' => 'text',
                //'label' => 'Тип',
                'content'=>function($model){
                    $roles = [
                        '' => '',
                        '0' => 'Зубной техник',
                        '1' => 'Врач',
                        '2' => 'Мед. директор',
                        '3' => 'Бухгалтер',
                        '4' => 'Админ'
                    ];
                    return  $roles[ $model->type];
                }
            ],
            //'edu',
            [
                'attribute' => 'edu',
                'format' => 'text',
                //'label' => 'Тип',
                'content'=>function($model){
                    $arr = [
                        '' => '',
                        '1' => 'Проходил', '0' => 'Не проходил'
                    ];
                    return  $arr[ $model->edu];
                }
            ],
            [
                'attribute' => 'gender',
                'filter'=> ['1' => 'Мужчина', '0' => 'Женщина',],
                'content'=>function($model){
                    $arr = [
                        '' => '',
                        '1' => 'Мужчина', '0' => 'Женщина',
                    ];
                    return  $arr[ $model->edu];
                }
            ],
            //'status',
            'passport',
            'email:email',
            'phone',
            // 'regalies',
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',],
        ],
    ]); ?>
</div>
