<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SprCity */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Города', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spr-city-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
