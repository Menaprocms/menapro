<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset">
    <h1><?php echo  Html::encode($this->title) ?></h1>

    <p><?php echo Yii::t('app','Please fill out your email. A link to reset password will be sent there.')?></p>

    <div class="row">
        <div class="">
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                <?php echo  $form->field($model, 'email')->textInput(['class'=>'none']) ?>

                <div class="form-group">
                    <?php echo  Html::submitButton('Send', ['class' => 'btn rstbtn']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
