<?php
use common\helpers\TextHelper;



$pacients = \backend\models\Pacients::findOne($model->pacient_id);?>
<style>
    @import '/backend/web/css/print.css';

    //th { background: #c9c9c9; }

<?php // download
if(isset($download_pdf) && $download_pdf){ ?>
    body {
        background-color: #fff;
        margin: 0px;
        padding: 40px;
        font-family: Arial;
        font-size: 16px;
        color: black;
    }
    table {
        width: 100%;
        border: 0;
        margin: 0px;
        padding: 0px;
        border-collapse: collapse;
    }
    table td {
        height: 24px;
        vertical-align: bottom;
    }
    .label {
        white-space: nowrap;
    }
    .big {
        font-size: 18px;
    }
    .bold {
        font-weight: bold;
    }
    .italic {
        font-style: italic;
    }
    .uc {
        text-transform: uppercase;
    }
    .center {
        text-align: center;
    }
    .right {
        text-align: right;
    }
    .middle {
        vertical-align: middle;
    }
    .border {
        border: 1px solid #000000;
    }
    .border-top {
        border-top: 1px solid #000000;
    }
    .border-bottom {
        border-bottom: 1px solid #000000;
    }
    .border-left {
        border-left: 1px solid #000000;
    }
    .border-right {
        border-right: 1px solid #000000;
    }
    .bold-border {
        border: 2px solid #000000;
    }
    .bold-border-top {
        border-top: 2px solid #000000;
    }
    .bold-border-bottom {
        border-bottom: 2px solid #000000;
    }
    .bold-border-left {
        border-left: 2px solid #000000;
    }
    .bold-border-right {
        border-right: 2px solid #000000;
    }
    .dashed {
        border-top: 1px dashed #000000;
    }
    .bold-dashed {
        border-top: 2px dashed #000000;
    }
    .container{
        page-break-after:auto;
        margin:0px 0px ;
        padding:35px;
    }
<?php }else{  // print ?>
    .container{
        page-break-after:auto;
    }
<?php }  ?>

    td p{
        height:12px;
        margin:0px;

    }
    #chat_trigger{
        display: none !important;
    }
    .footer{
        display: none !important;
    }

</style>

<div class="container">
    <img style="vertical-align: top;" src="/images/logo.png" width="100px">
    <p style="margin-top: 50px;"><strong>&nbsp;&nbsp;&nbsp; ВИРТУАЛЬНЫЙ ПЛАН ЛЕЧЕНИЯ №
            <span style="text-decoration: underline;"><?= $model->id; ?></span></strong>
        <span
            style="text-decoration: underline; float:right; margin-right: 30px;"><?= date('d-m-Y', $model->created_at); ?>
            г.</span></p>
    <div class="clearfix"></div>
    <table class="table table-bordered" style="background: #fff">
        <tbody>
        <!--        <tr style="background-color: gray; text-align: center; ">-->
        <tr>
            <th colspan="2" width="680" class="text-center">
                <p><strong>ПАСПОРТНАЯ ЧАСТЬ</strong></p>
            </th>
        </tr>
        <tr>
            <th colspan="2" width="680" class="text-center">
                <p><strong>ЗАКАЗЧИК</strong></p>
            </th>
        </tr>
        <tr>
            <td width="163">
                <p><strong>ФИО Врача</strong></p>
            </td>
            <td width="517">
                <p><strong><?= $pacients->getDoctorFirstname() ?></strong></p>
            </td>
        </tr>

        <tr>
            <td width="163">
                <p><strong>Название Клиники</strong></p>
            </td>
            <td width="517">
                <p><strong><?= $pacients->getClinicTitle() ?></strong></p>
            </td>
        </tr>
        <tr>
            <td width="163">
                <p><strong>Почтовый адрес</strong></p>
            </td>
            <td width="517">
                <p><strong><?= $pacients->getCityByClinicId() ?></strong></p>
            </td>
        </tr>
        <tr>
            <td width="163">
                <p><strong>Телефон</strong></p>
            </td>
            <td width="517">
                <p><strong><?= $pacients->getDoctorPhone(); ?></strong></p>
            </td>
        </tr>
        <tr>
            <td width="163">
                <p><strong>e</strong><strong>-</strong><strong>mail</strong></p>
            </td>
            <td width="517">
                <p><a href="<?= $pacients->getDoctorEmail(); ?>"> <?= $pacients->getDoctorEmail(); ?> </a></p>
            </td>
        </tr>
        <tr>
            <!--            <td colspan="2" width="680" style="background-color: gray; text-align: center; ">-->
            <td colspan="2" width="680" class="text-center">
                <p><strong>ПАЦИЕНТ</strong></p>
            </td>
        </tr>
        <tr>
            <td width="163">
                <p><strong>ФИО Пациента</strong></p>
            </td>
            <td width="517">
                <p><strong><?= $pacients->name; ?></strong></p>
            </td>
        </tr>
        <tr>
            <td width="163">
                <p><strong>Дата рождения</strong></p>
            </td>
            <td width="517">
                <p><strong><?= date('d-m-Y', $pacients->age) ?></strong></p>
            </td>
        </tr>
        <tr>
            <td width="163">
                <p><strong>Телефон</strong></p>
            </td>
            <td width="517">
                <p><strong><?= $pacients->phone; ?></strong></p>
            </td>
        </tr>
        <tr>
            <td width="163">
                <p><strong>Пол</strong></p>
            </td>
            <td width="517">
                <p><strong><?php
                        if ($pacients->gender == 1) {
                            echo 'мужчина';
                        } elseif ($pacients->gender == 0) {
                            echo 'женщина';
                        }
                        ?></strong></p>
            </td>
        </tr>
        <tr>
            <th colspan="2" width="680" class="text-center">
                <p><strong>Диагнозы</strong></p>
            </th>
        </tr>
        <tr>
            <td colspan="2"><?= $pacients->diagnosis; ?></td>
        </tr>
        <tr>
            <th colspan="2" width="680" class="text-center">
                <p><strong>Этапы лечения</strong></p>
            </th>
        </tr>
        <tr>
            <td colspan="2"><?= nl2br($model->comments); ?></td>
        </tr>
        <tr>
            <th colspan="2" width="680" class="text-center">
                <p><strong>Изображения</strong></p>
            </th>
        </tr>
        </tbody>
    </table>
