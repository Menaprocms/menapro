<?php
?>
<div class="install-index install_step">
    <div class="body-content">

        <div class="h1 title godown"><?php echo translate('MenaPRO License')?></div>
        <textarea id="license_terms" class="form-control" cols="20" rows="10" readonly><?php echo $license_text?></textarea>
        <div class="checkbox">
            <label>
                <input type="checkbox" id="terms_accepted"><?php echo translate('I accept the terms of the License Agreement.')?>
            </label>
        </div>
        <a href="#" class="btn btn-default installbtn" id="accept_license"><?php echo Yii::t('app', 'Install')?></a>

    </div>
</div>
<?php

function translate($string){
    return $string;
}

?>