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

/* @var $this yii\web\View
 * @var $model common\models\Content
 * @var $proCmsData object  Content decoded
 * @var $userLang   integer Id of current user lang.
 * @var $structure  array   Current lang content decoded
 * @var $cRow       integer Current row number
 * @var $theme      string  Current theme name
 */
use common\components\Tools;

$structure = $proCmsData->structure[$userLang];
$theme=$proCmsData->theme;
foreach ($structure as $cRow => $row) {
    if(isset($row->content)) {


        echo $this->render(Tools::getRowView($theme), [
            'cRow' => $cRow,
            'row'=>$row,
            'columns' => $row->content,
            'structure' => $structure,
            'theme' => $theme
        ]);
    }
}
