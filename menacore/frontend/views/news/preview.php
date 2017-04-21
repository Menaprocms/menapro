<?php
/**
 * Created by PhpStorm.
 * User: silvia
 * Date: 19/04/2017
 * Time: 14:34
 */
use common\components\Html;
if($post) {
    $this->title = $post->title;
}
?>
<div class="row">
  <div class="col-sm-12">
      <div class="eSinglePost">
          <?php if($post){
              ?>
              <h4><?php echo $post->title;?></h4>
              <div class="ePostContent">
                  <?php echo $post->content;?>
              </div>
          <?php
          }else{
              echo Html::tag('div',Yii::t('app', 'The requested post does not exists.',['class'=>'alert alert-warning']));
          } ?>

      </div>
  </div>
</div>
