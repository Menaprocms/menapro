<?php
/**
 * Created by MenaPRO.
 * User: XNX-PTL
 * Date: 25/10/2015
 * Time: 11:01
 */

namespace backend\components;
use Yii;
use common\models\Configuration;
use common\models\Language;
use common\models\User;

class Controller  extends \yii\web\Controller{

    public $menu=['home'=>['site/index']];
    public $address;
    public $email;
    public $phone;
    public $mobile_phone;
    public $opening_hours;
    public $facebook;
    public $twitter;
    public $instagram;
    public $pinterest;
    public $youtube;
    public $web_name;
    public $default_theme;
    public $default_lang;
    public $autosave;
    public $ua_analitycs;
    public $gmap_api_key;



    public function init() {
        Yii::$app->params['all_langs']=Language::find()->all();
        Yii::$app->params['active_langs']=Language::getActiveLanguages();
        Yii::$app->params['default_lang']=Configuration::getValue('_DEFAULT_LANG_');
    $uid = Yii::$app->user->id;
    $u=User::findOne($uid);
        if(Yii::$app->user->isGuest)
        {

            $l=Language::findOne(Yii::$app->params['default_lang']);
        }else{
            $id=$u->lang;
            if(isset(Yii::$app->session['_worklang'])){
                $id=Yii::$app->session['_worklang'];
            }
            $l=Language::findOne($id);
        }

        if(!$l){
            //NO: Idioma por defecto
            $lang=Yii::$app->params['default_lang'];
            $l=Language::findOne($lang);
            Yii::$app->language = $l->iso_code . '-' . $l->country_code;
            Yii::$app->session['_worklang'] = $l->id_lang;
            Yii::$app->params['app_lang']=$l->id_lang;

        }else {
            //SI: Definir session - definir idioma app

            Yii::$app->language = $l->iso_code . '-' . $l->country_code;
            Yii::$app->session['_worklang'] = $l->id_lang;
            Yii::$app->params['app_lang']=$l->id_lang;

        }

        if(Configuration::getValue('_WEB_NAME_')!=false){
            Yii::$app->name=Configuration::getValue('_WEB_NAME_');
        }
        if(Configuration::getValue('_EMAIL_')){
            Yii::$app->params['adminEmail']=Configuration::getValue('_EMAIL_');
            Yii::$app->params['supportEmail']=Configuration::getValue('_EMAIL_');
        }
        if(!isset(Yii::$app->params['user.passwordResetTokenExpire'])){
            Yii::$app->params['user.passwordResetTokenExpire']=3600;
        }


        parent::init();
    }
    public function  beforeAction($action){
        $this->address=Configuration::getValue('_ADDRESS_');
        $this->email=Configuration::getValue('_EMAIL_');
        $this->phone=Configuration::getValue('_PHONE_');
        $this->mobile_phone=Configuration::getValue('_MOBILE_PHONE_');
        $this->opening_hours=Configuration::getValue('_OPENING_HOURS_');
        $this->facebook=Configuration::getValue('_FACEBOOK_');
        $this->twitter=Configuration::getValue('_TWITTER_');
        $this->instagram=Configuration::getValue('_INSTAGRAM_');
        $this->pinterest=Configuration::getValue('_PINTEREST_');
        $this->youtube=Configuration::getValue('_YOUTUBE_');
        $this->web_name=Configuration::getValue('_WEB_NAME_');
        $this->autosave=Configuration::getValue('_AUTOSAVE_');
        $this->default_theme=Configuration::getValue('_DEFAULT_THEME_');
        $this->default_lang=Yii::$app->params['default_lang'];
        $this->ua_analitycs=Configuration::getValue('_UA_ANALYTICS_');
        $this->gmap_api_key=Configuration::getValue('_GMAP_API_KEY_');


        return parent::beforeAction($action);
    }
} 