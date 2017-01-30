<?php
    use common\models\Language;
    use common\widgets\Settingsbutton;
    use yii\helpers\Html;
    use common\models\Configuration;


    //@todo: Language Model????
    $lang=Language::find()->all();

?>
<div class="topbar <?php echo YII_DEBUG?'alertdev" data-alert="DEBUG is ENABLED':''?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-4 col-sm-4">
                <a href="#" class="mn_dropdown mn_btn menu-toggle  mn_tb_icon tb_pages " title="pages">
                </a>
            <h1 id="web_name_h1"><?php echo Configuration::getValue('_WEB_NAME_') ?></h1></div>

            <?php
            if( Yii::$app->controller->id=='content' && Yii::$app->controller->action->id !='index' ) {$hayPag=true; }else{$hayPag=false;}
            ?>
            <div class="col-xs-3 col-sm-3 mn_animated_childs">

                    <div id="lang-group" class="">

                    </div>
                    <div id="save_btn" class="">

                    </div>

            </div>




            <div class="col-xs-5 col-sm-5 mn_animated_childs text-right">
                <div class="row" id="topButtons">
                    <div id="published_btn" class=""></div>


    <?php
                         $id_item=Yii::$app->getRequest()->getQueryParam('id');

                    if( Yii::$app->controller->id=='content') {
                        echo Settingsbutton::widget(['id_item' => $id_item]);
                    }

                    ?>

                    <?php echo Html::a("","http://menapro.com/help.html",['class'=>'mn_dropdown mn_btn  mn_tb_icon tb_help']) ?>
                </div>
            </div>

        </div>

    </div>

</div>