<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OptionsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="options-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'model_price') ?>

    <?= $form->field($model, 'elayner_price') ?>

    <?= $form->field($model, 'attachment_price') ?>

    <?= $form->field($model, 'checkpoint_price') ?>

    <?php // echo $form->field($model, 'reteiner_price') ?>

    <?php // echo $form->field($model, 'days_payd') ?>

    <?php // echo $form->field($model, 'chat_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
