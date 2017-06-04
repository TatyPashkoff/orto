<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\UserType;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;


/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>

    <?php // echo $form->field($model, 'birth')->textInput(['maxlength' => true, 'type' => 'date']) ?>

    <label for="birth">Дата рождения</label>
    <br>
    <?php

    // <input type="date" class="form-control datepicker" name="Doctors[age]" value="<?= date('Y-m-d',$model->age);? >" />
    // usage without model
    //echo '<label>Дата рождения</label>';
    echo DatePicker::widget([
        'name' => 'User[birth]',
        'value' => @date('d-M-Y', $model->birth), // strtotime('+2 days')),
        'options' => ['placeholder' => 'Укажите дату рождения ...'],
        'pluginOptions' => [
            'language' => 'ru',
            'format' => 'dd-m-yyyy',
            'todayHighlight' => true
        ]
    ]);?>

    <?php echo $form->field($model, 'pasport_details')->textInput(['maxlength' => true]) ?>
    <?php echo $form->field($model, 'telefon')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <?//php echo $form->field($model, 'religion')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'gender')->radioList(['1' => 'Мужчина', '0' => 'Женщина']); ?>

    <?= $form->field($model, 'edu')->radioList(['1' => 'Проходил', '0' => 'Не проходил']);   ?>

    <?php // $form->field($model, 'regalies')->textArea(['rows'=>'4', 'maxlength' => true]) ?>


    <?= $form->field($model, 'password')->textInput(['maxlength' => true,'value'=>'']) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

