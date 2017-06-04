<?php

/* @var $this yii\web\View */

$this->title = 'ORTHOLINER - невидимое исправление без брекетов';
$role = Yii::$app->user->identity->role;
?>
<style>
    .info{
        margin:10px;
        padding: 10px;
        background:#ce8483;
    }
</style>
<div class="site-index">

    

    <?php if( $role == 4 ){ ?>

    <a href="/site/loadorder" class="btn btn-success">Сформировать и скачать отчет</a>

    <?php if (Yii::$app->session->getFlash('error')): ?>
            <div class="info">
                <?php echo Yii::$app->session->getFlash('error'); ?>
            </div>
    <?php endif; ?>

    <?php } ?>

    <?php if($role == 1):?>
    <div class="body-content">
    <div class="page-header">
        <h3 align="center">Добро пожаловать в систему</h3>
    </div>


    <?php

    // if( ! isset( Yii::$app->session['id_clinic']) && (int)Yii::$app->session['id_clinic'] == 0 ) { ?>
    <?php if( ! isset( $_COOKIE['id_clinic'] ) || (int)$_COOKIE['id_clinic'] == 0 ) { ?>
        <h3 align="center">Если вы работаете в нескольких клиниках, выберите клинику</h3>

    
        <?php 
if( isset( $clinics )) {
            foreach($clinics as $clinic):?>
                <div class="row">
                    <div class="col-lg-6">
                        <h4><?=$clinic['title'];?></h4>
                    </div>
                    <div class="col-lg-6">
                        <p><a class="btn btn-default" href="?id_clinic=<?=$clinic['id'];?>">Выбрать</a></p>
                    </div>
                </div>
            <?endforeach;
        }?>
    <?php } // не отображать если клиника уже выбрана


/*if(!is_null($banners)){
    foreach($banners as $banner){
        $path = Yii::getAlias("@backend/web/uploads/banners/" . $banner->id);
        if(is_dir($path)) {
            $dh = opendir($path);
            while (false !== ($filename = readdir($dh))) {
                if ($filename != '.' && $filename != '..') {?>                    
                    <div class="row">
                        <img src="/uploads/banners/<?=$banner->id;?>/<?=$filename;?>" width="100%"/>
                    </div>
                <?}
            }
            
        }
    }
}*/

?>

    </div>
<?endif;?>
</div>
