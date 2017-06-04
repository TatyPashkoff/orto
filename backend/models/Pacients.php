<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use backend\models\Assign;
use backend\models\Plans;
use backend\models\Price;
use common\helpers\UserHelper;

/**
 * This is the model class for table "{{%pacients}}".
 *
 * @property string $id
 * @property string $doctor_id
 * @property string $order_id
 * @property string $vp_id
 * @property string $code
 * @property string $age
 * @property string $date
 * @property integer $gender
 * @property integer $status
 * @property string $alert_date
 * @property string $alert_msg
 * @property string $firstname
 * @property string $lastname
 * @property string $thirdname
 * @property integer $type_paid
 * @property integer $var_paid
 * @property string $phone
 * @property string $diagnosis
 * @property string $result
 * @property string $files
 * @property string $product_id
 */
class Pacients extends \yii\db\ActiveRecord
{
    public $fileList;

    public $fullName;

    public $user_data;

    public $initialPreviewImg1 = [];
    public $initialPreviewImg2 = [];
    public $initialPreviewImg3 = [];
    public $initialPreviewImg4 = [];
    public $initialPreviewImg5 = [];
    public $initialPreviewImg6 = [];
    public $initialPreviewImg7 = [];
    public $initialPreviewImg8 = [];


    //public $img1, $img2, $img3, $img4, $img5, $img6, $img7, $img8;
//    public $img1=[], $img2=[], $img3=[], $img4=[], $img5=[], $img6=[], $img7=[], $img8=[];

    // события пациента
    public static $EVENT_CREATE = 0; // создание пациента
    public static $EVENT_PLAN_CREATE = 1; // создание план-графика оплаты
    public static $EVENT_VP_CREATE = 2; // создание Вирт плана
    public static $EVENT_ORDER_CREATE = 3; // создание заказа
    public static $EVENT_PAYMENT = 4; // оплата заказа
    public static $EVENT_DELIVERY = 5; // доставка заказа
    public static $EVENT_END = 6; // завершено лечение пациента
    public static $EVENT_ASSIGN_TEHNIK = 7; // назначен техник
    public static $EVENT_TEHNIK_ERROR = 8; // состояние диагностического материала
    public static $EVENT_TEHNIK_SUCCESS = 9; // состояние диагностического материала
//    public static $EVENT_CHANGE_PAYMENT = 5; // смена оплаты ???

    const LABEL_PACIENT_CREATE = 0; // создан новый пациент
    const LABEL_TECHNIC_ACCEPTED = 1; // зуботехник подтвердил, что принял диагностический материал
    const LABEL_TECHNIC_NOT_ACCEPTED = 2; // зуботехник НЕ принял диагностический материал
    const LABEL_ADMIN_CHOSE_VP_PAID = 3; // Админ выбрал способ оплаты За ВП
    const LABEL_BUCH_ACCEPTED_VP_PAID = 4; // бухгалтер подтвердил оплату за ВП
    const LABEL_MED_DIRECTOR_ACCEPTED_VP = 5; // медицинский директор утвердил виртуальный план
    const LABEL_DOCTOR_ACCEPTED_VP = 6; // врач УТВЕРДИЛ  виртуальный план
    const LABEL_ADMIN_CREATE_PLAN_GRAF = 7;  // админ создал план график оплаты
    const LABEL_TECHNIC_CREATE_ORDER = 8; // зуботехник создал заказ на производство
    const LABEL_TREATMENT_FINISHED = 9; // врач нажал кнопку завершить лечение (нажать эту копку также имеет право админ)
    const LABEL_REFUSED_THREATMENT = 10; // Админ вручную поставил статус отказ от лечение




