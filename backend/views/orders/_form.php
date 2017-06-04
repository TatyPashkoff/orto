 <?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\UploadFile;
use yii\helpers\ArrayHelper;
use backend\models\Assign;
use backend\models\Price;
use backend\models\Plans;
use backend\models\Delivery;
use backend\models\OrderPay;
use backend\models\Payments;
use kartik\date\DatePicker;

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
 */
    $role = Yii::$app->user->identity->role;
    $user_id = Yii::$app->user->id;
 
 
 
    ?>

    <div class="orders-form">


        <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>
        <ul class="nav nav-tabs">
            <?php /*
            <li><a data-toggle="tab" href="#home">Общее</a></li>
             if($role !=0){  // техники не должны видеть оплату ?>
                <li><a data-toggle="tab" href="#pay">Оплата</a></li>
            <?php } */ ?>

        </ul>
        


      <div class="tab-content">
          <div id="home" class="tab-pane fade in active">


                <label for="date">Дата заказа (доступна после выбора пациента и сохранения)</label>
                <br>


              <input type="text" name="Orders[date_paid]" value="<?= date('d-m-Y', strtotime($model->date )); // strtotime($_paid['date'])) ?>" class="form-control" readonly />

              <?php /*
              echo DatePicker::widget([
              'name' => 'Orders[date]',
              'value' => date('d-m-Y', $_paid['date']), // strtotime('+2 days')),
              'options' => ['placeholder' => 'Дата заказа'],
              'pluginOptions' => [
              'language' => 'ru',
              'format' => 'dd-mm-yyyy',
              'todayHighlight' => true
              ]
              ]); */
              ?>

                <br>
              <label>Номер заказа</label>

              <input type="text" value="<?= $model->num ?>" class="form-control" readonly />

              <?php // echo $form->field($model, 'num')->textInput(['maxlength' => true]); ?>
              <?php
    // группа для связи заказов для расчета по всем заказам кол-во и сумм
    // для связи используется creator    id модели  и num - номер заказа
            /* echo $form->field($model, 'group_id')->dropDownList(
                //ArrayHelper::map( backend\models\Orders::find()->where(['creater'=>$user_id])->all() , 'id','num'),
                ArrayHelper::map( backend\models\Orders::find()->where(['pacient_code'=>$model->pacient_code])->all() , 'id','num'),
                $param = ['options' =>[ $model->group_id => ['Selected' => true]], 'prompt' => 'Без привязки',]
                ); */
                ?>
                <?= $form->field($model, 'doctor_id')->hiddenInput(['maxlength' => true, 'value'=> Yii::$app->user->id ])->label(false) ?>

                <?php

                // нужны разрешения на создание заказа для пациентов
                // выбор пациента из списка
                echo $form->field($model, 'pacient_code')->dropDownList(
                        ArrayHelper::map( backend\models\Pacients::find()->where(['id'=>$pacients_id])->all() , 'id', function($model, $defaultValue) {
                            return $model->name;
                        }),$param = ['options' =>[ $model->pacient_code => ['Selected' => true]], 'prompt' => 'Выберите значения...' ]
                    );

                //$vp = Plans::find()->where(['pacient_id'=>$model->pacient->id])->all();
                //print_r($vp);

                // сделать динамически подгрузку ВП на ajax ???

                $vplans = [];
                if( isset($model->pacient->id) ){
                    $vplans =  backend\models\Plans::find()->where(['pacient_id'=>$model->pacient->id,'approved'=>'1','ready'=>'1'])->all();
                }
                    echo $form->field($model, 'vplan_id')->dropDownList(
                        ArrayHelper::map( $vplans, 'id','version'),
                        $param = ['options' =>[ $model->vplan_id => ['selected' => true]], 'prompt' => 'Не указан']
                    );


              /*$pay = Payments::find()->where(['pacient_id'=>$model->pacient->id, 'status'=>'0'])->one();
              $var_paid = $pay->var_paid;

              ?>
              <label>Способ оплаты</label>
              <input type="text" value="<?=$var_paid?>" class="form-control" disabled />

                <?php *//*$form->field($model, 'type_paid')
                ->dropDownList([
                    '0' => 'Без оплаты',
                    '1' => 'Оплата 2 частями',
                    '2' => 'Оплата 3 частями',
                    '3' => '100% предоплата',
                    ], $param = ['options' =>[ $model->type_paid => ['Selected' => true]]] );
                    */ ?>

    <?php  /*$form->field($model, 'status_object')->dropDownList([
        '0' => 'Бесплатно',
        '1' => 'Рассрочка',
        '2' => 'Полная оплата',
        ], $param = ['options' =>[ $model->status_object => ['Selected' => true]]] ); */
