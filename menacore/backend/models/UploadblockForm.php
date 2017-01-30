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

class UploadblockForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $blockFile;

    public function rules()
    {
        return [
            [['blockFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'zip'],
        ];
    }

    public function upload()
    {

        if ($this->validate()) {
           return $this->blockFile->saveAs($this->blockFile->name);

        } else {

            return false;
        }
    }

}