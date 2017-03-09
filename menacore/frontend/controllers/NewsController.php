<?php

namespace frontend\controllers;



use common\models\Post;
use Yii;
use common\models\Block;
use common\models\BlockLang;
use common\models\Configuration;
use yii\filters\VerbFilter;
use backend\models\UploadblockForm;
use yii\web\UploadedFile;
use common\components\Tools;
use backend\components\Controller;
use ReflectionClass;
use yii\captcha\Captcha;
use yii\captcha\CaptchaAction;
use yii\captcha\CaptchaValidator;
use yii\helpers\Url;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
            'class' => \yii\filters\AccessControl::className(),
            'rules' => [
                // allow authenticated users
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
                // everything else is denied
                [
                    'actions' => ['getposts','getsinglepost','updateshowpostparam'],
                    'allow' => true,
                    'roles' => ['?'],
                ],
            ],
        ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {

        return parent::beforeAction($action);
    }
    public static function getPosts($nposts,$type,$tag=null){
        $query=Post::find();
        if($type==2 && $tag!=null){
            $query->joinWith('tags')
                ->where(['id_tag' =>(int)$tag]);
        }
        $query->andWhere(['published'=>1])->orderBy(['date_add'=>SORT_DESC])->limit($nposts);

        return $query->all();
    }
    public static function getSinglePost($id){
        $post=Post::find()->where(['id'=>$id])->one();

        return $post;
    }
    public function actionUpdateshowpostparam(){
        $data = Yii::$app->request->post();
        Yii::$app->params['postpagecol']=$data['col'];
        Yii::$app->params['postpagerow']=$data['row'];
        die(json_encode(
            array(
                'success' => true)));
    }

}
