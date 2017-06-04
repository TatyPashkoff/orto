<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "orto_events".
 *
 * @property string $id
 * @property integer $event
 * @property string $pacient_id
 * @property string $date
 */
class Events extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orto_events';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event', 'pacient_id', 'date'], 'integer'],
            [['text'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event' => 'Event',
            'pacient_id' => 'Pacient ID',
            'date' => 'Date',
            'text' => 'Сообщение',
        ];
    }
}
