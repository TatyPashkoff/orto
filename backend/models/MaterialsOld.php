<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "orto_materials_old".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $doctor_id
 * @property integer $type
 * @property integer $date_start
 * @property integer $date_finish
 * @property integer $status
 * @property string $descr_err
 * @property string $filename
 */
class MaterialsOld extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orto_materials_old';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'order_id', 'doctor_id', 'type', 'date_start', 'date_finish', 'status'], 'integer'],
            [['descr_err'], 'string', 'max' => 1024],
            [['filename'], 'string', 'max' => 32],
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
            'date_start' => 'Date Start',
            'date_finish' => 'Date Finish',
            'status' => 'Status',
            'descr_err' => 'Descr Err',
            'filename' => 'Filename',
        ];
    }
}
