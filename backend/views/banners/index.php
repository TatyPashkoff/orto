<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BannersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Баннеры';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banners-index">

<!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>       

        <?= Html::a('Добавить баннер', ['create'], ['class' => 'btn btn-success']) ?>
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
            [
                'attribute'=>'status',
                'content'=>function($data){
                    return $data->status?'<span class="label label-success">Вкл.</span>' : '<span class="label label-danger">Откл.</span>';
                },
                //'value'=>function ($model, $index, $widget) { return $model->clinic->title; }
            ],
            'title',
            //'banner',
            //'type',
            //'date:date',
            // 'date_start',
            // 'date_finish',
            // 'status',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',],
        ],
    ]); ?>
</div>
