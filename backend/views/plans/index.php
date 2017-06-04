<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use backend\models\Pacients;
use backend\models\User;
use yii\widgets\LinkPager;

use backend\models\Doctors;
use app\rbac;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlansSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Планы';
$this->params['breadcrumbs'][] = $this->title;
$role = Yii::$app->user->identity->role;
?>
    <style>
        .material-switch {
            margin: 10px 0px 0px 10px;
        }

        .material-switch input[type="checkbox"] {
            display: none;
        }

        .material-switch > label {
            cursor: pointer;
            height: 0px;
            position: relative;
            width: 40px;
        }

        .material-switch > label::before {
            background: rgb(0, 0, 0);
            box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
            border-radius: 8px;
            content: '';
            height: 16px;
            margin-top: -8px;
            position: absolute;
            opacity: 0.3;
            transition: all 0.4s ease-in-out;
            width: 40px;
        }

        .material-switch > label::after {
            background: rgb(255, 255, 255);
            border-radius: 16px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
            content: '';
            height: 24px;
            left: -4px;
            margin-top: -8px;
            position: absolute;
            top: -4px;
            transition: all 0.3s ease-in-out;
            width: 24px;
        }

        .material-switch input[type="checkbox"]:checked + label::before {
            background: inherit;
            opacity: 0.5;
        }

        .material-switch input[type="checkbox"]:checked + label::after {
            background: inherit;
            left: 20px;
        }
    </style>
    <div class="plans-index">

