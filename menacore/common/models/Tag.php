<?php

namespace common\models;

use Yii;
use yii\app;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\BlockLang;

/**
 * This is the model class for table "news_tag".
 *
 * @property integer $id
 * @property integer $id_lang
 * @property string $name
 * @property string $friendly_url
 * @property string $description
 *
 * La categoría PADRE VIRTUAL es la 0;
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%news_tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_lang','name','friendly_url'], 'required'],
            [['name','friendly_url'], 'string','max' => 128],
            [['description'], 'string','max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_lang' => Yii::t('app', 'ID Language'),
            'name' => Yii::t('app', 'Name'),
            'frindly_url' => Yii::t('app', 'Friendly url'),
            'description'=>Yii::t('app', 'Description'),

        ];
    }
    public function getposts()
    {
        return $this->hasMany(Post::className(), ['id' => 'id_post'])
            ->viaTable('post_tag', ['id_tag' => 'id']);
    }
    public static function findByName($name,$id_lang){
        return self::find()->where(['name'=>$name,'id_lang'=>$id_lang])->one();
    }


}