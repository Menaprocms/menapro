<?php

namespace frontend\controllers;


use themes\grada\assets\AppAsset;
use Yii;
use common\models\Content;
use common\components\ProcmsCommon;
use common\components\Tools;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\View;
use yii\helpers\Html;
use frontend\components\Controller;

/**
 * ContentController implements the CRUD actions for Content model.
 */
class ContentController extends Controller
{
    protected $idContent;
    public function init()
    {
        parent::init();
    }
    public function behaviors()
    {

        $behaviors=[

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],

        ];

        $id=Yii::$app->request->getQueryParam('id');

        if($this->config['_COOKIES_NOTIFICATION_'] && !isset(Yii::$app->session['accept_cookies'])){

            Yii::$app->session['accept_cookies']=false;
        }
       
        if($id && !YII_DEBUG && $this->config['_ENABLE_CACHE_']) /*For disable or enable cache*///
        {
            $behaviors[]=[
                'class' => 'yii\filters\PageCache',
                'only' => ['view'],
                'duration' => 0,
                'variations' => [
                    Yii::$app->language,
                    $id,
                    $this->config['_DEFAULT_THEME_']

                ],
                'dependency' => [
                    'class' => 'yii\caching\DbDependency',
                    'sql' => 'SELECT MAX(date_upd) FROM '.Yii::$app->db->tablePrefix.'content',
                ],
            ];

            $behaviors[]=[
                'class' => 'yii\filters\HttpCache',
                'only' => ['view'],
                'lastModified' => function ($action, $params) {
                    $q = new \yii\db\Query();
                    return strtotime($q->from(Yii::$app->db->tablePrefix.'content')->max('date_upd'));
                },
                'etagSeed' => function ($action, $params) use ($id) {
                    return serialize([$id, Yii::$app->language]);
                },
            ];
        }

