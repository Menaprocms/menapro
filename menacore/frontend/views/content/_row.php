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

/**
 * @var $this yii\web\View
 * @var $columns array containing columns info and html customization
 * @var $row     object containing all data from current row
 * @var $cRow    integer Current row id
 * @var $theme   string Current theme name
 * @var $structure  object Full content structure
 */
use yii\helpers\Html;
use common\components\Tools;

$cssClasses=['row'];

$html="";
$combinationClass="";
$nbColumns=count($columns);
foreach ($columns as $cCol => $column) {
    if(trim($column->type)!="") {

        if($column->type=="splitted")
        {

            $column->content[0]->class=12;
            $sRowView=$this->render(Tools::getRowView($theme), [
                'cRow' => $cRow,
                'row'=>$row,
                'columns' => $column->content,
                'structure' => $structure,
                'theme' => $theme,
                'splitted'=>true
            ]);
            $html.=Html::tag('div',$sRowView,['class'=>'splitted col-sm-'.$column->class]);
        }else
        {

            if($column->class<12)
            {
                $combinationClass.=ucfirst($column->type).($cCol!=$nbColumns-1?"To":"");
            }else
            {
                $combinationClass="only".ucfirst($column->type);
            }

            $html.= $this->render(Tools::getColumnView($theme), [
                'cRow' => $cRow,
                'column' => $column,
                'structure' => $structure,
                'theme' => $theme,
                'cCol' => $cCol
            ]);
        }

    }else {
        $html.=Html::tag('div',"",['class'=>'col-sm-'.$column->class]);
    }
}

//Row styles
if(!isset($splitted))
{
    if (isset($row->htmlOptions->rowClass))
        $cssClasses[] = $row->htmlOptions->rowClass;


    $cssClasses[] = $combinationClass;
}

echo Html::tag('div',$html,['class'=>$cssClasses]);