    public static function getStatusList(){
        return[
            LABEL_PACIENT_CREATE => 'НОВЫЙ ПАЦИЕНТ',
            LABEL_TECHNIC_ACCEPTED => 'СКАНИРОВАНИЕ',
            LABEL_REFUSED_THREATMENT => 'ВОЗВРАТ ДИАГНОСТИЧЕСКОГО МАТЕРИАЛА',
            LABEL_ADMIN_CHOSE_VP_PAID => 'ОЖИДАНИЕ ОПЛАТЫ ЗА ВП',
            LABEL_BUCH_ACCEPTED_VP_PAID => 'МОДЕЛИРОВАНИЕ ВИРТУАЛЬНОГО ПЛАНА',
            LABEL_MED_DIRECTOR_ACCEPTED_VP => 'УТВЕРЖДЕНИЕ ВИРТУАЛЬНОГО ПЛАНА',
            LABEL_DOCTOR_ACCEPTED_VP => 'ВП УТВЕРЖДЕН. ЖДЕМ ПОДТВЕРЖДАЮЩИЕ ДОКУМЕНТЫ',
            LABEL_ADMIN_CREATE_PLAN_GRAF => 'ОЖИДАНИЕ ОПЛАТЫ ЗА ЛЕЧЕНИЕ',
            LABEL_TECHNIC_CREATE_ORDER => 'ЛЕЧЕНИЕ: ПЕЧАТЬ КАПП',
            LABEL_TREATMENT_FINISHED => 'ЛЕЧЕНИЕ ЗАВЕРШЕНО',
            LABEL_REFUSED_THREATMENT => 'ОТКАЗ ОТ ЛЕЧЕНИЯ',

        ];
    }


