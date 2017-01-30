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


namespace common\widgets;

use yii\base\Widget;
use common\components\Html;
use yii\web\HttpException;


class Elink extends Widget {

    public $linkData=false;

    /**
     * @var array Html options passed to link element
     */
    public $htmlOptions=[];
    /**
     * Starts capturing an output to be cleaned from whitespace characters between HTML tags.
     */
    public function init()
    {
        if(!$this->linkData)
            throw new HttpException(500,"Elink widget requires link data");


        ob_start();
        ob_implicit_flush(false);
    }

    /**
     * If link has not been defined return clean data
     * @return string html
     */
    public function run()
    {
        if($this->linkData->type!="none")
            return Html::elink(ob_get_clean(),$this->linkData,$this->htmlOptions);
            return ob_get_clean();

    }

}