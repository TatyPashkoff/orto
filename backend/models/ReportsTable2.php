<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "orto_reports_table2".
 *
 * @property integer $id
 * @property integer $report_id
 * @property integer $material_id
 * @property string $value
 * @property integer $row_id
 */
class ReportsTable2 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orto_reports_table2';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['report_id', 'material_id', 'row_id','pacient_id'], 'integer'],
            [['value'], 'string', 'skipOnEmpty'=> true,'max' => 255],
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
            'value' => Yii::t('app', 'Value'),
            'row_id' => Yii::t('app', 'Row ID'),
        ];
    }
}
