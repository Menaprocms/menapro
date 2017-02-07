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

use common\components\Html;
/**
 * @var $col object: Contains all column data
 */
?>
<div class="eContactdata">
    <?php if($col->content->showtitle=="1"){?>
        <h3><?php echo Yii::t('blocks/contactdata', 'Contact us')?></h3>
    <?php } ?>
<?php if($col->content->webname=="1" && $this->context->config['_WEB_NAME_']!=""){?>
    <span class="h4"><?php echo $this->context->config['_WEB_NAME_']?></span>
<?php } ?>
<ul>
<?php if($col->content->address=="1" && $this->context->config['_ADDRESS_']!=""){?>
    <li class="cd_element cd_address"><i class="fa fa-map-marker"></i><span class="contactdata_label"><?php echo Yii::t('blocks/contactdata', 'Address').' : '; ?></span><span class="contactdata_value "><?php echo $this->context->config['_ADDRESS_']?></span></li>
<?php } ?>
<?php if($col->content->openinghours=="1" && $this->context->config['_OPENING_HOURS_']!=""){?>
    <li class="cd_element cd_openinghours"><i class="fa fa-clock-o"></i><span class="contactdata_label"><?php echo  Yii::t('blocks/contactdata', 'Opening hours').' : '; ?></span><span class="contactdata_value "><?php echo $this->context->config['_OPENING_HOURS_']?></span></li>
<?php } ?>
<?php if($col->content->telephone=="1" && $this->context->config['_PHONE_']!=""){?>
    <li class="cd_element cd_phone"><i class="fa fa-phone"></i><span class="contactdata_label"><?php echo  Yii::t('blocks/contactdata', 'Telephone').' : ';?></span><span class="contactdata_value "><a href="tel:<?php echo $this->context->config['_PHONE_']?>"><?php echo $this->context->config['_PHONE_']?></a></span></li>
<?php } ?>
<?php if($col->content->mobile=="1" && $this->context->config['_MOBILE_PHONE_']!=""){?>
    <li class="cd_element cd_mobilephone"><i class="fa fa-mobile"></i><span class="contactdata_label"><?php echo  Yii::t('blocks/contactdata', 'Mobile phone').' : ';?></span><span class="contactdata_value "><a href="tel:<?php echo $this->context->config['_MOBILE_PHONE_']?>"><?php echo $this->context->config['_MOBILE_PHONE_']?></a></span></li>
<?php } ?>
<?php if($col->content->email=="1" && $this->context->config['_EMAIL_']!=""){?>
    <li class="cd_element cd_email"><i class="fa fa-envelope"></i><span class="contactdata_label"><?php echo  Yii::t('blocks/contactdata', 'Email').' : ';?></span><span class="contactdata_value "><a href="mailto:<?php echo $this->context->config['_EMAIL_']?>"><?php echo $this->context->config['_EMAIL_']?></a></span></li>
<?php } ?>
    <li>
<?php if($col->content->facebook=="1" && $this->context->config['_FACEBOOK_']!=""){?>
    <span class="contactdata_value cd_facebook cd_social"><a target="_blank" href="<?php echo $this->context->config['_FACEBOOK_']?>"><i class="fa fa-facebook-official"></i></a></span>
<?php } ?>
<?php if($col->content->twitter=="1" && $this->context->config['_TWITTER_']!=""){?>
    <span class="contactdata_value cd_twitter cd_social"><a target="_blank" href="<?php echo $this->context->config['_TWITTER_']?>"><i class="fa fa-twitter"></i></a></span>
<?php } ?>
<?php if($col->content->instagram=="1" && $this->context->config['_INSTAGRAM_']!=""){?>
    <span class="contactdata_value cd_instagram cd_social"><a target="_blank" href="<?php echo $this->context->config['_INSTAGRAM_']?>"><i class="fa fa-instagram"></i></a></span>
<?php } ?>
<?php if($col->content->pinterest=="1" && $this->context->config['_PINTEREST_']!=""){?>
   <span class="contactdata_value cd_pinterest cd_social"><a target="_blank" href="<?php echo $this->context->config['_PINTEREST_']?>"><i class="fa fa-pinterest"></i></a></span>
<?php } ?>
    </li>

</ul>
</div>