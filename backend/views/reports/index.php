<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ReportsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $page==1?'Отчеты затрат':'Отчеты остатков';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reports-index">

<!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php // Html::a('Создать отчет', ['create'], ['class' => 'btn btn-success']) ?>
        <a href="/reports/create?page=<?=$page?>" class="btn btn-success"/>Создать отчет</a>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
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
                'attribute'=>'id',
                'label'=>'Номер отчета'

            ],
            'date',
            //'doctor_id',
            //'pacient_code',
            // 'report_status',
            // 'status',
            // 'type',
            // 'count_models',
            // 'count_elayners',
            // 'count_attachment',
            // 'count_checkpoint',
            // 'count_reteiners',
            // 'comments:ntext',

            ['class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete} {print} ',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            //$role = Yii::$app->user->identity->role;
                            //if ($role == 1 || $role == 4 || $role == 0)
                                $page = Yii::$app->request->get('report-page');
                                return Html::a(
                                    Html::tag('span', null, ['class' => 'glyphicon glyphicon-edit'])
                                    , null
                                    , ['href' => Url::to(['reports/update', 'id' => $model->id, 'report-page'=>$page]),
                                        'data-url' => Url::to(['reports/update', 'id' => $model->id, 'report-page'=>$page])
                                    ]
                                );
                        },
                        'delete' => function ($url, $model, $key) {
                            //$role = Yii::$app->user->identity->role;
                            //if ($role == 1 || $role == 4 || $role == 0)
                                $page = Yii::$app->request->get('report-page');
                                return Html::a(
                                    Html::tag('span', null, ['class' => 'glyphicon glyphicon-trash'])
                                    , null
                                    , ['href' => Url::to(['reports/delete', 'id' => $model->id, 'report-page'=>$page]),
                                        'data-url' => Url::to(['reports/delete', 'id' => $model->id, 'report-page'=>$page])
                                    ]
                                );
                        },
                          'print' => function ($url, $model, $key) {
                            $role = Yii::$app->user->identity->role;
                            $page = Yii::$app->request->get('report-page');
                            if ($role == 1 || $role == 4 || $role == 2 || $role == 0) {
                                return Html::a(
                                    Html::tag('span', null, ['class' => 'glyphicon glyphicon-print'])
                                    , null
                                    , ['href' => Url::to(['reports/print', 'id' => $model->id, 'page' => $page]),
                                        'class' => 'print-btn',
                                        'data-url' => Url::to(['reports/print', 'id' => $model->id, 'page' => $page])
                                    ]
                                );
                            }
                        }
                    ],
                ],
            ],
        ]); ?>
<?php Pjax::end(); ?></div>
