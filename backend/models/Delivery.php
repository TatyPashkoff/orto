<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%delivery}}".
 *
 * @property string $id
 * @property string $order_id
 * @property string $pacient_id
 * @property string $delivery_ready
 * @property string $delivery_all
 * @property string $date_delivery
 */
class Delivery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%delivery}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'pacient_id', 'delivery_ready', 'delivery_all','status','tehnik_id'], 'integer'],
            [['date_delivery'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Заказ',
            'pacient_id' => 'Пациент',
            'delivery_ready' => 'Изготовлено изделий',
            'delivery_all' => 'Заказано изделий',
            'date_delivery' => 'Дата доставки',
            'status' => 'Статус доставки всех изедлий',
        ];
    }

    public static function getReady($pacient_id, $id){
        // $num - номер основного заказа
        $models_count = 0;
        // все заказы данного пациента по pacient_code, может быть несколько заказов с разными номерами
        // status=0 - заказ полностью НЕ завершен для данного пацента
        if( $delivery = self::find()->where(['pacient_id'=>$pacient_id,'order_id'=>$id])->all() ) {
            // суммирование ранее доставленных изделий
            foreach($delivery as $item) {
                $models_count += $item->delivery_ready; // доставленные модели
            }
        }


        return $models_count;
    }
}
