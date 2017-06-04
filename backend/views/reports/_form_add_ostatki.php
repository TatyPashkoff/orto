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
<style> th { background: #f5f5f5; } </style>

<div class="reports-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php // $form->field($model, 'date')->textInput(['type'=>'date']) ?>
    <?php // $form->field($model, 'date_delivery')->textInput();

    echo DatePicker::widget([
        'name' => 'Reports[date]',
        'value' => date('d-M-Y', strtotime($model->date)), // strtotime('+2 days')),
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
    <div class="list">
        <label>Выберите пациентов</label>
        <?php foreach($pacients_selected as $_id) { // по всем пациентам ?>
        <select name="pacients[]" class="form-control" id="<?=$k?>">
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
        <br>
        <?php $k++;
        } ?>
    </div><!-- pacient list -->
    <div class="btn btn-success" id="add_pacient">Добавить пациента</div>
    <br>
    <br>

    <table class="table table-bordered table-first" style="background: #fff">
        <tr>
           <?php // <th>Пациент</th> ?>
            <th>Наименование материала</th>
            <th>Всего, гр</th>
            <th>на 1 модель, гр</th>
            <th>Норма</th>
            <th>На начало периода</th>
            <th>На конец периода, гр</th>
        </tr>
        <?
        if(!$model->isNewRecord) {
            foreach ($table1 as $key => $table):?>
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
                    <td><input type="text" name="Materials[table1][total][]" value="<?= $table->total ?>"/></td>
                    <td><input id="mass" type="text" name="Materials[table1][model][]" value="<?= $table->model ?>"/></td>
                    <td><input id="norm" type="text" name="Materials[table1][norm][]" value="<?= $table->norm ?>"/></td>
                    <td><input type="text" name="Materials[table1][begin][]" value="<?= $table->begin ?>"/></td>
                    <td><input type="text" name="Materials[table1][end][]" value="<?= $table->end ?>"/></td>
                </tr>
            <?endforeach;
        }
        ?>
        <tr class="for-copy-row1 no-remove" id="copy_table1">
            <?php /*<td>
                <select name="Materials[table1][pacient_id][]">
                    <option value="0">Выберите значение</option>
                    <? foreach ($pacients as $id=>$name):?>
                        <option value="<?= $id; ?>"><?= $name; ?></option>
                    <?endforeach; ?>
                </select>
            </td> */ ?>
            <td>
                <select id="mat" name="Materials[table1][material_id][]">
                <option value="0">Выберите значение</option>
                <?foreach($materials as $mat):?>
                    <option value="<?=$mat->id;?>"><?=$mat->name;?></option>
                <?endforeach;?>
                </select>
            </td>
            <td><input type="text" name="Materials[table1][total][]" /></td>
            <td><input id="mass" type="text" name="Materials[table1][model][]" /></td>
            <td><input id="norm" type="text" name="Materials[table1][norm][]" /></td>
            <td><input type="text" name="Materials[table1][begin][]" /></td>
            <td><input type="text" name="Materials[table1][end][]" /></td>
        </tr>
    </table>
    <span class="btn btn-success add_row_table1">Добавить строку</span>
    <span class="btn btn-danger del_row_table1">Удалить последнюю строку</span>
    <hr />
    
    <table class="table table-bordered table-second" style="background: #fff">
        <tr> <?php // заголовок <th>Пациент</th> ?>
            <?php foreach($materials as $mat): ?>
                <th><?=$mat->name;?></th>
            <?php endforeach;?>
        </tr>        
        <?
        $row = 0;
        if(!$model->isNewRecord) { ?>
        <tr class="for-copy-row2 no-remove">
            <?php
            $field=0;
            $cnt = count($table2);


            foreach($table2 as $key => $table){


                if($field > 8){ // 9 полей
                    $field = 0;
                    $row++;
                    if($cnt!=$row) {
                        echo '</tr><tr>'; // новая строка
                    }
                }

               /*  if($field==0){ // список пациентов ?>
                    <td>
                        <select name="Materials[table2][<?=$row?>][0]">
                            <option value="0">Выберите значение</option>
                            <?php foreach ($pacients as $id=>$name):?>
                                <?
                                if ($id == $table->value) { // material_id = 0 - пациенты
                                    $selected = 'selected';
                                } else {
                                    $selected = '';
                                }
                                ?>
                                <option <?= $selected ?> value="<?= $id; ?>"><?= $name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                <?php }else{ */ ?>
                    <td>
                        <?php /* <input type="hidden" name="Materials[table2][id][]" value="<?=$table->id?>" /> */?>
                        <input type="text" name="Materials[table2][<?=$row?>][<?=$field?>]" value="<?=$table->value?>" />
                    </td>
            <?php   // }
            ?>
            <?php
                $field++;
            }
            $row++; // для следующей пустой строки
            ?>
        </tr>
        <? } //else{ //endforeach;  ?>
        <tr class="for-copy-row2 no-remove">
            <?php /*<td>
                <select name="Materials[table2][<?=$row?>][0]">
                    <option value="0">Выберите значение</option>
                    <? foreach ($pacients as $id=>$name):?>
                        <option value="<?= $id; ?>"><?= $name; ?></option>
                    <?endforeach; ?>
                </select>
            </td> */ ?>
            <?php foreach($materials as $mat): ?>
                <td><input type="text" name="Materials[table2][<?=$row?>][<?=$mat->id?>]" /></td>
            <?php 
            endforeach; ?>
        </tr>
        <?php // } ?>
    </table>
    <hr/>
<?php }else{ // page==2 - остатки ?>
    <table class="table table-bordered table-third" style="background: #fff">
        <tr>
            <?php  //<th>Пациент</th> ?>
            <th>Наименование материала</th>
            <th>Единица измерения</th>
            <th>Остаток, брутто</th>
            <th>Нормативный вес упаковки</th>
            <th>Остаток, нетто</th>
        </tr>
        <?
        if(!$model->isNewRecord) {
            foreach ($table3 as $key => $table):?>
                <tr class="no-remove">
                   <?php /* <td>
                        <select name="Materials[table3][pacient_id][]">
                            <option value="0">Выберите значение</option>
                            <? foreach ($pacients as $id => $name):?>
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
            <?php /*<td>
                <select name="Materials[table3][pacient_id][]">
                    <option value="0">Выберите значение</option>
                    <?php foreach ($pacients as $id => $name):?>
                        <option value="<?= $id; ?>"><?= $name; ?></option>
                    <?endforeach; ?>
                </select>
            </td> */?>
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
    </table>

    <span class="btn btn-success add_row_table3">Добавить строку</span>
    <span class="btn btn-danger del_row_table3">Удалить последнюю строку</span>

    <hr/>

<?php } ?>


    <input type="hidden" name="page" value="<?=$page?>" />

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php /* <a href="/reports/create?page=<?=$page?>" class="btn btn-success"/>Создать отчет</a> */ ?>

        <?php if(! $model->isNewRecord ) { ?>
            <?php if($page==1) { ?> <a class="btn btn-default print-btn" href="<?= Url::to(['/reports/print', 'id' => $model->id,'report-page'=>'1'])?>"><span class="glyphicon glyphicon-print"></span> Печать затрат</a> <?php } ?>
            <?php if($page==2) { ?> <a class="btn btn-default print-btn" href="<?= Url::to(['/reports/print', 'id' => $model->id,'report-page'=>'2'])?>"><span class="glyphicon glyphicon-print"></span> Печать остатков</a><?php } ?>
        <?php } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
if($page==1) {
    $script = "   
$('document').ready(function(){
    
    $('#add_pacient').click(function(){
        // alert($('select#1').html())
        $('.list').append('<select name=\"pacients[]\" class=\"form-control\">{$pacient_list}</select><br>');
    });
    $('.add_row_table1').click(function(){
        $('.table-first').append('<tr class=\"for-copy-row1\">' + $('#copy_table1').html() +'</tr>');
    });
    $('.add_row_table3').click(function(){
        $('.table-third').append('<tr class=\"for-copy-row3\">' + $('#copy_table3').html() +'</tr>');
    });
    $('.del_row_table1').click(function(){
        if(! $('.table-first tr:last-child').hasClass('no-remove') ) $('.table-first tr:last-child').remove();
    });
    $('.del_row_table3').click(function(){
        if(! $('.table-third tr:last-child').hasClass('no-remove') ) $('.table-third tr:last-child').remove();
    });
    
    $(document).on('change','select#mat', function(){
        //alert($(this).val());
        if( $(this).val() == 1 ) { // med-620
            $(this).parent().parent().find('#mass').val('15.67')
            $(this).parent().parent().find('#norm').val('15 - 20');
        }else if( $(this).val() == 2 ) { // support-705
            $(this).parent().parent().find('#mass').val('10.33');
            $(this).parent().parent().find('#norm').val('10 - 15');
        }else if( $(this).val() == 9 ) { // упаковочный пакет
            $(this).parent().parent().find('#mass').val('0.25');
        }else{
            $(this).parent().parent().find('#mass').val('')
            $(this).parent().parent().find('#norm').val('');
        }        
    })
    
});";


}else{

    $script = "   
$('document').ready(function(){
    

    $('.add_row_table3').click(function(){
        $('.table-third').append('<tr class=\"for-copy-row3\">' + $('#copy_table3').html() +'</tr>');
    });
    $('.del_row_table3').click(function(){
        if(! $('.table-third tr:last-child').hasClass('no-remove') ) $('.table-third tr:last-child').remove();
    });
    
    
});";

}

$this->registerJs($script, yii\web\View::POS_END);