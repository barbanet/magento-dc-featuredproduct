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

class Dc_FeaturedProduct_Block_Adminhtml_Group_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'featuredproduct';
        $this->_controller = 'adminhtml_group';
        $this->_updateButton('save', 'label', Mage::helper('featuredproduct')->__('Save Group'));
        $this->_updateButton('delete', 'label', Mage::helper('featuredproduct')->__('Delete Group'));
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('featuredproduct')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);
        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if (Mage::registry('featuredproduct_data') && Mage::registry('featuredproduct_data')->getId()) {
            return Mage::helper('featuredproduct')->__('Edit Featured Product Group');
        } else {
            return Mage::helper('featuredproduct')->__('Add Featured Product Group');
        }
    }

}
