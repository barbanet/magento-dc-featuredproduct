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

class Dc_FeaturedProduct_Block_Adminhtml_Group_Edit_Tab_Product extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('groupProductGrid');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(false);
    }

    public function getFeaturedGroup()
    {
        return Mage::registry('featuredproduct_data');
    }

    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_group') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$productIds));
            } elseif(!empty($productIds)) {
                $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$productIds));
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    protected function _prepareCollection()
    {
        if ($this->getFeaturedGroup() && $this->getFeaturedGroup()->getGroupId()) {
            $this->setDefaultFilter(array('in_group' => 1));
        }
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('price')
            ->addAttributeToSelect('type_id')
            ->addAttributeToSelect('visibility')
            ->addAttributeToSelect('status')
            ->joinField('position',
                'featuredproduct/item',
                'position',
                'product_id=entity_id',
                'group_id=' . (int) $this->getRequest()->getParam('id', 0),
                'left');
        $this->setCollection($collection);
        
        if (!$this->getRequest()->getPost('selected_products')) {
            $productIds = $this->_getSelectedProducts();
            if (!empty($productIds)) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $productIds));
            }
        }
        return parent::_prepareCollection();
    }
    
    protected function _prepareColumns()
    {
        $this->addColumn('in_group', array(
            'header_css_class' => 'a-center',
            'type'      => 'checkbox',
            'name'      => 'in_group',
            'values'    => $this->_getSelectedProducts(),
            'align'     => 'center',
            'index'     => 'entity_id'
        ));
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('featuredproduct')->__('ID'),
            'sortable'  => true,
            'width'     => '60',
            'index'     => 'entity_id'
        ));
        $this->addColumn('product_name', array(
            'header'    => Mage::helper('featuredproduct')->__('Name'),
            'index'     => 'name'
        ));
        $this->addColumn('product_sku', array(
            'header'    => Mage::helper('featuredproduct')->__('SKU'),
            'index'     => 'sku'
        ));
        $this->addColumn('product_type', array(
            'header'    => Mage::helper('featuredproduct')->__('Type'),
            'width'     => '150',
            'index'     => 'type_id',
            'type'  => 'options',
            'options' => Mage::getSingleton('catalog/product_type')->getOptionArray(),
        ));
        $this->addColumn('product_visibility', array(
            'header'    => Mage::helper('featuredproduct')->__('Visibility'),
            'width'     => '150',
            'index'     => 'visibility',
            'type'  => 'options',
            'options' => Mage::getModel('catalog/product_visibility')->getOptionArray(),
        ));
        $this->addColumn('product_status', array(
            'header'    => Mage::helper('featuredproduct')->__('Status'),
            'width'     => '120',
            'index'     => 'status',
            'type'  => 'options',
            'options' => Mage::getSingleton('catalog/product_status')->getOptionArray(),
        ));
        $this->addColumn('product_price', array(
            'header'    => Mage::helper('featuredproduct')->__('Price'),
            'type'  => 'currency',
            'width'     => '1',
            'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index'     => 'price'
        ));
        $this->addColumn('product_position', array(
            'header'    => Mage::helper('featuredproduct')->__('Position'),
            'width'     => '1',
            'type'      => 'number',
            'index'     => 'position',
            'editable'  => true
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/gridProduct', array('_current'=>true));
    }

    protected function _getSelectedProducts()
    {
        $_products = $this->getRequest()->getPost('selected_products');
        if (is_null($_products)) {
            if ($this->getFeaturedGroup() && $this->getFeaturedGroup()->getGroupId()) {
            $_collection = Mage::getModel('featuredproduct/item')->getProductsByGroup($this->getFeaturedGroup()->getGroupId());
            if (!empty($_collection)) {
                    $_products = array();
                    $_array = $_collection->toArray();
                    $_items = $_array['items'];
                    foreach ($_items as $_item) {
                        $_products[] = $_item['product_id'];
                    }
                    return $_products;
                }
            }
        }
        return $_products;
    }

}
