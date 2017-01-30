<?php
namespace app\components;

use yii;
use yii\base\Component;
use common\models\Content;



class Menu extends Component{

    public function menu()
    {
        return Content::getContentsTree();

    }

}