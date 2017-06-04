<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use backend\assets\ChatAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;

use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use backend\components\BannerWidget;
use backend\components\ChatWidget;

use backend\models\Assign;
use backend\models\Alerts;

$this->title = 'ORTHOLINER - невидимое исправление без брекетов';
AppAsset::register($this);
//ChatAsset::register($this);
//$role = Yii::$app->user->identitiy->role;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap" style="margin-top:130px;">
    <?php
    $user_id = -1;
    $role = -1;
    if( isset(Yii::$app->user) ){
        if( isset(Yii::$app->user->identity)  ) { // текущая роль пользователя
            $role = Yii::$app->user->identity->role;
        }
        $user_id = Yii::$app->user->id;
    }


    if( $role==0 ) {

       /*  // назначенные пациенты для данного техника в виде объекта assign для просмотра уровня
        $assign = Assign::getPacientsByDoctorId($user_id, true);


        echo '<div style="display:none;"><pre>';
        print_r($assign);
        echo '</pre></div>'; */

    }


    NavBar::begin([
//        'brandLabel' => '<img style="vertical-align: top;" src="/images/logos.png" width="100px" />',
//        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Главная', 'url' => ['/site/index']],
    ];


    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {



        if ($role == 4 || $role == 2 ) { // только админ
             $menuItems[] = ['label' => 'Клиники', 'url' => ['/clinics/index']];
             $menuItems[] = ['label' => 'Прайс', 'url' => ['/price/index']];
             //  $menuItems[] = ['label' => 'Доктора', 'url' => ['/doctors/index']];
             $menuItems[] = ['label' => 'Продукты', 'url' => ['/products/index']];

        }
        $menuItems[] = ['label' => 'Пациенты', 'url' => ['/pacients/index']];


        if($role != 3) {

            $menuItems[] = ['label' => 'Виртуальные планы', 'url' => ['/plans/index']];
        }
			
		if ($role == 4 || $role == 3  || $role == 2 || $role == 0) { // только админ и техник
			$menuItems[] = ['label' => 'Заказ на производство', 'url' => ['/orders/index']];

		   // $menuItems[] = ['label' => 'Изделия', 'url' => ['/objects/index']];
		}

        if($role == 4 || $role == 1  || $role == 3) {

            $menuItems[] = ['label' => 'Оплата', 'url' => ['/pacients/pay']];
        }

        $menuItems[] = ['label' => 'Доставка', 'url' => ['/delivery/index']];





        if ($role == 4 || $role ==2){

            $menuItems[] = ['label' => 'Справ.городов', 'url' => ['/spr-city/index']];

        }


        if ($role == 2|| $role == 4 ){
            $menuItems[] = ['label' => 'Баннеры', 'url' => ['/banners/index']];
            $menuItems[] = ['label' => 'Пользователи', 'url' => ['/user/index']];
        }

        // отчет техника о затратах производства
        if ( $role == 0 ||$role == 2 ||$role == 4  ) { // только техники, админ и мед.дир
            $menuItems[] = ['label' => 'Отчет затрат', 'url' => ['/reports/index','report-page'=>'1'] ];
            $menuItems[] = ['label' => 'Отчет остатков', 'url' => ['/reports/index','report-page'=>'2'] ];
        }

        if ($role != 4) {
          $menuItems[] = ['label' => 'Персональные данные', 'url' => ['/site/cabinet?id=' . Yii::$app->user->id]]; // админ пользователи
        }
        // кол-во непрочитанных сообщений для данного пользователя
		if($alerts = Alerts::find()
			->select(['COUNT(id) AS cnt'])
			->where(['doctor_id_to'=>Yii::$app->user->identity->id,'read_status'=>0])
            ->groupBy(['doctor_id_to'])
            ->one() ){		
			$alerts_count = $alerts->cnt;
		}else{
			$alerts_count = '';
		}
		
        $menuItems[] = ['label' => '<i class="glyphicon glyphicon-envelope"></i> <span class="label label-success" style="position:absolute;top:2px;right:-5px;">'.$alerts_count.'</span>',
			'url' => ['/alerts/index']
			//'options'=>['class'=>'dropdown'],
			//'template' => '<a href="{url}" class="url-class">{label}</a>',
			/*'items' => [
				['label' => 'Юридические услуги', 'url' => ['services/juridical-services']],
				['label' => 'Оценочные услуги', 'url' => ['services/valuation-services']],
			]*/	
			
		];
		

        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Выход (' . @Yii::$app->user->identity->fullname . /* ' ' . @Yii::$app->user->identity->role .*/ ')',
                ['class' => 'btn btn-link']
            )
            . Html::endForm()
            . '</li>';

    }
    if (!Yii::$app->user->isGuest) {
        $role = Yii::$app->user->identity->role;

        //print_r($_COOKIE);

        // если врач и клиника не выбрана
        if ( ! isset( $_COOKIE['id_clinic'] ) && $role==1 ) {
        //if ( ( is_null(Yii::$app->session['id_clinic'])) && $role==1 ) {
            if (!Yii::$app->user->isGuest || $role == 2)
                \Yii::$app->getSession()->setFlash('error', 'Выберите клинику');
            $menuItems1[] = '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Выход (' . Yii::$app->user->identity->fullname . ')',
                    ['class' => 'btn btn-link']
                )
                . Html::endForm()
                . '</li>';
            if (!Yii::$app->user->isGuest || $role <> 4)
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav navbar-right'],
                    'items' => $menuItems1,
                    'encodeLabels' =>'false',
                ]);

        }else{
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
				'encodeLabels' =>false,
            ]);
        }
    }


    NavBar::end();

    ?>

    <div class="container">
        <?//= BannerWidget::widget() ?>

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?//= Alert::widget() ?>
        <?php
            if(!Yii::$app->user->isGuest)
            if ($role == 4){
                //echo '<a href="/spr-city/index">Справочник городов</a>';
            }
            //            $menuItems[] = ['label' => 'Справ.городов', 'url' => ['/spr-city/index']];
         ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">


        <p class="pull-left">
            <img src="/images/Logo_Smartforce.png" alt="smartforce" width="315px" height="60px">
        </p>

        <p class="pull-right">
            Лаборатория «SmartForce», <br>
            010000, г. Астана, пр. Туран, 19/1, БЦ «ЭДЕМ», каб.505, <br>
            тел.: +7 (778) 900 90 54, info@smartforce.kz <br>
            </p>

        <?php //<p class="pull-right">  Yii::powered() </p>
            // <br><br> &copy; Ortholiner <?= date('Y')
        ?>
    </div>
</footer>

<?= ChatWidget::widget() ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

