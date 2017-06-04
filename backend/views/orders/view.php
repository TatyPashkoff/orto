<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Orders */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$role = Yii::$app->user->identity->role;

?>
<div class="orders-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a(Yii::t('app', 'Print'), ['print', 'id' => $model->id], ['class' => 'btn btn-default print-btn']); ?>
    </p>
    <p>

        <?if($role == 1 || $role == 4):?>
            <?= Html::a(Yii::t('app', 'Частичная оплата'), ['paid', 'id' => $model->id], ['class' => 'btn btn-default ']); ?>
        <?endif?>
        <?if($role == 4 || $role == 2):?>
            <?= Html::a(Yii::t('app', 'Отправить админу'), ['apply', 'id' => $model->id], ['class' => 'btn btn-default ']); ?>
            <?= Html::a(Yii::t('app', 'Назначения зубных техников'), ['appointment', 'id' => $model->id], ['class' => 'btn btn-default print-btn']); ?>
        <?endif?>
        <?if($role == 4 || $role == 3):?>
            <?= Html::a(Yii::t('app', 'Предоплата'), ['prepaid', 'id' => $model->id], ['class' => 'btn btn-default print-btn']); ?>
        <?endif?>
        <?if($role == 0 || $role == 2):?>
            <?echo Html::a('Создать планы', ['plans/create', $model->id], ['class' => 'btn btn-success']);?>
            <?= Html::a(Yii::t('app', 'Отметка об исполнении'), ['check', 'id' => $model->id], ['class' => 'btn btn-default print-btn']); ?>
        <?endif?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'date',
            'num',
            'group_id',
            'doctor_id',
            'clinics_id',
            'pacient_code',
            'date_paid',
            'type_paid',
            'status_paid',
            'status_object',
            'order_status',
            'admin_check',
            'status_agreement',
            'status',
            'scull_type',
            'type',
            //'price',
            'discount',
            'sum_paid',
            'count_models',
            'count_elayners',
            'count_attachment',
            'count_checkpoint',
            'count_reteiners',
            'count_models_vp',
            'count_elayners_vp',
            'count_attachment_vp',
            'count_checkpoint_vp',
            'count_reteiners_vp',
            'count_models_vc',
            'count_elayners_vc',
            'count_attachment_vc',
            'count_checkpoint_vc',
            'count_reteiners_vc',
            'count_models_nc',
            'count_elayners_nc',
            'count_attachment_nc',
            'count_checkpoint_nc',
            'count_reteiners_nc',
            'level_1_doctor_id',
            'level_2_doctor_id',
            'level_3_doctor_id',
            'level_4_doctor_id',
            'level_5_doctor_id',
            'level_1_status',
            'level_2_status',
            'level_3_status',
            'level_4_status',
            'level_5_status',
            'level_1_result:ntext',
            'level_2_result:ntext',
            'level_3_result:ntext',
            'level_4_result:ntext',
            'level_5_result:ntext',
            'comments:ntext',
            //'files',
        ],
    ]) ?>

<?
if( is_array($model->fileList) && count( $model->fileList ) ){
    $files = json_decode($model->files);
    echo '<table class="table"><tr><td><strong>Эскиз</strong></td><td><strong>Имя файла</strong></td><td><strong>Размер</strong></td><td><strong>Скачать</strong></td><td><strong>Удалить</strong></td></tr></tr>';
    $path = Yii::getAlias("@backend/web/uploads/orders/" . $model->id);
    foreach ($model->fileList as $k=>$f) {
        if( strpos($f,'.png')>0  || strpos($f,'.jpg')>0 || strpos($f,'.gif')>0 ){
            $img = '<img src="/uploads/orders/'. $model->id.'/'.$f .'" width="48" />';
        }else{
            $img = '<img src="/uploads/file.png" width="48" />';
        }
        echo '<tr><td>'.$img.'</td><td>' . $f . '</td><td>' . filesize( $path .'/' . $f ) . '</td><td><a target="_blank" href="/admin/orders/download?file=' . $f . '&id=' . $model->id . '"><span class="glyphicon glyphicon-save" id="' . $model->id . '" ></span></a></td><td><a target="_blank" href="/orders/delete?file=' . $f . '&id=' . $model->id . '"><span class="glyphicon glyphicon-trash" id="' . $model->id . '" ></span></a></td></tr>';
    }
    echo '</table>';
}
?>

</div>
