<?php

/* @var $this \yii\web\View */
/* @var $content string */

use common\components\Html;
use common\widgets\Responsivenav;
use common\widgets\languageSwitcher;
use yii\helpers\Url;

$this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <link rel="shortcut icon" href="<?php echo Url::base() ?>/img/favicon.ico" type="image/x-icon"/>
    <?php Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <div class="container">
        <header class="">
            <div class=" headerwrapper">

                <div class="row">
                    <div class="col-sm-4">
                        <div class="logo">
                            <a class="logoimg" href="<?php echo Url::base() ?>"
                               title="<?php echo $this->context->config['_WEB_NAME_'] ?>"
                               style="background-image: url('<?php echo Url::base() ?>/img/logo.png')">
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-8 socialtop">

                        <ul>
                            <?php if ($this->context->config['_FACEBOOK_']) { ?>
                                <li><?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-facebook']), $this->context->config['_FACEBOOK_'], ['target' => '_blank']) ?></li>
                            <?php } ?>

                            <?php if ($this->context->config['_TWITTER_']) { ?>
                                <li><?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-twitter']), $this->context->config['_TWITTER_'], ['target' => '_blank']) ?></li>
                            <?php } ?>

                            <?php if ($this->context->config['_INSTAGRAM_']) { ?>
                                <li><?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-instagram']), $this->context->config['_INSTAGRAM_'], ['target' => '_blank']) ?></li>
                            <?php } ?>
                            <?php if ($this->context->config['_PINTEREST_']) { ?>
                                <li><?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-pinterest']), $this->context->config['_PINTEREST_'], ['target' => '_blank']) ?></li>
                            <?php } ?>
                            <?php if ($this->context->config['_YOUTUBE_']) { ?>
                                <li><?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-youtube']), $this->context->config['_YOUTUBE_'], ['target' => '_blank']) ?></li>
                            <?php } ?>
                        </ul>
                    <?php echo languageSwitcher::Widget();?>
                    </div>

                </div>
            </div>
            <?php


            echo Responsivenav::widget([
                'items' => $this->context->menu,
                'liGutter' => ""

            ]);

            ?>

        </header>
        <div class="main">
            <?php echo $content ?>

        </div>
        <?php

        if($this->context->config['_COOKIES_NOTIFICATION_']){
           echo  $this->renderDynamic('return $this->renderFile("@frontend/views/content/cookies_notice.php");');
        }

        ?>
        <footer>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="logo">
                            <?php echo Html::img(Url::base()."/img/logo.png",['alt'=>$this->context->config['_WEB_NAME_'] ]) ?>
                        </div>
                    </div>
                    <div class="col-sm-3 address">
                        <ul>
                            <?php if ($this->context->config['_WEB_NAME_']) { ?>
                                <li><?php echo Html::tag('span', $this->context->config['_WEB_NAME_'],['class'=>'h3']) ?></li>
                            <?php } ?>
                            <?php if ($this->context->config['_ADDRESS_']) { ?>
                                <li><?php echo $this->context->config['_ADDRESS_']; ?></li>
                            <?php } ?>
                            <?php if ($this->context->config['_PHONE_']) { ?>
                                <li><?php echo $this->context->config['_PHONE_']; ?></li>
                            <?php } ?>
                            <?php if ($this->context->config['_MOBILE_PHONE_']) { ?>
                                <li><?php echo $this->context->config['_MOBILE_PHONE_']; ?></li>
                            <?php } ?>
                            <?php if ($this->context->config['_EMAIL_']) { ?>
                                <li><?php echo $this->context->config['_EMAIL_']; ?></li>
                            <?php } ?>
                            <?php if ($this->context->config['_OPENING_HOURS_']) { ?>
                                <li><?php echo $this->context->config['_OPENING_HOURS_']; ?></li>
                            <?php } ?>

                        </ul>

                    </div>
                    <div class="col-sm-3 contentlist">
                        <ul>

                            <?php
                            $footer_pages = $this->context->menu;
                            foreach ($footer_pages as $k => $page) {
                                if ($page['active']) {
                                    ?>
                                    <li><a
                                            href="<?php echo Yii::$app->urlManager->createUrl(['content/view', 'id' => $page['id'], 'link_rewrite' => $page['langFields'][0]['link_rewrite']]); ?>"><?php echo $page['langFields'][0]['title']; ?></a>
                                    </li>
                                <?php
                                }
                            }
                            ?>

                        </ul>
                    </div>


                    <div class="col-sm-3">
                        <div class="socialbottom">
                            <ul>
                                <?php if ($this->context->config['_FACEBOOK_']) { ?>
                                    <li><?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-facebook']), $this->context->config['_FACEBOOK_'], ['target' => '_blank']) ?></li>
                                <?php } ?>

                                <?php if ($this->context->config['_TWITTER_']) { ?>
                                    <li><?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-twitter']), $this->context->config['_TWITTER_'], ['target' => '_blank']) ?></li>
                                <?php } ?>

                                <?php if ($this->context->config['_INSTAGRAM_']) { ?>
                                    <li><?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-instagram']), $this->context->config['_INSTAGRAM_'], ['target' => '_blank']) ?></li>
                                <?php } ?>
                                <?php if ($this->context->config['_PINTEREST_']) { ?>
                                    <li><?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-pinterest']), $this->context->config['_PINTEREST_'], ['target' => '_blank']) ?></li>
                                <?php } ?>
                                <?php if ($this->context->config['_YOUTUBE_']) { ?>
                                    <li><?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-youtube']), $this->context->config['_YOUTUBE_'], ['target' => '_blank']) ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        Powered by <a href="http://menapro.com" title="MenaPro cms">MenaPro</a>
                    </div>
                </div>

        </footer>

    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
