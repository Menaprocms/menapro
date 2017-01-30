<?php
/*
* 2016-2016 MenaPro 1.0.0
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@menapro.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade MenaPro to newer
* versions in the future. If you wish to customize MenaPro for your
* needs please refer to http://www.menapro.com for more information.
*
*  @author Xenon media Burgos <contact25@menapro.com>
*  @copyright  2016-2016 Xenon Media
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*
*  Proudly made in Burgos, Spain. 
*
*/
use common\components\Html;

$classes = [
    0 => ['class'=>"success",'icon'=>'check'],
    1 => ['class'=>'info','icon'=>'info-circle'],
    2 => ['class'=>'warning','icon'=>'exclamation-triangle'],
    3 => ['class'=>'danger','icon'=>'exclamation-circle'],
];

echo Yii::$app->request->isAjax ? Html::tag('div', Yii::t('app', "Last update: ") . \Yii::$app->formatter->asDatetime(time(), "php:d-m-Y H:i:s"), ['class' => 'label label-info']) : "";
echo "<br><br>";
$counter = 0;
foreach ($messages as $severity => $msgGroup) {
    if ($severity != 0 && $severity != 1)
        $counter += count($msgGroup);

    echo Html::ul($msgGroup, [
        'class' => 'list-group',
        'item' => function ($item) use ($classes, $severity) {
            return Html::tag('li',Html::fa($classes[$severity]['icon'])." ".Html::tag('strong', $item['title'], ['class' => 'list-group-heading']) . ($item['description'] != "" ? Html::tag('p', $item['description'], ['class' => 'list-group-item-text']) : ""), ['class' => 'list-group-item list-group-item-' . $classes[$severity]['class']]);
        }
    ]);
}

if ($counter) {
    echo Html::tag('span', "Repair", [
        'class' => "btn btn-primary btn-block mn_ajax",
        'data-action' => "content/supervisor",
        'data-beforesend'=>'bs_supervisor_repair',
        'data-info' => ['repair' => true],
        'data-target' => "#supervisor_results",
        'data-callback'=>'cb_supervisor'
    ]);
}