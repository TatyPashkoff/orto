<?php 
use backend\models\Payments;
use backend\models\Pacients;

$role = Yii::$app->user->identity->role;


	$pay = Payments::getTarifAndPaid($model->pacient_code);

	if( ! $vp = \backend\models\Plans::findOne($model->vplan_id) ) { //->where(['pacient_id'=>$model->pacient_code,'order_id'=>$model->id])->one();
		$vp = new \backend\models\Plans();
	}
	/*
	 массив pay возвращает:
		sum_price - стоимость пакета по прайсу
		sum_discount - стоимость с учетом скидки
		sum_need_by_plan - план
		sum_paid - факт
		sum_need_by_plan - подлежит к оплате по заказу
		paid - оплачено по заказу
		date_last_paid - дата оплаты
		debt - долг по заказу
		date_next_paid - дата планируемого погашения
	*/

if (isset($model)): ?>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://_ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<style>
			.table-bordered>tbody>tr>th{text-align: center; background: #f1efef; }
			.table-bordered>tbody>tr>td{text-align: center;}
			.first_td:nth-child(1){width:20%; }
			td li{list-style-type:none }
			.table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {padding: 5px 5px!important; }
			table>tbody>tr>td.base{vertical-align: middle; display: table-cell; }
			.container{page-break-after:always; }
			.paket_info{
				//font-weight: bold;
				width:300px;
				float:left;
				margin:5px; 10px;
				padding:0px 0px 0px 20px;
			}
			.paket_info>div{
				float:left;
				margin:5px; 10px;
			}
		</style>
	</head>
	<div class="container">
		<h4> 
			<span>ЗАКАЗ-НАРЯД НА ПРОИЗВОДСТВО №<?php echo $model->num ?></span>
			<span class="pull-right">ТОО &quot;SmartForce&quot;</span>
		</h4><br>

		<table class="table table-bordered">
			<tbody>
				<tr>
					<td>Дата</td>
					<td colspan="5"><?php echo date('d.m.Y', strtotime($model->date)) ?></td>
				</tr>
				<tr>
					<td>Код пациента</td>
					<td colspan="5"><?php echo $model->pacient_code ?></td>
				</tr>
				<tr>
					<?php 
						$pacient = Pacients::find()->where(['code'=>$model->pacient_code])->one();
					?>
					<td>ФИО Пациента</td>
					<td colspan="5"><?= $pacient->name ?></td>
				</tr>
				<tr>
				<?php
					$sculls = ['Верхняя','Нижняя','Верхняя и нижняя'];
				?>
					<td>Челюсть (В, Н, ВН)</td>
					<td colspan="5"><?=$sculls[$model->scull_type]?></td>
				</tr>
			</tbody>
		</table>
		<table class="table table-bordered">
			<h3 class="text-center">Наименование продукции</h3>
			<tr>
				<td rowspan="2" class="base">Элайнер</td>
				<td colspan="2">Коррекция</td>
				<td rowspan="2" class="base">Ретейнер</td>
				<td rowspan="2" class="base">Бесплатный элайнер</td>
				<td rowspan="2" class="base">Клинические исследования</td>
			</tr>
			<tr>
				<td>Платная</td>
				<td>Бесплатная</td>
			</tr>
			<tr>
				<td><?=$model->type==0? '+' : ''?></td>
				<td><?=$model->type==1? '+' : ''?></td>
				<td><?=$model->type==2? '+' : ''?></td>
				<td><?=$model->type==3? '+' : ''?></td>
				<td><?=$model->type==4? '+' : ''?></td>
				<td><?=$model->type==5? '+' : ''?></td>
			</tr>
		</tbody>
	</table>
	<table class="table table-bordered">
		<tbody>
			<h3 class="text-center">Заказ</h3>
			<tr>
				<td>Заказ</td>
				<td>Планируемое кол-во по ВП<br>(верхняя челюсть)</td>
				<td>Планируемое кол-во по ВП<br>(нижняя челюсть)</td>
				<td>Количество на изготовление<br>(верхняя челюсть)</td>
				<td>Количество на изготовление<br>(нижняя челюсть)</td>
				<td>Этап ВЧ</td>
				<td>Этап НЧ</td>
			</tr>
			<?php /*<tr>
				<td>Количество моделей</td>
				<td><?=$model->count_models_vp?></td>
				<td><?=$model->count_models?></td>
				<td><?=$model->stage_models_vc?></td>
				<td><?=$model->stage_models_nc?></td>
				<td></td>
			</tr> */ ?>
			<tr>
				<td>Количество элайнеров (кап)</td>
				<td><?=$vp->count_elayners_vc?></td>
				<td><?=$vp->count_elayners_nc?></td>
				<td><?=$model->count_elayners_vc?></td>
				<td><?=$model->count_elayners_nc?></td>
				<td><?=$model->stage_elayners_vc?></td>
				<td><?=$model->stage_elayners_nc?></td>
			</tr>
			<tr>
				<td>Количество аттачментов (кап)</td>
				<td><?=$vp->count_attachment_vc?></td>
				<td><?=$vp->count_attachment_nc?></td>
				<td><?=$model->count_attachment_vc?></td>
				<td><?=$model->count_attachment_nc?></td>
				<td><?=$model->stage_attachment_vc?></td>
				<td><?=$model->stage_attachment_nc?></td>
			</tr>
			<tr>
				<td>Количество Check-point (кап)</td>
				<td><?=$vp->count_checkpoint_vc?></td>
				<td><?=$vp->count_checkpoint_nc?></td>
				<td><?=$model->count_checkpoint_vc?></td>
				<td><?=$model->count_checkpoint_nc?></td>
				<td><?=$model->stage_checkpoint_vc?></td>
				<td><?=$model->stage_checkpoint_nc?></td>
			</tr>
			<tr>
				<td>Количество ретейнеров (кап)</td>
				<td><?=$vp->count_reteiners_vc?></td>
				<td><?=$vp->count_reteiners_nc?></td>
				<td><?=$model->count_reteiners_vc?></td>
				<td><?=$model->count_reteiners_nc?></td>
				<td><?=$model->stage_reteiners_vc?></td>
				<td><?=$model->stage_reteiners_nc?></td>
			</tr>
			<tr>
				<td>Всего кап</td>
				<td>
					<?php echo  $vp->count_elayners_vc + $vp->count_attachment_vc +  $vp->count_checkpoint_vc + $vp->count_reteiners_vc ?>
				</td>
				<td>
					<?php echo $vp->count_elayners_nc + $vp->count_attachment_nc +  $vp->count_checkpoint_nc + $vp->count_reteiners_nc ?>
				</td>
				<td>
					<?php echo  $model->count_elayners_vc + $model->count_attachment_vc + $model->count_checkpoint_vc +  $model->count_reteiners_vc ?>
				</td>
				<td>
				<?php echo  $model->count_elayners_nc +  $model->count_attachment_nc +  $model->count_checkpoint_nc + $model->count_reteiners_nc ?>
				</td>
				<?php /*<td>
					<?php // echo $model->stage_elayners_vc + $model->stage_attachment_vc + $model->stage_checkpoint_vc + $model->stage_reteiners_vc?>
				</td>
				<td>
					<?php //echo $model->stage_elayners_nc + $model->stage_attachment_nc + $model->stage_checkpoint_nc + $model->stage_reteiners_nc?>
				</td> */?>
				<td colspan="2"></td>
			</tr>
			<tr>
				<td colspan="7" >Стоимость по прайсу и предыдущие оплаты</td>
			</tr>
			<tr><td colspan="2" >Пакет</td><td colspan="5"><?=$pay['paket']?></td></tr>
			<tr><td colspan="2" >Стоимость пакета</td><td colspan="5"><?=$pay['sum_price']?></td></tr>
			<tr><td colspan="2" >Стоимость пакета с учетом скидки</td><td colspan="5"><?=$pay['sum_discount']?></td></tr>
			<tr><td colspan="2" >Сумма задолженности</td><td colspan="5"><?=$pay['debt']?></td></tr>
			<tr><td colspan="2" >Дата планируемого погашения</td><td colspan="5"><?= isset($pay['date_next_paid']) && $pay['date_next_paid'] !='' ? date('d-m-Y', strtotime($pay['date_next_paid'])):'' ?></td></tr>
		</tbody>
	</table>

		<br>
	<table class="table table-bordered" border="0">
		<tbody>
			<p><span>Сумма задолженности (при наличии)</span><span class="pull-right">_______________________</span></p>
			<br>
			<?php /*
			<tr>
				<td>Подлежит к оплате по заказу</td>
				<td>Оплачено по заказу</td>
				<td>Дата оплаты</td>
				<td>Долг по заказу</td>
				<td>Дата планируемого погашения</td>
			</tr>
			<tr>
				<td><?=$pay['sum_need_by_plan']?></td>
				<td><?=$pay['sum_paid'];?></td>
				<td><?=date('d-m-Y',$pay['date_last_paid']) ?></td>
				<td><?=$pay['debt']?></td>
				<td><?=date('d-m-Y',$pay['date_next_paid']) ?></td>
			</tr>
			<tr>
				<td colspan=2>Основание скидки/долга/бесплатного отпуска (при наличии)</td>
				<td colspan="3"></td>
			</tr> */?>

			<?php // if( $role != 0 ){ ?>
			<tr>
				<td colspan="2">Разрешить печать со скидкой/долгом/бесплатно (подписывается руководителем)</td>
				<td colspan="4">________________</td>
			</tr>
			<?php // } ?>

			<tr>
				<td>Бухгалтер</td>
				<td colspan="2">_____________________________</td>
				<td colspan="2">_________</td>
			</tr>
			<tr>
				<td></td>
				<td colspan="2">ФИО</td>
				<td colspan="2">Подпись</td>
			</tr>
			<tr>
				<td>Техник</td>
				<td colspan="2">_____________________________</td>
				<td colspan="2">_________</td>
			</tr>
			<tr>
				<td></td>
				<td colspan="2">ФИО</td>
				<td colspan="2">Подпись</td>
			</tr>
			<tr>
				<td>Руководитель</td>
				<td colspan="2">_____________________________</td>
				<td colspan="2">_________</td>
				<tr>
					<td></td>
					<td colspan="2">ФИО</td>
					<td colspan="2">Подпись</td>
				</tr>
			</tbody>
		</table>
	</div>
    <!--end of container-->

<?php endif; ?>
