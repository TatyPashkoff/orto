<?php

namespace backend\models;

use Yii;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "orto_spr_city".
 *
 * @property integer $id
 * @property integer $code
 * @property string $name
 */
class SprCity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orto_spr_city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Код',
            'name' => 'Город',
        ];
    }
    
    public static function getList()
    {
        $clinics = self::find()->all();
        return ArrayHelper::map($clinics, 'id', 'name');
    }
    
    
}
