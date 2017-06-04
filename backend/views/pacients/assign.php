<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use backend\models\Assign;
use backend\models\Pacients;


/*
$this->title = 'Частичное оплата';
?>

<?php $form = ActiveForm::begin();  ?>

<p><b>Цена: </b><h1><?=$model->status;?></h1></p>



<?
echo $form->field($assign, 'num')->textInput();

echo $form->field($model, 'status')
    ->dropDownList([
        '0' => 'Не оплачено',
        '1' => 'Оплачено частично',
        '2' => 'Оплачено полностью',
    ], $param = ['options' =>[ $model->status => ['Selected' => true]]] );
?>


<div class="form-group">
    <?= Html::submitButton('Оплатить', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end();

return;

*/


/*
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\UploadFile;
use yii\helpers\ArrayHelper;
// use backend\models\Options;
*/
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

<div class="assign-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php

//    print_r($assign );
 //   print_r($model);
    //exit;

       // echo 'Номер заказа: ' . $model->num ;
        echo '<strong>Пациент:</strong> ' . $pacient->name;

   // echo $role;

    ?>
    <br><br>
    <?php
    // группа для связи заказов для расчета по всем заказам кол-во и сумм
    // для связи используется id модели  и num - номер заказа
/*
    echo $form->field($model, 'group_id')->dropDownList(
        ArrayHelper::map( backend\models\Orders::find()->where(['id'=>$model->id])->all() , 'id','num'),
        $param = ['options' =>[ $model->group_id => ['Selected' => true]], 'prompt' => 'Без привязки',]
    );


    ?>

    <?= $form->field($model, 'doctor_id')->hiddenInput(['maxlength' => true, 'value'=> Yii::$app->user->id ])->label(false) ?>
    <?= $form->field($model, 'clinics_id')->hiddenInput(['maxlength' => true, 'value'=> backend\models\Doctors::findOne(Yii::$app->user->id)->clinic_id ])->label(false) ?>

    <?php */
