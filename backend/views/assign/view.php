<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Assign */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Assigns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assign-view">

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
            'num',
            'pacient_id',
            'status',
            'level_1_doctor_id',
            'level_2_doctor_id',
            'level_3_doctor_id',
            'level_4_doctor_id',
            'level_5_doctor_id',
            'level_1_status',
            'level_2_status',
            'level_3_status',
            'level_4_status',
            'level_5_status',
            'level_1_result:ntext',
            'level_2_result:ntext',
            'level_3_result:ntext',
            'level_4_result:ntext',
            'level_5_result:ntext',
            'level_1_date',
            'level_2_date',
            'level_3_date',
            'level_4_date',
            'level_5_date',
        ],
    ]) ?>

</div>
