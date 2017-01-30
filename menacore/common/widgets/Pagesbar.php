<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\widgets;

use Yii;
use yii\helpers\Html;
use common\models\Content;


class Pagesbar extends \yii\bootstrap\Widget
{
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
        if(Content::getNumberOfPages()>0){
            $posts=Content::getContentsTree(true);
            $html='';
            $html=$this->renderItem($posts,$html);
            return $this->render('_pagesbar',['posts'=>$posts,'html'=>$html]);
        }else{
            return $this->render('_pagesbar',['html'=>'']);
        }

    }

    public function renderItem($contentsArray){
        $html='';
        foreach($contentsArray as $id=>$v){
            $thumbnail='images/pagethumbs/thumb_'.$v['id'].'.jpg';
            if(!file_exists($thumbnail))
            {
                $thumbnail='images/pagethumbs/thumb_default.jpg';
            }
            $ajaxOptions=['class'=>'mn_ajax','data-action'=>'content/load','data-info'=>array('id'=>$v['id']),'data-target'=>'','data-callback'=>'cb_mn_page','data-beforesend'=>'bs_mn_page'];
            $img=Html::img($thumbnail,['alt'=>$v['langFields'][0]['title'],'data-id'=>$v['id'],'class'=>'page_thumb img-responsive','id'=>'pagethumb_'.$v['id']]);
            $link=Html::a($img,'#',$ajaxOptions);
            $d=Html::tag('div',$v['langFields'][0]['title'],array_merge(['id'=>$v['id']],$ajaxOptions));
            $clss=[];
            $collect='';
            if(isset($v['subcats']) && $v['subcats']!=false){
                $clss[]='hasChild';
                $collect=$this->renderItem($v['subcats']);
            }
            $cul=Html::tag('ul',$collect,['class'=>'mn_childs']);
            if($v['active']<=0)
            {
                $clss[]="mn_page_unpublished";
            }
            if(!$v['in_menu']){
                $clss[]="mn_page_not_in_menu";
            }
            $li= Html::tag('li',$link.$d. $cul,['id'=>'menuItem_'.$v['id'],'class'=>'mn_page  mjs-nestedSortable-branch '.implode(" ",$clss)]);
            $html.=$li;
        }
        return $html;
    }


}
