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
*  @author Xenon media Burgos <contact@menapro.com>
*  @copyright  2016 Xenon Media
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*
*  Proudly made in Burgos, Spain.
*
*/


namespace frontend\components;

use common\models\Content;
use common\models\Language;
use common\models\Configuration;
use common\models\Post;
use yii\web\UrlRuleInterface;
use common\models\ContentLang;
use yii\base\BaseObject;
use yii;

class ContentUrlRule extends BaseObject implements UrlRuleInterface
{

    /**
     * @var array Default route to not found action
     */
    private $notFoundRoute=["content/view", ["id" => '0']];


    public function getLanguagePrefix()
    {

        if (array_key_exists(Yii::$app->params['app_lang'], Yii::$app->params['active_langs'])) {
            if (Yii::$app->params['app_lang'] == Yii::$app->params['default_lang']) {
                return "";
            }
            return mb_strtolower(Yii::$app->params['active_langs'][Yii::$app->params['app_lang']]['iso_code']) . "/";
        } else {
            return "";
        }


    }

    public function createUrl($manager, $route, $params)
    {

        if ($route == 'content/view') {
            if (isset($params['id'])) {

                if (isset($params['link_rewrite'])) {
                    $route = $params['link_rewrite'];
                } else {
                    $r = Content::find()->where(['id' => $params['id']])->one();
                    if (isset($r['langFields'][0]['link_rewrite'])) {
                        $route = $r['langFields'][0]['link_rewrite'];
                    } else
                        return false;
                }


                return $this->getLanguagePrefix() . $route . ".html";
            }
            return false;
        }
        return false;  // this rule does not apply
    }


