<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use backend\models\Clinics;
use backend\models\User;
use backend\models\Price;
/**
 * This is the model class for table "{{%orders}}".
 *
 * @property string $id
 * @property string $date
 * @property string $num
 * @property string $group_id
 * @property string $doctor_id
 * @property string $clinics_id
 * @property string $pacient_code
 * @property string $date_paid
 * @property integer $type_paid
 * @property integer $status_paid
 * @property integer $status_object
 * @property integer $order_status
 * @property integer $admin_check
 * @property integer $status_agreement
 * @property integer $status
 * @property integer $scull_type
 * @property integer $type
 * @property double $price
 * @property double $discount
 * @property double $sum_paid
 * @property string $count_models
 * @property string $count_elayners
 * @property string $count_attachment
 * @property string $count_checkpoint
 * @property string $count_reteiners
 * @property string $count_models_vp
 * @property string $count_elayners_vp
 * @property string $count_attachment_vp
 * @property string $count_checkpoint_vp
 * @property string $count_reteiners_vp
 * @property string $count_models_vc
 * @property string $count_elayners_vc
 * @property string $count_attachment_vc
 * @property string $count_checkpoint_vc
 * @property string $count_reteiners_vc
 * @property string $count_models_nc
 * @property string $count_elayners_nc
 * @property string $count_attachment_nc
 * @property string $count_checkpoint_nc
 * @property string $count_reteiners_nc
 * @property string $level_1_doctor_id
 * @property string $level_2_doctor_id
 * @property string $level_3_doctor_id
 * @property integer $level_4_doctor_id
 * @property string $level_5_doctor_id
 * @property integer $level_1_status
 * @property integer $level_2_status
 * @property integer $level_3_status
 * @property integer $level_4_status
 * @property integer $level_5_status
 * @property string $level_1_result
 * @property string $level_2_result
 * @property string $level_3_result
 * @property string $level_4_result
 * @property string $level_5_result
 * @property string $comments
 * @property string $files
 * @property string $tarif_plan
 * @property string $price_by_plan
 */
class Orders extends \yii\db\ActiveRecord
{

