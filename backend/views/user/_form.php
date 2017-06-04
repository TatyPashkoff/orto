<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\UserType;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */

if(count($model->getErrors())) {

    print_r($model->getErrors());
}




?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

     <?php

    echo $form->field($model, 'role')
        ->dropDownList([
            '0' => 'Зубной техник',
            '1' => 'Врач',
            '2' => 'Мед. директор',
            '3' => 'Бухгалтер',
            '4' => 'Админ'
        ], $param = ['options' =>[ $model->role => ['Selected' => true]]]
    );
  ;
    ?>
    <?=  $form->field($model, 'username')->textInput(['maxlength' => true]);    ?>
    
    <?= $form->field($model, 'clinics')
            ->dropDownList(
                ArrayHelper::map( backend\models\Clinics::find()->select(['id','title'])->all(), 'id', 'title'),
                    ['options' =>[ $model->clinic_id => ['Selected' => true,]] , 'multiple'=>'multiple']
            );
    ?>

    <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'birth')->textInput(['maxlength' => 255, 'value' =>  (is_int($model->birth))?date('d.m.Y',$model->birth):$model->birth])?>

    <?php

        // usage without model
        echo '<label>Дата рождения</label>';
        echo DatePicker::widget([
            'name' => 'User[birth]',
            'value' => @date('d-M-Y', $model->birth ), // strtotime('+2 days')),
            'options' => ['placeholder' => 'Укажите дату рождения ...'],
            'pluginOptions' => [
                'language' => 'ru',
                'format' => 'dd-m-yyyy',
                'todayHighlight' => true
            ]
        ]);

    /*  $form->field($model, 'birth')->widget(DatePicker::classname(), [
        //'language' => 'ru',
        //'format' => 'yyyy-MM-dd',
    ])  */ ?>

    <br>

    <?php echo $form->field($model, 'pasport_details')->textInput(['maxlength' => true]) ?>
    <?php echo $form->field($model, 'telefon')->textInput(['maxlength' => true]) ?>

    <?//php echo $form->field($model, 'religion')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'gender')->radioList(['1' => 'Мужчина', '0' => 'Женщина']); ?>

    <?= $form->field($model, 'edu')->radioList(['1' => 'Проходил', '0' => 'Не проходил']);   ?>

    <?php // $form->field($model, 'regalies')->textArea(['rows'=>'4', 'maxlength' => true]) ?>


    <?= $form->field($model, 'password')->textInput(['maxlength' => '','required' => $model->isNewRecord ? true : false ]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
