<?php
/**
 * Dc_FeaturedProduct
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Dc
 * @package    Dc_FeaturedProduct
 * @copyright  Copyright (c) 2013-2015 Damián Culotta. (http://www.damianculotta.com.ar/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Dc_FeaturedProduct_Block_Group extends Mage_Catalog_Block_Product_Abstract implements Mage_Widget_Block_Interface
{

    protected $items = array();
    
    protected function _construct()
    {
        parent::_construct();
        if ($this->_getGroupTemplate()) {
            $this->setTemplate($this->_getGroupTemplate());
        }
    }

    /**
     * Get the group id to be used with the widget.
     *
     * @return mixed
     */
    protected function getGroupId()
    {
        return $this->getData('group');
    }

    /**
     * Get widget title.
     *
     * @return mixed
     */
    protected function getGroupTitle()
    {
        return $this->getData('group_title');
    }

    /**
     * Get the template to be used into the widget instance.
     *
     * @return bool
     */
    protected function _getGroupTemplate()
    {
        $template = Mage::getModel('featuredproduct/template')->load($this->getData('group_template'));
        if ($template) {
            return $template->getFilePath();
        }
        return false;
    }

    /**
     * Get the products associated with the group.
     *
     * @return bool
     */
    protected function getGroupItems()
    {
        $items = Mage::getModel('featuredproduct/item')->getProductsByGroup($this->getGroupId());
        if ($items) {
            foreach ($items as $item) {
                $this->items[$item->getProductId()] = $item->getPosition();
            }
            return true;
        }
        return false;
    }

    /**
     * Get the product information according with the items group.
     *
     * @return mixed
     */
    public function _getProducts()
    {
        if ($this->getGroupItems()) {
            $ids = array_keys($this->items);
            $products = Mage::getModel('catalog/product')->getCollection()
                                ->addAttributeToSelect('*')
                                ->addMinimalPrice()
                                ->addFinalPrice()
                                ->addTaxPercents()
                                ->addStoreFilter()
                                ->addFieldToFilter('entity_id', array('in' => $ids))
                                ->joinTable('featuredproduct/item', 'product_id=entity_id', array('position'), 'group_id = ' . $this->getGroupId(), 'inner')
                                ;
            $direction = $this->getSortDirection();
            switch ($this->getData('group_sort_order')) {
                case Dc_FeaturedProduct_Model_Sort::NAME:
                    $products->addAttributeToSort('name', $direction);
                    break;
                case Dc_FeaturedProduct_Model_Sort::RANDOM:
                    $products->getSelect()->order(new Zend_Db_Expr('RAND()'));
                    break;
                case Dc_FeaturedProduct_Model_Sort::PRICE:
                    $products->getSelect()->order('final_price ' . $direction);
                    break;
                default:
                    $products->getSelect()->order('position ' . $direction);
            }
            if ($this->getData('group_limit')) {
                $products->getSelect()->limit($this->getData('group_limit'));
            }
            $this->assign('total', $products->getSize());
        } else {
            $this->assign('total', 0);
        }
        return $products;
    }

    /**
     * Get cache key informative items
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        $info = parent::getCacheKeyInfo();
        if ($id = $this->getData('unique_id')) {
            $info['unique_id'] =  (string) $id;
        }
        return $info;
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->isEnterprise() && $this->getData('group_sort_order') == Dc_FeaturedProduct_Model_Sort::RANDOM) {
            if (!$this->getFullPageCacheEnvironment() && $this->getUniqueId()) {
                $id = Dc_FeaturedProduct_Model_Fpc_Container_Widget::CACHE_PREFIX . $this->getUniqueId() . '_params';
                Enterprise_PageCache_Model_Cache::getCacheInstance()->save(serialize($this->getData()), $id);
            }
        }
        $this->assign('title', $this->getGroupTitle());
        $this->assign('products', $this->_getProducts());
        return parent::_toHtml();
    }

    /**
     * Check which edition is.
     *
     * @return bool
     */
    private function isEnterprise()
    {
        if (Mage::getEdition() === Mage::EDITION_ENTERPRISE) {
            return true;
        }
        return false;
    }

    /**
     * Get the SQL string for order.
     *
     * @return string
     */
    private function getSortDirection()
    {
        if ($this->getData('group_sort_direction') == Dc_FeaturedProduct_Model_Direction::DESC) {
            return 'DESC';
        }
        return 'ASC';
    }

}
