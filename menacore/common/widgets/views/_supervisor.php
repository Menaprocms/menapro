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

use common\components\Html;
use yii\web\View;


$br="<br>";
$opt='';
$i=Html::tag('i','',['class'=>'glyphicon glyphicon-chevron-left']);

$content=[
    Html::tag('span',Html::tag('i',"",['class'=>'fa fa-refresh']),['class'=>'btn btn-default mn_ajax','data-action'=>'content/supervisor','data-info'=>[],'data-callback'=>'cb_supervisor','data-target'=>'#supervisor_results']),
    Html::a($i.Yii::t('app', 'Back'),'#',['class'=>'btn btn-info pull-right btnAtras']),
    $br,
    $br,
    Html::beginTag('div',['id'=>'supervisor_results'])
];
$content[]=$this->render("_supervisor_results",["messages"=>$messages]);

$content[]=Html::endTag('div');

$content[]=Html::tag('p',Yii::t('app', 'Those are the results of a simple security supervision. Server configuration and conectivity is not tested. This information is not guaranteed. '),['class'=>'small']);
$wrapper=Html::tag('div',implode("",$content),['class'=>"mn_panel_wrapper"]);
$div1=Html::tag('div',$wrapper,['class'=>'mn_scrollbar sp_with_scrollbar']);
$div=Html::tag('div',$div1,['class'=>'hidden sub_panel withScrollbar','title'=>Yii::t('app', 'Languages'),'id'=>'sp_supervisor']);

echo $div;