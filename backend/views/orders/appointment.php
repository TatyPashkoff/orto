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

        <?php if(true || $role==2 || $role == 4){  // назначение техников для уровней только мед директор и админ ?>

            <div class="form-group field-orders-level_1_doctor_id has-success">
                <label class="control-label" for="orders-level_1_doctor_id">Техник / врач для уровня 1 (оценка качества и обработка)</label>

                <select id="orders-level_1_doctor_id" class="form-control" name="Orders[level_1_doctor_id]">
                    <option value="">...</option>
                    <?php
                    $docs = '';
                    $doctors = backend\models\Doctors::find()->where(['status'=>1])
                        ->andWhere(['in', 'type', ['2', '0']])->all();

                    foreach($doctors as $doc){
                        $sel = ($doc->id==$model->level_1_doctor_id)?'selected':'';
                        $docs .= '<option value="'.$doc->id .'" '.$sel.'>'.$doc->firstname . ' '. $doc->lastname . ' ' . $doc->thirdname .'</option>';
                    }
                    echo $docs;

                    ?>
                </select>
                <div class="help-block"></div>
            </div>

            <div class="form-group field-orders-level_2_doctor_id has-success">
                <label class="control-label" for="orders-level_2_doctor_id">Техник / врач для уровня 2 (сканирование модели)</label>
                <select id="orders-level_2_doctor_id" class="form-control" name="Orders[level_2_doctor_id]">
                    <option value="">...</option>
                    <?php
                    $docs ='';
                    foreach($doctors as $doc){
                        $sel = ($doc->id==$model->level_2_doctor_id)?'selected':'';
                        $docs .= '<option value="'.$doc->id .'" '.$sel.'>'.$doc->firstname . ' '. $doc->lastname . ' ' . $doc->thirdname .'</option>';
                    }
                    echo $docs;

                    ?>
                </select>
                <div class="help-block"></div>
            </div>

            <div class="form-group field-orders-level_3_doctor_id has-success">
                <label class="control-label" for="orders-level_3_doctor_id">Техник / врач для уровня 3 (моделирование и разработка виртуального плана)</label>
                <select id="orders-level_3_doctor_id" class="form-control" name="Orders[level_3_doctor_id]">
                    <option value="">...</option>
                    <?php
                    $docs ='';
                    foreach($doctors as $doc){
                        $sel = ($doc->id==$model->level_3_doctor_id)?'selected':'';
                        $docs .= '<option value="'.$doc->id .'" '.$sel.'>'.$doc->firstname . ' '. $doc->lastname . ' ' . $doc->thirdname .'</option>';
                    }
                    echo $docs;
                    ?>
                </select>
                <div class="help-block"></div>
            </div>

            <div class="form-group field-orders-level4_doctor_id has-success">
                <label class="control-label" for="orders-level_4_doctor_id">Техник / врач для уровня 4 (проверка и утверждение)</label>
                <select id="orders-level_4_doctor_id" class="form-control" name="Orders[level_4_doctor_id]">
                    <option value="">...</option>
                    <?php
                    $docs ='';
                    foreach($doctors as $doc){
                        $sel = ($doc->id==$model->level_4_doctor_id)?'selected':'';
                        $docs .= '<option value="'.$doc->id .'" '.$sel.'>'.$doc->firstname . ' '. $doc->lastname . ' ' . $doc->thirdname .'</option>';
                    }
                    echo $docs;
                    ?>
                </select>
                <div class="help-block"></div>
            </div>

            <div class="form-group field-orders-level_5_doctor_id has-success">
                <label class="control-label" for="orders-level_5_doctor_id">Техник / врач для уровня 5 (отправка)</label>
                <select id="orders-level_5_doctor_id" class="form-control" name="Orders[level_5_doctor_id]">
                    <option value="">...</option>
                    <?php
                    $docs ='';
                    foreach($doctors as $doc){
                        $sel = ($doc->id==$model->level_5_doctor_id)?'selected':'';
                        $docs .= '<option value="'.$doc->id .'" '.$sel.'>'.$doc->firstname . ' '. $doc->lastname . ' ' . $doc->thirdname .'</option>';
                    }
                    echo $docs;
                    ?>
                </select>
                <div class="help-block"></div>
            </div>


        <?php } // назначение техников на уровни 1-5 ?>


        <?php
/*        // установка статуса и вывод сообщения о состоянии
        // установка статусов по уровням только для техников, мед. директора и админ

        if( $model->level_1_doctor_id == $user_id ) {
            echo $form->field($model, 'level_1_status')->dropDownList([
                '0' => 'Возврат',
                '1' => 'Отправить',
            ], $param = ['options' =>[ $model->level_1_status => ['selected' => true]]] );
            echo $form->field($model, 'level_1_result')->textarea(['rows' => 5]);
        } */?><!--


        <?php /* if( $model->level_2_doctor_id == $user_id ) {
            echo $form->field($model, 'level_2_status')->dropDownList([
                '0' => 'Возврат',
                '1' => 'Отправить',
            ], $param = ['options' =>[ $model->level_2_status => ['selected' => true]]] );
            echo $form->field($model, 'level_2_result')->textarea(['rows' => 5]);
        } */?>

        <?php /*if( $model->level_3_doctor_id == $user_id ) {
            echo $form->field($model, 'level_3_status')->dropDownList([
                '0' => 'Возврат',
                '1' => 'Отправить',
            ], $param = ['options' =>[ $model->level_3_status => ['selected' => true]]] );
            echo $form->field($model, 'level_3_result')->textarea(['rows' => 5]);
        } */?>

        <?php /*if( $model->level_4_doctor_id == $user_id ) {
            echo $form->field($model, 'level_4_status')->dropDownList([
                '0' => 'Возврат',
                '1' => 'Отправить',
            ], $param = ['options' =>[ $model->level_4_status => ['selected' => true]]] );
            echo $form->field($model, 'level_4_result')->textarea(['rows' => 5]);
        } */?>

        --><?php /*if( $model->level_5_doctor_id == $user_id ) {
            echo $form->field($model, 'level_5_status')->dropDownList([
                '0' => 'Возврат',
                '1' => 'Отправить',
            ], $param = ['options' =>[ $model->level_5_status => ['selected' => true]]] );
            echo $form->field($model, 'level_5_result')->textarea(['rows' => 5]);
        } */?>

    </div>

        <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////- -->

        <br>
        <div class="form-group">
            <?//= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
            <?php if (!$model->isNewRecord): ?>
                <?= Html::a(Yii::t('app', 'Print'), ['print', 'id' => $model->id], ['class' => 'btn btn-default print-btn']); ?>
            <?php endif; ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>