<?php
/**
 * Created by PhpStorm.
 * User: murod
 * Date: 2016-08-23
 * Time: 11:58 AM
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\UploadFile;
use yii\helpers\ArrayHelper;
// use backend\models\Options;

/* @var $this yii\web\View */
/* @var $model backend\models\Orders */
/* @var $form yii\widgets\ActiveForm */

/* role
    0 - зубной техник
    1 - врач
    2 - мед. директор
    3 - бухгалтер
    4 - админ
 *
 *
 */
$role = Yii::$app->user->identity->role;
$user_id = Yii::$app->user->id;
?>

<div class="orders-form">
    <?php $form = ActiveForm::begin();  ?>

    <div class="tab-content">

        <?php
            echo $form->field($model, 'num')->textInput(['maxlength' => true, 'readonly'=>true]);
        ?>

        <?php
        // установка статуса и вывод сообщения о состоянии
        // установка статусов по уровням только для техников, мед. директора и админ

        if( $model->level_1_doctor_id == $user_id ) {
            echo "<h1>Оценка качества и обработка</h1>";
            echo $form->field($model, 'level_1_status')->dropDownList([
                '0' => 'Возврат',
                '1' => 'Отправить',
            ], $param = ['options' =>[ $model->level_1_status => ['selected' => true]]] );
            echo $form->field($model, 'level_1_result')->textarea(['rows' => 5]);
        } ?>


        <?php  if( $model->level_2_doctor_id == $user_id ) {
            echo "<h1>Сканирование модели</h1>";
            echo $form->field($model, 'level_2_status')->dropDownList([
                '0' => 'Возврат',
                '1' => 'Отправить',
            ], $param = ['options' =>[ $model->level_2_status => ['selected' => true]]] );
            echo $form->field($model, 'level_2_result')->textarea(['rows' => 5]);
        } ?>

        <?php if( $model->level_3_doctor_id == $user_id ) {
            echo "<h1>Моделирование и разработка виртуального плана</h1>";
            echo Html::a('Create Plans', ['plans/create', $model->id], ['class' => 'btn btn-success']);
            echo $form->field($model, 'level_3_status')->dropDownList([
                '0' => 'Возврат',
                '1' => 'Отправить',
            ], $param = ['options' =>[ $model->level_3_status => ['selected' => true]]] );
            echo $form->field($model, 'level_3_result')->textarea(['rows' => 5]);
        } ?>

        <?php if( $model->level_4_doctor_id == $user_id ) {
            echo "<h1>Проверка и утверждение</h1>";
            echo $form->field($model, 'level_4_status')->dropDownList([
                '0' => 'Возврат',
                '1' => 'Отправить',
            ], $param = ['options' =>[ $model->level_4_status => ['selected' => true]]] );
            echo $form->field($model, 'level_4_result')->textarea(['rows' => 5]);
        } ?>

        <?php if( $model->level_5_doctor_id == $user_id ) {
            echo "<h1>Отправка</h1>";
            echo $form->field($model, 'level_5_status')->dropDownList([
                '0' => 'Возврат',
                '1' => 'Отправить',
            ], $param = ['options' =>[ $model->level_5_status => ['selected' => true]]] );
            echo $form->field($model, 'level_5_result')->textarea(['rows' => 5]);
        } ?>

    </div>

    <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////- -->

    <br>
    <div class="form-group">
        <?//= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
        <?php if (!$model->isNewRecord): ?>
            <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-default print-btn']); ?>
        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

</div>