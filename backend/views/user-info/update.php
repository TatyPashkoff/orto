<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\UserInfo */

$this->title = 'Update User Info: ' . ' ' . $modelUserInfo->id;
$this->params['breadcrumbs'][] = ['label' => 'User Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $modelUserInfo->id, 'url' => ['view', 'id' => $modelUserInfo->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-info-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'modelUserInfo' => $modelUserInfo,
        'modelUser' => $modelUser,
    ]) ?>

</div>
