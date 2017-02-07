<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "configuration".
 *
 * @property integer $id_configuration
 * @property string $name
 * @property string $value
 */
class Configuration extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%configuration}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['value'], 'string'],
            [['name'], 'string', 'max' => 254]
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_configuration' => Yii::t('app', 'Id Configuration'),
            'name' => Yii::t('app', 'Name'),
            'value' => Yii::t('app', 'Value'),
        ];
    }
    public static function getValue($name=null){

        if($name!=null){
            $model = Configuration::getDb()->cache(function ($db) use ($name){
                return Configuration::findOne(['name' => $name]);
             });
//            $model=self::findOne(['name' => $name]);
        }

        if (!$model || $model == null){
            return false;
        }else{
            return $model->value;
        }

    }
}
