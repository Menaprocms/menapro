<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Install Menapro';
?>
<div class="install-first_step">
    <div class="body-content">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-2">
<!--Prevent chrome autocomplete-->
                <input type="password" style="display:none">

                <!--                User form-->
                <h3><?php echo Yii::t('app', 'User data')?></h3>

                <div class="form-group">
                    <label for="user"><?php echo Yii::t('app', 'user')?></label>
                    <input type="text" id="user" name="user" class="form-control" autocomplete="false">
                </div>
                <div class="form-group">
                    <label for="user"><?php echo Yii::t('app', 'password')?></label>
                    <input type="password" id="password" class="form-control" autocomplete="false">
                    <span class="s_tip" id="pass_length_note"><?php echo Yii::t('app', 'Min. 8 characters')?></span>
                </div>

                <div class="form-group">
                    <label for="user"><?php echo Yii::t('app', 'confirm password')?></label>
                    <input type="password" id="password_confirm" class="form-control" autocomplete="false">
                    <div id="error_confirm" class="hidden pass_error"><?php echo Yii::t('app', 'The passwords do not match ')?></div>
                    <div id="error_size" class="hidden pass_error"><?php echo Yii::t('app', 'The password must have 8 characters as minimum')?></div>
                </div>
                <div class="form-group">
                    <label for="user"><?php echo Yii::t('app', 'email')?></label>
                    <input type="text" id="email" class="form-control">
                </div>
<!--                End user form-->
                <div class="form-group">
                    <label for="lang_selection"><?php echo Yii::t('app', 'Select language')?></label>

                    <select class="form-control" id="lang_selection">
                        <?php
                        foreach($langs as $k=>$v){
                            ?>
                            <option value="<?php echo $v['iso']; ?> "><?php echo $v['name'];?></option>
                        <?php
                        }
                        ?>
<!--                        <option value="1">Español</option>-->
<!--                        <option value="2">English</option>-->
<!--                        <option value="3">Deutsch</option>-->
<!--                        <option value="4">Français</option>-->
<!--                        <option value="5">Ukrainian</option>-->
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
<!--                Db form-->
                <h3><?php echo Yii::t('app', 'Database')?></h3>

                <div class="form-group">
                    <label for="db_server"><?php echo Yii::t('app', 'Database server')?></label>
                    <input type="text" id="db_server" class="form-control" value="localhost">
                </div>


                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label for="db_name"><?php echo Yii::t('app', 'Database name')?></label>
                            <input type="text" id="db_name" class="form-control">
                        </div>

                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="db_prefix"><?php echo Yii::t('app', 'Prefix')?></label>
                            <input type="text" id="db_prefix" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="db_user"><?php echo Yii::t('app', 'Database user')?></label>
                    <input type="text" id="db_user" class="form-control">
                </div>
                <div class="form-group">
                    <label for="db_password"><?php echo Yii::t('app', 'Database password')?></label>
                    <input type="text" id="db_password" class="form-control">
                </div>
                <a href="#" class="btn btn-default" id="check_db_connection"><?php echo Yii::t('app', 'Check')?></a>
                <br>
                <div class="alert alert-danger hidden install_error db_no"><?php echo Yii::t('app', 'Could not connect to database.')?></div>
                <div class="alert alert-success hidden install_error db_ok"><?php echo Yii::t('app', 'Sucessfully connected.')?></div>
                <!--                End Db form-->
            </div>
        </div>



        <hr>

        <a href="#" class="btn btn-default" id="continue_first_step"><?php echo Yii::t('app', 'Continue')?></a>
        <span id="loader_install" class="hidden"><i class="fa fa-spinner fa-spin fa-3x"></i></span>
    </div>
</div>
