<?php

namespace backend\models;

class NextStep extends \yii\base\Model
{
    public $patient;
    public $doctor;
    public $text;

    public function rules()
    {
        return [
            [['doctor', 'patient', 'text'], 'string'],
            [['doctor', 'patient', 'text'], 'required'],

        ];
    }

};