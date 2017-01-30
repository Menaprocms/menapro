<?php
use yii\helpers\Html;
?>

                <?php foreach($trash as $k=>$element){
                    $thumbnail='images/pagethumbs/thumb_'.$element['id'].'.jpg';
                    if(!file_exists($thumbnail))
                    {
                        $thumbnail='images/pagethumbs/thumb_default.jpg';
                    }
                    $time = substr($element['date_upd'],0,10);
                    $i = new DateInterval('P30D');
                    $date_1 = DateTime::createFromFormat('Y-m-d', $time);
                    $date_1->add($i);
//                    $date_1=substr($element['date_upd'],0,9);
                    $time2=date('Y-m-d');
                    $date_2=DateTime::createFromFormat('Y-m-d', $time2);
                    $dif=date_diff($date_1,$date_2,true);
                    $cb="cb_recover";

                    $icon=Html::tag('i','',['class'=>'glyphicon glyphicon-share-alt']);
                    ?>
                    <div class="col-sm-3" id="trash_<?php echo $element->id;?>">
                        <div class="thumbnail">
                            <img src="<?php echo $thumbnail;?>">
                            <div class="caption">
                                <h4><?php echo $element->langFields[0]->title; ?></h4>
                                <p>  <?php echo Yii::t('app','Time for complete removal')?>:<?php echo $dif->format('%d días'); ?></p>
                                <?php echo Html::a($icon,'#',['class'=>'btn btn-default btn-xs mn_ajax','data-action'=>'content/recoverpage','data-info'=>array('id'=>$element->id),'data-callback'=>$cb,'data-target'=>'']);?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
