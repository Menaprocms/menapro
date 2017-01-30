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


use yii\helpers\Html;
use common\models\Configuration;
$br="<br>";
$hr="<hr>";
$opt='';
$opt.=$hr.Html::a( Html::tag('i',"",['class'=>'fa fa-newspaper-o']) ." ".Yii::t('app', 'Get more themes'),"http://menapro.com",['class'=>'btn btn-info','target'=>"_blank"]).$hr;
$i=Html::tag('i','',['class'=>'glyphicon glyphicon-chevron-left']);
$iplus=Html::tag('i','',['class'=>'glyphicon glyphicon-plus']);
$icon=Html::tag('i','',['class'=>'glyphicon glyphicon-trash']);

$btnAtras=[
    Html::a($i.Yii::t('app', 'Back'),'#',['class'=>'btn btn-default pull-right btnAtras']),
    $br,
    $br
];
$btnAdd=Html::a($iplus.Yii::t('app', 'Add theme'),'#',['id'=>'btnAddTheme','class'=>'btn btn-default pull-left']);
$opt.=$btnAdd;
$opt.= implode("",$btnAtras);

$uploadThemeForm=[
    Html::tag('i','',['class'=>'fa fa-spinner hidden fa-2x','id'=>'loader_theme']),
    Html::beginForm(['configuration/addtheme'],'post',['enctype'=>'multipart/form-data']),
    Html::fileInput('themeFile',''),
    Html::hiddenInput('name','theme'),
    Html::endForm()
];
$dv=Html::tag('div',implode("",$uploadThemeForm),['id'=>'upload_theme_form','class'=>'hidden']);
$opt.= $dv;


$opt.=Html::ul($themes,[
    'name'=>'config',
    'class'=>'list-group',
    'item'=>function($item,$index) use($icon){
        $label=Html::label($item,'general_theme_'.$index,['class'=>'theme_label']);
        $callback_del="$('#general_theme_".$index."').closest('li').remove();";
        $ba="if (confirm('Do you really want to delete this theme?')) {
                $(this).prev('span.text').remove();
                    return true;
                }else{
                    return false;
                }";
        $b=Html::tag('span',$icon,['class'=>'mn_ajax btn btn-xs btn-danger','data-action'=>'configuration/deletetheme','data-info'=>array('id'=>$index),'data-callback'=>$callback_del,'data-target'=>'','data-beforesend'=>$ba]);
        $img=file_exists(Yii::getAlias("@menaThemes/").$item."/thumbnail.png")?Html::img(Yii::getAlias("@menaBasePublic/themes/").$item."/thumbnail.png",['class'=>'img-responsive']):"";

        $html= Html::tag('li',
            Html::radio('select-theme',$index==Configuration::getValue('_DEFAULT_THEME_')?true:false,
                [
                    'id'=>'general_theme_'.$index,
                    'value'=>$index,
                    'class'=>'mn_ajax',
                    'data-action'=>'configuration/generaltheme',
                    'data-info'=>'',
                    'data-callback'=>'cb_general_theme',
                    'data-target'=>''
                ] //Radio html options
            ).$label.$b.$img
            ,
            ['class'=>'list-group-item']);
        return $html;
    }
]);
$content=[
    Html::tag('div',Yii::t('app', 'Web theme'),['class'=>'mn_subpanel_title']),
    $opt
];
$wrapper=Html::tag('div',implode("",$content),['class'=>"mn_panel_wrapper"]);
$div1=Html::tag('div',$wrapper,['class'=>'mn_scrollbar sp_with_scrollbar']);
$div=Html::tag('div',$div1,['class'=>'hidden sub_panel ','title'=>Yii::t('app', 'Theme'),'id'=>'sp_general_theme']);

echo $div;