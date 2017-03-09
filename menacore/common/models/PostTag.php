<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "news_post_tag".
 *
 * @property integer $id_post
 * @property integer $id_tag
 */
class PostTag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%news_post_tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_post', 'id_tag'], 'required'],
            [['id_post', 'id_tag'], 'integer'],
            [['id_post', 'id_tag'], 'unique', 'targetAttribute' => ['id_post', 'id_tag'], 'message' => 'The combination of Id Accesorio and Id Inmueble has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_post' => 'Id Post',
            'id_tag' => 'Id Tag',
        ];
    }
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'id_post','id_lang'=>'id_post_lang']);
    }
    public function getTag()
    {
        return $this->hasOne(Tag::className(), ['id' => 'id_tag','id_lang'=>'id_post_lang']);
    }
}
