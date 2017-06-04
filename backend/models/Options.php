<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%options}}".
 *
 * @property double $model_price
 * @property double $elayner_price
 * @property double $attachment_price
 * @property double $checkpoint_price
 * @property double $reteiner_price
 * @property string $days_payd
 * @property string $chat_time
 */
class Options extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%options}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_price', 'elayner_price', 'attachment_price', 'checkpoint_price', 'reteiner_price'], 'number'],
            [['days_payd', 'chat_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'model_price' => 'Model Price',
            'elayner_price' => 'Elayner Price',
            'attachment_price' => 'Attachment Price',
            'checkpoint_price' => 'Checkpoint Price',
            'reteiner_price' => 'Reteiner Price',
            'days_payd' => 'Days Payd',
            'chat_time' => 'Chat Time',
        ];
    }

    /**
     * @inheritdoc
     * @return OptionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OptionsQuery(get_called_class());
    }
}
