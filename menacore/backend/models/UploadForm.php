<?php
/**
 * advanced.
 * User: x
 * Date: 14/03/2016
 * Time: 19:46
 * Created by: Xenon Publicidad
 */

namespace backend\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'image', 'skipOnEmpty' => false, 'extensions' => 'png, jpg ,ico'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
           return $this->imageFile->saveAs($this->imageFile->name);

        } else {

            return false;
        }
    }

}