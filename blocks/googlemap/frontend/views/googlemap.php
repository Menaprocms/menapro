
<?php
/* @var $this \yii\web\View */

$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key='.\common\models\Configuration::getValue("_GMAP_API_KEY_"));
$this->registerJs("var mark" . $cRow . $cCol . "='" . json_encode($col->content) . "';", $this::POS_END);
?>

<div data-sattellite="<?php echo $col->content->type ?>" data-mapid="<?php echo $cRow . $cCol ?>"
     data-fit="<?php echo $col->content->fit ?>" id="googlemap_<?php echo $cRow . '_' . $cCol ?>" class="googlemap_map"
     style="height: 100%; width: 100%;min-width:300px;min-height:300px;"></div>
