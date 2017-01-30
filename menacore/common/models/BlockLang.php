<?php

namespace common\models;

use Yii;
use yii\app;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "content_lang".
 *
 * @property integer $id_block
 * @property integer $id_lang
 * @property string $name
 */
class BlockLang extends \yii\db\ActiveRecord
{
    public static $deep=0;
    public static $cArray=array();
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%block_lang}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_lang', 'id_block','name'], 'required'],
            [['name'], 'string','max' => 140],
             [['id_lang', 'id_block'], 'integer'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'id_block'=>Yii::t('app','Block'),
            'id_lang'=>Yii::t('app','Language')
        ];
    }


}
