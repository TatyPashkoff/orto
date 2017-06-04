<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PacientsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pacients-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'doctor_id') ?>

    <?= $form->field($model, 'order_id') ?>

    <?= $form->field($model, 'vp_id') ?>

    <?= $form->field($model, 'code') ?>

    <?php // echo $form->field($model, 'age') ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'alert_date') ?>

    <?php // echo $form->field($model, 'alert_msg') ?>

    <?php // echo $form->field($model, 'firstname') ?>

    <?php // echo $form->field($model, 'lastname') ?>

    <?php // echo $form->field($model, 'thirdname') ?>

    <?php // echo $form->field($model, 'type_paid') ?>

    <?php // echo $form->field($model, 'var_paid') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'diagnosis') ?>

    <?php // echo $form->field($model, 'result') ?>

    <?php // echo $form->field($model, 'files') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
