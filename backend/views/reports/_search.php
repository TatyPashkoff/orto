<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ReportsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reports-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'num') ?>

    <?= $form->field($model, 'doctor_id') ?>

    <?= $form->field($model, 'pacient_code') ?>

    <?php // echo $form->field($model, 'report_status') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'count_models') ?>

    <?php // echo $form->field($model, 'count_elayners') ?>

    <?php // echo $form->field($model, 'count_attachment') ?>

    <?php // echo $form->field($model, 'count_checkpoint') ?>

    <?php // echo $form->field($model, 'count_reteiners') ?>

    <?php // echo $form->field($model, 'comments') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
