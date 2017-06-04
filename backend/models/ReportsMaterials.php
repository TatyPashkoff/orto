<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%reports_materials}}".
 *
 * @property integer $id
 * @property integer $material_id
 * @property string $value
 */
class ReportsMaterials extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%reports_materials}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['material_id', 'value'], 'required'],
            [['material_id'], 'integer'],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'material_id' => 'Material ID',
            'value' => 'Value',
        ];
    }
}
