<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Pacients */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Пациенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pacients-create">

    <?php

        if(count($model->getErrors())) {

            print_r($model->getErrors()); die;
    }

    ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
