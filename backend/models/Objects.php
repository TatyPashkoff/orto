<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%objects}}".
 *
 * @property string $id
 * @property string $order_id
 * @property string $doctor_id
 * @property integer $type
 * @property double $price
 * @property string $counts
 * @property string $date_start
 * @property string $date_finish
 * @property integer $status
 */
class Objects extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%objects}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'order_id', 'doctor_id', 'type',  'date_start', 'date_finish', 'status'], 'integer'],
            //[[], 'number'],
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
            'doctor_id' => 'Doctor ID',
            'type' => 'Type',
            'price' => 'Price',
            'counts' => 'Counts',
            'date_start' => 'Date Start',
            'date_finish' => 'Date Finish',
            'status' => 'Status',
        ];
    }
}
