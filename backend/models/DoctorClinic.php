<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "orto_doctor_clinic".
 *
 * @property integer $id
 * @property integer $click_id
 * @property integer $doctor_id
 */
class DoctorClinic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orto_doctor_clinic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['id', 'click_id', 'doctor_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'click_id' => 'Click ID',
            'doctor_id' => 'Doctor ID',
        ];
    }
}
