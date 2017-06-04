<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Plans */

$this->title = "Версия ВП " . $model->version;
$this->params['breadcrumbs'][] = ['label' => 'Планы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$role = Yii::$app->user->identity->role;
?>
<div class="plans-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?if($role == 4):?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?endif;?>
    </p>
    <p>
        <?if($role == 2):?>
            <?= Html::a(Yii::t('app', 'Утвердить план и отправить врачу'), ['approve', 'id' => $model->id], ['class' => 'btn btn-default ']); ?>
        <?endif?>
        <?if($role == 1 && $model->approved == 1):?>
            <?= Html::a(Yii::t('app', 'Утвердить план (врачом)'), ['ready', 'id' => $model->id], ['class' => 'btn btn-default print-btn']); ?>
        <?endif?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'version',
            'doctor_id',
            'pacient_id',
            'order_id',
            //'ready',
            [
                'attribute' => 'ready',
                'value' => $model->ready == 1 ? 'Да' : 'Нет',
            ],
            //'ver_confirm',
            //'correct',
            /*[
                'attribute' => 'correct',
                'value' => $model->correct == 1 ? 'Корректный' : 'Не корректный',
            ],*/
            [
             'attribute' => 'approved',
             'format' => 'raw',
             'value' => $model->approved == 1 ? 'Да' : 'Нет' 
            ],
            /*'count_models',
            'count_elayners',
            'count_attachment',
            'count_checkpoint',
            'count_reteiners',
            'count_models_vp',
            'count_elayners_vp',
            'count_attachment_vp',
            'count_checkpoint_vp',
            'count_reteiners_vp',*/
            /*'level_1_doctor_id',
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
            'level_5_result:ntext',*/
            'comments:ntext',
            //'files',
        ],
    ]) ?>

    <?php // список файлов
    //vd($model->file);
    if( is_array($model->fileList) && count( $model->fileList ) ){


        $files = json_decode($model->files);
        echo '<table class="table"><tr><td><strong>Имя файла</strong></td><td><strong>Размер</strong></td><td><strong>Скачать</strong></td><td><!--<strong>Удалить</strong>--></td></tr></tr>';
        $path = Yii::getAlias("@backend/web/uploads/plans/" . $model->id);
        foreach ($model->fileList as $k=>$f) {
            echo '<tr><td>' . $f . '</td><td>' . filesize( $path .'/' . $f ) . '</td><td><a target="_blank" href="/plans/download?file=' . $f . '&id=' . $model->id . '"><span class="glyphicon glyphicon-save" id="' . $model->id . '" ></span></a></td><td><!--<a target="_blank" href="/plans/delete?file=' . $f . '&id=' . $model->id . '"><span class="glyphicon glyphicon-trash" id="' . $model->id . '" ></span></a>--></td></tr>';
        }
        echo '</table>';
    }
    ?>

</div>
