<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SearchUser */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <?php
    if(Yii::$app->session->hasFlash('Добавлено')){
        echo "<div class='alert alert-success' style='cursor:pointer'>". Yii::$app->session->getFlash('Добавлено')."<span style='float:right;'>x</span></div>";
    }
    ?>

<!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать пользователя', ['create'], ['class' => 'btn btn-success']) ?>
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

            //'id',
            'username',
            [
                 'attribute' => 'role',
                 'format' => 'text',
                 'label' => 'Тип',
                 'content'=>function($model){
                     $roles = [
                         '0' => 'Зубной техник',
                         '1' => 'Врач',
                         '2' => 'Мед. директор',
                         '3' => 'Бухгалтер',
                         '4' => 'Админ'
                     ];
                     return  $roles[ $model->role ];
                 }
            ],
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            // 'email_confirm_token:email',
            //'email:email',
            //'status',
            // [
            //     'attribute' => 'created_at',
            //     'format' => 'text',
            //     'label' => 'Дата создания',
            //     'label' => 'Дата обновления',
            //     'content'=>function($model, $key, $index, $column){
            //         return  date('Y-m-d', $index);
            //     }
            // ],
            // [
            //     'attribute' => 'updated_at',
            //     'format' => 'text',
            //     'label' => 'Дата обновления',
            //     'content'=>function($model, $key, $index, $column){
            //         return  date('Y-m-d', $index);
            //     }
            // ],
           /* [
                'attribute'=>'Активен',
                'format'=>'text', // Возможные варианты: raw, html
                'content'=>function($data){

                    return ($data->status == 1) ? 'Да' : 'Нет';
                },
                'filter' => Html::activeDropDownList($searchModel,
                    'status',
                    [
                        '1' => 'Да',
                        '0' => 'Нет',
                    ],
                    [
                        'class' => 'form-control',
                        'prompt' => 'Все'
                    ]),
                'options' => ['style' => ['width' => '100px']],
            ],*/
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Действия',
                'headerOptions' => ['width' => '80'],
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        $update_link = '/user/update?id='.$model->id;
                        return Html::a(
                        '<span class="glyphicon glyphicon-pencil"></span>',
                        $update_link);
                    },
                    'delete' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,['data' => ['confirm' => "Вы уверены, что хотите удалить этот элемент?"],]);
                    },
                ],
            ],
        ],
    ]); ?>

</div>


<?php
$script ="
$( document ).ready(function() {
    $('div.alert').click(function(){
        $(this).hide(300);
    });
})";
$this->registerJs($script);