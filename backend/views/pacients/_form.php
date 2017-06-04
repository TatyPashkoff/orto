<?php
//use yii;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Payments;
use yii\helpers\ArrayHelper;
use app\rbac\Pacient;
use kartik\date\DatePicker;
use kartik\file\FileInput;
use yii\helpers\Url;

$role = Yii::$app->user->identity->role;

// инициализация для предпросмотра
$model->initialPreviewImg1 = isset($model->img1) ? '<img src="' . $model->img1 . '" width="300px">' : '';
$model->initialPreviewImg2 = isset($model->img2) ? '<img src="' . $model->img2 . '" width="300px">'  : '';
$model->initialPreviewImg3 = isset($model->img3) ? '<img src="' . $model->img3 . '" width="300px">' : '';
$model->initialPreviewImg4 = isset($model->img4) ? '<img src="' . $model->img4 . '" width="300px">' : '';
$model->initialPreviewImg5 = isset($model->img5) ? '<img src="' . $model->img5 . '" width="300px">' : '';
$model->initialPreviewImg6 = isset($model->img6) ? '<img src="' . $model->img6 . '" width="300px">' : '';
$model->initialPreviewImg7 = isset($model->img7) ? '<img src="' . $model->img7 . '" width="300px">' : '';
$model->initialPreviewImg8 = isset($model->img8) ? '<img src="' . $model->img8 . '" width="300px">' : '';

/* @var $this yii\web\View */
/* @var $model backend\models\Pacients */
/* @var $form yii\widgets\ActiveForm */

$var_paid_vp = ['0'=>'Не задано','2'=> 'Бесплатно', '1' => 'Платно'];

?>

<style>
    #buttons_block{
        margin:5px;
    }
    #prev_tab{
        float:left;
        margin:0px 3px;
    }
    #next_tab{
        float:left;
        margin:0px 3px;
    }
    #save_reteiner{
        float:left;
        margin:0px 3px;
    }

    .btn.btn-zub{
        background:#eee;
    }
    .btn.btn-zub.active{
        border:3px solid #2077C1 !important;
       // background:#9acfea;
    }

    #save_btn{
        display:none; /* не показывать кнопку сохранить при загрузке */
        margin-left:200px;
    }
    
    .float-left{
        float:left;
        margin:0px 15px;
    }

</style>

<?php if (Yii::$app->session->getFlash('error')): ?>
    <div class="info">
        <?php echo Yii::$app->session->getFlash('error'); ?>
    </div>
<?php endif; ?>