        return $behaviors;
    }

    public function beforeAction($action)
    {


        Tools::checkHtaccess();

        if ($action->id == 'view') {
            if($this->config['_BOOTSTRAP4_'])
            {
                Yii::$app->view->registerAssetBundle('frontend\assets\ContentAssetB4');
            }else{
                Yii::$app->view->registerAssetBundle('frontend\assets\ContentAsset');
            }



        }

        $this->view->registerJs("var baseDir= '" . Yii::$app->request->baseUrl . "';", View::POS_HEAD, 'baseDir');
        $this->view->registerJs("var csrfToken='" . Yii::$app->request->getCsrfToken() . "';", View::POS_HEAD, 'CsrfToken');

        return parent::beforeAction($action);
    }

    /**
     * Lists all Content models.
     * @return mixed
     */
    public function actionIndex()
    {

        $dataProvider = new ActiveDataProvider([
            'query' => Content::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function registerBlocksMedias($eData)
    {
        $blocksInPage=array();
        foreach($eData->structure as $k=>$langCont){
            if(!is_null($langCont)){

                foreach($langCont as $q=>$row){
                    foreach($row->content as $x=>$block){
                        $blocksInPage[]=$block->type;
                    }

                }
            }

        }
        $blocksInPage=array_unique($blocksInPage);

        $dependency=$this->config['_BOOTSTRAP4_']?'frontend\assets\ContentAssetB4':'frontend\assets\ContentAsset';


        foreach ($blocksInPage as $k => $block_prefix) {
            $view = '';
            //Block CSS
            if (file_exists(Yii::getAlias('@menaThemes') . "/" . $eData->theme . '/blocks/' . $block_prefix . '/css/' . $block_prefix . '.css')) {

                $this->view->registerCssFile(
                    '@web/themes/' . $eData->theme . '/blocks/' . $block_prefix . '/css/' . $block_prefix . '.css', ['depends' => $dependency]);

            } elseif (file_exists(Yii::getAlias('@menaBase') . '/blocks/' . $block_prefix . '/frontend/css/' . $block_prefix . '.css')) {

                $this->view->registerCssFile(
                    '@web/blocks/' . $block_prefix . '/frontend/css/' . $block_prefix . '.css', ['depends' => $dependency]);
            }
            //Block JS
            if (file_exists(Yii::getAlias('@menaThemes') . "/" . $eData->theme . '/blocks/' . $block_prefix . '/js/' . $block_prefix . '.js')) {

                $this->view->registerJsFile(
                    '@web/themes/' . $eData->theme . '/blocks/' . $block_prefix . '/js/' . $block_prefix . '.js', ['depends' => $dependency]);

            } elseif (file_exists(Yii::getAlias('@menaBase') . '/blocks/' . $block_prefix . '/frontend/js/' . $block_prefix . '.js')) {

                $this->view->registerJsFile(
                    '@web/blocks/' . $block_prefix . '/frontend/js/' . $block_prefix. '.js', ['depends' => $dependency]);
            }

        }
    }

    /**
     * Displays a single Content model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $data = Yii::$app->request->get();
        $lang=Yii::$app->params['app_lang'];
        $theme = $this->config['_DEFAULT_THEME_'];


        //Register ThemeAsset and load main model
        if($data['id']!=0) {
            $model = $this->findModel($id);
            if($model->theme!="default")
            {
                $theme=$model->theme;
            }
        }
            
        if(file_exists(Yii::getAlias('@menaBase') . '/themes/' . $theme . '/assets/ThemeAsset.php'))
        {
            Yii::$app->view->registerAssetBundle("themes\\{$theme}\\assets\\ThemeAsset");
        }

        if($data['id']!=0) {
           
            Yii::$app->params['cur_model_langfields']=$model->assocLang;


            $themeData=[
                'class' => '\yii\base\Theme',
            ];

            Yii::$app->view->metaTags[]=Html::tag("base","",[ 'href'=>Yii::$app->request->baseUrl."/"]);

            if (($model->active == 1 && $model->in_trash==0 ) || (isset($data['liveview']) && isset($data['token']) && $data['token']==LIVEVIEW_HASH)) {
                $eData1 = ProcmsCommon::decodeMedinaPro($model->content);
                $eData = ProcmsCommon::cleanMedinaPro($eData1);

                if ($model->theme != 'default') {
                    Yii::$app->view->theme = Yii::createObject(array_merge($themeData,['pathMap' => ['@app/views' => '@menaThemes/' . $model->theme . '/views']]));
                } else {
                    Yii::$app->view->theme = Yii::createObject(array_merge($themeData,['pathMap' => ['@app/views' => '@menaThemes/' . $theme . '/views']]));
                    $eData->theme = $theme;
                }

                if($this->config['_BOOTSTRAP4_'])
                {
                    //                Bootstrap 4 alpha4
                    Yii::$app->view->registerMetaTag([
                        'name'=>'viewport',
                        'content'=>'width=device-width, initial-scale=1, shrink-to-fit=no'
                    ]);
                }else
                {
                    //                Bootstrap 3
                    Yii::$app->view->registerMetaTag([
                        'name'=>'viewport',
                        'content'=>'width=device-width, initial-scale=1'
                    ]);
                }


               Yii::$app->view->title=$model->langFields[0]->meta_title;

                Yii::$app->view->registerMetaTag([
                    'name' => 'description',
                    'content' => $model->langFields[0]->meta_description,
                ]);





                $this->registerBlocksMedias($eData);


                // Throw exception if content translations does not exist
                if(!isset($eData->structure[$lang]))
                    throw new NotFoundHttpException("This page is not available in your language.");


                if(Yii::$app->request->getQueryParam('content-only',false))
                {
                    $method="renderAjax";

                }else{
                    $method="render";
                }

                return $this->{$method}("procms", [
                    'model' => $model,
                    'proCmsData' => $eData,
                    'userLang' => $lang,
                    'activePage'=>$this->activePage,
                    'defaultLang'=>Yii::$app->params['default_lang'],
                    'show_signing' => true,

                ]);
            } else {
                //redirect 404


                if(Content::find()->where(['id_parent'=> 0])
                    ->andWhere(['active'=>1])
                    ->andWhere(['in_trash'=>0])
                    ->orderBy('position ASC')
                    ->count()
                >0)
                {
                    $theme =$this->config['_DEFAULT_THEME_'];
                    Yii::$app->view->theme = Yii::createObject(array_merge($themeData,['pathMap' => ['@app/views' => '@menaThemes/' . $theme . '/views']]));

                    throw new \yii\web\NotFoundHttpException(Yii::t('app','This page is no longer available.'));

                }else
                    throw new \yii\web\NotFoundHttpException(Yii::t('app','This site has not published content.'));


            }
        }else{

            $this->actionNotfound();
//            $theme =$this->config['_DEFAULT_THEME_'];
//            Yii::$app->view->theme = Yii::createObject([
//                'class' => '\yii\base\Theme',
//                'baseUrl' => '@menaThemes/' . $theme,
//                'basePath' => '@menaThemes/' . $theme,
//                'pathMap' => ['@app/views' => '@menaThemes/' . $theme . '/views'],
//            ]);
//            throw new \yii\web\NotFoundHttpException(Yii::t('app','The requested page does not exist.'));
        }

    }

    public function actionNotfound()
    {
        $theme=$this->config['_DEFAULT_THEME_'];
        Yii::$app->view->theme = Yii::createObject([
            'class' => '\yii\base\Theme',
            'baseUrl' => '@menaThemes/' . $theme,
            'basePath' => '@menaThemes/' . $theme,
            'pathMap' => ['@app/views' => '@menaThemes/' . $theme . '/views'],
        ]);
        throw new \yii\web\NotFoundHttpException(Yii::t('app','Page not found.'));
    }

    /**
     * Finds the Content model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Content the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {

        if (($model = Content::find()->where(['id' => $id])->one()) !== null) {
            return $model;
        } else {
            $theme =$this->config['_DEFAULT_THEME_'];
            Yii::$app->view->theme = Yii::createObject([
                'class' => '\yii\base\Theme',
                'baseUrl' => '@menaThemes/' . $theme,
                'basePath' => '@menaThemes/' . $theme,
                'pathMap' => ['@app/views' => '@menaThemes/' . $theme . '/views'],
            ]);
            throw new NotFoundHttpException(Yii::t('app','The requested page does not exist.'));
        }
    }
}
