<?php
/**
 * Created by PhpStorm.
 * User: murod
 * Date: 08.09.2016
 * Time: 23:01
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="plans-form">
<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'version')->textInput(['readonly' => true]) ?>

    <?echo $form->field($model, 'approved')->dropDownList([
        0 => 'Не утверждён',
        1 => 'Утверждён'
    ]) ?>

    <?= $form->field($model, 'order_id')->textInput(['readonly' => true]) ?>
<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success' ]) ?>
</div>

<?php ActiveForm::end(); ?>
</div>