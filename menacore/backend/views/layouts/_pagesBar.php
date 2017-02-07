<?php

use common\widgets\Pagesbar;


if( Yii::$app->controller->id=='content') {
    echo Pagesbar::widget();
}
?>