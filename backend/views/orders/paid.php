<?php
/**
 * Created by PhpStorm.
 * User: murod
 * Date: 12.09.2016
 * Time: 13:52
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Частичная оплата';
?>

<?php $form = ActiveForm::begin();  ?>

    <p><b>Цена: </b><h1><?=$model->price;?></h1></p>

<?//vd($model);?>
    <p><b>Оплачено: </b><strong><?=$model->sum_paid;?></strong></p>

    <?
$sum_paid = $model->price-$model->sum_paid;
$model->sum_paid = $sum_paid;
?>

    <p><b>Осталось: </b><strong><?=$sum_paid;?></strong></p>

    <?
    echo $form->field($model, 'status_paid')
        ->dropDownList([
            '0' => 'Не оплачено',
            '1' => 'Оплачено частично',
            '2' => 'Оплачено полностью',
        ], $param = ['options' =>[ $model->status_paid => ['Selected' => true]]] );
    ?>

    <?= $form->field($model, 'sum_paid')->textInput() ?>

    <div class="form-group">
        <label for="date_paid">Дата окончания оплаты</label>
        <br>
        <input type="date" name="date_paid" class="form-control" value="<?= date('Y-m-d',($model->date_paid>0)? $model->date_paid : time() );?>" />
    </div>

    <div class="form-group">
        <?= Html::submitButton('Оплатить', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>