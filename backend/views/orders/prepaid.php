<?php
/**
 * Created by PhpStorm.
 * User: murod
 * Date: 05.09.2016
 * Time: 14:52
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php $form = ActiveForm::begin();  ?>
<?// статус оплачено - устанавливается бухгалтером
echo $form->field($model, 'status_paid')
    ->dropDownList([
        '0' => 'Не оплачено',
        '1' => 'Оплачено частично',
        '2' => 'Оплачено полностью',
    ], $param = ['options' =>[ $model->status_paid => ['Selected' => true]]] );
?>
<div class="form-group">
    <?= Html::submitButton('Подтвердить', ['class' => 'btn btn-success']) ?>
    <?php if (!$model->isNewRecord): ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-default print-btn']); ?>
        <?//= Html::button(Yii::t('app', 'Cancel'), ['class' => 'btn btn-default cancel-btn', 'data-url'=>Url::to(['orders/index'])]) ?>
    <?php endif; ?>
</div>
<?php ActiveForm::end(); ?>
