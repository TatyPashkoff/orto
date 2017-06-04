<?php

use yii\helpers\Html;
use yii\helpers\Url;

$buttons = [
    'events' => function ($url, $model, $key) {//События
        $role = Yii::$app->user->identity->role;

        if ($role == 1 || $role == 4  || $role == 2 || $role == 3) {

            return Html::a(
                Html::tag('span', null, ['class' => 'glyphicon glyphicon-list']),
                null,
                [
                    'href' => Url::to(['/events/index', 'pacient_id' => $model->id]),
                    'data-url' => Url::to(['/events/index', 'pacient_id' => $model->id]),
                    'title' => 'События'
                ]
            );
        }

    },
    'update' => function ($url, $model, $key) {//Редактировать
        $role = Yii::$app->user->identity->role;

        if ($role == 1 || $role == 2 || $role == 4  || $role == 3) {

            return Html::a(
                Html::tag('span', null, ['class' => 'glyphicon glyphicon-pencil']),
                null,
                [
                    'href' => Url::to(['pacients/update', 'id' => $model->id]),
                    'data-url' => Url::to(['pacients/update', 'id' => $model->id]),
                    'title' => 'Редактировать'
                ]
            );
        }

    },
    'delete' => function ($url, $model, $key) {//Удалить
        $role = Yii::$app->user->identity->role;
        if ($role == 1 || $role == 2 || $role == 4)
            return Html::a(
                Html::tag('span', null, ['class' => 'glyphicon glyphicon-trash']),
                null,
                [
                    'href' => Url::to(['pacients/delete', 'id' => $model->id]),
                    //'class' => 'print-btn',
                    'data-url' => Url::to(['pacients/delete', 'id' => $model->id]),
                    'title' => 'Удалить'
                ]
            );
    },
    'approve' => function ($url, $model, $key) {//Отправить на коррекцию
        $role = Yii::$app->user->identity->role;
            return Html::a(
                Html::tag('span', null, ['class' => 'glyphicon glyphicon-share']),
                null,
                [
                    'href' => Url::to(['pacients/approve', 'id' => $model->id]),
                    'class' => 'print-btn',
                    'data-url' => Url::to(['pacients/approve', 'id' => $model->id]),
                    'title' => 'Отправить на коррекцию'
                ]
            );
    },
    'assign' => function($url, $model, $key) {//Назначить зуботехника
        $role = Yii::$app->user->identity->role;
        $user_id = Yii::$app->user->id;
        // проверка пациента на назначение данному user-технику
        // status 1 отражает назначение техника, иначе, если 0, все услуги выполнены или запрещено...

        // только техники, мед.директоры и админы
        if ($role == 0 || $role == 2 || $role == 4) {
            /*if( $role!=2 && $role!=4 && $assign = Assign::find()->where(['pacient_id'=>$model->id,'status'=>'1'])->andWhere(['or',
                    [ 'level_1_doctor_id' => $user_id ],
                    [ 'level_2_doctor_id' => $user_id ],
                    [ 'level_3_doctor_id' => $user_id ],
                    [ 'level_4_doctor_id' => $user_id ],
                    [ 'level_5_doctor_id' => $user_id ],
                ])->all() ) {
            }    */
            return Html::a(
                Html::tag('span', null, ['class' => 'glyphicon glyphicon-user']),
                null,
                [
                    'href' => Url::to(['pacients/assign', 'id' => $model->id]),
                    'data-url' => Url::to(['pacients/assign', 'id' => $model->id]),
                    'title' => 'Назначить зуботехника'
                ]
            );
        }

    },
    'plangraph' => function($url, $model, $key) {//План график оплаты

        $role = Yii::$app->user->identity->role;
        $user_id = Yii::$app->user->id;
        // проверка пациента на назначение данному user-технику
        // status 1 отражает назначение техника, иначе, если 0, все услуги выполнены или запрещено...

        // только техники, мед.директоры и админы
        if ( $role == 1 || $role == 2 || $role == 3 || $role == 4 ) {
            /*if( $role!=2 && $role!=4 && $assign = Assign::find()->where(['pacient_id'=>$model->id,'status'=>'1'])->andWhere(['or',
                    [ 'level_1_doctor_id' => $user_id ],
                    [ 'level_2_doctor_id' => $user_id ],
                    [ 'level_3_doctor_id' => $user_id ],
                    [ 'level_4_doctor_id' => $user_id ],
                    [ 'level_5_doctor_id' => $user_id ],
                ])->all() ) {
            }    */
            return Html::a(
                Html::tag('span', null, ['class' => 'glyphicon glyphicon-signal']),
                null,
                [
                    'href' => Url::to(['pacients/plan-graph', 'id' => $model->id]),
                    'data-url' => Url::to(['pacients/plan-graph', 'id' => $model->id]),
                    'title' => 'План график оплаты'
                ]
            );
        }

    },
    'print' => function ($url, $model, $key) {//Печать
        $role = Yii::$app->user->identity->role;
        if ($role == 1 ||  $role == 4 ||  $role == 2  /*||  $role == 0 не надо! */ )
            return Html::a(
                Html::tag('span', null, ['class' => 'glyphicon glyphicon-print']),
                null,
                [
                    'href' => Url::to(['pacients/print', 'id' => $model->id]),
                    'class' => 'print-btn',
                    'data-url' => Url::to(['pacients/print', 'id' => $model->id]),
                    'title' => 'Печать'
                ]
            );
    },

    'send' => function ($url, $model, $key) {//Печать
    $role = Yii::$app->user->identity->role;
    if ($role == 1 ||  $role == 4 ||  $role == 2  /*||  $role == 0 не надо! */ )
        return Html::a(
            Html::tag('span', null, ['class' => 'glyphicon glyphicon-print']),
            null,
            [
                'href' => Url::to(['pacients/print', 'id' => $model->id]),
                'class' => 'print-btn',
                'data-url' => Url::to(['pacients/print', 'id' => $model->id]),
                'title' => 'Печать'
            ]
        );
}

];