    /**
     *
     * Process url.
     *
     * @param yii\web\UrlManager $manager
     * @param yii\web\Request $request
     * @return array
     * @throws yii\base\ExitException
     */
    public function parseRequest($manager, $request)
    {


        \Yii::beginProfile('Parsing content request');

        //@todo: Detect browser language and check if is available. Then redirect.
        Yii::$app->params['default_lang']= Configuration::getValue('_DEFAULT_LANG_');

//		if(isset($_GET['liveview'])){

//            Yii::$app->params['app_lang'] = Yii::$app->session['_worklang'];

//        }else {

            $l=false;
            if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
            {
                  $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
                  $l = Language::findByIso($lang);
            }
        
          


            $pathInfo = "";

            \Yii::beginProfile('Loading needed params');
            Yii::$app->params['postpage']=false;
            Yii::$app->params['renderedpostpage']=false;
            Yii::$app->params['islatestpost']=false;

            if(Yii::$app->request->get('latest',false)){
                Yii::$app->params['islatestpost']=true;
            }
            \Yii::beginProfile('Loading default lang');
            if (!$l) {
                Yii::$app->params['app_lang'] = Yii::$app->params['default_lang'];
            } else {
                Yii::$app->params['app_lang'] = $l;
            }
//        }
            // Set default language as default
        \Yii::endProfile('Loading default lang');



        $allActiveLangs = Yii::$app->params['active_langs'] = Language::getActiveLanguages();


            \Yii::beginProfile('Exploding pathinfo');

            unset($allActiveLangs[Yii::$app->params['default_lang']]);
            $pieces = explode("/", $request->getPathInfo());

            $nbPieces = count($pieces);
            \Yii::endProfile('Exploding pathinfo');


        \Yii::endProfile('Loading needed params');

        \Yii::beginProfile('Parsing uri');


        if ($nbPieces == 2 || strlen($pieces[0]) == 2) {

            //Prevent uppercase language



            if (count(array_filter($allActiveLangs,function($var) use ($pieces){
                if(mb_strtolower($pieces[0])==$var['iso_code']){
                    return true;
                }
            }))>0){

                $result="";

                foreach($allActiveLangs as $key=>$value)
                {
                    if(strtolower(substr($pieces[0], 0, 2))==$value['iso_code']){
                        $result=$key;
                       break;
                    }
                }

                Yii::$app->params['app_lang'] =$result;

                    if (strlen($pieces[0]) == 2 && $nbPieces == 1) {
                        $pathInfo = "";
                        \Yii::endProfile('Parsing uri');
                        \Yii::endProfile('Parsing content request');
                        Yii::$app->getResponse()->redirect(Yii::$app->request->baseUrl . "/" . strtolower(substr($pieces[0], 0, 2)) . "/");
                    } else {

                        if (!ctype_lower($pieces[0]) && strlen($pieces[0] == 2)) {
                            \Yii::endProfile('Parsing uri');
                            \Yii::endProfile('Parsing content request');
                            Yii::$app->getResponse()->redirect(Yii::$app->request->baseUrl . "/" . strtolower(substr($pieces[0], 0, 2)) . "/" . $pieces[1]);
                        }
                        if(sizeof($pieces)==3){
                            $pathInfo=$pieces[1].'.html';
                            Yii::$app->params['postpage']=$pieces[2];
                        }else{
                            $pathInfo = $pieces[1];
                        }

                    }


            } else {
                if(sizeof($pieces)==2){
                    $pathInfo=$pieces[0].'.html';
                    Yii::$app->params['postpage']=$pieces[1];
                }else{
                    \Yii::endProfile('Parsing uri');
                    \Yii::endProfile('Parsing content request');
                    return $this->notFoundRoute;
                }

//                return false;
            }
        } else if ($nbPieces = 1) {

            if(Yii::$app->params['default_lang']!=Yii::$app->params['app_lang'] && !isset(Yii::$app->session['_autodetect'])){
                Yii::$app->session['_autodetect']=true;
                Yii::$app->getResponse()->redirect(Yii::$app->request->baseUrl . "/" . $this->getLanguagePrefix() . $pieces[0]);
            }else{
                Yii::$app->params['app_lang']=Yii::$app->params['default_lang'];
                $pathInfo = $pieces[0];
            }

        }

        \Yii::endProfile('Parsing uri');


        //@fixme: Get correct country code
        if (!isset(Yii::$app->params['active_langs'][Yii::$app->params['app_lang']])) {
            Yii::$app->params['app_lang']=Yii::$app->params['default_lang'];
        }

        Yii::$app->language = Yii::$app->params['active_langs'][Yii::$app->params['app_lang']]['iso_code'] . "-" . strtoupper(Yii::$app->params['active_langs'][Yii::$app->params['app_lang']]['country_code']);

        Yii::$app->params['index_friendly_url']=false;
        if (trim($pathInfo) == "" || $pathInfo == 'index.html') {

            $r = Content::find()->where(['id_parent' => 0])
                ->andWhere(['active' => 1])
                ->andWhere(['in_trash' => 0])->orderBy('position ASC')->one();
            $id = (int)$r['id'];
            Yii::$app->params['index_friendly_url']=$r->langFields[0]->link_rewrite.'.html';
            if ($id == 0) {
//                If no active page has been found do search for inactive content
                $r = Content::find()->where(['id_parent' => 0])
                    ->andWhere(['in_trash' => 0])->orderBy('position ASC')->one();
                $id = (int)$r['id'];
            }

        }else{

            if (strpos($pathInfo, '.html') === false && strlen($pieces[0] == 2)) {
                \Yii::endProfile('Parsing content request');

                Yii::$app->getResponse()->redirect(Yii::$app->request->baseUrl . "/" . strtolower(substr($pieces[0], 0, 2)) . "/");
                Yii::$app->end();
            } else if(strpos($pathInfo,".html")!=false){
                if(Yii::$app->params['postpage']){
                    $friendly=str_replace('.html','',Yii::$app->params['postpage']);
                    $pp=Post::find()->where(['friendly_url' => trim($friendly)])->one();
                    if((int)$pp['id']){
                        Yii::$app->params['postpage']=(int)$pp['id'];
                    }else{
                        Yii::$app->params['postpage']=false;
                    }

                }
                $route = str_ireplace('.html', "", $pathInfo);
                $r = ContentLang::find()->where(['link_rewrite' => $route])->andWhere(['id_lang' => Yii::$app->params['app_lang']])->one();
                $id = (int)$r['id_content'];
            }else
            {

                \Yii::endProfile('Parsing content request');
                return $this->notFoundRoute;
            }
        }
        $route = 'content/view';
        $params = array('id' => $id);

        \Yii::endProfile('Parsing content request');

        return [$route, $params];
    }


}