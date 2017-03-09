<?php
/**
 * Created by PhpStorm.
 * User: silvia
 * Date: 09/03/2017
 * Time: 9:20
 */
namespace frontend\components;

use skeeks\yii2\assetsAuto\AssetsAutoCompressComponent;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\FileHelper;

class CustomAssetsAutoCompressComponent extends AssetsAutoCompressComponent
{
    /**
     * @param array $files
     * @return array
     */
    protected function _processingJsFiles($files = [])
    {
        $fileName   =  md5( implode(array_keys($files)) . $this->getSettingsHash()) . '.js';
        $publicUrl  = \Yii::getAlias('@web/public_assets/js-compress/' . $fileName);

        $rootDir    = \Yii::getAlias('@webroot/public_assets/js-compress');
        $rootUrl    = $rootDir . '/' . $fileName;

        if (file_exists($rootUrl))
        {
            $resultFiles        = [];

            foreach ($files as $fileCode => $fileTag)
            {
                if (!Url::isRelative($fileCode))
                {
                    $resultFiles[$fileCode] = $fileTag;
                } else
                {
                    if ($this->jsFileRemouteCompile)
                    {
                        $resultFiles[$fileCode] = $fileTag;
                    }
                }
            }

            $publicUrl                  = $publicUrl . "?v=" . filemtime($rootUrl);
            $resultFiles[$publicUrl]    = Html::jsFile($publicUrl);
            return $resultFiles;
        }

        //Reading the contents of the files
        try
        {
            $resultContent  = [];
            $resultFiles    = [];
            foreach ($files as $fileCode => $fileTag)
            {
                if (Url::isRelative($fileCode))
                {
                    $contentFile = $this->fileGetContents( Url::to(\Yii::getAlias($fileCode), true) );
                    $resultContent[] = trim($contentFile) . "\n;";;
                } else
                {
                    if ($this->jsFileRemouteCompile)
                    {
                        //Пытаемся скачать удаленный файл
                        $contentFile = $this->fileGetContents( $fileCode );
                        $resultContent[] = trim($contentFile);
                    } else
                    {
                        $resultFiles[$fileCode] = $fileTag;
                    }
                }
            }
        } catch (\Exception $e)
        {
            \Yii::error($e->getMessage(), static::className());
            return $files;
        }

        if ($resultContent)
        {
            $content = implode($resultContent, ";\n");
            if (!is_dir($rootDir))
            {
                if (!FileHelper::createDirectory($rootDir, 0777))
                {
                    return $files;
                }
            }

            if ($this->jsFileCompress)
            {
                $content = \JShrink\Minifier::minify($content, ['flaggedComments' => $this->jsFileCompressFlaggedComments]);
            }

            $page = \Yii::$app->request->absoluteUrl;
            $useFunction = function_exists('curl_init') ? 'curl extension' : 'php file_get_contents';
            $filesString = implode(', ', array_keys($files));

            \Yii::info("Create js file: {$publicUrl} from files: {$filesString} to use {$useFunction} on page '{$page}'", static::className());

            $file = fopen($rootUrl, "w");
            fwrite($file, $content);
            fclose($file);
        }


        if (file_exists($rootUrl))
        {
            $publicUrl                  = $publicUrl . "?v=" . filemtime($rootUrl);
            $resultFiles[$publicUrl]    = Html::jsFile($publicUrl);
            return $resultFiles;
        } else
        {
            return $files;
        }
    }

    /**
     * @param array $files
     * @return array
     */
    protected function _processingCssFiles($files = [])
    {
        $fileName   =  md5( implode(array_keys($files)) . $this->getSettingsHash() ) . '.css';
        $publicUrl  = \Yii::getAlias('@web/public_assets/css-compress/' . $fileName);

        $rootDir    = \Yii::getAlias('@webroot/public_assets/css-compress');
        $rootUrl    = $rootDir . '/' . $fileName;

        if (file_exists($rootUrl))
        {
            $resultFiles        = [];

            foreach ($files as $fileCode => $fileTag)
            {
                if (Url::isRelative($fileCode))
                {

                } else
                {
                    if (!$this->cssFileRemouteCompile)
                    {
                        $resultFiles[$fileCode] = $fileTag;
                    }
                }

            }

            $publicUrl                  = $publicUrl . "?v=" . filemtime($rootUrl);
            $resultFiles[$publicUrl]    = Html::cssFile($publicUrl);
            return $resultFiles;
        }

        //Reading the contents of the files
        try
        {
            $resultContent  = [];
            $resultFiles    = [];
            foreach ($files as $fileCode => $fileTag)
            {
                if (Url::isRelative($fileCode))
                {
                    $contentTmp         = trim($this->fileGetContents( Url::to(\Yii::getAlias($fileCode), true) ));

                    $fileCodeTmp = explode("/", $fileCode);
                    unset($fileCodeTmp[count($fileCodeTmp) - 1]);
                    $prependRelativePath = implode("/", $fileCodeTmp) . "/";

                    $contentTmp    = \Minify_CSS::minify($contentTmp, [
                        "prependRelativePath" => $prependRelativePath,

                        'compress'          => true,
                        'removeCharsets'    => true,
                        'preserveComments'  => true,
                    ]);

                    //$contentTmp = \CssMin::minify($contentTmp);

                    $resultContent[] = $contentTmp;
                } else
                {
                    if ($this->cssFileRemouteCompile)
                    {
                        //Пытаемся скачать удаленный файл
                        $resultContent[] = trim($this->fileGetContents( $fileCode ));
                    } else
                    {
                        $resultFiles[$fileCode] = $fileTag;
                    }
                }
            }
        } catch (\Exception $e)
        {
            \Yii::error($e->getMessage(), static::className());
            return $files;
        }

        if ($resultContent)
        {
            $content = implode($resultContent, "\n");
            if (!is_dir($rootDir))
            {
                if (!FileHelper::createDirectory($rootDir, 0777))
                {
                    return $files;
                }
            }

            if ($this->cssFileCompress)
            {
                $content = \CssMin::minify($content);
            }

            $page = \Yii::$app->request->absoluteUrl;
            $useFunction = function_exists('curl_init') ? 'curl extension' : 'php file_get_contents';
            $filesString = implode(', ', array_keys($files));

            \Yii::info("Create css file: {$publicUrl} from files: {$filesString} to use {$useFunction} on page '{$page}'", static::className());


            $file = fopen($rootUrl, "w");
            fwrite($file, $content);
            fclose($file);
        }


        if (file_exists($rootUrl))
        {
            $publicUrl                  = $publicUrl . "?v=" . filemtime($rootUrl);
            $resultFiles[$publicUrl]    = Html::cssFile($publicUrl);
            return $resultFiles;
        } else
        {
            return $files;
        }
    }
}