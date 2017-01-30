<?php
/**
 * Created by MenaPRO.
 * User: XNX-PTL
 * Date: 25/10/2015
 * Time: 10:53
 */

namespace common\components;

use yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use common\models\Content;
use common\models\ContentLang;
use common\models\Configuration;


class UPDTProcmsCommon extends Component{


    public static function updateMedinaPro($jsonObject){
                foreach($jsonObject['structure'] as $k=>$langStructure) {
                    if ($langStructure != null) {
                        foreach ($langStructure as $row => $rv) {

                            if ($rv != null && sizeof($rv) > 0) {

                                foreach ($rv['content'] as $col => $colv) {

                                    if($colv['type']=='slider' || $colv['type']=='gallery' || $colv['type']=='list'){
                                        //es un parámetro con array (lista, gallery,slider)
                                        $val=self::changeParamsValue($colv['content'],$colv['type']);
//
                                        switch($colv['type']){
                                            case 'list'://
                                                $jsonObject['structure'][$k][$row]['content'][$col]['content']=[];
                                                if(isset($colv['htmlOptions']) && isset($colv['htmlOptions']['title'])){
                                                    $jsonObject['structure'][$k][$row]['content'][$col]['content']['title']=$colv['htmlOptions']['title'];
                                                    unset($colv['htmlOptions']);
                                                }
                                                $jsonObject['structure'][$k][$row]['content'][$col]['content']['items']=$val;
                                                foreach($jsonObject['structure'][$k][$row]['content'][$col]['content'] as $ki=>$vi){
                                                    if($ki!='items' && $ki!='title'){
                                                        unset($jsonObject['structure'][$k][$row]['content'][$col]['content'][$ki]);
                                                    }
                                                }
                                                break;
                                            case 'slider':
                                                $jsonObject['structure'][$k][$row]['content'][$col]['content']=[];
                                                $jsonObject['structure'][$k][$row]['content'][$col]['content']['slides']=$val;

                                                foreach($jsonObject['structure'][$k][$row]['content'][$col]['content'] as $ki=>$vi){
                                                    if($ki!=='slides'){
                                                      unset($jsonObject['structure'][$k][$row]['content'][$col]['content'][$ki]);
                                                    }
                                                }
                                                break;
                                            case 'gallery':
                                                $jsonObject['structure'][$k][$row]['content'][$col]['content']=[];
                                                $jsonObject['structure'][$k][$row]['content'][$col]['content']['images']=$val;
                                                foreach($jsonObject['structure'][$k][$row]['content'][$col]['content'] as $ki=>$vi){
                                                    if($ki!='images'){
                                                        unset($jsonObject['structure'][$k][$row]['content'][$col]['content'][$ki]);
                                                    }
                                                }
                                                break;
                                        }

                                    }else {
                                        if(isset($colv['content'][0])) {
                                            $val = self::changeParamsValue($colv['content'][0]);
                                        }else{
                                            $val = self::changeParamsValue($colv['content']);
                                        }
                                        $jsonObject['structure'][$k][$row]['content'][$col]['content'] = $val;
                                    }

                                }

                            }
                        }
                    }
                }
        if(sizeof($jsonObject['trash'])>0) {
            foreach ($jsonObject['trash'] as $k => $langStructure) {

                if ($langStructure != null) {
                    foreach ($langStructure as $row => $rv) {

                        if ($rv != null && sizeof($rv) > 0) {

                            foreach ($rv as $col => $colv) {

                                if($colv['type']=='slider' || $colv['type']=='gallery' || $colv['type']=='list') {
                                    //es un parámetro con array (lista, gallery,slider)

                                    $val = self::changeParamsValue($colv['content'], $colv['type']);
                                    switch ($colv['type']) {
                                        case 'list':
                                            if(isset($colv['htmlOptions']) && isset($colv['htmlOptions']['title'])){
                                                $jsonObject['trash']['elements'][$row][$col]['content']['title']=$colv['htmlOptions']['title'];
                                                unset($colv['htmlOptions']);
                                            }
                                            $jsonObject['trash']['elements'][$row][$col]['content']['items'] = $val;
                                            break;
                                        case 'slider':
                                            $jsonObject['trash']['elements'][$row][$col]['content']['slides'] = $val;
                                             break;
                                        case 'gallery':
                                            $jsonObject['trash']['elements'][$row][$col]['content']['images'] = $val;
                                            break;
                                    }

                                } else {
                                    if (isset($colv['content'][0])) {
                                        $val = self::changeParamsValue($colv['content'][0]);
                                    } else {
                                        $val = self::changeParamsValue($colv['content']);
                                    }
                                    $jsonObject['trash']['elements'][$row][$col]['content'] = $val;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $jsonObject;
    }
    public static function changeParamsValue($content,$type=''){

        if(is_array($content)) {
            foreach ($content as $param =>$value) {
                if (is_array($value)) {
                    $content[$param] = self::changeArrayParamsValue($value);
                } else {
                    if ($param == 'htmlOptions') {
                        foreach ($value as $k=>$v) {
                            if($k=='title'){
                                $content['title'] = $v;
                            }else{
                                $content['elink'] = self::getLinkData($v);
                            }
                        }
                        unset($content['htmlOptions']);
                    } elseif ($param == 'icon') {
                        $content['eicon'] = 'fa fa-' . $value;
                        unset($content['icon']);
                    } elseif ($param == 'lat') {
                        $content['latitude'] = $value;
                        unset($content['lat']);
                    } elseif ($param == 'long') {
                        $content['longitude'] = $value;
                        unset($content['long']);
                    }
                }
            }
        }
        return $content;
    }
    public static function changeArrayParamsValue($content){

        foreach($content as $param=>$value){

            if($param=='htmlOptions'){
                $content['elink']=self::getLinkData($value);

                unset($content['htmlOptions']);

            }elseif($param=='icon'){
                $content['eicon']='fa fa-'.$value;
                unset($content['icon']);
            }elseif($param=='lat'){
                $content['latitude']=$value;
                unset($content['lat']);
            }elseif($param=='long'){
                $content['longitude']=$value;
                unset($content['long']);
            }

        }
        return $content;
    }
    public static function getLinkData($data){

        $ret=[];

        if(isset($data['type'])){
            //concocemos el tipo del enlace
            $ret['newpage']=1;
            $ret['type']=strtolower($data['type']);

            if($data['type']=='PAGE'){
                $lnk=explode('/',$data['href']);
                $indx=sizeof($lnk);

                $a=str_replace('.html','',$lnk[$indx-1]);

               $mod=ContentLang::find()->where('link_rewrite LIKE "'.$a.'"')->one();
                $ret['page']=$mod->id_content;
            }else{
                $ret['page']='';
            }

            $ret['url']=$data['href'];

        }else{
            //no conocemos el tipo del enlace
        }

        return $ret;
    }

} 