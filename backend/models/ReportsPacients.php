<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "orto_reports_pacients".
 *
 * @property integer $id
 * @property integer $report_id
 * @property integer $pacient_id
 */
class ReportsPacients extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%reports_pacients}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['report_id', 'pacient_id', 'order_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'report_id' => Yii::t('app', 'Report ID'),
            'pacient_id' => Yii::t('app', 'Pacient ID'),
        ];
    }

    public function getReport()
    {
        return $this->hasOne(Reports::className(), ['id' => 'report_id']);
    }

}
