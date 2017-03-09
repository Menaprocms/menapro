<?php
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use common\models\Post;
use common\models\Tag;
use common\components\Html;
use yii\widgets\Pjax;

//
//$postdataProvider = new ActiveDataProvider([
//    'query' => Post::find()->where(['id_lang'=>Yii::$app->session['_worklang']]),
//    'pagination' => [
//        'pageSize' => 20,
//    ],
//]);
//$tagdataProvider = new ActiveDataProvider([
//    'query' => Tag::find()->where(['id_lang'=>Yii::$app->session['_worklang']]),
//    'pagination' => [
//        'pageSize' => 20,
//    ],
//]);
?>
<div id="newsconfig_panel">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="container-fluid">
                    <div id="newstabs">
                        <ul>
                            <li><a href="#news_editpost_tab"><?php echo Yii::t('app', 'Post Edition');?></a></li>
                            <li><a href="#news_post_tab"><?php echo Yii::t('app', 'Posts');?></a></li>
                            <li><a href="#news_tag_tab"><?php echo Yii::t('app', 'Tags');?></a></li>
                        </ul>
                        <div id="news_editpost_tab">
                            <div class="panel panel-default">
                                <div class="panel panel-heading">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div id="post_ok" class="alert alert-success post_message oculto">
                                                <?php echo Yii::t('app', 'Post succesfully procesed.');?>
                                            </div>
                                            <div id="post_ko" class="alert alert-danger post_message oculto">
                                                <?php echo Yii::t('app', 'Error procesing post.');?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="news_post_title"><?php echo Yii::t('app', 'Title');?></label>
                                                <input type="text" maxlength="128" id="news_post_title" class="news_post_title form-control">
                                                <span class="mn_tip"><?php echo Yii::t('app','Max. 128 characters');?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label for="news_post_friendly"><?php echo Yii::t('app', 'Friendly url');?></label>
                                                <input type="text"  maxlength="128" id="news_post_friendly" class="news_post_friendly form-control mn_ajax" data-action="news/checkposturl" data-callback="news.cb_postFriendlyUrl(data);">
                                                <span class="mn_tip"><?php echo Yii::t('app','Max. 128 characters');?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="post_published_cont pull-right"><?php echo Yii::t('app', 'Published');?>
                                                <div class="onoffswitch">
                                                    <input type="checkbox" id="news_post_published" class="onoffswitch-checkbox" name="onoffswitch">
                                                    <label class="onoffswitch-label" for="news_post_published"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel panel-body">
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <div id="news_text_editor">

                                            </div>
                                        </div>
                                        <div class="col-sm-3 news_editor_options">
                                            <div id="news_tinymenu"></div>
                                            <ul class="news_editor-formats">
                                                <li data-format="H1" data-selector="H1" data-group="common"><h1><?php echo Yii::t('app','Title'); ?> </h1></li>
                                                <li data-format="H2" data-selector="H2" data-group="common"><h2><?php echo Yii::t('app','SubTitle'); ?></h2></li>
                                                <li data-format="H3" data-selector="H3" data-group="common"><h3><?php echo Yii::t('app','Chapter'); ?></h3></li>
                                                <li data-format="H4" data-selector="H4" data-group="common"><h4><?php echo Yii::t('app','SubChapter'); ?></h4></li>
                                                <li data-format="H5" data-selector="H5" data-group="common"><h5><?php echo Yii::t('app','Part'); ?></h5></li>
                                                <li data-format="H6" data-selector="H6" data-group="common"><h6><?php echo Yii::t('app','SubPart'); ?></h6></li>
                                                <li data-format="P" data-selector="P"  data-group="common"><p><?php echo Yii::t('app','Paragraph'); ?></p></li>
                                                <li data-format="BLOCKQUOTE" data-selector="BLOCKQUOTE"  data-group="common"><p><?php echo Yii::t('app','Blockquote'); ?></p></li>
                                            </ul>
                                            <ul class="news_editor-formats" data-group="headers">
                                                <li data-format="insertunorderedlist" data-selector="UL"  data-group="list"><?php echo Yii::t('app','Unordered list'); ?></li>
                                                <li data-format="insertorderedlist" data-selector="OL"  data-group="list"><?php echo Yii::t('app','Ordered list'); ?></li>
                                            </ul>

                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-footer">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label for="news_post_tags"><?php echo Yii::t('app', 'Tags');?></label>
                                                <textarea  id="news_post_tags" rows="1" cols="50"></textarea>
                                             </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <span id="news-save-post" class="btn btn-success pull-right"><i class="fa fa-2x fa-save"></i></span>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div id="news_post_tab"></div>
                        <div id="news_tag_tab"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>