<?php

namespace backend\models;

use Yii;
use backend\models\Pacients;

/**
 * This is the model class for table "{{%reports}}".
 *
 * @property integer $id
 * @property string $date
 * @property integer $num
 * @property integer $doctor_id
 * @property integer $pacient_code
 * @property integer $report_status
 * @property integer $status
 * @property integer $type
 * @property integer $count_models
 * @property integer $count_elayners
 * @property integer $count_attachment
 * @property integer $count_checkpoint
 * @property integer $count_reteiners
 * @property string $comments
 */
class Reports extends \yii\db\ActiveRecord
{

    //public $pacients;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%reports}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['date'], 'required'],
            //[['num', 'doctor_id', 'pacient_code', 'report_status', 'status', 'type', 'count_models', 'count_elayners', 'count_attachment', 'count_checkpoint', 'count_reteiners'], 'integer'],
            //[['comments'], 'required'],
            //[['comments'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Дата',
            'num' => 'Номер',
            'doctor_id' => '№ Доктора',
            'pacient_code' => 'Код пациента', //массив для хранения id пациентов
            'report_status' => 'Статус отчета',
            'status' => 'Статус',
            'type' => 'Тип',
            'count_models' => 'Кол-во моделей',
            'count_elayners' => 'Кол-во элайнеров',
            'count_attachment' => 'Кол-во аттачментов',
            'count_checkpoint' => 'Кол-во чекпоинтов',
            'count_reteiners' => 'Кол-во ретейнеров',
            'package' => 'Упаковочный пакет',
            'comments' => 'Комментарии',
        ];
    }
    public function getItems()
    {
        return $this->hasMany(ReportsPacients::className(), ['report_id' => 'id']);
    }

    public function getPacientName()
    {
        return $this->pacient === null ? null : $this->pacient->name;//.' '.$this->pacient->firstname;
    }

}
