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



namespace frontend\assets;
use yii\web\AssetBundle;

/**
 * Class ContentAsset using Bootstrap 3
 * @package frontend\assets
 */
class NewspreviewAsset extends AssetBundle
{
    public $basePath = '@frontend';
    public $baseUrl = '@web';
    public $css = [

        'css/content/common.css',
//        'css/menu.css',
        'css/content/font-awesome-4.6.3/css/font-awesome.min.css',
    ];

    public $depends = [
        'frontend\assets\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
