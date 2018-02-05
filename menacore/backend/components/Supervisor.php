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


namespace backend\components;


use yii\base\Component;
use yii;

/**
 * Class Supervisor - Checks common security parameters
 * @package backend\components
 */
final class Supervisor extends Component
{


    private $messages = [];

    const SUCCESS = 0;
    const INFO = 1;
    const WARNING = 2;
    const DANGER = 3;

    private $htaccessSize;

    private $protectedDirs = [];
    private $apacheModules=[];

    public function init()
    {
        $this->protectedDirs = [
            Yii::getAlias('@menacore'),
            Yii::getAlias('@blocks'),
            Yii::getAlias('@themes'),
            Yii::getAlias('@menaBase').DIRECTORY_SEPARATOR."config",
            Yii::getAlias('@menaBase').DIRECTORY_SEPARATOR."mail",
            Yii::getAlias('@menaBase').DIRECTORY_SEPARATOR."thumbs",
            Yii::getAlias('@menaBase').DIRECTORY_SEPARATOR."tools",
            Yii::getAlias('@menaBase').DIRECTORY_SEPARATOR."public_assets",

        ];
    }

    public function supervise($repair=false)
    {

        if ($this->dummyHtaccess()) {
            foreach ($this->protectedDirs as $dir) {
                $this->checkFolder($dir,$repair);
            }

        }
        if (version_compare(PHP_VERSION, '5.6.0') < 0) {
            $this->msg(self::INFO,"Your <b>PHP version is outdated</b>."," Contact your hosting provider in order to update. (PHP Version: ".PHP_VERSION.")");
        }

        krsort($this->messages);
       return $this->messages;
    }


    private function apache_module_exists($module)
    {
        if(count($this->apacheModules)==0)
        {
         $this->apacheModules=apache_get_modules();
        }
        return in_array($module,$this->apacheModules);
    }

    private function checkFolder($path,$repair=false)
    {

        $sizeComparison = $this->htaccessSize ? true : false;

        if (is_dir($path)) {
            if (file_exists($path .DIRECTORY_SEPARATOR. "index.php")) {
                $this->msg(self::INFO, dirname($path), "In " . $this->short($path));
            }

            if (!file_exists($path .DIRECTORY_SEPARATOR. ".htaccess")) {
                if($repair)
                {
                    $this->createHtaccess($path);
                    $this->checkFolder($path,false);
                    return;
                }

                $this->msg(self::DANGER, "htaccess is not present", $this->short($path));

            } else {
                if($sizeComparison){
                    if (filesize($path .DIRECTORY_SEPARATOR. ".htaccess") != $this->htaccessSize) {
                        if($repair)
                        {
                            $this->createHtaccess($path);
                            $this->checkFolder($path,false);
                        }else
                        {
                            $this->msg(self::WARNING, ".htaccess different", "The file is different than expected. In folder: " . $this->short($path));
                        }


                        return;
                    }
                }

                $this->msg(self::SUCCESS,"Folder ".$this->short($path)." is protected");
            }


        }

    }

    private function short($path)
    {

        $path= preg_replace("/menacore[A-Za-z0-9]{1,8}/","menacoreXXXX",$path);
        return str_replace($_SERVER['DOCUMENT_ROOT'],"",$path);
    }


    private function apacheVersion()
    {
        if(function_exists('apache_get_version'))
        {
            $matches = [];
            if (preg_match("/Apache\/(\d\.\d)/", \apache_get_version(), $matches)) {
                return floatval($matches[1]);
            }
        }

        if(isset(Yii::$app->params['apache_version']))
        {
            $this->msg(self::WARNING, "Overrided Apache version", "MenaPro could not get apache version from server but there was a version value defined in params.");

            return floatval(Yii::$app->params['apache_version']);

        }


        $this->msg(self::DANGER, "Unrecognized Apache version", "MenaPro could not get apache version. Supervision of htaccess will be omitted.");
        return false;
    }

    private function dummyHtaccess()
    {
        $dummyPath = Yii::getAlias('@runtime') . DIRECTORY_SEPARATOR;
        if ($this->createHtaccess($dummyPath)) {
            if ($this->htaccessSize = filesize($dummyPath . ".htaccess")) {
                return true;
            } else {
                $this->msg(self::WARNING, "Dummy htaccess error", "Could not get filesize of dummy htaccess. Htaccess file size comparation will not work");
            }

        }
        return false;
    }


    /**
     * Since apache 2.4 Allow,Deny is deprecated.
     * @param $path string path to folder
     * @return bool success creating
     */
    private function createHtaccess($path)
    {
//        $this->msg(self::INFO,"Creating htaccess","in ".$path);
        $allowedExtensions=[
            'css',
            'js',
            'json',
            'jpeg',
            'jpg',
            'png',
            'svg',
            'gif',
            'less',
            'mov',
            'flv',
            'wav',
            'mp3',
            'mp4',
            'mpeg',
            'mpg',
            'eot',
            'woff',
            'woff2',
            'ttf',
            'otf',
            'bm'
        ];
        if ($version = $this->apacheVersion()) {
            if ($version >= 2.4) {
                $content = '
Require all denied
<FilesMatch ".*(?i)\.('.implode("|",$allowedExtensions).')$">
Require all granted
</FilesMatch>';
            } else {
                $content = '

Deny from all
<FilesMatch ".*(?i)\.('.implode("|",$allowedExtensions).')$">
Order Allow,Deny
Allow from all
</FilesMatch>';
            }

        }else
        {
            return false;
        }

        try {
            if (file_put_contents($path . DIRECTORY_SEPARATOR . ".htaccess", $content) === false) {
                $this->msg(self::DANGER, "ERROR writing htaccess", "Could not write htaccess in " . $this->short($path));
            } else {
//                chmod($path, 0755);
                return true;
            }
        } catch (\Exception $e) {
            $this->msg(self::DANGER, "ERROR creating htaccess", "Path:" . $this->short($path) . " Error message: " . $e->getMessage());
            return false;

        }

        return false;
    }

    private function msg($severity = self::INFO, $title, $description = "")
    {
        $this->messages[$severity][] = ['title' => $title, 'description' => $description];
    }

}
