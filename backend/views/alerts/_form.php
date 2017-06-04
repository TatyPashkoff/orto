<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use backend\models\User;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Alerts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="alerts-form">

    <?php $form = ActiveForm::begin(); ?>

        <?php

        if(! $model->isNewRecord){

            if( $from = User::findOne($model->doctor_id_from) ) {
                echo '<strong>Сообщение от:</strong> ' . $from->fullname;
            }
        ?>
        <br><br>
        <?php /* $form->field($model, 'doctor_id_to')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'doctor_id_from')->textInput(['maxlength' => true]) */ ?>

        <?= '<strong>Дата отправки:</strong> '.date('d-m-Y H:i',$model->date) ?>
        <br><br>
        <strong>Текст сообщения:</strong>
        <hr>
        <?= $model->text ?>
        <hr>
        <?php  /* echo $form->field($model, 'read_status')  // статус заказа 1-отпрвлен
        ->dropDownList([
            //'0' => 'Не прочитано',
            '1' => 'Прочитано',
        ], $param = ['options' =>[ $model->read_status => ['selected' => true]]] ); */ ?>
        <input type="hidden" name="Alerts[read_status]" value="1" />
        <div class="form-group">
            <?= Html::submitButton('Назад', ['class' => 'btn btn-success' ]) ?>
        </div>


    <?php }else{  // новое сообщение, возможность написать самому себе ?>

        <?php
            echo $form->field($model, 'doctor_id_to')->dropDownList(
            //ArrayHelper::map( backend\models\Orders::find()->where(['creater'=>$user_id])->all() , 'id','num'),
            ArrayHelper::map( backend\models\User::find()->all() , 'id','fullname'),
            $param = ['options' =>[ $model->doctor_id_to => ['Selected' => true]], 'prompt' => 'Укажите получателя сообщения...',]
            ); ?>

        <label>Дата</label>
        <?php echo DatePicker::widget([
            'name' => 'Alerts[date]',
            'value' => date('d-m-Y', $model->date ? $model->date : time() ), // strtotime('+2 days')),
            'options' => ['placeholder' => 'Дата'],
            'pluginOptions' => [
            'language' => 'ru',
            'format' => 'dd-mm-yyyy',
            'todayHighlight' => true
            ]
            ]);
            echo '<br>';

            echo $form->field($model, 'type')  // статус заказа 1-отпрвлен
            ->dropDownList([
            '0' => 'Сообщение',
            '1' => 'Напоминание по дате',
            ], $param = ['options' =>[ $model->type => ['selected' => true]]] );
        ?>
            <?= $form->field($model, 'text')->textArea(['rows'=>'5','maxlength' => true]) ?>
            <input type="hidden" name="Alerts[read_status]" value="0" />
            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success' ]) ?>
            </div>

    <?php } ?>
    <?php ActiveForm::end(); ?>

</div>
