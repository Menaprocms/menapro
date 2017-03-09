<?php
/**
 * Created by PhpStorm.
 * User: silvia
 * Date: 06/03/2017
 * Time: 12:40
 */
namespace backend\components;

use yii\grid\ActionColumn;
use yii\helpers\Html;
use Yii;
class CustomActionColumn extends ActionColumn
{
    public $id_reset_button;
    public $enable_reset_button;
    public $reset_button_class;
    protected function renderFilterCellContent()
    {
        if($this->enable_reset_button){
            return Html::a(Yii::t('app','Reset'),'#',['id'=>$this->id_reset_button,'class'=>'btn btn-warning '.$this->reset_button_class]);
        }else{
            return "";
        }

//        return Html::button('Reset', ['class' => 'btn btn-warning','id'=>$this->id_reset_button]);
    }
}