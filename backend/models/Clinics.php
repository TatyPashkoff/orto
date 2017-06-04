<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%clinics}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $adress
 * @property string $phone
 * @property string $contacts_admin
 * @property string $email
 * @property double $model_price
 * @property double $elayner_price
 * @property double $attachment_price
 * @property double $checkpoint_price
 * @property double $reteiner_price
 * @property double $model_discount
 * @property double $elayner_discount
 * @property double $attachment_discount
 * @property double $checkpoint_discount
 * @property double $reteiner_discount
 */
class Clinics extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%clinics}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_id', 'model_price', 'elayner_price', 'attachment_price', 'checkpoint_price', 'reteiner_price', 'model_discount', 'elayner_discount', 'attachment_discount', 'checkpoint_discount', 'reteiner_discount'], 'number'],
            [['title', 'adress', 'phone', 'contacts_admin', 'contract'], 'string', 'max' => 128],
            [['email'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'city_id' => 'Город',
            'adress' => 'Адрес',
            'contract' => '№ договора',
            'phone' => 'Телефон',
            'contacts_admin' => 'Контакт админ',
            'email' => 'Электронная почта',
            'model_price' => 'Цена модели',
            'elayner_price' => 'Цена элайнера',
            'attachment_price' => 'Цена атачмента',
            'checkpoint_price' => 'Цена чекпоинта',
            'reteiner_price' => 'Цена ретейнера',
            'model_discount' => 'Скидка модели',
            'elayner_discount' => 'Скидка элайнера',
            'attachment_discount' => 'Скидка атачмента',
            'checkpoint_discount' => 'Скидка чекпоинта',
            'reteiner_discount' => 'Скидка ретейнера',
        ];
    }

    /**
     * @inheritdoc
     * @return ClinicsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ClinicsQuery(get_called_class());
    }

    /**
     * Возвращает заказы
     */
    public static function getList()
    {
        $clinics = self::find()->all();
        return ArrayHelper::map($clinics, 'id', 'title');
    }

    public function getClinicsByDoctor(){
        //vd(Yii::$app->user->id);
        $id_doctor = Yii::$app->user->id;

        $clinicsArr = DoctorClinic::find()->select('click_id')->where(['doctor_id' => $id_doctor])->asArray()->all();
        //vd($clinicsArr);
        $clinics = self::find()->where(['in', 'id', $clinicsArr])->all();
        //return $clinics;
        //
        return ArrayHelper::map($clinics, 'id', 'title');
    }
    
    public function getCity() {
        return $this->hasOne(SprCity::className(), ['id' => 'city_id']);
    }

    public function getCityName() {
        //return $this->doctor->firstname;
        return $this->city === null ? null : $this->city->name;
    }
}
