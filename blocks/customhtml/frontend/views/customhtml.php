<?php
use yii\helpers\HtmlPurifier;
?>
<div class="eCustomhtml">

    <?php
    if($col->content->purify) {
        echo HtmlPurifier::process($col->content->code);
    }else
    {
        echo $col->content->code;
    }

    ?>

</div>