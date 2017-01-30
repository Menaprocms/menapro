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

use common\components\Html; ?>
<div id="proBox-call2action">
    <div class="proBoxTitle">
        <?php echo Yii::t('blocks/call2action', 'Call to action');?>
    </div>


    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">

     <div class="panel">
<div class="panel panel-body">
       <div class="row">
                <div class="col-sm-12">
                    <label for="call2action_header"><?php echo Yii::t('blocks/call2action', 'Title');?></label>
                    <input id="call2action_header" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label for="call2action_body"><?php echo Yii::t('blocks/call2action', 'Body text');?></label>
                    <textarea rows="6" id="call2action_body" class="form-control"></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">

                    <label><?php echo Yii::t('blocks/call2action', 'Button icon');?></label><br>
                     <div id="call2action_eicon" class="icon_helper call2action_eicon"></div>


                </div>
                <div class="col-sm-8">
                    <label for="call2action_text"><?php echo Yii::t('blocks/call2action', 'Button text');?></label>
                    <input id="call2action_text" type="text" class="form-control">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <label><?php echo Yii::t('blocks/call2action', 'Link');?></label>
                    <div id="call2action_elink" class="call2action_elink" ></div>
                </div>
            </div>
</div>
     </div>

        </div>
    </div>






   
    <div class="row">
        <div class="col-sm-12">
            <div id="call2action_required_errors" class="alert alert-danger hidden">
                <?php echo Yii::t('blocks/call2action', 'You must fill required fields');?>
            </div>
        </div>
    </div>

    <hr>
    <div class="row eRow saveButtonsRow">
        <span class="btn btn-danger toTrash"><i class="fa fa-trash"></i></span>
        <span id="save-call2action" class="btn btn-success pull-right proBox-save"><i class="fa fa-2x fa-check"></i></span>
    </div>
</div>
<div id="clonable-call2action-preview" class="hidden">
    <div class="row">
        <div class="col-xs-12">
            <i class="eIco eIcoCallToAction">
            </i>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <h3 class="call-title-preview"></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 call-body-preview">

        </div>
    </div>
</div>
