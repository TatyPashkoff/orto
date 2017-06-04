<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Banners */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banners-form">


    <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>
        
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'banner')->textInput(['maxlength' => true]) ?>

    <?php //= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'interval')->textInput() ?>

    <?= $form->field($model, 'link')->textInput() ?>

    <?php // $form->field($model, 'date')->textInput(['maxlength' => true, 'type' => 'date']) ?>

    <?php /* $form->field($model, 'date_start')->textInput(['maxlength' => true, 'type' => 'date']) ?>

    <?= $form->field($model, 'date_finish')->textInput([ 'maxlength' => true, 'type' => 'date']) ?>

    <?= $form->field($model, 'text')->textArea(['rows'=>'5','maxlength' => true]) */ ?>


    <?php  echo $form->field($model, 'status')  // статус заказа 1-отпрвлен
    ->dropDownList([
        '0' => 'Не активный',
        '1' => 'Активный',
    ], $param = ['options' =>[ $model->status => ['selected' => true]]] );
 // загрузка файлов
    echo  $form->field($model, 'files[]')->fileInput(['multiple' => true]) ;
    ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        
        <?
        if( is_array($model->fileList) && count( $model->fileList ) ) {
            $files = json_decode($model->files);
            echo '<table class="table"><tr><td><strong>Эскиз</strong></td><td><strong>Имя файла</strong></td><td><strong>Удалить</strong></td></tr></tr>';
            $path = Yii::getAlias("@backend/web/uploads/banners/" . $model->id);
            foreach ($model->fileList as $k => $f) {
                if (strpos($f, '.png') > 0 || strpos($f, '.jpg') > 0 || strpos($f, '.jpeg') > 0 || strpos($f, '.gif') > 0) {
                    $img = '<img src="/uploads/banners/' . $model->id . '/' . $f . '" width="48" />';
                } else {
                    $img = '<img src="/uploads/file.png" width="48" />';
                }
                echo '<tr><td>' . $img . '</td><td>' . $f . '</td><td><a href="' . Url::to(['/banners/delete-item', 'file' => $f, 'id' => $model->id]) . '"><span class="glyphicon glyphicon-trash" id="' . $model->id . '"></span></a></td></tr>';
            }
            echo '</table>';
        }
        ?>

        <?php ActiveForm::end(); ?>



</div>
