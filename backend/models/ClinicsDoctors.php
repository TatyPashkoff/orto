<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%clinic_doctor}}".
 *
 * @property string $doctor_id
 * @property string $clinic_id
 */
class ClinicsDoctors extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%clinic_doctor}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doctor_id', 'clinic_id'], 'required'],
            [['doctor_id', 'clinic_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'doctor_id' => 'Doctor ID',
            'clinic_id' => 'Clinic ID',
        ];
    }

    /**
     * @inheritdoc
     * @return ClinicsDoctorsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ClinicsDoctorsQuery(get_called_class());
    }
}
