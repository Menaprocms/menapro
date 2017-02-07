<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1> <?php echo  Html::encode($this->title) ?></h1>

    <p><?php echo Yii::t('app','Please fill out the following fields to signup')?>:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                 <?php echo  $form->field($model, 'username') ?>

                 <?php echo  $form->field($model, 'email') ?>

                 <?php echo  $form->field($model, 'password')->passwordInput() ?>

                <div class="form-group">
                     <?php echo  Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
