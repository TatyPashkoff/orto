<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SprCitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Справочник городов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spr-city-index">

<!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить город', ['create'], ['class' => 'btn btn-success']) ?>
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
            'code',
            'name',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ]
    ]); ?>
</div>
