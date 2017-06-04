<?php

use yii\helpers\Html;
use yii\grid\GridView;

use backend\models\Doctors;
use backend\models\User;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AlertsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сообщения';
$this->params['breadcrumbs'][] = $this->title;
$role = Yii::$app->user->identity->role;
?>
<div class="alerts-index">

<!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?if($role != 1 && $role != 3):?>
    <p>
        <?= Html::a('Создать сообщение', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?endif;?>
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
           // 'doctor_id_to',
           // 'doctor_id_from',
            [
                'attribute'=>'doctor_id_from',
                'filter'=>User::getDoctors(),
                'value'=>function ($model) { return isset($model->doctorfrom->fullname)?$model->doctorfrom->fullname :'';
                 }
            ],
            [
                'attribute'=>'doctor_id_to',
                'filter'=>User::getDoctors(),
                'value'=>function ($model) { return isset( $model->doctorto->fullname)?$model->doctorto->fullname:'';
            }
            ],
            'date:datetime',
            //'read_status',
            /*[
                'attribute' => 'read_status',
                'filter'=> [
                    '0' => 'не прочитано',
                    '1' => 'прочитано',
                ],
                'content'=>function($model){
                    $roles = [
                        '0' => 'не прочитано',
                        '1' => 'прочитано',
                    ];
                    $status = ($model->read_status)?'default':'success';
                    return  '<span class="label label-'.$status.'">' . $roles[ $model->read_status] . '</span>';
                }
            ],*/
            // 'text:ntext',

            [
                'class' => 'yii\grid\ActionColumn',
                       'template' => '{update} {delete}',


            ],
        ],
    ]); ?>
</div>
