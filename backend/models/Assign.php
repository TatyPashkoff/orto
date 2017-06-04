<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%assign}}".
 *
 * @property string $id
 * @property string $num
 * @property string $pacient_id
 * @property integer $status
 * @property string $level_1_doctor_id
 * @property string $level_2_doctor_id
 * @property string $level_3_doctor_id
 * @property integer $level_4_doctor_id
 * @property string $level_5_doctor_id
 * @property integer $level_1_status
 * @property integer $level_2_status
 * @property integer $level_3_status
 * @property integer $level_4_status
 * @property integer $level_5_status
 * @property string $level_1_result
 * @property string $level_2_result
 * @property string $level_3_result
 * @property string $level_4_result
 * @property string $level_5_result
 * @property string $level_1_date
 * @property string $level_2_date
 * @property string $level_3_date
 * @property string $level_4_date
 * @property string $level_5_date
 */
class Assign extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%assign}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['num', 'pacient_id', 'status', 'level_1_doctor_id', 'level_2_doctor_id', 'level_3_doctor_id', 'level_4_doctor_id', 'level_5_doctor_id', 'level_1_status', 'level_2_status', 'level_3_status', 'level_4_status', 'level_5_status','level'], 'integer'],
            [['level_1_result', 'level_2_result', 'level_3_result', 'level_4_result', 'level_5_result'], 'string'],
            [['level_1_date', 'level_2_date', 'level_3_date', 'level_4_date', 'level_5_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'num' => 'Num',
            'pacient_id' => 'ID пациента',
            'status' => 'Статус',
            'level' => 'Уровень',
            'level_1_doctor_id' => 'Доктор (уровень 1)',
            'level_2_doctor_id' => 'Доктор (уровень 2)',
            'level_3_doctor_id' => 'Доктор (уровень 3)',
            'level_4_doctor_id' => 'Доктор (уровень 4)',
            'level_5_doctor_id' => 'Доктор (уровень 5)',
            'level_1_status' => 'Состояние (уровень 1)',
            'level_2_status' => 'Состояние (уровень 2)',
            'level_3_status' => 'Состояние (уровень 3)',
            'level_4_status' => 'Состояние (уровень 4)',
            'level_5_status' => 'Состояние (уровень 5)',
            'level_1_result' => 'Результат (уровень 1)',
            'level_2_result' => 'Результат (уровень 2)',
            'level_3_result' => 'Результат (уровень 3)',
            'level_4_result' => 'Результат (уровень 4)',
            'level_5_result' => 'Результат (уровень 5)',
            'level_1_date' => 'Дата создания (уровень 1)',
            'level_2_date' => 'Дата создания (уровень 2)',
            'level_3_date' => 'Дата создания (уровень 3)',
            'level_4_date' => 'Дата создания (уровень 4)',
            'level_5_date' => 'Дата создания (уровень 5)',
        ];
    }

    public static function getPacientsByDoctorId($user_id,$return_object=false){
        $pacients_id = [];
        if($pacients = Assign::find()->where(['status'=>'0'])->andWhere(['or',
            [ 'level_1_doctor_id' => $user_id ],
            [ 'level_2_doctor_id' => $user_id ],
            [ 'level_3_doctor_id' => $user_id ],
            [ 'level_4_doctor_id' => $user_id ],
            [ 'level_5_doctor_id' => $user_id ],
        ])->all() ) {
            if($return_object) return $pacients;
            foreach ($pacients as $item) {
                $pacients_id[] = $item->pacient_id;
            }
        }

        return $pacients_id;
    }
    
    public static function getPacientsByDoctorIdForOrder($user_id,$return_object=false){
        $pacients_id = [];

  
        
        
        if($pacients = Assign::find()->select('pacient_id')->where(['status'=>'0'])->andWhere(['or',
            [ 'level_1_doctor_id' => $user_id ],
            [ 'level_2_doctor_id' => $user_id ],
            [ 'level_3_doctor_id' => $user_id ],
            [ 'level_4_doctor_id' => $user_id ],
            [ 'level_5_doctor_id' => $user_id ],
        ])->all() ) {


            if($return_object) return $pacients;
            // поиск всех пациентов
            foreach ($pacients as $item) {

                /*if( $res = Payments::isPaid($item->pacient_id) ) { // если оплата произведена
                    $res['pacient_id'] = $item->pacient_id;
                    $pacients_id[] = $res; // вернуть id пациента  
                }*/
                $pacients_id[] = $item->pacient_id;
            }
        }

        return $pacients_id;
    }

}
