<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Pacients */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pacients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pacients-view">

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
            'doctor_id',
            'order_id',
            'vp_id',
            'code',
            'age:date',
            'date:date',
            'gender',
            'status',
            //'alert_date',
            //'alert_msg',
            'name',
            //'type_paid',
            //'var_paid',
            'phone',
            'diagnosis',
            'result',
            'files',
        ],
    ]) ?>

</div>
