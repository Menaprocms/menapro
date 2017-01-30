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


class ProcmsCommon extends Component
{

    public static function decodeMedinaPro($data)
    {
        $cms_content = base64_decode($data);

        $cms_content = utf8_encode($cms_content);
        $cms_content = urldecode($cms_content);
        $decoded_cms = json_decode($cms_content);




        return $decoded_cms;
    }

    public static function cleanMedinaPro(&$jsonObject)
    {

        foreach ($jsonObject->structure as $k => $v) {
            if ($jsonObject->structure != null && sizeof($jsonObject->structure[$k]) > 0) {
                foreach ($jsonObject->structure[$k] as $row => $rv) {
                    if ($jsonObject->structure[$k][$row] != null && sizeof($jsonObject->structure[$k][$row]) > 0) {
                        if (isset($jsonObject->structure[$k][$row]->content) && sizeof($jsonObject->structure[$k][$row]->content) > 0) {
                            $empty = true;
                            foreach ($jsonObject->structure[$k][$row]->content as $col => $cv) {
                                if (sizeof($jsonObject->structure[$k][$row]->content[$col]->content) > 0 && $jsonObject->structure[$k][$row]->content[$col]->content != "") {
                                    $empty = false;
                                }
                            }

                            if ($empty) {
                                unset($jsonObject->structure[$k][$row]);
                            }
                        }
                    }
                }
            }

        }
        return $jsonObject;
    }


} 