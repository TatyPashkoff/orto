<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Orders */

$this->title = 'Изменить заказ №: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
$role = Yii::$app->user->identity->role;
?>
<!-- p>
    <?echo Html::a('Создать планы', ['plans/create', $model->id], ['class' => 'btn btn-success']);?>
    <?if($role == 4 || $role == 2):?>
        <?= Html::a(Yii::t('app', 'Отправить админу'), ['apply', 'id' => $model->id], ['class' => 'btn btn-default ']); ?>
        <?= Html::a(Yii::t('app', 'Назначения зубных техников'), ['appointment', 'id' => $model->id], ['class' => 'btn btn-default print-btn']); ?>
    <?endif?>
    <?if($role == 4 || $role == 3):?>
        <?= Html::a(Yii::t('app', 'Предоплата'), ['prepaid', 'id' => $model->id], ['class' => 'btn btn-default print-btn']); ?>
    <?endif?>
    <?if($role == 0 || $role == 2):?>
        <?= Html::a(Yii::t('app', 'Отметка об исполнении'), ['check', 'id' => $model->id], ['class' => 'btn btn-default print-btn']); ?>
    <?endif?>
</p -->

<div class="orders-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'clinic' => $clinic,
        'pacients_id' => $pacients_id,
        '_paid' => $_paid,

    ]) ?>

</div>
