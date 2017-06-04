<?php

use yii\helpers\Html;
use yii\grid\GridView;

use \backend\models\SprCity;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ClinicsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Клиники';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clinics-index">

<!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать клинику', ['create'], ['class' => 'btn btn-success']) ?>
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
            'title',
            'contract',
            [
                'attribute'=>'city_id',
                'filter'=>SprCity::getList(),
                'content'=>function($data){
                    return $data->getCityName();
                    //$p = SprCity::findOne($data->city_id);
                    //return $p->name;
                    //return $data->city_id;
                },
                //'value'=>function ($model, $index, $widget) { return $model->doctor->firstname; }
            ],
            'adress',
            'phone',
            'contacts_admin',
            'email:email',
            /*'model_price',
            'elayner_price',
            'attachment_price',
            'checkpoint_price',
            'reteiner_price',*/
            // 'model_discount',
            // 'elayner_discount',
            // 'attachment_discount',
            // 'checkpoint_discount',
            // 'reteiner_discount',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>
</div>
