<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use backend\models\Materials;
use backend\models\ReportsMaterials;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Reports */
/* @var $form yii\widgets\ActiveForm */
$prompt = [
    'prompt' => 'Выберите значения...'
];
?>
<style> th { background: #f5f5f5; }
.table-header{font-weight:bold;text-align: center;}

.table.table-first,
.table.table-second{
    width:100% ;
}
.table-first input{ width:100%; }
.table-second input{ width:100%; }

</style>

<div class="reports-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php // $form->field($model, 'date')->textInput(['type'=>'date']) ?>
    <?php // $form->field($model, 'date_delivery')->textInput();

    echo DatePicker::widget([
        'name' => 'Reports[date]',
        'value' => $model->isNewRecord ? date('d-m-Y',time()) : date('d-M-Y', strtotime($model->date)), // strtotime('+2 days')),
        'options' => ['placeholder' => 'Дата отчета'],
        'pluginOptions' => [
            'language' => 'ru',
            'format' => 'dd-mm-yyyy',
            'todayHighlight' => true,
            'autoclose'=> true,
        ]
    ]);
    ?>
    <br>

<?php if($page==1){ // отчет ЗАТРАТЫ ?>

    <?php

        $k = 1;
        $pacient_list = '<option value="-1">Укажите пациента</option>'; // список пациентов для добавления нового
    ?>
    <div class="row">
    <div class="list">
    <?php if($model->isNewRecord){ ?>
        <label>Выберите пациентов</label>
    <?php }else{ ?>
        <label>Выбранные пациенты</label>
    <?php } ?>
        <?php foreach($pacients_selected as $_id) { // по всем пациентам ?>
        <div class="row">
            <div class="col-sm-8">
                <div class="form-group">
                <select name="pacients[]" class="form-control pacients" id="<?=$k?>">
                    <option value="-1">Укажите пациента</option>
                    <?php foreach($pacients as $id=>$name) {
                        if( $id == $_id ){
                            $selected ='selected';
                        }else{
                            $selected ='';
                        }
                        ?>
                        <option <?=$selected?> value="<?=$id?>"><?=$name?></option>
                        <?php if($k==1) $pacient_list .= '<option value="'.$id.'">'.$name.'</option>'; ?>
                    <?php } ?>
                </select>

                </div>
            </div>
        <?php if($model->isNewRecord){ ?>
            <div class="col-sm-4">
                <div class="form-group">
                    <select name="orders[]" class="form-control order">
                        <option value="-1">Укажите № заказа</option>
                    </select>
                </div>
            </div>
        <?php }else{ ?>
            <div class="col-sm-4">
                <div class="form-group">
                    <?php // <label>Выберите № заказа</label> ?>
                    <select name="orders[]" class="form-control order">
                        <option value="-1">Укажите № заказа</option>
                        <?php /* foreach($orders as $id=>$name) {
                            if( $id == $_id ){
                                $selected ='selected';
                            }else{
                                $selected ='';
                            }
                            */
                        if(isset($order_ids[$k-1])){ ?>
                            <option selected value="<?=$order_ids[$k-1]?>"><?=$order_ids[$k-1]?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        <?php } ?>


        </div>
        <?php $k++;
        } ?>
    </div><!-- pacient list -->

    <?php if($model->isNewRecord){ ?>

    <div class="form-group">
        <div class="col-sm-12">
            <div class="btn btn-success" id="add_pacient">Добавить пациента</div>
        </div>
    </div>
    <?php } ?>
<?php
    // если создание не отображать таблицы
    $array = '';

    if(!  $model->isNewRecord ) {  ?>

    <br>
    <br>
    <table class="table table-bordered">
        <tbody>
        <tr>
            <th>Ф.И.О. Пациента</th>
            <th>Код пациента</th>
            <th>Вид продукции*</th>
            <th>Количество моделей</th>
            <th>Количество элайнеров (кап)</th>
            <th>Количество аттачментов (кап)</th>
            <th>Количество Check-point (кап)</th>
            <th>Количество ретейнеров (кап)</th>
        </tr>

        <?php // здесь цикл по всем пациентам с выводом всех изделий

    $array = '{"p0":"0",';

    if (isset($order) && count($order) > 5) {

        /*echo '<pre>';
         print_r($order);
         echo '<pre>';*/

        foreach ($pacients as $id => $name) {

            // исключить пациента, если заказа нет или пациент не выбран для отчета
            if (!isset($order[$id]) || !in_array($id, $pacients_selected)) {
               // echo 'пациент исключен ' . $id ;
                continue;
            }

            // массив для js
            $array .= '"p' . $id . '":"' . $order[$id]['count_models'] . '",';

            ?>
            <tr>
                <td><?= $name ?></td>
                <td><?= $id ?></td>
                <td><?= $order[$id]['type'] ?></td>
                <td><?= $order[$id]['count_models'] ?></td>
                <td><?= $order[$id]['count_elayners'] ?></td>
                <td><?= $order[$id]['count_attachment'] ?></td>
                <td><?= $order[$id]['count_checkpoint'] ?></td>
                <td><?= $order[$id]['count_reteiners'] ?></td>
            <tr>
        <?php } ?>

        <tr>
            <td colspan="3">ИТОГО:</td>
            <td><?= $order['count_models'] ?></td>
            <td><?= $order['count_elayners'] ?></td>
            <td><?= $order['count_attachment'] ?></td>
            <td><?= $order['count_checkpoint'] ?></td>
            <td><?= $order['count_reteiners'] ?></td>
        </tr>
    <?php }
    $array = trim($array, ',') . '}';
    ?>
    </tbody>


    </table>
    <div style="font-size:8pt;padding-bottom:20px;">*Элайнеры (Э), доп.элайнеры (ДЭ), ретейнеры (Р), бесплатная, платная
        коррекция (БК или ПК), беспл. элайнер (БЭ), клин. испытания (КИ)
    </div>
    <br>
    <table class="table table-bordered table-first" style="background: #fff;width:960px !important;">
        <tr>
            <?php // <th>Пациент</th> ?>
            <th>Наименование материала</th>
            <th>Всего, гр</th>
            <th>на 1 модель, гр</th>
            <th>Норма</th>
            <th>На начало периода</th>
            <th>Дополнительно, гр</th>
            <th>На конец периода, гр</th>
        </tr>
        <?php
        $row = 0;

        if (!$model->isNewRecord) {

            foreach ($table1 as $key => $table):
                $row++;
                if ($row > 2) break;
                ?>

                <tr class="no-remove">
                    <?php /*<td>
                        <select name="Materials[table1][pacient_id][]">
                            <option value="0">Выберите значение</option>
                            <? foreach ($pacients as $id=>$name):?>
                                <?
                                if ($id == $table->pacient_id) {
                                    $selected = 'selected';
                                } else {
                                    $selected = '';
                                }
                                ?>
                                <option <?= $selected ?> value="<?= $id; ?>"><?= $name; ?></option>
                            <?endforeach; ?>
                        </select>
                    </td> */ ?>
                    <td>
                        <input type="hidden" name="Materials[table1][id][]" value="<?= $table->id ?>"/>
                        <select id="mat" name="Materials[table1][material_id][]">
                            <option value="0">Выберите значение</option>
                            ?>
                            <?php foreach ($materials as $mat):
                                if ($mat->id > 2 || $row != $mat->id) continue;  // пропуск всех кроме Med и support
                                if ($mat->id == $table->material_id) {
                                    $selected = 'selected';
                                } else {
                                    $selected = '';
                                }
                                ?>
                                <option <?= $selected ?> value="<?= $mat->id; ?>"><?= $mat->name; ?></option>
                            <? endforeach; ?>
                        </select>

                    </td>
                    <td><input id="total" type="text" name="Materials[table1][total][]" value="<?= $table->total ?>"/>
                    </td>
                    <td><input id="mass" type="text" name="Materials[table1][model][]" value="<?= $table->model ?>"/>
                    </td>
                    <td><input id="norm" type="text" name="Materials[table1][norm][]" value="<?= $table->norm ?>"/></td>
                    <td><input type="text" id="ostatok" class="ostatok_<?= $mat->id ?>" readonly
                               name="Materials[table1][begin][]"
                               value="<?= $table->begin /*!='' ? $table->begin : $table->material_id=='1' ? $ostat['med'] : $ostat['sup']; */ ?>"/>
                    </td>
                    <td><input type="text" id="dop" name="Materials[table1][dop][]" value="<?= $table->dop ?>"/></td>
                    <td><input type="text" id="ostatok_end" name="Materials[table1][end][]" value="<?= $table->end ?>"/>
                    </td>
                </tr>

            <?php endforeach;
        }
        if ($row == 0) { ?>
            <tr class="for-copy-row1 no-remove" id="copy_table1">
                <?php /* <td>
                <select name="Materials[table1][pacient_id][]">
                    <option value="0">Выберите значение</option>
                    <? foreach ($pacients as $id=>$name):?>
                        <option value="<?= $id; ?>"><?= $name; ?></option>
                    <?endforeach; ?>
                </select>
            </td> */
                ?>
                <td>
                    <select id="mat" name="Materials[table1][material_id][]">
                        <?php /// <option value="0">Выберите значение</option>
                        ?>
                        <? foreach ($materials as $mat):
                            if ($mat->id != 1) continue;  // пропуск всех кроме Med и support
                            ?>
                            <option
                                value="<?= $mat->id; ?>" <?= $mat->id == 1 ? 'selected' : '' ?>><?= $mat->name; ?></option>
                        <? endforeach; ?>
                    </select>
                </td>
                <td><input id="total" type="text" name="Materials[table1][total][]"/></td>
                <td><input id="mass" type="text" name="Materials[table1][model][]"/></td>
                <td><input id="norm" type="text" name="Materials[table1][norm][]" value="15 - 20"/></td>
                <td><input type="text" id="ostatok" class="ostatok_1" readonly name="Materials[table1][begin][]"/></td>
                <td><input type="text" id="dop" name="Materials[table1][dop][]"/></td>
                <td><input type="text" id="ostatok_end" name="Materials[table1][end][]"/></td>
            </tr>
            <td>
                <select id="mat" name="Materials[table1][material_id][]">
                    <?php /// <option value="0">Выберите значение</option>
                    ?>
                    <? foreach ($materials as $mat):
                        if ($mat->id != 2) continue;  // пропуск всех кроме Med и support
                        ?>
                        <option
                            value="<?= $mat->id; ?>" <?= $mat->id == 2 ? 'selected' : '' ?>><?= $mat->name; ?></option>
                    <? endforeach; ?>
                </select>
            </td>
            <td><input id="total" type="text" name="Materials[table1][total][]"/></td>
            <td><input id="mass" type="text" name="Materials[table1][model][]"/></td>
            <td><input id="norm" type="text" name="Materials[table1][norm][]" value="10 - 15"/></td>
            <td><input type="text" id="ostatok" class="ostatok_2" readonly name="Materials[table1][begin][]"/></td>
            <td><input type="text" id="dop" name="Materials[table1][dop][]"/></td>
            <td><input type="text" id="ostatok_end" name="Materials[table1][end][]"/></td>
            </tr>
        <?php } ?>
    </table>
    <?php /* <span class="btn btn-success add_row_table1">Добавить строку</span>
    <span class="btn btn-danger del_row_table1">Удалить последнюю строку</span> */ ?>
    <hr/>

    <table class="table table-bordered table-second" style="background: #fff">
        <tr>
            <th>Пациент</th>
            <?php foreach ($materials as $mat): ?>
                <th><?= $mat->name; ?></th>
            <?php endforeach; ?>
        </tr>
        <?
        $row = 0;
        if (!$model->isNewRecord) { ?>
            <tr class="for-copy-row2 no-remove">
                <?php
                $field = 0;
                $cnt = count($table2);

                //print_r($table2);
                foreach ($table2 as $key => $table) {

                    if ($field > 9) { // 9 полей
                        $field = 0;
                        $row++;
                        if ($cnt != $row) {
                            echo '</tr><tr>'; // новая строка
                        } else {
                            // break; // нет записей
                        }
                    }

                    if ($field == 0) { // список пациентов
                        ?>
                        <td>
                            <select name="Materials[table2][<?=$row?>][0]" class="mat_new_pac">
                                <option value="0">Выберите пациента</option>
                                <?php foreach ($pacients as $id => $name): ?>
                                    <?php
                                    if ($id == $table->pacient_id) { // value=pid material_id = 0 - пациенты
                                        $selected = 'selected';
                                    } else {
                                        $selected = '';
                                    }
                                    ?>
                                    <option <?= $selected ?> value="<?= $id; ?>"><?= $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    <?php } else { ?>
                        <td>
                            <?php /* <input type="hidden" name="Materials[table2][id][]" value="<?=$table->id?>" /> */
                            ?>
                            <input id="mat_<?= $field ?>" type="text"
                                   name="Materials[table2][<?=$row?>][<?= $field ?>]" value="<?= $table->value ?>"/>
                        </td>
                    <?php }
                    $field++;
                }
                $row++; // для следующей пустой строки
                ?>
            </tr>
        <? } //else{ //endforeach;  ?>
        <tr class="for-copy-row2 no-remove" id="copy_table2">
            <td>
                <select name="Materials[table2][newrow][0]" class="mat_new_pac">
                    <option value="0">Выберите пациента</option>
                    <? foreach ($pacients as $id => $name): ?>
                        <option value="<?= $id; ?>"><?= $name; ?></option>
                    <? endforeach; ?>
                </select>
            </td>
            <?php

            $field = 0;
            foreach ($materials as $mat):
                $field++; ?>
                <td><input type="text" id="mat_<?= $field ?>" name="Materials[table2][newrow][<?= $mat->id ?>]"/>
                </td>
                <?php
            endforeach; ?>
        </tr>
        <?php // } ?>
    </table>
    <span class="btn btn-success add_row_table2">Добавить строку</span>
    <span class="btn btn-danger del_row_table2">Удалить последнюю строку</span>
    <hr/>

    <?php

} // если создание пациента


}else{ // page==2 - остатки ?>
    
    <table class="table table-bordered table-third" style="background: #fff">
        <tr>
            <?php  //<th>Пациент</th> ?>
            <th>Наименование материала</th>
            <th>Единица измерения</th>
            <th>Остаток, брутто</th>
            <th>Нормативный вес упаковки</th>
            <th>Остаток, нетто</th>
        </tr>

        <?php
        $table_names = [];
            $table_names[0] = ['name'=>'МЕД 620-М1','ed'=>'грамм','id'=>'med_1'];
            $table_names[1] = ['name'=>'МЕД 620-М2','ed'=>'грамм','id'=>'med_2'];
            $table_names[2] = ['name'=>'Support 705-М1','ed'=>'грамм','id'=>'sup_1'];
            $table_names[3] = ['name'=>'Support 705-М2','ed'=>'грамм','id'=>'sup_2'];
            $table_names[4] = ['name'=>'Итого МЕД-620','ed'=>'грамм','id'=>'med_count'];
            $table_names[5] = ['name'=>'Итого Support 705','ed'=>'грамм','id'=>'sup_count' ];
            $table_names[6] = ['name'=>'Erkodur 1mm','ed'=>'шт'];
            $table_names[7] = ['name'=>'Erkodur 1mm-frezee','ed'=>'шт'];
            $table_names[8] = ['name'=>'Erkodur 0.8mm','ed'=>'шт'];
            $table_names[9] = ['name'=>'Erkodur 0.6mm','ed'=>'шт'];
            $table_names[10] = ['name'=>'Коробка','ed'=>'шт'];
            $table_names[11] = ['name'=>'Контейнер','ed'=>'шт'];
            $table_names[12] = ['name'=>'Упаковочный материал','ed'=>'шт'];

       $is_new = false;
       if( !isset($table3) || ! $table3_data = json_decode($table3->data) ){
           $is_new = true;
       }

        $item = 0;
        $type='text';
        foreach( $table_names as $table ) {

            // заголовки
            if($item==0){ ?>
            <tr><td colspan="5" class="table-header">Картриджи</td></tr>
            <?php }elseif($item==6) { ?>
            <tr><td colspan="5" class="table-header">Фойлы</td></tr>
            <?php }elseif($item==10) { ?>
            <tr><td colspan="5" class="table-header">Упаковочный материал</td></tr>
            <?php } ?>
            <tr>
                <td><?=$table['name']?></td>
                <td><?=$table['ed']?></td>
                <td><input <?= isset($table['id'])? 'id="'.$table['id'].'1"' : ''; ?> type="text" name="Table[<?= $item ?>][brutto]" class="form-control"
                           value="<?= $is_new? '0' : $table3_data[$item]->brutto ?>"></td>
                <?php if( $item > 5 ) $type = 'hidden'; ?>
                <td><input <?= isset($table['id'])? 'id="'.$table['id'].'2"' : ''; ?> type="<?=$type?>" name="Table[<?= $item ?>][ves]" class="form-control"
                           value="<?= $is_new ? '135' : $table3_data[$item]->ves ?>"></td>
                <td><input <?= isset($table['id'])? 'id="'.$table['id'].'3"' : ''; ?> type="<?=$type?>" name="Table[<?= $item ?>][netto]" class="form-control"
                           value="<?= $is_new? '0': $table3_data[$item]->netto ?>"></td>
            <?php //} ?>
            </tr>

            <?    // med_11 med_12  med_13
            $item++;
        }

        /*
        if(!$model->isNewRecord) {
            foreach ($table3 as $key => $table):?>
                <tr class="no-remove">
<!--                   --><?php // <td>
//                        <select name="Materials[table3][pacient_id][]">
//                            <option value="0">Выберите значение</option>
//                            <? foreach ($pacients as $id => $name):?>
<!--                                --><?//
//                                if ($id == $table->pacient_id) {
//                                    $selected = 'selected';
//                                } else {
//                                    $selected = '';
//                                }
//                                ?>
<!--                                <option --><?//= $selected ?><!-- value="--><?//= $id; ?><!--">--><?//= $name; ?><!--</option>-->
<!--                            --><?//endforeach; ?>
<!--                        </select>-->
<!--                    </td>  ?>-->
                    <td>
                        <input type="hidden" name="Materials[table3][id][]" value="<?= $table->id ?>"/>
                        <select name="Materials[table3][material_id][]">
                            <option value="0">Выберите значение</option>
                            <? foreach ($materials as $mat):?>
                                <?
                                if ($mat->id == $table->material_id) {
                                    $selected = 'selected';
                                } else {
                                    $selected = '';
                                }
                                ?>
                                <option <?= $selected ?> value="<?= $mat->id; ?>"><?= $mat->name; ?></option>
                            <?endforeach; ?>
                        </select>
                    </td>
                    <td><input type="text" name="Materials[table3][unit][]" value="<?= $table->unit ?>"/></td>
                    <td><input type="text" name="Materials[table3][gross][]" value="<?= $table->gross ?>"/></td>
                    <td><input type="text" name="Materials[table3][norm_weight][]" value="<?= $table->norm_weight ?>"/></td>
                    <td><input type="text" name="Materials[table3][net][]" value="<?= $table->net ?>"/></td>
                </tr>
            <?endforeach;
        }
        ?>
        <tr class="for-copy-row3 no-remove" id="copy_table3">
            <?php //<td>
                //<select name="Materials[table3][pacient_id][]">
//                    <option value="0">Выберите значение</option>
//                    <?php foreach ($pacients as $id => $name):?>
<!--                        <option value="--><?//= $id; ?><!--">--><?//= $name; ?><!--</option>-->
<!--                    --><?//endforeach; ?>
<!--                </select>-->
<!--            </td> ?>-->
            <td>
                <select name="Materials[table3][material_id][]">
                    <option value="0">Выберите значение</option>
                    <?foreach($materials as $mat):?>
                        <option value="<?=$mat->id;?>"><?=$mat->name;?></option>
                    <?endforeach;?>
                </select>
            </td>
            <td><input type="text" name="Materials[table3][unit][]" /></td>
            <td><input type="text" name="Materials[table3][gross][]" /></td>
            <td><input type="text" name="Materials[table3][norm_weight][]" /></td>
            <td><input type="text" name="Materials[table3][net][]" /></td>
        </tr>
        */ ?>
    </table>

    <?php /*<span class="btn btn-success add_row_table3">Добавить строку</span>
    <span class="btn btn-danger del_row_table3">Удалить последнюю строку</span> */ ?>

    <hr/>

<?php } // page 2 ?>


    <input type="hidden" name="page" value="<?=$page?>" />
    <br>
    <br>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php /* <a href="/reports/create?page=<?=$page?>" class="btn btn-success"/>Создать отчет</a> */ ?>

        <?php if(! $model->isNewRecord ) { ?>
            <?php if($page==1) { ?> <a class="btn btn-default" target="_blank" href="<?= Url::to(['/reports/print', 'id' => $model->id,'page'=>'1'])?>"><span class="glyphicon glyphicon-print"></span> Печать затрат</a> <?php } ?>
            <?php if($page==2) { ?> <a class="btn btn-default" target="_blank" href="<?= Url::to(['/reports/print', 'id' => $model->id,'page'=>'2'])?>"><span class="glyphicon glyphicon-print"></span> Печать остатков</a><?php } ?>
        <?php } // print-btn ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php

if($page==1) {

    // кол-во моделей для расчета затрат
    if(isset($order) && count($order)>5 ) {
        $models_count = (int)$order['count_models'];
    }else{
        $models_count = 0;
    }
    $ost_med = isset($ostat['med'])?$ostat['med']:0;
    $ost_sup = isset($ostat['sup'])?$ostat['sup']:0;
    $script = "   
$('document').ready(function(){
var newrow = 100; 
var pac_models = new Array( $array );
var models_count = {$models_count}; // кол-во моделей
var total = 0; // масса
var ed = 0; // рез. ед
var ost_med = {$ost_med};
var ost_sup = {$ost_sup};
    
    $('#ostatok.ostatok_1').val(ost_med);
    $('#ostatok.ostatok_2').val(ost_sup);
    
    $('#add_pacient').click(function(){
        // alert($('select#1').html())
        $('.list').append('<div class=\"row\"><div class=\"col-sm-8\"><div class=\"form-group\"><select name=\"pacients[]\" class=\"form-control pacients\">{$pacient_list}</select></div></div><div class=\"col-sm-4\"><div class=\"form-group\"><select name=\"orders[]\" class=\"form-control order\"><option value=\"-1\">Укажите № заказа</option></select></div></div></div>');
    });
    $('.add_row_table1').click(function(){
        $('.table-first').append('<tr class=\"for-copy-row1\">' + $('#copy_table1').html() +'</tr>');
    });
    $('.add_row_table2').click(function(){
        var copy = $('#copy_table2').html();
        newrow++;
        copy = copy.replace(/newrow/g,newrow);
        $('.table-second').append('<tr class=\"for-copy-row2\">' + copy +'</tr>');
    });
    $('.add_row_table3').click(function(){
        $('.table-third').append('<tr class=\"for-copy-row3\">' + $('#copy_table3').html() +'</tr>');
    });
    $('.del_row_table1').click(function(){
        if(! $('.table-first tr:last-child').hasClass('no-remove') ) $('.table-first tr:last-child').remove();
    });
    $('.del_row_table2').click(function(){
        if(! $('.table-second tr:last-child').hasClass('no-remove') ) $('.table-second tr:last-child').remove();
    });
    $('.del_row_table3').click(function(){
        if(! $('.table-third tr:last-child').hasClass('no-remove') ) $('.table-third tr:last-child').remove();
    });
    
    $(document).on('change','#total', function(){
        calsTotal($(this));
        $('.mat_new_pac').change();
    })
    // отключение отправки на сервер по нажатию enter
    $(document).on('keydown','input', function(key){
        if(key.keyCode==13) {
            key.preventDefault();
            return false;
        }     
    })
    $(document).on('change','#dop', function(){
        calsTotal($(this));
    })    
    $(document).on('change','#mass', function(){
        calsTotal($(this));
    })  
    $(document).on('change','select#mat', function(){
        //alert($(this).val());
        if( $(this).val() == 1 ) { // med-620
            calsTotal($(this));
            $(this).parent().parent().find('#norm').val('15 - 20');                
        }else if( $(this).val() == 2 ) { // support-705
            calsTotal($(this));            
            $(this).parent().parent().find('#norm').val('10 - 15');
        }else if( $(this).val() == 9 ) { // упаковочный пакет
            $(this).parent().parent().find('#mass').val('0.25');
        }else{            
            $(this).parent().parent().find('#norm').val('');
        }        
    }) 
    
    $(document).on('change','select.mat_new_pac', function(){
         calsSum($(this));      
    })    
    $(document).on('change','#mat_3,#mat_4,#mat_5,#mat_6', function(){
         calsErkodur($(this));      
    })
    
    function calsSum(obj){
        var pm = pac_models[0]['p'+obj.val()];
        if(pm == undefined ) {
            pm = 0;
        }    
        var med = pm * parseFloat( $('.table-first tr:nth-child(2)').find('#mass').val() );
        var sup = pm * parseFloat( $('.table-first tr:nth-child(3)').find('#mass').val() );
        
        med *= 100;
        med = parseInt(med)/100;           
        sup *= 100;
        sup = parseInt(sup)/100;         
                    
        obj.parent().parent().find('#mat_1').val( med );
        obj.parent().parent().find('#mat_2').val( sup );        
    }
    function calsErkodur(obj){    
        var erkodur = 0;
        var e_item = 0;
        for( var k=3; k<7; k++ ){
            e_item = parseFloat( obj.parent().parent().find('#mat_'+k).val())
            if(e_item == undefined || e_item == '' || e_item < 0 || isNaN(e_item) ) {
                e_item = 0;
                obj.parent().parent().find('#mat_'+k).val('0')
            }    
            erkodur += e_item;                
        }
        if( erkodur == undefined || erkodur == '' || erkodur < 0 || isNaN(erkodur) ) erkodur = 0;
        // расчет упаковки 
        obj.parent().parent().find('#mat_9').val( erkodur * 0.25 );
    }   
    
    
    function calsTotal(obj){

        total = parseInt( obj.parent().parent().find('#total').val() );
        if( total == undefined || total == '' || total < 0 || isNaN(total) ) {
            total = 0;
            obj.parent().parent().find('#total').val(total);
        }           
        if(models_count==0){
            ed=0;
        }else{
            ed =  total / models_count;
        }
        ed *= 100;
        ed = parseInt(ed)/100;   
        if(ed == NaN) ed = 0;         
        obj.parent().parent().find('#mass').val(ed);
        var ost = parseInt( obj.parent().parent().find('#ostatok').val() );        
        var dop = parseInt( obj.parent().parent().find('#dop').val() );        
       
        if( dop == undefined || dop == '' || dop < 0 || isNaN(dop) ) {
            dop = 0;
            obj.parent().parent().find('#dop').val(dop);
        }    
        var ost_end = ost + dop - total;  
        if( ost_end == undefined || ost_end == '' || ost_end < 0 || isNaN(ost_end) ) {
            ost_end = 0;
        } 

        obj.parent().parent().find('#ostatok_end').val( ost_end );        
    }
    
    jQuery(document).on('change','.pacients',function(){    
        var id = jQuery(this).val(); 
        var order_list = jQuery(this).parent().parent().parent().find('.order');
        
        // alert(order_list.html())
       //  return false;
        jQuery.ajax({
			type: 'post',
            url: '/orders/getnum',
            data: 'id='+id+'&_csrf=' + yii.getCsrfToken() ,
            dataType: 'json',
            success: function(data){ 
                if( data.status == 1 ){
                    order_list.html( data.orders ); 
                }else{
                    order_list.html( '' ); 
                }
            },
            error: function(data){
                alert( data.error +' ERR');
            }
        });
    });    
    
});";



}else{
    // возможно нужно расчитать остатки авоматом ??

$script = "   
    $('document').ready(function(){
        
        $('#med_count2').css('display','none');
        $('#sup_count2').css('display','none');
    
        $('#med_11').change(function(){            
            calsCounts('#med',1);
        });
        $('#med_21').change(function(){
            calsCounts('#med',1);
        });
            /*$('#med_12').change(function(){
                calsCounts('#med',2);
            });
            $('#med_22').change(function(){
                calsCounts('#med',2);
            });*/
        $('#med_13').change(function(){
            calsCounts('#med',3);
        });
        $('#med_23').change(function(){
            calsCounts('#med',3);
        }); 
        $('#sup_11').change(function(){            
            calsCounts('#sup',1);
        });
        $('#sup_21').change(function(){
            calsCounts('#sup',1);
        });
            /*$('#sup_12').change(function(){
                calsCounts('#sup',2);
            });
            $('#sup_22').change(function(){
                calsCounts('#sup',2);
            });*/
        $('#sup_13').change(function(){
            calsCounts('#sup',3);
        });
        $('#sup_23').change(function(){
            calsCounts('#sup',3);
        });

        function calsCounts(mat,col){            
            var cnt = parseFloat($(mat+'_1'+col).val()) + parseFloat($(mat+'_2'+col).val()) ;
            cnt *= 100;
            cnt = parseInt(cnt)/100;            
            $(mat + '_count'+col).val( cnt );            
        }
        
        // отключение отправки на сервер по нажатию enter
        $(document).on('keydown','input', function(key){
            if(key.keyCode==13) {
                key.preventDefault();
                return false;
            }     
        })    
        $('.del_row_table3').click(function(){
            if(! $('.table-third tr:last-child').hasClass('no-remove') ) $('.table-third tr:last-child').remove();
        });
                
    });";

}

$this->registerJs($script, yii\web\View::POS_END);