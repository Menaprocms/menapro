<?php
use yii\web\View;
use common\models\Configuration;
?>



<div class="procms" id="proCms">


    <div id="design">

        <div id="cms-content" class="eContainer">


        </div>   <!-- end eContainer -->
        <br>
        <div class="row eRowCreate">
            <span class="fa fa-plus"></span>
            <div id="clonable-eColumns" class="row eRow eColumns">
                <?php foreach($rowStructures as $structure){?>
                <div class="col-xs-2" data-col="<?php echo implode ( ',' , $structure)?>">
                    <div class="eColModel row">
                        <?php foreach($structure as $col){?>
                        <div class="col-xs-<?php echo $col ?>">
                            <span></span>
                        </div>
                        <?php }?>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
        <div id="copyStructure"></div>
        <span id="clonable-trash-row" class="eOptionsBtn hidden"><i class="fa fa-cog"></i></span>
        <div  id="clonable-row-options" class="eRowOptions animate hidden">
            <div class="row">
                <div class="col-xs-8">
                    <span class="eRowHtmlOptions">
                        <div class="row">
                            <div class="col-xs-6">
                                <select class="selHtmlOptions form-control">
                                    <option value=""><?php echo Yii::t('app','Choose an option')?></option>
                                    <?php $contOptions=1;
                                    foreach($rowOptions as $k=>$option){?>
                                        <option value="<?php echo $k ?>"><?php if($option==""){ ?>estilo <?php echo $contOptions; $contOptions++;}else{echo $option;} ?></option>
                                    <?php
                                    }
                                    ?>
                                    <option value="eCustom"><?php echo Yii::t('app','Use custom class')?></option>
                                </select>
                            </div>
                            <div class="col-xs-4">
                                <input class="customClass form-control hidden" type="text" placeholder="css class">
                            </div>
                        </div>
                    </span>
                </div>
                <div class="col-xs-4">
                    <span class="eRowBtn eRowDeleteBtn"><i class="fa fa-trash"></i><?php echo Yii::t('app','Delete row')?></span>
                </div>
            </div>
        </div>
        <span id="clonable-eHandler" class="eHandler hidden"><span></span><span></span><span></span></span>
    </div>

</div>
