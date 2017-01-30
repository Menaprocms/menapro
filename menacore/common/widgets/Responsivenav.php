<?php
/*
*   ****************************
*   *       MenaPro 1.0        *
*   ****************************
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@menapro.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade MenaPro to newer
* versions in the future. If you wish to customize MenaPro for your
* needs please refer to http://menapro.com for more information.
*
*  @author Xenon media Burgos <contact25@menapro.com>
*  @copyright  2016 Xenon Media
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*
*  Proudly made in Burgos, Spain.
*
*/


namespace common\widgets;

use Yii;
use common\components\Html;

/**
 * Nav renders a nav HTML component.
 *
 * http://w3bits.com/css-responsive-nav-menu/
 */
class Responsivenav extends \yii\bootstrap\Widget
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

    /**
     * @var integer this property defines the current active id
     */
    public $activePage;


    public $menuWrapperClass = false;

    public $mainID = "menu";

    public $cont = 1;
    public $withNavText=false;

    /**
     * @var string Set to "" if you want to make menu inline and continuous, set as \n to use it with justify menus
     */
    public $liGutter ="\n";

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

        $this->activePage = Yii::$app->request->getQueryParam('id');
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        // BootstrapAsset::register($this->getView());

        return $this->renderItems();
    }

    public function renderCheck($id)
    {
        return Html::input('checkbox', null, null, array('id' => $id));
    }

    /**
     * Renders widget items.
     */
    public function renderItems()
    {


        $items = [];

        $options = array('class' => 'main-menu');

        $span = Html::tag('span', ($this->withNavText ? '▾' : ''), array('class' => 'drop-icon'));
        $lbl = Html::label(($this->withNavText ? Yii::t('app','Navigation') : '') . $span, 'tm', array('id' => 'toggle-menu'));

        $chk = $this->renderCheck('tm');


        $half = floor(count($this->items) / 2);

        foreach ($this->items as $i => $item) {
            if (isset($item['active']) && $item['active']) {
                if (isset($item['visible']) && !$item['visible']) {
                    continue;
                } else {
                    $items[] = $this->renderMenuItem($item, $i +1 == $half);
                }
            } else {
                continue;
            }
        }

        $ul = Html::tag('ul', implode($this->liGutter, $items), $options);
        $divG = $this->menuWrapperClass?Html::tag('div', $chk.$lbl  . $ul, array('class' => $this->menuWrapperClass."eros")):$chk.$lbl  . $ul;
        return Html::tag('nav', $divG, array('id' => $this->mainID));
    }

    public function renderMenuItem($item, $center = false)
    {

        $label = $item["langFields"][0]['menu_text'];
        $id = $item['id'];

        if ($id == $this->activePage) {
            $active = 'active';
        } else {
            $active = '';
        }
        $url = Yii::$app->urlManager->createUrl(['content/view', 'id' => $item['id'], 'link_rewrite' => $item["langFields"][0]['link_rewrite']]);


        if (isset($item['subcats']) && $item['subcats'] != false) {

            $ulCont = '';
            $contAct = 0;
            foreach ($item['subcats'] as $subItem) {
                // $ulCont.=$this->renderMenuItem($subItem);
                if (isset($subItem['active']) && $subItem['active']) {
                    if (isset($subItem['visible']) && !$subItem['visible']) {
                        continue;
                    } else {
                        $ulCont .= $this->renderMenuItem($subItem, false);
                        $contAct++;
                    }
                } else {
                    continue;
                }
            }
            if ($contAct > 0) {

                $lbl = Html::label(($this->withNavText ? '▾' : ''), 'sm' . $this->cont, array('class' => 'drop-icon', 'title' => 'Toggle Drop-down'));
                $span = Html::tag('span', ($this->withNavText ? '▾' : ''), array('class' => 'drop-icon'));
                $chk = $this->renderCheck('sm' . $this->cont);
            } else {
                $lbl = '';
                $span = '';
                $chk = '';
            }

            $ul = Html::tag('ul', $ulCont, array('class' => 'sub-menu'));
            $this->cont++;
            $link = Html::a($label . $lbl . $span, $url, ['class' => 'menadyn menu_item_link ' . $active, 'id' => 'page_' . $id]);
            $content = $chk .$link .  $ul;
        } else {
            $link = Html::a($label, $url, ['class' => 'menu_item_link menadyn ' . $active, 'id' => 'page_' . $id]);
            $content = $link;
        }
        return Html::tag('li', $content, [
            'class' => $center ? "center_of_menu" : ""
        ]);

    }


}
