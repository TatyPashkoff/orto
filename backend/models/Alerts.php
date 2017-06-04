<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%alerts}}".
 *
 * @property string $id
 * @property string $doctor_id_to
 * @property string $doctor_id_from
 * @property string $date
 * @property integer $read_status
 * @property string $text
 */
class Alerts extends \yii\db\ActiveRecord
{

    public $cnt;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%alerts}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doctor_id_to', 'doctor_id_from', 'date', 'read_status','type'], 'integer'],
            [['text'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'doctor_id_to' => 'Кому',
            'doctor_id_from' => 'От',
            'date' => 'Дата',
            'read_status' => 'Прочитан',
            'text' => 'Сообщение',
            'type' => 'Тип сообщения',
        ];
    }

    public static function alertTo($id_user, $msg='Отправлен заказ')
    {
        $alert = new self;
        $thisUserId = Yii::$app->user->id;
        $alert->doctor_id_from = $thisUserId;
        $alert->text = $msg;
        $alert->date= time();
        $alert->doctor_id_to = $id_user;
        if($alert->save()){
            return true;
        }else{
            return false;
        }

    }

    /**
     * @inheritdoc
     * @return AlertsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AlertsQuery(get_called_class());
    }

    public function getDoctorto() {
        //return $this->hasOne(Doctors::className(), ['id' => 'doctor_id_to']);
        return $this->hasOne(User::className(), ['id' => 'doctor_id_to']);
    }

    public function getDoctorfrom() {
        //return $this->hasOne(Doctors::className(), ['id' => 'doctor_id_from']);
        return $this->hasOne(User::className(), ['id' => 'doctor_id_from']);
    }

    public function getDoctorToFirstname() {
        return $this->doctorto === null ? null :  $this->doctorto->fullname;//$this->doctorto->firstname;
    }

    public function getDoctorFromFirstname() {
        return $this->doctorfrom === null ? null :  $this->doctorfrom->fullname; $this->doctorfrom->firstname;
    }
}
