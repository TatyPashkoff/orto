<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Plans */

if($title==""){
    $this->title = 'Создать план';
}else{
    $this->title = $title;
}

$this->params['breadcrumbs'][] = ['label' => 'Планы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="plans-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,'model' => $model,
                'orders' => $orders,
                'doctors' => $doctors,
                'pacients' => $pacients,
                'title' => $title,
    ]) ?>

</div>
