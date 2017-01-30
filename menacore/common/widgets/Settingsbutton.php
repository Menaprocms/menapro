<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\widgets;

use common\models\Block;
use Yii;
use common\components\Html;
use common\components\ProcmsCommon;
use common\models\Content;
use common\models\Configuration;
use common\models\Language;
use backend\models\User;


class Settingsbutton extends \yii\bootstrap\Widget
{
    public $id_item;
    public $page_theme;
    public $themes;
    public $available_languages=[
        "es-ES"=>"Español",

        "en-US"=>"‭English (American)",

        "de-DE"=>"Deutsch",

        "fr-FR"=>"Français",

        "ar-OM"=>"‭Arabic (Oman) ‮(عربية)"

        ,"ar-SY"=>"‭Arabic (Syria) ‮(عربية)"

        ,"id-ID"=>"Bahasa Indonesia"

        ,"bs-BA"=>"Bosanski"

        ,"bg-BG"=>"‭Bulgarian (Български)"

        ,"cs-CZ"=>"Český"

        ,"zh-CN"=>"‭Chinese (Simplified) (简体中文)"

        ,"zh-TW"=>"‭Chinese (Traditional) (正體中文)"

        ,"da-DK"=>"Dansk"

        ,"et-EE"=>"Eesti"

        ,"fa-IR"=>"‭Farsi (Persian) ‮(فارسی)"

        ,"el-GR"=>"‭Greek (Ελληνικά)"

        ,"he-IL"=>"‭Hebrew ‮(עברית)"

        ,"hr-HR"=>"Hrvatski"

        ,"is-IS"=>"Íslenska"

        ,"it-IT"=>"Italiano"

        ,"ja-JP"=>"‭Japanese (日本語)"

        ,"km-KH"=>"‭Khmer (ខមែរ)"

        ,"ko-KR"=>"‭Korean (한국어)"

        ,"lv-LV"=>"Latviešu"

        ,"lt-LT"=>"Lietuvių"

        ,"mk-MK"=>"‭Macedonian (Македонски)"

        ,"hu-HU"=>"Magyar"

        ,"nl-NL"=>"Nederlands"

        ,"nb-NO"=>"‭Norsk (bokmål)"

        ,"nn-NO"=>"‭Norsk (nynorsk)"

        ,"pl-PL"=>"Polski"

        ,"pt-PT"=>"Português"

        ,"pt-BR"=>"Português do Brasil"

        ,"ro-RO"=>"Română"

        ,"ru-RU"=>"‭Russian (Русский)"

        ,"sk-SK"=>"Slovenský"

        ,"sl-SI"=>"Slovensko"

        ,"fi-FI"=>"Suomi"

        ,"sv-SE"=>"Svenska"

        ,"th-TH"=>"‭Thai (ภาษาไทย)"

        ,"uk-UA"=>"‭Ukrainian (Українська)"];

    /**
     * @var array list of items in the nav widget. Each array element represents a single
     * menu item which can be either a string or an array with the following structure:
     *
     * - label: string, required, the nav item label.
     * - url: optional, the item's URL. Defaults to "#".
     * - visible: boolean, optional, whether this menu item is visible. Defaults to true.
     * - linkOptions: array, optional, the HTML attributes of the item's link.
     * - options: array, optional, the HTML attributes of the item container (LI).
     * - active: boolean, optional, whether the item should be on active state or not.
     * - items: array|string, optional, the configuration array for creating a [[Dropdown]] widget,
     *   or a string representing the dropdown menu. Note that Bootstrap does not support sub-dropdown menus.
     *
     * If a menu item is a string, it will be rendered directly without HTML encoding.
     */

