<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password">
    <h1><?php echo  Html::encode($this->title) ?></h1>

    <p><?php echo Yii::t('app','Please choose your new password')?>:</p>

    <div class="row">
        <div class="">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <?php echo  $form->field($model, 'password')->passwordInput() ?>

                <div class="form-group">
                    <?php echo  Html::submitButton('Save', ['class' => 'btn rstbtn']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
