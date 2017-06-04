<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "orto_reports_table1".
 *
 * @property integer $id
 * @property integer $report_id
 * @property integer $material_id
 * @property integer $total
 * @property integer $model
 * @property string $norm
 * @property integer $begin
 * @property integer $end
 */
class ReportsTable1 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orto_reports_table1';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['report_id', 'material_id' ], 'integer'],
            [['total','begin','end','dop'], 'number'],
            [['norm','model'], 'string', 'max' => 255],
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
            'material_id' => Yii::t('app', 'Material ID'),
            'total' => Yii::t('app', 'Total'),
            'model' => Yii::t('app', 'Model'),
            'norm' => Yii::t('app', 'Norm'),
            'begin' => Yii::t('app', 'Begin'),
            'end' => Yii::t('app', 'End'),
        ];
    }
}
