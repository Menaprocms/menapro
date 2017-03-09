<?php
/*
*   ****************************
*   *       MenaPro 1.0        *
*   ****************************
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@menapro.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade MenaPro to newer
* versions in the future. If you wish to customize MenaPro for your
* needs please refer to http://menapro.com for more information.
*
*  @author Xenon media Burgos <contact25@menapro.com>
*  @copyright  2016 Xenon Media
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*
*  Proudly made in Burgos, Spain.
*
*/

namespace backend\controllers;

use common\components\Tools;
use Yii;
use common\models\Content;
use common\models\ContentLang;
use common\models\Block;
use common\models\Language;
use common\models\Tag;
use common\models\Configuration;
use common\components\ProcmsCommon;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\View;
use common\widgets\Visiblebutton;
use common\widgets\Langbutton;
use common\widgets\Savebutton;
use common\widgets\Settingsbutton;
use common\widgets\Liveviewbutton;
use backend\components\Controller;

/**
 * ContentController implements the CRUD actions for Content model.
 */
class ContentController extends Controller
{
    public $row_structure = array([12], [6, 6], [4, 8], [8, 4], [4, 4, 4], [3, 3, 3, 3]);
    public $row_options;

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
                ],
            ]
        ];
    }

    public function beforeAction($action)
    {

        $this->row_options =['inverse' => "Inverse",'striking' => "Striking",'inforow' => "Inforow"];
        $theme=Configuration::getValue('_DEFAULT_THEME_');
        $rowOptionsFilepath=Yii::getAlias('@themes').'/'.$theme.'/rowOptions.json';
        if(file_exists($rowOptionsFilepath)){
            $str = file_get_contents(Yii::getAlias('@themes').'/'.$theme.'/rowOptions.json');
            $themeRowOptions= json_decode($str, true);
        }else{
            $themeRowOptions=[];
        }
        $this->row_options=array_merge($this->row_options,$themeRowOptions);
        Yii::$app->view->registerAssetBundle('backend\assets\MenatemaAsset');

        $l = Yii::$app->params['all_langs'];
        $lngA = array();
        $cont = 0;
        foreach ($l as $k => $v) {
            $lngA[$cont]['id'] = $v->id_lang;
            $lngA[$cont]['iso'] = $v->iso_code;
            $cont++;
        }

        Yii::$app->view->registerAssetBundle('backend\assets\ContentAsset');

        $baseDir = $this->_getBaseDir();

        $jsVars = [
            'baseFrontDir' => $baseDir,
            'baseDir' => Yii::$app->request->baseUrl,
            'langs' => $lngA,
            'protocol' => "http://",
            'theme_default' => $theme,
            'csrfToken' => Yii::$app->request->getCsrfToken(),
            'uTok' => LIVEVIEW_HASH,
            'uTokManager' => FILEMANAGER_HASH,
            'default_lang' => Yii::$app->params['default_lang'],
            'gmap_api_key' => Configuration::getValue('_GMAP_API_KEY_')
        ];

        foreach ($jsVars as $name => $value) {
            $this->view->registerJs('var ' . $name . '=' . (is_array($value) ? json_encode($value) : '"' . $value . '"'), View::POS_BEGIN, $name);
        }

    
        $b = $this->_getBlocks();

        $this->view->registerJs("var blockData=JSON.parse('" . json_encode($b) . "');", View::POS_BEGIN, 'blockData');
        $this->view->registerJs("var themeRowOptions=JSON.parse('" . json_encode($themeRowOptions) . "');", View::POS_BEGIN, 'themeRowOptions');

        return parent::beforeAction($action);
    }

    public function _getBaseDir()
    {
        $baseDir = Yii::getAlias('@web');
        $arr = explode('/', $baseDir);
        foreach ($arr as $k => $v) {
            $pos = strpos($v, 'manager');
            if ($pos !== false) {
                $index = $k;
            }
        }
        unset($arr[$index]);
        $baseDir = implode('/', $arr);
        return $baseDir;

    }

    public function _getAvailablePages()
    {
        $baseDir = $this->_getBaseDir();
        $pages = Content::findAll(['in_trash' => 0]);
        $p = array();

        foreach ($pages as $k => $page) {

            $p[] = ['name' => $page->langFields[0]->title, 'id' => $page->id, 'url' => $baseDir . '/' . $page->langFields[0]->link_rewrite . '.html','published'=>$page->active];

        }
        return $p;
    }

    public function _getBlocks()
    {
        $blocks = Block::findAll(['active' => 1]);
        $b = array();
        foreach ($blocks as $k => $block) {
            $b[$block->prefix] = $block->langFields[0]->name;
        }
        return $b;
    }

    /**
     * Lists all Content models.
     * @return mixed
     */
    public function actionIndex()
    {

        $this->actionChecktrash();
		$p = $this->_getAvailablePages();
        $this->view->registerJs("var availablePages=JSON.parse('" . json_encode($p) . "');", View::POS_BEGIN, 'availablePages');
        if (isset($post['work_lang']) && $post['work_lang'] != 0) {
            $curL = $post['work_lang'];
        } else {
            $iso = Yii::$app->language;
            $iso = substr($iso, 0, 2);
            $curL = Language::findByIso($iso);
        }


        $dataProvider = new ActiveDataProvider([
            'query' => Content::find()->where(['in_trash' => 0])

        ]);
        $blocks1 = Block::find()->where(['active' => 1])->andWhere(['>', 'position', 0])->orderBy(['position' => SORT_ASC])->all();
        $blocks2 = Block::find()->where(['active' => 1, 'position' => 0])->all();
        $blocks = array_merge($blocks1, $blocks2);

        foreach ($blocks as $k => $block) {
            $this->view->registerJsFile(Yii::getAlias('@web') . '/../blocks/' . $block->prefix . '/backend/js/' . $block->prefix . '.js', ['depends' => 'backend\assets\ContentAsset']);
            if (file_exists(Yii::getAlias('@menaBase') . '/blocks/' . $block->prefix . '/backend/css/' . $block->prefix . '.css')) {

                $this->view->registerCssFile(
                    Yii::getAlias('@web') . '/../blocks/' . $block->prefix . '/backend/css/' . $block->prefix . '.css', ['depends' => 'backend\assets\ContentAsset']);
            }

        }
        $pages_in_trash = Content::find()->where(['in_trash' => 1])->all();

        return $this->render('_form', [
            'blocks' => $blocks,
            'trash' => $pages_in_trash,
            'menapro_lastversion'=>(float)$this->getLastVersion(),
            'menapro_currentversion'=>(float)Yii::$app->params['menapro_version'],
            'rowStructures' => $this->row_structure,
            'rowOptions' => $this->row_options,
            'curLang' => $curL
        ]);
    }
    public function getLastVersion(){
        $url='http://menapro.com/version.json';
        $data="";
        if( ini_get('allow_url_fopen') ) {
            $data=json_decode(file_get_contents($url));
        }else if(function_exists('curl_version')){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            $data = json_decode(curl_exec($ch));
            curl_close($ch);
        }
        if($data!=""){
            return $data->version;
        }else{
            return false;
        }
    }
    public function actionChecktrash()
    {

        $c = Content::findAll(['in_trash' => 1]);
        $fechaMax = date('Y-m-d', strtotime('-30 day'));
        foreach ($c as $k => $page) {
            if ($page['date_upd'] <= $fechaMax) {
                $pag = $this->findModel($page['id']);
                $pag->delete();
            }
        }

    }

    public function actionRecoverpage()
    {
        $dta = Yii::$app->request->post();
        $id = (int)$dta['id'];
        $model = $this->findModel($id);
        $model->in_trash = 0;
        if ($model->save()) {
            $this->actionLoad($id);
        } else {
            throw new \yii\web\HttpException(500, Yii::t('app', 'Error al recuperar la página'));
        }
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

        if (($model = Content::find()->where(['id' => $id])->one())) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }


    /**
     *  Creates a new Content model and returns load function
     */
    public function actionCreate()
    {
        $model = $this->getNewModel();

        if ($model != false) {
            $this->actionLoad($model['id'], true);
        }
    }

    public function getNewModel()
    {

        $maxPosition = Content::find()->max('position');

        $model = new Content();

        $data['Content']['in_menu'] = 1;
        $data['Content']['active'] = 0;
        $data['Content']['id_parent'] = 0;

//        Default structure
        $data['Content']['content'] = 'eyJzdHJ1Y3R1cmUiOltudWxsLFtdXSwidGhlbWUiOiIiLCJ0cmFzaCI6eyJlbGVtZW50cyI6W251bGwsW11dfSwiY29sdW1ucyI6eyJsZWZ0IjowLCJyaWdodCI6MH19';

        //@todo: Poner valores que van a ser siempre igual en los behaviours de getNewModel
        $data['Content']['id_author'] = (int)Yii::$app->user->getId();
        $data['Content']['id_editor'] = (int)Yii::$app->user->getId();
        $data['Content']['in_trash'] = 0;
        $data['Content']['position'] = $maxPosition + 1;
        $data['Content']['theme'] = 'default';
        $errors = false;
        if ($model->load($data) && $model->save()) {
            $langs = Yii::$app->params['all_langs'];
            foreach ($langs as $lang) {

                $model_lang = new ContentLang();
                $dataL['ContentLang']['id_lang'] = $lang->id_lang;//isset(Yii::$app->session['_worklang']) ? Yii::$app->session['_worklang'] : $this->default_lang;
                $dataL['ContentLang']['id_content'] = $model->id;
                $dataL['ContentLang']['title'] = 'New Page ' . $model->id;
                $dataL['ContentLang']['menu_text'] = 'New Page ' . $model->id;
                $dataL['ContentLang']['meta_title'] = 'New Page ' . $model->id;
                $dataL['ContentLang']['meta_description'] = 'New Page ' . $model->id;
                $dataL['ContentLang']['link_rewrite'] = 'new-page-' . $model->id;


                if ($model_lang->load($dataL) && $model_lang->save()) {
                    continue;

                } else {
                    $errors = true;
                }
            }
            if (!$errors) {
                return $model;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    /**
     * Creates a new Content model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionLoad($id = null, $newRecord = false)
    {

        $post = Yii::$app->request->post();


        if (isset(Yii::$app->session['_worklang']) && Yii::$app->session['_worklang'] != 0) {

            $curL = Yii::$app->session['_worklang'];
        } else {


            $curL = $this->default_lang;
        }

        if ($id == null) {
            $id = $post["id"];
        }

        $model = $this->findModel($id);
        $model_lang = ContentLang::find()->where(['id_content' => $id, 'id_lang' => $curL])->one();
        $cms_content = ProcmsCommon::decodeMedinaPro($model->content);
        $page_header = $this->renderPartial('page_header', ['model' => $model, 'model_lang' => $model_lang]);
        $visibleBtn = Visiblebutton::widget(['id_item' => $model->id]);
        $liveviewBtn = Liveviewbutton::widget(['id_item' => $model->id, 'userToken' => LIVEVIEW_HASH]);
        $langBtn = Langbutton::widget();
        $saveBtn = Savebutton::widget();
        $sb = new Settingsbutton(['id_item' => $model->id]);
        $pageSettings = $sb->getPageSettings();
        $pageSubpanels = $sb->getPageSubpanels($curL);
        $auto = Configuration::findOne(['name' => '_AUTOSAVE_']);
        $l = Yii::$app->params['all_langs'];
        $lngA = array();
        $cont = 0;
        foreach ($l as $k => $v) {
            $lngA[$cont]['id'] = $v->id_lang;
            $lngA[$cont]['iso'] = $v->iso_code;
            $cont++;
        }

        $p = $this->_getAvailablePages();
//todo: Is needed to pass cms as json??

       die(json_encode(
            array(
                'success' => true,
                'model' => [
                    'active' => $model->active,
                    'in_menu' => $model->in_menu,
                    'theme' => $model->theme,
                    'content' => ProcmsCommon::decodeMedinaPro($model->content),
                    'id' => $model->id,
                    'id_parent' => $model->id_parent,
                    'menu_text' => $model->langFields[0]->menu_text,
                    'title' => $model->langFields[0]->title,
                    //@todo: retrieve if any parent element is inactive
                    'parentActive' => 1,
                    'thumbnail' => (int)file_exists(Yii::getAlias('@menaBase/thumbs') . DIRECTORY_SEPARATOR . "_" . $model->id . ".jpg")

                ],
                'modelLang' => $model->langFields[0]->getAttributes(),
                'published' => $model->active,
                'page_header' => $page_header,
                'lang_btn' => $langBtn,
                'visible_btn' => $visibleBtn,
                'liveview_btn' => $liveviewBtn,
                'save_btn' => $saveBtn,
                'page_settings' => $pageSettings,
                'page_subpanels' => $pageSubpanels,
                'curLang'=>$curL,
				'availablePages' => json_encode($p),
				'cms_json' => json_encode($cms_content),
                'cms_id' => $model->id,
                'autosave' => $auto->value,
                'backendBaseDir' => Yii::$app->urlManager->createAbsoluteUrl('')
               // 'session_lang' => Yii::$app->session['_worklang']
            )));

    }

    public function actionSave()
    {

        $data = Yii::$app->request->post();

        $curL = $data['work_lang'];
        if ($data['Content']['id'] == "") {
            $model = $this->getNewModel();
            $model_lang = new ContentLang();
            $id_p = 0;
            $pos = 0;
            $trash = 0;
        } else {
            $model = $this->findModel($data['Content']['id']);
            $model_lang = ContentLang::find()->where(['id_content' => $data['Content']['id'], 'id_lang' => $curL])->one();
            $id_p = $model->id_parent;
            $pos = $model->position;
            $trash = $model->in_trash;
        }


        //@todo: Meter en los behavior
        $data['Content']['id_author'] = (int)Yii::$app->user->getId();
        $data['Content']['id_editor'] = (int)Yii::$app->user->getId();
        $data['Content']['in_trash'] = $trash;
        $data['Content']['position'] = $pos;
        $data['Content']['id_parent'] = $id_p;

        if ($model->load($data) && $model->save()) {

            $dataL['ContentLang']['id_lang'] = $curL;
            $dataL['ContentLang']['id_content'] = $data['Content']['id'];
            $dataL['ContentLang']['title'] = $data['Content']['title'];
            $dataL['ContentLang']['menu_text'] = $data['Content']['menu_text'];
            $dataL['ContentLang']['meta_title'] = $data['Content']['meta_title'];
            $dataL['ContentLang']['link_rewrite'] = $data['Content']['link_rewrite'];
            $dataL['ContentLang']['meta_description'] = $data['Content']['meta_description'];
            if ($model_lang->load($dataL) && $model_lang->save()) {
                die(json_encode(array('success' => true)));
            } else {
                throw new \yii\web\HttpException(500, Yii::t('app', 'Error updating page languages'));
            }

        } else {
            throw new \yii\web\HttpException(500, Yii::t('app', 'Error updating page'));

        }


    }


    public function actionSetlang()
    {

        $post = Yii::$app->request->post();

        $curL = (int)$post['work_lang'];


        $id = (int)$post['id'];

        Yii::$app->session['_worklang'] = $curL;
        Yii::$app->params['app_lang'] = $curL;


        $urlPrefix = "";
        if (Yii::$app->session['_worklang'] != $this->default_lang) {
            $urlPrefix = Yii::$app->params['active_langs'][Yii::$app->session['_worklang']]['iso_code'] . "/";
        }

        if ($id != false) {

            $model = $this->findModel($id);

            if (sizeof($model->langFields) == 0) {
                $model_lang = new ContentLang();
                $dataL['ContentLang']['id_lang'] = $curL;
                $dataL['ContentLang']['id_content'] = $id;
                $dataL['ContentLang']['title'] = 'New Page ' . $id;
                $dataL['ContentLang']['menu_text'] = 'New Page ' . $id;
                $dataL['ContentLang']['meta_title'] = 'New Page ' . $id;
                $dataL['ContentLang']['link_rewrite'] = 'new-page-' . $id;


                if ($model_lang->load($dataL) && $model_lang->save()) {
                    die(json_encode(array('success' => true, 'session_lang' => Yii::$app->session['_worklang'], 'urlPrefix' => $urlPrefix, 'modelLang' => $model->langFields[0]->getAttributes())));
                } else {
                    throw new \yii\web\HttpException(500, Yii::t('app', 'Error updating page language'));

                }

            } else {
                die(json_encode(array(
                    'success' => true, 'urlPrefix' => $urlPrefix, 'session_lang' => Yii::$app->session['_worklang'], 'modelLang' => $model->langFields[0]->getAttributes())));
            }
        }else {
            $query=Tag::find()->where(['id_lang'=>Yii::$app->session['_worklang']]);
            $tagdataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
            $tagManagementHtml=$this->renderPartial('@backend/views/news/tagGridView',[
                'tagdataProvider'=> $tagdataProvider]);

            $tg=$query->all();
            $tags =[];

            foreach($tg as $k=>$tag){
                $tags[]=$tag->name;
            }

            die(json_encode(array(
                'success' => true, 'urlPrefix' => $urlPrefix, 'session_lang' => Yii::$app->session['_worklang'],'tagManagementHtml'=>$tagManagementHtml,'suggestions'=>$tags)));
        }

    }

    public function actionTogglevisible()
    {
        $post = Yii::$app->request->post();
        $this->updatePageParam("active", (isset($post['value']) && $post['value'] == "") ? "0" : "1", $post['id']);

    }


    public function actionTogglemenu()
    {
        $post = Yii::$app->request->post();
        $this->updatePageParam("in_menu", (isset($post['value']) && $post['value'] == "") ? "0" : "1", $post['id']);
    }

    public function actionMenutext()
    {
        $post = Yii::$app->request->post();
        $this->updateLangParam("menu_text", $post['value'], $post['id']);
    }

    public function validateLangFields($value, $sender)
    {
        $limit = '';
        $check = true;
        switch ($sender) {
            case 'meta_title':
                $limit = 140;
                break;
            case 'title':
                $limit = 256;
                break;
            case 'meta_description':
                $limit = 256;
                break;
            case 'menu_text':
                $limit = 128;
                break;
            case 'link_rewrite':
                $limit = 128;
                if (!preg_match('/^[_a-zA-Z0-9\-]+$/', $value)) {
                    $check = false;
                }
                break;
        }
        if ($check) {
            if (sizeof($value) > $limit) {

                throw new \yii\web\HttpException(500, Yii::t('app', 'Invalid value'));
            } else {
                return true;
            }
        } else {
            throw new \yii\web\HttpException(500, Yii::t('app', 'Invalid value'));

        }


    }

    public function actionPagetitle()
    {
        $post = Yii::$app->request->post();
        $this->updateLangParam("title", $post['value'], $post['id']);
    }

    public function actionLinkrewrite()
    {

        $post = Yii::$app->request->post();
        $friendlyUrl = Tools::format_uri($post['value']);

        if ($something=ContentLang::find()->where(
            ['link_rewrite' => $friendlyUrl])
            ->andWhere(['<>', 'id_content', $post['id']])
            ->andWhere(['id_lang' => Yii::$app->session['_worklang']])->count()
        ) {
            $friendlyUrl .= "-2";
        }

        $this->updateLangParam("link_rewrite", $friendlyUrl, $post['id']);




    }

    public function actionMetatitle()
    {
        $post = Yii::$app->request->post();
        if (trim($post['value']) == "") {
            $this->jsonResponse(false, null, ['error' => Yii::t("app", "Meta title cannot be empty")]);
        }
        $this->updateLangParam("meta_title", $post['value'], $post['id']);
    }

    public function actionMetadescription()
    {
        $post = Yii::$app->request->post();
        $this->updateLangParam("meta_description", $post['value'], $post['id']);
    }

    public function actionPagetheme()
    {


        $post = Yii::$app->request->post();
        $model = $this->findModel($post['id']);
        $model->theme = $post['value'];
        if ($model->update() !== false) {
            die(json_encode(['success' => true,
                "theme" => $model->theme == "default" ? Configuration::getValue("_DEFAULT_THEME_") : $model->theme]));
        }
    }

    public function actionSavecontent()
    {
        $dta = Yii::$app->request->post();
        $id = $dta['id'];
        $newContent = $dta['value'];
        if ($id != '') {
            $model = $this->findModel($id);
        } else {
            $model = $this->getNewModel();
        }
        $data['Content']['content'] = $newContent;

        if ($model->load($data) && $model->save()) {
            die(json_encode(array('success' => true)));

        } else {
            throw new \yii\web\HttpException(500, Yii::t('app', 'Error updating page'));

        }

    }

    /**
     * Deletes an existing Content model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {

        $dta = Yii::$app->request->post();
        if (Content::getNumberOfPages() > 1) {
            $id = $dta['id'];
            $model = $this->findModel($id);
            $data['Content']['in_trash'] = 1;
            $data['Content']['id_parent'] = 0;
            $data['Content']['active'] = 0;
            $data['Content']['in_menu'] = 0;
            if ($model->load($data) && $model->save()) {
                $p = $this->_getAvailablePages();
                $pages_in_trash = Content::find()->where(['in_trash' => 1])->all();
                $trash_pages = $this->renderPartial('_trash', ['trash' => $pages_in_trash]);
                die(json_encode(array('success' => true, 'id' => $id,'availablePages' => json_encode($p), 'trash_pages' => $trash_pages)));
            } else {
                throw new \yii\web\HttpException(500, Yii::t('app', 'Error deleting page'));
            }
        } else {
            die(json_encode(array('success' => true, 'message' => Yii::t('app', 'Site can´t be empty. Could not delete page'))));
        }
    }

    public function actionPagethumb()
    {
        $data = Yii::$app->request->post();

        $imageData = $data['image'];
        $idpage = $data['id'];
        $b = strrpos($imageData, ',');
        $c = substr($imageData, 0, $b + 1);
        $imageData = str_replace($c, '', $imageData);
        $imageData = base64_decode($imageData);
        $source = imagecreatefromstring($imageData);
        if (imagejpeg($source, getcwd() . '/images/pagethumbs/thumb_' . $idpage . '.jpg', 70)) {
            imagedestroy($source);
            die(json_encode(array('success' => true, 'img_src' => 'images/pagethumbs/thumb_' . $idpage . '.jpg')));
        } else {
            die(json_encode(array('success' => false)));
        }

    }

    public function actionGetcookiecsrf()
    {
        if (isset($_COOKIE['_csrf'])) {
            $csrf = $_COOKIE['_csrf'];
            die(json_encode(array('success' => true, 'csrf' => $csrf)));
        } else {
            die(json_encode(array('success' => false)));
        }

    }

    public function actionPagesorder()
    {
        $data = Yii::$app->request->post();
        $items = $data['pages'];

        $error = false;

        if (is_array($items)) {
            foreach ($items as $k => $item) {
                $c = $this->findModel((int)$item['id']);
                $c->id_parent = $item['id_parent'];
                $c->position = $item['position'];

                if (!$c->save()) {
                    $error = true;
                }
            }
        }

        if (!$error) {
            die(json_encode(array('success' => true)));
        } else {
            die(json_encode(array('success' => false)));
        }

    }


    private function updatePageParam($param, $value, $id)
    {
        $model = $this->findModel((int)$id);

        if ($model->{$param} == $value)
            $this->jsonResponse(true, $model);

        $model->{$param} = $value;
        if ($model->update()){
		if($param=='active'){
                $p = $this->_getAvailablePages();
                $this->jsonResponse(true, $model,['availablePages' => json_encode($p)]);
            }
            $this->jsonResponse(true, $model);
		}
        $this->jsonResponse(false, $model);

    }

    private function updateLangParam($param, $value, $id)
    {
        $model = ContentLang::findOne([
            'id_lang' => Yii::$app->session['_worklang'],
            'id_content' => $id
        ]);

        if ($model->{$param} == $value)
            $this->jsonResponse(true, $model);

        $model->{$param} = $value;


        if ($model->update()) {
				if($param=='link_rewrite' || $param=='title'){
                $p = $this->_getAvailablePages();
                $this->jsonResponse(true, $model,['availablePages' => json_encode($p)]);
            }
            $this->jsonResponse(true, $model);
        } else
            $this->jsonResponse(false, $model,['error'=>"No se ha podido actualizar el parametro"]);

    }

    private function jsonResponse($success = true, $model = null, array $additionalData = null)
    {
        $response = [];
        if (!is_null($model))
            $response['model'] = $model;

        if (!is_null($additionalData))
            $response = array_merge($response, $additionalData);


        $response['success'] = $success;

        echo Json::encode($response);
        Yii::$app->end();


    }

    public function actionSupervisor()
    {
        $repair = Yii::$app->request->post('repair', false);
        $messages = Yii::$app->supervisor->supervise($repair);
        $result = [
            'response' => $this->renderPartial("@common/widgets/views/_supervisor_results", ['messages' => $messages]),
            'messages' => $messages,
            'success' => true
        ];
        return json_encode($result);
    }

}