/*
    // статус оплачено - устанавливается бухгалтером
        echo $form->field($model, 'status_paid')
        ->dropDownList([
            '0' => 'Не оплачено',
            '1' => 'Оплачено частично',
            '2' => 'Оплачено полностью',
            ], $param = ['options' =>[ $model->status_paid => ['Selected' => true]]] );

    // цену видят только админ, бухгалтер, врач (который заказал) и мед директор
    if($role!=0) {


    ?>

    <?= $form->field($model, 'status_agreement')->dropDownList([
        '0' => 'Не подписан',
        '1' => 'Подписан',
        ], $param = ['options' =>[ $model->status_agreement => ['Selected' => true]]] );
    // договор подписан или нет   }
        */
        ?>

        <hr>
        <?= $form->field($model, 'type')->dropDownList([
            '0' => 'Элайнер',
            '1' => 'Платная коррекция элайнера',
            '2' => 'Бесплатная коррекция элайнера',
            '3' => 'Ретейнер',
            '4' => 'Бесплатный ретейнер',
            '5' => 'Клинические исследования',
            ], $param = ['options' =>[ $model->type => ['selected' => true]]] );
        ?>

        <?= $form->field($model, 'scull_type')->dropDownList([
            '0' => 'Верхняя челюсть',
            '1' => 'Нижняя челюсть',
            '2' => 'Верхняя и нижняя челюсти',
            ], $param = ['options' =>[ $model->scull_type => ['selected' => true]]] );

        if( ! $vp = Plans::find()->where(['id'=>$model->vplan_id])->one() ){
            $vp = new Plans();
        }

        ?>
            <table class="table">
                <tr><td><strong>Наименование</strong></td>
                    <td><strong>Планируемое кол-во ВЧ из ВП</strong></td>
                    <td><strong>Планируемое кол-во НЧ из ВП</strong></td>
                    <td><strong>Кол-во на изготовление ВЧ</strong></td>
                    <td><strong>Кол-во на изготовление НЧ</strong></td>
                    <td><strong>Этап ВЧ</strong></td>
                    <td><strong>Этап НЧ</strong></td>
                </tr>

                <?= '<tr><td>Количество элайнеров (кап)</td><td>' . $form->field($vp, 'count_elayners_vc')->textInput(['maxlength' => true,'disabled'=>true])->label(false) . '</td>' ?>
                <?= '<td>' . $form->field($vp, 'count_elayners_nc')->textInput(['maxlength' => true,'disabled'=>true])->label(false) .
                    '</td><td id="e_vc">' . $form->field($model, 'count_elayners_vc')->textInput(['maxlength' => true])->label(false) .
                    '</td><td id="e_nc">' . $form->field($model, 'count_elayners_nc')->textInput(['maxlength' => true])->label(false) .
                    '</td><td>' . $form->field($model, 'stage_elayners_vc')->textInput(['maxlength' => true])->label(false) .
                    '</td><td>' . $form->field($model, 'stage_elayners_nc')->textInput(['maxlength' => true])->label(false) .
                    '</td></tr>'  ?>

                <?= '<tr><td>Количество аттачментов (кап)</td><td>' . $form->field($vp, 'count_attachment_vc')->textInput(['maxlength' => true,'disabled'=>true])->label(false) . '</td>' ?>
                <?= '<td>' . $form->field($vp, 'count_attachment_nc')->textInput(['maxlength' => true,'disabled'=>true])->label(false) .
                    '</td><td id="a_vc">' . $form->field($model, 'count_attachment_vc')->textInput(['maxlength' => true])->label(false) .
                    '</td><td id="a_nc">' . $form->field($model, 'count_attachment_nc')->textInput(['maxlength' => true])->label(false) .
                    '</td><td>' . $form->field($model, 'stage_attachment_vc')->textInput(['maxlength' => true])->label(false) .
                    '</td><td>' . $form->field($model, 'stage_attachment_nc')->textInput(['maxlength' => true])->label(false) .
                    '</td></tr>'  ?>

                <?= '<tr><td>Количество Check-point (кап)</td><td>' . $form->field($vp, 'count_checkpoint_vc')->textInput(['maxlength' => true,'disabled'=>true])->label(false) . '</td>' ?>
                <?= '<td>' . $form->field($vp, 'count_checkpoint_nc')->textInput(['maxlength' => true,'disabled'=>true])->label(false)  .
                    '</td><td id="c_vc">' . $form->field($model, 'count_checkpoint_vc')->textInput(['maxlength' => true])->label(false) .
                    '</td><td id="c_nc">' . $form->field($model, 'count_checkpoint_nc')->textInput(['maxlength' => true])->label(false) .
                    '</td><td>' . $form->field($model, 'stage_checkpoint_vc')->textInput(['maxlength' => true])->label(false) .
                    '</td><td>' . $form->field($model, 'stage_checkpoint_nc')->textInput(['maxlength' => true])->label(false) .
                    '</td></tr>' ?>

                <?= '<tr><td>Количество ретейнеров (кап)</td><td>' . $form->field($vp, 'count_reteiners_vc')->textInput(['maxlength' => true,'disabled'=>true])->label(false) . '</td>' ?>
                <?= '<td>' . $form->field($vp, 'count_reteiners_nc')->textInput(['maxlength' => true,'disabled'=>true])->label(false) .
                    '</td><td id="r_vc">' . $form->field($model, 'count_reteiners_vc')->textInput(['maxlength' => true])->label(false) .
                    '</td><td id="r_nc">' . $form->field($model, 'count_reteiners_nc')->textInput(['maxlength' => true])->label(false) .
                    '</td><td>' . $form->field($model, 'stage_reteiners_vc')->textInput(['maxlength' => true])->label(false) .
                    '</td><td>' . $form->field($model, 'stage_reteiners_nc')->textInput(['maxlength' => true])->label(false) .
                    '</td></tr>' ?>

                <tr><td colspan="3">Всего планируемое количество капп: <span id="models_count"><?=$vp->getCapCount($model->vplan_id)?></span></td><td colspan="2" style="text-align:right;">Всего количество капп на изготовление: <?=$model->getCapCount()?></td>
                <tr><td>Пакет: <span id="tarif_plan"><?=$vp->getTarifPlan($model->vplan_id)?></span></td><td colspan="2" style="text-align:right;"></td><td></td>
            </table>
              
              

  <?php /* $form->field($model, 'tarif_plan')->dropDownList(
      ArrayHelper::map( backend\models\Price::find()->all() , 'id', 'paket_name'
      ),$param = ['options' =>[ $model->tarif_plan => ['Selected' => true]]]
  ); */ ?>

    <?php

    // статус заказа может менять только техник
    // либо заказ отправлен на производство, либо нет
    if($role==0) {
        echo $form->field($model, 'order_status')// статус заказа 1-отправлен
        ->dropDownList([
            '0' => 'Не отправлен',
            '1' => 'Отправлен',
        ], $param = ['options' => [$model->order_status => ['selected' => true]]]);
        echo '<p style="font-size:9pt;">Состояние заказа устанавливает техник для подтверждения того, что заказ создан</p>';

        echo $form->field($model, 'order_ready')// статус заказа 1-отправлен
        ->dropDownList([
            '0' => 'Не завершено',
            '1' => 'Завершено',
        ], $param = ['options' => [$model->order_ready => ['selected' => true]]]);
        echo '<p style="font-size:9pt;">Статус завершения производства заказа устанавливает техник для подтверждения того, что производство завершено</p>';

    }
    if($role ==2 || $role ==4 ) {
        echo $form->field($model, 'admin_check')->dropDownList([
            '0' => 'Не разрешено',
            '1' => 'Разрешено',
        ], $param = ['options' => [$model->admin_check => ['Selected' => true]]]);
    }

 // загрузка файлов
    /* echo  $form->field($model, 'files[]')->fileInput(['multiple' => true]) ;

    //print_r( $model->fileList );
    
    if( is_array($model->fileList) && count( $model->fileList ) ){
        $files = json_decode($model->files); ?>
        <table class="table"><tr><td><strong>Эскиз</strong></td><td><strong>Имя файла</strong></td><td><strong>Размер</strong></td><td><strong>Скачать</strong></td><td><strong>Удалить</strong></td></tr></tr>
    <?php  $path = Yii::getAlias("@backend/web/uploads/orders/" . $model->id);
        foreach ($model->fileList as $k=>$f) {
            if( strpos($f,'.png')>0  || strpos($f,'.jpg')>0 || strpos($f,'.gif')>0 ){
                $img = '<img src="/uploads/orders/'. $model->id.'/'.$f .'" width="48" />';
            }else{
                $img = '<img src="/uploads/file.png" width="48" />';
            }
            echo '<tr><td>'.$img.'</td><td>' . $f . '</td><td>' . filesize( $path .'/' . $f ) . '</td><td><a target="_blank" href="/admin/orders/download?file=' . $f . '&id=' . $model->id . '"><span class="glyphicon glyphicon-save" id="' . $model->id . '" ></span></a></td><td><a target="_blank" href="/orders/delete?file=' . $f . '&id=' . $model->id . '"><span class="glyphicon glyphicon-trash" id="' . $model->id . '" ></span></a></td></tr>';
        } ?>
        </table>
    <?php }
    */
    ?>
