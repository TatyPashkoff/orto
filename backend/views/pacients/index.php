<?php

use yii\helpers\Html;
use yii\grid\GridView;

use backend\models\Assign;
use backend\models\Doctors;
use backend\models\Plans;
use backend\models\User;
use backend\models\Orders;
use backend\models\Clinics;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PacientsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пациенты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pacients-index">

<!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    $role = Yii::$app->user->identity->role;

    require_once "__attributes.php";
    require_once "__buttons.php";

    $buttonList = [
        'update' => $buttons['update'],
        'print' => $buttons['print'],
        'delete' => $buttons['delete'],
    ];

    if ($role == 2 || $role == 4) {
        $buttonList['approve'] = $buttons['approve'];///Отправить на коррекцию»
    }

    if ($role == 4) {
        $buttonList['assign'] = $buttons['assign'];
    } elseif ($role == 1){
        $buttonList['plangraph'] = $buttons['plangraph'];
    }


    $button = [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{events} {update} {assign} {approve} {plangraph} {delete}  {print}',
        'buttons' => $buttonList
    ];

    $attributesList = [
        ['class' => 'yii\grid\SerialColumn'],
        $attributes['date'],
    ];

    if ($role == 0 || $role == 2 || $role == 3 || $role == 4) {
        $attributesList[] = $attributes['id'];
        $attributesList[] = $attributes['clinic_id'];
        $attributesList[] = $attributes['user'];
    }

    $attributesList[] = $attributes['name'];
    $attributesList[] = $attributes['product_id'];
    $attributesList[] = $attributes['level'];
    $attributesList[] = $button;

    if ($role == 4 || $role == 1 || $role == 2):?>
        <p>
            <?= Html::a('Добавить пациента', ['create'], ['class' => 'btn btn-success']); ?>
        </p>
    <? endif ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'pager' => [
            'firstPageLabel' => 'Первая',
            'lastPageLabel' => 'Последняя',
        ],
        'columns' => $attributesList
    ]); ?>
</div>
