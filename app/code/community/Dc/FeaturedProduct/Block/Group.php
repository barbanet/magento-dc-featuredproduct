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
 * @copyright  Copyright (c) 2015 Damián Culotta. (http://www.damianculotta.com.ar/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Dc_FeaturedProduct_Block_Group extends Mage_Catalog_Block_Product_Abstract implements Mage_Widget_Block_Interface
{

    protected $_items = array();
    
    protected function _construct()
    {
        parent::_construct();
        if ($this->_getGroupTemplate()) {
            $this->setTemplate($this->_getGroupTemplate());
        }
    }

    protected function _getGroupId()
    {
        return $this->getData('group');
    }
    
    protected function _getGroupTitle()
    {
        return $this->getData('group_title');
    }
    
    protected function _getGroupTemplate()
    {
        $_template = Mage::getModel('featuredproduct/template')->load($this->getData('group_template'));
        if ($_template) {
            return $_template->getFilePath();
        }
        return false;
    }
    
    protected function _getGroupItems()
    {
        $_items = Mage::getModel('featuredproduct/item')->getProductsByGroup($this->_getGroupId());
        if ($_items) {
            foreach ($_items as $_item) {
                $this->_items[$_item->getProductId()] = $_item->getPosition();
            }
            return true;
        }
        return false;
    }

    public function _getProducts()
    {
        if ($this->_getGroupItems()) {
            $_ids = array_keys($this->_items);
            $_products = Mage::getModel('catalog/product')->getCollection()
                                ->addAttributeToSelect('*')
                                ->addMinimalPrice()
                                ->addFinalPrice()
                                ->addTaxPercents()
                                ->addStoreFilter()
                                ->addFieldToFilter('entity_id', array('in' => $_ids))
                                ->joinTable('featuredproduct/item', 'product_id=entity_id', array('position'), 'group_id = ' . $this->_getGroupId(), 'inner')
                                ;
            $_direction = $this->_getSortDirection();
            Mage::log($_direction, null, 'debug.log', true);
            switch ($this->getData('group_sort_order')) {
                case Dc_FeaturedProduct_Model_Sort::NAME:
                    $_products->addAttributeToSort('name', $_direction);
                    break;
                case Dc_FeaturedProduct_Model_Sort::RANDOM:
                    $_products->getSelect()->order(new Zend_Db_Expr('RAND()'));
                    break;
                case Dc_FeaturedProduct_Model_Sort::PRICE:
                    $_products->getSelect()->order('final_price ' . $_direction);
                    break;
                default:
                    $_products->getSelect()->order('position ' . $_direction);
            }
            if ($this->getData('group_limit')) {
                $_products->getSelect()->limit($this->getData('group_limit'));
            }
            $this->assign('total', $_products->getSize());
        } else {
            $this->assign('total', 0);
        }
        return $_products;
    }
    
    public function getCacheKeyInfo()
    {
        $info = parent::getCacheKeyInfo();
        if ($id = $this->getData('unique_id')) {
            $info['unique_id'] =  (string) $id;
        }
        return $info;
    }
    
    protected function _toHtml()
    {
        if ($this->_isEnterprise() && $this->getData('group_sort_order') == Dc_FeaturedProduct_Model_Sort::RANDOM) {
            if (!$this->getFullPageCacheEnvironment() && $this->getUniqueId()) {
                $id = Dc_FeaturedProduct_Model_Fpc_Container_Widget::CACHE_PREFIX . $this->getUniqueId() . '_params';
                Enterprise_PageCache_Model_Cache::getCacheInstance()->save(serialize($this->getData()), $id);
            }
        }
        $this->assign('title', $this->_getGroupTitle());
        $this->assign('products', $this->_getProducts());
        return parent::_toHtml();
    }

    private function _isEnterprise()
    {
        if (Mage::getEdition() === Mage::EDITION_ENTERPRISE) {
            return true;
        }
        return false;
    }

    private function _getSortDirection()
    {
        if ($this->getData('group_sort_direction') == Dc_FeaturedProduct_Model_Direction::DESC) {
            return 'DESC';
        }
        return 'ASC';
    }

}