//print_r($model);
   /*echo 'pid ' . $model->pacient_id;
    // выбор пациента из списка
    if( $assign = Assign::find()->where(['pacient_id'=>$model->pacient_id,'status'=>1])
        ->andWhere(['or',
            [ 'level_1_doctor_id' => $user_id ],
            [ 'level_2_doctor_id' => $user_id ],
            [ 'level_3_doctor_id' => $user_id ],
            [ 'level_4_doctor_id' => $user_id ],
            [ 'level_5_doctor_id' => $user_id ],
        ])->all()){

        foreach ($assign as $item) {
            $pacients_id[] = $item->pacient_id;
        }
    }else{ // нет пациентов
        $pacients_id = [];
    }

    print_r($pacients_id);

    echo $form->field($model, 'pacient_id')->dropDownList(
        ArrayHelper::map( Pacients::find()->where(['in','doctor_id',$pacients_id])->all() , 'code','name'),
        $param = ['options' =>[ $model->pacient_id => ['Selected' => true]]]
    ); */?>

    <?php // $form->field($model, 'status')->textInput() // подтвержден договор ?>

    <?php if( $role==2 || $role == 4){  // назначение техников для уровней только мед директор и админ ?>

        <div class="form-group field-orders-level_1_doctor_id has-success">
            <label class="control-label" for="orders-level_1_doctor_id">Техник / врач для уровня 1 (оценка качества и обработка)</label>
            <select id="orders-level_1_doctor_id" class="form-control" name="Assign[level_1_doctor_id]">
                <option value="">Не назначен</option>
                <?php
                $docs = '';
                $doctors = backend\models\User::find()->where(['status'=>1])
                    ->andWhere(['!=','role','3'])->all();

                /*echo '<pre>';
                print_r($model);
                echo '</pre>';*/

                foreach($doctors as $doc){
                    $sel = ($doc->id==$model->level_1_doctor_id)?'selected':'';
                    $docs .= '<option value="'.$doc->id .'" '.$sel.'>'. $doc->fullname . '</option>';
                }
                echo $docs;

                ?>
            </select>
            <div class="help-block"></div>
        </div>

        <div class="form-group field-orders-level_2_doctor_id has-success">
            <label class="control-label" for="orders-level_2_doctor_id">Техник / врач для уровня 2 (сканирование модели)</label>
            <select id="orders-level_2_doctor_id" class="form-control" name="Assign[level_2_doctor_id]">
                <option value="">Не назначен</option>
                <?php
                $docs ='';
                foreach($doctors as $doc){
                    $sel = ($doc->id==$model->level_2_doctor_id)?'selected':'';
                    $docs .= '<option value="'.$doc->id .'" '.$sel.'>'. $doc->fullname .'</option>';
                }
                echo $docs;

                ?>
            </select>
            <div class="help-block"></div>
        </div>

        <div class="form-group field-orders-level_3_doctor_id has-success">
            <label class="control-label" for="orders-level_3_doctor_id">Техник / врач для уровня 3 (моделирование и разработка виртуального плана)</label>
            <select id="orders-level_3_doctor_id" class="form-control" name="Assign[level_3_doctor_id]">
                <option value="">Не назначен</option>
                <?php
                $docs ='';
                foreach($doctors as $doc){
                    $sel = ($doc->id==$model->level_3_doctor_id)?'selected':'';
                    $docs .= '<option value="'.$doc->id .'" '.$sel.'>'. $doc->fullname .'</option>';
                }
                echo $docs;
                ?>
            </select>
            <div class="help-block"></div>
        </div>

        <div class="form-group field-orders-level4_doctor_id has-success">
            <label class="control-label" for="orders-level_4_doctor_id">Техник / врач для уровня 4 (проверка и утверждение)</label>
            <select id="orders-level_4_doctor_id" class="form-control" name="Assign[level_4_doctor_id]">
                <option value="">Не назначен</option>
                <?php
                $docs ='';
                foreach($doctors as $doc){
                    $sel = ($doc->id==$model->level_4_doctor_id)?'selected':'';
                    $docs .= '<option value="'.$doc->id .'" '.$sel.'>'. $doc->fullname .'</option>';
                }
                echo $docs;
                ?>
            </select>
            <div class="help-block"></div>
        </div>

        <div class="form-group field-orders-level_5_doctor_id has-success">
            <label class="control-label" for="orders-level_5_doctor_id">Техник / врач для уровня 5 (отправка)</label>
            <select id="orders-level_5_doctor_id" class="form-control" name="Assign[level_5_doctor_id]">
                <option value="">Не назначен</option>
                <?php
                $docs ='';
                foreach($doctors as $doc){
                    $sel = ($doc->id==$model->level_5_doctor_id)?'selected':'';
                    $docs .= '<option value="'.$doc->id .'" '.$sel.'>'. $doc->fullname .'</option>';
                }
                echo $docs;
                ?>
            </select>
            <div class="help-block"></div>
        </div>

    <?php } // назначение техников на уровни 1-5 ?>

    <hr>

    <?php
    // установка статуса и вывод сообщения о состоянии
    // установка статусов по уровням только для техников, мед. директора и админ
   // echo '<br>doc='.$model->level_1_doctor_id . ' ' . $role . ' ' . $user_id;

    if($role==0) {
        $paciens_count = 0;
        if ($model->level == 1 && $model->level_1_doctor_id == $user_id) {
            $paciens_count++;
            echo $form->field($model, 'level_1_status')->dropDownList([
                '2' => 'Возврат',
                '1' => 'Принять',
            ], $param = ['options' => [$model->level_1_status => ['selected' => true]]]);
            echo $form->field($model, 'level_1_result')->textarea(['rows' => 5]);
        } ?>
        <hr>
        <?php if ( $model->level == 2 && $model->level_2_doctor_id == $user_id) {
            $paciens_count++;
            echo $form->field($model, 'level_2_status')->dropDownList([
                '2' => 'Возврат',
                '1' => 'Принять',
            ], $param = ['options' => [$model->level_2_status => ['selected' => true]]]);
            echo $form->field($model, 'level_2_result')->textarea(['rows' => 5]);
        } ?>
        <hr>
        <?php if ( $model->level == 3 && $model->level_3_doctor_id == $user_id) {
            $paciens_count++;
            echo $form->field($model, 'level_3_status')->dropDownList([
                '2' => 'Возврат',
                '1' => 'Принять',
            ], $param = ['options' => [$model->level_3_status => ['selected' => true]]]);
            echo $form->field($model, 'level_3_result')->textarea(['rows' => 5]);
        } ?>
        <hr>
        <?php if ( $model->level == 4 && $model->level_4_doctor_id == $user_id) {
            $paciens_count++;
            echo $form->field($model, 'level_4_status')->dropDownList([
                '2' => 'Возврат',
                '1' => 'Принять',
            ], $param = ['options' => [$model->level_4_status => ['selected' => true]]]);
            echo $form->field($model, 'level_4_result')->textarea(['rows' => 5]);
        } ?>
        <hr>
        <?php if ( $model->level == 5 && $model->level_5_doctor_id == $user_id) {
            $paciens_count++;
            echo $form->field($model, 'level_5_status')->dropDownList([
                '2' => 'Возврат',
                '1' => 'Принять',
            ], $param = ['options' => [$model->level_5_status => ['selected' => true]]]);
            echo $form->field($model, 'level_5_result')->textarea(['rows' => 5]);
        }
        if($paciens_count == 0 ) echo '<p>Нет назначенных пациентов!</p>';
    }

    if( $role==2 ||$role==4 || ($role==0 && $paciens_count > 0 )) {
        ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Назначить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>


        <?php
    }

        ActiveForm::end(); ?>

</div>