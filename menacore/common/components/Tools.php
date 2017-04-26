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
use common\models\Configuration;


class Tools extends Component
{
    public static $errors = false;


    public static function getColumnView($theme)
    {
        $route = Yii::getAlias('@menaThemes/') . $theme . '/views/content/_column';
        if (file_exists($route . ".php")) {
            return "_column";
        } else {

            return '@frontend/views/content/_column.php';
        }
    }


    /**
     * Prevents themes view fallback
     * @param $theme
     * @return string
     */
    public static function getRowView($theme)
    {
        $route = Yii::getAlias('@menaThemes/') . $theme . '/views/content/_row';
        if (file_exists($route . ".php")) {
            return '_row';
        } else {
            return '@frontend/views/content/_row.php';
        }
    }

    public static function getBlockView($blockname, $theme, $view=null)
    {
        $viewName=$view==null?$blockname:$view;

        $overrideRoute = Yii::getAlias('@menaThemes') . "/" . $theme . '/blocks/' . $blockname . '/views/' . $viewName . '.php';
        if (file_exists($overrideRoute)) {
            return "@menaThemes/".$theme.'/blocks/'.$blockname.'/views/'.$viewName;
        } else {
            return '@blocks/' . $blockname . '/frontend/views/' . $viewName;
        }

    }

    public static function blockHasViewMethod($blockName, $info)
    {
        $name=$blockName.'module';
        $class = '\blocks\\' . $blockName . '\frontend\\'.$name.'\\'.ucfirst($blockName);
        if (file_exists(Yii::getAlias('@blocks/'). $blockName . '/frontend/'.$name.'/'.ucfirst($blockName). '.php')) {
            $contr_class='\blocks\\' . $blockName . '\frontend\\'.$name.'\\controllers\\'.ucfirst($blockName).'Controller';
            if (file_exists(Yii::getAlias('@blocks/'). $blockName . '/frontend/'.$name.'/controllers/'.ucfirst($blockName).'Controller.php')) {
                $reflection = new \ReflectionClass($contr_class);
                if ($reflection->hasMethod('actionRenderview')) {
                    Yii::$app->setModule($name, ['class' => $class]);
                    $module = Yii::$app->getModule($name);
                    return $module->runAction($blockName.'/renderview', ['col'=>$info['col'],'cRow'=>$info['cRow'],'cCol'=>$info['cCol'],'containerClass'=>isset($info['class'])?$info['class']:'']);
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }


    public static function ZipExtract($from_file, $to_dir)
    {
        if (!file_exists($to_dir))
            yii\helpers\FileHelper::createDirectory($to_dir,0755);
        
        if (class_exists('ZipArchive', false)) {
            $zip = new \ZipArchive;
            chmod($to_dir, 0777);

            if ($zip->open($from_file) === true && $zip->extractTo($to_dir)) {
                $files = array();
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $files[] = $zip->getNameIndex($i);
                }

                if ($zip->close()) {
                    return true;
                }
            }

            return false;
        } else {
            //Incluimos la libreria
            $class = Yii::getAlias('@menaBase') . '/tools/pclzip/pclzip.lib.php';

            include($class);
            //forma de llamar la clase
            $zip = new \PclZip($from_file);

            //Ejecutamos la funcion extract
            if ($zip->extract(PCLZIP_OPT_PATH, $to_dir) == 0) {
                return false;
//                die("Error : ".$zip->errorInfo(true));
            } else {
                return true;
            }
        }

    }

    public static function recurse_copy($source, $dest)
    {
        if (is_dir($source)) {
            $dir_handle = opendir($source);
            while ($file = readdir($dir_handle)) {
                if ($file != "." && $file != "..") {
                    if (is_dir($source . "/" . $file)) {
                        if (!is_dir($dest . "/" . $file)) {
                            mkdir($dest . "/" . $file);
                        }
                        self::recurse_copy($source . "/" . $file, $dest . "/" . $file);
                    } else {
                        chmod($source . "/" . $file, 0777);
                        if (!copy($source . "/" . $file, $dest . "/" . $file)) {
                            return false;
                        } else {
                            chmod($dest . "/" . $file, 0777);

                        }

                    }
                }
            }
            closedir($dir_handle);
            return true;
        } else {
            chmod($source, 0777);
            if (!copy($source, $dest)) {
                return false;
            } else {
                chmod($dest, 0777);
                return true;
            }

        }

    }

    public static function format_uri( $string, $separator = '-' )
    {
        $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
        $special_cases = array( '&' => 'and', "'" => '');
        $string = mb_strtolower( trim( $string ), 'UTF-8' );
        $string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
        $string = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
        $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
        $string = preg_replace("/[$separator]+/u", "$separator", $string);
        return $string;
    }

    public static function delete_directory($dirname)
    {
        if (is_dir($dirname)) {
            @chmod($dirname, 0777);
            $dir_handle = opendir($dirname);
        }
        if (!$dir_handle)
            return false;
        while ($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($dirname . "/" . $file)) {

                    @chmod($dirname . "/" . $file, 0777);
                    if (!@unlink($dirname . "/" . $file)) {
                        return false;
                    }
                } else {
                    self::delete_directory($dirname . '/' . $file);
                }
            }
        }
        closedir($dir_handle);
        if (!@rmdir($dirname)) {
            return false;
        } else {
            return true;
        }

    }

