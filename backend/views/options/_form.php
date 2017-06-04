<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Options */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="options-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'model_price')->textInput() ?>

    <?= $form->field($model, 'elayner_price')->textInput() ?>

    <?= $form->field($model, 'attachment_price')->textInput() ?>

    <?= $form->field($model, 'checkpoint_price')->textInput() ?>

    <?= $form->field($model, 'reteiner_price')->textInput() ?>

    <?= $form->field($model, 'days_payd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'chat_time')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
