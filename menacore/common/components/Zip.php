<?php
/**
 * Created by MenaPRO.
 * User: XNX-PTL
 * Date: 25/10/2015
 * Time: 11:01
 */

namespace common\components;
use Yii;
use yii\helpers\Url;
use yii\base\Component;
use common\components\Pclzip;

class Zip extends Component
{
    public static $zip;

    public static function getfiles($from_file, $to_dir)
    {

        $zip = new PclZip($from_file);

        if ($zip->properties() == 0) {
            die("Error : ".$zip->errorInfo(true));
        }
        $list = $zip->extract(PCLZIP_OPT_PATH, $to_dir, PCLZIP_OPT_REPLACE_NEWER);
        return $list;
    }
}