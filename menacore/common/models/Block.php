<?php

namespace common\models;

use Yii;
use yii\app;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\BlockLang;

/**
 * This is the model class for table "content".
 *
 * @property integer $id
 * @property integer $active
 * @property integer $configurable
 * @property string $version
 *  @property string $prefix
 *  @property integer $block_default
 * @property string $date_upd
 *
 * La categoría PADRE VIRTUAL es la 0;
 */
class Block extends \yii\db\ActiveRecord
{
    public static $deep = 0;
    public static $cArray = array();
    public static $pages;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%block}}';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date_upd'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'date_upd',
                ],
                'value' => function () {
                    return date('Y-m-d H:i');
                },

            ],
        ];
    }

    public function getLangFields()
    {
        return $this->hasMany(BlockLang::className(), ['id_block' => 'id']);
    }

    public static function find()
    {
        $l = Yii::$app->params['default_lang'];
        return parent::find()->with([
            'langFields' => function (\yii\db\ActiveQuery $query) use ($l) {
                $query->andWhere('id_lang = ' . $l);
            }
        ]);

    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active','version','prefix','configurable','block_default'], 'required'],
            [['version','prefix'], 'string','max' => 140],
            [['active','configurable','block_default'], 'integer'],
            [['date_upd'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'active' => Yii::t('app', 'Active'),
            'version' => Yii::t('app', 'Version'),
            'position' => Yii::t('app', 'Position'),
            'configurable' => Yii::t('app', 'Configurable'),
            'prefix'=>Yii::t('app', 'Prefx'),
            'block_default'=>Yii::t('app', 'Block default'),
            'date_upd' => Yii::t('app', 'Updated'),
        ];
    }
    public static function isBlockInstalled($prefix,$version){
        $block=Block::find()->where(['LIKE', 'prefix', $prefix])->andWhere(['LIKE','version',$version])->one();
        if(!$block || is_null($block)){
            return false;
        }else{
            return true;
        }
    }

}