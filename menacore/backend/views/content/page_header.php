<?php

use common\components\Html;
use yii\widgets\ActiveForm;
use common\models\Content;
use backend\assets\ContentAsset;

/* @var $this yii\web\View */
/* @var $model common\models\Content */
/* @var $form yii\widgets\ActiveForm */
$title = Yii::t('app', 'Update {modelClass}: ', [
        'modelClass' => 'Content',
    ]) . ' ' . $model->langFields[0]->title;
?>

<div class="input-group">
    <?php echo Html::tag('span',"Title",['class'=>'input-group-addon']) ?>
    <?php echo Html::textInput("ContentLang[title]",$model->langFields[0]->title,['id'=>'contentlang-title','maxlength' => true,'class'=>'form-control mn_ajax', 'data-action'=>'content/pagetitle','data-info'=>array('id'=>$model['id']),'data-target'=>'','data-callback'=>'cb_title','data-currentval'=>$title]) ?>
    <?php echo Html::hiddenInput("Content[id]",$model->id,['id'=>'content-id']);?>
</div>
<span id="page_header_legend">

<!--            <span id="page_header_legend_published" class="hidden"> --><?php //echo Yii::t('app', 'La página se mostrará en el menú como: ')?><!--<b id="page_header_menu_text">--><?php //echo $model->langFields[0]->menu_text;?><!--</b></span>-->
<!--            <span id="page_header_legend_unpublished" class="hidden">--><?php //echo Yii::t('app', 'La página no se mostrará en el menú');?><!--</span>-->
<!--            <span id="page_header_legend_parent_unpublished" class="hidden">--><?php //echo Yii::t('app', 'La página no se mostrará en el menú porque la página padre no está publicada');?><!--</span>-->

         </span>