    public static function getStatusLabels($label, $default = null)
    {
        $labels = static::getStatusList();
        return isset( $labels[$label] ) ? $labels[$label] : $default;
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pacients}}';
    }

    public static function getList($where = [])
    {
        $pacients = self::find()->where($where)->orderBy('date DESC')->all();
        return ArrayHelper::map($pacients, 'id', function ($model, $defaultValue) {
            return $model['name'];
        });
    }

    public function getLevel()
    {
        return $this->hasOne(Assign::className(), ['pacient_id' => 'id']);
    }

    public function getClinic_id()
    {
        return $this->hasOne(Clinics::className(), ['id' => 'clinic_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['id', 'doctor_id', 'order_id', 'vp_id', 'gender', 'status', 'product_id', 'scull_top', 'scull_bottom', 'vp_enable', 'date_paid', 'var_paid', 'code'], 'integer'],
            [['alert_msg'], 'string', 'max' => 256],
            [['name'], 'string', 'max' => 32],
            [['phone', 'email'], 'string', 'max' => 128],
            [['phone_doctor'], 'string', 'max' => 128],
            [['email'], 'email'],
            ['age', 'safe'],
            //[['img1'], 'string', 'max' => 255],
            //[['img2'], 'string', 'max' => 255],
            // [['img4'], 'string', 'max' => 255],
            // [['img5'], 'string', 'max' => 255],
            //[['img6'], 'string', 'max' => 255],
            // [['img7'], 'string', 'max' => 255],
            //[['img8'], 'string', 'max' => 255],
            // [['age'], 'string', 'max' => 255],
            //[['img3'], 'string', 'max' => 255],
            [['dogovor'], 'string', 'max' => 255],
            [['product'], 'string', 'max' => 255],
            //[['date_paid'], 'string', 'max' => 255],
            [['sum_paid'], 'trim'],
            [['diagnostic_gips_modeli'], 'string', 'max' => 255],
            [['ottiski'], 'string', 'max' => 255],
            [['prikusnic_valik'], 'string', 'max' => 255],
            [['orta_tele'], 'string', 'max' => 255],
            [['anfas_prof'], 'string', 'max' => 255],
            [['diagnosis', 'result'], 'string', 'max' => 1024],
            [['files'], 'string', 'max' => 16],
            [['fullName', 'age'], 'safe'],
            //[['img1'], 'file','skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg, gif'], // должны быть заданы все картинки перед сохранением
            [['img1', 'img2', 'img3', 'img4', 'img5', 'img6', 'img7', 'img8'], 'string', 'max' => 255,], // должны быть заданы все картинки перед сохранением
            //[['img1','img2','img3','img4','img5','img6','img7','img8'], 'required' ], // должны быть заданы все картинки перед сохранением
            //[['img1',], 'required' ], // должны быть заданы все картинки перед сохранением

        ];
    }


    /* public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            // $this->sendToAdmins();

            return true;
        }

        return false;
    }*/

    /*
     * отправляем админам
     * */
    public function sendToAdmins_()
    {
        //if(strpos($_SERVER['SERVER_NAME'],'.loc')>0) return; // выход если локальный сервер


        // test
        return true;

        if ($admins = User::getAdmins()) {

            //$userPassword = $this->user_data['password'];//UserHelper::createPassword(6); // !!! здесь должен быть сформированный пароль для зарегистрированного пользователя ? ? ?
            $userEmail = $this->email;// 'test@test.ru';  // !!! здесь должна быть почта зарегистрированного пользователя ? ? ?

            foreach ($admins as $admin) {

                $str = 'Здравствуйте,  Уважаемый(-ая) ' . $admin->fullname . ' <br><br>Вы успешно прошли регистрацию в системе <br><br>Доступ для начала работы в системе:<br> <br> Адрес  для входа: http://doctors.ortholiner.kz/ <br><br>Ваш логин: ' . $userEmail . '<br><br> Ваш пароль: ' . $userPassword . '<br><br> Изменить Ваши Персональные данные, Вы можете в личном кабинете. <br> Если у Вас возникнут вопросы, Вы можете обратиться к нам написав письмо на ortholiner@smartforce.kz <br><hr>С уважением, Зарина Кидиралиева <br>Руководитель web-проектов Лаборатории Smartfotce <br>+7 707 700 32 02 <br>+7 707 042 70 18 <br>Г. Астана, пр. Туран 19/1, каб 505';

                if (!Yii::$app->mailer->compose()
                    ->setFrom(Yii::$app->params['adminEmail'])
                    ->setTo($admin->email)
                    ->setSubject('Регистрация на Ortholiner')
                    ->setHtmlBody($str)
                    ->send()
                ) {
                    echo 'err send to ' . $admin->email;
                    exit;
                }

            }
        }

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'doctor_id' => 'Доктор',
            'order_id' => '№ заказа',
            'vp_id' => 'ВП',
            'clinic_id' => 'Клиника',
            'code' => 'Код пациента',
            'age' => 'Дата рождения',
            'date' => 'Дата добавления пациента',
            'gender' => 'Пол',
            'date_paid' => 'Дата следующей оплаты',
            'sum_paid' => 'Сумма планируемой оплаты',
            'status' => 'Статус от техника',
            'product' => 'Наименование Продукции',
            'dogovor' => 'Загрузить договор',
            'alert_date' => 'Дата сообщения',
            'alert_msg' => 'Текст сообщения',
            'name' => 'ФИО Пациента',
            'type_paid' => 'Тип оплаты',
            'var_paid' => 'Вариант оплаты',
            'email' => 'Email пациента',
            'phone' => 'Номер телефона пациента',
            'phone_doctor' => 'Номер телефона врача',
            'img4' => 'img4',
            'diagnosis' => 'Диагноз',
            'result' => 'Результат',
            'files' => 'Файлы',
            'product_id' => 'Наименование продукции',
            'scull_top' => 'Верхняя челюсть',
            'scull_bottom' => 'Нижняя челюсть',
            'vp_enable' => 'Статус создания Виртуально Плана',

        ];

    }

    /**
     * @inheritdoc
     * @return PacientsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PacientsQuery(get_called_class());
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'doctor_id']);
    }

    public function getDoctor()
    {
        return $this->hasOne(User::className(), ['id' => 'doctor_id']);
    }

    public function getTehnik()
    {
        // получение зуботехника
        if ($tehnik = Assign::find()->where(['pacient_id' => $this->id])->one()) {
            // поиск назначенного техника для пациента на данном уровне
            $tehnik_id = $tehnik->{'level_' . $tehnik->level . '_doctor_id'};
        }
        if (!$tehnik = User::findOne($tehnik_id)) $tehnik = new User();

        return $tehnik;
    }

    public function getDoctorFirstname()
    {
        return $this->doctor === null ? null : $this->doctor->fullname;
    }

    public function getDoctorEmail()
    {
        return $this->doctor === null ? null : $this->doctor->email;
        //return $this->doctor->email;

    }

    public function getDoctorPhone()
    {
        return $this->doctor === null ? null : $this->doctor->telefon;
        //   return @$this->doctor->telefon;
    }


    public function getPlan()
    {
        return $this->hasOne(Plans::className(), ['id' => 'vp_id']);
    }

    // тип оплаты или сумма
    public function getPaid()
    {
        if ($plan_graf = \backend\models\Payments::find()->where(['pacient_id' => $this->id, 'status' => '0'])->one()) {
            if ($plan_graf->var_paid_vp == 2) {
                return 'Бесплатно';
            } else if ($plan_graf->var_paid_vp == 0) {
                return 'Не задано';
            } else {
                return $plan_graf->sum_paid_vp;
            }
        }

        return 0;
    }

    // тип оплаты или сумма
    public function getVP()
    {
        $var_paid = ['Не задано', 'Платно', 'Бесплатно'];
        $res = ['var' => '', 'sum' => '', 'date' => ''];
        if ($plan_graf = \backend\models\Payments::find()->where(['pacient_id' => $this->id, 'status' => '0'])->one()) {
            $res = ['var' => $var_paid[$plan_graf->var_paid_vp],
                'sum' => $plan_graf->sum_paid_vp,
                'date' => $plan_graf->date_paid_vp
            ];
        }

        return $res;
    }

    // остаток - задолженность
    public function getDebt()
    {

        $sum = 0;
        $sum_discount = 0;
        if ($plan_graf = \backend\models\Payments::find()->where(['pacient_id' => $this->id])->one()) {
            $sum_discount = $plan_graf->sum_discount;

            $sum = $plan_graf->status_paid ? $plan_graf->downpay : 0; // + предоплата, если она подтверждена!!

            if ($plan_graf->var_paid == 3) {
                return 'Бесплатно';
            }

            if ($pay_items = \backend\models\PaymentsItems::find()->where(['payment_id' => $plan_graf->id])->all()) {
                foreach ($pay_items as $item) {
                    // + подтверждено бухгалтером
                    if ($item->status_paid == 1) {
                        $sum += $item->sum;
                    }
                }
            } else {
                // $sum = ->downpay ;  //предоплата
                if ($plan_graf->var_paid == 2 && $plan_graf->status_paid == 1) { // полная оплата
                    return 'Все оплачено';
                }
            }
            //$sum -= $plan_graf->sum_discount; // скидка
        }


        $paket_sum = $this->getPaketSum(); // нужно найти стоимость всего заказа ?? по ВП? по прайсу?
        $res = $paket_sum - $sum_discount - $sum; // - минус скидка
        $result = $paket_sum - $sum_discount;

        if ($res == 0 && $result > 0) return 'Все оплачено';
        // if( $result == '0'  ) return 'Не задано';

        return $res . ' из ' . $result;


    }

    public function getPlanVersion()
    {
        return $this->plan === null ? null : $this->plan->version;
    }


    public function getPlanComments()
    {
        return $this->plan === null ? null : $this->plan->comments;

    }

    public function getClinic()
    {
        return $this->hasOne(Clinics::className(), ['id' => 'clinic_id']);
    }

    public function getClinicTitle()
    {
        return $this->clinic === null ? null : $this->clinic->title;
    }

    public static function getPacientsByDoctorId($doctor_id)
    {
        $clinic_id = $_COOKIE['id_clinic'];
        $pacients = self::find()->where(['doctor_id' => $doctor_id, 'clinic_id' => $clinic_id])->all();
        return $pacients;
    }

    public function getCityByClinicId()
    {
        if ($this->clinic) {

            $city_id = $this->clinic->city_id;
            $city = SprCity::findOne($city_id);
            return $city->name;

        }

        //return $this->hasOne(Clinics::className(), ['id' => 'clinic_id']);
    }

    // название пакета X-XL...XXXL
    public function getPaket()
    {
        /*if( ! $plan = Orders::find()->where(['id'=>$this->order_id])->one() ) { // ВП кол-во планируемых моделей

            return -1;
        }*/
        /*if( ! $plan = Plans::find()->where(['pacient_id'=>$this->id])->one() ) { // ВП кол-во планируемых моделей
        }
        return -1;
        $price = Price::find()->All(); // все тарифные планы

        $models_count =  (int)$plan->count_elayners_vc + (int)$plan->count_attachment_vp + (int)$plan->count_checkpoint_vp + (int)$plan->count_reteiners_vp;

        if($models_count==0 ) return 0;

        $paket_name = 'XXXL'; // если условие не подошло, значит последний ТП
        foreach($price as $p){
            if( $p->models_min <= (int)$models_count && $p->models_max >= (int)$models_count ){
                return $p->paket_name;
            }
        }
        return $paket_name;*/

        if (!$plan = Plans::find()->where(['pacient_id' => $this->id, 'ready' => '1', 'approved' => '1'])->one()) { // ВП кол-во планируемых моделей

            return '';

        }

        //$models_count_vc = (int)$plan->count_elayners_vc + (int)$plan->count_attachment_vc + (int)$plan->count_checkpoint_vc + (int)$plan->count_reteiners_vc;
        //$models_count_nc = (int)$plan->count_elayners_nc + (int)$plan->count_attachment_nc + (int)$plan->count_checkpoint_nc + (int)$plan->count_reteiners_nc;
        //$models_count = $models_count_nc + $models_count_vc;
        $models_count = (int)$plan->count_elayners_vc + (int)$plan->count_elayners_nc;
        if ($models_count == 0) return 0;
        $price = Price::find()->All(); // все тарифные планы

        $paket_name = ''; // если условие не подошло, значит последний ТП
        foreach ($price as $p) {
            if ($p->models_min <= (int)$models_count && $p->models_max >= (int)$models_count) {
                return $p->paket_name;
            }
        }
        return $paket_name;

    }

    public function getPaketSum()
    {

        // сортировать по убыванию версии ВП
        if (!$plan = Plans::find()->where(['pacient_id' => $this->id, 'ready' => '1', 'approved' => '1'])->one()) { // ВП кол-во планируемых моделей

            //echo 'ddd';exit;
            return '';

        }

        // $models_count_vc = (int)$plan->count_elayners_vc + (int)$plan->count_attachment_vc + (int)$plan->count_checkpoint_vc + (int)$plan->count_reteiners_vc;
        //$models_count_nc = (int)$plan->count_elayners_nc + (int)$plan->count_attachment_nc + (int)$plan->count_checkpoint_nc + (int)$plan->count_reteiners_nc;
        //$models_count = $models_count_nc + $models_count_vc;
        $models_count = (int)$plan->count_elayners_vc + (int)$plan->count_elayners_nc;

        //echo 'count '.$models_count . ' ' . $this->id; exit;

        if ($models_count == 0) return 0;
        $price = Price::find()->All(); // все тарифные планы


        //$cnt = count($price); // кол-во тарифов
        $i = 0;
        foreach ($price as $p) {
            $i++;
            if ($p->models_min <= (int)$models_count && $p->models_max >= (int)$models_count) {
                /*if($i==$cnt){ // для последнего умножаем кол-во на сумму 1 шт.
                    return $p->models_min * $p->price;
                }else {
                    return $p->price;
                }*/
                return $p->price;
            }
        }


        return 0; // если последний
        //
        /*
    //return $this->order_id;

        $price = Price::find()->All(); // все тарифные планы
        $models_count = (int)$plan->count_models_vp + (int)$plan->count_elayners_vp + (int)$plan->count_attachment_vp + (int)$plan->count_checkpoint_vp + (int)$plan->count_reteiners_vp;

        if($models_count==0 ) return 0;

        $cnt = count($price); // кол-во тарифов
        $i=0;
        foreach($price as $p){
            $i++;
            if( $p->models_min <= (int)$models_count && $p->models_max >= (int)$models_count ){
                /if($i==$cnt){ // для последнего умножаем кол-во на сумму 1 шт.
                    return $p->models_min * $p->price;
                }else {
                    return $p->price;
                }
                return $p->price;
            }
        }


        return 0; // если последний
            */
    }

    // получение списка файлов в папке
    public function getFileList($id = false)
    {

        if (!$id) $id = $this->id;
        $path = Yii::getAlias("@backend/web/uploads/pacients/" . $id . '/img9');
        if (is_dir($path)) {
            $dh = opendir($path);
            $files = [];
            while (false !== ($filename = readdir($dh))) {
                if ($filename != '.' && $filename != '..') $files[] = $filename;
            }
            return $files;
        }
        return '';

    }

    // 
    /*public function getPaymentReadyItems_(){

        if( $plan_graph = Payments::find()->where(['pacient_id'=>$this->id, 'order_id'=>$this->order_id])->one() ) {
            // получить все элементы план графика
            if($plan_items = PaymentsItems::find()->where(['payment_id' => $plan_graph->id])->all()) {
                $cnt = 0;
                foreach ($plan_items as $item) {
                    $cnt += (int)$item->status;
                }
                return ['ready' => $cnt, 'all' => count($plan_items)];
            }
        }
        return ['ready' => 0, 'all' => 0];
            
    }*/

//    public function getFiles()
//    {
//        if (!empty($this->img1) && !empty($this->img2) && !empty($this->img3) && !empty($this->img4) && !empty($this->img5) && !empty($this->img6) && !empty($this->img7) && !empty($this->img8)) {
//           return
//        }
//    }


}
