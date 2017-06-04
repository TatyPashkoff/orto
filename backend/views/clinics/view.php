<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Clinics */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Clinics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clinics-view">

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
            'title',
            'adress',
            'phone',
            'contacts_admin',
            'email:email',
            'model_price',
            'elayner_price',
            'attachment_price',
            'checkpoint_price',
            'reteiner_price',
            'model_discount',
            'elayner_discount',
            'attachment_discount',
            'checkpoint_discount',
            'reteiner_discount',
        ],
    ]) ?>

</div>
