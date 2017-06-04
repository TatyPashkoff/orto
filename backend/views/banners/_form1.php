<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Banners */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banners-form">

   <?php   $form = ActiveForm::begin();  ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'banner')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_start')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_finish')->textInput([ 'maxlength' => true]) ?>

    <?= $form->field($model, 'text')->textArea(['rows'=>'5','maxlength' => true]) ?>


    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

 ActiveForm::end();  ?>

        <?php $form = ActiveForm::begin(); ?>

        <?if(!is_null($model->order_id)):?>
            <?= $form->field($model, 'title')->dropDownList([], $prompt) ?>
            <?= $form->field($model, 'pacient_id')->dropDownList([], $prompt) ?>
            <?= $form->field($model, 'order_id')->dropDownList([], $prompt) ?>
        <?else:?>
            <?= $form->field($model, 'doctor_id')->textInput(['readonly' => true]) ?>
            <?= $form->field($model, 'pacient_id')->textInput(['readonly' => true]) ?>
            <?= $form->field($model, 'order_id')->textInput(['readonly' => true]) ?>
        <?endif;?>

        <?= $form->field($model, 'version')->textInput(['maxlength' => true]) ?>

        <?/*= $form->field($model, 'ready')->textInput() */?><!--

    <?/*= $form->field($model, 'ver_confirm')->textInput(['maxlength' => true]) */?>

    <?/*= $form->field($model, 'correct')->dropDownList([
        0 => 'Не корректный',
        1 => 'Корректный'
    ]) */?>

    --><?/*= $form->field($model, 'approved')->dropDownList([
        0 => 'Не утверждён',
        1 => 'Утверждён'
    ]) */?>

        <?= $form->field($model, 'count_models')->textInput() ?>

        <?= $form->field($model, 'count_elayners')->textInput() ?>

        <?= $form->field($model, 'count_attachment')->textInput() ?>

        <?= $form->field($model, 'count_checkpoint')->textInput() ?>

        <?= $form->field($model, 'count_reteiners')->textInput() ?>

        <?= $form->field($model, 'count_models_vp')->textInput() ?>

        <?= $form->field($model, 'count_elayners_vp')->textInput() ?>

        <?= $form->field($model, 'count_attachment_vp')->textInput() ?>

        <?= $form->field($model, 'count_checkpoint_vp')->textInput() ?>

        <?= $form->field($model, 'count_reteiners_vp')->textInput() ?>



        <?/*= $form->field($model, 'level_1_doctor_id')->textInput() */?><!--

    <?/*= $form->field($model, 'level_2_doctor_id')->textInput() */?>

    <?/*= $form->field($model, 'level_3_doctor_id')->textInput() */?>

    <?/*= $form->field($model, 'level_4_doctor_id')->textInput() */?>

    <?/*= $form->field($model, 'level_5_doctor_id')->textInput() */?>

    <?/*= $form->field($model, 'level_1_status')->textInput() */?>

    <?/*= $form->field($model, 'level_2_status')->textInput() */?>

    <?/*= $form->field($model, 'level_3_status')->textInput() */?>

    <?/*= $form->field($model, 'level_4_status')->textInput() */?>

    <?/*= $form->field($model, 'level_5_status')->textInput() */?>

    <?/*= $form->field($model, 'level_1_result')->textarea(['rows' => 6]) */?>

    <?/*= $form->field($model, 'level_2_result')->textarea(['rows' => 6]) */?>

    <?/*= $form->field($model, 'level_3_result')->textarea(['rows' => 6]) */?>

    <?/*= $form->field($model, 'level_4_result')->textarea(['rows' => 6]) */?>

    --><?/*= $form->field($model, 'level_5_result')->textarea(['rows' => 6]) */?>

        <?= $form->field($model, 'comments')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'files[]')->fileInput(['multiple'=>true]) ?>

        <?php // список файлов
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

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>



</div>
