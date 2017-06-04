<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UserInfoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-info-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_type') ?>

    <?= $form->field($model, 'fullname') ?>

    <?= $form->field($model, 'second_name') ?>

    <?= $form->field($model, 'avatar') ?>

    <?php // echo $form->field($model, 'website') ?>

    <?php // echo $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'category1') ?>

    <?php // echo $form->field($model, 'category2') ?>

    <?php // echo $form->field($model, 'category3') ?>

    <?php // echo $form->field($model, 'category4') ?>

    <?php // echo $form->field($model, 'category5') ?>

    <?php // echo $form->field($model, 'category6') ?>

    <?php // echo $form->field($model, 'category7') ?>

    <?php // echo $form->field($model, 'category8') ?>

    <?php // echo $form->field($model, 'category9') ?>

    <?php // echo $form->field($model, 'category10') ?>

    <?php // echo $form->field($model, 'category11') ?>

    <?php // echo $form->field($model, 'category12') ?>

    <?php // echo $form->field($model, 'category13') ?>

    <?php // echo $form->field($model, 'category14') ?>

    <?php // echo $form->field($model, 'category15') ?>

    <?php // echo $form->field($model, 'category16') ?>

    <?php // echo $form->field($model, 'category17') ?>

    <?php // echo $form->field($model, 'category18') ?>

    <?php // echo $form->field($model, 'category19') ?>

    <?php // echo $form->field($model, 'category20') ?>

    <?php // echo $form->field($model, 'category21') ?>

    <?php // echo $form->field($model, 'category22') ?>

    <?php // echo $form->field($model, 'category23') ?>

    <?php // echo $form->field($model, 'category24') ?>

    <?php // echo $form->field($model, 'pr') ?>

    <?php // echo $form->field($model, 'pr1') ?>

    <?php // echo $form->field($model, 'pr2') ?>

    <?php // echo $form->field($model, 'pr3') ?>

    <?php // echo $form->field($model, 'pr4') ?>

    <?php // echo $form->field($model, 'social1') ?>

    <?php // echo $form->field($model, 'social2') ?>

    <?php // echo $form->field($model, 'social3') ?>

    <?php // echo $form->field($model, 'social4') ?>

    <?php // echo $form->field($model, 'social5') ?>

    <?php // echo $form->field($model, 'social6') ?>

    <?php // echo $form->field($model, 'socialUrl1') ?>

    <?php // echo $form->field($model, 'socialUrl2') ?>

    <?php // echo $form->field($model, 'socialUrl3') ?>

    <?php // echo $form->field($model, 'socialUrl4') ?>

    <?php // echo $form->field($model, 'socialUrl5') ?>

    <?php // echo $form->field($model, 'socialUrl6') ?>

    <?php // echo $form->field($model, 'type1') ?>

    <?php // echo $form->field($model, 'type2') ?>

    <?php // echo $form->field($model, 'type3') ?>

    <?php // echo $form->field($model, 'type4') ?>

    <?php // echo $form->field($model, 'type5') ?>

    <?php // echo $form->field($model, 'type6') ?>

    <?php // echo $form->field($model, 'type7') ?>

    <?php // echo $form->field($model, 'partner') ?>

    <?php // echo $form->field($model, 'prDescr') ?>

    <?php // echo $form->field($model, 'contactPerson') ?>

    <?php // echo $form->field($model, 'contactEmail') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
