<?php

namespace common\models;

use Yii;
use common\models\Configuration;

/**
 * This is the model class for table "language".
 *
 * @property integer $id_lang
 * @property string $iso_code
 *  @property string $country_code
 * @property string $name
 * @property string $img
 * @property boolean $active
 */
class Language extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%language}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['iso_code','country_code','name','img'], 'required'],
            [['iso_code','country_code'], 'string', 'max' => 2],
            [['name','img'], 'string', 'max' => 50],
            [['active'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_lang' => Yii::t('app', 'Id Lang'),
            'iso_code' => Yii::t('app', 'Iso Code'),
            'country_code' => Yii::t('app', 'Country code'),
            'name'=>Yii::t('app', 'Name'),
            'img' => Yii::t('app', 'Img'),
            'active' => Yii::t('app', 'Active')
        ];
    }

    public static function getActiveLanguages()
    {
        \Yii::beginProfile('Loading active languages');
        if(Configuration::getValue('_ENABLE_CACHE_')){
            $langs = Language::getDb()->cache(function ($db) {
                return Language::find()->where(['active'=>1])->asArray()->all();
            });
        }else{
            $langs=Language::find()->where(['active'=>1])->asArray()->all();
        }


        $langsCodes=[];
        foreach ($langs as $key=>$language)
        {
            $langsCodes[$language['id_lang']]=['iso_code'=>$language["iso_code"],'name'=>$language["name"],'country_code'=>$language["country_code"]];
        }
        \Yii::endProfile('Loading active languages');



        return $langsCodes;
    }

    public static function findByIso($iso_code,$id=true){
        $l=self::find()->where(['iso_code' => $iso_code])->one();

        if($l!=null) {
            if ($id) {

                return $l->id_lang;
            } else {
                return $l;
            }
        }else{
            return false;
        }

    }

}
