<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%banners}}".
 *
 * @property string $id
 * @property string $title
 * @property string $banner
 * @property integer $type
 * @property string $date
 * @property string $date_start
 * @property string $date_finish
 * @property integer $status
 */
class Banners extends \yii\db\ActiveRecord
{
    var $fileList;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%banners}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'date', 'date_start', 'date_finish', 'status','interval'], 'integer'],
            [['title', 'banner', 'text'], 'string', 'max' => 50],
            [['link'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'banner' => 'Баннер',
            'type' => 'Тип',
            'date' => 'Дата создания',
            'date_start' => 'Дата начала',
            'date_finish' => 'Дата завершения',
            'text' => 'Текст',            
            'status' => 'Статус',
            'interval' => 'Интервал смены изображений в секундах',
            'link' => 'Ссылка (для перенаправления при нажатии на баннер)',
            'files' => 'Файлы',
        ];
    }

    /**
     * @inheritdoc
     * @return BannersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BannersQuery(get_called_class());
    }

    public function getFileList($id=false){

        if(!$id) $id = $this->id;
        $path = Yii::getAlias("@backend/web/uploads/banners/" . $id);
        if(is_dir($path)) {
            $dh = opendir($path);
            $files = [];
            while (false !== ($filename = readdir($dh))) {
                if ($filename != '.' && $filename != '..') $files[] = $filename;
            }
            return $files;
        }
        return '';

    }    
    
    
}