</div>
<div class="container">
    <?php // список файлов
    //vd($model->file);
    if( is_array($model->fileList) && count( $model->fileList ) ){
        $files = json_decode($model->files);
        //echo '<table class="table"><tr><td><strong>Имя файла</strong></td><td><strong>Размер</strong></td><td><strong>Скачать</strong></td><td><!--<strong>Удалить</strong>--></td></tr></tr>';
        $path = Yii::getAlias("@backend/web/uploads/plans/" . $model->id);
        foreach ($model->fileList as $k=>$f) {
            // если картинка
            if (strpos($f, '.png') > 0 || strpos($f, '.jpg') > 0 || strpos($f, '.jpeg') > 0 || strpos($f, '.gif') > 0) {
                $f_name = preg_replace('/(.png|.jpg|.jpeg|.gif)/i','',$f);
                echo '<div style="text-align:center;">' . $f_name .'</div>';
                echo '<img src="/uploads/plans/'. $model->id.'/'.$f .'" width="100%" />';
            }
            //echo '<tr><td>' . $f . '</td><td>' . filesize( $path .'/' . $f ) . '</td><td><a target="_blank" href="/plans/download?file=' . $f . '&id=' . $model->id . '"><span class="glyphicon glyphicon-save" id="' . $model->id . '" ></span></a></td><td><!--<a target="_blank" href="/plans/delete?file=' . $f . '&id=' . $model->id . '"><span class="glyphicon glyphicon-trash" id="' . $model->id . '" ></span></a>--></td></tr>';
        }
        //echo '</table>';
    }
    ?>
    <?/*php echo !empty($pacients->img1) ? '<img width="300" height="300" src=' . $pacients->img1 . '>' : null; ?>
    <?php echo !empty($pacients->img2) ? '<img width="300" height="300" src=' . $pacients->img2 . '>' : null; ?>
    <?php echo !empty($pacients->img3) ? '<img width="300" height="300" src=' . $pacients->img3 . '>' : null; ?>
    <?php echo !empty($pacients->img4) ? '<img width="300" height="300" src=' . $pacients->img4 . '>' : null; ?>
    <?php echo !empty($pacients->img5) ? '<img width="300" height="300" src=' . $pacients->img5 . '>' : null; ?>
    <?php echo !empty($pacients->img6) ? '<img width="300" height="300" src=' . $pacients->img6 . '>' : null; ?>
    <?php echo !empty($pacients->img7) ? '<img width="300" height="300" src=' . $pacients->img7 . '>' : null; ?>
    <?php echo !empty($pacients->img8) ? '<img width="300" height="300" src=' . $pacients->img8 . '>' : null; ?>
    <?php echo !empty($pacients->img9) ? '<img width="300" height="300" src=' . $pacients->img9 . '>' : null; */?>
    <!--    <p><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;</strong></p>-->
    <!--    <p><strong>&nbsp;</strong></p>-->
    <!--    <p><strong>&nbsp;&nbsp; Ниж</strong><strong>няя челюсть</strong><strong>: 1) Необходимо установить аттачменты на-->
    <!--            1.6</strong><strong>;1</strong><strong>.</strong><strong>5</strong><strong>;1.4;1.3;1.2;1.1;2.1;2.2;2.3;2.6</strong>-->
    <!--        <strong>&nbsp;зубы.</strong></p>-->
    <!--    <p><strong>2</strong><strong>) </strong><strong>&nbsp;</strong><strong>Необходимо</strong><strong>-->
    <!--            после </strong><strong>2</strong><strong>4</strong><strong> каппы </strong><strong>пере</strong><strong>клеить-->
    <!--            аттачмент на 1.3 зубы.</strong></p>-->
    <!--    <p><strong>&nbsp;</strong><strong>3</strong><strong>) </strong><strong>Необходимо-->
    <!--            выполнить </strong><strong>IPR</strong><strong>(сепарацию) между зубами</strong><strong> 1.6-1.5;-->
    <!--            1.5-1.4;1.1-1.2;1.2-1.3;1.3-1.4; 1.1-2.1; 2.1-2.2; 2.2-2.3 (0.3мм</strong><strong>)</strong></p>-->
    <br><br>
    <h4 class="text-center" style="margin-top:30px !important;"><b>Виртуальный план лечения подготовил:</b></h4>
    <div class="row">
        <div class="col-md-6">
            ФИО представителя Исполнителя <span>______________</span>
        </div>
        <div class="col-md-6">
            Подпись__________________
        </div>
    </div>
    <br>
    <h4 class="text-center"><b>С виртуальным планом лечения ознакомлены. С результатами согласны.</b></h4>
    <table class="table" border="0">
        <tr>
            <td>
                ФИО представителя Заказчика_________________
            </td>
            <td>
                ФИО пациента &nbsp;&nbsp;&nbsp;&nbsp;<span style="text-decoration: underline"><?= $pacients->name; ?></span>
            </td>
        </tr>
        <tr>
            <td>
                Подпись__________________
            </td>
            <td>
                Подпись__________________
            </td>
         </tr>
    </table>
    <br>
    <div class="row">
        <div class="col-md-6">
            <p class="text-center"><b>Исполнитель</b></p>
            <b>Юридический адрес:</b> Республика Казахстан, г.Астана, 010000, ул.Сыганак 10, к.481 <br>
            <b>Фактический адрес:</b> Республика Казахстан, г. Астана, 010000, район «Есиль», ул. Туран, д 19/1., оф.
            505. <br><br>

            <b>БИН:</b> 150 540 012 596 <br>
            <b>Филиал АО «Банк RBK»</b> <br>
            <b>ИИК:</b> KZ4 182 104 398 121 591 61 <br>
            <b>БИК:</b> KINCKZKA <br><br>

            <b>___________________ Сарбупин Н.С. <br>
                м.п.</b>

        </div>
        <div class="col-md-6">
            <p class="text-center"><b>Заказчик:</b></p>
            <b>Юридический адрес:</b> _____________ <br>
            <b>Фактический адрес:</b> Республика Казахстан <br>
            010000, г.Астана, район «_______________», <br>
            ___________________ <br><br>

            <b>ИИН:</b> <br>
            <b>АО « _________БАНК» </b><br>
            <b>ИИК:</b> ________________________ <br>
            <b>БИК:</b> ________________________ <br><br>

            <b>__________________ _____________ <br>
                м.п.</b>

        </div>
    </div>
    <br><br>
    <p>Лаборатория <strong>&laquo;SmartForce&raquo;,</strong>
        010000, г. Астана,&nbsp; пр.Туран, 19/1, БЦ "ЭДЕМ", каб.505<br>Тел.: +7 (717) 246 -96-92, <a
            href="mailto:info@ortholiner.kz">info@ortholiner.kz</a>.</p>
    <br>
</div><?php // container - page-break - разрыв страницы при печати ?>
