<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Reports */

$this->title = 'Создать отчет';
$this->params['breadcrumbs'][] = ['label' => 'Отчеты', 'url' => ['index', 'report-page'=>$page]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reports-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'pacients' => $pacients,
        'pacients_selected' => $pacients_selected,
        'doctors' => $doctors,
        'materials'  => $materials,
        'page' => $page,
        'order' => $order,
       // 'reporstsMaterials' => $reporstsMaterials,
        'ostat' => $ostat,
    ]) ?>

</div>
