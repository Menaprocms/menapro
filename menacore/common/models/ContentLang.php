<?php

namespace common\models;

use Yii;
use yii\app;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\Configuration;

/**
 * This is the model class for table "content_lang".
 *
 * @property integer $id_content
 * @property integer $id_lang
 * @property string $title
 * @property string $meta_title
 * @property string $meta_description
 * @property string $link_rewrite
 */
class ContentLang extends \yii\db\ActiveRecord
{
    public static $deep=0;
    public static $cArray=array();
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%content_lang}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_lang', 'id_content','title','meta_title', 'link_rewrite'], 'required'],
            [['title','meta_title', 'link_rewrite','meta_description','menu_text'], 'string'],
             [['id_lang', 'id_content'], 'integer'],
            [['meta_title'], 'string', 'max' => 140],
            [['link_rewrite','menu_text'], 'string', 'max' => 128],
            [['title'], 'string', 'max' => 256],

            ['link_rewrite','unique','targetAttribute'=>['link_rewrite','id_lang']]

        ];
    }

    public static function primaryKey()
    {
        return static::getTableSchema()->primaryKey;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'Title'),
            'meta_title' => Yii::t('app', 'Metatitle'),
            'meta_description' => Yii::t('app', 'Metadescription'),
            'link_rewrite' => Yii::t('app', 'Friendly url'),
            'menu_text'=>Yii::t('app','Menu text'),
            'id_content'=>Yii::t('app','Page'),
            'id_lang'=>Yii::t('app','Language')
        ];
    }
}
