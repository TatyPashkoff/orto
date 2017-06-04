<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%price}}".
 *
 * @property string $id
 * @property string $paket_name
 * @property string $models_min
 * @property string $models_max
 * @property double $price
 */
class Price extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%price}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['models_min', 'models_max'], 'integer'],
            [['price'], 'number'],
            [['paket_name'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'paket_name' => 'Пакет',
            'models_min' => 'Количество моделей От:',
            'models_max' => 'Количество моделей До:',
            'price' => 'Цена пакета',
        ];
    }

    public static function getPrice($tarif_plan=false){


        if($price = self::findOne($tarif_plan)) {

            /*if($price->paket_name == 'XXXL' ){
                return $price->price *
            }else{
            }*/

            return $price->price ;
        }
        return 0 ;
    }


    public static function getPaketName($tarif_plan=false){


        if($name = self::findOne($tarif_plan)) {

            /*if($price->paket_name == 'XXXL' ){
                return $price->price *
            }else{

            }*/
            return $name->paket_name ;
        }
        return 0 ;
    }


}
