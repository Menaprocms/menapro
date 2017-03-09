<?php use common\components\Html;

$msgs = [
    "select_a_tag" => Yii::t('blocks/news', 'Select a tag'),
    "latest" => Yii::t('blocks/news', 'LATEST'),
    "by_tag" => Yii::t('blocks/news', 'BY TAG')
];
$this->registerJs("var news_lang=" . json_encode($msgs) . ";", $this::POS_END, "news_lang");
?>
<div id="proBox-news">
    <div class="proBoxTitle">
        <span id="newsTitle" class=""><?php echo Yii::t('blocks/news', 'News');?></span>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label for="news_nposts"><?php echo Yii::t('blocks/news', 'Number of posts');?></label>
                <select id="news_nposts" class="news_nposts form-control">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="news_type"><?php echo Yii::t('blocks/news', 'Block type');?></label>
                <select id="news_type" class="news_type form-control">
                    <option value="1"><?php echo Yii::t('blocks/news', 'Latest posts');?></option>
                    <option value="2"><?php echo Yii::t('blocks/news', 'Post by tag');?></option>
                </select>
            </div>
        </div>
        <div class="col-sm-4">
            <div id="news_tag_container" class="form-group oculto">
                <label for="news_tag"><?php echo Yii::t('blocks/news', 'Tag');?></label>
                <select id="news_tag" class="news_tag form-control">

                </select>
            </div>
        </div>

    </div>
    <hr>
    <div class="row eRow saveButtonsRow">
        <span class="btn btn-danger toTrash"><i class="fa fa-trash"></i></span>
        <span class="btn btn-success pull-right proBox-save" id="save-news"><i class="fa fa-2x fa-check"></i></span>
    </div>
</div>