    public $route;
    /**
     * @var array the parameters used to determine if a menu item is active or not.
     * If not set, it will use `$_GET`.
     * @see route
     * @see isItemActive
     */
    public $params;
    /**
     * @var string this property allows you to customize the HTML which is used to generate the drop down caret symbol,
     * which is displayed next to the button text to indicate the drop down functionality.
     * Defaults to `null` which means `<b class="caret"></b>` will be used. To disable the caret, set this property to be an empty string.
     */
    public $dropDownCaret;


    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }
        if ($this->params === null) {
            $this->params = Yii::$app->request->getQueryParams();
        }
        if ($this->dropDownCaret === null) {
            $this->dropDownCaret = Html::tag('b', '', ['class' => 'caret']);
        }
        Html::addCssClass($this->options, 'nav');
    }

    /**
     * Renders the widget.
     */
    public function run()
    {

        $this->fillThemesArray();
        if($this->id_item!=null) {
            $model = Content::findOne($this->id_item);
                $this->page_theme=$model->theme;

        }

        $curUserId=Yii::$app->user->getId();
        $curUser=User::findOne($curUserId);

        $langs=Language::find()->all();
        $languages=array();
        foreach($langs as $k=>$lang){
            if($lang->active) {
                $languages[$lang->id_lang] = $lang->name;
            }
        }

        return $this->render('_settingsbutton',[
//            'model'=>$model,
            'page_theme'=>$this->page_theme,
            'themes'=>$this->themes,
            'default_theme'=>Configuration::getValue('_DEFAULT_THEME_'),
            'aut'=>Configuration::getValue('_AUTOSAVE_'),//Configuration::findOne(['name'=>'_AUTOSAVE_']),
            'webName'=>Configuration::getValue('_WEB_NAME_'),
            'facebook'=>Configuration::getValue('_FACEBOOK_'),
            'twitter'=>Configuration::getValue('_TWITTER_'),
            'instagram'=>Configuration::getValue('_INSTAGRAM_'),
            'pinterest'=>Configuration::getValue('_PINTEREST_'),
            'youtube'=>Configuration::getValue('_YOUTUBE_'),
            'email'=>Configuration::getValue('_EMAIL_'),
            'address'=>Configuration::getValue('_ADDRESS_'),
            'phone'=>Configuration::getValue('_PHONE_'),
            'mobile_phone'=>Configuration::getValue('_MOBILE_PHONE_'),
            'opening_hours'=>Configuration::getValue('_OPENING_HOURS_'),
            'blocks'=>Block::find()->orderBy('block_default DESC')->all(),
            'curUser'=>$curUser,
            'langs'=>$languages,
            'all_langs'=>$langs,
            'default_lang'=>Configuration::getValue('_DEFAULT_LANG_'),
            'available_langs'=>$this->available_languages,
            'ua_analytics_code'=>Configuration::getValue('_UA_ANALYTICS_'),
            'gmap_api_key'=>Configuration::getValue('_GMAP_API_KEY_'),
            'supervisorMessages'=>Yii::$app->supervisor->supervise()
        ]);
    }



    public function getPageSubpanels(){

        $this->fillThemesArray();
        if($this->id_item!=null) {
            $model = Content::find()->where(['id'=>$this->id_item])->one();

//            if($model->content=='d'){
//                $this->page_theme=Configuration::getValue('_DEFAULT_THEME_');
//            }else{

                $eData=ProcmsCommon::decodeMedinaPro($model->content);

                $this->page_theme=$eData->theme;
//            }
            $save=true;
        }else{
            $save=false;
        }
        $html=[];
        $html[]=$this->render('_menusubpanel',[
            'model'=>$model,
            'save'=>$save,
        ]);
        $html[]=$this->render('_seosubpanel',[
            'model'=>$model,
            'save'=>$save,
        ]);
        $html[]=$this->render('_pagethemesubpanel',[
            'model'=>$model,
            'themes'=>$this->themes,
            'page_theme'=>$model->theme,
            'general_theme'=>Configuration::getValue('_DEFAULT_THEME_')
        ]);
        $html[]=$this->render('_deletepagesubpanel',[
            'model'=>$model,
            'hasChilds'=>Content::hasChilds($model->id)
        ]);
        return implode("",$html);
    }
    public function getPageSettings(){
        if($this->id_item!=null) {
            $model = Content::findOne($this->id_item);
//            $eData=ProcmsCommon::decodeMedinaPro($model->content);
            $save=true;
        }else{

            $save=false;
        }
        return $this->render('_pagesettings',[
            'save'=>$save
        ]);

    }
    public function fillThemesArray(){
        $dir = Yii::getAlias("@menaThemes")."/*";

        $this->themes=array();
        $themes2=array();

        $defaultTheme=Configuration::getValue('_DEFAULT_THEME_');
        foreach(glob($dir,GLOB_ONLYDIR) as $file){
            if(basename($file)== $defaultTheme){
                $this->themes[basename($file)] = basename($file);
            }else{
                $themes2[basename($file)] = basename($file);

            }

        }
        $this->themes=array_merge($this->themes,$themes2);

    }
    public function getMenuSubpanel(){

        if($this->id_item!=null) {
            $model = Content::findOne($this->id_item);
            $save=true;
        }else{
            $save=false;
        }
        $opt='';
        $btnAtras=Html::a(Yii::t('app', 'Back'),'#',['class'=>'btn btn-default pull-right btnAtras']);
        $opt.= $btnAtras.'<br><br>';


        $lbl=Html::label ('','content-in_menu',['class'=>'onoffswitch-label']);
        if($save) {
            if ($model['in_menu'] === 1) {
                $inp = Html::input('checkbox', 'Content[in_menu]', true, ['id' => 'content-in_menu','class' => 'onoffswitch-checkbox mn_ajax','checked' => '','data-action'=>'content/togglemenu','data-info'=>array('id'=>$this->id_item),'data-callback'=>'','data-target'=>'']);
            } else {
                $inp = Html::input('checkbox', 'Content[in_menu]', true, ['id' => 'content-in_menu','class' => 'onoffswitch-checkbox mn_ajax','data-action'=>'content/togglemenu','data-info'=>array('id'=>$this->id_item),'data-callback'=>'','data-target'=>'']);
            }
        }else{
            $inp = Html::input('checkbox', 'Content[in_menu]', true, ['id' => 'content-in_menu','class' => 'onoffswitch-checkbox mn_ajax', 'checked' => '','data-action'=>'content/togglemenu','data-info'=>array('id'=>$this->id_item),'data-callback'=>'','data-target'=>'']);
        }



        $divCheck=Html::tag('div',$inp.$lbl,['class'=>'onoffswitch pull-right']);

        $li=Html::tag('li',Yii::t('app', 'Show in menu').$divCheck,['class'=>'list-group-item']);
        $ul=Html::tag('ul',$li,['class'=>'list-group']);
        $opt.=$ul;


        $lbl=Html::label (Yii::t('app', 'Menu text'),'menu_text',['class'=>'page_settings_label']);
        $menuText=isset($model)?$model['menu_text']:'';
        $inp=Html::input ('text','Content[menu_text]', $menuText,['id'=>'content-menu_text','class'=>'mn_ajax','data-action'=>'content/menutext','data-info'=>array('id'=>$this->id_item),'data-callback'=>'cms.setPageHeaderMenuText('.$menuText.')','data-target'=>'']);
        $li=Html::tag('li',$lbl.'<br>'.$inp,['class'=>'list-group-item']);
        $ul=Html::tag('ul',$li,['class'=>'list-group']);
        $c=$ul;



        $div2=Html::tag('div',$c,['class'=>'hidden','id'=>'menu_sub_options']);
        $opt.=$div2;


        $divTitle=Html::tag('div',Yii::t('app', 'Menu options'),['class'=>'mn_subpanel_title']);
        $div=Html::tag('div',$divTitle.$opt,['class'=>'hidden sub_panel','title'=>Yii::t('app', 'Menu'),'id'=>'sp_menu']);
        return $div;
    }
    public function getPageThemeSubpanel(){
        $this->fillThemesArray();
        if($this->id_item!=null) {

            $model = Content::findOne($this->id_item);
//            if($model->content=='d'){
//                $this->page_theme=Configuration::getValue('_DEFAULT_THEME_');
//            }else{
                $eData=ProcmsCommon::decodeMedinaPro($model->content);
                $this->page_theme=$eData->theme;
            //}

            $save=true;
        }else{
            $this->page_theme=Configuration::getValue('_DEFAULT_THEME_');
            $save=false;
        }
//        echo  'tema-->'.$eData->theme.'<--';
        $opt='';
        $i=Html::tag('i','',['class'=>'glyphicon glyphicon-chevron-left']);
        $btnAtras=Html::a($i.Yii::t('app', 'Back'),'#',['class'=>'btn btn-default pull-right btnAtras']);
        $opt.= $btnAtras.'<br><br>';



//        $selTheme=Html::dropDownList('select-theme',$curTheme, $this->themes, ['id'=>'select-theme']);



        $opt.=Html::ul($this->themes,[
            'name'=>'config',
            'class'=>'list-group',
            'item'=>function($item,$index){
                //@todo:Preguntar Eros si es necesario instanciar elemento ---> Solucionado con variable global
//                $model = Content::findOne($this->id_item);
//                $eData=ProcmsCommon::decodeMedinaPro($model->content);
//                $curTheme=$eData->theme;
                /********************/
                $label=Html::label($item,'theme_'.$index);
                $html= Html::tag('li',
                   Html::radio('select-page-theme',$index==$this->page_theme?true:false,
                       [
                           'id'=>'theme_'.$index,
                           'value'=>$index,
                           'class'=>'mn_ajax',
                           'data-action'=>'content/pagetheme',
                           'data-info'=>array('id'=>$this->id_item),
                           'data-callback'=>'',
                           'data-target'=>''
                       ] //Radio html options
                   ).$label
                   ,
                   ['class'=>'list-group-item']);
           return $html;
            }
        ]);

        $divTitle=Html::tag('div',Yii::t('app', 'Theme options'),['class'=>'mn_subpanel_title']);
        $div=Html::tag('div',$divTitle.$opt,['class'=>'hidden sub_panel','title'=>Yii::t('app', 'Theme'),'id'=>'sp_theme']);

        return $div;
    }
    public function getGeneralThemeSubpanel(){

        $opt='';
        $i=Html::tag('i','',['class'=>'glyphicon glyphicon-chevron-left']);
        $btnAtras=Html::a($i.Yii::t('app', 'Back'),'#',['class'=>'btn btn-default pull-right btnAtras']);
        $opt.= $btnAtras.'<br><br>';

        $opt.=Html::ul($this->themes,[
            'name'=>'config',
            'class'=>'list-group',
            'item'=>function($item,$index){
                $label=Html::label($item,'theme_'.$index);
                $html= Html::tag('li',
                    Html::radio('select-theme',$index==Configuration::getValue('_DEFAULT_THEME_')?true:false,
                        [
                            'id'=>'theme_'.$index,
                            'value'=>$index,
                            'class'=>'mn_ajax',
                            'data-action'=>'configuration/generaltheme',
                            'data-info'=>'',
                            'data-callback'=>'',
                            'data-target'=>''
                        ] //Radio html options
                    ).$label
                    ,
                    ['class'=>'list-group-item']);
                return $html;
            }
        ]);
        $lblTheme=Html:: label ('Tema','select-theme');

//        $li=Html::tag('li',$lblTheme.'<br>'.$selTheme,['class'=>'list-group-item']);
//        $ul=Html::tag('ul',$li,['class'=>'list-group']);
//        $opt.=$ul;

//@todo: Los títulos internos de los subpaneles pasan a ser mn_subpanel_title
        $divTitle=Html::tag('div',Yii::t('app', 'Web theme'),['class'=>'mn_subpanel_title']);
        $div=Html::tag('div',$divTitle.$opt,['class'=>'hidden sub_panel','title'=>Yii::t('app', 'Theme'),'id'=>'sp_general_theme']);

        return $div;
    }

    public function getDeletePageSubpanel(){


        $opt='';
        $i=Html::tag('i','',['class'=>'glyphicon glyphicon-chevron-left']);
        $btnAtras=Html::a($i.Yii::t('app', 'Back'),'#',['class'=>'btn btn-default pull-right btnAtras']);
        $opt.= $btnAtras.'<br><br>';
        if(Content::hasChilds($this->id_item)){
            $i=Html::tag('i','',['class'=>'icon icon-warning-sign']);
            $msg=Html::tag('p',$i.' '.Yii::t('app', 'The page can´t be deleted because it has other pages associated'));
            $col=Html::tag('div',$msg,['class'=>'col-md-10 col-md-offset-1 alert alert-danger']);
            $div=Html::tag('div',$col,['class'=>'row']);
            $opt.=$div;
        }else{

            $iOk=Html::tag('i','',['class'=>'icon icon-ok']);
            $iKo=Html::tag('i','',['class'=>'icon icon-remove']);
            $msg=Html::tag('p',Yii::t('app', 'Do you really want to delete this page?'));
            $col=Html::tag('div',$msg,['class'=>'col-md-12']);
            $div=Html::tag('div',$col,['class'=>'row']);
            $opt.=$div;
            $btnok=Html::a($iOk.'SI','#',['class'=>'btn btn-success delPageConfirmBtn mn_ajax','id'=>'delpage_ok','data-action'=>'content/delete','data-info'=>array('id'=>$this->id_item),'data-callback'=>'','data-target'=>'']);
            $btnno=Html::a($iKo.'NO','#',['class'=>'btn btn-danger delPageConfirmBtn','id'=>'delpage_no']);
            $colok=Html::tag('div',$btnok,['class'=>'col-md-6 text-center']);
            $colno=Html::tag('div',$btnno,['class'=>'col-md-6 text-center']);
            $row=Html::tag('div',$colok.$colno,['class'=>'row']);

            $opt.=$row;
        }
        $divTitle=Html::tag('div',Yii::t('app', 'Delete page'),['class'=>'mn_subpanel_title']);
        $div=Html::tag('div',$divTitle.$opt,['class'=>'hidden sub_panel','title'=>Yii::t('app', 'Delete'),'id'=>'sp_delete_page_confirm']);

        return $div;
    }
}