<div class="pacients-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <ul id="tab_pages" class="nav nav-tabs">

        <?php if( $role!=3 ) { ?>
            <li class="active"><a id="tab_home" data-toggle="tab"  href="#home">Общие</a></li>
            <?php
            if (Pacient::canEdit($role) ) { ?>
                <li><a data-toggle="tab" id="tab_menu1" href="#menu1">Зубная карта</a></li>
                <li><a data-toggle="tab" id="tab_menu2" href="#menu2">Файлы</a></li>
            <? }
        } // скрыть от бух
        $class = ' in active';
        $class_buh = '';
        ?>
        <?php
         // убрана оплата за ВП , теперь все в план графике
       /* if ($role != 0 ) { // && $role != 1 ) { // только бухг., мед.дир и админ
            $class_buh = $role==3 ? ' in active' : '';
            $class = $role==1 || $role==2 || $role==4 ? ' in active':'';
            echo '<li' . $class . '><a data-toggle="tab" href="#menu3">Оплачено за виртуальный план</a></li>';
        }*/
        ?>
    </ul>
    <br>


    <div class="tab-content">
        <div id="home" class="tab-pane fade <?=$class?>">
            <?php
            if (Yii::$app->user->identity->role == 1) { ?>
                <input type="hidden" name="Pacients[doctor_id]" value="<?= Yii::$app->user->identity->id ?>"/>
            <? } elseif (
                 Yii::$app->user->identity->role == 3
                    || Yii::$app->user->identity->role == 0
            ) { ?>

            <? } else { ?>
            <?php
                echo $form->field($model, 'doctor_id')
                ->dropDownList(
                ArrayHelper::map(backend\models\User::find()->where(['status' => 1])
                ->andWhere(['in', 'role', ['1']])->all(), 'id', 'fullname')
                ); ?>
            <? } ?>


            <?php if (!$model->isNewRecord): ?>
                <?php if (Yii::$app->user->identity->role ==  1 ||    Yii::$app->user->identity->role == 4) {
                    echo $form->field($model, 'vp_id')
                        ->dropDownList(
                            ArrayHelper::map(backend\models\Plans::find()->select(['id', 'version'])->where(['pacient_id' => $model->id])->all(), 'id', 'version')
                        );
                }
                ?>
            <?php endif; ?>

            <? //$form->field($model, 'code')->textInput(['maxlength' => true]) ?>

            <?php if (Yii::$app->user->identity->role == 3 || Yii::$app->user->identity->role == 0): ?>
                <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'disabled' =>  !Pacient::canEdit($role)]) ?>
                  <?php if(Pacient::canEdit($role)) :?>
                <?= $form->field($model, 'gender')->radioList(['1' => 'Мужчина', '0' => 'Женщина']); ?>

                <?php // $form->field($model, 'status')->radioList(['1' => 'Активный', '0' => 'Отключен']); ?>
                    
            <?php endif; ?>
                <? //= $form->field($model, 'alert_date')->textInput(['maxlength' => true, 'type' => 'date']) ?>
                <? //= $form->field($model, 'alert_msg')->textInput(['maxlength' => true]) ?>
                <? //= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>
                <? //= $form->field($model, 'thirdname')->textInput(['maxlength' => true]) ?>
                <? //= $form->field($model, 'type_paid')->textInput() ?>
                <? // $form->field($model, 'var_paid')->textInput() ?>

                <?php
                // <label>Дата рождения</label>
                /*echo DatePicker::widget([
                    'name' => 'Pacients[age]',
                    'value' => $model->isNewRecord ? date('d-m-Y',time()) : date('d-M-Y',$model->age),
                    'options' => ['placeholder' => 'Дата рождения'],
                    'pluginOptions' => [
                        'language' => 'ru',
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true,
                        'autoclose'=> true,
                    ]
                ]);*/
                echo \yii\widgets\MaskedInput::widget([
                    'name' => 'Pacients[age]',
                    'mask' => '99-99-9999',
                    'value' => date('d-m-Y',$model->age),
                ]);
                // <input type="text" id="user-birth" name="Pacients[age]" value="<?=$model->age? >">
                ?>



                <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'disabled' =>   !Pacient::canEdit($role)]) ?>
                <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'disabled' =>   !Pacient::canEdit($role)]) ?>
                <?= $form->field($model, 'phone_doctor')->textInput(['maxlength' => true, 'disabled' =>   !Pacient::canEdit($role)]) ?>

                <?= $form->field($model, 'diagnosis')->textArea(['maxlength' => true,'rows'=>5, 'disabled' =>  !Pacient::canEdit($role)]) ?>
                <? //= $form->field($model, 'result')->textInput(['maxlength' => true]) ?>
                <? //= $form->field($model, 'files')->textInput(['maxlength' => true]) ?>
                <?php
                if($role!=3){ // от бухгалтера скрыть
                    echo $form->field($model, 'product_id')->dropDownList(
                        ArrayHelper::map(backend\models\Products::find()->select(['id', 'title'])->all(), 'id', 'title')
                    , $param = ['options' =>[ $model->product_id => ['Selected' => true]], 'disabled' =>  !Pacient::canEdit($role)]
                    );
                    echo $form->field($model, 'scull_top')->checkbox(['disabled' =>  !Pacient::canEdit($role)]) ;//['1' => '1', '0' => '0']);
                    echo $form->field($model, 'scull_bottom')->checkbox(['disabled' =>  !Pacient::canEdit($role)]) ;//['1' => '1', '0' => '0']);
                } ?>

            <?php else: ?>
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                <?php
                // <label>Дата рождения</label>
                /*echo DatePicker::widget([
                'name' => 'Pacients[age]',
                'value' => $model->isNewRecord ? date('d-m-Y',time()) : date('d-M-Y',$model->age),
                'options' => ['placeholder' => 'Дата рождения'],
                'pluginOptions' => [
                    'language' => 'ru',
                    'format' => 'dd-mm-yyyy',
                    'todayHighlight' => true,
                    'autoclose'=> true,
                ]
                ]);*/

                echo \yii\widgets\MaskedInput::widget([
                    'name' => 'Pacients[age]',
                    'mask' => '99-99-9999',
                    'value' => date('d-m-Y',$model->age),
                ]);
                /*echo $form->field($model, 'age')->widget(\yii\widgets\MaskedInput::className(), [
                    'mask' => '99/99/9999',
                    'value' => date('d/m/Y', $model->age),
                ]);-**/

                // <input type="text" id="user-birth" name="Pacients[age]" value="<?=$model->age? >">

                ?>

                <br>

                  <?= $form->field($model, 'gender')->radioList(['1' => 'Мужчина', '0' => 'Женщина']); ?>
                <?// $form->field($model, 'status')->radioList(['1' => 'Активный', '0' => 'Отключен']); ?>
                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'phone_doctor')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'diagnosis')->textArea(['maxlength' => true,'rows'=>5]) ?>
                <?php
                if( $role!=3) {
                    echo $form->field($model, 'product_id')->dropDownList(
                        ArrayHelper::map(backend\models\Products::find()->select(['id', 'title'])->all(), 'id', 'title')
                        , $param = ['options' => [$model->product_id => ['Selected' => true]]]
                    );

                    echo $form->field($model, 'scull_top')->checkbox();//['1' => '1', '0' => '0']);
                    echo $form->field($model, 'scull_bottom')->checkbox();//['1' => '1', '0' => '0']);
                }
            /* if($role==2 || $role==4) {
                 echo $form->field($model, 'vp_enable')
                     ->dropDownList([
                         '0' => 'Запрещено',
                         '1' => 'Разрешено',
                     ], $param = ['options' => [$model->vp_enable => ['selected' => true]], 'disabled' => !Pacient::canEdit($role)]);
            }*/

             endif; ?>
        </div>

        <div id="menu1" class="tab-pane fade text-center">
            <div>
                <h3>Сокращения исп. в таблице ниже:</h3>
                <div>
                    <p>С – кариес; Р – пульпит; Pt – периодонтит; F – резорцин-формалиновый зуб; П – пломба; A – пародонтит, пародонтоз,</p>
                    <p>в скобках (I-IV) - степень подвижности; К – коронка; И – искусственный зуб; О – отсутствующий зуб; R – корень; I –имплантат.</p>
                </div>
            </div>
            <hr/>
            <h4 class="">ЗУБНАЯ ФОРМУЛА</h4>
            <?php
            $formula = json_decode($model->formula, true);
            if (isset($formula['teeth1']))
                $teeth1 = $formula['teeth1'];
            $type = ['С' => 'кариес', 'Р' => 'пульпит', 'Pt' => 'периодонтит', 'F' => 'резорцин-формалиновый зуб', 'П' => 'пломба', 'A1' => 'пародонтит, пародонтоз (степень  подвижности - I)', 'A2' => 'пародонтит, пародонтоз (степень  подвижности - II)', 'A3' => 'пародонтит, пародонтоз (степень  подвижности - III)', 'A4' => 'пародонтит, пародонтоз (степень  подвижности - IV)', 'К' => 'коронка', 'И' => 'искусственный зуб', 'О' => 'отсутствующий зуб', 'R' => 'корень', 'I' => 'имплантат'];
            $tooths = [18 => '1.8', 17 => '1.7', 16 => '1.6', 15 => '1.5', 14 => '1.4', 13 => '1.3', 12 => '1.2', 11 => '1.1',
                21 => '2.1', 22 => '2.2', 23 => '2.3', 24 => '2.4', 25 => '2.5', 26 => '2.6', 27 => '2.7', 28 => '2.8',
                48 => '4.8', 47 => '4.7', 46 => '4.6', 45 => '4.5', 44 => '4.4', 43 => '4.3', 42 => '4.2', 41 => '4.1',
                31 => '3.1', 32 => '3.2', 33 => '3.3', 34 => '3.4', 35 => '3.5', 36 => '3.6', 37 => '3.7', 38 => '3.8'];
            ?>
            <?php foreach ($tooths as $k => $t) : ?>
                <?php if ($k == 48) {
                    echo '<br><br>';
                } ?>

                <div class="dropdown" style="display:inline">
                    <?
                    $class = '';
                    if (isset($teeth1) && is_array($teeth1) && in_array($k, array_keys($teeth1))) {
                        $class = 'btn-danger';
                    } ?>
                    <button class="btn dropdown-toggle <?= $class; ?>" type="button" data-toggle="dropdown"><?= $t ?>
                        <br/>
                        <?
                        $val = '';
                        if (isset($teeth1[$k]) && count($teeth1[$k]) < 4)
                            $val = join(',', array_keys($teeth1[$k]));
                        ?>
                        <input type="text" class="t_input form-control" value="<?= $val ?>"/>
                    </button>
                    <ul class="dropdown-menu">

                        <?php foreach ($type as $key => $value): ?>
                            <?
                            $checked = '';
                            if (isset($teeth1[$k][$key]) && $teeth1[$k][$key] == 'on') $checked = 'checked';
                            ?>
                            <div class="checkbox unchecked" style="margin-left:10px" id='bla'>
                                <label><input type="checkbox" name="teeth1[<?= $k ?>][<?= $key ?>]"
                                              data-key="<?= $key ?>" <?= $checked ?>/> <?= $key ?></label>
                            </div>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endforeach ?>


            <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////- -->
            <hr/>
            <h3>ПЛАН ЛЕЧЕНИЯ</h3><h5><b>ИСПРАВЛЕНИЕ ЗУБНОЙ ДУГИ</b></h5>
            <?
            $opt = $formula['formula'];
            $opt_data = [0 => 'Оставить как есть', 1 => 'Расширить', 2 => 'Сузить'];
            ?>
            <h5><b>Зубная дуга</b></h5>
            <div class="row">
                <div class="col-md-6">
                    <p><b>Верхняя челюсть</b></p>
                    <? foreach ($opt_data as $key => $data): ?>
                        <?
                        $selected = '';
                        if (isset($opt['opt_1']) && $opt['opt_1'] == $key) $selected = 'checked';
                        ?>
                        <label class="radio-inline"><input type="radio" name="Formula[opt_1]" <?= $selected; ?>
                                                           value="<?= $key; ?>"/><?= $data; ?></label>
                    <? endforeach; ?>
                </div>
                <div class="col-md-6">
                    <p><b>Нижняя челюсть</b></p>
                    <? foreach ($opt_data as $key => $data): ?>
                        <?
                        $selected = '';
                        if (isset($opt['opt_2']) && $opt['opt_2'] == $key) $selected = 'checked';
                        ?>
                        <label class="radio-inline"><input type="radio" name="Formula[opt_2]" <?= $selected; ?>
                                                           value="<?= $key; ?>"/><?= $data; ?></label>
                    <? endforeach; ?>
                </div>
            </div>
            <hr>

            <h5><b>Соотношение резцов</b></h5>
            <div class="row">
                <?
                $opt_data0 = [0 => 'Не менять', 1 => 'Улучшить'];
                $opt_data = [0 => 'Не менять', 1 => 'Устранить протрузию', 2 => 'Устранить ретрузию'];
                $opt_data2 = [0 => 'Не менять', 1 => 'Установить резцы в контакт', 2 => 'Сохранить, если необходимо для поддержания класса'];
                ?>
                <div class="col-md-12">
                    <p><b>По Сагиттали</b></p>
                    <div class="row" style="display:inline; text-align:left">
                        <div class="col-md-4">
                            <p><b>Верхние</b></p>
                            <? foreach ($opt_data as $key => $data): ?>
                                <?
                                $selected = '';
                                if (isset($opt['opt_4']) && $opt['opt_4'] == $key) $selected = 'checked';
                                ?>
                                <label class="radio-inline"><input type="radio" name="Formula[opt_4]" <?= $selected; ?>
                                                                   value="<?= $key; ?>"/><?= $data; ?></label><br>
                            <? endforeach; ?>
                        </div>
                        <div class="col-md-4">
                            <p><b>Нижние</b></p>
                            <? foreach ($opt_data as $key => $data): ?>
                                <?
                                $selected = '';
                                if (isset($opt['opt_5']) && $opt['opt_5'] == $key) $selected = 'checked';
                                ?>
                                <label class="radio-inline"><input type="radio" name="Formula[opt_5]" <?= $selected; ?>
                                                                   value="<?= $key; ?>"/><?= $data; ?></label><br>
                            <? endforeach; ?>
                        </div>
                        <div class="col-md-4">
                            <p><b>Сагиттальная щель</b></p>
                            <? foreach ($opt_data2 as $key => $data): ?>
                                <?
                                $selected = '';
                                if (isset($opt['opt_6']) && $opt['opt_6'] == $key) $selected = 'checked';
                                ?>
                                <label class="radio-inline"><input type="radio" name="Formula[opt_6]" <?= $selected; ?>
                                                                   value="<?= $key; ?>"/><?= $data; ?></label><br>
                            <? endforeach; ?>

                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <p><b>Соотношение резцов по трансверзали <br/>(средняя линия)</b></p>
                    <? foreach ($opt_data0 as $key => $data): ?>
                        <?
                        $selected = '';
                        if (isset($opt['opt_3']) && $opt['opt_3'] == $key) $selected = 'checked';
                        ?>
                        <label class="radio-inline"><input type="radio" name="Formula[opt_3]" <?= $selected; ?>
                                                           value="<?= $key; ?>"/><?= $data; ?></label><br>
                    <? endforeach; ?>
                </div>

            </div>

            <hr/>
            <h5><b>ВЕРТИКАЛЬНОЕ ПЕРЕКРЫТИЕ</b></h5>
            <div class="row">
                <? $opt_data = [0 => 'Не менять', 1 => 'Интрузия', 2 => 'Экструзия']; ?>
                <div class="col-md-6">
                    <p><b>Верхние</b></p>
                    <? foreach ($opt_data as $key => $data): ?>
                        <?
                        $selected = '';
                        if (isset($opt['opt_7']) && $opt['opt_7'] == $key) $selected = 'checked';
                        ?>
                        <label class="radio-inline"><input type="radio" name="Formula[opt_7]" <?= $selected; ?>
                                                           value="<?= $key; ?>"/><?= $data; ?></label>
                    <? endforeach; ?>
                </div>
                <div class="col-md-6">
                    <p><b>Нижние</b></p>
                    <? foreach ($opt_data as $key => $data): ?>
                        <?
                        $selected = '';
                        if (isset($opt['opt_8']) && $opt['opt_8'] == $key) $selected = 'checked';
                        ?>
                        <label class="radio-inline"><input type="radio" name="Formula[opt_8]" <?= $selected; ?>
                                                           value="<?= $key; ?>"/><?= $data; ?></label>
                    <? endforeach; ?>
                </div>
            </div>
            <hr>
            <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////- -->
            <h3>СООТНОШЕНИЕ ЗУБОВ ПОСЛЕ ЛЕЧЕНИЯ (отметить)</h3>

            <?
            $zub = $formula['formula']['zub'];
            $fang = isset($formula['formula']['fang']) ? $formula['formula']['fang']:[];
            $opt_data = ['' => '', 1 => '1', 2 => '2', 3 => 3];
            ?>
            <table class="table table-bordered text-center">
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
                    <input type="hidden" name="Formula[zub][pmr]" value="<?= isset($zub['pmr'])? $zub['pmr'] : null ?>" />
                    <input type="hidden" name="Formula[zub][pml]" value="<?= isset($zub['pmr'])? $zub['pml'] : null ?>" />
                </tr>
                <tr>
                    <td>ЗА СЧЕТ ЧЕГО? (способ)</td>
                    <td><input type="button" class="btn btn-zub zub_disr <?=isset($zub['dis_r'])&&$zub['dis_r']==1?'active':''?>" data-id="1" value="Дистализация" /></td>
                    <td><input type="button" class="btn btn-zub zub_disr <?=isset($zub['dis_r'])&&$zub['dis_r']==2?'active':''?>" data-id="2" value="Мезиализация" /></td>
                    <td><input type="button" class="btn btn-zub zub_disr <?=isset($zub['dis_r'])&&$zub['dis_r']==3?'active':''?>" data-id="3" value="Сепарация" /></td>
                    <td><input type="button" class="btn btn-zub zub_disl <?=isset($zub['dis_l'])&&$zub['dis_l']==1?'active':''?>" data-id="1" value="Дистализация" /></td>
                    <td><input type="button" class="btn btn-zub zub_disl <?=isset($zub['dis_l'])&&$zub['dis_l']==2?'active':''?>" data-id="2" value="Мезиализация" /></td>
                    <td><input type="button" class="btn btn-zub zub_disl <?=isset($zub['dis_l'])&&$zub['dis_l']==3?'active':''?>" data-id="3" value="Сепарация" /></td>
                    <input type="hidden" name="Formula[zub][dis_r]" value="<?= isset($zub['pmr'])? $zub['dis_r'] : null ?>" />
                    <input type="hidden" name="Formula[zub][dis_l]" value="<?= isset($zub['pmr'])? $zub['dis_l'] : null ?>" />
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
                    <td colspan="3"><input type="text" class="form-control" name="Formula[zub][com_r]"></td>
                    <td colspan="3"><input type="text" class="form-control" name="Formula[zub][com_l]"></td>
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
                    <input type="hidden" name="Formula[zub][c_r]" value="<?= isset($zub['pmr'])? $zub['c_r'] : null ?>" />
                    <input type="hidden" name="Formula[zub][c_l]" value="<?= isset($zub['pmr'])? $zub['c_l'] : null ?>" />
                </tr>
                <tr>
                    <td>ЗА СЧЕТ ЧЕГО? (способ)</td>
                    <td><input type="button" class="btn btn-zub zub_cdisr <?=isset($zub['cdis_r'])&&$zub['cdis_r']==1?'active':''?>" data-id="1" value="Дистализация" /></td>
                    <td><input type="button" class="btn btn-zub zub_cdisr <?=isset($zub['cdis_r'])&&$zub['cdis_r']==2?'active':''?>" data-id="2" value="Мезиализация" /></td>
                    <td><input type="button" class="btn btn-zub zub_cdisr <?=isset($zub['cdis_r'])&&$zub['cdis_r']==3?'active':''?>" data-id="3" value="Сепарация" /></td>
                    <td><input type="button" class="btn btn-zub zub_cdisl <?=isset($zub['cdis_l'])&&$zub['cdis_l']==1?'active':''?>" data-id="1" value="Дистализация" /></td>
                    <td><input type="button" class="btn btn-zub zub_cdisl <?=isset($zub['cdis_l'])&&$zub['cdis_l']==2?'active':''?>" data-id="2" value="Мезиализация" /></td>
                    <td><input type="button" class="btn btn-zub zub_cdisl <?=isset($zub['cdis_l'])&&$zub['cdis_l']==3?'active':''?>" data-id="3" value="Сепарация" /></td>
                    <input type="hidden" name="Formula[zub][cdis_r]" value="<?= isset($zub['pmr'])? $zub['cdis_r'] : null ?>" />
                    <input type="hidden" name="Formula[zub][cdis_l]" value="<?= isset($zub['pmr'])? $zub['cdis_l'] : null ?>" />
                </tr>
                <tr>
                    <td>Комментарий</td>
                    <td colspan="3"><input type="text" class="form-control" name="Formula[zub][com_cr]"></td>
                    <td colspan="3"><input type="text" class="form-control" name="Formula[zub][com_cl]"></td>
                </tr>
                    <?php /* <td colspan="2">
                        Дистализация:
                        <select name="Formula[fang][dis]" class="form-control small_select" id="">
                            <? foreach ($opt_data as $key => $data): ?>
                                <?
                                $selected = '';
                                if (isset($fang['dis']) && $fang['dis'] == $key) $selected = 'selected';
                                ?>
                                <option <?= $selected; ?> value="<?= $key; ?>"><?= $data; ?></option>
                            <? endforeach; ?>
                        </select>

                    </td>
                    <td colspan="2">
                        Мезиализация:
                        <select name="Formula[fang][mez]" class="form-control small_select" id="">
                            <? foreach ($opt_data as $key => $data): ?>
                                <?
                                $selected = '';
                                if (isset($fang['mez']) && $fang['mez'] == $key) $selected = 'selected';
                                ?>
                                <option <?= $selected; ?> value="<?= $key; ?>"><?= $data; ?></option>
                            <? endforeach; ?>
                        </select>
                    </td>
                    <td colspan="2">
                        Сепарация:
                        <select name="Formula[fang][sep]" class="form-control small_select" id="">
                            <? foreach ($opt_data as $key => $data): ?>
                                <?
                                $selected = '';
                                if (isset($fang['sep']) && $fang['sep'] == $key) $selected = 'selected';
                                ?>
                                <option <?= $selected; ?> value="<?= $key; ?>"><?= $data; ?></option>
                            <? endforeach; ?>
                        </select>
                    </td>
                </tr> */?>

                </tbody>
            </table>

            <h4>УДАЛЕНИЕ (отметить планируемые к удалению зубы)</h4>
            <?php
            //$tooths = [18, 17, 16, 15, 14, 13, 12, 11, 21, 22, 23, 24, 25, 26, 27, 28, 48, 47, 46, 45, 44, 43, 42, 41, 31, 33, 33, 34, 35, 36, 37, 38];
            $tooths = [18 => '1.8', 17 => '1.7', 16 => '1.6', 15 => '1.5', 14 => '1.4', 13 => '1.3', 12 => '1.2', 11 => '1.1',
                21 => '2.1', 22 => '2.2', 23 => '2.3', 24 => '2.4', 25 => '2.5', 26 => '2.6', 27 => '2.7', 28 => '2.8',
                48 => '4.8', 47 => '4.7', 46 => '4.6', 45 => '4.5', 44 => '4.4', 43 => '4.3', 42 => '4.2', 41 => '4.1',
                31 => '3.1', 32 => '3.2', 33 => '3.3', 34 => '3.4', 35 => '3.5', 36 => '3.6', 37 => '3.7', 38 => '3.8'];
            ?>
            <div class="checkbox">
                <?php foreach ($tooths as $k => $t) : ?>
                    <?php
                    if ($k == 48) {
                        echo '<br><br>';
                    }
                    $checked = '';
                    if (isset($formula['remove'])) {
                        $remove = $formula['remove'];
                        if (isset($remove[$k]) && $remove[$k] == 'on') $checked = 'checked';
                    }
                    ?>
                    <label><input type="checkbox" name="remove[<?= $k ?>]" <?= $checked ?>/><?= $t ?></label>
                <?php endforeach ?>
            </div>
            <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////- -->
            <hr>
            <h4>ЗУБЫ НЕТРЕБУЮЩИЕ ПЕРЕМЕЩЕНИЯ (отметить)</h4>
            <?php
            //$tooths = [18, 17, 16, 15, 14, 13, 12, 11, 21, 22, 23, 24, 25, 26, 27, 28, 48, 47, 46, 45, 44, 43, 42, 41, 31, 33, 33, 34, 35, 36, 37, 38];
            $tooths = [18 => '1.8', 17 => '1.7', 16 => '1.6', 15 => '1.5', 14 => '1.4', 13 => '1.3', 12 => '1.2', 11 => '1.1',
                21 => '2.1', 22 => '2.2', 23 => '2.3', 24 => '2.4', 25 => '2.5', 26 => '2.6', 27 => '2.7', 28 => '2.8',
                48 => '4.8', 47 => '4.7', 46 => '4.6', 45 => '4.5', 44 => '4.4', 43 => '4.3', 42 => '4.2', 41 => '4.1',
                31 => '3.1', 32 => '3.2', 33 => '3.3', 34 => '3.4', 35 => '3.5', 36 => '3.6', 37 => '3.7', 38 => '3.8'];
            ?>
            <div class="checkbox">
                <?php foreach ($tooths as $k => $t) : ?>
                    <?php if ($k == 48) {
                        echo '<br><br>';
                    }
                    $checked = '';
                    if (isset($formula['not_moving'])) {
                        $not_moving = $formula['not_moving'];
                        if (isset($not_moving[$k]) && $not_moving[$k] == 'on') $checked = 'checked';
                    }
                    ?>
                    <label><input type="checkbox" name="not_moving[<?= $k ?>]" <?= $checked ?>/><?= $t ?></label>
                <?php endforeach ?>
            </div>


            <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////- -->
            <hr>
            <h4>НЕВОЗМОЖНО УСТАНОВИТЬ АТТАЧМЕНТЫ (отметить)</h4>
            <?php
            //$tooths = [18, 17, 16, 15, 14, 13, 12, 11, 21, 22, 23, 24, 25, 26, 27, 28, 48, 47, 46, 45, 44, 43, 42, 41, 31, 33, 33, 34, 35, 36, 37, 38];
            $tooths = [18 => '1.8', 17 => '1.7', 16 => '1.6', 15 => '1.5', 14 => '1.4', 13 => '1.3', 12 => '1.2', 11 => '1.1',
                21 => '2.1', 22 => '2.2', 23 => '2.3', 24 => '2.4', 25 => '2.5', 26 => '2.6', 27 => '2.7', 28 => '2.8',
                48 => '4.8', 47 => '4.7', 46 => '4.6', 45 => '4.5', 44 => '4.4', 43 => '4.3', 42 => '4.2', 41 => '4.1',
                31 => '3.1', 32 => '3.2', 33 => '3.3', 34 => '3.4', 35 => '3.5', 36 => '3.6', 37 => '3.7', 38 => '3.8'];
            ?>
            <div class="checkbox">
                <?php foreach ($tooths as $k => $t) : ?>
                    <?php if ($k == 48) {
                        echo '<br><br>';
                    }
                    $checked = '';
                    if (isset($formula['cant_install'])) {
                        $cant_install = $formula['cant_install'];
                        if (isset($cant_install[$k]) && $cant_install[$k] == 'on') $checked = 'checked';
                    }
                    ?>
                    <label><input type="checkbox" name="cant_install[<?= $k ?>]" <?= $checked ?>/><?= $t ?></label>
                <?php endforeach ?>
            </div>
            <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////- -->
            <hr/>
            <div>
                <h4>Сокращения исп. в таблице ниже:</h4>
                <p>A - TIP ( ангуляция); ROTATION: R-вправо, R-влево; T - TORGUE; B - LINGUAL; L - LINGUAL; E - EXTRUSION; I - INTRUSION;
                    M - MESIAL; D - DISTAL</p>
            </div>
            <h4>ИЗМЕНИТЬ ПОЛОЖЕНИЕ ЗУБОВ</h4>
            <?php
            //$type = ['A' => 'TIP ( ангуляция)', 'R' => 'ROTATION', 'T' => 'TORGUE', 'B/L' => 'BUCCAL-LINGUAL', 'E' => 'EXTRUSION', 'I' => 'INTRUSION', 'M/D' => 'MESIAL-DISTAL'];
            $type = ['A' => 'TIP ( ангуляция)', 'R-вправо' => 'ROTATION-R','R-влево' => 'ROTATION-L', 'T' => 'TORGUE', 'B' => 'BUCCAL', 'L' => 'LINGUAL', 'E' => 'EXTRUSION', 'I' => 'INTRUSION', 'M' => 'MESIAL', 'D' => 'DISTAL'];
            //$tooths = [18, 17, 16, 15, 14, 13, 12, 11, 21, 22, 23, 24, 25, 26, 27, 28, 48, 47, 46, 45, 44, 43, 42, 41, 31, 33, 33, 34, 35, 36, 37, 38];
            $tooths = [18 => '1.8', 17 => '1.7', 16 => '1.6', 15 => '1.5', 14 => '1.4', 13 => '1.3', 12 => '1.2', 11 => '1.1',
                21 => '2.1', 22 => '2.2', 23 => '2.3', 24 => '2.4', 25 => '2.5', 26 => '2.6', 27 => '2.7', 28 => '2.8',
                48 => '4.8', 47 => '4.7', 46 => '4.6', 45 => '4.5', 44 => '4.4', 43 => '4.3', 42 => '4.2', 41 => '4.1',
                31 => '3.1', 32 => '3.2', 33 => '3.3', 34 => '3.4', 35 => '3.5', 36 => '3.6', 37 => '3.7', 38 => '3.8'];
            $change = [];
            if (isset($formula['change']))
                $change = $formula['change'];
            ?>
            <?php foreach ($tooths as $k => $t) : ?>
                <?php if ($k == 48) {
                    echo '<br><br>';
                } ?>

                <div class="dropdown" style="display:inline">
                    <?
                    $class = '';
                    if (is_array($change) && in_array($k, array_keys($change))) {
                        $class = 'btn-danger';
                    } ?>
                    <?
                    $val = '';
                    if (isset($change[$k]) && count($change[$k]) < 4)
                        $val = join(',', array_keys($change[$k]));
                    ?>
                    <button class="btn dropdown-toggle <?= $class; ?>" type="button" data-toggle="dropdown"><?= $t ?>
                        <br>
                        <input type="text" class="t_input form-control" value="<?= $val ?>"/>
                    </button>
                    <ul class="dropdown-menu">
                        <?php foreach ($type as $key => $value): ?>
                            <?
                            $checked = '';
                            if (isset($change[$k][$key]) && $change[$k][$key] == 'on') $checked = 'checked';
                            ?>
                            <div class="checkbox unchecked" style="margin-left:10px" id="bla">
                                <label><input type="checkbox" name="change[<?= $k ?>][<?= $key ?>]"
                                              data-key="<?= $key ?>" <?= $checked ?>/> <?= $key ?></label>
                            </div>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endforeach ?>


            <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////- -->
            <hr>
            <h4> ПЛАНИРУЕМЫЕ ПРОТЕЗЫ / ИМПЛАНТЫ (отметить место и размеры в мм.)</h4>
            <p style="text-align:center;">Сокращения исп. в таблице ниже: К-коронка, Т- имплант, П-Пломба, V- винир</p>

            <?php
            $tooths = [18, 17, 16, 15, 14, 13, 12, 11, 21, 22, 23, 24, 25, 26, 27, 28, 48, 47, 46, 45, 44, 43, 42, 41, 31, 33, 33, 34, 35, 36, 37, 38];
            if (isset($formula['implant'])) {
                $implant = $formula['implant'];
            }else{
                $implant = [];
            }

            //print_r($implant);
           // echo '<br><br>';

           // if (isset($formula['implant']))

            $type = ['K' => 'коронка', 'T' => 'имплант', 'П' => 'пломба', 'V'=>'винир','text'=>'мм'];
            /*$tooths = [18 => '1.8', 17 => '1.7', 16 => '1.6', 15 => '1.5', 14 => '1.4', 13 => '1.3', 12 => '1.2', 11 => '1.1',
                21 => '2.1', 22 => '2.2', 23 => '2.3', 24 => '2.4', 25 => '2.5', 26 => '2.6', 27 => '2.7', 28 => '2.8',
                48 => '4.8', 47 => '4.7', 46 => '4.6', 45 => '4.5', 44 => '4.4', 43 => '4.3', 42 => '4.2', 41 => '4.1',
                31 => '3.1', 32 => '3.2', 33 => '3.3', 34 => '3.4', 35 => '3.5', 36 => '3.6', 37 => '3.7', 38 => '3.8'] ; */
        /* ?>
            <?php foreach ($tooths as $t) : ?>
                <?php if ($t == 48) {
                    echo '<br><br>';
                }
                $class = 'btn-default';
                if ($implant[$t] != '') {
                    $class = 'btn-danger';
                } ?>
                <!--<button class="btn t_btn <?= $class; ?>" type="button"> -->
                <div class="btn <?=$class?>">
                    <label><?= $t ?><br/>
                        <input type="text" name="implant[<?= $t ?>]" value="<?= $implant[$t] ?>"
                               class="form-control t_input"/>
                    </label>
                </div>
                <!-- </button> -->
            <?php endforeach */?>
            <?php //<p style="font-size:9pt; text-align:left;">Примечание* Указываются необходимые размеры имплантов в миллиметрах. В случае если точные размеры импланта неизвестны и размер импланта будет подбираться после окончания ортодонтического лечения ставится +</p> ?>

            <?php

            $is_array_implant = is_array($implant);



            foreach ($tooths as $k => $t) : ?>
                <?php if ($k == 48) {
                    echo '<br><br>';
                } ?>

                <div class="dropdown" style="display:inline">
                    <?
                    $val = '';
                    $text_value ='';

                    // если текстовое поле НЕ пустое
                    if( isset($implant[$k]['text']) ) {
                        $text_value = $implant[$k]['text'];
                        unset($implant[$k]['text']); // удалить
                    }

                    $class = '';
                    if ( $is_array_implant && in_array($k, array_keys($implant)) && ( count($implant[$k])>0 ||  $text_value!='') ){
                        $class = 'btn-danger';
                    } ?>
                    <button class="btn dropdown-toggle <?= $class; ?>" type="button" data-toggle="dropdown"><?= $t ?>
                        <br/>
                        <?


                        if( isset($implant[$k]) && is_array($implant[$k]) ) {

                            if( $text_value != '' ) {
                                $val = join(',', array_keys($implant[$k]));
                                if($val!='') {
                                    $val .= ',' . $text_value;
                                }else{
                                    $val = $text_value;
                                }
                            }else{
                                $val = join(',', array_keys($implant[$k]));
                            }

                        }
                        ?>
                        
                        <input type="text" class="t_input form-control" value="<?= $val ?>"/>
                    </button>
                    <ul class="dropdown-menu">
                        <?php foreach ($type as $key => $value): ?>
                            <?
                        if($key!='text'){

                            $checked = '';
                            if (isset($implant[$k][$key]) && $implant[$k][$key] == 'on') $checked = 'checked';
                            ?>
                            <div class="checkbox unchecked" style="margin-left:10px" id='bla_implant'>
                                <label><input type="checkbox" name="implant[<?= $k ?>][<?= $key ?>]"
                                              data-key="<?= $key ?>" <?= $checked ?>/> <?= $key ?></label>
                            </div>
                            
                        <?php }else { ?>

                                <div class="" style="margin-left:10px" id='bla'>
                                    <label><input type="text" class="form-control t_input" style="float:left" name="implant[<?= $k ?>][<?=$key?>]"
                                                  data-key="<?= $key ?>" value="<?= $text_value !='' ? $text_value : '' ?>" /><div style="float:left; margin-left:5px;"><?=$text_value ?> мм</div></label>
                                </div>


                        <?php } ?>

                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endforeach;
           // print_r($implant);
            ?>




            <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////- -->
            <hr>
            <div class="form-group">
                <label for="comment">КОММЕНТАРИИ:</label>
                <textarea class="form-control" rows="5" id="comment" name="comment"><?=isset($formula['comment'])? $formula['comment'] :'' ?></textarea>
            </div>

            <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////- -->
        </div>
        <br>
        <div id="menu2" class="tab-pane fade text-center">

            <div class="row">

                <div class="col-md-4" style="min-height: 400px;">
                    <p>Фото в профиль <span style="color: red">*</span></p>
                    <?php /*if (isset($model->img1) && strlen($model->img1)) { ?>

                        <img src="<?= $model->img1 ?>" style="width: 360px;" alt="">

                    <? }*/ ?>
                    <?php
                    echo $form->field($model, 'img1')->widget(\kartik\file\FileInput::classname(),[
                        'options' => [
                            'accept' => ['application/zip','image/*'],
                        ],
                        'pluginOptions' => [
                            'uploadUrl' => \yii\helpers\Url::to(['file-upload']),
                            'uploadExtraData' => [
                                'id' => $model->id,
                            ],
                            'allowedFileExtensions' =>  ['zip','jpg','jpeg','png','gif'],
                            'initialPreview' => $model->initialPreviewImg1, // предпросмотр для загруженного баннера
                            'showUpload' => false,
                            'showRemove' => false,
                            'dropZoneEnabled' => false,
                            //'maxFileSize' => 5120, //1мб, // в КБ    15.07.2016   размер файла
                            'maxFileCount'=>1, // загружать только 1 файл
                            'language'=>'ru',
                        ]
                    ])->label(false); ?>
                    <?php // echo $form->field($model, 'img1')->hiddenInput(['value'=>$model->img1])->label(false); ?>

                    <?php // $form->field($model, 'img1')->fileInput() ?>
                </div>

                <div class="col-md-4" style="min-height: 400px;">
                    <p>Фото в анфас с улыбкой <span style="color: red">*</span></p>
                    <?php /* if (isset($model->img2) && strlen($model->img2)) { ?>

                        <img src="<?= $model->img2 ?>" style="width: 360px;" alt="">

                    <? } */?>

                    <?php  //echo $form->field($model, 'img2')->hiddenInput(['value'=>$model->img1])->label(false); ?>

                    <?php // $form->field($model, 'img2')->fileInput() ?>
                    <?php
                    echo $form->field($model, 'img2')->widget(\kartik\file\FileInput::classname(),[
                        'options' => [
                            'accept' => ['application/zip','image/*'],
                        ],
                        'pluginOptions' => [
                            'uploadUrl' => \yii\helpers\Url::to(['file-upload']),
                            'uploadExtraData' => [
                                'id' => $model->id,
                            ],
                            'allowedFileExtensions' =>  ['zip','jpg','jpeg','png','gif'],
                            'initialPreview' => $model->initialPreviewImg2, // предпросмотр для загруженного баннера
                            'showUpload' => false,
                            'showRemove' => false,
                            'dropZoneEnabled' => false,
                            //'maxFileSize' => 5120, //1мб, // в КБ    15.07.2016   размер файла
                            'maxFileCount'=>1, // загружать только 1 файл
                            'language'=>'ru',
                        ]
                    ])->label(false); ?>
                </div>

                <div class="col-md-4" style="min-height: 400px;">
                    <p>Фото в анфас без улыбки<span style="color: red">*</span></p>
                    <?php /* if (isset($model->img3) && strlen($model->img3)) { ?>

                        <img src="<?= $model->img3 ?>" style="width: 360px;" alt="">

                    <? } ?>

                    <?php  echo $form->field($model, 'img3')->hiddenInput(['value'=>$model->img1])->label(false); ?>
                    <?= $form->field($model, 'img3')->fileInput() */ ?>
                    <?php
                    echo $form->field($model, 'img3')->widget(\kartik\file\FileInput::classname(),[
                        'options' => [
                            'accept' => ['application/zip','image/*'],
                        ],
                        'pluginOptions' => [
                            'uploadUrl' => \yii\helpers\Url::to(['file-upload']),
                            'uploadExtraData' => [
                                'id' => $model->id,
                            ],
                            'allowedFileExtensions' =>  ['zip','jpg','jpeg','png','gif'],
                            'initialPreview' => $model->initialPreviewImg3, // предпросмотр для загруженного баннера
                            'showUpload' => false,
                            'showRemove' => false,
                            'dropZoneEnabled' => false,
                            //'maxFileSize' => 5120, //1мб, // в КБ    15.07.2016   размер файла
                            'maxFileCount'=>1, // загружать только 1 файл
                            'language'=>'ru',
                        ]
                    ])->label(false); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4" style="min-height: 400px;">
                    <p>Окклюзионный вид <br> верхнего зубного ряда<span style="color: red">*</span></p>
                    <?php /* if (isset($model->img4) && strlen($model->img4)) { ?>

                        <img src="<?= $model->img4 ?>" style="width: 360px;" alt="">

                    <? } ?>

                    <?php echo $form->field($model, 'img4')->hiddenInput(['value'=>$model->img1])->label(false); ?>

                    <?= $form->field($model, 'img4')->fileInput() */ ?>
                    <?php
                    echo $form->field($model, 'img4')->widget(\kartik\file\FileInput::classname(),[
                        'options' => [
                            'accept' => ['application/zip','image/*'],
                        ],
                        'pluginOptions' => [
                            'uploadUrl' => \yii\helpers\Url::to(['file-upload']),
                            'uploadExtraData' => [
                                'id' => $model->id,
                            ],
                            'allowedFileExtensions' =>  ['zip','jpg','jpeg','png','gif'],
                            'initialPreview' => $model->initialPreviewImg4, // предпросмотр для загруженного баннера
                            'showUpload' => false,
                            'showRemove' => false,
                            'dropZoneEnabled' => false,
                            //'maxFileSize' => 5120, //1мб, // в КБ    15.07.2016   размер файла
                            'maxFileCount'=>1, // загружать только 1 файл
                            'language'=>'ru',
                        ]
                    ])->label(false); ?>
                </div>
                <div class="col-md-4" style="min-height: 400px;">
                    <p>Окклюзионный вид <br> нижнего зубного ряда<span style="color: red">*</span></p>

                    <?php /*if (isset($model->img5) && strlen($model->img5)) { ?>

                        <img src="<?= $model->img5 ?>" style="width: 360px;" alt="">

                    <? } ?>

                    <?php echo $form->field($model, 'img5')->hiddenInput(['value'=>$model->img1])->label(false); ?>

                    <?= $form->field($model, 'img5')->fileInput() */ ?>
                    <?php
                    echo $form->field($model, 'img5')->widget(\kartik\file\FileInput::classname(),[
                        'options' => [
                            'accept' => ['application/zip','image/*'],
                        ],
                        'pluginOptions' => [
                            'uploadUrl' => \yii\helpers\Url::to(['file-upload']),
                            'uploadExtraData' => [
                                'id' => $model->id,
                            ],
                            'allowedFileExtensions' =>  ['zip','jpg','jpeg','png','gif'],
                            'initialPreview' => $model->initialPreviewImg5, // предпросмотр для загруженного баннера
                            'showUpload' => false,
                            'showRemove' => false,
                            'dropZoneEnabled' => false,
                            //'maxFileSize' => 5120, //1мб, // в КБ    15.07.2016   размер файла
                            'maxFileCount'=>1, // загружать только 1 файл
                            'language'=>'ru',
                        ]
                    ])->label(false); ?>
                </div>

                <div class="col-md-4" style="min-height: 400px;">
                    <p>Латеральный вид справа<span style="color: red">*</span></p>

                    <?php /* if (isset($model->img6) && strlen($model->img6)) { ?>

                        <img src="<?= $model->img6 ?>" style="width: 360px;" alt="">

                    <? } ?>

                    <?php echo $form->field($model, 'img6')->hiddenInput(['value'=>$model->img1])->label(false); ?>


                    <?= $form->field($model, 'img6')->fileInput() */ ?>

                    <?php
                    echo $form->field($model, 'img6')->widget(\kartik\file\FileInput::classname(),[
                        'options' => [
                            'accept' => ['application/zip','image/*'],
                        ],
                        'pluginOptions' => [
                            'uploadUrl' => \yii\helpers\Url::to(['file-upload']),
                            'uploadExtraData' => [
                                'id' => $model->id,
                            ],
                            'allowedFileExtensions' =>  ['zip','jpg','jpeg','png','gif'],
                            'initialPreview' => $model->initialPreviewImg6, // предпросмотр для загруженного баннера
                            'showUpload' => false,
                            'showRemove' => false,
                            'dropZoneEnabled' => false,
                            //'maxFileSize' => 5120, //1мб, // в КБ    15.07.2016   размер файла
                            'maxFileCount'=>1, // загружать только 1 файл
                            'language'=>'ru',
                        ]
                    ])->label(false); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4" style="min-height: 400px;">
                    <p>Фронтальный вид<span style="color: red">*</span></p>


                    <?php /* if (isset($model->img7) && strlen($model->img7)) { ?>

                        <img src="<?= $model->img7 ?>" style="width: 360px;" alt="">

                    <? } ?>

                    <?php echo $form->field($model, 'img7')->hiddenInput(['value'=>$model->img1])->label(false); ?>

                    <?= $form->field($model, 'img7')->fileInput() */ ?>
                    <?php
                    echo $form->field($model, 'img7')->widget(\kartik\file\FileInput::classname(),[
                        'options' => [
                            'accept' => ['application/zip','image/*'],
                        ],
                        'pluginOptions' => [
                            'uploadUrl' => \yii\helpers\Url::to(['file-upload']),
                            'uploadExtraData' => [
                                'id' => $model->id,
                            ],
                            'allowedFileExtensions' =>  ['zip','jpg','jpeg','png','gif'],
                            'initialPreview' => $model->initialPreviewImg7, // предпросмотр для загруженного баннера
                            'showUpload' => false,
                            'showRemove' => false,
                            'dropZoneEnabled' => false,
                            ///'maxFileSize' => 5120, //1мб, // в КБ    15.07.2016   размер файла
                            'maxFileCount'=>1, // загружать только 1 файл
                            'language'=>'ru',
                        ]
                    ])->label(false); ?>
                </div>
                <div class="col-md-4" style="min-height: 400px;">
                    <p>Латеральный вид слева<span style="color: red">*</span></p>

                    <?php /* if (isset($model->img8) && strlen($model->img8)) { ?>

                        <img src="<?= $model->img8 ?>" style="width: 360px;" alt="">

                    <? } ?>

                    <?php echo $form->field($model, 'img8')->hiddenInput(['value'=>$model->img1])->label(false); ?>

                    <?= $form->field($model, 'img8')->fileInput() */ ?>
                    <?php
                    echo $form->field($model, 'img8')->widget(\kartik\file\FileInput::classname(),[
                        'options' => [
                            'accept' => ['application/zip','image/*'],
                        ],
                        'pluginOptions' => [
                            'uploadUrl' => \yii\helpers\Url::to(['file-upload']),
                            'uploadExtraData' => [
                                'id' => $model->id,
                            ],
                            'allowedFileExtensions' =>  ['zip','jpg','jpeg','png','gif'],
                            'initialPreview' => $model->initialPreviewImg8, // предпросмотр для загруженного баннера
                            'showUpload' => false,
                            'showRemove' => false,
                            'dropZoneEnabled' => false,
                            //'maxFileSize' => 5120, //1мб, // в КБ    15.07.2016   размер файла
                            'maxFileCount'=>1, // загружать только 1 файл
                            'language'=>'ru',
                        ]
                    ])->label(false); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4" style="min-height: 100px;">
                    <p>Добавить ОПТГ и другие файлы<span style="color: red"></span></p>

                    <?php if (isset($model->img9) && strlen($model->img9)) { ?>

                        <img src="<?= $model->img9 ?>" style="width: 360px;" alt="">

                    <? } ?>

                    <?php //echo $form->field($model, 'img9')->hiddenInput()->label(false); ?>

                    <?= $form->field($model, 'img9[]')->fileInput(['multiple' => true]) ?>

                </div>
                <div class="col-md-4" style="min-height: 100px;">
                    <p>Добавить ОПТГ и другие файлы<span style="color: red"></span></p>

                    <?php if (isset($model->img10) && strlen($model->img10)) { ?>

                        <img src="<?= $model->img10 ?>" style="width: 360px;" alt="">

                    <? } ?>

                    <?php //echo $form->field($model, 'img10')->hiddenInput()->label(false); ?>


                    <?= $form->field($model, 'img10[]')->fileInput(['multiple' => true]) ?>


                </div>
                <div class="col-md-4" style="min-height: 100px;">
                    <p>Добавить ОПТГ и другие файлы<span style="color: red"></span></p>

                    <?php if (isset($model->img11) && strlen($model->img11)) { ?>

                        <img src="<?= $model->img11 ?>" style="width: 360px;" alt="">

                    <? } ?>

                    <?php //echo $form->field($model, 'img11')->hiddenInput()->label(false); ?>


                    <?= $form->field($model, 'img11[]')->fileInput(['multiple' => true]) ?>


                </div>
            </div>

                <?php // список файлов  img9

                if (isset($model->fileList) && is_array($model->fileList) && count($model->fileList)) {
                    //  $files = json_decode($model->files);
                ?>


                    <table class="table"><tr><td><strong>Эскиз</strong></td><td><strong>Имя файла</strong></td><td><strong>Размер</strong></td><td><strong>Скачать</strong></td><td><strong>Удалить</strong></td></tr></tr>
                    <?php
                    $path = Yii::getAlias("@backend/web/uploads/pacients/" . $model->id . '/img9');
                    foreach ($model->fileList as $k => $f) {
                        echo '<tr><td><img src="/uploads/pacients/' . $model->id . '/img9/' . $f . '" width="120px;"/></td><td>' . $f . '</td><td>' . filesize($path . '/' . $f) . '</td><td><a target="_blank" href="/pacients/download?file=' . $f . '&id=' . $model->id . '"><span class="glyphicon glyphicon-save" id="' . $model->id . '" ></span></a></td><td><a href="/pacients/delete-file?file=' . $f . '&id=' . $model->id . '"><span class="glyphicon glyphicon-trash" id="' . $model->id . '" ></span></a></td></tr>';
                    } ?>
                    </table>
                <?php
                }
                ?>
                <span class="clearfix"></span>
                <div>
                    <h3>Врач предоставил:</h3>

                    <ul class="list-unstyled">
                        <li class="text-left">
                            <?php echo $form->field($model, 'diagnostic_gips_modeli')
                                ->checkbox(['label' => 'Диагностические гипсовые модели']); ?>


                        <li class="text-left"> <?php echo $form->field($model, 'ottiski')
                                ->checkbox(['label' => 'Оттиски']); ?></label></li>
                        <li class="text-left"><?php echo $form->field($model, 'prikusnic_valik')
                                ->checkbox(['label' => 'Прикусной валик']); ?></li>
                        <li class="text-left">
                            <?php echo $form->field($model, 'orta_tele')
                                ->checkbox(['label' => 'Ортопантогмограмму/ Телерентгенограмма']); ?>
                        </li>
                        <li class="text-left"><?php echo $form->field($model, 'anfas_prof')
                                ->checkbox(['label' => 'Фотографии Пациента анфас и профиль, улыбки Пациента, внутриротовой снимок слева, справа, центр']); ?>
                        </li>

                    </ul>
                </div>

            <?php if(isset($model->img1) && $model->img1!=''){
                $save_image = '1';
            }else{
                $save_image = '0';
            }
            // скрытое поле для хранения изменений
            // <input type="hidden" name="save_images" value="<?=$save_image ? >" />
            ?>




        </div>
        <div id="menu3" class="tab-pane fade <?=$class_buh?>">

            <?php
                    // echo $form->field($model, 'date_paid')->textInput(['class' => 'datepicker', 'disabled' =>  !Pacient::canEditPay($role)])


            if( $payment = Payments::find()->where(['pacient_id'=>$model->id,'status'=>'0' ])->one() ) {
                ?>
                <label>Вариант оплаты ВП</label>
                <input type="text" value="<?= $var_paid_vp[$payment->var_paid_vp] ?>" class="form-control" disabled>
                <br>
                <label>Сумма оплаты ВП</label>
                <input type="text" value="<?= $payment->sum_paid_vp ?>" class="form-control" disabled>
                <br>
                <?php
            }


            /*echo '<label>Дата оплаты</label>';
            echo DatePicker::widget([
                'name' => 'Pacients[date_paid]',
                'value' => date('d-M-Y', $model->date_paid), // strtotime('+2 days')),
                'options' => ['placeholder' => 'Укажите дату оплаты...'],
                'pluginOptions' => [
                    'language' => 'ru',
                    'format' => 'dd-mm-yyyy',
                    'todayHighlight' => true
                ]
            ]);*/
            /*$form->field($model, 'product')
            ->dropDownList([
                'ретейнер' =>   'ретейнер',
                'элайнер' =>   'элайнер',
                'бесплатная коррекция' =>   'бесплатная коррекция',
                'платная коррекция' =>   'платная коррекция',
                'клиниченское испытание' =>   'клиниченское испытание',
                'капы от бруксизма' =>   'капы от бруксизма',
                'замена элайнера' =>   'замена элайнера',
            ], ['disabled' =>  !Pacient::canEditPay($role)]
            );*/

            echo $form->field($model, 'product')->dropDownList([
                   '0' => 'Элайнер',
                   '1' => 'Платная коррекция элайнера',
                   '2' => 'Бесплатная коррекция элайнера',
                   '3' => 'Ретейнер',
                   '4' => 'Бесплатный ретейнер',
                   '5' => 'Клинические исследования',
               ], $param = ['options' => [$model->product => ['selected' => true]],'disabled' => ($role==2 || $role==4) ? false: true ]);


            /*if( $model->sum_paid == 0 ) {
                echo $form->field($model, 'type_paid')->dropDownList([
                    '0' =>   'Бесплатно',
                    '1' =>   'Платно',
                ], $param = ['options' =>[ $model->var_paid => ['selected' => true]],'disabled' =>  !Pacient::canEditPay($role)] );
            }*/
           

            // список для бесплатно
            /*
            if(isset($model->dogovor)) {
                ?>
                    <a class="btn btn-primary"  download href="<?= $model->dogovor?>">договор</a>
                    <br>
                    <br>
                    <br>
                    <br>
            <?
                }
            ?>

             <?php if(Pacient::canEditPay($role)) : ?>

            <?= $form->field($model, 'dogovor')->fileInput(['value' => '']) ?>

            <?php endif; */
            ?>
            <br>
            <br>
            <?// Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>


        </div>

        <?php if( ($role==1 || $role==2 || $role==3 || $role==4) &&  ( (int)$model->dogovor != 0 ) ) { // есть права и договор существует ?>
            <a class="btn btn-primary"  download href="/pacients/download-dogovor?id=<?=$model->id?>">Скачать договор</a>
            <br>
        <?php } ?>
            <br>


        <div class="form-group">

            <?php if($role!=3){ /// скрыть от бухгалтера ?>
                <div id="buttons_block" class="row">
                    <div class="form-group">
                        <div id="prev_tab" class="btn btn-primary">Назад</div>
                        <?php //if($model->product_id==1 || $model->isNewRecord){ // если элайнер показывать ?><div id="next_tab" class="btn btn-primary">Далее</div>

                        <?php if( !$model->isNewRecord ){ ?>
                        <div class="float-left" id="end_cure">
                            <?php if( $model->status == 0 ) { ?>
                                <a href="<?=Url::to(['pacients/end?id='. $model->id]) ?>" class="btn btn-warning" >Завершить лечение</a>
                            <?php }else{ ?>
                                <a href="<?=Url::to(['pacients/end?id='. $model->id]) ?>" class="btn btn-success" >Продолжить лечение</a>
                            <?php } ?>
                        </div>
                        <?php } ?>
                        <?php // } ?>
                        <?php // if($model->product_id==2){ // если ретейнер показать ?><div class="float-left" id="save_reteiner"><?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['id'=>'save_reteiner_btn','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                        </div>
                        <?php /*<div class="float-left" id="end_cure_reteiner">
                            <?php if( $model->status == 0 ) { ?>
                                <a href="<?=Url::to(['pacients/end?id='. $model->id]) ?>" class="btn btn-warning" >Завершить лечение</a>
                            <?php }else{ ?>
                                <a href="<?=Url::to(['pacients/end?id='. $model->id]) ?>" class="btn btn-success" >Продолжить лечение</a>
                            <?php } ?>
                        </div>
                        <?php  } */ ?>
                        <div class="float-left">
                        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['id'=>'save_btn','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                        </div>

                   </div>
                </div>
            <?php }
            if ( !$model->isNewRecord && ( $role != 0 )) { ?>

               <?php if( $role!=0 && $role!=1 ) { ?>
                    <a href="<?=Url::to(['pacients/plan-graph?id='. $model->id]) ?>" class="btn btn-primary"><span class="glyphicon glyphicon-signal"></span> План график</a>
                    <?php if( $role!=3 ) { ?>
                       <a target="_blank" href="<?=Url::to(['pacients/print?id='. $model->id]) ?>" class="btn btn-primary">Печать</a>
                    <?php } ?>
               <?php } ?>

            <? } ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
<?php
$reteiner = $model->isNewRecord ? 1 : $model->product_id;
$show_save_reteiner =   $model->product_id == 1 || $model->isNewRecord ? "jQuery('#end_cure').css('display','block');jQuery('#save_reteiner').css('display','none');" : '';
$show_save_reteiner .= $model->product_id ==2 ? "jQuery('#next_tab').css('display','none');jQuery('#end_cure').css('display','block');" : "";

$script = "
   var cur_tab = 1; // общие
   var reteiner = {$reteiner}; 
   jQuery(document).ready(function(){
  
		$('#user-age').datepicker({dateFormat: 'dd.mm.yy'});
   
        jQuery('#prev_tab').css('display','none');
        {$show_save_reteiner}
        if(cur_tab!=3) jQuery('#save_btn').css('display','none');
        //if(cur_tab!=3) jQuery('#end_cure').css('display','none');
        
        if( reteiner == 2 ){
            jQuery('#tab_menu1').css('display','none');
            jQuery('#tab_menu2').css('display','none');
        }
        
        
        jQuery(document).on('change','#pacients-product_id',function(){
            if( jQuery(this).val()==1){
                jQuery('#tab_menu1').css('display','block');
                jQuery('#tab_menu2').css('display','block');
                jQuery('#save_reteiner').css('display','none');                
                jQuery('#next_tab').css('display','block');                        
                //alert('Элайнер');
                reteiner = 1;
            }else{
                //alert('Ретейнер');
                jQuery('#save_reteiner').css('display','block');                
                jQuery('#tab_menu1').css('display','none');
                jQuery('#tab_menu2').css('display','none');
                jQuery('#next_tab').css('display','none');
                reteiner = 2;
            }         
            jQuery('#end_cure').css('display','block');
            //alert(reteiner)   
               
        });
        
        jQuery('#next_tab').click(function(){
           
            cur_tab++;
            if(cur_tab>1) jQuery('#prev_tab').css('display','block');
            if(cur_tab!=3) jQuery('#save_btn').css('display','none');
            //if(cur_tab!=3) jQuery('#end_cure').css('display','none');

            jQuery('#tab_pages > li').removeClass('active');
            jQuery('.tab-content > div').removeClass('active in');
            jQuery('#tab_pages > li:nth-child('+ cur_tab +')').addClass('active');
            if(cur_tab==1){
                jQuery('.tab-content div#home').addClass('active in');
                //jQuery('#end_cure').css('display','none');
            }else if(cur_tab==2){
                jQuery('.tab-content div#menu1').addClass('active in');
               // jQuery('#end_cure').css('display','none');
            }else if(cur_tab==3){
                jQuery('.tab-content div#menu2').addClass('active in'); 
                jQuery(this).css('display','none');
                jQuery('#save_btn').css('display','block');            
                jQuery('#end_cure').css('display','block');            
                                
            }
        });
    
        jQuery('#prev_tab').click(function(){
           
            cur_tab--;
            if(cur_tab<3) jQuery('#next_tab').css('display','block');
            jQuery('#tab_pages > li').removeClass('active');
            jQuery('.tab-content > div').removeClass('active in');
            jQuery('#tab_pages > li:nth-child('+ cur_tab +')').addClass('active');
            
            if(cur_tab!=3) jQuery('#save_btn').css('display','none');

            if(cur_tab==1){
                jQuery('.tab-content div#home').addClass('active in');
                jQuery('#prev_tab').css('display','none');
                //jQuery('#end_cure').css('display','none');                                
            }else if(cur_tab==2){
                jQuery('.tab-content div#menu1').addClass('active in');
                //jQuery('#end_cure').css('display','none');                                 
            }else if(cur_tab==3){
                jQuery('#next_tab').css('display','block');
                jQuery('.tab-content div#menu2').addClass('active in');
                jQuery('#save_btn').css('display','block');                                
                jQuery('#end_cure').css('display','block');                                
            }
        });
        
        jQuery('#tab_pages #tab_home').click(function(){
            jQuery('#prev_tab').css('display','none');
            jQuery('#next_tab').css('display','block');
            jQuery('#save_btn').css('display','none');
            //jQuery('#end_cure').css('display','none');
            cur_tab = 1;
        })
        jQuery('#tab_pages #tab_menu1').click(function(){
            jQuery('#prev_tab').css('display','block');
            jQuery('#next_tab').css('display','block');
            jQuery('#save_btn').css('display','none');
            //jQuery('#end_cure').css('display','none');
            cur_tab = 2;
        })
        jQuery('#tab_pages #tab_menu2').click(function(){
            jQuery('#prev_tab').css('display','block');
            jQuery('#next_tab').css('display','none');
            jQuery('#save_btn').css('display','block');
            jQuery('#end_cure').css('display','block');
            cur_tab = 3;
        })
        
        
     $('.zub_pmr').click(function(){
        $('.zub_pmr').removeClass('active');
        $(this).addClass('active');
        $('input[name=\"Formula[zub][pmr]\"]').val($(this).data('id'));
        //alert( $('input[name=\"Formula[zub][pmr]\"]').val());
     });   
     $('.zub_pml').click(function(){
        $('.zub_pml').removeClass('active');
        $(this).addClass('active');
        $('input[name=\"Formula[zub][pml]\"]').val($(this).data('id'));
        //alert( $('input[name=\"Formula[zub][pml]\"]').val());
     });   
     $('.zub_disr').click(function(){
        $('.zub_disr').removeClass('active');
        $(this).addClass('active');
        $('input[name=\"Formula[zub][dis_r]\"]').val($(this).data('id'));
        //alert( $('input[name=\"Formula[zub][dis_r]\"]').val());
     });  
     $('.zub_disl').click(function(){
        $('.zub_disl').removeClass('active');
        $(this).addClass('active');
        $('input[name=\"Formula[zub][dis_l]\"]').val($(this).data('id'));
        //alert( $('input[name=\"Formula[zub][dis_l]\"]').val());
     });   
     
     
     $('.zub_cr').click(function(){
        $('.zub_cr').removeClass('active');
        $(this).addClass('active');
        $('input[name=\"Formula[zub][c_r]\"]').val($(this).data('id'));
        //alert( $('input[name=\"Formula[zub][c_r]\"]').val());
     });   
     $('.zub_cl').click(function(){
        $('.zub_cl').removeClass('active');
        $(this).addClass('active');
        $('input[name=\"Formula[zub][c_l]\"]').val($(this).data('id'));
        //alert( $('input[name=\"Formula[zub][c_l]\"]').val());
     });   
     $('.zub_cdisr').click(function(){
        $('.zub_cdisr').removeClass('active');
        $(this).addClass('active');
        $('input[name=\"Formula[zub][cdis_r]\"]').val($(this).data('id'));
        //alert( $('input[name=\"Formula[zub][cdis_r]\"]').val());
     });  
     $('.zub_cdisl').click(function(){
        $('.zub_cdisl').removeClass('active');
        $(this).addClass('active');
        $('input[name=\"Formula[zub][cdis_l]\"]').val($(this).data('id'));
        //alert( $('input[name=\"Formula[zub][cdis_l]\"]').val());
     });   
     
     /* $(document).on('change','#pacients-img1',function(){
        $('#pacients-img1').val('image load');
     });
     $(document).on('change','#pacients-img2',function(){
        $('#pacients-img2').val('image load');
     });
     $(document).on('change','#pacients-img3',function(){
        $('#pacients-img3').val('image load');
     });
     $(document).on('change','#pacients-img4',function(){
        $('#pacients-img4').val('image load');
     });
     $(document).on('change','#pacients-img5',function(){
        $('#pacients-img5').val('image load');
     });
     $(document).on('change','#pacients-img6',function(){
        $('#pacients-img6').val('image load');
     });
     $(document).on('change','#pacients-img7',function(){
        $('#pacients-img7').val('image load');
     });
     $(document).on('change','#pacients-img8',function(){
        $('#pacients-img8').val('image load');
     }); */

    //$('#save_btn').click(function(){
    $('form#w0').submit(function(){
        var img_count = 0; // проверка заголовка = название изображения
        $('.file-input .file-caption-name').each(function(){
            if($(this).attr('title') != undefined && $(this).attr('title') != '' ) {
               // alert( $(this).attr('title') );
                img_count++;             
            }
        }) 
//        if( img_count != 8 && reteiner == 1 ) {
//            alert('Необходимо указать все 8 изображений Вид. Выбрано '+ img_count);
//            return false; // всего 8 картинок
//        }
        //alert('ok ' + img_count);
        return true;
    })
    
    
    

});";


$this->registerJs($script, yii\web\View::POS_END);