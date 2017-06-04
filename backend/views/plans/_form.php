<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Price;
use backend\models\Pacients;
use app\rbac\Plan;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model backend\models\Plans */
/* @var $form yii\widgets\ActiveForm */
$prompt = [
    'prompt' => 'Выберите значения...'
];
$role = Yii::$app->user->identity->role;
?>

<div class="plans-form">

    <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>

    <?php if(Yii::$app->user->identity->role != 1) : ?>
        <?= $form->field($model, 'pacient_id')->dropDownList($pacients, $prompt) ?>
        <?if(is_null($model->order_id) & 0):?>
            <?= $form->field($model, 'doctor_id')->dropDownList($doctors, $prompt) ?>
            <?= $form->field($model, 'pacient_id')->dropDownList($pacients, $prompt) ?>
            <?= $form->field($model, 'order_id')->dropDownList($orders, $prompt) ?>
        <?else:?>

        <?endif;?>
    <?php else : $p= Pacients::findOne($model->pacient_id);   ?>
        <br>
        <div><strong>Пациент:</strong> <?php echo $p->getName();?></div>
        <br>
    <?php endif; ?>

    <?php if(Plan::choseCreate($role)) :

        echo $form->field($model, 'creater')
            ->dropDownList(
                ArrayHelper::map(backend\models\User::find()->where(['status' => 1])
                    ->andWhere(['in', 'role', ['0']])->all(), 'id', 'fullname')
            );

    endif;?>

    <?php //планируемое кол-во моделей кап ?>

    <table class="table">
        <tr><td><strong>Наименование</strong></td><td><strong>Планируемое кол-во верхняя челюсть</strong></td><td><strong>Планируемое кол-во нижняя челюсть</strong></td></tr>

        <?= '<tr><td>Количество элайнеров (кап)</td><td>' . $form->field($model, 'count_elayners_vc')->textInput(['maxlength' => true])->label(false) . '</td><td>' . $form->field($model, 'count_elayners_nc')->textInput(['maxlength' => true])->label(false) . '</td></tr>' ?>
        <?php // '<td>' . $model->elayner_price_vc . '</td><td>' . $model->elayner_price_nc . '</td></tr>'  ?>

        <?= '<tr><td>Количество аттачментов (кап)</td><td>' . $form->field($model, 'count_attachment_vc')->textInput(['maxlength' => true])->label(false) . '</td><td>' . $form->field($model, 'count_attachment_nc')->textInput(['maxlength' => true])->label(false) . '</td></tr>' ?>
        <?php // '<td>' . $model->attachment_price_vc . '</td><td>' . $model->attachment_price_nc . '</td></tr>'  ?>

        <?= '<tr><td>Количество Check-point (кап)</td><td>' . $form->field($model, 'count_checkpoint_vc')->textInput(['maxlength' => true])->label(false) . '</td><td>' . $form->field($model, 'count_checkpoint_nc')->textInput(['maxlength' => true])->label(false) . '</td></tr>' ?>

        <?= '<tr><td>Количество ретейнеров (кап)</td><td>' . $form->field($model, 'count_reteiners_vc')->textInput(['maxlength' => true])->label(false) . '</td><td>' . $form->field($model, 'count_reteiners_nc')->textInput(['maxlength' => true])->label(false) . '</td></tr>' ?>
        <?php // '<td>' . (isset($clinic) && is_object($clinic) ? $clinic->reteiner_price : '') . '</td></tr>' ?>

    </table>

    <?php /*//таблица тарифов   ?>
    <table class="table" style="font-size:8pt">
        <tr><td><strong>Тарифный план</strong></td><td><strong>Количество от</strong></td><td><strong>Количество до</strong></td></tr>
        <?php
        $price = Price::find()->All();
        foreach ($price as $p){ ?>
            <td><?=$p->paket_name ?></td><td><?=$p->models_min?></td><td><?=$p->models_max?></td></tr>
  <?php } ?>
    </table>
     */ // таблица тарифов ?>

    <?php if(Yii::$app->user->identity->role != 1) : ?>

        <?= $form->field($model, 'comments')->textarea(['rows' => 6])->label('Этапы лечения:') ?>

    <?else:?>
        <div>
            Этапы лечения: <br>
            <?php echo $model->comments; ?>
        </div>
        <br>
    <?endif;?>

    <?php if($role == 4 || $role == 2) : ?>
    <?= $form->field($model, 'approved')->checkbox(); ?>
    <?php endif; ?>

    <?php if($role == 1) : ?>
    <?= $form->field($model, 'ready')->checkbox(); ?>
    <?= $form->field($model, 'cancel_msg')->textArea(['rows' => 3]); ?>



    <?php endif; ?>
    <?php if($role == 4 ||  $role == 2 || $role == 0) : ?>
    <?= $form->field($model, 'files[]')->fileInput(['multiple'=>true]) ?>
    <?php endif; ?>
    <?php // список файлов
    if( is_array($model->fileList) && count( $model->fileList ) ){

        $files = json_decode($model->files);
        echo '<table class="table"><tr><td><strong>Эскиз</strong></td><td><strong>Размер</strong></td><td><strong>Имя файла</strong></td><td><strong>Скачать</strong></td><td><!--<strong>Удалить</strong>--></td></tr></tr>';
        $path = Yii::getAlias("@backend/web/uploads/plans/" . $model->id);
        foreach ($model->fileList as $k=>$f) {
            if (strpos($f, '.png') > 0 || strpos($f, '.jpg') > 0 || strpos($f, '.jpeg') > 0 || strpos($f, '.gif') > 0) {
                $img = '<img src="/uploads/plans/' . $model->id . '/' . $f . '" width="300" />';
            } else {
                $img = '<img src="/uploads/file.png" width="48" />';
            }
            $_fs = 0;
            $fs = filesize( $path .'/' . $f ) ;
            if($fs>1048576) {
                $_fs = $fs / 1048576;
                $st = ' МБ';
            }else if($fs>1024){
                $_fs = $fs / 1024;
                $st = ' КБ';
            }
            echo '<tr><td>' . $img . '</td><td>' . sprintf( '%0.2f' ,$_fs) . $st . '</td><td>'.$f.'</td><td><a target="_blank" href="/plans/download?file=' . $f . '&id=' . $model->id . '"><span class="glyphicon glyphicon-save" id="' . $model->id . '" ></span></a></td><td><a href="/plans/delete-item?file=' . $f . '&id=' . $model->id . '"><span class="glyphicon glyphicon-trash" id="' . $model->id . '" ></span></a></td></tr>';
        }
        echo '</table>';
    }
    ?>

    <?php if($role!=1 && $role !=3){ ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?PHP } ?>
    <div class="form-group">

        <?if(!$model->isNewRecord):?>
            <a class="btn btn-primary" href="/plans/print?id=<?= $model->id ?>" target="_blank">Печать</a>
            <a class="btn btn-warning" href="/plans/downloadplan?id=<?= $model->id ?>" target="_blank">Скачать</a>
        <?endif;?>
        <?if($role == 2 || $role == 4):?>
            <?= Html::a(Yii::t('app', 'Утвердить план и отправить врачу'), ['approve', 'id' => $model->id], ['class' => 'btn btn-default ']); ?>
        <?endif?>
        <?if($role == 1 && $model->approved == 1):?>
            <?= Html::a(Yii::t('app', 'Утвердить план'), ['ready', 'id' => $model->id], ['class' => 'btn btn-default']); ?>
            <?= Html::a(Yii::t('app', 'Отказать'), ['cancel', 'id' => $model->id], ['class' => 'btn btn-danger', 'id' =>'cancel_vp_btn']); ?>
        <?endif?>
    </div>
    <input type="hidden" name="cancel_vp" id="cancel_vp" value="0" />

    <?php ActiveForm::end(); ?>

</div>

<?php
// при нажатии отмена нужно сохранить сообщение причины, потом вполнить отмену cancel
//cancel_vp
$script = "
$('document').ready(function(){
    jQuery('#cancel_vp_btn').click(function(){
        jQuery('#cancel_vp').val('1');
        jQuery('form#w0').submit();
        return false; // отмена нажатия на ссылку    
    });
})
";

$this->registerJs($script, yii\web\View::POS_END);