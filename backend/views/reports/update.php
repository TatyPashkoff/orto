<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Reports */

$this->title = 'Изменить отчет №: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Отчеты', 'url' => ['index', 'report-page'=>$page]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['update', 'id' => $model->id, 'report-page'=>$page]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="reports-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'pacients' => $pacients,
        'pacients_selected' => $pacients_selected,
        'doctors' => $doctors,
        'materials'  => $materials,
        //'reporstsMaterials' => $reporstsMaterials,
        'table1' => $table1,
        'table2' => $table2,
        'table3' => $table3,
        'page' => $page,
        'order' => $order,
        'ostat' => $ostat,
        'order_ids'=>$order_ids,

    ]) ?>

</div>
