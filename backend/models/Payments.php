<?php

namespace backend\models;

use backend\models\PaymentsItems;
use backend\models\Price;
use backend\models\Plans;
use Yii;

/**
 * This is the model class for table "{{%payments}}".
 *
 * @property string $id
 * @property string $pacient_id
 * @property string $order_id
 * @property string $paket_id
 * @property double $downpay
 * @property double $pay_month1
 * @property double $pay_month2
 * @property double $pay_month3
 * @property string $date_downpay
 * @property string $date_month1
 * @property string $date_month2
 * @property string $date_month3
 */
class Payments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payments}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pacient_id', 'order_id', 'paket_id', 'status_paid', 'var_paid_vp', 'var_paid', 'status','status_paid_vp'], 'integer'],
            [['downpay', 'sum_discount', 'pay_month1', 'pay_month2', 'pay_month3', 'sum_paid_vp'], 'number'],
            [['date_downpay', 'date_month1', 'date_month2', 'date_month3','date_paid_vp'], 'safe'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pacient_id' => 'Pacient ID',
            'order_id' => 'Order ID',
            'paket_id' => 'Paket ID',
            'downpay' => 'Сумма предоплаты',
            'pay_month1' => 'Сумма оплаты за 1-й месяц',
            'pay_month2' => 'Сумма оплаты за 2-й месяц',
            'pay_month3' => 'Сумма оплаты за 3-й месяц',
            'date_downpay' => 'Дата предоплаты',
            'date_month1' => 'Дата оплаты за 1-й месяц',
            'date_month2' => 'Дата оплаты за 2-й месяц',
            'date_month3' => 'Дата оплаты за 3-й месяц',
            'status_paid' => 'Подтверждение оплаты',
            'type_paid' => 'Способ оплаты',
            'sum_discount' => 'Скидка',
            'var_paid' => 'Способ оплаты за пакет',
            'var_paid_vp' => 'Способ оплаты за ВП',
            'sum_paid_vp' => 'Сумма оплаты за ВП',
            'status_paid_vp' => 'Подтверждение оплаты за ВП',
        ];
    }

    public function getPaymentItems()
    {

        return $this->hasMany(PaymentsItems::className(), ['id' => 'payment_id']);
    }

    // сумма оплаты на заданную дату по пациенту и заказу
    public static function getPaymentSum($order_id, $pacient_id, $date)
    {

        if ($pay = Payments::find()->where(['order_id' => $order_id, 'pacient_id' => $pacient_id, 'status' => '0'])->one()) {

            $pay_items = PaymentsItems::find()->where(['payment_id' => $pay->id])->all();

            foreach ($pay_items as $pay) {
                // поиск суммы оплаты по дате заказа
                if ($pay->date == $date) {
                    return $pay->sum;
                }
            }
        }

        return 0;//'Дата оплаты не указана';

    }

    // получение данных о выполнении заказов N из N
    // N - это кол-во дат для оплаты и соответственно для доставок
    public static function getPaymentReadyItems($order_id, $pacient_id)
    {

        if ($plan_graph = self::find()->where(['pacient_id' => $pacient_id, 'order_id' => $order_id, 'status' => '0'])->one()) {
            // получить все элементы план графика
            if ($plan_items = PaymentsItems::find()->where(['payment_id' => $plan_graph->id])->all()) {
                $cnt = 0;
                foreach ($plan_items as $item) {
                    $cnt += (int)$item->status; // просуммировать все статусы 0+1+0+1+1 = 3 из 5  
                }
                return ['ready' => $cnt, 'all' => count($plan_items)];
           /* }else {
                // если нет элементов оплаты и предоплата подтверждена
                // и не бесплатно -3, значит оплата прошла
                if ($plan_graph->var_paid != 3 && $plan_graph->status_paid == 1) {
                    $paid = 1;
                }*/
            }

        }
        return ['ready' => 0, 'all' => 0];

    }

    // получение даты последней подтвержденной оплаты в план графике для пациента
    public static function getLastPaid($pacient_id)
    {
        $paid = ['ready'=>0,'date' => 0, 'sum' => 0];
        if ($plan_graph = self::find()->where(['pacient_id' => $pacient_id, 'status' => '0'])->one()) {

            // бесплатно 3 или полностью оплачено 1
            if($plan_graph->var_paid == 3 ){ // || $plan_graph->status_paid == 1 ){
                $paid = ['ready'=> 1, 'date' => '', 'sum' => 'Все оплачено']; // все оплачено var_paid=2 - полная оплата
                return $paid;
            }

            // получить все элементы план графика
            if ($plan_items = PaymentsItems::find()->where(['payment_id' => $plan_graph->id])->orderBy('date ASC')->all()) {
                $cnt = count($plan_items) - 1;
                // если предоплата подтверждена и оплата следующего периода не подтв.
                if ($plan_graph->status_paid == 1 && $plan_items[0]->status_paid == 0) {
                    $paid = ['ready'=>0,'date' => strtotime($plan_graph->date_downpay), 'sum' => $plan_graph->downpay]; // i подтверждение оплаты на последнюю оплаченную дату

                } else {

                    // поиск интервала с последней предоплатой
                    $find = false;
                    for ($i = 0; $i < $cnt; $i++) {
                        if ($plan_items[$i]->status_paid == 1 && $plan_items[$i + 1]->status_paid == 0) { // поиск по статусу оплаты
                            //$order_date = strtotime($plan_items[$i]->date); // последняя оплаченная дата
                            $paid = ['ready'=>0,'date' => strtotime($plan_items[$i]->date), 'sum' => $plan_items[$i]->sum]; // i подтверждение оплаты на последнюю оплаченную дату
                            $find = true;
                            break;
                        } elseif ($plan_items[$i]->status_paid == 0) { // если первая дата не подтверждена выход
                            $find = true; // откл. нижней проверки
                            break;
                        }
                    }
                    if (!$find) { // просмотр последней оплаты, если все ранние оплачены
                        if ($plan_items[$cnt]->status_paid == 1) { // поиск по статусу оплаты
                            $paid = ['ready'=>1,'date' => strtotime($plan_items[$cnt]->date), 'sum' => $plan_items[$cnt]->sum]; // подтверждение оплаты на последнюю оплаченную дату
                        }
                    }
                }
            }else{
                // если нет элементов оплаты и предоплата подтверждена
                // и не бесплатно -3, значит оплата прошла
                if( $plan_graph->var_paid !=3 && $plan_graph->status_paid ==1 ){
                    $paid = ['ready'=>1,'date' => $plan_graph->date_downpay, 'sum' => $plan_graph->downpay]; // подтверждение оплаты на последнюю оплаченную дату
                }
            }
        }
        return $paid;

    }

    // получение даты последней неподтвержденной после подтвержденной оплаты в план графике для пациента
    public static function getNextPaid($pacient_id)
    {
        $paid = ['ready'=> 0, 'date' => 0, 'sum' => 0]; // ready - последняя сумма оплачена и подтверждена
        if ($plan_graph = self::find()->where(['pacient_id' => $pacient_id, 'status' => '0'])->one()) {
            $debt = '';
            if( $pacient = Pacients::findOne($pacient_id) ) {
                $paket_sum = $pacient->getPaketSum();
                $sum_discount = $plan_graph->sum_discount;
                $sum = $plan_graph->status_paid ? $plan_graph->downpay : 0; // + предоплата, если подтверждена
                $debt = $paket_sum - $sum; // задолженность
            }

            // бесплатно 3 или полностью оплачено 1
            if($plan_graph->var_paid == 3 ){ //|| $plan_graph->status_paid == 1 ){
                $paid = ['ready'=> 1, 'date' => 'Все оплачено', 'sum' => 'Все оплачено']; // все оплачено var_paid=2 - полная оплата
                return $paid;
            }

            // бесплатно 3 или полностью оплачено 1
            if($plan_graph->var_paid == 2 && $plan_graph->status_paid == 0 ){ // предоплата не подтверждена
                $paid = ['ready'=> 0, 'date' => strtotime($plan_graph->date_downpay), 'sum' => $plan_graph->downpay]; // все оплачено var_paid=2 - полная оплата
                return $paid;
            }

            //
            // получить все элементы план графика
            // если задана дата и сумма не указана выйдет пусто, поэтому условие дополнилось сумма>0
            if ($plan_items = PaymentsItems::find()->where(['payment_id' => $plan_graph->id])->andWhere(['>','sum',0])->orderBy('date ASC')->all()) {
                $cnt = count($plan_items) - 1;
                // если предоплата подтверждена и оплата следующего периода не подтв.
                if ($plan_graph->status_paid == 1 && $plan_items[0]->status_paid == 0) {
                    $paid = ['ready'=> 0,'date' => strtotime($plan_items[0]->date), 'sum' => $plan_items[0]->sum]; // i подтверждение оплаты на последнюю оплаченную дату
                } else {
                    // поиск интервала с последней предоплатой
                    $find = false;
                    for ($i = 0; $i < $cnt; $i++) {
                        if ($plan_items[$i]->status_paid == 1 && $plan_items[$i + 1]->status_paid == 0) { // поиск по статусу оплаты
                            //$order_date = strtotime($plan_items[$i]->date); // последняя оплаченная дата
                            $paid = ['ready'=> 0,'date' => strtotime($plan_items[$i + 1]->date), 'sum' => $plan_items[$i + 1]->sum];  // i+1 !!! следующая дата
                            return $paid;
                            //$find = true;
                            //break;
                        } elseif ($plan_items[$i]->status_paid == 0) { // если первая дата не подтверждена взять ее
                            //$find = true; // откл. нижней проверки
                            $paid = ['ready'=> 0,'date' => strtotime($plan_items[$i]->date), 'sum' => $plan_items[$i]->sum];
                            //break;
                            return $paid;
                        }
                    }
                    if (!$find) { // просмотр последней оплаты, если все ранние оплачены и подтверждены
                        if ( $plan_items[$cnt]->status_paid == 1 || $debt==0 ) { // поиск по статусу оплаты
                            // берется последняя дата, хоть она и подтвержденная ! ?
                            $paid = ['ready'=> 1, 'date' => strtotime($plan_items[$cnt]->date), 'sum' => $plan_items[$cnt]->sum]; // подтверждение оплаты на последнюю оплаченную дату
                        }
                    }
                }
            }else{
                // если нет элементов оплаты и предоплата подтверждена
                // и не бесплатно -3, значит оплата прошла
                if( $plan_graph->var_paid !=3 && $plan_graph->status_paid == 1 && $debt == 0 ){
                    $paid = ['ready'=>1,'date' => strtotime($plan_graph->date_downpay), 'sum' => $plan_graph->downpay]; // подтверждение оплаты на последнюю оплаченную дату
                }

                if($plan_graph->var_paid == 2 && $plan_graph->status_paid == 1 ){ // предоплата подтверждена
                    $paid = ['ready'=> 1, 'date' => 'Все оплачено', 'sum' => 'Все оплачено']; // все оплачено var_paid=2 - полная оплата
                    return $paid;
                }
            }

        }
        return $paid;

    }

    // получение тарифного плана и оплаченной и подтвержденной бух-ом суммы 
    public static function getTarifAndPaid($pacient_id)
    {
        //$pacient = Pacients::findOne($pacient_id);
        $sum = 0;
        $sum_discount = 0;
        $paket = '';


        if ($plan_graf = self::find()->where(['pacient_id' => $pacient_id])->one()) {
            $sum = $plan_graf->downpay; // + предоплата
            $sum_discount = $plan_graf->sum_discount;
            if ($pay_items = \backend\models\PaymentsItems::find()->where(['payment_id' => $plan_graf->id])->all()) {
                foreach ($pay_items as $item) {
                    // + подтверждено бухгалтером
                    if ($item->status_paid == 1) {
                        $sum += $item->sum;
                    }
                }
            }
            $paket = Price::getPaketName($plan_graf->paket_id);

        }

        $paket_sum = self::getPaketSum($pacient_id); // нужно найти стоимость всего заказа ?? по ВП? по прайсу?
        $by_plan = self::getlastPaid($pacient_id);
        $by_plan_next = self::getNextPaid($pacient_id);

        $paid = $paket_sum - $sum_discount - $sum; // - минус скидка
        $sum_disc = $paket_sum - $sum_discount;

        $debt = $by_plan['sum'] - $sum; // долг по заказу
        return ['paket' => $paket ,
                'sum_paid' => $paid ,
                'sum_price'=>$paket_sum,
                'sum_need_by_plan'=>$by_plan['sum'], // оплата по плану
                'sum_discount'=>$sum_disc, // сумма с учетом скидки
                'date_last_paid' => $by_plan['date'],
                'debt' => $debt,
                'date_next_paid' => $by_plan_next['date'],
        ];
        /*
            paket - название пакета
            sum_price - стоимость пакета по прайсу
            sum_discount - стоимость с учетом скидки
            sum_need_by_plan - план
            sum_paid - факт и оплачено по заказу
            sum_need_by_plan - подлежит к оплате по заказу
            date_last_paid - дата оплаты
            debt - долг по заказу
            date_next_paid - дата планируемого погашения
        */


    }


    public static function getPaketSum($pacient_id)
    {

        if (!$plan = Plans::find()->where(['pacient_id' => $pacient_id])->one()) { // ВП кол-во планируемых моделей

            return 0;

        }

        //$models_count_vc = (int)$plan->count_elayners_vc + (int)$plan->count_attachment_vc + (int)$plan->count_checkpoint_vc + (int)$plan->count_reteiners_vc;
        //$models_count_nc = (int)$plan->count_elayners_nc + (int)$plan->count_attachment_nc + (int)$plan->count_checkpoint_nc + (int)$plan->count_reteiners_nc;
        //$models_count = $models_count_nc + $models_count_vc;
        $models_count = (int)$plan->count_elayners_vc + (int)$plan->count_elayners_nc;

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
    }

    public static function getVarPaid($pacient_id){

        if( $payment = self::find()->where(['pacient_id'=>$pacient_id,'status'=>'0'])->one() ){
            return $payment->var_paid;
        }
        return false;

    }


}
