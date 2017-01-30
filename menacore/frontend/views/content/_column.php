<?php
/*
* 2016-2016 MenaPro 1.0.0
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
* needs please refer to http://www.menapro.com for more information.
*
*  @author Xenon media Burgos <contact25@menapro.com>
*  @copyright  2016-2016 Xenon Media
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*
*  Proudly made in Burgos, Spain. 
*
*/

use common\components\Tools;
use yii\helpers\Html;

echo Html::beginTag('div',['class'=>"col-sm-".$column->class]);
if ($column->type == "splitted") {
    foreach ($column->content as $subColKey => $subColumn) {
        $scContent = Tools::blockHasViewMethod($column->type, ['col'=>$subColumn,'subCol' => $subColKey, 'cCol' => $cCol, 'cRow' => $cRow, 'theme' => $theme]);

        if (!$scContent) {
            $html[] = Html::tag('div', $this->render(Tools::getBlockView($column->type, $theme),['col'=>$column,'subCol' => $subColKey, 'cCol' => $cCol, 'cRow' => $cRow, 'theme' => $theme]), ['class' => 'row']);
        }else{
            $html[]=$scContent;
        }
    }
} else {
    $cContent = Tools::blockHasViewMethod($column->type, ['col' => $column, 'cCol' => $cCol, 'cRow' => $cRow, 'theme' => $theme]);

    if (!$cContent) {
        $html[] = $this->render(Tools::getBlockView($column->type, $theme), ['col' => $column, 'cCol' => $cCol, 'cRow' => $cRow, 'theme' => $theme]);
    }else{
        $html[]=$cContent;
    }

}
echo implode("",$html);

echo Html::endTag('div');
