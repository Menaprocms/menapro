<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\widgets;

use Yii;
use common\components\Html;
use common\models\Content;



class Visiblebutton extends \yii\bootstrap\Widget
{
    public $id_item;
    /**
     * @var array list of items in the nav widget. Each array element represents a single
     * menu item which can be either a string or an array with the following structure:
     *
     * - label: string, required, the nav item label.
     * - url: optional, the item's URL. Defaults to "#".
     * - visible: boolean, optional, whether this menu item is visible. Defaults to true.
     * - linkOptions: array, optional, the HTML attributes of the item's link.
     * - options: array, optional, the HTML attributes of the item container (LI).
     * - active: boolean, optional, whether the item should be on active state or not.
     * - items: array|string, optional, the configuration array for creating a [[Dropdown]] widget,
     *   or a string representing the dropdown menu. Note that Bootstrap does not support sub-dropdown menus.
     *
     * If a menu item is a string, it will be rendered directly without HTML encoding.
     */
    public $items = [];
    /**
     * @var boolean whether the nav items labels should be HTML-encoded.
     */
    public $encodeLabels = true;
    /**
     * @var boolean whether to automatically activate items according to whether their route setting
     * matches the currently requested route.
     * @see isItemActive
     */
    public $activateItems = true;
    /**
     * @var boolean whether to activate parent menu items when one of the corresponding child menu items is active.
     */
    public $activateParents = false;
    /**
     * @var string the route used to determine if a menu item is active or not.
     * If not set, it will use the route of the current request.
     * @see params
     * @see isItemActive
     */
    public $route;
    /**
     * @var array the parameters used to determine if a menu item is active or not.
     * If not set, it will use `$_GET`.
     * @see route
     * @see isItemActive
     */
    public $params;
    /**
     * @var string this property allows you to customize the HTML which is used to generate the drop down caret symbol,
     * which is displayed next to the button text to indicate the drop down functionality.
     * Defaults to `null` which means `<b class="caret"></b>` will be used. To disable the caret, set this property to be an empty string.
     */
    public $dropDownCaret;
    public $cont=1;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }
        if ($this->params === null) {
            $this->params = Yii::$app->request->getQueryParams();
        }
        if ($this->dropDownCaret === null) {
            $this->dropDownCaret = Html::tag('b', '', ['class' => 'caret']);
        }
        Html::addCssClass($this->options, 'nav');
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
       // BootstrapAsset::register($this->getView());
//        return $this->renderItem();
        return $this->render('_visiblebutton',['id_item'=>$this->id_item]);
    }

    /**
     * Renders widget items.
     */
//    public function renderItem()
//    {
//        $btnText='Published';
//        if($this->id_item!=null) {
//            $model = Content::findOne($this->id_item);
//            $save=true;
//        }else{
//            $save=false;
//        }
//
//        $lbl=Html::label('','myonoffswitch15',['class'=>'onoffswitch-label']);
//        //$input=Html::activeCheckbox($model,'active',['id'=>'myonoffswitch15','name'=>'onoffswitch','class'=>'onoffswitch-checkbox','label'=>'','labelOptions'=>['class'=>'onoffswitch-label']]);
//      if($save) {
//          if ($model['active'] === 1) {
//              $input = Html::input('checkbox', 'onoffswitch', true, ['id' => 'myonoffswitch15', 'class' => 'onoffswitch-checkbox mn_ajax', 'checked' => '','data-action'=>'content/togglevisible','data-info'=>array('id'=>$this->id_item),'data-callback'=>'','data-target'=>'']);
//          } else {
//              $input = Html::input('checkbox', 'onoffswitch', true, ['id' => 'myonoffswitch15', 'class' => 'onoffswitch-checkbox mn_ajax','data-action'=>'content/togglevisible','data-info'=>array('id'=>$this->id_item),'data-callback'=>'','data-target'=>'']);
//          }
//      }else{
//          $input = Html::input('checkbox', 'onoffswitch', true, ['id' => 'myonoffswitch15', 'class' => 'onoffswitch-checkbox mn_ajax', 'checked' => '','data-action'=>'content/togglevisible','data-info'=>array('id'=>$this->id_item),'data-callback'=>'','data-target'=>'']);
//      }
//
//       // $chkd=$model['active']==1?'checked':'';
//
//        $div=Html::tag('div',$input.$lbl,['class'=>'onoffswitch']);
//        $superDiv=Html::tag('div',$btnText.$div,['class'=>'tb_published']);
//       return $superDiv;
//
//    }

}
