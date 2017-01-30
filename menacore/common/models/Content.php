<?php

namespace common\models;

use Yii;
use yii\app;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "content".
 *
 * @property integer $id
 * @property string $content
 * @property integer $id_author
 * @property integer $id_editor
 * @property integer $id_parent
 * @property integer $position
 * @property integer $active
 * @property string $date_add
 * @property string $date_upd
 * @property integer $in_trash
 * @property string $theme
 *
 * La categoría PADRE VIRTUAL es la 0;
 */
class Content extends \yii\db\ActiveRecord
{
    public static $deep = 0;
    public static $cArray = array();
    public static $pages;
    public static $html = '';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%content}}';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date_add', 'date_upd'],
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
        return $this->hasMany(ContentLang::className(), ['id_content' => 'id'])->where('id_lang = ' . (isset(Yii::$app->params['app_lang'])?Yii::$app->params['app_lang']:Configuration::getValue('_DEFAULT_LANG_')));
    }
    public function getAssocLang(){
        return $this->hasMany(ContentLang::className(), ['id_content' => 'id'])->select(['id_lang','link_rewrite'])->asArray();
    }
    public static function find()
    {

        return parent::find()->with('langFields');

    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'id_author', 'id_editor', 'id_parent'], 'required'],
            [['content', 'theme'], 'string'],
            [['id_author', 'id_editor', 'id_parent', 'in_menu', 'position', 'active', 'in_trash'], 'integer'],
            [['date_add', 'date_upd'], 'safe'],
            //Default parent page
            ['id_parent','default','value'=>0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'content' => Yii::t('app', 'Content'),
            'id_author' => Yii::t('app', 'Author'),
            'id_editor' => Yii::t('app', 'Edited by'),
            'id_parent' => Yii::t('app', 'Associated to'),
            'position' => Yii::t('app', 'Position'),
            'active' => Yii::t('app', 'Published'),
            'date_add' => Yii::t('app', 'Created'),
            'date_upd' => Yii::t('app', 'Updated'),
            'in_menu' => Yii::t('app', 'Show in menu'),
            'in_trash' => Yii::t('app', 'In trash'),
            'theme' => Yii::t('app', 'Page theme')
        ];
    }



    public static function getContentsTree($all=false)
    {
        $pages=self::find()
            ->where(['active'=>$all?[1,0]:1])
            ->where(['in_menu'=>$all?[1,0]:1])
            ->andWhere(["in_trash"=>0])
            ->orderBy('position ASC')
            ->asArray()
            ->all();
        \Yii::beginProfile('Building pages tree');
        $tree= self::buildTree($pages,0);
        \Yii::endProfile('Building pages tree');
        return $tree;
    }

    public static function buildTree(array $elements, $parentId = 0)
    {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['id'] == 0)
                continue;

            if ($element['id_parent'] == $parentId) {
                $children = self::buildTree($elements, $element['id']);
                if ($children) {
                    $element['subcats'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }


    /**
     * Checks wheter a page have childs.
     * @param $id
     * @return bool true when it have childs
     */
    public static function hasChilds($id)
    {
        $result = self::findAll([
            'id_parent' => $id
        ]);
        if (!$result) {
            return false;
        } else {
            return true;
        }
    }

    public static function getChilds($id, $in_menu = false)
    {
        if ($in_menu) {
            $result = self::findAll([
                'id_parent' => $id,
                'in_trash' => 0,
                'in_menu' => 1
            ]);
        } else {
            $result = self::findAll([
                'id_parent' => $id,
                'in_trash' => 0
            ]);
        }
        if (!$result) {
            return false;
        } else {
            return $result;
        }
    }


    public function delete()
    {
        if (parent::delete()) {
            ContentLang::deleteAll(['id_content' => $this->id]);
            if (file_exists(getcwd() . '/images/pagethumbs/thumb_' . $this->id . '.jpg')) {
                if (!unlink(getcwd() . '/images/pagethumbs/thumb_' . $this->id . '.jpg')) {
                    throw new \yii\web\HttpException(500, Yii::t('app', 'Error deleting page thumbnail'));
                } else {
                    return true;
                }
            } else {
                return true;
            }
        }
    }

    public static function getNumberOfPages()
    {
        $result = self::find()
            ->select(['id'])
            ->where('in_trash = 0')
            ->all();
        return count($result);
    }

    public static function linkrewriteExits($link, $id)
    {
        $result = ContentLang::find()
            ->select(['id_content'])
            ->where('link_rewrite LIKE "' . $link . '"')
            ->andWhere('id_content != ' . $id)
            ->all();
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }
}