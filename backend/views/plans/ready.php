<?php
/**
 * Created by PhpStorm.
 * User: murod
 * Date: 08.09.2016
 * Time: 23:01
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->registerJs("
$(document).ready(function(){
        $('.ready').change(function(){
            if($(this).val() == 1){
                $('.comments').attr('disabled', 'disabled');
                $('.correct').attr('disabled', 'disabled');
            }else{
                $('.comments').removeAttr('disabled');
                $('.correct').removeAttr('disabled');
            }
        })
    })
");
$this->title = "Утвердить";
?>

<div class="plans-form">
    <?php $form = ActiveForm::begin(); ?>

    <p><b>№ заказа</b><h2><?=$model->order_id?></h2></p>
    <p><b>Версия</b><h2><?=$model->version?></h2></p>

    <?echo $form->field($model, 'ready')->dropDownList([
        0 => 'Не утверждён',
        1 => 'Утверждён'
    ], ['class' => 'form-control ready']) ?>

    <?= $form->field($model, 'correct')->dropDownList([
        0 => 'Не корректный',
        1 => 'Корректный'
    ], ['class' => 'form-control correct']) ?>

    <?= $form->field($model, 'comments')->textarea(['rows' => 6, 'class' => 'form-control comments']) ?>

    <div class="form-group">
        <?= Html::submitButton('Утвердить', ['class' => 'btn btn-success' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>