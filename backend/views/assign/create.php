<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Assign */

$this->title = 'Create Assign';
$this->params['breadcrumbs'][] = ['label' => 'Assigns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assign-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
