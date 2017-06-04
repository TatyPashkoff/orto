<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Delivery */

$this->title = 'Создание доставки';
$this->params['breadcrumbs'][] = ['label' => 'Доставка', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="delivery-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'orders' => $orders,
        'pacients' => $pacients,
    ]) ?>

</div>
