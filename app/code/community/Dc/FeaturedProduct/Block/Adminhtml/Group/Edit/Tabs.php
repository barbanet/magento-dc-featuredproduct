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

class Dc_FeaturedProduct_Block_Adminhtml_Group_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('group_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('featuredproduct')->__('Group'));
    }

    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('featuredproduct')->__('Group'),
            'title'     => Mage::helper('featuredproduct')->__('Group'),
            'content'   => $this->getLayout()->createBlock('featuredproduct/adminhtml_group_edit_tab_form')->toHtml(),
        ));
        $this->addTab('product_section', array(
            'label'     => Mage::helper('featuredproduct')->__('Products'),
            'title'     => Mage::helper('featuredproduct')->__('Products'),
            'content'   => $this->getLayout()->createBlock('featuredproduct/adminhtml_group_edit_tab_product')->toHtml(),
        ));
                
        return parent::_beforeToHtml();
    }

}
