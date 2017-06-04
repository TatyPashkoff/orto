<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "orto_reports_table3".
 *
 * @property integer $id
 * @property integer $report_id
 * @property integer $material_id
 * @property string $unit
 * @property integer $gross
 * @property integer $norm_weight
 * @property integer $net
 */
class ReportsTable3 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orto_reports_table3';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['report_id', 'material_id', 'gross', 'norm_weight', 'net'], 'integer'],
            [['unit'], 'string', 'max' => 255],
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
            'unit' => Yii::t('app', 'Unit'),
            'gross' => Yii::t('app', 'Gross'),
            'norm_weight' => Yii::t('app', 'Norm Weight'),
            'net' => Yii::t('app', 'Net'),
        ];
    }
}