</div>

          <div id="pay"  class="tab-pane fade">

              <table class="table table-bordered table-first">
                  <tbody>
                  <tr>
                      <td width="183">Стоимость по прайсу</td>
                      <td  width="115">Стоимость с учетом скидки</td>
                      <td colspan="2" width="211">Оплата на дату заказа</td>
                      <td width="201">Элайнеры</td>
                  </tr>
                  <tr>  <td></td>
                      <td></td>
                      <td width="104">План</td>
                      <td>Факт</td>
                     <td width="101">Отпущено</td>
                      <?php //<td>Оплачено</td>?>

                  </tr>
                  <tr>
                      <td><?php
                          //if( $model_price = Price::findOne($model->tarif_plan) )
                          $model_price = Price::getPrice($model->tarif_plan);
                          echo '<span style="padding:10px">' . $model_price .'</span>';
                          //echo $form->field($model_price, 'price')->textInput(['maxlength' => true, 'disabled'=>!\app\rbac\Order::canEditPay($role) ])->label(false);
                          ?>
                      </td>

                      <td><? echo $form->field($model, 'pricewithdiscount')
                              ->textInput(['maxlength' => true, 'disabled'=>!\app\rbac\Order::canEditPay($role) ])->label(false); ?></td>

                      <td width="101">
                          <?php /*echo $form->field($model, 'payondatecreate_plan')
                              ->textInput(['maxlength' => true, 'disabled'=>!\app\rbac\Order::canEditPay($role) ])->label(false); */

                          // стоимость оплаты из плана графика пациента, по дате текущего заказа
                          if( isset($model->id) ) echo Payments::getPaymentSum($model->id, $model->pacient_code, $model->date);

                        ?></td>
                      <td><? echo $form->field($model, 'payondatecreate_fact')
                              ->textInput(['maxlength' => true, 'disabled'=>!\app\rbac\Order::canEditPay($role) ])->label(false); ?></td>

                      <td><?php
                          // кол-во ранее отпущенных капп по текущему номеру заказа и пациенту, без учета заказанных!
                          if( isset($model->id) ) echo Delivery::getReady($model->pacient_code, $model->id);
                          /*echo $form->field($model, 'ort_otp')
                              ->textInput(['maxlength' => true, 'disabled'=>!\app\rbac\Order::canEditPay($role) ])->label(false);
                          */
                          ?>
                      </td>
                      <?php /*  <td>echo $form->field($model, 'ort_pol')
                              ->textInput(['maxlength' => true, 'disabled'=>!\app\rbac\Order::canEditPay($role) ])->label(false);  </td> */  ?>

                  </tr>
                  </tbody>
              </table>
              <p>&nbsp;</p>

              <table class="table table-bordered table-first">
                  <tbody>
                  <tr>
                      <td width="183">Подлежит к оплате по заказу</td>
                      <td width="115">Оплачено по заказу</td>
                      <td width="104">Дата оплаты</td>
                      <td width="107">Долг по заказу</td>
                      <td width="101">Дата планируемого погашения</td>
                  </tr>
                  <tr>
                      <td width="183"><? echo $form->field($model, 'shouldpay')
                              ->textInput(['maxlength' => true, 'disabled'=>!\app\rbac\Order::canEditPay($role) ])->label(false); ?></td>
                      <td width="115">

                          <? echo $form->field($model, 'waspayfororder')
                              ->textInput(['maxlength' => true, 'disabled'=>!\app\rbac\Order::canEditPay($role) ])->label(false); ?>

                      </td>
                      <td width="104">

                          <? echo $form->field($model, 'dateofpay')
                              ->textInput(['maxlength' => true, 'disabled'=>!\app\rbac\Order::canEditPay($role), 'class' => 'datepicker', 'value' => (strlen($model->dateofpay) > 1 )? date('Y-m-d', $model->dateofpay):'' ] )->label(false); ?>

                      </td>

                      <td width="104">

                          <? echo $form->field($model, 'duty_on_request')
                              ->textInput(['maxlength' => true, 'disabled'=>!\app\rbac\Order::canEditPay($role) ])->label(false); ?>

                      </td>

                      <td width="101">   <? echo $form->field($model, 'dateofplanpay')
                              ->textInput(['maxlength' => true, 'disabled'=>!\app\rbac\Order::canEditPay($role),
                                  'class' => 'datepicker', 'value' => (strlen($model->dateofplanpay) > 1 )? date('Y-m-d', $model->dateofplanpay):''])->label(false); ?>
                      </td>

                  </tr>
                  </tbody>
              </table>
              <?php
              // динамически добавляемые поля даты и суммы оплаты
              // они дублируют таблицу payments_items, где задаются все даты оплаты

                /*if($role == 2 || $role == 3 || $role == 4 ){ // мед.дир, бухг, админ ?>
              <div class="btn btn-success" id="add_date_paid">Добавить дату оплаты</div>
              <table class="table date_paid">
                  <tr><td>Дата оплаты</td><td>Сумма оплаты</td><td>#</td></tr>
                  <?php
                  if( $order_pay = OrderPay::find()->where(['order_id'=>$model->id])->orderBy('date_paid')->all() ) {
                      foreach ($order_pay as $op) { ?>

                          <tr><td><?=$op->date_paid ?></td><td><?=$op->sum_paid ?></td></tr>

              <?php   }
                  }  ? >
              </table>
              <?php
               } */// дата оплаты


              ?>

          </div>
