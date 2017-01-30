<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Install Menapro';
?>
<div class="install-second_step">
    <div class="body-content">
        <h3><?php echo translate('MenaPRO successfully installed')?></h3>
        <p><?php echo translate('The database has been copied to your server. There is one thing more to do. Please, delete install folder.')?></p>
        <div class="alert alert-success hidden install_error rename_folder"><?php echo translate('Backend access: ')?><a id="back_link" href="#" target="_blank"><span id="back_link_text"></span></a></div>
        <div class="alert alert-danger hidden install_error rename_folder_adv"><?php echo translate('We can´t change manager folder name, we advise to change manager folder name for your security.')?></div>
        <a href="#" class="btn btn-default" id="delete_install"><?php echo translate('Delete install folder')?></a>
        <div class="alert alert-success hidden install_error delete_folder_ok"><?php echo translate('Install folder has been removed successfully')?></div>
        <div class="alert alert-danger hidden install_error delete_folder_no"><?php echo translate('Can´t remove install folder, please remove it manually for start working')?></div>
        <div class="alert alert-danger hidden install_error install_no"><?php echo translate('Installation error')?></div>
    </div>
</div>
<?php

function translate($string){
    return $string;
}

?>