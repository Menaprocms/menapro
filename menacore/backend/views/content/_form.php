<?php

use common\components\Html;
use yii\widgets\ActiveForm;
use common\models\Content;
use backend\assets\ContentAsset;

/* @var $this yii\web\View */
/* @var $model common\models\Content */
/* @var $form yii\widgets\ActiveForm */

if($menapro_lastversion!=false && $menapro_lastversion > $menapro_currentversion) {
    ?>

    <div class="alert alert-info version_notice" id="version_notice"><i class="fa fa-info-circle"></i> <?php echo Yii::t('app', 'There is a new MenaPRO version available. Check it in '); ?><a
            href="http://menapro.com" target="_blank">MenaPRO.com</a></div>
<?php
}
$form = ActiveForm::begin(); ?>
<div class="row">
    <div id="app_notice" class="col-sm-6 contentTitle">
        <div class="alert alert-success hidden" id="theme_notice"><?php echo Yii::t('app','The theme was installed succesfully');?></div>
        <div class="alert alert-success hidden" id="block_notice"><?php echo Yii::t('app','The block was installed succesfully');?></div>
    </div>
</div>
<div class="row">
    <div id="page_header" class="col-sm-8 col-sm-offset-2 contentTitle">
    </div>
</div>
<div class="row">
    <div class="col-md-12">

        <?php

            echo Html::tag('div', $this->render('_medinaForm', [
                'trash'=>$trash,
                'blocks'=>$blocks,
                'rowStructures' => $rowStructures,
                'rowOptions' => $rowOptions,
                'curLang' => $curLang
            ]), ['class' => 'superMedinapro closed mn_animated hidden']);


 ?>
    </div>
</div>

<?php echo $this->render('_probox', [
    'trash'=>$trash,
    'blocks'=>$blocks,
    'rowStructures'=>$rowStructures,
    'rowOptions'=>$rowOptions,
]) ?>

    <?php

 ActiveForm::end(); ?>

