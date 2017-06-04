<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OrdersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'num') ?>

    <?= $form->field($model, 'group_id') ?>

    <?= $form->field($model, 'doctor_id') ?>

    <?php // echo $form->field($model, 'clinics_id') ?>

    <?php // echo $form->field($model, 'pacient_code') ?>

    <?php // echo $form->field($model, 'date_paid') ?>

    <?php // echo $form->field($model, 'type_paid') ?>

    <?php // echo $form->field($model, 'status_paid') ?>

    <?php // echo $form->field($model, 'status_object') ?>

    <?php // echo $form->field($model, 'order_status') ?>

    <?php // echo $form->field($model, 'admin_check') ?>

    <?php // echo $form->field($model, 'status_agreement') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'scull_type') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'discount') ?>

    <?php // echo $form->field($model, 'sum_paid') ?>

    <?php // echo $form->field($model, 'count_models') ?>

    <?php // echo $form->field($model, 'count_elayners') ?>

    <?php // echo $form->field($model, 'count_attachment') ?>

    <?php // echo $form->field($model, 'count_checkpoint') ?>

    <?php // echo $form->field($model, 'count_reteiners') ?>

    <?php // echo $form->field($model, 'count_models_vp') ?>

    <?php // echo $form->field($model, 'count_elayners_vp') ?>

    <?php // echo $form->field($model, 'count_attachment_vp') ?>

    <?php // echo $form->field($model, 'count_checkpoint_vp') ?>

    <?php // echo $form->field($model, 'count_reteiners_vp') ?>

    <?php // echo $form->field($model, 'count_models_vc') ?>

    <?php // echo $form->field($model, 'count_elayners_vc') ?>

    <?php // echo $form->field($model, 'count_attachment_vc') ?>

    <?php // echo $form->field($model, 'count_checkpoint_vc') ?>

    <?php // echo $form->field($model, 'count_reteiners_vc') ?>

    <?php // echo $form->field($model, 'count_models_nc') ?>

    <?php // echo $form->field($model, 'count_elayners_nc') ?>

    <?php // echo $form->field($model, 'count_attachment_nc') ?>

    <?php // echo $form->field($model, 'count_checkpoint_nc') ?>

    <?php // echo $form->field($model, 'count_reteiners_nc') ?>

    <?php // echo $form->field($model, 'level_1_doctor_id') ?>

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

    <?php // echo $form->field($model, 'comments') ?>

    <?php // echo $form->field($model, 'files') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
