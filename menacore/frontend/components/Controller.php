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


namespace frontend\components;

use Yii;
use common\models\Content;
use common\models\Configuration;
use yii\web\View;

class Controller extends \yii\web\Controller
{


    public $menu = [];
    public $activePage;

    /**
     * List of params to preload into ${controller}->config['nameorparameter'].
     * It is made to reduce time and resource consumption.
     * If you need other parameter call @see Configuration::getValue()
     * @var array
     */
    public $preloadedParams=[
        '_WEB_NAME_',
        '_FACEBOOK_',
        '_TWITTER_',
        '_INSTAGRAM_',
        '_PINTEREST_',
        '_YOUTUBE_',
        '_EMAIL_',
        '_ADDRESS_',
        '_PHONE_',
        '_MOBILE_PHONE_',
        '_OPENING_HOURS_',
        '_DEFAULT_LANG_',
        '_DEFAULT_THEME_',
        '_GMAP_API_KEY_',
        '_UA_ANALYTICS_',
        '_COMPRESS_HTML_',
        '_BOOTSTRAP4_',
        '_COOKIES_NOTIFICATION_',
        '_ENABLE_CACHE_'

    ];



    public $config = [];



    public function init()
    {

        $this->loadDatabaseConfigValues();

        if (isset($_GET['id'])) {
            $this->activePage = $_GET['id'];

        }

        if ($this->config['_WEB_NAME_']) {
            Yii::$app->name = $this->config['_WEB_NAME_'];

        }



        if ($this->config['_EMAIL_']) {
            Yii::$app->params['adminEmail'] = $this->config['_EMAIL_'];
            Yii::$app->params['supportEmail'] = $this->config['_EMAIL_'];
        }
        if ($this->config['_UA_ANALYTICS_']) {
            Yii::$app->params['ua_analitycs'] = Configuration::getValue('_UA_ANALYTICS_');
            $this->view->registerJs(
                "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
                ga('create', '" . Yii::$app->params['ua_analitycs'] . "', 'auto');
                ga('send', 'pageview');"
                , View::POS_HEAD);
        }


        Yii::$app->assetsAutoCompress->enabled=$this->config['_COMPRESS_HTML_'];
        parent::init();

    }




    public function beforeAction($action)
    {

        if ($this->id == "content" && $action->id == "view")
            $this->menu = Content::getContentsTree();

        return parent::beforeAction($action);
    }

    /**
     * Preloads all commonly used config values stored in database
     * to a common variable in controller. Use $this->context->config['_PARAM_']
     * If a param is empty is converted to false.
     */
    public function loadDatabaseConfigValues()
    {
        $rows = Configuration::findAll(['name' => $this->preloadedParams]);

        foreach ($rows as $config) {
            $this->config[$config->name] = trim($config->value) != "" ? $config->value : false;
        }


    }
} 