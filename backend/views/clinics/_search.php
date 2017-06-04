<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ClinicsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="clinics-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'adress') ?>

    <?= $form->field($model, 'phone') ?>

    <?= $form->field($model, 'contacts_admin') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'model_price') ?>

    <?php // echo $form->field($model, 'elayner_price') ?>

    <?php // echo $form->field($model, 'attachment_price') ?>

    <?php // echo $form->field($model, 'checkpoint_price') ?>

    <?php // echo $form->field($model, 'reteiner_price') ?>

    <?php // echo $form->field($model, 'model_discount') ?>

    <?php // echo $form->field($model, 'elayner_discount') ?>

    <?php // echo $form->field($model, 'attachment_discount') ?>

    <?php // echo $form->field($model, 'checkpoint_discount') ?>

    <?php // echo $form->field($model, 'reteiner_discount') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
