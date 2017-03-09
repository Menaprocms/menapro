<?php

namespace common\models;

use Yii;
use yii\app;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "news_post".
 *
 * @property integer $id
 * @property integer $author
 * @property integer $published
 * @property string $title
 * @property string $friendly_url
 * @property string $content
 * @property string $date_add
 * @property string $date_upd
 *
 *
 */
class Post extends \yii\db\ActiveRecord
{
//    public static $deep = 0;
//    public static $cArray = array();
//    public static $pages;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%news_post}}';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date_add','date_upd'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'date_upd',
                ],
                'value' => function () {
                    return date('Y-m-d H:i');
                },

            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','author','published','friendly_url'], 'required'],
            [['title','friendly_url'], 'string','max' => 128],
            [['content'], 'string'],
            [['author','published'], 'integer'],
            [['date_upd','date_add'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'author' => Yii::t('app', 'Author'),
            'frindly_url' => Yii::t('app', 'Friendly url'),
            'published'=>Yii::t('app', 'Published'),
            'content'=>Yii::t('app', 'Content'),
            'date_add'=>Yii::t('app', 'Created'),
            'date_upd' => Yii::t('app', 'Updated'),
        ];
    }
    public function gettags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'id_tag'])
            ->viaTable('{{%news_post_tag}}', ['id_post' => 'id']);
    }
    public function getpostauthor(){

        return $this->hasOne(User::className(),['id'=>'author']);//->select(['username']);
    }



}