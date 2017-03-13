<?php
use frontend\controllers\NewsController;
use yii\helpers\StringHelper;
use common\components\Html;

$postpage=false;
$renderpost=false;
if(Yii::$app->params['postpage']){

    $postpage=true;
    $postR=NewsController::getSinglePost(Yii::$app->params['postpage']);
    if(Yii::$app->params['islatestpost']){
        if($col->content->type==1){

            $renderpost = true;
            Yii::$app->params['renderedpostpage']=true;
        }
    }else{

        if($col->content->type==2 && $col->content->tag!=0 && !Yii::$app->params['renderedpostpage']) {
            foreach ($postR->tags as $k => $t) {
                if ($t->id == $col->content->tag) {
                    $renderpost = true;
                    Yii::$app->params['renderedpostpage']=true;
                }
            }
        }
    }

}
?>
<div class="eNewsBlock">
    <?php if($postpage && $renderpost){
        ?>
       <div class="eSinglePost">
           <?php if($postR){
               ?>
               <h4><?php echo $postR->title;?></h4>
               <div class="ePostContent">
                   <?php echo $postR->content;?>
               </div>
           <?php
           }else{
                echo Html::tag('div',Yii::t('blocks/news', 'The requested post does not exists.',['class'=>'alert alert-warning']));
           } ?>

       </div>
    <?php }else{
        if($col->content->type==1 || ($col->content->type==2 && $col->content->tag!=0)) {
            $posts = NewsController::getPosts($col->content->nposts, $col->content->type, $col->content->tag);
        }else{
            $posts = [];
        }
        ?>
    <div class="<?php echo ($col->content->type==1?'eLatestposts':'eTagposts');?>">

            <ul class="news_posts list-group">
                <?php
                    foreach($posts as $k=>$post){
                        ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-4">
                                <?php
                                preg_match( '/src\s*=\s*"(.+?)"/', $post->content, $image );
                                $cont_to_show=$post->content;
                                if(sizeof($image)==0){
                                    $image='img/noimage.jpg';
                                }else{
                                    $image=$image[0];
                                    $image=str_replace('"','',$image);
                                    $image=str_replace('src=','',$image);
                                }
                                echo Html::img(Html::thumbnail($image,200,200));
                                ?>
                                </div>
                                <div class="col-md-8">
                                    <?php $date=trim(substr($post->date_add,0,10)); ?>
                                    <p class="pull-right"><?php echo Yii::$app->formatter->asDate($date,'long'); ?></p>
                                    <h3><?php echo $post->title;?></h3>
                                    <div class="post_content">
                                        <?php echo StringHelper::truncateWords(strip_tags($cont_to_show),21,'...',null,true);?>
                                    </div>
                                    <?php
                                    $postUrl= Yii::$app->request->getUrl();
                                    if($postpage){
                                        $postUrl=str_replace($postR->friendly_url,'',$postUrl);
                                        $postUrl=str_replace('.html',$post->friendly_url.'.html',$postUrl);

                                    }else{
                                        $postUrl=str_replace('.html','/'.$post->friendly_url.'.html',$postUrl);
                                    }
                                    if($col->content->type==1){
                                        if(strpos($postUrl,'?')===false){
                                            $postUrl.='?latest=1';
                                        }else{
                                            $postUrl.='&latest=1';
                                        }
                                    }else{
                                        if(strpos($postUrl,'?latest=1')===false){
                                            $postUrl=str_replace('&latest=1','',$postUrl);
                                        }else{
                                            $postUrl=str_replace('?latest=1','',$postUrl);
                                        }
                                    }
                                    ?>
                                    <a href="<?php echo $postUrl?>" class="btn btn-mena pull-right"><?php echo Yii::t('blocks/news', 'View more');?></a>
                                </div>
                            </div>

                        </li>
                        <?php
                    }
                ?>
            </ul>
    </div>
    <?php } ?>
</div>
