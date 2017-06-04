<?php

use yii\helpers\Html;

use backend\models\Price;

$this->title = 'План график оплаты';

$var_paid = [ '0' => 'Не задано', '3' => 'Бесплатно','1' => 'Частичная оплата','2' => 'Полная оплата' ];
$status_paid = ['0' => 'Не оплачено', '1' => 'Оплачено' ];

?>
<style>
	.table.top td:first-child{
		text-align: right;
	}
	.table td {
		border: 1px solid #e3e3e3;
		text-align: center;
		padding:5px;
		font-size:12pt;
	}
</style>

<div class="pacients-index">

    <h3><?=Html::encode($this->title) ?></h3>

    <table class="table top">
		<tr><td><strong>Пациент:</strong></td><td><?=$model->name?></td></tr>
		<tr><td><strong>Пакет:</strong></td><td><?=  Price::getPaketName($plan_graph->paket_id)?></td></tr>
		<tr><td><strong>Стоимость пакета:</strong></td><td><?=Price::getPrice($plan_graph->paket_id) - $plan_graph->sum_discount ?></td></tr>
		<tr><td><strong>Скидка:</strong></td><td><?=$plan_graph->sum_discount?></td></tr>
		<tr><td><strong>Способ оплаты:</strong></td><td><?=$var_paid[$plan_graph->var_paid]?></td></tr>
	</table>
	
	<br><br>
	<table class="table">
	<tr>
		<td><strong></strong></td>
		<td><strong>Дата</strong></td>
		<td><strong>Сумма оплаты</strong></td>
		<td><strong>Статус подтверждения</strong></td>
	</tr>
	
	<tr>
		<td>Дата предоплаты</td>
		<td><?=date('d-m-Y', strtotime($plan_graph->date_downpay))?></td>
		<td><?=$plan_graph->downpay?></td>
		<td><?=$status_paid[$plan_graph->status_paid]?></td>
	</tr>
	  
    <?php
    
    $m=0;
	$sum = ($plan_graph->status_paid==1)? $plan_graph->downpay : 0; // предоплата
    foreach($plan_items as $item){ 
        $m++;
		$sum += $item->status_paid==1? $item->sum : 0;
    ?>
	<tr>
		<td>Дата оплаты за месяц <?=$m?></td>
		<td><?=date('d-m-Y', strtotime($item->date))?></td>
		<td><?=$item->sum?></td>
		<td><?=$status_paid[$item->status_paid]?></td>
	</tr>
        
    <?php } ?>

		<tr>
			<td colspan="2"><strong>Итого оплачено:</strong></td>
			<td><strong><?=$sum?></strong></td>
			<td></td>
		</tr>
    </table>


</div>

