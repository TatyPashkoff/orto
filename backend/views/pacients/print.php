<?php

if (isset($pacient)):

    $is_reteiner = $pacient->product_id == 2; ?>

    <head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<style>
			.table-bordered>tbody>tr>th{text-align: center; background: #f1efef; }
			.table-bordered>tbody>tr>td{text-align: center;}
			.first_td:nth-child(1){width:20%; }
			td li{list-style-type:none }
			.table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {padding: 5px 5px!important; }
			table>tbody>tr>td.base{vertical-align: middle; display: table-cell; }
			.container{page-break-after:always; }
            .btn.btn-zub{
                background:#eee;
            }
            .btn.btn-zub.active{
                border:3px solid #2077C1 !important;
            // background:#9acfea;
            }
		</style>
	</head>
	<div class="container">
        <img style="vertical-align: top;" src="/images/logo.png" width="100px">

	<?php if(! $is_reteiner){ ?>
        <h4 class="text-right">Приложение № </h4>
    	<h4 class="text-right">к Договору №____ от «___» _______ 201___г.</h4>
    <?php } ?>
    <?php if($is_reteiner){ ?>
        <h4><span class="pull-left">БЛАНК ЗАКАЗА для изготовления ретенионной каппы №____</span>  		<span class="pull-right">«____»_____________ 201___г.</span></h4><br><br>
	<?php }else{ ?>
        <h4><span class="pull-left">БЛАНК ЗАКАЗА на изготовление элайнеров №____</span>  		<span class="pull-right">«____»_____________ 201___г.</span></h4><br><br>
    <?php } ?>
	<table class="table table-bordered">
		<tbody>
			<tr> <th colspan="2" >ПАСПОРТНАЯ ЧАСТЬ</th> </tr> <tr> <th colspan="2">ЗАКАЗЧИК</th> </tr>
			<tr>
				<td class="first_td">ФИО Врача</td>
				<td><?=$pacient->getDoctorFirstname();?></td>
			</tr>
			<tr>
				<td class="first_td">Специализация</td>
				<td>Стоматолог-ортодонт</td>
			</tr>
			<tr>
				<td class="first_td">Название Клиники</td>
				<td><?=$pacient->getClinicTitle()?></td>
			</tr>
			<tr>
				<td class="first_td">Телефон</td>
				<td><?= $pacient->getDoctorPhone()?></td>
			</tr>
			<tr>
				<td class="first_td">e-mail</td>
				<td><?=$pacient->getDoctorEmail()?></td>
			</tr>

			<tr> <th colspan="2">ПАЦИЕНТ</th> </tr>
			<tr>
				<td class="first_td">ФИО Пациента</td>
				<td><?= $pacient->name; ?></td>
			</tr>
			<tr>
				<td class="first_td">Дата рождения</td>
				<td><?= date('d-m-Y', $pacient->age)?></td>
			</tr>
			<tr>
				<td class="first_td">Пол</td>
				<td><?php
                        if ($pacient->gender == 1) {
                            echo 'мужчина';
                        } elseif ($pacient->gender == 0) {
                            echo 'женщина';
                        }
                        ?></td>
			</tr>
            <tr>
                <td class="first_td">Телефон</td>
                <td><?= $pacient->phone?></td>
            </tr>
            <tr>
                <td class="first_td">email</td>
                <td><?= $pacient->email?></td>
            </tr>
        <?php if( ! $is_reteiner ){ ?>
            <tr>
                <td class="first_td">Диагноз</td>
                <td><?= $pacient->diagnosis; ?></td>
            </tr>
			<tr>
				<th colspan="2">ЗУБНАЯ ФОРМУЛА<br>
					Сокращения исп. в таблице ниже: С – кариес; Р – пульпит; Pt – периодонтит;F – резорцин-формалиновый зуб; П – пломба; A –пародонтит, пародонтоз , в скобках (I-IV) - степень  подвижности; К – коронка; И – искусственный  зуб; О – отсутствующий зуб; R – корень; I –имплантат.
				</th>
			</tr>
        <?php }else{ ?>
            <tr>
                <td class="first_td">Верхняя челюсть</td>
                <td><?= $pacient->scull_top ? '+' : '' ; ?></td>
            </tr>
            <tr>
                <td class="first_td">Нижняя челюсть</td>
                <td><?= $pacient->scull_bottom ? '+' : '' ; ?></td>
            </tr>

        <?php } ?>


		</table>
<?php

// если не ретейнер
if( ! $is_reteiner ) {


            $formula = json_decode($pacient->formula, true);
            if(isset($formula['teeth1']))
                $teeth1 = $formula['teeth1'];
            if(isset($formula['formula']))
                $opt = $formula['formula'];
            if(isset($formula['remove']))
                $remove = $formula['remove'];
            if(isset($formula['not_moving']))
                $not_moving = $formula['not_moving'];
            if(isset($formula['cant_install']))
                $cant_install = $formula['cant_install'];
            if(isset($formula['change']))
                $change = $formula['change'];
            if(isset($formula['implant']))
                $implant = $formula['implant'];
            $type1 = ['A' => 'TIP ( ангуляция)', 'R' => 'ROTATION', 'T' => 'TORGUE', 'B/L' => 'BUCCAL-LINGUAL', 'E' => 'EXTRUSION', 'I' => 'INTRUSION', 'M/D' => 'MESIAL-DISTAL'];
            $type2 = ['С' => 'кариес', 'Р' => 'пульпит', 'Pt' => 'периодонтит', 'F' => 'резорцин-формалиновый зуб', 'П' => 'пломба', 
                        'A1' => 'пародонтит, пародонтоз (степень  подвижности - I)', 'A2' => 'пародонтит, пародонтоз (степень  подвижности - II)', 
                        'A3' => 'пародонтит, пародонтоз (степень  подвижности - III)', 'A4' => 'пародонтит, пародонтоз (степень  подвижности - IV)', 'К' => 'коронка', 
                        'И' => 'искусственный зуб', 'О' => 'отсутствующий зуб', 'R' => 'корень', 'I' => 'имплантат'];
            $opt_data = [0 => 'Оставить как есть', 1 => 'Расширить', 2 => 'Сузить'];
            $opt_data0 = [0 => 'Не менять', 1 => 'Улучшить'];
            $opt_data1 = [0 => 'Не менять', 1 => 'Устранить протрузию', 2 => 'Устранить ретрузию'];
            $opt_data2 = [0 => 'Не менять', 1 => 'Установить резцы в контакт', 2 => 'Сохранить, если необходимо для поддержания класса'];
            $opt_data4 = [0 => 'Не менять', 1 => 'Интрузия', 2 => 'Экструзия'];
            $zub = $formula['formula']['zub'];
            //$fang = $formula['formula']['fang'];
            $opt_data5 = ['' => '', 1 => '1', 2 => '2'];
            
            $tooths = [18=>'1.8', 17=>'1.7', 16=>'1.6', 15=>'1.5', 14=>'1.4', 13=>'1.3', 12=>'1.2', 11=>'1.1', 
                        21=>'2.1', 22=>'2.2', 23=>'2.3', 24=>'2.4', 25=>'2.5', 26=>'2.6', 27=>'2.7', 28=>'2.8', 
                        48=>'4.8', 47=>'4.7', 46=>'4.6', 45=>'4.5', 44=>'4.4', 43=>'4.3', 42=>'4.2', 41=>'4.1', 
                        31=>'3.1', 32=>'3.2', 33=>'3.3', 34=>'3.4', 35=>'3.5', 36=>'3.6', 37=>'3.7', 38=>'3.8'];
?>
		<table class="table table-bordered ">
			<tbody>
				<tr>
                    <?php
                    foreach ($tooths as $k=>$t) :
                    if(isset($teeth1[$k]) && is_array($teeth1[$k])) 
                        echo '<td>'.join(',', array_keys($teeth1[$k])).'</td>';
                    else
                        echo '<td>&nbsp;</td>';
                    if ($k > 27) {
                        break;
                    }
                    endforeach;?>
                </tr>
				<tr>
                    <?php 
                    foreach ($tooths as $k=>$t) : 
                    echo '<td>'.$t.'</td>';
                    if ($k == 28) {
                        echo '</tr><tr>';
                    } 
                    endforeach;?>
                </tr>
                <tr>
                    <?php
                    foreach ($tooths as $k=>$t) :
                    if ($k <= 28) continue;
                    if(isset($teeth1[$k]) && is_array($teeth1[$k])) 
                        echo '<td>'.join(',', array_keys($teeth1[$k])).'</td>';
                    else
                        echo '<td></td>';                     
                    endforeach;?>
                </tr>
			</tbody>
		</table>
		<table class="table table-bordered ">
			<tbody>
				<tr> <th colspan="7">ПЛАН ЛЕЧЕНИЯ</th> </tr>
				<tr> <th colspan="7">ИСПРАВЛЕНИЕ ЗУБНОЙ ДУГИ</th> </tr>
				<tr> <th rowspan="2" class="base">ЗУБНАЯ ДУГА</th> <th colspan="3">ВЕРХНЯЯ ЧЕЛЮСТЬ</th> <th colspan="3">НИЖНЯЯ ЧЕЛЮСТЬ</th> </tr>
                <tr> 
                
				<th>ОСТАВИТЬ КАК ЕСТЬ</th> <th>РАСШИРИТЬ</th> <th>СУЗИТЬ</th> <th>ОСТАВИТЬ КАК ЕСТЬ</th> <th>РАСШИРИТЬ</th> <th>СУЗИТЬ</th> 
                </tr>
				<tr>
                <td></td>
                    <?foreach($opt_data as $key => $data):
                        if(isset($opt['opt_1']) && $opt['opt_1'] == $key):?>
                        <td>+</td>
                        <?else:?>
                        <td></td>
                        <?endif;?>                        
                    <?endforeach;?>
					<?foreach($opt_data as $key => $data):
                        if(isset($opt['opt_2']) && $opt['opt_2'] == $key):?>
                        <td>+</td>
                        <?else:?>
                        <td></td>
                        <?endif;?>
                    <?endforeach;?>
				</tr>
			</tbody>
		</table>
		<table class="table table-bordered ">
			<tbody>
				<tr> <th colspan="10">Соотношение резцов</th> </tr>
				<tr> <td rowspan="2" class="base">Соотношение резцов по трансверзали (средняя линия) </td> <td colspan="5">не менять</td> <td colspan="5">улучшить</td> </tr>
				<tr>
                    <?if(isset($opt['opt_3'])):?>
                    <td colspan="9"><b></strong><?=$opt_data0[$opt['opt_3']]?></b></td>
                    <?else:?>
                    <td colspan="9"></td>
                    <?endif;?>
                </tr>
				<tr> <td rowspan="3">По Сагиттали</td> <td colspan="3">верхние</td> <td colspan="3">нижние</td> <td colspan="3">сагиттальная щель</td> </tr>
				<tr> <td>Не менять</td> <td>Устранить протрузию</td> <td>Устранить ретрузию</td> <td>Не менять</td> <td>Устранить протрузию</td> <td>Устранить ретрузию</td> <td>Не менять</td> <td>Устранить протрузию</td> <td>Устранить ретрузию</td> </tr>
				<tr>                    
					<?foreach($opt_data1 as $key => $data):
                        if(isset($opt['opt_4']) && $opt['opt_4'] == $key):?>
                        <td><?=$opt_data1[$opt['opt_4']]?></td>
                        <?else:?>
                        <td></td>
                        <?endif;?>
                    <?endforeach;?>
					<?foreach($opt_data1 as $key => $data):
                        if(isset($opt['opt_5']) && $opt['opt_5'] == $key):?>
                        <td><?=$opt_data1[$opt['opt_5']]?></td>
                        <?else:?>
                        <td></td>
                        <?endif;?>
                    <?endforeach;?>
					<?foreach($opt_data2 as $key => $data):
                        if(isset($opt['opt_6']) && $opt['opt_6'] == $key):?>
                        <td><?=$opt_data2[$opt['opt_6']]?></td>
                        <?else:?>
                        <td></td>
                        <?endif;?>
                    <?endforeach;?>
				</tr>
			</tbody>
		</table>
		<table class="table table-bordered">
			<tbody>
				<tr> <th rowspan="3">ВЕРТИКАЛЬНОЕ ПЕРЕКРЫТИЕ</th> <th colspan="3">Верхние</th> <th colspan="3">Нижние</th> </tr>
				<tr> <td>Не менять</td> <td>Интрузия</td> <td>Экструзия</td> <td>Не менять</td> <td>Интрузия</td> <td>Экструзия</td> </tr>
				<tr>
					<?foreach($opt_data4 as $key => $data):
                        if(isset($opt['opt_7']) && $opt['opt_7'] == $key):?>
                        <td><?=$opt_data4[$opt['opt_7']]?></td>
                        <?else:?>
                        <td></td>
                        <?endif;?>
                    <?endforeach;?>
                    <?foreach($opt_data4 as $key => $data):
                        if(isset($opt['opt_8']) && $opt['opt_8'] == $key):?>
                        <td><?=$opt_data4[$opt['opt_8']]?></td>
                        <?else:?>
                        <td></td>
                        <?endif;?>
                    <?endforeach;?>
				</tr>
			</tbody>	
		</table> 
		<table class="table table-bordered">
			<?php /*<tbody>
				<tr><th colspan="7">СООТНОШЕНИЕ ЗУБОВ ПОСЛЕ ЛЕЧЕНИЯ (отметить)</th></tr>
				<tr> <th>СООТНОШЕНИЕ ЗУБОВ</th> <th colspan="3">R</th> <th colspan="3">L</th> </tr>
				<tr>
					<td>ПЕРВЫХ МОЛЯРОВ</td>
					<td>1</td>
					<td>2</td>
					<td>3</td>
					<td>1</td>
					<td>2</td>
					<td>3</td>
				</tr>
				<tr> <th rowspan="2">ЗА СЧЕТ ЧЕГО? (способ) </th> <td colspan="2">Дистализация</td> <td colspan="2">Мезиализация</td> <td colspan="2">Сепарация</td> </tr>
				<tr>
					
                    <?if(isset($zub)):?>
                        <td colspan="2"><?=$zub['dis']?></td>
                        <td colspan="2"><?=$zub['mez']?></td>
                        <td colspan="2"><?=$zub['sep']?></td>
                    <?else:?>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                    <?endif;?>

				</tr>
			</tbody> */ ?>

        <thead>
            <tr style="text-align:center">
                <th>СООТНОШЕНИЕ ЗУБОВ</th>
                <th colspan="3">R</th>
                <th colspan="3">L</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>ПЕРВЫХ МОЛЯРОВ</td>
                <td><input type="button" class="btn btn-zub zub_pmr <?=isset($zub['pmr'])&&$zub['pmr']==1?'active':''?>" data-id="1" value="1" /></td>
                <td><input type="button" class="btn btn-zub zub_pmr <?=isset($zub['pmr'])&&$zub['pmr']==2?'active':''?>" data-id="2" value="2" /></td>
                <td><input type="button" class="btn btn-zub zub_pmr <?=isset($zub['pmr'])&&$zub['pmr']==3?'active':''?>" data-id="3" value="3" /></td>
                <td><input type="button" class="btn btn-zub zub_pml <?=isset($zub['pml'])&&$zub['pml']==1?'active':''?>" data-id="1" value="1" /></td>
                <td><input type="button" class="btn btn-zub zub_pml <?=isset($zub['pml'])&&$zub['pml']==2?'active':''?>" data-id="2" value="2" /></td>
                <td><input type="button" class="btn btn-zub zub_pml <?=isset($zub['pml'])&&$zub['pml']==3?'active':''?>" data-id="3" value="3" /></td>
            </tr>
            <tr>
                <td>ЗА СЧЕТ ЧЕГО? (способ)</td>
                <td><input type="button" class="btn btn-zub zub_disr <?=isset($zub['dis_r'])&&$zub['dis_r']==1?'active':''?>" data-id="1" value="Дистализация" /></td>
                <td><input type="button" class="btn btn-zub zub_disr <?=isset($zub['dis_r'])&&$zub['dis_r']==2?'active':''?>" data-id="2" value="Мезиализация" /></td>
                <td><input type="button" class="btn btn-zub zub_disr <?=isset($zub['dis_r'])&&$zub['dis_r']==3?'active':''?>" data-id="3" value="Сепарация" /></td>
                <td><input type="button" class="btn btn-zub zub_disl <?=isset($zub['dis_l'])&&$zub['dis_l']==1?'active':''?>" data-id="1" value="Дистализация" /></td>
                <td><input type="button" class="btn btn-zub zub_disl <?=isset($zub['dis_l'])&&$zub['dis_l']==2?'active':''?>" data-id="2" value="Мезиализация" /></td>
                <td><input type="button" class="btn btn-zub zub_disl <?=isset($zub['dis_l'])&&$zub['dis_l']==3?'active':''?>" data-id="3" value="Сепарация" /></td>
                <?php /*<td colspan="2">
                        Дистализация:
                        <select name="Formula[zub][dis]" class="form-control small_select" id="">
                            <? foreach ($opt_data as $key => $data): ?>
                                <?
                                $selected = '';
                                if (isset($zub['dis']) && $zub['dis'] == $key) $selected = 'selected';
                                ?>
                                <option <?= $selected; ?> value="<?= $key; ?>"><?= $data; ?></option>
                            <? endforeach; ?>
                        </select>

                    </td>
                    <td colspan="2">
                        Мезиализация
                        <select name="Formula[zub][mez]" class="form-control small_select" id="">
                            <? foreach ($opt_data as $key => $data): ?>
                                <?
                                $selected = '';
                                if (isset($zub['mez']) && $zub['mez'] == $key) $selected = 'selected';
                                ?>
                                <option <?= $selected; ?> value="<?= $key; ?>"><?= $data; ?></option>
                            <? endforeach; ?>
                        </select>
                    </td>
                    <td colspan="2">
                        Сепарация
                        <select name="Formula[zub][sep]" class="form-control small_select" id="">
                            <? foreach ($opt_data as $key => $data): ?>
                                <?
                                $selected = '';
                                if (isset($zub['sep']) && $zub['sep'] == $key) $selected = 'selected';
                                ?>
                                <option <?= $selected; ?> value="<?= $key; ?>"><?= $data; ?></option>
                            <? endforeach; ?>
                        </select>
                    </td>*/ ?>
            </tr>
            <tr>
                <td>Комментарий</td>
                <td colspan="3"><?= isset($zub['com_r'])?></td>
                <td colspan="3"><?= isset($zub['com_l'])?></td>
            </tr>
            </tbody>
        </table>

        <table class="table table-bordered text-center">
            <thead>
            <tr style="text-align:center">
                <th rowspan="2">Клыков</th>
                <th colspan="3">R</th>
                <th colspan="3">L</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td></td>
                <td><input type="button" class="btn btn-zub zub_cr <?=isset($zub['c_r'])&&$zub['c_r']==1?'active':''?>" data-id="1" value="1" /></td>
                <td><input type="button" class="btn btn-zub zub_cr <?=isset($zub['c_r'])&&$zub['c_r']==2?'active':''?>" data-id="2" value="2" /></td>
                <td><input type="button" class="btn btn-zub zub_cr <?=isset($zub['c_r'])&&$zub['c_r']==3?'active':''?>" data-id="3" value="3" /></td>
                <td><input type="button" class="btn btn-zub zub_cl <?=isset($zub['c_l'])&&$zub['c_l']==1?'active':''?>" data-id="1" value="1" /></td>
                <td><input type="button" class="btn btn-zub zub_cl <?=isset($zub['c_l'])&&$zub['c_l']==2?'active':''?>" data-id="2" value="2" /></td>
                <td><input type="button" class="btn btn-zub zub_cl <?=isset($zub['c_l'])&&$zub['c_l']==3?'active':''?>" data-id="3" value="3" /></td>
            </tr>
            <tr>
                <td>ЗА СЧЕТ ЧЕГО? (способ)</td>
                <td><input type="button" class="btn btn-zub zub_cdisr <?=isset($zub['cdis_r'])&&$zub['cdis_r']==1?'active':''?>" data-id="1" value="Дистализация" /></td>
                <td><input type="button" class="btn btn-zub zub_cdisr <?=isset($zub['cdis_r'])&&$zub['cdis_r']==2?'active':''?>" data-id="2" value="Мезиализация" /></td>
                <td><input type="button" class="btn btn-zub zub_cdisr <?=isset($zub['cdis_r'])&&$zub['cdis_r']==3?'active':''?>" data-id="3" value="Сепарация" /></td>
                <td><input type="button" class="btn btn-zub zub_cdisl <?=isset($zub['cdis_l'])&&$zub['cdis_l']==1?'active':''?>" data-id="1" value="Дистализация" /></td>
                <td><input type="button" class="btn btn-zub zub_cdisl <?=isset($zub['cdis_l'])&&$zub['cdis_l']==2?'active':''?>" data-id="2" value="Мезиализация" /></td>
                <td><input type="button" class="btn btn-zub zub_cdisl <?=isset($zub['cdis_l'])&&$zub['cdis_l']==3?'active':''?>" data-id="3" value="Сепарация" /></td>
            </tr>
            <tr>
                <td>Комментарий</td>
                <td colspan="3"><?= isset($zub['com_cr'])?></td>
                <td colspan="3"><?= isset($zub['com_cl'])?></td>
            </tr>

            </tbody>
		</table>	
		<table class="table table-bordered">
			<tbody>
				<tr><th colspan="16">УДАЛЕНИЕ (отметить планируемые к удалению зубы)</th></tr>
                <tr>
                    <?php
                    foreach ($tooths as $k=>$t) :
                    if(isset($remove[$k])) 
                        echo '<td>+</td>';
                    else
                        echo '<td>-</td>';
                    if ($k > 27) {
                        break;
                    } 
                    endforeach;?>
                </tr>
				<tr>
                    <?php 
                    foreach ($tooths as $k=>$t) : 
                    echo '<td>'.$t.'</td>';
                    if ($k == 28) {
                        echo '</tr><tr>';
                    } 
                    endforeach;?>
                </tr>
                <tr>
                    <?php
                    foreach ($tooths as $k=>$t) :
                    if ($k <= 28) continue;
                    if(isset($remove[$k])) 
                        echo '<td>+</td>';
                    else
                        echo '<td>-</td>';                     
                    endforeach;?>
                </tr>
                
				<tr><th colspan="16">ЗУБЫ НЕТРЕБУЮЩИЕ ПЕРЕМЕЩЕНИЯ (отметить)</th></tr>
				<tr>
                    <?php
                    foreach ($tooths as $k=>$t) :
                    if(isset($not_moving[$k])) 
                        echo '<td>+</td>';
                    else
                        echo '<td>-</td>';
                    if ($k > 27) {
                        break;
                    } 
                    endforeach;?>
                </tr>
				<tr>
                    <?php 
                    foreach ($tooths as $k=>$t) : 
                    echo '<td>'.$t.'</td>';
                    if ($k == 28) {
                        echo '</tr><tr>';
                    } 
                    endforeach;?>
                </tr>
                <tr>
                    <?php
                    foreach ($tooths as $k=>$t) :
                    if ($k <= 28) continue;
                    if(isset($not_moving[$k])) 
                        echo '<td>+</td>';
                    else
                        echo '<td>-</td>';                     
                    endforeach;?>
                </tr>

				<tr><th colspan="16">НЕВОЗМОЖНО УСТАНОВИТЬ АТТАЧМЕНТЫ (отметить)</th></tr>
				<tr>
                    <?php
                    foreach ($tooths as $k=>$t) :
                    if(isset($cant_install[$k])) 
                        echo '<td>+</td>';
                    else
                        echo '<td>-</td>';
                    if ($k > 27) {
                        break;
                    } 
                    endforeach;?>
                </tr>
				<tr>
                    <?php 
                    foreach ($tooths as $k=>$t) : 
                    echo '<td>'.$t.'</td>';
                    if ($k == 28) {
                        echo '</tr><tr>';
                    } 
                    endforeach;?>
                </tr>
                <tr>
                    <?php
                    foreach ($tooths as $k=>$t) :
                    if ($k <= 28) continue;
                    if(isset($cant_install[$k])) 
                        echo '<td>+</td>';
                    else
                        echo '<td>-</td>';                     
                    endforeach;?>
                </tr>

				<tr><th colspan="16">ИЗМЕНИТЬ ПОЛОЖЕНИЕ ЗУБОВ</th></tr>
				<tr>
                    <?php
                    foreach ($tooths as $k=>$t) :
                    if(isset($change[$k])) 
                        echo '<td>'.join(',', array_keys($change[$k])).'</td>';
                    else
                        echo '<td>-</td>';
                    if ($k > 27) {
                        break;
                    } 
                    endforeach;?>
                </tr>
				<tr>
                    <?php 
                    foreach ($tooths as $k=>$t) : 
                    echo '<td>'.$t.'</td>';
                    if ($k == 28) {
                        echo '</tr><tr>';
                    } 
                    endforeach;?>
                </tr>
                <tr>
                    <?php
                    foreach ($tooths as $k=>$t) :
                    if ($k <= 28) continue;
                    if(isset($change[$k])) 
                        echo '<td>'.join(',', array_keys($change[$k])).'</td>';
                    else
                        echo '<td>-</td>';                     
                    endforeach;?>
                </tr>

				<tr><th colspan="16">ПЛАНИРУЕМЫЕ ПРОТЕЗЫ / ИМПЛАНТЫ (отметить место и размеры в мм.)</th></tr>
				<tr>
                    <?php

                   // print_r($implant);

                    $it=0; // смещение к id
                    foreach ($tooths as $k=>$t) :

                        $val = '';
                        $text_value ='';

                        // если текстовое поле НЕ пустое
                        if( isset($implant[$it]['text']) ) {
                            $text_value = $implant[$it]['text'];
                            unset($implant[$it]['text']); // удалить
                        }

                        if( isset($implant[$it]) && is_array($implant[$it]) ) {

                            if ($text_value != '') {
                                $val = join(',', array_keys($implant[$it]));
                                if ($val != '') {
                                    $val .= ',' . $text_value;
                                } else {
                                    $val = $text_value;
                                }
                            } else {
                                $val = join(',', array_keys($implant[$it]));
                            }



                        }else{
                            $val = '-';
                        }
                        echo '<td>' . $val . '</td>';
                        if ($k > 27) {  //>2.7
                            break;
                        }
                        $it++;
                    endforeach; ?>
                </tr>
				<tr>
                    <?php 
                    foreach ($tooths as $k=>$t) : 
                    echo '<td>'.$t.'</td>';
                    if ($k == 28) {
                        echo '</tr><tr>';
                    } 
                    endforeach;?>
                </tr>
                <tr>
                    <?php
                    $it++;
                    foreach ($tooths as $k=>$t) :
                    if ($k <= 28) continue;
                        $val = '';
                        $text_value ='';
                             // смещение к нужному зубу
                        // если текстовое поле НЕ пустое
                        if( isset($implant[$it]['text']) ) {
                            $text_value = $implant[$it]['text'];
                            unset($implant[$it]['text']); // удалить
                        }

                        if( isset($implant[$it]) && is_array($implant[$it]) ) {

                            if( $text_value != '' ) {
                                $val = join(',', array_keys($implant[$it]));
                                if($val!='') {
                                    $val .= ',' . $text_value;
                                }else{
                                    $val = $text_value;
                                }
                            }else{
                                $val = join(',', array_keys($implant[$it]));
                            }

                        }else{
                            $val = '-';
                        }
                        echo '<td>'. $val.'</td>';
                        $it++;
                    endforeach;?>
                </tr>
				<tr> <th colspan="16">КОММЕНТАРИИ</th> </tr>

				<tr>
					<td colspan="16">
                        <?php // =$pacient->comments;
                        if(isset($formula['comment']))
                            echo $formula['comment'];
                        ?>
                    </td>
				</tr>
				<tr><th colspan="16">Врач предоставил</th></tr>
				<tr>
					<td colspan="16" style="padding:20px 40px">
						<li class="text-left"><label><input <?= ($pacient->diagnostic_gips_modeli)?'checked':''?> type="checkbox"> Диагностические гипсовые модели</label></li>
						<li class="text-left"><label><input  <?= ($pacient->ottiski)?'checked':''?> type="checkbox"> Оттиски</label></li>
						<li class="text-left"><label><input  <?= ($pacient->prikusnic_valik)?'checked':''?> type="checkbox"> Прикусной валик</label></li>
						<li class="text-left"><label><input  <?= ($pacient->orta_tele)?'checked':''?> type="checkbox"> Ортопантогмограмму/ Телерентгенограмма</label></li>
						<li class="text-left"><label><input  <?= ($pacient->anfas_prof)?'checked':''?> type="checkbox"> Фотографии Пациента анфас и профиль, улыбки Пациента, внутриротовой снимок слева, справа, центр</label></li>
						<li class="text-left">Оплата в сумме: ___________ тенге</li>
					</td>
				</tr>
			</tbody>
		</table>


		<div>
			<span>ФИО Врача__________________________</span>
			<span class="pull-right">Подпись_________________________</span>
			<div class="clearfix"></div><br>
			<h5>Лаборатория <strong>«SmartForce»</strong>, 010000, г. Астана,  пр.Туран, 19/1, БЦ "ЭДЕМ", оф.505 Тел.: +7 (717) 246 -96-92, <i>info@ortholiner.kz</i></h5>
		</div>

<?php }else{ // если ретейнер $is_reteiner ?>

    <div>
        <span>ФИО представителя Заказчика __________________________</span>
        <span class="pull-right">Подпись _________________________</span>
        <div class="clearfix"></div><br>
        <span>ФИО представителя Исполнителя __________________________</span>
        <span class="pull-right">Подпись _________________________</span>
        <div class="clearfix"></div><br>

        <h5>Лаборатория <strong>«SmartForce»</strong>, 010000, г. Астана,  пр.Туран, 19/1, БЦ "ЭДЕМ", каб.203 Тел.: +7 (717) 246 -96-92, <i>info@ortholiner.kz</i></h5>
    </div>

<?php } ?>
</div><!-- end of container-->


<?php endif; ?>
