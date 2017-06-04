<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AssignSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="assign-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'num') ?>

    <?= $form->field($model, 'pacient_id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'level_1_doctor_id') ?>

    <?php // echo $form->field($model, 'level_2_doctor_id') ?>

    <?php // echo $form->field($model, 'level_3_doctor_id') ?>

    <?php // echo $form->field($model, 'level_4_doctor_id') ?>

    <?php // echo $form->field($model, 'level_5_doctor_id') ?>

    <?php // echo $form->field($model, 'level_1_status') ?>

    <?php // echo $form->field($model, 'level_2_status') ?>

    <?php // echo $form->field($model, 'level_3_status') ?>

    <?php // echo $form->field($model, 'level_4_status') ?>

    <?php // echo $form->field($model, 'level_5_status') ?>

    <?php // echo $form->field($model, 'level_1_result') ?>

    <?php // echo $form->field($model, 'level_2_result') ?>

    <?php // echo $form->field($model, 'level_3_result') ?>

    <?php // echo $form->field($model, 'level_4_result') ?>

    <?php // echo $form->field($model, 'level_5_result') ?>

    <?php // echo $form->field($model, 'level_1_date') ?>

    <?php // echo $form->field($model, 'level_2_date') ?>

    <?php // echo $form->field($model, 'level_3_date') ?>

    <?php // echo $form->field($model, 'level_4_date') ?>

    <?php // echo $form->field($model, 'level_5_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
