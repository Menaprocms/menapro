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

class UploadthemeForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $themeFile;

    public function rules()
    {
        return [
            [['themeFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'zip'],
        ];
    }

    public function upload()
    {

        if ($this->validate()) {
           return $this->themeFile->saveAs($this->themeFile->name);

        } else {

            return false;
        }
    }

}