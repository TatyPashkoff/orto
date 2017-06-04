<?php
namespace backend\models;

use yii\base\Model;
use yii\web\UploadedFile;
use common\helpers\TextHelper;

use Yii;
class UploadFile extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $imageFiles;

    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'maxFiles' => 20],
        ];
    }

    public function upload($path, $filename=false)
    {
        if ($this->validate()) {
            
			if(is_dir( $path ) == '') mkdir( $path );
            /* if( ! $filename ) {
                $filename = time();
            //}else{
               // $filename .= time();
            }**/

			foreach ($this->imageFiles as $file) {
                // $filename ++;
                // удаление расширения
                $filename = preg_replace('/(.png|.jpg|.jpeg|.gif)/i','',$file->name);
                // преобразование имени файла
                $filename = TextHelper::Transliterate($filename);

                $file->saveAs($path . '/' . $filename . '.' . $file->extension);
                /*if(isset($filename)){
                    $file->saveAs($path . '/' . $filename . '.' . $file->extension);
                }else{
                    $file->saveAs($path . '/' . $file->baseName . '.' . $file->extension);
                }-*/
            }
            return true;
        } else {
            return false;
        }
    }
}