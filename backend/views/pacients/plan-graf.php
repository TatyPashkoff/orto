<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

use backend\models\Doctors;
use backend\models\Price;
use backend\models\Plans;
use backend\models\User;
use backend\models\Orders;
use backend\models\Clinics;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PacientsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'План график оплаты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pacients-index">

    <h1><?= (Yii::$app->controller->action->id == 'pay')? Html::encode('Оплата пациентов') : Html::encode($this->title) ?></h1>

<?php
   // $role = Yii::$app->user->identity->role;
    $role = !empty(Yii::$app->user->identity) ? Yii::$app->user->identity->role : NULL;
    // разрешение на добавление дат в план-график
    // бесплатно=2
    // разрешено, платно и оплачено на сумму больше 0
    $enable_plan =  ( $plan_graph->var_paid_vp == 2) ||
                    ($model->vp_enable && $plan_graph->var_paid_vp == 1 &&
                        $plan_graph->status_paid_vp == 1 )  ;
//     echo (int) $enable_plan . ' ' . $plan_graph->var_paid_vp . ' ' . $plan_graph->sum_paid_vp ;
/*echo '<pre>';
print_r($plan_graph);
echo '</pre>';*/


?>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <p><strong>Ф.И.О пациента:</strong> <?=$model->name?></p>
    <?php if($enable_plan) { ?>

        <p><strong>Пакет:</strong> <?= $model->getPaket(); // Price::getPaketName($plan_graph->paket_id) ?></p>
        <p><strong>Стоимость пакета по прайсу:</strong> <?= $model->getPaketSum(); //Price::getPrice($plan_graph->paket_id) ?></p>
        <p><strong>Стоимость пакета с учетом скидки:</strong> <?=  /* Price::getPrice($plan_graph->paket_id)*/ $model->getPaketSum() - $plan_graph->sum_discount ?></p>
        <p><?= $form->field($plan_graph, 'sum_discount')->textInput(['disabled' => $role != 2 && $role != 4 ? true : false]) ?></p>

    <?php
    } // enable_plan

    echo $form->field($plan_graph, 'var_paid_vp')->dropDownList([
        '2' => 'Бесплатно',
        '1' => 'Платно',
        ], $param = ['prompt' => 'Укажите вариант оплаты за ВП', 'options' => [$plan_graph->var_paid_vp => ['selected' => true]], 'disabled' => $role != 2 && $role != 4 ? true : false]);

    ?>
    <p><?=$form->field($plan_graph, 'sum_paid_vp')->textInput(['disabled' => ($role!=2 && $role!=3 && $role!=4) || $plan_graph->var_paid_vp==2 ? true : false]) ?></p>



    <?php
        echo $form->field($plan_graph, 'status_paid_vp')->dropDownList([
            '0' => 'Не оплачено',
            '1' => 'Оплачено',
        ], $param = [ 'options' => [$plan_graph->status_paid_vp => ['selected' => true]], 'disabled' => $role != 2 && $role != 3 && $role != 4 ? true : false]);

    // нужно состояние готовности ВП от техника,
    // $plan = Plans::findOne($model->vp_id);
    //echo $model->vp_enable; // уст-ет админ

    // если vp_id у пациентв установлен, значит план график создан
    if( $enable_plan ){

        echo $form->field($plan_graph, 'var_paid')->dropDownList([
            '3' =>   'Бесплатно',
            '1' =>   'Рассрочка',
            '2' =>   'Полная оплата',
        ], $param = ['prompt'=>'Укажите вариант оплаты','options' =>[ $plan_graph->var_paid => ['selected' => true]],'disabled' =>  $role!=2 && $role!=4 ? true : false] );

        // если ВП создан и подтвержден, разрешить админу добавлять новые даты в план график
    ?>
    <table class="table date_paid">
        <tr>
           <td>
               <?php
               echo '<label>Дата предоплаты</label>';
               echo DatePicker::widget([
                   'name' => 'Payments[date_downpay]',
                   'value' => date('d-m-Y', strtotime($plan_graph->date_downpay)), // strtotime('+2 days')),
                   'options' => ['placeholder' => 'Дата предоплаты', 'disabled' => $role!=2 && $role!=4 ? true : false],
                   'pluginOptions' => [
                       'language' => 'ru',
                       'format' => 'dd-mm-yyyy',
                       'autoclose' => true,
                       'todayHighlight' => true
                   ]
               ]);
               ?>
           </td>
            <td><?=$form->field($plan_graph, 'downpay')->textInput(['disabled' => $role!=2 && $role!=4  ? true : false]) ?></td>
            <td>
                <?php
               // статус подтверждения ПРЕДОплаты задает бухгалтер
               echo $form->field($plan_graph, 'status_paid')->dropDownList(
                   ['0'=>'Не оплачено','1'=>'Оплачено'],
                   $param = ['options' =>[ $plan_graph->status_paid => ['Selected' => true]], 'disabled' => $role!=3 && $role!=2 && $role!=4 ? true : false ]
               );
               //$form->field($plan_graph, 'status_paid')->textInput(['disabled' => $role<=1 ? true : false]) ?></td>
           <td></td>
        </tr>
    <?php
    
    $m=0;
    $paid = 0;
    foreach($plan_items as $item){ //($m=1;$m<=$mcnt;$m++){
        //$date_month = 'date_month' . $m;
        //$pay_month = 'pay_month' . $m;

        // если найдена первая не подтвержденная дата
        if( $role == 3 ) {
            $item->status_paid == 0;
            // если найдена вторая не подтвержденная дата не показывать остальное
            if ($paid == 2) break;
            $paid++;
        }

        $m++;
    ?>
        <tr>
           <td>
               <?php
               echo '<label>Дата оплаты за месяц '.$m.'</label>';
               echo DatePicker::widget([
                   'name' => 'PaymentsItemsNew['.$item->id.'][date]',
                   'value' => date('d-m-Y', strtotime($item->date)), // strtotime('+2 days')),
                   'options' => ['placeholder' => 'Дата предоплаты' ,'disabled' => $role!=2 && $role!=4 ? true : false  ],
                   'pluginOptions' => [
                       'language' => 'ru',
                       'format' => 'dd-mm-yyyy',
                       'autoclose' => true,
                       'todayHighlight' => true
                   ]
               ]);
               ?>
           </td>
           <td><label>Оплата за месяц <?=$m?></label><input type="text" name="PaymentsItemsNew[<?=$item->id?>][sum]" value="<?=$item->sum?>" class="form-control" <?= $role!=2 && $role!=4 ? 'disabled' : '' ?> ><?php // =$form->field($plan_graph, $pay_month )->textInput() ?></td>
           <?php /* <td><label>Сумма с учетом скидки</label><input type="text" name="PaymentsItemsNew[<?=$item->id?>][sum_discount]" value="<?=$item->sum_discount?>" class="form-control" <?= $role!=2 && $role!=4 ? 'disabled' : '' ?> ><?php // =$form->field($plan_graph, $pay_month )->textInput() ?></td>
            */?>
           <td>
                <?php // статус подтверждения Оплаты задает бухгалтер ?>
                <label>Подтверждение оплаты</label>
                <select name="PaymentsItemsNew[<?=$item->id?>][status_paid]" <?=($role!=2 && $role!=3 && $role!=4) || ( (int)$item->sum == 0)?' disabled':'' ?> class="form-control">
                    <option value="0" <?=$item->status_paid==0? ' selected':''?>>Не оплачено</option>
                    <option value="1" <?=$item->status_paid==1? ' selected':''?>>Оплачено</option>
                </select>
                <?php //$form->field($plan_graph, 'status_paid')->textInput(['disabled' => $role<=1 ? true : false]) ?>
           <td>
        </tr>
    <?php } ?>

    </table>

    <?php if($role==2 || $role==4 ) { ?>
        <div class="btn btn-success" id="add_date_paid">Добавить дату оплаты</div>
    <?php } ?>

    <br><br>

<?php }elseif($role==2 || $role==4){ // $plan_enable ?>
    <p>После создания виртуального плана и его подтверждения, можно добавлять новые даты в план график</p>
<?php } ?>



    <?php if( ($role==1 || $role==2 || $role==3|| $role==4) &&  $model->dogovor != '' ) { // есть права и договор существует ?>
        <a class="btn btn-primary"  download href="/pacients/download-dogovor?id=<?= $model->id?>">Скачать договор</a>
        <a class="btn btn-primary"  href="/pacients/delete-dogovor?id=<?= $model->id?>">Удалить договор</a>
    <?php }  ?>
    <br><br>
     <?php if($role==2 || $role==4){
        echo $form->field($model, 'dogovor')->fileInput();
     } ?>

    <br>

    <div class="form-group">
        <?php if($role !=0 && $role !=1 ) echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        <a  href="<?=Url::to(['pacients/update?id='. $model->id]) ?>" class="btn btn-primary">Назад к пациенту</a>

    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php
$cur_date = date('Y-m-d',time());
$script = "
$('document').ready(function(){
var odp_id = 0;
    $('#add_date_paid').click(function(){
        odp_id++; 
        $( '<tr class=\"add_row\" id='+odp_id+'><td><input type=\"text\" name=\"PaymentsItemsNew[new][date][]\" value=\"$cur_date\"></td><td><input type=\"text\" name=\"PaymentsItemsNew[new][sum][]\"></td><td><span class=\"delete_odp glyphicon glyphicon-trash\" id='+odp_id+' style=\"cursor:pointer\"></span></td></tr>' ).appendTo( '.table.date_paid' );
    });
    $(document).on('click','.delete_odp',function(){
        $( 'tr#'+ $(this).attr('id') + '.add_row').remove();
    });
    $(document).on('click','.kv-date-remove',function(){
        $(this).parent().parent().parent().css('opacity','0.4');
    });
     $(document).on('change','.krajee-datepicker',function(){
        $(this).parent().parent().parent().css('opacity','1');
    });
    
    
});";

$this->registerJs($script, yii\web\View::POS_END);