<?php
/**
 * Created by PhpStorm.
 * User: silvia
 * Date: 01/03/2017
 * Time: 12:56
 *
**/
use yii\widgets\Pjax;
use yii\grid\GridView;
use common\components\Html;
?>
<div class="row">
    <div class="col-sm-12">
        <span id="news-new-tag" class="btn btn-success pull-right"><i class="fa fa-plus newtagbtnitem"></i> <span class="newtagbtnitem"><?php echo Yii::t('app', 'New tag');?></span></span>
    </div>
</div>
<div id="showtags">
  <?php
  //$tagdataProvider
  echo $this->render('tagGridView',[
      'tagdataProvider'=>$tagdataProvider
  ])
  ?>
</div>
<div id="edittag" class="tagForm oculto">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="news_tag_name"><?php echo Yii::t('app', 'Name');?></label>
                <input type="text"  maxlength="128" id="news_tag_name" class="form-control">
                <span class="mn_tip"><?php echo Yii::t('app','Max. 128 characters');?></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="news_tag_friendly"><?php echo Yii::t('app', 'Friendly url');?></label>
                <input type="text"  maxlength="128" id="news_tag_friendly" class="form-control mn_ajax" data-action="news/checktagurl" data-callback="news.cb_tagFriendlyUrl(data);">
                <span class="mn_tip"><?php echo Yii::t('app','Max. 128 characters');?></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="news_tag_description"><?php echo Yii::t('app', 'Description');?></label>
                <textarea id="news_tag_description"  maxlength="256" class="form-control" cols="10" rows="5"></textarea>
                <span class="mn_tip"><?php echo Yii::t('app','Max. 256 characters');?></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <span id="news-save-tag" class="btn btn-success pull-right"><i class="fa fa-2x fa-save"></i></span>
        </div>
    </div>
</div>
