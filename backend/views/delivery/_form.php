<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model backend\models\Delivery */
/* @var $form yii\widgets\ActiveForm */

$role = Yii::$app->user->identity->role;
?>

<div class="delivery-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //  $form->field($model, 'order_id')->textInput(['maxlength' => true]) ;

    echo $form->field($model, 'pacient_id')
        ->dropDownList(
            //ArrayHelper::map(backend\models\Orders::find()->select(['id', 'num'])->where(['id' => $model->order_id])->all(), 'id', 'num')
            //$pacients
            ArrayHelper::map($pacients, 'id', 'name'),
            $param = ['prompt'=>'Укажите пациента','options' =>[ $model->pacient_id => ['selected' => true]]]
        );

    //$s = backend\models\User::find()->select(['id', 'fullname'])->where(['id' => $model->tehnik_id])->all();
    //print_r($s);
    /*echo $form->field($model, 'tehnik_id')
        ->dropDownList(
            // ArrayHelper::map(backend\models\User::find()->select(['id', 'fullname'])->where(['id' => $model->tehnik_id])->all(), 'id', 'fullname')
            ArrayHelper::map(backend\models\User::find()->select(['id', 'fullname'])->where(['role' => Yii::$app->user->identity->role])->all(), 'id', 'fullname')
        );
    */
    //$form->field($model, 'pacient_id')->textInput(['maxlength' => true]) ?>



    <?= $form->field($model, 'delivery_ready')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'delivery_all')->textInput(['maxlength' => true,'readonly'=>true]) ?>
    <label>Дата доставки</label>
    <?php // $form->field($model, 'date_delivery')->textInput();

    echo DatePicker::widget([
    'name' => 'Delivery[date_delivery]',
    'value' => date('d-M-Y', isset($model->date_delivery)?strtotime($model->date_delivery):time()), // strtotime('+2 days')),
    'options' => ['placeholder' => 'Дата доставки'],
        'pluginOptions' => [
        'language' => 'ru',
        'format' => 'dd-mm-yyyy',
        'todayHighlight' => true,
        'autoclose' => true,
    ]
    ]);
    ?>
    <br>
    <?php if($role==0 || $role==2 || $role == 4) { ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить и отправить оповещения', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php } ?>
    <?php ActiveForm::end(); ?>

</div>
<?php
$script = "
//alert(1)
$(document).ready(function(){
//alert(2)
    jQuery('#delivery-pacient_id').change(function(){        
        var id = jQuery(this).val();               
        jQuery.ajax({
			type: 'post',
            url: '/orders/getordercount',
            data: 'id='+id+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                //alert(data.status );
                if(data.status==1) { 
                    //alert( data.status + ' ' + data.count + 'Load was performed.');
                    jQuery('#delivery-delivery_all').val(data.count);
                }else{
                    jQuery('#delivery-delivery_all').val('0');
                }
            },
            error: function(data){                
                alert( data +' ERR');
            }
        });
    });
});
";

$this->registerJs($script, yii\web\View::POS_END);
