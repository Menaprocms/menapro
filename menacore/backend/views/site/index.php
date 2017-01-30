<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">


    <div class="body-content">
        <div class="col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Administrar menu
                </div>
                <div class="panel-body">
                    <p>Permite modificar los elementos que aparecen en el menú principal de su página web.</p>
                    <?php echo Html::a('Editar menu','menu/edit',['class'=>'btn btn-primary']) ?>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Editar páginas
                </div>
                <div class="panel-body">
                    <p>Cree, modifique o añada páginas. Modifique también el orden en el que aparecen</p>
                    <?php echo Html::a('Editar menu',['content/index'],['class'=>'btn btn-primary']) ?>
                </div>
            </div>
        </div>

    </div>


</div>
