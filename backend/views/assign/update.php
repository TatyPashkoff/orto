<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Assign */

$this->title = 'Обновление: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Assigns', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="assign-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
