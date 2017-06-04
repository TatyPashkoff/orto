<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use backend\models\Plans;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email_confirm_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Posts[] $posts
 * @property UserInfo $userInfo
 */
class User extends ActiveRecord

{

    const STATUS_ACTIVE = 1;
    const STATUS_WAIT = 2;
    const STATUS_ADMIN_WAIT = 4;
    const STATUS_BLOCKED = 3;
    const STATUS_DELETED = 0;

    var $password = '';

    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }

    public static function getStatusesArray()
    {
        return [
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_WAIT => 'Ожидает подтверждения',
            self::STATUS_BLOCKED => 'Заблокирован',
            self::STATUS_DELETED => 'Удален',
            self::STATUS_ADMIN_WAIT => 'Ожидает подтверждения от админ',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public static function getAdmins()
    {

        return User::find()->where(['status' => 1])
            ->andWhere(['in', 'role', ['4','2']])->all();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status','role'], 'integer'],
            //['status', 'in', 'range' => array_keys(self::getStatusesArray())],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],


            [['username'],'required'],
            ['username','email'],


            ['username', 'unique', 'targetClass' => self::className(), 'message' => 'This email address has already been taken.'],
//            ['username', 'email', 'min' => 3, 'max' => 255],


            ['fullname', 'string',  'max' => 255],

            ['birth', 'required'],
//            ['birth', 'date', 'format'=> 'd-m-Y'],
           // ['pasport_details', 'required'],

            ['telefon', 'string',  'max' => 255],
            ['pasport_details', 'string',  'max' => 255],

            ['study_status', 'integer',  'max' => 11],

            ['pasport_details', 'string',   'max' => 255],
            ['telefon', 'string',   'max' => 255],
            ['religion', 'string' ,'max' => 255],
            ['gender', 'string' ,'max' => 255],
            ['edu', 'integer'],
            ['regalies', 'string',   'max' => 255],

            ['username', 'filter', 'filter' => 'trim'],
            //['password_hash', 'required'], // email не обязательный, добавляется из личного кабинета
            
//            ['email', 'string', 'max' => 255],
          //  ['email', 'unique', 'targetClass' => self::className(), 'message' => 'This email address has already been taken.'],
        ];
    }

    public function setClinics($values)
    {
        DoctorClinic::deleteAll('doctor_id = :doctor_id', [':doctor_id' => $this->id]);
        foreach($values as $u) {
            $modelCon = new DoctorClinic();
            $modelCon->click_id = $u;
            $modelCon->doctor_id = $this->id;
            if(!$modelCon->save()) {
                vd($modelCon->getErrors());
            }
        }
    }



    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'fullname' => 'Имя',
            'email' => 'Е-mail',
            'birth' => 'Дата рождения',
            'pasport_details' => 'ИИН',
            'telefon' => 'Телефон',
            'gender' => 'Пол',
            'password' => 'Пароль',
            'password_hash' => 'Пароль',
            'role' => 'Тип',
            'edu' => 'Обучение',
            'clinics' => 'Клиники',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }


    /**
     * Возвращает заказы
     */
    public static function getDoctors()
    {
        $doctors = self::find()->where(['status'=>1])
            ->andWhere(['=', 'role', '1'])->all();
        return ArrayHelper::map($doctors, 'id', 'fullname');
    }


    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getClinicsByDoctor($asArray = true){
        //vd(Yii::$app->user->id);
        $id_doctor = $this->id;

        if($asArray){
            $clinics = DoctorClinic::find()->select('click_id')->where(['doctor_id' => $id_doctor])->asArray()->all();
        }else{
            $clinics = DoctorClinic::find()->where(['doctor_id' => $id_doctor])->all();
        }
        return $clinics;
    }


    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        //return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserInfo()
    {
        return $this->hasOne(UserInfo::className(), ['id' => 'id']);
    }

     /**
     * @param string $email_confirm_token
     * @return static|null
     */
    public static function findByEmailConfirmToken($email_confirm_token)
    {
        return static::findOne(['email_confirm_token' => $email_confirm_token, 'status' => self::STATUS_WAIT]);
    }

    /**
     * Generates email confirmation token
     */
    public function generateEmailConfirmToken()
    {
        $this->email_confirm_token = Yii::$app->security->generateRandomString();
    }

    /**
     * Removes email confirmation token
     */
    public function removeEmailConfirmToken()
    {
        $this->email_confirm_token = null;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            return true;
        }
        return false;
    }
	public function changePassword($password)
    {

		$this->password = $password;
		$this->setPassword($this->password_hash);
        $this->generateAuthKey();

		$this->save();

    }
    public function displayName() {

		return $this->username;
	}

    public function getPacients()
    {
        return $this->hasMany(Pacients::className(), ['id' => 'doctor_id']);
    }
    public function getDoctor()
    {
        return $this->hasOne(Doctors::className(), ['id' => 'id']);
    }
    /*public function getClinics()
    {
        return $this->hasMany(Clinics::className(), ['id' => 'clinic_id']);
    }
    public function getClinic()
    {
        return $this->hasOne(Clinics::className(), ['id' => 'clinic_id']);
    }*/


}
