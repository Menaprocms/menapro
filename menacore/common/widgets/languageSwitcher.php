<?php

namespace common\widgets;

use Yii;
use yii\base\Component;
use yii\base\Widget;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Url;
use yii\web\Cookie;
use common\models\Language;
use frontend\components\ContentUrlRule;

class languageSwitcher extends Widget
{
    /* ใส่ภาษาของคุณที่นี่ */
    public $languages;

    public function init()
    {

        if(php_sapi_name() === 'cli')
        {
            return true;
        }

        parent::init();


    }

    public function run(){

        $this->languages=Yii::$app->params['active_langs'];

        $languages = $this->languages;

        $current = $languages[Yii::$app->params['app_lang']];

        unset($languages[Yii::$app->params['app_lang']]);

        $items = [];
        //die(var_dump(Yii::$app->params['active_langs']));
        if(isset(Yii::$app->params['cur_model_langfields']) && sizeof(Yii::$app->params['active_langs'])>1) {
            foreach ($languages as $code => $language) {

                $link_r = '';
                $id_lang = '';
                foreach (Yii::$app->params['cur_model_langfields'] as $k => $var) {
                    if ($var['id_lang'] == $code) {
                        $link_r = $var['link_rewrite'];
                        $id_lang = $var['id_lang'];
                        break;
                    }
                };

                $temp = [];
                $temp['label'] = $language['name'];
                if (Yii::$app->params['default_lang'] == $id_lang) {
                    $temp['url'] = Yii::$app->request->baseUrl . '/' . $link_r . '.html';
                } else {
                    $temp['url'] = Yii::$app->request->baseUrl . '/' . $language['iso_code'] . '/' . $link_r . '.html';
                }

                // die(var_dump($temp['url']));
                array_push($items, $temp);
            }

            echo ButtonDropdown::widget([
                'label' => $current['name'],
                'containerOptions'=>['class'=>'languageSwitch'],
                'dropdown' => [
                    'items' => $items,
                ],
            ]);
        }
    }

}
