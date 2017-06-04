<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Plans */

$this->title = 'Изменить план №: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Планы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
$role = Yii::$app->user->identity->role;
?>
<div class="plans-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <h3>Версия № <?= Html::encode($model->version); ?></h3>
    <?= $this->render('_form', [
        'model' => $model,
        'pacients' =>$pacients
    ]) ?>

</div>