<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////- -->

<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?php
    
    if($role==2|| $role==4) {
        echo Html::a(Yii::t('app', 'Отправить админу'), ['apply', 'id' => $model->id], ['class' => 'btn btn-default']);
    }
    ?>    

    <?php if (!$model->isNewRecord): ?>
        <?= Html::Button(Yii::t('app', 'Print'), ['class' => 'btn btn-default print-btn', 'data-url'=>Url::to(['orders/print', 'id' => $model->id])]) ?>
    <?php endif; ?>
</div>

<?php ActiveForm::end(); ?>

</div>


<?php
 $cur_date = date('Y-m-d',time());
$script = "
$('document').ready(function(){
var odp_id = 0;
//alert('loaded')
    $('#add_date_paid').click(function(){
        odp_id++; 
        $( '<tr class=\"add_row\" id='+odp_id+'><td><input type=\"text\" name=\"order_pay[date][]\" value=\"$cur_date\" class=\"datepicker hasDatepicker\"></td><td><input type=\"text\" name=\"order_pay[sum][]\"></td><td><span class=\"delete_odp glyphicon glyphicon-trash\" id='+odp_id+' style=\"cursor:pointer\"></span></td></tr>' ).appendTo( '.table.date_paid' );
    });
    $(document).on('click','.delete_odp',function(){
        $( 'tr#'+ $(this).attr('id') + '.add_row').remove();
    });
});

    jQuery('#orders-pacient_code').change(function(){
        
        var uid = jQuery(this).val();        
        jQuery.ajax({
			type: 'post',
            url: '/orders/getplan',
            data: 'uid='+uid+'&_csrf=' + yii.getCsrfToken() ,
            dataType: 'html',
            success: function(data){                
               // alert( data +' Load was performed.');
                jQuery('#orders-vplan_id').html('<option>Укажите ВП</option>'+data);
            },
            error: function(data){                
                alert( data +' ERR');
            }
        });
    });
    jQuery('#orders-vplan_id').change(function(){    
        var id = jQuery(this).val();  
        jQuery.ajax({
			type: 'post',
            url: '/orders/getmodels',
            data: 'id='+id+'&_csrf=' + yii.getCsrfToken() ,
            dataType: 'json',
            success: function(data){ 
                if( data.status == 1 ){
                    
                    jQuery('#plans-count_elayners_vc').val( parseInt(data.e_vc) );
                    jQuery('#plans-count_attachment_vc').val( parseInt(data.a_vc) );
                    jQuery('#plans-count_checkpoint_vc').val( parseInt(data.c_vc) );
                    jQuery('#plans-count_reteiners_vc').val( parseInt(data.r_vc) );
                    
                    jQuery('#plans-count_elayners_nc').val( parseInt(data.e_nc) );
                    jQuery('#plans-count_attachment_nc').val( parseInt(data.a_nc) );
                    jQuery('#plans-count_checkpoint_nc').val( parseInt(data.c_nc) );
                    jQuery('#plans-count_reteiners_nc').val( parseInt(data.r_nc) ); 
                    // кол-во моделей
                    jQuery('#models_count').text( parseInt(data.models_count) );
                     // тарифный план
                    jQuery('#tarif_plan').text( data.tarif_plan ); 
                   
                }
            },
            error: function(data){
                alert( data.error +' ERR');
            }
        });
    })
";

$this->registerJs($script, yii\web\View::POS_END);