    public $fileList;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%orders}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['fact',
                'otpusheno', 'paid', 'ort_otp', 'ort_pol',
                'payondatecreate_plan', 'payondatecreate_fact',
                'waspayfororder', 'pricewithdiscount',
                'duty_on_request',], 'trim'],
            /*[['num',
                'group_id', 'clinics_id',*/ /*'pacient_code', 'date_paid', 'type_paid', 'status_paid',
                'status_object', 'order_status', 'admin_check', 'status_agreement', 'status', 'scull_type', 'type',
                'count_models', 'count_elayners', 'count_attachment', 'count_checkpoint', 'count_reteiners',
                'count_models_vp', 'count_elayners_vp', 'count_attachment_vp', 'count_checkpoint_vp',
                'count_reteiners_vp', 'count_models_vc', 'count_elayners_vc', 'count_attachment_vc',
                'count_checkpoint_vc', 'count_reteiners_vc', 'count_models_nc', 'count_elayners_nc',
                'count_attachment_nc', 'count_checkpoint_nc', 'count_reteiners_nc',*/ /*'level_1_doctor_id',
                'level_2_doctor_id', 'level_3_doctor_id', 'level_4_doctor_id', 'level_5_doctor_id', *//*'level_1_status',
                'level_2_status', 'level_3_status', 'level_4_status', 'level_5_status', 'level_1_result',
                'level_2_result', 'level_3_result', 'level_4_result', 'level_5_result',*/ /*'comments'], 'required'],*/
            [['num', 'group_id', 'doctor_id', 'clinics_id', 'pacient_code', 'type_paid', 'status_paid', 'status_object', 'order_status', 'admin_check', 'status_agreement', 'status', 'scull_type', 'type', 'count_models', 'count_elayners', 'count_attachment', 'count_checkpoint', 'count_reteiners', 'count_models_vp', 'count_elayners_vp', 'count_attachment_vp', 'count_checkpoint_vp', 'count_reteiners_vp', 'count_models_vc', 'count_elayners_vc', 'count_attachment_vc', 'count_checkpoint_vc', 'count_reteiners_vc', 'count_models_nc', 'count_elayners_nc', 'count_attachment_nc', 'count_checkpoint_nc', 'count_reteiners_nc', 'level_1_doctor_id', 'level_2_doctor_id', 'level_3_doctor_id', 'level_4_doctor_id', 'level_5_doctor_id', 'level_1_status', 'level_2_status', 'level_3_status', 'level_4_status', 'level_5_status', 'tarif_plan', 'vplan_id','order_ready'], 'integer'],
            [['stage_elayners_vc', 'stage_attachment_vc', 'stage_checkpoint_vc', 'stage_reteiners_vc', 'stage_elayners_nc', 'stage_attachment_nc', 'stage_checkpoint_nc', 'stage_reteiners_nc'], 'string'],
            [['price', 'discount', 'sum_paid', 'price_by_plan'], 'number'],
            [['level_1_result', 'dateofpay', 'level_2_result', 'level_3_result', 'level_4_result', 'level_5_result', 'comments'], 'string'],
            //[['comments'], 'required'],
            //[['tarif_plan'], 'required'],
            [['pacient_code'], 'required'],
            [['date_paid'], 'safe'],
            [['files'], 'string', 'max' => 1024],
            // ['vplan_id','required'],
        ];
    }

    public function afterSave($insert, $changedAttributes){

        // автонумерация
            if($this->isNewRecord || $this->num=='') {
                if ($m = Orders::find()->select('num')->where(['pacient_code' => $this->pacient_code])->orderBy('num DESC')->one() ) {
                    $this->num = (int)$m->num+1;
                    $this->save();
                }
            }

        parent::afterSave($insert,$changedAttributes);

        return true;
    }


    public function beforeSave($insert)
    {

        if (strlen($this->dateofpay) > 0) {

            $this->dateofpay = strtotime($this->dateofpay);

        }


        if (strlen($this->dateofplanpay) > 0) {


            $this->dateofplanpay = strtotime($this->dateofplanpay);

        }





        return true;

    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Дата заказа',
            'num' => 'Номер заказа',
            'group_id' => 'Связанный заказ',
            'doctor_id' => 'ФИО врача',
            'clinics_id' => 'Название клиники',
            'pacient_code' => 'Пациент',
            'date_paid' => 'Дата полной оплаты',
            'type_paid' => 'Тип оплаты',
            'status_paid' => 'Статус оплаты',
            'status_object' => 'Статус заказа',
            'order_status' => 'Состояние заказа',
            'admin_check' => 'Разрешение на производство',
            'status_agreement' => 'Состояние договора',
            'status' => 'Состояние заказа',
            'scull_type' => 'Челюсть',
            'type' => 'Тип',
            'price' => 'Цена',
            'discount' => 'Скидка',
            'sum_paid' => 'Сумма к оплате',
            'count_models' => 'Всего моделей',
            'count_elayners' => 'Всего элайнеров',
            'count_attachment' => 'Всего аттачментов',
            'count_checkpoint' => 'Всего Check-point',
            'count_reteiners' => 'Всего ретейнеров',
            'count_models_vp' => 'Count Models Vp',
            'count_elayners_vp' => 'Count Elayners Vp',
            'count_attachment_vp' => 'Count Attachment Vp',
            'count_checkpoint_vp' => 'Count Checkpoint Vp',
            'count_reteiners_vp' => 'Count Reteiners Vp',
            'count_models_vc' => 'Count Models Vc',
            'count_elayners_vc' => 'Count Elayners Vc',
            'count_attachment_vc' => 'Count Attachment Vc',
            'count_checkpoint_vc' => 'Count Checkpoint Vc',
            'count_reteiners_vc' => 'Count Reteiners Vc',
            'count_models_nc' => 'Count Models Nc',
            'count_elayners_nc' => 'Count Elayners Nc',
            'count_attachment_nc' => 'Count Attachment Nc',
            'count_checkpoint_nc' => 'Count Checkpoint Nc',
            'count_reteiners_nc' => 'Count Reteiners Nc',
            'level_1_doctor_id' => 'Level 1 Doctor ID',
            'level_2_doctor_id' => 'Level 2 Doctor ID',
            'level_3_doctor_id' => 'Level 3 Doctor ID',
            'level_4_doctor_id' => 'Level 4 Doctor ID',
            'level_5_doctor_id' => 'Level 5 Doctor ID',
            'level_1_status' => 'Статус уровня 1',
            'level_2_status' => 'Статус уровня 2',
            'level_3_status' => 'Статус уровня 3',
            'level_4_status' => 'Статус уровня 4',
            'level_5_status' => 'Статус уровня 5',
            'level_1_result' => 'Описание результата уровня 1',
            'level_2_result' => 'Описание результата уровня 2',
            'level_3_result' => 'Описание результата уровня 3',
            'level_4_result' => 'Описание результата уровня 4',
            'level_5_result' => 'Описание результата уровня 5',
            'comments' => 'Комментарий к заказу',
            'files' => 'Файлы моделей к заказу',
            'tarif_plan' => 'Тарифный план',
            'price_by_plan' => 'Оплата по плану',
            'vplan_id' => 'Виртуальный план (данные доступны после выбора пациента)',
            'order_ready' => 'Статус завершения производства заказа',
        ];
    }

    /**
     * @inheritdoc
     * @return OrdersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrdersQuery(get_called_class());
    }

    /**
     * Возвращает заказы
     */
    public static function getList()
    {
        $orders = self::find()->all();
        return ArrayHelper::map($orders, 'id', 'num');
    }

    public function getDoctor()
    {
        return $this->hasOne(User::className(), ['id' => 'doctor_id']);
    }

    public function getDoctorFirstname()
    {
        //return $this->doctor->firstname;
        return isset($this->pacient) ? $this->pacient->getDoctorFirstname() : '';// === null ? null : $this->doctor->fullname;
    }

    public function getClinic()
    {
        return $this->hasOne(Clinics::className(), ['id' => 'clinics_id']);
    }

    public function getClinicTitle()
    {
        // return $this->clinic === null ? null : $this->clinic->title;
        return isset($this->pacient) ? $this->pacient->getClinicTitle() : '';// === null ? null : $this->clinic->title;
    }

    public function getPacient()
    {
        return $this->hasOne(Pacients::className(), ['id' => 'pacient_code']);
    }

    public function getPacientCode()
    {
        return $this->pacient === null ? null : $this->pacient->code;//.' '.$this->pacient->firstname;
    }

    public function getPacientName()
    {
        return $this->pacient === null ? null : $this->pacient->name;//.' '.$this->pacient->firstname;
    }

    public function getTarifPlan($id = false)
    {

        if ($id) { // заказываемых моделей
            $plan = Orders::findOne($id);
        } else {
            $plan = $this;
        }

        $price = Price::find()->All(); // все тарифные планы

       // $models_count_vc = (int)$plan->count_elayners_vc + (int)$plan->count_attachment_vc + (int)$plan->count_checkpoint_vc + (int)$plan->count_reteiners_vc;
       // $models_count_nc = (int)$plan->count_elayners_nc + (int)$plan->count_attachment_nc + (int)$plan->count_checkpoint_nc + (int)$plan->count_reteiners_nc;
       // $models_count = $models_count_nc + $models_count_vc;
        $models_count = (int)$plan->count_elayners_vc + (int)$plan->count_elayners_nc;

        if ($models_count == 0) return 0;

        $paket_name = ''; // если условие не подошло, значит последний ТП
        foreach ($price as $p) {
            if ($p->models_min <= (int)$models_count && $p->models_max >= (int)$models_count) {
                return $p->paket_name;
            }
        }


        return $paket_name;
    }

    // кол-во моделей без кап
    public function getModelsCount($id = false)
    {
        if ($id) { // заказываемых моделей
            $plan = Orders::findOne($id);
        } else {
            $plan = $this;
        }

        $models_count_vc = (int)$plan->count_elayners_vc + (int)$plan->count_attachment_vc;// + (int)$plan->count_checkpoint_vc;// + (int)$plan->count_reteiners_vc;
        $models_count_nc = (int)$plan->count_elayners_nc + (int)$plan->count_attachment_nc;// + (int)$plan->count_checkpoint_nc;// + (int)$plan->count_reteiners_nc;
        $models_count = $models_count_nc + $models_count_vc;
        return $models_count;

    }

    // кол-во кап без ретейнеров
    public function getCapCount($id = false)
    {
        if ($id) { // заказываемых моделей
            $plan = Orders::findOne($id);
        } else {
            $plan = $this;
        }

        $models_count_vc = (int)$plan->count_elayners_vc + (int)$plan->count_attachment_vc + (int)$plan->count_checkpoint_vc;// + (int)$plan->count_reteiners_vc;
        $models_count_nc = (int)$plan->count_elayners_nc + (int)$plan->count_attachment_nc + (int)$plan->count_checkpoint_nc;// + (int)$plan->count_reteiners_nc;
        $models_count = $models_count_nc + $models_count_vc;
        return $models_count;

    }


    public static function getReady($pacient_code, $id=0){
        // $num - номер основного заказа
        $models_count = 0;
        // все заказы данного пациента по pacient_code, может быть несколько заказов с разными номерами
        // status=0 - заказ полностью НЕ завершен для данного пацента
        if( $orders = self::find()->where(['pacient_code'=>$pacient_code,'status'=>'0'])->all() ) {
            // суммирование ранее изготовленных изделий, кроме текущих, заказанных
            foreach($orders as $order) {
                if ( $order->id != $id ) { // исключить текущий
                    $models_count_vc = (int)$order->count_elayners_vc + (int)$order->count_attachment_vc + (int)$order->count_checkpoint_vc + (int)$order->count_reteiners_vc;
                    $models_count_nc = (int)$order->count_elayners_nc + (int)$order->count_attachment_nc + (int)$order->count_checkpoint_nc + (int)$order->count_reteiners_nc;
                    $models_count += $models_count_nc + $models_count_vc;                    
                }
            }
        }

        return $models_count;
    }

    /* public static function getParentsList()
    {
        // Выбираем только те категории, у которых есть дочерние категории
        $parents = self::find()
            ->select(['o.id', 'o.num'])
            ->join('JOIN', 'orto_orders o', 'orto_orders.group_id = o.id')
            ->distinct(true)
            ->all();

        return ArrayHelper::map($parents, 'id', 'name');
    }*/

}