<!--        <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <? if ($role != 1 && $role != 3): ?>
            <p>
                <?= Html::a('Создать план', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
        <? endif; ?>
        <?php \yii\widgets\Pjax::begin() ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'pager' => [
                'firstPageLabel' => 'Первая',
                'lastPageLabel' => 'Последняя',
             ],
            'columns' => [

                ['class' => 'yii\grid\SerialColumn'],
              /*  [
                    'label' => 'Пациент',
                    'format' => 'raw',
                    'filter' => true,
                    'attribute' => 'pacientName',
                    'value' => function ($data) {
                        $p = Pacients::findOne($data->pacient_id);
                        $name = 'нет';
                        if ($p) {
                            $name = $p->getName();
                        }
                        return $name;
                    },
                ],*/

                [
                    'format' => 'raw',
                    'attribute' => 'pacient',
                    'value' => 'pacient.name',

                ],
                'version',


                [
                    'label' => 'Создал',
                    'format' => 'raw',
                    'value' => function ($data) {
                        $u = User::findOne($data->creater);
                        $name = 'нет';
                        if ($u) {
                            $name = $u->fullname;;
                        }
                        return $name;
                    },
                ],
                [
                    'label' => 'Одобрен директором',
                    'format' => 'raw',
                   // 'visible'  => (Yii::$app->user->identity->role == 4 || Yii::$app->user->identity->role == 2)?1:0,
                    'value' => function ($model) {

                    if (Yii::$app->user->identity->role == 4 || Yii::$app->user->identity->role == 2) {
                        $checked = ($model->approved == 1) ? 'checked="checked"' : '';
                        $switch = '<div class="material-switch">
                            <input id="approvedSwitchOption_' . $model->id . '" name="approvedSwitchOption_' . $model->id . '" type="checkbox" ' . $checked . ' style="display:none"/>
                            <label for="approvedSwitchOption_' . $model->id . '" class="label-success" id="approved"></label>
                        </div>';
                    }
                    else {
                            $switch = ($model->approved == 1)?'да':'нет';
                        }
                        return $switch;
                    }
                ],
                [
                    'label' => 'Одобрен врачем',
                    'format' => 'raw',
               //     'visible'  => (Yii::$app->user->identity->role == 4 || Yii::$app->user->identity->role == 1)?1:0,
                    'value' => function ($model) {
                        $role = Yii::$app->user->identity->role;

                        if($role == 4 ||$role == 2 || $role == 1) {
                                $checked = ($model->ready == 1) ? 'checked="checked"' : '';
                                $switch = '<div class="material-switch">
                                    <input id="readySwitchOption_' . $model->id . '" name="readySwitchOption_' . $model->id . '" type="checkbox" ' . $checked . ' style="display:none"/>
                                    <label for="readySwitchOption_' . $model->id . '" class="label-success" id="ready"></label>
                                </div>';
                        } else {
                            // Да если принят и не отказано врачем в создании ВП
                            $switch = ($model->ready == 1)?'да': ($model->cancel == 0)?'нет':'Возврат';
                        }


                        return $switch;
                    }
                ],


                ['class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete} {approve} {print} {download}',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            $role = Yii::$app->user->identity->role;
                            if ($role == 1 || $role == 2 || $role == 4 || $role == 0)
                                return Html::a(
                                    Html::tag('span', null, ['class' => 'glyphicon glyphicon-edit'])
                                    , null
                                    , ['href' => Url::to(['plans/update', 'id' => $model->id]),
                                        //'class' => 'print-btn',
                                        'data-url' => Url::to(['plans/update', 'id' => $model->id])
                                    ]
                                );
                        },
                        'approve' => function ($url, $model, $key) {
                            $role = Yii::$app->user->identity->role;
                            if ($role == 2 || $role==4)
                                return Html::a(
                                    Html::tag('span', null, ['class' => 'glyphicon glyphicon-share'])
                                    , null
                                    , ['href' => Url::to(['plans/approve', 'id' => $model->id]),
                                        'class' => 'print-btn',
                                        'data-url' => Url::to(['plans/approve', 'id' => $model->id])
                                    ]
                                );
                        },
                        'print' => function ($url, $model, $key) {
                            $role = Yii::$app->user->identity->role;
                            if ($role == 1 || $role == 2 || $role == 4 || $role == 0)
                                return Html::a(
                                    Html::tag('span', null, ['class' => 'glyphicon glyphicon-print'])
                                    , null
                                    , ['href' => Url::to(['plans/print', 'id' => $model->id]),
                                        'class' => 'print-btn',
                                        'data-url' => Url::to(['plans/print', 'id' => $model->id])
                                    ]
                                );
                        },
                        'download' => function ($url, $model, $key) {
                            $role = Yii::$app->user->identity->role;
                            if ($role == 1 || $role == 2 || $role == 4 || $role == 0)
                                return Html::a(
                                    Html::tag('span', null, ['class' => 'glyphicon glyphicon-download'])
                                    , null
                                    , ['href' => Url::to(['plans/downloadplan', 'id' => $model->id]),
                                        'class' => 'down-btn',
                                        'data-url' => Url::to(['plans/downloadplan', 'id' => $model->id])
                                    ]
                                );
                        }
                    ],

                ],
            ],
        ]);
        ?>
        <?php \yii\widgets\Pjax::end(); ?>
    </div>

<?php
$script = "
$(document).ready(function(){
    // switch
       $('label#approved').click(function () {
        var name = jQuery(this).attr('for');  
        var id = name.split('_')
        var approved = jQuery( 'input[name=' + name + ']' ).prop('checked');
        jQuery.ajax({
            type: 'post',
            url: 'http://{$_SERVER['SERVER_NAME']}/plans/apply?id=' + id[1],
            data: 'ss=approved&approved='+approved,
            dataType:'text',
            success: function(msg){
                if(msg=='ok'){
//                    alert('ok')
                }
            },
            error: function(msg){
//                  alert(0)
            },
        });
        return true;
    });   
    
    $('label#ready').click(function () {
        var name = jQuery(this).attr('for');  
        var id = name.split('_')
        var ready = jQuery( 'input[name=' + name + ']' ).prop('checked');
        jQuery.ajax({
            type: 'post',
            url: 'http://{$_SERVER['SERVER_NAME']}/plans/apply?id=' + id[1],
            data: 'ss=ready&ready='+ready,
            dataType:'text',
            success: function(msg){
                if(msg=='ok'){
//                    alert('ok')
                }
            },
            error: function(msg){
//                  alert(0)
            },
        });
        return true;
    })

});";
$this->registerJs($script);