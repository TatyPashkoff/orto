<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\Doctors */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="doctors-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
        $roles = [
            '' => '',
            '0' => 'Зубной техник',
            '1' => 'Врач',
            '2' => 'Мед. директор',
            '3' => 'Бухгалтер',
            '4' => 'Админ'
        ];
        echo '<strong>Тип пользователя:</strong> ' . $roles[ $model->type ];
    ?>
    <br><br>


    <?= $form->field($model, 'clinics')
            ->dropDownList(
                ArrayHelper::map( backend\models\Clinics::find()->select(['id','title'])->all(), 'id', 'title'),
                    ['options' =>[ $model->clinic_id => ['Selected' => true,]] , 'multiple'=>'multiple']
            );
    ?>

    <?= $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?>

    <?php //$form->field($model, 'age')->textInput(['maxlength' => true]) ?>
    <label for="age">Дата рождения</label>
    <br>
    <?php

    // <input type="date" class="form-control datepicker" name="Doctors[age]" value="<?= date('Y-m-d',$model->age);? >" />
     // usage without model
    //echo '<label>Дата рождения</label>';
    echo DatePicker::widget([
    'name' => 'Doctors[age]',
    'value' => @date('d-M-Y', $model->age), // strtotime('+2 days')),
    'options' => ['placeholder' => 'Укажите дату рождения ...'],
    'pluginOptions' => [
    'language' => 'ru',
    'format' => 'dd-m-yyyy',
    'todayHighlight' => true
    ]
    ]);?>

    <br><br>

    <?php
    /*
    $form->field($model, 'type')
        ->dropDownList([
            '0' => 'Зубной техник',
            '1' => 'Врач',
            '2' => 'Мед. директор',
            '3' => 'Бухгалтер',
        ], $param = ['options' =>[ $model->type=> ['Selected' => true]]]
        ); */
    ?>

    <?= $form->field($model, 'edu')->radioList(['1' => 'Проходил', '0' => 'Не проходил']);   ?>

    <?= $form->field($model, 'gender')->radioList(['1' => 'Мужчина', '0' => 'Женщина']); ?>

    <?php // $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'passport')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'regalies')->textArea(['rows'=>'4', 'maxlength' => true]) ?>

    <?= $form->field($model, 'password')->textInput(['maxlength' => true,'value'=>'']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
