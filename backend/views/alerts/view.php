<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Alerts */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Alerts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alerts-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'doctor_id_to',
            'doctor_id_from',
            'date:datetime',
            //'read_status',
            [
                'attribute' => 'read_status',
                'format' => 'text',
                'content'=>function($model){
                    $roles = [
                        '0' => 'не прочитано',
                        '1' => 'прочитано',
                    ];
                    return  $roles[ $model->read_status];
                }
            ],
            'text:ntext',
        ],
    ]) ?>

</div>
