<?php
/*
*   ****************************
*   *       MenaPro 1.0        *
*   ****************************
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
* needs please refer to http://menapro.com for more information.
*
*  @author Xenon media Burgos <contact25@menapro.com>
*  @copyright  2016 Xenon Media
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*
*  Proudly made in Burgos, Spain.
*
*/

/**
 * @var $col object containing all column information
 */
use common\widgets\Elink;

?>
<div class="eCall2Action">
    <div class="row">
        <div class="col-xs-12">
            <h3 class="header-text"><?php echo $col->content->header; ?></h3>
        </div>
    </div>
    <?php if (isset($col->content->body) && $col->content->body != '') { ?>
        <div class="row">
            <div class="col-xs-12">
                <p class="body-text"><?php echo $col->content->body; ?></p>
            </div>
        </div>
    <?php } ?>
    <div class="row">
        <div class="col-xs-12">
            <?php
            Elink::begin([
                    'linkData' => $col->content->elink,
                    'htmlOptions'=>[
                        'class'=>'call-link btn btn-default'
                    ]
                ]
            );
            ?>

            <i class="<?php echo $col->content->eicon ?> call-icon fa-2x"></i>
            <span class="call-text"><?php echo $col->content->text ?></span>
            <?php
            Elink::end();
            ?>
        </div>
    </div>
</div>