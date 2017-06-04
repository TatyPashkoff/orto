<?php
use backend\models\User;
use backend\models\Pacients;

if( $page==1 && ( ! isset($order) || count($order)==0 )  ){
    echo '<meta charset="UTF-8">Не все данные внесены!';
    exit;
}



$month = ['','Января','Февраля','Марта','Апреля','Мая','Июня','Июля','Августа','Сентября','Октября','Ноября','Декабря'];
$types = ['Картриджи','Фойлы','Упаковочный материал'];
if( ! $pacient = Pacients::find()->where(['code'=>$model->pacient_code])->one() ) $pacient = new Pacients();

?>
<style>
    th{background: #f1efef;}
    .container{page-break-after:always;}
    .row-head{font-weight:bold;text-align:center}
    .table td{text-align: center;}

    .table-bordered>tbody>tr>th{text-align: center; background: #f1efef; }
    .table-bordered>tbody>tr>td{text-align: center;}
    .first_td:nth-child(1){width:20%; }
    td li{list-style-type:none }
    .table-bordered > thead > tr > th,
    .table-bordered > tbody > tr > th,
    .table-bordered > tfoot > tr > th,
    .table-bordered > thead > tr > td,
    .table-bordered > tbody > tr > td,
    .table-bordered > tfoot > tr > td {padding: 5px 5px!important; }
    .table>tbody>tr>td.base{vertical-align: middle; display: table-cell; }

</style>

<?php if($page==1){ // первая страница затраты   ?>

<div class="container">
    <img style="vertical-align: top;" src="/images/logo.png" width="100px">
    <p style="margin-top: 50px; text-align: center;"><strong>&nbsp;&nbsp;&nbsp; ОТЧЕТ ОБ ИСПОЛНЕНИИ ЗАКАЗА И КОЛИЧЕСТВЕ ЗАТРАЧЕННЫХ МАТЕРИАЛОВ №
            <span style="text-decoration: underline;"><?= $model->id; ?></span></strong>
    </p>
    <p style="margin-top: 50px;"><strong>ТОО "SMARTFORCE"</strong>
        <span style="text-decoration: underline; float:right; margin-right: 30px; font-weight: bold; text-decoration: underline;">Дата печати "<?=date('d',time())?>"_<?= $month[ date('n') ] . date(' Y',time()); ?> г.</span>
    </p>
    <div class="clearfix"></div>
<?php // данные по заказу пользователя ?>

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
            foreach ($pacients as $id=>$name) {

                if(!isset($order[$id])) continue; ?>
            <tr>
                <td><?=$name?></td>
                <td><?=$id?></td>
                <td><?=$order[$id]['type']?></td>
                <td><?=$order[$id]['count_models']?></td>
                <td><?=$order[$id]['count_elayners']?></td>
                <td><?=$order[$id]['count_attachment']?></td>
                <td><?=$order[$id]['count_checkpoint']?></td>
                <td><?=$order[$id]['count_reteiners']?></td>
            <tr>
            <?php } ?>
            
            <tr>
                <td colspan="3">ИТОГО:</td>
                <td><?=$order['count_models']?></td>
                <td><?=$order['count_elayners']?></td>
                <td><?=$order['count_attachment']?></td>
                <td><?=$order['count_checkpoint']?></td>
                <td><?=$order['count_reteiners']?></td>
            </tr>
        
        </tbody>
    </table>
    <br>
    <div style="font-size:8pt;padding-bottom:20px;">*Элайнеры (Э), доп.элайнеры (ДЭ), ретейнеры (Р), бесплатная, платная коррекция (БК или ПК), беспл. элайнер (БЭ), клин. испытания (КИ)</div>
<?php // данные по заказу пользователя  ?>

    <table class="table table-bordered" style="background: #fff">
        <tr>
            <th>Наименование материала</th>
            <th>Всего, гр</th>
            <th>на 1 модель, гр</th>
            <th>Норма</th>
            <th>На начало периода</th>
            <th>На конец периода, гр</th>
        </tr>
        <?foreach($table1 as $key => $table):?>
        <tr class="for-copy-row1 no-remove">
            <td>
                <?foreach($materials as $mat):?>
                <?if($mat->id == $table->material_id){echo $mat->name;}?>
                <?endforeach;?>
            </td>
            <td><?=$table->total?></td>
            <td><?=$table->model?></td>
            <td><?=$table->norm?></td>
            <td><?=$table->begin?></td>
            <td><?=$table->end?></td>
        </tr>
        <?endforeach;?>
    </table>

    <?php // <div style="margin:20px 0px">Упаковочный пакет, шт: <?=$model->package ? ></div> ?>

    <hr />
    
    <? /* <table class="table table-bordered table-second" style="background: #fff">
        <tr>            
            <?foreach($materials as $mat):?>
                <th><?=$mat->name;?></th>
            <?endforeach;?>
        </tr>
        <tr class="for-copy-row2 no-remove">
            <?foreach($materials as $mat):?>
                <td><input type="text" name="Materials[table2][<?=$mat->id?>][]" /></td>
            <?endforeach;?>
        </tr>
    </table> */?>
    <table class="table table-bordered table-second" style="background: #fff">
        <tr>
            <th>Ф.И.О. пациента</th>
            <?foreach($materials as $mat):?>
                <th><?=$mat->name;?></th>
            <?endforeach;?>
    </tr>
    <tr class="for-copy-row2 no-remove">
        <?php
        $field=-1;
        $cnt = count($table2);
        $row =0;
        $itog = array_fill(0,11,0); // ИТОГО

       // echo '<pre>';print_r($table2);echo '<pre>';

        foreach($table2 as $key => $table){
            $field++;
            if($field==0){ // пациент ?>
                <td><?=isset($pacients[$table->pacient_id])?$pacients[$table->pacient_id]:''?></td>
            <?php
            }else if($field > 8){ // 9 полей ?>
                <td><?= $table->value?></td>
            <?php
                $itog[$field] += $table->value;
                $field = -1;
                $row++;
                //if($cnt!=$row) {
                    echo '</tr><tr>'; // новая строка
                //}
            }else{
                $itog[$field] += $table->value;     ?>
                <td><?= $table->value?></td>
            <?php
            }

           } ?>
    </tr>
    <tr>
        <td>ИТОГО</td>
        <td><?=$itog[1]?></td>
        <td><?=$itog[2]?></td>
        <td><?=$itog[3]?></td>
        <td><?=$itog[4]?></td>
        <td><?=$itog[5]?></td>
        <td><?=$itog[6]?></td>
        <td><?=$itog[7]?></td>
        <td><?=$itog[8]?></td>
        <td><?=$itog[9]?></td>
    </tr>

    </table>
    <br><br>
    <div class="row">
        <div class="col-md-6">
            ИСПОЛНИТЕЛЬ_<u><?=User::findOne(Yii::$app->user->id)->fullname?></u>________ (ФИО, Подпись)
        </div>
        <br>
        <div class="col-md-6">
            БУХГАЛТЕР      __________________________ (ФИО, Подпись)
        </div>
    </div>
    <br><br>
    <!--<p>Лаборатория <strong>&laquo;SmartForce&raquo;,</strong>
        010000, г. Астана,&nbsp; пр.Туран, 19/1, БЦ "ЭДЕМ", каб.505 Тел.: +7 (717) 246 -96-92, <a
            href="mailto:info@ortholiner.kz">info@ortholiner.kz</a>.</p>-->

</div>

<?php }elseif($page==2){ //  вторая страница остатки ?>

<!-- page 2 -  страница 2-->

<div class="container">
    <img style="vertical-align: top;" src="/images/logo.png" width="100px">
    <p style="margin-top: 50px; text-align: center;"><strong>&nbsp;&nbsp;&nbsp; ОТЧЕТ О ФАКТИЧЕСКИХ ОСТАТКАХ МАТЕРИАЛОВ №
            <span style="text-decoration: underline;"><?= $model->id; ?></span></strong>
    </p>
    <p style="margin-top: 50px;"><strong>ТОО "SMARTFORCE"</strong>
        <span style="text-decoration: underline; float:right; margin-right: 30px; font-weight: bold; text-decoration: underline;">Дата печати "<?=date('d',time())?>"_<?= $month[ date('n') ] . date(' Y',time()); ?> г.</span>
    </p>
    <div class="clearfix"></div>

    <table class="table table-bordered table-third" style="background: #fff">
        <tr>
            <th>Наименование материала</th>
            <th>Единица измерения</th>
            <th>Остаток, брутто</th>
            <th>Нормативный вес упаковки</th>
            <th>Остаток, нетто</th>
        </tr>
        <?php
            $table_names = [];
            $table_names[0] = ['name'=>'МЕД 620-М1','ed'=>'грамм'];
            $table_names[1] = ['name'=>'МЕД 620-М2','ed'=>'грамм'];
            $table_names[2] = ['name'=>'Support 705-М1','ed'=>'грамм'];
            $table_names[3] = ['name'=>'Support 705-М2','ed'=>'грамм'];
            $table_names[4] = ['name'=>'Итого МЕД-620','ed'=>'грамм'];
            $table_names[5] = ['name'=>'Итого Support 705','ed'=>'грамм'];
            $table_names[6] = ['name'=>'Erkodur 1mm','ed'=>'шт'];
            $table_names[7] = ['name'=>'Erkodur 1mm-frezee','ed'=>'шт'];
            $table_names[8] = ['name'=>'Erkodur 0.8mm','ed'=>'шт'];
            $table_names[9] = ['name'=>'Erkodur 0.6mm','ed'=>'шт'];
            $table_names[10] = ['name'=>'Коробка','ed'=>'шт'];
            $table_names[11] = ['name'=>'Контейнер','ed'=>'шт'];
            $table_names[12] = ['name'=>'Упаковочный материал','ed'=>'шт'];

            $is_new = false;
            if( ! isset($table3) || ! $table3_data = json_decode($table3->data) ){
                $is_new = true;
            }

            $item = 0;
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
                    <td><?= $table3_data[$item]->brutto ?></td>
                <?php if($item<4){ // только 4 верхних ?>
                        <td><?= $table3_data[$item]->ves ?></td>
                <?php }else{ ?>
                    <td></td>
                <?php }
                    if($item<6){ // не показывать нижние поля
                ?>
                    <td><?= $table3_data[$item]->netto ?></td>
                <?php } ?>
                </tr>

                <?
                $item++;
            } ?>
    </table>
    <br><br>
    <div class="row">
        <div class="col-md-6">
            Ответственный исполнитель_<u><?=User::findOne(Yii::$app->user->id)->fullname?></u>________ (ФИО, Подпись)
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6">
            Бухгалтер __________________________ (ФИО, Подпись)
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6">
            Руководитель __________________________ (ФИО, Подпись)
        </div>
    </div>

</div>

<?php  } // вторая страница остатки