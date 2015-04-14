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

class Dc_FeaturedProduct_Block_Adminhtml_Template_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('template_form', array('legend' => Mage::helper('featuredproduct')->__('Template Information')));
        $fieldset->addField('name', 'text', array(
            'label'     => Mage::helper('featuredproduct')->__('Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'name',
        ));
        $fieldset->addField('file_path', 'text', array(
            'label'              => Mage::helper('featuredproduct')->__('File'),
            'class'              => 'required-entry',
            'required'           => true,
            'name'               => 'file_path',
            'after_element_html' => '<p>' . Mage::helper('featuredproduct')->__('File path starting from the template folder.<br />Example: folder/file.phtml') . '</p>',
        ));
        
        if (Mage::getSingleton('adminhtml/session')->getFeaturedproductTemplate()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getFeaturedproductTemplate());
            Mage::getSingleton('adminhtml/session')->setFeaturedproductTemplate(null);
        } elseif (Mage::registry('featuredproduct_template')) {
            $form->setValues(Mage::registry('featuredproduct_template')->getData());
        }
        return parent::_prepareForm();
    }

}
