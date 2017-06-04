<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%order_pay}}".
 *
 * @property string $id
 * @property string $order_id
 * @property string $pacient_id
 * @property string $sum_paid
 * @property string $date_paid
 */
class OrderPay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_pay}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'pacient_id', 'sum_paid'], 'integer'],
            [['date_paid'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'pacient_id' => 'Pacient ID',
            'sum_paid' => 'Sum Paid',
            'date_paid' => 'Date Paid',
        ];
    }
}
