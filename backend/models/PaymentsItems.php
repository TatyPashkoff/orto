<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%payments_items}}".
 *
 * @property string $id
 * @property string $payment_id
 * @property double $sum
 * @property string $counts
 * @property string $date
 * @property integer $status
 */
class PaymentsItems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payments_items}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_id', 'counts', 'status','status_paid','type_paid'], 'integer'],
            [['sum'], 'number'],
            [['date'], 'safe'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'payment_id' => 'Payment ID',
            'sum' => 'Sum',
            'counts' => 'Counts',
            'date' => 'Date',
            'status' => 'Status',
            'status_paid' => 'Подтверждение оплаты',
            'type_paid' => 'Способ оплаты',
        ];
    }



}
