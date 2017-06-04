<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\UserType;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model backend\models\UserInfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-info-form">

                      <?php

                      $form = ActiveForm::begin(
                                    [
                                        'id' => 'form-signup-user',
                                        'enableClientValidation' => false,
                                        'enableAjaxValidation' => true,
                                        'options' => [
                                            'class' => 'regist-form',
                                            'enctype' => 'multipart/form-data',
                                            'data-pjax' => '',
                                        ]
                                    ]
                                );
                            ?>
                        <input type="hidden" name="deleteImg" class="deleteImg" value="0">
                        <?php

                        print_r($modelUserInfo);

                        echo $form->field($modelUserInfo, 'user_type')
                            ->dropDownList([
                                '0' => 'Зубной техник',
                                '1' => 'Врач',
                                '2' => 'Мед. директор',
                                '3' => 'Бухгалтер',
                                '4' => 'Админ'
                            ], $param = ['options' =>[ $model->user_type => ['Selected' => true]]]
                            );
                        ?>
                        <?php echo $form->field($modelUserInfo, 'active')->radioList(['1' => 'Да', '0' => 'Нет']); ?>
                        <?php echo $form->field($modelUserInfo, 'fullname')->textInput(['placeholder' => 'Укажите имя']); ?>
                        <?php echo $form->field($modelUserInfo, 'second_name')->textInput(['placeholder' => 'Укажите фамилию']); ?>
                        <?php echo $form->field($modelUser, 'username')->textInput(['placeholder' => 'Укажите ник на сайте']); ?>
                        <?php echo $form->field($modelUser, 'email')->input('email', ['placeholder' => 'Укажите Е-mail']); ?>
                        <?php echo $form->field($modelUserInfo, 'description')->textarea(['placeholder' => $modelUserInfo->getAttributeLabel('description')]); ?>


                           <input type="submit" value="Применить" class="btn btn-success" name="update">
                            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                            <?php ActiveForm::end(); ?>
</div>