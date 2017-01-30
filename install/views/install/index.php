<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Menapro installer';
?>
<div class="install-index install_step">
    <div class="body-content">
        <div class="h1 title godown"><?php echo Yii::t('app', 'Welcome to MenaPRO')?></div>
        <div class="alert alert-danger hidden install_error"><?php echo Yii::t('app', 'Please give write permissions to those files')?></div>
        <ul id="take_permissions" class="hidden"></ul>
        <a href="#" class="btn btn-default startbtn fadein delay2s" id="start_installation"><?php echo Yii::t('app', 'Click to start')?></a>
    </div>
</div>