    /**
     * If htaccess is not present
     * @return bool
     */
    public static function checkHtaccess()
    {
        $path = Yii::getAlias('@menaBase') . '/.htaccess';
        $common_text = "
            # ~~start~~ Do not remove this comment, MenaPRO will keep automatically the code outside this comment when .htaccess will be generated again
            # .htaccess automaticaly generated by MenaPRO
            # http://www.menapro.com\n\n

            <IfModule mod_deflate.c>
            AddOutputFilterByType DEFLATE text/plain
            AddOutputFilterByType DEFLATE text/html
            AddOutputFilterByType DEFLATE text/xml
            AddOutputFilterByType DEFLATE text/css
            AddOutputFilterByType DEFLATE text/javascript
            AddOutputFilterByType DEFLATE application/xml
            AddOutputFilterByType DEFLATE application/xhtml+xml
            AddOutputFilterByType DEFLATE application/rss+xml
            AddOutputFilterByType DEFLATE application/atom_xml
            AddOutputFilterByType DEFLATE application/javascript
            AddOutputFilterByType DEFLATE application/x-javascript
            AddOutputFilterByType DEFLATE application/x-shockwave-flash
            </IfModule>
            \n\n
            RewriteEngine on
            RewriteCond %{REQUEST_URI} \.(gif|jpg|jpeg|png)$ [NC]
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^.*$ img/404.jpg [L]
            # If a directory or a file exists, use it directly
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            # Otherwise forward it to index.php
            RewriteRule . index.php
            \n\n
            # ~~end~~ Do not remove this comment, MenaPRO will keep automatically the code outside this comment when .htaccess will be generated again";

        if (file_exists($path)) {
            //@todo: Search start and end and replace text between tags
        } else {
            $text = $common_text;
            try {

                if (file_put_contents($path, $text) == false) {
                    throw new \HttpRuntimeException("Can´t create htaccess");
//                die('<div class="alert alert-danger">FATAL ERROR: CAN´T CREATE .htaccess file </div>');
                } else {
                    chmod($path, 0755);
                    return true;
                }
            } catch (\Exception $e) {
                throw new \HttpRuntimeException("Error while processing htaccess");

            }
        }
    }
    public static function isDarkColor($hexcode){
        $hex = str_replace('#','',$hexcode); //Bg color in hex, without any prefixing #!

        //break up the color in its RGB components
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));

        //do simple weighted avarage
        //
        //(This might be overly simplistic as different colors are perceived
        // differently. That is a green of 128 might be brighter than a red of 128.
        // But as long as it's just about picking a white or black text color...)
        if($r + $g + $b > 500){//382
            //bright color, use dark font
            return false;
        }else{
            //dark color, use bright font
            return true;
        }
    }

}
