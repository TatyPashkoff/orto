<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AssignSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Assigns';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assign-index">

<!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Assign', ['create'], ['class' => 'btn btn-success']) ?>
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

            'id',
            'num',
            'pacient_id',
            'status',
            'level_1_doctor_id',
            // 'level_2_doctor_id',
            // 'level_3_doctor_id',
            // 'level_4_doctor_id',
            // 'level_5_doctor_id',
            // 'level_1_status',
            // 'level_2_status',
            // 'level_3_status',
            // 'level_4_status',
            // 'level_5_status',
            // 'level_1_result:ntext',
            // 'level_2_result:ntext',
            // 'level_3_result:ntext',
            // 'level_4_result:ntext',
            // 'level_5_result:ntext',
            // 'level_1_date',
            // 'level_2_date',
            // 'level_3_date',
            // 'level_4_date',
            // 'level_5_date',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',],
        ],
    ]); ?>
</div>
