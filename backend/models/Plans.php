<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "{{%plans}}".
 *
 * @property string $id
 * @property string $version
 * @property integer $ready
 * @property string $ver_confirm
 * @property string $correct
 * @property string $approved
 * @property string $count_models
 * @property string $count_elayners_vc
 * @property string $count_attachment_vc
 * @property string $count_checkpoint_vc
 * @property string $count_reteiners_vc
 * @property string $count_models_vp
 * @property string $count_elayners_nc
 * @property string $count_attachment_nc
 * @property string $count_checkpoint_nc
 * @property string $count_reteiners_nc
 * @property string $doctor_id
 * @property string $pacient_id
 * @property string $order_id
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
 * @property string $comments
 * @property string $files
 * @property integer $creater
 * @property integer $created_at
 */
class Plans extends \yii\db\ActiveRecord
{
    public $fileList;
    public $pacientName;
    public $doctorName;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plans}}';
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            $this->created_at= time();

            return true;
        }




        return false;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ready', 'count_models', 'count_elayners_vc', 'count_attachment_vc', 'count_checkpoint_vc', 'count_reteiners_vc', 'count_models_nc', 'count_elayners_nc', 'count_attachment_nc', 'count_checkpoint_nc', 'count_reteiners_nc', 'doctor_id', 'pacient_id', 'order_id', 'level_1_doctor_id', 'level_2_doctor_id', 'level_3_doctor_id', 'level_4_doctor_id', 'level_5_doctor_id', 'level_1_status', 'level_2_status', 'level_3_status', 'level_4_status', 'level_5_status', 'creater', 'created_at', 'cancel'], 'integer'],
            [['comments','cancel_msg'], 'string'],
            [['pacient_id'],'required'],
            [['level_1_result', 'level_2_result', 'level_3_result', 'level_4_result', 'level_5_result', 'comments'], 'string'],
            [['version', 'ver_confirm', 'correct', 'approved' ], 'string', 'max' => 16],
            ['files','string'],
           // [['files'],'string','skipOnEmpty' => false],
        ];
    }


    public function getPacient()
    {
        return $this->hasOne(Pacients::className(), ['id' => 'pacient_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pacient' => 'Пациент',
            'version' => 'Версия ВП',
            'ready' => 'Утвержден (врачом)',
            'ver_confirm' => 'Ver Confirm',
            'correct' => 'Корректность',
            'approved' => 'Утвержден (мед. директором)',
            'count_models' => 'Количество моделей',
            'count_elayners_vc' => 'Количество элайнеров (кап)',
            'count_attachment_vc' => 'Количество аттачментов (кап)',
            'count_checkpoint_vc' => 'Количество Check-point (кап)',
            'count_reteiners_vc' => 'Количество ретейнеров (кап)',
            'count_models_nc' => 'Count Models Vp',
            'count_elayners_nc' => 'Count Elayners Vp',
            'count_attachment_nc' => 'Count Attachment Vp',
            'count_checkpoint_nc' => 'Count Checkpoint Vp',
            'count_reteiners_nc' => 'Count Reteiners Vp',
            'doctor_id' => 'Доктор',
            'creater' => 'Техник',
            'doctorName' => 'Доктор',
            'pacient_id' => 'Пациент',
            'pacientName' => 'Пациент',
            'order_id' => '№ заказа',
            'level_1_doctor_id' => 'Level 1 Doctor ID',
            'level_2_doctor_id' => 'Level 2 Doctor ID',
            'level_3_doctor_id' => 'Level 3 Doctor ID',
            'level_4_doctor_id' => 'Level 4 Doctor ID',
            'level_5_doctor_id' => 'Level 5 Doctor ID',
            'level_1_status' => 'Level 1 Status',
            'level_2_status' => 'Level 2 Status',
            'level_3_status' => 'Level 3 Status',
            'level_4_status' => 'Level 4 Status',
            'level_5_status' => 'Level 5 Status',
            'level_1_result' => 'Level 1 Result',
            'level_2_result' => 'Level 2 Result',
            'level_3_result' => 'Level 3 Result',
            'level_4_result' => 'Level 4 Result',
            'level_5_result' => 'Level 5 Result',
            'comments' => 'Комментарии',
            'cancel_msg' => 'Причина отказа',
            'files' => 'Файлы',
        ];
    }

    public function getDoctor() {
//
        return $this->hasOne(User::className(), ['id' => 'doctor_id']);
//        return 'asdsadS';
    }

    public function getDoctorFirstname() {
        return $this->doctor === null ? null : $this->doctor->firstname;
    }

    public static function getList()
    {
        $plans = self::find()->all();
        return ArrayHelper::map($plans, 'id', 'version');
    }

    public static function getTarifPlanByPacient($pacient_id=false){


        if( $pacient_id ) { // заказываемых моделей
            if( ! $plan = self::find()->where(['pacient_id'=>$pacient_id,'ready'=>'1'])->one() )return 0;
        }else{
            return 0;
        }

        $price = Price::find()->All(); // все тарифные планы

        //$models_count_vc = (int)$plan->count_elayners_vc + (int)$plan->count_attachment_vc + (int)$plan->count_checkpoint_vc + (int)$plan->count_reteiners_vc;
        //$models_count_nc = (int)$plan->count_elayners_nc + (int)$plan->count_attachment_nc + (int)$plan->count_checkpoint_nc + (int)$plan->count_reteiners_nc;
        //$models_count = $models_count_nc + $models_count_vc;
        $models_count = (int)$plan->count_elayners_vc + (int)$plan->count_elayners_nc;//$models_count_nc + $models_count_vc;

        if($models_count==0 ) return 0;

        $paket_name = ''; // если условие не подошло, значит последний ТП
        foreach($price as $p){
            if( $p->models_min <= (int)$models_count && $p->models_max >= (int)$models_count ){
                return ['id'=>$p->id, 'name'=>$p->paket_name,'sum'=>$p->price];
            }
        }
        return ['id'=>-1,$paket_name=>'' ,'sum'=>''];

    }

    public function getTarifPlan($id=false){

        if( $id ) { // заказываемых моделей
            $plan = self::find()->where(['id'=>$id,'ready'=>'1'])->one();
        }else{
            return 0;
        }

        $price = Price::find()->All(); // все тарифные планы

        // $models_count_vc = (int)$plan->count_elayners_vc + (int)$plan->count_attachment_vc + (int)$plan->count_checkpoint_vc + (int)$plan->count_reteiners_vc;
        // $models_count_nc = (int)$plan->count_elayners_nc + (int)$plan->count_attachment_nc + (int)$plan->count_checkpoint_nc + (int)$plan->count_reteiners_nc;

        $models_count = (int)$plan->count_elayners_vc + (int)$plan->count_elayners_nc;//$models_count_nc + $models_count_vc;

        if($models_count==0 ) return 0;

        $paket_name = ''; // если условие не подошло, значит последний ТП
        foreach($price as $p){
            if( $p->models_min <= (int)$models_count && $p->models_max >= (int)$models_count ){
                return $p->paket_name;
            }
        }
        return $paket_name;
    }

    // всего моделей
    public function getModelsCount($id=false){

        if( $id ) { // заказываемых моделей
            $plan = self::find()->where(['id'=>$id,'ready'=>'1'])->one();
        }else{
            return 0;
        }

        $models_count_vc =  (int)$plan->count_elayners_vc + (int)$plan->count_attachment_vc;// + (int)$plan->count_checkpoint_vc;// + (int)$plan->count_reteiners_vc;
        $models_count_nc =  (int)$plan->count_elayners_nc + (int)$plan->count_attachment_nc;// + (int)$plan->count_checkpoint_nc;// + (int)$plan->count_reteiners_nc;
        $models_count = $models_count_nc + $models_count_vc;

        return $models_count;

    }

    // всего кап
    public function getCapCount($id=false){

        if( $id ) { // заказываемых кап
            $plan = self::find()->where(['id'=>$id,'ready'=>'1'])->one();
        }else{
            return 0;
        }

        $models_count_vc =  (int)$plan->count_elayners_vc + (int)$plan->count_attachment_vc + (int)$plan->count_checkpoint_vc;// + (int)$plan->count_reteiners_vc;
        $models_count_nc =  (int)$plan->count_elayners_nc + (int)$plan->count_attachment_nc + (int)$plan->count_checkpoint_nc;// + (int)$plan->count_reteiners_nc;
        $models_count = $models_count_nc + $models_count_vc;

        return $models_count;

    }
}
