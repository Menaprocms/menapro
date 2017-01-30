<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

 <?php echo Html::a(" ","http://menapro.com",[
        'title'=>"MenaPro official site",
        'target'=>'_blank',
        'class'=>'loginLogo'
    ]); ?>




<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
<?php
echo $form->errorSummary($model);
?>
<?php echo $form->field($model, 'username', ['inputOptions' => ['class' => " "]]) ?>

<?php echo $form->field($model, 'password', ['inputOptions' => ['class' => " "]])->passwordInput() ?>

<?php echo $form->field($model, 'rememberMe')->checkbox() ?>
<div class="form-group">
    <?php echo Html::submitButton('Login', ['class' => '', 'name' => 'login-button']) ?>
</div>
<div class="reset">
    Forgot password?  <?php echo Html::a('Reset it', ['site/request-password-reset']) ?>
</div>


<?php ActiveForm::end(); ?>




